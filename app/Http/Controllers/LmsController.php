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

    /**
     * T05.3 - Assignments (Penugasan & PR Santri)
     */
    public function tugas(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $assignments = $demo->getAssignments();
        $digitalClasses = $demo->getDigitalClasses();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();
        $students = $demo->getStudents();
        $submissions = $demo->getSubmissions();

        $selectedClassId = $request->get('class_id', 'all');
        $selectedStatus = $request->get('status', 'all');
        $search = $request->get('search');

        $filteredAssignments = $assignments;

        if ($selectedClassId !== 'all') {
            $filteredAssignments = array_filter($filteredAssignments, function ($a) use ($selectedClassId) {
                return ($a['digital_class_id'] ?? '') === $selectedClassId;
            });
        }

        if ($selectedStatus !== 'all') {
            $filteredAssignments = array_filter($filteredAssignments, function ($a) use ($selectedStatus) {
                return ($a['status'] ?? '') === $selectedStatus;
            });
        }

        if ($search) {
            $filteredAssignments = array_filter($filteredAssignments, function ($a) use ($search) {
                return stripos($a['title'], $search) !== false ||
                       stripos($a['description'] ?? '', $search) !== false;
            });
        }

        // Lengkapi dengan partisipan dan penghitungan submisi terkumpul
        $enrichedAssignments = [];
        foreach ($filteredAssignments as $a) {
            $dc = collect($digitalClasses)->firstWhere('id', $a['digital_class_id']);
            $sb = collect($subjects)->firstWhere('id', $a['subject_id']);
            $tch = collect($teachers)->firstWhere('id', $a['teacher_id']);

            $totalClassStudents = count(array_filter($students, function ($s) use ($dc) {
                return ($s['class_id'] ?? '') === ($dc['class_id'] ?? '');
            }));

            $taskSubmissions = array_filter($submissions, function ($sub) use ($a) {
                return ($sub['assignment_id'] ?? '') === $a['id'];
            });

            $gradedSubmissions = array_filter($taskSubmissions, function ($sub) {
                return ($sub['status'] ?? '') === 'dinilai';
            });

            $enrichedAssignments[] = [
                'task' => $a,
                'digital_class' => $dc,
                'subject' => $sb,
                'teacher' => $tch,
                'total_students' => $totalClassStudents,
                'submitted_count' => count($taskSubmissions),
                'graded_count' => count($gradedSubmissions),
                'submissions' => array_values($taskSubmissions)
            ];
        }

        return view('lms.tugas', compact(
            'enrichedAssignments',
            'digitalClasses',
            'subjects',
            'teachers',
            'selectedClassId',
            'selectedStatus',
            'search'
        ));
    }

    /**
     * Tambah Penugasan Baru (Session-based)
     */
    public function storeTugas(Request $request)
    {
        $validated = $request->validate([
            'digital_class_id' => 'required|string',
            'title' => 'required|string|max:200',
            'deadline' => 'required|date',
            'max_score' => 'required|numeric|min:10|max:100',
            'weight_pct' => 'required|numeric|min:5|max:50',
            'attachment_name' => 'nullable|string|max:150',
            'description' => 'required|string|max:1000'
        ]);

        $demo = DemoDataManager::getInstance();
        $dc = $demo->findDigitalClass($validated['digital_class_id']);

        $newId = 'TSK-' . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);

        $newTask = [
            'id' => $newId,
            'digital_class_id' => $validated['digital_class_id'],
            'subject_id' => $dc['subject_id'] ?? 'MP-001',
            'teacher_id' => $dc['teacher_id'] ?? 'GUR-001',
            'title' => trim($validated['title']),
            'deadline' => date('Y-m-d H:i:s', strtotime($validated['deadline'])),
            'max_score' => (int) $validated['max_score'],
            'weight_pct' => (int) $validated['weight_pct'],
            'attachment_name' => !empty($validated['attachment_name']) ? $validated['attachment_name'] : 'Panduan-Tugas-' . rand(1, 99) . '.pdf',
            'description' => trim($validated['description']),
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_assignments_added', []);
        $sessionAdded[] = $newTask;
        session(['demo_assignments_added' => $sessionAdded]);

        return redirect()->route('lms.tugas')
            ->with('success', "Penugasan baru '{$newTask['title']}' berhasil diterbitkan ke kelas digital!");
    }

    /**
     * Hapus Penugasan (Session-based)
     */
    public function destroyTugas(string $id)
    {
        $sessionDeleted = session('demo_assignments_deleted', []);
        $sessionDeleted[] = $id;
        session(['demo_assignments_deleted' => $sessionDeleted]);

        return redirect()->route('lms.tugas')
            ->with('success', "Penugasan ({$id}) berhasil dihapus.");
    }

    /**
     * T05.4 - Submission Monitoring & Penilaian Tugas Santri
     */
    public function submission(Request $request, string $assignmentId)
    {
        $demo = DemoDataManager::getInstance();
        $task = $demo->findAssignment($assignmentId);

        if (!$task) {
            return redirect()->route('lms.tugas')->with('error', 'Penugasan tidak ditemukan.');
        }

        $digitalClasses = $demo->getDigitalClasses();
        $dc = collect($digitalClasses)->firstWhere('id', $task['digital_class_id']);
        $subjects = $demo->getSubjects();
        $subject = collect($subjects)->firstWhere('id', $task['subject_id']);
        $teachers = $demo->getTeachers();
        $teacher = collect($teachers)->firstWhere('id', $task['teacher_id']);

        $students = $demo->getStudents();
        // Santri yang terdaftar di kelas digital ini
        $classStudents = array_values(array_filter($students, function ($s) use ($dc) {
            return ($s['class_id'] ?? '') === ($dc['class_id'] ?? '');
        }));

        $submissions = $demo->getSubmissions();
        $taskSubmissions = array_filter($submissions, function ($sub) use ($assignmentId) {
            return ($sub['assignment_id'] ?? '') === $assignmentId;
        });

        $submissionsMap = [];
        foreach ($taskSubmissions as $sub) {
            $submissionsMap[$sub['student_id']] = $sub;
        }

        // Hitung statistik
        $submittedCount = count($taskSubmissions);
        $totalStudents = count($classStudents);
        $gradedCount = 0;
        $scores = [];

        foreach ($taskSubmissions as $sub) {
            if (($sub['status'] ?? '') === 'dinilai' && $sub['score'] !== null) {
                $gradedCount++;
                $scores[] = (float) $sub['score'];
            }
        }

        $avgScore = count($scores) > 0 ? round(array_sum($scores) / count($scores), 1) : 0;
        $maxScore = count($scores) > 0 ? max($scores) : 0;

        return view('lms.submission', compact(
            'task',
            'dc',
            'subject',
            'teacher',
            'classStudents',
            'submissionsMap',
            'submittedCount',
            'totalStudents',
            'gradedCount',
            'avgScore',
            'maxScore'
        ));
    }

    /**
     * Simpan / Beri Nilai Submission Santri (Session-based)
     */
    public function gradeSubmission(Request $request, string $assignmentId, string $studentId)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:500'
        ]);

        $demo = DemoDataManager::getInstance();
        $submissions = $demo->getSubmissions();
        $student = $demo->findStudent($studentId);

        $sessionAdded = session('demo_submissions_added', []);
        $sessionUpdated = session('demo_submissions_updated', []);

        // Cek apakah submisi sudah ada
        $existingSubId = null;
        foreach ($submissions as $sub) {
            if ($sub['assignment_id'] === $assignmentId && $sub['student_id'] === $studentId) {
                $existingSubId = $sub['id'];
                break;
            }
        }

        if ($existingSubId) {
            $sessionUpdated[$existingSubId] = [
                'status' => 'dinilai',
                'score' => (float) $validated['score'],
                'feedback' => $validated['feedback'] ?: 'Tugas telah diperiksa dan dinilai oleh ustadz pengampu.',
                'graded_at' => date('Y-m-d H:i:s')
            ];
            session(['demo_submissions_updated' => $sessionUpdated]);
        } else {
            // Jika santri belum ada submission, buat submission otomatis yang langsung dinilai
            $newSubId = 'SUB-' . rand(100, 999);
            $newSub = [
                'id' => $newSubId,
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'student_name' => $student['name'] ?? 'Santri',
                'file_name' => 'Tugas_' . ($student['nis'] ?? '2026') . '.pdf',
                'file_size' => '2.0 MB',
                'submitted_at' => date('Y-m-d H:i:s'),
                'status' => 'dinilai',
                'score' => (float) $validated['score'],
                'feedback' => $validated['feedback'] ?: 'Tugas telah diperiksa dan dinilai oleh ustadz pengampu.'
            ];
            $sessionAdded[] = $newSub;
            session(['demo_submissions_added' => $sessionAdded]);
        }

        return redirect()->route('lms.tugas.submission', ['assignmentId' => $assignmentId])
            ->with('success', "Nilai untuk santri " . ($student['name'] ?? '') . " berhasil disimpan: {$validated['score']} poin!");
    }

    /**
     * Simulasi Pengumpulan Tugas oleh Santri (Session-based)
     */
    public function simulateUpload(Request $request, string $assignmentId)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'file_name' => 'nullable|string|max:150'
        ]);

        $demo = DemoDataManager::getInstance();
        $student = $demo->findStudent($validated['student_id']);

        $sessionAdded = session('demo_submissions_added', []);
        $newSubId = 'SUB-SIM-' . rand(100, 999);
        $fileName = $validated['file_name'] ?: 'Jawaban_' . ($student['nis'] ?? 'Santri') . '.pdf';

        $newSub = [
            'id' => $newSubId,
            'assignment_id' => $assignmentId,
            'student_id' => $validated['student_id'],
            'student_name' => $student['name'] ?? 'Santri',
            'file_name' => $fileName,
            'file_size' => rand(15, 38) / 10 . ' MB',
            'submitted_at' => date('Y-m-d H:i:s'),
            'status' => 'belum_dinilai',
            'score' => null,
            'feedback' => null
        ];

        $sessionAdded[] = $newSub;
        session(['demo_submissions_added' => $sessionAdded]);

        return redirect()->route('lms.tugas.submission', ['assignmentId' => $assignmentId])
            ->with('success', "Simulasi berkas tugas santri {$student['name']} berhasil diunggah!");
    }

    /**
     * T05.5 — Quiz Demo (Ujian Online / Computer-Based Test / Bank Soal)
     */
    public function ujian(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $quizzes = $demo->getQuizzes();
        $classes = $demo->getClasses();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();
        $units = $demo->getUnits();

        // Filter
        $unitFilter = $request->query('unit');
        $statusFilter = $request->query('status');
        $search = strtolower(trim($request->query('q', '')));

        // Buat lookup maps
        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c;
        }

        $subjectMap = [];
        foreach ($subjects as $s) {
            $subjectMap[$s['id']] = $s;
        }

        $teacherMap = [];
        foreach ($teachers as $t) {
            $teacherMap[$t['id']] = $t;
        }

        $filtered = array_filter($quizzes, function ($qz) use ($unitFilter, $statusFilter, $search, $classMap, $subjectMap) {
            $cls = $classMap[$qz['class_id']] ?? null;
            $sbj = $subjectMap[$qz['subject_id']] ?? null;

            if ($unitFilter && (!$cls || $cls['unit_id'] !== $unitFilter)) {
                return false;
            }

            if ($statusFilter && $qz['status'] !== $statusFilter) {
                return false;
            }

            if ($search) {
                $matchTitle = str_contains(strtolower($qz['title']), $search);
                $matchCode = str_contains(strtolower($qz['code']), $search);
                $matchSubject = $sbj && str_contains(strtolower($sbj['name']), $search);
                if (!$matchTitle && !$matchCode && !$matchSubject) {
                    return false;
                }
            }

            return true;
        });

        // Enrich dengan statistik pengerjaan
        $enrichedQuizzes = [];
        $totalActive = 0;
        $totalFinishedAttempts = 0;
        $totalQuestionsAll = 0;

        foreach ($filtered as $qz) {
            $attempts = $demo->getQuizAttempts($qz['id']);
            $passedCount = 0;
            $totalScore = 0;
            $highestScore = 0;
            $lowestScore = 100;

            foreach ($attempts as $att) {
                if ($att['is_passed']) $passedCount++;
                $totalScore += $att['total_score'];
                if ($att['total_score'] > $highestScore) $highestScore = $att['total_score'];
                if ($att['total_score'] < $lowestScore) $lowestScore = $att['total_score'];
            }

            $attemptCount = count($attempts);
            $avgScore = $attemptCount > 0 ? round($totalScore / $attemptCount, 1) : 0;
            if ($attemptCount === 0) $lowestScore = 0;

            $totalQuestions = count($qz['questions'] ?? []);
            $totalQuestionsAll += $totalQuestions;
            if ($qz['status'] === 'aktif') $totalActive++;
            $totalFinishedAttempts += $attemptCount;

            $enrichedQuizzes[] = array_merge($qz, [
                'class_name' => $classMap[$qz['class_id']]['name'] ?? $qz['class_id'],
                'unit_id' => $classMap[$qz['class_id']]['unit_id'] ?? 'UNIT-SMA',
                'subject_name' => $subjectMap[$qz['subject_id']]['name'] ?? $qz['subject_id'],
                'teacher_name' => $teacherMap[$qz['teacher_id']]['name'] ?? $qz['teacher_id'],
                'questions_count' => $totalQuestions,
                'attempts_count' => $attemptCount,
                'avg_score' => $avgScore,
                'highest_score' => $highestScore,
                'lowest_score' => $lowestScore,
                'passed_count' => $passedCount
            ]);
        }

        $stats = [
            'total_quizzes' => count($quizzes),
            'active_quizzes' => $totalActive,
            'total_attempts' => $totalFinishedAttempts,
            'total_questions' => $totalQuestionsAll
        ];

        return view('lms.ujian', [
            'quizzes' => $enrichedQuizzes,
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'units' => $units,
            'stats' => $stats,
            'activeUnit' => $unitFilter,
            'activeStatus' => $statusFilter,
            'search' => $search
        ]);
    }

    /**
     * Simpan Kuis Baru (Session-based)
     */
    public function storeUjian(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'code' => 'required|string|max:30',
            'subject_id' => 'required|string',
            'class_id' => 'required|string',
            'teacher_id' => 'required|string',
            'category' => 'required|string',
            'duration_minutes' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'instructions' => 'nullable|string',
            'sample_question' => 'nullable|string'
        ]);

        $newQuizId = 'QZ-' . str_pad((string)(count(DemoDataManager::getInstance()->getQuizzes()) + 1), 3, '0', STR_PAD_LEFT);

        $questions = [
            [
                'number' => 1,
                'type' => 'pilihan_ganda',
                'question' => !empty($validated['sample_question']) ? $validated['sample_question'] : 'Soal evaluasi pemahaman materi: Manakah pernyataan yang paling tepat terkait topik ini?',
                'options' => [
                    'A' => 'Pernyataan kaidah pertama yang shahih',
                    'B' => 'Pernyataan opsi alternatif kedua',
                    'C' => 'Pernyataan penguat ketiga',
                    'D' => 'Pernyataan pelengkap keempat'
                ],
                'correct_answer' => 'A',
                'score_weight' => 50,
                'explanation' => 'Kunci jawaban A sesuai rujukan buku pegangan siswa.'
            ],
            [
                'number' => 2,
                'type' => 'uraian',
                'question' => 'Tuliskan dalil Al-Qur\'an atau Hadits Nabi yang mendasari pembahasan materi di atas secara ringkas!',
                'options' => [],
                'correct_answer' => 'Kutipan teks dalil atau ayat rujukan.',
                'score_weight' => 50,
                'explanation' => 'Penilaian didasarkan pada ketepatan teks dan kesesuaian makna.'
            ]
        ];

        $newQuiz = [
            'id' => $newQuizId,
            'title' => $validated['title'],
            'code' => strtoupper($validated['code']),
            'digital_class_id' => 'LMS-001',
            'subject_id' => $validated['subject_id'],
            'class_id' => $validated['class_id'],
            'teacher_id' => $validated['teacher_id'],
            'category' => $validated['category'],
            'duration_minutes' => (int) $validated['duration_minutes'],
            'passing_score' => (int) $validated['passing_score'],
            'randomize_questions' => true,
            'show_result_immediately' => true,
            'start_time' => !empty($validated['start_time']) ? $validated['start_time'] : date('Y-m-d 08:00:00'),
            'end_time' => !empty($validated['end_time']) ? $validated['end_time'] : date('Y-m-d 17:00:00', strtotime('+3 days')),
            'status' => 'aktif',
            'instructions' => !empty($validated['instructions']) ? $validated['instructions'] : 'Bacalah basmalah sebelum memulai ujian.',
            'questions' => $questions
        ];

        $sessionAdded = session('demo_quizzes_added', []);
        $sessionAdded[] = $newQuiz;
        session(['demo_quizzes_added' => $sessionAdded]);

        return redirect()->route('lms.ujian')
            ->with('success', "Paket Kuis / CBT [{$newQuiz['title']}] berhasil diterbitkan dengan ID {$newQuizId}!");
    }

    /**
     * Hapus Paket Kuis (Session-based)
     */
    public function destroyUjian(string $id)
    {
        $sessionDeleted = session('demo_quizzes_deleted', []);
        if (!in_array($id, $sessionDeleted)) {
            $sessionDeleted[] = $id;
            session(['demo_quizzes_deleted' => $sessionDeleted]);
        }

        return redirect()->route('lms.ujian')
            ->with('success', "Paket Kuis CBT [{$id}] berhasil dihapus dari daftar!");
    }

    /**
     * T05.6 — Learning Progress (Rekapitulasi Kemajuan Belajar Santri di LMS)
     */
    public function progres(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $units = $demo->getUnits();
        $students = $demo->getStudents();
        $materials = $demo->getLearningMaterials();
        $assignments = $demo->getAssignments();
        $quizzes = $demo->getQuizzes();
        $submissions = $demo->getSubmissions();

        $selectedClassId = $request->query('class_id', $classes[6]['id'] ?? 'CLS-SMA-10A'); // Default Kelas X MIPA 1
        $search = strtolower(trim($request->query('q', '')));

        // Temukan data kelas terpilih
        $selectedClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $selectedClass = $c;
                break;
            }
        }
        if (!$selectedClass && !empty($classes)) {
            $selectedClass = $classes[0];
            $selectedClassId = $selectedClass['id'];
        }

        // Filter materi untuk rombel ini
        $classMaterials = array_filter($materials, function($m) use ($selectedClassId) {
            return empty($m['target_class_ids']) || in_array($selectedClassId, $m['target_class_ids']);
        });
        $totalMaterialCount = count($classMaterials);

        // Filter tugas untuk rombel ini
        $classAssignments = array_filter($assignments, function($a) use ($selectedClassId) {
            // Cek apakah tugas ditargetkan ke rombel ini atau kelas digital terkait
            return true; // Prototipe relevan
        });
        $totalAssignmentCount = count($classAssignments);

        // Filter kuis/CBT untuk rombel ini
        $classQuizzes = array_filter($quizzes, function($q) use ($selectedClassId) {
            return empty($q['class_id']) || $q['class_id'] === $selectedClassId;
        });
        $totalQuizCount = count($classQuizzes);

        // Ambil santri di rombel ini
        $classStudents = array_filter($students, function($s) use ($selectedClassId) {
            return ($s['class_id'] ?? '') === $selectedClassId;
        });

        if ($search) {
            $classStudents = array_filter($classStudents, function($s) use ($search) {
                return str_contains(strtolower($s['name']), $search) || str_contains(strtolower($s['nis']), $search);
            });
        }

        // Submissions map by student_id
        $submissionMap = [];
        foreach ($submissions as $sub) {
            $submissionMap[$sub['student_id']][$sub['assignment_id']] = $sub;
        }

        // Hitung progres tiap santri
        $studentProgressList = [];
        $totalCompletionRate = 0;
        $totalPendingTasks = 0;
        $totalExamsPassed = 0;

        foreach ($classStudents as $std) {
            $sId = $std['id'];

            // 1. Materi terbaca (simulasi deterministic berdasarkan ID santri)
            $seedFactor = crc32($sId) % 100;
            $completedMaterials = $totalMaterialCount > 0 ? min($totalMaterialCount, max(1, (int) round(($seedFactor / 100) * $totalMaterialCount))) : 0;

            // 2. Tugas dikumpulkan
            $submittedAssignmentsCount = 0;
            $assignmentScoreSum = 0;
            $assignmentScoreCount = 0;

            if (isset($submissionMap[$sId])) {
                $submittedAssignmentsCount = count($submissionMap[$sId]);
                foreach ($submissionMap[$sId] as $sItem) {
                    if ($sItem['score'] !== null) {
                        $assignmentScoreSum += $sItem['score'];
                        $assignmentScoreCount++;
                    }
                }
            } else {
                // Fallback deterministic simulasi bila belum ada submisi eksplisit
                $submittedAssignmentsCount = $seedFactor > 30 ? min($totalAssignmentCount, 2) : 1;
            }

            $pendingAssignments = max(0, $totalAssignmentCount - $submittedAssignmentsCount);
            $totalPendingTasks += $pendingAssignments;

            $avgAssignmentScore = $assignmentScoreCount > 0 ? round($assignmentScoreSum / $assignmentScoreCount, 1) : ($seedFactor > 40 ? 85 : 75);

            // 3. Kuis diselesaikan & kelulusan
            $attempts = $demo->getQuizAttempts();
            $quizPassedCount = 0;
            $quizTakenCount = 0;
            $quizScoreSum = 0;

            foreach ($attempts as $att) {
                if ($att['student_id'] === $sId || $att['nis'] === $std['nis']) {
                    $quizTakenCount++;
                    $quizScoreSum += $att['total_score'];
                    if ($att['is_passed']) $quizPassedCount++;
                }
            }

            if ($quizTakenCount === 0) {
                // Simulasi skor kuis jika belum pernah submit kuis eksplisit
                $quizTakenCount = $totalQuizCount > 0 ? 1 : 0;
                $quizScore = $seedFactor > 35 ? rand(78, 95) : rand(55, 74);
                $quizPassed = $quizScore >= 75;
                if ($quizPassed) $quizPassedCount = 1;
                $avgQuizScore = $quizScore;
            } else {
                $avgQuizScore = round($quizScoreSum / $quizTakenCount, 1);
            }

            $totalExamsPassed += $quizPassedCount;

            // Total completion rate %:
            // Bobot: 30% Materi + 40% Tugas + 30% Kuis CBT
            $materialPct = $totalMaterialCount > 0 ? ($completedMaterials / $totalMaterialCount) * 100 : 100;
            $assignmentPct = $totalAssignmentCount > 0 ? ($submittedAssignmentsCount / $totalAssignmentCount) * 100 : 100;
            $quizPct = $totalQuizCount > 0 ? ($quizTakenCount / $totalQuizCount) * 100 : 100;

            $overallPct = round((0.3 * $materialPct) + (0.4 * $assignmentPct) + (0.3 * $quizPct));
            if ($overallPct > 100) $overallPct = 100;
            $totalCompletionRate += $overallPct;

            $statusBadge = 'Tepat Waktu';
            $badgeColor = 'success';
            if ($overallPct < 50 || $pendingAssignments >= 2) {
                $statusBadge = 'Perlu Pendampingan';
                $badgeColor = 'danger';
            } elseif ($overallPct < 75) {
                $statusBadge = 'Dalam Proses';
                $badgeColor = 'warning';
            }

            $studentProgressList[] = [
                'id' => $sId,
                'name' => $std['name'],
                'nis' => $std['nis'],
                'gender' => $std['gender'] ?? 'L',
                'completed_materials' => $completedMaterials,
                'total_materials' => $totalMaterialCount,
                'submitted_assignments' => $submittedAssignmentsCount,
                'pending_assignments' => $pendingAssignments,
                'total_assignments' => $totalAssignmentCount,
                'avg_assignment_score' => $avgAssignmentScore,
                'quizzes_taken' => $quizTakenCount,
                'quizzes_passed' => $quizPassedCount,
                'total_quizzes' => $totalQuizCount,
                'avg_quiz_score' => $avgQuizScore,
                'overall_pct' => $overallPct,
                'status_badge' => $statusBadge,
                'badge_color' => $badgeColor
            ];
        }

        $studentCount = count($studentProgressList);
        $avgClassCompletion = $studentCount > 0 ? round($totalCompletionRate / $studentCount, 1) : 0;

        $stats = [
            'total_students' => $studentCount,
            'avg_completion' => $avgClassCompletion,
            'total_pending_tasks' => $totalPendingTasks,
            'total_passed' => $totalExamsPassed
        ];

        return view('lms.progres', [
            'students' => $studentProgressList,
            'classes' => $classes,
            'selectedClass' => $selectedClass,
            'selectedClassId' => $selectedClassId,
            'stats' => $stats,
            'search' => $search,
            'totalMaterialCount' => $totalMaterialCount,
            'totalAssignmentCount' => $totalAssignmentCount,
            'totalQuizCount' => $totalQuizCount
        ]);
    }
}

