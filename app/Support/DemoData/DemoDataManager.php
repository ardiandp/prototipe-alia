<?php

namespace App\Support\DemoData;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;

/**
 * DemoDataManager: Sumber data Faker terpusat & deterministik.
 * Memastikan ID dan relasi Siswa, Wali, Kelas, Tagihan, Asrama, dan Tahfidz
 * tetap konsisten lintas modul dan portal.
 */
class DemoDataManager
{
    private static ?DemoDataManager $instance = null;
    private FakerGenerator $faker;

    private array $units = [];
    private array $academicYears = [];
    private array $classes = [];
    private array $teachers = [];
    private array $subjects = [];
    private array $guardians = [];
    private array $students = [];
    private array $boardingBuildings = [];
    private array $boardingRooms = [];
    private array $facilityRooms = [];
    private array $ppdbApplicants = [];
    private array $timetables = [];
    private array $calendarEvents = [];
    private array $studentAttendance = [];
    private array $studentGrades = [];
    private array $digitalClasses = [];
    private array $learningMaterials = [];
    private array $assignments = [];
    private array $submissions = [];
    private array $quizzes = [];
    private array $quizAttempts = [];
    private array $tahfidzPrograms = [];
    private array $halaqahs = [];
    private array $memorizationRecords = [];
    private array $murojaahRecords = [];
    private array $diniyahSubjects = [];
    private array $diniyahSchedules = [];
    private array $violationRules = [];
    private array $studentViolations = [];
    private array $studentAchievements = [];
    private array $counselingSessions = [];
    private array $extracurriculars = [];
    private array $uksMedicines = [];
    private array $uksVisits = [];
    private array $studentMutations = [];
    private array $residentAssignments = [];
    private array $boardingActivities = [];
    private array $leavePermissions = [];
    private array $boardingAttendance = [];
    private array $boardingMeals = [];
    private array $feeCategories = [];
    private array $studentInvoices = [];
    private array $paymentTransactions = [];
    private array $financeExpenses = [];
    private array $scholarships = [];
    private array $staffAttendance = [];
    private array $staffLeaves = [];
    private array $staffOvertimes = [];
    private array $staffEvaluations = [];
    private array $staffPayrolls = [];
    private array $staffTrainings = [];
    private array $facilityAssets = [];
    private array $roomBookings = [];
    private array $maintenanceReports = [];

