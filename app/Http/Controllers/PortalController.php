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

    /**
     * T13.6 - T13.7: Portal Mandiri Santri & Jadwal Belajar Harian
     */
    public function studentDashboard(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();

        $selectedStudentId = $request->get('student_id', 'SIS-0001');
        $selectedStudent = $demo->findStudent($selectedStudentId) ?? ($students[0] ?? null);

        // Timetable jadwal harian santri
        $timetables = $demo->getTimetables();
        $classId = $selectedStudent['class_id'] ?? 'CLS-SMA-10A';
        $studentTimetables = array_filter($timetables, function ($tt) use ($classId) {
            return ($tt['class_id'] ?? '') === $classId;
        });

        // Pengelompokan jadwal berdasarkan hari
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $scheduleByDay = [];
        foreach ($days as $day) {
            $scheduleByDay[$day] = array_values(array_filter($studentTimetables, fn($tt) => ($tt['day'] ?? '') === $day));
            usort($scheduleByDay[$day], fn($a, $b) => strcmp($a['time_slot'] ?? '', $b['time_slot'] ?? ''));
        }

        // Ambil data LMS tugas santri
        $assignments = $demo->getAssignments();
        $materials = $demo->getLearningMaterials();
        $classes = $demo->getDigitalClasses();

        // Cari tugas aktif
        $myAssignments = array_slice($assignments, 0, 5);
        $myMaterials = array_slice($materials, 0, 6);

        $activeTab = $request->get('tab', 'beranda'); // beranda, jadwal, tugas, materi

        return view('portal.student', compact(
            'selectedStudent',
            'students',
            'scheduleByDay',
            'myAssignments',
            'myMaterials',
            'classes',
            'activeTab'
        ));
    }

    /**
     * T13.8: Student LMS Pages (Detail Kelas & Materi Belajar Santri)
     */
    public function studentLms(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();
        $selectedStudentId = $request->get('student_id', 'SIS-0001');
        $selectedStudent = $demo->findStudent($selectedStudentId) ?? ($students[0] ?? null);

        $classes = $demo->getDigitalClasses();
        $materials = $demo->getLearningMaterials();
        $assignments = $demo->getAssignments();

        $classId = $request->get('class_id', $classes[0]['id'] ?? 'DCL-001');
        $activeClass = $demo->findDigitalClass($classId) ?? ($classes[0] ?? null);

        // Filter materi & tugas untuk kelas terpilih
        $classMaterials = array_filter($materials, fn($m) => ($m['digital_class_id'] ?? '') === $classId);
        $classAssignments = array_filter($assignments, fn($a) => ($a['digital_class_id'] ?? '') === $classId);

        return view('portal.student_lms', compact(
            'selectedStudent',
            'classes',
            'activeClass',
            'classMaterials',
            'classAssignments'
        ));
    }

    /**
     * Simulasi Pengumpulan Tugas oleh Santri
     */
    public function submitTugas(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|string',
            'student_id' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        return redirect()->back()->with('success', 'Alhamdulillah! Tugas ananda berhasil dikumpulkan ke sistem e-Learning Alia!');
    }
}

