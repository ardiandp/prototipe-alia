<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\LmsController;

// Helper untuk route placeholder view
function makePlaceholder($title, $subtitle, $icon = 'fa-cube') {
    return function() use ($title, $subtitle, $icon) {
        return view('placeholder', [
            'title' => $title,
            'subtitle' => $subtitle,
            'icon' => $icon
        ]);
    };
}

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Demo Session Actions
Route::get('/demo/switch-role/{role}', [DemoController::class, 'switchRole'])->name('demo.switch-role');
Route::get('/demo/reset', [DemoController::class, 'resetDemo'])->name('demo.reset');

// UI Components Catalog & Styleguide
Route::get('/components-demo', function () {
    return view('components-demo');
})->name('components.demo');

// Dashboard & Executive View
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Master Data Sekolah
Route::prefix('master')->name('master.')->group(function () {
    Route::get('/unit', [MasterDataController::class, 'unit'])->name('unit');
    Route::post('/unit', [MasterDataController::class, 'storeUnit'])->name('unit.store');
    Route::get('/unit/destroy/{id}', [MasterDataController::class, 'destroyUnit'])->name('unit.destroy');
    Route::get('/tahun-ajaran', [MasterDataController::class, 'tahunAjaran'])->name('tahun-ajaran');
    Route::post('/tahun-ajaran', [MasterDataController::class, 'storeTahunAjaran'])->name('tahun-ajaran.store');
    Route::get('/tahun-ajaran/activate/{id}', [MasterDataController::class, 'activateTahunAjaran'])->name('tahun-ajaran.activate');
    Route::get('/tahun-ajaran/destroy/{id}', [MasterDataController::class, 'destroyTahunAjaran'])->name('tahun-ajaran.destroy');
    Route::get('/kelas', [MasterDataController::class, 'kelas'])->name('kelas');
    Route::post('/kelas', [MasterDataController::class, 'storeKelas'])->name('kelas.store');
    Route::get('/kelas/destroy/{id}', [MasterDataController::class, 'destroyKelas'])->name('kelas.destroy');
    Route::get('/ruangan', [MasterDataController::class, 'ruangan'])->name('ruangan');
    Route::post('/ruangan', [MasterDataController::class, 'storeRuangan'])->name('ruangan.store');
    Route::get('/ruangan/destroy/{id}', [MasterDataController::class, 'destroyRuangan'])->name('ruangan.destroy');
    Route::get('/mapel', [MasterDataController::class, 'mapel'])->name('mapel');
    Route::post('/mapel', [MasterDataController::class, 'storeMapel'])->name('mapel.store');
    Route::get('/mapel/destroy/{id}', [MasterDataController::class, 'destroyMapel'])->name('mapel.destroy');
    Route::get('/siswa', [MasterDataController::class, 'siswa'])->name('siswa');
    Route::post('/siswa', [MasterDataController::class, 'storeSiswa'])->name('siswa.store');
    Route::get('/siswa/destroy/{id}', [MasterDataController::class, 'destroySiswa'])->name('siswa.destroy');
    Route::get('/pegawai', [MasterDataController::class, 'pegawai'])->name('pegawai');
    Route::post('/pegawai', [MasterDataController::class, 'storePegawai'])->name('pegawai.store');
    Route::get('/pegawai/destroy/{id}', [MasterDataController::class, 'destroyPegawai'])->name('pegawai.destroy');
    Route::get('/orang-tua', [MasterDataController::class, 'orangTua'])->name('orang-tua');
    Route::post('/orang-tua', [MasterDataController::class, 'storeOrangTua'])->name('orang-tua.store');
    Route::get('/orang-tua/destroy/{id}', [MasterDataController::class, 'destroyOrangTua'])->name('orang-tua.destroy');
});

// PPDB Online
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/dashboard', [PpdbController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftar', [PpdbController::class, 'pendaftar'])->name('pendaftar');
    Route::get('/pendaftar/destroy/{id}', [PpdbController::class, 'destroyPendaftar'])->name('pendaftar.destroy');
    Route::get('/daftar', [PpdbController::class, 'form'])->name('form');
    Route::post('/daftar', [PpdbController::class, 'storeForm'])->name('form.store');
    Route::get('/verifikasi', [PpdbController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/verifikasi/{id}', [PpdbController::class, 'updateVerifikasi'])->name('verifikasi.update');
    Route::get('/seleksi', [PpdbController::class, 'seleksi'])->name('seleksi');
    Route::post('/seleksi/{id}', [PpdbController::class, 'updateSeleksi'])->name('seleksi.update');
    Route::get('/daftar-ulang', [PpdbController::class, 'daftarUlang'])->name('daftar-ulang');
    Route::post('/daftar-ulang/{id}/bayar', [PpdbController::class, 'bayarDaftarUlang'])->name('daftar-ulang.pay');
    Route::post('/daftar-ulang/{id}/konversi', [PpdbController::class, 'konversiSiswa'])->name('daftar-ulang.konversi');
});

