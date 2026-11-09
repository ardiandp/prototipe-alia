# PROGRESS.md — IRNIS School Management System Prototype

Catatan pemantauan progres implementasi prototipe Alia Islamic School.

---

## 📌 Status Terkini

- **Batch Aktif**: Batch D (Learning, Islamic Programs, Student Affairs)
- **Batch Aktif**: Batch E (Boarding, Finance, HRIS)
- **Batch Aktif**: Batch F (Facilities, Inventory, Portals)
- **Task Terakhir Selesai**:
  - `T11.3`: Maintenance/damage reports (Sarpras & Fasilitas: Manajemen Tiket Laporan Kerusakan Fasilitas, Klasifikasi 4 Tingkat Prioritas Darurat/Tinggi/Sedang/Rendah, Alokasi Teknisi Internal & Vendor Rekanan, Formulir SPK Servis Berkop Alia, Update Progres Log Servis & Realisasi Biaya).
- **Task Selanjutnya**: `T11.4`: Procurement requests (Sarpras & Fasilitas: Pengajuan Pengadaan Sarpras Baru, Daftar Kebutuhan Barang/Fasilitas, Estimasi Anggaran, dan Alur Persetujuan Yayasan — Penutup Phase 11 Sarpras).

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
- `[x] T08.1`: Boarding Buildings/Rooms (Gedung & Kamar Asrama):
  - Dataset baseline gedung & kamar asrama terstruktur di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getBoardingBuildings`, `findBoardingBuilding`, `getBoardingRooms`, `findBoardingRoom`).
  - Controller baru: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`kamar`, `storeKamar`, `storeGedung`, `destroyKamar`).
  - View: [`resources/views/asrama/kamar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/kamar.blade.php) (Dwi-tab Kamar Santri & Profil Gedung, grid kartu kamar interaktif dengan progress bar okupansi tempat tidur, modal roster penghuni kamar aktif, modal tambah kamar baru, modal tambah gedung baru berbasis sesi, filter gedung & gender, cetak denah & okupansi).
  - Rute aktif `/asrama/kamar`, `/asrama/kamar` (POST), `/asrama/gedung` (POST), `/asrama/kamar/{id}/destroy`.
- `[x] T08.2`: Resident Assignment (Penempatan Kamar Santri):
  - Dataset baseline alokasi kamar santri di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getResidentAssignments`, `findResidentAssignment`).
  - Controller: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`penempatan`, `storePenempatan`, `transferKamar`, `checkoutSantri`).
  - View: [`resources/views/asrama/penempatan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/penempatan.blade.php) (Tabel buku alokasi kamar santri, metrik kapasitas total, santri mukim aktif, sisa bed, modal check-in santri baru, modal relokasi/pindah kamar tujuan otomatis, aksi check-out santri, filter gedung/kamar/status, cetak).
  - Rute aktif `/asrama/penempatan`, `/asrama/penempatan` (POST), `/asrama/penempatan/{id}/transfer` (POST), `/asrama/penempatan/{id}/checkout`, dan update menu di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).
- `[x] T08.3`: Daily Activity Schedule (Jadwal Aktivitas Harian Asrama):
  - Dataset baseline rutinitas 24 jam santri di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getBoardingActivities`, `findBoardingActivity`).
  - Controller: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`aktivitas`, `storeAktivitas`, `destroyAktivitas`).
  - View: [`resources/views/asrama/aktivitas.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/aktivitas.blade.php) (Tabel daftar agenda rutinitas santri 24 jam, metrik ringkasan sesi harian/ibadah/tahfidz/wajib, alur timeline visual 5 waktu asrama, badge sifat wajib/pilihan, modal tambah agenda aktivitas baru berbasis sesi, filter kategori, cetak).
  - Rute aktif `/asrama/aktivitas`, `/asrama/aktivitas` (POST), `/asrama/aktivitas/{id}/destroy`.
