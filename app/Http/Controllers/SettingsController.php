<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class SettingsController extends Controller
{
    /**
     * T16.1 - T16.5: Pengaturan Sistem, Profil Sekolah, Unit/Tahun, Switcher Role & Reset Demo
     */
    public function index(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $units = $demo->getUnits();
        $academicYears = $demo->getAcademicYears();

        $activeTab = $request->get('tab', 'institusi'); // institusi, unit_tahun, role_switcher, notifikasi, reset_demo

        // Ambil profil institusi dari sesi demo (atau default baseline)
        $institution = session('demo_settings_institution', [
            'name' => 'Alia Islamic School',
            'legal_name' => 'Yayasan Airis Pendidikan Islam Indonesia',
            'npsn_sma' => '20108923',
            'npsn_smp' => '20108922',
            'npsn_sd' => '20108921',
            'foundation_chairman' => 'Dr. Ir. H. Muhammad Ridwan, M.M.',
            'director' => 'Drs. H. Mulyadi, M.Pd.',
            'accreditation' => 'A (Unggul) — BAN-S/M',
            'address' => 'Jl. Pendidikan Karakter No. 99, Cibubur, Jakarta Timur 13720',
            'phone' => '(021) 876-5432 / 876-5433',
            'email' => 'info@alia.sch.id / sekretariat@alia.sch.id',
            'website' => 'https://alia.sch.id',
            'tagline' => 'Membentuk Generasi Qur\'ani, Berkarakter Pemimpin, dan Berwawasan Global',
            'vision' => 'Menjadi lembaga pendidikan Islam rujukan bertaraf internasional yang unggul dalam tahfidz Al-Qur\'an, sains terapan, dan akhlakul karimah pada tahun 2030.',
            'curriculum' => 'Kurikulum Merdeka Plus Terintegrasi Pesantren Modern & Cambridge English'
        ]);

        // Preferensi Notifikasi dari sesi
        $notificationPrefs = session('demo_settings_notifications', [
            'wa_guardian_spp' => true,
            'wa_guardian_attendance' => true,
            'wa_guardian_tahfidz' => true,
            'wa_guardian_permission' => true,
            'email_weekly_digest' => true,
            'app_push_alert' => true,
            'alert_min_stock' => true,
            'alert_maintenance_due' => true
        ]);

        // Daftar Role Demo Lengkap
        $rolesList = [
            'yayasan' => [
                'name' => 'Ust. Dr. H. Zulkifli, Lc., M.A.',
                'title' => 'Ketua Yayasan',
                'role' => 'Yayasan / Administrator Eksekutif',
                'badge' => 'Executive',
                'desc' => 'Akses penuh seluruh metrik pimpinan, rekapitulasi keuangan, dan audit.'
            ],
            'kepsek' => [
                'name' => 'Drs. H. Mulyadi, M.Pd.',
                'title' => 'Kepala Sekolah SMA',
                'role' => 'Kepala Sekolah',
                'badge' => 'Akademik',
                'desc' => 'Supervisi KBM, kelulusan PPDB, rapor santri, dan kepegawaian guru.'
            ],
            'guru' => [
                'name' => 'Ustazah Aisyah Salsabila, S.Pd.I.',
                'title' => 'Guru PAI & Pengampu Tahfidz',
                'role' => 'Guru / Tenaga Pendidik',
                'badge' => 'Pengajar',
                'desc' => 'Input nilai formatif/sumatif, presensi kelas, halaqah, dan materi LMS.'
            ],
            'bendahara' => [
                'name' => 'Hj. Nurul Hidayati, S.E.',
                'title' => 'Bendahara Sekolah',
                'role' => 'Bendahara Keuangan',
                'badge' => 'Keuangan',
                'desc' => 'Manajemen pos tarif SPP, rekonsiliasi kas, dan kuitansi pembayaran.'
            ],
            'asrama' => [
                'name' => 'Ust. Salman Al-Farisi',
                'title' => 'Mudir Asrama Santri',
                'role' => 'Pembina & Musyrif Asrama',
                'badge' => 'Kepengasuhan',
                'desc' => 'Alokasi tempat tidur santri, monitoring katering, dan approval perizinan keluar.'
            ],
            'wali' => [
                'name' => 'Ir. Hendra Gunawan',
                'title' => 'Wali Santri (Ahmad Fauzan - X MIPA 1)',
                'role' => 'Wali Santri / Orang Tua',
                'badge' => 'Portal',
                'desc' => 'Portal mandiri orang tua: pantau rapor, tahfidz, bayar SPP, dan ajukan izin pesiar.'
            ]
        ];

        return view('settings.index', compact(
            'activeTab',
            'institution',
            'units',
            'academicYears',
            'notificationPrefs',
            'rolesList'
        ));
    }

    /**
     * T16.1: Update Profil Lembaga Institusi
     */
    public function updateInstitution(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'legal_name' => 'required|string|max:200',
            'foundation_chairman' => 'required|string|max:150',
            'director' => 'required|string|max:150',
            'phone' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'address' => 'required|string|max:300',
            'tagline' => 'nullable|string|max:255',
            'vision' => 'nullable|string|max:500'
        ]);

        $current = session('demo_settings_institution', []);
        session(['demo_settings_institution' => array_merge($current, $validated)]);

        return redirect()->route('settings.index', ['tab' => 'institusi'])
            ->with('success', 'Profil institusi Alia Islamic School berhasil diperbarui dalam sesi demo!');
    }

    /**
     * T16.4: Update Preferensi Notifikasi
     */
    public function updateNotifications(Request $request)
    {
        $prefs = [
            'wa_guardian_spp' => $request->has('wa_guardian_spp'),
            'wa_guardian_attendance' => $request->has('wa_guardian_attendance'),
            'wa_guardian_tahfidz' => $request->has('wa_guardian_tahfidz'),
            'wa_guardian_permission' => $request->has('wa_guardian_permission'),
            'email_weekly_digest' => $request->has('email_weekly_digest'),
            'app_push_alert' => $request->has('app_push_alert'),
            'alert_min_stock' => $request->has('alert_min_stock'),
            'alert_maintenance_due' => $request->has('alert_maintenance_due')
        ];

        session(['demo_settings_notifications' => $prefs]);

        return redirect()->route('settings.index', ['tab' => 'notifikasi'])
            ->with('success', 'Preferensi notifikasi gateway WhatsApp & Email berhasil disimpan!');
    }

    /**
     * Halaman Profil Akun Pengguna Aktif
     */
    public function profile()
    {
        return redirect()->route('settings.index', ['tab' => 'role_switcher']);
    }
}
