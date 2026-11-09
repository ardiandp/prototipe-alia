@extends('layouts.app')

@section('title', 'Presensi & Kehadiran Pegawai')
@section('page_title', 'Presensi & Kehadiran Pegawai (HRIS)')
@section('page_description', 'Monitoring Kehadiran, Jam Masuk/Pulang, & Rekapitulasi Keaktifan Pendidik/Tendik')
@section('breadcrumbs')
  <li>SDM / HRIS</li>
      <li class="active">Presensi Pegawai</li>
@endsection

@section('content')

    <h1>
      Presensi & Kehadiran Pegawai (HRIS)
      <small>Monitoring Kehadiran, Jam Masuk/Pulang, & Rekapitulasi Keaktifan Pendidik/Tendik</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>SDM / HRIS</li>
      <li class="active">Presensi Pegawai</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Flash Messages -->
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
        <h4><i class="icon fa fa-info"></i> Informasi</h4>
        {{ session('info') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Callout Prototipe -->
    <div class="callout callout-info" style="border-left-color: #2b6cb0;">
      <h4><i class="fa fa-clock-o"></i> Sistem Integrasi Presensi Multi-Terminal Alia</h4>
      <p>Pencatatan presensi terhubung dengan terminal biometrik fingerprint, face-recognition gerbang kampus, dan presensi manual wali kelas/kepala sekolah. Standar jam masuk kerja pukul <b>07:00 WIB</b> dan jam pulang kerja pukul <b>15:30 WIB</b>.</p>
    </div>

    <!-- 5 Info Box Presensi Hari Ini -->
    <div class="row">
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total SDM</span>
            <span class="info-box-number">{{ $totalStaff }}</span>
            <span class="progress-description text-muted" style="font-size: 10px;">Pegawai Terdaftar</span>
          </div>
        </div>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Hadir Tepat</span>
            <span class="info-box-number text-green">{{ $totalHadir }}</span>
            <span class="progress-description text-muted" style="font-size: 10px;">Pukul < 07:00 WIB</span>
          </div>
        </div>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-hourglass-half"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Terlambat</span>
            <span class="info-box-number text-yellow">{{ $totalTerlambat }}</span>
            <span class="progress-description text-muted" style="font-size: 10px;">Masuk > 07:00 WIB</span>
          </div>
        </div>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-envelope-open-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Izin / Dinas</span>
            <span class="info-box-number text-blue">{{ $totalIzin }}</span>
            <span class="progress-description text-muted" style="font-size: 10px;">Surat Tugas Resmi</span>
          </div>
        </div>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box">
          <span class="info-box-icon bg-purple"><i class="fa fa-medkit"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Sakit</span>
            <span class="info-box-number text-purple">{{ $totalSakit }}</span>
            <span class="progress-description text-muted" style="font-size: 10px;">Klinik / Dokter</span>
          </div>
        </div>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Alpa / Bolos</span>
            <span class="info-box-number text-red">{{ $totalAlpa }}</span>
            <span class="progress-description text-muted" style="font-size: 10px;">Tanpa Keterangan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs & Actions -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'harian' ? 'active' : '' }}">
          <a href="{{ route('hris.presensi', ['tab' => 'harian', 'date' => $dateFilter, 'unit' => $unitFilter, 'status' => $statusFilter, 'search' => $search]) }}">
            <i class="fa fa-calendar-check-o text-primary"></i> <b>Buku Presensi Harian</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredAttendance) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'rekap' ? 'active' : '' }}">
          <a href="{{ route('hris.presensi', ['tab' => 'rekap', 'date' => $dateFilter]) }}">
            <i class="fa fa-bar-chart text-green"></i> <b>Rekapitulasi Kehadiran & Kedisiplinan</b>
          </a>
        </li>
        <li class="{{ $activeTab === 'mesin' ? 'active' : '' }}">
          <a href="{{ route('hris.presensi', ['tab' => 'mesin']) }}">
            <i class="fa fa-microchip text-purple"></i> <b>Status Mesin & Terminal Biometrik</b>
          </a>
        </li>
        <li class="pull-right header">
          <form action="{{ route('hris.presensi.quick') }}" method="POST" style="display: inline-block; margin-right: 5px;">
            @csrf
            <input type="hidden" name="date" value="{{ $dateFilter }}">
            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai semua pegawai yang belum presensi hadir tepat waktu pada tanggal {{ $dateFilter }}?')">
              <i class="fa fa-check-square-o"></i> Quick Attendance (Hadir Semua)
            </button>
          </form>
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-attendance">
            <i class="fa fa-plus"></i> Catat Presensi Manual
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Presensi
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: BUKU PRESENSI HARIAN -->
        <div class="tab-pane {{ $activeTab === 'harian' ? 'active' : '' }}" id="tab_harian">
          
          <!-- Filter Saringan Tanggal & Unit -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.presensi') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="harian">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;"><i class="fa fa-calendar"></i> Tanggal:</label>
                <input type="date" name="date" class="form-control input-sm" value="{{ $dateFilter }}">
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Unit:</label>
                <select name="unit" class="form-control input-sm">
                  <option value="all">-- Semua Unit --</option>
                  @foreach($units as $u)
                    <option value="{{ $u['id'] }}" {{ $unitFilter === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Status Kehadiran:</label>
                <select name="status" class="form-control input-sm">
                  <option value="all">-- Semua Status --</option>
                  <option value="hadir" {{ $statusFilter === 'hadir' ? 'selected' : '' }}>Hadir Tepat Waktu</option>
                  <option value="terlambat" {{ $statusFilter === 'terlambat' ? 'selected' : '' }}>Terlambat Datang</option>
                  <option value="izin" {{ $statusFilter === 'izin' ? 'selected' : '' }}>Izin Dinas / Pribadi</option>
                  <option value="sakit" {{ $statusFilter === 'sakit' ? 'selected' : '' }}>Sakit</option>
                  <option value="alpa" {{ $statusFilter === 'alpa' ? 'selected' : '' }}>Alpa (Tanpa Keterangan)</option>
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Cari:</label>
                <input type="text" name="search" class="form-control input-sm" placeholder="Nama, NIP, Alat..." value="{{ $search }}" style="width: 150px;">
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.presensi') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Presensi -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>Nama Pegawai & NIP</th>
                  <th>Unit Penempatan</th>
                  <th style="text-align: center;">Jam Masuk</th>
                  <th style="text-align: center;">Jam Pulang</th>
                  <th style="text-align: center;">Status Presensi</th>
                  <th style="text-align: center;">Keterlambatan</th>
                  <th>Terminal / Lokasi Absen</th>
                  <th>Keterangan / Catatan</th>
                  <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredAttendance as $index => $att)
                  @php
                    $uName = 'Yayasan';
                    foreach($units as $u) {
                      if ($u['id'] === ($att['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <b style="font-size: 13px; color: #1a365d;">{{ $att['name'] }}</b>
                      <div style="font-size: 11px; color: #718096;">
                        <span><i class="fa fa-id-card-o"></i> NIP: {{ $att['nip'] ?? '-' }}</span>
                        <span style="margin-left: 5px;"><code>{{ $att['id'] }}</code></span>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      <span class="label label-default" style="font-size: 11px;">{{ $uName }}</span>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(!empty($att['check_in']))
                        <b style="font-family: monospace; font-size: 13px; color: #2d3748;">
                          <i class="fa fa-sign-in text-green"></i> {{ $att['check_in'] }} WIB
                        </b>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(!empty($att['check_out']))
                        <b style="font-family: monospace; font-size: 13px; color: #2d3748;">
                          <i class="fa fa-sign-out text-blue"></i> {{ $att['check_out'] }} WIB
                        </b>
                      @else
                        <span class="text-muted"><i class="fa fa-clock-o"></i> Belum Pulang</span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(($att['status'] ?? '') === 'hadir')
                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check-circle"></i> Hadir Tepat
                        </span>
                      @elseif(($att['status'] ?? '') === 'terlambat')
                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-exclamation-triangle"></i> Terlambat
                        </span>
                      @elseif(($att['status'] ?? '') === 'izin')
                        <span class="label label-primary" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-envelope-o"></i> Izin Dinas
                        </span>
                      @elseif(($att['status'] ?? '') === 'sakit')
                        <span class="label label-purple" style="background-color: #6b46c1; font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-medkit"></i> Sakit
                        </span>
                      @else
                        <span class="label label-danger" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-times"></i> Alpa
                        </span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(($att['late_minutes'] ?? 0) > 0)
                        <span class="text-danger" style="font-weight: bold; font-size: 12px;">
                          +{{ $att['late_minutes'] }} Menit
                        </span>
                      @else
                        <span class="text-muted"><i class="fa fa-check text-green"></i> 0 mnt</span>
                      @endif
                    </td>
                    <td style="vertical-align: middle; font-size: 11px;">
                      <div><i class="fa fa-microchip text-muted"></i> <b>{{ $att['device'] ?? 'Biometrik' }}</b></div>
                      <div class="text-muted"><i class="fa fa-map-marker"></i> {{ $att['location'] ?? 'Kampus Alia' }}</div>
                    </td>
                    <td style="vertical-align: middle; font-size: 11px; color: #4a5568;">
                      {{ $att['notes'] ?? '-' }}
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-att-{{ $att['id'] }}" title="Detail Bukti Presensi">
                          <i class="fa fa-eye text-primary"></i>
                        </button>
                        <a href="{{ route('hris.presensi.destroy', $att['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus riwayat presensi {{ $att['name'] }}?')" title="Hapus Log">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="10" class="text-center" style="padding: 30px;">
                      <i class="fa fa-calendar-times-o text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data presensi pegawai untuk parameter tanggal dan saringan ini.</p>
                      <a href="{{ route('hris.presensi') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: REKAPITULASI KEHADIRAN & KEDISIPLINAN -->
        <div class="tab-pane {{ $activeTab === 'rekap' ? 'active' : '' }}" id="tab_rekap">
          <div class="row">
            
            <div class="col-md-7">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-trophy text-yellow"></i> Indeks Kedisiplinan & Kinerja Kehadiran Pegawai</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Nama Pegawai</th>
                        <th>Jabatan</th>
                        <th style="text-align: center;">Hadir</th>
                        <th style="text-align: center;">Telat</th>
                        <th style="text-align: center;">Izin/Sakit</th>
                        <th style="text-align: center;">Skor Disiplin</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($teachers as $t)
                        @php
                          $tAttendance = array_filter($attendanceList, fn($a) => ($a['teacher_id'] ?? '') === $t['id']);
                          $hCount = count(array_filter($tAttendance, fn($a) => ($a['status'] ?? '') === 'hadir'));
                          $tCount = count(array_filter($tAttendance, fn($a) => ($a['status'] ?? '') === 'terlambat'));
                          $iCount = count(array_filter($tAttendance, fn($a) => in_array($a['status'] ?? '', ['izin', 'sakit'])));
                          $totalLog = count($tAttendance);
                          $score = $totalLog > 0 ? round((($hCount + ($tCount * 0.7) + ($iCount * 0.8)) / $totalLog) * 100) : 100;
                        @endphp
                        <tr>
                          <td><b>{{ $t['name'] }}</b></td>
                          <td><small class="text-muted">{{ $t['position'] }}</small></td>
                          <td class="text-center text-green"><b>{{ $hCount }}</b></td>
                          <td class="text-center text-yellow"><b>{{ $tCount }}</b></td>
                          <td class="text-center text-blue"><b>{{ $iCount }}</b></td>
                          <td class="text-center">
                            @if($score >= 90)
                              <span class="badge bg-green">{{ $score }}% Sangat Baik</span>
                            @elseif($score >= 75)
                              <span class="badge bg-yellow">{{ $score }}% Disiplin</span>
                            @else
                              <span class="badge bg-red">{{ $score }}% Perlu Evaluasi</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-5">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-info-circle text-info"></i> Kebijakan Disiplin Kerja Alia Islamic School</h3>
                </div>
                <div class="box-body" style="font-size: 13px;">
                  <ul class="list-unstyled" style="line-height: 2;">
                    <li><i class="fa fa-check text-green"></i> <b>Jam Kerja Standar:</b> Senin - Jum'at (07:00 - 15:30 WIB).</li>
                    <li><i class="fa fa-clock-o text-yellow"></i> <b>Toleransi Keterlambatan:</b> Maksimal 10 menit dengan notifikasi wali santri/piket.</li>
                    <li><i class="fa fa-ban text-red"></i> <b>Sanksi Keterlambatan:</b> Akumulasi keterlambatan > 60 menit per bulan dikenakan evaluasi tunjangan kehadiran.</li>
                    <li><i class="fa fa-calendar-check-o text-blue"></i> <b>Kewajiban Apel Pagi:</b> Setiap Senin pukul 06:45 WIB di plaza kampus.</li>
                    <li><i class="fa fa-file-text-o text-purple"></i> <b>Surat Keterangan Dokter:</b> Wajib diunggah maksimal 1x24 jam saat absen sakit.</li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB 3: STATUS MESIN BIOMETRIK -->
        <div class="tab-pane {{ $activeTab === 'mesin' ? 'active' : '' }}" id="tab_mesin">
          <div class="row">
            
            <div class="col-md-4">
              <div class="box box-solid box-success" style="border-top-color: #00a65a;">
                <div class="box-header with-border" style="background: #f0fff4; color: #276749;">
                  <h3 class="box-title" style="font-size: 14px; font-weight: bold;"><i class="fa fa-microchip"></i> Terminal Biometrik 01 (Utama)</h3>
                </div>
                <div class="box-body">
                  <p><b>Lokasi:</b> Lobby Kantor Yayasan & Gerbang Rektorat SMA</p>
                  <p><b>Tipe Sensor:</b> Face Recognition + Fingerprint Optik</p>
                  <p><b>IP Address:</b> <code>192.168.1.101</code></p>
                  <p><b>Status Jaringan:</b> <span class="label label-success"><i class="fa fa-wifi"></i> Online / Tersinkronisasi</span></p>
                  <p><b>Log Terakhir:</b> Hari ini pukul 07:18 WIB (Rahmat Hidayat, M.Sc.)</p>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="box box-solid box-success" style="border-top-color: #00a65a;">
                <div class="box-header with-border" style="background: #f0fff4; color: #276749;">
                  <h3 class="box-title" style="font-size: 14px; font-weight: bold;"><i class="fa fa-microchip"></i> Terminal Biometrik 02 (SMP & Asrama)</h3>
                </div>
                <div class="box-body">
                  <p><b>Lokasi:</b> Pos Pengawas Asrama Putri & Mudir Asrama</p>
                  <p><b>Tipe Sensor:</b> Dual Camera AI + Fingerprint</p>
                  <p><b>IP Address:</b> <code>192.168.1.102</code></p>
                  <p><b>Status Jaringan:</b> <span class="label label-success"><i class="fa fa-wifi"></i> Online / Tersinkronisasi</span></p>
                  <p><b>Log Terakhir:</b> Hari ini pukul 06:40 WIB (Aisyah Salsabila, S.Pd.I.)</p>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="box box-solid box-success" style="border-top-color: #00a65a;">
                <div class="box-header with-border" style="background: #f0fff4; color: #276749;">
                  <h3 class="box-title" style="font-size: 14px; font-weight: bold;"><i class="fa fa-microchip"></i> Terminal Biometrik SD Islam</h3>
                </div>
                <div class="box-body">
                  <p><b>Lokasi:</b> Gedung Kampus Terpadu SD Islam Alia</p>
                  <p><b>Tipe Sensor:</b> Kartu RFID + Fingerprint Biometrik</p>
                  <p><b>IP Address:</b> <code>192.168.2.105</code></p>
                  <p><b>Status Jaringan:</b> <span class="label label-success"><i class="fa fa-wifi"></i> Online / Tersinkronisasi</span></p>
                  <p><b>Log Terakhir:</b> Hari ini pukul 06:48 WIB (Khadijah Nurul Fikri, S.Pd.)</p>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

  

<!-- Modal Catat Presensi Manual -->
<div class="modal fade" id="modal-add-attendance" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.presensi.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Input Presensi Manual Pegawai</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Pilih Pegawai / Guru <span class="text-danger">*</span></label>
            <select name="teacher_id" class="form-control" required>
              <option value="">-- Pilih Pegawai --</option>
              @foreach($teachers as $t)
                <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['position'] }})</option>
              @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Presensi <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control" value="{{ $dateFilter }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status Kehadiran <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                  <option value="hadir">Hadir Tepat Waktu</option>
                  <option value="terlambat">Terlambat Datang</option>
                  <option value="izin">Izin Dinas / Pribadi</option>
                  <option value="sakit">Sakit</option>
                  <option value="alpa">Alpa (Tanpa Keterangan)</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Jam Masuk (Check-in)</label>
                <input type="time" name="check_in" class="form-control" value="06:45">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Jam Pulang (Check-out)</label>
                <input type="time" name="check_out" class="form-control" value="15:30">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Perangkat / Metode Pencatatan</label>
            <input type="text" name="device" class="form-control" value="Form Input Manual Admin HRIS">
          </div>

          <div class="form-group">
            <label>Keterangan / Catatan Khusus</label>
            <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Izin terlambat karena ada tugas koordinasi rapat dinas..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Catatan Presensi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Detail Log Presensi (Diluar Tabel) -->
@foreach($filteredAttendance as $att)
  @php
    $uNameModal = 'Yayasan';
    foreach($units as $u) {
      if ($u['id'] === ($att['unit_id'] ?? '')) {
        $uNameModal = $u['name'];
        break;
      }
    }
  @endphp
  <div class="modal fade" id="modal-view-att-{{ $att['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-id-card"></i> Lembar Verifikasi Log Presensi</h4>
        </div>
        <div class="modal-body">
          <div class="text-center" style="margin-bottom: 15px;">
            <h3 style="margin: 0; color: #1a365d; font-weight: bold;">{{ $att['name'] }}</h3>
            <p class="text-muted">NIP: {{ $att['nip'] ?? '-' }} &bull; {{ $uNameModal }}</p>
          </div>

          <table class="table table-bordered table-striped">
            <tr>
              <th style="width: 40%; background: #f9f9f9;">ID Transaksi Presensi</th>
              <td><code>{{ $att['id'] }}</code></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Tanggal Presensi</th>
              <td><b>{{ date('d F Y', strtotime($att['date'])) }}</b></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Jam Masuk Kerja</th>
              <td>{{ $att['check_in'] ? $att['check_in'] . ' WIB' : '-' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Jam Pulang Kerja</th>
              <td>{{ $att['check_out'] ? $att['check_out'] . ' WIB' : 'Belum Melakukan Check-out' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Status Kehadiran</th>
              <td>
                @if(($att['status'] ?? '') === 'hadir')
                  <span class="label label-success">Hadir Tepat Waktu</span>
                @elseif(($att['status'] ?? '') === 'terlambat')
                  <span class="label label-warning">Terlambat ({{ $att['late_minutes'] }} Menit)</span>
                @elseif(($att['status'] ?? '') === 'izin')
                  <span class="label label-primary">Izin Dinas</span>
                @elseif(($att['status'] ?? '') === 'sakit')
                  <span class="label label-purple" style="background-color: #6b46c1;">Sakit</span>
                @else
                  <span class="label label-danger">Alpa</span>
                @endif
              </td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Terminal Perangkat</th>
              <td><i class="fa fa-microchip text-muted"></i> {{ $att['device'] ?? 'Biometrik' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Lokasi GPS / Gedung</th>
              <td><i class="fa fa-map-marker text-red"></i> {{ $att['location'] ?? 'Kampus Alia' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Catatan Tambahan</th>
              <td>{{ $att['notes'] ?? '-' }}</td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Bukti</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

@endsection

