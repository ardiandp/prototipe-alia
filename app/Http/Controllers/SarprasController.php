<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    /**
     * T11.1 - Facility Registry / Registrasi Sarana, Prasarana, Tanah, Gedung & Lapangan
     */
    public function fasilitas(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $assets = $demo->getFacilityAssets();
        $rooms = $demo->getFacilityRooms();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, ruangan, peta, statistik
        $categoryFilter = $request->get('category', 'all');
        $conditionFilter = $request->get('condition', 'all');
        $unitFilter = $request->get('unit', 'all');
        $search = $request->get('search');

        // Filter aset sarpras
        $filteredAssets = array_filter($assets, function ($ast) use ($categoryFilter, $conditionFilter, $unitFilter, $search) {
            $matchCat = ($categoryFilter === 'all') || (($ast['category'] ?? '') === $categoryFilter);
            $matchCond = ($conditionFilter === 'all') || (($ast['condition'] ?? '') === $conditionFilter);
            $matchUnit = ($unitFilter === 'all') || (($ast['unit_id'] ?? '') === $unitFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($ast['name'], $search) !== false ||
                               stripos($ast['asset_code'] ?? '', $search) !== false ||
                               stripos($ast['location'] ?? '', $search) !== false ||
                               stripos($ast['person_in_charge'] ?? '', $search) !== false;
            }
            return $matchCat && $matchCond && $matchUnit && $matchSearch;
        });

        // Metrik Ringkasan Fasilitas
        $totalAssets = count($assets);
        $totalValue = array_sum(array_column($assets, 'asset_value'));
        $totalGoodCondition = count(array_filter($assets, fn($a) => in_array($a['condition'] ?? '', ['sangat_baik', 'baik'])));
        $totalNeedRepair = count(array_filter($assets, fn($a) => in_array($a['condition'] ?? '', ['rusak_ringan', 'rusak_berat'])));
        $totalRooms = count($rooms);

        // Sebaran Nilai Aset per Kategori
        $categorySummary = [];
        foreach ($assets as $a) {
            $cat = $a['category'] ?? 'lainnya';
            if (!isset($categorySummary[$cat])) {
                $categorySummary[$cat] = [
                    'label' => $a['category_label'] ?? ucfirst($cat),
                    'count' => 0,
                    'value' => 0
                ];
            }
            $categorySummary[$cat]['count']++;
            $categorySummary[$cat]['value'] += ($a['asset_value'] ?? 0);
        }

        return view('sarpras.fasilitas', compact(
            'filteredAssets',
            'assets',
            'rooms',
            'units',
            'activeTab',
            'categoryFilter',
            'conditionFilter',
            'unitFilter',
            'search',
            'totalAssets',
            'totalValue',
            'totalGoodCondition',
            'totalNeedRepair',
            'totalRooms',
            'categorySummary'
        ));
    }

    /**
     * Daftarkan Aset Sarana & Prasarana Baru
     */
    public function storeFasilitas(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:tanah,gedung,lapangan,ruangan,utilitas',
            'location' => 'required|string|max:150',
            'area_size' => 'required|string|max:50',
            'capacity' => 'required|numeric|min:1',
            'ownership_status' => 'required|string|max:100',
            'procurement_year' => 'required|numeric|min:1990|max:2030',
            'asset_value' => 'required|numeric|min:0',
            'condition' => 'required|in:sangat_baik,baik,rusak_ringan,rusak_berat',
            'person_in_charge' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $categoryLabels = [
            'tanah' => 'Tanah & Lahan Kampus',
            'gedung' => 'Bangunan Gedung Bertingkat',
            'lapangan' => 'Sarana Lapangan Olahraga',
            'ruangan' => 'Ruang Laboratorium & Fasilitas Khusus',
            'utilitas' => 'Utilitas & Sumber Daya Kampus'
        ];

        $demo = DemoDataManager::getInstance();
        $count = count($demo->getFacilityAssets()) + 1;
        $categoryPrefix = [
            'tanah' => 'TNH',
            'gedung' => 'GDG',
            'lapangan' => 'LPG',
            'ruangan' => 'RNG',
            'utilitas' => 'UTL'
        ][$validated['category']] ?? 'AST';

        $assetCode = "AST/{$categoryPrefix}/ALIA/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newAsset = [
            'id' => 'FCL-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'asset_code' => $assetCode,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'category_label' => $categoryLabels[$validated['category']] ?? 'Sarana Prasarana',
            'location' => $validated['location'],
            'area_size' => $validated['area_size'],
            'capacity' => (int)$validated['capacity'],
            'ownership_status' => $validated['ownership_status'],
            'procurement_year' => (string)$validated['procurement_year'],
            'asset_value' => (float)$validated['asset_value'],
            'condition' => $validated['condition'],
            'person_in_charge' => $validated['person_in_charge'],
            'unit_id' => $validated['unit_id'],
            'status' => 'aktif',
            'notes' => $validated['notes'] ?: '-'
        ];

        $sessionAdded = session('demo_facility_assets_added', []);
        $sessionAdded[] = $newAsset;
        session(['demo_facility_assets_added' => $sessionAdded]);

        return redirect()->route('sarpras.fasilitas')->with('success', "Aset fasilitas \"{$newAsset['name']}\" ({$assetCode}) berhasil didaftarkan ke buku register induk!");
    }

    /**
     * Update Status & Kondisi Fisik Fasilitas
     */
    public function updateKondisi(Request $request, string $id)
    {
        $validated = $request->validate([
            'condition' => 'required|in:sangat_baik,baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string|max:500',
        ]);

        $sessionUpdated = session('demo_facility_assets_updated', []);
        $sessionUpdated[$id] = [
            'condition' => $validated['condition'],
            'notes' => $validated['notes'] ?: 'Pembaruan kondisi fisik fasilitas.'
        ];
        session(['demo_facility_assets_updated' => $sessionUpdated]);

        return redirect()->route('sarpras.fasilitas')->with('success', "Kondisi fisik aset {$id} berhasil diperbarui!");
    }

    /**
     * Hapus Aset Fasilitas dari Sesi Demo
     */
    public function destroyFasilitas(string $id)
    {
        $sessionDeleted = session('demo_facility_assets_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_facility_assets_deleted' => $sessionDeleted]);
        }

        return redirect()->route('sarpras.fasilitas')->with('info', "Aset fasilitas ID: {$id} berhasil dihapus dari register sesi demo.");
    }

    /**
     * T11.2 - Room Usage and Booking / Peminjaman & Jadwal Ruangan Khusus, Lab & Aula
     */
    public function peminjaman(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $bookings = $demo->getRoomBookings();
        $rooms = $demo->getFacilityRooms();
        $assets = $demo->getFacilityAssets();
        $teachers = $demo->getTeachers();
        $units = $demo->getUnits();

        // Kumpulkan daftar ruangan & lapangan yang bisa dipinjam
        $bookableVenues = [];
        foreach ($rooms as $r) {
            $bookableVenues[] = [
                'id' => $r['id'],
                'name' => $r['name'],
                'location' => $r['location'] ?? 'Kampus Alia',
                'capacity' => $r['capacity'] ?? 30,
                'type' => $r['type'] ?? 'Ruangan'
            ];
        }
        foreach ($assets as $a) {
            if (in_array($a['category'] ?? '', ['lapangan', 'gedung', 'ruangan'])) {
                // Jangan duplikasi jika sudah ada
                $exists = false;
                foreach ($bookableVenues as $bv) {
                    if ($bv['name'] === $a['name']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $bookableVenues[] = [
                        'id' => $a['id'],
                        'name' => $a['name'],
                        'location' => $a['location'],
                        'capacity' => $a['capacity'],
                        'type' => $a['category_label'] ?? 'Sarpras Khusus'
                    ];
                }
            }
        }

        $activeTab = $request->get('tab', 'daftar'); // daftar, jadwal, persetujuan
        $statusFilter = $request->get('status', 'all');
        $roomFilter = $request->get('room', 'all');
        $dateFilter = $request->get('date');
        $search = $request->get('search');

        $filteredBookings = array_filter($bookings, function ($bkg) use ($statusFilter, $roomFilter, $dateFilter, $search) {
            $matchStatus = ($statusFilter === 'all') || (($bkg['status'] ?? '') === $statusFilter);
            $matchRoom = ($roomFilter === 'all') || (($bkg['room_id'] ?? '') === $roomFilter) || (($bkg['room_name'] ?? '') === $roomFilter);
            $matchDate = empty($dateFilter) || (($bkg['booking_date'] ?? '') === $dateFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($bkg['purpose'] ?? '', $search) !== false ||
                               stripos($bkg['requester_name'] ?? '', $search) !== false ||
                               stripos($bkg['room_name'] ?? '', $search) !== false ||
                               stripos($bkg['booking_code'] ?? '', $search) !== false;
            }
            return $matchStatus && $matchRoom && $matchDate && $matchSearch;
        });

        // Metrik Ringkasan Peminjaman Ruangan
        $totalBookings = count($bookings);
        $totalPending = count(array_filter($bookings, fn($b) => ($b['status'] ?? '') === 'diajukan'));
        $totalApproved = count(array_filter($bookings, fn($b) => ($b['status'] ?? '') === 'disetujui'));
        $totalCompleted = count(array_filter($bookings, fn($b) => ($b['status'] ?? '') === 'selesai'));

        return view('sarpras.peminjaman', compact(
            'filteredBookings',
            'bookings',
            'bookableVenues',
            'rooms',
            'teachers',
            'units',
            'activeTab',
            'statusFilter',
            'roomFilter',
            'dateFilter',
            'search',
            'totalBookings',
            'totalPending',
            'totalApproved',
            'totalCompleted'
        ));
    }

    /**
     * Ajukan Permohonan Peminjaman Ruangan / Fasilitas Baru
     */
    public function storePeminjaman(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|string',
            'requester_name' => 'required|string|max:100',
            'requester_role' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'booking_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'purpose' => 'required|string|max:255',
            'attendees_count' => 'required|numeric|min:1',
            'equipment_needed' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $demo = DemoDataManager::getInstance();
        $rooms = $demo->getFacilityRooms();
        $assets = $demo->getFacilityAssets();

        // Cari nama dan lokasi ruangan terpilih
        $roomName = 'Fasilitas Kampus';
        $roomLocation = 'Kampus Alia Islamic School';
        foreach ($rooms as $r) {
            if ($r['id'] === $validated['room_id']) {
                $roomName = $r['name'];
                $roomLocation = $r['location'] ?? 'Kampus Alia';
                break;
            }
        }
        if ($roomName === 'Fasilitas Kampus') {
            foreach ($assets as $a) {
                if ($a['id'] === $validated['room_id']) {
                    $roomName = $a['name'];
                    $roomLocation = $a['location'] ?? 'Kampus Alia';
                    break;
                }
            }
        }

        $allBookings = $demo->getRoomBookings();
        $count = count($allBookings) + 1;
        $bookingCode = "PINJAM/RNG/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Deteksi Bentrok Jadwal Otomatis (conflict check)
        $hasConflict = false;
        foreach ($allBookings as $eb) {
            if (($eb['room_id'] ?? '') === $validated['room_id'] &&
                ($eb['booking_date'] ?? '') === $validated['booking_date'] &&
                in_array($eb['status'] ?? '', ['disetujui', 'diajukan'])) {
                // Cek tumpang tindih waktu
                $existingStart = strtotime($eb['booking_date'] . ' ' . $eb['start_time']);
                $existingEnd = strtotime($eb['booking_date'] . ' ' . $eb['end_time']);
                $newStart = strtotime($validated['booking_date'] . ' ' . $validated['start_time']);
                $newEnd = strtotime($validated['booking_date'] . ' ' . $validated['end_time']);

                if ($newStart < $existingEnd && $newEnd > $existingStart) {
                    $hasConflict = true;
                    break;
                }
            }
        }

        $newBooking = [
            'id' => 'BKG-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'booking_code' => $bookingCode,
            'room_id' => $validated['room_id'],
            'room_name' => $roomName,
            'room_location' => $roomLocation,
            'requester_name' => $validated['requester_name'],
            'requester_role' => $validated['requester_role'],
            'unit_id' => $validated['unit_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'purpose' => $validated['purpose'],
            'attendees_count' => (int)$validated['attendees_count'],
            'equipment_needed' => $validated['equipment_needed'] ?: 'Standar ruangan (tanpa perlengkapan tambahan)',
            'status' => 'diajukan',
            'approver_name' => null,
            'approved_at' => null,
            'rejection_reason' => null,
            'notes' => $hasConflict
                ? "[PERINGATAN SISTEM: Terindikasi bentrok jam pemakaian dengan permohonan lain!] " . ($validated['notes'] ?: '')
                : ($validated['notes'] ?: '-')
        ];

        $sessionAdded = session('demo_room_bookings_added', []);
        $sessionAdded[] = $newBooking;
        session(['demo_room_bookings_added' => $sessionAdded]);

        $msg = "Permohonan peminjaman \"{$roomName}\" ({$bookingCode}) berhasil diajukan ke Bagian Sarpras!";
        if ($hasConflict) {
            return redirect()->route('sarpras.peminjaman')->with('info', "{$msg} (Catatan: Terdeteksi potensi bentrok waktu pemakaian pada tanggal dan jam tersebut).");
        }

        return redirect()->route('sarpras.peminjaman')->with('success', $msg);
    }

    /**
     * Persetujuan / Penolakan Peminjaman Ruangan
     */
    public function approvePeminjaman(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak,selesai',
            'rejection_reason' => 'nullable|string|max:500',
            'approver_name' => 'required|string|max:100',
        ]);

        $sessionUpdated = session('demo_room_bookings_updated', []);
        $sessionUpdated[$id] = [
            'status' => $validated['status'],
            'approver_name' => $validated['approver_name'],
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $validated['status'] === 'ditolak' ? ($validated['rejection_reason'] ?: 'Jadwal ruangan tidak tersedia atau bentrok.') : null,
        ];
        session(['demo_room_bookings_updated' => $sessionUpdated]);

        $statusLabels = [
            'disetujui' => 'DISETUJUI',
            'ditolak' => 'DITOLAK',
            'selesai' => 'DITANDAI SELESAI'
        ];
        $label = $statusLabels[$validated['status']] ?? $validated['status'];

        return redirect()->route('sarpras.peminjaman')->with('success', "Permohonan peminjaman ruangan {$id} berhasil {$label}!");
    }

    /**
     * Hapus Data Peminjaman Ruangan dari Sesi Demo
     */
    public function destroyPeminjaman(string $id)
    {
        $sessionDeleted = session('demo_room_bookings_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_room_bookings_deleted' => $sessionDeleted]);
        }

        return redirect()->route('sarpras.peminjaman')->with('info', "Data peminjaman ruangan ID: {$id} berhasil dihapus dari sesi demo.");
    }

    /**
     * T11.3 - Maintenance & Damage Reports / Pemeliharaan, Laporan Kerusakan & Servis Sarpras
     */
    public function pemeliharaan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $reports = $demo->getMaintenanceReports();
        $assets = $demo->getFacilityAssets();
        $rooms = $demo->getFacilityRooms();
        $units = $demo->getUnits();

        // Gabungkan seluruh target aset/ruangan yang bisa dilaporkan kerusakannya
        $reportableAssets = [];
        foreach ($assets as $a) {
            $reportableAssets[] = [
                'id' => $a['id'],
                'name' => $a['name'],
                'location' => $a['location'] ?? 'Kampus Alia',
                'category' => $a['category'] ?? 'umum',
                'category_label' => $a['category_label'] ?? 'Aset Fisik'
            ];
        }
        foreach ($rooms as $r) {
            $reportableAssets[] = [
                'id' => $r['id'],
                'name' => $r['name'],
                'location' => $r['location'] ?? 'Kampus Alia',
                'category' => 'ruangan',
                'category_label' => 'Ruangan Belajar / Khusus'
            ];
        }

        $activeTab = $request->get('tab', 'daftar'); // daftar, teknisi, rekap
        $statusFilter = $request->get('status', 'all');
        $priorityFilter = $request->get('priority', 'all');
        $categoryFilter = $request->get('category', 'all');
        $search = $request->get('search');

        $filteredReports = array_filter($reports, function ($r) use ($statusFilter, $priorityFilter, $categoryFilter, $search) {
            $matchStatus = ($statusFilter === 'all') || (($r['status'] ?? '') === $statusFilter);
            $matchPriority = ($priorityFilter === 'all') || (($r['priority'] ?? '') === $priorityFilter);
            $matchCategory = ($categoryFilter === 'all') || (($r['category'] ?? '') === $categoryFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($r['title'] ?? '', $search) !== false ||
                               stripos($r['ticket_code'] ?? '', $search) !== false ||
                               stripos($r['asset_name'] ?? '', $search) !== false ||
                               stripos($r['reporter_name'] ?? '', $search) !== false ||
                               stripos($r['assigned_to'] ?? '', $search) !== false;
            }
            return $matchStatus && $matchPriority && $matchCategory && $matchSearch;
        });

        // Metrik Ringkasan Pemeliharaan
        $totalReports = count($reports);
        $totalEmergency = count(array_filter($reports, fn($r) => in_array($r['priority'] ?? '', ['darurat', 'tinggi'])));
        $totalInProgress = count(array_filter($reports, fn($r) => in_array($r['status'] ?? '', ['disetujui', 'dalam_pengerjaan'])));
        $totalCompleted = count(array_filter($reports, fn($r) => ($r['status'] ?? '') === 'selesai'));
        $totalEstimatedCost = array_sum(array_column($reports, 'estimated_cost'));
        $totalActualCost = array_sum(array_column($reports, 'actual_cost'));

        return view('sarpras.pemeliharaan', compact(
            'filteredReports',
            'reports',
            'reportableAssets',
            'units',
            'activeTab',
            'statusFilter',
            'priorityFilter',
            'categoryFilter',
            'search',
            'totalReports',
            'totalEmergency',
            'totalInProgress',
            'totalCompleted',
            'totalEstimatedCost',
            'totalActualCost'
        ));
    }

    /**
     * Lapor Kerusakan Fasilitas / Buat Tiket Servis Baru
     */
    public function storePemeliharaan(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|string',
            'title' => 'required|string|max:150',
            'category' => 'required|in:kelistrikan,sipil_bangunan,ac_pendingin,sanitasi_plumbing,elektronik_it,mebelair',
            'priority' => 'required|in:darurat,tinggi,sedang,rendah',
            'report_date' => 'required|date',
            'reporter_name' => 'required|string|max:100',
            'reporter_role' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'location' => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'assigned_to' => 'nullable|string|max:100',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        $categoryLabels = [
            'kelistrikan' => 'Kelistrikan & Penerangan',
            'sipil_bangunan' => 'Sipil & Konstruksi Gedung',
            'ac_pendingin' => 'Tata Udara & AC',
            'sanitasi_plumbing' => 'Sanitasi & Plumbing',
            'elektronik_it' => 'Elektronik & Perangkat IT',
            'mebelair' => 'Mebelair & Sarana Kampus'
        ];

        $demo = DemoDataManager::getInstance();
        $assets = $demo->getFacilityAssets();
        $rooms = $demo->getFacilityRooms();

        $assetName = 'Aset Fasilitas';
        foreach ($assets as $a) {
            if ($a['id'] === $validated['asset_id']) {
                $assetName = $a['name'];
                break;
            }
        }
        if ($assetName === 'Aset Fasilitas') {
            foreach ($rooms as $r) {
                if ($r['id'] === $validated['asset_id']) {
                    $assetName = $r['name'];
                    break;
                }
            }
        }

        $allReports = $demo->getMaintenanceReports();
        $count = count($allReports) + 1;
        $ticketCode = "TIKET/MNT/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $hasAssigned = !empty($validated['assigned_to']);

        $newReport = [
            'id' => 'MNT-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'ticket_code' => $ticketCode,
            'title' => $validated['title'],
            'asset_id' => $validated['asset_id'],
            'asset_name' => $assetName,
            'location' => $validated['location'],
            'category' => $validated['category'],
            'category_label' => $categoryLabels[$validated['category']] ?? 'Pemeliharaan',
            'priority' => $validated['priority'],
            'report_date' => $validated['report_date'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_role' => $validated['reporter_role'],
            'unit_id' => $validated['unit_id'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'] ?: null,
            'assigned_type' => 'internal',
            'vendor_name' => null,
            'estimated_cost' => (float)($validated['estimated_cost'] ?? 0),
            'actual_cost' => 0,
            'progress' => $hasAssigned ? 20 : 0,
            'status' => $hasAssigned ? 'disetujui' : 'dilaporkan',
            'started_at' => $hasAssigned ? date('Y-m-d H:i:s') : null,
            'completed_at' => null,
            'resolution_notes' => $hasAssigned ? 'Laporan diterima dan teknisi telah ditugaskan untuk investigasi lapangan.' : 'Laporan baru menunggu verifikasi koordinator sarpras.'
        ];

        $sessionAdded = session('demo_maintenance_reports_added', []);
        $sessionAdded[] = $newReport;
        session(['demo_maintenance_reports_added' => $sessionAdded]);

        return redirect()->route('sarpras.pemeliharaan')->with('success', "Tiket laporan kerusakan \"{$newReport['title']}\" ({$ticketCode}) berhasil dibuat!");
    }

    /**
     * Update Status Progres Pengerjaan / Tindakan Servis
     */
    public function updateProgressPemeliharaan(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,dalam_pengerjaan,selesai,ditunda',
            'progress' => 'required|numeric|min:0|max:100',
            'assigned_to' => 'nullable|string|max:100',
            'actual_cost' => 'nullable|numeric|min:0',
            'resolution_notes' => 'required|string|max:500',
        ]);

        $sessionUpdated = session('demo_maintenance_reports_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $updateData = [
            'status' => $validated['status'],
            'progress' => (int)$validated['progress'],
            'resolution_notes' => $validated['resolution_notes'],
        ];

        if (!empty($validated['assigned_to'])) {
            $updateData['assigned_to'] = $validated['assigned_to'];
        }
        if (isset($validated['actual_cost'])) {
            $updateData['actual_cost'] = (float)$validated['actual_cost'];
        }
        if ($validated['status'] === 'selesai') {
            $updateData['progress'] = 100;
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        } elseif ($validated['status'] === 'dalam_pengerjaan' && empty($existing['started_at'])) {
            $updateData['started_at'] = date('Y-m-d H:i:s');
        }

        $sessionUpdated[$id] = array_merge($existing, $updateData);
        session(['demo_maintenance_reports_updated' => $sessionUpdated]);

        return redirect()->route('sarpras.pemeliharaan')->with('success', "Progres perbaikan tiket {$id} berhasil diperbarui (Status: " . strtoupper($validated['status']) . ")!");
    }

    /**
     * Hapus Laporan Pemeliharaan dari Sesi Demo
     */
    public function destroyPemeliharaan(string $id)
    {
        $sessionDeleted = session('demo_maintenance_reports_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_maintenance_reports_deleted' => $sessionDeleted]);
        }

        return redirect()->route('sarpras.pemeliharaan')->with('info', "Tiket laporan perbaikan ID: {$id} berhasil dihapus dari sesi demo.");
    }

    /**
     * T11.4 - Procurement Requests / Pengajuan Pengadaan Sarpras Baru
     */
    public function pengadaan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $procurements = $demo->getFacilityProcurements();
        $units = $demo->getUnits();
        $rooms = $demo->getFacilityRooms();
        $assets = $demo->getFacilityAssets();

        // Kumpulkan opsi target lokasi
        $targetLocations = [];
        foreach ($assets as $a) {
            $targetLocations[] = $a['name'] . ' (' . ($a['location'] ?? 'Kampus') . ')';
        }
        foreach ($rooms as $r) {
            $targetLocations[] = $r['name'] . ' (' . ($r['location'] ?? 'Kampus') . ')';
        }

        $activeTab = $request->get('tab', 'daftar'); // daftar, rincian, persetujuan
        $statusFilter = $request->get('status', 'all');
        $categoryFilter = $request->get('category', 'all');
        $urgencyFilter = $request->get('urgency', 'all');
        $search = $request->get('search');

        $filteredProcurements = array_filter($procurements, function ($prc) use ($statusFilter, $categoryFilter, $urgencyFilter, $search) {
            $matchStatus = ($statusFilter === 'all') || (($prc['status'] ?? '') === $statusFilter);
            $matchCategory = ($categoryFilter === 'all') || (($prc['category'] ?? '') === $categoryFilter);
            $matchUrgency = ($urgencyFilter === 'all') || (($prc['urgency'] ?? '') === $urgencyFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($prc['title'] ?? '', $search) !== false ||
                               stripos($prc['request_code'] ?? '', $search) !== false ||
                               stripos($prc['requester_name'] ?? '', $search) !== false ||
                               stripos($prc['target_location'] ?? '', $search) !== false;
            }
            return $matchStatus && $matchCategory && $matchUrgency && $matchSearch;
        });

        // Metrik Ringkasan Pengadaan
        $totalRequests = count($procurements);
        $totalApproved = count(array_filter($procurements, fn($p) => in_array($p['status'] ?? '', ['disetujui', 'dalam_pemesanan', 'selesai'])));
        $totalPending = count(array_filter($procurements, fn($p) => in_array($p['status'] ?? '', ['diajukan', 'verifikasi_sarpras'])));
        $totalBudget = array_sum(array_column($procurements, 'estimated_total'));

        return view('sarpras.pengadaan', compact(
            'filteredProcurements',
            'procurements',
            'targetLocations',
            'units',
            'activeTab',
            'statusFilter',
            'categoryFilter',
            'urgencyFilter',
            'search',
            'totalRequests',
            'totalApproved',
            'totalPending',
            'totalBudget'
        ));
    }

    /**
     * Ajukan Usulan Pengadaan Sarpras Baru
     */
    public function storePengadaan(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|in:elektronik_it,mebelair,sarana_olahraga,utilitas_daya,laboratorium',
            'urgency' => 'required|in:mendesak,tinggi,sedang,rutin',
            'requester_name' => 'required|string|max:100',
            'requester_role' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'target_location' => 'required|string|max:150',
            'request_date' => 'required|date',
            'source_funding' => 'required|string|max:100',
            'justification' => 'required|string|max:500',
            'item_names' => 'required|array|min:1',
            'item_names.*' => 'required|string|max:150',
            'item_quantities' => 'required|array|min:1',
            'item_quantities.*' => 'required|numeric|min:1',
            'item_units' => 'required|array|min:1',
            'item_units.*' => 'required|string|max:50',
            'item_prices' => 'required|array|min:1',
            'item_prices.*' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $categoryLabels = [
            'elektronik_it' => 'Elektronik & Perangkat Komputer',
            'mebelair' => 'Mebelair & Furnitur Santri',
            'sarana_olahraga' => 'Sarana & Alat Olahraga',
            'utilitas_daya' => 'Utilitas & Kelistrikan',
            'laboratorium' => 'Alat & Bahan Praktikum Lab'
        ];

        // Susun item rincian belanja
        $items = [];
        $estimatedTotal = 0;
        $totalItemsCount = 0;

        foreach ($validated['item_names'] as $i => $name) {
            $qty = (int)($validated['item_quantities'][$i] ?? 1);
            $unit = $validated['item_units'][$i] ?? 'Unit';
            $price = (float)($validated['item_prices'][$i] ?? 0);
            $subtotal = $qty * $price;

            $items[] = [
                'item_name' => $name,
                'quantity' => $qty,
                'unit' => $unit,
                'unit_price' => $price,
                'subtotal' => $subtotal
            ];

            $estimatedTotal += $subtotal;
            $totalItemsCount += $qty;
        }

        $demo = DemoDataManager::getInstance();
        $allProcurements = $demo->getFacilityProcurements();
        $count = count($allProcurements) + 1;
        $requestCode = "PENGADAAN/SARPRAS/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newProcurement = [
            'id' => 'PRC-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'request_code' => $requestCode,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'category_label' => $categoryLabels[$validated['category']] ?? 'Pengadaan Sarpras',
            'requester_name' => $validated['requester_name'],
            'requester_role' => $validated['requester_role'],
            'unit_id' => $validated['unit_id'],
            'target_location' => $validated['target_location'],
            'request_date' => $validated['request_date'],
            'urgency' => $validated['urgency'],
            'total_items_count' => $totalItemsCount,
            'items' => $items,
            'estimated_total' => $estimatedTotal,
            'source_funding' => $validated['source_funding'],
            'justification' => $validated['justification'],
            'status' => 'diajukan',
            'approver_name' => null,
            'approved_at' => null,
            'rejection_reason' => null,
            'notes' => $validated['notes'] ?: '-'
        ];

        $sessionAdded = session('demo_facility_procurements_added', []);
        $sessionAdded[] = $newProcurement;
        session(['demo_facility_procurements_added' => $sessionAdded]);

        return redirect()->route('sarpras.pengadaan')->with('success', "Usulan pengadaan sarpras \"{$newProcurement['title']}\" ({$requestCode}) berhasil diajukan ke Bagian Sarpras & Yayasan!");
    }

    /**
     * Persetujuan / Update Status Pengadaan Sarpras
     */
    public function approvePengadaan(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:verifikasi_sarpras,disetujui,ditolak,dalam_pemesanan,selesai',
            'approver_name' => 'required|string|max:100',
            'rejection_reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $sessionUpdated = session('demo_facility_procurements_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $updateData = [
            'status' => $validated['status'],
            'approver_name' => $validated['approver_name'],
            'rejection_reason' => $validated['status'] === 'ditolak' ? ($validated['rejection_reason'] ?: 'Ditolak pimpinan / anggaran tidak mencukupi.') : null,
        ];

        if (in_array($validated['status'], ['disetujui', 'dalam_pemesanan', 'selesai'])) {
            $updateData['approved_at'] = date('Y-m-d H:i:s');
        }
        if (!empty($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $sessionUpdated[$id] = array_merge($existing, $updateData);
        session(['demo_facility_procurements_updated' => $sessionUpdated]);

        $statusLabels = [
            'verifikasi_sarpras' => 'DIVERIFIKASI SARPRAS',
            'disetujui' => 'DISETUJUI YAYASAN',
            'ditolak' => 'DITOLAK',
            'dalam_pemesanan' => 'DALAM PEMESANAN / PO',
            'selesai' => 'SELESAI DITERIMA'
        ];
        $label = $statusLabels[$validated['status']] ?? $validated['status'];

        return redirect()->route('sarpras.pengadaan')->with('success', "Usulan pengadaan sarpras {$id} berhasil diproses ({$label})!");
    }

    /**
     * Hapus Usulan Pengadaan Sarpras dari Sesi Demo
     */
    public function destroyPengadaan(string $id)
    {
        $sessionDeleted = session('demo_facility_procurements_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_facility_procurements_deleted' => $sessionDeleted]);
        }

        return redirect()->route('sarpras.pengadaan')->with('info', "Usulan pengadaan sarpras ID: {$id} berhasil dihapus dari sesi demo.");
    }
}
