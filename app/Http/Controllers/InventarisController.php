<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * T12.1 - Item Master / Katalog Master Barang Inventaris Sekolah
     */
    public function barang(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $items = $demo->getInventoryItems();

        $activeTab = $request->get('tab', 'daftar'); // daftar, stok_menipis, valuasi
        $categoryFilter = $request->get('category', 'all');
        $conditionFilter = $request->get('condition', 'all');
        $stockFilter = $request->get('stock_status', 'all'); // all, aman, menipis, habis
        $search = $request->get('search');

        $filteredItems = array_filter($items, function ($item) use ($categoryFilter, $conditionFilter, $stockFilter, $search) {
            $matchCat = ($categoryFilter === 'all') || (($item['category'] ?? '') === $categoryFilter);
            $matchCond = ($conditionFilter === 'all') || (($item['condition'] ?? '') === $conditionFilter);

            $matchStock = true;
            if ($stockFilter === 'menipis') {
                $matchStock = ($item['stock'] <= $item['min_stock']) && ($item['stock'] > 0);
            } elseif ($stockFilter === 'habis') {
                $matchStock = ($item['stock'] == 0);
            } elseif ($stockFilter === 'aman') {
                $matchStock = ($item['stock'] > $item['min_stock']);
            }

            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($item['name'], $search) !== false ||
                               stripos($item['item_code'] ?? '', $search) !== false ||
                               stripos($item['barcode'] ?? '', $search) !== false ||
                               stripos($item['location'] ?? '', $search) !== false;
            }

            return $matchCat && $matchCond && $matchStock && $matchSearch;
        });

        // Metrik Ringkasan Inventaris
        $totalItems = count($items);
        $totalStockUnits = array_sum(array_column($items, 'stock'));
        $totalValuation = array_sum(array_map(fn($it) => ($it['stock'] * $it['unit_price']), $items));
        $lowStockCount = count(array_filter($items, fn($it) => $it['stock'] <= $it['min_stock']));

        // Kategori Summary
        $categorySummary = [];
        foreach ($items as $it) {
            $c = $it['category'] ?? 'lainnya';
            if (!isset($categorySummary[$c])) {
                $categorySummary[$c] = [
                    'label' => $it['category_label'] ?? ucfirst($c),
                    'count' => 0,
                    'stock' => 0,
                    'value' => 0
                ];
            }
            $categorySummary[$c]['count']++;
            $categorySummary[$c]['stock'] += $it['stock'];
            $categorySummary[$c]['value'] += ($it['stock'] * $it['unit_price']);
        }

        return view('inventaris.barang', compact(
            'filteredItems',
            'items',
            'activeTab',
            'categoryFilter',
            'conditionFilter',
            'stockFilter',
            'search',
            'totalItems',
            'totalStockUnits',
            'totalValuation',
            'lowStockCount',
            'categorySummary'
        ));
    }

    /**
     * Daftarkan Master Barang Baru ke Buku Inventaris
     */
    public function storeBarang(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:atk,elektronik,mebelair,kebersihan,olahraga,laboratorium,uks_medis',
            'unit' => 'required|string|max:50',
            'location' => 'required|string|max:150',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'specification' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $categoryLabels = [
            'atk' => 'Alat Tulis Kantor (ATK)',
            'elektronik' => 'Elektronik & Multimedia',
            'mebelair' => 'Mebelair & Furnitur',
            'kebersihan' => 'Kebersihan & Sanitasi',
            'olahraga' => 'Sarana & Alat Olahraga',
            'laboratorium' => 'Laboratorium & Sains',
            'uks_medis' => 'Kesehatan & UKS'
        ];

        $categoryPrefix = [
            'atk' => 'ATK',
            'elektronik' => 'ELK',
            'mebelair' => 'MBL',
            'kebersihan' => 'KBR',
            'olahraga' => 'OLG',
            'laboratorium' => 'LAB',
            'uks_medis' => 'UKS'
        ][$validated['category']] ?? 'INV';

        $demo = DemoDataManager::getInstance();
        $count = count($demo->getInventoryItems()) + 1;

        $itemCode = "BRG/{$categoryPrefix}/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);
        $barcode = "899" . rand(100, 999) . str_pad($count, 6, '0', STR_PAD_LEFT);

        $newItem = [
            'id' => 'INV-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'item_code' => $itemCode,
            'barcode' => $barcode,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'category_label' => $categoryLabels[$validated['category']] ?? 'Inventaris',
            'unit' => $validated['unit'],
            'location' => $validated['location'],
            'stock' => (int)$validated['stock'],
            'min_stock' => (int)$validated['min_stock'],
            'unit_price' => (float)$validated['unit_price'],
            'condition' => $validated['condition'],
            'specification' => $validated['specification'] ?: '-',
            'status' => 'aktif',
            'notes' => $validated['notes'] ?: '-'
        ];

        $sessionAdded = session('demo_inventory_items_added', []);
        $sessionAdded[] = $newItem;
        session(['demo_inventory_items_added' => $sessionAdded]);

        return redirect()->route('inventaris.barang')->with('success', "Barang inventaris \"{$newItem['name']}\" ({$itemCode}) berhasil didaftarkan ke sistem logistik!");
    }

    /**
     * Update Data / Kondisi / Stok Minimum Barang
     */
    public function updateBarang(Request $request, string $id)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:150',
            'min_stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string|max:500',
        ]);

        $sessionUpdated = session('demo_inventory_items_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $updateData = [
            'location' => $validated['location'],
            'min_stock' => (int)$validated['min_stock'],
            'unit_price' => (float)$validated['unit_price'],
            'condition' => $validated['condition'],
            'notes' => $validated['notes'] ?: 'Pembaruan data inventaris.'
        ];

        $sessionUpdated[$id] = array_merge($existing, $updateData);
        session(['demo_inventory_items_updated' => $sessionUpdated]);

        return redirect()->route('inventaris.barang')->with('success', "Data barang inventaris {$id} berhasil diperbarui!");
    }

    /**
     * Hapus Master Barang dari Sesi Demo
     */
    public function destroyBarang(string $id)
    {
        $sessionDeleted = session('demo_inventory_items_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_inventory_items_deleted' => $sessionDeleted]);
        }

        return redirect()->route('inventaris.barang')->with('info', "Barang inventaris ID: {$id} berhasil dihapus dari master data sesi demo.");
    }
}
