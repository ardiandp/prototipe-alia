# PROGRESS.md — IRNIS School Management System Prototype

Catatan pemantauan progres implementasi prototipe Alia Islamic School.

---

## 📌 Status Terkini

- **Batch Aktif**: Batch D (Learning, Islamic Programs, Student Affairs)
- **Batch Aktif**: Batch E (Boarding, Finance, HRIS)
- **Task Terakhir Selesai**:
  - `T07.6`: Student Mutation (Kesiswaan: Sistem Mutasi Santri Masuk/Pindahan & Keluar/Lulus/DO, Nomor SK Mutasi Resmi, Sekolah Asal/Tujuan, Modal SK Berkop Pondok, Form Pengajuan Mutasi Berbasis Sesi, dan Cetak Surat Keterangan Pindah).
- **Task Selanjutnya**: `T08.1`: Boarding Buildings/Rooms (Asrama: Manajemen Gedung Asrama, Blok Lantai, Kapasitas & Kamar Santri).

---

## 📋 Riwayat Task & Log Implementasi

### Batch A — Foundation & Layout
- `[x] T00.1`: Inspeksi lingkungan teknis (PHP 8.3 Laragon, Laravel 13, AdminLTE 2.4.2).
- `[x] T00.2`: Batasan prototipe & driver sesi file di `.env`.
- `[x] T00.3`: Base layout AdminLTE 2 terpasang & CSS Alia Deep Navy/Purple.
- `[x] T00.4`: UI Partials & Katalog Komponen (`/components-demo`).
- `[x] T00.5`: Routing skeleton 58 routes.
- `[x] T00.6`: Data foundation terpusat (`DemoDataManager.php`).

### Batch B — Dashboard & Master Data
- `[x] T01.1`: Dashboard Eksekutif Yayasan.
- `[x] T01.2`: Dashboard Kepsek, Guru, Bendahara, Asrama.
- `[x] T02.1 - T02.8`: Master Data lengkap (Unit, Tahun Ajaran, Kelas, Ruangan, Mapel, Siswa Buku Induk, Pegawai, Orang Tua).

### Batch C — PPDB (Penerimaan Peserta Didik Baru)
- `[x] T03.1`: PPDB Dashboard (Statistik kuota, corong seleksi, pie chart jalur masuk).
- `[x] T03.2`: Applicant List & Modal Detail Berkas.
- `[x] T03.3`: Form Pendaftaran Wizard Online.
- `[x] T03.4`: Document Verification (Checklist berkas & approval).
- `[x] T03.5`: Selection Workflow (Penilaian 3 pilar: Akademik, Al-Quran, Wawancara).
- `[x] T03.6`: Announcement & Re-registration (Surat SK Resmi & Pembayaran).
- `[x] T03.7`: Convert Applicant to Student (Konversi ke Buku Induk).

### Batch C (Lanjutan) — Akademik & Pembelajaran (Lengkap)
- `[x] T04.1`: Class/learning-group management (Manajemen Rombel, roster santri, alokasi wali kelas & kursi).
- `[x] T04.2`: Timetable (Jadwal Pelajaran per Rombel, dwi-tampilan matriks & tabel, 230 slot jam belajar).
- `[x] T04.3`: Academic calendar (Kalender Akademik FullCalendar, strip metrik, agenda kegiatan, modal add/detail event).
- `[x] T04.4`: Student attendance (Presensi Harian Santri & Rekap Kehadiran, quick action Hadir Semua).
- `[x] T04.5`: Grade input (Input Nilai Formatif, PTS, PAS, Praktik, kalkulasi live bobot otomatis).
- `[x] T04.6`: E-report (E-Rapor format cetak resmi berkop Alia Islamic School, rekap nilai, tahfidz, ekstrakurikuler, catatan wali kelas).
- `[x] T04.7`: Promotion simulation (Simulasi Kenaikan Kelas, sidang pleno kelulusan, penetapan rombel tujuan).