    public function __construct()
    {
        $this->faker = FakerFactory::create('id_ID');
        // Gunakan seed tetap agar seluruh data konsisten dan repeatable
        $this->faker->seed(192601);

        $this->generateBaseline();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inisialisasi dataset baseline.
     */
    private function generateBaseline(): void
    {
        // 1. Unit Pendidikan
        $this->units = [
            [
                'id' => 'UNIT-SD',
                'code' => 'SD-ALIA',
                'name' => 'SD Islam Alia',
                'level' => 'SD',
                'npsn' => '20108921',
                'headmaster' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'phone' => '021-86901234',
                'email' => 'sd@alia.sch.id',
                'address' => 'Kampus 1 Alia, Jl. Pendidikan No. 10, Jakarta Timur',
                'student_count' => 320,
                'class_count' => 12,
                'status' => 'aktif'
            ],
            [
                'id' => 'UNIT-SMP',
                'code' => 'SMP-ALIA',
                'name' => 'SMP Islam Alia',
                'level' => 'SMP',
                'npsn' => '20108922',
                'headmaster' => 'Ustazah Siti Aminah, M.Pd.',
                'phone' => '021-86901235',
                'email' => 'smp@alia.sch.id',
                'address' => 'Kampus 2 Alia, Jl. Pesantren Modern No. 5, Jakarta Timur',
                'student_count' => 280,
                'class_count' => 10,
                'status' => 'aktif'
            ],
            [
                'id' => 'UNIT-SMA',
                'code' => 'SMA-ALIA',
                'name' => 'SMA Islam Alia Boarding School',
                'level' => 'SMA',
                'npsn' => '20108923',
                'headmaster' => 'Drs. H. Mulyadi, M.Pd.',
                'phone' => '021-86901236',
                'email' => 'sma@alia.sch.id',
                'address' => 'Kampus Asrama Alia, Jl. Pondok Kopi Raya Kav. 8, Jakarta Timur',
                'student_count' => 245,
                'class_count' => 8,
                'status' => 'aktif'
            ]
        ];

        // 2. Tahun Ajaran & Semester
        $this->academicYears = [
            [
                'id' => 'TA-2024-2',
                'name' => '2024/2025 Genap',
                'year' => '2024/2025',
                'semester' => 'Genap',
                'start_date' => '2025-01-06',
                'end_date' => '2025-06-20',
                'status' => 'selesai',
                'is_current' => false,
                'description' => 'Telah selesai dievaluasi & pembagian rapor'
            ],
            [
                'id' => 'TA-2025-1',
                'name' => '2025/2026 Ganjil',
                'year' => '2025/2026',
                'semester' => 'Ganjil',
                'start_date' => '2025-07-14',
                'end_date' => '2025-12-19',
                'status' => 'selesai',
                'is_current' => false,
                'description' => 'Telah selesai dievaluasi'
            ],
            [
                'id' => 'TA-2025-2',
                'name' => '2025/2026 Genap',
                'year' => '2025/2026',
                'semester' => 'Genap',
                'start_date' => '2026-01-05',
                'end_date' => '2026-06-19',
                'status' => 'selesai',
                'is_current' => false,
                'description' => 'Telah selesai dievaluasi'
            ],
            [
                'id' => 'TA-2026-1',
                'name' => '2026/2027 Ganjil',
                'year' => '2026/2027',
                'semester' => 'Ganjil',
                'start_date' => '2026-07-13',
                'end_date' => '2026-12-18',
                'status' => 'aktif',
                'is_current' => true,
                'description' => 'Periode perkuliahan & pembelajaran aktif saat ini'
            ],
            [
                'id' => 'TA-2026-2',
                'name' => '2026/2027 Genap',
                'year' => '2026/2027',
                'semester' => 'Genap',
                'start_date' => '2027-01-04',
                'end_date' => '2027-06-18',
                'status' => 'draft',
                'is_current' => false,
                'description' => 'Periode semester mendatang'
            ]
        ];

        // 3. Guru & Pegawai
        $teacherNames = [
            ['Dr. H. Zulkifli, Lc., M.A.', 'PAI & Tafsir', 'L', 'zulkifli@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Ketua Yayasan / Dosen Pembina', 'UNIT-SMA', 'avatar5.png'],
            ['Aisyah Salsabila, S.Pd.I.', 'Tahfidz Al-Quran', 'P', 'aisyah@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Koordinator Tahfidz Akhwat', 'UNIT-SMP', 'avatar3.png'],
            ['Salman Al-Farisi, S.Sos.', 'Bahasa Arab & Hadits', 'L', 'salman@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Mudir Asrama Santri', 'UNIT-SMA', 'avatar5.png'],
            ['Rahmat Hidayat, M.Sc.', 'Matematika IPA', 'L', 'rahmat.h@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Waka Kurikulum SMA', 'UNIT-SMA', 'avatar.png'],
            ['Khadijah Nurul Fikri, S.Pd.', 'Bahasa Inggris', 'P', 'khadijah@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Guru Bahasa Inggris SD/SMP', 'UNIT-SD', 'avatar2.png'],
            ['Budi Santoso, S.Kom.', 'Informatika & Robotika', 'L', 'budi.s@alia.sch.id', 'Guru Kontrak', 'Kepala Lab Komputer', 'UNIT-SMA', 'avatar04.png'],
            ['Nurul Hasanah, M.Pd.', 'Fisika & IPA', 'P', 'nurul.h@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Wali Kelas XI MIPA', 'UNIT-SMA', 'avatar3.png'],
            ['Irfan Maulana, S.Pd.', 'Pendidikan Jasmani & Olahraga', 'L', 'irfan@alia.sch.id', 'Guru Kontrak', 'Pembina Ekstrakurikuler Silat', 'UNIT-SMP', 'avatar.png'],
            ['Drs. H. Mulyadi, M.Pd.', 'Manajemen Pendidikan', 'L', 'mulyadi@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Kepala Sekolah SMA', 'UNIT-SMA', 'avatar5.png'],
            ['Hj. Nurul Hidayati, S.E.', 'Akuntansi & Keuangan', 'P', 'nurul.acc@alia.sch.id', 'Tenaga Kependidikan', 'Bendahara Utama Sekolah', 'UNIT-SMA', 'avatar2.png'],
            ['Ust. Muhammad Ihsan, S.Pd.I.', 'Pendidikan Dasar Islam', 'L', 'ihsan@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Kepala Sekolah SD', 'UNIT-SD', 'avatar.png'],
            ['Ustazah Siti Aminah, M.Pd.', 'Bahasa & Sastra Arab', 'P', 'aminah@alia.sch.id', 'Guru Tetap Yayasan (GTY)', 'Kepala Sekolah SMP', 'UNIT-SMP', 'avatar3.png']
        ];

        foreach ($teacherNames as $idx => $t) {
            $this->teachers[] = [
                'id' => 'GUR-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                'nip' => '1985' . str_pad($idx + 10, 4, '0', STR_PAD_LEFT) . '201001100' . ($idx + 1),
                'name' => $t[0],
                'subject' => $t[1],
                'gender' => $t[2],
                'email' => $t[3],
                'employment_status' => $t[4],
                'position' => $t[5],
                'unit_id' => $t[6],
                'avatar' => $t[7],
                'phone' => '0812' . $this->faker->numerify('########'),
                'education' => 'S1 / S2 Pendidikan',
                'status' => 'aktif'
            ];
        }

        // 3.b Mata Pelajaran (Kurikulum Nasional, Kepesantrenan/Diniyah, Muatan Lokal)
        $subjectList = [
            ['MP-001', 'Pendidikan Agama Islam & Budi Pekerti', 'PAI', 'Nasional', 75, 3, 'Semua Unit'],
            ['MP-002', 'Tahfidz & Tahsin Al-Qur\'an', 'Tahfidz', 'Kepesantrenan', 80, 6, 'Semua Unit'],
            ['MP-003', 'Bahasa Arab & Nahwu Sharaf', 'B. Arab', 'Kepesantrenan', 75, 4, 'SMP, SMA'],
            ['MP-004', 'Hadits & Aqidah Akhlak', 'Hadits', 'Kepesantrenan', 78, 3, 'Semua Unit'],
            ['MP-005', 'Fikih Ibadah & Muamalah', 'Fikih', 'Kepesantrenan', 78, 3, 'Semua Unit'],
            ['MP-006', 'Matematika Peminatan & Umum', 'Matematika', 'Nasional', 72, 4, 'Semua Unit'],
            ['MP-007', 'Bahasa Inggris & Conversation', 'B. Inggris', 'Nasional', 75, 4, 'Semua Unit'],
            ['MP-008', 'Bahasa Indonesia & Literasi', 'B. Indonesia', 'Nasional', 75, 4, 'Semua Unit'],
            ['MP-009', 'Fisika & Sains Terapan', 'Fisika', 'Nasional', 72, 3, 'SMP, SMA'],
            ['MP-010', 'Informatika, Koding & Robotika', 'Informatika', 'Muatan Lokal', 75, 2, 'Semua Unit'],
            ['MP-011', 'Pendidikan Jasmani & Thibbun Nabawi', 'PJOK', 'Muatan Lokal', 75, 2, 'Semua Unit']
        ];

        foreach ($subjectList as $sb) {
            $this->subjects[] = [
                'id' => $sb[0],
                'name' => $sb[1],
                'short_name' => $sb[2],
                'group' => $sb[3], // Nasional, Kepesantrenan, Muatan Lokal
                'kkm' => $sb[4],
                'hours_per_week' => $sb[5],
                'unit_target' => $sb[6],
                'status' => 'aktif'
            ];
        }

        // 4. Kelas & Rombel
        $classList = [
            ['CLS-SD-1A', 'Kelas 1 Ali bin Abi Thalib', 'UNIT-SD', '1', 'GUR-002', 28, 30],
            ['CLS-SD-1B', 'Kelas 1 Abu Bakar Ash-Shiddiq', 'UNIT-SD', '1', 'GUR-005', 27, 30],
            ['CLS-SD-2A', 'Kelas 2 Utsman bin Affan', 'UNIT-SD', '2', 'GUR-008', 26, 30],
            ['CLS-SMP-7A', 'Kelas VII Putra (Thariq bin Ziyad)', 'UNIT-SMP', '7', 'GUR-003', 29, 30],
            ['CLS-SMP-7B', 'Kelas VII Putri (Fathimah Az-Zahra)', 'UNIT-SMP', '7', 'GUR-007', 28, 30],
            ['CLS-SMP-8A', 'Kelas VIII Putra (Salahuddin Al-Ayyubi)', 'UNIT-SMP', '8', 'GUR-004', 30, 30],
            ['CLS-SMA-10A', 'Kelas X MIPA 1 (Boarding)', 'UNIT-SMA', '10', 'GUR-001', 28, 30],
            ['CLS-SMA-10B', 'Kelas X MIPA 2 (Boarding)', 'UNIT-SMA', '10', 'GUR-006', 29, 30],
            ['CLS-SMA-11A', 'Kelas XI MIPA (Boarding)', 'UNIT-SMA', '11', 'GUR-004', 27, 30],
            ['CLS-SMA-12A', 'Kelas XII MIPA (Boarding)', 'UNIT-SMA', '12', 'GUR-001', 25, 30]
        ];

        foreach ($classList as $c) {
            $this->classes[] = [
                'id' => $c[0],
                'name' => $c[1],
                'unit_id' => $c[2],
                'grade' => $c[3],
                'homeroom_teacher_id' => $c[4],
                'student_count' => $c[5],
                'capacity' => $c[6],
                'room' => 'Ruang ' . substr($c[0], -3),
                'status' => 'aktif'
            ];
        }

        // 5. Gedung & Kamar Asrama (T08.1)
        $this->boardingBuildings = [
            [
                'id' => 'BLD-001',
                'name' => 'Gedung Asrama Al-Fatih (Putra)',
                'code' => 'BLD-AF',
                'gender' => 'L', // L (Putra / Ikhwan), P (Putri / Akhwat)
                'total_floors' => 3,
                'supervisor_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'supervisor_phone' => '081234567891',
                'description' => 'Asrama santri putra jenjang SMP & SMA dengan lingkungan religius dan disiplin tinggi.',
                'status' => 'aktif'
            ],
            [
                'id' => 'BLD-002',
                'name' => 'Gedung Asrama Maryam binti Imran (Putri)',
                'code' => 'BLD-MR',
                'gender' => 'P',
                'total_floors' => 3,
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'supervisor_phone' => '081234567892',
                'description' => 'Asrama santri putri jenjang SMP & SMA dengan pengawasan intensif ustadzah pembina.',
                'status' => 'aktif'
            ],
            [
                'id' => 'BLD-003',
                'name' => 'Gedung Asrama Umar bin Khattab (Takhassus Tahfidz)',
                'code' => 'BLD-UBK',
                'gender' => 'L',
                'total_floors' => 2,
                'supervisor_name' => 'Budi Santoso, S.Kom. (Pamong Asrama)',
                'supervisor_phone' => '081234567893',
                'description' => 'Asrama khusus santri program akselerasi tahfidz 30 juz putra.',
                'status' => 'aktif'
            ]
        ];

        $rooms = [
            [
                'id' => 'KMR-A01',
                'building_id' => 'BLD-001',
                'building_name' => 'Gedung Asrama Al-Fatih (Putra)',
                'floor' => 1,
                'number' => '101',
                'name' => 'Kamar 101 Thariq bin Ziyad',
                'gender' => 'L',
                'category' => 'Putra',
                'capacity' => 6,
                'resident_student_ids' => ['SIS-0001', 'SIS-0003'], // Ahmad Fauzan, Bilal Ramadhan
                'facilities' => ['Ranjang Tingkat 3 Set', 'Lemari Pribadi 6 Pintu', 'AC Split 1.5 PK', 'Meja Belajar', 'Kamar Mandi Dalam'],
                'status' => 'aktif'
            ],
            [
                'id' => 'KMR-A02',
                'building_id' => 'BLD-001',
                'building_name' => 'Gedung Asrama Al-Fatih (Putra)',
                'floor' => 1,
                'number' => '102',
                'name' => 'Kamar 102 Khalid bin Walid',
                'gender' => 'L',
                'category' => 'Putra',
                'capacity' => 6,
                'resident_student_ids' => ['SIS-0007'], // Rayhan Pratama
                'facilities' => ['Ranjang Tingkat 3 Set', 'Lemari Pribadi 6 Pintu', 'AC Split 1.5 PK', 'Meja Belajar', 'Kamar Mandi Dalam'],
                'status' => 'aktif'
            ],
            [
                'id' => 'KMR-A03',
                'building_id' => 'BLD-001',
                'building_name' => 'Gedung Asrama Al-Fatih (Putra)',
                'floor' => 2,
                'number' => '201',
                'name' => 'Kamar 201 Salahuddin Al-Ayyubi',
                'gender' => 'L',
                'category' => 'Putra',
                'capacity' => 8,
                'resident_student_ids' => ['SIS-0009'], // Ibrahim Khalilullah
                'facilities' => ['Ranjang Tingkat 4 Set', 'Lemari Pribadi 8 Pintu', 'Kipas Angin Dinding', 'Meja Belajar', 'Kamar Mandi Luar'],
                'status' => 'aktif'
            ],
            [
                'id' => 'KMR-B01',
                'building_id' => 'BLD-002',
                'building_name' => 'Gedung Asrama Maryam binti Imran (Putri)',
                'floor' => 1,
                'number' => '101',
                'name' => 'Kamar 101 Khadijah Al-Kubra',
                'gender' => 'P',
                'category' => 'Putri',
                'capacity' => 6,
                'resident_student_ids' => ['SIS-0002'], // Siti Fatimah
                'facilities' => ['Ranjang Tingkat 3 Set', 'Lemari Pribadi 6 Pintu', 'AC Split 1.5 PK', 'Meja Belajar', 'Kamar Mandi Dalam'],
                'status' => 'aktif'
            ],
            [
                'id' => 'KMR-B02',
                'building_id' => 'BLD-002',
                'building_name' => 'Gedung Asrama Maryam binti Imran (Putri)',
                'floor' => 1,
                'number' => '102',
                'name' => 'Kamar 102 Aisyah Ash-Shiddiqah',
                'gender' => 'P',
                'category' => 'Putri',
                'capacity' => 6,
                'resident_student_ids' => ['SIS-0004'], // Zaskia Nur
                'facilities' => ['Ranjang Tingkat 3 Set', 'Lemari Pribadi 6 Pintu', 'AC Split 1.5 PK', 'Meja Belajar', 'Kamar Mandi Dalam'],
                'status' => 'aktif'
            ],
            [
                'id' => 'KMR-C01',
                'building_id' => 'BLD-003',
                'building_name' => 'Gedung Asrama Umar bin Khattab (Takhassus Tahfidz)',
                'floor' => 1,
                'number' => '101',
                'name' => 'Kamar 101 Zaid bin Tsabit (Takhassus)',
                'gender' => 'L',
                'category' => 'Putra',
                'capacity' => 4,
                'resident_student_ids' => [],
                'facilities' => ['Ranjang Single 4 Set', 'Lemari Pribadi 4 Pintu', 'AC Split 1 PK', 'Meja Halaqah Lesehan', 'Kamar Mandi Dalam'],
                'status' => 'aktif'
            ]
        ];
        foreach ($rooms as $r) {
            $this->boardingRooms[] = $r;
        }

        // 5.b Ruangan & Fasilitas Sekolah (Kelas, Lab, Aula, Perpustakaan)
        $facilityList = [
            ['RNG-101', 'Ruang Kelas 101', 'Kelas Teori', 'Gedung Umar bin Khattab (Lt 1)', 32, 'baik', 'UNIT-SMA'],
            ['RNG-102', 'Ruang Kelas 102', 'Kelas Teori', 'Gedung Umar bin Khattab (Lt 1)', 32, 'baik', 'UNIT-SMA'],
            ['RNG-201', 'Laboratorium Komputer & Robotika', 'Laboratorium', 'Gedung Ali bin Abi Thalib (Lt 2)', 36, 'baik', 'UNIT-SMA'],
            ['RNG-202', 'Laboratorium Sains & Bahasa', 'Laboratorium', 'Gedung Ali bin Abi Thalib (Lt 2)', 30, 'baik', 'UNIT-SMP'],
            ['RNG-301', 'Perpustakaan & Ruang Baca Digital', 'Perpustakaan', 'Gedung Pusat Perpustakaan (Lt 1)', 60, 'baik', 'UNIT-SMA'],
            ['RNG-302', 'Aula Serbaguna / Islamic Center', 'Aula', 'Gedung Utama Alia (Lt 3)', 350, 'baik', 'UNIT-SMA'],
            ['RNG-401', 'Masjid Jami\' Kampus Alia', 'Tempat Ibadah', 'Pusat Kampus Alia', 500, 'baik', 'UNIT-SMA'],
            ['RNG-402', 'Ruang UKS Terpadu', 'Kesehatan', 'Gedung Pelayanan Santri (Lt 1)', 10, 'baik', 'UNIT-SD'],
            ['RNG-501', 'Ruang Kelas 201', 'Kelas Teori', 'Gedung Utsman (Lt 2)', 30, 'perbaikan', 'UNIT-SMP']
        ];

        foreach ($facilityList as $fl) {
            $this->facilityRooms[] = [
                'id' => $fl[0],
                'name' => $fl[1],
                'type' => $fl[2],
                'location' => $fl[3],
                'capacity' => $fl[4],
                'condition' => $fl[5],
                'unit_id' => $fl[6],
                'status' => 'aktif'
            ];
        }

        // 6. Wali & Siswa Terhubung Konsisten
        $sampleStudents = [
            ['Ahmad Fauzan Al-Ghifari', 'L', 'CLS-SMA-10A', 'KMR-A01', 'Ir. Hendra Gunawan', '081122334455', 'Jakarta', '2010-04-15', 'SMA', '10', 'Boarding', 5, 'lunas', 'avatar5.png'],
            ['Siti Fatimah Az-Zahra', 'P', 'CLS-SMP-7B', 'KMR-B01', 'H. Bambang Sudiro', '081233445566', 'Bandung', '2013-08-20', 'SMP', '7', 'Boarding', 4, 'lunas', 'avatar3.png'],
            ['Muhammad Bilal Ramadhan', 'L', 'CLS-SMA-10A', 'KMR-A01', 'Drs. Ridwan Hakim', '081344556677', 'Bekasi', '2010-09-12', 'SMA', '10', 'Boarding', 6, 'belum_lunas', 'avatar.png'],
            ['Zaskia Nur Ramadhani', 'P', 'CLS-SMP-8A', null, 'H. Agus Hermawan', '081455667788', 'Depok', '2012-11-05', 'SMP', '8', 'Non-Boarding (Full Day)', 3, 'lunas', 'avatar2.png'],
            ['Faris Abdurrahman', 'L', 'CLS-SD-1A', null, 'Yudi Pratama, S.T.', '081566778899', 'Jakarta Timur', '2019-03-25', 'SD', '1', 'Non-Boarding (Full Day)', 1, 'lunas', 'avatar04.png'],
            ['Salma Khairunnisa', 'P', 'CLS-SMA-10B', 'KMR-B02', 'Dr. Tri Wibowo', '081677889900', 'Tangerang', '2010-07-18', 'SMA', '10', 'Boarding', 5, 'belum_lunas', 'avatar3.png'],
            ['Rayhan Pratama', 'L', 'CLS-SMA-11A', 'KMR-A02', 'H. Joko Susilo', '081788990011', 'Bogor', '2009-02-14', 'SMA', '11', 'Boarding', 7, 'lunas', 'avatar5.png'],
            ['Aisyah Humaira', 'P', 'CLS-SD-2A', null, 'M. Taufik, M.M.', '081899001122', 'Jakarta Timur', '2018-05-30', 'SD', '2', 'Non-Boarding (Full Day)', 2, 'lunas', 'avatar2.png'],
            ['Ibrahim Khalilullah', 'L', 'CLS-SMP-7A', 'KMR-A01', 'H. Anwar Ibrahim', '081900112233', 'Jakarta Selatan', '2013-01-10', 'SMP', '7', 'Boarding', 4, 'lunas', 'avatar.png'],
            ['Maryam Qanita', 'P', 'CLS-SMA-12A', 'KMR-B01', 'Ir. H. Danang Prasetyo', '081911223344', 'Surabaya', '2008-10-22', 'SMA', '12', 'Boarding', 10, 'lunas', 'avatar3.png']
        ];

        foreach ($sampleStudents as $idx => $s) {
            $studentId = 'SIS-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            $guardianId = 'WLI-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            $nis = '2026' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            $nisn = '0089' . $this->faker->numerify('######');

            $guardian = [
                'id' => $guardianId,
                'name' => $s[4],
                'phone' => $s[5],
                'email' => strtolower(str_replace([' ', '.', ','], '', $s[4])) . '@gmail.com',
                'occupation' => 'Wiraswasta / Profesional Swasta',
                'address' => 'Jl. Alia Raya No. ' . ($idx + 12) . ', Jakarta Timur',
                'student_id' => $studentId,
                'student_name' => $s[0]
            ];
            $this->guardians[] = $guardian;

            $this->students[] = [
                'id' => $studentId,
                'nis' => $nis,
                'nisn' => $nisn,
                'name' => $s[0],
                'gender' => $s[1],
                'pob' => $s[6],
                'dob' => $s[7],
                'unit_level' => $s[8],
                'unit_id' => 'UNIT-' . $s[8],
                'grade' => $s[9],
                'class_id' => $s[2],
                'residence_type' => $s[10],
                'boarding_room_id' => $s[3],
                'is_boarding' => !empty($s[3]),
                'avatar' => $s[13] ?? 'avatar5.png',
                'guardian_id' => $guardianId,
                'guardian_name' => $s[4],
                'guardian_phone' => $s[5],
                'status' => 'aktif',
                'tahfidz_juz' => $s[11],
                'tahfidz_last_surah' => 'Surah An-Naba s/d Al-Infitar',
                'tahfidz_predicate' => 'Jayyid Jiddan',
                'spp_status' => $s[12],
                'spp_nominal' => 1850000,
                'gpa' => 88.5 + ($idx % 5),
                'attendance_pct' => 97.5 + ($idx % 3)
            ];
        }

        // 7. Data Pendaftar PPDB Online
        $ppdbRaw = [
            ['PPDB-2026-001', 'Muhammad Fatih Robbani', 'L', 'SMA', 'SMA Islam Alia Boarding School', 'Reguler', 'diverifikasi', 'lulus', 'lunas', 'Ir. H. Rahmat Robbani', '081299887766', 'Berkas Lengkap (KK, Akta, Rapor)', 92.5],
            ['PPDB-2026-002', 'Aisyah Putri Azzahra', 'P', 'SMP', 'SMP Islam Alia', 'Prestasi Tahfidz', 'diverifikasi', 'lulus', 'menunggu', 'Drs. Hendro Wibowo', '081388776655', 'Sertifikat Tahfidz 5 Juz terlampir', 95.0],
            ['PPDB-2026-003', 'Haikal Zaidan Al-Farizi', 'L', 'SMA', 'SMA Islam Alia Boarding School', 'Reguler', 'menunggu', 'proses', 'belum_lunas', 'Bambang Kusuma, S.E.', '081477665544', 'Menunggu upload Kartu Keluarga', 78.0],
            ['PPDB-2026-004', 'Nayla Zahra Salsabila', 'P', 'SD', 'SD Islam Alia', 'Reguler', 'diverifikasi', 'lulus', 'lunas', 'Agus Setiawan', '081566554433', 'Berkas Lengkap', 88.0],
            ['PPDB-2026-005', 'Ziyad Mubarak', 'L', 'SMP', 'SMP Islam Alia', 'Afirmasi / Yatim', 'diverifikasi', 'lulus', 'lunas', 'Ibu Nurhayati (Wali)', '081655443322', 'Surat Keterangan Kematian & SKTM', 90.0],
            ['PPDB-2026-006', 'Khalid Saifullah', 'L', 'SMA', 'SMA Islam Alia Boarding School', 'Prestasi Akademik', 'diverifikasi', 'lulus', 'lunas', 'Dr. Ir. Taufik Hidayat', '081744332211', 'Juara 1 OSN Matematika Kab.', 96.0],
            ['PPDB-2026-007', 'Safiyya Almahyra', 'P', 'SD', 'SD Islam Alia', 'Reguler', 'menunggu', 'proses', 'belum_lunas', 'Rizal Fahmi, S.Kom.', '081833221100', 'Menunggu verifikasi biaya formulir', 82.0],
            ['PPDB-2026-008', 'Umar Abdul Aziz', 'L', 'SMP', 'SMP Islam Alia', 'Reguler', 'ditolak', 'tidak_lulus', 'belum_lunas', 'Syamsul Arifin', '081922110099', 'Nilai tes baca Al-Qur\'an belum memenuhi standar KKM', 62.0]
        ];

        foreach ($ppdbRaw as $p) {
            $this->ppdbApplicants[] = [
                'id' => $p[0],
                'reg_number' => $p[0],
                'name' => $p[1],
                'gender' => $p[2],
                'unit_level' => $p[3],
                'unit_target' => $p[4],
                'track' => $p[5], // Reguler, Prestasi Tahfidz, Prestasi Akademik, Afirmasi
                'doc_status' => $p[6], // menunggu, diverifikasi, ditolak
                'selection_status' => $p[7], // proses, lulus, tidak_lulus
                'payment_status' => $p[8], // lunas, menunggu, belum_lunas
                'guardian_name' => $p[9],
                'guardian_phone' => $p[10],
                'notes' => $p[11],
                'exam_score' => $p[12],
                'registered_at' => '2026-11-0' . rand(1, 5) . ' 10:30:00',
                'status' => 'aktif'
            ];
        }

        // 8. Baseline Matriks Jadwal Pelajaran (Timetable)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeSlots = [
            1 => ['07:30 - 08:50', 'Sesi Pagi 1'],
            2 => ['09:05 - 10:25', 'Sesi Pagi 2'],
            3 => ['10:40 - 12:00', 'Sesi Menjelang Dzuhur'],
            4 => ['13:15 - 14:35', 'Sesi Siang 1'],
            5 => ['14:45 - 16:00', 'Sesi Ba\'da Ashar']
        ];

        $subjectKeys = array_column($this->subjects, 'id');
        $teacherKeys = array_column($this->teachers, 'id');

        $ttIdx = 1;
        foreach ($this->classes as $cIdx => $cls) {
            foreach ($days as $dIdx => $day) {
                // Hari Jumat maksimal 3 slot (karena Shalat Jumat dan persiapan Diniyah)
                $maxSlots = ($day === 'Jumat') ? 3 : 5;
                for ($slot = 1; $slot <= $maxSlots; $slot++) {
                    $sbId = $subjectKeys[($cIdx * 3 + $dIdx * 2 + $slot) % count($subjectKeys)];
                    $tId = $teacherKeys[($cIdx + $dIdx + $slot) % count($teacherKeys)];

                    // Slot pertama Senin: Tahfidz Quran / Halaqah Pagi
                    if ($day === 'Senin' && $slot === 1) {
                        $sbId = 'MP-002'; // Tahfidz & Tahsin
                        $tId = 'GUR-001'; // Dr. H. Zulkifli / Pendidik Al-Quran
                    }

                    $this->timetables[] = [
                        'id' => 'SCH-' . str_pad($ttIdx++, 4, '0', STR_PAD_LEFT),
                        'class_id' => $cls['id'],
                        'day' => $day,
                        'slot' => $slot,
                        'time' => $timeSlots[$slot][0],
                        'session_name' => $timeSlots[$slot][1],
                        'subject_id' => $sbId,
                        'teacher_id' => $tId,
                        'room' => $cls['room'] ?? 'Ruang Belajar',
                        'academic_year_id' => 'AY-2026-GANJIL',
                        'status' => 'aktif'
                    ];
                }
            }
        }

        // 9. Agenda Kalender Akademik (Tahun Ajaran 2026/2027)
        $this->calendarEvents = [
            [
                'id' => 'EVT-001',
                'title' => 'Awal Masuk Santri Baru (Masa Ta\'aruf Santri - MATSAMA)',
                'start' => '2026-07-13',
                'end' => '2026-07-16',
                'category' => 'Kegiatan',
                'color' => '#2b6cb0',
                'location' => 'Masjid Jami\' & Aula Utama Alia',
                'description' => 'Orientasi pengenalan kurikulum pesantren, adab penuntut ilmu, dan keasramaan.',
                'target_audience' => 'Semua Unit (Santri Baru)'
            ],
            [
                'id' => 'EVT-002',
                'title' => 'Hari Pertama Belajar Efektif Semester Ganjil',
                'start' => '2026-07-20',
                'end' => '2026-07-20',
                'category' => 'Akademik',
                'color' => '#00a65a',
                'location' => 'Seluruh Ruang Kelas',
                'description' => 'Mulai KBM efektif dan halaqah tahfidz Al-Quran intensif.',
                'target_audience' => 'Seluruh Santri & Guru'
            ],
            [
                'id' => 'EVT-003',
                'title' => 'Peringatan Hari Kemerdekaan RI Ke-81',
                'start' => '2026-08-17',
                'end' => '2026-08-17',
                'category' => 'Libur',
                'color' => '#dd4b39',
                'location' => 'Lapangan Utama Kampus Alia',
                'description' => 'Upacara bendera gabungan santri ikhwan, akhwat, asatidzah, dan pimpinan.',
                'target_audience' => 'Seluruh Sivitas Sekolah'
            ],
            [
                'id' => 'EVT-004',
                'title' => 'Penilaian Tengah Semester (PTS) Ganjil',
                'start' => '2026-09-21',
                'end' => '2026-09-26',
                'category' => 'Ujian',
                'color' => '#f39c12',
                'location' => 'Laboratorium CBT & Ruang Ujian',
                'description' => 'Evaluasi hasil belajar kurikulum nasional dan tes tulis diniyah.',
                'target_audience' => 'SD, SMP, SMA'
            ],
            [
                'id' => 'EVT-005',
                'title' => 'Munaqasyah Tahfidz Al-Qur\'an Juz 30, 29, & 1-5',
                'start' => '2026-10-12',
                'end' => '2026-10-15',
                'category' => 'Tahfidz',
                'color' => '#6b46c1',
                'location' => 'Masjid Alia Islamic School',
                'description' => 'Ujian kelayakan hafalan Al-Qur\'an sekali duduk di hadapan dewan penguji lajnah.',
                'target_audience' => 'Santri Terpilih Tahfidz'
            ],
            [
                'id' => 'EVT-006',
                'title' => 'Peringatan Hari Santri Nasional',
                'start' => '2026-10-22',
                'end' => '2026-10-22',
                'category' => 'Kegiatan',
                'color' => '#2b6cb0',
                'location' => 'Gedung Serbaguna & Halaman',
                'description' => 'Kajian akbar kebangsaan dan lomba kreasi seni islami antarkelas.',
                'target_audience' => 'Seluruh Santri'
            ],
            [
                'id' => 'EVT-007',
                'title' => 'Penilaian Akhir Semester (PAS) Ganjil TA 2026/2027',
                'start' => '2026-11-23',
                'end' => '2026-12-04',
                'category' => 'Ujian',
                'color' => '#f39c12',
                'location' => 'CBT Online & Ruang Kelas',
                'description' => 'Asesmen sumatif akhir semester ganjil seluruh mata pelajaran.',
                'target_audience' => 'Semua Tingkat'
            ],
            [
                'id' => 'EVT-008',
                'title' => 'Classmeeting & Porseni Antar-Asrama',
                'start' => '2026-12-07',
                'end' => '2026-12-10',
                'category' => 'Kegiatan',
                'color' => '#00c0ef',
                'location' => 'Sport Center & Lapangan Olahraga',
                'description' => 'Turnamen futsal, panahan, renang, cerdas cermat, dan debat Bahasa Arab/Inggris.',
                'target_audience' => 'Santri Full Day & Boarding'
            ],
            [
                'id' => 'EVT-009',
                'title' => 'Pembagian Rapor Semester Ganjil & Laporan Tahfidz',
                'start' => '2026-12-18',
                'end' => '2026-12-18',
                'category' => 'Rapor',
                'color' => '#00a65a',
                'location' => 'Ruang Rombel Masing-Masing',
                'description' => 'Pertemuan wali santri dengan wali kelas dan pengambilan buku laporan capaian.',
                'target_audience' => 'Orang Tua / Wali Santri'
            ],
            [
                'id' => 'EVT-010',
                'title' => 'Libur Semester Ganjil Santri & Kepulangan Asrama',
                'start' => '2026-12-19',
                'end' => '2027-01-03',
                'category' => 'Libur',
                'color' => '#dd4b39',
                'location' => 'Kediaman Masing-Masing',
                'description' => 'Masa libur semester dan penugasan murojaah mandiri di rumah bersama orang tua.',
                'target_audience' => 'Seluruh Santri'
            ]
        ];

        // 10. Data Baseline Presensi Santri (Student Attendance)
        // Dibuat untuk 7 hari sekolah aktif terakhir
        $recentDates = [
            '2026-11-01', '2026-11-02', '2026-11-03', '2026-11-04', '2026-11-05', '2026-11-06', '2026-11-07'
        ];
        $statuses = ['H', 'H', 'H', 'H', 'H', 'H', 'H', 'H', 'T', 'S', 'I', 'A']; // Mayoritas Hadir

        $attId = 1;
        foreach ($this->students as $sIdx => $st) {
            foreach ($recentDates as $dIdx => $date) {
                // Pola deterministik presensi per santri
                $stCode = $statuses[($sIdx * 3 + $dIdx * 5) % count($statuses)];
                $timeIn = ($stCode === 'H') ? '06:' . rand(40, 58) : (($stCode === 'T') ? '07:' . rand(15, 30) : null);
                $note = null;
                if ($stCode === 'S') $note = 'Sakit demam / istirahat di UKS';
                elseif ($stCode === 'I') $note = 'Izin keperluan keluarga dengan surat wali';
                elseif ($stCode === 'T') $note = 'Terlambat masuk gerbang utama';
                elseif ($stCode === 'A') $note = 'Tanpa keterangan';

                $this->studentAttendance[] = [
                    'id' => 'ATT-' . str_pad($attId++, 5, '0', STR_PAD_LEFT),
                    'student_id' => $st['id'],
                    'student_name' => $st['name'],
                    'nis' => $st['nis'],
                    'class_id' => $st['class_id'],
                    'date' => $date,
                    'status' => $stCode, // H = Hadir, S = Sakit, I = Izin, A = Alpha, T = Terlambat
                    'time_in' => $timeIn,
                    'note' => $note
                ];
            }
        }

        // 11. Data Baseline Nilai Siswa (Student Grades)
        // Nilai Formatif (Tugas 1, Tugas 2), PTS, PAS, dan Nilai Keterampilan/Praktik
        $grdId = 1;
        foreach ($this->students as $sIdx => $st) {
            foreach ($this->subjects as $sbIdx => $sb) {
                // Variasi nilai berbasis deterministik santri + mapel
                $base = 78 + (($sIdx * 3 + $sbIdx * 7) % 18); // Rentang 78 - 95
                $tugas1 = min(98, $base + (($sIdx % 3) * 2));
                $tugas2 = min(96, $base + (($sbIdx % 3) * 3) - 1);
                $pts = min(97, $base - (($sIdx % 2) * 2) + 2);
                $pas = min(99, $base + (($sbIdx % 2) * 3) + 1);
                $praktik = min(98, $base + 2);

                // Bobot: 20% Tugas1, 20% Tugas2, 25% PTS, 35% PAS
                $finalKnowledge = round(($tugas1 * 0.2) + ($tugas2 * 0.2) + ($pts * 0.25) + ($pas * 0.35), 1);
                $finalSkill = round($praktik, 1);
                $finalScore = round(($finalKnowledge * 0.6) + ($finalSkill * 0.4), 1);

                $kkm = $sb['kkm'] ?? 75;
                $predicate = ($finalScore >= 90) ? 'A' : (($finalScore >= 80) ? 'B' : (($finalScore >= $kkm) ? 'C' : 'D'));

                $this->studentGrades[] = [
                    'id' => 'GRD-' . str_pad($grdId++, 5, '0', STR_PAD_LEFT),
                    'student_id' => $st['id'],
                    'student_name' => $st['name'],
                    'nis' => $st['nis'],
                    'class_id' => $st['class_id'],
                    'subject_id' => $sb['id'],
                    'subject_name' => $sb['name'],
                    'kkm' => $kkm,
                    'tugas_1' => (float) $tugas1,
                    'tugas_2' => (float) $tugas2,
                    'pts' => (float) $pts,
                    'pas' => (float) $pas,
                    'praktik' => (float) $praktik,
                    'final_knowledge' => (float) $finalKnowledge,
                    'final_skill' => (float) $finalSkill,
                    'final_score' => (float) $finalScore,
                    'predicate' => $predicate,
                    'description' => ($predicate === 'A') ? 'Sangat menguasai materi dengan capaian istimewa.' : (($predicate === 'B') ? 'Menguasai materi dengan baik dan aktif berdiskusi.' : 'Cukup menguasai capaian pembelajaran minimal.'),
                    'academic_year_id' => 'AY-2026-GANJIL'
                ];
            }
        }

        // 12. Data Baseline Ruang Kelas Digital (LMS Digital Classes)
        $lmsPresets = [
            ['LMS-001', 'CLS-SMA-10A', 'MP-001', 'GUR-001', 'PAI & Tafsir Tematik X MIPA 1', 'Kajian tafsir ayat ahkam, akidah ahlussunnah, dan adab thalabul ilmi.', 'Senin 07:30', 'fa-book', '#2b6cb0', 4, 3, 2],
            ['LMS-002', 'CLS-SMA-10A', 'MP-006', 'GUR-002', 'Matematika Peminatan X MIPA 1', 'Eksplorasi fungsi eksponensial, logaritma, dan vektor analitik.', 'Selasa 09:05', 'fa-calculator', '#00a65a', 6, 4, 1],
            ['LMS-003', 'CLS-SMA-10A', 'MP-003', 'GUR-003', 'Bahasa Arab & Nahwu Sharaf X', 'Kaidah dasar Nahwu Wadih, tashrif lughawi, dan muhadatsah yaumiyyah.', 'Rabu 10:40', 'fa-language', '#6b46c1', 5, 2, 2],
            ['LMS-004', 'CLS-SMA-10A', 'MP-009', 'GUR-004', 'Fisika & Sains Terapan X MIPA 1', 'Konsep mekanika klasik, gerak lurus beraturan, dan hukum gravitasi Newton.', 'Kamis 13:15', 'fa-flask', '#dd4b39', 3, 2, 1],
            ['LMS-005', 'CLS-SMP-7A', 'MP-007', 'GUR-005', 'English Intensive & Public Speaking VII', 'Vocabulary building, Islamic daily conversation, and basic grammar.', 'Senin 09:05', 'fa-globe', '#f39c12', 4, 3, 1],
            ['LMS-006', 'CLS-SMP-7B', 'MP-002', 'GUR-006', 'Tahfidz & Tahsin Al-Qur\'an VII Putri', 'Bimbingan tajwid jazariyah, makharijul huruf, dan setor ziyadah hafalan juz 30.', 'Jumat 07:30', 'fa-star', '#6b46c1', 8, 5, 3],
            ['LMS-007', 'CLS-SD-1A', 'MP-008', 'GUR-007', 'Bahasa Indonesia & Literasi Membaca 1A', 'Pengenalan fonik, ejaan kata berima, dan cerita keteladanan para nabi.', 'Selasa 07:30', 'fa-pencil', '#00c0ef', 5, 2, 1]
        ];

        foreach ($lmsPresets as $lp) {
            $this->digitalClasses[] = [
                'id' => $lp[0],
                'class_id' => $lp[1],
                'subject_id' => $lp[2],
                'teacher_id' => $lp[3],
                'name' => $lp[4],
                'description' => $lp[5],
                'schedule_time' => $lp[6],
                'icon' => $lp[7],
                'theme_color' => $lp[8],
                'materials_count' => $lp[9],
                'assignments_count' => $lp[10],
                'quizzes_count' => $lp[11],
                'status' => 'aktif',
                'join_code' => 'ALIA-' . strtoupper(substr(md5($lp[0]), 0, 5))
            ];
        }

        // 13. Data Baseline Materi & Modul Pembelajaran (Learning Materials)
        $this->learningMaterials = [
            [
                'id' => 'MAT-001',
                'digital_class_id' => 'LMS-001',
                'subject_id' => 'MP-001',
                'teacher_id' => 'GUR-001',
                'title' => 'Kaidah Tafsir Ayat Ahkam: Bab Ibadah & Muamalah',
                'type' => 'PDF',
                'file_name' => 'Kaidah-Tafsir-Ayat-Ahkam-Bab-1.pdf',
                'file_size' => '3.8 MB',
                'duration' => null,
                'external_url' => null,
                'description' => 'Ringkasan metodologi penafsiran ayat-ayat hukum dalam Al-Qur\'an karya ulama salafus shalih.',
                'downloads_count' => 28,
                'is_published' => true,
                'published_at' => '2026-10-15 08:00:00'
            ],
            [
                'id' => 'MAT-002',
                'digital_class_id' => 'LMS-001',
                'subject_id' => 'MP-001',
                'teacher_id' => 'GUR-001',
                'title' => 'Video Kajian: Adab Penuntut Ilmu Menurut Imam An-Nawawi',
                'type' => 'Video',
                'file_name' => 'Talaqqi-Adab-Penuntut-Ilmu.mp4',
                'file_size' => '124 MB',
                'duration' => '35 Menit',
                'external_url' => 'https://youtube.com/watch?v=alia_adab_01',
                'description' => 'Rekaman video ceramah interaktif adab thalabul ilmi dan akhlak santri mukim.',
                'downloads_count' => 30,
                'is_published' => true,
                'published_at' => '2026-10-18 09:30:00'
            ],
            [
                'id' => 'MAT-003',
                'digital_class_id' => 'LMS-002',
                'subject_id' => 'MP-006',
                'teacher_id' => 'GUR-002',
                'title' => 'Modul Latihan Vektor Analitik & Geometri Ruang Dimensi Tiga',
                'type' => 'PDF',
                'file_name' => 'Modul-Vektor-Analitik-Kelas-X.pdf',
                'file_size' => '4.2 MB',
                'duration' => null,
                'external_url' => null,
                'description' => 'Latihan 50 soal peminatan matematika persiapan olimpiade dan seleksi PTN.',
                'downloads_count' => 25,
                'is_published' => true,
                'published_at' => '2026-10-20 10:15:00'
            ],
            [
                'id' => 'MAT-004',
                'digital_class_id' => 'LMS-003',
                'subject_id' => 'MP-003',
                'teacher_id' => 'GUR-003',
                'title' => 'Kitab Matan Al-Ajurrumiyyah & Tabel Tashrif Istilahi',
                'type' => 'PDF',
                'file_name' => 'Matan-Al-Ajurrumiyyah-Tashrif.pdf',
                'file_size' => '2.1 MB',
                'duration' => null,
                'external_url' => null,
                'description' => 'Teks lengkap matan ilmu nahwu dasar beserta bagan skema perubahan wazan fi\'il.',
                'downloads_count' => 30,
                'is_published' => true,
                'published_at' => '2026-10-22 07:45:00'
            ],
            [
                'id' => 'MAT-005',
                'digital_class_id' => 'LMS-004',
                'subject_id' => 'MP-009',
                'teacher_id' => 'GUR-004',
                'title' => 'Simulasi Interaktif PhET: Hukum Gravitasi & Gerak Parabola',
                'type' => 'Link',
                'file_name' => null,
                'file_size' => null,
                'duration' => '15 Menit',
                'external_url' => 'https://phet.colorado.edu/sims/html/projectile-motion/latest/',
                'description' => 'Laboratorium virtual interaktif fisika terapan untuk santri ikhwan.',
                'downloads_count' => 19,
                'is_published' => true,
                'published_at' => '2026-10-25 13:00:00'
            ],
            [
                'id' => 'MAT-006',
                'digital_class_id' => 'LMS-005',
                'subject_id' => 'MP-007',
                'teacher_id' => 'GUR-005',
                'title' => 'Audio Listening & Conversation Guide: Daily Islamic Manners',
                'type' => 'Audio',
                'file_name' => 'Listening-Track-01-Islamic-Manners.mp3',
                'file_size' => '14.5 MB',
                'duration' => '18 Menit',
                'external_url' => null,
                'description' => 'Materi rekaman audio native speaker untuk melatih pendengaran bahasa Inggris islami.',
                'downloads_count' => 22,
                'is_published' => true,
                'published_at' => '2026-10-28 11:00:00'
            ],
            [
                'id' => 'MAT-007',
                'digital_class_id' => 'LMS-001',
                'subject_id' => 'MP-001',
                'teacher_id' => 'GUR-001',
                'title' => 'Draf Silabus & Bahan Diskusi: Fiqih Mawaris (Ilmu Waris)',
                'type' => 'Dokumen',
                'file_name' => 'Draf-Bahan-Ajar-Fiqih-Mawaris.docx',
                'file_size' => '1.5 MB',
                'duration' => null,
                'external_url' => null,
                'description' => 'Materi kajian pembagian harta waris menurut syariat Islam (masih dalam peninjauan).',
                'downloads_count' => 0,
                'is_published' => false,
                'published_at' => null
            ]
        ];

        // 14. Data Baseline Tugas & PR Santri (Assignments)
        $this->assignments = [
            [
                'id' => 'TSK-001',
                'digital_class_id' => 'LMS-001',
                'subject_id' => 'MP-001',
                'teacher_id' => 'GUR-001',
                'title' => 'Tugas 1: Resume Analisis Ayat-Ayat Ahkam Shalat Berjamaah',
                'deadline' => '2026-11-15 23:59:00',
                'max_score' => 100,
                'weight_pct' => 20,
                'description' => 'Tuliskan rangkuman tafsir surat Al-Baqarah ayat 43 dan hadits Nabi tentang keutamaan shalat berjamaah 27 derajat. Ketik rapi format PDF.',
                'attachment_name' => 'Panduan-Resume-Ayat-Ahkam.pdf',
                'status' => 'aktif'
            ],
            [
                'id' => 'TSK-002',
                'digital_class_id' => 'LMS-002',
                'subject_id' => 'MP-006',
                'teacher_id' => 'GUR-002',
                'title' => 'Tugas Praktik 2: Soal Latihan Vektor Satuan & Perkalian Silang',
                'deadline' => '2026-11-18 23:59:00',
                'max_score' => 100,
                'weight_pct' => 20,
                'description' => 'Kerjakan 5 soal nomor genap pada halaman 42 modul matematika peminatan. Unggah foto lembar jawaban atau berkas scan PDF.',
                'attachment_name' => 'Lembar-Kerja-Vektor-42.pdf',
                'status' => 'aktif'
            ],
            [
                'id' => 'TSK-003',
                'digital_class_id' => 'LMS-003',
                'subject_id' => 'MP-003',
                'teacher_id' => 'GUR-003',
                'title' => 'Tugas Mandiri: I\'rab 5 Ayat Pilihan Surat Al-Mulk',
                'deadline' => '2026-11-20 20:00:00',
                'max_score' => 100,
                'weight_pct' => 15,
                'description' => 'Uraikan kedudukan i\'rab (mubtada\', khabar, fa\'il, maf\'ul bih) dari ayat 1 sampai 5 Surat Al-Mulk dengan harakat lengkap.',
                'attachment_name' => 'Tabel-Irab-AlMulk.docx',
                'status' => 'aktif'
            ],
            [
                'id' => 'TSK-004',
                'digital_class_id' => 'LMS-004',
                'subject_id' => 'MP-009',
                'teacher_id' => 'GUR-004',
                'title' => 'Laporan Praktikum: Gerak Parabola dengan Simulasi PhET',
                'deadline' => '2026-11-22 23:59:00',
                'max_score' => 100,
                'weight_pct' => 25,
                'description' => 'Catat hasil eksperimen kecepatan awal terhadap jarak tempuh peluru, buat grafik linier, dan simpulkan hukum mekanika gerak.',
                'attachment_name' => 'Template-Laporan-Fisika-PhET.docx',
                'status' => 'aktif'
            ],
            [
                'id' => 'TSK-005',
                'digital_class_id' => 'LMS-005',
                'subject_id' => 'MP-007',
                'teacher_id' => 'GUR-005',
                'title' => 'Speaking Assignment: 2-Minute Speech "Muslim Teenager in Modern Era"',
                'deadline' => '2026-11-25 18:00:00',
                'max_score' => 100,
                'weight_pct' => 20,
                'description' => 'Rekam pidato bahasa Inggris santri berdurasi 2 menit, perhatikan intonasi, pengucapan kata, dan percaya diri. Unggah berkas audio MP3 atau video MP4.',
                'attachment_name' => 'Speech-Rubric-Guideline.pdf',
                'status' => 'aktif'
            ]
        ];

        // 15. Data Baseline Submission Santri untuk TSK-001 (Untuk T05.4)
        $this->submissions = [
            [
                'id' => 'SUB-001',
                'assignment_id' => 'TSK-001',
                'student_id' => 'SIS-0001',
                'student_name' => 'Ahmad Fauzan Al-Ghifari',
                'file_name' => 'Tugas1_AhmadFauzan_PAI.pdf',
                'file_size' => '1.8 MB',
                'submitted_at' => '2026-11-06 14:20:10',
                'status' => 'dinilai', // dinilai, belum_dinilai, terlambat
                'score' => 92,
                'feedback' => 'Analisis ayat sangat runut dan dalil hadits riwayat Bukhari dikutip dengan sanad yang jelas. Mumtaz!'
            ],
            [
                'id' => 'SUB-002',
                'assignment_id' => 'TSK-001',
                'student_id' => 'SIS-0003',
                'student_name' => 'Muhammad Bilal Ramadhan',
                'file_name' => 'Tugas1_BilalRamadhan_AyatAhkam.pdf',
                'file_size' => '2.1 MB',
                'submitted_at' => '2026-11-06 19:45:00',
                'status' => 'belum_dinilai',
                'score' => null,
                'feedback' => null
            ]
        ];

        // 16. Data Baseline Kuis & Ujian Online CBT (Quizzes)
        $this->quizzes = [
            [
                'id' => 'QZ-001',
                'title' => 'PTS Genap: Fikih Thaharah & Shalat Berjamaah',
                'code' => 'CBT-FKH-10A',
                'digital_class_id' => 'LMS-001',
                'subject_id' => 'MP-001',
                'class_id' => 'CLS-SMA-10A',
                'teacher_id' => 'GUR-001',
                'category' => 'PTS', // Kuis Harian, PTS, PAS, Tryout
                'duration_minutes' => 60,
                'passing_score' => 75,
                'randomize_questions' => true,
                'show_result_immediately' => true,
                'start_time' => '2026-11-10 08:00:00',
                'end_time' => '2026-11-12 17:00:00',
                'status' => 'aktif', // draft, aktif, selesai
                'instructions' => 'Pilihlah satu jawaban yang paling tepat pada soal pilihan ganda, dan uraikan penjelasan dalil secara ringkas pada soal essay.',
                'questions' => [
                    [
                        'number' => 1,
                        'type' => 'pilihan_ganda',
                        'question' => 'Manakah di antara air berikut yang dihukumi Thahir Muthahhir ghairu makruh menurut jumhur ulama Syafi\'iyyah?',
                        'options' => [
                            'A' => 'Air laut dan air embun pegunungan',
                            'B' => 'Air yang terjemur terik matahari dalam bejana tembaga (musyammas)',
                            'C' => 'Air teh manis yang harum',
                            'D' => 'Air musta\'mal kurang dari dua qullah'
                        ],
                        'correct_answer' => 'A',
                        'score_weight' => 20,
                        'explanation' => 'Air mutlak seperti air laut, hujan, embun, dan mata air adalah suci dan mensucikan tanpa makruh.'
                    ],
                    [
                        'number' => 2,
                        'type' => 'pilihan_ganda',
                        'question' => 'Berapakah ukuran volume dua qullah (qullatain) jika dikonversikan ke dalam satuan liter modern standar?',
                        'options' => [
                            'A' => 'Sekitar 100 liter',
                            'B' => 'Sekitar 216 liter (atau bak kubus sisi ± 60 cm)',
                            'C' => 'Sekitar 500 liter',
                            'D' => 'Sekitar 50 liter'
                        ],
                        'correct_answer' => 'B',
                        'score_weight' => 20,
                        'explanation' => 'Dua qullah menurut hitungan mu\'tamad madzhab Syafi\'i setara dengan kurang lebih 216 liter air.'
                    ],
                    [
                        'number' => 3,
                        'type' => 'pilihan_ganda',
                        'question' => 'Jika seorang makmum masbuq mendapati imam sedang ruku\', kapan makmum tersebut terhitung mendapatkan satu rakaat yang sah?',
                        'options' => [
                            'A' => 'Mendapati imam sebelum bangkit dari sujud kedua',
                            'B' => 'Mendapati tasyahhud akhir bersama imam',
                            'C' => 'Ikut ruku\' bersama imam dan mendapati thuma\'ninah sebelum imam i\'tidal',
                            'D' => 'Cukup berniat takbiratul ihram sebelum salam'
                        ],
                        'correct_answer' => 'C',
                        'score_weight' => 20,
                        'explanation' => 'Makmum masbuq memperoleh rakaat bila mendapati ruku\' bersama imam disertai thuma\'ninah sebelum imam bangkit i\'tidal.'
                    ],
                    [
                        'number' => 4,
                        'type' => 'pilihan_ganda',
                        'question' => 'Apakah hukum sujud sahwi dalam shalat menurut jumhur fuqaha?',
                        'options' => [
                            'A' => 'Wajib bagi makmum masbuq',
                            'B' => 'Sunnah muakkadah bila meninggalkan ab\'adhus shalat',
                            'C' => 'Fardhu \'ain yang membatalkan shalat bila ditinggalkan',
                            'D' => 'Mubah tanpa pahala'
                        ],
                        'correct_answer' => 'B',
                        'score_weight' => 20,
                        'explanation' => 'Sujud sahwi berhukum sunnah muakkad ketika meninggalkan sunnah ab\'adh atau terjadi keraguan rakaat.'
                    ],
                    [
                        'number' => 5,
                        'type' => 'uraian',
                        'question' => 'Jelaskan syarat-syarat sahnya mengusap khuff (sepatu/kaos kaki tebal) sebagai pengganti membasuh kaki saat berwudhu!',
                        'options' => [],
                        'correct_answer' => 'Khuff dipakai dalam keadaan suci sempurna, menutup mata kaki, kuat dipakai berjalan, suci dari najis.',
                        'score_weight' => 20,
                        'explanation' => 'Syarat: 1. Dipakai setelah bersuci sempurna. 2. Menutupi area fardhu wudhu. 3. Tahan air dan tembus pandang. 4. Suci.'
                    ]
                ]
            ],
            [
                'id' => 'QZ-002',
                'title' => 'Kuis Harian 2: Vektor Dimensi Dua dan Operasi Skalar',
                'code' => 'CBT-MTK-10B',
                'digital_class_id' => 'LMS-002',
                'subject_id' => 'MP-006',
                'class_id' => 'CLS-SMA-10B',
                'teacher_id' => 'GUR-002',
                'category' => 'Kuis Harian',
                'duration_minutes' => 45,
                'passing_score' => 75,
                'randomize_questions' => false,
                'show_result_immediately' => true,
                'start_time' => '2026-11-15 09:00:00',
                'end_time' => '2026-11-17 18:00:00',
                'status' => 'aktif',
                'instructions' => 'Selesaikan perhitungan secara mandiri dengan teliti. Gunakan kalkulator bila diperlukan.',
                'questions' => [
                    [
                        'number' => 1,
                        'type' => 'pilihan_ganda',
                        'question' => 'Jika vektor u = (3, 4), maka panjang (magnitudo) vektor u adalah...',
                        'options' => [
                            'A' => '7',
                            'B' => '5',
                            'C' => '25',
                            'D' => '12'
                        ],
                        'correct_answer' => 'B',
                        'score_weight' => 50,
                        'explanation' => '|u| = sqrt(3^2 + 4^2) = sqrt(9 + 16) = sqrt(25) = 5.'
                    ],
                    [
                        'number' => 2,
                        'type' => 'pilihan_ganda',
                        'question' => 'Hasil kali titik (dot product) antara vektor a = (2, -1) dan b = (3, 5) adalah...',
                        'options' => [
                            'A' => '1',
                            'B' => '11',
                            'C' => '-1',
                            'D' => '6'
                        ],
                        'correct_answer' => 'A',
                        'score_weight' => 50,
                        'explanation' => 'a . b = (2*3) + (-1*5) = 6 - 5 = 1.'
                    ]
                ]
            ],
            [
                'id' => 'QZ-003',
                'title' => 'Try Out CBT: Ujian Masuk Perguruan Tinggi Islam (Bahasa Arab)',
                'code' => 'CBT-ARB-TRY',
                'digital_class_id' => 'LMS-003',
                'subject_id' => 'MP-003',
                'class_id' => 'CLS-SMA-12A',
                'teacher_id' => 'GUR-003',
                'category' => 'Tryout',
                'duration_minutes' => 90,
                'passing_score' => 80,
                'randomize_questions' => true,
                'show_result_immediately' => false,
                'start_time' => '2026-11-25 08:00:00',
                'end_time' => '2026-11-26 15:00:00',
                'status' => 'draft',
                'instructions' => 'Soal latihan standar seleksi beasiswa Timur Tengah (Al-Azhar / Madinah).',
                'questions' => [
                    [
                        'number' => 1,
                        'type' => 'pilihan_ganda',
                        'question' => 'Apa kedudukan kata \'Muhammadun\' pada kalimat: "Inna Muhammadan Rosulullah"?',
                        'options' => [
                            'A' => 'Fa\'il marfu\'',
                            'B' => 'Isim Inna manshub',
                            'C' => 'Khabar Inna marfu\'',
                            'D' => 'Maf\'ul bih'
                        ],
                        'correct_answer' => 'B',
                        'score_weight' => 100,
                        'explanation' => 'Inna beramal tanshibul isma wa tarfa\'ul khabar.'
                    ]
                ]
            ]
        ];

        // 17. Data Hasil Pengerjaan Santri (Quiz Attempts / Rekap Nilai)
        $this->quizAttempts = [
            [
                'id' => 'ATT-001',
                'quiz_id' => 'QZ-001',
                'student_id' => 'SIS-0001',
                'student_name' => 'Ahmad Fauzan Al-Ghifari',
                'nis' => '20261001',
                'started_at' => '2026-11-10 08:15:20',
                'finished_at' => '2026-11-10 09:02:15',
                'duration_used' => '47 menit',
                'correct_count' => 4,
                'wrong_count' => 0,
                'essay_score' => 18,
                'total_score' => 98,
                'is_passed' => true,
                'status' => 'selesai'
            ],
            [
                'id' => 'ATT-002',
                'quiz_id' => 'QZ-001',
                'student_id' => 'SIS-0003',
                'student_name' => 'Muhammad Bilal Ramadhan',
                'nis' => '20261003',
                'started_at' => '2026-11-10 08:30:00',
                'finished_at' => '2026-11-10 09:22:40',
                'duration_used' => '52 menit',
                'correct_count' => 3,
                'wrong_count' => 1,
                'essay_score' => 16,
                'total_score' => 76,
                'is_passed' => true,
                'status' => 'selesai'
            ],
            [
                'id' => 'ATT-003',
                'quiz_id' => 'QZ-001',
                'student_id' => 'SIS-0005',
                'student_name' => 'Zaid bin Tsabit',
                'nis' => '20261005',
                'started_at' => '2026-11-10 10:05:00',
                'finished_at' => '2026-11-10 11:00:00',
                'duration_used' => '55 menit',
                'correct_count' => 2,
                'wrong_count' => 2,
                'essay_score' => 14,
                'total_score' => 54,
                'is_passed' => false,
                'status' => 'selesai'
            ]
        ];

        // 18. Program Tahfidz Al-Qur'an (Tahfidz Programs & Targets)
        $this->tahfidzPrograms = [
            [
                'id' => 'PRG-TFZ-01',
                'name' => 'Program Reguler Tahfidz (Mutqin 5 Juz)',
                'unit_id' => 'UNIT-SD',
                'level' => 'SD',
                'target_juz' => 5,
                'target_description' => 'Target Juz 30, 29, 28, 1, 2 dengan hafalan mutqin dan kaidah tajwid makharijul huruf standar.',
                'period' => '2026/2027 Ganjil',
                'daily_target' => '½ Halaman / Hari',
                'session_time' => 'Pagi (07.00 - 08.00 WIB)',
                'status' => 'aktif'
            ],
            [
                'id' => 'PRG-TFZ-02',
                'name' => 'Program Intensif Tahfidz Putri (Target 15 Juz)',
                'unit_id' => 'UNIT-SMP',
                'level' => 'SMP',
                'target_juz' => 15,
                'target_description' => 'Target Juz 1 s/d 10 dan Juz 26 s/d 30, pendalaman matan Jazariyah dan setoran ziyadah harian.',
                'period' => '2026/2027 Ganjil',
                'daily_target' => '1 Halaman / Hari',
                'session_time' => 'Ba\'da Subuh (05.00 - 06.30 WIB) & Ashar',
                'status' => 'aktif'
            ],
            [
                'id' => 'PRG-TFZ-03',
                'name' => 'Program Takhassus Al-Qur\'an 30 Juz (Boarding Putra)',
                'unit_id' => 'UNIT-SMA',
                'level' => 'SMA',
                'target_juz' => 30,
                'target_description' => 'Program unggulan takhassus penghafal 30 Juz Al-Qur\'an bersanad riwayat Hafsh \'an \'Ashim thariq Asy-Syathibiyyah.',
                'period' => '2026/2027 Ganjil',
                'daily_target' => '1.5 Halaman / Hari + 1 Juz Murojaah',
                'session_time' => 'Subuh, Ashar, dan Ba\'da Maghrib',
                'status' => 'aktif'
            ],
            [
                'id' => 'PRG-TFZ-04',
                'name' => 'Program Tahsin & Penguatan Juz \'Amma',
                'unit_id' => 'UNIT-SMP',
                'level' => 'SMP',
                'target_juz' => 3,
                'target_description' => 'Fokus perbaikan tajwid dasar, kelancaran bacaan tilawah tartil, dan tahfidz juz 30 & juz 29.',
                'period' => '2026/2027 Ganjil',
                'daily_target' => '¼ Halaman / Hari',
                'session_time' => 'Sore (16.00 - 17.15 WIB)',
                'status' => 'aktif'
            ]
        ];

        // 19. Kelompok Halaqah Al-Qur'an (Halaqahs)
        $this->halaqahs = [
            [
                'id' => 'HLQ-001',
                'name' => 'Halaqah Utsman bin Affan (Takhassus Putra)',
                'program_id' => 'PRG-TFZ-03',
                'teacher_id' => 'GUR-001', // Dr. H. Zulkifli, Lc., M.A.
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'unit_id' => 'UNIT-SMA',
                'gender_target' => 'L',
                'location' => 'Masjid Kampus Alia Lantai 2 (Shaf Depan)',
                'session_schedule' => 'Subuh (05.00) & Maghrib (18.15)',
                'target_juz' => 30,
                'student_ids' => ['SIS-0001', 'SIS-0003'], // Ahmad Fauzan, Bilal Ramadhan
                'status' => 'aktif',
                'description' => 'Halaqah percepatan 30 juz santri putra boarding SMA.'
            ],
            [
                'id' => 'HLQ-002',
                'name' => 'Halaqah Fathimah Az-Zahra (Intensif Putri)',
                'program_id' => 'PRG-TFZ-02',
                'teacher_id' => 'GUR-002', // Aisyah Salsabila, S.Pd.I.
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'unit_id' => 'UNIT-SMP',
                'gender_target' => 'P',
                'location' => 'Musholla Asrama Putri Gedung Maryam',
                'session_schedule' => 'Subuh (05.00) & Ashar (16.00)',
                'target_juz' => 15,
                'student_ids' => ['SIS-0002', 'SIS-0004'], // Siti Fatimah, Zaskia Nur
                'status' => 'aktif',
                'description' => 'Kelompok halaqah tahfidz putri santri SMP Islam Alia.'
            ],
            [
                'id' => 'HLQ-003',
                'name' => 'Halaqah Ali bin Abi Thalib (Tahsin & Juz 30)',
                'program_id' => 'PRG-TFZ-01',
                'teacher_id' => 'GUR-011', // Ust. Muhammad Ihsan, S.Pd.I.
                'supervisor_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'unit_id' => 'UNIT-SD',
                'gender_target' => 'L',
                'location' => 'Ruang Kelas 1 Ali bin Abi Thalib',
                'session_schedule' => 'Pagi (07.15 - 08.00 WIB)',
                'target_juz' => 5,
                'student_ids' => ['SIS-0005', 'SIS-0008'], // Faris Abdurrahman, Aisyah Humaira
                'status' => 'aktif',
                'description' => 'Bimbingan dasar makhraj dan kelancaran juz 30 anak usia SD.'
            ],
            [
                'id' => 'HLQ-004',
                'name' => 'Halaqah Zaid bin Tsabit (Reguler Putra)',
                'program_id' => 'PRG-TFZ-03',
                'teacher_id' => 'GUR-003', // Salman Al-Farisi, S.Sos.
                'supervisor_name' => 'Salman Al-Farisi, S.Sos.',
                'unit_id' => 'UNIT-SMA',
                'gender_target' => 'L',
                'location' => 'Masjid Kampus Alia Sayap Kanan',
                'session_schedule' => 'Ashar (16.00) & Isya (19.45)',
                'target_juz' => 10,
                'student_ids' => ['SIS-0007', 'SIS-0009'], // Rayhan Pratama, Ibrahim Khalilullah
                'status' => 'aktif',
                'description' => 'Peningkatan ziyadah hafalan baru dan murojaah mutqin.'
            ]
        ];

        // 20. Jurnal Buku Setoran Hafalan Baru (Ziyadah Records)
        $this->memorizationRecords = [
            [
                'id' => 'ZYD-001',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'halaqah_id' => 'HLQ-001',
                'teacher_id' => 'GUR-001',
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'date' => '2026-11-06',
                'session' => 'Subuh',
                'surah_name' => 'Al-Baqarah',
                'surah_number' => 2,
                'juz' => 1,
                'ayat_start' => 1,
                'ayat_end' => 25,
                'total_ayat' => 25,
                'grade' => 'Mumtaz', // Mumtaz (90-100), Jayyid Jiddan (80-89), Jayyid (70-79), Maqbul (60-69)
                'score' => 95,
                'tajwid_quality' => 'Sangat Baik',
                'makhraj_quality' => 'Fasih & Jelas',
                'notes' => 'Bacaan tartil sangat tenang, hukum mad wajib & jaiz munfashil teratur 4-5 harakat.',
                'status' => 'lulus'
            ],
            [
                'id' => 'ZYD-002',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'halaqah_id' => 'HLQ-001',
                'teacher_id' => 'GUR-001',
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'date' => '2026-11-07',
                'session' => 'Subuh',
                'surah_name' => 'Al-Baqarah',
                'surah_number' => 2,
                'juz' => 1,
                'ayat_start' => 26,
                'ayat_end' => 43,
                'total_ayat' => 18,
                'grade' => 'Mumtaz',
                'score' => 93,
                'tajwid_quality' => 'Sangat Baik',
                'makhraj_quality' => 'Fasih & Jelas',
                'notes' => 'Kelancaran hafalan sangat baik, perhatikan ketukan ghunnah nun bertasydid.',
                'status' => 'lulus'
            ],
            [
                'id' => 'ZYD-003',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'halaqah_id' => 'HLQ-001',
                'teacher_id' => 'GUR-001',
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'date' => '2026-11-06',
                'session' => 'Maghrib',
                'surah_name' => 'Ali \'Imran',
                'surah_number' => 3,
                'juz' => 3,
                'ayat_start' => 1,
                'ayat_end' => 18,
                'total_ayat' => 18,
                'grade' => 'Jayyid Jiddan',
                'score' => 86,
                'tajwid_quality' => 'Baik',
                'makhraj_quality' => 'Baik',
                'notes' => 'Ada 1 kali keraguan waqaf pada ayat 7, selebihnya lancar dan mutqin.',
                'status' => 'lulus'
            ],
            [
                'id' => 'ZYD-004',
                'student_id' => 'SIS-0002', // Siti Fatimah
                'halaqah_id' => 'HLQ-002',
                'teacher_id' => 'GUR-002',
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'date' => '2026-11-06',
                'session' => 'Subuh',
                'surah_name' => 'An-Nisa\'',
                'surah_number' => 4,
                'juz' => 4,
                'ayat_start' => 1,
                'ayat_end' => 14,
                'total_ayat' => 14,
                'grade' => 'Mumtaz',
                'score' => 96,
                'tajwid_quality' => 'Sangat Baik',
                'makhraj_quality' => 'Fasih & Jelas',
                'notes' => 'Masya Allah suara merdu, tajwid matan Jazariyah teraplikasi sempurna.',
                'status' => 'lulus'
            ],
            [
                'id' => 'ZYD-005',
                'student_id' => 'SIS-0004', // Zaskia Nur
                'halaqah_id' => 'HLQ-002',
                'teacher_id' => 'GUR-002',
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'date' => '2026-11-06',
                'session' => 'Ashar',
                'surah_name' => 'Maryam',
                'surah_number' => 19,
                'juz' => 16,
                'ayat_start' => 1,
                'ayat_end' => 22,
                'total_ayat' => 22,
                'grade' => 'Jayyid Jiddan',
                'score' => 84,
                'tajwid_quality' => 'Baik',
                'makhraj_quality' => 'Baik',
                'notes' => 'Lancar, perlu penguatan ketukan mad lazim harfi mukhaffaf pada pembuka surat.',
                'status' => 'lulus'
            ],
            [
                'id' => 'ZYD-006',
                'student_id' => 'SIS-0005', // Faris Abdurrahman (SD)
                'halaqah_id' => 'HLQ-003',
                'teacher_id' => 'GUR-011',
                'supervisor_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'date' => '2026-11-07',
                'session' => 'Pagi',
                'surah_name' => 'An-Naba\'',
                'surah_number' => 78,
                'juz' => 30,
                'ayat_start' => 1,
                'ayat_end' => 20,
                'total_ayat' => 20,
                'grade' => 'Mumtaz',
                'score' => 91,
                'tajwid_quality' => 'Sangat Baik',
                'makhraj_quality' => 'Jelas',
                'notes' => 'Santri cilik sangat antusias, makhraj huruf ta, tsa, jim sudah tertib.',
                'status' => 'lulus'
            ],
            [
                'id' => 'ZYD-007',
                'student_id' => 'SIS-0008', // Aisyah Humaira (SD)
                'halaqah_id' => 'HLQ-003',
                'teacher_id' => 'GUR-011',
                'supervisor_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'date' => '2026-11-07',
                'session' => 'Pagi',
                'surah_name' => 'An-Nazi\'at',
                'surah_number' => 79,
                'juz' => 30,
                'ayat_start' => 1,
                'ayat_end' => 26,
                'total_ayat' => 26,
                'grade' => 'Jayyid',
                'score' => 78,
                'tajwid_quality' => 'Cukup',
                'makhraj_quality' => 'Cukup',
                'notes' => 'Perlu pengulangan sedikit pada ayat 15-20 kisah Nabi Musa.',
                'status' => 'perlu_pengulangan'
            ]
        ];

        // 21. Jurnal Pengulangan Hafalan & Ujian Kelancaran (Murojaah Records)
        $this->murojaahRecords = [
            [
                'id' => 'MRJ-001',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'halaqah_id' => 'HLQ-001',
                'teacher_id' => 'GUR-001',
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'date' => '2026-11-06',
                'session' => 'Ashar',
                'type' => 'Tasmi\' Bil Ghoib (1 Juz Penuh)', // Harian (Rubu'), Mingguan (1/2 Juz), Tasmi' Bil Ghoib (1 Juz Penuh), Ujian Kenaikan Juz
                'juz' => 30,
                'surah_range' => 'An-Naba\' s/d An-Nas',
                'repetition_count' => 5, // Frekuensi pengulangan mandiri santri
                'fluency_status' => 'Mutqin', // Mutqin, Cukup Lancar, Perlu Pengulangan
                'grade' => 'Mumtaz',
                'score' => 96,
                'evaluation_notes' => 'Masya Allah tasmi\' 1 juz sekali duduk sangat lancar, waqaf dan ibtida\' sempurna tanpa kesalahan.',
                'status' => 'lulus'
            ],
            [
                'id' => 'MRJ-002',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'halaqah_id' => 'HLQ-001',
                'teacher_id' => 'GUR-001',
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'date' => '2026-11-07',
                'session' => 'Ashar',
                'type' => 'Mingguan (1/2 Juz)',
                'juz' => 29,
                'surah_range' => 'Al-Mulk s/d Al-Jin',
                'repetition_count' => 4,
                'fluency_status' => 'Mutqin',
                'grade' => 'Mumtaz',
                'score' => 94,
                'evaluation_notes' => 'Sangat lancar, tempo bacaan hadr rapi.',
                'status' => 'lulus'
            ],
            [
                'id' => 'MRJ-003',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'halaqah_id' => 'HLQ-001',
                'teacher_id' => 'GUR-001',
                'supervisor_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'date' => '2026-11-06',
                'session' => 'Ashar',
                'type' => 'Harian (Rubu\')',
                'juz' => 1,
                'surah_range' => 'Al-Baqarah ayat 1 s/d 74',
                'repetition_count' => 3,
                'fluency_status' => 'Cukup Lancar',
                'grade' => 'Jayyid Jiddan',
                'score' => 85,
                'evaluation_notes' => 'Ada 2 kali teguran pada ayat serupa (mutasyabihat) kisah sapi betina.',
                'status' => 'lulus'
            ],
            [
                'id' => 'MRJ-004',
                'student_id' => 'SIS-0002', // Siti Fatimah
                'halaqah_id' => 'HLQ-002',
                'teacher_id' => 'GUR-002',
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'date' => '2026-11-06',
                'session' => 'Ashar',
                'type' => 'Tasmi\' Bil Ghoib (1 Juz Penuh)',
                'juz' => 29,
                'surah_range' => 'Al-Mulk s/d An-Nas',
                'repetition_count' => 6,
                'fluency_status' => 'Mutqin',
                'grade' => 'Mumtaz',
                'score' => 98,
                'evaluation_notes' => 'Mumtaz! Tartil dan intonasi sangat terjaga dari awal hingga akhir surat.',
                'status' => 'lulus'
            ],
            [
                'id' => 'MRJ-005',
                'student_id' => 'SIS-0004', // Zaskia Nur
                'halaqah_id' => 'HLQ-002',
                'teacher_id' => 'GUR-002',
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'date' => '2026-11-07',
                'session' => 'Pagi',
                'type' => 'Mingguan (1/2 Juz)',
                'juz' => 30,
                'surah_range' => 'An-Naba\' s/d Al-Buruj',
                'repetition_count' => 3,
                'fluency_status' => 'Cukup Lancar',
                'grade' => 'Jayyid Jiddan',
                'score' => 83,
                'evaluation_notes' => 'Cukup lancar, perlu penguatan akhir-akhir ayat surat An-Nazi\'at.',
                'status' => 'lulus'
            ],
            [
                'id' => 'MRJ-006',
                'student_id' => 'SIS-0005', // Faris Abdurrahman (SD)
                'halaqah_id' => 'HLQ-003',
                'teacher_id' => 'GUR-011',
                'supervisor_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'date' => '2026-11-07',
                'session' => 'Pagi',
                'type' => 'Harian (Rubu\')',
                'juz' => 30,
                'surah_range' => 'Ad-Dhuha s/d An-Nas',
                'repetition_count' => 4,
                'fluency_status' => 'Mutqin',
                'grade' => 'Mumtaz',
                'score' => 92,
                'evaluation_notes' => 'Surat-surat pendek sangat lancar dan fasih.',
                'status' => 'lulus'
            ],
            [
                'id' => 'MRJ-007',
                'student_id' => 'SIS-0008', // Aisyah Humaira (SD)
                'halaqah_id' => 'HLQ-003',
                'teacher_id' => 'GUR-011',
                'supervisor_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'date' => '2026-11-07',
                'session' => 'Pagi',
                'type' => 'Harian (Rubu\')',
                'juz' => 30,
                'surah_range' => 'Al-A\'la s/d Al-Balad',
                'repetition_count' => 2,
                'fluency_status' => 'Perlu Pengulangan',
                'grade' => 'Maqbul',
                'score' => 68,
                'evaluation_notes' => 'Masih banyak terlupa urutan ayat pada surat Al-Fajr. Disarankan murojaah mandiri 3x lagi.',
                'status' => 'perlu_pengulangan'
            ]
        ];

        // 22. Kurikulum Mata Pelajaran Diniyah & Kitab Kuning
        $this->diniyahSubjects = [
            [
                'id' => 'DIN-001',
                'code' => 'KTB-FIQ-01',
                'name' => 'Fikih Ibadah (Kitab Safinatun Najah)',
                'category' => 'Fikih & Ibadah',
                'level' => 'SMP & SD Atas',
                'kitab_author' => 'Syaikh Salim bin Sumair Al-Hadhrami',
                'description' => 'Membahas rukun Islam, thaharah, fardhu wudhu, syarat shalat, dan pembatalnya.',
                'status' => 'aktif'
            ],
            [
                'id' => 'DIN-002',
                'code' => 'KTB-FIQ-02',
                'name' => 'Fikih Muamalah (Kitab Fathul Qorib Al-Mujib)',
                'category' => 'Fikih & Ibadah',
                'level' => 'SMA Boarding',
                'kitab_author' => 'Syaikh Ibnu Qasim Al-Ghazi',
                'description' => 'Kajian lanjutan madzhab Syafi\'i meliputi thaharah, shalat, zakat, puasa, haji, dan transaksi muamalah.',
                'status' => 'aktif'
            ],
            [
                'id' => 'DIN-003',
                'code' => 'KTB-ARB-01',
                'name' => 'Nahwu & Tata Bahasa Arab (Kitab Al-Jurumiyyah)',
                'category' => 'Bahasa & Kaidah',
                'level' => 'SMP & SMA',
                'kitab_author' => 'Ibnu Ajurrum Ash-Shanhaji',
                'description' => 'Kaidah dasar i\'rab kalimat bahasa Arab, marfu\'atul asma, manshubat, dan majrurat.',
                'status' => 'aktif'
            ],
            [
                'id' => 'DIN-004',
                'code' => 'KTB-HDT-01',
                'name' => 'Hadits Nabawi (Kitab Al-Arba\'in An-Nawawiyyah)',
                'category' => 'Hadits & Sunnah',
                'level' => 'Semua Jenjang',
                'kitab_author' => 'Imam Abu Zakariya Yahya bin Syaraf An-Nawawi',
                'description' => '42 hadits pokok ajaran Islam, pondasi niat, rukun iman/islam/ihsan, dan akhlak.',
                'status' => 'aktif'
            ],
            [
                'id' => 'DIN-005',
                'code' => 'KTB-AKH-01',
                'name' => 'Adab & Akhlak Santri (Kitab Ta\'limul Muta\'allim)',
                'category' => 'Aqidah & Akhlak',
                'level' => 'Semua Jenjang',
                'kitab_author' => 'Syaikh Az-Zarnuji',
                'description' => 'Pedoman adab menuntut ilmu, menghormati guru, memilih teman belajar, dan kesungguhan.',
                'status' => 'aktif'
            ],
            [
                'id' => 'DIN-006',
                'code' => 'KTB-TJW-01',
                'name' => 'Kaidah Tajwid (Matan Al-Jazariyyah)',
                'category' => 'Al-Qur\'an & Tajwid',
                'level' => 'SMP & SMA Takhassus',
                'kitab_author' => 'Al-Imam Ibnu Al-Jazari Asy-Syafi\'i',
                'description' => 'Nadzom tajwid makharijul huruf, shifatul huruf, ahkamul mad wal qashr, waqaf dan ibtida\'.',
                'status' => 'aktif'
            ]
        ];

        // 23. Jadwal Pengajian & Bimbingan Diniyah (Diniyah Schedules)
        $this->diniyahSchedules = [
            [
                'id' => 'SCH-DIN-001',
                'subject_id' => 'DIN-002',
                'teacher_id' => 'GUR-001', // Dr. H. Zulkifli
                'class_target' => 'Kelas X MIPA 1 & 2 (Putra Boarding)',
                'room' => 'Masjid Kampus Lantai 1 (Sayap Utama)',
                'day' => 'Senin',
                'time_start' => '18:30',
                'time_end' => '19:45',
                'session_label' => 'Ba\'da Maghrib',
                'status' => 'aktif'
            ],
            [
                'id' => 'SCH-DIN-002',
                'subject_id' => 'DIN-003',
                'teacher_id' => 'GUR-003', // Salman Al-Farisi
                'class_target' => 'Kelas X & XI (Putra Boarding)',
                'room' => 'Ruang Teater / Aula Perpustakaan',
                'day' => 'Selasa',
                'time_start' => '05:30',
                'time_end' => '06:30',
                'session_label' => 'Ba\'da Subuh',
                'status' => 'aktif'
            ],
            [
                'id' => 'SCH-DIN-003',
                'subject_id' => 'DIN-001',
                'teacher_id' => 'GUR-002', // Aisyah Salsabila
                'class_target' => 'Kelas VII & VIII (Putri Asrama)',
                'room' => 'Musholla Asrama Putri Lt. 2',
                'day' => 'Rabu',
                'time_start' => '16:00',
                'time_end' => '17:15',
                'session_label' => 'Ba\'da Ashar',
                'status' => 'aktif'
            ],
            [
                'id' => 'SCH-DIN-004',
                'subject_id' => 'DIN-004',
                'teacher_id' => 'GUR-001', // Dr. H. Zulkifli
                'class_target' => 'Santri Seluruh Asrama (Kajian Akbar)',
                'room' => 'Masjid Kampus Alia Lantai 2',
                'day' => 'Kamis',
                'time_start' => '18:30',
                'time_end' => '19:45',
                'session_label' => 'Ba\'da Maghrib',
                'status' => 'aktif'
            ],
            [
                'id' => 'SCH-DIN-005',
                'subject_id' => 'DIN-005',
                'teacher_id' => 'GUR-011', // Ust. Muhammad Ihsan
                'class_target' => 'Santri SD Kelas 1 & 2',
                'room' => 'Ruang Kelas 1 Ali bin Abi Thalib',
                'day' => 'Jumat',
                'time_start' => '07:15',
                'time_end' => '08:00',
                'session_label' => 'Pagi Dhuha',
                'status' => 'aktif'
            ],
            [
                'id' => 'SCH-DIN-006',
                'subject_id' => 'DIN-006',
                'teacher_id' => 'GUR-002', // Aisyah Salsabila
                'class_target' => 'Halaqah Takhassus Al-Qur\'an',
                'room' => 'Masjid Kampus Lantai 2',
                'day' => 'Sabtu',
                'time_start' => '05:30',
                'time_end' => '06:45',
                'session_label' => 'Ba\'da Subuh',
                'status' => 'aktif'
            ]
        ];

        // 24. Aturan Tata Tertib & Buku Poin Pelanggaran (Violation Rules)
        $this->violationRules = [
            [
                'id' => 'RUL-001',
                'category' => 'Kedisiplinan & Waktu',
                'name' => 'Terlambat mengikuti shalat berjamaah di masjid',
                'severity' => 'Ringan',
                'points' => 5,
                'consequence' => 'Teguran lisan & berdiri menyimak wirid ba\'da shalat',
                'status' => 'aktif'
            ],
            [
                'id' => 'RUL-002',
                'category' => 'Kedisiplinan & Waktu',
                'name' => 'Terlambat masuk kelas / halaqah tahfidz (> 15 menit)',
                'severity' => 'Ringan',
                'points' => 5,
                'consequence' => 'Mencatat kosa kata bahasa Arab 10 kata',
                'status' => 'aktif'
            ],
            [
                'id' => 'RUL-003',
                'category' => 'Kerapihan & Seragam',
                'name' => 'Tidak memakai seragam resmi / atribut tidak lengkap',
                'severity' => 'Ringan',
                'points' => 5,
                'consequence' => 'Peringatan & pembenahan atribut sebelum masuk kelas',
                'status' => 'aktif'
            ],
            [
                'id' => 'RUL-004',
                'category' => 'Ketertiban Asrama',
                'name' => 'Meninggalkan asrama tanpa izin musyrif / surat izin resmi',
                'severity' => 'Sedang',
                'points' => 20,
                'consequence' => 'Peringatan tertulis (SP 1) & piket membersihkan musholla',
                'status' => 'aktif'
            ],
            [
                'id' => 'RUL-005',
                'category' => 'Barang Terlarang & Gadget',
                'name' => 'Membawa gawai / smartphone di luar jadwal yang diizinkan',
                'severity' => 'Sedang',
                'points' => 25,
                'consequence' => 'Penyitaan perangkat selama 1 bulan & pemanggilan orang tua',
                'status' => 'aktif'
            ],
            [
                'id' => 'RUL-006',
                'category' => 'Akhlak & Moral',
                'name' => 'Berkelahi fisik atau melakukan perundungan (bullying)',
                'severity' => 'Berat',
                'points' => 50,
                'consequence' => 'Surat Peringatan 3 (SP 3) & skorsing 1 pekan',
                'status' => 'aktif'
            ],
            [
                'id' => 'RUL-007',
                'category' => 'Akhlak & Moral',
                'name' => 'Merokok atau membawa rokok/vape di lingkungan sekolah/asrama',
                'severity' => 'Berat',
                'points' => 40,
                'consequence' => 'Surat Peringatan 2 (SP 2) & pembinaan intensif BK',
                'status' => 'aktif'
            ]
        ];

        // 25. Catatan Kasus Pelanggaran Santri (Student Violations)
        $this->studentViolations = [
            [
                'id' => 'VIO-001',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'rule_id' => 'RUL-001',
                'violation_name' => 'Terlambat mengikuti shalat berjamaah di masjid',
                'category' => 'Kedisiplinan & Waktu',
                'severity' => 'Ringan',
                'points' => 5,
                'date' => '2026-11-04 05:10:00',
                'reporter_name' => 'Ust. Salman Al-Farisi (Musyrif Asrama)',
                'action_taken' => 'Teguran lisan & menyimak wirid doa bersama',
                'notes' => 'Santri tertidur setelah qiyamul lail mandiri.',
                'status' => 'selesai' // selesai, pembinaan, panggilan_ortu
            ],
            [
                'id' => 'VIO-002',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'rule_id' => 'RUL-002',
                'violation_name' => 'Terlambat masuk kelas / halaqah tahfidz (> 15 menit)',
                'category' => 'Kedisiplinan & Waktu',
                'severity' => 'Ringan',
                'points' => 5,
                'date' => '2026-11-06 07:20:00',
                'reporter_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'action_taken' => 'Menulis hafalan mufrodat 10 kata bahasa Arab',
                'notes' => 'Terlambat karena menunggu antrian sarapan.',
                'status' => 'selesai'
            ],
            [
                'id' => 'VIO-003',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'rule_id' => 'RUL-005',
                'violation_name' => 'Membawa gawai / smartphone di luar jadwal yang diizinkan',
                'category' => 'Barang Terlarang & Gadget',
                'severity' => 'Sedang',
                'points' => 25,
                'date' => '2026-11-05 21:30:00',
                'reporter_name' => 'Budi Santoso, S.Kom. (Pamong Asrama)',
                'action_taken' => 'Gawai disita di kantor kesantrian & surat peringatan',
                'notes' => 'Ditemukan memainkan game online di asrama ba\'da Isya.',
                'status' => 'pembinaan'
            ],
            [
                'id' => 'VIO-004',
                'student_id' => 'SIS-0009', // Ibrahim Khalilullah
                'rule_id' => 'RUL-003',
                'violation_name' => 'Tidak memakai seragam resmi / atribut tidak lengkap',
                'category' => 'Kerapihan & Seragam',
                'severity' => 'Ringan',
                'points' => 5,
                'date' => '2026-11-06 07:05:00',
                'reporter_name' => 'Irfan Maulana, S.Pd. (Piket Pagi)',
                'action_taken' => 'Peringatan & pemakaian peci hitam resmi Alia',
                'notes' => 'Lupa membawa peci hitam saat upacara apel pagi.',
                'status' => 'selesai'
            ]
        ];

        // 26. Data Prestasi & Penghargaan Santri (Student Achievements) - T07.2
        $this->studentAchievements = [
            [
                'id' => 'ACH-001',
                'student_id' => 'SIS-0001', // Ahmad Fauzan Al-Ghifari
                'title' => 'Juara 1 Musabaqah Hifdzil Qur\'an (MHQ) 30 Juz Tingkat Nasional',
                'category' => 'Tahfidz & Keagamaan',
                'competition_name' => 'Festival Al-Qur\'an Nusantara Kemenag RI',
                'level' => 'Nasional', // Sekolah, Kota/Kab, Provinsi, Nasional, Internasional
                'rank' => 'Juara 1',
                'year' => 2026,
                'date' => '2026-09-15',
                'organizer' => 'Kementerian Agama Republik Indonesia',
                'coach_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'reward' => 'Piala Bergilir, Piagam Emas, & Beasiswa Pendidikan Rp 15.000.000',
                'points' => 100,
                'certificate_number' => 'SK-MHQ-NAT/IX/2026/042',
                'status' => 'terverifikasi',
                'notes' => 'Tampil memukau tanpa salah pada babak final sambung ayat dan tartil.'
            ],
            [
                'id' => 'ACH-002',
                'student_id' => 'SIS-0002', // Siti Fatimah Az-Zahra
                'title' => 'Medali Perak Olimpiade Sains Nasional (OSN) Bidang Biologi',
                'category' => 'Akademik & Sains',
                'competition_name' => 'Olimpiade Sains Nasional Jenjang SMP/MTs',
                'level' => 'Nasional',
                'rank' => 'Juara 2 / Perak',
                'year' => 2026,
                'date' => '2026-08-20',
                'organizer' => 'Pusat Prestasi Nasional (Puspresnas) Kemendikbudristek',
                'coach_name' => 'Ustadzah Nurul Hidayati, M.Pd.',
                'reward' => 'Medali Perak, Piagam Nasional, & Tabungan Pendidikan Rp 7.500.000',
                'points' => 75,
                'certificate_number' => 'PUSPRESNAS/OSN-SMP/2026/189',
                'status' => 'terverifikasi',
                'notes' => 'Praktikum anatomi sel dan analisis genetik tumbuhan nilainya tertinggi.'
            ],
            [
                'id' => 'ACH-003',
                'student_id' => 'SIS-0001', // Ahmad Fauzan Al-Ghifari
                'title' => 'Juara 1 Lomba Pidato Bahasa Arab (Khitobah) Tingkat Provinsi',
                'category' => 'Bahasa & Sastra',
                'competition_name' => 'Pekan Olahraga dan Seni Antar Pondok Pesantren Daerah (POSPEDA)',
                'level' => 'Provinsi',
                'rank' => 'Juara 1',
                'year' => 2026,
                'date' => '2026-07-12',
                'organizer' => 'Kanwil Kemenag Jawa Barat',
                'coach_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'reward' => 'Piala Gubernur & Piagam Penghargaan',
                'points' => 50,
                'certificate_number' => 'POSPEDA-JBR/VIII/2026/088',
                'status' => 'terverifikasi',
                'notes' => 'Tema pidato: Peran Generasi Qur\'ani di Era Kecerdasan Buatan.'
            ],
            [
                'id' => 'ACH-004',
                'student_id' => 'SIS-0003', // Muhammad Bilal Ramadhan
                'title' => 'Juara 1 Turnamen Panahan Tradisional (Horsebow) Putra',
                'category' => 'Olahraga & Seni Bela Diri',
                'competition_name' => 'Kejurda Panahan Sunnah Pelajar Se-Jabodetabek',
                'level' => 'Provinsi',
                'rank' => 'Juara 1',
                'year' => 2026,
                'date' => '2026-10-04',
                'organizer' => 'Persatuan Panahan Berkuda Indonesia',
                'coach_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'reward' => 'Busur Busur Horsebow Eksklusif & Trophy',
                'points' => 45,
                'certificate_number' => 'ARCH-JKT/X/2026/012',
                'status' => 'terverifikasi',
                'notes' => 'Skor total tembakan jarak 30 meter: 285 poin dari 300.'
            ],
            [
                'id' => 'ACH-005',
                'student_id' => 'SIS-0004', // Zaskia Nur Ramadhani
                'title' => 'Juara Harapan 1 Lomba Kaligrafi Kontemporer Putri',
                'category' => 'Seni & Kaligrafi',
                'competition_name' => 'Festival Seni Islam dan Kaligrafi Nasional',
                'level' => 'Nasional',
                'rank' => 'Juara Harapan 1',
                'year' => 2026,
                'date' => '2026-09-28',
                'organizer' => 'Lembaga Kaligrafi Al-Qur\'an (LEMKA)',
                'coach_name' => 'Aisyah Salsabila, S.Pd.I.',
                'reward' => 'Piagam Penghargaan & Set Kuas Kaligrafi Profesional',
                'points' => 35,
                'certificate_number' => 'LEMKA/NAT/IX/2026/304',
                'status' => 'terverifikasi',
                'notes' => 'Karya khat Tsuluts kontemporer surat Ar-Rahman.'
            ],
            [
                'id' => 'ACH-006',
                'student_id' => 'SIS-0005', // Faris Abdurrahman
                'title' => 'Juara 2 Cerdas Cermat PAI Tingkat SD/MI Kota',
                'category' => 'Akademik & Keagamaan',
                'competition_name' => 'Gebyar PAI SD Tingkat Kota',
                'level' => 'Kota/Kab',
                'rank' => 'Juara 2',
                'year' => 2026,
                'date' => '2026-10-18',
                'organizer' => 'Kelompok Kerja Guru (KKG) PAI',
                'coach_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'reward' => 'Piala & Piagam Penghargaan',
                'points' => 25,
                'certificate_number' => 'KKG-PAI/X/2026/054',
                'status' => 'terverifikasi',
                'notes' => 'Menjawab cepat pada babak rebutan kisah 25 Nabi dan Rasul.'
            ]
        ];

        // 27. Data Layanan Bimbingan & Konseling / BK (Counseling Sessions) - T07.3
        $this->counselingSessions = [
            [
                'id' => 'BK-001',
                'student_id' => 'SIS-0003', // Muhammad Bilal Ramadhan
                'counselor_name' => 'Drs. H. Mulyadi, M.Pd., Kons.',
                'date' => '2026-11-05',
                'time' => '10:00 - 11:00',
                'category' => 'Disiplin & Motivasi', // Belajar, Pribadi, Sosial, Karier, Disiplin & Motivasi
                'service_type' => 'Konseling Individu', // Konseling Individu, Konseling Kelompok, Mediasi Teman Sebaya, Konferensi Kasus, Kunjungan Rumah (Home Visit)
                'referral_from' => 'Musyrif Asrama (Rujukan Pelanggaran)',
                'problem_summary' => 'Sering terlambat shalat subuh dan kelelahan di kelas pagi karena begadang mandiri murajaah tanpa manajemen waktu istirahat yang seimbang.',
                'action_plan' => 'Menyusun jadwal harian proporsional bersama pamong asrama, membatasi jam murojaah malam maksimal pkl 22.00 WIB, dan evaluasi berkala pekanan.',
                'status' => 'dalam_proses', // dijadwalkan, dalam_proses, selesai, dirujuk
                'follow_up_date' => '2026-11-12',
                'notes' => 'Santri sangat kooperatif dan menyadari kekeliruan ritme istirahatnya. Tidak ada indikasi pembangkangan.'
            ],
            [
                'id' => 'BK-002',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'counselor_name' => 'Drs. H. Mulyadi, M.Pd., Kons.',
                'date' => '2026-11-06',
                'time' => '13:30 - 14:30',
                'category' => 'Pribadi & Adiksi Gadget',
                'service_type' => 'Konseling Individu',
                'referral_from' => 'Pamong Asrama (Kasus Penyitaan HP)',
                'problem_summary' => 'Kecemasan dan kejenuhan adaptasi lingkungan asrama yang dikompensasi dengan bermain game online diam-diam pada malam hari.',
                'action_plan' => 'Bimbingan mindfulness Islam (muhasabah), pengalihan minat ke ekskul Robotik dan Futsal sore hari, serta konseling lanjutan bersama wali santri.',
                'status' => 'dalam_proses',
                'follow_up_date' => '2026-11-13',
                'notes' => 'Perlu pendampingan psikologis bertahap agar tidak timbul penolakan terhadap aturan pondok.'
            ],
            [
                'id' => 'BK-003',
                'student_id' => 'SIS-0001', // Ahmad Fauzan Al-Ghifari
                'counselor_name' => 'Ustadzah Siti Rahmah, S.Psi., M.A.',
                'date' => '2026-11-03',
                'time' => '09:00 - 10:00',
                'category' => 'Karier & Studi Lanjut',
                'service_type' => 'Bimbingan Karier & Minat',
                'referral_from' => 'Inisiatif Mandiri Santri',
                'problem_summary' => 'Konsultasi persiapan berkas beasiswa studi Islam ke Universitas Islam Madinah (UIM) dan Al-Azhar Kairo, serta penjurusan muamalah kontemporer.',
                'action_plan' => 'Penyusunan portofolio sertifikat tahfidz 30 juz, rekomendasi mudir pondok, dan jadwal bimbingan tes lisan bahasa Arab bersama tim penguji khusus.',
                'status' => 'selesai',
                'follow_up_date' => '2026-12-01',
                'notes' => 'Potensi akademik dan tahfidz sangat matang. Direkomendasikan penuh oleh dewan asatidz.'
            ],
            [
                'id' => 'BK-004',
                'student_id' => 'SIS-0004', // Zaskia Nur Ramadhani
                'counselor_name' => 'Ustadzah Siti Rahmah, S.Psi., M.A.',
                'date' => '2026-11-04',
                'time' => '11:00 - 12:00',
                'category' => 'Belajar & Akademik',
                'service_type' => 'Konseling Individu',
                'referral_from' => 'Wali Kelas 9B SMP',
                'problem_summary' => 'Kesulitan konsentrasi pada mata pelajaran Matematika dan IPA menjelang ujian formatif karena cemas tidak mencapai KKM target.',
                'action_plan' => 'Terapi reduksi kecemasan belajar (relaxation technique), pembentukan kelompok tutor sebaya (peer tutoring) bersama santri berprestasi sains.',
                'status' => 'selesai',
                'follow_up_date' => '2026-11-18',
                'notes' => 'Tingkat kecemasan menurun signifikan setelah mendapatkan mitra belajar sebaya.'
            ],
            [
                'id' => 'BK-005',
                'student_id' => 'SIS-0009', // Ibrahim Khalilullah
                'counselor_name' => 'Drs. H. Mulyadi, M.Pd., Kons.',
                'date' => '2026-11-08',
                'time' => '15:30 - 16:30',
                'category' => 'Sosial & Adaptasi Asrama',
                'service_type' => 'Konseling Individu',
                'referral_from' => 'Musyrif Kamar Umar bin Khattab',
                'problem_summary' => 'Sering menyendiri di kamar asrama dan merasa homesick (rindu rumah), jarang berinteraksi saat kegiatan bersama santri sekamar.',
                'action_plan' => 'Pendampingan kakak asuh (santri senior kamar), pelibatan aktif dalam panitia peringatan hari besar Islam (PHBI), dan telepon keluarga terjadwal.',
                'status' => 'dalam_proses',
                'follow_up_date' => '2026-11-15',
                'notes' => 'Respons positif saat diajak berbicara santai di gazebo perpustakaan.'
            ]
        ];

        // 28. Data Ekstrakurikuler & Klub Bakat Santri (Extracurriculars) - T07.4
        $this->extracurriculars = [
            [
                'id' => 'EKS-001',
                'name' => 'Panahan Tradisional (Horsebow Archery)',
                'category' => 'Olahraga Sunnah & Bela Diri',
                'coach_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'target_gender' => 'Semua', // Ikhwan, Akhwat, Semua
                'schedule_day' => 'Sabtu',
                'schedule_time' => '15:30 - 17:30',
                'location' => 'Lapangan Panahan Sayap Barat Kampus Alia',
                'quota' => 30,
                'student_ids' => ['SIS-0001', 'SIS-0003', 'SIS-0007'], // Ahmad Fauzan, Bilal Ramadhan, Rayhan Pratama
                'status' => 'aktif',
                'description' => 'Olahraga sunnah memanah tradisional teknik thumb draw, melatih fokus, kekuatan fisik, dan kesabaran santri.',
                'achievements_count' => 3
            ],
            [
                'id' => 'EKS-002',
                'name' => 'Kaligrafi Kontemporer & Khat Al-Qur\'an',
                'category' => 'Seni & Kebudayaan Islam',
                'coach_name' => 'Aisyah Salsabila, S.Pd.I.',
                'target_gender' => 'Semua',
                'schedule_day' => 'Ahad',
                'schedule_time' => '08:30 - 10:30',
                'location' => 'Studio Seni & Khat Gedung Khadijah',
                'quota' => 25,
                'student_ids' => ['SIS-0002', 'SIS-0004'], // Siti Fatimah, Zaskia Nur
                'status' => 'aktif',
                'description' => 'Pendalaman kaidah khat Naskhi, Riq\'ah, Tsuluts, dan seni kaligrafi lukis modern berstandar lomba nasional.',
                'achievements_count' => 2
            ],
            [
                'id' => 'EKS-003',
                'name' => 'Khitobah & Debat Bahasa Arab (Fashohah Club)',
                'category' => 'Bahasa & Keilmuan',
                'coach_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'target_gender' => 'Semua',
                'schedule_day' => 'Jumat',
                'schedule_time' => '13:30 - 15:00',
                'location' => 'Auditorium Syura Kampus Alia Lantai 2',
                'quota' => 35,
                'student_ids' => ['SIS-0001', 'SIS-0009'], // Ahmad Fauzan, Ibrahim Khalilullah
                'status' => 'aktif',
                'description' => 'Klub orasi, public speaking bahasa Arab & Inggris, retorika dakwah, dan simulasi debat ilmiah kepesantrenan.',
                'achievements_count' => 4
            ],
            [
                'id' => 'EKS-004',
                'name' => 'Robotika & Coding IoT Pesantren',
                'category' => 'Sains & Teknologi',
                'coach_name' => 'Budi Santoso, S.Kom.',
                'target_gender' => 'Semua',
                'schedule_day' => 'Sabtu',
                'schedule_time' => '09:00 - 11:30',
                'location' => 'Laboratorium Komputer & STEM Gedung Al-Biruni',
                'quota' => 20,
                'student_ids' => ['SIS-0007', 'SIS-0005'], // Rayhan Pratama, Faris Abdurrahman
                'status' => 'aktif',
                'description' => 'Eksplorasi mikrokontroler Arduino/ESP32, pemrograman logika dasar, dan rekayasa otomatisasi teknologi tepat guna.',
                'achievements_count' => 1
            ],
            [
                'id' => 'EKS-005',
                'name' => 'Pencak Silat Tapak Suci Putera Muhammadiyah',
                'category' => 'Olahraga Sunnah & Bela Diri',
                'coach_name' => 'Irfan Maulana, S.Pd.',
                'target_gender' => 'Semua',
                'schedule_day' => 'Selasa & Kamis',
                'schedule_time' => '16:00 - 17:30',
                'location' => 'Gedung Olahraga (GOR) Indoor Alia',
                'quota' => 40,
                'student_ids' => ['SIS-0003', 'SIS-0009'], // Bilal Ramadhan, Ibrahim Khalilullah
                'status' => 'aktif',
                'description' => 'Seni bela diri ragawi dan rohani tapak suci berakhlakul karimah untuk pertahanan diri dan kejuaraan silat.',
                'achievements_count' => 2
            ],
            [
                'id' => 'EKS-006',
                'name' => 'Jurnalistik Santri & Media Siber (Alia Media)',
                'category' => 'Media & Kepemimpinan',
                'coach_name' => 'Dewi Anggraini, S.Pd.',
                'target_gender' => 'Semua',
                'schedule_day' => 'Ahad',
                'schedule_time' => '13:00 - 15:00',
                'location' => 'Ruang Redaksi Majalah Santri & Podcast',
                'quota' => 20,
                'student_ids' => ['SIS-0002', 'SIS-0004'], // Siti Fatimah, Zaskia Nur
                'status' => 'aktif',
                'description' => 'Pelatihan kepenulisan esai, fotografi jurnalistik pesantren, produksi buletin dakwah, dan podcast kesiswaan.',
                'achievements_count' => 1
            ]
        ];

        // 29. Data Inventaris Obat-obatan UKS (UKS Medicines) - T07.5
        $this->uksMedicines = [
            [
                'id' => 'MED-001',
                'name' => 'Paracetamol 500mg Tablet',
                'category' => 'Analgesik & Antipiretik',
                'stock' => 120,
                'unit' => 'Tablet',
                'expiry_date' => '2027-08-30',
                'indication' => 'Pereda demam, sakit kepala, dan nyeri ringan santri.',
                'status' => 'tersedia'
            ],
            [
                'id' => 'MED-002',
                'name' => 'Antasida Doen Tablet Kunyah',
                'category' => 'Antasida & Saluran Cerna',
                'stock' => 85,
                'unit' => 'Tablet',
                'expiry_date' => '2027-05-15',
                'indication' => 'Mengurangi gejala kelebihan asam lambung, gastritis, nyeri ulu hati.',
                'status' => 'tersedia'
            ],
            [
                'id' => 'MED-003',
                'name' => 'Oralit 200ml Sachet',
                'category' => 'Elektrolit & Rehidrasi',
                'stock' => 60,
                'unit' => 'Sachet',
                'expiry_date' => '2028-01-20',
                'indication' => 'Mencegah dan mengatasi dehidrasi akibat diare atau muntah.',
                'status' => 'tersedia'
            ],
            [
                'id' => 'MED-004',
                'name' => 'Minyak Kayu Putih Cajuput 60ml',
                'category' => 'Obat Luar & Aromaterapi',
                'stock' => 15,
                'unit' => 'Botol',
                'expiry_date' => '2028-10-10',
                'indication' => 'Meredakan perut kembung, masuk angin, dan gatal gigitan serangga.',
                'status' => 'tersedia'
            ],
            [
                'id' => 'MED-005',
                'name' => 'Betadine Antiseptic Solution 60ml',
                'category' => 'Antiseptik & Perawatan Luka',
                'stock' => 10,
                'unit' => 'Botol',
                'expiry_date' => '2027-12-01',
                'indication' => 'Pembersih dan pencegah infeksi pada luka lecet, gores, atau cedera ringan.',
                'status' => 'tersedia'
            ],
            [
                'id' => 'MED-006',
                'name' => 'Perban Elastis (Elastic Bandage 3 Inch)',
                'category' => 'Alat Kesehatan & P3K',
                'stock' => 8,
                'unit' => 'Roll',
                'expiry_date' => '2029-01-01',
                'indication' => 'Fiksasi cedera terkilir, pergelangan kaki/tangan saat olahraga.',
                'status' => 'tersedia'
            ]
        ];

        // 30. Buku Register Kunjungan & Pemeriksaan Medis UKS (UKS Visits) - T07.5
        $this->uksVisits = [
            [
                'id' => 'UKS-001',
                'student_id' => 'SIS-0003', // Muhammad Bilal Ramadhan
                'paramedic_name' => 'Ns. Rahmatia Hidayah, S.Kep.',
                'visit_date' => '2026-11-06 09:30:00',
                'symptoms' => 'Demam meriang sejak shalat Subuh, pusing, suhu tubuh 38.3°C, tenggorokan terasa sakit saat menelan.',
                'diagnosis' => 'ISPA Ringan / Faringitis Akut & Febris',
                'temperature' => '38.3 °C',
                'blood_pressure' => '110/70 mmHg',
                'treatment_given' => 'Istirahat di ruang observasi UKS, kompres dahi hangat, diberikan Paracetamol 500mg 1 tab dan vitamin C.',
                'medicines_prescribed' => 'Paracetamol 500mg (3x1 tab p.c), Vitamin C 50mg (1x1)',
                'action_taken' => 'Istirahat di Ruang UKS (Bed 2) selama 4 jam, lalu diantar kembali ke asrama kamar istirahat.',
                'status' => 'selesai', // diobservasi, selesai, dirujuk_faskes
                'referral_hospital' => null,
                'parent_notified' => true,
                'notes' => 'Kondisi membaik pkl 14.00 suhu turun menjadi 37.1°C.'
            ],
            [
                'id' => 'UKS-002',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'paramedic_name' => 'Ns. Rahmatia Hidayah, S.Kep.',
                'visit_date' => '2026-11-07 16:15:00',
                'symptoms' => 'Pergelangan kaki kanan terkilir (sprain) saat latihan loncat tali dan panahan, tampak bengkak ringan dan nyeri tekan.',
                'diagnosis' => 'Sprain Ankle Dextra Derajat 1',
                'temperature' => '36.5 °C',
                'blood_pressure' => '115/75 mmHg',
                'treatment_given' => 'Protokol R.I.C.E: Istirahat, kompres es (ice pack) 15 menit, balut tekan dengan elastic bandage 3 inch, elevasi tungkai.',
                'medicines_prescribed' => 'Gel pereda nyeri topikal, Paracetamol 500mg bila nyeri',
                'action_taken' => 'Balut fiksasi pergelangan kaki, diistirahatkan dari aktivitas lari/olahraga selama 3 hari.',
                'status' => 'selesai',
                'referral_hospital' => null,
                'parent_notified' => false,
                'notes' => 'Tidak tampak deformitas tulang, mobilitas terbatas ringan.'
            ],
            [
                'id' => 'UKS-003',
                'student_id' => 'SIS-0004', // Zaskia Nur Ramadhani
                'paramedic_name' => 'Ns. Rahmatia Hidayah, S.Kep.',
                'visit_date' => '2026-11-08 10:45:00',
                'symptoms' => 'Mual, nyeri perih di ulu hati menjalar ke dada, lemas, mengaku telat sarapan karena mengejar waktu setoran tahfidz.',
                'diagnosis' => 'Dispepsia Sindrom / Maag Akut',
                'temperature' => '36.7 °C',
                'blood_pressure' => '105/65 mmHg',
                'treatment_given' => 'Diberikan Antasida Doen 1 tab kunyah 30 menit sebelum makan, teh manis hangat dan bubur ayam pondok.',
                'medicines_prescribed' => 'Antasida Doen (3x1 tab kunyah a.c), Minyak Kayu Putih untuk perut',
                'action_taken' => 'Istirahat di ruang UKS Putri selama 2 jam hingga rasa perih mereda.',
                'status' => 'selesai',
                'referral_hospital' => null,
                'parent_notified' => false,
                'notes' => 'Edukasi pola sarapan tepat waktu sebelum kegiatan halaqah.'
            ],
            [
                'id' => 'UKS-004',
                'student_id' => 'SIS-0009', // Ibrahim Khalilullah
                'paramedic_name' => 'Ns. Rahmatia Hidayah, S.Kep.',
                'visit_date' => '2026-11-09 06:45:00',
                'symptoms' => 'Demam tinggi 39.5°C terus-menerus selama 3 hari, menggigil, nyeri sendi hebat, bintik merah (petekie) pada lengan.',
                'diagnosis' => 'Suspek Demam Berdarah Dengue (DBD) / Dengue Fever',
                'temperature' => '39.5 °C',
                'blood_pressure' => '100/60 mmHg',
                'treatment_given' => 'Pemberian oralit rehidrasi cepat, infus sementara, dan surat rujukan segera.',
                'medicines_prescribed' => 'Rujukan Pemeriksaan Hematologi Lengkap & NS1 Antigen',
                'action_taken' => 'Dirujuk segera menggunakan ambulans sekolah ke RS Islam Karawang didampingi musyrif asrama.',
                'status' => 'dirujuk_faskes',
                'referral_hospital' => 'RS Islam Karawang (Instalasi Gawat Darurat)',
                'parent_notified' => true,
                'notes' => 'Wali santri telah dihubungi via telepon dan sedang dalam perjalanan menuju rumah sakit.'
            ]
        ];

        // 31. Data Mutasi Santri (Student Mutations: Masuk/Pindahan & Keluar) - T07.6
        $this->studentMutations = [
            [
                'id' => 'MUT-001',
                'mutation_number' => 'SK-MUT/SMA/2026/014',
                'student_id' => 'SIS-0008', // Aisyah Humaira
                'type' => 'masuk', // masuk (pindahan masuk), keluar (pindahan keluar), lulus, dikeluarkan
                'date' => '2026-10-15',
                'class_id' => 'CLS-SD-1A',
                'origin_school' => 'SD Islam Terpadu Al-Fatih Bekasi',
                'destination_school' => null,
                'reason' => 'Mengikuti perpindahan tugas kedinasan orang tua ke Karawang dan ingin fokus hafalan Qur\'an sejak dini.',
                'approval_status' => 'disetujui', // diajukan, disetujui, ditolak
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A. (Mudir Pesantren)',
                'notes' => 'Dokumen rapor pindahan, surat validasi NISN Kemendikbudristek, dan surat kelakuan baik telah lengkap diverifikasi.'
            ],
            [
                'id' => 'MUT-002',
                'mutation_number' => 'SK-MUT/SMP/2026/021',
                'student_id' => 'SIS-0006', // Nabila Khairunnisa
                'type' => 'keluar',
                'date' => '2026-10-28',
                'class_id' => 'CLS-SMP-8B',
                'origin_school' => null,
                'destination_school' => 'Pesantren Tahfidz Putri Darul Marhamah Bogor',
                'reason' => 'Permohonan keluarga untuk melanjutkan pendidikan di pondok pesantren khusus putri takhassus tahfidz 30 juz di Bogor.',
                'approval_status' => 'disetujui',
                'approver_name' => 'Drs. H. Mulyadi, M.Pd. (Kepala Sekolah)',
                'notes' => 'Telah melunasi seluruh kewajiban syahriah asrama dan mengembalikan buku perpustakaan. Surat mutasi resmi diterbitkan.'
            ],
            [
                'id' => 'MUT-003',
                'mutation_number' => 'SK-MUT/SMA/2026/029',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'type' => 'masuk',
                'date' => '2026-09-01',
                'class_id' => 'CLS-SMA-11A',
                'origin_school' => 'SMA Negeri 1 Cikarang Pusat',
                'destination_school' => null,
                'reason' => 'Pindahan masuk jenjang SMA demi penguatan karakter islami dan pembinaan lingkungan asrama santri.',
                'approval_status' => 'disetujui',
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'notes' => 'Konversi nilai matrikulasi kurikulum diniyah telah diselesaikan selama 2 pekan orientasi.'
            ]
        ];

        // 32. Data Alokasi Penempatan Kamar Santri (Resident Assignments) - T08.2
        $this->residentAssignments = [
            [
                'id' => 'ASN-001',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'room_id' => 'KMR-A01',
                'bed_number' => 'Bed 01 (Bawah)',
                'wardrobe_number' => 'LMR-101-A',
                'checkin_date' => '2026-07-15',
                'checkout_date' => null,
                'status' => 'aktif', // aktif, pindah, checkout
                'assigned_by' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Ketua kamar 101 Thariq bin Ziyad.'
            ],
            [
                'id' => 'ASN-002',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'room_id' => 'KMR-A01',
                'bed_number' => 'Bed 02 (Atas)',
                'wardrobe_number' => 'LMR-101-B',
                'checkin_date' => '2026-07-15',
                'checkout_date' => null,
                'status' => 'aktif',
                'assigned_by' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Penempatan awal tahun ajaran baru.'
            ],
            [
                'id' => 'ASN-003',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'room_id' => 'KMR-A02',
                'bed_number' => 'Bed 01 (Bawah)',
                'wardrobe_number' => 'LMR-102-A',
                'checkin_date' => '2026-09-01',
                'checkout_date' => null,
                'status' => 'aktif',
                'assigned_by' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Santri pindahan masuk SMA, masa orientasi adaptasi kamar.'
            ],
            [
                'id' => 'ASN-004',
                'student_id' => 'SIS-0009', // Ibrahim Khalilullah
                'room_id' => 'KMR-A03',
                'bed_number' => 'Bed 03 (Bawah)',
                'wardrobe_number' => 'LMR-201-C',
                'checkin_date' => '2026-07-15',
                'checkout_date' => null,
                'status' => 'aktif',
                'assigned_by' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Penempatan kamar Salahuddin Al-Ayyubi Lt 2.'
            ],
            [
                'id' => 'ASN-005',
                'student_id' => 'SIS-0002', // Siti Fatimah
                'room_id' => 'KMR-B01',
                'bed_number' => 'Bed 01 (Bawah)',
                'wardrobe_number' => 'LMR-MR-01',
                'checkin_date' => '2026-07-15',
                'checkout_date' => null,
                'status' => 'aktif',
                'assigned_by' => 'Aisyah Salsabila, S.Pd.I.',
                'notes' => 'Ketua kamar Khadijah Al-Kubra.'
            ],
            [
                'id' => 'ASN-006',
                'student_id' => 'SIS-0004', // Zaskia Nur
                'room_id' => 'KMR-B02',
                'bed_number' => 'Bed 01 (Bawah)',
                'wardrobe_number' => 'LMR-MR-02',
                'checkin_date' => '2026-07-15',
                'checkout_date' => null,
                'status' => 'aktif',
                'assigned_by' => 'Aisyah Salsabila, S.Pd.I.',
                'notes' => 'Penempatan kamar Aisyah Ash-Shiddiqah.'
            ]
        ];

        // 33. Data Jadwal Aktivitas Harian Santri Asrama (Boarding Daily Activities) - T08.3
        $this->boardingActivities = [
            [
                'id' => 'ACT-001',
                'time_start' => '03:45',
                'time_end' => '04:30',
                'name' => 'Qiyamul Lail, Sahur Puasa Sunnah & Persiapan Shalat Subuh',
                'category' => 'Ibadah & Spiritual',
                'days' => 'Setiap Hari',
                'location' => 'Masjid Kampus Alia & Kamar Asrama',
                'supervisor' => 'Musyrif Piket Malam & Tim Kesantrian',
                'mandatory' => true,
                'description' => 'Bangun tidur, tahajud mandiri, witir, pembacaan istighfar sahar, dan wudhu shalat Subuh berjamaah.'
            ],
            [
                'id' => 'ACT-002',
                'time_start' => '04:30',
                'time_end' => '05:30',
                'name' => 'Shalat Subuh Berjamaah & Dzikir Pagi Al-Ma\'tsurat',
                'category' => 'Ibadah & Spiritual',
                'days' => 'Setiap Hari',
                'location' => 'Masjid Jami\' Kampus Alia',
                'supervisor' => 'Dr. H. Zulkifli, Lc. & Asatidz Pembina',
                'mandatory' => true,
                'description' => 'Shalat fardhu Subuh berjamaah, wirid doa, pembacaan dzikir pagi kubro, dan kultum tazkiyatun nufus.'
            ],
            [
                'id' => 'ACT-003',
                'time_start' => '05:30',
                'time_end' => '06:30',
                'name' => 'Halaqah Tahfidz Pagi (Ziyadah Hafalan Baru)',
                'category' => 'Tahfidz & Al-Qur\'an',
                'days' => 'Senin - Sabtu',
                'location' => 'Masjid Kampus & Gazebo Asrama',
                'supervisor' => 'Musyrif Halaqah Masing-masing',
                'mandatory' => true,
                'description' => 'Setoran hafalan baru Al-Qur\'an kepada musyrif halaqah dan tasmi\' antar teman sebaya.'
            ],
            [
                'id' => 'ACT-004',
                'time_start' => '06:30',
                'time_end' => '07:15',
                'name' => 'Piket Kebersihan Kamar (Tandhif), Mandi & Sarapan Pagi',
                'category' => 'Kerapihan & Kedisiplinan',
                'days' => 'Setiap Hari',
                'location' => 'Gedung Asrama & Ruang Makan / Dapur',
                'supervisor' => 'Pamong Asrama & Pengurus Kamar',
                'mandatory' => true,
                'description' => 'Operasi semut kebersihan kamar, merapikan ranjang dan lemari, mandi pagi, dan sarapan bergizi bersama.'
            ],
            [
                'id' => 'ACT-005',
                'time_start' => '07:15',
                'time_end' => '15:15',
                'name' => 'KBM Sekolah Formal & Shalat Dzuhur Berjamaah',
                'category' => 'Akademik & Sekolah',
                'days' => 'Senin - Jumat',
                'location' => 'Gedung Sekolah & Ruang Kelas Teori',
                'supervisor' => 'Wali Kelas & Guru Pengampu Mapel',
                'mandatory' => true,
                'description' => 'Pembelajaran kurikulum nasional & diniyah di ruang kelas, istirahat siang, dan shalat Dzuhur di masjid.'
            ],
            [
                'id' => 'ACT-006',
                'time_start' => '15:15',
                'time_end' => '16:00',
                'name' => 'Shalat Ashar Berjamaah & Dzikir Petang',
                'category' => 'Ibadah & Spiritual',
                'days' => 'Setiap Hari',
                'location' => 'Masjid Jami\' Kampus Alia',
                'supervisor' => 'Ust. Salman Al-Farisi, S.Sos.',
                'mandatory' => true,
                'description' => 'Shalat fardhu Ashar berjamaah dan lantunan dzikir petang bersama seluruh santri.'
            ],
            [
                'id' => 'ACT-007',
                'time_start' => '16:00',
                'time_end' => '17:15',
                'name' => 'Olahraga Sore, Ekstrakurikuler Sunnah & Mandi Sore',
                'category' => 'Olahraga & Bakat',
                'days' => 'Senin - Sabtu',
                'location' => 'Lapangan Olahraga, Lapangan Panahan & GOR',
                'supervisor' => 'Pelatih Ekskul & Tim Olahraga Santri',
                'mandatory' => false,
                'description' => 'Aktivitas fisik kebugaran (panahan, silat, futsal, jogging kampus), persiapan mandi sore menjelang Maghrib.'
            ],
            [
                'id' => 'ACT-008',
                'time_start' => '17:45',
                'time_end' => '19:45',
                'name' => 'Shalat Maghrib, Ta\'lim Diniyah Kitab Kuning & Shalat Isya',
                'category' => 'Diniyah & Turats',
                'days' => 'Setiap Hari',
                'location' => 'Masjid Kampus Alia Lantai 1 & 2',
                'supervisor' => 'Dewan Asatidz Diniyah Pondok',
                'mandatory' => true,
                'description' => 'Shalat Maghrib berjamaah, kajian kitab fiqih/akhlak/tajwid ba\'da Maghrib, dilanjutkan shalat Isya berjamaah.'
            ],
            [
                'id' => 'ACT-009',
                'time_start' => '19:45',
                'time_end' => '20:30',
                'name' => 'Makan Malam Bersama & Muhadatsah Bahasa Arab/Inggris',
                'category' => 'Bahasa & Sosial',
                'days' => 'Setiap Hari',
                'location' => 'Ruang Makan Asrama Putra & Putri',
                'supervisor' => 'Pamong Asrama & Bagian Bahasa Santri',
                'mandatory' => true,
                'description' => 'Santap malam bersama dan pembiasaan percakapan bahasa resmi pondok (Bi\'ah Lughawiyyah).'
            ],
            [
                'id' => 'ACT-010',
                'time_start' => '20:30',
                'time_end' => '22:00',
                'name' => 'Belajar Mandiri Malam (Mudzakarah) & Murojaah Mandiri',
                'category' => 'Belajar Mandiri',
                'days' => 'Senin - Jumat',
                'location' => 'Kamar Asrama & Perpustakaan Digital',
                'supervisor' => 'Musyrif Asrama & Tim Pembina Kamar',
                'mandatory' => true,
                'description' => 'Mengerjakan tugas sekolah LMS, pengulangan hafalan mutqin, dan literasi membaca buku.'
            ],
            [
                'id' => 'ACT-011',
                'time_start' => '22:00',
                'time_end' => '03:45',
                'name' => 'Jam Wajib Istirahat Malam (Naum / Lampu Padam)',
                'category' => 'Kerapihan & Kedisiplinan',
                'days' => 'Setiap Hari',
                'location' => 'Kamar Asrama Masing-masing',
                'supervisor' => 'Musyrif Piket & Tim Patroli Asrama',
                'mandatory' => true,
                'description' => 'Absensi malam, lampu utama dipadamkan, doa sebelum tidur, dan wajib istirahat tenang.'
            ]
        ];

        // 34. Data Alur Perizinan Keluar & Pulang Santri (Leave Permissions) - T08.4
        $this->leavePermissions = [
            [
                'id' => 'IZN-001',
                'permit_number' => 'IZN/ASR/2026/11/008',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'type' => 'keluar_sebentar', // keluar_sebentar (izin keluar kampus beberapa jam), pulang (izin bermalam di rumah), darurat (sakit/keluarga duka)
                'reason' => 'Membeli perlengkapan lomba kaligrafi dan buku rujukan hadits di Karawang Kota.',
                'date_start' => '2026-11-07 13:30:00',
                'date_end' => '2026-11-07 17:00:00',
                'pickup_person' => 'Mandiri (Bersama Regu Santri)',
                'pickup_phone' => '081234567890',
                'approval_status' => 'disetujui', // diajukan, disetujui, ditolak, kembali
                'approver_name' => 'Ust. Salman Al-Farisi, S.Sos. (Musyrif Asrama)',
                'security_check_out' => '2026-11-07 13:35:10',
                'security_check_in' => '2026-11-07 16:45:20',
                'late_minutes' => 0,
                'gate_pass_code' => 'GP-7721-AF',
                'notes' => 'Kembali tepat waktu sebelum adzan Maghrib berkumandang.'
            ],
            [
                'id' => 'IZN-002',
                'permit_number' => 'IZN/ASR/2026/11/012',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'type' => 'pulang',
                'reason' => 'Menghadiri walimatul ursy (pernikahan) kakak kandung di Cirebon.',
                'date_start' => '2026-11-07 15:00:00',
                'date_end' => '2026-11-09 17:00:00',
                'pickup_person' => 'Bapak Ramadhan (Ayah Kandung)',
                'pickup_phone' => '081298765432',
                'approval_status' => 'kembali',
                'approver_name' => 'Dr. H. Zulkifli, Lc. (Mudir Pondok)',
                'security_check_out' => '2026-11-07 15:15:00',
                'security_check_in' => '2026-11-09 16:30:00',
                'late_minutes' => 0,
                'gate_pass_code' => 'GP-9832-BR',
                'notes' => 'Diantar langsung oleh orang tua kembali ke asrama, surat izin ditandatangani wali.'
            ],
            [
                'id' => 'IZN-003',
                'permit_number' => 'IZN/ASR/2026/11/019',
                'student_id' => 'SIS-0009', // Ibrahim Khalilullah
                'type' => 'darurat',
                'reason' => 'Rujukan medis intensif rawat inap demam berdarah di RS Islam Karawang.',
                'date_start' => '2026-11-09 07:00:00',
                'date_end' => '2026-11-12 17:00:00',
                'pickup_person' => 'Ambulans Sekolah & Ibu Halimah (Ibu Kandung)',
                'pickup_phone' => '081344556677',
                'approval_status' => 'disetujui',
                'approver_name' => 'Ns. Rahmatia Hidayah (UKS) & Ust. Salman Al-Farisi',
                'security_check_out' => '2026-11-09 07:10:00',
                'security_check_in' => null,
                'late_minutes' => 0,
                'gate_pass_code' => 'GP-3490-IK',
                'notes' => 'Izin darurat kesehatan, didampingi pihak medis sekolah.'
            ],
            [
                'id' => 'IZN-004',
                'permit_number' => 'IZN/ASR/2026/11/025',
                'student_id' => 'SIS-0002', // Siti Fatimah
                'type' => 'pulang',
                'reason' => 'Izin menjenguk nenek yang sedang menjalani operasi di Bandung.',
                'date_start' => '2026-11-14 10:00:00',
                'date_end' => '2026-11-16 17:00:00',
                'pickup_person' => 'Drs. H. Mulyadi (Wali / Keluarga)',
                'pickup_phone' => '081223344556',
                'approval_status' => 'diajukan',
                'approver_name' => 'Menunggu Verifikasi Musyirfah',
                'security_check_out' => null,
                'security_check_in' => null,
                'late_minutes' => 0,
                'gate_pass_code' => 'GP-PENDING',
                'notes' => 'Permohonan masuk via portal orang tua / wali santri.'
            ]
        ];

        // 35. Data Presensi & Monitoring Ibadah / Keberadaan Asrama (Boarding Attendance) - T08.5
        $this->boardingAttendance = [
            [
                'id' => 'BAT-001',
                'date' => '2026-11-09',
                'session' => 'Subuh', // Qiyamul Lail, Subuh, Dzuhur, Ashar, Maghrib, Isya, Malam (Naum)
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'room_id' => 'KMR-A01',
                'status' => 'hadir', // hadir, terlambat, sakit, izin, alpa
                'location' => 'Masjid Kampus Alia (Shaf 1)',
                'supervisor_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Tepat waktu dan menyimak wirid doa.'
            ],
            [
                'id' => 'BAT-002',
                'date' => '2026-11-09',
                'session' => 'Subuh',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'room_id' => 'KMR-A01',
                'status' => 'hadir',
                'location' => 'Masjid Kampus Alia (Shaf 2)',
                'supervisor_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Tepat waktu mengikuti takbiratul ihram.'
            ],
            [
                'id' => 'BAT-003',
                'date' => '2026-11-09',
                'session' => 'Subuh',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'room_id' => 'KMR-A02',
                'status' => 'hadir',
                'location' => 'Masjid Kampus Alia (Shaf 2)',
                'supervisor_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Hadir berjamaah.'
            ],
            [
                'id' => 'BAT-004',
                'date' => '2026-11-09',
                'session' => 'Subuh',
                'student_id' => 'SIS-0009', // Ibrahim Khalilullah
                'room_id' => 'KMR-A03',
                'status' => 'sakit',
                'location' => 'RS Islam Karawang (Rawat Inap)',
                'supervisor_name' => 'Ust. Salman Al-Farisi, S.Sos.',
                'notes' => 'Sakit demam berdarah dan dirujuk ke RS.'
            ],
            [
                'id' => 'BAT-005',
                'date' => '2026-11-09',
                'session' => 'Subuh',
                'student_id' => 'SIS-0002', // Siti Fatimah
                'room_id' => 'KMR-B01',
                'status' => 'hadir',
                'location' => 'Musholla Asrama Putri Gedung Maryam',
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'notes' => 'Memimpin tilawah bersama santri putri.'
            ],
            [
                'id' => 'BAT-006',
                'date' => '2026-11-09',
                'session' => 'Subuh',
                'student_id' => 'SIS-0004', // Zaskia Nur
                'room_id' => 'KMR-B02',
                'status' => 'hadir',
                'location' => 'Musholla Asrama Putri Gedung Maryam',
                'supervisor_name' => 'Aisyah Salsabila, S.Pd.I.',
                'notes' => 'Hadir tepat waktu.'
            ],
            [
                'id' => 'BAT-007',
                'date' => '2026-11-08',
                'session' => 'Malam (Naum)',
                'student_id' => 'SIS-0001',
                'room_id' => 'KMR-A01',
                'status' => 'hadir',
                'location' => 'Kamar 101 Thariq bin Ziyad',
                'supervisor_name' => 'Budi Santoso, S.Kom.',
                'notes' => 'Pukul 22.00 sudah berada di ranjang tidur.'
            ],
            [
                'id' => 'BAT-008',
                'date' => '2026-11-08',
                'session' => 'Malam (Naum)',
                'student_id' => 'SIS-0003',
                'room_id' => 'KMR-A01',
                'status' => 'hadir',
                'location' => 'Kamar 101 Thariq bin Ziyad',
                'supervisor_name' => 'Budi Santoso, S.Kom.',
                'notes' => 'Hadir lengkap.'
            ]
        ];

        // 36. Data Menu Makanan & Katering Asrama (Boarding Meals) - T08.6
        $this->boardingMeals = [
            [
                'id' => 'MEL-001',
                'day' => 'Senin',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Nasi Kuning Komplit & Telur Balado',
                'main_dish' => 'Telur Ayam Balado Bumbu Manis Gurih',
                'side_dish' => 'Orek Tempe Manis & Kerupuk Kampung',
                'vegetable' => 'Timun Segar & Selada Daun',
                'fruit_drink' => 'Susu Kedelai Hangat & Teh Manis',
                'calories' => 480,
                'nutrition_highlight' => 'Karbohidrat kompleks, protein nabati & hewani seimbang',
                'allergy_notes' => 'Bebas gluten. Mengandung telur ayam & kedelai.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-002',
                'day' => 'Senin',
                'meal_time' => 'Siang',
                'time_range' => '12:15 - 13:15 WIB',
                'menu_title' => 'Ayam Lengkuas & Sayur Asem Jakarta',
                'main_dish' => 'Ayam Goreng Bumbu Serundeng Lengkuas',
                'side_dish' => 'Tahu & Tempe Goreng Sambal Terasi',
                'vegetable' => 'Sayur Asem Labu Siam & Jagung Manis',
                'fruit_drink' => 'Semangka Merah Segar & Air Mineral',
                'calories' => 650,
                'nutrition_highlight' => 'Kaya vitamin C dari buah semangka dan serat alami sayur asam',
                'allergy_notes' => 'Aman tanpa alergen kacang tanah berbahaya.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-003',
                'day' => 'Senin',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Soto Ayam Madura & Telur Rebus',
                'main_dish' => 'Suwiran Ayam Kampung & Telur Rebus',
                'side_dish' => 'Perkedel Kentang & Kerupuk Udang',
                'vegetable' => 'Kol Iris, Tauge Pendek & Seledri Daun',
                'fruit_drink' => 'Wedang Jahe Hangat Rempah Alami',
                'calories' => 550,
                'nutrition_highlight' => 'Kuah rempah alami menghangatkan tubuh santri malam hari',
                'allergy_notes' => 'Opsi non-seafood tanpa kerupuk udang tersedia untuk santri alergi.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-004',
                'day' => 'Selasa',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Nasi Goreng Spesial & Telur Mata Sapi',
                'main_dish' => 'Telur Ayam Ceplok Setengah Matang / Matang',
                'side_dish' => 'Sosis Sapi Panggang & Acar Mentimun',
                'vegetable' => 'Irisan Tomat Buah & Selada',
                'fruit_drink' => 'Teh Manis Hangat Melati & Air Putih',
                'calories' => 500,
                'nutrition_highlight' => 'Energi cepat untuk mengawali halaqah tahfidz dan sekolah',
                'allergy_notes' => 'Tingkat kepedasan rendah ramah lambung santri.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-005',
                'day' => 'Selasa',
                'meal_time' => 'Siang',
                'time_range' => '12:15 - 13:15 WIB',
                'menu_title' => 'Ikan Kembung Bakar Kecap & Sayur Lodeh',
                'main_dish' => 'Ikan Kembung Segar Bakar Kecap Limau',
                'side_dish' => 'Bakwan Jagung Manis Renyah',
                'vegetable' => 'Sayur Lodeh Labu Siam & Terong Ungu',
                'fruit_drink' => 'Pisang Cavendish & Air Mineral',
                'calories' => 620,
                'nutrition_highlight' => 'Asam lemak Omega-3 tinggi dari ikan laut untuk daya ingat tahfidz',
                'allergy_notes' => 'Mengandung ikan laut. Opsi alternatif telur dadar disediakan.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-006',
                'day' => 'Selasa',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Rolade Sapi Saus Tiram & Capcay Bakso',
                'main_dish' => 'Rolade Daging Sapi Olahan Saus Gurih',
                'side_dish' => 'Tahu Crispy Bumbu Bawang',
                'vegetable' => 'Capcay Kuah Wortel, Kembang Kol & Bakso',
                'fruit_drink' => 'Air Mineral & Jeruk Manis Pontianak',
                'calories' => 580,
                'nutrition_highlight' => 'Zat besi dan multivitamin serat sayuran hijau berlimpah',
                'allergy_notes' => 'Bebas alergen udang dan kacang.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-007',
                'day' => 'Rabu',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Bubur Ayam Komplit Khas Sukabumi',
                'main_dish' => 'Ayam Suwir Bumbu Kuning & Cakwe Gurih',
                'side_dish' => 'Sate Telur Puyuh & Kerupuk Pangsit',
                'vegetable' => 'Daun Bawang, Seledri & Kedelai Goreng',
                'fruit_drink' => 'Susu Segar Pasteurisasi & Teh Hangat',
                'calories' => 460,
                'nutrition_highlight' => 'Tekstur lembut kaya kalsium dan karbohidrat hangat',
                'allergy_notes' => 'Bagi santri alergi kedelai goreng disajikan terpisah.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-008',
                'day' => 'Rabu',
                'meal_time' => 'Siang',
                'time_range' => '12:15 - 13:15 WIB',
                'menu_title' => 'Rendang Daging Sapi & Gulai Daun Singkong',
                'main_dish' => 'Rendang Daging Sapi Bumbu Minang Asli',
                'side_dish' => 'Perkedel Kentang & Sambal Ijo Padang',
                'vegetable' => 'Gulai Daun Singkong Santan Encer Gurih',
                'fruit_drink' => 'Buah Pepaya California & Air Mineral',
                'calories' => 700,
                'nutrition_highlight' => 'Protein hewani tinggi, zat besi, dan serat pepaya untuk pencernaan',
                'allergy_notes' => 'Santan kelapa alami segar, bebas pengawet.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-009',
                'day' => 'Rabu',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Sup Daging Sapi Bening & Tempe Bacem',
                'main_dish' => 'Daging Sapi Empuk Kuah Bening Rempah',
                'side_dish' => 'Tempe Bacem Legit & Sambal Kecap Rawit',
                'vegetable' => 'Wortel, Buncis & Kentang Rebus',
                'fruit_drink' => 'Air Mineral & Pisang Emas',
                'calories' => 560,
                'nutrition_highlight' => 'Kuah kaldu alami mengembalikan cairan dan elektrolit tubuh',
                'allergy_notes' => 'Bebas alergen umum.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-010',
                'day' => 'Kamis',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Nasi Uduk Betawi & Semur Tahu Telur',
                'main_dish' => 'Telur Ayam Semur Kecap Manis Rempah',
                'side_dish' => 'Tahu Semur Gurih & Bihun Goreng',
                'vegetable' => 'Timun Iris Segar & Bawang Goreng Brebes',
                'fruit_drink' => 'Teh Melati Manis Hangat',
                'calories' => 490,
                'nutrition_highlight' => 'Karbohidrat aromatik gurih pembakar semangat pagi hari',
                'allergy_notes' => 'Aman tanpa alergen gluten berat.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-011',
                'day' => 'Kamis',
                'meal_time' => 'Siang',
                'time_range' => '12:15 - 13:15 WIB',
                'menu_title' => 'Ayam Suwir Pedas Manis & Sayur Bening Bayam',
                'main_dish' => 'Fillet Ayam Suwir Pedas Manis Bumbu Bali',
                'side_dish' => 'Tempe Kemul Renyah & Kerupuk Ikan',
                'vegetable' => 'Sayur Bening Bayam Hijau & Jagung Pipil',
                'fruit_drink' => 'Buah Melon Segar & Air Mineral',
                'calories' => 610,
                'nutrition_highlight' => 'Zat besi tinggi dari bayam segar dan vitamin A jagung',
                'allergy_notes' => 'Bebas alergen udang.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-012',
                'day' => 'Kamis',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Paket Buka Puasa Sunnah: Kurma & Soto Betawi',
                'main_dish' => 'Soto Daging Betawi Kuah Susu Gurih Sehat',
                'side_dish' => 'Emping Melinjo & Sambal Cuka Pedas',
                'vegetable' => 'Tomat Merah Iris & Daun Bawang',
                'fruit_drink' => 'Kurma Ajwa 3 Butir & Jus Jambu Merah',
                'calories' => 670,
                'nutrition_highlight' => 'Glukosa alami kurma mengembalikan stamina puasa sunnah',
                'allergy_notes' => 'Menggunakan susu sapi segar pengganti santan berat.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-013',
                'day' => 'Jumat',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Lontong Sayur Labu Siam & Telur Rebus',
                'main_dish' => 'Telur Ayam Rebus Bumbu Kuning Gurih',
                'side_dish' => 'Tahu Goreng Segitiga & Kerupuk Merah',
                'vegetable' => 'Sayur Labu Siam Serut Kuah Kuning Encer',
                'fruit_drink' => 'Susu Sapi Segar Cokelat Hangat',
                'calories' => 510,
                'nutrition_highlight' => 'Kalsium tinggi dari susu dan karbohidrat lembut lontong beras',
                'allergy_notes' => 'Laktosa susu sapi segar.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-014',
                'day' => 'Jumat',
                'meal_time' => 'Siang',
                'time_range' => '12:30 - 13:30 WIB',
                'menu_title' => 'Nasi Kebuli Daging Kambing Khas Arabika',
                'main_dish' => 'Daging Kambing Empuk Bumbu Rempah Arab',
                'side_dish' => 'Emping Renyah & Sambal Goreng Kismis',
                'vegetable' => 'Acar Nanas Timun Segar Manis Asam',
                'fruit_drink' => 'Jus Kurma Susu Segar & Air Mineral',
                'calories' => 740,
                'nutrition_highlight' => 'Menu sunnah Jumat berkah, kaya kalori, zat besi dan stamina',
                'allergy_notes' => 'Tersedia pilihan ayam kebuli untuk santri yang menghindari daging kambing.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-015',
                'day' => 'Jumat',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Ikan Nila Goreng Kering & Tumis Kangkung',
                'main_dish' => 'Ikan Nila Segar Goreng Bumbu Bawang Gurih',
                'side_dish' => 'Tahu Goreng & Sambal Terasi Bakar',
                'vegetable' => 'Tumis Kangkung Saus Tiram Gurih Hangat',
                'fruit_drink' => 'Air Mineral & Semangka Kuning Manis',
                'calories' => 590,
                'nutrition_highlight' => 'Protein ikan air tawar kaya mineral dan vitamin serat kangkung',
                'allergy_notes' => 'Ikan air tawar, aman untuk santri yang alergi ikan laut.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-016',
                'day' => 'Sabtu',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Sandwich Roti Gandum Telur Keju & Salad',
                'main_dish' => 'Scrambled Egg Telur Ayam & Keju Cheddar',
                'side_dish' => 'Sosis Ayam Panggang Saus Tomat',
                'vegetable' => 'Selada Romaine & Irisan Tomat Segar',
                'fruit_drink' => 'Susu UHT Full Cream & Jus Apel Manis',
                'calories' => 470,
                'nutrition_highlight' => 'Serat gandum utuh dan kalsium susu menunjang latihan ekstrakurikuler',
                'allergy_notes' => 'Mengandung gandum (gluten) & susu sapi.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-017',
                'day' => 'Sabtu',
                'meal_time' => 'Siang',
                'time_range' => '12:15 - 13:15 WIB',
                'menu_title' => 'Rawon Daging Sapi Surabaya & Telur Asin',
                'main_dish' => 'Daging Sapi Rawon Kuah Kluwek Gurih',
                'side_dish' => 'Telur Bebek Asin Rebus & Tempe Goreng',
                'vegetable' => 'Tauge Pendek Segar & Sambal Terasi',
                'fruit_drink' => 'Air Mineral & Buah Jeruk Manis',
                'calories' => 660,
                'nutrition_highlight' => 'Antioksidan rempah kluwek alami & protein telur asin',
                'allergy_notes' => 'Telur bebek asin.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-018',
                'day' => 'Sabtu',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Mie Goreng Seafood Halal & Bakwan Udang',
                'main_dish' => 'Fillet Ikan Kakap & Cumi Segar Suwir',
                'side_dish' => 'Bakwan Sayur Udang & Kerupuk',
                'vegetable' => 'Sawi Hijau, Wortel & Kol Segar',
                'fruit_drink' => 'Es Teh Manis Hangat Sereh',
                'calories' => 590,
                'nutrition_highlight' => 'Karbohidrat mie kuning dan zink seafood',
                'allergy_notes' => 'Menu alternatif ayam cincang disediakan untuk santri alergi seafood.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-019',
                'day' => 'Ahad',
                'meal_time' => 'Pagi',
                'time_range' => '06:30 - 07:15 WIB',
                'menu_title' => 'Nasi Liwet Solo Gurih & Opor Ayam Suwir',
                'main_dish' => 'Ayam Suwir Opor Bumbu Santan Gurih',
                'side_dish' => 'Tahu Bacem Manis & Telur Pindang Cokelat',
                'vegetable' => 'Sayur Labu Siam Bumbu Pedas Manis',
                'fruit_drink' => 'Wedang Ronde Hangat & Air Putih',
                'calories' => 520,
                'nutrition_highlight' => 'Sajian khas tradisi Solo ramah keluarga saat hari sambangan wali',
                'allergy_notes' => 'Aman tanpa kacang tanah.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-020',
                'day' => 'Ahad',
                'meal_time' => 'Siang',
                'time_range' => '12:15 - 13:15 WIB',
                'menu_title' => 'Bebek Goreng Kremes & Sambal Korek Gurih',
                'main_dish' => 'Potongan Bebek Empuk Goreng Kremes Renyah',
                'side_dish' => 'Tahu Tempe Goreng & Lalapan Segar',
                'vegetable' => 'Daun Kemangi, Kol Goreng & Timun',
                'fruit_drink' => 'Es Sirup Melon Selasih Segar',
                'calories' => 690,
                'nutrition_highlight' => 'Energi tinggi memulihkan stamina santri pasca pekan olahraga',
                'allergy_notes' => 'Bebas gluten pada menu utama.',
                'chef_in_charge' => 'Chef Marzuki',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
            [
                'id' => 'MEL-021',
                'day' => 'Ahad',
                'meal_time' => 'Malam',
                'time_range' => '19:45 - 20:30 WIB',
                'menu_title' => 'Sup Jamur Tiram Bakso Sapi & Tempe Orek',
                'main_dish' => 'Bakso Sapi Halus & Jamur Tiram Kuah Kaldu',
                'side_dish' => 'Tempe Orek Kering Renyah & Kerupuk',
                'vegetable' => 'Wortel, Seledri & Kembang Kol',
                'fruit_drink' => 'Air Mineral & Buah Pisang Barangan',
                'calories' => 540,
                'nutrition_highlight' => 'Asupan ringan ramah pencernaan menjelang tidur malam santri',
                'allergy_notes' => 'Bebas alergen umum.',
                'chef_in_charge' => 'Ibu Rohani',
                'portion_count' => 450,
                'halal_status' => 'Tersertifikasi Halal LPPOM MUI'
            ],
        ];

        // 37. Data Master Pos Biaya & Tarif Keuangan (Fee Categories) - T09.2
        $this->feeCategories = [
            [
                'id' => 'FEE-001',
                'code' => 'SPP-SMA',
                'name' => 'SPP Bulanan SMA Boarding',
                'category' => 'SPP',
                'unit_id' => 'UNIT-SMA',
                'unit_name' => 'SMA Islam Alia',
                'amount' => 1850000,
                'period_type' => 'bulanan', // bulanan, tahunan, sekali
                'description' => 'Biaya pendidikan bulanan, bimbingan akademik, & ekstrakurikuler SMA.',
                'status' => 'aktif'
            ],
            [
                'id' => 'FEE-002',
                'code' => 'SPP-SMP',
                'name' => 'SPP Bulanan SMP Boarding',
                'category' => 'SPP',
                'unit_id' => 'UNIT-SMP',
                'unit_name' => 'SMP Islam Alia',
                'amount' => 1650000,
                'period_type' => 'bulanan',
                'description' => 'Biaya pendidikan bulanan, pembinaan karakter & tahfidz SMP.',
                'status' => 'aktif'
            ],
            [
                'id' => 'FEE-003',
                'code' => 'SPP-SD',
                'name' => 'SPP Bulanan SD Full Day',
                'category' => 'SPP',
                'unit_id' => 'UNIT-SD',
                'unit_name' => 'SD Islam Alia',
                'amount' => 1250000,
                'period_type' => 'bulanan',
                'description' => 'Biaya sekolah dasar full-day school terpadu.',
                'status' => 'aktif'
            ],
            [
                'id' => 'FEE-004',
                'code' => 'ASR-MAKAN',
                'name' => 'Uang Asrama & Makan (Boarding)',
                'category' => 'Asrama',
                'unit_id' => 'UNIT-SMA',
                'unit_name' => 'Semua Unit Boarding',
                'amount' => 1500000,
                'period_type' => 'bulanan',
                'description' => 'Akomodasi kamar ber-AC, laundry pakaian, & makan 3 kali sehari.',
                'status' => 'aktif'
            ],
            [
                'id' => 'FEE-005',
                'code' => 'REG-DU',
                'name' => 'Daftar Ulang Tahunan',
                'category' => 'Daftar Ulang',
                'unit_id' => 'UNIT-SMA',
                'unit_name' => 'SMA Islam Alia',
                'amount' => 3500000,
                'period_type' => 'tahunan',
                'description' => 'Perpanjangan sarana belajar, modul buku, & kegiatan tahunan santri.',
                'status' => 'aktif'
            ],
            [
                'id' => 'FEE-006',
                'code' => 'SERAGAM-01',
                'name' => 'Paket Seragam & Atribut Sekolah',
                'category' => 'Perlengkapan',
                'unit_id' => 'UNIT-SMA',
                'unit_name' => 'Semua Unit',
                'amount' => 1200000,
                'period_type' => 'sekali',
                'description' => '5 stel seragam resmi, gamis batik, baju olahraga & atribut bordir.',
                'status' => 'aktif'
            ],
            [
                'id' => 'FEE-007',
                'code' => 'EKS-OUTING',
                'name' => 'Rihlah Ilmiah & Outing Class',
                'category' => 'Kegiatan',
                'unit_id' => 'UNIT-SMP',
                'unit_name' => 'SMP Islam Alia',
                'amount' => 450000,
                'period_type' => 'tahunan',
                'description' => 'Kunjungan observasi sains terpadu dan tadabbur alam.',
                'status' => 'aktif'
            ]
        ];

        // 38. Data Invoice / Tagihan Santri (Student Invoices) - T09.3
        $this->studentInvoices = [
            [
                'id' => 'INV-202611-001',
                'invoice_number' => 'INV/ALIA/2026/11/001',
                'student_id' => 'SIS-0001', // Ahmad Fauzan (10-A)
                'fee_category_id' => 'FEE-001',
                'fee_title' => 'SPP Bulanan SMA Boarding - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1850000,
                'paid_amount' => 1850000,
                'status' => 'lunas', // lunas, sebagian, belum_lunas, jatuh_tempo
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-03 09:15:00',
                'created_date' => '2026-11-01',
                'notes' => 'Pembayaran via Virtual Account Bank Syariah Indonesia (BSI).'
            ],
            [
                'id' => 'INV-202611-002',
                'invoice_number' => 'INV/ALIA/2026/11/002',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'fee_category_id' => 'FEE-004',
                'fee_title' => 'Uang Asrama & Konsumsi Dapur - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1500000,
                'paid_amount' => 1500000,
                'status' => 'lunas',
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-03 09:15:00',
                'created_date' => '2026-11-01',
                'notes' => 'Pembayaran lunas bersamaan dengan SPP.'
            ],
            [
                'id' => 'INV-202611-003',
                'invoice_number' => 'INV/ALIA/2026/11/003',
                'student_id' => 'SIS-0002', // Siti Fatimah (10-B)
                'fee_category_id' => 'FEE-001',
                'fee_title' => 'SPP Bulanan SMA Boarding - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1850000,
                'paid_amount' => 1850000,
                'status' => 'lunas',
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-05 14:20:00',
                'created_date' => '2026-11-01',
                'notes' => 'Transfer manual Bank Muamalat konfirmasi via kasir.'
            ],
            [
                'id' => 'INV-202611-004',
                'invoice_number' => 'INV/ALIA/2026/11/004',
                'student_id' => 'SIS-0003', // Bilal Ramadhan (10-A)
                'fee_category_id' => 'FEE-001',
                'fee_title' => 'SPP Bulanan SMA Boarding - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1850000,
                'paid_amount' => 1000000,
                'status' => 'sebagian',
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-06 10:10:00',
                'created_date' => '2026-11-01',
                'notes' => 'Cicilan pertama disetujui Bendahara, pelunasan pekan depan.'
            ],
            [
                'id' => 'INV-202611-005',
                'invoice_number' => 'INV/ALIA/2026/11/005',
                'student_id' => 'SIS-0004', // Zaskia Nur (10-B)
                'fee_category_id' => 'FEE-001',
                'fee_title' => 'SPP Bulanan SMA Boarding - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1850000,
                'paid_amount' => 0,
                'status' => 'belum_lunas',
                'due_date' => '2026-11-10',
                'paid_date' => null,
                'created_date' => '2026-11-01',
                'notes' => 'Menunggu konfirmasi transfer wali santri.'
            ],
            [
                'id' => 'INV-202611-006',
                'invoice_number' => 'INV/ALIA/2026/11/006',
                'student_id' => 'SIS-0005', // Muhammad Rayhan (7-A SMP)
                'fee_category_id' => 'FEE-002',
                'fee_title' => 'SPP Bulanan SMP Boarding - November 2026',
                'unit_id' => 'UNIT-SMP',
                'period_month' => '2026-11',
                'amount' => 1650000,
                'paid_amount' => 1650000,
                'status' => 'lunas',
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-04 11:30:00',
                'created_date' => '2026-11-01',
                'notes' => 'Pembayaran lunas tepat waktu.'
            ],
            [
                'id' => 'INV-202611-007',
                'invoice_number' => 'INV/ALIA/2026/11/007',
                'student_id' => 'SIS-0006', // Aisyah Humaira (7-B SMP)
                'fee_category_id' => 'FEE-002',
                'fee_title' => 'SPP Bulanan SMP Boarding - November 2026',
                'unit_id' => 'UNIT-SMP',
                'period_month' => '2026-11',
                'amount' => 1650000,
                'paid_amount' => 0,
                'status' => 'belum_lunas',
                'due_date' => '2026-11-10',
                'paid_date' => null,
                'created_date' => '2026-11-01',
                'notes' => 'Invoice terkirim ke WhatsApp wali santri.'
            ],
            [
                'id' => 'INV-202610-008',
                'invoice_number' => 'INV/ALIA/2026/10/018',
                'student_id' => 'SIS-0004', // Zaskia Nur
                'fee_category_id' => 'FEE-004',
                'fee_title' => 'Uang Asrama & Dapur - Oktober 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-10',
                'amount' => 1500000,
                'paid_amount' => 0,
                'status' => 'jatuh_tempo',
                'due_date' => '2026-10-10',
                'paid_date' => null,
                'created_date' => '2026-10-01',
                'notes' => 'Surat pemberitahuan tunggakan telah diterbitkan oleh Tata Usaha.'
            ],
            [
                'id' => 'INV-202611-009',
                'invoice_number' => 'INV/ALIA/2026/11/009',
                'student_id' => 'SIS-0007', // Rayhan Pratama (10-A)
                'fee_category_id' => 'FEE-001',
                'fee_title' => 'SPP Bulanan SMA Boarding - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1850000,
                'paid_amount' => 1850000,
                'status' => 'lunas',
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-02 08:45:00',
                'created_date' => '2026-11-01',
                'notes' => 'Lunas awal bulan.'
            ],
            [
                'id' => 'INV-202611-010',
                'invoice_number' => 'INV/ALIA/2026/11/010',
                'student_id' => 'SIS-0008', // Maryam Khairunnisa (10-B)
                'fee_category_id' => 'FEE-001',
                'fee_title' => 'SPP Bulanan SMA Boarding - November 2026',
                'unit_id' => 'UNIT-SMA',
                'period_month' => '2026-11',
                'amount' => 1850000,
                'paid_amount' => 1850000,
                'status' => 'lunas',
                'due_date' => '2026-11-10',
                'paid_date' => '2026-11-04 16:00:00',
                'created_date' => '2026-11-01',
                'notes' => 'Pembayaran via BSI Mobile.'
            ]
        ];

        // 39. Data Transaksi Penerimaan Kasir (Payment Transactions) - T09.4
        $this->paymentTransactions = [
            [
                'id' => 'TRX-202611-001',
                'receipt_number' => 'KWT/ALIA/2026/11/001',
                'invoice_id' => 'INV-202611-001',
                'student_id' => 'SIS-0001',
                'amount' => 1850000,
                'payment_method' => 'VA Bank Syariah Indonesia',
                'payment_date' => '2026-11-03 09:15:00',
                'cashier_name' => 'Hj. Nurul Hidayati, S.E.',
                'status' => 'berhasil',
                'notes' => 'Otomatis terverifikasi sistem Payment Gateway BSI.'
            ],
            [
                'id' => 'TRX-202611-002',
                'receipt_number' => 'KWT/ALIA/2026/11/002',
                'invoice_id' => 'INV-202611-002',
                'student_id' => 'SIS-0001',
                'amount' => 1500000,
                'payment_method' => 'VA Bank Syariah Indonesia',
                'payment_date' => '2026-11-03 09:15:00',
                'cashier_name' => 'Hj. Nurul Hidayati, S.E.',
                'status' => 'berhasil',
                'notes' => 'Pembayaran biaya asrama & katering.'
            ],
            [
                'id' => 'TRX-202611-003',
                'receipt_number' => 'KWT/ALIA/2026/11/003',
                'invoice_id' => 'INV-202611-003',
                'student_id' => 'SIS-0002',
                'amount' => 1850000,
                'payment_method' => 'Transfer Bank Muamalat',
                'payment_date' => '2026-11-05 14:20:00',
                'cashier_name' => 'Staf Keuangan Alia',
                'status' => 'berhasil',
                'notes' => 'Bukti mutasi rekening koran terlampir.'
            ],
            [
                'id' => 'TRX-202611-004',
                'receipt_number' => 'KWT/ALIA/2026/11/004',
                'invoice_id' => 'INV-202611-004',
                'student_id' => 'SIS-0003',
                'amount' => 1000000,
                'payment_method' => 'Tunai / Kasir Sekolah',
                'payment_date' => '2026-11-06 10:10:00',
                'cashier_name' => 'Hj. Nurul Hidayati, S.E.',
                'status' => 'berhasil',
                'notes' => 'Pembayaran angsuran pertama SPP November.'
            ],
            [
                'id' => 'TRX-202611-005',
                'receipt_number' => 'KWT/ALIA/2026/11/005',
                'invoice_id' => 'INV-202611-006',
                'student_id' => 'SIS-0005',
                'amount' => 1650000,
                'payment_method' => 'VA Bank BCA',
                'payment_date' => '2026-11-04 11:30:00',
                'cashier_name' => 'Sistem Auto-Posting',
                'status' => 'berhasil',
                'notes' => 'Pelunasan SPP SMP Unit.'
            ],
            [
                'id' => 'TRX-202611-006',
                'receipt_number' => 'KWT/ALIA/2026/11/006',
                'invoice_id' => 'INV-202611-009',
                'student_id' => 'SIS-0007',
                'amount' => 1850000,
                'payment_method' => 'VA Bank Syariah Indonesia',
                'payment_date' => '2026-11-02 08:45:00',
                'cashier_name' => 'Hj. Nurul Hidayati, S.E.',
                'status' => 'berhasil',
                'notes' => 'Setoran awal bulan.'
            ],
            [
                'id' => 'TRX-202611-007',
                'receipt_number' => 'KWT/ALIA/2026/11/007',
                'invoice_id' => 'INV-202611-010',
                'student_id' => 'SIS-0008',
                'amount' => 1850000,
                'payment_method' => 'QRIS Kasir Sekolah',
                'payment_date' => '2026-11-04 16:00:00',
                'cashier_name' => 'Staf Keuangan Alia',
                'status' => 'berhasil',
                'notes' => 'Scan QRIS Dinamis Loket Keuangan.'
            ]
        ];

        // 40. Data Pengeluaran & Beban Operasional Sekolah (Expenses) - T09.1
        $this->financeExpenses = [
            [
                'id' => 'EXP-202611-001',
                'title' => 'Pengadaan Bahan Mentah Dapur & Katering Santri Pekan I',
                'category' => 'Konsumsi & Asrama',
                'amount' => 12500000,
                'date' => '2026-11-02',
                'unit_id' => 'UNIT-SMA',
                'recipient' => 'Mitra Pasar Induk Sayur & Daging',
                'status' => 'disetujui',
                'notes' => 'Beras premium, telur 50 tray, daging ayam & bumbu rempah.'
            ],
            [
                'id' => 'EXP-202611-002',
                'title' => 'Tagihan Listrik PLN & Air PDAM Kompleks Asrama',
                'category' => 'Utilitas & Sarpras',
                'amount' => 8450000,
                'date' => '2026-11-04',
                'unit_id' => 'UNIT-SMA',
                'recipient' => 'PT PLN & PDAM Tirta Tarum',
                'status' => 'disetujui',
                'notes' => 'Gardu listrik gedung Al-Fatih, Maryam & Masjid.'
            ],
            [
                'id' => 'EXP-202611-003',
                'title' => 'Honorium Khotib Jumat & Guru Tamu Halaqah Takhassus',
                'category' => 'Kegiatan Dakwah',
                'amount' => 1800000,
                'date' => '2026-11-06',
                'unit_id' => 'UNIT-SMA',
                'recipient' => 'Asatidz Tamu',
                'status' => 'disetujui',
                'notes' => 'Kajian akidah pekanan & tasmi santri.'
            ],
            [
                'id' => 'EXP-202611-004',
                'title' => 'Perawatan Berkala AC Split Kamar Santri & Lab Komputer',
                'category' => 'Pemeliharaan',
                'amount' => 3200000,
                'date' => '2026-11-05',
                'unit_id' => 'UNIT-SMA',
                'recipient' => 'CV Sejuk Mandiri Teknik',
                'status' => 'disetujui',
                'notes' => 'Cuci AC 16 unit dan penambahan freon.'
            ]
        ];

        // 41. Data Beasiswa & Potongan Biaya (Scholarships & Discounts) - T09.5
        $this->scholarships = [
            [
                'id' => 'SCH-001',
                'code' => 'SCH-THF-30',
                'student_id' => 'SIS-0001', // Ahmad Fauzan
                'type' => 'tahfidz', // tahfidz, prestasi, yatim_dhuafa, bersaudara, pegawai
                'name' => 'Beasiswa Khusus Hafidz 30 Juz (Yayasan Alia)',
                'discount_percentage' => 100,
                'discount_amount' => 1850000,
                'fee_category_id' => 'FEE-001',
                'fee_name' => 'SPP Bulanan SMA Boarding',
                'period' => '2026/2027 (1 Tahun Penuh)',
                'approved_by' => 'KH. Abdullah Gymnastiar / Pimpinan Yayasan',
                'approval_status' => 'disetujui', // disetujui, diajukan, ditolak
                'notes' => 'Apresiasi santri mutqin 30 juz berprestasi MHQ Nasional.'
            ],
            [
                'id' => 'SCH-002',
                'code' => 'SCH-PRS-50',
                'student_id' => 'SIS-0002', // Siti Fatimah
                'type' => 'prestasi',
                'name' => 'Keringanan Prestasi Juara OSN Kimia',
                'discount_percentage' => 50,
                'discount_amount' => 925000,
                'fee_category_id' => 'FEE-001',
                'fee_name' => 'SPP Bulanan SMA Boarding',
                'period' => 'Semester Ganjil 2026/2027',
                'approved_by' => 'Kepala Sekolah SMA Islam Alia',
                'approval_status' => 'disetujui',
                'notes' => 'Peraih Medali Perak Olimpiade Sains Nasional tingkat Provinsi.'
            ],
            [
                'id' => 'SCH-003',
                'code' => 'SCH-YTM-75',
                'student_id' => 'SIS-0003', // Bilal Ramadhan
                'type' => 'yatim_dhuafa',
                'name' => 'Santunan Beasiswa Yatim Berprestasi',
                'discount_percentage' => 75,
                'discount_amount' => 1387500,
                'fee_category_id' => 'FEE-001',
                'fee_name' => 'SPP Bulanan SMA Boarding',
                'period' => '2026/2027 (1 Tahun Penuh)',
                'approved_by' => 'Divisi ZISWAF Yayasan Alia',
                'approval_status' => 'disetujui',
                'notes' => 'Didanai dari alokasi infaq dan zakat maal donatur Alia Care.'
            ],
            [
                'id' => 'SCH-004',
                'code' => 'SCH-SDR-20',
                'student_id' => 'SIS-0007', // Rayhan Pratama
                'type' => 'bersaudara',
                'name' => 'Diskon Saudara Kandung (Sibling Discount)',
                'discount_percentage' => 20,
                'discount_amount' => 370000,
                'fee_category_id' => 'FEE-001',
                'fee_name' => 'SPP Bulanan SMA Boarding',
                'period' => '2026/2027',
                'approved_by' => 'Bendahara Utama Sekolah',
                'approval_status' => 'disetujui',
                'notes' => 'Adik kandung bersekolah di SMP Islam Alia (Muhammad Rayhan).'
            ],
            [
                'id' => 'SCH-005',
                'code' => 'SCH-THF-10',
                'student_id' => 'SIS-0005', // Muhammad Rayhan
                'type' => 'tahfidz',
                'name' => 'Beasiswa Akselerasi Tahfidz 10 Juz',
                'discount_percentage' => 30,
                'discount_amount' => 495000,
                'fee_category_id' => 'FEE-002',
                'fee_name' => 'SPP Bulanan SMP Boarding',
                'period' => 'Semester Ganjil 2026/2027',
                'approved_by' => 'Mudir Pesantren Alia',
                'approval_status' => 'diajukan',
                'notes' => 'Pengajuan berkas tasmi sekali duduk 5 juz sedang diverifikasi.'
            ]
        ];

        // 17. Presensi Pegawai & Guru (Staff Attendance)
        $this->staffAttendance = [
            [
                'id' => 'ATT-STF-001',
                'teacher_id' => 'GUR-001',
                'name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'nip' => '1985102010011001',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => '06:45',
                'check_out' => '15:30',
                'status' => 'hadir', // hadir, terlambat, izin, sakit, alpa
                'late_minutes' => 0,
                'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                'device' => 'Terminal Biomertik 01',
                'notes' => 'Tepat waktu, memimpin apel pembina yayasan.'
            ],
            [
                'id' => 'ATT-STF-002',
                'teacher_id' => 'GUR-002',
                'name' => 'Aisyah Salsabila, S.Pd.I.',
                'nip' => '1985112010011002',
                'unit_id' => 'UNIT-SMP',
                'date' => '2026-11-09',
                'check_in' => '06:40',
                'check_out' => '15:15',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                'device' => 'Terminal Biomertik 02',
                'notes' => 'Tepat waktu, halaqah subuh akhwat.'
            ],
            [
                'id' => 'ATT-STF-003',
                'teacher_id' => 'GUR-003',
                'name' => 'Salman Al-Farisi, S.Sos.',
                'nip' => '1985122010011003',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => '06:50',
                'check_out' => '16:00',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                'device' => 'Terminal Biomertik 01',
                'notes' => 'Tepat waktu, mudir asrama siaga santri.'
            ],
            [
                'id' => 'ATT-STF-004',
                'teacher_id' => 'GUR-004',
                'name' => 'Rahmat Hidayat, M.Sc.',
                'nip' => '1985132010011004',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => '07:18',
                'check_out' => '15:35',
                'status' => 'terlambat',
                'late_minutes' => 18,
                'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                'device' => 'Terminal Biomertik 01',
                'notes' => 'Kendala lalu lintas tol Becakayu (konfirmasi WA).'
            ],
            [
                'id' => 'ATT-STF-005',
                'teacher_id' => 'GUR-005',
                'name' => 'Khadijah Nurul Fikri, S.Pd.',
                'nip' => '1985142010011005',
                'unit_id' => 'UNIT-SD',
                'date' => '2026-11-09',
                'check_in' => '06:48',
                'check_out' => '14:45',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Kampus Terpadu SD Islam Alia',
                'device' => 'Terminal Biomertik SD',
                'notes' => 'Hadir mengajar Bahasa Inggris dasar.'
            ],
            [
                'id' => 'ATT-STF-006',
                'teacher_id' => 'GUR-006',
                'name' => 'Budi Santoso, S.Kom.',
                'nip' => '1985152010011006',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => '07:05',
                'check_out' => '15:40',
                'status' => 'terlambat',
                'late_minutes' => 5,
                'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                'device' => 'Terminal Biomertik 01',
                'notes' => 'Terlambat 5 menit, persiapan lab informatika.'
            ],
            [
                'id' => 'ATT-STF-007',
                'teacher_id' => 'GUR-007',
                'name' => 'Nurul Hasanah, M.Pd.',
                'nip' => '1985162010011007',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => null,
                'check_out' => null,
                'status' => 'izin',
                'late_minutes' => 0,
                'location' => '-',
                'device' => 'Form Izin HRIS',
                'notes' => 'Izin menghadiri seminar PPG di Kemdikbud (Surat Terlampir).'
            ],
            [
                'id' => 'ATT-STF-008',
                'teacher_id' => 'GUR-008',
                'name' => 'Irfan Maulana, S.Pd.',
                'nip' => '1985172010011008',
                'unit_id' => 'UNIT-SMP',
                'date' => '2026-11-09',
                'check_in' => '06:35',
                'check_out' => '15:20',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Lapangan Olahraga Alia',
                'device' => 'Terminal Biomertik 02',
                'notes' => 'Memimpin senam kesegaran jasmani santri.'
            ],
            [
                'id' => 'ATT-STF-009',
                'teacher_id' => 'GUR-009',
                'name' => 'Drs. H. Mulyadi, M.Pd.',
                'nip' => '1985182010011009',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => '06:52',
                'check_out' => '16:10',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Kampus Asrama Alia (Fingerprint/Face)',
                'device' => 'Terminal Biomertik 01',
                'notes' => 'Kepala SMA memantau kegiatan KBM.'
            ],
            [
                'id' => 'ATT-STF-010',
                'teacher_id' => 'GUR-010',
                'name' => 'Hj. Nurul Hidayati, S.E.',
                'nip' => '1985192010011010',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'check_in' => '06:55',
                'check_out' => '15:30',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Gedung Rektorat Lt 1 (Keuangan)',
                'device' => 'Terminal Biomertik 01',
                'notes' => 'Hadir pembukuan kas bulanan.'
            ],
            [
                'id' => 'ATT-STF-011',
                'teacher_id' => 'GUR-011',
                'name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'nip' => '1985202010011011',
                'unit_id' => 'UNIT-SD',
                'date' => '2026-11-09',
                'check_in' => '06:42',
                'check_out' => '15:00',
                'status' => 'hadir',
                'late_minutes' => 0,
                'location' => 'Kampus Terpadu SD Islam Alia',
                'device' => 'Terminal Biomertik SD',
                'notes' => 'Kepala SD memimpin doa bersama pagi.'
            ],
            [
                'id' => 'ATT-STF-012',
                'teacher_id' => 'GUR-012',
                'name' => 'Ustazah Siti Aminah, M.Pd.',
                'nip' => '1985212010011012',
                'unit_id' => 'UNIT-SMP',
                'date' => '2026-11-09',
                'check_in' => null,
                'check_out' => null,
                'status' => 'sakit',
                'late_minutes' => 0,
                'location' => '-',
                'device' => 'Surat Dokter Terlampir',
                'notes' => 'Sakit demam & flu (surat istirahat dokter klinik).'
            ]
        ];

        // 18. Pengajuan Cuti & Izin Pegawai (Staff Leaves & Permissions)
        $this->staffLeaves = [
            [
                'id' => 'CUTI-001',
                'leave_number' => 'CT/ALIA/2026/XI/001',
                'teacher_id' => 'GUR-007', // Nurul Hasanah, M.Pd.
                'name' => 'Nurul Hasanah, M.Pd.',
                'nip' => '1985162010011007',
                'unit_id' => 'UNIT-SMA',
                'type' => 'dinas', // tahunan, sakit, melahirkan, umroh_haji, dinas, penting
                'type_label' => 'Izin Tugas Kedinasan (PPG Kemdikbud)',
                'start_date' => '2026-11-09',
                'end_date' => '2026-11-10',
                'duration_days' => 2,
                'reason' => 'Menghadiri Lokakarya Orientasi Program Pendidikan Profesi Guru (PPG) di Balai Diklat Kemdikbudristek.',
                'substitute_teacher_id' => 'GUR-006', // Budi Santoso
                'substitute_teacher_name' => 'Budi Santoso, S.Kom.',
                'attachment' => 'surat_tugas_kemdikbud_ppg.pdf',
                'status' => 'disetujui', // diajukan, disetujui, ditolak, selesai
                'approver_role' => 'Kepala Sekolah SMA',
                'approved_by' => 'Drs. H. Mulyadi, M.Pd.',
                'approved_at' => '2026-11-08 14:20:00',
                'approver_notes' => 'Disetujui. Tugas mengajar Fisika kelas XI MIPA dititipkan modul terstruktur.',
                'remaining_quota' => 12
            ],
            [
                'id' => 'CUTI-002',
                'leave_number' => 'CT/ALIA/2026/XI/002',
                'teacher_id' => 'GUR-012', // Siti Aminah
                'name' => 'Ustazah Siti Aminah, M.Pd.',
                'nip' => '1985212010011012',
                'unit_id' => 'UNIT-SMP',
                'type' => 'sakit',
                'type_label' => 'Cuti Sakit Rawat Jalan',
                'start_date' => '2026-11-09',
                'end_date' => '2026-11-11',
                'duration_days' => 3,
                'reason' => 'Gejala demam berdarah dan flu berat, dianjurkan dokter klinik beristirahat total.',
                'substitute_teacher_id' => 'GUR-002', // Aisyah Salsabila
                'substitute_teacher_name' => 'Aisyah Salsabila, S.Pd.I.',
                'attachment' => 'surat_keterangan_sakit_klinik.pdf',
                'status' => 'disetujui',
                'approver_role' => 'Ketua Yayasan',
                'approved_by' => 'Dr. H. Zulkifli, Lc., M.A.',
                'approved_at' => '2026-11-09 07:30:00',
                'approver_notes' => 'Syafakillah, semoga lekas pulih dan sehat wal afiat.',
                'remaining_quota' => 12
            ],
            [
                'id' => 'CUTI-003',
                'leave_number' => 'CT/ALIA/2026/XI/003',
                'teacher_id' => 'GUR-004', // Rahmat Hidayat
                'name' => 'Rahmat Hidayat, M.Sc.',
                'nip' => '1985132010011004',
                'unit_id' => 'UNIT-SMA',
                'type' => 'umroh_haji',
                'type_label' => 'Cuti Ibadah Umroh',
                'start_date' => '2026-11-20',
                'end_date' => '2026-12-04',
                'duration_days' => 14,
                'reason' => 'Menunaikan ibadah Umroh ke Tanah Suci Makkah dan Madinah bersama keluarga.',
                'substitute_teacher_id' => 'GUR-006',
                'substitute_teacher_name' => 'Budi Santoso, S.Kom.',
                'attachment' => 'tiket_dan_visa_umroh_2026.pdf',
                'status' => 'diajukan',
                'approver_role' => 'Kepala Sekolah SMA & Yayasan',
                'approved_by' => null,
                'approved_at' => null,
                'approver_notes' => 'Menunggu verifikasi jadwal ujian akhir semester ganjil.',
                'remaining_quota' => 12
            ],
            [
                'id' => 'CUTI-004',
                'leave_number' => 'CT/ALIA/2026/XI/004',
                'teacher_id' => 'GUR-008', // Irfan Maulana
                'name' => 'Irfan Maulana, S.Pd.',
                'nip' => '1985172010011008',
                'unit_id' => 'UNIT-SMP',
                'type' => 'tahunan',
                'type_label' => 'Cuti Tahunan',
                'start_date' => '2026-11-16',
                'end_date' => '2026-11-18',
                'duration_days' => 3,
                'reason' => 'Acara pernikahan adik kandung di Klaten, Jawa Tengah.',
                'substitute_teacher_id' => 'GUR-003',
                'substitute_teacher_name' => 'Salman Al-Farisi, S.Sos.',
                'attachment' => 'undangan_pernikahan.pdf',
                'status' => 'diajukan',
                'approver_role' => 'Kepala Sekolah SMP',
                'approved_by' => null,
                'approved_at' => null,
                'approver_notes' => 'Sedang dalam review beban ajar guru pengganti.',
                'remaining_quota' => 9
            ],
            [
                'id' => 'CUTI-005',
                'leave_number' => 'CT/ALIA/2026/XI/005',
                'teacher_id' => 'GUR-005', // Khadijah Nurul Fikri
                'name' => 'Khadijah Nurul Fikri, S.Pd.',
                'nip' => '1985142010011005',
                'unit_id' => 'UNIT-SD',
                'type' => 'melahirkan',
                'type_label' => 'Cuti Bersalin / Melahirkan',
                'start_date' => '2026-12-01',
                'end_date' => '2027-02-28',
                'duration_days' => 90,
                'reason' => 'Masa persiapan persalinan anak kedua (HPL awal Desember 2026) dan pemulihan pasca melahirkan.',
                'substitute_teacher_id' => 'GUR-011',
                'substitute_teacher_name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'attachment' => 'surat_keterangan_hpl_spog.pdf',
                'status' => 'disetujui',
                'approver_role' => 'Kepala SD & Yayasan',
                'approved_by' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'approved_at' => '2026-11-05 10:15:00',
                'approver_notes' => 'Hak cuti melahirkan 3 bulan disetujui penuh dengan tunjangan pokok tetap.',
                'remaining_quota' => 12
            ]
        ];

        // 19. Lembur Pegawai & Surat Perintah Kerja Lembur (Staff Overtimes / SPKL)
        $this->staffOvertimes = [
            [
                'id' => 'LMBR-001',
                'spkl_number' => 'SPKL/ALIA/2026/XI/001',
                'teacher_id' => 'GUR-006', // Budi Santoso
                'name' => 'Budi Santoso, S.Kom.',
                'nip' => '1985152010011006',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-07',
                'start_time' => '16:00',
                'end_time' => '20:30',
                'duration_hours' => 4.5,
                'hourly_rate' => 35000,
                'compensation_amount' => 157500,
                'activity_category' => 'it_lab', // it_lab, ppdb, asrama, ujian, sarpras, acara
                'activity_category_label' => 'Instalasi Jaringan & Maintenance Lab Komputer',
                'task_description' => 'Konfigurasi server CBT lokal dan pengecekan 40 client PC untuk simulasi Asesmen Nasional Berbasis Komputer (ANBK).',
                'status' => 'disetujui', // diajukan, disetujui, ditolak, dibayarkan
                'approver_role' => 'Kepala Sekolah SMA',
                'approved_by' => 'Drs. H. Mulyadi, M.Pd.',
                'approved_at' => '2026-11-06 13:00:00',
                'approver_notes' => 'Lembur teknis disetujui, harap koordinasi dengan pengawas sarpras.'
            ],
            [
                'id' => 'LMBR-002',
                'spkl_number' => 'SPKL/ALIA/2026/XI/002',
                'teacher_id' => 'GUR-003', // Salman Al-Farisi
                'name' => 'Salman Al-Farisi, S.Sos.',
                'nip' => '1985122010011003',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-08',
                'start_time' => '20:00',
                'end_time' => '04:00',
                'duration_hours' => 8.0,
                'hourly_rate' => 40000, // Tarif malam / asrama
                'compensation_amount' => 320000,
                'activity_category' => 'asrama',
                'activity_category_label' => 'Piket Pengawasan & Qiyamul Lail Asrama Santri',
                'task_description' => 'Piket malam khusus pengawasan intensif ujian tahfidz santri mukim dan koordinasi pos jaga keamanan malam.',
                'status' => 'dibayarkan',
                'approver_role' => 'Mudir Pesantren & Bendahara',
                'approved_by' => 'Dr. H. Zulkifli, Lc., M.A.',
                'approved_at' => '2026-11-07 10:30:00',
                'approver_notes' => 'Honor lembur malam telah dibukukan dalam voucher kas operasional.'
            ],
            [
                'id' => 'LMBR-003',
                'spkl_number' => 'SPKL/ALIA/2026/XI/003',
                'teacher_id' => 'GUR-010', // Nurul Hidayati (Keuangan)
                'name' => 'Hj. Nurul Hidayati, S.E.',
                'nip' => '1985192010011010',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-09',
                'start_time' => '16:00',
                'end_time' => '19:30',
                'duration_hours' => 3.5,
                'hourly_rate' => 35000,
                'compensation_amount' => 122500,
                'activity_category' => 'ppdb',
                'activity_category_label' => 'Rekonsiliasi Keuangan PPDB Gelombang 1',
                'task_description' => 'Rekonsiliasi bukti bayar daftar ulang calon santri baru dan verifikasi virtual account bank muamalat.',
                'status' => 'disetujui',
                'approver_role' => 'Ketua Yayasan',
                'approved_by' => 'Dr. H. Zulkifli, Lc., M.A.',
                'approved_at' => '2026-11-09 11:15:00',
                'approver_notes' => 'Disetujui untuk percepatan rilis daftar peserta daftar ulang.'
            ],
            [
                'id' => 'LMBR-004',
                'spkl_number' => 'SPKL/ALIA/2026/XI/004',
                'teacher_id' => 'GUR-008', // Irfan Maulana
                'name' => 'Irfan Maulana, S.Pd.',
                'nip' => '1985172010011008',
                'unit_id' => 'UNIT-SMP',
                'date' => '2026-11-14',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'duration_hours' => 4.0,
                'hourly_rate' => 35000,
                'compensation_amount' => 140000,
                'activity_category' => 'acara',
                'activity_category_label' => 'Pelatihan & Latihan Gabungan Silat Antar Ponpes',
                'task_description' => 'Pendampingan santri kontingen Tapak Suci Alia dalam ajang latihan tanding se-DKI Jakarta di hari Sabtu.',
                'status' => 'diajukan',
                'approver_role' => 'Kepala SMP & Kesiswaan',
                'approved_by' => null,
                'approved_at' => null,
                'approver_notes' => 'Menunggu persetujuan alokasi dana kesiswaan.'
            ],
            [
                'id' => 'LMBR-005',
                'spkl_number' => 'SPKL/ALIA/2026/XI/005',
                'teacher_id' => 'GUR-004', // Rahmat Hidayat
                'name' => 'Rahmat Hidayat, M.Sc.',
                'nip' => '1985132010011004',
                'unit_id' => 'UNIT-SMA',
                'date' => '2026-11-12',
                'start_time' => '16:00',
                'end_time' => '18:30',
                'duration_hours' => 2.5,
                'hourly_rate' => 35000,
                'compensation_amount' => 87500,
                'activity_category' => 'ujian',
                'activity_category_label' => 'Klinik Remedial & Bimbingan OSN Matematika',
                'task_description' => 'Pembinaan intensif sore hari untuk santri delegasi Olimpiade Sains Nasional (OSN) bidang Matematika.',
                'status' => 'diajukan',
                'approver_role' => 'Kepala Sekolah SMA',
                'approved_by' => null,
                'approved_at' => null,
                'approver_notes' => 'Pengajuan jadwal bimbingan sore.'
            ]
        ];

        // 20. Evaluasi Kinerja Pegawai & Guru (Staff Performance Evaluations / PKG)
        $this->staffEvaluations = [
            [
                'id' => 'EVL-001',
                'doc_number' => 'PKG/ALIA/2026/GUR-001',
                'teacher_id' => 'GUR-001', // Dr. H. Zulkifli
                'name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'nip' => '1985102010011001',
                'position' => 'Ketua Yayasan / Dosen Pembina',
                'unit_id' => 'UNIT-SMA',
                'period' => 'Tahun Ajaran 2026/2027 Ganjil',
                'evaluator_name' => 'Dewan Pembina Yayasan Alia',
                'evaluation_date' => '2026-11-05',
                'pedagogik_score' => 96,
                'profesional_score' => 98,
                'kepribadian_score' => 98,
                'sosial_score' => 95,
                'final_score' => 96.8,
                'predicate' => 'Amat Baik (A)',
                'strengths' => 'Kepemimpinan visioner, integrasi kurikulum diniyah dengan tsaqafah Islamiyah yang mendalam, teladan ibadah.',
                'recommendations' => 'Mempertahankan mentoring manajerial pimpinan unit di bawah naungan yayasan.',
                'status' => 'final'
            ],
            [
                'id' => 'EVL-002',
                'doc_number' => 'PKG/ALIA/2026/GUR-002',
                'teacher_id' => 'GUR-002', // Aisyah Salsabila
                'name' => 'Aisyah Salsabila, S.Pd.I.',
                'nip' => '1985112010011002',
                'position' => 'Koordinator Tahfidz Akhwat',
                'unit_id' => 'UNIT-SMP',
                'period' => 'Tahun Ajaran 2026/2027 Ganjil',
                'evaluator_name' => 'Ustazah Siti Aminah, M.Pd.',
                'evaluation_date' => '2026-11-04',
                'pedagogik_score' => 94,
                'profesional_score' => 96,
                'kepribadian_score' => 95,
                'sosial_score' => 92,
                'final_score' => 94.3,
                'predicate' => 'Amat Baik (A)',
                'strengths' => 'Metodologi tasmi hafalan sangat teliti, qari’ah mutqin, interaksi ramah dan penuh keibuan terhadap santriwati.',
                'recommendations' => 'Mengembangkan modul talaqqi berirama untuk santri pemula jenjang SMP.',
                'status' => 'final'
            ],
            [
                'id' => 'EVL-003',
                'doc_number' => 'PKG/ALIA/2026/GUR-004',
                'teacher_id' => 'GUR-004', // Rahmat Hidayat
                'name' => 'Rahmat Hidayat, M.Sc.',
                'nip' => '1985132010011004',
                'position' => 'Waka Kurikulum SMA',
                'unit_id' => 'UNIT-SMA',
                'period' => 'Tahun Ajaran 2026/2027 Ganjil',
                'evaluator_name' => 'Drs. H. Mulyadi, M.Pd.',
                'evaluation_date' => '2026-11-03',
                'pedagogik_score' => 90,
                'profesional_score' => 94,
                'kepribadian_score' => 88,
                'sosial_score' => 86,
                'final_score' => 89.5,
                'predicate' => 'Baik (B)',
                'strengths' => 'Penguasaan materi Matematika tingkat lanjut luar biasa, pembimbingan tim olimpiade sains efektif.',
                'recommendations' => 'Tingkatkan ketepatan waktu hadir pagi apel dan variasi pembelajaran berdiferensiasi di kelas.',
                'status' => 'final'
            ],
            [
                'id' => 'EVL-004',
                'doc_number' => 'PKG/ALIA/2026/GUR-006',
                'teacher_id' => 'GUR-006', // Budi Santoso
                'name' => 'Budi Santoso, S.Kom.',
                'nip' => '1985152010011006',
                'position' => 'Kepala Lab Komputer',
                'unit_id' => 'UNIT-SMA',
                'period' => 'Tahun Ajaran 2026/2027 Ganjil',
                'evaluator_name' => 'Drs. H. Mulyadi, M.Pd.',
                'evaluation_date' => '2026-11-05',
                'pedagogik_score' => 86,
                'profesional_score' => 92,
                'kepribadian_score' => 88,
                'sosial_score' => 85,
                'final_score' => 87.8,
                'predicate' => 'Baik (B)',
                'strengths' => 'Sangat cekatan dalam menyelesaikan kendala teknis jaringan CBT dan sistem informasi lab.',
                'recommendations' => 'Tingkatkan dokumentasi modul ajar praktikum koding untuk santri pemula.',
                'status' => 'final'
            ],
            [
                'id' => 'EVL-005',
                'doc_number' => 'PKG/ALIA/2026/GUR-008',
                'teacher_id' => 'GUR-008', // Irfan Maulana
                'name' => 'Irfan Maulana, S.Pd.',
                'nip' => '1985172010011008',
                'position' => 'Pembina Ekstrakurikuler Silat',
                'unit_id' => 'UNIT-SMP',
                'period' => 'Tahun Ajaran 2026/2027 Ganjil',
                'evaluator_name' => 'Ustazah Siti Aminah, M.Pd.',
                'evaluation_date' => '2026-11-02',
                'pedagogik_score' => 84,
                'profesional_score' => 88,
                'kepribadian_score' => 86,
                'sosial_score' => 90,
                'final_score' => 87.0,
                'predicate' => 'Baik (B)',
                'strengths' => 'Hubungan antarpribadi sangat akrab dengan santri, disiplin dalam pembinaan fisik dan silat.',
                'recommendations' => 'Penguatan administrasi rancangan modul ajar PJOK kurikulum merdeka.',
                'status' => 'final'
            ]
        ];

        // 21. Penggajian & Simulasi Payroll Pegawai (Staff Payrolls)
        $this->staffPayrolls = [
            [
                'id' => 'PAY-2026-11-001',
                'slip_number' => 'SLIP/ALIA/2026/XI/001',
                'teacher_id' => 'GUR-001', // Dr. H. Zulkifli
                'name' => 'Dr. H. Zulkifli, Lc., M.A.',
                'nip' => '1985102010011001',
                'position' => 'Ketua Yayasan / Dosen Pembina',
                'employment_status' => 'Guru Tetap Yayasan (GTY)',
                'unit_id' => 'UNIT-SMA',
                'month' => '2026-11',
                'period_label' => 'November 2026',
                'bank_name' => 'Bank Muamalat',
                'account_number' => '301-0082-192',
                'basic_salary' => 7500000,
                'position_allowance' => 3000000,
                'functional_allowance' => 1500000,
                'family_allowance' => 800000,
                'transport_meal_allowance' => 1200000,
                'overtime_pay' => 0,
                'bonus_incentive' => 500000,
                'gross_salary' => 14500000,
                'bpjs_kesehatan' => 145000,
                'bpjs_ketenagakerjaan' => 290000,
                'infaq_sedekah' => 250000,
                'loan_deduction' => 0,
                'late_deduction' => 0,
                'total_deductions' => 685000,
                'net_salary' => 13815000,
                'payment_date' => '2026-11-25',
                'status' => 'dibayarkan', // draft, disetujui, dibayarkan
                'notes' => 'Gaji pokok dan tunjangan kehormatan ketua dewan pembina.'
            ],
            [
                'id' => 'PAY-2026-11-002',
                'slip_number' => 'SLIP/ALIA/2026/XI/002',
                'teacher_id' => 'GUR-002', // Aisyah Salsabila
                'name' => 'Aisyah Salsabila, S.Pd.I.',
                'nip' => '1985112010011002',
                'position' => 'Koordinator Tahfidz Akhwat',
                'employment_status' => 'Guru Tetap Yayasan (GTY)',
                'unit_id' => 'UNIT-SMP',
                'month' => '2026-11',
                'period_label' => 'November 2026',
                'bank_name' => 'BSI (Bank Syariah Indonesia)',
                'account_number' => '712-4458-901',
                'basic_salary' => 4500000,
                'position_allowance' => 1200000,
                'functional_allowance' => 1000000,
                'family_allowance' => 450000,
                'transport_meal_allowance' => 850000,
                'overtime_pay' => 0,
                'bonus_incentive' => 300000,
                'gross_salary' => 8300000,
                'bpjs_kesehatan' => 83000,
                'bpjs_ketenagakerjaan' => 166000,
                'infaq_sedekah' => 100000,
                'loan_deduction' => 0,
                'late_deduction' => 0,
                'total_deductions' => 349000,
                'net_salary' => 7951000,
                'payment_date' => '2026-11-25',
                'status' => 'dibayarkan',
                'notes' => 'Tunjangan koordinator tahfidz dan sertifikasi quranic.'
            ],
            [
                'id' => 'PAY-2026-11-003',
                'slip_number' => 'SLIP/ALIA/2026/XI/003',
                'teacher_id' => 'GUR-003', // Salman Al-Farisi
                'name' => 'Salman Al-Farisi, S.Sos.',
                'nip' => '1985122010011003',
                'position' => 'Mudir Asrama Santri',
                'employment_status' => 'Guru Tetap Yayasan (GTY)',
                'unit_id' => 'UNIT-SMA',
                'month' => '2026-11',
                'period_label' => 'November 2026',
                'bank_name' => 'BSI (Bank Syariah Indonesia)',
                'account_number' => '718-9902-331',
                'basic_salary' => 4800000,
                'position_allowance' => 1500000,
                'functional_allowance' => 900000,
                'family_allowance' => 500000,
                'transport_meal_allowance' => 900000,
                'overtime_pay' => 320000, // dari SPKL-002
                'bonus_incentive' => 250000,
                'gross_salary' => 9170000,
                'bpjs_kesehatan' => 91700,
                'bpjs_ketenagakerjaan' => 183400,
                'infaq_sedekah' => 100000,
                'loan_deduction' => 0,
                'late_deduction' => 0,
                'total_deductions' => 375100,
                'net_salary' => 8794900,
                'payment_date' => '2026-11-25',
                'status' => 'dibayarkan',
                'notes' => 'Termasuk honor piket malam asrama santri.'
            ],
            [
                'id' => 'PAY-2026-11-004',
                'slip_number' => 'SLIP/ALIA/2026/XI/004',
                'teacher_id' => 'GUR-004', // Rahmat Hidayat
                'name' => 'Rahmat Hidayat, M.Sc.',
                'nip' => '1985132010011004',
                'position' => 'Waka Kurikulum SMA',
                'employment_status' => 'Guru Tetap Yayasan (GTY)',
                'unit_id' => 'UNIT-SMA',
                'month' => '2026-11',
                'period_label' => 'November 2026',
                'bank_name' => 'Bank Mandiri Syariah',
                'account_number' => '140-00-9812-441',
                'basic_salary' => 5000000,
                'position_allowance' => 1800000,
                'functional_allowance' => 1100000,
                'family_allowance' => 500000,
                'transport_meal_allowance' => 900000,
                'overtime_pay' => 87500,
                'bonus_incentive' => 0,
                'gross_salary' => 9387500,
                'bpjs_kesehatan' => 93875,
                'bpjs_ketenagakerjaan' => 187750,
                'infaq_sedekah' => 100000,
                'loan_deduction' => 500000, // Angsuran koperasi guru
                'late_deduction' => 25000, // Denda terlambat presensi
                'total_deductions' => 906625,
                'net_salary' => 8480875,
                'payment_date' => '2026-11-25',
                'status' => 'disetujui',
                'notes' => 'Tunjangan wakil kepala sekolah bidang kurikulum.'
            ],
            [
                'id' => 'PAY-2026-11-005',
                'slip_number' => 'SLIP/ALIA/2026/XI/005',
                'teacher_id' => 'GUR-006', // Budi Santoso
                'name' => 'Budi Santoso, S.Kom.',
                'nip' => '1985152010011006',
                'position' => 'Kepala Lab Komputer',
                'employment_status' => 'Guru Kontrak',
                'unit_id' => 'UNIT-SMA',
                'month' => '2026-11',
                'period_label' => 'November 2026',
                'bank_name' => 'BCA Syariah',
                'account_number' => '029-881-209',
                'basic_salary' => 3800000,
                'position_allowance' => 800000,
                'functional_allowance' => 600000,
                'family_allowance' => 0,
                'transport_meal_allowance' => 750000,
                'overtime_pay' => 157500, // dari SPKL-001
                'bonus_incentive' => 200000,
                'gross_salary' => 6307500,
                'bpjs_kesehatan' => 63075,
                'bpjs_ketenagakerjaan' => 126150,
                'infaq_sedekah' => 50000,
                'loan_deduction' => 0,
                'late_deduction' => 10000,
                'total_deductions' => 249225,
                'net_salary' => 6058275,
                'payment_date' => '2026-11-25',
                'status' => 'disetujui',
                'notes' => 'Honor lembur instalasi lab ANBK terhitung.'
            ],
            [
                'id' => 'PAY-2026-11-006',
                'slip_number' => 'SLIP/ALIA/2026/XI/006',
                'teacher_id' => 'GUR-010', // Nurul Hidayati (Keuangan)
                'name' => 'Hj. Nurul Hidayati, S.E.',
                'nip' => '1985192010011010',
                'position' => 'Bendahara Utama Sekolah',
                'employment_status' => 'Tenaga Kependidikan',
                'unit_id' => 'UNIT-SMA',
                'month' => '2026-11',
                'period_label' => 'November 2026',
                'bank_name' => 'BSI (Bank Syariah Indonesia)',
                'account_number' => '711-2033-918',
                'basic_salary' => 4600000,
                'position_allowance' => 1400000,
                'functional_allowance' => 800000,
                'family_allowance' => 450000,
                'transport_meal_allowance' => 850000,
                'overtime_pay' => 122500,
                'bonus_incentive' => 300000,
                'gross_salary' => 8522500,
                'bpjs_kesehatan' => 85225,
                'bpjs_ketenagakerjaan' => 170450,
                'infaq_sedekah' => 100000,
                'loan_deduction' => 0,
                'late_deduction' => 0,
                'total_deductions' => 355675,
                'net_salary' => 8166825,
                'payment_date' => '2026-11-25',
                'status' => 'dibayarkan',
                'notes' => 'Penggajian staf tenaga kependidikan tata usaha keuangan.'
            ]
        ];

        // 22. Pengembangan Kompetensi Pegawai & Pelatihan (Staff Trainings & Competency Development)
        $this->staffTrainings = [
            [
                'id' => 'TRN-001',
                'doc_number' => 'DIKLAT/ALIA/2026/001',
                'teacher_id' => 'GUR-007', // Nurul Hasanah, M.Pd.
                'name' => 'Nurul Hasanah, M.Pd.',
                'nip' => '1985162010011007',
                'unit_id' => 'UNIT-SMA',
                'title' => 'Program Sertifikasi Guru Profesional (PPG Dalam Jabatan)',
                'category' => 'sertifikasi', // sertifikasi, workshop, seminar, inhouse, kurikulum
                'category_label' => 'Sertifikasi Profesi Pendidik',
                'organizer' => 'Balai Diklat Kemdikbudristek & Universitas Negeri Jakarta',
                'location' => 'Jakarta (Hybrid: Daring & Luring)',
                'start_date' => '2026-11-09',
                'end_date' => '2026-12-20',
                'duration_hours' => 64,
                'cost' => 0, // Dibiayai APBN / Beasiswa Kemdikbud
                'cost_source' => 'Beasiswa Pemerintah (Gratis)',
                'certificate_number' => 'CERT-PPG-2026-0982',
                'status' => 'berjalan', // terdaftar, berjalan, selesai
                'description' => 'Pendalaman materi pedagogik dan bidang studi Fisika SMA berbasis kurikulum merdeka.',
                'output_target' => 'Sertifikat Pendidik (Gr.) dan pemenuhan kualifikasi guru profesional yayasan.'
            ],
            [
                'id' => 'TRN-002',
                'doc_number' => 'DIKLAT/ALIA/2026/002',
                'teacher_id' => 'GUR-002', // Aisyah Salsabila
                'name' => 'Aisyah Salsabila, S.Pd.I.',
                'nip' => '1985112010011002',
                'unit_id' => 'UNIT-SMP',
                'title' => 'Daurah Sanad Tajwid Matan Al-Jazariyah & Tuhfatul Athfal',
                'category' => 'workshop',
                'category_label' => 'Workshop Sanad & Tahfidz Quran',
                'organizer' => 'Lembaga Pengembangan Tahfidz Quran (LPTQ) Markaz Al-Qur\'an',
                'location' => 'Hotel Balairung Jakarta',
                'start_date' => '2026-10-15',
                'end_date' => '2026-10-18',
                'duration_hours' => 32,
                'cost' => 1750000,
                'cost_source' => 'Anggaran Yayasan Alia (HRD)',
                'certificate_number' => 'SANAD-JZR-2026-441',
                'status' => 'selesai',
                'description' => 'Pelatihan intensif tajwid bersanad muttashil sampai ke Rasulullah SAW untuk pengajar tahfidz.',
                'output_target' => 'Ijazah Sanad Matan Jazariyah dan kurikulum tahsin mutqin santriwati.'
            ],
            [
                'id' => 'TRN-003',
                'doc_number' => 'DIKLAT/ALIA/2026/003',
                'teacher_id' => 'GUR-006', // Budi Santoso
                'name' => 'Budi Santoso, S.Kom.',
                'nip' => '1985152010011006',
                'unit_id' => 'UNIT-SMA',
                'title' => 'Workshop AI & Machine Learning dalam Pembelajaran Koding Sekolah Menengah',
                'category' => 'workshop',
                'category_label' => 'Teknologi Informasi & AI Education',
                'organizer' => 'Google for Education & Asosiasi Guru TIK Indonesia (AGTIK)',
                'location' => 'Auditorium Kampus UI Depok',
                'start_date' => '2026-10-24',
                'end_date' => '2026-10-25',
                'duration_hours' => 16,
                'cost' => 750000,
                'cost_source' => 'Anggaran Yayasan Alia (HRD)',
                'certificate_number' => 'GFE-AI-2026-8819',
                'status' => 'selesai',
                'description' => 'Eksplorasi modul kecerdasan buatan (GenAI), prompt engineering edukatif, dan koding Python untuk santri.',
                'output_target' => 'Modul ajar ekstrakurikuler koding & robotika cerdas Alia Islamic School.'
            ],
            [
                'id' => 'TRN-004',
                'doc_number' => 'DIKLAT/ALIA/2026/004',
                'teacher_id' => 'GUR-004', // Rahmat Hidayat
                'name' => 'Rahmat Hidayat, M.Sc.',
                'nip' => '1985132010011004',
                'unit_id' => 'UNIT-SMA',
                'title' => 'Bimbingan Teknis Penyusunan Asesmen Pembelajaran Berdiferensiasi Kurikulum Merdeka',
                'category' => 'kurikulum',
                'category_label' => 'Pengembangan Kurikulum & Asesmen',
                'organizer' => 'Musyawarah Kerja Kepala Sekolah (MKKS) Swasta Jakarta Timur',
                'location' => 'Aula BPMP DKI Jakarta',
                'start_date' => '2026-11-18',
                'end_date' => '2026-11-19',
                'duration_hours' => 18,
                'cost' => 500000,
                'cost_source' => 'Anggaran Unit SMA Alia',
                'certificate_number' => null,
                'status' => 'terdaftar',
                'description' => 'Strategi merancang asesmen diagnostik, formatif adaptif, dan rubrik portofolio capaian pembelajaran.',
                'output_target' => 'Bank instrumen asesmen berdiferensiasi SMA Islam Alia.'
            ],
            [
                'id' => 'TRN-005',
                'doc_number' => 'DIKLAT/ALIA/2026/005',
                'teacher_id' => 'GUR-011', // Ust. Muhammad Ihsan
                'name' => 'Ust. Muhammad Ihsan, S.Pd.I.',
                'nip' => '1985202010011011',
                'unit_id' => 'UNIT-SD',
                'title' => 'In-House Training (IHT): Penguatan Karakter Profil Pelajar Pancasila Rahmatan Lil Alamin',
                'category' => 'inhouse',
                'category_label' => 'In-House Training (IHT) Yayasan',
                'organizer' => 'Divisi Penjaminan Mutu & HRD Yayasan Alia',
                'location' => 'Masjid Kampus Terpadu Alia',
                'start_date' => '2026-10-05',
                'end_date' => '2026-10-06',
                'duration_hours' => 16,
                'cost' => 350000,
                'cost_source' => 'Anggaran Yayasan Alia (HRD)',
                'certificate_number' => 'IHT-P3RA-2026-012',
                'status' => 'selesai',
                'description' => 'Pelatihan internal integrasi nilai adab nabawiyah ke dalam tema P5 Kurikulum Merdeka jenjang SD/SMP.',
                'output_target' => 'Buku panduan proyek penguatan karakter islami terpadu Alia.'
            ]
        ];

        // 23. Registrasi Sarana & Prasarana Fisik Kampus (Facility & Physical Assets)
        $this->facilityAssets = [
            [
                'id' => 'FCL-001',
                'asset_code' => 'AST/TNH/ALIA/001',
                'name' => 'Lahan Kampus Asrama & Pendidikan Terpadu',
                'category' => 'tanah', // tanah, gedung, lapangan, ruangan, utilitas
                'category_label' => 'Tanah & Lahan Kampus',
                'location' => 'Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur',
                'area_size' => '12.500 m²',
                'capacity' => 1500,
                'ownership_status' => 'Wakaf Bersertifikat (SHM)',
                'procurement_year' => '2015',
                'asset_value' => 37500000000,
                'condition' => 'sangat_baik', // sangat_baik, baik, rusak_ringan, rusak_berat
                'person_in_charge' => 'Dr. H. Zulkifli, Lc., M.A.',
                'unit_id' => 'UNIT-SMA',
                'status' => 'aktif',
                'notes' => 'Lahan wakaf induk sarana asrama putra/putri, masjid, dan gedung sekolah terpadu.'
            ],
            [
                'id' => 'FCL-002',
                'asset_code' => 'AST/GDG/ALIA/001',
                'name' => 'Gedung Utama Umar bin Khattab (SMA & Kantor)',
                'category' => 'gedung',
                'category_label' => 'Bangunan Gedung Bertingkat',
                'location' => 'Kampus Terpadu Alia - Sisi Timur',
                'area_size' => '2.400 m² (3 Lantai)',
                'capacity' => 350,
                'ownership_status' => 'Milik Yayasan',
                'procurement_year' => '2018',
                'asset_value' => 6200000000,
                'condition' => 'sangat_baik',
                'person_in_charge' => 'Drs. H. Mulyadi, M.Pd.',
                'unit_id' => 'UNIT-SMA',
                'status' => 'aktif',
                'notes' => 'Terdiri dari 8 ruang kelas SMA, ruang kepala sekolah, tata usaha, dan ruang guru.'
            ],
            [
                'id' => 'FCL-003',
                'asset_code' => 'AST/GDG/ALIA/002',
                'name' => 'Gedung Asrama Putra (Thalhah bin Ubaidillah)',
                'category' => 'gedung',
                'category_label' => 'Bangunan Gedung Bertingkat',
                'location' => 'Kampus Asrama Barat',
                'area_size' => '1.800 m² (3 Lantai)',
                'capacity' => 120,
                'ownership_status' => 'Milik Yayasan',
                'procurement_year' => '2019',
                'asset_value' => 4800000000,
                'condition' => 'baik',
                'person_in_charge' => 'Salman Al-Farisi, S.Sos.',
                'unit_id' => 'UNIT-SMA',
                'status' => 'aktif',
                'notes' => 'Asrama mukim santri putra, dilengkapi 20 kamar, kamar mandi dalam, dan ruang jemur.'
            ],
            [
                'id' => 'FCL-004',
                'asset_code' => 'AST/GDG/ALIA/003',
                'name' => 'Gedung Asrama Putri (Khadijah binti Khuwailid)',
                'category' => 'gedung',
                'category_label' => 'Bangunan Gedung Bertingkat',
                'location' => 'Kampus Asrama Sisi Utara (Khusus Akhwat)',
                'area_size' => '1.950 m² (3 Lantai)',
                'capacity' => 130,
                'ownership_status' => 'Milik Yayasan',
                'procurement_year' => '2020',
                'asset_value' => 5200000000,
                'condition' => 'sangat_baik',
                'person_in_charge' => 'Aisyah Salsabila, S.Pd.I.',
                'unit_id' => 'UNIT-SMP',
                'status' => 'aktif',
                'notes' => 'Asrama tertutup khusus putri dengan pos satpam wanita dan akses finger scan.'
            ],
            [
                'id' => 'FCL-005',
                'asset_code' => 'AST/LPG/ALIA/001',
                'name' => 'Lapangan Olahraga Multifungsi (Futsal, Basket, Voli)',
                'category' => 'lapangan',
                'category_label' => 'Sarana Lapangan Olahraga',
                'location' => 'Area Terbuka Tengah Kampus',
                'area_size' => '800 m²',
                'capacity' => 200,
                'ownership_status' => 'Milik Yayasan',
                'procurement_year' => '2019',
                'asset_value' => 450000000,
                'condition' => 'baik',
                'person_in_charge' => 'Irfan Maulana, S.Pd.',
                'unit_id' => 'UNIT-SMP',
                'status' => 'aktif',
                'notes' => 'Lantai vinyl interlock outdoor, ring basket hidrolik, dan jaring pengaman sekeliling.'
            ],
            [
                'id' => 'FCL-006',
                'asset_code' => 'AST/IBD/ALIA/001',
                'name' => 'Masjid Jami\' Kampus Alia Islamic School',
                'category' => 'gedung',
                'category_label' => 'Fasilitas Ibadah & Islamic Center',
                'location' => 'Jantung Utama Kampus',
                'area_size' => '950 m² (2 Lantai)',
                'capacity' => 800,
                'ownership_status' => 'Wakaf Penuh',
                'procurement_year' => '2017',
                'asset_value' => 3800000000,
                'condition' => 'sangat_baik',
                'person_in_charge' => 'Dr. H. Zulkifli, Lc., M.A.',
                'unit_id' => 'UNIT-SMA',
                'status' => 'aktif',
                'notes' => 'Pusat shalat berjamaah 5 waktu, kajian diniyah ba\'da subuh, tasmi Quran, dan aula lantai 2.'
            ],
            [
                'id' => 'FCL-007',
                'asset_code' => 'AST/LAB/ALIA/001',
                'name' => 'Laboratorium Komputer, Multimedia & Robotika',
                'category' => 'ruangan',
                'category_label' => 'Ruang Laboratorium & Praktikum',
                'location' => 'Gedung Ali bin Abi Thalib Lt 2',
                'area_size' => '120 m²',
                'capacity' => 40,
                'ownership_status' => 'Milik Yayasan',
                'procurement_year' => '2021',
                'asset_value' => 650000000,
                'condition' => 'baik',
                'person_in_charge' => 'Budi Santoso, S.Kom.',
                'unit_id' => 'UNIT-SMA',
                'status' => 'aktif',
                'notes' => 'Dilengkapi 40 unit PC Core i5, server CBT cadangan, router Mikrotik, dan proyektor laser.'
            ],
            [
                'id' => 'FCL-008',
                'asset_code' => 'AST/UTL/ALIA/001',
                'name' => 'Instalasi Gardu Listrik PLN & Genset Cadangan 100 kVA',
                'category' => 'utilitas',
                'category_label' => 'Utilitas & Sumber Daya',
                'location' => 'Area Belakang Asrama Putra',
                'area_size' => '36 m²',
                'capacity' => 100, // kVA
                'ownership_status' => 'Milik Yayasan',
                'procurement_year' => '2020',
                'asset_value' => 320000000,
                'condition' => 'rusak_ringan',
                'person_in_charge' => 'Koordinator Sarpras Yayasan',
                'unit_id' => 'UNIT-SMA',
                'status' => 'aktif',
                'notes' => 'Sedang dijadwalkan pergantian aki starter dan servis filter solar berkala.'
            ]
        ];

        // 24. Peminjaman & Pemakaian Ruangan / Fasilitas Khusus (Room Usage & Booking)
        $this->roomBookings = [
            [
                'id' => 'BKG-001',
                'booking_code' => 'PINJAM/RNG/2026/001',
                'room_id' => 'RNG-201', // Lab Komputer
                'room_name' => 'Laboratorium Komputer & Robotika',
                'room_location' => 'Gedung Ali bin Abi Thalib (Lt 2)',
                'requester_name' => 'Budi Santoso, S.Kom.',
                'requester_role' => 'Guru Informatika SMA',
                'unit_id' => 'UNIT-SMA',
                'booking_date' => '2026-11-12',
                'start_time' => '08:00',
                'end_time' => '11:30',
                'purpose' => 'Simulasi Ujian Computer-Based Test (CBT) dan Olimpiade Sains Komputer',
                'attendees_count' => 35,
                'equipment_needed' => 'Proyektor HD, 35 PC Client, Server CBT Lokal, Akses Internet Dedicated',
                'status' => 'disetujui', // diajukan, disetujui, ditolak, selesai
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A. (Wakabid Sarpras)',
                'approved_at' => '2026-11-08 14:20:00',
                'rejection_reason' => null,
                'notes' => 'Harap pastikan ruangan dirapikan dan server di-shutdown setelah pemakaian.'
            ],
            [
                'id' => 'BKG-002',
                'booking_code' => 'PINJAM/RNG/2026/002',
                'room_id' => 'RNG-302', // Aula Serbaguna
                'room_name' => 'Aula Serbaguna / Islamic Center',
                'room_location' => 'Gedung Utama Alia (Lt 3)',
                'requester_name' => 'Ust. Fauzan Azhim, S.T.',
                'requester_role' => 'Pembina OSIS & Kesiswaan SMA',
                'unit_id' => 'UNIT-SMA',
                'booking_date' => '2026-11-15',
                'start_time' => '13:00',
                'end_time' => '16:00',
                'purpose' => 'Kajian Bulanan & Pelantikan Pengurus OSIS / ISMA Periode 2026/2027',
                'attendees_count' => 180,
                'equipment_needed' => 'Sound System 5000W, 4 Mic Wireless, Mimbar Podium, 200 Kursi Lipat',
                'status' => 'disetujui',
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A. (Wakabid Sarpras)',
                'approved_at' => '2026-11-07 09:15:00',
                'rejection_reason' => null,
                'notes' => 'Petugas kebersihan wajib standby 30 menit sebelum dan sesudah acara.'
            ],
            [
                'id' => 'BKG-003',
                'booking_code' => 'PINJAM/RNG/2026/003',
                'room_id' => 'RNG-401', // Masjid Jami'
                'room_name' => 'Masjid Jami\' Kampus Alia',
                'room_location' => 'Pusat Kampus Alia',
                'requester_name' => 'Ust. Salman Al-Farisi, M.Pd.',
                'requester_role' => 'Koordinator Tahfidz Al-Qur\'an',
                'unit_id' => 'UNIT-SMP',
                'booking_date' => '2026-11-16',
                'start_time' => '07:30',
                'end_time' => '12:00',
                'purpose' => 'Tasmi\' Akbar Bil Ghoib 5 Juz Sekali Duduk Santri Kelas IX SMP',
                'attendees_count' => 60,
                'equipment_needed' => 'Mic Meja Musyrif, Sajadah Karpet Bersih, Sound Monitor Dalam',
                'status' => 'diajukan',
                'approver_name' => null,
                'approved_at' => null,
                'rejection_reason' => null,
                'notes' => 'Acara sakral tahfidz; mohon tidak ada mobilisasi logistik bising di sekitar selasar.'
            ],
            [
                'id' => 'BKG-004',
                'booking_code' => 'PINJAM/RNG/2026/004',
                'room_id' => 'RNG-202', // Lab Sains & Bahasa
                'room_name' => 'Laboratorium Sains & Bahasa',
                'room_location' => 'Gedung Ali bin Abi Thalib (Lt 2)',
                'requester_name' => 'Aisyah Salsabila, S.Pd.I.',
                'requester_role' => 'Guru Bahasa Arab & Inggris',
                'unit_id' => 'UNIT-SMP',
                'booking_date' => '2026-11-14',
                'start_time' => '09:00',
                'end_time' => '11:00',
                'purpose' => 'Praktikum Listening Comprehension & Muhadatsah Interaktif',
                'attendees_count' => 28,
                'equipment_needed' => '30 Unit Headset Master, Master Audio Console, Proyektor Smart TV',
                'status' => 'disetujui',
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A. (Wakabid Sarpras)',
                'approved_at' => '2026-11-08 10:45:00',
                'rejection_reason' => null,
                'notes' => 'Kunci lab dapat diambil di pos piket satpam 15 menit sebelum praktikum.'
            ],
            [
                'id' => 'BKG-005',
                'booking_code' => 'PINJAM/RNG/2026/005',
                'room_id' => 'FCL-005', // Lapangan Olahraga
                'room_name' => 'Lapangan Olahraga Multifungsi (Futsal, Basket, Voli)',
                'room_location' => 'Area Terbuka Tengah Kampus',
                'requester_name' => 'Irfan Maulana, S.Pd.',
                'requester_role' => 'Guru PJOK & Pembina Olahraga',
                'unit_id' => 'UNIT-SMA',
                'booking_date' => '2026-11-15',
                'start_time' => '15:30',
                'end_time' => '17:30',
                'purpose' => 'Pertandingan Persahabatan Futsal Antar Asrama Santri Putra',
                'attendees_count' => 80,
                'equipment_needed' => 'Bola Futsal Molten, Peluit, Papan Skor Manual, Rompi Tanding',
                'status' => 'ditolak',
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A. (Wakabid Sarpras)',
                'approved_at' => '2026-11-08 16:30:00',
                'rejection_reason' => 'Jadwal bentrok dengan agenda gladi bersih wisuda tahfidz di selasar tengah.',
                'notes' => 'Disarankan reschedule ke hari Ahad pagi pukul 07.00 - 09.30 WIB.'
            ],
            [
                'id' => 'BKG-006',
                'booking_code' => 'PINJAM/RNG/2026/006',
                'room_id' => 'RNG-301', // Perpustakaan
                'room_name' => 'Perpustakaan & Ruang Baca Digital',
                'room_location' => 'Gedung Pusat Perpustakaan (Lt 1)',
                'requester_name' => 'Zahra Amalia, S.Hum.',
                'requester_role' => 'Staf Tata Usaha & Literasi SD',
                'unit_id' => 'UNIT-SD',
                'booking_date' => '2026-11-13',
                'start_time' => '10:00',
                'end_time' => '11:30',
                'purpose' => 'Storytelling Islami & Safari Literasi Santri Cilik SD Islam Alia',
                'attendees_count' => 24,
                'equipment_needed' => 'Karpet Duduk Edukatif, Layar Proyektor Portabel, Puppet Show Kit',
                'status' => 'selesai',
                'approver_name' => 'Dr. H. Zulkifli, Lc., M.A. (Wakabid Sarpras)',
                'approved_at' => '2026-11-06 08:30:00',
                'rejection_reason' => null,
                'notes' => 'Kegiatan telah selesai dilaksanakan dengan tertib.'
            ]
        ];

        // 25. Laporan Kerusakan & Pemeliharaan Sarpras (Maintenance and Damage Reports)
        $this->maintenanceReports = [
            [
                'id' => 'MNT-001',
                'ticket_code' => 'TIKET/MNT/2026/001',
                'title' => 'Genset 100 kVA Macet Starter & Perlu Ganti Aki',
                'asset_id' => 'FCL-008', // Instalasi Gardu & Genset
                'asset_name' => 'Instalasi Gardu Listrik PLN & Genset Cadangan 100 kVA',
                'location' => 'Area Belakang Asrama Putra',
                'category' => 'kelistrikan', // kelistrikan, sipil_bangunan, ac_pendingin, sanitasi_plumbing, elektronik_it, mebelair
                'category_label' => 'Kelistrikan & Genset',
                'priority' => 'tinggi', // darurat, tinggi, sedang, rendah
                'report_date' => '2026-11-06',
                'reporter_name' => 'Ust. Fauzan Azhim, S.T.',
                'reporter_role' => 'Pembina Asrama Putra',
                'unit_id' => 'UNIT-SMA',
                'description' => 'Genset utama tidak mau menyala saat pemadaman uji coba bulanan. Tegangan aki drop di bawah 10V dan filter solar keruh.',
                'assigned_to' => 'Pak Joko Sutrisno (Teknisi Listrik)',
                'assigned_type' => 'internal', // internal, vendor_eksternal
                'vendor_name' => 'PT Daya Genset Nusantara',
                'estimated_cost' => 3800000,
                'actual_cost' => 3750000,
                'progress' => 85, // 0 - 100%
                'status' => 'dalam_pengerjaan', // dilaporkan, disetujui, dalam_pengerjaan, selesai, ditunda
                'started_at' => '2026-11-07 09:00:00',
                'completed_at' => null,
                'resolution_notes' => 'Aki starter 120 Ah baru telah dipasang. Saat ini sedang proses penggantian filter oli dan uji beban (running test).'
            ],
            [
                'id' => 'MNT-002',
                'ticket_code' => 'TIKET/MNT/2026/002',
                'title' => 'AC Split 2 PK Ruang Lab Komputer Bocor Air & Kurang Dingin',
                'asset_id' => 'RNG-201', // Lab Komputer
                'asset_name' => 'Laboratorium Komputer & Robotika (Unit AC-02)',
                'location' => 'Gedung Ali bin Abi Thalib Lt 2',
                'category' => 'ac_pendingin',
                'category_label' => 'Tata Udara & AC',
                'priority' => 'sedang',
                'report_date' => '2026-11-05',
                'reporter_name' => 'Budi Santoso, S.Kom.',
                'reporter_role' => 'Kepala Lab Komputer',
                'unit_id' => 'UNIT-SMA',
                'description' => 'Air kondensasi menetes ke atas meja komputer santri baris kedua. Suhu ruangan tidak bisa turun di bawah 26°C.',
                'assigned_to' => 'Dwi Wahyudi (Teknisi Pendingin)',
                'assigned_type' => 'internal',
                'vendor_name' => null,
                'estimated_cost' => 450000,
                'actual_cost' => 400000,
                'progress' => 100,
                'status' => 'selesai',
                'started_at' => '2026-11-06 10:00:00',
                'completed_at' => '2026-11-06 14:30:00',
                'resolution_notes' => 'Pipa drainase tersumbat lumut telah dibersihkan (flushing) dan freon R32 ditambah 30 psi. Suhu kembali normal 20°C.'
            ],
            [
                'id' => 'MNT-003',
                'ticket_code' => 'TIKET/MNT/2026/003',
                'title' => 'Plafon Bocor & Rembes Air Hujan Selasar Gedung Umar',
                'asset_id' => 'FCL-002', // Gedung Umar
                'asset_name' => 'Gedung Utama Umar bin Khattab',
                'location' => 'Lantai 2 Koridor Menuju Ruang Guru SMA',
                'category' => 'sipil_bangunan',
                'category_label' => 'Sipil & Konstruksi Gedung',
                'priority' => 'tinggi',
                'report_date' => '2026-11-08',
                'reporter_name' => 'Ahmad Fauzi, S.Pd.',
                'reporter_role' => 'Wakasek Kurikulum SMA',
                'unit_id' => 'UNIT-SMA',
                'description' => 'Hujan lebat kemarin sore menyebabkan rembesan air di plafon gypsum koridor. Rangka gypsum mulai melengkung dan berbahaya jika runtuh.',
                'assigned_to' => 'CV Karya Cipta Mandiri',
                'assigned_type' => 'vendor_eksternal',
                'vendor_name' => 'CV Karya Cipta Mandiri',
                'estimated_cost' => 2500000,
                'actual_cost' => 0,
                'progress' => 20,
                'status' => 'disetujui',
                'started_at' => '2026-11-09 08:30:00',
                'completed_at' => null,
                'resolution_notes' => 'Survei lokasi telah dilakukan. Vendor menyiapkan waterproofing dak atap dan penggantian 4 lembar papan gypsum.'
            ],
            [
                'id' => 'MNT-004',
                'ticket_code' => 'TIKET/MNT/2026/004',
                'title' => 'Kran Otomatis Tempat Wudhu Masjid Patah & Air Meluap',
                'asset_id' => 'FCL-006', // Masjid Jami'
                'asset_name' => 'Masjid Jami\' Kampus Alia Islamic School',
                'location' => 'Area Tempat Wudhu Ikhwan Lantai 1',
                'category' => 'sanitasi_plumbing',
                'category_label' => 'Sanitasi & Plumbing',
                'priority' => 'darurat',
                'report_date' => '2026-11-09',
                'reporter_name' => 'Ust. Salman Al-Farisi, M.Pd.',
                'reporter_role' => 'Musyrif Masjid Kampus',
                'unit_id' => 'UNIT-SMA',
                'description' => 'Kran air nomor 4 dan 5 patah pada drat pipa PVC akibat tarikan santri. Air mengucur deras tanpa henti.',
                'assigned_to' => 'Pak Joko Sutrisno (Teknisi Sarpras)',
                'assigned_type' => 'internal',
                'vendor_name' => null,
                'estimated_cost' => 350000,
                'actual_cost' => 320000,
                'progress' => 100,
                'status' => 'selesai',
                'started_at' => '2026-11-09 07:15:00',
                'completed_at' => '2026-11-09 08:45:00',
                'resolution_notes' => 'Stop kran utama ditutup sementara, penggantian 2 unit kran kuningan tebal 1/2 inch dan soket drat luar selesai dikerjakan.'
            ],
            [
                'id' => 'MNT-005',
                'ticket_code' => 'TIKET/MNT/2026/005',
                'title' => 'Jaring Pengaman & Papan Ring Basket Lapangan Rusak',
                'asset_id' => 'FCL-005', // Lapangan Olahraga
                'asset_name' => 'Lapangan Olahraga Multifungsi (Futsal, Basket, Voli)',
                'location' => 'Area Terbuka Tengah Kampus',
                'category' => 'mebelair',
                'category_label' => 'Sarana Olahraga & Lapangan',
                'priority' => 'sedang',
                'report_date' => '2026-11-04',
                'reporter_name' => 'Irfan Maulana, S.Pd.',
                'reporter_role' => 'Guru PJOK SMA',
                'unit_id' => 'UNIT-SMA',
                'description' => 'Jaring pengaman sisi utara sobek sepanjang 4 meter terkena tendangan bola, dan pegas ring basket sisi timur kendor.',
                'assigned_to' => 'Bengkel Las Sportindo Jaya',
                'assigned_type' => 'vendor_eksternal',
                'vendor_name' => 'Bengkel Las Sportindo Jaya',
                'estimated_cost' => 1800000,
                'actual_cost' => 0,
                'progress' => 40,
                'status' => 'dalam_pengerjaan',
                'started_at' => '2026-11-07 13:00:00',
                'completed_at' => null,
                'resolution_notes' => 'Penyambungan jaring tambang PE nylon baru dan penggantian per ring basket hidrolik dalam proses pengelasan.'
            ],
            [
                'id' => 'MNT-006',
                'ticket_code' => 'TIKET/MNT/2026/006',
                'title' => 'Lampu Sorot LED Halaman Depan Mati Total',
                'asset_id' => 'FCL-001', // Lahan & Halaman
                'asset_name' => 'Lahan Kampus Asrama & Pendidikan Terpadu',
                'location' => 'Pintu Gerbang Utama & Pos Satpam',
                'category' => 'kelistrikan',
                'category_label' => 'Kelistrikan & Penerangan',
                'priority' => 'rendah',
                'report_date' => '2026-11-08',
                'reporter_name' => 'Komandan Regu Satpam Alia',
                'reporter_role' => 'Koordinator Keamanan Kampus',
                'unit_id' => 'UNIT-SD',
                'description' => 'Dua unit lampu tembak LED 100 watt di atas pos satpam padam sehingga area parkir depan gelap saat malam.',
                'assigned_to' => null,
                'assigned_type' => 'internal',
                'vendor_name' => null,
                'estimated_cost' => 600000,
                'actual_cost' => 0,
                'progress' => 0,
                'status' => 'dilaporkan',
                'started_at' => null,
                'completed_at' => null,
                'resolution_notes' => 'Menunggu verifikasi ketersediaan stok lampu LED pengganti di gudang sarpras.'
            ]
        ];
    }

    // Getters
    public function getPpdbApplicants(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_ppdb_added', []);
                $sessionDeleted = session('demo_ppdb_deleted', []);
                $sessionUpdated = session('demo_ppdb_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->ppdbApplicants, $sessionAdded);
        $filtered = array_filter($list, function($p) use ($sessionDeleted) {
            return !in_array($p['id'], $sessionDeleted);
        });

        // Terapkan update status jika ada
        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findPpdbApplicant(string $id): ?array
    {
        foreach ($this->getPpdbApplicants() as $p) {
            if ($p['id'] === $id || $p['reg_number'] === $id) {
                return $p;
            }
        }
        return null;
    }

    public function getUnits(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_units_added', []);
                $sessionDeleted = session('demo_units_deleted', []);
            }
        } catch (\Throwable $e) {}

        $active = array_filter($this->units, function($u) use ($sessionDeleted) {
            return !in_array($u['id'], $sessionDeleted);
        });

        return array_merge(array_values($active), $sessionAdded);
    }

    public function findUnit(string $id): ?array
    {
        foreach ($this->getUnits() as $unit) {
            if ($unit['id'] === $id || ($unit['code'] ?? '') === $id) {
                return $unit;
            }
        }
        return null;
    }
    public function getAcademicYears(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $activeYearId = null;
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_academic_years_added', []);
                $sessionDeleted = session('demo_academic_years_deleted', []);
                $activeYearId = session('demo_active_academic_year_id');
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->academicYears, $sessionAdded);
        $filtered = array_filter($list, function($ay) use ($sessionDeleted) {
            return !in_array($ay['id'], $sessionDeleted);
        });

        // Terapkan penyesuaian active year dari session jika diset
        if ($activeYearId) {
            foreach ($filtered as &$ay) {
                if ($ay['id'] === $activeYearId) {
                    $ay['is_current'] = true;
                    $ay['status'] = 'aktif';
                } else {
                    $ay['is_current'] = false;
                    if ($ay['status'] === 'aktif') {
                        $ay['status'] = 'selesai';
                    }
                }
            }
        }

        return array_values($filtered);
    }

