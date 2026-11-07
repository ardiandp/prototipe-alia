# PROGRESS.md --- IRNIS School Prototype

## Project status

**Overall status:** NOT STARTED\
**Current phase:** PHASE 00 --- Repository Audit & Foundation\
**Current task:** T00.1 --- Inspect existing project\
**Last updated:** 2026-11-06

## Project constraints

-   Existing Laravel project; inspect before changing.
-   AdminLTE 2.x; verify exact Bootstrap, jQuery, and plugin versions.
-   UI-first prototype for Alia Islamic School.
-   Blue/purple Alia identity with Irnis Dev branding.
-   Faker-only fictional demo data.
-   No database schema, migrations, or database-backed persistence.
-   Session-backed demo mutations only if compatible with the existing
    project.
-   Bahasa Indonesia UI.

## Phase tracker

  Phase   Description                     Status
  ------- ------------------------------- -------------
  00      Repository audit & foundation   COMPLETED
  01      Dashboard & executive view      COMPLETED
  02      Master data sekolah             COMPLETED
  03      PPDB Online                     IN PROGRESS
  04      Akademik & Pembelajaran         NOT STARTED
  05      LMS / E-Learning                NOT STARTED
  06      Tahfidz & Diniyah               NOT STARTED
  07      Kesiswaan & Pembinaan           NOT STARTED
  08      Asrama / Boarding               NOT STARTED
  09      Keuangan & SPP                  NOT STARTED
  10      SDM / HRIS                      NOT STARTED
  11      Sarana Prasarana                NOT STARTED
  12      Inventaris                      NOT STARTED
  13      Parent & Student Portals        NOT STARTED
  14      AI School Assistant             NOT STARTED
  15      Reports & Analytics             NOT STARTED
  16      System Settings                 NOT STARTED
  17      Integration & QA                NOT STARTED

## Task log

  ---------------------------------------------------------------------------------------
  Task ID           Task              Status            Notes
  ----------------- ----------------- ----------------- ---------------------------------
  T00.1             Inspect existing  COMPLETED         Laravel 13.40.0, PHP 8.3.28,
                    project                             AdminLTE 2.4.2 (Bootstrap 3.3.7,
                                                        jQuery 3.2.1) audited.

  T00.2             Establish         COMPLETED         No-DB architecture established;
                    prototype                           file session confirmed; docs/ARCHITECTURE.md
                    constraints                         created.

  T00.3             Integrate/reuse   COMPLETED         AdminLTE 2.4.2 base layout, header,
                    AdminLTE 2 base                     sidebar, footer, and .irnis-*
                    layout                              identity styles created.

  T00.4             Build reusable UI COMPLETED         Blade components: card, stat-card,
                    partials                            toolbar, badge, empty-state, modal.
                                                        Demo page at /components-demo.

  T00.5             Establish route   COMPLETED         58 routes mapped with sidebar
                    and navigation                      navigation and active states.
                    skeleton                            

  T00.6             Demo data         COMPLETED         App\Support\DemoData\DemoDataManager
                    foundation                          deterministic Faker baseline seeded.

  T01.1             Yayasan           COMPLETED         Executive Yayasan Dashboard built with
                    dashboard                           KPI cards, Chart.js visualizations,
                                                        filter bar, audit trail & agenda.

  T01.2             Role dashboard    COMPLETED         Dedicated dashboard views for
                    variants                            Kepala Sekolah, Guru, Bendahara,
                                                        and Pembina Asrama connected to session switcher.

  T02.1             Unit pendidikan   COMPLETED         Unit list, filter by level, search,
                                                        add unit modal, detail modal, delete action.

  T02.2             Tahun ajaran and  COMPLETED         Academic year and semester list,
                    semester                            active indicator banner, switch active
                                                        period action, and session creation modal.

  T02.3             Kelas /           COMPLETED         Classes list, homeroom teacher allocation,
                    rombongan belajar                   capacity progress bars, filter by unit & grade,
                                                        add rombel modal, detail modal, and delete action.

  T02.4             Ruangan           COMPLETED         Rooms and facilities list, condition status,
                                                        filter by room type & condition, capacity tracking,
                                                        add room modal, and delete action.

  T02.5             Mata pelajaran    COMPLETED         Subjects list, curriculum groups (Nasional,
                                                        Kepesantrenan, Muatan Lokal), KKM standard,
                                                        filter by group, add mapel modal, delete action.

  T02.6             Data siswa        COMPLETED         Comprehensive Student Master Table, NIS/NISN,
                                                        unit & boarding filter, search, multi-tab modal detail
                                                        (Biodata, Wali, Akademik, Tahfidz, Keuangan, Asrama).

  T02.7             Guru dan pegawai  COMPLETED         Staff & teacher table, NIP, position,
                                                        unit, employment status (GTY/Kontrak),
                                                        filter by unit & status, add staff modal, delete action.

  T02.8             Orang tua/wali    COMPLETED         Guardian list, connected student references,
                                                        WhatsApp contact action, address, add guardian modal,
                                                        and delete action.

  T03.1             PPDB dashboard    COMPLETED         PPDB executive dashboard, applicant funnel metrics,
                                                        track distribution chart (Reguler/Tahfidz/Akademik/Afirmasi),
                                                        quota progress bar, and recent applicants table.

  T03.2             Applicant         COMPLETED         Applicant register table, track & status filter,
                    list/detail                         exam scores, guardian WA links, detail modal,
                                                        and session delete action.

  T03.3             Registration form COMPLETED         Online public application wizard, 3-step form
                                                        (Unit & track, Student bio, Guardian details),
                                                        validation, and registration number generation.

  T03.4             Document          COMPLETED         Document verification queue, checklist
                    verification                        (Akta, KK, Rapor, Piagam Tahfidz), approve/reject
                                                        modal with notes, and status updates.

  T03.5             Selection         COMPLETED         Selection exam scoring (Academic 40%,
                    workflow                            Quran 40%, Interview 20%), status decisions (Lulus/
                                                        Tidak Lulus/Proses), and notes.

  T03.6             Announcement/re-  NOT STARTED       Public announcement letter and re-registration
                    registration                        payment simulation.
  ---------------------------------------------------------------------------------------

