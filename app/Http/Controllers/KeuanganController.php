<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class KeuanganController extends Controller
{
    /**
     * T09.1 — Finance Dashboard (Dashboard Eksekutif Keuangan & SPP)
     */
    public function dashboard(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $units = $demo->getUnits();
        $invoices = $demo->getStudentInvoices();
        $transactions = $demo->getPaymentTransactions();
        $expenses = $demo->getFinanceExpenses();
        $feeCategories = $demo->getFeeCategories();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        // Filters
        $unitFilter = $request->query('unit_id');
        $periodFilter = $request->query('period', '2026-11'); // YYYY-MM

        // Student & Class Maps
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        // Filter Invoices
        $filteredInvoices = array_filter($invoices, function ($inv) use ($unitFilter, $periodFilter) {
            if ($unitFilter && ($inv['unit_id'] ?? '') !== $unitFilter) {
                return false;
            }
            if ($periodFilter && ($inv['period_month'] ?? '') !== $periodFilter) {
                return false;
            }
            return true;
        });

        // Filter Transactions
        $filteredTransactions = array_filter($transactions, function ($trx) use ($unitFilter, $periodFilter, $studentMap) {
            if ($unitFilter) {
                $st = $studentMap[$trx['student_id']] ?? null;
                if (!$st || ($st['unit_id'] ?? '') !== $unitFilter) {
                    return false;
                }
            }
            if ($periodFilter && !str_starts_with($trx['payment_date'], $periodFilter)) {
                return false;
            }
            return true;
        });

        // Filter Expenses
        $filteredExpenses = array_filter($expenses, function ($exp) use ($unitFilter, $periodFilter) {
            if ($unitFilter && ($exp['unit_id'] ?? '') !== $unitFilter) {
                return false;
            }
            if ($periodFilter && !str_starts_with($exp['date'], $periodFilter)) {
                return false;
            }
            return true;
        });

        // KPI Calculations
        $totalInvoiced = array_sum(array_column($filteredInvoices, 'amount'));
        $totalCollected = array_sum(array_column($filteredTransactions, 'amount'));
        $totalExpense = array_sum(array_column($filteredExpenses, 'amount'));
        $netBalance = $totalCollected - $totalExpense;

        // Arrears / Tunggakan
        $unpaidInvoices = array_filter($filteredInvoices, fn($inv) => in_array($inv['status'], ['belum_lunas', 'jatuh_tempo', 'sebagian']));
        $totalArrears = 0;
        foreach ($unpaidInvoices as $uInv) {
            $totalArrears += ($uInv['amount'] - ($uInv['paid_amount'] ?? 0));
        }

        $collectionRate = $totalInvoiced > 0 ? round(($totalCollected / $totalInvoiced) * 100, 1) : 0;

        // Status Breakdown
        $countLunas = count(array_filter($filteredInvoices, fn($i) => $i['status'] === 'lunas'));
        $countSebagian = count(array_filter($filteredInvoices, fn($i) => $i['status'] === 'sebagian'));
        $countBelumLunas = count(array_filter($filteredInvoices, fn($i) => $i['status'] === 'belum_lunas'));
        $countJatuhTempo = count(array_filter($filteredInvoices, fn($i) => $i['status'] === 'jatuh_tempo'));

        // Recent Invoices Enriched
        $recentInvoices = [];
        foreach (array_slice($filteredInvoices, 0, 5) as $inv) {
            $st = $studentMap[$inv['student_id']] ?? null;
            $recentInvoices[] = array_merge($inv, [
                'student_name' => $st['name'] ?? '-',
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-'
            ]);
        }

        // Recent Transactions Enriched
        $recentTransactions = [];
        foreach (array_slice($filteredTransactions, 0, 5) as $trx) {
            $st = $studentMap[$trx['student_id']] ?? null;
            $recentTransactions[] = array_merge($trx, [
                'student_name' => $st['name'] ?? '-',
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-'
            ]);
        }

        return view('keuangan.dashboard', compact(
            'units',
            'unitFilter',
            'periodFilter',
            'totalInvoiced',
            'totalCollected',
            'totalExpense',
            'netBalance',
            'totalArrears',
            'collectionRate',
            'countLunas',
            'countSebagian',
            'countBelumLunas',
            'countJatuhTempo',
            'filteredInvoices',
            'filteredTransactions',
            'filteredExpenses',
            'recentInvoices',
            'recentTransactions'
        ));
    }

    /**
     * T09.2 — Fee Master (Master Data Pos Biaya & Tarif Sekolah)
     */
    public function posBiaya(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $units = $demo->getUnits();
        $feeCategories = $demo->getFeeCategories();

        $unitFilter = $request->query('unit_id');
        $categoryFilter = $request->query('category');
        $periodTypeFilter = $request->query('period_type');
        $search = strtolower(trim($request->query('q', '')));

        // Unit Map
        $unitMap = [];
        foreach ($units as $u) {
            $unitMap[$u['id']] = $u;
        }

        // Filter List
        $filteredFees = array_filter($feeCategories, function ($fee) use ($unitFilter, $categoryFilter, $periodTypeFilter, $search) {
            if ($unitFilter && ($fee['unit_id'] ?? '') !== $unitFilter && ($fee['unit_id'] ?? '') !== 'ALL') {
                return false;
            }

            if ($categoryFilter && ($fee['category'] ?? '') !== $categoryFilter) {
                return false;
            }

            if ($periodTypeFilter && ($fee['period_type'] ?? '') !== $periodTypeFilter) {
                return false;
            }

            if ($search) {
                $haystack = strtolower(
                    ($fee['name'] ?? '') . ' ' .
                    ($fee['code'] ?? '') . ' ' .
                    ($fee['category'] ?? '') . ' ' .
                    ($fee['description'] ?? '')
                );
                if (strpos($haystack, $search) === false) {
                    return false;
                }
            }

            return true;
        });

        // Metrik
        $totalTarif = count($feeCategories);
        $totalSpp = count(array_filter($feeCategories, fn($f) => ($f['category'] ?? '') === 'SPP'));
        $totalNonSpp = $totalTarif - $totalSpp;
        $totalBulanan = count(array_filter($feeCategories, fn($f) => ($f['period_type'] ?? '') === 'bulanan'));

        // Kategori Unik untuk dropdown
        $categories = array_values(array_unique(array_column($feeCategories, 'category')));

        return view('keuangan.pos-biaya', compact(
            'units',
            'feeCategories',
            'filteredFees',
            'unitFilter',
            'categoryFilter',
            'periodTypeFilter',
            'search',
            'totalTarif',
            'totalSpp',
            'totalNonSpp',
            'totalBulanan',
            'categories',
            'unitMap'
        ));
    }

    /**
     * Store new fee category (Session-backed)
     */
    public function storePosBiaya(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50',
            'category' => 'required|string|max:50',
            'unit_id' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'period_type' => 'required|string|in:bulanan,tahunan,sekali',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:aktif,nonaktif'
        ]);

        $demo = DemoDataManager::getInstance();
        $existing = $demo->getFeeCategories();

        $sessionAdded = session('demo_fee_categories_added', []);
        $newId = 'FEE-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);

        $unitName = 'Semua Unit';
        foreach ($demo->getUnits() as $u) {
            if ($u['id'] === $validated['unit_id']) {
                $unitName = $u['name'];
                break;
            }
        }

        $newItem = [
            'id' => $newId,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'category' => $validated['category'],
            'unit_id' => $validated['unit_id'],
            'unit_name' => $unitName,
            'amount' => (int)$validated['amount'],
            'period_type' => $validated['period_type'],
            'description' => $validated['description'] ?: 'Pos biaya pendidikan resmi Alia Islamic School.',
            'status' => $validated['status'] ?: 'aktif'
        ];

        $sessionAdded[] = $newItem;
        session(['demo_fee_categories_added' => $sessionAdded]);

        return redirect()->route('keuangan.pos-biaya')
            ->with('success', "Pos biaya baru [{$validated['name']}] dengan tarif Rp " . number_format($validated['amount'], 0, ',', '.') . " berhasil ditambahkan!");
    }

    /**
     * Destroy fee category (Session-backed)
     */
    public function destroyPosBiaya(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $fee = $demo->findFeeCategory($id);

        if (!$fee) {
            return redirect()->route('keuangan.pos-biaya')->with('error', 'Pos biaya tidak ditemukan.');
        }

        $sessionDeleted = session('demo_fee_categories_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_fee_categories_deleted' => $sessionDeleted]);
        }

        return redirect()->route('keuangan.pos-biaya')
            ->with('success', "Pos biaya [{$fee['name']}] ({$fee['code']}) berhasil dihapus dari sistem.");
    }

    /**
     * T09.3 — Student Invoices (Daftar Tagihan & Penerbitan Invoice Santri)
     */
    public function tagihan(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $units = $demo->getUnits();
        $invoices = $demo->getStudentInvoices();
        $feeCategories = $demo->getFeeCategories();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();

        // Maps
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $feeMap = [];
        foreach ($feeCategories as $f) {
            $feeMap[$f['id']] = $f;
        }

        $unitMap = [];
        foreach ($units as $u) {
            $unitMap[$u['id']] = $u;
        }

        // Filters
        $unitFilter = $request->query('unit_id');
        $statusFilter = $request->query('status');
        $monthFilter = $request->query('month');
        $search = strtolower(trim($request->query('q', '')));

        $filteredInvoices = array_filter($invoices, function ($inv) use ($unitFilter, $statusFilter, $monthFilter, $search, $studentMap) {
            if ($unitFilter && ($inv['unit_id'] ?? '') !== $unitFilter) {
                return false;
            }

            if ($statusFilter && ($inv['status'] ?? '') !== $statusFilter) {
                return false;
            }

            if ($monthFilter && ($inv['period_month'] ?? '') !== $monthFilter) {
                return false;
            }

            if ($search) {
                $st = $studentMap[$inv['student_id']] ?? null;
                $haystack = strtolower(
                    ($inv['invoice_number'] ?? '') . ' ' .
                    ($inv['fee_title'] ?? '') . ' ' .
                    ($st['name'] ?? '') . ' ' .
                    ($st['nisn'] ?? ($st['nis'] ?? ''))
                );
                if (strpos($haystack, $search) === false) {
                    return false;
                }
            }

            return true;
        });

        // Enriched Invoices
        $enrichedInvoices = [];
        foreach ($filteredInvoices as $inv) {
            $st = $studentMap[$inv['student_id']] ?? null;
            $enrichedInvoices[] = array_merge($inv, [
                'student_name' => $st['name'] ?? 'Santri ID: ' . $inv['student_id'],
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-',
                'guardian_name' => $st['guardian_name'] ?? '-',
                'guardian_phone' => $st['guardian_phone'] ?? '-',
                'unit_name' => $unitMap[$inv['unit_id']]['name'] ?? ($inv['unit_id'] ?? '-')
            ]);
        }

        // Metrik
        $totalTagihan = count($invoices);
        $nominalTotalTagihan = array_sum(array_column($invoices, 'amount'));
        $totalLunas = count(array_filter($invoices, fn($i) => $i['status'] === 'lunas'));
        $totalBelumLunas = count(array_filter($invoices, fn($i) => in_array($i['status'], ['belum_lunas', 'jatuh_tempo', 'sebagian'])));
        $nominalTunggakan = 0;
        foreach ($invoices as $inv) {
            if (in_array($inv['status'], ['belum_lunas', 'jatuh_tempo', 'sebagian'])) {
                $nominalTunggakan += ($inv['amount'] - ($inv['paid_amount'] ?? 0));
            }
        }

        return view('keuangan.tagihan', compact(
            'units',
            'students',
            'feeCategories',
            'enrichedInvoices',
            'unitFilter',
            'statusFilter',
            'monthFilter',
            'search',
            'totalTagihan',
            'nominalTotalTagihan',
            'totalLunas',
            'totalBelumLunas',
            'nominalTunggakan'
        ));
    }

    /**
     * Store new student invoice (Session-backed)
     */
    public function storeTagihan(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'fee_category_id' => 'required|string',
            'period_month' => 'required|string|max:10', // YYYY-MM
            'amount' => 'required|numeric|min:1000',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:255'
        ]);

        $demo = DemoDataManager::getInstance();
        $student = $demo->findStudent($validated['student_id']);
        $feeCategory = $demo->findFeeCategory($validated['fee_category_id']);
        $existing = $demo->getStudentInvoices();

        $sessionAdded = session('demo_student_invoices_added', []);
        $newNumber = 'INV/ALIA/' . date('Y/m/') . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);
        $newId = 'INV-' . date('Ym') . '-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);

        $feeTitle = ($feeCategory['name'] ?? 'Tagihan Pendidikan') . ' - ' . date('F Y', strtotime($validated['period_month'] . '-01'));

        $newItem = [
            'id' => $newId,
            'invoice_number' => $newNumber,
            'student_id' => $validated['student_id'],
            'fee_category_id' => $validated['fee_category_id'],
            'fee_title' => $feeTitle,
            'unit_id' => $student['unit_id'] ?? ($feeCategory['unit_id'] ?? 'UNIT-SMA'),
            'period_month' => $validated['period_month'],
            'amount' => (int)$validated['amount'],
            'paid_amount' => 0,
            'status' => 'belum_lunas',
            'due_date' => $validated['due_date'],
            'paid_date' => null,
            'created_date' => date('Y-m-d'),
            'notes' => $validated['notes'] ?: 'Invoice tagihan resmi diterbitkan bagian Tata Usaha Keuangan Alia.'
        ];

        $sessionAdded[] = $newItem;
        session(['demo_student_invoices_added' => $sessionAdded]);

        return redirect()->route('keuangan.tagihan')
            ->with('success', "Tagihan baru [{$newNumber}] atas nama santri {$student['name']} berhasil diterbitkan!");
    }

    /**
     * Destroy invoice (Session-backed)
     */
    public function destroyTagihan(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $inv = $demo->findStudentInvoice($id);

        if (!$inv) {
            return redirect()->route('keuangan.tagihan')->with('error', 'Tagihan tidak ditemukan.');
        }

        $sessionDeleted = session('demo_student_invoices_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_student_invoices_deleted' => $sessionDeleted]);
        }

        return redirect()->route('keuangan.tagihan')
            ->with('success', "Invoice tagihan [{$inv['invoice_number']}] berhasil dibatalkan / dihapus.");
    }

    /**
     * T09.4 — Payment Simulation (Simulasi Penerimaan Kasir & Pembayaran Tagihan)
     */
    public function transaksi(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $transactions = $demo->getPaymentTransactions();
        $invoices = $demo->getStudentInvoices();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();
        $units = $demo->getUnits();

        // Maps
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $invoiceMap = [];
        foreach ($invoices as $inv) {
            $invoiceMap[$inv['id']] = $inv;
        }

        // Filters
        $methodFilter = $request->query('method');
        $dateFilter = $request->query('date');
        $search = strtolower(trim($request->query('q', '')));

        $filteredTransactions = array_filter($transactions, function ($trx) use ($methodFilter, $dateFilter, $search, $studentMap, $invoiceMap) {
            if ($methodFilter && ($trx['payment_method'] ?? '') !== $methodFilter) {
                return false;
            }

            if ($dateFilter && !str_starts_with($trx['payment_date'] ?? '', $dateFilter)) {
                return false;
            }

            if ($search) {
                $st = $studentMap[$trx['student_id']] ?? null;
                $inv = $invoiceMap[$trx['invoice_id']] ?? null;
                $haystack = strtolower(
                    ($trx['receipt_number'] ?? '') . ' ' .
                    ($st['name'] ?? '') . ' ' .
                    ($st['nisn'] ?? ($st['nis'] ?? '')) . ' ' .
                    ($inv['invoice_number'] ?? '') . ' ' .
                    ($trx['cashier_name'] ?? '')
                );
                if (strpos($haystack, $search) === false) {
                    return false;
                }
            }

            return true;
        });

        // Enriched Transactions
        $enrichedTransactions = [];
        foreach ($filteredTransactions as $trx) {
            $st = $studentMap[$trx['student_id']] ?? null;
            $inv = $invoiceMap[$trx['invoice_id']] ?? null;
            $enrichedTransactions[] = array_merge($trx, [
                'student_name' => $st['name'] ?? 'Santri ID: ' . $trx['student_id'],
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-',
                'guardian_name' => $st['guardian_name'] ?? '-',
                'invoice_number' => $inv['invoice_number'] ?? '-',
                'fee_title' => $inv['fee_title'] ?? 'Pembayaran Pendidikan'
            ]);
        }

        // Metrik Transaksi
        $totalTrx = count($transactions);
        $totalNominalTrx = array_sum(array_column($transactions, 'amount'));
        $countVaBsi = count(array_filter($transactions, fn($t) => str_contains($t['payment_method'], 'VA Bank Syariah Indonesia')));
        $countTunai = count(array_filter($transactions, fn($t) => str_contains($t['payment_method'], 'Tunai') || str_contains($t['payment_method'], 'Kasir')));
        $countQris = count(array_filter($transactions, fn($t) => str_contains($t['payment_method'], 'QRIS') || str_contains($t['payment_method'], 'Transfer')));

        // Invoice belum lunas untuk modal input bayar
        $pendingInvoices = array_filter($invoices, fn($i) => in_array($i['status'], ['belum_lunas', 'sebagian', 'jatuh_tempo']));

        return view('keuangan.transaksi', compact(
            'transactions',
            'enrichedTransactions',
            'pendingInvoices',
            'students',
            'studentMap',
            'methodFilter',
            'dateFilter',
            'search',
            'totalTrx',
            'totalNominalTrx',
            'countVaBsi',
            'countTunai',
            'countQris'
        ));
    }

    /**
     * Store simulated payment transaction (Session-backed & updates invoice)
     */
    public function storeTransaksi(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'cashier_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:255'
        ]);

        $demo = DemoDataManager::getInstance();
        $invoice = $demo->findStudentInvoice($validated['invoice_id']);

        if (!$invoice) {
            return redirect()->route('keuangan.transaksi')->with('error', 'Invoice tagihan tidak ditemukan.');
        }

        $existingTrx = $demo->getPaymentTransactions();
        $sessionTrxAdded = session('demo_payment_transactions_added', []);
        $newReceipt = 'KWT/ALIA/' . date('Y/m/') . str_pad((string)(count($existingTrx) + count($sessionTrxAdded) + 1), 3, '0', STR_PAD_LEFT);
        $newTrxId = 'TRX-' . date('Ym') . '-' . str_pad((string)(count($existingTrx) + count($sessionTrxAdded) + 1), 3, '0', STR_PAD_LEFT);

        $payAmount = (int)$validated['amount'];
        $currentPaid = (int)($invoice['paid_amount'] ?? 0);
        $totalPaidNow = $currentPaid + $payAmount;
        $totalInvoiceAmount = (int)$invoice['amount'];

        // Determine new invoice status
        $newInvoiceStatus = 'lunas';
        if ($totalPaidNow < $totalInvoiceAmount) {
            $newInvoiceStatus = 'sebagian';
        }

        // 1. Simpan Transaksi Baru
        $newTrx = [
            'id' => $newTrxId,
            'receipt_number' => $newReceipt,
            'invoice_id' => $invoice['id'],
            'student_id' => $invoice['student_id'],
            'amount' => $payAmount,
            'payment_method' => $validated['payment_method'],
            'payment_date' => date('Y-m-d H:i:s'),
            'cashier_name' => $validated['cashier_name'],
            'status' => 'berhasil',
            'notes' => $validated['notes'] ?: 'Simulasi pembayaran kasir loket keuangan berhasil diproses.'
        ];

        $sessionTrxAdded[] = $newTrx;
        session(['demo_payment_transactions_added' => $sessionTrxAdded]);

        // 2. Perbarui Status & Nominal Invoice
        $sessionInvoiceUpdated = session('demo_student_invoices_updated', []);
        $sessionInvoiceUpdated[$invoice['id']] = [
            'paid_amount' => $totalPaidNow,
            'status' => $newInvoiceStatus,
            'paid_date' => date('Y-m-d H:i:s')
        ];
        session(['demo_student_invoices_updated' => $sessionInvoiceUpdated]);

        $st = $demo->findStudent($invoice['student_id']);
        $studentName = $st['name'] ?? 'Santri';

        return redirect()->route('keuangan.transaksi')
            ->with('success', "Simulasi pembayaran Rp " . number_format($payAmount, 0, ',', '.') . " untuk invoice [{$invoice['invoice_number']}] ({$studentName}) BERHASIL dicatat! Status invoice kini: " . strtoupper($newInvoiceStatus) . ".");
    }

    /**
     * Destroy payment transaction (Session-backed)
     */
    public function destroyTransaksi(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $trx = $demo->findPaymentTransaction($id);

        if (!$trx) {
            return redirect()->route('keuangan.transaksi')->with('error', 'Transaksi pembayaran tidak ditemukan.');
        }

        $sessionDeleted = session('demo_payment_transactions_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_payment_transactions_deleted' => $sessionDeleted]);
        }

        return redirect()->route('keuangan.transaksi')
            ->with('success', "Transaksi pembayaran [{$trx['receipt_number']}] berhasil dibatalkan.");
    }

    /**
     * T09.5 — Scholarship & Discount (Manajemen Beasiswa & Potongan Tagihan)
     */
    public function beasiswa(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $scholarships = $demo->getScholarships();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();
        $feeCategories = $demo->getFeeCategories();

        // Maps
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        // Filters
        $typeFilter = $request->query('type');
        $statusFilter = $request->query('status');
        $search = strtolower(trim($request->query('q', '')));

        $filteredScholarships = array_filter($scholarships, function ($sch) use ($typeFilter, $statusFilter, $search, $studentMap) {
            if ($typeFilter && ($sch['type'] ?? '') !== $typeFilter) {
                return false;
            }

            if ($statusFilter && ($sch['approval_status'] ?? '') !== $statusFilter) {
                return false;
            }

            if ($search) {
                $st = $studentMap[$sch['student_id']] ?? null;
                $haystack = strtolower(
                    ($sch['code'] ?? '') . ' ' .
                    ($sch['name'] ?? '') . ' ' .
                    ($st['name'] ?? '') . ' ' .
                    ($st['nisn'] ?? '') . ' ' .
                    ($sch['approved_by'] ?? '')
                );
                if (strpos($haystack, $search) === false) {
                    return false;
                }
            }

            return true;
        });

        // Enriched
        $enrichedScholarships = [];
        foreach ($filteredScholarships as $sch) {
            $st = $studentMap[$sch['student_id']] ?? null;
            $enrichedScholarships[] = array_merge($sch, [
                'student_name' => $st['name'] ?? 'Santri ID: ' . $sch['student_id'],
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-'
            ]);
        }

        // Metrik
        $totalBeasiswa = count($scholarships);
        $totalApproved = count(array_filter($scholarships, fn($s) => ($s['approval_status'] ?? '') === 'disetujui'));
        $totalPending = count(array_filter($scholarships, fn($s) => ($s['approval_status'] ?? '') === 'diajukan'));
        $totalAlokasiNominal = array_sum(array_column($scholarships, 'discount_amount'));

        return view('keuangan.beasiswa', compact(
            'scholarships',
            'enrichedScholarships',
            'students',
            'feeCategories',
            'typeFilter',
            'statusFilter',
            'search',
            'totalBeasiswa',
            'totalApproved',
            'totalPending',
            'totalAlokasiNominal'
        ));
    }

    /**
     * Store new scholarship / discount (Session-backed & updates invoice if requested)
     */
    public function storeBeasiswa(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'name' => 'required|string|max:150',
            'type' => 'required|string|in:tahfidz,prestasi,yatim_dhuafa,bersaudara,pegawai',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'fee_category_id' => 'required|string',
            'period' => 'required|string|max:100',
            'approved_by' => 'required|string|max:100',
            'approval_status' => 'required|string|in:disetujui,diajukan,ditolak',
            'notes' => 'nullable|string|max:255',
            'apply_to_invoices' => 'nullable|boolean'
        ]);

        $demo = DemoDataManager::getInstance();
        $student = $demo->findStudent($validated['student_id']);
        $feeCategory = $demo->findFeeCategory($validated['fee_category_id']);
        $existing = $demo->getScholarships();

        $sessionAdded = session('demo_scholarships_added', []);
        $newId = 'SCH-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);
        $newCode = 'SCH-' . strtoupper(substr($validated['type'], 0, 3)) . '-' . (int)$validated['discount_percentage'];

        // Calculate nominal discount
        $baseFee = $feeCategory ? (int)$feeCategory['amount'] : 1850000;
        $discountAmount = round(($baseFee * (float)$validated['discount_percentage']) / 100);

        $newItem = [
            'id' => $newId,
            'code' => $newCode,
            'student_id' => $validated['student_id'],
            'type' => $validated['type'],
            'name' => $validated['name'],
            'discount_percentage' => (int)$validated['discount_percentage'],
            'discount_amount' => (int)$discountAmount,
            'fee_category_id' => $validated['fee_category_id'],
            'fee_name' => $feeCategory['name'] ?? 'SPP Pendidikan',
            'period' => $validated['period'],
            'approved_by' => $validated['approved_by'],
            'approval_status' => $validated['approval_status'],
            'notes' => $validated['notes'] ?: 'Beasiswa / keringanan biaya resmi disetujui Yayasan.'
        ];

        $sessionAdded[] = $newItem;
        session(['demo_scholarships_added' => $sessionAdded]);

        // Auto apply discount to student's pending invoices if requested and approved
        if ($validated['approval_status'] === 'disetujui') {
            $sessionInvoiceUpdated = session('demo_student_invoices_updated', []);
            foreach ($demo->getStudentInvoices() as $inv) {
                if ($inv['student_id'] === $validated['student_id'] && $inv['fee_category_id'] === $validated['fee_category_id'] && $inv['status'] !== 'lunas') {
                    $newAmount = max(0, $inv['amount'] - $discountAmount);
                    $newStatus = $newAmount === 0 ? 'lunas' : $inv['status'];
                    $sessionInvoiceUpdated[$inv['id']] = [
                        'amount' => $newAmount,
                        'status' => $newStatus,
                        'notes' => $inv['notes'] . " (Dipotong beasiswa {$newCode}: -Rp " . number_format($discountAmount, 0, ',', '.') . ")"
                    ];
                }
            }
            session(['demo_student_invoices_updated' => $sessionInvoiceUpdated]);
        }

        return redirect()->route('keuangan.beasiswa')
            ->with('success', "Beasiswa [{$validated['name']}] untuk santri {$student['name']} sebesar {$validated['discount_percentage']}% (Rp " . number_format($discountAmount, 0, ',', '.') . ") berhasil disimpan!");
    }

    /**
     * Approve scholarship (Session-backed)
     */
    public function approveBeasiswa(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $sch = $demo->findScholarship($id);

        if (!$sch) {
            return redirect()->route('keuangan.beasiswa')->with('error', 'Data beasiswa tidak ditemukan.');
        }

        $sessionUpdated = session('demo_scholarships_updated', []);
        $sessionUpdated[$id] = [
            'approval_status' => 'disetujui',
            'approved_by' => 'KH. Abdullah Gymnastiar / Pimpinan Yayasan'
        ];
        session(['demo_scholarships_updated' => $sessionUpdated]);

        return redirect()->route('keuangan.beasiswa')
            ->with('success', "Pengajuan beasiswa [{$sch['name']}] telah disetujui Pimpinan Yayasan!");
    }

    /**
     * Destroy scholarship (Session-backed)
     */
    public function destroyBeasiswa(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $sch = $demo->findScholarship($id);

        if (!$sch) {
            return redirect()->route('keuangan.beasiswa')->with('error', 'Data beasiswa tidak ditemukan.');
        }

        $sessionDeleted = session('demo_scholarships_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_scholarships_deleted' => $sessionDeleted]);
        }

        return redirect()->route('keuangan.beasiswa')
            ->with('success', "Data beasiswa [{$sch['name']}] berhasil dihapus.");
    }

    /**
     * T09.6 — Arrears & Aging (Manajemen Piutang & Surat Peringatan Tunggakan)
     */
    public function tunggakan(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $invoices = $demo->getStudentInvoices();
        $students = $demo->getStudents();
        $classes = $demo->getClasses();
        $units = $demo->getUnits();

        // Maps
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $unitMap = [];
        foreach ($units as $u) {
            $unitMap[$u['id']] = $u;
        }

        // Filters
        $unitFilter = $request->query('unit_id');
        $agingFilter = $request->query('aging'); // current, 30_days, 60_days, 90_days
        $classFilter = $request->query('class_id');
        $search = strtolower(trim($request->query('q', '')));

        // Reference current date for demo: 2026-11-09
        $currentDate = strtotime('2026-11-09');

        // Only take unpaid or partially paid invoices
        $arrearsInvoices = array_filter($invoices, function ($inv) {
            return in_array($inv['status'], ['belum_lunas', 'jatuh_tempo', 'sebagian']);
        });

        $enrichedArrears = [];
        $agingSummary = [
            'current' => ['count' => 0, 'amount' => 0], // Belum jatuh tempo / < 0 hari
            '1_30' => ['count' => 0, 'amount' => 0],    // 1 - 30 hari jatuh tempo
            '31_60' => ['count' => 0, 'amount' => 0],   // 31 - 60 hari jatuh tempo
            'over_60' => ['count' => 0, 'amount' => 0], // > 60 hari jatuh tempo
        ];

        foreach ($arrearsInvoices as $inv) {
            $st = $studentMap[$inv['student_id']] ?? null;
            $unpaidAmount = $inv['amount'] - ($inv['paid_amount'] ?? 0);

            // Calculate overdue days
            $dueTimestamp = strtotime($inv['due_date']);
            $diffDays = (int)floor(($currentDate - $dueTimestamp) / (60 * 60 * 24));

            // Aging bucket
            $agingBucket = 'current';
            $agingLabel = 'Belum Jatuh Tempo';
            if ($diffDays > 60) {
                $agingBucket = 'over_60';
                $agingLabel = '> 60 Hari (Kritis)';
                $agingSummary['over_60']['count']++;
                $agingSummary['over_60']['amount'] += $unpaidAmount;
            } elseif ($diffDays > 30) {
                $agingBucket = '31_60';
                $agingLabel = '31 - 60 Hari';
                $agingSummary['31_60']['count']++;
                $agingSummary['31_60']['amount'] += $unpaidAmount;
            } elseif ($diffDays > 0) {
                $agingBucket = '1_30';
                $agingLabel = '1 - 30 Hari';
                $agingSummary['1_30']['count']++;
                $agingSummary['1_30']['amount'] += $unpaidAmount;
            } else {
                $agingBucket = 'current';
                $agingLabel = 'Jatuh Tempo Mendatang';
                $agingSummary['current']['count']++;
                $agingSummary['current']['amount'] += $unpaidAmount;
            }

            // Surat Peringatan Terakhir yang tersimpan di sesi
            $sentNotices = session('demo_tunggakan_notices', []);
            $noticeHistory = $sentNotices[$inv['id']] ?? null;

            $item = array_merge($inv, [
                'student_name' => $st['name'] ?? 'Santri ID: ' . $inv['student_id'],
                'nisn' => $st['nisn'] ?? ($st['nis'] ?? '-'),
                'class_name' => $st ? ($classMap[$st['class_id']]['name'] ?? '-') : '-',
                'class_id' => $st['class_id'] ?? null,
                'guardian_name' => $st['guardian_name'] ?? '-',
                'guardian_phone' => $st['guardian_phone'] ?? '-',
                'unpaid_amount' => $unpaidAmount,
                'overdue_days' => max(0, $diffDays),
                'aging_bucket' => $agingBucket,
                'aging_label' => $agingLabel,
                'unit_name' => $unitMap[$inv['unit_id']]['name'] ?? ($inv['unit_id'] ?? '-'),
                'notice_sent' => $noticeHistory
            ]);

            // Filter Application
            if ($unitFilter && ($inv['unit_id'] ?? '') !== $unitFilter) {
                continue;
            }

            if ($agingFilter && $agingBucket !== $agingFilter) {
                continue;
            }

            if ($classFilter && ($st['class_id'] ?? '') !== $classFilter) {
                continue;
            }

            if ($search) {
                $haystack = strtolower(
                    ($inv['invoice_number'] ?? '') . ' ' .
                    ($item['student_name'] ?? '') . ' ' .
                    ($item['nisn'] ?? '') . ' ' .
                    ($item['guardian_name'] ?? '') . ' ' .
                    ($inv['fee_title'] ?? '')
                );
                if (strpos($haystack, $search) === false) {
                    continue;
                }
            }

            $enrichedArrears[] = $item;
        }

        // Urutkan berdasarkan hari keterlambatan tertinggi (aging terbanyak)
        usort($enrichedArrears, function ($a, $b) {
            return $b['overdue_days'] - $a['overdue_days'];
        });

        // Metrik Ringkasan
        $totalArrearsCount = count($arrearsInvoices);
        $totalArrearsAmount = array_sum(array_column($arrearsInvoices, 'amount')) - array_sum(array_column($arrearsInvoices, 'paid_amount'));
        $totalCriticalCount = $agingSummary['31_60']['count'] + $agingSummary['over_60']['count'];
        $totalCriticalAmount = $agingSummary['31_60']['amount'] + $agingSummary['over_60']['amount'];

        return view('keuangan.tunggakan', compact(
            'units',
            'classes',
            'enrichedArrears',
            'agingSummary',
            'unitFilter',
            'agingFilter',
            'classFilter',
            'search',
            'totalArrearsCount',
            'totalArrearsAmount',
            'totalCriticalCount',
            'totalCriticalAmount'
        ));
    }

    /**
     * Send warning letter / notice to student guardian (Session-backed)
     */
    public function sendTunggakanNotice(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|string',
            'notice_type' => 'required|string|in:Surat Peringatan I (SP-1),Surat Peringatan II (SP-2),Surat Peringatan III (SP-3),Pemberitahuan Tagihan WhatsApp',
            'message' => 'nullable|string|max:500'
        ]);

        $demo = DemoDataManager::getInstance();
        $inv = $demo->findStudentInvoice($validated['invoice_id']);

        if (!$inv) {
            return redirect()->route('keuangan.tunggakan')->with('error', 'Tagihan tidak ditemukan.');
        }

        $sentNotices = session('demo_tunggakan_notices', []);
        $sentNotices[$inv['id']] = [
            'type' => $validated['notice_type'],
            'sent_at' => date('Y-m-d H:i:s'),
            'sender' => 'Tata Usaha Keuangan Alia Islamic School',
            'message' => $validated['message'] ?: 'Mengingatkan batas waktu pelunasan tagihan SPP santri.'
        ];
        session(['demo_tunggakan_notices' => $sentNotices]);

        $st = $demo->findStudent($inv['student_id']);
        $studentName = $st['name'] ?? 'Santri';

        return redirect()->route('keuangan.tunggakan')
            ->with('success', "Notifikasi [{$validated['notice_type']}] untuk wali santri {$studentName} ({$inv['invoice_number']}) BERHASIL diterbitkan & dikirim!");
    }

    /**
     * T09.7 — Cash In/Out (Buku Kas Umum, Arus Kas Masuk & Kas Keluar Operasional)
     */
    public function arusKas(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $expenses = $demo->getFinanceExpenses();
        $transactions = $demo->getPaymentTransactions();
        $units = $demo->getUnits();
        $students = $demo->getStudents();

        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s;
        }

        $unitMap = [];
        foreach ($units as $u) {
            $unitMap[$u['id']] = $u;
        }

        // Filters
        $typeFilter = $request->query('type'); // in, out
        $categoryFilter = $request->query('category');
        $monthFilter = $request->query('month');
        $search = strtolower(trim($request->query('q', '')));

        // Build unified ledger
        $ledger = [];

        // 1. Kas Masuk (Inflow dari Transaksi Pembayaran)
        foreach ($transactions as $trx) {
            $st = $studentMap[$trx['student_id']] ?? null;
            $ledger[] = [
                'id' => $trx['id'],
                'type' => 'in',
                'ref_number' => $trx['receipt_number'],
                'title' => 'Penerimaan ' . ($trx['payment_method'] ?? 'Kasir') . ' — ' . ($st['name'] ?? 'Santri'),
                'category' => 'Penerimaan SPP & Biaya Santri',
                'date' => substr($trx['payment_date'], 0, 10),
                'amount' => (int)$trx['amount'],
                'recipient_pic' => $trx['cashier_name'] ?? 'Kasir Sekolah',
                'status' => 'berhasil',
                'proof' => 'KWT-' . substr($trx['id'], -6) . '.pdf',
                'notes' => $trx['notes'] ?? '-'
            ];
        }

        // 2. Kas Keluar (Outflow dari Beban Operasional)
        foreach ($expenses as $exp) {
            $ledger[] = [
                'id' => $exp['id'],
                'type' => 'out',
                'ref_number' => $exp['id'],
                'title' => $exp['title'],
                'category' => $exp['category'],
                'date' => $exp['date'],
                'amount' => (int)$exp['amount'],
                'recipient_pic' => $exp['recipient'],
                'status' => $exp['status'] ?? 'disetujui',
                'proof' => 'BKK-' . substr($exp['id'], -6) . '.pdf',
                'notes' => $exp['notes'] ?? '-'
            ];
        }

        // Apply filters
        $filteredLedger = array_filter($ledger, function ($item) use ($typeFilter, $categoryFilter, $monthFilter, $search) {
            if ($typeFilter && $item['type'] !== $typeFilter) {
                return false;
            }

            if ($categoryFilter && $item['category'] !== $categoryFilter) {
                return false;
            }

            if ($monthFilter && !str_starts_with($item['date'], $monthFilter)) {
                return false;
            }

            if ($search) {
                $haystack = strtolower(
                    $item['ref_number'] . ' ' .
                    $item['title'] . ' ' .
                    $item['category'] . ' ' .
                    $item['recipient_pic'] . ' ' .
                    $item['notes']
                );
                if (strpos($haystack, $search) === false) {
                    return false;
                }
            }

            return true;
        });

        // Urutkan berdasarkan tanggal terbaru
        usort($filteredLedger, function ($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        // Metrik Keuangan
        $totalMasuk = array_sum(array_map(fn($item) => $item['type'] === 'in' ? $item['amount'] : 0, $ledger));
        $totalKeluar = array_sum(array_map(fn($item) => $item['type'] === 'out' ? $item['amount'] : 0, $ledger));
        $saldoKas = $totalMasuk - $totalKeluar;
        $totalTransaksiBuku = count($ledger);

        // Kategori Unik untuk dropdown
        $categories = array_values(array_unique(array_column($ledger, 'category')));

        return view('keuangan.arus-kas', compact(
            'filteredLedger',
            'units',
            'typeFilter',
            'categoryFilter',
            'monthFilter',
            'search',
            'totalMasuk',
            'totalKeluar',
            'saldoKas',
            'totalTransaksiBuku',
            'categories'
        ));
    }

    /**
     * Store new expense (Kas Keluar Operasional - Session-backed)
     */
    public function storeArusKas(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1000',
            'date' => 'required|date',
            'recipient' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'notes' => 'nullable|string|max:255'
        ]);

        $demo = DemoDataManager::getInstance();
        $existing = $demo->getFinanceExpenses();

        $sessionAdded = session('demo_finance_expenses_added', []);
        $newId = 'EXP-' . date('Ym') . '-' . str_pad((string)(count($existing) + count($sessionAdded) + 1), 3, '0', STR_PAD_LEFT);

        $newItem = [
            'id' => $newId,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'amount' => (int)$validated['amount'],
            'date' => $validated['date'],
            'unit_id' => $validated['unit_id'],
            'recipient' => $validated['recipient'],
            'status' => 'disetujui',
            'notes' => $validated['notes'] ?: 'Pengeluaran operasional resmi disetujui Bendahara Sekolah.'
        ];

        $sessionAdded[] = $newItem;
        session(['demo_finance_expenses_added' => $sessionAdded]);

        return redirect()->route('keuangan.arus-kas')
            ->with('success', "Pengeluaran kas [{$validated['title']}] sebesar Rp " . number_format($validated['amount'], 0, ',', '.') . " berhasil dicatat dalam Buku Kas Umum!");
    }

    /**
     * Destroy expense (Session-backed)
     */
    public function destroyArusKas(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $exp = $demo->findFinanceExpense($id);

        if (!$exp) {
            return redirect()->route('keuangan.arus-kas')->with('error', 'Catatan pengeluaran tidak ditemukan.');
        }

        $sessionDeleted = session('demo_finance_expenses_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_finance_expenses_deleted' => $sessionDeleted]);
        }

        return redirect()->route('keuangan.arus-kas')
            ->with('success', "Catatan pengeluaran [{$exp['title']}] berhasil dibatalkan dari buku kas.");
    }
}
