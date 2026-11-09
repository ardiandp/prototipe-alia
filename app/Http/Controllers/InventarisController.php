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

    /**
     * T12.4 - Procurement Simulation / Simulasi Pengadaan Barang Gudang & PO
     */
    public function pengadaan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $procurements = $demo->getInventoryProcurements();
        $items = $demo->getInventoryItems();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, persetujuan, po
        $statusFilter = $request->get('status', 'all');
        $unitFilter = $request->get('unit', 'all');
        $search = $request->get('search');

        $filteredProcurements = array_filter($procurements, function ($po) use ($statusFilter, $unitFilter, $search) {
            $matchStatus = ($statusFilter === 'all') || (($po['status'] ?? '') === $statusFilter);
            $matchUnit = ($unitFilter === 'all') || (($po['unit_id'] ?? '') === $unitFilter);
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($po['title'] ?? '', $search) !== false ||
                               stripos($po['po_number'] ?? '', $search) !== false ||
                               stripos($po['request_code'] ?? '', $search) !== false ||
                               stripos($po['vendor_name'] ?? '', $search) !== false ||
                               stripos($po['requester_name'] ?? '', $search) !== false;
            }
            return $matchStatus && $matchUnit && $matchSearch;
        });

        // Metrik Ringkasan Pengadaan
        $totalOrders = count($procurements);
        $totalBudget = array_sum(array_column($procurements, 'total_amount'));
        $totalPOIssued = count(array_filter($procurements, fn($p) => in_array($p['status'] ?? '', ['terbit_po', 'diterima'])));
        $totalPending = count(array_filter($procurements, fn($p) => ($p['status'] ?? '') === 'diajukan'));

        return view('inventaris.pengadaan', compact(
            'filteredProcurements',
            'procurements',
            'items',
            'units',
            'activeTab',
            'statusFilter',
            'unitFilter',
            'search',
            'totalOrders',
            'totalBudget',
            'totalPOIssued',
            'totalPending'
        ));
    }

    /**
     * Buat Usulan Permintaan Pengadaan Barang Baru (Request)
     */
    public function storePengadaan(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'requester_name' => 'required|string|max:100',
            'requester_role' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'request_date' => 'required|date',
            'vendor_name' => 'required|string|max:150',
            'vendor_contact' => 'nullable|string|max:150',
            'source_funding' => 'required|string|max:100',
            'item_names' => 'required|array|min:1',
            'item_names.*' => 'required|string|max:150',
            'item_quantities' => 'required|array|min:1',
            'item_quantities.*' => 'required|numeric|min:1',
            'item_units' => 'required|array|min:1',
            'item_units.*' => 'required|string|max:50',
            'item_prices' => 'required|array|min:1',
            'item_prices.*' => 'required|numeric|min:0',
            'item_ids' => 'nullable|array',
            'notes' => 'nullable|string|max:500',
        ]);

        $items = [];
        $totalAmount = 0;

        foreach ($validated['item_names'] as $idx => $name) {
            $qty = (int)($validated['item_quantities'][$idx] ?? 1);
            $unit = $validated['item_units'][$idx] ?? 'Unit';
            $price = (float)($validated['item_prices'][$idx] ?? 0);
            $subtotal = $qty * $price;
            $itemId = $validated['item_ids'][$idx] ?? null;

            $items[] = [
                'item_id' => $itemId ?: null,
                'item_name' => $name,
                'quantity' => $qty,
                'unit' => $unit,
                'unit_price' => $price,
                'subtotal' => $subtotal
            ];
            $totalAmount += $subtotal;
        }

        $demo = DemoDataManager::getInstance();
        $allProcurements = $demo->getInventoryProcurements();
        $count = count($allProcurements) + 1;
        $requestCode = "REQ/LOG/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);

        $newProcurement = [
            'id' => 'PO-' . str_pad($count, 3, '0', STR_PAD_LEFT),
            'po_number' => null,
            'request_code' => $requestCode,
            'title' => $validated['title'],
            'requester_name' => $validated['requester_name'],
            'requester_role' => $validated['requester_role'],
            'unit_id' => $validated['unit_id'],
            'request_date' => $validated['request_date'],
            'vendor_name' => $validated['vendor_name'],
            'vendor_contact' => $validated['vendor_contact'] ?: 'Vendor Rekanan Alia',
            'items' => $items,
            'total_amount' => $totalAmount,
            'source_funding' => $validated['source_funding'],
            'status' => 'diajukan',
            'approver_name' => null,
            'approved_at' => null,
            'po_issued_at' => null,
            'received_at' => null,
            'rejection_reason' => null,
            'notes' => $validated['notes'] ?: '-'
        ];

        $sessionAdded = session('demo_inventory_procurements_added', []);
        $sessionAdded[] = $newProcurement;
        session(['demo_inventory_procurements_added' => $sessionAdded]);

        return redirect()->route('inventaris.pengadaan')->with('success', "Permintaan pengadaan \"{$newProcurement['title']}\" ({$requestCode}) berhasil diajukan!");
    }

    /**
     * Approval / Penerbitan Purchase Order (PO)
     */
    public function approvePengadaan(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,terbit_po,ditolak',
            'approver_name' => 'required|string|max:100',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $sessionUpdated = session('demo_inventory_procurements_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $demo = DemoDataManager::getInstance();
        $procurement = $demo->findInventoryProcurement($id);

        $updateData = [
            'status' => $validated['status'],
            'approver_name' => $validated['approver_name'],
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $validated['status'] === 'ditolak' ? ($validated['rejection_reason'] ?: 'Ditolak pimpinan / anggaran tidak tersedia.') : null,
        ];

        if ($validated['status'] === 'terbit_po') {
            $poNumber = $procurement['po_number'] ?? ("PO/LOG/" . date('Y') . "/" . substr($id, -3));
            $updateData['po_number'] = $poNumber;
            $updateData['po_issued_at'] = date('Y-m-d H:i:s');
        }

        $sessionUpdated[$id] = array_merge($existing, $updateData);
        session(['demo_inventory_procurements_updated' => $sessionUpdated]);

        $statusLabels = [
            'disetujui' => 'DISETUJUI',
            'terbit_po' => 'TERBIT PURCHASE ORDER (PO)',
            'ditolak' => 'DITOLAK'
        ];
        $label = $statusLabels[$validated['status']] ?? $validated['status'];

        return redirect()->route('inventaris.pengadaan')->with('success', "Status pengadaan {$id} berhasil diperbarui ({$label})!");
    }

    /**
     * Konfirmasi Penerimaan Barang dari PO (Receipt -> Otomatis Tambah Stok)
     */
    public function receivePengadaan(Request $request, string $id)
    {
        $validated = $request->validate([
            'officer_name' => 'required|string|max:100',
            'delivery_order_no' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $demo = DemoDataManager::getInstance();
        $procurement = $demo->findInventoryProcurement($id);

        if (!$procurement) {
            return redirect()->back()->withErrors(['error' => 'Data pengadaan PO tidak ditemukan!']);
        }

        // 1. Update status PO menjadi diterima
        $sessionUpdated = session('demo_inventory_procurements_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $sessionUpdated[$id] = array_merge($existing, [
            'status' => 'diterima',
            'received_at' => date('Y-m-d H:i:s'),
            'notes' => "Diterima lengkap oleh {$validated['officer_name']} (DO: {$validated['delivery_order_no']}). " . ($validated['notes'] ?? '')
        ]);
        session(['demo_inventory_procurements_updated' => $sessionUpdated]);

        // 2. Tambah stok fisik barang terkait secara otomatis ke katalog master
        $sessionItemUpdated = session('demo_inventory_items_updated', []);
        foreach ($procurement['items'] as $item) {
            if (!empty($item['item_id'])) {
                $invItem = $demo->findInventoryItem($item['item_id']);
                if ($invItem) {
                    $existingItemUpdate = $sessionItemUpdated[$invItem['id']] ?? [];
                    $currentStock = $existingItemUpdate['stock'] ?? $invItem['stock'];
                    $newStock = $currentStock + (int)$item['quantity'];

                    $sessionItemUpdated[$invItem['id']] = array_merge($existingItemUpdate, [
                        'stock' => $newStock
                    ]);
                }
            }
        }
        session(['demo_inventory_items_updated' => $sessionItemUpdated]);

        // 3. Catat transaksi log penerimaan masuk (Stock Receipt)
        $sessionReceiptsAdded = session('demo_stock_receipts_added', []);
        $firstItem = $procurement['items'][0] ?? null;
        if ($firstItem) {
            $count = count($demo->getStockReceipts()) + 1;
            $receiptCode = "MASUK/LOG/" . date('Y') . "/" . str_pad($count, 3, '0', STR_PAD_LEFT);
            $sessionReceiptsAdded[] = [
                'id' => 'RCV-' . str_pad($count, 3, '0', STR_PAD_LEFT),
                'receipt_code' => $receiptCode,
                'delivery_order_no' => $validated['delivery_order_no'],
                'receipt_date' => date('Y-m-d'),
                'item_id' => $firstItem['item_id'] ?? 'INV-001',
                'item_name' => $procurement['title'],
                'item_code' => 'BRG/PO/' . substr($id, -3),
                'category' => 'atk',
                'quantity' => array_sum(array_column($procurement['items'], 'quantity')),
                'unit' => 'Unit/Pcs',
                'unit_price' => $procurement['total_amount'] / max(1, array_sum(array_column($procurement['items'], 'quantity'))),
                'total_amount' => $procurement['total_amount'],
                'source_type' => 'pembelian_vendor',
                'source_label' => 'Realisasi PO: ' . ($procurement['po_number'] ?? $id),
                'source_name' => $procurement['vendor_name'],
                'officer_name' => $validated['officer_name'],
                'storage_location' => 'Gudang Logistik Utama',
                'condition_received' => 'baik',
                'notes' => 'Penerimaan otomatis dari penyelesaian Purchase Order ' . ($procurement['po_number'] ?? $id)
            ];
            session(['demo_stock_receipts_added' => $sessionReceiptsAdded]);
        }

        return redirect()->route('inventaris.pengadaan')->with('success', "Barang dari {$id} (" . ($procurement['po_number'] ?? 'PO') . ") berhasil dikonfirmasi diterima dan stok fisik inventaris otomatis bertambah!");
    }

    /**
     * Hapus Pengadaan Barang dari Sesi Demo
     */
    public function destroyPengadaan(string $id)
    {
        $sessionDeleted = session('demo_inventory_procurements_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_inventory_procurements_deleted' => $sessionDeleted]);
        }

        return redirect()->route('inventaris.pengadaan')->with('info', "Pengadaan logistik ID: {$id} berhasil dihapus dari sesi demo.");
    }
}

