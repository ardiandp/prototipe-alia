<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class PpdbController extends Controller
{
    /**
     * T03.1 - PPDB Dashboard
     */
    public function dashboard(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $applicants = $demo->getPpdbApplicants();
        $units = $demo->getUnits();

        $unitFilter = $request->get('unit', 'all');

        if ($unitFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($unitFilter) {
                return ($a['unit_level'] ?? '') === $unitFilter;
            });
        }

        // Agregasi metrik PPDB
        $totalRegistered = count($applicants);
        $totalVerified = count(array_filter($applicants, fn($a) => ($a['doc_status'] ?? '') === 'diverifikasi'));
        $totalPendingDoc = count(array_filter($applicants, fn($a) => ($a['doc_status'] ?? '') === 'menunggu'));
        $totalAccepted = count(array_filter($applicants, fn($a) => ($a['selection_status'] ?? '') === 'lulus'));
        $totalReRegistered = count(array_filter($applicants, fn($a) => ($a['selection_status'] ?? '') === 'lulus' && ($a['payment_status'] ?? '') === 'lunas'));
        
        // Kuota penerimaan total (target)
        $quotaTarget = 210;
        $conversionRate = $totalRegistered > 0 ? round(($totalReRegistered / $totalRegistered) * 100, 1) : 0;

        // Distribusi Jalur Masuk
        $tracks = [
            'Reguler' => count(array_filter($applicants, fn($a) => ($a['track'] ?? '') === 'Reguler')),
            'Prestasi Tahfidz' => count(array_filter($applicants, fn($a) => ($a['track'] ?? '') === 'Prestasi Tahfidz')),
            'Prestasi Akademik' => count(array_filter($applicants, fn($a) => ($a['track'] ?? '') === 'Prestasi Akademik')),
            'Afirmasi / Yatim' => count(array_filter($applicants, fn($a) => ($a['track'] ?? '') === 'Afirmasi / Yatim')),
        ];

        return view('ppdb.dashboard', compact(
            'applicants',
            'units',
            'unitFilter',
            'totalRegistered',
            'totalVerified',
            'totalPendingDoc',
            'totalAccepted',
            'totalReRegistered',
            'quotaTarget',
            'conversionRate',
            'tracks'
        ));
    }

    /**
     * T03.2 - Data Pendaftar PPDB
     */
    public function pendaftar(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $applicants = $demo->getPpdbApplicants();
        $units = $demo->getUnits();

        $search = $request->get('search');
        $unitFilter = $request->get('unit', 'all');
        $trackFilter = $request->get('track', 'all');
        $statusFilter = $request->get('status', 'all');

        if ($search) {
            $applicants = array_filter($applicants, function ($a) use ($search) {
                return stripos($a['name'], $search) !== false ||
                       stripos($a['reg_number'], $search) !== false ||
                       stripos($a['guardian_name'], $search) !== false ||
                       stripos($a['guardian_phone'], $search) !== false;
            });
        }

        if ($unitFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($unitFilter) {
                return ($a['unit_level'] ?? '') === $unitFilter;
            });
        }

        if ($trackFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($trackFilter) {
                return ($a['track'] ?? '') === $trackFilter;
            });
        }

        if ($statusFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($statusFilter) {
                return ($a['selection_status'] ?? '') === $statusFilter;
            });
        }

        return view('ppdb.pendaftar', compact('applicants', 'units', 'search', 'unitFilter', 'trackFilter', 'statusFilter'));
    }

    /**
     * Hapus Pendaftar dari Sesi Demo
     */
    public function destroyPendaftar(string $id)
    {
        $sessionDeleted = session('demo_ppdb_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_ppdb_deleted' => $sessionDeleted]);
        }

        return redirect()->route('ppdb.pendaftar')->with('info', "Data pendaftar (No. Reg: {$id}) berhasil dihapus dari sesi demo.");
    }

    /**
     * T03.3 - Formulir Pendaftaran PPDB Daring
     */
    public function form()
    {
        $demo = DemoDataManager::getInstance();
        $units = $demo->getUnits();

        return view('ppdb.form', compact('units'));
    }

    /**
     * Simpan Pendaftaran Mandiri Calon Santri Baru (Session-based)
     */
    public function storeForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|in:L,P',
            'unit_level' => 'required|in:SD,SMP,SMA',
            'track' => 'required|in:Reguler,Prestasi Tahfidz,Prestasi Akademik,Afirmasi / Yatim',
            'pob' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'guardian_name' => 'required|string|max:100',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email|max:100',
            'address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255'
        ]);

        $demo = DemoDataManager::getInstance();
        $count = count($demo->getPpdbApplicants()) + 1;
        $regNumber = 'PPDB-2026-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $unitTarget = 'SMA Islam Alia Boarding School';
        if ($validated['unit_level'] === 'SD') {
            $unitTarget = 'SD Islam Alia';
        } elseif ($validated['unit_level'] === 'SMP') {
            $unitTarget = 'SMP Islam Alia';
        }

        $newApplicant = [
            'id' => $regNumber,
            'reg_number' => $regNumber,
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'unit_level' => $validated['unit_level'],
            'unit_target' => $unitTarget,
            'track' => $validated['track'],
            'doc_status' => 'menunggu', // default baru masuk: butuh verifikasi berkas
            'selection_status' => 'proses',
            'payment_status' => 'belum_lunas',
            'guardian_name' => $validated['guardian_name'],
            'guardian_phone' => $validated['guardian_phone'],
            'notes' => $validated['notes'] ?: 'Formulir mandiri didaftarkan via PPDB Online.',
            'exam_score' => null,
            'registered_at' => date('Y-m-d H:i:s'),
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_ppdb_added', []);
        $sessionAdded[] = $newApplicant;
        session(['demo_ppdb_added' => $sessionAdded]);

        return redirect()->route('ppdb.pendaftar')->with('success', "Pendaftaran mandiri berhasil! Nomor Registrasi Anda: {$regNumber}. Berkas telah masuk ke antrean verifikasi panitia.");
    }

    /**
     * T03.4 - Verifikasi Berkas Persyaratan Calon Santri
     */
    public function verifikasi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $applicants = $demo->getPpdbApplicants();

        $statusFilter = $request->get('status', 'all');
        $search = $request->get('search');

        if ($search) {
            $applicants = array_filter($applicants, function ($a) use ($search) {
                return stripos($a['name'], $search) !== false ||
                       stripos($a['reg_number'], $search) !== false;
            });
        }

        if ($statusFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($statusFilter) {
                return ($a['doc_status'] ?? '') === $statusFilter;
            });
        }

        return view('ppdb.verifikasi', compact('applicants', 'statusFilter', 'search'));
    }

    /**
     * Aksi Verifikasi / Tolak Berkas Dokumen (Session-based)
     */
    public function updateVerifikasi(Request $request, string $id)
    {
        $validated = $request->validate([
            'doc_status' => 'required|in:diverifikasi,ditolak,menunggu',
            'notes' => 'nullable|string|max:255'
        ]);

        $sessionUpdated = session('demo_ppdb_updated', []);
        $sessionUpdated[$id] = [
            'doc_status' => $validated['doc_status'],
            'notes' => $validated['notes'] ?: ($validated['doc_status'] === 'diverifikasi' ? 'Seluruh berkas persyaratan telah diverifikasi panitia.' : 'Berkas belum memenuhi kelayakan.')
        ];
        session(['demo_ppdb_updated' => $sessionUpdated]);

        $statusLabel = $validated['doc_status'] === 'diverifikasi' ? 'Diterima & Terverifikasi' : ($validated['doc_status'] === 'ditolak' ? 'Ditolak' : 'Dikembalikan');

        return redirect()->route('ppdb.verifikasi')->with('success', "Status berkas pendaftar ({$id}) berhasil diperbarui: {$statusLabel}!");
    }

    /**
     * T03.5 - Seleksi & Keputusan Kelulusan Calon Santri
     */
    public function seleksi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $applicants = $demo->getPpdbApplicants();

        $statusFilter = $request->get('status', 'all');
        $unitFilter = $request->get('unit', 'all');
        $search = $request->get('search');

        if ($search) {
            $applicants = array_filter($applicants, function ($a) use ($search) {
                return stripos($a['name'], $search) !== false ||
                       stripos($a['reg_number'], $search) !== false;
            });
        }

        if ($unitFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($unitFilter) {
                return ($a['unit_level'] ?? '') === $unitFilter;
            });
        }

        if ($statusFilter !== 'all') {
            $applicants = array_filter($applicants, function ($a) use ($statusFilter) {
                return ($a['selection_status'] ?? '') === $statusFilter;
            });
        }

        return view('ppdb.seleksi', compact('applicants', 'statusFilter', 'unitFilter', 'search'));
    }

    /**
     * Simpan Nilai Ujian Seleksi & Keputusan Kelulusan (Session-based)
     */
    public function updateSeleksi(Request $request, string $id)
    {
        $validated = $request->validate([
            'academic_score' => 'required|numeric|min:0|max:100',
            'quran_score' => 'required|numeric|min:0|max:100',
            'interview_score' => 'required|numeric|min:0|max:100',
            'selection_status' => 'required|in:lulus,tidak_lulus,proses',
            'notes' => 'nullable|string|max:255'
        ]);

        $finalScore = round(($validated['academic_score'] * 0.4) + ($validated['quran_score'] * 0.4) + ($validated['interview_score'] * 0.2), 1);

        $sessionUpdated = session('demo_ppdb_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $sessionUpdated[$id] = array_merge($existing, [
            'exam_score' => $finalScore,
            'selection_status' => $validated['selection_status'],
            'notes' => $validated['notes'] ?: "Hasil seleksi: {$validated['selection_status']} dengan skor akhir {$finalScore}."
        ]);

        session(['demo_ppdb_updated' => $sessionUpdated]);

        $statusLabel = $validated['selection_status'] === 'lulus' ? 'LULUS SELEKSI' : ($validated['selection_status'] === 'tidak_lulus' ? 'TIDAK LULUS' : 'DALAM PROSES');

        return redirect()->route('ppdb.seleksi')->with('success', "Penilaian calon santri ({$id}) berhasil disimpan! Keputusan: {$statusLabel} (Skor Akhir: {$finalScore}).");
    }

    /**
     * T03.6 - Pengumuman & Daftar Ulang Calon Santri Lulus
     */
    public function daftarUlang(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $applicants = $demo->getPpdbApplicants();

        // Fokuskan pada santri yang dinyatakan lulus seleksi
        $unitFilter = $request->get('unit', 'all');
        $paymentFilter = $request->get('payment', 'all');
        $search = $request->get('search');

        // Filter calon santri lulus
        $acceptedApplicants = array_filter($applicants, function ($a) {
            return ($a['selection_status'] ?? '') === 'lulus';
        });

        if ($search) {
            $acceptedApplicants = array_filter($acceptedApplicants, function ($a) use ($search) {
                return stripos($a['name'], $search) !== false ||
                       stripos($a['reg_number'], $search) !== false ||
                       stripos($a['guardian_name'], $search) !== false;
            });
        }

        if ($unitFilter !== 'all') {
            $acceptedApplicants = array_filter($acceptedApplicants, function ($a) use ($unitFilter) {
                return ($a['unit_level'] ?? '') === $unitFilter;
            });
        }

        if ($paymentFilter !== 'all') {
            $acceptedApplicants = array_filter($acceptedApplicants, function ($a) use ($paymentFilter) {
                return ($a['payment_status'] ?? '') === $paymentFilter;
            });
        }

        $applicants = $acceptedApplicants;

        return view('ppdb.daftar-ulang', compact('applicants', 'acceptedApplicants', 'unitFilter', 'paymentFilter', 'search'));
    }

    /**
     * Simulasi Pembayaran / Pelunasan Biaya Daftar Ulang (Session-based)
     */
    public function bayarDaftarUlang(Request $request, string $id)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:BSI Virtual Account,Transfer Mandiri,Kasir Tunai',
            'amount' => 'required|numeric|min:1000000',
            'receipt_number' => 'nullable|string'
        ]);

        $receipt = $validated['receipt_number'] ?: 'KW-PPDB-' . date('Ymd') . '-' . rand(100, 999);

        $sessionUpdated = session('demo_ppdb_updated', []);
        $existing = $sessionUpdated[$id] ?? [];

        $sessionUpdated[$id] = array_merge($existing, [
            'payment_status' => 'lunas',
            'payment_receipt' => $receipt,
            'payment_method' => $validated['payment_method'],
            'payment_amount' => $validated['amount'],
            'paid_at' => date('Y-m-d H:i:s'),
            'notes' => "Daftar ulang lunas via {$validated['payment_method']}. Kwitansi: {$receipt}."
        ]);

        session(['demo_ppdb_updated' => $sessionUpdated]);

        return redirect()->route('ppdb.daftar-ulang')->with('success', "Pembayaran daftar ulang calon santri ({$id}) berhasil diverifikasi! Kwitansi: {$receipt}. Santri siap dikonversi ke Buku Induk.");
    }

    /**
     * T03.7 - Konversi Pendaftar Lunas Menjadi Siswa Baru di Buku Induk (Cross-module workflow)
     */
    public function konversiSiswa(Request $request, string $id)
    {
        $demo = DemoDataManager::getInstance();
        $applicant = $demo->findPpdbApplicant($id);

        if (!$applicant) {
            return redirect()->route('ppdb.daftar-ulang')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        if (($applicant['payment_status'] ?? '') !== 'lunas') {
            return redirect()->route('ppdb.daftar-ulang')->with('error', 'Calon santri belum menyelesaikan pembayaran daftar ulang.');
        }

        // Tentukan kelas target berdasarkan level
        $level = $applicant['unit_level'] ?? 'SMA';
        $classId = ($level === 'SMA') ? 'CLS-SMA-10A' : (($level === 'SMP') ? 'CLS-SMP-7A' : 'CLS-SD-1A');
        $unitId = 'UNIT-' . $level;
        $gender = $applicant['gender'] ?? 'L';
        $newNis = '2027' . ($level === 'SMA' ? '10' : ($level === 'SMP' ? '07' : '01')) . rand(100, 999);
        $newNisn = '0089' . rand(100000, 999999);

        // Tambahkan ke session_students_added
        $sessionStudents = session('demo_students_added', []);
        $newStudentId = 'STU-PPDB-' . substr($applicant['reg_number'], -6);

        // Cek apakah sudah pernah dikonversi
        $alreadyConverted = false;
        foreach (array_merge($demo->getStudents(), $sessionStudents) as $existingStudent) {
            if ($existingStudent['id'] === $newStudentId || ($existingStudent['ppdb_ref'] ?? '') === $applicant['reg_number']) {
                $alreadyConverted = true;
                break;
            }
        }

        if ($alreadyConverted) {
            return redirect()->route('ppdb.daftar-ulang')->with('info', "Santri {$applicant['name']} sudah pernah dikonversi sebelumnya ke Buku Induk Santri.");
        }

        $newStudent = [
            'id' => $newStudentId,
            'nis' => $newNis,
            'nisn' => $newNisn,
            'name' => $applicant['name'],
            'gender' => $gender,
            'pob' => 'Jakarta',
            'dob' => '2012-05-15',
            'unit_level' => $level,
            'unit_id' => $unitId,
            'grade' => ($level === 'SMA') ? '10' : (($level === 'SMP') ? '7' : '1'),
            'class_id' => $classId,
            'residence_type' => ($level === 'SMA') ? 'Boarding (Asrama)' : 'Full Day',
            'boarding_room_id' => ($level === 'SMA') ? ($gender === 'L' ? 'ROM-ASR-A1' : 'ROM-ASR-B1') : null,
            'is_boarding' => ($level === 'SMA'),
            'avatar' => ($gender === 'L' ? 'avatar5.png' : 'avatar3.png'),
            'guardian_id' => 'GUA-' . rand(100, 999),
            'guardian_name' => $applicant['guardian_name'] ?? 'Orang Tua / Wali Santri',
            'guardian_phone' => $applicant['guardian_phone'] ?? '081299887766',
            'status' => 'aktif',
            'tahfidz_juz' => 3,
            'tahfidz_last_surah' => 'Surah An-Naba s/d Al-Infitar',
            'tahfidz_predicate' => 'Jayyid Jiddan',
            'spp_status' => 'lunas',
            'spp_nominal' => ($level === 'SMA') ? 1850000 : 1200000,
            'gpa' => 88.0,
            'attendance_pct' => 100.0,
            'ppdb_ref' => $applicant['reg_number']
        ];

        $sessionStudents[] = $newStudent;
        session(['demo_students_added' => $sessionStudents]);

        // Tandai applicant sudah dikonversi
        $sessionUpdated = session('demo_ppdb_updated', []);
        $existing = $sessionUpdated[$id] ?? [];
        $sessionUpdated[$id] = array_merge($existing, [
            'is_converted' => true,
            'converted_nis' => $newNis,
            'converted_at' => date('Y-m-d H:i:s'),
            'notes' => "Telah dikonversi menjadi santri aktif (NIS: {$newNis}, Rombel: {$classId})."
        ]);
        session(['demo_ppdb_updated' => $sessionUpdated]);

        return redirect()->route('master.siswa')->with('success', "Alhamdulillah! Calon santri {$applicant['name']} berhasil dikonversi menjadi santri aktif dengan NIS: {$newNis} di rombel {$classId}.");
    }
}

