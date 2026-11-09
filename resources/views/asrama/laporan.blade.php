@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi Asrama')

@section('content_header')
    <h1>
        <i class="fa fa-file-text-o text-purple"></i> Laporan & Rekapitulasi Terpadu Asrama
        <small>Okupansi Kamar, Statistik Perizinan Santri, Kehadiran Shalat Jamaah, & Logistik Dapur</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('asrama.kamar') }}">Asrama</a></li>
        <li class="active">Laporan & Rekapitulasi</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Metrik Global Asrama -->
<div class="row">
    <!-- Stat 1: Okupansi Bed -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-bed"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Okupansi Tempat Tidur</span>
                <span class="info-box-number">{{ $totalOccupied }} / {{ $totalCapacity }} <small style="color:#eee; font-weight:normal;">({{ $totalOccupancyRate }}%)</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalOccupancyRate }}%"></div></div>
                <span class="progress-description">Sisa Bed Kosong: {{ $totalVacant }}</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Izin Santri Aktif Keluar -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-id-card-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Santri di Luar Asrama</span>
                <span class="info-box-number">{{ $leavesActiveOut }} <small style="color:#eee; font-weight:normal;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalLeaves > 0 ? round(($leavesActiveOut / $totalLeaves)*100) : 0 }}%"></div></div>
                <span class="progress-description">Total Pengajuan: {{ $totalLeaves }} Izin</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Kehadiran Shalat Jamaah -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kedisiplinan Shalat</span>
                <span class="info-box-number">{{ $attendanceRate }}% <small style="color:#eee; font-weight:normal;">Hadir/Masbuq</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $attendanceRate }}%"></div></div>
                <span class="progress-description">{{ $totalHadir }} Tepat Waktu | {{ $totalMasbuq }} Masbuq</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Logistik Dapur & Konsumsi -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal">
            <span class="info-box-icon"><i class="fa fa-cutlery"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Standar Dapur Santri</span>
                <span class="info-box-number">{{ $avgMealCalories }} <small style="color:#eee; font-weight:normal;">kkal / porsi</small></span>
                <div class="progress"><div class="progress-bar" style="width: 88%"></div></div>
                <span class="progress-description">{{ $totalMealVariations }} Menu Terjadwal Mingguan</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li><a href="#tab-konsumsi" data-toggle="tab"><i class="fa fa-cutlery"></i> Evaluasi Dapur & Menu</a></li>
                <li><a href="#tab-presensi" data-toggle="tab"><i class="fa fa-heartbeat"></i> Rekap Ibadah & Shalat</a></li>
                <li><a href="#tab-perizinan" data-toggle="tab"><i class="fa fa-sign-out"></i> Rekap Perizinan Keluar</a></li>
                <li class="active"><a href="#tab-okupansi" data-toggle="tab"><i class="fa fa-building-o"></i> Okupansi Kamar & Gedung</a></li>
                <li class="pull-left header">
                    <span style="font-size: 18px; font-weight: bold; color: #1a365d;">
                        <i class="fa fa-bar-chart text-purple"></i> Dashboard Laporan Operasional Asrama
                    </span>
                </li>
            </ul>

            <div class="tab-content" style="padding: 20px;">
                <!-- TAB 1: OKUPANSI GEDUNG & KAMAR -->
                <div class="tab-pane active" id="tab-okupansi">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-university text-purple"></i> Rekapitulasi Kapasitas Gedung & Kamar Santri
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Data status okupansi bed asrama santri Ikhwan & Akhwat Alia Islamic School Tahun Ajaran Berjalan.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <form action="{{ route('asrama.laporan') }}" method="GET" class="form-inline">
                                <input type="hidden" name="tab" value="okupansi">
                                <div class="form-group">
                                    <select name="building" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="">-- Semua Gedung --</option>
                                        @foreach($buildings as $b)
                                            <option value="{{ $b['id'] }}" {{ $selectedBuilding === $b['id'] ? 'selected' : '' }}>{{ $b['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                    <i class="fa fa-print"></i> Cetak Laporan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Ringkasan Profil Gedung -->
                    <div class="row" style="margin-bottom: 20px;">
                        @foreach($buildingStats as $bId => $bs)
                        <div class="col-md-6">
                            <div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid {{ $bs['gender'] === 'L' ? '#2b6cb0' : '#805ad5' }};">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <h4 style="margin-top: 0; font-weight: bold; color: #1a365d;">
                                                <i class="fa {{ $bs['gender'] === 'L' ? 'fa-male text-blue' : 'fa-female text-purple' }}"></i> {{ $bs['name'] }}
                                            </h4>
                                            <small class="text-muted"><i class="fa fa-user"></i> Pembina/Musyrif: {{ $bs['head'] }}</small>
                                        </div>
                                        <div class="col-xs-4 text-right">
                                            <span class="label {{ $bs['occupancy_pct'] >= 80 ? 'label-warning' : 'label-success' }}" style="font-size: 13px;">
                                                {{ $bs['occupancy_pct'] }}% Okupansi
                                            </span>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div class="progress-bar {{ $bs['gender'] === 'L' ? 'progress-bar-primary' : 'progress-bar-purple' }}" style="width: {{ $bs['occupancy_pct'] }}%"></div>
                                    </div>
                                    <div class="row text-center" style="font-size: 12px;">
                                        <div class="col-xs-3"><strong>{{ $bs['total_rooms'] }}</strong><br><span class="text-muted">Kamar</span></div>
                                        <div class="col-xs-3"><strong>{{ $bs['total_capacity'] }}</strong><br><span class="text-muted">Total Bed</span></div>
                                        <div class="col-xs-3"><strong class="text-green">{{ $bs['occupied_beds'] }}</strong><br><span class="text-muted">Terisi</span></div>
                                        <div class="col-xs-3"><strong class="text-red">{{ $bs['vacant_beds'] }}</strong><br><span class="text-muted">Kosong</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tabel Detail Okupansi Kamar -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Nomor Kamar</th>
                                    <th>Gedung / Lantai</th>
                                    <th>Gender</th>
                                    <th class="text-center">Kapasitas</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Kosong</th>
                                    <th style="width: 160px;">Rasio Okupansi</th>
                                    <th>Daftar Penghuni Santri Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roomReportList as $idx => $r)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong style="color: #1a365d; font-size: 14px;">Kamar {{ $r['room_number'] }}</strong>
                                        <br><small class="text-muted">ID: {{ $r['id'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ $r['building_name'] }}<br>
                                        <small class="text-muted"><i class="fa fa-level-up"></i> Lantai {{ $r['floor'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        @if($r['gender'] === 'L')
                                            <span class="label label-primary"><i class="fa fa-mars"></i> Santri Ikhwan</span>
                                        @else
                                            <span class="label label-danger" style="background-color: #9f7aea;"><i class="fa fa-venus"></i> Santriwati Akhwat</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;"><strong>{{ $r['capacity'] }}</strong> Bed</td>
                                    <td class="text-center" style="vertical-align: middle;"><span class="badge bg-green">{{ $r['occupied'] }}</span></td>
                                    <td class="text-center" style="vertical-align: middle;"><span class="badge {{ $r['vacant'] > 0 ? 'bg-orange' : 'bg-gray' }}">{{ $r['vacant'] }}</span></td>
                                    <td style="vertical-align: middle;">
                                        <div class="progress progress-sm" style="margin-bottom: 3px;">
                                            <div class="progress-bar {{ $r['occupancy_pct'] == 100 ? 'progress-bar-danger' : ($r['occupancy_pct'] > 0 ? 'progress-bar-info' : 'progress-bar-default') }}" style="width: {{ $r['occupancy_pct'] }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $r['occupancy_pct'] }}% Kapasitas Terpakai</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        @if(count($r['residents']) > 0)
                                            <ul style="padding-left: 15px; margin-bottom: 0; font-size: 12px;">
                                                @foreach($r['residents'] as $res)
                                                    <li>
                                                        <strong>{{ $res['student_name'] }}</strong> ({{ $res['class_name'] }})
                                                        <span class="text-muted">— Bed #{{ $res['bed_number'] }}, Loker #{{ $res['locker_number'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted" style="font-style: italic; font-size: 12px;">(Kamar kosong belum terisi)</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted" style="padding: 25px;">
                                        Belum ada data kamar asrama yang sesuai dengan kriteria filter.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: REKAP PERIZINAN KELUAR / PULANG -->
                <div class="tab-pane" id="tab-perizinan">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-sign-out text-purple"></i> Log Rekapitulasi Perizinan & Mobilitas Santri
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Pengawasan arus keluar-masuk gerbang pesantren, verifikasi barcode Gate Pass, dan kedisiplinan kembali ke asrama.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Log Perizinan
                            </button>
                        </div>
                    </div>

                    <!-- Strip Metrik Perizinan -->
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-3 col-xs-6">
                            <div class="small-box bg-gray">
                                <div class="inner">
                                    <h3>{{ $totalLeaves }}</h3>
                                    <p>Total Pengajuan</p>
                                </div>
                                <div class="icon"><i class="fa fa-folder-open"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="small-box bg-purple">
                                <div class="inner">
                                    <h3>{{ $leavesApproved }}</h3>
                                    <p>Disetujui Musyrif</p>
                                </div>
                                <div class="icon"><i class="fa fa-check-circle"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>{{ $leavesActiveOut }}</h3>
                                    <p>Sedang di Luar Pesantren</p>
                                </div>
                                <div class="icon"><i class="fa fa-external-link"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ $leavesCompleted }}</h3>
                                    <p>Telah Kembali Aman</p>
                                </div>
                                <div class="icon"><i class="fa fa-home"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Kode Gate Pass</th>
                                    <th>Nama Santri</th>
                                    <th>Kamar Asrama</th>
                                    <th>Kategori & Alasan</th>
                                    <th>Jadwal Keluar s/d Kembali</th>
                                    <th>Status Persetujuan</th>
                                    <th>Verifikasi Satpam Gerbang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrichedLeaves as $idx => $l)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <code style="font-weight: bold; font-size: 13px; color: #6b46c1;">{{ $l['permit_number'] }}</code><br>
                                        <small class="text-muted"><i class="fa fa-barcode"></i> Barcode Valid</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <strong style="color: #1a365d; font-size: 14px;">{{ $l['student_name'] }}</strong><br>
                                        <small class="text-muted">NISN: {{ $l['nisn'] }} | Kelas: {{ $l['class_name'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ $l['building_name'] }}<br>
                                        <small class="text-muted">Kamar {{ $l['room_number'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <span class="label label-info">{{ strtoupper(str_replace('_', ' ', $l['type'])) }}</span><br>
                                        <span style="font-size: 12px; color: #4a5568;">{{ $l['reason'] }}</span>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">
                                        <i class="fa fa-clock-o text-muted"></i> Keluar: <strong>{{ $l['date_start'] }}</strong><br>
                                        <i class="fa fa-calendar-check-o text-muted"></i> Kembali: <strong>{{ $l['date_end'] }}</strong>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        @if($l['approval_status'] === 'kembali')
                                            <span class="label label-success"><i class="fa fa-check"></i> Selesai (Kembali)</span>
                                        @elseif($l['approval_status'] === 'disetujui')
                                            <span class="label label-primary"><i class="fa fa-check"></i> Disetujui</span>
                                        @elseif($l['approval_status'] === 'diajukan')
                                            <span class="label label-warning"><i class="fa fa-hourglass-half"></i> Menunggu</span>
                                        @else
                                            <span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>
                                        @endif
                                        <br><small class="text-muted">Oleh: {{ $l['approver_name'] ?? '-' }}</small>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">
                                        @if($l['security_check_in'])
                                            <span class="text-green"><i class="fa fa-check-circle"></i> Check-in: {{ $l['security_check_in'] }}</span>
                                        @elseif($l['security_check_out'])
                                            <span class="text-yellow"><i class="fa fa-external-link"></i> Check-out: {{ $l['security_check_out'] }}</span>
                                        @else
                                            <span class="text-muted"><i class="fa fa-clock-o"></i> Menunggu Gerbang</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                        Belum ada riwayat perizinan santri tercatat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: REKAP IBADAH & SHALAT BERJAMAAH -->
                <div class="tab-pane" id="tab-presensi">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-heartbeat text-purple"></i> Rekapitulasi Presensi Shalat Berjamaah & Ibadah Asrama
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Kedisiplinan shalat 5 waktu di Masjid Kampus Alia, Qiyamul Lail, dan pengecekan naum (tidur malam) santri.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Rekap Ibadah
                            </button>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-3 col-xs-6">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Hadir Tepat Waktu</span>
                                    <span class="info-box-number">{{ $totalHadir }}</span>
                                    <span class="progress-description">{{ $totalAttRecords > 0 ? round(($totalHadir/$totalAttRecords)*100) : 0 }}% dari total rekaman</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Masbuq Shalat</span>
                                    <span class="info-box-number">{{ $totalMasbuq }}</span>
                                    <span class="progress-description">Masuk rakaat pertengahan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="info-box bg-teal">
                                <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Izin / Sakit (UKS)</span>
                                    <span class="info-box-number">{{ $totalIzinSakit }}</span>
                                    <span class="progress-description">Tercatat di pengawasan musyrif</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="info-box bg-red">
                                <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Alpa / Tanpa Keterangan</span>
                                    <span class="info-box-number">{{ $totalAlpa }}</span>
                                    <span class="progress-description">Tindak lanjut bimbingan konseling</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Tanggal & Sesi Ibadah</th>
                                    <th>Nama Santri</th>
                                    <th>Kamar</th>
                                    <th class="text-center">Status Kehadiran</th>
                                    <th>Lokasi Pelaksanaan</th>
                                    <th>Musyrif Pengawas</th>
                                    <th>Catatan Kedisiplinan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrichedAttendance as $idx => $att)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ $att['date'] }}</strong><br>
                                        <span class="label label-primary">{{ $att['session'] }}</span>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <strong style="color: #1a365d; font-size: 14px;">{{ $att['student_name'] }}</strong><br>
                                        <small class="text-muted">NISN: {{ $att['nisn'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">Kamar {{ $att['room_number'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        @if($att['status'] === 'hadir')
                                            <span class="label label-success"><i class="fa fa-check"></i> Hadir</span>
                                        @elseif($att['status'] === 'masbuq')
                                            <span class="label label-warning"><i class="fa fa-clock-o"></i> Masbuq</span>
                                        @elseif($att['status'] === 'sakit')
                                            <span class="label label-info"><i class="fa fa-medkit"></i> Sakit</span>
                                        @elseif($att['status'] === 'izin')
                                            <span class="label label-default"><i class="fa fa-info-circle"></i> Izin</span>
                                        @else
                                            <span class="label label-danger"><i class="fa fa-times"></i> Alpa</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;"><i class="fa fa-map-marker text-muted"></i> {{ $att['location'] }}</td>
                                    <td style="vertical-align: middle; font-size: 12px;"><i class="fa fa-user-circle text-muted"></i> {{ $att['supervisor_name'] }}</td>
                                    <td style="vertical-align: middle; font-size: 12px; color: #4a5568;">{{ $att['notes'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                        Belum ada log presensi shalat jamaah tercatat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 4: EVALUASI DAPUR & KONSUMSI -->
                <div class="tab-pane" id="tab-konsumsi">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-cutlery text-purple"></i> Ringkasan Evaluasi Katering & Dapur Asrama
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Standar gizi makanan harian santri, rotasi menu 4 sehat 5 sempurna, dan logistik porsi makan.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Laporan Dapur
                            </button>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-4">
                            <div class="box box-solid" style="border: 1px solid #e2e8f0; background-color: #faf5ff;">
                                <div class="box-body text-center">
                                    <h3 style="color: #6b46c1; margin-top: 5px; font-weight: bold;">{{ $totalMealVariations }} Variasi</h3>
                                    <p class="text-muted" style="margin-bottom: 0;">Siklus Menu Terjadwal Mingguan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-solid" style="border: 1px solid #e2e8f0; background-color: #ebf8ff;">
                                <div class="box-body text-center">
                                    <h3 style="color: #2b6cb0; margin-top: 5px; font-weight: bold;">{{ $avgMealCalories }} kkal</h3>
                                    <p class="text-muted" style="margin-bottom: 0;">Rata-rata Kalori Sehat per Sesi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-solid" style="border: 1px solid #e2e8f0; background-color: #f0fff4;">
                                <div class="box-body text-center">
                                    <h3 style="color: #2f855a; margin-top: 5px; font-weight: bold;">{{ number_format($totalWeeklyPortions, 0, ',', '.') }} Porsi</h3>
                                    <p class="text-muted" style="margin-bottom: 0;">Estimasi Total Porsi Dapur Mingguan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Hari & Sesi</th>
                                    <th>Nama Menu Hidangan</th>
                                    <th>Komposisi Menu</th>
                                    <th class="text-center">Kalori</th>
                                    <th>Standar Gizi & Alergen</th>
                                    <th>Koki Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meals as $idx => $m)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ $m['day'] }}</strong><br>
                                        <span class="label label-default">{{ $m['meal_time'] }} ({{ $m['time_range'] }})</span>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <strong style="color: #1a365d; font-size: 13px;">{{ $m['menu_title'] }}</strong><br>
                                        <small class="text-muted">Porsi: {{ $m['portion_count'] }} santri | {{ $m['halal_status'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">
                                        <strong>Utama:</strong> {{ $m['main_dish'] }}<br>
                                        <strong>Pendamping:</strong> {{ $m['side_dish'] }}<br>
                                        <strong>Sayur:</strong> {{ $m['vegetable'] }} | <strong>Buah:</strong> {{ $m['fruit_drink'] }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-purple">{{ $m['calories'] }} kkal</span>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">
                                        <div style="color: #2d3748;"><i class="fa fa-heartbeat text-red"></i> {{ $m['nutrition_highlight'] }}</div>
                                        <div style="color: #c53030; font-size: 11px;"><i class="fa fa-warning"></i> {{ $m['allergy_notes'] }}</div>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">
                                        <i class="fa fa-user-circle text-muted"></i> <strong>{{ $m['chef_in_charge'] }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
