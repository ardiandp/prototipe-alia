@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')
@section('page_title', 'Dashboard Kepala Sekolah')
@section('page_description', 'Monitoring Pembelajaran, Guru, dan Ketertiban Santri Unit')

@section('breadcrumbs')
  <li class="active">Dashboard Kepala Sekolah</li>
@endsection

@section('content')

  <!-- Role Notification Alert -->
  <div class="callout callout-info" style="border-left-color: #2b6cb0;">
    <h4><i class="fa fa-briefcase"></i> Mode Peran: Kepala Sekolah ({{ session('user_name', 'Drs. H. Mulyadi, M.Pd.') }})</h4>
    <p>Tampilan ini menyajikan metrik operasional harian: presensi guru & santri hari ini, progres pengisian e-rapor, evaluasi pembelajaran, serta pengajuan izin/mutasi.</p>
  </div>

  <!-- Primary Stat Cards -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="245" label="Santri Aktif Unit" icon="fa-users" bg="aqua" :url="route('master.siswa')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="98.2%" label="Presensi Santri Hari Ini" icon="fa-check-circle-o" bg="green" :url="route('akademik.presensi')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="22 / 24" label="Guru Mengajar Hadir" icon="fa-graduation-cap" bg="yellow" :url="route('hris.presensi')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="88.5%" label="Progres Nilai & E-Rapor" icon="fa-file-text-o" bg="purple" :url="route('akademik.rapor')" />
    </div>
  </div>

  <!-- Row: Monitoring Guru & Status Pembelajaran -->
  <div class="row">
    <div class="col-md-7">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list-alt text-blue"></i> Monitoring Kehadiran Kelas & Jurnal Guru Hari Ini</h3>
          <div class="box-tools pull-right">
            <span class="label label-primary">Pekan ke-12</span>
          </div>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped table-hover">
            <thead>
              <tr style="background:#f4f6f9;">
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Guru Pengajar</th>
                <th>Jam Ke-</th>
                <th>Status Jurnal</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>X MIPA 1</strong></td>
                <td>Matematika Peminatan</td>
                <td>Rahmat Hidayat, M.Sc.</td>
                <td>1 - 2 (07:30 - 09:00)</td>
                <td><span class="label label-success">Terisi (97% Hadir)</span></td>
              </tr>
              <tr>
                <td><strong>X MIPA 2</strong></td>
                <td>Bahasa Arab & Nahwu</td>
                <td>Ust. Salman Al-Farisi</td>
                <td>1 - 2 (07:30 - 09:00)</td>
                <td><span class="label label-success">Terisi (100% Hadir)</span></td>
              </tr>
              <tr>
                <td><strong>XI MIPA</strong></td>
                <td>Fisika Inti</td>
                <td>Nurul Hasanah, M.Pd.</td>
                <td>3 - 4 (09:15 - 10:45)</td>
                <td><span class="label label-primary">Sedang Berlangsung</span></td>
              </tr>
              <tr>
                <td><strong>XII IPS</strong></td>
                <td>Sosiologi Islam</td>
                <td>Budi Santoso, S.Kom.</td>
                <td>5 - 6 (11:00 - 12:30)</td>
                <td><span class="label label-warning">Belum Mulai</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Kotak Pengajuan & Persetujuan Tertunda -->
    <div class="col-md-5">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bell-o text-yellow"></i> Butuh Persetujuan Kepala Sekolah (3)</h3>
        </div>
        <div class="box-body">
          <ul class="products-list product-list-in-box">
            <li class="item">
              <div class="product-info" style="margin-left: 0;">
                <a href="{{ route('hris.cuti') }}" class="product-title text-primary">
                  Pengajuan Cuti Melahirkan (Khadijah Nurul Fikri, S.Pd.)
                  <span class="label label-warning pull-right">Menunggu</span>
                </a>
                <span class="product-description">
                  Permohonan cuti 40 hari mulai 15 November 2026.
                </span>
              </div>
            </li>
            <li class="item">
              <div class="product-info" style="margin-left: 0;">
                <a href="{{ route('kesiswaan.konseling') }}" class="product-title text-primary">
                  Rekomendasi Pemanggilan Orang Tua (Santri: SIS-0003)
                  <span class="label label-warning pull-right">Menunggu</span>
                </a>
                <span class="product-description">
                  Diserahkan oleh Guru BK (Poin pelanggaran kedisiplinan mencapai 45).
                </span>
              </div>
            </li>
            <li class="item">
              <div class="product-info" style="margin-left: 0;">
                <a href="{{ route('akademik.kalender') }}" class="product-title text-primary">
                  Proposal Rihlah Ilmiah Santri Kelas XI
                  <span class="label label-info pull-right">Reviu</span>
                </a>
                <span class="product-description">
                  Diajukan panitia kesiswaan untuk persetujuan tanggal pelaksanaan.
                </span>
              </div>
            </li>
          </ul>
        </div>
        <div class="box-footer text-center">
          <a href="{{ route('hris.cuti') }}" class="uppercase">Buka Pusat Persetujuan &raquo;</a>
        </div>
      </div>
    </div>
  </div>

@endsection

