<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class KesiswaanController extends Controller
{
    /**
     * T07.1 — Student Violations & Guidance Points (Poin & Tata Tertib Pelanggaran Santri)
     */
    public function pelanggaran(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $violations = $demo->getStudentViolations();
        $rules = $demo->getViolationRules();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $activeTab = $request->query('tab', 'kasus'); // 'kasus', 'rekap', atau 'aturan'
        $severityFilter = $request->query('severity');
        $statusFilter = $request->query('status');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        // Filter riwayat kasus
        $filteredViolations = array_filter($violations, function ($v) use ($severityFilter, $statusFilter, $search, $studentMap) {
            if ($severityFilter && $v['severity'] !== $severityFilter) {
                return false;
            }

            if ($statusFilter && $v['status'] !== $statusFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$v['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchVio = str_contains(strtolower($v['violation_name']), $search);
                $matchReporter = str_contains(strtolower($v['reporter_name']), $search);
                if (!$matchStudent && !$matchNis && !$matchVio && !$matchReporter) {
                    return false;
                }
            }

            return true;
        });

        // Enrich kasus
        $enrichedViolations = [];
        $totalPoinSemuaKasus = 0;
        foreach ($filteredViolations as $v) {
            $std = $studentMap[$v['student_id']] ?? null;
            $cls = $std ? ($classMap[$std['class_id'] ?? ''] ?? null) : null;
            $totalPoinSemuaKasus += (int) $v['points'];

            $enrichedViolations[] = array_merge($v, [
                'student_name' => $std['name'] ?? 'Santri',
                'nis' => $std['nis'] ?? '-',
                'class_name' => $cls['name'] ?? ($std['class_id'] ?? '-')
            ]);
        }

        // Rekapitulasi poin per santri
        $studentPointsMap = [];
        foreach ($violations as $v) {
            $sId = $v['student_id'];
            if (!isset($studentPointsMap[$sId])) {
                $studentPointsMap[$sId] = [
                    'student_id' => $sId,
                    'total_points' => 0,
                    'case_count' => 0,
                    'cases' => []
                ];
            }
            $studentPointsMap[$sId]['total_points'] += (int) $v['points'];
            $studentPointsMap[$sId]['case_count']++;
            $studentPointsMap[$sId]['cases'][] = $v;
        }

        $rekapSantri = [];
        $totalSantriKenaPoin = count($studentPointsMap);
        $totalSantriPerluSP = 0;

        foreach ($studentPointsMap as $sId => $item) {
            $std = $studentMap[$sId] ?? null;
            $cls = $std ? ($classMap[$std['class_id'] ?? ''] ?? null) : null;
            $pts = $item['total_points'];

            if ($pts >= 40) {
                $spStatus = 'Surat Peringatan 2 (SP 2)';
                $spColor = 'danger';
                $totalSantriPerluSP++;
            } elseif ($pts >= 20) {
                $spStatus = 'Surat Peringatan 1 (SP 1)';
                $spColor = 'warning';
                $totalSantriPerluSP++;
            } else {
                $spStatus = 'Pembinaan Walas / Musyrif';
                $spColor = 'info';
            }

            $rekapSantri[] = [
                'student_id' => $sId,
                'name' => $std['name'] ?? 'Santri',
                'nis' => $std['nis'] ?? '-',
                'class_name' => $cls['name'] ?? '-',
                'total_points' => $pts,
                'case_count' => $item['case_count'],
                'sp_status' => $spStatus,
                'sp_color' => $spColor,
                'cases' => $item['cases']
            ];
        }

        // Urutkan rekap santri dari poin tertinggi
        usort($rekapSantri, function ($a, $b) {
            return $b['total_points'] <=> $a['total_points'];
        });

        $stats = [
            'total_cases' => count($violations),
            'total_points' => $totalPoinSemuaKasus,
            'total_students_involved' => $totalSantriKenaPoin,
            'total_need_sp' => $totalSantriPerluSP
        ];

        return view('kesiswaan.pelanggaran', [
            'violations' => $enrichedViolations,
            'rekapSantri' => $rekapSantri,
            'rules' => $rules,
            'students' => $students,
            'stats' => $stats,
            'activeTab' => $activeTab,
            'severityFilter' => $severityFilter,
            'statusFilter' => $statusFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Kasus Pelanggaran Santri Baru (Session-based)
     */
    public function storePelanggaran(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'rule_id' => 'required|string',
            'date' => 'required|date',
            'reporter_name' => 'required|string|max:120',
            'action_taken' => 'required|string|max:200',
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $rule = $demo->findViolationRule($validated['rule_id']);
        if (!$rule) {
            return redirect()->route('kesiswaan.pelanggaran')
                ->with('error', 'Tata tertib pasal pelanggaran tidak ditemukan.');
        }

        $newId = 'VIO-' . str_pad((string)(count($demo->getStudentViolations()) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'rule_id' => $rule['id'],
            'violation_name' => $rule['name'],
            'category' => $rule['category'],
            'severity' => $rule['severity'],
            'points' => (int) $rule['points'],
            'date' => $validated['date'] . ' ' . date('H:i:s'),
            'reporter_name' => $validated['reporter_name'],
            'action_taken' => $validated['action_taken'],
            'notes' => !empty($validated['notes']) ? $validated['notes'] : 'Tercatat dalam mutaba\'ah tata tertib santri.',
            'status' => $validated['status']
        ];

        $sessionAdded = session('demo_student_violations_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_student_violations_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('kesiswaan.pelanggaran', ['tab' => 'kasus'])
            ->with('success', "Pelanggaran [{$rule['name']}] santri " . ($std['name'] ?? '') . " (+{$rule['points']} poin) berhasil dicatat!");
    }

    /**
     * Hapus Kasus Pelanggaran (Session-based)
     */
    public function destroyPelanggaran(string $id)
    {
        $sessionDeleted = session('demo_student_violations_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_student_violations_deleted' => $sessionDeleted]);
        }

        return redirect()->route('kesiswaan.pelanggaran', ['tab' => 'kasus'])
            ->with('success', "Kasus catatan pelanggaran [{$id}] berhasil dihapus!");
    }

    /**
     * Tambah Aturan Tata Tertib Baru (Session-based)
     */
    public function storeRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:80',
            'severity' => 'required|in:Ringan,Sedang,Berat',
            'points' => 'required|integer|min:1|max:100',
            'consequence' => 'required|string|max:200'
        ]);

        $demo = DemoDataManager::getInstance();
        $newRuleId = 'RUL-' . str_pad((string)(count($demo->getViolationRules()) + 1), 3, '0', STR_PAD_LEFT);

        $newRule = [
            'id' => $newRuleId,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'severity' => $validated['severity'],
            'points' => (int) $validated['points'],
            'consequence' => $validated['consequence'],
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_violation_rules_added', []);
        $sessionAdded[] = $newRule;
        session(['demo_violation_rules_added' => $sessionAdded]);

        return redirect()->route('kesiswaan.pelanggaran', ['tab' => 'aturan'])
            ->with('success', "Aturan tata tertib [{$newRule['name']}] berhasil didaftarkan ke buku saku!");
    }

    /**
     * T07.2 — Student Achievements (Prestasi & Penghargaan Santri)
     */
    public function prestasi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $achievements = $demo->getStudentAchievements();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $categoryFilter = $request->query('category');
        $levelFilter = $request->query('level');
        $yearFilter = $request->query('year');
        $search = strtolower(trim($request->query('q', '')));

        // Map lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $filteredAchievements = array_filter($achievements, function ($a) use ($categoryFilter, $levelFilter, $yearFilter, $search, $studentMap) {
            if ($categoryFilter && $a['category'] !== $categoryFilter) {
                return false;
            }

            if ($levelFilter && $a['level'] !== $levelFilter) {
                return false;
            }

            if ($yearFilter && (string)$a['year'] !== (string)$yearFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$a['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchTitle = str_contains(strtolower($a['title']), $search);
                $matchComp = str_contains(strtolower($a['competition_name']), $search);
                $matchOrg = str_contains(strtolower($a['organizer']), $search);
                if (!$matchStudent && !$matchNis && !$matchTitle && !$matchComp && !$matchOrg) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalPrestasi = count($achievements);
        $totalNasional = count(array_filter($achievements, fn($a) => in_array($a['level'], ['Nasional', 'Internasional'])));
        $totalProvinsi = count(array_filter($achievements, fn($a) => $a['level'] === 'Provinsi'));
        $totalPoinApresiasi = array_sum(array_column($achievements, 'points'));

        // Rekapitulasi per santri peraih medali terbanyak
        $studentAchievementCounts = [];
        foreach ($achievements as $a) {
            $sid = $a['student_id'];
            if (!isset($studentAchievementCounts[$sid])) {
                $studentAchievementCounts[$sid] = [
                    'student' => $studentMap[$sid] ?? null,
                    'count' => 0,
                    'total_points' => 0,
                    'top_achievement' => $a['title']
                ];
            }
            $studentAchievementCounts[$sid]['count']++;
            $studentAchievementCounts[$sid]['total_points'] += ($a['points'] ?? 0);
        }

        uasort($studentAchievementCounts, function ($a, $b) {
            return $b['total_points'] <=> $a['total_points'];
        });

        // Daftar kategori unik & tahun untuk filter
        $categories = array_values(array_unique(array_column($achievements, 'category')));
        $levels = ['Sekolah', 'Kota/Kab', 'Provinsi', 'Nasional', 'Internasional'];
        $years = array_values(array_unique(array_column($achievements, 'year')));
        rsort($years);

        return view('kesiswaan.prestasi', [
            'achievements' => $filteredAchievements,
            'allAchievements' => $achievements,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'studentAchievementCounts' => $studentAchievementCounts,
            'categories' => $categories,
            'levels' => $levels,
            'years' => $years,
            'totalPrestasi' => $totalPrestasi,
            'totalNasional' => $totalNasional,
            'totalProvinsi' => $totalProvinsi,
            'totalPoinApresiasi' => $totalPoinApresiasi,
            'categoryFilter' => $categoryFilter,
            'levelFilter' => $levelFilter,
            'yearFilter' => $yearFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Prestasi Santri Baru (Session-based)
     */
    public function storePrestasi(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'title' => 'required|string|max:200',
            'category' => 'required|string|max:80',
            'competition_name' => 'required|string|max:200',
            'level' => 'required|in:Sekolah,Kota/Kab,Provinsi,Nasional,Internasional',
            'rank' => 'required|string|max:80',
            'date' => 'required|date',
            'organizer' => 'required|string|max:150',
            'coach_name' => 'required|string|max:120',
            'reward' => 'nullable|string|max:200',
            'points' => 'required|integer|min:5|max:200',
            'certificate_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $achievements = $demo->getStudentAchievements();
        $newId = 'ACH-' . str_pad((string)(count($achievements) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'title' => $validated['title'],
            'category' => $validated['category'],
            'competition_name' => $validated['competition_name'],
            'level' => $validated['level'],
            'rank' => $validated['rank'],
            'year' => (int) date('Y', strtotime($validated['date'])),
            'date' => $validated['date'],
            'organizer' => $validated['organizer'],
            'coach_name' => $validated['coach_name'],
            'reward' => $validated['reward'] ?? '-',
            'points' => (int) $validated['points'],
            'certificate_number' => $validated['certificate_number'] ?? ('SK-ACH/' . date('Y') . '/' . rand(100, 999)),
            'status' => 'terverifikasi',
            'notes' => $validated['notes'] ?? 'Prestasi resmi tercatat dalam Buku Induk Prestasi Santri Alia.'
        ];

        $sessionAdded = session('demo_student_achievements_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_student_achievements_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('kesiswaan.prestasi')
            ->with('success', "Prestasi [{$validated['title']}] santri " . ($std['name'] ?? '') . " (+{$validated['points']} Poin Apresiasi) berhasil dicatat!");
    }

    /**
     * Hapus Rekam Prestasi (Session-based)
     */
    public function destroyPrestasi(string $id)
    {
        $sessionDeleted = session('demo_student_achievements_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_student_achievements_deleted' => $sessionDeleted]);
        }

        return redirect()->route('kesiswaan.prestasi')
            ->with('success', "Rekam prestasi [{$id}] berhasil dihapus!");
    }

    /**
     * T07.3 — Counseling / BK (Bimbingan Konseling Santri)
     */
    public function konseling(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $sessions = $demo->getCounselingSessions();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $categoryFilter = $request->query('category');
        $statusFilter = $request->query('status');
        $serviceTypeFilter = $request->query('service_type');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $filteredSessions = array_filter($sessions, function ($cs) use ($categoryFilter, $statusFilter, $serviceTypeFilter, $search, $studentMap) {
            if ($categoryFilter && $cs['category'] !== $categoryFilter) {
                return false;
            }

            if ($statusFilter && $cs['status'] !== $statusFilter) {
                return false;
            }

            if ($serviceTypeFilter && $cs['service_type'] !== $serviceTypeFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$cs['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchCounselor = str_contains(strtolower($cs['counselor_name']), $search);
                $matchSummary = str_contains(strtolower($cs['problem_summary']), $search);
                $matchPlan = str_contains(strtolower($cs['action_plan']), $search);
                if (!$matchStudent && !$matchNis && !$matchCounselor && !$matchSummary && !$matchPlan) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalSesi = count($sessions);
        $totalDalamProses = count(array_filter($sessions, fn($s) => $s['status'] === 'dalam_proses'));
        $totalSelesai = count(array_filter($sessions, fn($s) => $s['status'] === 'selesai'));
        $totalRujukan = count(array_filter($sessions, fn($s) => str_contains($s['referral_from'] ?? '', 'Asrama') || str_contains($s['referral_from'] ?? '', 'Pelanggaran')));

        // Kategori & tipe layanan untuk filter
        $categories = ['Belajar & Akademik', 'Pribadi & Adiksi Gadget', 'Sosial & Adaptasi Asrama', 'Karier & Studi Lanjut', 'Disiplin & Motivasi'];
        $serviceTypes = ['Konseling Individu', 'Konseling Kelompok', 'Bimbingan Karier & Minat', 'Mediasi Teman Sebaya', 'Konferensi Kasus', 'Kunjungan Rumah (Home Visit)'];

        return view('kesiswaan.konseling', [
            'sessions' => $filteredSessions,
            'allSessions' => $sessions,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'totalSesi' => $totalSesi,
            'totalDalamProses' => $totalDalamProses,
            'totalSelesai' => $totalSelesai,
            'totalRujukan' => $totalRujukan,
            'categoryFilter' => $categoryFilter,
            'statusFilter' => $statusFilter,
            'serviceTypeFilter' => $serviceTypeFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Sesi Konseling Baru (Session-based)
     */
    public function storeKonseling(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'counselor_name' => 'required|string|max:120',
            'date' => 'required|date',
            'time' => 'required|string|max:50',
            'category' => 'required|string|max:80',
            'service_type' => 'required|string|max:80',
            'referral_from' => 'required|string|max:150',
            'problem_summary' => 'required|string|max:500',
            'action_plan' => 'required|string|max:500',
            'status' => 'required|in:dijadwalkan,dalam_proses,selesai,dirujuk',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $sessions = $demo->getCounselingSessions();
        $newId = 'BK-' . str_pad((string)(count($sessions) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'counselor_name' => $validated['counselor_name'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'category' => $validated['category'],
            'service_type' => $validated['service_type'],
            'referral_from' => $validated['referral_from'],
            'problem_summary' => $validated['problem_summary'],
            'action_plan' => $validated['action_plan'],
            'status' => $validated['status'],
            'follow_up_date' => $validated['follow_up_date'] ?? date('Y-m-d', strtotime('+7 days')),
            'notes' => $validated['notes'] ?? 'Tercatat dalam Buku Log Bimbingan Konseling Santri Alia.'
        ];

        $sessionAdded = session('demo_counseling_sessions_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_counseling_sessions_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('kesiswaan.konseling')
            ->with('success', "Sesi layanan konseling santri " . ($std['name'] ?? '') . " [{$newId}] berhasil dicatat!");
    }

    /**
     * Update Status / RTL Sesi Konseling (Session-based)
     */
    public function updateKonselingStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:dijadwalkan,dalam_proses,selesai,dirujuk',
            'action_plan' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:300'
        ]);

        $sessionUpdated = session('demo_counseling_sessions_updated', []);
        $sessionUpdated[$id] = array_filter([
            'status' => $validated['status'],
            'action_plan' => $validated['action_plan'] ?? null,
            'notes' => $validated['notes'] ?? null
        ]);
        session(['demo_counseling_sessions_updated' => $sessionUpdated]);

        return redirect()->route('kesiswaan.konseling')
            ->with('success', "Status dan rencana tindak lanjut sesi konseling [{$id}] berhasil diperbarui!");
    }

    /**
     * Hapus Rekam Sesi Konseling (Session-based)
     */
    public function destroyKonseling(string $id)
    {
        $sessionDeleted = session('demo_counseling_sessions_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_counseling_sessions_deleted' => $sessionDeleted]);
        }

        return redirect()->route('kesiswaan.konseling')
            ->with('success', "Rekam berkas konseling [{$id}] berhasil dihapus!");
    }

    /**
     * T07.4 — Extracurricular (Ekstrakurikuler & Klub Bakat Santri)
     */
    public function ekskul(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $ekskuls = $demo->getExtracurriculars();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $categoryFilter = $request->query('category');
        $dayFilter = $request->query('day');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $filteredEkskuls = array_filter($ekskuls, function ($e) use ($categoryFilter, $dayFilter, $search) {
            if ($categoryFilter && $e['category'] !== $categoryFilter) {
                return false;
            }

            if ($dayFilter && !str_contains($e['schedule_day'], $dayFilter)) {
                return false;
            }

            if ($search) {
                $matchName = str_contains(strtolower($e['name']), $search);
                $matchCoach = str_contains(strtolower($e['coach_name']), $search);
                $matchLoc = str_contains(strtolower($e['location']), $search);
                $matchDesc = str_contains(strtolower($e['description']), $search);
                if (!$matchName && !$matchCoach && !$matchLoc && !$matchDesc) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalKlub = count($ekskuls);
        $totalAnggota = 0;
        foreach ($ekskuls as $e) {
            $totalAnggota += count($e['student_ids'] ?? []);
        }
        $totalPelatih = count(array_unique(array_column($ekskuls, 'coach_name')));
        $totalPrestasiEkskul = array_sum(array_column($ekskuls, 'achievements_count'));

        $categories = array_values(array_unique(array_column($ekskuls, 'category')));
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];

        return view('kesiswaan.ekskul', [
            'ekskuls' => $filteredEkskuls,
            'allEkskuls' => $ekskuls,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'categories' => $categories,
            'days' => $days,
            'totalKlub' => $totalKlub,
            'totalAnggota' => $totalAnggota,
            'totalPelatih' => $totalPelatih,
            'totalPrestasiEkskul' => $totalPrestasiEkskul,
            'categoryFilter' => $categoryFilter,
            'dayFilter' => $dayFilter,
            'search' => $search
        ]);
    }

    /**
     * Tambah Ekstrakurikuler Baru (Session-based)
     */
    public function storeEkskul(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'coach_name' => 'required|string|max:120',
            'target_gender' => 'required|in:Ikhwan,Akhwat,Semua',
            'schedule_day' => 'required|string|max:50',
            'schedule_time' => 'required|string|max:50',
            'location' => 'required|string|max:150',
            'quota' => 'required|integer|min:5|max:100',
            'description' => 'required|string|max:400'
        ]);

        $demo = DemoDataManager::getInstance();
        $ekskuls = $demo->getExtracurriculars();
        $newId = 'EKS-' . str_pad((string)(count($ekskuls) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'coach_name' => $validated['coach_name'],
            'target_gender' => $validated['target_gender'],
            'schedule_day' => $validated['schedule_day'],
            'schedule_time' => $validated['schedule_time'],
            'location' => $validated['location'],
            'quota' => (int) $validated['quota'],
            'student_ids' => [],
            'status' => 'aktif',
            'description' => $validated['description'],
            'achievements_count' => 0
        ];

        $sessionAdded = session('demo_extracurriculars_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_extracurriculars_added' => $sessionAdded]);

        return redirect()->route('kesiswaan.ekskul')
            ->with('success', "Klub ekstrakurikuler [{$validated['name']}] berhasil didaftarkan!");
    }

    /**
     * Daftarkan Santri ke Ekstrakurikuler (Session-based)
     */
    public function enrollStudent(Request $request, string $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $ekskul = $demo->findExtracurricular($id);

        if (!$ekskul) {
            return redirect()->route('kesiswaan.ekskul')->with('error', 'Klub ekstrakurikuler tidak ditemukan!');
        }

        $sessionUpdated = session('demo_extracurriculars_updated', []);
        $currentEkskul = $sessionUpdated[$id] ?? $ekskul;
        $studentIds = $currentEkskul['student_ids'] ?? [];

        if (in_array($validated['student_id'], $studentIds)) {
            return redirect()->route('kesiswaan.ekskul')->with('error', 'Santri sudah terdaftar di klub ekstrakurikuler ini!');
        }

        if (count($studentIds) >= ($currentEkskul['quota'] ?? 30)) {
            return redirect()->route('kesiswaan.ekskul')->with('error', 'Kuota keanggotaan klub telah penuh!');
        }

        $studentIds[] = $validated['student_id'];
        $sessionUpdated[$id] = array_merge($currentEkskul, ['student_ids' => $studentIds]);
        session(['demo_extracurriculars_updated' => $sessionUpdated]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('kesiswaan.ekskul')
            ->with('success', "Santri " . ($std['name'] ?? '') . " berhasil didaftarkan ke klub [{$ekskul['name']}]!");
    }

    /**
     * Hapus Ekstrakurikuler (Session-based)
     */
    public function destroyEkskul(string $id)
    {
        $sessionDeleted = session('demo_extracurriculars_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_extracurriculars_deleted' => $sessionDeleted]);
        }

        return redirect()->route('kesiswaan.ekskul')
            ->with('success', "Klub ekstrakurikuler [{$id}] berhasil dihapus!");
    }

    /**
     * T07.5 — UKS (Unit Kesehatan Sekolah)
     */
    public function uks(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $visits = $demo->getUksVisits();
        $medicines = $demo->getUksMedicines();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $activeTab = $request->query('tab', 'kunjungan'); // 'kunjungan' atau 'obat'
        $statusFilter = $request->query('status');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $filteredVisits = array_filter($visits, function ($v) use ($statusFilter, $search, $studentMap) {
            if ($statusFilter && $v['status'] !== $statusFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$v['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchDiag = str_contains(strtolower($v['diagnosis']), $search);
                $matchSymp = str_contains(strtolower($v['symptoms']), $search);
                if (!$matchStudent && !$matchNis && !$matchDiag && !$matchSymp) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalKunjungan = count($visits);
        $totalDirujuk = count(array_filter($visits, fn($v) => $v['status'] === 'dirujuk_faskes'));
        $totalSelesai = count(array_filter($visits, fn($v) => $v['status'] === 'selesai'));
        $totalObatKritis = count(array_filter($medicines, fn($m) => $m['stock'] <= 15));

        return view('kesiswaan.uks', [
            'visits' => $filteredVisits,
            'allVisits' => $visits,
            'medicines' => $medicines,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'activeTab' => $activeTab,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'totalKunjungan' => $totalKunjungan,
            'totalDirujuk' => $totalDirujuk,
            'totalSelesai' => $totalSelesai,
            'totalObatKritis' => $totalObatKritis
        ]);
    }

    /**
     * Catat Pemeriksaan / Kunjungan UKS Baru (Session-based)
     */
    public function storeUksVisit(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'paramedic_name' => 'required|string|max:120',
            'visit_date' => 'required|date',
            'symptoms' => 'required|string|max:500',
            'diagnosis' => 'required|string|max:150',
            'temperature' => 'required|string|max:20',
            'blood_pressure' => 'required|string|max:20',
            'treatment_given' => 'required|string|max:500',
            'medicines_prescribed' => 'required|string|max:250',
            'action_taken' => 'required|string|max:250',
            'status' => 'required|in:diobservasi,selesai,dirujuk_faskes',
            'referral_hospital' => 'nullable|string|max:150',
            'parent_notified' => 'nullable|boolean',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $visits = $demo->getUksVisits();
        $newId = 'UKS-' . str_pad((string)(count($visits) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'paramedic_name' => $validated['paramedic_name'],
            'visit_date' => $validated['visit_date'] . ' ' . date('H:i:s'),
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['diagnosis'],
            'temperature' => $validated['temperature'],
            'blood_pressure' => $validated['blood_pressure'],
            'treatment_given' => $validated['treatment_given'],
            'medicines_prescribed' => $validated['medicines_prescribed'],
            'action_taken' => $validated['action_taken'],
            'status' => $validated['status'],
            'referral_hospital' => $validated['referral_hospital'] ?? null,
            'parent_notified' => !empty($request->input('parent_notified')),
            'notes' => $validated['notes'] ?? 'Pemeriksaan standar operasional medis UKS Alia.'
        ];

        $sessionAdded = session('demo_uks_visits_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_uks_visits_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('kesiswaan.uks', ['tab' => 'kunjungan'])
            ->with('success', "Kunjungan & rekam medis santri " . ($std['name'] ?? '') . " [{$newId}] berhasil dicatat!");
    }

    /**
     * Tambah Stok Obat Baru (Session-based)
     */
    public function storeUksMedicine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:1|max:1000',
            'unit' => 'required|string|max:50',
            'expiry_date' => 'required|date',
            'indication' => 'required|string|max:250'
        ]);

        $demo = DemoDataManager::getInstance();
        $medicines = $demo->getUksMedicines();
        $newId = 'MED-' . str_pad((string)(count($medicines) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'stock' => (int) $validated['stock'],
            'unit' => $validated['unit'],
            'expiry_date' => $validated['expiry_date'],
            'indication' => $validated['indication'],
            'status' => 'tersedia'
        ];

        $sessionAdded = session('demo_uks_medicines_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_uks_medicines_added' => $sessionAdded]);

        return redirect()->route('kesiswaan.uks', ['tab' => 'obat'])
            ->with('success', "Obat [{$validated['name']}] berhasil didaftarkan ke inventaris UKS!");
    }

    /**
     * Hapus Kunjungan UKS (Session-based)
     */
    public function destroyUksVisit(string $id)
    {
        $sessionDeleted = session('demo_uks_visits_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_uks_visits_deleted' => $sessionDeleted]);
        }

        return redirect()->route('kesiswaan.uks', ['tab' => 'kunjungan'])
            ->with('success', "Arsip kunjungan medis [{$id}] berhasil dihapus!");
    }

    /**
     * T07.6 — Student Mutation (Mutasi Santri Masuk/Pindahan & Keluar)
     */
    public function mutasi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $mutations = $demo->getStudentMutations();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $typeFilter = $request->query('type');
        $statusFilter = $request->query('status');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $filteredMutations = array_filter($mutations, function ($m) use ($typeFilter, $statusFilter, $search, $studentMap) {
            if ($typeFilter && $m['type'] !== $typeFilter) {
                return false;
            }

            if ($statusFilter && $m['approval_status'] !== $statusFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$m['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchSk = str_contains(strtolower($m['mutation_number']), $search);
                $matchOrigin = str_contains(strtolower($m['origin_school'] ?? ''), $search);
                $matchDest = str_contains(strtolower($m['destination_school'] ?? ''), $search);
                $matchReason = str_contains(strtolower($m['reason']), $search);
                if (!$matchStudent && !$matchNis && !$matchSk && !$matchOrigin && !$matchDest && !$matchReason) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalMutasi = count($mutations);
        $totalMasuk = count(array_filter($mutations, fn($m) => $m['type'] === 'masuk'));
        $totalKeluar = count(array_filter($mutations, fn($m) => in_array($m['type'], ['keluar', 'dikeluarkan'])));
        $totalDisetujui = count(array_filter($mutations, fn($m) => $m['approval_status'] === 'disetujui'));

        return view('kesiswaan.mutasi', [
            'mutations' => $filteredMutations,
            'allMutations' => $mutations,
            'students' => $students,
            'studentMap' => $studentMap,
            'classes' => $classes,
            'classMap' => $classMap,
            'typeFilter' => $typeFilter,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'totalMutasi' => $totalMutasi,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalDisetujui' => $totalDisetujui
        ]);
    }

    /**
     * Ajukan / Catat Mutasi Santri Baru (Session-based)
     */
    public function storeMutasi(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'type' => 'required|in:masuk,keluar,lulus,dikeluarkan',
            'date' => 'required|date',
            'class_id' => 'required|string',
            'origin_school' => 'nullable|string|max:150',
            'destination_school' => 'nullable|string|max:150',
            'reason' => 'required|string|max:500',
            'approver_name' => 'required|string|max:120',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $mutations = $demo->getStudentMutations();
        $newId = 'MUT-' . str_pad((string)(count($mutations) + 1), 3, '0', STR_PAD_LEFT);
        $skNumber = 'SK-MUT/' . strtoupper(substr($validated['class_id'], 4, 3)) . '/' . date('Y') . '/' . str_pad((string)(count($mutations) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'mutation_number' => $skNumber,
            'student_id' => $validated['student_id'],
            'type' => $validated['type'],
            'date' => $validated['date'],
            'class_id' => $validated['class_id'],
            'origin_school' => $validated['origin_school'] ?? null,
            'destination_school' => $validated['destination_school'] ?? null,
            'reason' => $validated['reason'],
            'approval_status' => 'disetujui',
            'approver_name' => $validated['approver_name'],
            'notes' => $validated['notes'] ?? 'Surat keterangan resmi mutasi disahkan oleh pimpinan pondok.'
        ];

        $sessionAdded = session('demo_student_mutations_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_student_mutations_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('kesiswaan.mutasi')
            ->with('success', "Mutasi santri " . ($std['name'] ?? '') . " [{$skNumber}] berhasil diproses!");
    }

    /**
     * Hapus Rekam Mutasi Santri (Session-based)
     */
    public function destroyMutasi(string $id)
    {
        $sessionDeleted = session('demo_student_mutations_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_student_mutations_deleted' => $sessionDeleted]);
        }

        return redirect()->route('kesiswaan.mutasi')
            ->with('success', "Rekam berkas mutasi santri [{$id}] berhasil dihapus!");
    }
}