### Batch D — LMS, Tahfidz, Kesiswaan, & Asrama
- `[x] T05.1`: Digital classes (Portal Kelas Digital LMS, kartu kelas dengan kode gabung unik, guru pengampu, daftar santri partisipan, feed alur pembelajaran).
- `[x] T05.2`: Learning materials (Katalog Materi & Modul Pembelajaran Digital, file PDF/Video/Audio/Link, toggle publish/draft).
- `[x] T05.3`: Assignments:
  - Dataset baseline penugasan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getAssignments`, `findAssignment`, `getSubmissions`).
  - Controller: [`LmsController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/LmsController.php) (`tugas`, `storeTugas`, `destroyTugas`).
  - View: [`resources/views/lms/tugas.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/lms/tugas.blade.php) (Tabel daftar tugas, progress bar pengumpulan santri, modal monitoring berkas submisi dan nilai feedback, form buat tugas baru, dan cetak).
  - Rute aktif `/lms/tugas`, `/lms/tugas` (POST), dan `/lms/tugas/{id}/destroy`.
- `[x] T05.4`: Submission monitoring:
  - Controller: [`LmsController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/LmsController.php) (`submission`, `gradeSubmission`, `simulateUpload`).
  - View: [`resources/views/lms/submission.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/lms/submission.blade.php) (Daftar santri per rombel tugas, status terkirim / terlambat / belum, preview berkas santri, modal penilaian & feedback, feedback visual status kualitatif A/B/C/D, simulasi kirim tugas demo).
  - Rute aktif `/lms/tugas/{assignmentId}/submission`, `/lms/tugas/{assignmentId}/grade/{studentId}` (POST), `/lms/tugas/{assignmentId}/simulate` (POST).
- `[x] T05.5`: Quiz demo:
  - Dataset baseline kuis CBT & riwayat pengerjaan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getQuizzes`, `findQuiz`, `getQuizAttempts`).
  - Controller: [`LmsController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/LmsController.php) (`ujian`, `storeUjian`, `destroyUjian`).
  - View: [`resources/views/lms/ujian.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/lms/ujian.blade.php) (Daftar kartu paket CBT, indikator durasi & KKM, modal pratinjau butir soal interaktif pilihan ganda/esai dengan kunci jawaban & pembahasan, modal rekapitulasi nilai peserta santri, form pembuatan paket kuis baru, cetak).
  - Rute aktif `/lms/ujian`, `/lms/ujian` (POST), dan `/lms/ujian/{id}/destroy`.
- `[x] T05.6`: Learning progress:
  - Controller: [`LmsController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/LmsController.php) (`progres`).
  - View: [`resources/views/lms/progres.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/lms/progres.blade.php) (Matriks monitoring kemajuan belajar santri, metrik kelengkapan materi, tugas pending, skor CBT, progress bar kelengkapan total, modal rincian belajar santri, filter per rombel & search, cetak).
  - Rute aktif `/lms/progres` dan link menu di sidebar AdminLTE.
