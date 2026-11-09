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
}
