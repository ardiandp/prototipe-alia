# PROGRESS.md — IRNIS School Management System Prototype

Catatan pemantauan progres implementasi prototipe Alia Islamic School.

---

## 📌 Status Terkini

- **Batch Aktif**: Batch D (Learning, Islamic Programs, Student Affairs)
- **Task Terakhir Selesai**:
  - `T05.2`: Learning materials (Katalog Materi & Modul Pembelajaran Digital, upload bahan ajar PDF/Video/Audio/Link interaktif, filter tipe format, preview detail modul, aksi toggle publish/draft, dan hapus berkas materi).
- **Task Selanjutnya**: `T05.3`: Assignments (Penugasan & PR Santri, deadline, instruksi tugas, dan pembagian kelas).

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
- `[x] T05.2`: Learning materials:
  - Dataset baseline 7 modul & bahan ajar di [`DemoDataManager.php`](file:///c:/laragon/www/prototipe-alia/app/Support/DemoData/DemoDataManager.php) (`getLearningMaterials`, `findLearningMaterial`).
  - Controller: [`LmsController.php`](file:///c:/laragon/www/prototipe-alia/app/Http/Controllers/LmsController.php) (`materi`, `storeMateri`, `togglePublishMateri`, `destroyMateri`).
  - View: [`resources/views/lms/materi.blade.php`](file:///c:/laragon/www/prototipe-alia/resources/views/lms/materi.blade.php) (Katalog modul, badge tipe format PDF/Video/Audio/Link, modal preview/unduh berkas, tombol toggle terbit/draf, modal upload bahan ajar, dan cetak katalog).
  - Rute aktif `/lms/materi`, `/lms/materi` (POST), `/lms/materi/{id}/toggle`, dan `/lms/materi/{id}/destroy`.

