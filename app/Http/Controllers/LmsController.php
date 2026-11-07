<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class LmsController extends Controller
{
    /**
     * T05.1 - Digital Classes (Kelas Digital LMS)
     */
    public function kelasDigital(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $digitalClasses = $demo->getDigitalClasses();
        $classes = $demo->getClasses();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();
        $students = $demo->getStudents();

        $selectedClassId = $request->get('class_id', 'all');
        $search = $request->get('search');

        $filteredClasses = $digitalClasses;

        if ($selectedClassId !== 'all') {
            $filteredClasses = array_filter($filteredClasses, function ($dc) use ($selectedClassId) {
                return ($dc['class_id'] ?? '') === $selectedClassId;
            });
        }

        if ($search) {
            $filteredClasses = array_filter($filteredClasses, function ($dc) use ($search) {
                return stripos($dc['name'], $search) !== false ||
                       stripos($dc['description'] ?? '', $search) !== false;
            });
        }

        // Hitung santri per kelas digital
        $classesData = [];
        foreach ($filteredClasses as $dc) {
            $tch = collect($teachers)->firstWhere('id', $dc['teacher_id']);
            $sb = collect($subjects)->firstWhere('id', $dc['subject_id']);
            $cls = collect($classes)->firstWhere('id', $dc['class_id']);

            $participants = array_values(array_filter($students, function ($s) use ($dc) {
                return ($s['class_id'] ?? '') === $dc['class_id'];
            }));

            $classesData[] = [
                'info' => $dc,
                'teacher' => $tch,
                'subject' => $sb,
                'class' => $cls,
                'participants' => $participants,
                'participant_count' => count($participants)
            ];
        }

        return view('lms.kelas-digital', compact(
            'classesData',
            'classes',
            'subjects',
            'teachers',
            'selectedClassId',
            'search'
        ));
    }

    /**
     * Detail Kelas Digital Tertentu (Roster Santri & Aktivitas Belajar)
     */
    public function showKelasDigital(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $dc = $demo->findDigitalClass($id);

        if (!$dc) {
            return redirect()->route('lms.kelas-digital')->with('error', 'Kelas digital tidak ditemukan.');
        }

        $teachers = $demo->getTeachers();
        $subjects = $demo->getSubjects();
        $classes = $demo->getClasses();
        $students = $demo->getStudents();

        $teacher = collect($teachers)->firstWhere('id', $dc['teacher_id']);
        $subject = collect($subjects)->firstWhere('id', $dc['subject_id']);
        $class = collect($classes)->firstWhere('id', $dc['class_id']);

        $participants = array_values(array_filter($students, function ($s) use ($dc) {
            return ($s['class_id'] ?? '') === $dc['class_id'];
        }));

        return view('lms.kelas-detail', compact(
            'dc',
            'teacher',
            'subject',
            'class',
            'participants'
        ));
    }

    /**
     * Tambah Kelas Digital Baru (Session-based)
     */
    public function storeKelasDigital(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'teacher_id' => 'required|string',
            'name' => 'required|string|max:150',
            'schedule_time' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'theme_color' => 'nullable|string|max:20'
        ]);

        $demo = DemoDataManager::getInstance();
        $newId = 'LMS-' . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);

        $newDc = [
            'id' => $newId,
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'name' => trim($validated['name']),
            'description' => $validated['description'] ?: 'Ruang kelas daring interaktif kurikulum Alia Islamic School.',
            'schedule_time' => $validated['schedule_time'] ?: 'Senin 08:00',
            'icon' => 'fa-laptop',
            'theme_color' => $validated['theme_color'] ?: '#2b6cb0',
            'materials_count' => 0,
            'assignments_count' => 0,
            'quizzes_count' => 0,
            'status' => 'aktif',
            'join_code' => 'ALIA-' . strtoupper(substr(md5($newId), 0, 5))
        ];

        $sessionAdded = session('demo_digital_classes_added', []);
        $sessionAdded[] = $newDc;
        session(['demo_digital_classes_added' => $sessionAdded]);

        return redirect()->route('lms.kelas-digital')
            ->with('success', "Kelas digital '{$newDc['name']}' berhasil dibuka! Kode gabung: {$newDc['join_code']}.");
    }

    /**
     * Hapus Kelas Digital (Session-based)
     */
    public function destroyKelasDigital(string $id)
    {
        $sessionDeleted = session('demo_digital_classes_deleted', []);
        $sessionDeleted[] = $id;
        session(['demo_digital_classes_deleted' => $sessionDeleted]);

        return redirect()->route('lms.kelas-digital')
            ->with('success', "Kelas digital ({$id}) telah ditutup dan diarsipkan.");
    }

    /**
     * T05.2 - Learning Materials (Materi & Modul Pembelajaran Digital)
     */
    public function materi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $materials = $demo->getLearningMaterials();
        $digitalClasses = $demo->getDigitalClasses();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();

        $selectedClassId = $request->get('class_id', 'all');
        $selectedType = $request->get('type', 'all');
        $search = $request->get('search');

        $filteredMaterials = $materials;

        if ($selectedClassId !== 'all') {
            $filteredMaterials = array_filter($filteredMaterials, function ($m) use ($selectedClassId) {
                return ($m['digital_class_id'] ?? '') === $selectedClassId;
            });
        }

        if ($selectedType !== 'all') {
            $filteredMaterials = array_filter($filteredMaterials, function ($m) use ($selectedType) {
                return ($m['type'] ?? '') === $selectedType;
            });
        }

        if ($search) {
            $filteredMaterials = array_filter($filteredMaterials, function ($m) use ($search) {
                return stripos($m['title'], $search) !== false ||
                       stripos($m['description'] ?? '', $search) !== false ||
                       stripos($m['file_name'] ?? '', $search) !== false;
            });
        }

        // Lengkapi objek relasi
        $enrichedMaterials = [];
        foreach ($filteredMaterials as $m) {
            $dc = collect($digitalClasses)->firstWhere('id', $m['digital_class_id']);
            $sb = collect($subjects)->firstWhere('id', $m['subject_id']);
            $tch = collect($teachers)->firstWhere('id', $m['teacher_id']);

            $enrichedMaterials[] = [
                'material' => $m,
                'digital_class' => $dc,
                'subject' => $sb,
                'teacher' => $tch
            ];
        }

        return view('lms.materi', compact(
            'enrichedMaterials',
            'digitalClasses',
            'subjects',
            'teachers',
            'selectedClassId',
            'selectedType',
            'search'
        ));
    }

    /**
     * Tambah Materi Baru (Session-based)
     */
    public function storeMateri(Request $request)
    {
        $validated = $request->validate([
            'digital_class_id' => 'required|string',
            'title' => 'required|string|max:200',
            'type' => 'required|in:PDF,Video,Audio,Link,Dokumen',
            'file_name' => 'nullable|string|max:150',
            'file_size' => 'nullable|string|max:50',
            'duration' => 'nullable|string|max:50',
            'external_url' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean'
        ]);

        $demo = DemoDataManager::getInstance();
        $dc = $demo->findDigitalClass($validated['digital_class_id']);

        $newId = 'MAT-' . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);
        $isPublished = $request->has('is_published') ? (bool) $request->input('is_published') : true;

        $newMat = [
            'id' => $newId,
            'digital_class_id' => $validated['digital_class_id'],
            'subject_id' => $dc['subject_id'] ?? 'MP-001',
            'teacher_id' => $dc['teacher_id'] ?? 'GUR-001',
            'title' => trim($validated['title']),
            'type' => $validated['type'],
            'file_name' => $validated['file_name'] ?: ($validated['type'] === 'PDF' ? 'Modul-Ajar-' . rand(1, 99) . '.pdf' : ($validated['type'] === 'Video' ? 'Video-Materi.mp4' : null)),
            'file_size' => $validated['file_size'] ?: ($validated['type'] === 'PDF' ? '2.5 MB' : ($validated['type'] === 'Video' ? '85 MB' : null)),
            'duration' => $validated['duration'] ?? null,
            'external_url' => $validated['external_url'] ?? null,
            'description' => $validated['description'] ?? 'Materi ajar resmi pembelajaran daring Alia Islamic School.',
            'downloads_count' => 0,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? date('Y-m-d H:i:s') : null
        ];

        $sessionAdded = session('demo_materials_added', []);
        $sessionAdded[] = $newMat;
        session(['demo_materials_added' => $sessionAdded]);

        return redirect()->route('lms.materi')
            ->with('success', "Materi '{$newMat['title']}' berhasil diunggah dan " . ($isPublished ? 'diterbitkan!' : 'disimpan sebagai draf.'));
    }

    /**
     * Ubah Status Publikasi Materi (Publish/Draft)
     */
    public function togglePublishMateri(string $id)
    {
        $demo = DemoDataManager::getInstance();
        $mat = $demo->findLearningMaterial($id);

        if (!$mat) {
            return redirect()->route('lms.materi')->with('error', 'Materi tidak ditemukan.');
        }

        $sessionUpdated = session('demo_materials_updated', []);
        $newStatus = !($mat['is_published'] ?? false);

        $sessionUpdated[$id] = [
            'is_published' => $newStatus,
            'published_at' => $newStatus ? date('Y-m-d H:i:s') : null
        ];
        session(['demo_materials_updated' => $sessionUpdated]);

        $statusLabel = $newStatus ? 'diterbitkan' : 'disimpan sebagai draf';
        return redirect()->route('lms.materi')
            ->with('success', "Status materi '{$mat['title']}' berhasil diubah: {$statusLabel}!");
    }

    /**
     * Hapus Materi Pembelajaran (Session-based)
     */
    public function destroyMateri(string $id)
    {
        $sessionDeleted = session('demo_materials_deleted', []);
        $sessionDeleted[] = $id;
        session(['demo_materials_deleted' => $sessionDeleted]);

        return redirect()->route('lms.materi')
            ->with('success', "Materi pembelajaran ({$id}) berhasil dihapus.");
    }
}

