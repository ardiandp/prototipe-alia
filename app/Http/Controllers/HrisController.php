<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class HrisController extends Controller
{
    /**
     * T10.1 - Employee profiles, list, and organization chart
     */
    public function pegawai(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $units = $demo->getUnits();

        $activeTab = $request->get('tab', 'daftar'); // daftar, struktur, statistik
        $search = $request->get('search');
        $unitFilter = $request->get('unit', 'all');
        $statusFilter = $request->get('status', 'all');
        $genderFilter = $request->get('gender', 'all');

        // Filter daftar pegawai
        $filteredStaff = array_filter($teachers, function ($t) use ($search, $unitFilter, $statusFilter, $genderFilter) {
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($t['name'], $search) !== false ||
                               stripos($t['nip'] ?? '', $search) !== false ||
                               stripos($t['position'] ?? '', $search) !== false ||
                               stripos($t['subject'] ?? '', $search) !== false ||
                               stripos($t['email'] ?? '', $search) !== false;
            }

            $matchUnit = ($unitFilter === 'all') || (($t['unit_id'] ?? '') === $unitFilter);
            $matchStatus = ($statusFilter === 'all') || (($t['employment_status'] ?? '') === $statusFilter);
            $matchGender = ($genderFilter === 'all') || (($t['gender'] ?? '') === $genderFilter);

            return $matchSearch && $matchUnit && $matchStatus && $matchGender;
        });

        // Metrik Ringkasan HRIS
        $totalStaff = count($teachers);
        $totalGTY = count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Guru Tetap Yayasan (GTY)'));
        $totalKontrak = count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Guru Kontrak'));
        $totalTendik = count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Tenaga Kependidikan'));
        $totalLaki = count(array_filter($teachers, fn($t) => ($t['gender'] ?? '') === 'L'));
        $totalPerempuan = count(array_filter($teachers, fn($t) => ($t['gender'] ?? '') === 'P'));

        // Distribusi per Unit
        $unitDistribution = [];
        foreach ($units as $u) {
            $count = count(array_filter($teachers, fn($t) => ($t['unit_id'] ?? '') === $u['id']));
            $unitDistribution[$u['id']] = [
                'unit' => $u,
                'count' => $count
            ];
        }

        // Struktur Organisasi Hierarkis
        $orgStructure = [
            'pembina' => array_filter($teachers, fn($t) => stripos($t['position'], 'Ketua Yayasan') !== false || stripos($t['position'], 'Pembina') !== false),
            'kepsek' => array_filter($teachers, fn($t) => stripos($t['position'], 'Kepala Sekolah') !== false),
            'pimpinan_unit' => array_filter($teachers, fn($t) => stripos($t['position'], 'Waka') !== false || stripos($t['position'], 'Mudir') !== false || stripos($t['position'], 'Koordinator') !== false || stripos($t['position'], 'Bendahara') !== false || stripos($t['position'], 'Kepala Lab') !== false),
            'guru_wali' => array_filter($teachers, fn($t) => stripos($t['position'], 'Wali Kelas') !== false || stripos($t['position'], 'Guru') !== false || stripos($t['position'], 'Pembina Ekstrakurikuler') !== false)
        ];

        return view('hris.pegawai', compact(
            'filteredStaff',
            'teachers',
            'units',
            'activeTab',
            'search',
            'unitFilter',
            'statusFilter',
            'genderFilter',
            'totalStaff',
            'totalGTY',
            'totalKontrak',
            'totalTendik',
            'totalLaki',
            'totalPerempuan',
            'unitDistribution',
            'orgStructure'
        ));
    }

    /**
     * Simpan Pegawai Baru ke Sesi Demo
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
            'email' => 'nullable|email|max:100',
            'education' => 'nullable|string|max:100',
        ]);

        $demo = DemoDataManager::getInstance();
        $teachers = $demo->getTeachers();
        $count = count($teachers) + 1;
        $id = 'GUR-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        $nip = $validated['nip'] ?: '1988' . rand(1000, 9999) . '201501100' . $count;

        $newStaff = [
            'id' => $id,
            'nip' => $nip,
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'gender' => $validated['gender'],
            'email' => $validated['email'] ?: strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name'])) . '@alia.sch.id',
            'employment_status' => $validated['employment_status'],
            'position' => $validated['position'],
            'unit_id' => $validated['unit_id'],
            'avatar' => $validated['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png',
            'phone' => $validated['phone'],
            'education' => $validated['education'] ?: 'S1 Pendidikan',
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_teachers_added', []);
        $sessionAdded[] = $newStaff;
        session(['demo_teachers_added' => $sessionAdded]);

        return redirect()->route('hris.pegawai')->with('success', "Pegawai/Pendidik baru \"{$newStaff['name']}\" ({$newStaff['position']}) berhasil didaftarkan ke sistem SDM!");
    }

    /**
     * Update Data Pegawai di Sesi Demo
     */
    public function updatePegawai(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nip' => 'required|string|max:30',
            'gender' => 'required|in:L,P',
            'subject' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|string',
            'unit_id' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'education' => 'nullable|string|max:100',
        ]);

        $sessionUpdated = session('demo_teachers_updated', []);
        $sessionUpdated[$id] = [
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'gender' => $validated['gender'],
            'subject' => $validated['subject'],
            'position' => $validated['position'],
            'employment_status' => $validated['employment_status'],
            'unit_id' => $validated['unit_id'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?: ($id . '@alia.sch.id'),
            'education' => $validated['education'] ?: 'S1 Pendidikan',
        ];
        session(['demo_teachers_updated' => $sessionUpdated]);

        return redirect()->route('hris.pegawai')->with('success', "Data profil pegawai {$validated['name']} berhasil diperbarui!");
    }

    /**
     * Hapus Pegawai dari Sesi Demo
     */
    public function destroyPegawai(string $id)
    {
        $sessionDeleted = session('demo_teachers_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_teachers_deleted' => $sessionDeleted]);
        }

        return redirect()->route('hris.pegawai')->with('info', "Data pegawai ID: {$id} berhasil dinonaktifkan/dihapus dari sesi demo.");
    }
}
