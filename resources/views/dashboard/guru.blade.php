@extends('layouts.app')

@section('title', 'Dashboard Guru / Pengajar')
@section('page_title', 'Dashboard Guru & Pengajar')
@section('page_description', 'Jadwal Mengajar, Presensi Kelas, dan Evaluasi Tugas Santri')

@section('breadcrumbs')
  <li class="active">Dashboard Guru</li>
@endsection

@section('content')

  <!-- Role Notification Alert -->
  <div class="callout callout-success" style="border-left-color: #38a169;">
    <h4><i class="fa fa-pencil"></i> Mode Peran: Guru / Asatidz ({{ session('user_name', 'Ustazah Aisyah Salsabila, S.Pd.I.') }})</h4>
    <p>Kelola kelas aktif Anda: input nilai formatif/sumatif, catat kehadiran santri pada jam pelajaran, dan periksa penugasan LMS.</p>
  </div>

  <!-- Primary Stat Cards -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="4" label="Jadwal Mengajar Hari Ini" icon="fa-calendar-check-o" bg="aqua" :url="route('akademik.jadwal')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="148" label="Total Santri Yang Diampu" icon="fa-users" bg="green" :url="route('akademik.nilai')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="18" label="Tugas LMS Butuh Penilaian" icon="fa-tasks" bg="yellow" :url="route('lms.tugas')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="28" label="Santri Binaan Halaqah" icon="fa-book" bg="purple" :url="route('tahfidz.setoran')" />
    </div>
  </div>

  <!-- Row: Jadwal Hari Ini & Halaqah Tahfidz -->
  <div class="row">
    <div class="col-md-7">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-clock-o text-green"></i> Jadwal Mengajar Hari Ini (Kamis)</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped table-hover">
            <thead>
              <tr style="background:#f4f6f9;">
                <th>Jam</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Ruangan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>07:30 - 09:00</strong></td>
                <td>X MIPA 1</td>
                <td>PAI & Akhlak Lil Banin</td>
                <td>R. 101 Gedung Umar</td>
                <td><a href="{{ route('akademik.presensi') }}" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check-square"></i> Input Presensi</a></td>
              </tr>
              <tr>
                <td><strong>09:15 - 10:45</strong></td>
                <td>XI IPS</td>
                <td>Sejarah Kebudayaan Islam</td>
                <td>R. 203 Gedung Ali</td>
                <td><a href="{{ route('akademik.presensi') }}" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i> Jurnal Mengajar</a></td>
              </tr>
              <tr>
                <td><strong>13:00 - 14:30</strong></td>
                <td>VII B</td>
                <td>Tahsin & Tahfidz Halaqah 2</td>
                <td>Masjid Lt. 1</td>
                <td><a href="{{ route('tahfidz.setoran') }}" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-microphone"></i> Catat Setoran</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Kotak Tugas Masuk LMS -->
    <div class="col-md-5">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-text-o text-muted"></i> Pengumpulan Tugas LMS Terkini</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('lms.tugas') }}" class="btn btn-xs btn-default">Semua Tugas</a>
          </div>
        </div>
        <div class="box-body">
          <ul class="products-list product-list-in-box">
            <li class="item">
              <div class="product-info" style="margin-left: 0;">
                <a href="{{ route('lms.tugas') }}" class="product-title text-primary">
                  Ahmad Fauzan (X MIPA 1)
                  <span class="label label-success pull-right">Tepat Waktu</span>
                </a>
                <span class="product-description">
                  Makalah: Penerapan Prinsip Syariah dalam Muamalah Modern
                </span>
              </div>
            </li>
            <li class="item">
              <div class="product-info" style="margin-left: 0;">
                <a href="{{ route('lms.tugas') }}" class="product-title text-primary">
                  Siti Fatimah (VII B)
                  <span class="label label-success pull-right">Tepat Waktu</span>
                </a>
                <span class="product-description">
                  Rekaman Audio: Pelafalan Makharijul Huruf Surah An-Naba
                </span>
              </div>
            </li>
            <li class="item">
              <div class="product-info" style="margin-left: 0;">
                <a href="{{ route('lms.tugas') }}" class="product-title text-primary">
                  Muhammad Bilal (X MIPA 1)
                  <span class="label label-warning pull-right">Terlambat 1 Jam</span>
                </a>
                <span class="product-description">
                  Ringkasan Hadits Arba'in ke-5 tentang Perkara Bid'ah
                </span>
              </div>
            </li>
          </ul>
        </div>
        <div class="box-footer text-center">
          <a href="{{ route('lms.tugas') }}" class="uppercase">Nilai Semua Kiriman &raquo;</a>
        </div>
      </div>
    </div>
  </div>

@endsection