- `[x] T08.4`: Leave Permission Workflow (Perizinan Santri Asrama):
  - Dataset baseline perizinan keluar, pulang bermalam & darurat di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getLeavePermissions`, `findLeavePermission`).
  - Controller: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`perizinan`, `storePerizinan`, `checkInPerizinan`, `destroyPerizinan`).
  - View: [`resources/views/asrama/perizinan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/perizinan.blade.php) (Tabel perizinan santri, tiket Gate Pass dengan barcode & kode validasi, metrik izin diajukan/disetujui/kembali, modal rincian izin & cetak Gate Pass pos satpam, aksi check-in konfirmasi kembali di asrama, modal buat izin baru, filter tipe/status, cetak).
  - Rute aktif `/asrama/perizinan`, `/asrama/perizinan` (POST), `/asrama/perizinan/{id}/checkin`, `/asrama/perizinan/{id}/destroy`.
- `[x] T08.5`: Attendance / Monitoring (Presensi Ibadah & Asrama):
  - Dataset baseline presensi sesi ibadah asrama di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getBoardingAttendance`, `findBoardingAttendance`).
  - Controller: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`presensi`, `storePresensi`, `markAllPresent`).
  - View: [`resources/views/asrama/presensi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/presensi.blade.php) (Tabel presensi sesi ibadah harian santri mukim aktif, selector tanggal & 7 sesi shalat/qiyamul lail/naum, tombol cepat Quick Present Hadirkan Semua, radio button status Hadir/Masbuq/Sakit/Izin/Alpa, input catatan musyrif, cetak lembar presensi).
  - Rute aktif `/asrama/presensi`, `/asrama/presensi` (POST), `/asrama/presensi/quick-present` (POST), serta penambahan sub-menu di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).
- `[x] T08.6`: Meals / Consumption (Manajemen Katering & Dapur Asrama):
  - Dataset baseline 21 variasi menu makan mingguan lengkap (Senin s/d Ahad: Pagi/Siang/Malam) di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getBoardingMeals`, `findBoardingMeal`).
  - Controller: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`katering`, `storeKatering`, `destroyKatering`).
  - View: [`resources/views/asrama/katering.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/katering.blade.php) (Dwi-tab Matriks Jadwal Mingguan & Semua Daftar Menu, metrik 4 info box variasi menu/sarapan/siang/kalori kkal, rincian lauk utama/pendamping/sayur/buah/susu, sorotan standar gizi 4 Sehat 5 Sempurna, catatan alergen & porsi khusus, modal detail hidangan, modal tambah menu makanan baru berbasis sesi, filter hari/waktu makan, tombol cetak jadwal dapur asrama).
  - Rute aktif `/asrama/katering`, `/asrama/katering` (POST), `/asrama/katering/{id}/destroy`.
- `[x] T08.7`: Boarding Reports (Laporan & Rekapitulasi Terpadu Asrama):
  - Controller: [`AsramaController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/AsramaController.php) (`laporan`).
  - View: [`resources/views/asrama/laporan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/asrama/laporan.blade.php) (4 Tab Laporan Asrama: Okupansi Gedung & Kamar Santri, Log Rekap Perizinan Keluar/Pulang Santri, Rekapitulasi Presensi Shalat Berjamaah 5 Waktu & Ibadah Asrama, Evaluasi Menu & Logistik Dapur Santri, tombol cetak laporan untuk setiap tab).
  - Rute aktif `/asrama/laporan` dan penambahan sub-menu Laporan Asrama di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).
  - Penanda penutupan **Phase 08 (Boarding Management)** selesai 100% (T08.1 s/d T08.7).
- `[x] T09.1`: Finance Dashboard (Dashboard Eksekutif Keuangan & SPP):
  - Inisialisasi dataset Keuangan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getFeeCategories`, `getStudentInvoices`, `getPaymentTransactions`, `getFinanceExpenses`).
  - Controller baru: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`dashboard`).
  - View: [`resources/views/keuangan/dashboard.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/dashboard.blade.php) (4 Info box metrik KPI: Penerimaan Kas, Beban Pengeluaran, Saldo Kas Bersih, Piutang SPP; Breakdown Status Invoice Lunas/Sebagian/Belum Lunas/Jatuh Tempo; Tabel Beban Pengeluaran Operasional; Tabel Penerimaan Pembayaran Kasir Terkini; Tabel Tagihan Santri Aktif; Filter Unit Pendidikan & Periode Bulan; Tombol Cetak Laporan Keuangan).
  - Rute aktif `/keuangan/dashboard` di [`routes/web.php`](file:///c:/laragon/www/prototipe-alia/routes/web.php).
- `[x] T09.2`: Fee Master (Master Data Pos Biaya & Tarif Sekolah):
  - Dataset pos biaya & tarif pendidikan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getFeeCategories`, `findFeeCategory`).
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`posBiaya`, `storePosBiaya`, `destroyPosBiaya`).
  - View: [`resources/views/keuangan/pos-biaya.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/pos-biaya.blade.php) (Tabel master tarif, filter unit/kategori/siklus/search, modal detail tarif, modal form tambah pos biaya baru berbasis sesi, format cetak tarif).
  - Rute aktif `/keuangan/pos-biaya`, `/keuangan/pos-biaya` (POST), dan `/keuangan/pos-biaya/{id}/destroy`.