// Akademik & Pembelajaran
Route::prefix('akademik')->name('akademik.')->group(function () {
    Route::get('/rombel', [AcademicController::class, 'rombel'])->name('rombel');
    Route::post('/rombel', [AcademicController::class, 'storeRombel'])->name('rombel.store');
    Route::post('/rombel/{classId}/student', [AcademicController::class, 'addStudentToRombel'])->name('rombel.student.add');
    Route::get('/rombel/{classId}/student/remove/{studentId}', [AcademicController::class, 'removeStudentFromRombel'])->name('rombel.student.remove');
    Route::get('/jadwal', [AcademicController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal', [AcademicController::class, 'storeJadwal'])->name('jadwal.store');
    Route::get('/jadwal/destroy/{id}', [AcademicController::class, 'destroyJadwal'])->name('jadwal.destroy');
    Route::get('/kalender', [AcademicController::class, 'kalender'])->name('kalender');
    Route::post('/kalender', [AcademicController::class, 'storeKalender'])->name('kalender.store');
    Route::get('/kalender/destroy/{id}', [AcademicController::class, 'destroyKalender'])->name('kalender.destroy');
    Route::get('/presensi', [AcademicController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [AcademicController::class, 'storePresensi'])->name('presensi.store');
    Route::get('/nilai', [AcademicController::class, 'nilai'])->name('nilai');
    Route::post('/nilai', [AcademicController::class, 'storeNilai'])->name('nilai.store');
    Route::get('/rapor', [AcademicController::class, 'rapor'])->name('rapor');
    Route::post('/rapor/{studentId}/catatan', [AcademicController::class, 'updateCatatanRapor'])->name('rapor.catatan');
    Route::get('/kenaikan', [AcademicController::class, 'kenaikan'])->name('kenaikan');
    Route::post('/kenaikan', [AcademicController::class, 'storeKenaikan'])->name('kenaikan.store');
});

// LMS E-Learning
Route::prefix('lms')->name('lms.')->group(function () {
    Route::get('/kelas-digital', [LmsController::class, 'kelasDigital'])->name('kelas-digital');
    Route::post('/kelas-digital', [LmsController::class, 'storeKelasDigital'])->name('kelas-digital.store');
    Route::get('/kelas-digital/{id}', [LmsController::class, 'showKelasDigital'])->name('kelas-digital.show');
    Route::get('/kelas-digital/{id}/destroy', [LmsController::class, 'destroyKelasDigital'])->name('kelas-digital.destroy');
    Route::get('/materi', [LmsController::class, 'materi'])->name('materi');
    Route::post('/materi', [LmsController::class, 'storeMateri'])->name('materi.store');
    Route::get('/materi/{id}/toggle', [LmsController::class, 'togglePublishMateri'])->name('materi.toggle');
    Route::get('/materi/{id}/destroy', [LmsController::class, 'destroyMateri'])->name('materi.destroy');
    Route::get('/tugas', makePlaceholder('Tugas & Submission', 'Penugasan & Pengumpulan PR Siswa', 'fa-tasks'))->name('tugas');
    Route::get('/ujian', makePlaceholder('Ujian Online / CBT', 'Computer Based Testing & Bank Soal', 'fa-question-circle'))->name('ujian');
});

// Tahfidz & Diniyah
Route::prefix('tahfidz')->name('tahfidz.')->group(function () {
    Route::get('/target', makePlaceholder('Target & Halaqah', 'Pembagian Kelompok Halaqah Al-Quran', 'fa-bookmark'))->name('target');
    Route::get('/setoran', makePlaceholder('Setoran Hafalan (Ziyadah)', 'Catatan Surat, Ayat, & Nilai Kelancaran', 'fa-microphone'))->name('setoran');
    Route::get('/murojaah', makePlaceholder('Murojaah & Ujian Tahfidz', 'Pemantauan Pengulangan Hafalan', 'fa-repeat'))->name('murojaah');
    Route::get('/diniyah', makePlaceholder('Kurikulum Diniyah', 'Kitab Kuning, Hadits, & Bahasa Arab', 'fa-book'))->name('diniyah');
});

// Kesiswaan & Pembinaan
Route::prefix('kesiswaan')->name('kesiswaan.')->group(function () {
    Route::get('/pelanggaran', makePlaceholder('Poin & Pelanggaran', 'Sistem Tata Tertib & Buku Poin', 'fa-exclamation-triangle'))->name('pelanggaran');
    Route::get('/prestasi', makePlaceholder('Prestasi Siswa', 'Catatan Penghargaan & Lomba', 'fa-star'))->name('prestasi');
    Route::get('/konseling', makePlaceholder('Bimbingan Konseling (BK)', 'Konsultasi & Layanan Pembinaan', 'fa-comments-o'))->name('konseling');
    Route::get('/ekskul', makePlaceholder('Ekstrakurikuler', 'Kegiatan Pilihan & Bakat Santri', 'fa-soccer-ball-o'))->name('ekskul');
});

// Asrama / Boarding
Route::prefix('asrama')->name('asrama.')->group(function () {
    Route::get('/kamar', makePlaceholder('Kamar & Santri Asrama', 'Alokasi Gedung & Penempatan Kamar', 'fa-bed'))->name('kamar');
    Route::get('/aktivitas', makePlaceholder('Jadwal Aktivitas Asrama', 'Qiyamul Lail, Shalat Berjamaah, Disiplin', 'fa-clock-o'))->name('aktivitas');
    Route::get('/perizinan', makePlaceholder('Perizinan Keluar/Pulang', 'Workflow Izin Pulang Santri', 'fa-ticket'))->name('perizinan');
    Route::get('/katering', makePlaceholder('Konsumsi & Dapur', 'Menu Makanan & Jadwal Makan Santri', 'fa-cutlery'))->name('katering');
});

// Keuangan & SPP
Route::prefix('keuangan')->name('keuangan.')->group(function () {
    Route::get('/dashboard', makePlaceholder('Dashboard Keuangan', 'Rekap Arus Kas, SPP, & Tagihan', 'fa-line-chart'))->name('dashboard');
    Route::get('/pos-biaya', makePlaceholder('Pos Biaya & Tarif', 'Struktur SPP, Uang Makan, Daftar Ulang', 'fa-tags'))->name('pos-biaya');
    Route::get('/tagihan', makePlaceholder('Tagihan Santri', 'Daftar Invoice & Surat Tagihan', 'fa-file-invoice-dollar'))->name('tagihan');
    Route::get('/transaksi', makePlaceholder('Penerimaan Pembayaran', 'Pencatatan Kasir & Validasi Bayar', 'fa-credit-card'))->name('transaksi');
    Route::get('/laporan', makePlaceholder('Laporan Keuangan', 'Laporan Penerimaan & Tunggakan', 'fa-balance-scale'))->name('laporan');
});

// SDM / HRIS
Route::prefix('hris')->name('hris.')->group(function () {
    Route::get('/presensi', makePlaceholder('Presensi Pegawai', 'Kehadiran Harian Tenaga Pendidik', 'fa-clock-o'))->name('presensi');
    Route::get('/cuti', makePlaceholder('Pengajuan Cuti / Izin', 'Persetujuan & Riwayat Izin Cuti', 'fa-envelope-open-o'))->name('cuti');
    Route::get('/penggajian', makePlaceholder('Penggajian (Payroll)', 'Slip Gaji & Tunjangan Bulanan', 'fa-money'))->name('penggajian');
});

// Sarpras & Inventaris
Route::prefix('sarpras')->name('sarpras.')->group(function () {
    Route::get('/fasilitas', makePlaceholder('Fasilitas & Aset', 'Daftar Tanah, Gedung, & Sarana', 'fa-university'))->name('fasilitas');
    Route::get('/pemeliharaan', makePlaceholder('Pemeliharaan Sarpras', 'Log Perbaikan & Servis Alat', 'fa-wrench'))->name('pemeliharaan');
});

Route::prefix('inventaris')->name('inventaris.')->group(function () {
    Route::get('/stok', makePlaceholder('Stok & ATK', 'Inventaris Barang Masuk/Keluar', 'fa-boxes'))->name('stok');
});

// Portal Wali / Santri
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', makePlaceholder('Portal Orang Tua & Santri', 'Ringkasan Rapor, Tahfidz, SPP, & Izin Asrama', 'fa-mobile-phone'))->name('dashboard');
});

// AI Assistant & Laporan & Pengaturan
Route::get('/ai-assistant', makePlaceholder('AI School Assistant Demo', 'Asisten Cerdas Analisis Data Sekolah', 'fa-magic'))->name('ai.assistant');
Route::get('/laporan', makePlaceholder('Pusat Laporan & Ekspor', 'Ekspor Data Rekapitulasi Sekolah', 'fa-file-pdf-o'))->name('laporan.index');

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', makePlaceholder('Pengaturan Sistem', 'Identitas Sekolah & Konfigurasi Prototype', 'fa-cogs'))->name('index');
    Route::get('/profile', makePlaceholder('Profil Akun', 'Informasi Pengguna Demo', 'fa-user'))->name('profile');
});
