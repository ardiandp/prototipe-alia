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
}

