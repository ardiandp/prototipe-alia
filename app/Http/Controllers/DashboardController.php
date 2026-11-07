<?php

namespace App\Http\Controllers;

use App\Support\DemoData\DemoDataManager;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilan Dashboard Utama (Multi-Role Support).
     */
    public function index(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $roleKey = session('active_role_key', 'yayasan');

        // Parameter Filter
        $unitFilter = $request->get('unit', 'all');
        $academicYear = $request->get('ta', 'TA-2026-1');

        $units = $demo->getUnits();
        $academicYears = $demo->getAcademicYears();
        $students = $demo->getStudents();
        $teachers = $demo->getTeachers();
        $classes = $demo->getClasses();

        // Data Eksekutif Yayasan
        $metrics = [
            'total_students' => 845, // Total santri seluruh unit
            'sd_students' => 320,
            'smp_students' => 280,
            'sma_students' => 245,
            'total_teachers' => count($teachers) + 38, // Asatidz & Pegawai
            'total_units' => count($units),
            'total_classes' => count($classes),
            'attendance_rate' => 97.4, // %
            'ppdb_registered' => 184,
            'ppdb_accepted' => 96,
            'ppdb_target' => 210,
            'collection_rate' => 93.2, // %
            'spp_collected' => 'Rp 642.500.000',
            'spp_arrears' => 'Rp 46.800.000',
            'boarding_residents' => 382,
            'boarding_capacity' => 420,
            'tahfidz_average_juz' => 5.4,
        ];

        // Aktivitas Terbaru (Audit trail simulasi)
        $recentActivities = [
            [
                'time' => '10 menit yang lalu',
                'icon' => 'fa-credit-card text-green',
                'title' => 'Pembayaran SPP Diterima',
                'desc' => 'Wali Santri Ahmad Fauzan (X MIPA 1) melunasi SPP November 2026 sebesar Rp 1.850.000.',
            ],
            [
                'time' => '45 menit yang lalu',
                'icon' => 'fa-user-plus text-aqua',
                'title' => 'Pendaftaran PPDB Baru',
                'desc' => 'Calon santri Muhammad Fatih terdaftar pada Unit SMP Islam Alia (Jalur Prestasi Tahfidz).',
            ],
            [
                'time' => '2 jam yang lalu',
                'icon' => 'fa-book text-purple',
                'title' => 'Setoran Tahfidz Selesai',
                'desc' => 'Santri Siti Fatimah (VII B) menyelesaikan ujian Tasmi\' Juz 3 dengan predikat Mumtaz.',
            ],
            [
                'time' => '4 jam yang lalu',
                'icon' => 'fa-bed text-maroon',
                'title' => 'Izin Keluar Asrama Disetujui',
                'desc' => 'Ust. Salman menyetujui izin berobat santri Rayhan Pratama ke RS Islam Pondok Kopi.',
            ],
            [
                'time' => 'Hari ini, 07:15',
                'icon' => 'fa-clock-o text-yellow',
                'title' => 'Presensi Apel Pagi Selesai',
                'desc' => 'Tingkat kehadiran guru & santri mencapai 98.1% pada pembukaan pekan ke-12.',
            ]
        ];

        // Agenda Kalender Akademik Terdekat
        $upcomingEvents = [
            ['date' => '12 Nov 2026', 'title' => 'Tasmi\' Akbar Al-Qur\'an Juz 1 - 5', 'badge' => 'Tahfidz', 'color' => 'purple'],
            ['date' => '18 Nov 2026', 'title' => 'Simulasi Asesmen Sumatif Akhir Semester', 'badge' => 'Akademik', 'color' => 'blue'],
            ['date' => '25 Nov 2026', 'title' => 'Pertemuan Evaluasi Bulanan Wali Santri & Asrama', 'badge' => 'Asrama', 'color' => 'maroon'],
            ['date' => '05 Des 2026', 'title' => 'Ujian Akhir Semester Ganjil TA 2026/2027', 'badge' => 'Ujian', 'color' => 'green'],
        ];

        // Routing tampilan varian dashboard berdasarkan role pengguna demo
        if ($roleKey === 'kepsek') {
            return view('dashboard.kepsek', compact('metrics', 'teachers', 'students', 'classes'));
        } elseif ($roleKey === 'guru') {
            return view('dashboard.guru', compact('metrics', 'students', 'classes'));
        } elseif ($roleKey === 'bendahara') {
            return view('dashboard.bendahara', compact('metrics', 'students'));
        } elseif ($roleKey === 'asrama') {
            return view('dashboard.asrama', compact('metrics', 'students'));
        }

        return view('dashboard.yayasan', compact(
            'roleKey',
            'unitFilter',
            'academicYear',
            'units',
            'academicYears',
            'metrics',
            'recentActivities',
            'upcomingEvents'
        ));
    }
}

