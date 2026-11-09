<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class PortalController extends Controller
{
    /**
     * T13.1 & T13.2 - T13.5: Portal Terpadu Orang Tua & Wali Santri
     */
    public function parentDashboard(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $guardians = $demo->getGuardians();
        $students = $demo->getStudents();

        // Ambil wali aktif demo (default wali santri pertama atau dari sesi)
        $activeGuardian = $guardians[0] ?? [
            'id' => 'WLI-0001',
            'name' => 'Ir. Hendra Gunawan',
            'phone' => '081122334455',
            'email' => 'hendragunawan@gmail.com'
        ];

        // Daftar santri anak dari wali ini
        // Jika hanya 1 santri, tambahkan santri kedua untuk keperluan demo switcher anak yang kaya
        $guardianStudents = array_values(array_filter($students, function ($s) use ($activeGuardian) {
            return ($s['guardian_id'] ?? '') === $activeGuardian['id'];
        }));

        if (count($guardianStudents) < 2 && count($students) >= 2) {
            // Pasangkan santri kedua (misal adik di SMP) untuk demo multi-anak
            $guardianStudents[] = $students[1];
        }

        // Tentukan santri yang sedang dipilih (selected)
        $selectedStudentId = $request->get('student_id', $guardianStudents[0]['id'] ?? 'SIS-0001');
        $selectedStudent = $demo->findStudent($selectedStudentId) ?? ($guardianStudents[0] ?? ($students[0] ?? null));

        // Tab aktif portal
        $activeTab = $request->get('tab', 'ringkasan'); // ringkasan, akademik, tahfidz, keuangan, asrama, pengumuman

        return view('portal.parent', compact(
            'activeGuardian',
            'guardianStudents',
            'selectedStudent',
            'activeTab'
        ));
    }
}