- `[x] T09.3`: Student Invoices (Manajemen Tagihan & Invoice Santri):
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`tagihan`, `storeTagihan`, `destroyTagihan`).
  - View: [`resources/views/keuangan/tagihan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/tagihan.blade.php) (Tabel buku tagihan santri, 4 kartu info box total tagihan/lunas/menunggu/piutang, modal pratinjau invoice resmi berkop Alia Islamic School dengan instruksi VA BSI, modal form penerbitan invoice baru berbasis sesi, filter unit/status/bulan/search, format cetak).
  - Rute aktif `/keuangan/tagihan`, `/keuangan/tagihan` (POST), dan `/keuangan/tagihan/{id}/destroy`.
- `[x] T09.4`: Payment Simulation (Simulasi Penerimaan Kasir & Pembayaran):
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`transaksi`, `storeTransaksi`, `destroyTransaksi`).
  - View: [`resources/views/keuangan/transaksi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/transaksi.blade.php) (Log kuitansi kasir terverifikasi, 4 info box metrik transaksi kas/VA BSI/Tunai/QRIS, callout label prototipe simulasi, modal bukti kuitansi resmi printable, modal form input pembayaran baru yang secara otomatis memperbarui status invoice terkait dan saldo kas masuk, filter metode/tanggal/search).
  - Rute aktif `/keuangan/transaksi`, `/keuangan/transaksi` (POST), dan `/keuangan/transaksi/{id}/destroy`.
- `[x] T09.5`: Scholarship & Discount (Manajemen Beasiswa & Keringanan Biaya):
  - Inisialisasi dataset beasiswa di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getScholarships`, `findScholarship`).
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`beasiswa`, `storeBeasiswa`, `approveBeasiswa`, `destroyBeasiswa`).
  - View: [`resources/views/keuangan/beasiswa.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/beasiswa.blade.php) (Tabel penerima beasiswa, 4 info box total santri/disetujui/menunggu/subsidi ZISWAF, aksi persetujuan cepat pimpinan, modal SK Beasiswa Resmi Berkop Alia, modal form pendaftaran baru, filter kategori beasiswa, format cetak).
  - Rute aktif `/keuangan/beasiswa`, `/keuangan/beasiswa` (POST), `/keuangan/beasiswa/{id}/approve`, `/keuangan/beasiswa/{id}/destroy`, serta penambahan navigasi menu di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).
- `[x] T09.6`: Arrears (Manajemen Piutang & Surat Peringatan Tunggakan):
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`tunggakan`, `sendTunggakanNotice`).
  - View: [`resources/views/keuangan/tunggakan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/tunggakan.blade.php) (Tabel aging report piutang santri, 4 kartu info box aging 1-30/31-60/>60 hari kritis, modal penerbitan SP-1/SP-2/SP-3 WhatsApp, modal surat pemberitahuan tunggakan resmi berkop sekolah, filter unit/aging/kelas/search).
  - Rute aktif `/keuangan/tunggakan`, `/keuangan/tunggakan/send-notice` (POST), dan penambahan sub-menu di sidebar.
- `[x] T09.7`: Cash In/Out (Buku Kas Umum & Mutasi Arus Kas):
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`arusKas`, `storeArusKas`, `destroyArusKas`).
  - View: [`resources/views/keuangan/arus-kas.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/arus-kas.blade.php) (Tabel jurnal buku kas umum integrasi debit/kredit, 4 info box total kas masuk/keluar/saldo/mutasi, modal pencatatan pengeluaran baru berbasis sesi, modal cetak bukti voucher kas masuk BKM & keluar BKK, filter aliran kas/kategori/bulan/search, cetak buku kas).
  - Rute aktif `/keuangan/arus-kas`, `/keuangan/arus-kas` (POST), `/keuangan/arus-kas/{id}/destroy`, serta penambahan sub-menu di sidebar.
