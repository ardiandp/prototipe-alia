<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class AcademicController extends Controller
{
    /**
     * T04.1 - Class and learning-group management (Manajemen Rombel)
     */
    public function rombel(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $units = $demo->getUnits();
        $teachers = $demo->getTeachers();
        $students = $demo->getStudents();
        $subjects = $demo->getSubjects();
        $academicYears = $demo->getAcademicYears();
        $rooms = $demo->getFacilityRooms();

        $selectedClassId = $request->get('class_id', $classes[0]['id'] ?? '');
        $selectedUnit = $request->get('unit', 'all');
        $search = $request->get('search');

        // Filter daftar rombel
        $filteredClasses = $classes;
        if ($selectedUnit !== 'all') {
            $filteredClasses = array_filter($filteredClasses, function ($c) use ($selectedUnit) {
                return ($c['unit_id'] ?? '') === $selectedUnit;
            });
        }

        if ($search) {
            $filteredClasses = array_filter($filteredClasses, function ($c) use ($search) {
                return stripos($c['name'], $search) !== false || stripos($c['id'], $search) !== false;
            });
        }

        // Tentukan rombel yang sedang aktif dilihat detailnya
        $currentClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $currentClass = $c;
                break;
            }
        }
        if (!$currentClass && count($filteredClasses) > 0) {
            $currentClass = reset($filteredClasses);
            $selectedClassId = $currentClass['id'];
        }

        // Dapatkan santri-santri yang berada dalam rombel terpilih
        $classStudents = [];
        if ($currentClass) {
            $classStudents = array_values(array_filter($students, function ($s) use ($currentClass) {
                return ($s['class_id'] ?? '') === $currentClass['id'];
            }));
        }

        // Santri yang belum memiliki rombel atau dari kelas lain (untuk modal tambah santri ke rombel)
        $availableStudents = array_values(array_filter($students, function ($s) use ($currentClass) {
            if (!$currentClass) return false;
            // Santri di jenjang yang sama tapi belum di kelas ini
            $isSameLevel = ($s['unit_level'] ?? '') === str_replace('UNIT-', '', $currentClass['unit_id']);
            return $isSameLevel && ($s['class_id'] ?? '') !== $currentClass['id'];
        }));

        // Wali kelas objek
        $homeroomTeacher = null;
        if ($currentClass) {
            foreach ($teachers as $t) {
                if ($t['id'] === ($currentClass['homeroom_teacher_id'] ?? '')) {
                    $homeroomTeacher = $t;
                    break;
                }
            }
        }

        // Tahun ajaran aktif
        $activeYear = collect($academicYears)->firstWhere('is_current', true) ?? ($academicYears[0] ?? null);

        return view('akademik.rombel', compact(
            'classes',
            'filteredClasses',
            'currentClass',
            'selectedClassId',
            'selectedUnit',
            'search',
            'classStudents',
            'availableStudents',
            'units',
            'teachers',
            'homeroomTeacher',
            'subjects',
            'activeYear',
            'rooms'
        ));
    }

    /**
     * Tambah Rombel Baru (Session-based)
     */
    public function storeRombel(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:30',
            'name' => 'required|string|max:100',
            'unit_id' => 'required|string',
            'grade' => 'required|string|max:10',
            'homeroom_teacher_id' => 'required|string',
            'room' => 'required|string|max:50',
            'capacity' => 'required|numeric|min:1|max:50'
        ]);

        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();

        foreach ($classes as $c) {
            if ($c['id'] === $validated['id']) {
                return redirect()->route('akademik.rombel')->with('error', "Kode Rombel {$validated['id']} sudah ada!");
            }
        }

        $newClass = [
            'id' => strtoupper(trim($validated['id'])),
            'name' => trim($validated['name']),
            'unit_id' => $validated['unit_id'],
            'grade' => $validated['grade'],
            'homeroom_teacher_id' => $validated['homeroom_teacher_id'],
            'student_count' => 0,
            'capacity' => (int) $validated['capacity'],
            'room' => trim($validated['room']),
            'status' => 'aktif'
        ];

        $sessionAdded = session('demo_classes_added', []);
        $sessionAdded[] = $newClass;
        session(['demo_classes_added' => $sessionAdded]);

        return redirect()->route('akademik.rombel', ['class_id' => $newClass['id']])
            ->with('success', "Rombel {$newClass['name']} ({$newClass['id']}) berhasil ditambahkan ke sesi demo!");
    }

    /**
     * Alokasi / Tambahkan Santri ke Rombel (Session-based)
     */
    public function addStudentToRombel(Request $request, string $classId)
    {
        $validated = $request->validate([
            'student_id' => 'required|string'
        ]);

        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();
        $targetClass = $demo->findClass($classId);

        if (!$targetClass) {
            return redirect()->route('akademik.rombel')->with('error', 'Rombel tujuan tidak ditemukan.');
        }

        $sessionStudents = session('demo_students_added', []);
        $found = false;

        // Cek dan update di session_students_added jika ada
        foreach ($sessionStudents as &$st) {
            if ($st['id'] === $validated['student_id']) {
                $st['class_id'] = $classId;
                $found = true;
                break;
            }
        }

        if (!$found) {
            // Jika santri berasal dari dataset dasar, salin ke session_students_added dengan class_id baru
            foreach ($students as $st) {
                if ($st['id'] === $validated['student_id']) {
                    $stCopy = $st;
                    $stCopy['class_id'] = $classId;
                    $sessionStudents[] = $stCopy;
                    // Hapus dari default list via demo_students_deleted
                    $deleted = session('demo_students_deleted', []);
                    $deleted[] = $st['id'];
                    session(['demo_students_deleted' => $deleted]);
                    break;
                }
            }
        }

        session(['demo_students_added' => $sessionStudents]);

        return redirect()->route('akademik.rombel', ['class_id' => $classId])
            ->with('success', "Santri berhasil dialokasikan ke rombel {$targetClass['name']}!");
    }

    /**
     * Pindahkan / Keluarkan Santri dari Rombel
     */
    public function removeStudentFromRombel(Request $request, string $classId, string $studentId)
    {
        $sessionStudents = session('demo_students_added', []);
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();

        $found = false;
        foreach ($sessionStudents as &$st) {
            if ($st['id'] === $studentId) {
                $st['class_id'] = ''; // lepaskan dari rombel
                $found = true;
                break;
            }
        }

        if (!$found) {
            foreach ($students as $st) {
                if ($st['id'] === $studentId) {
                    $stCopy = $st;
                    $stCopy['class_id'] = '';
                    $sessionStudents[] = $stCopy;
                    $deleted = session('demo_students_deleted', []);
                    $deleted[] = $st['id'];
                    session(['demo_students_deleted' => $deleted]);
                    break;
                }
            }
        }

        session(['demo_students_added' => $sessionStudents]);

        return redirect()->route('akademik.rombel', ['class_id' => $classId])
            ->with('success', "Santri telah dikeluarkan dari rombel aktif.");
    }

    /**
     * T04.2 - Timetable (Jadwal Pelajaran per Rombel / Matriks Waktu Mingguan)
     */
    public function jadwal(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();
        $timetables = $demo->getTimetables();
        $rooms = $demo->getFacilityRooms();
        $units = $demo->getUnits();

        $selectedClassId = $request->get('class_id', $classes[0]['id'] ?? '');
        $viewMode = $request->get('view', 'matrix'); // 'matrix' (kalender mingguan) atau 'list' (tabel daftar)

        // Rombel terpilih
        $currentClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $currentClass = $c;
                break;
            }
        }
        if (!$currentClass && count($classes) > 0) {
            $currentClass = $classes[0];
            $selectedClassId = $currentClass['id'];
        }

        // Filter jadwal untuk rombel terpilih
        $classTimetables = array_filter($timetables, function ($tt) use ($selectedClassId) {
            return ($tt['class_id'] ?? '') === $selectedClassId;
        });

        // Struktur matriks [Hari][Slot]
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeSlots = [
            1 => ['time' => '07:30 - 08:50', 'label' => 'Sesi Pagi 1'],
            2 => ['time' => '09:05 - 10:25', 'label' => 'Sesi Pagi 2'],
            3 => ['time' => '10:40 - 12:00', 'label' => 'Sesi Menjelang Dzuhur'],
            4 => ['time' => '13:15 - 14:35', 'label' => 'Sesi Siang 1'],
            5 => ['time' => '14:45 - 16:00', 'label' => 'Sesi Ba\'da Ashar']
        ];

        $scheduleMatrix = [];
        foreach ($days as $day) {
            $scheduleMatrix[$day] = [];
            foreach (array_keys($timeSlots) as $slot) {
                $scheduleMatrix[$day][$slot] = null;
            }
        }

        foreach ($classTimetables as $tt) {
            $d = $tt['day'] ?? '';
            $s = (int) ($tt['slot'] ?? 0);
            if (isset($scheduleMatrix[$d]) && isset($scheduleMatrix[$d][$s])) {
                $scheduleMatrix[$d][$s] = $tt;
            }
        }

        return view('akademik.jadwal', compact(
            'classes',
            'currentClass',
            'selectedClassId',
            'viewMode',
            'days',
            'timeSlots',
            'scheduleMatrix',
            'classTimetables',
            'subjects',
            'teachers',
            'rooms',
            'units'
        ));
    }

    /**
     * Tambah / Perbarui Slot Jadwal Pelajaran (Session-based)
     */
    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|string',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'slot' => 'required|numeric|min:1|max:5',
            'subject_id' => 'required|string',
            'teacher_id' => 'required|string',
            'room' => 'required|string|max:50'
        ]);

        $timeSlots = [
            1 => '07:30 - 08:50',
            2 => '09:05 - 10:25',
            3 => '10:40 - 12:00',
            4 => '13:15 - 14:35',
            5 => '14:45 - 16:00'
        ];

        $sessionAdded = session('demo_timetables_added', []);
        $demo = DemoDataManager::getInstance();
        $timetables = $demo->getTimetables();

        // Cek apakah slot di hari dan kelas ini sudah ada
        $slotKey = null;
        foreach ($timetables as $tt) {
            if ($tt['class_id'] === $validated['class_id'] && $tt['day'] === $validated['day'] && (int)$tt['slot'] === (int)$validated['slot']) {
                $slotKey = $tt['id'];
                break;
            }
        }

        $sessionUpdated = session('demo_timetables_updated', []);
        if ($slotKey) {
            // Update slot yang ada
            $sessionUpdated[$slotKey] = [
                'subject_id' => $validated['subject_id'],
                'teacher_id' => $validated['teacher_id'],
                'room' => $validated['room'],
                'time' => $timeSlots[$validated['slot']] ?? '08:00 - 09:30'
            ];
            session(['demo_timetables_updated' => $sessionUpdated]);
        } else {
            // Buat slot baru
            $newEntry = [
                'id' => 'SCH-MAN-' . rand(1000, 9999),
                'class_id' => $validated['class_id'],
                'day' => $validated['day'],
                'slot' => (int) $validated['slot'],
                'time' => $timeSlots[$validated['slot']] ?? '08:00 - 09:30',
                'session_name' => "Sesi {$validated['slot']}",
                'subject_id' => $validated['subject_id'],
                'teacher_id' => $validated['teacher_id'],
                'room' => $validated['room'],
                'academic_year_id' => 'AY-2026-GANJIL',
                'status' => 'aktif'
            ];
            $sessionAdded[] = $newEntry;
            session(['demo_timetables_added' => $sessionAdded]);
        }

        return redirect()->route('akademik.jadwal', ['class_id' => $validated['class_id']])
            ->with('success', "Jadwal pelajaran hari {$validated['day']} sesi {$validated['slot']} berhasil diperbarui!");
    }

    /**
     * Hapus Slot Jadwal Pelajaran (Session-based)
     */
    public function destroyJadwal(Request $request, string $id)
    {
        $classId = $request->get('class_id', '');
        $sessionDeleted = session('demo_timetables_deleted', []);
        $sessionDeleted[] = $id;
        session(['demo_timetables_deleted' => $sessionDeleted]);

        return redirect()->route('akademik.jadwal', ['class_id' => $classId])
            ->with('success', "Slot jadwal pelajaran ({$id}) telah dikosongkan.");
    }

    /**
     * T04.3 - Academic Calendar (Kalender Akademik & Agenda Kegiatan Sekolah)
     */
    public function kalender(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $events = $demo->getAcademicCalendarEvents();
        $academicYears = $demo->getAcademicYears();
        $units = $demo->getUnits();

        $selectedCategory = $request->get('category', 'all');
        $search = $request->get('search');

        $filteredEvents = $events;
        if ($selectedCategory !== 'all') {
            $filteredEvents = array_filter($filteredEvents, function ($e) use ($selectedCategory) {
                return ($e['category'] ?? '') === $selectedCategory;
            });
        }

        if ($search) {
            $filteredEvents = array_filter($filteredEvents, function ($e) use ($search) {
                return stripos($e['title'], $search) !== false ||
                       stripos($e['location'] ?? '', $search) !== false ||
                       stripos($e['description'] ?? '', $search) !== false;
            });
        }

        // Siapkan payload JSON untuk FullCalendar plugin
        $calendarJson = array_map(function ($e) {
            return [
                'id' => $e['id'],
                'title' => $e['title'],
                'start' => $e['start'],
                // Fullcalendar end date is exclusive for all-day events, add 1 day if end is set
                'end' => !empty($e['end']) ? date('Y-m-d', strtotime($e['end'] . ' +1 day')) : $e['start'],
                'backgroundColor' => $e['color'] ?? '#2b6cb0',
                'borderColor' => $e['color'] ?? '#2b6cb0',
                'description' => $e['description'] ?? '',
                'location' => $e['location'] ?? '',
                'category' => $e['category'] ?? 'Kegiatan'
            ];
        }, array_values($events));

        $activeYear = collect($academicYears)->firstWhere('is_current', true) ?? ($academicYears[0] ?? null);

        return view('akademik.kalender', compact(
            'events',
            'filteredEvents',
            'selectedCategory',
            'search',
            'calendarJson',
            'activeYear',
            'units'
        ));
    }

    /**
     * Tambah Agenda / Kegiatan Kalender Akademik (Session-based)
     */
    public function storeKalender(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'category' => 'required|in:Akademik,Ujian,Tahfidz,Kegiatan,Libur,Rapor',
            'location' => 'nullable|string|max:100',
            'target_audience' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500'
        ]);

        $categoryColors = [
            'Akademik' => '#00a65a',
            'Ujian' => '#f39c12',
            'Tahfidz' => '#6b46c1',
            'Kegiatan' => '#2b6cb0',
            'Libur' => '#dd4b39',
            'Rapor' => '#00c0ef'
        ];

        $sessionAdded = session('demo_calendar_added', []);
        $newId = 'EVT-' . rand(100, 999);

        $newEvent = [
            'id' => $newId,
            'title' => trim($validated['title']),
            'start' => $validated['start'],
            'end' => $validated['end'] ?: $validated['start'],
            'category' => $validated['category'],
            'color' => $categoryColors[$validated['category']] ?? '#2b6cb0',
            'location' => $validated['location'] ?: 'Kampus Alia Islamic School',
            'description' => $validated['description'] ?: 'Kegiatan resmi dalam kalender pendidikan Alia Islamic School.',
            'target_audience' => $validated['target_audience'] ?: 'Seluruh Santri & Sivitas'
        ];

        $sessionAdded[] = $newEvent;
        session(['demo_calendar_added' => $sessionAdded]);

        return redirect()->route('akademik.kalender')
            ->with('success', "Agenda '{$newEvent['title']}' berhasil ditambahkan ke kalender akademik!");
    }

    /**
     * Hapus Agenda Kalender Akademik (Session-based)
     */
    public function destroyKalender(Request $request, string $id)
    {
        $sessionDeleted = session('demo_calendar_deleted', []);
        $sessionDeleted[] = $id;
        session(['demo_calendar_deleted' => $sessionDeleted]);

        return redirect()->route('akademik.kalender')
            ->with('success', "Agenda ({$id}) berhasil dihapus dari kalender akademik.");
    }

    /**
     * T04.4 - Student Attendance (Presensi Siswa Harian & Rekap Kehadiran)
     */
    public function presensi(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $students = $demo->getStudents();
        $allAttendance = $demo->getStudentAttendance();
        $units = $demo->getUnits();

        $selectedClassId = $request->get('class_id', $classes[0]['id'] ?? '');
        $selectedDate = $request->get('date', date('Y-m-d')); // Default hari ini atau tanggal filter
        $viewMode = $request->get('view', 'daily'); // 'daily' (input harian) atau 'recap' (rekap bulanan/semester)

        // Rombel aktif
        $currentClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $currentClass = $c;
                break;
            }
        }
        if (!$currentClass && count($classes) > 0) {
            $currentClass = $classes[0];
            $selectedClassId = $currentClass['id'];
        }

        // Santri yang terdaftar dalam rombel ini
        $classStudents = array_values(array_filter($students, function ($s) use ($selectedClassId) {
            return ($s['class_id'] ?? '') === $selectedClassId;
        }));

        // Presensi harian santri rombel ini pada tanggal terpilih
        $attendanceMap = [];
        foreach ($allAttendance as $att) {
            if ($att['class_id'] === $selectedClassId && $att['date'] === $selectedDate) {
                $attendanceMap[$att['student_id']] = $att;
            }
        }

        // Hitung ringkasan hari ini
        $counts = [
            'H' => 0, // Hadir
            'T' => 0, // Terlambat
            'S' => 0, // Sakit
            'I' => 0, // Izin
            'A' => 0  // Alpha
        ];

        foreach ($classStudents as $st) {
            $status = $attendanceMap[$st['id']]['status'] ?? 'H'; // Default Hadir jika belum diinput
            if (isset($counts[$status])) {
                $counts[$status]++;
            }
        }

        // Untuk view rekap (7 tanggal terkini)
        $recentDates = ['2026-11-01', '2026-11-02', '2026-11-03', '2026-11-04', '2026-11-05', '2026-11-06', '2026-11-07'];
        $matrixRecap = [];
        foreach ($classStudents as $st) {
            $matrixRecap[$st['id']] = [
                'student' => $st,
                'dates' => [],
                'total_H' => 0,
                'total_T' => 0,
                'total_S' => 0,
                'total_I' => 0,
                'total_A' => 0,
                'percentage' => 100
            ];

            foreach ($recentDates as $d) {
                $match = null;
                foreach ($allAttendance as $att) {
                    if ($att['student_id'] === $st['id'] && $att['date'] === $d) {
                        $match = $att;
                        break;
                    }
                }
                $stCode = $match['status'] ?? 'H';
                $matrixRecap[$st['id']]['dates'][$d] = $stCode;
                if (isset($matrixRecap[$st['id']]['total_' . $stCode])) {
                    $matrixRecap[$st['id']]['total_' . $stCode]++;
                }
            }

            $totalAttended = $matrixRecap[$st['id']]['total_H'] + $matrixRecap[$st['id']]['total_T'];
            $matrixRecap[$st['id']]['percentage'] = count($recentDates) > 0 ? round(($totalAttended / count($recentDates)) * 100, 1) : 100;
        }

        return view('akademik.presensi', compact(
            'classes',
            'currentClass',
            'selectedClassId',
            'selectedDate',
            'viewMode',
            'classStudents',
            'attendanceMap',
            'counts',
            'recentDates',
            'matrixRecap',
            'units'
        ));
    }

    /**
     * Simpan Presensi Harian Santri (Bulk update via form session)
     */
    public function storePresensi(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|string',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'notes' => 'nullable|array'
        ]);

        $sessionUpdated = session('demo_attendance_updated', []);
        $date = $validated['date'];

        foreach ($validated['attendance'] as $studentId => $status) {
            $key = $studentId . '_' . $date;
            $note = $validated['notes'][$studentId] ?? null;
            $timeIn = ($status === 'H') ? '06:' . rand(45, 55) : (($status === 'T') ? '07:' . rand(15, 25) : null);

            $sessionUpdated[$key] = [
                'status' => $status,
                'note' => $note,
                'time_in' => $timeIn,
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        session(['demo_attendance_updated' => $sessionUpdated]);

        return redirect()->route('akademik.presensi', ['class_id' => $validated['class_id'], 'date' => $date])
            ->with('success', "Presensi kelas tanggal " . date('d M Y', strtotime($date)) . " berhasil disimpan dan diperbarui!");
    }

    /**
     * T04.5 - Grade Input (Input Nilai Formatif, PTS, PAS, dan Nilai Akhir)
     */
    public function nilai(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $subjects = $demo->getSubjects();
        $students = $demo->getStudents();
        $teachers = $demo->getTeachers();
        $allGrades = $demo->getStudentGrades();

        $selectedClassId = $request->get('class_id', $classes[0]['id'] ?? '');
        $selectedSubjectId = $request->get('subject_id', $subjects[0]['id'] ?? '');

        // Rombel aktif
        $currentClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $currentClass = $c;
                break;
            }
        }
        if (!$currentClass && count($classes) > 0) {
            $currentClass = $classes[0];
            $selectedClassId = $currentClass['id'];
        }

        // Mapel aktif
        $currentSubject = null;
        foreach ($subjects as $sb) {
            if ($sb['id'] === $selectedSubjectId) {
                $currentSubject = $sb;
                break;
            }
        }
        if (!$currentSubject && count($subjects) > 0) {
            $currentSubject = $subjects[0];
            $selectedSubjectId = $currentSubject['id'];
        }

        // Santri yang terdaftar dalam rombel ini
        $classStudents = array_values(array_filter($students, function ($s) use ($selectedClassId) {
            return ($s['class_id'] ?? '') === $selectedClassId;
        }));

        // Mapping nilai santri rombel ini pada mapel terpilih
        $gradesMap = [];
        foreach ($allGrades as $grd) {
            if ($grd['class_id'] === $selectedClassId && $grd['subject_id'] === $selectedSubjectId) {
                $gradesMap[$grd['student_id']] = $grd;
            }
        }

        // Hitung statistik kelas pada mapel ini
        $scores = [];
        $passCount = 0;
        $kkm = $currentSubject['kkm'] ?? 75;

        foreach ($classStudents as $st) {
            $item = $gradesMap[$st['id']] ?? null;
            $final = $item['final_score'] ?? 80.0;
            $scores[] = $final;
            if ($final >= $kkm) {
                $passCount++;
            }
        }

        $avgScore = count($scores) > 0 ? round(array_sum($scores) / count($scores), 1) : 0;
        $maxScore = count($scores) > 0 ? max($scores) : 0;
        $minScore = count($scores) > 0 ? min($scores) : 0;
        $passPct = count($scores) > 0 ? round(($passCount / count($scores)) * 100, 1) : 0;

        return view('akademik.nilai', compact(
            'classes',
            'subjects',
            'currentClass',
            'currentSubject',
            'selectedClassId',
            'selectedSubjectId',
            'classStudents',
            'gradesMap',
            'avgScore',
            'maxScore',
            'minScore',
            'passCount',
            'passPct'
        ));
    }

    /**
     * Simpan / Perbarui Nilai Santri (Bulk update via session)
     */
    public function storeNilai(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'tugas_1' => 'required|array',
            'tugas_2' => 'required|array',
            'pts' => 'required|array',
            'pas' => 'required|array',
            'praktik' => 'required|array',
            'description' => 'nullable|array'
        ]);

        $demo = DemoDataManager::getInstance();
        $subject = $demo->findSubject($validated['subject_id']);
        $kkm = $subject['kkm'] ?? 75;

        $sessionUpdated = session('demo_grades_updated', []);

        foreach ($validated['tugas_1'] as $studentId => $t1) {
            $key = $studentId . '_' . $validated['subject_id'];
            $t1Val = (float) $t1;
            $t2Val = (float) ($validated['tugas_2'][$studentId] ?? 80);
            $ptsVal = (float) ($validated['pts'][$studentId] ?? 80);
            $pasVal = (float) ($validated['pas'][$studentId] ?? 80);
            $praktikVal = (float) ($validated['praktik'][$studentId] ?? 80);

            // Perhitungan bobot: 20% Tugas 1, 20% Tugas 2, 25% PTS, 35% PAS
            $finalKnowledge = round(($t1Val * 0.2) + ($t2Val * 0.2) + ($ptsVal * 0.25) + ($pasVal * 0.35), 1);
            $finalSkill = round($praktikVal, 1);
            $finalScore = round(($finalKnowledge * 0.6) + ($finalSkill * 0.4), 1);

            $predicate = ($finalScore >= 90) ? 'A' : (($finalScore >= 80) ? 'B' : (($finalScore >= $kkm) ? 'C' : 'D'));
            $desc = $validated['description'][$studentId] ?? ($predicate === 'A' ? 'Sangat menguasai materi secara mandiri.' : ($predicate === 'B' ? 'Menguasai materi dengan baik.' : 'Cukup memenuhi kriteria ketuntasan minimal.'));

            $sessionUpdated[$key] = [
                'tugas_1' => $t1Val,
                'tugas_2' => $t2Val,
                'pts' => $ptsVal,
                'pas' => $pasVal,
                'praktik' => $praktikVal,
                'final_knowledge' => $finalKnowledge,
                'final_skill' => $finalSkill,
                'final_score' => $finalScore,
                'predicate' => $predicate,
                'description' => $desc,
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        session(['demo_grades_updated' => $sessionUpdated]);

        return redirect()->route('akademik.nilai', [
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id']
        ])->with('success', "Nilai mata pelajaran '{$subject['name']}' berhasil disimpan dan dihitung otomatis!");
    }

    /**
     * T04.6 - E-Report (E-Rapor Santri & Lembar Rapor Lengkap)
     */
    public function rapor(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $students = $demo->getStudents();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();
        $allGrades = $demo->getStudentGrades();
        $allAttendance = $demo->getStudentAttendance();
        $academicYears = $demo->getAcademicYears();

        $selectedClassId = $request->get('class_id', $classes[0]['id'] ?? '');

        // Rombel aktif
        $currentClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $currentClass = $c;
                break;
            }
        }
        if (!$currentClass && count($classes) > 0) {
            $currentClass = $classes[0];
            $selectedClassId = $currentClass['id'];
        }

        // Santri rombel ini
        $classStudents = array_values(array_filter($students, function ($s) use ($selectedClassId) {
            return ($s['class_id'] ?? '') === $selectedClassId;
        }));

        $selectedStudentId = $request->get('student_id', $classStudents[0]['id'] ?? '');

        // Santri yang sedang dibuka rapornya
        $currentStudent = null;
        foreach ($classStudents as $st) {
            if ($st['id'] === $selectedStudentId) {
                $currentStudent = $st;
                break;
            }
        }
        if (!$currentStudent && count($classStudents) > 0) {
            $currentStudent = $classStudents[0];
            $selectedStudentId = $currentStudent['id'];
        }

        // Wali kelas
        $homeroomTeacher = null;
        if ($currentClass) {
            foreach ($teachers as $t) {
                if ($t['id'] === ($currentClass['homeroom_teacher_id'] ?? '')) {
                    $homeroomTeacher = $t;
                    break;
                }
            }
        }

        // Nilai seluruh mapel santri terpilih
        $studentSubjectGrades = [];
        $totalScore = 0;
        $countGraded = 0;

        if ($currentStudent) {
            foreach ($subjects as $sb) {
                $match = null;
                foreach ($allGrades as $grd) {
                    if ($grd['student_id'] === $currentStudent['id'] && $grd['subject_id'] === $sb['id']) {
                        $match = $grd;
                        break;
                    }
                }

                if (!$match) {
                    $kkm = $sb['kkm'] ?? 75;
                    $final = 82.0;
                    $match = [
                        'subject_id' => $sb['id'],
                        'subject_name' => $sb['name'],
                        'kkm' => $kkm,
                        'final_knowledge' => 82.0,
                        'final_skill' => 83.0,
                        'final_score' => 82.4,
                        'predicate' => 'B',
                        'description' => 'Menguasai materi pembelajaran dengan baik.'
                    ];
                }

                $match['group'] = $sb['group'] ?? 'Nasional';
                $studentSubjectGrades[] = $match;
                $totalScore += $match['final_score'];
                $countGraded++;
            }
        }

        $overallGpa = $countGraded > 0 ? round($totalScore / $countGraded, 1) : 0;

        // Rekapitulasi Presensi Santri
        $attendanceSummary = [
            'H' => 0,
            'S' => 0,
            'I' => 0,
            'A' => 0,
            'T' => 0
        ];
        if ($currentStudent) {
            foreach ($allAttendance as $att) {
                if ($att['student_id'] === $currentStudent['id']) {
                    $st = $att['status'] ?? 'H';
                    if (isset($attendanceSummary[$st])) {
                        $attendanceSummary[$st]++;
                    }
                }
            }
        }

        // Ekstrakurikuler & Kepribadian (Fictional seeded data)
        $extracurriculars = [
            ['name' => 'Pramuka Penggalang / Penegak', 'predicate' => 'Sangat Baik (A)', 'notes' => 'Disiplin dan aktif memimpin regu ambalan.'],
            ['name' => 'Klub Panahan & Bela Diri Tapak Suci', 'predicate' => 'Baik (B)', 'notes' => 'Menunjukkan ketepatan bidikan dan ketangkasan jurus.'],
            ['name' => 'Jurnalistik & Bahasa Arab', 'predicate' => 'Sangat Baik (A)', 'notes' => 'Aktif berkontribusi menulis artikel mading dwi-bahasa.']
        ];

        // Catatan Wali Kelas (Disimpan di session jika diedit)
        $sessionNotes = session('demo_report_notes', []);
        $noteKey = $selectedStudentId;
        $homeroomNote = $sessionNotes[$noteKey] ?? 'Ananda menunjukkan kesungguhan yang tinggi dalam belajar, sopan santun yang mulia kepada asatidz, serta hafalan Al-Qur\'an yang terus bertambah mutqin. Pertahankan prestasi dan tingkatkan semangat belajarnya.';

        $activeYear = collect($academicYears)->firstWhere('is_current', true) ?? ($academicYears[0] ?? null);

        return view('akademik.rapor', compact(
            'classes',
            'currentClass',
            'selectedClassId',
            'classStudents',
            'currentStudent',
            'selectedStudentId',
            'homeroomTeacher',
            'studentSubjectGrades',
            'overallGpa',
            'attendanceSummary',
            'extracurriculars',
            'homeroomNote',
            'activeYear'
        ));
    }

    /**
     * Simpan Catatan Wali Kelas pada E-Rapor (Session-based)
     */
    public function updateCatatanRapor(Request $request, string $studentId)
    {
        $validated = $request->validate([
            'class_id' => 'required|string',
            'homeroom_note' => 'required|string|max:500'
        ]);

        $sessionNotes = session('demo_report_notes', []);
        $sessionNotes[$studentId] = trim($validated['homeroom_note']);
        session(['demo_report_notes' => $sessionNotes]);

        return redirect()->route('akademik.rapor', [
            'class_id' => $validated['class_id'],
            'student_id' => $studentId
        ])->with('success', "Catatan wali kelas untuk rapor santri berhasil diperbarui!");
    }

    /**
     * T04.7 - Promotion / Grade Advancement Simulation (Simulasi Kenaikan Kelas)
     */
    public function kenaikan(Request $request)
    {
        $demo = DemoDataManager::getInstance();
        $classes = $demo->getClasses();
        $students = $demo->getStudents();
        $subjects = $demo->getSubjects();
        $teachers = $demo->getTeachers();
        $allGrades = $demo->getStudentGrades();
        $academicYears = $demo->getAcademicYears();

        $selectedClassId = $request->get('class_id', $classes[0]['id'] ?? '');

        // Rombel aktif
        $currentClass = null;
        foreach ($classes as $c) {
            if ($c['id'] === $selectedClassId) {
                $currentClass = $c;
                break;
            }
        }
        if (!$currentClass && count($classes) > 0) {
            $currentClass = $classes[0];
            $selectedClassId = $currentClass['id'];
        }

        // Santri rombel terpilih
        $classStudents = array_values(array_filter($students, function ($s) use ($selectedClassId) {
            return ($s['class_id'] ?? '') === $selectedClassId;
        }));

        // Wali kelas
        $homeroomTeacher = null;
        if ($currentClass) {
            foreach ($teachers as $t) {
                if ($t['id'] === ($currentClass['homeroom_teacher_id'] ?? '')) {
                    $homeroomTeacher = $t;
                    break;
                }
            }
        }

        // Cek keputusan kenaikan dari session
        $sessionDecisions = session('demo_promotion_decisions', []);

        // Rekapitulasi kelayakan tiap santri
        $studentPromotions = [];
        $statPromoted = 0;
        $statRetained = 0;
        $statGraduated = 0;

        $isGraduatingGrade = in_array((string)($currentClass['grade'] ?? ''), ['6', '9', '12']);

        foreach ($classStudents as $st) {
            $studentGrades = array_filter($allGrades, function ($g) use ($st) {
                return $g['student_id'] === $st['id'];
            });

            $totalScore = 0;
            $belowKkmCount = 0;
            $gradeCount = count($studentGrades);

            foreach ($studentGrades as $g) {
                $totalScore += $g['final_score'];
                if ($g['final_score'] < ($g['kkm'] ?? 75)) {
                    $belowKkmCount++;
                }
            }

            $gpa = $gradeCount > 0 ? round($totalScore / $gradeCount, 1) : 82.0;

            // Kriteria Otomatis:
            // 1. Mapel di bawah KKM maksimal 2
            // 2. Kehadiran minimal 85%
            // 3. Hafalan Tahfidz terpenuhi
            $attPct = $st['attendance_pct'] ?? 95.0;
            $tahfidzJuz = $st['tahfidz_juz'] ?? 3;

            $recommendedStatus = 'naik';
            if ($isGraduatingGrade) {
                $recommendedStatus = 'lulus';
            }

            if ($belowKkmCount > 2 || $attPct < 80.0) {
                $recommendedStatus = 'tinggal';
            }

            // Keputusan tersimpan atau default rekomendasi
            $decision = $sessionDecisions[$st['id']] ?? [
                'status' => $recommendedStatus,
                'target_class' => $isGraduatingGrade ? 'Alumni / Lulus' : 'Naik ke Tingkat Berikutnya',
                'notes' => 'Memenuhi kriteria ketuntasan akademik, tahfidz, dan tata tertib.'
            ];

            if ($decision['status'] === 'naik') $statPromoted++;
            elseif ($decision['status'] === 'lulus') $statGraduated++;
            else $statRetained++;

            $studentPromotions[] = [
                'student' => $st,
                'gpa' => $gpa,
                'below_kkm_count' => $belowKkmCount,
                'attendance_pct' => $attPct,
                'tahfidz_juz' => $tahfidzJuz,
                'recommended_status' => $recommendedStatus,
                'decision_status' => $decision['status'],
                'target_class' => $decision['target_class'],
                'notes' => $decision['notes']
            ];
        }

        $activeYear = collect($academicYears)->firstWhere('is_current', true) ?? ($academicYears[0] ?? null);

        return view('akademik.kenaikan', compact(
            'classes',
            'currentClass',
            'selectedClassId',
            'classStudents',
            'homeroomTeacher',
            'studentPromotions',
            'statPromoted',
            'statRetained',
            'statGraduated',
            'isGraduatingGrade',
            'activeYear'
        ));
    }

    /**
     * Simpan Keputusan Pleno Kenaikan Kelas Santri (Bulk update via session)
     */
    public function storeKenaikan(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|string',
            'decision' => 'required|array',
            'target_class' => 'nullable|array',
            'notes' => 'nullable|array'
        ]);

        $sessionDecisions = session('demo_promotion_decisions', []);

        foreach ($validated['decision'] as $studentId => $status) {
            $sessionDecisions[$studentId] = [
                'status' => $status,
                'target_class' => $validated['target_class'][$studentId] ?? ($status === 'lulus' ? 'Alumni' : 'Tingkat Selanjutnya'),
                'notes' => $validated['notes'][$studentId] ?? ($status === 'tinggal' ? 'Perlu pembinaan remedial intensif.' : 'Memenuhi seluruh kriteria ketuntasan.')
            ];
        }

        session(['demo_promotion_decisions' => $sessionDecisions]);

        return redirect()->route('akademik.kenaikan', ['class_id' => $validated['class_id']])
            ->with('success', "Keputusan rapat pleno kenaikan/kelulusan kelas berhasil disahkan dan disimpan!");
    }
}