- `[x] T06.1`: Tahfidz programs and targets:
  - Dataset baseline program tahfidz & halaqah di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getTahfidzPrograms`, `findTahfidzProgram`, `getHalaqahs`, `findHalaqah`).
  - Controller baru: [`TahfidzController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/TahfidzController.php) (`target`, `storeHalaqah`, `storeProgram`, `destroyHalaqah`, `assignStudent`).
  - View: [`resources/views/tahfidz/target.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/tahfidz/target.blade.php) (Dwi-tab Kelompok Halaqah & Kurikulum Program Tahfidz, kartu halaqah ikhwan/akhwat, musyrif pengampu, target juz, modal alokasi santri, modal buat halaqah baru & program baru, cetak).
  - Rute aktif `/tahfidz/target`, `/tahfidz/halaqah` (POST), `/tahfidz/program` (POST), `/tahfidz/halaqah/{id}/assign` (POST), `/tahfidz/halaqah/{id}/destroy`.
- `[x] T06.2`: Student memorization records:
  - Dataset baseline setoran ziyadah di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getMemorizationRecords`, `findMemorizationRecord`).
  - Controller: [`TahfidzController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/TahfidzController.php) (`setoran`, `storeSetoran`, `destroySetoran`).
  - View: [`resources/views/tahfidz/setoran.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/tahfidz/setoran.blade.php) (Tabel buku mutaba'ah ziyadah santri, surat & ayat, total ayat, predikat & skor kualitatif Mumtaz/Jayyid Jiddan/Jayyid/Maqbul, feedback musyrif, modal input setoran baru, cetak).
  - Rute aktif `/tahfidz/setoran`, `/tahfidz/setoran` (POST), dan `/tahfidz/setoran/{id}/destroy`.
- `[x] T06.3`: Murojaah records:
  - Dataset baseline murojaah di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getMurojaahRecords`, `findMurojaahRecord`).
  - Controller: [`TahfidzController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/TahfidzController.php) (`murojaah`, `storeMurojaah`, `destroyMurojaah`).
  - View: [`resources/views/tahfidz/murojaah.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/tahfidz/murojaah.blade.php) (Tabel buku mutaba'ah murojaah, jenis evaluasi Rubu'/Mingguan/Tasmi' 1 Juz/Kenaikan Juz, frekuensi pengulangan tikrar, status Mutqin/Cukup Lancar/Perlu Pengulangan, feedback evaluasi musyrif, modal input murojaah baru, cetak).
  - Rute aktif `/tahfidz/murojaah`, `/tahfidz/murojaah` (POST), dan `/tahfidz/murojaah/{id}/destroy`.
- `[x] T06.4`: Tahfidz progress:
  - Controller: [`TahfidzController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/TahfidzController.php) (`progres`).
  - View: [`resources/views/tahfidz/progres.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/tahfidz/progres.blade.php) (Dashboard capaian tahfidz, metrik juz mutqin, rata-rata juz, rasio target program vs realisasi, status On Track/Perlu Akselerasi, modal peta visual 30 juz Al-Qur'an warna-warni per santri, riwayat mutaba'ah ziyadah & murojaah, cetak).
  - Rute aktif `/tahfidz/progres` dan link navigasi di sidebar AdminLTE.
- `[x] T06.5`: Diniyah subjects and schedules:
  - Dataset baseline mata pelajaran diniyah & jadwal di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getDiniyahSubjects`, `findDiniyahSubject`, `getDiniyahSchedules`, `findDiniyahSchedule`).
  - Controller: [`TahfidzController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/TahfidzController.php) (`diniyah`, `storeSubject`, `storeSchedule`, `destroySchedule`).
  - View: [`resources/views/tahfidz/diniyah.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/tahfidz/diniyah.blade.php) (Dwi-tab Jadwal Pengajian & Katalog Kitab Kuning, badge warna-warni hari & sesi Ba'da Subuh/Ashar/Maghrib, ustadz pengajar, target santri, modal tambah jadwal pengajian baru, modal daftarkan kitab rujukan baru, cetak).
  - Rute aktif `/tahfidz/diniyah`, `/tahfidz/diniyah/kitab` (POST), `/tahfidz/diniyah/jadwal` (POST), dan `/tahfidz/diniyah/jadwal/{id}/destroy`.
- `[x] T06.6`: Tahfidz report:
  - Controller: [`TahfidzController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/TahfidzController.php) (`rapor`, `updateCatatanRapor`).
  - View: [`resources/views/tahfidz/rapor.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/tahfidz/rapor.blade.php) (Lembar Buku Rapor Tahfidz Al-Qur'an resmi berkop sekolah, capaian kuantitatif target vs realisasi juz mutqin, tabel penilaian 4 pilar mutu kelancaran 35% / tajwid 25% / makhraj 25% / disiplin 15%, skor akhir kumulatif dan predikat Mumtaz, status tasmi' bil ghoib, form update catatan musyrif berbasis sesi, tanda tangan 3 pihak wali santri/musyrif/mudir, print-ready CSS).
  - Rute aktif `/tahfidz/rapor`, `/tahfidz/rapor/{studentId}/catatan` (POST), dan link navigasi di sidebar AdminLTE.
- `[x] T07.1`: Violations/guidance points:
  - Dataset baseline aturan tata tertib poin pelanggaran dan riwayat kasus santri di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getDisciplinaryRules`, `getStudentViolations`, `addStudentViolation`, `deleteStudentViolation`, `addDisciplinaryRule`).
  - Controller baru: [`KesiswaanController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KesiswaanController.php) (`pelanggaran`, `storePelanggaran`, `destroyPelanggaran`, `storeRule`).
  - View: [`resources/views/kesiswaan/pelanggaran.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/kesiswaan/pelanggaran.blade.php) (Dwi-tab Jurnal Kasus Santri & Master Buku Saku Tata Tertib, rekapitulasi poin akumulasi santri dengan indikator ambang sanksi SP 1 / SP 2 / SP 3, modal catat pelanggaran baru, modal tambah aturan baru, feedback flash message, filter kategori & tingkat sanksi, cetak).
  - Rute aktif `/kesiswaan/pelanggaran`, `/kesiswaan/pelanggaran` (POST), `/kesiswaan/pelanggaran/aturan` (POST), `/kesiswaan/pelanggaran/{id}/destroy`.
- `[x] T07.2`: Achievements (Prestasi Santri):
  - Dataset baseline prestasi & kejuaraan santri di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStudentAchievements`, `findStudentAchievement`).
  - Controller: [`KesiswaanController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KesiswaanController.php) (`prestasi`, `storePrestasi`, `destroyPrestasi`).
  - View: [`resources/views/kesiswaan/prestasi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/kesiswaan/prestasi.blade.php) (Tabel rekam jejak prestasi, badge tingkatan Nasional/Provinsi/Kota/Internasional, modal pratinjau sertifikat SK resmi, leaderboard top 4 santri peraih medali, tabel matriks standar bobot poin apresiasi, modal catat prestasi baru berbasis sesi, cetak).
  - Rute aktif `/kesiswaan/prestasi`, `/kesiswaan/prestasi` (POST), dan `/kesiswaan/prestasi/{id}/destroy`.
