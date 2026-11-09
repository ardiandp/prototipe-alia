<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class HrisController extends Controller
{
    /**
     * T10.1 - Employee profiles, list, and organization chart
     */
    public function pegawai(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, struktur, statistik
        $search = $request->get('search');
        $unitFilter = $request->get('unit', 'all');
        $statusFilter = $request->get('status', 'all');
        $genderFilter = $request->get('gender', 'all');

        // Filter daftar pegawai
        $filteredStaff = array_filter($teachers, function ($t) use ($search, $unitFilter, $statusFilter, $genderFilter) {
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($t['name'], $search) !== false ||
                               stripos($t['nip'] ?? '', $search) !== false ||
                               stripos($t['position'] ?? '', $search) !== false ||
                               stripos($t['subject'] ?? '', $search) !== false ||
                               stripos($t['email'] ?? '', $search) !== false;
            }

            $matchUnit = ($unitFilter === 'all') || (($t['unit_id'] ?? '') === $unitFilter);
            $matchStatus = ($statusFilter === 'all') || (($t['employment_status'] ?? '') === $statusFilter);
            $matchGender = ($genderFilter === 'all') || (($t['gender'] ?? '') === $genderFilter);

            return $matchSearch && $matchUnit && $matchStatus && $matchGender;
        });

        // Metrik Ringkasan HRIS
        $totalStaff = count($teachers);
        $totalGTY = count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Guru Tetap Yayasan (GTY)'));
        $totalKontrak = count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Guru Kontrak'));
        $totalTendik = count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Tenaga Kependidikan'));
        $totalLaki = count(array_filter($teachers, fn($t) => ($t['gender'] ?? '') === 'L'));
        $totalPerempuan = count(array_filter($teachers, fn($t) => ($t['gender'] ?? '') === 'P'));

        // Distribusi per Unit
        $unitDistribution = [];
        foreach ($units as $u) {
            $count = count(array_filter($teachers, fn($t) => ($t['unit_id'] ?? '') === $u['id']));
            $unitDistribution[$u['id']] = [
                'unit' => $u,
                'count' => $count
            ];
        }

        // Struktur Organisasi Hierarkis
        $orgStructure = [
            'pembina' => array_filter($teachers, fn($t) => stripos($t['position'], 'Ketua Yayasan') !== false || stripos($t['position'], 'Pembina') !== false),
            'kepsek' => array_filter($teachers, fn($t) => stripos($t['position'], 'Kepala Sekolah') !== false),
            'pimpinan_unit' => array_filter($teachers, fn($t) => stripos($t['position'], 'Waka') !== false || stripos($t['position'], 'Mudir') !== false || stripos($t['position'], 'Koordinator') !== false || stripos($t['position'], 'Bendahara') !== false || stripos($t['position'], 'Kepala Lab') !== false),
            'guru_wali' => array_filter($teachers, fn($t) => stripos($t['position'], 'Wali Kelas') !== false || stripos($t['position'], 'Guru') !== false || stripos($t['position'], 'Pembina Ekstrakurikuler') !== false)
        ];

        return view('hris.pegawai', compact(
            'filteredStaff',
            'teachers',
            'units',
            'activeTab',
            'search',
            'unitFilter',
            'statusFilter',
            'genderFilter',
            'totalStaff',
            'totalGTY',
            'totalKontrak',
            'totalTendik',
            'totalLaki',
            'totalPerempuan',
            'unitDistribution',
            'orgStructure'
        ));
    }

    /**
     * Simpan Pegawai Baru ke Sesi Demo
     */
    public function storePegawai(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nip' => 'nullable|string|max:30',
            'gender' => 'required|in:L,P',
            'subject' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|string',
            'unit_id' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'education' => 'nullable|string|max:100',
        ]);

        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $count = count($teachers) + 1;
        $id = 'GUR-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        $nip = $validated['nip'] ?: '1988' . rand(1000, 9999) . '201501100' . $count;

        $newStaff = [
            'id' => $id,
            'nip' => $nip,
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'gender' => $validated['gender'],
            'email' => $validated['email'] ?: strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name'])) . '@alia.sch.id',
            'employment_status' => $validated['employment_status'],
            'position' => $validated['position'],
            'unit_id' => $validated['unit_id'],
            'avatar' => $validated['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png',
            'phone' => $validated['phone'],
            'education' => $validated['education'] ?: 'S1 Pendidikan',
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_teachers_added', []);
        $sessionAdded[] = $newStaff;
        session(['demo_teachers_added' => $sessionAdded]);

        return redirect()->route('hris.pegawai')->with('success', "Pegawai/Pendidik baru \"{$newStaff['name']}\" ({$newStaff['position']}) berhasil didaftarkan ke sistem SDM!");
    }

    /**
     * Update Data Pegawai di Sesi Demo
     */
    public function updatePegawai(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nip' => 'required|string|max:30',
            'gender' => 'required|in:L,P',
            'subject' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|string',
            'unit_id' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'education' => 'nullable|string|max:100',
        ]);

        $sessionUpdated = session('demo_teachers_updated', []);
        $sessionUpdated[$id] = [
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'gender' => $validated['gender'],
            'subject' => $validated['subject'],
            'position' => $validated['position'],
            'employment_status' => $validated['employment_status'],
            'unit_id' => $validated['unit_id'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?: ($id . '@alia.sch.id'),
            'education' => $validated['education'] ?: 'S1 Pendidikan',
        ];
        session(['demo_teachers_updated' => $sessionUpdated]);

        return redirect()->route('hris.pegawai')->with('success', "Data profil pegawai {$validated['name']} berhasil diperbarui!");
    }

    /**
     * Hapus Pegawai dari Sesi Demo
     */
    public function destroyPegawai(string $id)
    {
        $sessionDeleted = session('demo_teachers_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_teachers_deleted' => $sessionDeleted]);
        }

        return redirect()->route('hris.pegawai')->with('info', "Data pegawai ID: {$id} berhasil dinonaktifkan/dihapus dari sesi demo.");
    }

    /**
     * T10.2 - Staff Attendance (Presensi Pegawai)
     */
    public function presensi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $attendanceList = $demo->getStaffAttendance();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'harian'); // harian, rekap, mesin
        $dateFilter = $request->get('date', '2026-11-09');
        $unitFilter = $request->get('unit', 'all');
        $statusFilter = $request->get('status', 'all');
        $search = $request->get('search');

        // Filter log presensi
        $filteredAttendance = array_filter($attendanceList, function ($att) use ($dateFilter, $unitFilter, $statusFilter, $search) {
            $matchDate = ($dateFilter === 'all') || (($att['date'] ?? '') === $dateFilter);
            $matchUnit = ($unitFilter === 'all') || (($att['unit_id'] ?? '') === $unitFilter);
            $matchStatus = ($statusFilter === 'all') || (($att['status'] ?? '') === $statusFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($att['name'], $search) !== false ||
                               stripos($att['nip'] ?? '', $search) !== false ||
                               stripos($att['device'] ?? '', $search) !== false;
            }
            return $matchDate && $matchUnit && $matchStatus && $matchSearch;
        });

        // Metrik Ringkasan Hari Ini
        $totalStaff = count($teachers);
        $totalHadir = count(array_filter($filteredAttendance, fn($a) => ($a['status'] ?? '') === 'hadir'));
        $totalTerlambat = count(array_filter($filteredAttendance, fn($a) => ($a['status'] ?? '') === 'terlambat'));
        $totalIzin = count(array_filter($filteredAttendance, fn($a) => ($a['status'] ?? '') === 'izin'));
        $totalSakit = count(array_filter($filteredAttendance, fn($a) => ($a['status'] ?? '') === 'sakit'));
        $totalAlpa = count(array_filter($filteredAttendance, fn($a) => ($a['status'] ?? '') === 'alpa'));

        // Pegawai yang belum presensi (jika ada)
        $attendedTeacherIds = array_column($attendanceList, 'teacher_id');
        $unrecordedStaff = array_filter($teachers, function ($t) use ($attendedTeacherIds) {
            return !in_array($t['id'], $attendedTeacherIds);
        });

        return view('hris.presensi', compact(
            'filteredAttendance',
            'attendanceList',
            'teachers',
            'units',
            'activeTab',
            'dateFilter',
            'unitFilter',
            'statusFilter',
            'search',
            'totalStaff',
            'totalHadir',
            'totalTerlambat',
            'totalIzin',
            'totalSakit',
            'totalAlpa',
            'unrecordedStaff'
        ));
    }

    /**
     * Simpan / Catat Presensi Manual Pegawai
     */
    public function storePresensi(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|string',
            'date' => 'required|date',
            'check_in' => 'nullable|string',
            'check_out' => 'nullable|string',
            'status' => 'required|in:hadir,terlambat,izin,sakit,alpa',
            'notes' => 'nullable|string|max:255',
            'device' => 'nullable|string|max:100',
        ]);

        $demo = DemoDataManager::getInstance();
        $teacher = $demo->findTeacher($validated['teacher_id']);
        if (!$teacher) {
            return redirect()->back()->withErrors(['teacher_id' => 'Pegawai tidak ditemukan.']);
        }

        // Hitung menit keterlambatan jika status terlambat
        $lateMinutes = 0;
        if ($validated['status'] === 'terlambat' && !empty($validated['check_in'])) {
            $parts = explode(':', $validated['check_in']);
            if (count($parts) === 2) {
                $checkInMins = ((int)$parts[0] * 60) + (int)$parts[1];
                $standardMins = (7 * 60); // Standar masuk 07:00
                $lateMinutes = max(0, $checkInMins - $standardMins);
            }
        }

        $newRecord = [
            'id' => 'ATT-STF-' . rand(100, 999),
            'teacher_id' => $teacher['id'],
            'name' => $teacher['name'],
            'nip' => $teacher['nip'] ?? '-',
            'unit_id' => $teacher['unit_id'] ?? 'UNIT-SMA',
            'date' => $validated['date'],
            'check_in' => $validated['check_in'] ?: null,
            'check_out' => $validated['check_out'] ?: null,
            'status' => $validated['status'],
            'late_minutes' => $lateMinutes,
            'location' => 'Kampus Alia Islamic School',
            'device' => $validated['device'] ?: 'Input Manual Admin HRIS',
            'notes' => $validated['notes'] ?: '-'
        ];

        $sessionAdded = session('demo_staff_attendance_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_staff_attendance_added' => $sessionAdded]);

        return redirect()->route('hris.presensi', ['date' => $validated['date']])->with('success', "Presensi {$teacher['name']} untuk tanggal {$validated['date']} berhasil dicatat!");
    }

    /**
     * Aksi Cepat: Tandai Semua Hadir Tepat Waktu (Quick Attendance)
     */
    public function markAllPresent(Request $request)
    {
        $date = $request->get('date', '2026-11-09');
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $currentAttendance = $demo->getStaffAttendance();
        $existingTeacherIds = array_column(array_filter($currentAttendance, fn($a) => ($a['date'] ?? '') === $date), 'teacher_id');

        $sessionAdded = session('demo_staff_attendance_added', []);

        $addedCount = 0;
        foreach ($teachers as $t) {
            if (!in_array($t['id'], $existingTeacherIds)) {
                $sessionAdded[] = [
                    'id' => 'ATT-STF-' . rand(100, 999),
                    'teacher_id' => $t['id'],
                    'name' => $t['name'],
                    'nip' => $t['nip'] ?? '-',
                    'unit_id' => $t['unit_id'] ?? 'UNIT-SMA',
                    'date' => $date,
                    'check_in' => '06:45',
                    'check_out' => '15:30',
                    'status' => 'hadir',
                    'late_minutes' => 0,
                    'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                    'device' => 'Terminal Biometrik Presensi Cepat',
                    'notes' => 'Presensi serentak tepat waktu (Quick Check-in).'
                ];
                $addedCount++;
            }
        }

        session(['demo_staff_attendance_added' => $sessionAdded]);

        return redirect()->route('hris.presensi', ['date' => $date])->with('success', "Berhasil mencatat {$addedCount} pegawai hadir serentak pada tanggal {$date}!");
    }

    /**
     * Hapus Presensi dari Sesi Demo
     */
    public function destroyPresensi(string $id)
    {
        $sessionDeleted = session('demo_staff_attendance_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_staff_attendance_deleted' => $sessionDeleted]);
        }

        return redirect()->route('hris.presensi')->with('info', "Catatan presensi ID: {$id} berhasil dihapus dari sesi demo.");
    }

    /**
     * T10.3 - Leave & Permission Workflow (Pengajuan & Persetujuan Cuti Pegawai)
     */
    public function cuti(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $leaves = $demo->getStaffLeaves();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, ajukan, saldo
        $statusFilter = $request->get('status', 'all');
        $typeFilter = $request->get('type', 'all');
        $unitFilter = $request->get('unit', 'all');
        $search = $request->get('search');

        // Filter pengajuan cuti
        $filteredLeaves = array_filter($leaves, function ($l) use ($statusFilter, $typeFilter, $unitFilter, $search) {
            $matchStatus = ($statusFilter === 'all') || (($l['status'] ?? '') === $statusFilter);
            $matchType = ($typeFilter === 'all') || (($l['type'] ?? '') === $typeFilter);
            $matchUnit = ($unitFilter === 'all') || (($l['unit_id'] ?? '') === $unitFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($l['name'], $search) !== false ||
                               stripos($l['nip'] ?? '', $search) !== false ||
                               stripos($l['leave_number'] ?? '', $search) !== false ||
                               stripos($l['type_label'] ?? '', $search) !== false ||
                               stripos($l['reason'] ?? '', $search) !== false;
            }
            return $matchStatus && $matchType && $matchUnit && $matchSearch;
        });

        // Metrik Ringkasan Cuti
        $totalPengajuan = count($leaves);
        $totalDiajukan = count(array_filter($leaves, fn($l) => ($l['status'] ?? '') === 'diajukan'));
        $totalDisetujui = count(array_filter($leaves, fn($l) => ($l['status'] ?? '') === 'disetujui'));
        $totalDitolak = count(array_filter($leaves, fn($l) => ($l['status'] ?? '') === 'ditolak'));

        // Saldo Cuti Pegawai (Standar 12 hari/tahun)
        $leaveBalances = [];
        foreach ($teachers as $t) {
            $usedDays = 0;
            $tApprovedLeaves = array_filter($leaves, fn($l) => ($l['teacher_id'] ?? '') === $t['id'] && in_array($l['status'] ?? '', ['disetujui', 'selesai']));
            foreach ($tApprovedLeaves as $al) {
                if (($al['type'] ?? '') === 'tahunan') {
                    $usedDays += ($al['duration_days'] ?? 0);
                }
            }
            $remaining = max(0, 12 - $usedDays);
            $leaveBalances[] = [
                'teacher' => $t,
                'quota' => 12,
                'used' => $usedDays,
                'remaining' => $remaining
            ];
        }

        return view('hris.cuti', compact(
            'filteredLeaves',
            'leaves',
            'teachers',
            'units',
            'activeTab',
            'statusFilter',
            'typeFilter',
            'unitFilter',
            'search',
            'totalPengajuan',
            'totalDiajukan',
            'totalDisetujui',
            'totalDitolak',
            'leaveBalances'
        ));
    }

    /**
     * Simpan Pengajuan Cuti Baru (Session-based)
     */
    public function storeCuti(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|string',
            'type' => 'required|in:tahunan,sakit,melahirkan,umroh_haji,dinas,penting',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'substitute_teacher_id' => 'nullable|string',
            'attachment' => 'nullable|string|max:100',
        ]);

        $demo = DemoDataManager::getInstance();
        $teacher = $demo->findTeacher($validated['teacher_id']);
        if (!$teacher) {
            return redirect()->back()->withErrors(['teacher_id' => 'Pegawai tidak ditemukan.']);
        }

        // Hitung durasi hari
        $start = strtotime($validated['start_date']);
        $end = strtotime($validated['end_date']);
        $durationDays = max(1, round(($end - $start) / (60 * 60 * 24)) + 1);

        $substituteName = '-';
        if (!empty($validated['substitute_teacher_id'])) {
            $sub = $demo->findTeacher($validated['substitute_teacher_id']);
            if ($sub) {
                $substituteName = $sub['name'];
            }
        }

        $typeLabels = [
            'tahunan' => 'Cuti Tahunan',
            'sakit' => 'Cuti Sakit',
            'melahirkan' => 'Cuti Bersalin / Melahirkan',
            'umroh_haji' => 'Cuti Ibadah Umroh / Haji',
            'dinas' => 'Izin Tugas Kedinasan',
            'penting' => 'Cuti Alasan Penting'
        ];

        $count = count($demo->getStaffLeaves()) + 1;
        $leaveNumber = 'CT/ALIA/2026/XI/' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newLeave = [
            'id' => 'CUTI-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'leave_number' => $leaveNumber,
            'teacher_id' => $teacher['id'],
            'name' => $teacher['name'],
            'nip' => $teacher['nip'] ?? '-',
            'unit_id' => $teacher['unit_id'] ?? 'UNIT-SMA',
            'type' => $validated['type'],
            'type_label' => $typeLabels[$validated['type']] ?? 'Cuti Kerja',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_days' => $durationDays,
            'reason' => $validated['reason'],
            'substitute_teacher_id' => $validated['substitute_teacher_id'] ?: null,
            'substitute_teacher_name' => $substituteName,
            'attachment' => $validated['attachment'] ?: 'berkas_pengajuan_cuti_' . strtolower($validated['type']) . '.pdf',
            'status' => 'diajukan',
            'approver_role' => 'Kepala Sekolah & HRD',
            'approved_by' => null,
            'approved_at' => null,
            'approver_notes' => 'Menunggu review atasan langsung.',
            'remaining_quota' => 12
        ];

        $sessionAdded = session('demo_staff_leaves_added', []);
        $sessionAdded[] = $newLeave;
        session(['demo_staff_leaves_added' => $sessionAdded]);

        return redirect()->route('hris.cuti')->with('success', "Permohonan cuti no. {$leaveNumber} atas nama {$teacher['name']} berhasil diajukan!");
    }

    /**
     * Persetujuan / Penolakan Cuti (Workflow Approval)
     */
    public function approveCuti(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'approver_notes' => 'nullable|string|max:255',
        ]);

        $sessionUpdated = session('demo_staff_leaves_updated', []);
        $sessionUpdated[$id] = [
            'status' => $validated['status'],
            'approved_by' => 'Dr. H. Zulkifli, Lc., M.A. / Kepala Unit',
            'approved_at' => date('Y-m-d H:i:s'),
            'approver_notes' => $validated['approver_notes'] ?: ($validated['status'] === 'disetujui' ? 'Permohonan cuti disetujui penuh.' : 'Permohonan cuti belum dapat disetujui.')
        ];
        session(['demo_staff_leaves_updated' => $sessionUpdated]);

        $statusText = $validated['status'] === 'disetujui' ? 'disetujui' : 'ditolak';
        return redirect()->route('hris.cuti')->with('success', "Permohonan cuti (ID: {$id}) telah berhasil {$statusText}!");
    }

    /**
     * Hapus Pengajuan Cuti dari Sesi Demo
     */
    public function destroyCuti(string $id)
    {
        $sessionDeleted = session('demo_staff_leaves_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_staff_leaves_deleted' => $sessionDeleted]);
        }

        return redirect()->route('hris.cuti')->with('info', "Pengajuan cuti ID: {$id} berhasil dibatalkan/dihapus dari sesi demo.");
    }

    /**
     * T10.4 - Overtime Management (Lembur Pegawai & SPKL)
     */
    public function lembur(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $overtimes = $demo->getStaffOvertimes();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, rekap, tarif
        $statusFilter = $request->get('status', 'all');
        $categoryFilter = $request->get('category', 'all');
        $unitFilter = $request->get('unit', 'all');
        $search = $request->get('search');

        // Filter pengajuan lembur
        $filteredOvertimes = array_filter($overtimes, function ($o) use ($statusFilter, $categoryFilter, $unitFilter, $search) {
            $matchStatus = ($statusFilter === 'all') || (($o['status'] ?? '') === $statusFilter);
            $matchCat = ($categoryFilter === 'all') || (($o['activity_category'] ?? '') === $categoryFilter);
            $matchUnit = ($unitFilter === 'all') || (($o['unit_id'] ?? '') === $unitFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($o['name'], $search) !== false ||
                               stripos($o['nip'] ?? '', $search) !== false ||
                               stripos($o['spkl_number'] ?? '', $search) !== false ||
                               stripos($o['activity_category_label'] ?? '', $search) !== false ||
                               stripos($o['task_description'] ?? '', $search) !== false;
            }
            return $matchStatus && $matchCat && $matchUnit && $matchSearch;
        });

        // Metrik Ringkasan Lembur
        $totalSPKL = count($overtimes);
        $totalDiajukan = count(array_filter($overtimes, fn($o) => ($o['status'] ?? '') === 'diajukan'));
        $totalDisetujui = count(array_filter($overtimes, fn($o) => in_array($o['status'] ?? '', ['disetujui', 'dibayarkan'])));
        $totalHours = array_sum(array_column($overtimes, 'duration_hours'));
        $totalCompensation = array_sum(array_column($overtimes, 'compensation_amount'));

        // Rekapitulasi per Pegawai
        $staffOvertimeSummary = [];
        foreach ($teachers as $t) {
            $tOvertimes = array_filter($overtimes, fn($o) => ($o['teacher_id'] ?? '') === $t['id']);
            $count = count($tOvertimes);
            $hours = array_sum(array_column($tOvertimes, 'duration_hours'));
            $comp = array_sum(array_column($tOvertimes, 'compensation_amount'));
            if ($count > 0) {
                $staffOvertimeSummary[] = [
                    'teacher' => $t,
                    'count' => $count,
                    'hours' => $hours,
                    'compensation' => $comp
                ];
            }
        }

        return view('hris.lembur', compact(
            'filteredOvertimes',
            'overtimes',
            'teachers',
            'units',
            'activeTab',
            'statusFilter',
            'categoryFilter',
            'unitFilter',
            'search',
            'totalSPKL',
            'totalDiajukan',
            'totalDisetujui',
            'totalHours',
            'totalCompensation',
            'staffOvertimeSummary'
        ));
    }

    /**
     * Simpan Surat Perintah Kerja Lembur Baru (SPKL)
     */
    public function storeLembur(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'activity_category' => 'required|in:it_lab,ppdb,asrama,ujian,sarpras,acara',
            'task_description' => 'required|string|max:500',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $demo = DemoDataManager::getInstance();
        $teacher = $demo->findTeacher($validated['teacher_id']);
        if (!$teacher) {
            return redirect()->back()->withErrors(['teacher_id' => 'Pegawai tidak ditemukan.']);
        }

        // Hitung durasi jam
        $startParts = explode(':', $validated['start_time']);
        $endParts = explode(':', $validated['end_time']);
        $startMinutes = ((int)$startParts[0] * 60) + (int)($startParts[1] ?? 0);
        $endMinutes = ((int)$endParts[0] * 60) + (int)($endParts[1] ?? 0);
        
        if ($endMinutes < $startMinutes) {
            // Lembur lintas tengah malam (misal 20:00 - 04:00)
            $diffMinutes = (1440 - $startMinutes) + $endMinutes;
        } else {
            $diffMinutes = max(30, $endMinutes - $startMinutes);
        }
        $durationHours = round($diffMinutes / 60, 1);

        // Tarif per jam default Rp 35.000 (Rp 40.000 untuk asrama/malam)
        $rate = $validated['hourly_rate'] ?: ($validated['activity_category'] === 'asrama' ? 40000 : 35000);
        $compensation = round($durationHours * $rate);

        $categoryLabels = [
            'it_lab' => 'Instalasi & Maintenance Lab IT',
            'ppdb' => 'Kepengurusan Panitia PPDB',
            'asrama' => 'Piket Pengawasan & Pembinaan Asrama',
            'ujian' => 'Klinik Remedial & Bimbingan OSN/Ujian',
            'sarpras' => 'Perbaikan & Pemeliharaan Sarpras',
            'acara' => 'Kepanitiaan Event & Ekstrakurikuler'
        ];

        $count = count($demo->getStaffOvertimes()) + 1;
        $spklNumber = 'SPKL/ALIA/2026/XI/' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newOvertime = [
            'id' => 'LMBR-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'spkl_number' => $spklNumber,
            'teacher_id' => $teacher['id'],
            'name' => $teacher['name'],
            'nip' => $teacher['nip'] ?? '-',
            'unit_id' => $teacher['unit_id'] ?? 'UNIT-SMA',
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_hours' => $durationHours,
            'hourly_rate' => $rate,
            'compensation_amount' => $compensation,
            'activity_category' => $validated['activity_category'],
            'activity_category_label' => $categoryLabels[$validated['activity_category']] ?? 'Kegiatan Lembur Sekolah',
            'task_description' => $validated['task_description'],
            'status' => 'diajukan',
            'approver_role' => 'Kepala Sekolah / Mudir',
            'approved_by' => null,
            'approved_at' => null,
            'approver_notes' => 'Menunggu verifikasi bukti pelaksanaan tugas.'
        ];

        $sessionAdded = session('demo_staff_overtimes_added', []);
        $sessionAdded[] = $newOvertime;
        session(['demo_staff_overtimes_added' => $sessionAdded]);

        return redirect()->route('hris.lembur')->with('success', "Surat Perintah Kerja Lembur no. {$spklNumber} untuk {$teacher['name']} berhasil diterbitkan!");
    }

    /**
     * Persetujuan / Pembayaran Lembur Pegawai
     */
    public function approveLembur(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak,dibayarkan',
            'approver_notes' => 'nullable|string|max:255',
        ]);

        $sessionUpdated = session('demo_staff_overtimes_updated', []);
        $sessionUpdated[$id] = [
            'status' => $validated['status'],
            'approved_by' => 'Dr. H. Zulkifli, Lc., M.A. / Bendahara',
            'approved_at' => date('Y-m-d H:i:s'),
            'approver_notes' => $validated['approver_notes'] ?: ($validated['status'] === 'disetujui' ? 'Perintah lembur telah disetujui pimpinan.' : ($validated['status'] === 'dibayarkan' ? 'Honor lembur telah dibayarkan tunai/transfer.' : 'Pengajuan lembur ditolak.'))
        ];
        session(['demo_staff_overtimes_updated' => $sessionUpdated]);

        $statusText = $validated['status'] === 'disetujui' ? 'disetujui' : ($validated['status'] === 'dibayarkan' ? 'dibayarkan' : 'ditolak');
        return redirect()->route('hris.lembur')->with('success', "Surat Perintah Kerja Lembur (ID: {$id}) telah berhasil {$statusText}!");
    }

    /**
     * Hapus Data Lembur dari Sesi Demo
     */
    public function destroyLembur(string $id)
    {
        $sessionDeleted = session('demo_staff_overtimes_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_staff_overtimes_deleted' => $sessionDeleted]);
        }

        return redirect()->route('hris.lembur')->with('info', "Catatan SPKL ID: {$id} berhasil dihapus dari sesi demo.");
    }

    /**
     * T10.5 - Performance Evaluation / Penilaian Kinerja Guru & Tendik (PKG)
     */
    public function evaluasi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $evaluations = $demo->getStaffEvaluations();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, rubrik, komparasi
        $predicateFilter = $request->get('predicate', 'all');
        $unitFilter = $request->get('unit', 'all');
        $search = $request->get('search');

        // Filter evaluasi kinerja
        $filteredEvaluations = array_filter($evaluations, function ($evl) use ($predicateFilter, $unitFilter, $search) {
            $matchPredicate = true;
            if ($predicateFilter !== 'all') {
                $matchPredicate = stripos($evl['predicate'], $predicateFilter) !== false;
            }
            $matchUnit = ($unitFilter === 'all') || (($evl['unit_id'] ?? '') === $unitFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($evl['name'], $search) !== false ||
                               stripos($evl['nip'] ?? '', $search) !== false ||
                               stripos($evl['doc_number'] ?? '', $search) !== false ||
                               stripos($evl['position'] ?? '', $search) !== false ||
                               stripos($evl['evaluator_name'] ?? '', $search) !== false;
            }
            return $matchPredicate && $matchUnit && $matchSearch;
        });

        // Metrik Ringkasan Evaluasi
        $totalEvaluasi = count($evaluations);
        $totalGradeA = count(array_filter($evaluations, fn($e) => stripos($e['predicate'], 'Amat Baik') !== false));
        $totalGradeB = count(array_filter($evaluations, fn($e) => stripos($e['predicate'], 'Baik') !== false && stripos($e['predicate'], 'Amat Baik') === false));
        $totalGradeC = count(array_filter($evaluations, fn($e) => stripos($e['predicate'], 'Cukup') !== false));
        $avgScore = $totalEvaluasi > 0 ? round(array_sum(array_column($evaluations, 'final_score')) / $totalEvaluasi, 1) : 0;

        // Pegawai yang belum dievaluasi
        $evaluatedTeacherIds = array_column($evaluations, 'teacher_id');
        $unevaluatedTeachers = array_filter($teachers, fn($t) => !in_array($t['id'], $evaluatedTeacherIds));

        return view('hris.evaluasi', compact(
            'filteredEvaluations',
            'evaluations',
            'teachers',
            'units',
            'activeTab',
            'predicateFilter',
            'unitFilter',
            'search',
            'totalEvaluasi',
            'totalGradeA',
            'totalGradeB',
            'totalGradeC',
            'avgScore',
            'unevaluatedTeachers'
        ));
    }

    /**
     * Simpan Lembar Penilaian Kinerja Guru/Pegawai Baru (PKG)
     */
    public function storeEvaluasi(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|string',
            'period' => 'required|string|max:100',
            'evaluator_name' => 'required|string|max:100',
            'evaluation_date' => 'required|date',
            'pedagogik_score' => 'required|numeric|min:0|max:100',
            'profesional_score' => 'required|numeric|min:0|max:100',
            'kepribadian_score' => 'required|numeric|min:0|max:100',
            'sosial_score' => 'required|numeric|min:0|max:100',
            'strengths' => 'required|string|max:500',
            'recommendations' => 'required|string|max:500',
        ]);

        $demo = DemoDataManager::getInstance();
        $teacher = $demo->findTeacher($validated['teacher_id']);
        if (!$teacher) {
            return redirect()->back()->withErrors(['teacher_id' => 'Pegawai tidak ditemukan.']);
        }

        // Kalkulasi Skor Akhir Berbobot (Pedagogik 30%, Profesional 30%, Kepribadian 20%, Sosial 20%)
        $finalScore = round(
            ($validated['pedagogik_score'] * 0.30) +
            ($validated['profesional_score'] * 0.30) +
            ($validated['kepribadian_score'] * 0.20) +
            ($validated['sosial_score'] * 0.20),
            1
        );

        // Penentuan Predikat
        if ($finalScore >= 91) {
            $predicate = 'Amat Baik (A)';
        } elseif ($finalScore >= 76) {
            $predicate = 'Baik (B)';
        } elseif ($finalScore >= 61) {
            $predicate = 'Cukup (C)';
        } else {
            $predicate = 'Kurang (D)';
        }

        $count = count($demo->getStaffEvaluations()) + 1;
        $docNumber = 'PKG/ALIA/2026/' . $teacher['id'];

        $newEvaluation = [
            'id' => 'EVL-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'doc_number' => $docNumber,
            'teacher_id' => $teacher['id'],
            'name' => $teacher['name'],
            'nip' => $teacher['nip'] ?? '-',
            'position' => $teacher['position'] ?? 'Tenaga Pendidik',
            'unit_id' => $teacher['unit_id'] ?? 'UNIT-SMA',
            'period' => $validated['period'],
            'evaluator_name' => $validated['evaluator_name'],
            'evaluation_date' => $validated['evaluation_date'],
            'pedagogik_score' => (float)$validated['pedagogik_score'],
            'profesional_score' => (float)$validated['profesional_score'],
            'kepribadian_score' => (float)$validated['kepribadian_score'],
            'sosial_score' => (float)$validated['sosial_score'],
            'final_score' => $finalScore,
            'predicate' => $predicate,
            'strengths' => $validated['strengths'],
            'recommendations' => $validated['recommendations'],
            'status' => 'final'
        ];

        $sessionAdded = session('demo_staff_evaluations_added', []);
        $sessionAdded[] = $newEvaluation;
        session(['demo_staff_evaluations_added' => $sessionAdded]);

        return redirect()->route('hris.evaluasi')->with('success', "Penilaian kinerja pegawai {$teacher['name']} berhasil diterbitkan dengan skor {$finalScore} ({$predicate})!");
    }

    /**
     * Hapus Lembar Evaluasi dari Sesi Demo
     */
    public function destroyEvaluasi(string $id)
    {
        $sessionDeleted = session('demo_staff_evaluations_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_staff_evaluations_deleted' => $sessionDeleted]);
        }

        return redirect()->route('hris.evaluasi')->with('info', "Lembar evaluasi kinerja ID: {$id} berhasil dihapus dari sesi demo.");
    }
}

