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

    /**
     * T12.2 - Stock Receipt / Penerimaan Barang Masuk Logistik
     */
    public function penerimaan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $receipts = $demo->getStockReceipts();
        $items = $demo->getInventoryItems();

        $activeTab = $request->get('tab', 'daftar'); // daftar, rekap_sumber
        $sourceFilter = $request->get('source_type', 'all');
        $dateFilter = $request->get('date');
        $search = $request->get('search');

        $filteredReceipts = array_filter($receipts, function ($rcv) use ($sourceFilter, $dateFilter, $search) {
            $matchSource = ($sourceFilter === 'all') || (($rcv['source_type'] ?? '') === $sourceFilter);
            $matchDate = empty($dateFilter) || (($rcv['receipt_date'] ?? '') === $dateFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($rcv['item_name'] ?? '', $search) !== false ||
                               stripos($rcv['receipt_code'] ?? '', $search) !== false ||
                               stripos($rcv['delivery_order_no'] ?? '', $search) !== false ||
                               stripos($rcv['source_name'] ?? '', $search) !== false ||
                               stripos($rcv['officer_name'] ?? '', $search) !== false;
            }
            return $matchSource && $matchDate && $matchSearch;
        });

        // Metrik Ringkasan Penerimaan
        $totalTransactions = count($receipts);
        $totalItemsReceived = array_sum(array_column($receipts, 'quantity'));
        $totalReceiptValue = array_sum(array_column($receipts, 'total_amount'));
        $vendorCount = count(array_filter($receipts, fn($r) => ($r['source_type'] ?? '') === 'pembelian_vendor'));

        // Sebaran Sumber Penerimaan
        $sourceSummary = [];
        foreach ($receipts as $r) {
            $src = $r['source_type'] ?? 'lainnya';
            if (!isset($sourceSummary[$src])) {
                $sourceSummary[$src] = [
                    'label' => $r['source_label'] ?? ucfirst($src),
                    'count' => 0,
                    'quantity' => 0,
                    'amount' => 0
                ];
            }
            $sourceSummary[$src]['count']++;
            $sourceSummary[$src]['quantity'] += $r['quantity'];
            $sourceSummary[$src]['amount'] += ($r['total_amount'] ?? 0);
        }

        return view('inventaris.penerimaan', compact(
            'filteredReceipts',
            'receipts',
            'items',
            'activeTab',
            'sourceFilter',
            'dateFilter',
            'search',
            'totalTransactions',
            'totalItemsReceived',
            'totalReceiptValue',
            'vendorCount',
            'sourceSummary'
        ));
    }

    /**
     * Catat Penerimaan Barang Masuk Baru (Otomatis Tambah Stok Fisik)
     */
    public function storePenerimaan(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|string',
            'delivery_order_no' => 'required|string|max:100',
            'receipt_date' => 'required|date',
            'quantity' => 'required|numeric|min:1',
            'source_type' => 'required|in:pembelian_vendor,dana_bos,hibah_donatur,yayasan',
            'source_name' => 'required|string|max:150',
            'officer_name' => 'required|string|max:100',
            'storage_location' => 'required|string|max:150',
            'condition_received' => 'required|in:baik,cacat_sebagian,rusak',
            'notes' => 'nullable|string|max:500',
        ]);

        $sourceLabels = [
            'pembelian_vendor' => 'Pembelian Vendor / Rekanan',
            'dana_bos' => 'Dana Bantuan Operasional Sekolah (BOS)',
            'hibah_donatur' => 'Hibah / Wakaf Donatur & Komite',
            'yayasan' => 'Anggaran Yayasan Alia'
        ];

        $demo = DemoDataManager::getInstance();
        $item = $demo->findInventoryItem($validated['item_id']);

        $itemName = $item['name'] ?? 'Barang Inventaris';
        $itemCode = $item['item_code'] ?? 'BRG/INV';
        $itemCategory = $item['category'] ?? 'atk';
        $itemUnit = $item['unit'] ?? 'Unit';
        $unitPrice = $item['unit_price'] ?? 0;
        $totalAmount = (int)$validated['quantity'] * $unitPrice;

        $allReceipts = $demo->getStockReceipts();
        $count = count($allReceipts) + 1;
        $receiptCode = "MASUK/LOG/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newReceipt = [
            'id' => 'RCV-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'receipt_code' => $receiptCode,
            'delivery_order_no' => $validated['delivery_order_no'],
            'receipt_date' => $validated['receipt_date'],
            'item_id' => $validated['item_id'],
            'item_name' => $itemName,
            'item_code' => $itemCode,
            'category' => $itemCategory,
            'quantity' => (int)$validated['quantity'],
            'unit' => $itemUnit,
            'unit_price' => (float)$unitPrice,
            'total_amount' => (float)$totalAmount,
            'source_type' => $validated['source_type'],
            'source_label' => $sourceLabels[$validated['source_type']] ?? 'Logistik',
            'source_name' => $validated['source_name'],
            'officer_name' => $validated['officer_name'],
            'storage_location' => $validated['storage_location'],
            'condition_received' => $validated['condition_received'],
            'notes' => $validated['notes'] ?: '-'
        ];

        // 1. Catat ke demo_stock_receipts_added
        $sessionAdded = session('demo_stock_receipts_added', []);
        $sessionAdded[] = $newReceipt;
        session(['demo_stock_receipts_added' => $sessionAdded]);

        // 2. Tambah stok fisik barang terkait secara otomatis di demo_inventory_items_updated
        if ($item) {
            $sessionItemUpdated = session('demo_inventory_items_updated', []);
            $existingItemUpdate = $sessionItemUpdated[$item['id']] ?? [];
            $currentStock = $existingItemUpdate['stock'] ?? $item['stock'];
            $newStock = $currentStock + (int)$validated['quantity'];

            $sessionItemUpdated[$item['id']] = array_merge($existingItemUpdate, [
                'stock' => $newStock
            ]);
            session(['demo_inventory_items_updated' => $sessionItemUpdated]);
        }

        return redirect()->route('inventaris.penerimaan')->with('success', "Penerimaan barang \"{$itemName}\" ({$validated['quantity']} {$itemUnit}) berhasil dicatat ({$receiptCode}) dan stok fisik telah bertambah otomatis!");
    }

    /**
     * Hapus Catatan Penerimaan Barang dari Sesi Demo
     */
    public function destroyPenerimaan(string $id)
    {
        $sessionDeleted = session('demo_stock_receipts_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_stock_receipts_deleted' => $sessionDeleted]);
        }

        return redirect()->route('inventaris.penerimaan')->with('info', "Catatan penerimaan barang ID: {$id} berhasil dihapus dari sesi demo.");
    }

    /**
     * T12.3 - Stock Issue / Pengeluaran & Distribusi Barang Logistik
     */
    public function pengeluaran(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $issues = $demo->getStockIssues();
        $items = $demo->getInventoryItems();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, rekap_unit
        $unitFilter = $request->get('unit', 'all');
        $dateFilter = $request->get('date');
        $search = $request->get('search');

        $filteredIssues = array_filter($issues, function ($iss) use ($unitFilter, $dateFilter, $search) {
            $matchUnit = ($unitFilter === 'all') || (($iss['unit_id'] ?? '') === $unitFilter);
            $matchDate = empty($dateFilter) || (($iss['issue_date'] ?? '') === $dateFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($iss['item_name'] ?? '', $search) !== false ||
                               stripos($iss['issue_code'] ?? '', $search) !== false ||
                               stripos($iss['request_number'] ?? '', $search) !== false ||
                               stripos($iss['recipient_name'] ?? '', $search) !== false ||
                               stripos($iss['destination'] ?? '', $search) !== false ||
                               stripos($iss['purpose'] ?? '', $search) !== false;
            }
            return $matchUnit && $matchDate && $matchSearch;
        });

        // Metrik Ringkasan Pengeluaran
        $totalTransactions = count($issues);
        $totalItemsIssued = array_sum(array_column($issues, 'quantity'));
        $smaCount = count(array_filter($issues, fn($i) => ($i['unit_id'] ?? '') === 'UNIT-SMA'));
        $smpCount = count(array_filter($issues, fn($i) => ($i['unit_id'] ?? '') === 'UNIT-SMP'));

        // Sebaran Unit Penerima
        $unitSummary = [];
        foreach ($issues as $i) {
            $u = $i['unit_id'] ?? 'UMUM';
            if (!isset($unitSummary[$u])) {
                $unitSummary[$u] = [
                    'label' => $u,
                    'count' => 0,
                    'quantity' => 0
                ];
            }
            $unitSummary[$u]['count']++;
            $unitSummary[$u]['quantity'] += $i['quantity'];
        }

        return view('inventaris.pengeluaran', compact(
            'filteredIssues',
            'issues',
            'items',
            'units',
            'activeTab',
            'unitFilter',
            'dateFilter',
            'search',
            'totalTransactions',
            'totalItemsIssued',
            'smaCount',
            'smpCount',
            'unitSummary'
        ));
    }

    /**
     * Catat Pengeluaran / Distribusi Barang Baru (Otomatis Kurangi Stok Fisik)
     */
    public function storePengeluaran(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|string',
            'request_number' => 'required|string|max:100',
            'issue_date' => 'required|date',
            'quantity' => 'required|numeric|min:1',
            'recipient_name' => 'required|string|max:100',
            'recipient_role' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'destination' => 'required|string|max:150',
            'purpose' => 'required|string|max:255',
            'officer_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $demo = DemoDataManager::getInstance();
        $item = $demo->findInventoryItem($validated['item_id']);

        if (!$item) {
            return redirect()->back()->withErrors(['item_id' => 'Barang inventaris tidak ditemukan di sistem!']);
        }

        // Cek ketersediaan stok fisik
        $currentStock = $item['stock'] ?? 0;
        $reqQuantity = (int)$validated['quantity'];

        if ($reqQuantity > $currentStock) {
            return redirect()->back()->withErrors([
                'quantity' => "Permintaan gagal! Kuantitas diminta ({$reqQuantity} {$item['unit']}) melebihi sisa stok fisik yang tersedia di gudang ({$currentStock} {$item['unit']})."
            ])->withInput();
        }

        $itemName = $item['name'];
        $itemCode = $item['item_code'];
        $itemCategory = $item['category'];
        $itemUnit = $item['unit'];

        $allIssues = $demo->getStockIssues();
        $count = count($allIssues) + 1;
        $issueCode = "KELUAR/LOG/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newIssue = [
            'id' => 'ISS-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'issue_code' => $issueCode,
            'request_number' => $validated['request_number'],
            'issue_date' => $validated['issue_date'],
            'item_id' => $validated['item_id'],
            'item_name' => $itemName,
            'item_code' => $itemCode,
            'category' => $itemCategory,
            'quantity' => $reqQuantity,
            'unit' => $itemUnit,
            'recipient_name' => $validated['recipient_name'],
            'recipient_role' => $validated['recipient_role'],
            'unit_id' => $validated['unit_id'],
            'destination' => $validated['destination'],
            'purpose' => $validated['purpose'],
            'officer_name' => $validated['officer_name'],
            'status' => 'diserahkan',
            'notes' => $validated['notes'] ?: '-'
        ];

        // 1. Simpan ke demo_stock_issues_added
        $sessionAdded = session('demo_stock_issues_added', []);
        $sessionAdded[] = $newIssue;
        session(['demo_stock_issues_added' => $sessionAdded]);

        // 2. Kurangi stok fisik barang di demo_inventory_items_updated
        $sessionItemUpdated = session('demo_inventory_items_updated', []);
        $existingItemUpdate = $sessionItemUpdated[$item['id']] ?? [];
        $newStock = $currentStock - $reqQuantity;

        $sessionItemUpdated[$item['id']] = array_merge($existingItemUpdate, [
            'stock' => $newStock
        ]);
        session(['demo_inventory_items_updated' => $sessionItemUpdated]);

        return redirect()->route('inventaris.pengeluaran')->with('success', "Pengeluaran barang \"{$itemName}\" ({$reqQuantity} {$itemUnit}) berhasil dicatat ({$issueCode}) dan sisa stok fisik otomatis dikurangi (sisa: {$newStock} {$itemUnit})!");
    }

    /**
     * Hapus Catatan Pengeluaran Barang dari Sesi Demo
     */
    public function destroyPengeluaran(string $id)
    {
        $sessionDeleted = session('demo_stock_issues_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_stock_issues_deleted' => $sessionDeleted]);
        }

        return redirect()->route('inventaris.pengeluaran')->with('info', "Catatan pengeluaran barang ID: {$id} berhasil dihapus dari sesi demo.");
    }
}

