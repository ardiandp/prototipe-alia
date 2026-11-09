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
}