- `[x] T09.8`: Finance Reports (Laporan Keuangan Komprehensif):
  - Controller: [`KeuanganController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/KeuanganController.php) (`laporan`).
  - View: [`resources/views/keuangan/laporan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/keuangan/laporan.blade.php) (4 Tab: Rekap Penerimaan SPP & Pos Biaya, Laporan Piutang & Aging Tunggakan, Arus Kas / Cash Flow In/Out, Ringkasan Kinerja per Unit Pendidikan, 4 KPI info box Finansial, filter unit pendidikan & periode bulan, tombol ekspor cetak/PDF/Excel simulasi).
  - Rute aktif `/keuangan/laporan` dan sub-menu di sidebar AdminLTE.
- `[x] T10.1`: Employee Profiles & Organization (Profil Pegawai & Struktur Organisasi):
  - Controller baru: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`pegawai`, `storePegawai`, `updatePegawai`, `destroyPegawai`).
  - View: [`resources/views/hris/pegawai.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/pegawai.blade.php) (3 Tab: Daftar Profil Pegawai, Bagan Struktur Organisasi 4 Tingkat, Analitik & Distribusi SDM, 4 KPI info box total pegawai/GTY/kontrak/tendik, modal kartu profil pegawai, modal edit pegawai berbasis sesi, form tambah pegawai baru, filter unit/status/gender/search, cetak).
  - Integrasi session update pegawai di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php).
  - Rute aktif `/hris/pegawai`, `/hris/pegawai` (POST), `/hris/pegawai/{id}/update` (POST), `/hris/pegawai/{id}/destroy`, serta link menu di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).
- `[x] T10.2`: Staff Attendance (Presensi Pegawai & Guru):
  - Dataset baseline presensi harian pegawai, jam masuk & pulang, mesin biometrik di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStaffAttendance`, `findStaffAttendance`).
  - Controller: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`presensi`, `storePresensi`, `markAllPresent`, `destroyPresensi`).
  - View: [`resources/views/hris/presensi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/presensi.blade.php) (3 Tab: Buku Presensi Harian, Rekapitulasi Kehadiran & Kedisiplinan, Status Mesin Biometrik Online, 5 info box kehadiran harian hadir/terlambat/izin/sakit/alpa, aksi Quick Attendance Hadirkan Semua, modal input presensi manual, modal lembar verifikasi presensi, filter tanggal/unit/status/search, cetak).
  - Rute aktif `/hris/presensi`, `/hris/presensi` (POST), `/hris/presensi/quick-present` (POST), `/hris/presensi/{id}/destroy`, serta update link di sidebar.
- `[x] T10.3`: Leave / Permission Workflow (Pengajuan & Persetujuan Cuti Pegawai):
  - Dataset baseline permohonan cuti, kategori cuti, durasi, dan guru pengganti di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStaffLeaves`, `findStaffLeave`).
  - Controller: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`cuti`, `storeCuti`, `approveCuti`, `destroyCuti`).
  - View: [`resources/views/hris/cuti.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/cuti.blade.php) (Dwi-tab Daftar Permohonan Cuti & Monitoring Saldo Hak Cuti 12 Hari, 4 info box status cuti diajukan/disetujui/ditolak, modal surat izin cuti resmi berkop Alia Islamic School, modal keputusan persetujuan atasan/pimpinan, form pengajuan cuti baru dengan penugasan guru badal, filter status/kategori/unit/search, cetak).
  - Rute aktif `/hris/cuti`, `/hris/cuti` (POST), `/hris/cuti/{id}/approve` (POST), `/hris/cuti/{id}/destroy`, serta link menu di sidebar.
- `[x] T10.4`: Overtime Management (Lembur Pegawai & SPKL):
  - Dataset baseline surat perintah kerja lembur (SPKL), durasi jam, tarif per jam, dan kompensasi honor di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStaffOvertimes`, `findStaffOvertime`).
  - Controller: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`lembur`, `storeLembur`, `approveLembur`, `destroyLembur`).
  - View: [`resources/views/hris/lembur.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/lembur.blade.php) (3 Tab: Daftar SPKL, Rekapitulasi Jam & Honor per Pegawai, Ketentuan Standar Tarif Yayasan, 4 info box total SPKL/jam/kompensasi honor/menunggu approval, modal fisik SPKL berkop Alia, modal approval & penandaan status pembayaran, form penerbitan SPKL baru, filter status/kategori/unit/search, cetak).
  - Rute aktif `/hris/lembur`, `/hris/lembur` (POST), `/hris/lembur/{id}/approve` (POST), `/hris/lembur/{id}/destroy`, serta sub-menu di [`resources/views/layouts/sidebar.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/layouts/sidebar.blade.php).
