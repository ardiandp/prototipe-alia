<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\LmsController;
use App\Http\Controllers\TahfidzController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\AsramaController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\HrisController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PortalController;

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
    Route::get('/tugas', [LmsController::class, 'tugas'])->name('tugas');
    Route::post('/tugas', [LmsController::class, 'storeTugas'])->name('tugas.store');
    Route::get('/tugas/{id}/destroy', [LmsController::class, 'destroyTugas'])->name('tugas.destroy');
    Route::get('/tugas/{assignmentId}/submission', [LmsController::class, 'submission'])->name('tugas.submission');
    Route::post('/tugas/{assignmentId}/grade/{studentId}', [LmsController::class, 'gradeSubmission'])->name('tugas.grade');
    Route::post('/tugas/{assignmentId}/simulate', [LmsController::class, 'simulateUpload'])->name('tugas.simulate');
    Route::get('/ujian', [LmsController::class, 'ujian'])->name('ujian');
    Route::post('/ujian', [LmsController::class, 'storeUjian'])->name('ujian.store');
    Route::get('/ujian/{id}/destroy', [LmsController::class, 'destroyUjian'])->name('ujian.destroy');
    Route::get('/progres', [LmsController::class, 'progres'])->name('progres');
});

// Tahfidz & Diniyah
Route::prefix('tahfidz')->name('tahfidz.')->group(function () {
    Route::get('/target', [TahfidzController::class, 'target'])->name('target');
    Route::post('/halaqah', [TahfidzController::class, 'storeHalaqah'])->name('halaqah.store');
    Route::post('/program', [TahfidzController::class, 'storeProgram'])->name('program.store');
    Route::get('/halaqah/{id}/destroy', [TahfidzController::class, 'destroyHalaqah'])->name('halaqah.destroy');
    Route::post('/halaqah/{id}/assign', [TahfidzController::class, 'assignStudent'])->name('halaqah.assign');
    Route::get('/setoran', [TahfidzController::class, 'setoran'])->name('setoran');
    Route::post('/setoran', [TahfidzController::class, 'storeSetoran'])->name('setoran.store');
    Route::get('/setoran/{id}/destroy', [TahfidzController::class, 'destroySetoran'])->name('setoran.destroy');
    Route::get('/murojaah', [TahfidzController::class, 'murojaah'])->name('murojaah');
    Route::post('/murojaah', [TahfidzController::class, 'storeMurojaah'])->name('murojaah.store');
    Route::get('/murojaah/{id}/destroy', [TahfidzController::class, 'destroyMurojaah'])->name('murojaah.destroy');
    Route::get('/progres', [TahfidzController::class, 'progres'])->name('progres');
    Route::get('/diniyah', [TahfidzController::class, 'diniyah'])->name('diniyah');
    Route::post('/diniyah/kitab', [TahfidzController::class, 'storeSubject'])->name('diniyah.kitab.store');
    Route::post('/diniyah/jadwal', [TahfidzController::class, 'storeSchedule'])->name('diniyah.jadwal.store');
    Route::get('/diniyah/jadwal/{id}/destroy', [TahfidzController::class, 'destroySchedule'])->name('diniyah.jadwal.destroy');
    Route::get('/rapor', [TahfidzController::class, 'rapor'])->name('rapor');
    Route::post('/rapor/{studentId}/catatan', [TahfidzController::class, 'updateCatatanRapor'])->name('rapor.catatan');
});

