@extends('layouts.app')

@section('title', 'Unit Kesehatan Sekolah (UKS) — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-medkit text-red"></i> Unit Kesehatan Sekolah (UKS) & Layanan Medis Santri
        <small>Pemeriksaan Kesehatan, Buku Register Kunjungan, Inventaris Obat-obatan & Rujukan Faskes</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Kesiswaan</li>
        <li class="active">UKS & Kesehatan</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan UKS --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-heartbeat"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Register Medis</span>
                <span class="info-box-number">{{ $totalKunjungan }} <small style="color: #cbd5e0; font-size: 11px;">Pemeriksaan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Buku Register UKS</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-user-md"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai / Teratasi</span>
                <span class="info-box-number">{{ $totalSelesai }} <small style="color: #d1fae5; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Penanganan Internal UKS</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-ambulance"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rujukan RS / Klinik</span>
                <span class="info-box-number">{{ $totalDirujuk }} <small style="color: #fecaca; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #fef2f2;">Fasilitas Kesehatan Lanjutan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-pills"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Obat Kritis</span>
                <span class="info-box-number">{{ $totalObatKritis }} <small style="color: #fff; font-size: 11px;">Item (< 15 stok)</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Perlu Restock Segera</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert Notifikasi Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
    </div>
@endif

{{-- Dwi-Tab: Buku Kunjungan & Inventaris Obat --}}
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'kunjungan' ? 'active' : '' }}">
            <a href="{{ route('kesiswaan.uks', ['tab' => 'kunjungan']) }}"><i class="fa fa-stethoscope text-red"></i> <strong>Buku Register Kunjungan Medis</strong></a>
        </li>
        <li class="{{ $activeTab === 'obat' ? 'active' : '' }}">
            <a href="{{ route('kesiswaan.uks', ['tab' => 'obat']) }}"><i class="fa fa-archive text-yellow"></i> <strong>Inventaris Obat & Alat P3K</strong></a>
        </li>
        <li class="pull-right">
            @if($activeTab === 'kunjungan')
                <button type="button" class="btn btn-primary btn-sm margin-r-5" data-toggle="modal" data-target="#modalTambahKunjungan">
                    <i class="fa fa-plus-circle"></i> Catat Pemeriksaan Santri
                </button>
            @else
                <button type="button" class="btn btn-success btn-sm margin-r-5" data-toggle="modal" data-target="#modalTambahObat">
                    <i class="fa fa-plus-circle"></i> Tambah Item Obat
                </button>
            @endif
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Laporan
            </button>
        </li>
    </ul>

    <div class="tab-content" style="padding: 15px 0 0 0;">
        @if($activeTab === 'kunjungan')
            {{-- TAB 1: REGISTER KUNJUNGAN --}}
            <div class="tab-pane active" id="tab_kunjungan">
                {{-- Filter Bar --}}
                <div style="background-color: #f9fafb; padding: 12px 15px; border-bottom: 1px solid #e5e7eb; margin-top: -15px; margin-bottom: 15px;">
                    <form method="GET" action="{{ route('kesiswaan.uks') }}" class="form-inline">
                        <input type="hidden" name="tab" value="kunjungan">

                        <div class="form-group margin-r-5">
                            <label class="sr-only" for="status">Status</label>
                            <select name="status" class="form-control input-sm">
                                <option value="">-- Semua Status Medis --</option>
                                <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai (Rawat Jalan)</option>
                                <option value="diobservasi" {{ $statusFilter === 'diobservasi' ? 'selected' : '' }}>Diobservasi di Bed UKS</option>
                                <option value="dirujuk_faskes" {{ $statusFilter === 'dirujuk_faskes' ? 'selected' : '' }}>Dirujuk ke RS / Klinik</option>
                            </select>
                        </div>

                        <div class="form-group margin-r-5">
                            <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari santri / diagnosis / gejala..." style="width: 200px;">
                        </div>

                        <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
                        @if($statusFilter || $search)
                            <a href="{{ route('kesiswaan.uks', ['tab' => 'kunjungan']) }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
                        @endif
                    </form>
                </div>

                <div class="table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr style="background-color: #f4f6f9; color: #334155;">
                                <th style="width: 40px; text-align: center;">#</th>
                                <th>Santri & Kelas</th>
                                <th>Waktu & Paramedis</th>
                                <th>Tanda Vital</th>
                                <th>Keluhan & Diagnosis Medis</th>
                                <th>Terapi & Tindakan</th>
                                <th style="text-align: center;">Status</th>
                                <th style="width: 85px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visits as $index => $v)
                                @php
                                    $std = $studentMap[$v['student_id']] ?? null;
                                    $cls = $std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null;
                                    
                                    $statusBadge = match($v['status']) {
                                        'selesai' => '<span class="label label-success"><i class="fa fa-check"></i> Selesai</span>',
                                        'diobservasi' => '<span class="label label-warning"><i class="fa fa-bed"></i> Observasi</span>',
                                        'dirujuk_faskes' => '<span class="label label-danger"><i class="fa fa-ambulance"></i> Rujukan RS</span>',
                                        default => '<span class="label label-default">Draft</span>'
                                    };
                                @endphp
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ $std['name'] ?? 'Santri #'.$v['student_id'] }}</strong><br>
                                        <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</small><br>
                                        @if($v['parent_notified'])
                                            <span class="badge bg-green" style="font-size: 9px;"><i class="fa fa-phone"></i> Ortu Diberitahu</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <i class="fa fa-calendar"></i> {{ date('d/m/Y H:i', strtotime($v['visit_date'])) }}<br>
                                        <small class="text-muted"><i class="fa fa-user-md"></i> {{ $v['paramedic_name'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <span class="label label-info"><i class="fa fa-thermometer-half"></i> {{ $v['temperature'] }}</span><br>
                                        <small class="text-muted">TD: {{ $v['blood_pressure'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle; max-width: 250px;">
                                        <span style="font-weight: 600; color: #b91c1c;">{{ $v['diagnosis'] }}</span>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                            {{ Str::limit($v['symptoms'], 75) }}
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; max-width: 230px;">
                                        <div style="font-size: 11px; color: #047857; font-weight: 500;">
                                            <i class="fa fa-medkit"></i> {{ Str::limit($v['medicines_prescribed'], 60) }}
                                        </div>
                                        <small class="text-muted">{{ Str::limit($v['action_taken'], 60) }}</small>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        {!! $statusBadge !!}
                                        @if($v['referral_hospital'])
                                            <br><small class="text-danger" style="font-size: 10px;">{{ $v['referral_hospital'] }}</small>
                                        @endif
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailKunjungan{{ $v['id'] }}" title="Lihat Rekam Medis">
                                                <i class="fa fa-eye text-primary"></i>
                                            </button>
                                            <a href="{{ route('kesiswaan.uks.visit.destroy', $v['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Hapus rekam medis kunjungan ini?')" title="Hapus">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Detail Rekam Medis Santri --}}
                                <div class="modal fade" id="modalDetailKunjungan{{ $v['id'] }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                                                <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                                <h4 class="modal-title"><i class="fa fa-file-text-o text-yellow"></i> Rekam Medis Pemeriksaan UKS [{{ $v['id'] }}]</h4>
                                            </div>
                                            <div class="modal-body" style="background-color: #fdfdfd;">
                                                <div class="text-center" style="margin-bottom: 15px; padding: 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px;">
                                                    <h4 style="margin: 0 0 5px 0; font-weight: bold; color: #991b1b;">{{ $std['name'] ?? '-' }}</h4>
                                                    <span class="label label-primary">{{ $cls['name'] ?? '-' }}</span> &nbsp;
                                                    <span class="label label-info">NIS: {{ $std['nis'] ?? '-' }}</span> &nbsp;
                                                    {!! $statusBadge !!}
                                                </div>

                                                <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                    <tr>
                                                        <th style="width: 35%;">Waktu Kunjungan</th>
                                                        <td>{{ date('l, d F Y H:i', strtotime($v['visit_date'])) }} WIB</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tenaga Medis / Paramedis</th>
                                                        <td><strong>{{ $v['paramedic_name'] }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tanda-tanda Vital</th>
                                                        <td>
                                                            Suhu Tubuh: <strong>{{ $v['temperature'] }}</strong> | 
                                                            Tekanan Darah: <strong>{{ $v['blood_pressure'] }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Keluhan Santri (Anamnesis)</th>
                                                        <td style="background: #fff; line-height: 1.5;">{{ $v['symptoms'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Diagnosis Medis</th>
                                                        <td><span class="label label-danger" style="font-size: 12px;">{{ $v['diagnosis'] }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Terapi & Tindakan Medis</th>
                                                        <td style="background: #f0fdf4; color: #166534;">{{ $v['treatment_given'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resep Obat yang Diberikan</th>
                                                        <td><strong>{{ $v['medicines_prescribed'] }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tindakan Lanjutan / Disposisi</th>
                                                        <td>{{ $v['action_taken'] }}</td>
                                                    </tr>
                                                    @if($v['referral_hospital'])
                                                        <tr>
                                                            <th>Rumah Sakit Rujukan</th>
                                                            <td><strong class="text-red"><i class="fa fa-ambulance"></i> {{ $v['referral_hospital'] }}</strong></td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <th>Notifikasi Wali Santri</th>
                                                        <td>
                                                            @if($v['parent_notified'])
                                                                <span class="text-green"><i class="fa fa-check-circle"></i> Sudah dihubungi via telepon</span>
                                                            @else
                                                                <span class="text-muted"><i class="fa fa-times-circle"></i> Tidak memerlukan pemberitahuan darurat</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Catatan Tambahan Paramedis</th>
                                                        <td><em>"{{ $v['notes'] }}"</em></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Surat Keterangan Sakit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                                        <i class="fa fa-info-circle fa-2x"></i><br>
                                        Belum ada data kunjungan medis yang cocok dengan filter pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- TAB 2: INVENTARIS OBAT --}}
            <div class="tab-pane active" id="tab_obat">
                <div class="table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr style="background-color: #f4f6f9; color: #334155;">
                                <th style="width: 40px; text-align: center;">#</th>
                                <th>Nama Obat & Sediaan</th>
                                <th>Kategori Farmasi</th>
                                <th style="text-align: center;">Sisa Stok</th>
                                <th>Indikasi Medis</th>
                                <th>Masa Kedaluwarsa</th>
                                <th style="text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $index => $m)
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ $m['name'] }}</strong><br>
                                        <small class="text-muted">Satuan: {{ $m['unit'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <span class="label label-default">{{ $m['category'] }}</span>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="badge {{ $m['stock'] <= 15 ? 'bg-red' : ($m['stock'] <= 50 ? 'bg-yellow' : 'bg-green') }}" style="font-size: 13px;">
                                            {{ $m['stock'] }} {{ $m['unit'] }}
                                        </span>
                                    </td>
                                    <td style="vertical-align: middle;">{{ $m['indication'] }}</td>
                                    <td style="vertical-align: middle;">
                                        <i class="fa fa-calendar-times-o text-muted"></i> {{ date('d F Y', strtotime($m['expiry_date'])) }}
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        @if($m['stock'] <= 15)
                                            <span class="label label-danger">Kritis</span>
                                        @else
                                            <span class="label label-success">Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal Catat Kunjungan / Pemeriksaan Santri Baru --}}
<div class="modal fade" id="modalTambahKunjungan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('kesiswaan.uks.visit.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Catat Pemeriksaan Medis Santri</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Pilih Santri Pasien <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($students as $s)
                                <option value="{{ $s['id'] }}">{{ $s['name'] }} (NIS: {{ $s['nis'] ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paramedic_name">Tenaga Medis / Paramedis <span class="text-danger">*</span></label>
                                <input type="text" name="paramedic_name" id="paramedic_name" value="Ns. Rahmatia Hidayah, S.Kep." class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="visit_date">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                                <input type="date" name="visit_date" id="visit_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="temperature">Suhu Tubuh (°C) <span class="text-danger">*</span></label>
                                <input type="text" name="temperature" id="temperature" value="37.5 °C" class="form-control" placeholder="Cth: 37.8 °C" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="blood_pressure">Tekanan Darah <span class="text-danger">*</span></label>
                                <input type="text" name="blood_pressure" id="blood_pressure" value="110/70 mmHg" class="form-control" placeholder="Cth: 120/80 mmHg" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="symptoms">Keluhan & Gejala (Anamnesis) <span class="text-danger">*</span></label>
                        <textarea name="symptoms" id="symptoms" rows="2" class="form-control" placeholder="Uraikan keluhan yang dirasakan santri..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="diagnosis">Diagnosis Medis Sementara <span class="text-danger">*</span></label>
                        <input type="text" name="diagnosis" id="diagnosis" class="form-control" placeholder="Cth: ISPA Akut / Dispepsia / Cephalgia" required>
                    </div>

                    <div class="form-group">
                        <label for="treatment_given">Tindakan Medis & Penanganan UKS <span class="text-danger">*</span></label>
                        <textarea name="treatment_given" id="treatment_given" rows="2" class="form-control" placeholder="Cth: Istirahat di bed UKS, kompres hangat, terapi cairan..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medicines_prescribed">Obat yang Diberikan <span class="text-danger">*</span></label>
                                <input type="text" name="medicines_prescribed" id="medicines_prescribed" class="form-control" placeholder="Cth: Paracetamol 500mg 3x1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action_taken">Disposisi / Tindak Lanjut <span class="text-danger">*</span></label>
                                <input type="text" name="action_taken" id="action_taken" class="form-control" placeholder="Cth: Istirahat di asrama / Rawat jalan" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status Penanganan Medis <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="selesai" selected>Selesai (Kembali ke Asrama/Kelas)</option>
                                    <option value="diobservasi">Diobservasi di Ruang UKS</option>
                                    <option value="dirujuk_faskes">Dirujuk ke RS / Klinik Faskes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="referral_hospital">Nama RS Rujukan (Bila Dirujuk)</label>
                                <input type="text" name="referral_hospital" id="referral_hospital" class="form-control" placeholder="Cth: RS Islam Karawang">
                            </div>
                        </div>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="parent_notified" value="1"> <strong>Beritahu Orang Tua / Wali Santri</strong> (Kondisi darurat / rujukan)
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan Tambahan</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Catatan khusus untuk musyrif asrama..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan ke Register UKS</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Stok Obat Baru --}}
<div class="modal fade" id="modalTambahObat" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('kesiswaan.uks.medicine.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #047857; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Item Obat / P3K UKS</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Obat & Sediaan <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Cth: Amoxicillin 500mg Tablet" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Kategori Farmasi <span class="text-danger">*</span></label>
                                <input type="text" name="category" id="category" class="form-control" placeholder="Cth: Antibiotik / Analgesik" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit">Satuan Sediaan <span class="text-danger">*</span></label>
                                <input type="text" name="unit" id="unit" class="form-control" placeholder="Cth: Tablet / Botol / Strip" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" id="stock" value="50" min="1" max="1000" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expiry_date">Tanggal Kedaluwarsa <span class="text-danger">*</span></label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ date('Y-m-d', strtotime('+2 years')) }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="indication">Indikasi Medis & Kegunaan <span class="text-danger">*</span></label>
                        <input type="text" name="indication" id="indication" class="form-control" placeholder="Cth: Mengobati infeksi bakteri saluran pernapasan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Daftarkan Obat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

