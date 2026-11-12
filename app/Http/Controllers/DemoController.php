<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    /**
     * Ganti peran demo dalam sesi untuk simulasi multi-role.
     */
    public function switchRole(Request $request, string $role)
    {
        $roles = [
            'yayasan' => [
                'name' => 'Ust. Dr. H. Zulkifli, Lc., M.A.',
                'title' => 'Ketua Yayasan',
                'role' => 'Yayasan / Administrator'
            ],
            'kepsek' => [
                'name' => 'Drs. H. Mulyadi, M.Pd.',
                'title' => 'Kepala Sekolah SMA',
                'role' => 'Kepala Sekolah'
            ],
            'guru' => [
                'name' => 'Ustazah Aisyah Salsabila, S.Pd.I.',
                'title' => 'Guru PAI & Tahfidz',
                'role' => 'Guru / Pengajar'
            ],
            'bendahara' => [
                'name' => 'Hj. Nurul Hidayati, S.E.',
                'title' => 'Bendahara Sekolah',
                'role' => 'Bendahara Keuangan'
            ],
            'asrama' => [
                'name' => 'Ust. Salman Al-Farisi',
                'title' => 'Mudir Asrama Santri',
                'role' => 'Pembina Asrama'
            ],
            'wali' => [
                'name' => 'Ir. Hendra Gunawan',
                'title' => 'Wali Santri (Ahmad Fauzan - X MIPA 1)',
                'role' => 'Wali Santri'
            ]
        ];

        $selected = $roles[$role] ?? $roles['yayasan'];

        session([
            'current_role' => $selected['role'],
            'user_name' => $selected['name'],
            'user_title' => $selected['title'],
            'active_role_key' => $role
        ]);

        return redirect()->back()->with('success', "Peran demo berhasil dialihkan ke: {$selected['role']} ({$selected['name']})");
    }

    /**
     * Reset sesi data demo ke kondisi baseline deterministic Faker.
     */
    public function resetDemo()
    {
        // Hapus custom state demo dari session
        session()->forget([
            'demo_students_added',
            'demo_students_deleted',
            'demo_invoices_paid',
            'demo_leave_requests',
            'demo_grades',
            'demo_attendance',
            'demo_stock_receipts_added',
            'demo_stock_issues_added',
            'demo_inventory_items_updated',
            'demo_inventory_procurements_added',
            'demo_inventory_maintenances_added',
            'demo_ai_chat_messages',
            'demo_settings_institution',
            'demo_settings_notifications'
        ]);

        // Re-set default identity
        session([
            'current_role' => 'Yayasan / Administrator',
            'user_name' => 'Ust. Dr. H. Zulkifli, Lc., M.A.',
            'user_title' => 'Ketua Yayasan',
            'active_role_key' => 'yayasan'
        ]);

        return redirect()->route('dashboard')->with('info', 'Seluruh data mutasi sesi demo telah direset ke baseline awal.');
    }
}

