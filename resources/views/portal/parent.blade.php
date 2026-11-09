@extends('layouts.app')

@section('title', 'Portal Wali Santri & Orang Tua')

@push('styles')
<style>
  /* Mobile-first Portal enhancements */
  .portal-header-card {
    background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 60%, #6b46c1 100%);
    color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(26, 54, 93, 0.15);
  }
  .portal-avatar-circle {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    object-fit: cover;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }
  .child-selector-btn {
    border: 2px solid rgba(255, 255, 255, 0.4);
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-weight: 600;
    border-radius: 20px;
    padding: 6px 16px;
    margin-right: 8px;
    margin-top: 8px;
    transition: all 0.25s ease;
    display: inline-block;
    text-decoration: none !important;
  }
  .child-selector-btn:hover, .child-selector-btn.active {
    background: #ffffff;
    color: #1a365d !important;
    border-color: #ffffff;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
  }
  .portal-quick-action {
    display: block;
    background: #ffffff;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    color: #333333;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 15px;
    text-decoration: none !important;
  }
  .portal-quick-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    color: #2b6cb0;
  }
  .portal-quick-action .icon-circle {
    width: 48px;
    height: 48px;
    line-height: 48px;
    border-radius: 50%;
    font-size: 20px;
    margin: 0 auto 10px;
    display: block;
  }
  .nav-tabs-portal {
    border-bottom: 2px solid #2b6cb0;
  }
  .nav-tabs-portal > li > a {
    border-radius: 6px 6px 0 0;
    font-weight: 600;
    color: #555555;
    padding: 10px 18px;
  }
  .nav-tabs-portal > li.active > a,
  .nav-tabs-portal > li.active > a:focus,
  .nav-tabs-portal > li.active > a:hover {
    background-color: #2b6cb0;
    color: #ffffff !important;
    border-color: #2b6cb0;
  }
  @media (max-width: 767px) {
    .portal-header-card {
      padding: 15px;
      text-align: center;
    }
    .portal-header-card .text-right {
      text-align: center !important;
      margin-top: 15px;
    }
    .nav-tabs-portal > li {
      float: none;
      display: inline-block;
      margin-bottom: 5px;
    }
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Portal Orang Tua / Wali Santri
      <small>Alia Islamic School Integrated Mobile Portal</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Portal Orang Tua</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Flash Message -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
      </div>
    @endif
    @if(session('info'))
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan!</h4>
        {{ session('info') }}
      </div>
    @endif

    <!-- Header Card: Profil Orang Tua & Switcher Anak -->
    <div class="portal-header-card">
      <div class="row">
        <div class="col-sm-8 col-xs-12">
          <div class="media">
            <div class="media-left" style="vertical-align: middle;">
              <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="portal-avatar-circle" alt="Wali Santri">
            </div>
            <div class="media-body" style="padding-left: 10px;">
              <span class="label label-warning" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Wali Santri Terdaftar</span>
              <h3 style="margin: 4px 0 2px 0; font-weight: 700; font-size: 20px;">
                {{ $activeGuardian['name'] ?? 'Bpk. Ir. Hendra Gunawan' }}
              </h3>
              <p style="margin: 0; opacity: 0.9; font-size: 12px;">
                <i class="fa fa-phone"></i> {{ $activeGuardian['phone'] ?? '081122334455' }} &nbsp;|&nbsp;
                <i class="fa fa-envelope-o"></i> {{ $activeGuardian['email'] ?? 'wali.santri@alia.sch.id' }}
              </p>
              
              <!-- Switcher Anak Santri -->
              <div style="margin-top: 12px;">
                <span style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 2px;">
                  <i class="fa fa-users"></i> Pilih Putra/Putri Anda:
                </span>
                @foreach($guardianStudents as $gs)
                  <a href="{{ route('portal.dashboard', ['student_id' => $gs['id'], 'tab' => $activeTab]) }}" 
                     class="child-selector-btn {{ ($selectedStudent['id'] ?? '') === $gs['id'] ? 'active' : '' }}">
                    <i class="fa {{ $gs['gender'] === 'L' ? 'fa-male' : 'fa-female' }}"></i>
                    {{ $gs['name'] }}
                    <small>({{ $gs['unit_level'] ?? 'SMA' }} - Kelas {{ $gs['grade'] ?? '10' }})</small>
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-4 col-xs-12 text-right">
          <div style="background: rgba(0, 0, 0, 0.2); padding: 12px 16px; border-radius: 8px; display: inline-block; text-align: left; min-width: 220px;">
            <div style="font-size: 11px; text-transform: uppercase; color: #cbd5e0;">Santri Terpilih:</div>
            <div style="font-size: 16px; font-weight: bold; margin: 2px 0;">{{ $selectedStudent['name'] ?? '-' }}</div>
            <div style="font-size: 12px; opacity: 0.9;">
              NISN: <strong>{{ $selectedStudent['nisn'] ?? '-' }}</strong><br>
              Status: <span class="label label-success" style="font-size: 10px;">{{ strtoupper($selectedStudent['status'] ?? 'AKTIF') }}</span>
              <span class="label label-info" style="font-size: 10px;">{{ $selectedStudent['residence_type'] ?? 'Boarding' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 6 Pintasan Navigasi Cepat (Quick Access Menu) -->
    <div class="row">
      <div class="col-md-2 col-sm-4 col-xs-6">
        <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'ringkasan']) }}" class="portal-quick-action">
          <span class="icon-circle bg-aqua"><i class="fa fa-dashboard"></i></span>
          <strong style="font-size: 13px;">Dashboard</strong>
          <small class="text-muted" style="display:block; font-size: 11px;">Ringkasan Utama</small>
        </a>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'akademik']) }}" class="portal-quick-action">
          <span class="icon-circle bg-blue"><i class="fa fa-graduation-cap"></i></span>
          <strong style="font-size: 13px;">Akademik & Rapor</strong>
          <small class="text-muted" style="display:block; font-size: 11px;">Nilai & Kehadiran</small>
        </a>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'tahfidz']) }}" class="portal-quick-action">
          <span class="icon-circle bg-green"><i class="fa fa-book"></i></span>
          <strong style="font-size: 13px;">Tahfidz Quran</strong>
          <small class="text-muted" style="display:block; font-size: 11px;">Setoran & Mutqin</small>
        </a>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'keuangan']) }}" class="portal-quick-action">
          <span class="icon-circle bg-yellow"><i class="fa fa-money"></i></span>
          <strong style="font-size: 13px;">SPP & Keuangan</strong>
          <small class="text-muted" style="display:block; font-size: 11px;">Tagihan & Bayar</small>
        </a>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'asrama']) }}" class="portal-quick-action">
          <span class="icon-circle bg-purple"><i class="fa fa-bed"></i></span>
          <strong style="font-size: 13px;">Asrama & Izin</strong>
          <small class="text-muted" style="display:block; font-size: 11px;">Izin Pulang/Pesiar</small>
        </a>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'pengumuman']) }}" class="portal-quick-action">
          <span class="icon-circle bg-red"><i class="fa fa-bullhorn"></i></span>
          <strong style="font-size: 13px;">Pengumuman</strong>
          <small class="text-muted" style="display:block; font-size: 11px;">Kabar Sekolah</small>
        </a>
      </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="nav-tabs-custom" style="box-shadow: 0 2px 10px rgba(0,0,0,0.06); border-radius: 8px;">
      <ul class="nav nav-tabs nav-tabs-portal">
        <li class="{{ $activeTab === 'ringkasan' ? 'active' : '' }}">
          <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'ringkasan']) }}">
            <i class="fa fa-dashboard"></i> Ringkasan Perkembangan
          </a>
        </li>
        <li class="{{ $activeTab === 'akademik' ? 'active' : '' }}">
          <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'akademik']) }}">
            <i class="fa fa-graduation-cap"></i> Nilai & Presensi Belajar
          </a>
        </li>
        <li class="{{ $activeTab === 'tahfidz' ? 'active' : '' }}">
          <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'tahfidz']) }}">
            <i class="fa fa-book"></i> Capaian Tahfidz
          </a>
        </li>
        <li class="{{ $activeTab === 'keuangan' ? 'active' : '' }}">
          <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'keuangan']) }}">
            <i class="fa fa-credit-card"></i> Tagihan SPP & Pembayaran
          </a>
        </li>
        <li class="{{ $activeTab === 'asrama' ? 'active' : '' }}">
          <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'asrama']) }}">
            <i class="fa fa-home"></i> Kehidupan Asrama & Izin Keluar
          </a>
        </li>
        <li class="{{ $activeTab === 'pengumuman' ? 'active' : '' }}">
          <a href="{{ route('portal.dashboard', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'pengumuman']) }}">
            <i class="fa fa-bell"></i> Informasi & Pengumuman
          </a>
        </li>
      </ul>

      <div class="tab-content" style="padding: 20px;">

        <!-- ========================================== -->
        <!-- TAB 1: RINGKASAN UTAMA (DASHBOARD WALI)   -->
        <!-- ========================================== -->
        @if($activeTab === 'ringkasan')
          <div class="tab-pane active" id="tab_ringkasan">
            
            <!-- 4 Indikator Pilar Santri -->
            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-blue" style="border-radius: 6px;">
                  <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Indeks Prestasi / Rata-rata</span>
                    <span class="info-box-number">{{ number_format($selectedStudent['gpa'] ?? 89.2, 1) }} / 100</span>
                    <div class="progress"><div class="progress-bar" style="width: {{ $selectedStudent['gpa'] ?? 89.2 }}%"></div></div>
                    <span class="progress-description" style="font-size: 11px;">Predikat: <strong>Amat Baik (A)</strong></span>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green" style="border-radius: 6px;">
                  <span class="info-box-icon"><i class="fa fa-book"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Progres Tahfidz Al-Quran</span>
                    <span class="info-box-number">{{ $selectedStudent['tahfidz_juz'] ?? 5 }} Juz Mutqin</span>
                    <div class="progress"><div class="progress-bar" style="width: {{ (($selectedStudent['tahfidz_juz'] ?? 5) / 30) * 100 }}%"></div></div>
                    <span class="progress-description" style="font-size: 11px;">Terakhir: {{ $selectedStudent['tahfidz_last_surah'] ?? 'An-Naba' }}</span>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua" style="border-radius: 6px;">
                  <span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Presensi Kehadiran Kelas</span>
                    <span class="info-box-number">{{ number_format($selectedStudent['attendance_pct'] ?? 98.5, 1) }}%</span>
                    <div class="progress"><div class="progress-bar" style="width: {{ $selectedStudent['attendance_pct'] ?? 98.5 }}%"></div></div>
                    <span class="progress-description" style="font-size: 11px;">Kehadiran sangat tertib & disiplin</span>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box {{ ($selectedStudent['spp_status'] ?? 'lunas') === 'lunas' ? 'bg-purple' : 'bg-yellow' }}" style="border-radius: 6px;">
                  <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Status Tagihan SPP</span>
                    <span class="info-box-number">{{ ($selectedStudent['spp_status'] ?? 'lunas') === 'lunas' ? 'LUNAS' : 'ADA TAGIHAN' }}</span>
                    <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                    <span class="progress-description" style="font-size: 11px;">
                      @if(($selectedStudent['spp_status'] ?? 'lunas') === 'lunas')
                        Bulan {{ date('F Y') }} terbayar
                      @else
                        Jatuh tempo tgl 10 bulan ini
                      @endif
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kartu Info Kamar Asrama & Roster Santri -->
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-7">
                <div class="box box-primary box-solid">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-id-card-o"></i> Biodata Lengkap Santri</h3>
                  </div>
                  <div class="box-body">
                    <div class="row">
                      <div class="col-sm-3 text-center">
                        <img src="{{ asset('AdminLTE/dist/img/' . ($selectedStudent['avatar'] ?? 'avatar5.png')) }}" 
                             alt="Avatar" class="img-circle" style="width: 90px; height: 90px; border: 3px solid #2b6cb0; margin-bottom: 10px;">
                        <div><span class="label label-primary">{{ $selectedStudent['nis'] ?? '20260001' }}</span></div>
                      </div>
                      <div class="col-sm-9">
                        <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                          <tr>
                            <th style="width: 30%;">Nama Lengkap</th>
                            <td><strong>{{ $selectedStudent['name'] }}</strong></td>
                          </tr>
                          <tr>
                            <th>Unit Sekolah</th>
                            <td>{{ $selectedStudent['unit_level'] ?? 'SMA' }} Islam Alia (Kelas {{ $selectedStudent['grade'] ?? '10' }})</td>
                          </tr>
                          <tr>
                            <th>Tempat, Tgl Lahir</th>
                            <td>{{ $selectedStudent['pob'] ?? 'Jakarta' }}, {{ date('d F Y', strtotime($selectedStudent['dob'] ?? '2010-01-01')) }}</td>
                          </tr>
                          <tr>
                            <th>Tipe Mukim</th>
                            <td>
                              <span class="label {{ !empty($selectedStudent['is_boarding']) ? 'label-success' : 'label-default' }}">
                                {{ $selectedStudent['residence_type'] ?? 'Boarding' }}
                              </span>
                            </td>
                          </tr>
                          <tr>
                            <th>Kamar Asrama</th>
                            <td>
                              @if(!empty($selectedStudent['is_boarding']))
                                <i class="fa fa-bed text-purple"></i> {{ $selectedStudent['boarding_room_id'] ?? 'Kamar 101' }}
                              @else
                                <span class="text-muted">Santri Non-Boarding (Pulang Hari)</span>
                              @endif
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kontak Cepat Sekolah & Musyrif Asrama -->
              <div class="col-md-5">
                <div class="box box-default">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-comments-o text-blue"></i> Kontak Cepat Pihak Sekolah</h3>
                  </div>
                  <div class="box-body">
                    <ul class="list-group list-group-unbordered" style="margin-bottom: 0;">
                      <li class="list-group-item">
                        <i class="fa fa-user-circle text-blue"></i> <strong>Wali Kelas:</strong> Ust. Abdullah, S.Pd.I
                        <a href="https://wa.me/6281234567890" target="_blank" class="pull-right btn btn-xs btn-success">
                          <i class="fa fa-whatsapp"></i> Chat WA
                        </a>
                      </li>
                      <li class="list-group-item">
                        <i class="fa fa-user-circle text-green"></i> <strong>Musyrif Asrama:</strong> Ust. Ahmad Fauzi
                        <a href="https://wa.me/6281234567891" target="_blank" class="pull-right btn btn-xs btn-success">
                          <i class="fa fa-whatsapp"></i> Chat WA
                        </a>
                      </li>
                      <li class="list-group-item">
                        <i class="fa fa-building-o text-purple"></i> <strong>Customer Service Alia:</strong> (021) 876-5432
                        <span class="pull-right text-muted">07:00 - 16:00 WIB</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 2: AKADEMIK & RAPOR (T13.3)           -->
        <!-- ========================================== -->
        @if($activeTab === 'akademik')
          <div class="tab-pane active" id="tab_akademik">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-graduation-cap text-blue"></i> Riwayat Akademik & Transkrip Nilai Semester Berjalan
            </h4>
            
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr style="background: #f4f6f9;">
                        <th class="text-center" style="width: 40px;">No</th>
                        <th>Mata Pelajaran</th>
                        <th class="text-center" style="width: 80px;">KKM</th>
                        <th class="text-center" style="width: 80px;">Formatif</th>
                        <th class="text-center" style="width: 80px;">PTS</th>
                        <th class="text-center" style="width: 80px;">PAS</th>
                        <th class="text-center" style="width: 90px; font-weight: 700;">Nilai Akhir</th>
                        <th class="text-center" style="width: 80px;">Predikat</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        $sampleGrades = [
                          ['Pendidikan Agama Islam & Budi Pekerti', 78, 92, 88, 94, 91.5, 'A'],
                          ['Bahasa Arab & Nahwu Sharaf', 75, 88, 85, 90, 87.8, 'A'],
                          ['Tahfidz & Ulumul Quran', 80, 95, 96, 95, 95.3, 'A'],
                          ['Matematika Terapan', 75, 84, 82, 86, 84.0, 'B+'],
                          ['Bahasa Indonesia', 78, 86, 85, 88, 86.5, 'A'],
                          ['Bahasa Inggris & Conversation', 75, 90, 89, 92, 90.3, 'A'],
                          ['Fisika / IPA Terpadu', 75, 85, 80, 85, 83.5, 'B+'],
                          ['Teknologi Informasi & Komputer', 75, 92, 90, 94, 92.0, 'A'],
                        ];
                      @endphp
                      @foreach($sampleGrades as $gIdx => $gr)
                        <tr>
                          <td class="text-center">{{ $gIdx + 1 }}</td>
                          <td><strong>{{ $gr[0] }}</strong></td>
                          <td class="text-center text-muted">{{ $gr[1] }}</td>
                          <td class="text-center">{{ $gr[2] }}</td>
                          <td class="text-center">{{ $gr[3] }}</td>
                          <td class="text-center">{{ $gr[4] }}</td>
                          <td class="text-center font-bold" style="color: #2b6cb0;">{{ number_format($gr[5], 1) }}</td>
                          <td class="text-center"><span class="label {{ $gr[6] === 'A' ? 'label-success' : 'label-primary' }}">{{ $gr[6] }}</span></td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr style="background: #edf2f7; font-weight: bold;">
                        <td colspan="6" class="text-right">Rata-Rata Kumulatif:</td>
                        <td class="text-center text-blue" style="font-size: 15px;">88.9</td>
                        <td class="text-center"><span class="label label-success">SANGAT BAIK</span></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <div style="margin-top: 10px;">
                  <a href="{{ route('akademik.rapor') }}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa fa-print"></i> Pratinjau & Cetak Buku E-Rapor Resmi
                  </a>
                </div>
              </div>

              <!-- Rekap Presensi Belajar -->
              <div class="col-md-4">
                <div class="box box-solid box-primary">
                  <div class="box-header with-border" style="background: #1a365d;">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-pie-chart"></i> Ringkasan Presensi Belajar</h3>
                  </div>
                  <div class="box-body">
                    <table class="table table-bordered">
                      <tr>
                        <th>Hadir (H)</th>
                        <td class="text-center font-bold text-green">142 Hari (98.6%)</td>
                      </tr>
                      <tr>
                        <th>Izin (I)</th>
                        <td class="text-center font-bold text-blue">2 Hari</td>
                      </tr>
                      <tr>
                        <th>Sakit (S)</th>
                        <td class="text-center font-bold text-yellow">0 Hari</td>
                      </tr>
                      <tr>
                        <th>Alpa / Tanpa Keterangan</th>
                        <td class="text-center font-bold text-red">0 Hari</td>
                      </tr>
                    </table>
                    <div class="alert alert-info" style="margin-bottom: 0; font-size: 11px; padding: 8px 12px;">
                      <i class="fa fa-info-circle"></i> Santri menunjukkan disiplin dan integritas tinggi dalam mengikuti jam pelajaran tatap muka.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 3: TAHFIDZ & DINIYAH (T13.4)          -->
        <!-- ========================================== -->
        @if($activeTab === 'tahfidz')
          <div class="tab-pane active" id="tab_tahfidz">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-book text-green"></i> Rekam Jejak Setoran Ziyadah & Murojaah Al-Qur'an
            </h4>

            <div class="row">
              <div class="col-md-4">
                <div class="box box-solid box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-check-circle"></i> Capaian Target Juz</h3>
                  </div>
                  <div class="box-body text-center">
                    <h2 style="font-weight: 800; color: #00a65a; margin: 10px 0;">
                      {{ $selectedStudent['tahfidz_juz'] ?? 5 }} / 30 <small style="font-size: 14px;">JUZ MUTQIN</small>
                    </h2>
                    <p class="text-muted">Halaqah Takhassus Imam Asy-Syathibi</p>
                    <table class="table table-condensed text-left" style="font-size: 12px;">
                      <tr>
                        <th>Musyrif Pembimbing</th>
                        <td>Ust. Ridwan Hafizhahullah</td>
                      </tr>
                      <tr>
                        <th>Kelancaran / Mutqin</th>
                        <td><span class="label label-success">Mumtaz (95)</span></td>
                      </tr>
                      <tr>
                        <th>Tajwid & Makhraj</th>
                        <td><span class="label label-success">Jayyid Jiddan (88)</span></td>
                      </tr>
                      <tr>
                        <th>Adab & Disiplin</th>
                        <td><span class="label label-success">Mumtaz (96)</span></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-md-8">
                <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 10px;">5 Log Setoran Terakhir:</h5>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr style="background: #f4f6f9;">
                        <th class="text-center" style="width: 100px;">Tanggal</th>
                        <th style="width: 90px;" class="text-center">Tipe</th>
                        <th>Surah & Ayat</th>
                        <th class="text-center" style="width: 90px;">Kelancaran</th>
                        <th>Catatan Musyrif</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-center">08/11/2026</td>
                        <td class="text-center"><span class="label label-success">Ziyadah</span></td>
                        <td>Surah An-Nazi'at ayat 1 - 25</td>
                        <td class="text-center font-bold text-green">Mumtaz</td>
                        <td><small>Bacaan fasih, ghunnah dan mad thabi'i konsisten dijaga.</small></td>
                      </tr>
                      <tr>
                        <td class="text-center">06/11/2026</td>
                        <td class="text-center"><span class="label label-info">Murojaah</span></td>
                        <td>Surah An-Naba ayat 1 - 40 (1 Ruku Penuh)</td>
                        <td class="text-center font-bold text-blue">Jayyid Jiddan</td>
                        <td><small>Lancar tanpa pengulangan ayat.</small></td>
                      </tr>
                      <tr>
                        <td class="text-center">04/11/2026</td>
                        <td class="text-center"><span class="label label-success">Ziyadah</span></td>
                        <td>Surah 'Abasa ayat 1 - 20</td>
                        <td class="text-center font-bold text-green">Mumtaz</td>
                        <td><small>Makharijul huruf 'Ain dan Ha bersih.</small></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 4: TAGIHAN SPP & KEUANGAN (T13.4)     -->
        <!-- ========================================== -->
        @if($activeTab === 'keuangan')
          <div class="tab-pane active" id="tab_keuangan">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-credit-card text-yellow"></i> Informasi Tagihan SPP & Rekening Pembayaran
            </h4>

            <div class="row">
              <div class="col-md-7">
                <div class="box box-solid box-warning">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-file-text-o"></i> Daftar Tagihan Periode Berjalan</h3>
                  </div>
                  <div class="box-body no-padding">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr style="background: #fdf6e2;">
                          <th>Kode Tagihan</th>
                          <th>Pos Biaya</th>
                          <th class="text-right">Nominal</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><strong>INV/2026/11/008</strong></td>
                          <td>SPP Bulanan (November 2026) - Boarding</td>
                          <td class="text-right font-bold">Rp 1.850.000</td>
                          <td class="text-center">
                            @if(($selectedStudent['spp_status'] ?? 'lunas') === 'lunas')
                              <span class="label label-success">Lunas</span>
                            @else
                              <span class="label label-danger">Belum Lunas</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if(($selectedStudent['spp_status'] ?? 'lunas') === 'lunas')
                              <button type="button" class="btn btn-xs btn-default" onclick="alert('Kuitansi pembayaran digital sudah terkirim ke WhatsApp wali!')">
                                <i class="fa fa-download"></i> Kuitansi
                              </button>
                            @else
                              <button type="button" class="btn btn-xs btn-primary" onclick="alert('Silakan lakukan transfer ke Virtual Account BSI: 98820260001 lalu konfirmasi ke Keuangan!')">
                                <i class="fa fa-credit-card"></i> Bayar
                              </button>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td><strong>INV/2026/10/008</strong></td>
                          <td>SPP Bulanan (Oktober 2026) - Boarding</td>
                          <td class="text-right font-bold">Rp 1.850.000</td>
                          <td class="text-center"><span class="label label-success">Lunas</span></td>
                          <td class="text-center">
                            <span class="label label-default"><i class="fa fa-check"></i> Selesai</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Info Rekening Virtual Account -->
              <div class="col-md-5">
                <div class="box box-default">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-bank text-blue"></i> Saluran Pembayaran Resmi</h3>
                  </div>
                  <div class="box-body" style="font-size: 12px;">
                    <div class="well well-sm" style="background: #f7fafc; border-left: 4px solid #3182ce;">
                      <strong>Bank Syariah Indonesia (BSI)</strong><br>
                      No. Virtual Account: <strong style="font-size: 15px; color: #2b6cb0;">988 2026 {{ substr($selectedStudent['nis'] ?? '0001', -4) }}</strong><br>
                      Atas Nama: <strong>AIS - {{ $selectedStudent['name'] }}</strong>
                    </div>
                    <p class="text-muted">
                      Pembayaran melalui ATM, BSI Mobile, atau transfer antar bank (kode 451) terverifikasi otomatis oleh sistem dalam 15 menit.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 5: ASRAMA & PERIZINAN KELUAR (T13.5)  -->
        <!-- ========================================== -->
        @if($activeTab === 'asrama')
          <div class="tab-pane active" id="tab_asrama">
            <div class="row">
              <div class="col-md-7">
                <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
                  <i class="fa fa-bed text-purple"></i> Pengajuan Izin Keluar Asrama / Pesiar Santri
                </h4>

                <!-- Form Pengajuan Izin oleh Orang Tua -->
                <div class="box box-solid box-primary">
                  <div class="box-header with-border" style="background: #6b46c1;">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-paper-plane-o"></i> Form Pengajuan Izin Baru</h3>
                  </div>
                  <form action="{{ route('portal.dashboard') }}" method="GET" class="box-body">
                    <input type="hidden" name="tab" value="asrama">
                    <input type="hidden" name="student_id" value="{{ $selectedStudent['id'] ?? 'SIS-0001' }}">
                    
                    <div class="row">
                      <div class="col-md-6 form-group">
                        <label>Tipe Izin:</label>
                        <select name="permission_type" class="form-control input-sm">
                          <option value="pesiar">Pesiar Mingguan (Sabtu/Ahad)</option>
                          <option value="pulang">Pulang ke Rumah (Weekend Bersama)</option>
                          <option value="sakit">Izin Berobat / Medis Faskes Luar</option>
                          <option value="khusus">Keperluan Keluarga Khusus</option>
                        </select>
                      </div>
                      <div class="col-md-6 form-group">
                        <label>Nama Penjemput:</label>
                        <input type="text" class="form-control input-sm" value="{{ $activeGuardian['name'] ?? 'Orang Tua Langsung' }}" required>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 form-group">
                        <label>Rencana Tanggal Keluar:</label>
                        <input type="date" class="form-control input-sm" value="{{ date('Y-m-d', strtotime('+5 days')) }}" required>
                      </div>
                      <div class="col-md-6 form-group">
                        <label>Rencana Tanggal Kembali Masuk:</label>
                        <input type="date" class="form-control input-sm" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>Alasan / Keterangan Keperluan:</label>
                      <textarea class="form-control input-sm" rows="2" placeholder="Contoh: Menghadiri walimatul 'ursy kakak kandung di Bandung..."></textarea>
                    </div>

                    <button type="button" class="btn btn-purple btn-sm" onclick="alert('Alhamdulillah, permohonan izin berhasil dikirim ke Pengasuhan Asrama untuk diverifikasi Musyrif!')">
                      <i class="fa fa-check"></i> Kirim Permohonan Izin ke Musyrif
                    </button>
                  </form>
                </div>
              </div>

              <!-- Riwayat Izin Terakhir -->
              <div class="col-md-5">
                <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
                  <i class="fa fa-history text-blue"></i> Status Izin Terakhir
                </h4>

                <div class="box box-default">
                  <div class="box-body no-padding">
                    <table class="table table-striped" style="font-size: 12px;">
                      <thead>
                        <tr>
                          <th>Tgl Keluar</th>
                          <th>Tipe</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>25/10/2026</td>
                          <td>Pesiar Akhir Pekan</td>
                          <td><span class="label label-success">Selesai (Tepat Waktu)</span></td>
                        </tr>
                        <tr>
                          <td>10/10/2026</td>
                          <td>Izin Berobat Dokter Gigi</td>
                          <td><span class="label label-success">Selesai</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 6: INFORMASI & PENGUMUMAN (T13.5)     -->
        <!-- ========================================== -->
        @if($activeTab === 'pengumuman')
          <div class="tab-pane active" id="tab_pengumuman">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-bullhorn text-red"></i> Pengumuman & Surat Pemberitahuan Sekolah
            </h4>

            <div class="box box-solid box-default" style="border-left: 4px solid #2b6cb0; margin-bottom: 15px;">
              <div class="box-body">
                <span class="label label-primary pull-right">Akademik</span>
                <h4 style="margin-top: 0; font-weight: 700; color: #1a365d;">Jadwal Penilaian Akhir Semester (PAS) Ganjil TA 2026/2027</h4>
                <p class="text-muted" style="font-size: 12px;"><i class="fa fa-calendar"></i> Dipublikasikan: 05 November 2026 | Oleh: Bagian Kurikulum</p>
                <p style="font-size: 13px;">
                  Diberitahukan kepada seluruh orang tua/wali santri bahwa PAS Ganjil akan diselenggarakan pada tanggal 1 s/d 10 Desember 2026. Mohon kerja sama bapak/ibu untuk senantiasa mendampingi dan mendoakan ketekunan belajar ananda.
                </p>
              </div>
            </div>

            <div class="box box-solid box-default" style="border-left: 4px solid #00a65a; margin-bottom: 15px;">
              <div class="box-body">
                <span class="label label-success pull-right">Kepesantrenan</span>
                <h4 style="margin-top: 0; font-weight: 700; color: #1a365d;">Wisuda Akbar Tahfidz Al-Qur'an Ke-VIII Alia Islamic School</h4>
                <p class="text-muted" style="font-size: 12px;"><i class="fa fa-calendar"></i> Dipublikasikan: 01 November 2026 | Oleh: Mudir Pondok</p>
                <p style="font-size: 13px;">
                  Wisuda Akbar Tahfidz bagi santri yang telah menyelesaikan kategori 5, 10, 20, dan 30 Juz Mutqin akan digelar di Aula Utama Islamic Center Alia pada 18 Desember 2026. Undangan resmi akan dikirimkan kepada masing-masing wali santri.
                </p>
              </div>
            </div>
          </div>
        @endif

      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
@endsection
