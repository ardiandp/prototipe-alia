<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * T02.1 - Master Data Unit Pendidikan
     */
    public function unit(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $units = $demo->getUnits();

        // Search & Filter
        $search = $request->get('search');
        $levelFilter = $request->get('level', 'all');

        if ($search) {
            $units = array_filter($units, function ($u) use ($search) {
                return stripos($u['name'], $search) !== false ||
                       stripos($u['headmaster'], $search) !== false ||
                       stripos($u['npsn'] ?? '', $search) !== false ||
                       stripos($u['code'] ?? '', $search) !== false;
            });
        }

        if ($levelFilter !== 'all') {
            $units = array_filter($units, function ($u) use ($levelFilter) {
                return ($u['level'] ?? '') === $levelFilter;
            });
        }

        return view('master.unit', compact('units', 'search', 'levelFilter'));
    }

    /**
     * Simpan Tambah Unit Pendidikan (Session-based demo)
     */
    public function storeUnit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
            'level' => 'required|in:TK,SD,SMP,SMA,SMK',
            'headmaster' => 'required|string|max:100',
            'npsn' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $newUnit = [
            'id' => 'UNIT-' . strtoupper($validated['level']) . '-' . rand(10, 99),
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'level' => $validated['level'],
            'npsn' => $validated['npsn'] ?: '2010' . rand(1000, 9999),
            'headmaster' => $validated['headmaster'],
            'phone' => $validated['phone'] ?: '021-8690' . rand(1000, 9999),
            'email' => $validated['email'] ?: strtolower($validated['code']) . '@alia.sch.id',
            'address' => $validated['address'] ?: 'Kampus Alia Islamic School, Jakarta Timur',
            'student_count' => 0,
            'class_count' => 0,
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_units_added', []);
        $sessionAdded[] = $newUnit;
        session(['demo_units_added' => $sessionAdded]);

        return redirect()->route('master.unit')->with('success', "Unit pendidikan \"{$newUnit['name']}\" berhasil ditambahkan ke sesi demo!");
    }

    /**
     * Hapus Unit Pendidikan (Session-based demo)
     */
    public function destroyUnit(string $id)
    {
        $sessionDeleted = session('demo_units_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_units_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.unit')->with('info', "Unit pendidikan (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.2 - Master Data Tahun Ajaran & Semester
     */
    public function tahunAjaran(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $academicYears = $demo->getAcademicYears();

        $search = $request->get('search');
        $semesterFilter = $request->get('semester', 'all');

        if ($search) {
            $academicYears = array_filter($academicYears, function ($ay) use ($search) {
                return stripos($ay['name'], $search) !== false ||
                       stripos($ay['year'], $search) !== false;
            });
        }

        if ($semesterFilter !== 'all') {
            $academicYears = array_filter($academicYears, function ($ay) use ($semesterFilter) {
                return ($ay['semester'] ?? '') === $semesterFilter;
            });
        }

        return view('master.tahun-ajaran', compact('academicYears', 'search', 'semesterFilter'));
    }

    /**
     * Simpan Tambah Tahun Ajaran & Semester Baru (Session-based)
     */
    public function storeTahunAjaran(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:20', // Contoh: 2027/2028
            'semester' => 'required|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:255',
            'set_as_active' => 'nullable'
        ]);

        $yearSlug = str_replace('/', '-', $validated['year']);
        $semId = $validated['semester'] === 'Ganjil' ? '1' : '2';
        $newId = "TA-{$yearSlug}-{$semId}";

        $newAy = [
            'id' => $newId,
            'name' => "{$validated['year']} {$validated['semester']}",
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => !empty($validated['set_as_active']) ? 'aktif' : 'draft',
            'is_current' => !empty($validated['set_as_active']),
            'description' => $validated['description'] ?: 'Tahun ajaran & semester baru'
        ];

        $sessionAdded = session('demo_academic_years_added', []);
        $sessionAdded[] = $newAy;
        session(['demo_academic_years_added' => $sessionAdded]);

        if (!empty($validated['set_as_active'])) {
            session(['demo_active_academic_year_id' => $newId]);
        }

        return redirect()->route('master.tahun-ajaran')->with('success', "Tahun ajaran & semester \"{$newAy['name']}\" berhasil ditambahkan!");
    }

    /**
     * Setel Periode Tahun Ajaran Menjadi Aktif
     */
    public function activateTahunAjaran(string $id)
    {
        session(['demo_active_academic_year_id' => $id]);
        return redirect()->route('master.tahun-ajaran')->with('success', "Tahun ajaran (ID: {$id}) berhasil diaktifkan sebagai periode aktif sistem!");
    }

    /**
     * Hapus Tahun Ajaran dari Sesi Demo
     */
    public function destroyTahunAjaran(string $id)
    {
        $sessionDeleted = session('demo_academic_years_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_academic_years_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.tahun-ajaran')->with('info', "Tahun ajaran (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.3 - Master Data Kelas / Rombongan Belajar
     */
    public function kelas(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $units = $demo->getUnits();
        $teachers = $demo->getTeachers();

        $search = $request->get('search');
        $unitFilter = $request->get('unit', 'all');
        $gradeFilter = $request->get('grade', 'all');

        if ($search) {
            $classes = array_filter($classes, function ($c) use ($search) {
                return stripos($c['name'], $search) !== false ||
                       stripos($c['id'], $search) !== false ||
                       stripos($c['room'] ?? '', $search) !== false;
            });
        }

        if ($unitFilter !== 'all') {
            $classes = array_filter($classes, function ($c) use ($unitFilter) {
                return ($c['unit_id'] ?? '') === $unitFilter;
            });
        }

        if ($gradeFilter !== 'all') {
            $classes = array_filter($classes, function ($c) use ($gradeFilter) {
                return ($c['grade'] ?? '') === $gradeFilter;
            });
        }

        return view('master.kelas', compact('classes', 'units', 'teachers', 'search', 'unitFilter', 'gradeFilter'));
    }

    /**
     * Simpan Tambah Kelas Baru (Session-based)
     */
    public function storeKelas(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|string',
            'name' => 'required|string|max:100',
            'grade' => 'required|string|max:10',
            'homeroom_teacher_id' => 'required|string',
            'capacity' => 'required|integer|min:10|max:50',
            'room' => 'nullable|string|max:50'
        ]);

        $newId = 'CLS-' . strtoupper($validated['unit_id']) . '-' . rand(10, 99);

        $newClass = [
            'id' => $newId,
            'name' => $validated['name'],
            'unit_id' => $validated['unit_id'],
            'grade' => $validated['grade'],
            'homeroom_teacher_id' => $validated['homeroom_teacher_id'],
            'student_count' => 0,
            'capacity' => (int) $validated['capacity'],
            'room' => $validated['room'] ?: 'Ruang ' . rand(101, 305),
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_classes_added', []);
        $sessionAdded[] = $newClass;
        session(['demo_classes_added' => $sessionAdded]);

        return redirect()->route('master.kelas')->with('success', "Rombongan belajar \"{$newClass['name']}\" berhasil ditambahkan!");
    }

    /**
     * Hapus Kelas dari Sesi Demo
     */
    public function destroyKelas(string $id)
    {
        $sessionDeleted = session('demo_classes_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_classes_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.kelas')->with('info', "Kelas/Rombel (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.4 - Master Data Ruangan & Fasilitas
     */
    public function ruangan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $rooms = $demo->getFacilityRooms();
        $units = $demo->getUnits();

        $search = $request->get('search');
        $typeFilter = $request->get('type', 'all');
        $conditionFilter = $request->get('condition', 'all');

        if ($search) {
            $rooms = array_filter($rooms, function ($r) use ($search) {
                return stripos($r['name'], $search) !== false ||
                       stripos($r['location'], $search) !== false ||
                       stripos($r['id'], $search) !== false;
            });
        }

        if ($typeFilter !== 'all') {
            $rooms = array_filter($rooms, function ($r) use ($typeFilter) {
                return ($r['type'] ?? '') === $typeFilter;
            });
        }

        if ($conditionFilter !== 'all') {
            $rooms = array_filter($rooms, function ($r) use ($conditionFilter) {
                return ($r['condition'] ?? '') === $conditionFilter;
            });
        }

        return view('master.ruangan', compact('rooms', 'units', 'search', 'typeFilter', 'conditionFilter'));
    }

    /**
     * Simpan Tambah Ruangan Baru (Session-based)
     */
    public function storeRuangan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'location' => 'required|string|max:150',
            'capacity' => 'required|integer|min:1|max:1000',
            'condition' => 'required|in:baik,perbaikan,rusak',
            'unit_id' => 'required|string'
        ]);

        $newId = 'RNG-' . rand(100, 999);

        $newRoom = [
            'id' => $newId,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'location' => $validated['location'],
            'capacity' => (int) $validated['capacity'],
            'condition' => $validated['condition'],
            'unit_id' => $validated['unit_id'],
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_rooms_added', []);
        $sessionAdded[] = $newRoom;
        session(['demo_rooms_added' => $sessionAdded]);

        return redirect()->route('master.ruangan')->with('success', "Ruangan / Fasilitas \"{$newRoom['name']}\" berhasil ditambahkan!");
    }

    /**
     * Hapus Ruangan dari Sesi Demo
     */
    public function destroyRuangan(string $id)
    {
        $sessionDeleted = session('demo_rooms_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_rooms_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.ruangan')->with('info', "Ruangan (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.5 - Master Data Mata Pelajaran
     */
    public function mapel(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $subjects = $demo->getSubjects();

        $search = $request->get('search');
        $groupFilter = $request->get('group', 'all');

        if ($search) {
            $subjects = array_filter($subjects, function ($s) use ($search) {
                return stripos($s['name'], $search) !== false ||
                       stripos($s['short_name'], $search) !== false ||
                       stripos($s['id'], $search) !== false;
            });
        }

        if ($groupFilter !== 'all') {
            $subjects = array_filter($subjects, function ($s) use ($groupFilter) {
                return ($s['group'] ?? '') === $groupFilter;
            });
        }

        return view('master.mapel', compact('subjects', 'search', 'groupFilter'));
    }

    /**
     * Simpan Tambah Mata Pelajaran Baru (Session-based)
     */
    public function storeMapel(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'short_name' => 'required|string|max:30',
            'group' => 'required|in:Nasional,Kepesantrenan,Muatan Lokal',
            'kkm' => 'required|integer|min:50|max:100',
            'hours_per_week' => 'required|integer|min:1|max:12',
            'unit_target' => 'required|string|max:50'
        ]);

        $newId = 'MP-' . rand(100, 999);

        $newSubject = [
            'id' => $newId,
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'group' => $validated['group'],
            'kkm' => (int) $validated['kkm'],
            'hours_per_week' => (int) $validated['hours_per_week'],
            'unit_target' => $validated['unit_target'],
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_subjects_added', []);
        $sessionAdded[] = $newSubject;
        session(['demo_subjects_added' => $sessionAdded]);

        return redirect()->route('master.mapel')->with('success', "Mata pelajaran \"{$newSubject['name']}\" berhasil ditambahkan!");
    }

    /**
     * Hapus Mata Pelajaran dari Sesi Demo
     */
    public function destroyMapel(string $id)
    {
        $sessionDeleted = session('demo_subjects_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_subjects_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.mapel')->with('info', "Mata pelajaran (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.6 - Master Data Siswa / Buku Induk Santri
     */
    public function siswa(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();
        $units = $demo->getUnits();
        $classes = $demo->getClasses();
        $boardingRooms = $demo->getBoardingRooms();

        $search = $request->get('search');
        $unitFilter = $request->get('unit', 'all');
        $residenceFilter = $request->get('residence', 'all');
        $statusFilter = $request->get('status', 'all');

        if ($search) {
            $students = array_filter($students, function ($s) use ($search) {
                return stripos($s['name'], $search) !== false ||
                       stripos($s['nis'], $search) !== false ||
                       stripos($s['nisn'], $search) !== false ||
                       stripos($s['guardian_name'] ?? '', $search) !== false;
            });
        }

        if ($unitFilter !== 'all') {
            $students = array_filter($students, function ($s) use ($unitFilter) {
                return ($s['unit_level'] ?? '') === $unitFilter || ($s['unit_id'] ?? '') === $unitFilter;
            });
        }

        if ($residenceFilter !== 'all') {
            $isBoarding = ($residenceFilter === 'boarding');
            $students = array_filter($students, function ($s) use ($isBoarding) {
                return ($s['is_boarding'] ?? false) === $isBoarding;
            });
        }

        if ($statusFilter !== 'all') {
            $students = array_filter($students, function ($s) use ($statusFilter) {
                return ($s['status'] ?? '') === $statusFilter;
            });
        }

        return view('master.siswa', compact('students', 'units', 'classes', 'boardingRooms', 'search', 'unitFilter', 'residenceFilter', 'statusFilter'));
    }

    /**
     * Simpan Tambah Siswa Baru (Session-based)
     */
    public function storeSiswa(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|in:L,P',
            'unit_level' => 'required|in:SD,SMP,SMA',
            'class_id' => 'required|string',
            'pob' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'guardian_name' => 'required|string|max:100',
            'guardian_phone' => 'required|string|max:20',
            'is_boarding' => 'nullable',
            'boarding_room_id' => 'nullable|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $currentCount = count($demo->getStudents()) + 1;
        $studentId = 'SIS-' . str_pad($currentCount, 4, '0', STR_PAD_LEFT);
        $guardianId = 'WLI-' . str_pad($currentCount, 4, '0', STR_PAD_LEFT);
        $nis = '2026' . str_pad($currentCount, 4, '0', STR_PAD_LEFT);
        $nisn = '0089' . rand(100000, 999999);

        $isBoarding = !empty($validated['is_boarding']);

        $newStudent = [
            'id' => $studentId,
            'nis' => $nis,
            'nisn' => $nisn,
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'pob' => $validated['pob'] ?: 'Jakarta',
            'dob' => $validated['dob'] ?: '2010-01-01',
            'unit_level' => $validated['unit_level'],
            'unit_id' => 'UNIT-' . $validated['unit_level'],
            'grade' => '10',
            'class_id' => $validated['class_id'],
            'residence_type' => $isBoarding ? 'Boarding' : 'Non-Boarding (Full Day)',
            'boarding_room_id' => $isBoarding ? $validated['boarding_room_id'] : null,
            'is_boarding' => $isBoarding,
            'avatar' => $validated['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png',
            'guardian_id' => $guardianId,
            'guardian_name' => $validated['guardian_name'],
            'guardian_phone' => $validated['guardian_phone'],
            'status' => 'aktif',
            'tahfidz_juz' => 3,
            'tahfidz_last_surah' => 'Surah An-Naba',
            'tahfidz_predicate' => 'Jayyid',
            'spp_status' => 'lunas',
            'spp_nominal' => 1850000,
            'gpa' => 85.0,
            'attendance_pct' => 100.0
        ];

        $sessionAdded = session('demo_students_added', []);
        $sessionAdded[] = $newStudent;
        session(['demo_students_added' => $sessionAdded]);

        return redirect()->route('master.siswa')->with('success', "Santri baru \"{$newStudent['name']}\" (NIS: {$newStudent['nis']}) berhasil didaftarkan ke Buku Induk!");
    }

    /**
     * Hapus Santri dari Sesi Demo
     */
    public function destroySiswa(string $id)
    {
        $sessionDeleted = session('demo_students_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_students_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.siswa')->with('info', "Santri (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.7 - Master Data Guru & Pegawai
     */
    public function pegawai(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $units = $demo->getUnits();

        $search = $request->get('search');
        $unitFilter = $request->get('unit', 'all');
        $statusFilter = $request->get('status', 'all');

        if ($search) {
            $teachers = array_filter($teachers, function ($t) use ($search) {
                return stripos($t['name'], $search) !== false ||
                       stripos($t['nip'], $search) !== false ||
                       stripos($t['subject'], $search) !== false ||
                       stripos($t['position'], $search) !== false;
            });
        }

        if ($unitFilter !== 'all') {
            $teachers = array_filter($teachers, function ($t) use ($unitFilter) {
                return ($t['unit_id'] ?? '') === $unitFilter;
            });
        }

        if ($statusFilter !== 'all') {
            $teachers = array_filter($teachers, function ($t) use ($statusFilter) {
                return ($t['employment_status'] ?? '') === $statusFilter;
            });
        }

        return view('master.pegawai', compact('teachers', 'units', 'search', 'unitFilter', 'statusFilter'));
    }

    /**
     * Simpan Tambah Guru/Pegawai Baru (Session-based)
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
            'email' => 'nullable|email|max:100'
        ]);

        $demo = DemoDataManager::getInstance();
        $count = count($demo->getTeachers()) + 1;
        $id = 'GUR-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        $nip = $validated['nip'] ?: '1988' . rand(1000, 9999) . '201501100' . $count;

        $newTeacher = [
            'id' => $id,
            'nip' => $nip,
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'gender' => $validated['gender'],
            'email' => $validated['email'] ?: strtolower(str_replace([' ', '.', ','], '', $validated['name'])) . '@alia.sch.id',
            'employment_status' => $validated['employment_status'],
            'position' => $validated['position'],
            'unit_id' => $validated['unit_id'],
            'avatar' => $validated['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png',
            'phone' => $validated['phone'],
            'education' => 'S1 / S2 Pendidikan',
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_teachers_added', []);
        $sessionAdded[] = $newTeacher;
        session(['demo_teachers_added' => $sessionAdded]);

        return redirect()->route('master.pegawai')->with('success', "Data pendidik \"{$newTeacher['name']}\" (NIP: {$newTeacher['nip']}) berhasil ditambahkan!");
    }

    /**
     * Hapus Guru/Pegawai dari Sesi Demo
     */
    public function destroyPegawai(string $id)
    {
        $sessionDeleted = session('demo_teachers_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_teachers_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.pegawai')->with('info', "Pegawai/Guru (ID: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T02.8 - Master Data Orang Tua / Wali
     */
    public function orangTua(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $guardians = $demo->getGuardians();
        $students = $demo->getStudents();

        $search = $request->get('search');

        if ($search) {
            $guardians = array_filter($guardians, function ($g) use ($search) {
                return stripos($g['name'], $search) !== false ||
                       stripos($g['phone'], $search) !== false ||
                       stripos($g['student_name'] ?? '', $search) !== false ||
                       stripos($g['occupation'] ?? '', $search) !== false;
            });
        }

        return view('master.orang-tua', compact('guardians', 'students', 'search'));
    }

    /**
     * Simpan Tambah Orang Tua / Wali Baru (Session-based)
     */
    public function storeOrangTua(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'occupation' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'student_id' => 'required|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $student = $demo->findStudent($validated['student_id']);
        $count = count($demo->getGuardians()) + 1;
        $id = 'WLI-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $newGuardian = [
            'id' => $id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?: strtolower(str_replace([' ', '.', ','], '', $validated['name'])) . '@gmail.com',
            'occupation' => $validated['occupation'],
            'address' => $validated['address'],
            'student_id' => $validated['student_id'],
            'student_name' => $student['name'] ?? 'Santri Alia'
        ];

        $sessionAdded = session('demo_guardians_added', []);
        $sessionAdded[] = $newGuardian;
        session(['demo_guardians_added' => $sessionAdded]);

        return redirect()->route('master.orang-tua')->with('success', "Data Orang Tua/Wali \"{$newGuardian['name']}\" berhasil ditambahkan!");
    }

    /**
     * Hapus Orang Tua/Wali dari Sesi Demo
     */
    public function destroyOrangTua(string $id)
    {
        $sessionDeleted = session('demo_guardians_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_guardians_deleted' => $sessionDeleted]);
        }

        return redirect()->route('master.orang-tua')->with('info', "Data Orang Tua/Wali (ID: {$id}) berhasil dihapus dari sesi demo.");
    }
}