- `[x] T10.5`: Performance Evaluation (Evaluasi Kinerja Pegawai / PKG):
  - Dataset baseline penilaian kinerja guru & tendik di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStaffEvaluations`, `findStaffEvaluation`).
  - Controller: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`evaluasi`, `storeEvaluasi`, `destroyEvaluasi`).
  - View: [`resources/views/hris/evaluasi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/evaluasi.blade.php) (3 Tab: Buku Rekap Penilaian PKG, Rubrik 4 Pilar Kompetensi & Bobot Pedagogik 30%/Profesional 30%/Kepribadian 20%/Sosial 20%, Monitoring Pegawai Belum Dievaluasi, 4 info box total evaluasi/predikat A/predikat B/rata-rata skor, modal lembar rapor kinerja resmi berkop Alia Islamic School, form instrumen asesmen baru, filter predikat/unit/search, cetak).
  - Rute aktif `/hris/evaluasi`, `/hris/evaluasi` (POST), `/hris/evaluasi/{id}/destroy`, serta link menu di sidebar.
- `[x] T10.6`: Payroll Simulation (Simulasi Penggajian & Payroll Pegawai):
  - Dataset baseline penggajian bulanan pegawai, rekening bank syariah, struktur tunjangan lengkap, dan potongan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStaffPayrolls`, `findStaffPayroll`).
  - Controller: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`penggajian`, `storePenggajian`, `approvePenggajian`, `destroyPenggajian`).
  - View: [`resources/views/hris/penggajian.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/penggajian.blade.php) (3 Tab: Buku Penggajian & Slip Gaji, Struktur Komponen Gaji & Tunjangan, Pemantauan Pegawai Belum Diproses Payroll, 4 info box total slip/gaji bruto/total potongan/gaji bersih THP, modal slip gaji digital resmi berkop Alia Islamic School, aksi konfirmasi transfer bank, form penerbitan slip gaji baru, filter bulan/unit/status/search, cetak).
  - Rute aktif `/hris/penggajian`, `/hris/penggajian` (POST), `/hris/penggajian/{id}/approve` (POST), `/hris/penggajian/{id}/destroy`, serta sub-menu aktif di sidebar.