    public function findAcademicYear(string $id): ?array
    {
        foreach ($this->getAcademicYears() as $ay) {
            if ($ay['id'] === $id) {
                return $ay;
            }
        }
        return null;
    }
    public function getSubjects(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_subjects_added', []);
                $sessionDeleted = session('demo_subjects_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->subjects, $sessionAdded);
        $filtered = array_filter($list, function($sb) use ($sessionDeleted) {
            return !in_array($sb['id'], $sessionDeleted);
        });

        return array_values($filtered);
    }

    public function findSubject(string $id): ?array
    {
        foreach ($this->getSubjects() as $sb) {
            if ($sb['id'] === $id) {
                return $sb;
            }
        }
        return null;
    }

    public function getTeachers(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_teachers_added', []);
                $sessionDeleted = session('demo_teachers_deleted', []);
                $sessionUpdated = session('demo_teachers_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->teachers, $sessionAdded);
        $filtered = array_filter($list, function($t) use ($sessionDeleted) {
            return !in_array($t['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findTeacher(string $id): ?array
    {
        foreach ($this->getTeachers() as $t) {
            if ($t['id'] === $id || $t['nip'] === $id) {
                return $t;
            }
        }
        return null;
    }
    public function getClasses(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_classes_added', []);
                $sessionDeleted = session('demo_classes_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->classes, $sessionAdded);
        $filtered = array_filter($list, function($cls) use ($sessionDeleted) {
            return !in_array($cls['id'], $sessionDeleted);
        });

        return array_values($filtered);
    }

    public function findClass(string $id): ?array
    {
        foreach ($this->getClasses() as $cls) {
            if ($cls['id'] === $id) {
                return $cls;
            }
        }
        return null;
    }
    public function getFacilityRooms(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_rooms_added', []);
                $sessionDeleted = session('demo_rooms_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->facilityRooms, $sessionAdded);
        $filtered = array_filter($list, function($r) use ($sessionDeleted) {
            return !in_array($r['id'], $sessionDeleted);
        });

        return array_values($filtered);
    }

    public function findFacilityRoom(string $id): ?array
    {
        foreach ($this->getFacilityRooms() as $r) {
            if ($r['id'] === $id) {
                return $r;
            }
        }
        return null;
    }

    public function getGuardians(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_guardians_added', []);
                $sessionDeleted = session('demo_guardians_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->guardians, $sessionAdded);
        $filtered = array_filter($list, function($g) use ($sessionDeleted) {
            return !in_array($g['id'], $sessionDeleted);
        });

        return array_values($filtered);
    }

    public function findGuardian(string $id): ?array
    {
        foreach ($this->getGuardians() as $g) {
            if ($g['id'] === $id) {
                return $g;
            }
        }
        return null;
    }

    public function getStudents(): array
    {
        // Periksa apakah session service tersedia dalam lifecycle aplikasi saat ini
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_students_added', []);
                $sessionDeleted = session('demo_students_deleted', []);
            }
        } catch (\Throwable $e) {
            // Fallback graceful jika diakses di luar session lifecycle (misal unit test / CLI script)
        }

        $active = array_filter($this->students, function($s) use ($sessionDeleted) {
            return !in_array($s['id'], $sessionDeleted);
        });

        return array_merge(array_values($active), $sessionAdded);
    }

    public function findStudent(string $id): ?array
    {
        foreach ($this->getStudents() as $student) {
            if ($student['id'] === $id || $student['nis'] === $id) {
                return $student;
            }
        }
        return null;
    }

    public function getTimetables(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_timetables_added', []);
                $sessionDeleted = session('demo_timetables_deleted', []);
                $sessionUpdated = session('demo_timetables_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->timetables, $sessionAdded);
        $filtered = array_filter($list, function($tt) use ($sessionDeleted) {
            return !in_array($tt['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findTimetable(string $id): ?array
    {
        foreach ($this->getTimetables() as $tt) {
            if ($tt['id'] === $id) {
                return $tt;
            }
        }
        return null;
    }

    public function getAcademicCalendarEvents(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_calendar_added', []);
                $sessionDeleted = session('demo_calendar_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->calendarEvents, $sessionAdded);
        $filtered = array_filter($list, function($evt) use ($sessionDeleted) {
            return !in_array($evt['id'], $sessionDeleted);
        });

        // Urutkan berdasarkan tanggal mulai (start asc)
        usort($filtered, function($a, $b) {
            return strcmp($a['start'], $b['start']);
        });

        return array_values($filtered);
    }

    public function findAcademicCalendarEvent(string $id): ?array
    {
        foreach ($this->getAcademicCalendarEvents() as $evt) {
            if ($evt['id'] === $id) {
                return $evt;
            }
        }
        return null;
    }

    public function getStudentAttendance(): array
    {
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionUpdated = session('demo_attendance_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = $this->studentAttendance;
        foreach ($list as &$item) {
            $key = $item['student_id'] . '_' . $item['date'];
            if (isset($sessionUpdated[$key])) {
                $item = array_merge($item, $sessionUpdated[$key]);
            }
        }

        return $list;
    }

    public function findStudentAttendance(string $studentId, string $date): ?array
    {
        foreach ($this->getStudentAttendance() as $att) {
            if ($att['student_id'] === $studentId && $att['date'] === $date) {
                return $att;
            }
        }
        return null;
    }

    public function getStudentGrades(): array
    {
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionUpdated = session('demo_grades_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = $this->studentGrades;
        foreach ($list as &$item) {
            $key = $item['student_id'] . '_' . $item['subject_id'];
            if (isset($sessionUpdated[$key])) {
                $item = array_merge($item, $sessionUpdated[$key]);
            }
        }

        return $list;
    }

    public function findStudentGrade(string $studentId, string $subjectId): ?array
    {
        foreach ($this->getStudentGrades() as $grd) {
            if ($grd['student_id'] === $studentId && $grd['subject_id'] === $subjectId) {
                return $grd;
            }
        }
        return null;
    }

    public function getDigitalClasses(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_digital_classes_added', []);
                $sessionDeleted = session('demo_digital_classes_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->digitalClasses, $sessionAdded);
        $filtered = array_filter($list, function($dc) use ($sessionDeleted) {
            return !in_array($dc['id'], $sessionDeleted);
        });

        return array_values($filtered);
    }

    public function findDigitalClass(string $id): ?array
    {
        foreach ($this->getDigitalClasses() as $dc) {
            if ($dc['id'] === $id) {
                return $dc;
            }
        }
        return null;
    }

    public function getLearningMaterials(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_materials_added', []);
                $sessionDeleted = session('demo_materials_deleted', []);
                $sessionUpdated = session('demo_materials_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->learningMaterials, $sessionAdded);
        $filtered = array_filter($list, function($mat) use ($sessionDeleted) {
            return !in_array($mat['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findLearningMaterial(string $id): ?array
    {
        foreach ($this->getLearningMaterials() as $mat) {
            if ($mat['id'] === $id) {
                return $mat;
            }
        }
        return null;
    }

    public function getAssignments(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_assignments_added', []);
                $sessionDeleted = session('demo_assignments_deleted', []);
                $sessionUpdated = session('demo_assignments_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->assignments, $sessionAdded);
        $filtered = array_filter($list, function($tsk) use ($sessionDeleted) {
            return !in_array($tsk['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findAssignment(string $id): ?array
    {
        foreach ($this->getAssignments() as $tsk) {
            if ($tsk['id'] === $id) {
                return $tsk;
            }
        }
        return null;
    }

    public function getSubmissions(): array
    {
        $sessionAdded = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_submissions_added', []);
                $sessionUpdated = session('demo_submissions_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->submissions, $sessionAdded);
        foreach ($list as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($list);
    }

    public function findSubmission(string $id): ?array
    {
        foreach ($this->getSubmissions() as $sub) {
            if ($sub['id'] === $id) {
                return $sub;
            }
        }
        return null;
    }

    public function getQuizzes(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_quizzes_added', []);
                $sessionDeleted = session('demo_quizzes_deleted', []);
                $sessionUpdated = session('demo_quizzes_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->quizzes, $sessionAdded);
        $filtered = array_filter($list, function($qz) use ($sessionDeleted) {
            return !in_array($qz['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findQuiz(string $id): ?array
    {
        foreach ($this->getQuizzes() as $qz) {
            if ($qz['id'] === $id) {
                return $qz;
            }
        }
        return null;
    }

    public function getQuizAttempts(string $quizId = null): array
    {
        $sessionAdded = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_quiz_attempts_added', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->quizAttempts, $sessionAdded);
        if ($quizId) {
            $list = array_filter($list, function($att) use ($quizId) {
                return $att['quiz_id'] === $quizId;
            });
        }

        return array_values($list);
    }

    public function getTahfidzPrograms(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_tahfidz_programs_added', []);
                $sessionDeleted = session('demo_tahfidz_programs_deleted', []);
                $sessionUpdated = session('demo_tahfidz_programs_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->tahfidzPrograms, $sessionAdded);
        $filtered = array_filter($list, function($prg) use ($sessionDeleted) {
            return !in_array($prg['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findTahfidzProgram(string $id): ?array
    {
        foreach ($this->getTahfidzPrograms() as $prg) {
            if ($prg['id'] === $id) {
                return $prg;
            }
        }
        return null;
    }

    public function getHalaqahs(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_halaqahs_added', []);
                $sessionDeleted = session('demo_halaqahs_deleted', []);
                $sessionUpdated = session('demo_halaqahs_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->halaqahs, $sessionAdded);
        $filtered = array_filter($list, function($hlq) use ($sessionDeleted) {
            return !in_array($hlq['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findHalaqah(string $id): ?array
    {
        foreach ($this->getHalaqahs() as $hlq) {
            if ($hlq['id'] === $id) {
                return $hlq;
            }
        }
        return null;
    }

    public function getMemorizationRecords(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_ziyadah_added', []);
                $sessionDeleted = session('demo_ziyadah_deleted', []);
                $sessionUpdated = session('demo_ziyadah_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->memorizationRecords, $sessionAdded);
        $filtered = array_filter($list, function($rec) use ($sessionDeleted) {
            return !in_array($rec['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal & ID terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        return array_values($filtered);
    }

    public function findMemorizationRecord(string $id): ?array
    {
        foreach ($this->getMemorizationRecords() as $rec) {
            if ($rec['id'] === $id) {
                return $rec;
            }
        }
        return null;
    }

    public function getMurojaahRecords(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_murojaah_added', []);
                $sessionDeleted = session('demo_murojaah_deleted', []);
                $sessionUpdated = session('demo_murojaah_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->murojaahRecords, $sessionAdded);
        $filtered = array_filter($list, function($rec) use ($sessionDeleted) {
            return !in_array($rec['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal & ID terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        return array_values($filtered);
    }

    public function findMurojaahRecord(string $id): ?array
    {
        foreach ($this->getMurojaahRecords() as $rec) {
            if ($rec['id'] === $id) {
                return $rec;
            }
        }
        return null;
    }

    public function getDiniyahSubjects(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_diniyah_subjects_added', []);
                $sessionDeleted = session('demo_diniyah_subjects_deleted', []);
                $sessionUpdated = session('demo_diniyah_subjects_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->diniyahSubjects, $sessionAdded);
        $filtered = array_filter($list, function($sub) use ($sessionDeleted) {
            return !in_array($sub['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findDiniyahSubject(string $id): ?array
    {
        foreach ($this->getDiniyahSubjects() as $sub) {
            if ($sub['id'] === $id) {
                return $sub;
            }
        }
        return null;
    }

    public function getDiniyahSchedules(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_diniyah_schedules_added', []);
                $sessionDeleted = session('demo_diniyah_schedules_deleted', []);
                $sessionUpdated = session('demo_diniyah_schedules_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->diniyahSchedules, $sessionAdded);
        $filtered = array_filter($list, function($sch) use ($sessionDeleted) {
            return !in_array($sch['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findDiniyahSchedule(string $id): ?array
    {
        foreach ($this->getDiniyahSchedules() as $sch) {
            if ($sch['id'] === $id) {
                return $sch;
            }
        }
        return null;
    }

    public function getViolationRules(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_violation_rules_added', []);
                $sessionDeleted = session('demo_violation_rules_deleted', []);
                $sessionUpdated = session('demo_violation_rules_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->violationRules, $sessionAdded);
        $filtered = array_filter($list, function($rul) use ($sessionDeleted) {
            return !in_array($rul['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findViolationRule(string $id): ?array
    {
        foreach ($this->getViolationRules() as $rul) {
            if ($rul['id'] === $id) {
                return $rul;
            }
        }
        return null;
    }

    public function getStudentViolations(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_student_violations_added', []);
                $sessionDeleted = session('demo_student_violations_deleted', []);
                $sessionUpdated = session('demo_student_violations_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->studentViolations, $sessionAdded);
        $filtered = array_filter($list, function($vio) use ($sessionDeleted) {
            return !in_array($vio['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal kasus terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        return array_values($filtered);
    }

    public function findStudentViolation(string $id): ?array
    {
        foreach ($this->getStudentViolations() as $vio) {
            if ($vio['id'] === $id) {
                return $vio;
            }
        }
        return null;
    }

    public function getStudentAchievements(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_student_achievements_added', []);
                $sessionDeleted = session('demo_student_achievements_deleted', []);
                $sessionUpdated = session('demo_student_achievements_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->studentAchievements, $sessionAdded);
        $filtered = array_filter($list, function($ach) use ($sessionDeleted) {
            return !in_array($ach['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        return array_values($filtered);
    }

    public function findStudentAchievement(string $id): ?array
    {
        foreach ($this->getStudentAchievements() as $ach) {
            if ($ach['id'] === $id) {
                return $ach;
            }
        }
        return null;
    }

    public function getCounselingSessions(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_counseling_sessions_added', []);
                $sessionDeleted = session('demo_counseling_sessions_deleted', []);
                $sessionUpdated = session('demo_counseling_sessions_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->counselingSessions, $sessionAdded);
        $filtered = array_filter($list, function($cs) use ($sessionDeleted) {
            return !in_array($cs['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal & waktu terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['time'], $a['date'] . $a['time']);
        });

        return array_values($filtered);
    }

    public function findCounselingSession(string $id): ?array
    {
        foreach ($this->getCounselingSessions() as $cs) {
            if ($cs['id'] === $id) {
                return $cs;
            }
        }
        return null;
    }

    public function getExtracurriculars(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_extracurriculars_added', []);
                $sessionDeleted = session('demo_extracurriculars_deleted', []);
                $sessionUpdated = session('demo_extracurriculars_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->extracurriculars, $sessionAdded);
        $filtered = array_filter($list, function($ek) use ($sessionDeleted) {
            return !in_array($ek['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findExtracurricular(string $id): ?array
    {
        foreach ($this->getExtracurriculars() as $ek) {
            if ($ek['id'] === $id) {
                return $ek;
            }
        }
        return null;
    }

    public function getUksMedicines(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_uks_medicines_added', []);
                $sessionDeleted = session('demo_uks_medicines_deleted', []);
                $sessionUpdated = session('demo_uks_medicines_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->uksMedicines, $sessionAdded);
        $filtered = array_filter($list, function($med) use ($sessionDeleted) {
            return !in_array($med['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findUksMedicine(string $id): ?array
    {
        foreach ($this->getUksMedicines() as $med) {
            if ($med['id'] === $id) {
                return $med;
            }
        }
        return null;
    }

    public function getUksVisits(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_uks_visits_added', []);
                $sessionDeleted = session('demo_uks_visits_deleted', []);
                $sessionUpdated = session('demo_uks_visits_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->uksVisits, $sessionAdded);
        $filtered = array_filter($list, function($v) use ($sessionDeleted) {
            return !in_array($v['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan waktu kunjungan terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['visit_date'], $a['visit_date']);
        });

        return array_values($filtered);
    }

    public function findUksVisit(string $id): ?array
    {
        foreach ($this->getUksVisits() as $v) {
            if ($v['id'] === $id) {
                return $v;
            }
        }
        return null;
    }

    public function getStudentMutations(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_student_mutations_added', []);
                $sessionDeleted = session('demo_student_mutations_deleted', []);
                $sessionUpdated = session('demo_student_mutations_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->studentMutations, $sessionAdded);
        $filtered = array_filter($list, function($m) use ($sessionDeleted) {
            return !in_array($m['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal mutasi terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        return array_values($filtered);
    }

    public function findStudentMutation(string $id): ?array
    {
        foreach ($this->getStudentMutations() as $m) {
            if ($m['id'] === $id) {
                return $m;
            }
        }
        return null;
    }

    public function getBoardingBuildings(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_boarding_buildings_added', []);
                $sessionDeleted = session('demo_boarding_buildings_deleted', []);
                $sessionUpdated = session('demo_boarding_buildings_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->boardingBuildings, $sessionAdded);
        $filtered = array_filter($list, function($bld) use ($sessionDeleted) {
            return !in_array($bld['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findBoardingBuilding(string $id): ?array
    {
        foreach ($this->getBoardingBuildings() as $bld) {
            if ($bld['id'] === $id) {
                return $bld;
            }
        }
        return null;
    }

    public function getBoardingRooms(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_boarding_rooms_added', []);
                $sessionDeleted = session('demo_boarding_rooms_deleted', []);
                $sessionUpdated = session('demo_boarding_rooms_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->boardingRooms, $sessionAdded);
        $filtered = array_filter($list, function($kmr) use ($sessionDeleted) {
            return !in_array($kmr['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findBoardingRoom(string $id): ?array
    {
        foreach ($this->getBoardingRooms() as $kmr) {
            if ($kmr['id'] === $id) {
                return $kmr;
            }
        }
        return null;
    }

    public function getResidentAssignments(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_resident_assignments_added', []);
                $sessionDeleted = session('demo_resident_assignments_deleted', []);
                $sessionUpdated = session('demo_resident_assignments_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->residentAssignments, $sessionAdded);
        $filtered = array_filter($list, function($asn) use ($sessionDeleted) {
            return !in_array($asn['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findResidentAssignment(string $id): ?array
    {
        foreach ($this->getResidentAssignments() as $asn) {
            if ($asn['id'] === $id) {
                return $asn;
            }
        }
        return null;
    }

    public function getBoardingActivities(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_boarding_activities_added', []);
                $sessionDeleted = session('demo_boarding_activities_deleted', []);
                $sessionUpdated = session('demo_boarding_activities_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->boardingActivities, $sessionAdded);
        $filtered = array_filter($list, function($act) use ($sessionDeleted) {
            return !in_array($act['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan jam mulai
        usort($filtered, function($a, $b) {
            return strcmp($a['time_start'], $b['time_start']);
        });

        return array_values($filtered);
    }

    public function findBoardingActivity(string $id): ?array
    {
        foreach ($this->getBoardingActivities() as $act) {
            if ($act['id'] === $id) {
                return $act;
            }
        }
        return null;
    }

    public function getLeavePermissions(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_leave_permissions_added', []);
                $sessionDeleted = session('demo_leave_permissions_deleted', []);
                $sessionUpdated = session('demo_leave_permissions_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->leavePermissions, $sessionAdded);
        $filtered = array_filter($list, function($izn) use ($sessionDeleted) {
            return !in_array($izn['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal mulai terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date_start'], $a['date_start']);
        });

        return array_values($filtered);
    }

    public function findLeavePermission(string $id): ?array
    {
        foreach ($this->getLeavePermissions() as $izn) {
            if ($izn['id'] === $id) {
                return $izn;
            }
        }
        return null;
    }

    public function getBoardingAttendance(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_boarding_attendance_added', []);
                $sessionDeleted = session('demo_boarding_attendance_deleted', []);
                $sessionUpdated = session('demo_boarding_attendance_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->boardingAttendance, $sessionAdded);
        $filtered = array_filter($list, function($att) use ($sessionDeleted) {
            return !in_array($att['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan tanggal & id terbaru
        usort($filtered, function($a, $b) {
            return strcmp($b['date'] . $b['id'], $a['date'] . $a['id']);
        });

        return array_values($filtered);
    }

    public function findBoardingAttendance(string $id): ?array
    {
        foreach ($this->getBoardingAttendance() as $att) {
            if ($att['id'] === $id) {
                return $att;
            }
        }
        return null;
    }

    public function getBoardingMeals(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_boarding_meals_added', []);
                $sessionDeleted = session('demo_boarding_meals_deleted', []);
                $sessionUpdated = session('demo_boarding_meals_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->boardingMeals, $sessionAdded);
        $filtered = array_filter($list, function($meal) use ($sessionDeleted) {
            return !in_array($meal['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        // Urutkan berdasarkan hari (Senin s/d Ahad) dan jam makan
        $dayOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Ahad' => 7
        ];
        $timeOrder = [
            'Pagi' => 1,
            'Siang' => 2,
            'Malam' => 3
        ];

        usort($filtered, function($a, $b) use ($dayOrder, $timeOrder) {
            $d1 = $dayOrder[$a['day']] ?? 9;
            $d2 = $dayOrder[$b['day']] ?? 9;
            if ($d1 !== $d2) {
                return $d1 - $d2;
            }
            $t1 = $timeOrder[$a['meal_time']] ?? 9;
            $t2 = $timeOrder[$b['meal_time']] ?? 9;
            return $t1 - $t2;
        });

        return array_values($filtered);
    }

    public function findBoardingMeal(string $id): ?array
    {
        foreach ($this->getBoardingMeals() as $meal) {
            if ($meal['id'] === $id) {
                return $meal;
            }
        }
        return null;
    }

    public function getFeeCategories(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_fee_categories_added', []);
                $sessionDeleted = session('demo_fee_categories_deleted', []);
                $sessionUpdated = session('demo_fee_categories_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->feeCategories, $sessionAdded);
        $filtered = array_filter($list, function($fee) use ($sessionDeleted) {
            return !in_array($fee['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findFeeCategory(string $id): ?array
    {
        foreach ($this->getFeeCategories() as $fee) {
            if ($fee['id'] === $id) {
                return $fee;
            }
        }
        return null;
    }

    public function getStudentInvoices(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_student_invoices_added', []);
                $sessionDeleted = session('demo_student_invoices_deleted', []);
                $sessionUpdated = session('demo_student_invoices_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->studentInvoices, $sessionAdded);
        $filtered = array_filter($list, function($inv) use ($sessionDeleted) {
            return !in_array($inv['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        usort($filtered, function($a, $b) {
            return strcmp($b['invoice_number'], $a['invoice_number']);
        });

        return array_values($filtered);
    }

    public function findStudentInvoice(string $id): ?array
    {
        foreach ($this->getStudentInvoices() as $inv) {
            if ($inv['id'] === $id) {
                return $inv;
            }
        }
        return null;
    }

    public function getPaymentTransactions(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_payment_transactions_added', []);
                $sessionDeleted = session('demo_payment_transactions_deleted', []);
                $sessionUpdated = session('demo_payment_transactions_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->paymentTransactions, $sessionAdded);
        $filtered = array_filter($list, function($trx) use ($sessionDeleted) {
            return !in_array($trx['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        usort($filtered, function($a, $b) {
            return strcmp($b['payment_date'], $a['payment_date']);
        });

        return array_values($filtered);
    }

    public function findPaymentTransaction(string $id): ?array
    {
        foreach ($this->getPaymentTransactions() as $trx) {
            if ($trx['id'] === $id) {
                return $trx;
            }
        }
        return null;
    }

    public function getFinanceExpenses(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_finance_expenses_added', []);
                $sessionDeleted = session('demo_finance_expenses_deleted', []);
                $sessionUpdated = session('demo_finance_expenses_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->financeExpenses, $sessionAdded);
        $filtered = array_filter($list, function($exp) use ($sessionDeleted) {
            return !in_array($exp['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        usort($filtered, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        return array_values($filtered);
    }

    public function findFinanceExpense(string $id): ?array
    {
        foreach ($this->getFinanceExpenses() as $exp) {
            if ($exp['id'] === $id) {
                return $exp;
            }
        }
        return null;
    }

    public function getScholarships(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_scholarships_added', []);
                $sessionDeleted = session('demo_scholarships_deleted', []);
                $sessionUpdated = session('demo_scholarships_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->scholarships, $sessionAdded);
        $filtered = array_filter($list, function($sch) use ($sessionDeleted) {
            return !in_array($sch['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findScholarship(string $id): ?array
    {
        foreach ($this->getScholarships() as $sch) {
            if ($sch['id'] === $id) {
                return $sch;
            }
        }
        return null;
    }

    public function getStaffAttendance(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_staff_attendance_added', []);
                $sessionDeleted = session('demo_staff_attendance_deleted', []);
                $sessionUpdated = session('demo_staff_attendance_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->staffAttendance, $sessionAdded);
        $filtered = array_filter($list, function($att) use ($sessionDeleted) {
            return !in_array($att['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findStaffAttendance(string $id): ?array
    {
        foreach ($this->getStaffAttendance() as $att) {
            if ($att['id'] === $id) {
                return $att;
            }
        }
        return null;
    }

    public function getStaffLeaves(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_staff_leaves_added', []);
                $sessionDeleted = session('demo_staff_leaves_deleted', []);
                $sessionUpdated = session('demo_staff_leaves_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->staffLeaves, $sessionAdded);
        $filtered = array_filter($list, function($leave) use ($sessionDeleted) {
            return !in_array($leave['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findStaffLeave(string $id): ?array
    {
        foreach ($this->getStaffLeaves() as $leave) {
            if ($leave['id'] === $id || ($leave['leave_number'] ?? '') === $id) {
                return $leave;
            }
        }
        return null;
    }

    public function getStaffOvertimes(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_staff_overtimes_added', []);
                $sessionDeleted = session('demo_staff_overtimes_deleted', []);
                $sessionUpdated = session('demo_staff_overtimes_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->staffOvertimes, $sessionAdded);
        $filtered = array_filter($list, function($ovt) use ($sessionDeleted) {
            return !in_array($ovt['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findStaffOvertime(string $id): ?array
    {
        foreach ($this->getStaffOvertimes() as $ovt) {
            if ($ovt['id'] === $id || ($ovt['spkl_number'] ?? '') === $id) {
                return $ovt;
            }
        }
        return null;
    }

    public function getStaffEvaluations(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_staff_evaluations_added', []);
                $sessionDeleted = session('demo_staff_evaluations_deleted', []);
                $sessionUpdated = session('demo_staff_evaluations_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->staffEvaluations, $sessionAdded);
        $filtered = array_filter($list, function($evl) use ($sessionDeleted) {
            return !in_array($evl['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findStaffEvaluation(string $id): ?array
    {
        foreach ($this->getStaffEvaluations() as $evl) {
            if ($evl['id'] === $id || ($evl['doc_number'] ?? '') === $id) {
                return $evl;
            }
        }
        return null;
    }

    public function getStaffPayrolls(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_staff_payrolls_added', []);
                $sessionDeleted = session('demo_staff_payrolls_deleted', []);
                $sessionUpdated = session('demo_staff_payrolls_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->staffPayrolls, $sessionAdded);
        $filtered = array_filter($list, function($pay) use ($sessionDeleted) {
            return !in_array($pay['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findStaffPayroll(string $id): ?array
    {
        foreach ($this->getStaffPayrolls() as $pay) {
            if ($pay['id'] === $id || ($pay['slip_number'] ?? '') === $id) {
                return $pay;
            }
        }
        return null;
    }

    public function getStaffTrainings(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_staff_trainings_added', []);
                $sessionDeleted = session('demo_staff_trainings_deleted', []);
                $sessionUpdated = session('demo_staff_trainings_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->staffTrainings, $sessionAdded);
        $filtered = array_filter($list, function($trn) use ($sessionDeleted) {
            return !in_array($trn['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findStaffTraining(string $id): ?array
    {
        foreach ($this->getStaffTrainings() as $trn) {
            if ($trn['id'] === $id || ($trn['doc_number'] ?? '') === $id) {
                return $trn;
            }
        }
        return null;
    }

    public function getFacilityAssets(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_facility_assets_added', []);
                $sessionDeleted = session('demo_facility_assets_deleted', []);
                $sessionUpdated = session('demo_facility_assets_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->facilityAssets, $sessionAdded);
        $filtered = array_filter($list, function($ast) use ($sessionDeleted) {
            return !in_array($ast['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findFacilityAsset(string $id): ?array
    {
        foreach ($this->getFacilityAssets() as $ast) {
            if ($ast['id'] === $id || ($ast['asset_code'] ?? '') === $id) {
                return $ast;
            }
        }
        return null;
    }

    public function getRoomBookings(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_room_bookings_added', []);
                $sessionDeleted = session('demo_room_bookings_deleted', []);
                $sessionUpdated = session('demo_room_bookings_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->roomBookings, $sessionAdded);
        $filtered = array_filter($list, function($bkg) use ($sessionDeleted) {
            return !in_array($bkg['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findRoomBooking(string $id): ?array
    {
        foreach ($this->getRoomBookings() as $bkg) {
            if ($bkg['id'] === $id || ($bkg['booking_code'] ?? '') === $id) {
                return $bkg;
            }
        }
        return null;
    }

    public function getMaintenanceReports(): array
    {
        $sessionAdded = [];
        $sessionDeleted = [];
        $sessionUpdated = [];
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_maintenance_reports_added', []);
                $sessionDeleted = session('demo_maintenance_reports_deleted', []);
                $sessionUpdated = session('demo_maintenance_reports_updated', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->maintenanceReports, $sessionAdded);
        $filtered = array_filter($list, function($mnt) use ($sessionDeleted) {
            return !in_array($mnt['id'], $sessionDeleted);
        });

        foreach ($filtered as &$item) {
            if (isset($sessionUpdated[$item['id']])) {
                $item = array_merge($item, $sessionUpdated[$item['id']]);
            }
        }

        return array_values($filtered);
    }

    public function findMaintenanceReport(string $id): ?array
    {
        foreach ($this->getMaintenanceReports() as $mnt) {
            if ($mnt['id'] === $id || ($mnt['ticket_code'] ?? '') === $id) {
                return $mnt;
            }
        }
        return null;
    }
}
