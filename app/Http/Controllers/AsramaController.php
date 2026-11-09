<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class AsramaController extends Controller
{
    /**
     * T08.1 — Boarding Buildings & Rooms (Gedung & Kamar Asrama Santri)
     */
    public function kamar(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $buildings = $demo->getBoardingBuildings();
        $rooms = $demo->getBoardingRooms();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $activeTab = $request->query('tab', 'kamar'); // 'kamar' atau 'gedung'
        $buildingFilter = $request->query('building');
        $genderFilter = $request->query('gender');
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

        $buildingMap = [];
        foreach ($buildings as $b) {
            $buildingMap[$b['id']] = $b;
        }

        // Filter Rooms
        $filteredRooms = array_filter($rooms, function ($r) use ($buildingFilter, $genderFilter, $search) {
            if ($buildingFilter && $r['building_id'] !== $buildingFilter) {
                return false;
            }

            if ($genderFilter && $r['gender'] !== $genderFilter) {
                return false;
            }

            if ($search) {
                $matchName = str_contains(strtolower($r['name']), $search);
                $matchNumber = str_contains(strtolower($r['number'] ?? ''), $search);
                $matchBld = str_contains(strtolower($r['building_name'] ?? ''), $search);
                if (!$matchName && !$matchNumber && !$matchBld) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalGedung = count($buildings);
        $totalKamar = count($rooms);
        $totalKapasitasBed = array_sum(array_column($rooms, 'capacity'));
        $totalTerisi = 0;
        foreach ($rooms as $r) {
            $totalTerisi += count($r['resident_student_ids'] ?? []);
        }
        $totalSisaBed = max(0, $totalKapasitasBed - $totalTerisi);
        $okupansiPersen = $totalKapasitasBed > 0 ? round(($totalTerisi / $totalKapasitasBed) * 100) : 0;

        return view('asrama.kamar', [
            'rooms' => $filteredRooms,
            'allRooms' => $rooms,
            'buildings' => $buildings,
            'buildingMap' => $buildingMap,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'activeTab' => $activeTab,
            'buildingFilter' => $buildingFilter,
            'genderFilter' => $genderFilter,
            'search' => $search,
            'totalGedung' => $totalGedung,
            'totalKamar' => $totalKamar,
            'totalKapasitasBed' => $totalKapasitasBed,
            'totalTerisi' => $totalTerisi,
            'totalSisaBed' => $totalSisaBed,
            'okupansiPersen' => $okupansiPersen
        ]);
    }

    /**
     * Tambah Kamar Asrama Baru (Session-based)
     */
    public function storeKamar(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|string',
            'name' => 'required|string|max:150',
            'number' => 'required|string|max:20',
            'floor' => 'required|integer|min:1|max:10',
            'capacity' => 'required|integer|min:1|max:30',
            'facilities' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $bld = $demo->findBoardingBuilding($validated['building_id']);
        $rooms = $demo->getBoardingRooms();
        $newId = 'KMR-' . strtoupper(substr($validated['building_id'], -3)) . '-' . str_pad((string)(count($rooms) + 1), 2, '0', STR_PAD_LEFT);

        $facilitiesArray = !empty($validated['facilities'])
            ? array_map('trim', explode(',', $validated['facilities']))
            : ['Ranjang Tingkat', 'Lemari Pribadi', 'Kipas Angin'];

        $newRecord = [
            'id' => $newId,
            'building_id' => $validated['building_id'],
            'building_name' => $bld['name'] ?? 'Gedung Asrama',
            'floor' => (int) $validated['floor'],
            'number' => $validated['number'],
            'name' => $validated['name'],
            'gender' => $bld['gender'] ?? 'L',
            'category' => ($bld['gender'] ?? 'L') === 'L' ? 'Putra' : 'Putri',
            'capacity' => (int) $validated['capacity'],
            'resident_student_ids' => [],
            'facilities' => $facilitiesArray,
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_boarding_rooms_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_boarding_rooms_added' => $sessionAdded]);

        return redirect()->route('asrama.kamar', ['tab' => 'kamar'])
            ->with('success', "Kamar asrama [{$validated['name']}] berhasil didaftarkan!");
    }

    /**
     * Tambah Gedung Asrama Baru (Session-based)
     */
    public function storeGedung(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:20',
            'gender' => 'required|in:L,P',
            'total_floors' => 'required|integer|min:1|max:10',
            'supervisor_name' => 'required|string|max:120',
            'supervisor_phone' => 'required|string|max:30',
            'description' => 'required|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $buildings = $demo->getBoardingBuildings();
        $newId = 'BLD-' . str_pad((string)(count($buildings) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'name' => $validated['name'],
            'code' => $validated['code'],
            'gender' => $validated['gender'],
            'total_floors' => (int) $validated['total_floors'],
            'supervisor_name' => $validated['supervisor_name'],
            'supervisor_phone' => $validated['supervisor_phone'],
            'description' => $validated['description'],
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_boarding_buildings_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_boarding_buildings_added' => $sessionAdded]);

        return redirect()->route('asrama.kamar', ['tab' => 'gedung'])
            ->with('success', "Gedung asrama [{$validated['name']}] berhasil ditambahkan!");
    }

    /**
     * Hapus Kamar Asrama (Session-based)
     */
    public function destroyKamar(string $id)
    {
        $sessionDeleted = session('demo_boarding_rooms_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_boarding_rooms_deleted' => $sessionDeleted]);
        }

        return redirect()->route('asrama.kamar', ['tab' => 'kamar'])
            ->with('success', "Kamar asrama [{$id}] berhasil dihapus!");
    }

    /**
     * T08.2 — Resident Assignment (Alokasi & Penempatan Kamar Santri)
     */
    public function penempatan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $assignments = $demo->getResidentAssignments();
        $rooms = $demo->getBoardingRooms();
        $buildings = $demo->getBoardingBuildings();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        $buildingFilter = $request->query('building');
        $roomFilter = $request->query('room');
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

        $roomMap = [];
        foreach ($rooms as $r) {
            $roomMap[$r['id']] = $r;
        }

        $buildingMap = [];
        foreach ($buildings as $b) {
            $buildingMap[$b['id']] = $b;
        }

        $filteredAssignments = array_filter($assignments, function ($a) use ($buildingFilter, $roomFilter, $statusFilter, $search, $studentMap, $roomMap) {
            $rm = $roomMap[$a['room_id']] ?? null;

            if ($buildingFilter && ($rm['building_id'] ?? '') !== $buildingFilter) {
                return false;
            }

            if ($roomFilter && $a['room_id'] !== $roomFilter) {
                return false;
            }

            if ($statusFilter && $a['status'] !== $statusFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$a['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchRoom = $rm && str_contains(strtolower($rm['name']), $search);
                $matchBed = str_contains(strtolower($a['bed_number']), $search);
                $matchWardrobe = str_contains(strtolower($a['wardrobe_number']), $search);
                if (!$matchStudent && !$matchNis && !$matchRoom && !$matchBed && !$matchWardrobe) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalAlokasi = count($assignments);
        $totalSantriMukim = count(array_filter($assignments, fn($a) => $a['status'] === 'aktif'));
        $totalSantri = count($students);
        $totalBelumAlokasi = max(0, $totalSantri - $totalSantriMukim);
        $totalKapasitasBed = array_sum(array_column($rooms, 'capacity'));
        $sisaBed = max(0, $totalKapasitasBed - $totalSantriMukim);

        // Santri yang belum memiliki alokasi aktif
        $activeStudentIds = array_column(array_filter($assignments, fn($a) => $a['status'] === 'aktif'), 'student_id');
        $unassignedStudents = array_filter($students, fn($s) => !in_array($s['id'], $activeStudentIds));

        return view('asrama.penempatan', [
            'assignments' => $filteredAssignments,
            'allAssignments' => $assignments,
            'rooms' => $rooms,
            'buildings' => $buildings,
            'students' => $students,
            'unassignedStudents' => $unassignedStudents,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'roomMap' => $roomMap,
            'buildingMap' => $buildingMap,
            'buildingFilter' => $buildingFilter,
            'roomFilter' => $roomFilter,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'totalAlokasi' => $totalAlokasi,
            'totalSantriMukim' => $totalSantriMukim,
            'totalBelumAlokasi' => $totalBelumAlokasi,
            'totalKapasitasBed' => $totalKapasitasBed,
            'sisaBed' => $sisaBed
        ]);
    }

    /**
     * Check-in / Alokasi Santri ke Kamar (Session-based)
     */
    public function storePenempatan(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'room_id' => 'required|string',
            'bed_number' => 'required|string|max:50',
            'wardrobe_number' => 'required|string|max:50',
            'checkin_date' => 'required|date',
            'assigned_by' => 'required|string|max:120',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $room = $demo->findBoardingRoom($validated['room_id']);

        if (!$room) {
            return redirect()->route('asrama.penempatan')->with('error', 'Kamar asrama tidak ditemukan!');
        }

        $sessionAssignments = session('demo_resident_assignments_added', []);
        $allAssignments = $demo->getResidentAssignments();

        // Validasi apakah santri sudah aktif di kamar lain
        foreach ($allAssignments as $a) {
            if ($a['student_id'] === $validated['student_id'] && $a['status'] === 'aktif') {
                return redirect()->route('asrama.penempatan')->with('error', 'Santri sudah aktif teralokasi di kamar lain! Lakukan pindah kamar jika ingin merelokasi.');
            }
        }

        $newId = 'ASN-' . str_pad((string)(count($allAssignments) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'student_id' => $validated['student_id'],
            'room_id' => $validated['room_id'],
            'bed_number' => $validated['bed_number'],
            'wardrobe_number' => $validated['wardrobe_number'],
            'checkin_date' => $validated['checkin_date'],
            'checkout_date' => null,
            'status' => 'aktif',
            'assigned_by' => $validated['assigned_by'],
            'notes' => $validated['notes'] ?? 'Check-in santri baru asrama.'
        ];

        $sessionAssignments[] = $newRecord;
        session(['demo_resident_assignments_added' => $sessionAssignments]);

        // Perbarui daftar penghuni di room
        $sessionRoomsUpdated = session('demo_boarding_rooms_updated', []);
        $currentRoom = $sessionRoomsUpdated[$room['id']] ?? $room;
        $residentIds = $currentRoom['resident_student_ids'] ?? [];
        if (!in_array($validated['student_id'], $residentIds)) {
            $residentIds[] = $validated['student_id'];
            $sessionRoomsUpdated[$room['id']] = array_merge($currentRoom, ['resident_student_ids' => $residentIds]);
            session(['demo_boarding_rooms_updated' => $sessionRoomsUpdated]);
        }

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('asrama.penempatan')
            ->with('success', "Santri " . ($std['name'] ?? '') . " berhasil dialokasikan ke {$room['name']} ({$validated['bed_number']})!");
    }

    /**
     * Pindah Kamar Santri (Session-based)
     */
    public function transferKamar(Request $request, string $id)
    {
        $validated = $request->validate([
            'target_room_id' => 'required|string',
            'bed_number' => 'required|string|max:50',
            'wardrobe_number' => 'required|string|max:50',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $assignment = $demo->findResidentAssignment($id);

        if (!$assignment) {
            return redirect()->route('asrama.penempatan')->with('error', 'Rekam alokasi santri tidak ditemukan!');
        }

        $targetRoom = $demo->findBoardingRoom($validated['target_room_id']);
        if (!$targetRoom) {
            return redirect()->route('asrama.penempatan')->with('error', 'Kamar tujuan tidak ditemukan!');
        }

        $oldRoomId = $assignment['room_id'];
        $oldRoom = $demo->findBoardingRoom($oldRoomId);

        // Update assignment
        $sessionUpdated = session('demo_resident_assignments_updated', []);
        $sessionUpdated[$id] = array_merge($assignment, [
            'room_id' => $validated['target_room_id'],
            'bed_number' => $validated['bed_number'],
            'wardrobe_number' => $validated['wardrobe_number'],
            'notes' => !empty($validated['notes']) ? $validated['notes'] : ("Pindah dari " . ($oldRoom['name'] ?? 'kamar lama') . " ke " . $targetRoom['name'])
        ]);
        session(['demo_resident_assignments_updated' => $sessionUpdated]);

        // Sinkronisasi room penghuni
        $sessionRoomsUpdated = session('demo_boarding_rooms_updated', []);

        // Hapus dari room lama
        if ($oldRoom) {
            $currOld = $sessionRoomsUpdated[$oldRoomId] ?? $oldRoom;
            $resOld = array_filter($currOld['resident_student_ids'] ?? [], fn($sid) => $sid !== $assignment['student_id']);
            $sessionRoomsUpdated[$oldRoomId] = array_merge($currOld, ['resident_student_ids' => array_values($resOld)]);
        }

        // Tambah ke room baru
        $currNew = $sessionRoomsUpdated[$validated['target_room_id']] ?? $targetRoom;
        $resNew = $currNew['resident_student_ids'] ?? [];
        if (!in_array($assignment['student_id'], $resNew)) {
            $resNew[] = $assignment['student_id'];
            $sessionRoomsUpdated[$validated['target_room_id']] = array_merge($currNew, ['resident_student_ids' => $resNew]);
        }
        session(['demo_boarding_rooms_updated' => $sessionRoomsUpdated]);

        $std = $demo->findStudent($assignment['student_id']);

        return redirect()->route('asrama.penempatan')
            ->with('success', "Relokasi santri " . ($std['name'] ?? '') . " ke {$targetRoom['name']} berhasil diproses!");
    }

    /**
     * Check-out Santri dari Asrama (Session-based)
     */
    public function checkoutSantri(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $assignment = $demo->findResidentAssignment($id);

        if (!$assignment) {
            return redirect()->route('asrama.penempatan')->with('error', 'Rekam alokasi santri tidak ditemukan!');
        }

        $sessionUpdated = session('demo_resident_assignments_updated', []);
        $sessionUpdated[$id] = array_merge($assignment, [
            'status' => 'checkout',
            'checkout_date' => date('Y-m-d')
        ]);
        session(['demo_resident_assignments_updated' => $sessionUpdated]);

        // Hapus dari daftar penghuni kamar
        $room = $demo->findBoardingRoom($assignment['room_id']);
        if ($room) {
            $sessionRoomsUpdated = session('demo_boarding_rooms_updated', []);
            $currRoom = $sessionRoomsUpdated[$room['id']] ?? $room;
            $res = array_filter($currRoom['resident_student_ids'] ?? [], fn($sid) => $sid !== $assignment['student_id']);
            $sessionRoomsUpdated[$room['id']] = array_merge($currRoom, ['resident_student_ids' => array_values($res)]);
            session(['demo_boarding_rooms_updated' => $sessionRoomsUpdated]);
        }

        $std = $demo->findStudent($assignment['student_id']);

        return redirect()->route('asrama.penempatan')
            ->with('success', "Santri " . ($std['name'] ?? '') . " telah di-checkout dari asrama.");
    }

    /**
     * T08.3 — Daily Activity Schedule (Jadwal Aktivitas Harian Santri Asrama)
     */
    public function aktivitas(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $activities = $demo->getBoardingActivities();

        $categoryFilter = $request->query('category');
        $search = strtolower(trim($request->query('q', '')));

        $filteredActivities = array_filter($activities, function ($act) use ($categoryFilter, $search) {
            if ($categoryFilter && $act['category'] !== $categoryFilter) {
                return false;
            }

            if ($search) {
                $matchName = str_contains(strtolower($act['name']), $search);
                $matchLoc = str_contains(strtolower($act['location']), $search);
                $matchSupervisor = str_contains(strtolower($act['supervisor']), $search);
                $matchDesc = str_contains(strtolower($act['description']), $search);
                if (!$matchName && !$matchLoc && !$matchSupervisor && !$matchDesc) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalAktivitas = count($activities);
        $totalWajib = count(array_filter($activities, fn($a) => !empty($a['mandatory'])));
        $totalIbadah = count(array_filter($activities, fn($a) => $a['category'] === 'Ibadah & Spiritual'));
        $totalDiniyahTahfidz = count(array_filter($activities, fn($a) => in_array($a['category'], ['Tahfidz & Al-Qur\'an', 'Diniyah & Turats'])));

        $categories = array_values(array_unique(array_column($activities, 'category')));

        return view('asrama.aktivitas', [
            'activities' => $filteredActivities,
            'allActivities' => $activities,
            'categories' => $categories,
            'categoryFilter' => $categoryFilter,
            'search' => $search,
            'totalAktivitas' => $totalAktivitas,
            'totalWajib' => $totalWajib,
            'totalIbadah' => $totalIbadah,
            'totalDiniyahTahfidz' => $totalDiniyahTahfidz
        ]);
    }

    /**
     * Tambah Agenda Aktivitas Harian Baru (Session-based)
     */
    public function storeAktivitas(Request $request)
    {
        $validated = $request->validate([
            'time_start' => 'required|string|max:10',
            'time_end' => 'required|string|max:10',
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:80',
            'days' => 'required|string|max:80',
            'location' => 'required|string|max:120',
            'supervisor' => 'required|string|max:120',
            'description' => 'required|string|max:400',
            'mandatory' => 'nullable|boolean'
        ]);

        $demo = DemoDataManager::getInstance();
        $activities = $demo->getBoardingActivities();
        $newId = 'ACT-' . str_pad((string)(count($activities) + 1), 3, '0', STR_PAD_LEFT);

        $newRecord = [
            'id' => $newId,
            'time_start' => $validated['time_start'],
            'time_end' => $validated['time_end'],
            'name' => $validated['name'],
            'category' => $validated['category'],
            'days' => $validated['days'],
            'location' => $validated['location'],
            'supervisor' => $validated['supervisor'],
            'mandatory' => !empty($request->input('mandatory')),
            'description' => $validated['description']
        ];

        $sessionAdded = session('demo_boarding_activities_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_boarding_activities_added' => $sessionAdded]);

        return redirect()->route('asrama.aktivitas')
            ->with('success', "Aktivitas asrama [{$validated['name']}] berhasil ditambahkan!");
    }

    /**
     * Hapus Aktivitas Asrama (Session-based)
     */
    public function destroyAktivitas(string $id)
    {
        $sessionDeleted = session('demo_boarding_activities_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_boarding_activities_deleted' => $sessionDeleted]);
        }

        return redirect()->route('asrama.aktivitas')
            ->with('success', "Jadwal aktivitas [{$id}] berhasil dihapus!");
    }

    /**
     * T08.4 — Leave Permission Workflow (Alur Perizinan Keluar/Pulang Santri Asrama)
     */
    public function perizinan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $permissions = $demo->getLeavePermissions();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();
        $rooms = $demo->getBoardingRooms();

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

        $roomMap = [];
        foreach ($rooms as $r) {
            $roomMap[$r['id']] = $r;
        }

        $filteredPermissions = array_filter($permissions, function ($p) use ($typeFilter, $statusFilter, $search, $studentMap) {
            if ($typeFilter && $p['type'] !== $typeFilter) {
                return false;
            }

            if ($statusFilter && $p['approval_status'] !== $statusFilter) {
                return false;
            }

            if ($search) {
                $std = $studentMap[$p['student_id']] ?? null;
                $matchStudent = $std && str_contains(strtolower($std['name']), $search);
                $matchNis = $std && str_contains(strtolower($std['nis'] ?? ''), $search);
                $matchPermit = str_contains(strtolower($p['permit_number']), $search);
                $matchReason = str_contains(strtolower($p['reason']), $search);
                $matchPickup = str_contains(strtolower($p['pickup_person']), $search);
                if (!$matchStudent && !$matchNis && !$matchPermit && !$matchReason && !$matchPickup) {
                    return false;
                }
            }

            return true;
        });

        // Metrik statistik
        $totalIzin = count($permissions);
        $totalDiajukan = count(array_filter($permissions, fn($p) => $p['approval_status'] === 'diajukan'));
        $totalDisetujui = count(array_filter($permissions, fn($p) => in_array($p['approval_status'], ['disetujui'])));
        $totalKembali = count(array_filter($permissions, fn($p) => $p['approval_status'] === 'kembali'));

        return view('asrama.perizinan', [
            'permissions' => $filteredPermissions,
            'allPermissions' => $permissions,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'roomMap' => $roomMap,
            'typeFilter' => $typeFilter,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'totalIzin' => $totalIzin,
            'totalDiajukan' => $totalDiajukan,
            'totalDisetujui' => $totalDisetujui,
            'totalKembali' => $totalKembali
        ]);
    }

    /**
     * Ajukan Izin Baru (Session-based)
     */
    public function storePerizinan(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'type' => 'required|in:keluar_sebentar,pulang,darurat',
            'reason' => 'required|string|max:400',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'pickup_person' => 'required|string|max:120',
            'pickup_phone' => 'required|string|max:30',
            'approver_name' => 'required|string|max:120',
            'notes' => 'nullable|string|max:300'
        ]);

        $demo = DemoDataManager::getInstance();
        $permissions = $demo->getLeavePermissions();
        $newId = 'IZN-' . str_pad((string)(count($permissions) + 1), 3, '0', STR_PAD_LEFT);
        $permitNumber = 'IZN/ASR/' . date('Y/m') . '/' . str_pad((string)(count($permissions) + 1), 3, '0', STR_PAD_LEFT);
        $gatePassCode = 'GP-' . rand(1000, 9999) . '-' . strtoupper(substr(md5($newId), 0, 2));

        $newRecord = [
            'id' => $newId,
            'permit_number' => $permitNumber,
            'student_id' => $validated['student_id'],
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'pickup_person' => $validated['pickup_person'],
            'pickup_phone' => $validated['pickup_phone'],
            'approval_status' => 'disetujui',
            'approver_name' => $validated['approver_name'],
            'security_check_out' => date('Y-m-d H:i:s'),
            'security_check_in' => null,
            'late_minutes' => 0,
            'gate_pass_code' => $gatePassCode,
            'notes' => $validated['notes'] ?? 'Izin telah diverifikasi oleh musyrif asrama.'
        ];

        $sessionAdded = session('demo_leave_permissions_added', []);
        $sessionAdded[] = $newRecord;
        session(['demo_leave_permissions_added' => $sessionAdded]);

        $std = $demo->findStudent($validated['student_id']);

        return redirect()->route('asrama.perizinan')
            ->with('success', "Surat izin santri " . ($std['name'] ?? '') . " [{$permitNumber}] berhasil disetujui & Gate Pass aktif!");
    }

    /**
     * Konfirmasi Kepulangan Santri ke Asrama (Check-In Pos Satpam)
     */
    public function checkInPerizinan(Request $request, string $id)
    {
        $demo = DemoDataManager::getInstance();
        $permission = $demo->findLeavePermission($id);

        if (!$permission) {
            return redirect()->route('asrama.perizinan')->with('error', 'Data surat izin tidak ditemukan!');
        }

        $sessionUpdated = session('demo_leave_permissions_updated', []);
        $sessionUpdated[$id] = array_merge($permission, [
            'approval_status' => 'kembali',
            'security_check_in' => date('Y-m-d H:i:s'),
            'notes' => ($permission['notes'] ?? '') . ' (Telah kembali di asrama dengan selamat pkl ' . date('H:i') . ' WIB).'
        ]);
        session(['demo_leave_permissions_updated' => $sessionUpdated]);

        $std = $demo->findStudent($permission['student_id']);

        return redirect()->route('asrama.perizinan')
            ->with('success', "Santri " . ($std['name'] ?? '') . " terkonfirmasi telah kembali di asrama!");
    }

    /**
     * Hapus Berkas Izin (Session-based)
     */
    public function destroyPerizinan(string $id)
    {
        $sessionDeleted = session('demo_leave_permissions_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_leave_permissions_deleted' => $sessionDeleted]);
        }

        return redirect()->route('asrama.perizinan')
            ->with('success', "Berkas perizinan [{$id}] berhasil dihapus!");
    }

    /**
     * T08.5 — Attendance & Monitoring (Presensi Shalat Berjamaah & Keberadaan Asrama)
     */
    public function presensi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $attendances = $demo->getBoardingAttendance();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();
        $rooms = $demo->getBoardingRooms();

        $selectedDate = $request->query('date', date('Y-m-d'));
        $selectedSession = $request->query('session', 'Subuh'); // Subuh, Dzuhur, Ashar, Maghrib, Isya, Qiyamul Lail, Malam (Naum)
        $roomFilter = $request->query('room');
        $statusFilter = $request->query('status');

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $roomMap = [];
        foreach ($rooms as $r) {
            $roomMap[$r['id']] = $r;
        }

        // Filter Presensi untuk tanggal & sesi terpilih
        $filtered = array_filter($attendances, function ($a) use ($selectedDate, $selectedSession, $roomFilter, $statusFilter) {
            if ($a['date'] !== $selectedDate) {
                return false;
            }

            if ($a['session'] !== $selectedSession) {
                return false;
            }

            if ($roomFilter && $a['room_id'] !== $roomFilter) {
                return false;
            }

            if ($statusFilter && $a['status'] !== $statusFilter) {
                return false;
            }

            return true;
        });

        // Santri mukim aktif yang harus diabsen
        $activeAssignments = array_filter($demo->getResidentAssignments(), fn($asn) => $asn['status'] === 'aktif');
        $residentStudentIds = array_column($activeAssignments, 'student_id');

        // Metrik statistik sesi ini
        $totalTercatat = count($filtered);
        $totalHadir = count(array_filter($filtered, fn($a) => $a['status'] === 'hadir'));
        $totalSakit = count(array_filter($filtered, fn($a) => $a['status'] === 'sakit'));
        $totalIzin = count(array_filter($filtered, fn($a) => $a['status'] === 'izin'));
        $totalAlpa = count(array_filter($filtered, fn($a) => in_array($a['status'], ['alpa', 'terlambat'])));

        $availableSessions = ['Qiyamul Lail', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya', 'Malam (Naum)'];

        return view('asrama.presensi', [
            'attendances' => $filtered,
            'allAttendances' => $attendances,
            'selectedDate' => $selectedDate,
            'selectedSession' => $selectedSession,
            'roomFilter' => $roomFilter,
            'statusFilter' => $statusFilter,
            'availableSessions' => $availableSessions,
            'rooms' => $rooms,
            'roomMap' => $roomMap,
            'students' => $students,
            'studentMap' => $studentMap,
            'classMap' => $classMap,
            'activeAssignments' => $activeAssignments,
            'totalTercatat' => $totalTercatat,
            'totalHadir' => $totalHadir,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalAlpa' => $totalAlpa
        ]);
    }

    /**
     * Simpan / Batch Update Presensi Sesi Asrama (Session-based)
     */
    public function storePresensi(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'session' => 'required|string|max:50',
            'supervisor_name' => 'required|string|max:120',
            'attendance' => 'required|array' // student_id => ['status' => ..., 'notes' => ...]
        ]);

        $demo = DemoDataManager::getInstance();
        $existing = $demo->getBoardingAttendance();
        $assignments = $demo->getResidentAssignments();
        
        $studentRoomMap = [];
        foreach ($assignments as $asn) {
            $studentRoomMap[$asn['student_id']] = $asn['room_id'];
        }

        $sessionAdded = session('demo_boarding_attendance_added', []);
        $sessionUpdated = session('demo_boarding_attendance_updated', []);

        foreach ($validated['attendance'] as $studentId => $item) {
            $status = $item['status'] ?? 'hadir';
            $notes = $item['notes'] ?? '-';

            // Cari apakah sudah ada data untuk student_id, date, dan session ini
            $foundId = null;
            foreach ($existing as $att) {
                if ($att['student_id'] === $studentId && $att['date'] === $validated['date'] && $att['session'] === $validated['session']) {
                    $foundId = $att['id'];
                    break;
                }
            }

            if ($foundId) {
                $sessionUpdated[$foundId] = [
                    'status' => $status,
                    'supervisor_name' => $validated['supervisor_name'],
                    'notes' => $notes
                ];
            } else {
                $newId = 'BAT-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);
                $sessionAdded[] = [
                    'id' => $newId,
                    'date' => $validated['date'],
                    'session' => $validated['session'],
                    'student_id' => $studentId,
                    'room_id' => $studentRoomMap[$studentId] ?? 'KMR-A01',
                    'status' => $status,
                    'location' => 'Masjid Kampus Alia',
                    'supervisor_name' => $validated['supervisor_name'],
                    'notes' => $notes
                ];
            }
        }

        session([
            'demo_boarding_attendance_added' => $sessionAdded,
            'demo_boarding_attendance_updated' => $sessionUpdated
        ]);

        return redirect()->route('asrama.presensi', ['date' => $validated['date'], 'session' => $validated['session']])
            ->with('success', "Presensi santri asrama sesi [{$validated['session']}] tanggal " . date('d/m/Y', strtotime($validated['date'])) . " berhasil disimpan!");
    }

    /**
     * Quick Action: Hadirkan Semua Santri pada Sesi Terpilih (Session-based)
     */
    public function markAllPresent(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'session' => 'required|string|max:50'
        ]);

        $demo = DemoDataManager::getInstance();
        $assignments = array_filter($demo->getResidentAssignments(), fn($a) => $a['status'] === 'aktif');
        $existing = $demo->getBoardingAttendance();

        $sessionAdded = session('demo_boarding_attendance_added', []);
        $sessionUpdated = session('demo_boarding_attendance_updated', []);

        foreach ($assignments as $asn) {
            $sid = $asn['student_id'];
            $foundId = null;
            foreach ($existing as $att) {
                if ($att['student_id'] === $sid && $att['date'] === $validated['date'] && $att['session'] === $validated['session']) {
                    $foundId = $att['id'];
                    break;
                }
            }

            if ($foundId) {
                $sessionUpdated[$foundId] = [
                    'status' => 'hadir',
                    'notes' => 'Tepat waktu mengikuti jamaah.'
                ];
            } else {
                $newId = 'BAT-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);
                $sessionAdded[] = [
                    'id' => $newId,
                    'date' => $validated['date'],
                    'session' => $validated['session'],
                    'student_id' => $sid,
                    'room_id' => $asn['room_id'],
                    'status' => 'hadir',
                    'location' => 'Masjid Kampus Alia',
                    'supervisor_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                    'notes' => 'Tepat waktu mengikuti jamaah.'
                ];
            }
        }

        session([
            'demo_boarding_attendance_added' => $sessionAdded,
            'demo_boarding_attendance_updated' => $sessionUpdated
        ]);

        return redirect()->route('asrama.presensi', ['date' => $validated['date'], 'session' => $validated['session']])
            ->with('success', "Seluruh santri mukim aktif berhasil ditandai HADIR pada sesi [{$validated['session']}]!");
    }

    /**
     * T08.6 — Meals / Consumption (Manajemen Katering & Dapur Asrama)
     */
    public function katering(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $allMeals = $demo->getBoardingMeals();

        $dayFilter = $request->query('day');
        $timeFilter = $request->query('meal_time');
        $search = strtolower(trim($request->query('q', '')));

        // Filter list
        $meals = array_filter($allMeals, function ($meal) use ($dayFilter, $timeFilter, $search) {
            if ($dayFilter && $meal['day'] !== $dayFilter) {
                return false;
            }

            if ($timeFilter && $meal['meal_time'] !== $timeFilter) {
                return false;
            }

            if ($search) {
                $haystack = strtolower(
                    $meal['menu_title'] . ' ' .
                    $meal['main_dish'] . ' ' .
                    $meal['side_dish'] . ' ' .
                    $meal['vegetable'] . ' ' .
                    $meal['fruit_drink'] . ' ' .
                    $meal['nutrition_highlight'] . ' ' .
                    $meal['chef_in_charge']
                );
                if (strpos($haystack, $search) === false) {
                    return false;
                }
            }

            return true;
        });

        // Grouping by Day for Weekly Schedule View
        $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
        $mealsByDay = [];
        foreach ($daysList as $d) {
            $mealsByDay[$d] = [
                'Pagi' => null,
                'Siang' => null,
                'Malam' => null,
            ];
        }

        foreach ($allMeals as $m) {
            if (isset($mealsByDay[$m['day']][$m['meal_time']])) {
                $mealsByDay[$m['day']][$m['meal_time']] = $m;
            }
        }

        // Metrics
        $totalVariations = count($allMeals);
        $totalBreakfast = count(array_filter($allMeals, fn($m) => $m['meal_time'] === 'Pagi'));
        $totalLunch = count(array_filter($allMeals, fn($m) => $m['meal_time'] === 'Siang'));
        $totalDinner = count(array_filter($allMeals, fn($m) => $m['meal_time'] === 'Malam'));

        // Average Calories
        $avgCalories = $totalVariations > 0 ? round(array_sum(array_column($allMeals, 'calories')) / $totalVariations) : 0;

        return view('asrama.katering', compact(
            'meals',
            'allMeals',
            'mealsByDay',
            'daysList',
            'dayFilter',
            'timeFilter',
            'search',
            'totalVariations',
            'totalBreakfast',
            'totalLunch',
            'totalDinner',
            'avgCalories'
        ));
    }

    /**
     * Store new meal item (Session-backed)
     */
    public function storeKatering(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|string',
            'meal_time' => 'required|string|in:Pagi,Siang,Malam',
            'menu_title' => 'required|string|max:150',
            'time_range' => 'nullable|string|max:50',
            'main_dish' => 'required|string|max:150',
            'side_dish' => 'required|string|max:150',
            'vegetable' => 'required|string|max:150',
            'fruit_drink' => 'required|string|max:150',
            'calories' => 'required|integer|min:100|max:2000',
            'nutrition_highlight' => 'required|string|max:255',
            'allergy_notes' => 'nullable|string|max:255',
            'chef_in_charge' => 'required|string|max:100',
            'portion_count' => 'required|integer|min:1',
            'halal_status' => 'nullable|string',
        ]);

        $demo = DemoDataManager::getInstance();
        $existing = $demo->getBoardingMeals();

        $sessionAdded = session('demo_boarding_meals_added', []);
        $newId = 'MEL-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);

        // Fallback default time_range if empty
        if (empty($validated['time_range'])) {
            if ($validated['meal_time'] === 'Pagi') $validated['time_range'] = '06:30 - 07:15 WIB';
            elseif ($validated['meal_time'] === 'Siang') $validated['time_range'] = '12:15 - 13:15 WIB';
            else $validated['time_range'] = '19:45 - 20:30 WIB';
        }

        $newItem = [
            'id' => $newId,
            'day' => $validated['day'],
            'meal_time' => $validated['meal_time'],
            'time_range' => $validated['time_range'],
            'menu_title' => $validated['menu_title'],
            'main_dish' => $validated['main_dish'],
            'side_dish' => $validated['side_dish'],
            'vegetable' => $validated['vegetable'],
            'fruit_drink' => $validated['fruit_drink'],
            'calories' => (int)$validated['calories'],
            'nutrition_highlight' => $validated['nutrition_highlight'],
            'allergy_notes' => $validated['allergy_notes'] ?: 'Bebas alergen umum.',
            'chef_in_charge' => $validated['chef_in_charge'],
            'portion_count' => (int)$validated['portion_count'],
            'halal_status' => $validated['halal_status'] ?: 'Tersertifikasi Halal LPPOM MUI',
        ];

        $sessionAdded[] = $newItem;
        session(['demo_boarding_meals_added' => $sessionAdded]);

        return redirect()->route('asrama.katering')
            ->with('success', "Menu katering baru [{$validated['menu_title']}] hari {$validated['day']} berhasil ditambahkan ke jadwal dapur asrama!");
    }

    /**
     * Destroy meal item (Session-backed)
     */
    public function destroyKatering(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $meal = $demo->findBoardingMeal($id);

        if (!$meal) {
            return redirect()->route('asrama.katering')->with('error', 'Menu katering tidak ditemukan.');
        }

        $sessionDeleted = session('demo_boarding_meals_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_boarding_meals_deleted' => $sessionDeleted]);
        }

        return redirect()->route('asrama.katering')
            ->with('success', "Menu [{$meal['menu_title']}] ({$meal['day']} - {$meal['meal_time']}) berhasil dihapus dari jadwal.");
    }

    /**
     * T08.7 — Boarding Reports (Laporan & Rekapitulasi Terpadu Asrama)
     */
    public function laporan(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $buildings = $demo->getBoardingBuildings();
        $rooms = $demo->getBoardingRooms();
        $assignments = $demo->getResidentAssignments();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();
        $leaves = $demo->getLeavePermissions();
        $attendances = $demo->getBoardingAttendance();
        $activities = $demo->getBoardingActivities();
        $meals = $demo->getBoardingMeals();

        // Lookups
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $roomMap = [];
        foreach ($rooms as $r) {
            $roomMap[$r['id']] = $r;
        }

        $buildingMap = [];
        foreach ($buildings as $b) {
            $buildingMap[$b['id']] = $b;
        }

        // Active tab
        $activeTab = $request->query('tab', 'okupansi'); // 'okupansi', 'perizinan', 'presensi', 'konsumsi'
        $selectedBuilding = $request->query('building');

        // 1. Room Occupancy Report Calculation
        $totalCapacity = 0;
        $totalOccupied = 0;
        $buildingStats = [];

        foreach ($buildings as $b) {
            $buildingStats[$b['id']] = [
                'name' => $b['name'],
                'gender' => $b['gender'],
                'head' => $b['supervisor_name'] ?? ($b['head_name'] ?? '-'),
                'total_rooms' => 0,
                'total_capacity' => 0,
                'occupied_beds' => 0,
                'vacant_beds' => 0,
                'occupancy_pct' => 0
            ];
        }

        $roomReportList = [];
        foreach ($rooms as $r) {
            $capacity = (int)($r['capacity'] ?? 0);
            $occupied = count($r['resident_student_ids'] ?? []);

            $totalCapacity += $capacity;
            $totalOccupied += $occupied;

            $bId = $r['building_id'];
            if (isset($buildingStats[$bId])) {
                $buildingStats[$bId]['total_rooms']++;
                $buildingStats[$bId]['total_capacity'] += $capacity;
                $buildingStats[$bId]['occupied_beds'] += $occupied;
                $buildingStats[$bId]['vacant_beds'] += ($capacity - $occupied);
            }

            // Cari santri di kamar ini
            $roomResidents = [];
            foreach ($assignments as $asn) {
                if ($asn['room_id'] === $r['id'] && $asn['status'] === 'aktif') {
                    $st = $studentMap[$asn['student_id']] ?? null;
                    if ($st) {
                        $roomResidents[] = [
                            'student_name' => $st['name'],
                            'nisn' => $st['nisn'],
                            'bed_number' => $asn['bed_number'] ?? '-',
                            'locker_number' => $asn['wardrobe_number'] ?? ($asn['locker_number'] ?? '-'),
                            'class_name' => $classMap[$st['class_id']]['name'] ?? '-'
                        ];
                    }
                }
            }

            if (!$selectedBuilding || $r['building_id'] === $selectedBuilding) {
                $roomReportList[] = [
                    'id' => $r['id'],
                    'room_number' => $r['number'] ?? ($r['room_number'] ?? $r['name']),
                    'name' => $r['name'] ?? '',
                    'building_id' => $r['building_id'],
                    'building_name' => $r['building_name'] ?? ($buildingMap[$r['building_id']]['name'] ?? '-'),
                    'floor' => $r['floor'] ?? 1,
                    'gender' => $r['gender'],
                    'capacity' => $capacity,
                    'occupied' => $occupied,
                    'vacant' => max(0, $capacity - $occupied),
                    'occupancy_pct' => $capacity > 0 ? round(($occupied / $capacity) * 100) : 0,
                    'status' => $r['status'] ?? 'aktif',
                    'residents' => $roomResidents
                ];
            }
        }

        foreach ($buildingStats as &$bs) {
            $bs['occupancy_pct'] = $bs['total_capacity'] > 0 ? round(($bs['occupied_beds'] / $bs['total_capacity']) * 100) : 0;
        }

        $totalVacant = $totalCapacity - $totalOccupied;
        $totalOccupancyRate = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100) : 0;

        // 2. Leave Status Report Calculation
        $totalLeaves = count($leaves);
        $leavesApproved = count(array_filter($leaves, fn($l) => ($l['approval_status'] ?? '') === 'disetujui' || ($l['approval_status'] ?? '') === 'kembali'));
        $leavesCompleted = count(array_filter($leaves, fn($l) => ($l['approval_status'] ?? '') === 'kembali'));
        $leavesActiveOut = count(array_filter($leaves, fn($l) => ($l['approval_status'] ?? '') === 'disetujui' && !empty($l['security_check_out']) && empty($l['security_check_in'])));
        $leavesPending = count(array_filter($leaves, fn($l) => ($l['approval_status'] ?? '') === 'diajukan'));

        $enrichedLeaves = [];
        foreach ($leaves as $l) {
            $st = $studentMap[$l['student_id']] ?? null;
            $rm = isset($l['room_id']) ? ($roomMap[$l['room_id']] ?? null) : null;
            $enrichedLeaves[] = array_merge($l, [
                'student_name' => $st['name'] ?? 'Santri ID: ' . $l['student_id'],
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-',
                'room_number' => $rm ? ($rm['number'] ?? $rm['name']) : '-',
                'building_name' => $rm ? ($rm['building_name'] ?? '-') : '-'
            ]);
        }

        // 3. Attendance & Shalat Monitoring Report
        $totalAttRecords = count($attendances);
        $totalHadir = count(array_filter($attendances, fn($a) => $a['status'] === 'hadir'));
        $totalMasbuq = count(array_filter($attendances, fn($a) => $a['status'] === 'masbuq'));
        $totalIzinSakit = count(array_filter($attendances, fn($a) => in_array($a['status'], ['izin', 'sakit'])));
        $totalAlpa = count(array_filter($attendances, fn($a) => $a['status'] === 'alpa'));

        $attendanceRate = $totalAttRecords > 0 ? round((($totalHadir + $totalMasbuq) / $totalAttRecords) * 100) : 0;

        $enrichedAttendance = [];
        foreach ($attendances as $att) {
            $st = $studentMap[$att['student_id']] ?? null;
            $rm = $roomMap[$att['room_id']] ?? null;
            $enrichedAttendance[] = array_merge($att, [
                'student_name' => $st['name'] ?? 'Santri ID: ' . $att['student_id'],
                'nisn' => $st['nisn'] ?? '-',
                'room_number' => $rm['room_number'] ?? '-'
            ]);
        }

        // 4. Consumption & Dapur Asrama Report
        $totalMealVariations = count($meals);
        $avgMealCalories = $totalMealVariations > 0 ? round(array_sum(array_column($meals, 'calories')) / $totalMealVariations) : 0;
        $totalWeeklyPortions = array_sum(array_column($meals, 'portion_count'));

        return view('asrama.laporan', compact(
            'activeTab',
            'buildings',
            'buildingStats',
            'selectedBuilding',
            'roomReportList',
            'totalCapacity',
            'totalOccupied',
            'totalVacant',
            'totalOccupancyRate',
            'leaves',
            'enrichedLeaves',
            'totalLeaves',
            'leavesApproved',
            'leavesCompleted',
            'leavesActiveOut',
            'leavesPending',
            'attendances',
            'enrichedAttendance',
            'totalAttRecords',
            'totalHadir',
            'totalMasbuq',
            'totalIzinSakit',
            'totalAlpa',
            'attendanceRate',
            'meals',
            'totalMealVariations',
            'avgMealCalories',
            'totalWeeklyPortions',
            'activities'
        ));
    }
}

