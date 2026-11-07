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
    private array $boardingRooms = [];
    private array $facilityRooms = [];
    private array $ppdbApplicants = [];
    private array $timetables = [];
    private array $calendarEvents = [];
    private array $studentAttendance = [];
    private array $studentGrades = [];
    private array $digitalClasses = [];
    private array $learningMaterials = [];

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

        // 5. Kamar Asrama
        $rooms = [
            ['KMR-A01', 'Gedung Al-Fatih Lt 1 / Kamar 101', 'Putra', 6],
            ['KMR-A02', 'Gedung Al-Fatih Lt 1 / Kamar 102', 'Putra', 6],
            ['KMR-B01', 'Gedung Maryam Lt 1 / Kamar 201', 'Putri', 6],
            ['KMR-B02', 'Gedung Maryam Lt 1 / Kamar 202', 'Putri', 6],
        ];
        foreach ($rooms as $r) {
            $this->boardingRooms[] = [
                'id' => $r[0],
                'name' => $r[1],
                'category' => $r[2],
                'capacity' => $r[3],
                'status' => 'aktif'
            ];
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
        try {
            if (function_exists('session') && app()->has('session')) {
                $sessionAdded = session('demo_teachers_added', []);
                $sessionDeleted = session('demo_teachers_deleted', []);
            }
        } catch (\Throwable $e) {}

        $list = array_merge($this->teachers, $sessionAdded);
        $filtered = array_filter($list, function($t) use ($sessionDeleted) {
            return !in_array($t['id'], $sessionDeleted);
        });

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

    public function getBoardingRooms(): array { return $this->boardingRooms; }
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
}