## Repository audit findings

*Completed by OpenCode during T00.1 (2026-11-06).*

-   Laravel version: 13.40.0
-   PHP version: PHP 8.3.28 (CLI via Laragon: `C:\laragon\bin\php\8.3.28\php.exe`)
-   AdminLTE version/location: AdminLTE 2.4.2 located at `public/AdminLTE/` (static copy with bower_components, dist, build, plugins, pages, documentation)
-   Bootstrap version: 3.3.7 (in `public/AdminLTE/bower_components/bootstrap`)
-   jQuery version: 3.2.1 (in `public/AdminLTE/bower_components/jquery`)
-   Font Awesome version: 4.7.0 (in `public/AdminLTE/bower_components/font-awesome`)
-   DataTables availability: Tersedia (v1.10.15 + datatables.net-bs di `public/AdminLTE/bower_components/datatables.net*`)
-   Chart.js availability: Tersedia (v1.0.* di `public/AdminLTE/bower_components/chart.js`), juga tersedia Morris.js, Flot, Sparkline
-   Select2 availability: Tersedia (v4.0.3/4.0.4 di `public/AdminLTE/bower_components/select2`)
-   SweetAlert availability: Tidak tersedia bawaan di AdminLTE 2.4.2 maupun composer/npm. Alternatif: gunakan Bootstrap 3 standard modals, alert callouts, atau bootstrap alert banners/native JS alerts tanpa menambah library external.
-   Current session driver: `database` (di `.env` tertulis `SESSION_DRIVER=database`). Perlu disesuaikan menjadi `SESSION_DRIVER=file` atau `cookie` pada T00.2 agar tidak membutuhkan database.
-   Existing layout path: Belum ada layout blade khusus (hanya `resources/views/welcome.blade.php`). Layout AdminLTE 2 akan dibuat di `resources/views/layouts/` dengan mereferensikan aset `public/AdminLTE/`.
-   Existing route conventions: Hanya route default `/` di `routes/web.php`.
-   Existing asset/build process: Tersedia Vite (`vite.config.js` & `package.json`), namun aset AdminLTE 2 sudah terpasang mandiri di `public/AdminLTE/` dan dapat langsung diakses via `asset('AdminLTE/...')` tanpa build step.
-   Official logos/images: Logo spesifik Alia / Irnis Dev tidak ditemukan di repositori (hanya sampel avatar dan ikon bawaan AdminLTE). Perlu dibuat SVG/CSS placeholder logo yang rapi dan elegan sesuai identitas biru/ungu Alia Islamic School.
-   Relevant risks:
    - `php` default belum terdaftar di global Windows PATH terminal sandbox, tetapi dapat diakses langsung via `C:\laragon\bin\php\8.3.28\php.exe`.
    - Session saat ini diset ke `database` di `.env` padahal tidak boleh menggunakan database, sehingga harus diubah ke `file` agar demo interaktif session-based dapat berjalan mulus.