- `[x] T07.3`: Counseling / BK (Bimbingan Konseling):
  - Dataset baseline sesi bimbingan konseling santri di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getCounselingSessions`, `findCounselingSession`).
  - Controller: [`KesiswaanController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KesiswaanController.php) (`konseling`, `storeKonseling`, `updateKonselingStatus`, `destroyKonseling`).
  - View: [`resources/views/kesiswaan/konseling.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/kesiswaan/konseling.blade.php) (Jurnal layanan konseling santri, metrik statistik sesi aktif/tuntas/rujukan, filter 4 pilar masalah dan tipe layanan, alur integrasi rujukan tata tertib & asrama, modal detail berkas konseling berkop pondok, modal update status RTL interaktif, modal catat sesi baru berbasis sesi, cetak).
  - Rute aktif `/kesiswaan/konseling`, `/kesiswaan/konseling` (POST), `/kesiswaan/konseling/{id}/update-status` (POST), dan `/kesiswaan/konseling/{id}/destroy`.
- `[x] T07.4`: Extracurricular (Ekstrakurikuler):
  - Dataset baseline klub ekstrakurikuler di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getExtracurriculars`, `findExtracurricular`).
  - Controller: [`KesiswaanController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KesiswaanController.php) (`ekskul`, `storeEkskul`, `enrollStudent`, `destroyEkskul`).
  - View: [`resources/views/kesiswaan/ekskul.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/kesiswaan/ekskul.blade.php) (Grid kartu profil klub warna-warni berdasarkan kategori, metrik ringkasan klub/anggota/pelatih/prestasi, progress bar kuota kapasitas santri, modal roster anggota aktif per klub, modal daftarkan santri baru, modal buka klub baru berbasis sesi, filter hari/bidang, cetak).
  - Rute aktif `/kesiswaan/ekskul`, `/kesiswaan/ekskul` (POST), `/kesiswaan/ekskul/{id}/enroll` (POST), dan `/kesiswaan/ekskul/{id}/destroy`.
- `[x] T07.5`: UKS (Unit Kesehatan Sekolah):
  - Dataset baseline inventaris obat & rekam medis kunjungan santri di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getUksMedicines`, `findUksMedicine`, `getUksVisits`, `findUksVisit`).
  - Controller: [`KesiswaanController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KesiswaanController.php) (`uks`, `storeUksVisit`, `storeUksMedicine`, `destroyUksVisit`).
  - View: [`resources/views/kesiswaan/uks.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/kesiswaan/uks.blade.php) (Dwi-tab Buku Register Kunjungan Medis & Inventaris Obat P3K, metrik ringkasan total register, selesai, rujukan faskes darurat, indikator stok obat kritis, modal detail rekam medis berkop pondok, modal catat pemeriksaan medis baru, modal tambah stok obat baru berbasis sesi, cetak surat keterangan sakit).
  - Rute aktif `/kesiswaan/uks`, `/kesiswaan/uks/kunjungan` (POST), `/kesiswaan/uks/obat` (POST), dan `/kesiswaan/uks/kunjungan/{id}/destroy`.
- `[x] T07.6`: Student Mutation (Mutasi Santri):
  - Dataset baseline riwayat mutasi santri masuk & keluar di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStudentMutations`, `findStudentMutation`).
  - Controller: [`KesiswaanController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KesiswaanController.php) (`mutasi`, `storeMutasi`, `destroyMutasi`).
  - View: [`resources/views/kesiswaan/mutasi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/kesiswaan/mutasi.blade.php) (Tabel buku register mutasi santri, badge tipe mutasi masuk/keluar/lulus/dikeluarkan, modal surat keputusan resmi berkop Alia Islamic School, form pengajuan & penerbitan SK mutasi berbasis sesi, cetak surat keterangan pindah).
  - Rute aktif `/kesiswaan/mutasi`, `/kesiswaan/mutasi` (POST), `/kesiswaan/mutasi/{id}/destroy`, serta link navigasi UKS & Mutasi di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).