- `[x] T10.7`: Competency Development (Pengembangan Kompetensi & Pelatihan):
  - Dataset baseline program diklat pegawai, sertifikasi profesi pendidik, ijazah sanad tahfidz Quran, jam pelajaran, dan biaya pelatihan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getStaffTrainings`, `findStaffTraining`).
  - Controller: [`HrisController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/HrisController.php) (`pelatihan`, `storePelatihan`, `completePelatihan`, `destroyPelatihan`).
  - View: [`resources/views/hris/pelatihan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/hris/pelatihan.blade.php) (3 Tab: Riwayat Program Pelatihan, Buku Registrasi Sertifikat & Sanad, Roadmap Peningkatan Kualifikasi Guru 2026-2028, 4 info box total program/selesai/akumulasi jam/investasi diklat, modal surat tugas resmi berkop Alia Islamic School, modal konfirmasi penyelesaian sertifikat, form pendaftaran pelatihan baru, filter status/kategori/unit/search, cetak).
  - Rute aktif `/hris/pelatihan`, `/hris/pelatihan` (POST), `/hris/pelatihan/{id}/complete` (POST), `/hris/pelatihan/{id}/destroy`, serta link menu di sidebar.

### Batch F — Facilities, Inventory, Portals (Phase 11 Sarpras & Fasilitas)
- `[x] T11.1`: Facility Registry (Registrasi Sarana & Prasarana Kampus):
  - Dataset master aset sarpras fisik di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getFacilityAssets`, `findFacilityAsset`) mencakup tanah SHM Wakaf, gedung bertingkat (Umar, Thalhah, Khadijah), lapangan futsal/basket, lab komputer/bahasa/sains, masjid jami', gardu & genset utilitas 100 kVA.
  - Controller: [`SarprasController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/SarprasController.php) (`fasilitas`, `storeFasilitas`, `updateKondisi`, `destroyFasilitas`).
  - View: [`resources/views/sarpras/fasilitas.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/sarpras/fasilitas.blade.php) (3 Tab: Buku Register Aset Sarpras, Ruangan & Fasilitas Khusus, Rekapitulasi & Valuasi Aset, 4 info box total aset/nilai perolehan/kondisi prima/perlu pemeliharaan, modal kartu inventaris aset resmi berkop Alia Islamic School, modal pembaruan kondisi fisik, form pendaftaran aset baru, filter kategori/kondisi/search, cetak).
  - Rute aktif `/sarpras/fasilitas`, `/sarpras/fasilitas` (POST), `/sarpras/fasilitas/{id}/kondisi` (POST), `/sarpras/fasilitas/{id}/destroy`, serta update link di sidebar AdminLTE.
- `[x] T11.2`: Room Usage & Booking (Peminjaman Ruangan & Fasilitas Khusus):
  - Dataset baseline peminjaman ruangan di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getRoomBookings`, `findRoomBooking`) dengan daftar tempat pinjam lab komputer/sains/bahasa, aula serbaguna, masjid, dan lapangan olahraga.
  - Controller: [`SarprasController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/SarprasController.php) (`peminjaman`, `storePeminjaman`, `approvePeminjaman`, `destroyPeminjaman`) dengan sistem auto-conflict detection jadwal jika ada jam yang bertabrakan.
  - View: [`resources/views/sarpras/peminjaman.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/sarpras/peminjaman.blade.php) (3 Tab: Daftar Peminjaman & Status, Jadwal Pemakaian Ruangan Linimasa Timeline, Antrean Persetujuan Sarpras, 4 info box total/pending/disetujui/selesai, modal surat izin pemakaian resmi berkop Alia Islamic School, modal keputusan persetujuan & alasan penolakan, form permohonan baru, filter ruangan/status/tanggal/search, cetak).
  - Rute aktif `/sarpras/peminjaman`, `/sarpras/peminjaman` (POST), `/sarpras/peminjaman/{id}/approve` (POST), `/sarpras/peminjaman/{id}/destroy`, serta link menu di sidebar AdminLTE.
- `[x] T11.3`: Maintenance & Damage Reports (Pemeliharaan & Laporan Kerusakan Sarpras):
  - Dataset baseline tiket kerusakan dan log servis di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getMaintenanceReports`, `findMaintenanceReport`) mencakup kelistrikan genset, AC split bocor, rembesan plafon gypsum, kran wudhu masjid patah, perbaikan jaring lapangan, lampu sorot LED gerbang.
  - Controller: [`SarprasController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/SarprasController.php) (`pemeliharaan`, `storePemeliharaan`, `updateProgressPemeliharaan`, `destroyPemeliharaan`).
  - View: [`resources/views/sarpras/pemeliharaan.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/sarpras/pemeliharaan.blade.php) (3 Tab: Buku Tiket Kerusakan & Servis, Tim Teknisi & Vendor Rekanan, Rekapitulasi Biaya & Kategori Servis, 4 info box total tiket/kasus darurat/dalam pengerjaan/selesai, modal lembar kerja SPK servis resmi berkop Alia Islamic School, modal update progres pengerjaan teknisi & realisasi biaya, form laporan kerusakan baru, filter status/prioritas/kategori/search, cetak).
  - Rute aktif `/sarpras/pemeliharaan`, `/sarpras/pemeliharaan` (POST), `/sarpras/pemeliharaan/{id}/progress` (POST), `/sarpras/pemeliharaan/{id}/destroy`, serta submenu aktif di sidebar.