## Completed work

- [x] **T00.1 Inspect existing project**: Audit repositori, dependensi, dan pencatatan temuan.
- [x] **T00.2 Establish prototype constraints**: Penyesuaian `SESSION_DRIVER=file`, isolasi batas non-database, dan pembuatan `docs/ARCHITECTURE.md`.
- [x] **T00.3 Integrate AdminLTE 2 base layout**: Integrasi template AdminLTE 2.4.2 ke Blade (`layouts/app.blade.php`, `header.blade.php`, `sidebar.blade.php`, `footer.blade.php`) dan penambahan styling `.irnis-*` warna Alia Islamic School (biru & ungu).
- [x] **T00.4 Build reusable UI partials**: Komponen reusable Blade `<x-card>`, `<x-stat-card>`, `<x-toolbar>`, `<x-badge>`, `<x-empty-state>`, modal konfirmasi Bootstrap 3, serta halaman katalog `/components-demo`.
- [x] **T00.5 Establish route and navigation skeleton**: Seluruh 58 route modul prototipe terpetakan dengan sidebar responsif dan status aktif.
- [x] **T00.6 Demo data foundation**: `App\Support\DemoData\DemoDataManager` dengan deterministic Faker seeding untuk menjamin stabilitas ID dan relasi data siswa, wali, guru, kelas, asrama, dan keuangan.
- [x] **T01.1 Yayasan dashboard**: Membangun tampilan utama Dashboard Eksekutif Yayasan di `dashboard/yayasan.blade.php` lengkap dengan filter Unit & Tahun Ajaran, metrik KPI santri & keuangan, grafik Chart.js (Doughnut dan Bar), riwayat aktivitas (audit trail), kalender agenda, dan panel akses cepat.
- [x] **T01.2 Role dashboard variants**: Membangun varian dashboard khusus untuk peran:
  - `dashboard/kepsek.blade.php`: Monitoring kehadiran guru, jurnal harian, dan approval permohonan.
  - `dashboard/guru.blade.php`: Jadwal mengajar hari ini, input presensi cepat, dan evaluasi tugas LMS.
  - `dashboard/bendahara.blade.php`: Realisasi kasir penerimaan SPP, kuitansi terbaru, dan tombol broadcast WA tagihan.
  - `dashboard/asrama.blade.php`: Mutasi perizinan keluar/pesiar, siklus menu katering santri mukim, dan apel subuh.
  Terhubung dinamis melalui role switcher di header navigasi.