// Kesiswaan & Pembinaan
Route::prefix('kesiswaan')->name('kesiswaan.')->group(function () {
    Route::get('/pelanggaran', [KesiswaanController::class, 'pelanggaran'])->name('pelanggaran');
    Route::post('/pelanggaran', [KesiswaanController::class, 'storePelanggaran'])->name('pelanggaran.store');
    Route::get('/pelanggaran/{id}/destroy', [KesiswaanController::class, 'destroyPelanggaran'])->name('pelanggaran.destroy');
    Route::post('/pelanggaran/aturan', [KesiswaanController::class, 'storeRule'])->name('pelanggaran.rule.store');
    Route::get('/prestasi', [KesiswaanController::class, 'prestasi'])->name('prestasi');
    Route::post('/prestasi', [KesiswaanController::class, 'storePrestasi'])->name('prestasi.store');
    Route::get('/prestasi/{id}/destroy', [KesiswaanController::class, 'destroyPrestasi'])->name('prestasi.destroy');
    Route::get('/konseling', [KesiswaanController::class, 'konseling'])->name('konseling');
    Route::post('/konseling', [KesiswaanController::class, 'storeKonseling'])->name('konseling.store');
    Route::post('/konseling/{id}/update-status', [KesiswaanController::class, 'updateKonselingStatus'])->name('konseling.update-status');
    Route::get('/konseling/{id}/destroy', [KesiswaanController::class, 'destroyKonseling'])->name('konseling.destroy');
    Route::get('/ekskul', [KesiswaanController::class, 'ekskul'])->name('ekskul');
    Route::post('/ekskul', [KesiswaanController::class, 'storeEkskul'])->name('ekskul.store');
    Route::post('/ekskul/{id}/enroll', [KesiswaanController::class, 'enrollStudent'])->name('ekskul.enroll');
    Route::get('/ekskul/{id}/destroy', [KesiswaanController::class, 'destroyEkskul'])->name('ekskul.destroy');
    Route::get('/uks', [KesiswaanController::class, 'uks'])->name('uks');
    Route::post('/uks/kunjungan', [KesiswaanController::class, 'storeUksVisit'])->name('uks.visit.store');
    Route::post('/uks/obat', [KesiswaanController::class, 'storeUksMedicine'])->name('uks.medicine.store');
    Route::get('/uks/kunjungan/{id}/destroy', [KesiswaanController::class, 'destroyUksVisit'])->name('uks.visit.destroy');
    Route::get('/mutasi', [KesiswaanController::class, 'mutasi'])->name('mutasi');
    Route::post('/mutasi', [KesiswaanController::class, 'storeMutasi'])->name('mutasi.store');
    Route::get('/mutasi/{id}/destroy', [KesiswaanController::class, 'destroyMutasi'])->name('mutasi.destroy');
});

