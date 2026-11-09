<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class TahfidzController extends Controller
{
    /**
     * T06.1 — Tahfidz Programs and Targets (Target & Halaqah Al-Qur'an)
     */
    public function target(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $programs = $demo->getTahfidzPrograms();
        $halaqahs = $demo->getHalaqahs();
        $teachers = $demo->getTeachers();
        $students = $demo->getStudents();
        $units = $demo->getUnits();

        $unitFilter = $request->query('unit');
        $tab = $request->query('tab', 'halaqah'); // 'halaqah' atau 'program'
        $search = strtolower(trim($request->query('q', '')));

        // Buat lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $programMap = [];
        foreach ($programs as $p) {
            $programMap[$p['id']] = $p;
        }

        $unitMap = [];
        foreach ($units as $u) {
            $unitMap[$u['id']] = $u;
        }

        // Filter Halaqah
        $filteredHalaqahs = array_filter($halaqahs, function ($hlq) use ($unitFilter, $search, $programMap) {
            if ($unitFilter && $hlq['unit_id'] !== $unitFilter) {
                return false;
            }

            if ($search) {
                $prg = $programMap[$hlq['program_id']] ?? null;
                $matchName = str_contains(strtolower($hlq['name']), $search);
                $matchMusyrif = str_contains(strtolower($hlq['supervisor_name']), $search);
                $matchProgram = $prg && str_contains(strtolower($prg['name']), $search);
                if (!$matchName && !$matchMusyrif && !$matchProgram) {
                    return false;
                }
            }

            return true;
        });

        // Enrich data halaqah dengan detail santri
        $enrichedHalaqahs = [];
        $totalSantriTahfidz = 0;
        foreach ($filteredHalaqahs as $h) {
            $memberStudents = [];
            foreach ($h['student_ids'] as $sId) {
                if (isset($studentMap[$sId])) {
                    $memberStudents[] = $studentMap[$sId];
                }
            }
            $totalSantriTahfidz += count($memberStudents);

            $enrichedHalaqahs[] = array_merge($h, [
                'unit_name' => $unitMap[$h['unit_id']]['name'] ?? $h['unit_id'],
                'program_name' => $programMap[$h['program_id']]['name'] ?? $h['program_id'],
                'students' => $memberStudents,
                'student_count' => count($memberStudents)
            ]);
        }

        // Filter Program Tahfidz
        $filteredPrograms = array_filter($programs, function ($prg) use ($unitFilter, $search) {
            if ($unitFilter && $prg['unit_id'] !== $unitFilter) {
                return false;
            }

            if ($search) {
                $matchName = str_contains(strtolower($prg['name']), $search);
                $matchDesc = str_contains(strtolower($prg['target_description']), $search);
                if (!$matchName && !$matchDesc) {
                    return false;
                }
            }

            return true;
        });

        $stats = [
            'total_halaqah' => count($halaqahs),
            'total_program' => count($programs),
            'total_santri' => $totalSantriTahfidz,
            'total_musyrif' => count(array_unique(array_column($halaqahs, 'supervisor_name')))
        ];

        return view('tahfidz.target', [
            'halaqahs' => $enrichedHalaqahs,
            'programs' => $filteredPrograms,
            'allPrograms' => $programs,
            'teachers' => $teachers,
            'students' => $students,
            'units' => $units,
            'stats' => $stats,
            'activeUnit' => $unitFilter,
            'activeTab' => $tab,
            'search' => $search
        ]);
    }

    /**
     * Simpan Kelompok Halaqah Baru (Session-based)
     */
    public function storeHalaqah(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'program_id' => 'required|string',
            'teacher_id' => 'required|string',
            'unit_id' => 'required|string',
            'gender_target' => 'required|in:L,P',
            'location' => 'required|string|max:150',
            'session_schedule' => 'required|string|max:150',
            'target_juz' => 'required|integer|min:1|max:30',
            'student_ids' => 'nullable|array',
            'description' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $supervisorName = 'Musyrif Halaqah';
        foreach ($teachers as $t) {
            if ($t['id'] === $validated['teacher_id']) {
                $supervisorName = $t['name'];
                break;
            }
        }

        $newHalaqahId = 'HLQ-' . str_pad((string)(count($demo->getHalaqahs()) + 1), 3, '0', STR_PAD_LEFT);

        $newHalaqah = [
            'id' => $newHalaqahId,
            'name' => $validated['name'],
            'program_id' => $validated['program_id'],
            'teacher_id' => $validated['teacher_id'],
            'supervisor_name' => $supervisorName,
            'unit_id' => $validated['unit_id'],
            'gender_target' => $validated['gender_target'],
            'location' => $validated['location'],
            'session_schedule' => $validated['session_schedule'],
            'target_juz' => (int) $validated['target_juz'],
            'student_ids' => !empty($validated['student_ids']) ? $validated['student_ids'] : [],
            'status' => 'aktif',
            'description' => !empty($validated['description']) ? $validated['description'] : 'Kelompok bimbingan tahfidz Al-Qur\'an Alia Islamic School.'
        ];

        $sessionAdded = session('demo_halaqahs_added', []);
        $sessionAdded[] = $newHalaqah;
        session(['demo_halaqahs_added' => $sessionAdded]);

        return redirect()->route('tahfidz.target', ['tab' => 'halaqah'])
            ->with('success', "Kelompok [{$newHalaqah['name']}] berhasil dibentuk dengan Musyrif {$supervisorName}!");
    }

    /**
     * Simpan Program Tahfidz Baru (Session-based)
     */
    public function storeProgram(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'unit_id' => 'required|string',
            'level' => 'required|string|max:20',
            'target_juz' => 'required|integer|min:1|max:30',
            'period' => 'required|string|max:50',
            'daily_target' => 'required|string|max:100',
            'session_time' => 'required|string|max:100',
            'target_description' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $newPrgId = 'PRG-TFZ-' . str_pad((string)(count($demo->getTahfidzPrograms()) + 1), 2, '0', STR_PAD_LEFT);

        $newProgram = [
            'id' => $newPrgId,
            'name' => $validated['name'],
            'unit_id' => $validated['unit_id'],
            'level' => $validated['level'],
            'target_juz' => (int) $validated['target_juz'],
            'target_description' => !empty($validated['target_description']) ? $validated['target_description'] : 'Kurikulum target hafalan mutqin.',
            'period' => $validated['period'],
            'daily_target' => $validated['daily_target'],
            'session_time' => $validated['session_time'],
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_tahfidz_programs_added', []);
        $sessionAdded[] = $newProgram;
        session(['demo_tahfidz_programs_added' => $sessionAdded]);

        return redirect()->route('tahfidz.target', ['tab' => 'program'])
            ->with('success', "Program Tahfidz [{$newProgram['name']}] berhasil diterbitkan!");
    }

    /**
     * Hapus Kelompok Halaqah (Session-based)
     */
    public function destroyHalaqah(string $id)
    {
        $sessionDeleted = session('demo_halaqahs_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_halaqahs_deleted' => $sessionDeleted]);
        }

        return redirect()->route('tahfidz.target', ['tab' => 'halaqah'])
            ->with('success', "Kelompok Halaqah [{$id}] berhasil dihapus!");
    }

    /**
     * Alokasi / Tambah Santri ke Halaqah (Session-based)
     */
    public function assignStudent(Request $request, string $halaqahId)
    {
        $validated = $request->validate([
            'student_id' => 'required|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $hlq = $demo->findHalaqah($halaqahId);
        if (!$hlq) {
            return redirect()->route('tahfidz.target')
                ->with('error', 'Halaqah tidak ditemukan.');
        }

        $studentIds = $hlq['student_ids'] ?? [];
        if (!in_array($validated['student_id'], $studentIds)) {
            $studentIds[] = $validated['student_id'];
        }

        $sessionUpdated = session('demo_halaqahs_updated', []);
        $sessionUpdated[$halaqahId] = ['student_ids' => $studentIds];
        session(['demo_halaqahs_updated' => $sessionUpdated]);

        $student = $demo->findStudent($validated['student_id']);

        return redirect()->route('tahfidz.target', ['tab' => 'halaqah'])
            ->with('success', "Santri " . ($student['name'] ?? '') . " berhasil dialokasikan ke kelompok {$hlq['name']}!");
    }

    /**
     * T06.2 — Student Memorization Records (Setoran Hafalan Ziyadah Al-Qur'an)
     */
    public function setoran(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $records = $demo->getMemorizationRecords();
        $students = $demo->getStudents();
        $halaqahs = $demo->getHalaqahs();
        $teachers = $demo->getTeachers();
        $units = $demo->getUnits();

        $selectedHalaqahId = $request->query('halaqah_id');
        $selectedStudentId = $request->query('student_id');
        $gradeFilter = $request->query('grade');
        $search = strtolower(trim($request->query('q', '')));

        // Buat lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $halaqahMap = [];
        foreach ($halaqahs as $h) {
            $halaqahMap[$h['id']] = $h;
        }

        // Filter records
        $filteredRecords = array_filter($records, function ($rec) use ($selectedHalaqahId, $selectedStudentId, $gradeFilter, $search, $studentMap) {
            if ($selectedHalaqahId && ($rec['halaqah_id'] ?? '') !== $selectedHalaqahId) {
                return false;
            }

            if ($selectedStudentId && ($rec['student_id'] ?? '') !== $selectedStudentId) {
                return false;
            }

            if ($gradeFilter && ($rec['grade'] ?? '') !== $gradeFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$rec['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchSurah = str_contains(strtolower($rec['surah_name']), $search);
                $matchMusyrif = str_contains(strtolower($rec['supervisor_name']), $search);
                if (!$matchStudent && !$matchSurah && !$matchMusyrif) {
                    return false;
                }
            }

            return true;
        });

        // Enrich records
        $enrichedRecords = [];
        $totalAyatAll = 0;
        $totalScoreAll = 0;
        $countMumtaz = 0;

        foreach ($filteredRecords as $r) {
            $std = $studentMap[$r['student_id']] ?? null;
            $hlq = $halaqahMap[$r['halaqah_id']] ?? null;

            $totalAyatAll += (int) $r['total_ayat'];
            $totalScoreAll += (int) $r['score'];
            if ($r['grade'] === 'Mumtaz') $countMumtaz++;

            $enrichedRecords[] = array_merge($r, [
                'student_name' => $std['name'] ?? 'Santri',
                'nis' => $std['nis'] ?? '-',
                'class_id' => $std['class_id'] ?? '-',
                'halaqah_name' => $hlq['name'] ?? ($r['halaqah_id'] ?? 'Halaqah'),
            ]);
        }

        $recCount = count($enrichedRecords);
        $avgScore = $recCount > 0 ? round($totalScoreAll / $recCount, 1) : 0;

        $stats = [
            'total_setoran' => $recCount,
            'total_ayat' => $totalAyatAll,
            'avg_score' => $avgScore,
            'total_mumtaz' => $countMumtaz
        ];

        return view('tahfidz.setoran', [
            'records' => $enrichedRecords,
            'students' => $students,
            'halaqahs' => $halaqahs,
            'teachers' => $teachers,
            'stats' => $stats,
            'selectedHalaqahId' => $selectedHalaqahId,
            'selectedStudentId' => $selectedStudentId,
            'gradeFilter' => $gradeFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Catatan Setoran Baru (Session-based)
     */
    public function storeSetoran(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'halaqah_id' => 'required|string',
            'teacher_id' => 'required|string',
            'date' => 'required|date',
            'session' => 'required|string',
            'surah_name' => 'required|string|max:100',
            'juz' => 'required|integer|min:1|max:30',
            'ayat_start' => 'required|integer|min:1',
            'ayat_end' => 'required|integer|min:1',
            'score' => 'required|integer|min:0|max:100',
            'tajwid_quality' => 'required|string',
            'makhraj_quality' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $supervisorName = 'Musyrif Pengampu';
        foreach ($teachers as $t) {
            if ($t['id'] === $validated['teacher_id']) {
                $supervisorName = $t['name'];
                break;
            }
        }

        // Tentukan predikat grade otomatis dari skor
        $score = (int) $validated['score'];
        if ($score >= 90) {
            $grade = 'Mumtaz';
            $status = 'lulus';
        } elseif ($score >= 80) {
            $grade = 'Jayyid Jiddan';
            $status = 'lulus';
        } elseif ($score >= 70) {
            $grade = 'Jayyid';
            $status = 'lulus';
        } else {
            $grade = 'Maqbul';
            $status = 'perlu_pengulangan';
        }

        $totalAyat = max(1, ($validated['ayat_end'] - $validated['ayat_start'] + 1));
        $newId = 'ZYD-' . str_pad((string)(count($demo->getMemorizationRecords()) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'halaqah_id' => $validated['halaqah_id'],
            'teacher_id' => $validated['teacher_id'],
            'supervisor_name' => $supervisorName,
            'date' => $validated['date'],
            'session' => $validated['session'],
            'surah_name' => $validated['surah_name'],
            'surah_number' => 0,
            'juz' => (int) $validated['juz'],
            'ayat_start' => (int) $validated['ayat_start'],
            'ayat_end' => (int) $validated['ayat_end'],
            'total_ayat' => $totalAyat,
            'grade' => $grade,
            'score' => $score,
            'tajwid_quality' => $validated['tajwid_quality'],
            'makhraj_quality' => $validated['makhraj_quality'],
            'notes' => !empty($validated['notes']) ? $validated['notes'] : 'Setoran ziyadah hafalan Al-Qur\'an tercatat.',
            'status' => $status
        ];

        $sessionAdded = session('demo_ziyadah_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_ziyadah_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('tahfidz.setoran')
            ->with('success', "Setoran hafalan santri " . ($std['name'] ?? '') . " [QS. {$validated['surah_name']}: {$validated['ayat_start']}-{$validated['ayat_end']}] predikat {$grade} ({$score}) berhasil dicatat!");
    }

    /**
     * Hapus Catatan Setoran (Session-based)
     */
    public function destroySetoran(string $id)
    {
        $sessionDeleted = session('demo_ziyadah_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_ziyadah_deleted' => $sessionDeleted]);
        }

        return redirect()->route('tahfidz.setoran')
            ->with('success', "Catatan setoran [{$id}] berhasil dihapus!");
    }

    /**
     * T06.3 — Murojaah Records (Pemantauan Pengulangan Hafalan & Ujian Kelancaran Mutqin)
     */
    public function murojaah(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $records = $demo->getMurojaahRecords();
        $students = $demo->getStudents();
        $halaqahs = $demo->getHalaqahs();
        $teachers = $demo->getTeachers();

        $selectedHalaqahId = $request->query('halaqah_id');
        $selectedStudentId = $request->query('student_id');
        $typeFilter = $request->query('type');
        $fluencyFilter = $request->query('fluency');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $halaqahMap = [];
        foreach ($halaqahs as $h) {
            $halaqahMap[$h['id']] = $h;
        }

        // Filter
        $filtered = array_filter($records, function ($rec) use ($selectedHalaqahId, $selectedStudentId, $typeFilter, $fluencyFilter, $search, $studentMap) {
            if ($selectedHalaqahId && ($rec['halaqah_id'] ?? '') !== $selectedHalaqahId) {
                return false;
            }

            if ($selectedStudentId && ($rec['student_id'] ?? '') !== $selectedStudentId) {
                return false;
            }

            if ($typeFilter && ($rec['type'] ?? '') !== $typeFilter) {
                return false;
            }

            if ($fluencyFilter && ($rec['fluency_status'] ?? '') !== $fluencyFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$rec['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchSurah = str_contains(strtolower($rec['surah_range']), $search);
                $matchMusyrif = str_contains(strtolower($rec['supervisor_name']), $search);
                if (!$matchStudent && !$matchSurah && !$matchMusyrif) {
                    return false;
                }
            }

            return true;
        });

        // Enrich
        $enriched = [];
        $totalMutqin = 0;
        $totalTasmi = 0;
        $totalRepetitions = 0;

        foreach ($filtered as $r) {
            $std = $studentMap[$r['student_id']] ?? null;
            $hlq = $halaqahMap[$r['halaqah_id']] ?? null;

            if ($r['fluency_status'] === 'Mutqin') $totalMutqin++;
            if (str_contains($r['type'], 'Tasmi')) $totalTasmi++;
            $totalRepetitions += (int) $r['repetition_count'];

            $enriched[] = array_merge($r, [
                'student_name' => $std['name'] ?? 'Santri',
                'nis' => $std['nis'] ?? '-',
                'class_id' => $std['class_id'] ?? '-',
                'halaqah_name' => $hlq['name'] ?? ($r['halaqah_id'] ?? 'Halaqah'),
            ]);
        }

        $stats = [
            'total_murojaah' => count($enriched),
            'total_mutqin' => $totalMutqin,
            'total_tasmi' => $totalTasmi,
            'total_repetitions' => $totalRepetitions
        ];

        return view('tahfidz.murojaah', [
            'records' => $enriched,
            'students' => $students,
            'halaqahs' => $halaqahs,
            'teachers' => $teachers,
            'stats' => $stats,
            'selectedHalaqahId' => $selectedHalaqahId,
            'selectedStudentId' => $selectedStudentId,
            'typeFilter' => $typeFilter,
            'fluencyFilter' => $fluencyFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Catatan Murojaah Baru (Session-based)
     */
    public function storeMurojaah(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'halaqah_id' => 'required|string',
            'teacher_id' => 'required|string',
            'date' => 'required|date',
            'session' => 'required|string',
            'type' => 'required|string',
            'juz' => 'required|integer|min:1|max:30',
            'surah_range' => 'required|string|max:150',
            'repetition_count' => 'required|integer|min:1|max:50',
            'fluency_status' => 'required|string',
            'score' => 'required|integer|min:0|max:100',
            'evaluation_notes' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $supervisorName = 'Musyrif Pengampu';
        foreach ($teachers as $t) {
            if ($t['id'] === $validated['teacher_id']) {
                $supervisorName = $t['name'];
                break;
            }
        }

        $score = (int) $validated['score'];
        if ($score >= 90) {
            $grade = 'Mumtaz';
            $status = 'lulus';
        } elseif ($score >= 80) {
            $grade = 'Jayyid Jiddan';
            $status = 'lulus';
        } elseif ($score >= 70) {
            $grade = 'Jayyid';
            $status = 'lulus';
        } else {
            $grade = 'Maqbul';
            $status = 'perlu_pengulangan';
        }

        $newId = 'MRJ-' . str_pad((string)(count($demo->getMurojaahRecords()) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'halaqah_id' => $validated['halaqah_id'],
            'teacher_id' => $validated['teacher_id'],
            'supervisor_name' => $supervisorName,
            'date' => $validated['date'],
            'session' => $validated['session'],
            'type' => $validated['type'],
            'juz' => (int) $validated['juz'],
            'surah_range' => $validated['surah_range'],
            'repetition_count' => (int) $validated['repetition_count'],
            'fluency_status' => $validated['fluency_status'],
            'grade' => $grade,
            'score' => $score,
            'evaluation_notes' => !empty($validated['evaluation_notes']) ? $validated['evaluation_notes'] : 'Murojaah penguatan hafalan mutqin terlaksana.',
            'status' => $status
        ];

        $sessionAdded = session('demo_murojaah_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_murojaah_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('tahfidz.murojaah')
            ->with('success', "Murojaah santri " . ($std['name'] ?? '') . " [Juz {$validated['juz']}: {$validated['surah_range']}] predikat {$grade} status {$validated['fluency_status']} berhasil dicatat!");
    }

    /**
     * Hapus Catatan Murojaah (Session-based)
     */
    public function destroyMurojaah(string $id)
    {
        $sessionDeleted = session('demo_murojaah_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_murojaah_deleted' => $sessionDeleted]);
        }

        return redirect()->route('tahfidz.murojaah')
            ->with('success', "Catatan murojaah [{$id}] berhasil dihapus!");
    }

    /**
     * T06.4 — Tahfidz Progress (Visual Dashboard & Progress Bar Capaian Tahfidz Santri)
     */
    public function progres(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();
        $halaqahs = $demo->getHalaqahs();
        $programs = $demo->getTahfidzPrograms();
        $units = $demo->getUnits();
        $classes = $demo->getClasses();
        $ziyadahRecords = $demo->getMemorizationRecords();
        $murojaahRecords = $demo->getMurojaahRecords();

        $unitFilter = $request->query('unit');
        $selectedHalaqahId = $request->query('halaqah_id');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $halaqahMap = [];
        foreach ($halaqahs as $h) {
            $halaqahMap[$h['id']] = $h;
        }

        $programMap = [];
        foreach ($programs as $p) {
            $programMap[$p['id']] = $p;
        }

        $unitMap = [];
        foreach ($units as $u) {
            $unitMap[$u['id']] = $u;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        // Student's halaqah map
        $studentHalaqahMap = [];
        foreach ($halaqahs as $h) {
            foreach ($h['student_ids'] as $sId) {
                $studentHalaqahMap[$sId] = $h;
            }
        }

        // Filter students
        $filteredStudents = array_filter($students, function ($s) use ($unitFilter, $selectedHalaqahId, $search, $studentHalaqahMap, $classMap) {
            $hlq = $studentHalaqahMap[$s['id']] ?? null;
            $cls = $classMap[$s['class_id'] ?? ''] ?? null;
            $unitId = $hlq['unit_id'] ?? ($cls['unit_id'] ?? 'UNIT-SMA');

            if ($unitFilter && $unitId !== $unitFilter) {
                return false;
            }

            if ($selectedHalaqahId && (!$hlq || $hlq['id'] !== $selectedHalaqahId)) {
                return false;
            }

            if ($search) {
                $matchName = str_contains(strtolower($s['name']), $search);
                $matchNis = str_contains(strtolower($s['nis'] ?? ''), $search);
                $matchHlq = $hlq && str_contains(strtolower($hlq['name']), $search);
                if (!$matchName && !$matchNis && !$matchHlq) {
                    return false;
                }
            }

            return true;
        });

        // Group ziyadah and murojaah by student
        $studentZiyadah = [];
        foreach ($ziyadahRecords as $z) {
            $studentZiyadah[$z['student_id']][] = $z;
        }

        $studentMurojaah = [];
        foreach ($murojaahRecords as $m) {
            $studentMurojaah[$m['student_id']][] = $m;
        }

        // Hitung progres tiap santri
        $studentProgressList = [];
        $totalJuzMutqinAll = 0;
        $totalJuzZiyadahAll = 0;
        $totalKhatam30 = 0;
        $totalTasmiPassed = 0;

        foreach ($filteredStudents as $std) {
            $sId = $std['id'];
            $hlq = $studentHalaqahMap[$sId] ?? null;
            $targetJuz = $hlq['target_juz'] ?? 5;

            // Deterministic calculation berdasarkan ID & record mutaba'ah
            $seed = crc32($sId) % 100;
            $zList = $studentZiyadah[$sId] ?? [];
            $mList = $studentMurojaah[$sId] ?? [];

            // Realisasi Juz Ziyadah & Mutqin
            if ($sId === 'SIS-0001') { // Ahmad Fauzan (Takhassus SMA)
                $juzZiyadah = 28.5;
                $juzMutqin = 25.0;
                $lastSurah = 'Al-Baqarah (Juz 1 & 2)';
                $currentJuz = 29;
            } elseif ($sId === 'SIS-0002') { // Siti Fatimah (SMP)
                $juzZiyadah = 14.0;
                $juzMutqin = 12.0;
                $lastSurah = 'An-Nisa\' (Juz 4)';
                $currentJuz = 14;
            } elseif ($sId === 'SIS-0003') { // Bilal Ramadhan (SMA)
                $juzZiyadah = 18.0;
                $juzMutqin = 15.0;
                $lastSurah = 'Ali \'Imran (Juz 3)';
                $currentJuz = 19;
            } elseif ($sId === 'SIS-0004') { // Zaskia Nur (SMP)
                $juzZiyadah = 8.5;
                $juzMutqin = 7.0;
                $lastSurah = 'Maryam (Juz 16)';
                $currentJuz = 9;
            } elseif ($sId === 'SIS-0005') { // Faris (SD)
                $juzZiyadah = 2.5;
                $juzMutqin = 2.0;
                $lastSurah = 'An-Naba\' (Juz 30)';
                $currentJuz = 29;
            } elseif ($sId === 'SIS-0008') { // Aisyah Humaira (SD)
                $juzZiyadah = 1.8;
                $juzMutqin = 1.0;
                $lastSurah = 'An-Nazi\'at (Juz 30)';
                $currentJuz = 30;
            } else {
                $juzZiyadah = round(max(1.0, ($seed / 100) * $targetJuz), 1);
                $juzMutqin = round(max(0.5, $juzZiyadah * 0.8), 1);
                $lastSurah = 'Juz ' . ceil($juzZiyadah);
                $currentJuz = min(30, (int)ceil($juzZiyadah) + 1);
            }

            // Hitung persentase capaian terhadap target program
            $pct = $targetJuz > 0 ? round(($juzMutqin / $targetJuz) * 100) : 0;
            if ($pct > 100) $pct = 100;

            if ($juzMutqin >= 30) $totalKhatam30++;
            $totalJuzMutqinAll += $juzMutqin;
            $totalJuzZiyadahAll += $juzZiyadah;

            // Status evaluasi kelancaran
            $statusLabel = 'Sesuai Target';
            $statusColor = 'success';
            if ($pct < 50) {
                $statusLabel = 'Perlu Akselerasi';
                $statusColor = 'danger';
            } elseif ($pct < 80) {
                $statusLabel = 'On Track';
                $statusColor = 'warning';
            }

            // Cek tasmi'
            $hasTasmi = false;
            foreach ($mList as $mItem) {
                if (str_contains($mItem['type'], 'Tasmi') && $mItem['status'] === 'lulus') {
                    $hasTasmi = true;
                    $totalTasmiPassed++;
                    break;
                }
            }

            $studentProgressList[] = [
                'id' => $sId,
                'name' => $std['name'],
                'nis' => $std['nis'] ?? '-',
                'class_name' => $classMap[$std['class_id'] ?? '']['name'] ?? '-',
                'halaqah_name' => $hlq['name'] ?? 'Halaqah Mandiri',
                'supervisor_name' => $hlq['supervisor_name'] ?? 'Musyrif',
                'target_juz' => $targetJuz,
                'juz_ziyadah' => $juzZiyadah,
                'juz_mutqin' => $juzMutqin,
                'current_juz' => $currentJuz,
                'last_surah' => $lastSurah,
                'progress_pct' => $pct,
                'status_label' => $statusLabel,
                'status_color' => $statusColor,
                'has_tasmi' => $hasTasmi,
                'total_ziyadah_entries' => count($zList),
                'total_murojaah_entries' => count($mList),
                'recent_ziyadah' => array_slice($zList, 0, 3),
                'recent_murojaah' => array_slice($mList, 0, 3)
            ];
        }

        $studentCount = count($studentProgressList);
        $avgJuzMutqin = $studentCount > 0 ? round($totalJuzMutqinAll / $studentCount, 1) : 0;

        $stats = [
            'total_students' => $studentCount,
            'total_juz_mutqin' => round($totalJuzMutqinAll, 1),
            'avg_juz' => $avgJuzMutqin,
            'total_tasmi_passed' => $totalTasmiPassed
        ];

        return view('tahfidz.progres', [
            'students' => $studentProgressList,
            'halaqahs' => $halaqahs,
            'units' => $units,
            'stats' => $stats,
            'activeUnit' => $unitFilter,
            'selectedHalaqahId' => $selectedHalaqahId,
            'search' => $search
        ]);
    }

    /**
     * T06.5 — Diniyah Subjects and Schedules (Kurikulum Kitab Kuning & Jadwal Diniyah)
     */
    public function diniyah(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $subjects = $demo->getDiniyahSubjects();
        $schedules = $demo->getDiniyahSchedules();
        $teachers = $demo->getTeachers();
        $classes = $demo->getClasses();

        $activeTab = $request->query('tab', 'jadwal'); // 'jadwal' atau 'kitab'
        $categoryFilter = $request->query('category');
        $dayFilter = $request->query('day');
        $search = strtolower(trim($request->query('q', '')));

        // Lookups
        $subjectMap = [];
        foreach ($subjects as $sbj) {
            $subjectMap[$sbj['id']] = $sbj;
        }

        $teacherMap = [];
        foreach ($teachers as $t) {
            $teacherMap[$t['id']] = $t;
        }

        // Filter jadwal
        $filteredSchedules = array_filter($schedules, function ($sch) use ($dayFilter, $search, $subjectMap, $teacherMap) {
            if ($dayFilter && $sch['day'] !== $dayFilter) {
                return false;
            }

            if ($search) {
                $sbj = $subjectMap[$sch['subject_id']] ?? null;
                $tch = $teacherMap[$sch['teacher_id']] ?? null;
                $matchSbj = $sbj && str_contains(strtolower($sbj['name']), $search);
                $matchTch = $tch && str_contains(strtolower($tch['name']), $search);
                $matchTarget = str_contains(strtolower($sch['class_target']), $search);
                $matchRoom = str_contains(strtolower($sch['room']), $search);
                if (!$matchSbj && !$matchTch && !$matchTarget && !$matchRoom) {
                    return false;
                }
            }

            return true;
        });

        // Enrich jadwal
        $enrichedSchedules = [];
        foreach ($filteredSchedules as $sch) {
            $sbj = $subjectMap[$sch['subject_id']] ?? null;
            $tch = $teacherMap[$sch['teacher_id']] ?? null;

            $enrichedSchedules[] = array_merge($sch, [
                'subject_name' => $sbj['name'] ?? 'Mata Pelajaran',
                'subject_category' => $sbj['category'] ?? 'Kitab Kuning',
                'kitab_author' => $sbj['kitab_author'] ?? 'Ulama Salaf',
                'teacher_name' => $tch['name'] ?? 'Ustadz Pengajar'
            ]);
        }

        // Filter kitab
        $filteredSubjects = array_filter($subjects, function ($sbj) use ($categoryFilter, $search) {
            if ($categoryFilter && $sbj['category'] !== $categoryFilter) {
                return false;
            }

            if ($search) {
                $matchName = str_contains(strtolower($sbj['name']), $search);
                $matchAuthor = str_contains(strtolower($sbj['kitab_author']), $search);
                $matchCode = str_contains(strtolower($sbj['code']), $search);
                if (!$matchName && !$matchAuthor && !$matchCode) {
                    return false;
                }
            }

            return true;
        });

        $categories = array_values(array_unique(array_column($subjects, 'category')));

        $stats = [
            'total_kitab' => count($subjects),
            'total_schedules' => count($schedules),
            'total_asatim' => count(array_unique(array_column($schedules, 'teacher_id'))),
            'total_categories' => count($categories)
        ];

        return view('tahfidz.diniyah', [
            'schedules' => $enrichedSchedules,
            'subjects' => $filteredSubjects,
            'allSubjects' => $subjects,
            'teachers' => $teachers,
            'classes' => $classes,
            'categories' => $categories,
            'stats' => $stats,
            'activeTab' => $activeTab,
            'categoryFilter' => $categoryFilter,
            'dayFilter' => $dayFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Kitab Diniyah Baru (Session-based)
     */
    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30',
            'category' => 'required|string|max:80',
            'level' => 'required|string|max:50',
            'kitab_author' => 'required|string|max:150',
            'description' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $newId = 'DIN-' . str_pad((string)(count($demo->getDiniyahSubjects()) + 1), 3, '0', STR_PAD_LEFT);

        $newSubject = [
            'id' => $newId,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'category' => $validated['category'],
            'level' => $validated['level'],
            'kitab_author' => $validated['kitab_author'],
            'description' => !empty($validated['description']) ? $validated['description'] : 'Kajian kitab kuning kurikulum pesantren.',
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_diniyah_subjects_added', []);
        $sessionAdded[] = $newSubject;
        session(['demo_diniyah_subjects_added' => $sessionAdded]);

        return redirect()->route('tahfidz.diniyah', ['tab' => 'kitab'])
            ->with('success', "Mata Pelajaran Kitab [{$newSubject['name']}] berhasil didaftarkan ke kurikulum!");
    }

    /**
     * Simpan Jadwal Pengajian Diniyah Baru (Session-based)
     */
    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|string',
            'teacher_id' => 'required|string',
            'class_target' => 'required|string|max:150',
            'room' => 'required|string|max:120',
            'day' => 'required|string',
            'time_start' => 'required|string',
            'time_end' => 'required|string',
            'session_label' => 'required|string|max:80'
        ]);

        $demo = DemoDataManager::getInstance();
        $newSchId = 'SCH-DIN-' . str_pad((string)(count($demo->getDiniyahSchedules()) + 1), 3, '0', STR_PAD_LEFT);

        $newSchedule = [
            'id' => $newSchId,
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'class_target' => $validated['class_target'],
            'room' => $validated['room'],
            'day' => $validated['day'],
            'time_start' => $validated['time_start'],
            'time_end' => $validated['time_end'],
            'session_label' => $validated['session_label'],
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_diniyah_schedules_added', []);
        $sessionAdded[] = $newSchedule;
        session(['demo_diniyah_schedules_added' => $sessionAdded]);

        return redirect()->route('tahfidz.diniyah', ['tab' => 'jadwal'])
            ->with('success', "Jadwal pengajian diniyah hari {$validated['day']} ({$validated['session_label']}) berhasil ditambahkan!");
    }

    /**
     * Hapus Jadwal Diniyah (Session-based)
     */
    public function destroySchedule(string $id)
    {
        $sessionDeleted = session('demo_diniyah_schedules_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_diniyah_schedules_deleted' => $sessionDeleted]);
        }

        return redirect()->route('tahfidz.diniyah', ['tab' => 'jadwal'])
            ->with('success', "Jadwal pengajian [{$id}] berhasil dihapus!");
    }

    /**
     * T06.6 — Tahfidz Report (Buku Rapor Khusus Tahfidz Al-Qur'an Santri)
     */
    public function rapor(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();
        $halaqahs = $demo->getHalaqahs();
        $programs = $demo->getTahfidzPrograms();
        $units = $demo->getUnits();
        $classes = $demo->getClasses();
        $ziyadahRecords = $demo->getMemorizationRecords();
        $murojaahRecords = $demo->getMurojaahRecords();

        $selectedStudentId = $request->query('student_id', $students[0]['id'] ?? 'SIS-0001');

        // Lookups
        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $studentHalaqahMap = [];
        foreach ($halaqahs as $h) {
            foreach ($h['student_ids'] as $sId) {
                $studentHalaqahMap[$sId] = $h;
            }
        }

        $programMap = [];
        foreach ($programs as $p) {
            $programMap[$p['id']] = $p;
        }

        // Cari santri terpilih
        $selectedStudent = null;
        foreach ($students as $s) {
            if ($s['id'] === $selectedStudentId) {
                $selectedStudent = $s;
                break;
            }
        }
        if (!$selectedStudent && !empty($students)) {
            $selectedStudent = $students[0];
            $selectedStudentId = $selectedStudent['id'];
        }

        $hlq = $studentHalaqahMap[$selectedStudentId] ?? null;
        $prg = $hlq ? ($programMap[$hlq['program_id']] ?? null) : null;
        $cls = $classMap[$selectedStudent['class_id'] ?? ''] ?? null;

        // Filter riwayat santri
        $studentZiyadah = array_filter($ziyadahRecords, function ($z) use ($selectedStudentId) {
            return $z['student_id'] === $selectedStudentId;
        });

        $studentMurojaah = array_filter($murojaahRecords, function ($m) use ($selectedStudentId) {
            return $m['student_id'] === $selectedStudentId;
        });

        // Capaian akumulatif
        $targetJuz = $hlq['target_juz'] ?? 5;
        if ($selectedStudentId === 'SIS-0001') {
            $juzMutqin = 25.0;
            $juzZiyadah = 28.5;
            $hifzhScore = 95;
            $tajwidScore = 94;
            $makhrajScore = 96;
            $disciplineScore = 98;
            $tasmiStatus = 'Lulus Tasmi\' Bil Ghoib Juz 30 (Mumtaz 96)';
        } elseif ($selectedStudentId === 'SIS-0002') {
            $juzMutqin = 12.0;
            $juzZiyadah = 14.0;
            $hifzhScore = 96;
            $tajwidScore = 96;
            $makhrajScore = 95;
            $disciplineScore = 95;
            $tasmiStatus = 'Lulus Tasmi\' Bil Ghoib Juz 29 (Mumtaz 98)';
        } elseif ($selectedStudentId === 'SIS-0003') {
            $juzMutqin = 15.0;
            $juzZiyadah = 18.0;
            $hifzhScore = 86;
            $tajwidScore = 88;
            $makhrajScore = 87;
            $disciplineScore = 92;
            $tasmiStatus = 'Persiapan Tasmi\' Bil Ghoib Juz 1';
        } else {
            $seed = crc32($selectedStudentId) % 100;
            $juzMutqin = round(max(1.0, ($seed / 100) * $targetJuz), 1);
            $juzZiyadah = round(min(30.0, $juzMutqin + 2.5), 1);
            $hifzhScore = rand(82, 94);
            $tajwidScore = rand(80, 92);
            $makhrajScore = rand(80, 95);
            $disciplineScore = rand(85, 96);
            $tasmiStatus = 'Telah menyelesaikan tasmi\' bertahap per rubu\'';
        }

        // Kalkulasi skor rapor tahfidz
        // Bobot: 35% Hifzh + 25% Tajwid + 25% Makhraj + 15% Disiplin
        $finalScore = round((0.35 * $hifzhScore) + (0.25 * $tajwidScore) + (0.25 * $makhrajScore) + (0.15 * $disciplineScore));

        if ($finalScore >= 90) {
            $predicate = 'Mumtaz (Istimewa)';
            $badgeColor = 'success';
        } elseif ($finalScore >= 80) {
            $predicate = 'Jayyid Jiddan (Amat Baik)';
            $badgeColor = 'primary';
        } elseif ($finalScore >= 70) {
            $predicate = 'Jayyid (Baik)';
            $badgeColor = 'warning';
        } else {
            $predicate = 'Maqbul (Cukup)';
            $badgeColor = 'danger';
        }

        // Catatan tersimpan di sesi jika ada
        $sessionNotes = session('demo_tahfidz_rapor_notes', []);
        $savedNote = $sessionNotes[$selectedStudentId] ?? null;

        $defaultNote = "Alhamdulillah ananda {$selectedStudent['name']} menunjukkan kesungguhan dan adab mulia dalam halaqah Al-Qur'an. Disarankan untuk konsisten menjaga rutinitas murojaah ba'da Shubuh dan Maghrib agar hafalan tetap mutqin dan terjaga.";
        $musyrifNote = $savedNote ?: $defaultNote;

        return view('tahfidz.rapor', [
            'students' => $students,
            'selectedStudent' => $selectedStudent,
            'selectedStudentId' => $selectedStudentId,
            'halaqah' => $hlq,
            'program' => $prg,
            'class' => $cls,
            'targetJuz' => $targetJuz,
            'juzMutqin' => $juzMutqin,
            'juzZiyadah' => $juzZiyadah,
            'hifzhScore' => $hifzhScore,
            'tajwidScore' => $tajwidScore,
            'makhrajScore' => $makhrajScore,
            'disciplineScore' => $disciplineScore,
            'finalScore' => $finalScore,
            'predicate' => $predicate,
            'badgeColor' => $badgeColor,
            'tasmiStatus' => $tasmiStatus,
            'musyrifNote' => $musyrifNote,
            'ziyadahList' => array_slice(array_values($studentZiyadah), 0, 5),
            'murojaahList' => array_slice(array_values($studentMurojaah), 0, 5)
        ]);
    }

    /**
     * Simpan Catatan Musyrif di Rapor Tahfidz (Session-based)
     */
    public function updateCatatanRapor(Request $request, string $studentId)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:500'
        ]);

        $sessionNotes = session('demo_tahfidz_rapor_notes', []);
        $sessionNotes[$studentId] = $validated['notes'];
        session(['demo_tahfidz_rapor_notes' => $sessionNotes]);

        return redirect()->route('tahfidz.rapor', ['student_id' => $studentId])
            ->with('success', "Catatan resmi musyrif untuk rapor tahfidz berhasil disimpan!");
    }
}