- [x] **T02.1 Unit pendidikan**: Membangun modul Master Data Unit Pendidikan di `master/unit.blade.php` lengkap dengan statistik santri/rombel, filter jenjang, pencarian, modal detail profil unit, form modal tambah unit baru, serta simulasi hapus unit berbasis mutasi sesi.
- [x] **T02.2 Tahun ajaran and semester**: Membangun modul Master Data Tahun Ajaran & Semester di `master/tahun-ajaran.blade.php` lengkap dengan banner penanda periode aktif, filter semester ganjil/genap, pencarian, formulir modal tambah periode baru, serta aksi tombol alih status periode aktif (switching active year) secara realtime via session state.
- [x] **T02.3 Kelas / rombongan belajar**: Membangun modul Master Data Kelas & Rombongan Belajar di `master/kelas.blade.php` lengkap dengan kartu ringkasan kapasitas santri & okupansi, visual progress bar kapasitas rombel, filter unit & tingkat, alokasi wali kelas dari data guru terpusat, modal detail rombel, form modal tambah rombel, serta penghapusan rombel sesi demo.
- [x] **T02.4 Ruangan**: Membangun modul Master Data Ruangan & Fasilitas di `master/ruangan.blade.php` lengkap dengan kartu ringkasan kondisi ruangan (baik/perbaikan/kapasitas kursi), filter kategori ruangan (Kelas Teori, Lab, Perpustakaan, Aula, Masjid, UKS), filter kondisi fasilitas, modal detail ruangan, formulir tambah ruangan baru, serta simulasi penghapusan berbasis session.
- [x] **T02.5 Mata pelajaran**: Membangun modul Master Data Mata Pelajaran di `master/mapel.blade.php` lengkap dengan kartu ringkasan jumlah mapel, kurikulum Nasional, Diniyah/Kepesantrenan, Muatan Lokal, rata-rata KKM, filter kelompok kurikulum, alokasi jam JP per pekan, modal rincian mapel, formulir modal tambah mapel, serta simulasi hapus mapel demo.
- [x] **T02.6 Data siswa**: Membangun modul Master Data Buku Induk Santri di `master/siswa.blade.php` lengkap dengan kartu ringkasan santri boarding/fullday, filter unit & status hunian, pencarian nama/NIS/NISN/wali, modal dialog profil santri interaktif multi-tab (Biodata, Orang Tua/Wali, Akademik, Capaian Tahfidz, Keuangan SPP, dan Status Asrama), form modal registrasi santri baru, serta simulasi penghapusan berbasis session.
- [x] **T02.7 Guru dan pegawai**: Membangun modul Master Data Guru & Pegawai di `master/pegawai.blade.php` lengkap dengan statistik guru GTY & asatidz diniyah, filter unit & status kepegawaian, pencarian nama/NIP/mapel/jabatan, modal profil pendidik, formulir penambahan pegawai baru, serta simulasi penghapusan berbasis session.
- [x] **T02.8 Orang tua/wali**: Membangun modul Master Data Orang Tua & Wali Santri di `master/orang-tua.blade.php` lengkap dengan statistik wali terdata, tautan relasi santri terdaftar, nomor WhatsApp aktif, modal detail wali santri dengan tautan ke simulasi portal orang tua, formulir pendaftaran wali baru, serta simulasi penghapusan berbasis session.
- [x] **T03.1 PPDB dashboard**: Membangun antarmuka Dashboard PPDB Online di `ppdb/dashboard.blade.php` lengkap dengan filter unit jenjang, corong pendaftaran (formulir masuk, verifikasi berkas, kelulusan seleksi, daftar ulang), grafik donat distribusi jalur masuk (Reguler, Prestasi Tahfidz, Prestasi Akademik, Afirmasi/Yatim), persentase capaian kuota & tingkat konversi, serta tabel pendaftar terbaru.
- [x] **T03.2 Applicant list/detail**: Membangun antarmuka Buku Register Pendaftar PPDB di `ppdb/pendaftar.blade.php` lengkap dengan kartu ringkasan pendaftar lulus/tahfidz/berkas, filter jenjang, filter jalur masuk, filter status kelulusan seleksi, modal rincian berkas calon santri & wali, nilai hasil ujian tes, serta simulasi penghapusan berbasis session.
- [x] **T03.3 Registration form**: Membangun formulir pendaftaran daring mandiri calon santri baru di `ppdb/form.blade.php` dengan wizard 3 langkah (Pilihan Jenjang & Jalur, Identitas Lengkap Santri, Data Orang Tua/Wali), validasi input server-side, otomatisasi penerbitan nomor registrasi unik `PPDB-2026-xxx`, dan integrasi ke antrean verifikasi panitia.
- [x] **T03.4 Document verification**: Membangun antarmuka verifikasi berkas PPDB di `ppdb/verifikasi.blade.php` lengkap dengan statistik berkas antre/lengkap/ditolak, filter status berkas, tabel pemeriksaan dokumen persyaratan (Akta Kelahiran, KK, Rapor, Piagam Tahfidz), serta modal keputusan verifikator untuk menyetujui atau menolak berkas dengan catatan revisi.
- [x] **T03.5 Selection workflow**: Membangun antarmuka Seleksi & Pengumuman PPDB di `ppdb/seleksi.blade.php` lengkap dengan perhitungan skor berbobot (Tes Akademik 40%, Tes Al-Qur'an 40%, Wawancara 20%), modal input nilai & penetapan keputusan kelulusan (LULUS, TIDAK LULUS, DALAM PROSES), serta pembaruan realtime pada sesi demo.

## Checks performed

- Run `C:\laragon\bin\php\8.3.28\php.exe -v` (Passed: PHP 8.3.28)
- Run `C:\laragon\bin\php\8.3.28\php.exe artisan --version` (Passed: Laravel Framework 13.40.0)
- Run `C:\laragon\bin\php\8.3.28\php.exe artisan route:list` (Passed: 58 routes registered)
- HTTP Endpoints Verification via Kernel: `/components-demo`, `/dashboard`, `/master/siswa`, `/ppdb/pendaftar`, `/tahfidz/setoran`, `/asrama/kamar`, `/keuangan/tagihan`, `/portal/dashboard` (Passed: Status 200 OK)
- HTTP Endpoint Verification for `/dashboard` with query filters `?unit=UNIT-SMA&ta=TA-2026-1` (Passed: Status 200 OK)
- HTTP Role Switching & Verification for all dashboard variants [yayasan, kepsek, guru, bendahara, asrama, wali] (Passed: All Status 200 OK)
- HTTP Verification for `/master/unit` (Passed: Status 200 OK)
- HTTP Verification for `/master/unit?search=SMP&level=SMP` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/unit/destroy/{id}` (Passed: Redirect 302 to /master/unit with session flash)
- HTTP Verification for `/master/tahun-ajaran` (Passed: Status 200 OK)
- HTTP Verification for `/master/tahun-ajaran?semester=Ganjil` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/tahun-ajaran/activate/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/master/kelas` (Passed: Status 200 OK)
- HTTP Verification for `/master/kelas?unit=UNIT-SMA&grade=10` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/kelas/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/master/ruangan` (Passed: Status 200 OK)
- HTTP Verification for `/master/ruangan?type=Laboratorium&condition=baik` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/ruangan/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/master/mapel` (Passed: Status 200 OK)
- HTTP Verification for `/master/mapel?group=Kepesantrenan` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/mapel/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/master/siswa` (Passed: Status 200 OK)
- HTTP Verification for `/master/siswa?unit=SMA&residence=boarding` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/siswa/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/master/pegawai` (Passed: Status 200 OK)
- HTTP Verification for `/master/pegawai?unit=UNIT-SMA` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/master/pegawai/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/master/orang-tua` (Passed: Status 200 OK)
- HTTP Verification for `/master/orang-tua?search=Hendra` (Passed: Status 200 OK, search working)
- HTTP Verification for `/master/orang-tua/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/ppdb/dashboard` (Passed: Status 200 OK)
- HTTP Verification for `/ppdb/dashboard?unit=SMA` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/ppdb/pendaftar` (Passed: Status 200 OK)
- HTTP Verification for `/ppdb/pendaftar?track=Tahfidz` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/ppdb/pendaftar/destroy/{id}` (Passed: Redirect 302 with session update)
- HTTP Verification for `/ppdb/daftar` (Passed: Status 200 OK)
- HTTP Verification for `/ppdb/verifikasi` (Passed: Status 200 OK)
- HTTP Verification for `/ppdb/verifikasi?status=menunggu` (Passed: Status 200 OK, filter working)
- HTTP Verification for `/ppdb/seleksi` (Passed: Status 200 OK)
- HTTP Verification for `/ppdb/seleksi?status=lulus` (Passed: Status 200 OK, filter working)
- Deterministic Faker test on `DemoDataManager::getInstance()->findStudent('SIS-0001')` (Passed: Return stable record Ahmad Fauzan Al-Ghifari)

## Known issues / blockers

- Tidak ada blocker.

## Next action

Lanjut ke **T03.6 Announcement/re-registration** (Pengumuman resmi kelulusan, simulasi pelunasan biaya daftar ulang, dan penerbitan kwitansi).