// Asrama / Boarding
Route::prefix('asrama')->name('asrama.')->group(function () {
    Route::get('/kamar', [AsramaController::class, 'kamar'])->name('kamar');
    Route::post('/kamar', [AsramaController::class, 'storeKamar'])->name('kamar.store');
    Route::post('/gedung', [AsramaController::class, 'storeGedung'])->name('gedung.store');
    Route::get('/kamar/{id}/destroy', [AsramaController::class, 'destroyKamar'])->name('kamar.destroy');
    Route::get('/penempatan', [AsramaController::class, 'penempatan'])->name('penempatan');
    Route::post('/penempatan', [AsramaController::class, 'storePenempatan'])->name('penempatan.store');
    Route::post('/penempatan/{id}/transfer', [AsramaController::class, 'transferKamar'])->name('penempatan.transfer');
    Route::get('/penempatan/{id}/checkout', [AsramaController::class, 'checkoutSantri'])->name('penempatan.checkout');
    Route::get('/aktivitas', [AsramaController::class, 'aktivitas'])->name('aktivitas');
    Route::post('/aktivitas', [AsramaController::class, 'storeAktivitas'])->name('aktivitas.store');
    Route::get('/aktivitas/{id}/destroy', [AsramaController::class, 'destroyAktivitas'])->name('aktivitas.destroy');
    Route::get('/perizinan', [AsramaController::class, 'perizinan'])->name('perizinan');
    Route::post('/perizinan', [AsramaController::class, 'storePerizinan'])->name('perizinan.store');
    Route::get('/perizinan/{id}/checkin', [AsramaController::class, 'checkInPerizinan'])->name('perizinan.checkin');
    Route::get('/perizinan/{id}/destroy', [AsramaController::class, 'destroyPerizinan'])->name('perizinan.destroy');
    Route::get('/presensi', [AsramaController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [AsramaController::class, 'storePresensi'])->name('presensi.store');
    Route::post('/presensi/quick-present', [AsramaController::class, 'markAllPresent'])->name('presensi.quick');
    Route::get('/katering', [AsramaController::class, 'katering'])->name('katering');
    Route::post('/katering', [AsramaController::class, 'storeKatering'])->name('katering.store');
    Route::get('/katering/{id}/destroy', [AsramaController::class, 'destroyKatering'])->name('katering.destroy');
    Route::get('/laporan', [AsramaController::class, 'laporan'])->name('laporan');
});

// Keuangan & SPP
Route::prefix('keuangan')->name('keuangan.')->group(function () {
    Route::get('/dashboard', [KeuanganController::class, 'dashboard'])->name('dashboard');
    Route::get('/pos-biaya', [KeuanganController::class, 'posBiaya'])->name('pos-biaya');
    Route::post('/pos-biaya', [KeuanganController::class, 'storePosBiaya'])->name('pos-biaya.store');
    Route::get('/pos-biaya/{id}/destroy', [KeuanganController::class, 'destroyPosBiaya'])->name('pos-biaya.destroy');
    Route::get('/tagihan', [KeuanganController::class, 'tagihan'])->name('tagihan');
    Route::post('/tagihan', [KeuanganController::class, 'storeTagihan'])->name('tagihan.store');
    Route::get('/tagihan/{id}/destroy', [KeuanganController::class, 'destroyTagihan'])->name('tagihan.destroy');
    Route::get('/transaksi', [KeuanganController::class, 'transaksi'])->name('transaksi');
    Route::post('/transaksi', [KeuanganController::class, 'storeTransaksi'])->name('transaksi.store');
    Route::get('/transaksi/{id}/destroy', [KeuanganController::class, 'destroyTransaksi'])->name('transaksi.destroy');
    Route::get('/beasiswa', [KeuanganController::class, 'beasiswa'])->name('beasiswa');
    Route::post('/beasiswa', [KeuanganController::class, 'storeBeasiswa'])->name('beasiswa.store');
    Route::get('/beasiswa/{id}/approve', [KeuanganController::class, 'approveBeasiswa'])->name('beasiswa.approve');
    Route::get('/beasiswa/{id}/destroy', [KeuanganController::class, 'destroyBeasiswa'])->name('beasiswa.destroy');
    Route::get('/tunggakan', [KeuanganController::class, 'tunggakan'])->name('tunggakan');
    Route::post('/tunggakan/send-notice', [KeuanganController::class, 'sendTunggakanNotice'])->name('tunggakan.notice');
    Route::get('/arus-kas', [KeuanganController::class, 'arusKas'])->name('arus-kas');
    Route::post('/arus-kas', [KeuanganController::class, 'storeArusKas'])->name('arus-kas.store');
    Route::get('/arus-kas/{id}/destroy', [KeuanganController::class, 'destroyArusKas'])->name('arus-kas.destroy');
    Route::get('/laporan', [KeuanganController::class, 'laporan'])->name('laporan');
});

// SDM / HRIS
Route::prefix('hris')->name('hris.')->group(function () {
    Route::get('/pegawai', [HrisController::class, 'pegawai'])->name('pegawai');
    Route::post('/pegawai', [HrisController::class, 'storePegawai'])->name('pegawai.store');
    Route::post('/pegawai/{id}/update', [HrisController::class, 'updatePegawai'])->name('pegawai.update');
    Route::get('/pegawai/{id}/destroy', [HrisController::class, 'destroyPegawai'])->name('pegawai.destroy');
    Route::get('/presensi', [HrisController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [HrisController::class, 'storePresensi'])->name('presensi.store');
    Route::post('/presensi/quick-present', [HrisController::class, 'markAllPresent'])->name('presensi.quick');
    Route::get('/presensi/{id}/destroy', [HrisController::class, 'destroyPresensi'])->name('presensi.destroy');
    Route::get('/cuti', [HrisController::class, 'cuti'])->name('cuti');
    Route::post('/cuti', [HrisController::class, 'storeCuti'])->name('cuti.store');
    Route::post('/cuti/{id}/approve', [HrisController::class, 'approveCuti'])->name('cuti.approve');
    Route::get('/cuti/{id}/destroy', [HrisController::class, 'destroyCuti'])->name('cuti.destroy');
    Route::get('/lembur', [HrisController::class, 'lembur'])->name('lembur');
    Route::post('/lembur', [HrisController::class, 'storeLembur'])->name('lembur.store');
    Route::post('/lembur/{id}/approve', [HrisController::class, 'approveLembur'])->name('lembur.approve');
    Route::get('/lembur/{id}/destroy', [HrisController::class, 'destroyLembur'])->name('lembur.destroy');
    Route::get('/evaluasi', [HrisController::class, 'evaluasi'])->name('evaluasi');
    Route::post('/evaluasi', [HrisController::class, 'storeEvaluasi'])->name('evaluasi.store');
    Route::get('/evaluasi/{id}/destroy', [HrisController::class, 'destroyEvaluasi'])->name('evaluasi.destroy');
    Route::get('/penggajian', [HrisController::class, 'penggajian'])->name('penggajian');
    Route::post('/penggajian', [HrisController::class, 'storePenggajian'])->name('penggajian.store');
    Route::post('/penggajian/{id}/approve', [HrisController::class, 'approvePenggajian'])->name('penggajian.approve');
    Route::get('/penggajian/{id}/destroy', [HrisController::class, 'destroyPenggajian'])->name('penggajian.destroy');
    Route::get('/pelatihan', [HrisController::class, 'pelatihan'])->name('pelatihan');
    Route::post('/pelatihan', [HrisController::class, 'storePelatihan'])->name('pelatihan.store');
    Route::post('/pelatihan/{id}/complete', [HrisController::class, 'completePelatihan'])->name('pelatihan.complete');
    Route::get('/pelatihan/{id}/destroy', [HrisController::class, 'destroyPelatihan'])->name('pelatihan.destroy');
});

// Sarpras & Inventaris
Route::prefix('sarpras')->name('sarpras.')->group(function () {
    Route::get('/fasilitas', [SarprasController::class, 'fasilitas'])->name('fasilitas');
    Route::post('/fasilitas', [SarprasController::class, 'storeFasilitas'])->name('fasilitas.store');
    Route::post('/fasilitas/{id}/kondisi', [SarprasController::class, 'updateKondisi'])->name('fasilitas.kondisi');
    Route::get('/fasilitas/{id}/destroy', [SarprasController::class, 'destroyFasilitas'])->name('fasilitas.destroy');
    Route::get('/peminjaman', [SarprasController::class, 'peminjaman'])->name('peminjaman');
    Route::post('/peminjaman', [SarprasController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::post('/peminjaman/{id}/approve', [SarprasController::class, 'approvePeminjaman'])->name('peminjaman.approve');
    Route::get('/peminjaman/{id}/destroy', [SarprasController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');
    Route::get('/pemeliharaan', [SarprasController::class, 'pemeliharaan'])->name('pemeliharaan');
    Route::post('/pemeliharaan', [SarprasController::class, 'storePemeliharaan'])->name('pemeliharaan.store');
    Route::post('/pemeliharaan/{id}/progress', [SarprasController::class, 'updateProgressPemeliharaan'])->name('pemeliharaan.progress');
    Route::get('/pemeliharaan/{id}/destroy', [SarprasController::class, 'destroyPemeliharaan'])->name('pemeliharaan.destroy');
    Route::get('/pengadaan', [SarprasController::class, 'pengadaan'])->name('pengadaan');
    Route::post('/pengadaan', [SarprasController::class, 'storePengadaan'])->name('pengadaan.store');
    Route::post('/pengadaan/{id}/approve', [SarprasController::class, 'approvePengadaan'])->name('pengadaan.approve');
    Route::get('/pengadaan/{id}/destroy', [SarprasController::class, 'destroyPengadaan'])->name('pengadaan.destroy');
});

Route::prefix('inventaris')->name('inventaris.')->group(function () {
    Route::get('/barang', [InventarisController::class, 'barang'])->name('barang');
    Route::post('/barang', [InventarisController::class, 'storeBarang'])->name('barang.store');
    Route::post('/barang/{id}', [InventarisController::class, 'updateBarang'])->name('barang.update');
    Route::get('/barang/{id}/destroy', [InventarisController::class, 'destroyBarang'])->name('barang.destroy');
    Route::get('/penerimaan', [InventarisController::class, 'penerimaan'])->name('penerimaan');
    Route::post('/penerimaan', [InventarisController::class, 'storePenerimaan'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}/destroy', [InventarisController::class, 'destroyPenerimaan'])->name('penerimaan.destroy');
    Route::get('/pengeluaran', [InventarisController::class, 'pengeluaran'])->name('pengeluaran');
    Route::post('/pengeluaran', [InventarisController::class, 'storePengeluaran'])->name('pengeluaran.store');
    Route::get('/pengeluaran/{id}/destroy', [InventarisController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');
    Route::get('/pengadaan', [InventarisController::class, 'pengadaan'])->name('pengadaan');
    Route::post('/pengadaan', [InventarisController::class, 'storePengadaan'])->name('pengadaan.store');
    Route::post('/pengadaan/{id}/approve', [InventarisController::class, 'approvePengadaan'])->name('pengadaan.approve');
    Route::post('/pengadaan/{id}/receive', [InventarisController::class, 'receivePengadaan'])->name('pengadaan.receive');
    Route::get('/pengadaan/{id}/destroy', [InventarisController::class, 'destroyPengadaan'])->name('pengadaan.destroy');
    Route::get('/pemeliharaan', [InventarisController::class, 'pemeliharaan'])->name('pemeliharaan');
    Route::post('/pemeliharaan', [InventarisController::class, 'storePemeliharaan'])->name('pemeliharaan.store');
    Route::post('/pemeliharaan/{id}/status', [InventarisController::class, 'updateStatusPemeliharaan'])->name('pemeliharaan.status');
    Route::get('/pemeliharaan/{id}/destroy', [InventarisController::class, 'destroyPemeliharaan'])->name('pemeliharaan.destroy');
    Route::get('/laporan', [InventarisController::class, 'laporan'])->name('laporan');
    Route::get('/stok', [InventarisController::class, 'laporan'])->name('stok');
});

// Portal Wali / Santri
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'parentDashboard'])->name('dashboard');
    Route::get('/wali', [PortalController::class, 'parentDashboard'])->name('parent');
    Route::get('/santri', [PortalController::class, 'studentDashboard'])->name('student');
    Route::get('/santri/lms', [PortalController::class, 'studentLms'])->name('student.lms');
    Route::post('/santri/tugas/submit', [PortalController::class, 'submitTugas'])->name('student.tugas.submit');
});

// AI Assistant & Laporan & Pengaturan
Route::get('/ai-assistant', makePlaceholder('AI School Assistant Demo', 'Asisten Cerdas Analisis Data Sekolah', 'fa-magic'))->name('ai.assistant');
Route::get('/laporan', makePlaceholder('Pusat Laporan & Ekspor', 'Ekspor Data Rekapitulasi Sekolah', 'fa-file-pdf-o'))->name('laporan.index');

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', makePlaceholder('Pengaturan Sistem', 'Identitas Sekolah & Konfigurasi Prototype', 'fa-cogs'))->name('index');
    Route::get('/profile', makePlaceholder('Profil Akun', 'Informasi Pengguna Demo', 'fa-user'))->name('profile');
});
