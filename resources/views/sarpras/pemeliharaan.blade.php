@extends('layouts.app')

@section('title', 'Pemeliharaan & Laporan Kerusakan Sarpras')

@section('content_header')
    <h1>
        <i class="fa fa-wrench text-warning"></i> Pemeliharaan & Perbaikan Sarpras
        <small>Pusat Laporan Kerusakan Fasilitas, Penugasan Teknisi & Log Riwayat Servis</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Sarpras & Fasilitas</li>
        <li class="active">Pemeliharaan</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-ticket"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Tiket Laporan</span>
                <span class="info-box-number">{{ $totalReports }} Kasus</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Kerusakan sarana terdaftar</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Darurat / Prioritas Tinggi</span>
                <span class="info-box-number">{{ $totalEmergency }} Kasus</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalReports > 0 ? round(($totalEmergency / $totalReports) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Perlu penanganan segera</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sedang Dikerjakan</span>
                <span class="info-box-number">{{ $totalInProgress }} Kasus</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalReports > 0 ? round(($totalInProgress / $totalReports) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Penanganan teknisi / vendor</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Perbaikan Selesai</span>
                <span class="info-box-number">{{ $totalCompleted }} Kasus</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalReports > 0 ? round(($totalCompleted / $totalReports) * 100) : 0 }}%"></div></div>
                <span class="progress-description">{{ $totalReports > 0 ? round(($totalCompleted / $totalReports) * 100) : 0 }}% tingkat penyelesaian</span>
            </div>
        </div>
    </div>
</div>

{{-- Feedback Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Sukses Berhasil!</h4>
        {{ session('success') }}
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan</h4>
        {{ session('info') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Buku Tiket Kerusakan & Servis</a>
                </li>
                <li class="{{ $activeTab === 'teknisi' ? 'active' : '' }}">
                    <a href="#tab_teknisi" data-toggle="tab"><i class="fa fa-users text-green"></i> Tim Teknisi & Vendor Rekanan</a>
                </li>
                <li class="{{ $activeTab === 'rekap' ? 'active' : '' }}">
                    <a href="#tab_rekap" data-toggle="tab"><i class="fa fa-bar-chart text-purple"></i> Rekapitulasi Biaya & Kategori Servis</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Buku Tiket Kerusakan & Servis --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('sarpras.pemeliharaan') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Status:</label>
                                    <select name="status" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                        <option value="dilaporkan" {{ $statusFilter === 'dilaporkan' ? 'selected' : '' }}>Baru Dilaporkan</option>
                                        <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui / Dijadwalkan</option>
                                        <option value="dalam_pengerjaan" {{ $statusFilter === 'dalam_pengerjaan' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                                        <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai / Tuntas</option>
                                        <option value="ditunda" {{ $statusFilter === 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Prioritas:</label>
                                    <select name="priority" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $priorityFilter === 'all' ? 'selected' : '' }}>-- Semua Prioritas --</option>
                                        <option value="darurat" {{ $priorityFilter === 'darurat' ? 'selected' : '' }}>Darurat (Emergency)</option>
                                        <option value="tinggi" {{ $priorityFilter === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                        <option value="sedang" {{ $priorityFilter === 'sedang' ? 'selected' : '' }}>Sedang</option>
                                        <option value="rendah" {{ $priorityFilter === 'rendah' ? 'selected' : '' }}>Rendah</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kategori:</label>
                                    <select name="category" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>-- Semua Kategori --</option>
                                        <option value="kelistrikan" {{ $categoryFilter === 'kelistrikan' ? 'selected' : '' }}>Kelistrikan & Genset</option>
                                        <option value="ac_pendingin" {{ $categoryFilter === 'ac_pendingin' ? 'selected' : '' }}>Tata Udara & AC</option>
                                        <option value="sipil_bangunan" {{ $categoryFilter === 'sipil_bangunan' ? 'selected' : '' }}>Sipil & Konstruksi</option>
                                        <option value="sanitasi_plumbing" {{ $categoryFilter === 'sanitasi_plumbing' ? 'selected' : '' }}>Sanitasi & Plumbing</option>
                                        <option value="mebelair" {{ $categoryFilter === 'mebelair' ? 'selected' : '' }}>Sarana / Mebelair</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Judul, tiket, teknisi..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('sarpras.pemeliharaan', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-lapor-kerusakan">
                                        <i class="fa fa-plus-circle"></i> Lapor Kerusakan Baru
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Tiket Pemeliharaan --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Tiket</th>
                                    <th>Masalah / Kerusakan</th>
                                    <th>Aset & Lokasi</th>
                                    <th class="text-center">Prioritas</th>
                                    <th>Pelapor</th>
                                    <th>Teknisi / Biaya</th>
                                    <th class="text-center" width="130">Progres & Status</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredReports as $rep)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $rep['ticket_code'] }}</span><br>
                                            <small class="text-muted"><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($rep['report_date'])) }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $rep['title'] }}</strong><br>
                                            <span class="label label-info" style="font-size: 10px;">{{ $rep['category_label'] }}</span>
                                        </td>
                                        <td>
                                            <span class="text-bold text-navy">{{ $rep['asset_name'] }}</span><br>
                                            <small class="text-muted"><i class="fa fa-map-marker text-red"></i> {{ $rep['location'] }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($rep['priority'] === 'darurat')
                                                <span class="label label-danger"><i class="fa fa-bolt"></i> DARURAT</span>
                                            @elseif($rep['priority'] === 'tinggi')
                                                <span class="label label-warning"><i class="fa fa-arrow-up"></i> TINGGI</span>
                                            @elseif($rep['priority'] === 'sedang')
                                                <span class="label label-primary"><i class="fa fa-minus"></i> SEDANG</span>
                                            @else
                                                <span class="label label-default"><i class="fa fa-arrow-down"></i> RENDAH</span>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> {{ $rep['reporter_name'] }}<br>
                                            <small class="text-muted">{{ $rep['reporter_role'] }} ({{ $rep['unit_id'] }})</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-wrench text-warning"></i> <strong>{{ $rep['assigned_to'] ?: 'Belum Ditugaskan' }}</strong><br>
                                            <small class="text-muted">Biaya: Rp {{ number_format($rep['actual_cost'] > 0 ? $rep['actual_cost'] : $rep['estimated_cost'], 0, ',', '.') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress progress-xs" style="margin-bottom: 3px;">
                                                <div class="progress-bar progress-bar-{{ $rep['progress'] == 100 ? 'success' : ($rep['progress'] > 40 ? 'warning' : 'primary') }}" style="width: {{ $rep['progress'] }}%"></div>
                                            </div>
                                            <small class="text-bold">{{ $rep['progress'] }}%</small><br>
                                            @if($rep['status'] === 'selesai')
                                                <span class="label label-success"><i class="fa fa-check"></i> Selesai</span>
                                            @elseif($rep['status'] === 'dalam_pengerjaan')
                                                <span class="label label-warning"><i class="fa fa-cogs"></i> Dikerjakan</span>
                                            @elseif($rep['status'] === 'disetujui')
                                                <span class="label label-primary"><i class="fa fa-thumbs-up"></i> Terjadwal</span>
                                            @elseif($rep['status'] === 'ditunda')
                                                <span class="label label-default">Ditunda</span>
                                            @else
                                                <span class="label label-danger">Baru Masuk</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-rep-{{ $rep['id'] }}" title="Lihat SPK & Detail">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-progress-rep-{{ $rep['id'] }}" title="Update Progres Servis">
                                                    <i class="fa fa-wrench text-warning"></i>
                                                </button>
                                                <a href="{{ route('sarpras.pemeliharaan.destroy', $rep['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket laporan ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Surat Perintah Kerja (SPK) Servis Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-rep-{{ $rep['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-warning">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title" style="color: #333;"><i class="fa fa-wrench"></i> Lembar Kerja Servis & Perbaikan Sarpras</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">BAGIAN PEMELIHARAAN SARANA, PRASARANA & LOGISTIK KAMPUS</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">TIKET LAPORAN & SURAT PERINTAH KERJA (SPK)</h5>
                                                                <small>Nomor: <strong>{{ $rep['ticket_code'] }}</strong></small>
                                                            </div>

                                                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                                <tr>
                                                                    <th width="35%">Nama Masalah / Kerusakan</th>
                                                                    <td><strong class="text-primary">{{ $rep['title'] }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Aset Sarana Terkait</th>
                                                                    <td>{{ $rep['asset_name'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Lokasi Kampus</th>
                                                                    <td><i class="fa fa-map-marker text-red"></i> {{ $rep['location'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kategori & Tingkat Urgensi</th>
                                                                    <td>
                                                                        <span class="label label-info">{{ $rep['category_label'] }}</span> &mdash;
                                                                        <strong>Prioritas: {{ strtoupper($rep['priority']) }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Pelapor / Saksi</th>
                                                                    <td>{{ $rep['reporter_name'] }} ({{ $rep['reporter_role'] }} - {{ $rep['unit_id'] }})</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tanggal Pelaporan</th>
                                                                    <td>{{ date('d F Y', strtotime($rep['report_date'])) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Uraian Kerusakan</th>
                                                                    <td>{{ $rep['description'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Teknisi / Rekanan Pelaksana</th>
                                                                    <td><i class="fa fa-user-circle"></i> <strong>{{ $rep['assigned_to'] ?: 'Belum Ditugaskan' }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Biaya Servis / Penggantian Part</th>
                                                                    <td class="text-bold text-green">
                                                                        Estimasi: Rp {{ number_format($rep['estimated_cost'], 0, ',', '.') }}<br>
                                                                        Realisasi: Rp {{ number_format($rep['actual_cost'], 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status Pengerjaan & Progres</th>
                                                                    <td>
                                                                        <strong>{{ strtoupper($rep['status']) }}</strong> ({{ $rep['progress'] }}%)
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Catatan Hasil Penanganan</th>
                                                                    <td><small class="text-muted">{{ $rep['resolution_notes'] }}</small></td>
                                                                </tr>
                                                            </table>

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Teknisi / Pelaksana,</small><br><br><br>
                                                                    <strong>({{ $rep['assigned_to'] ?: 'Teknisi Sarpras' }})</strong>
                                                                </div>
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Mengetahui,</small><br>
                                                                    <small>Koordinator Sarpras Alia,</small><br><br>
                                                                    <strong>(Dr. H. Zulkifli, Lc., M.A.)</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak SPK Servis</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Update Progres Servis --}}
                                            <div class="modal fade" id="modal-progress-rep-{{ $rep['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <form method="POST" action="{{ route('sarpras.pemeliharaan.progress', $rep['id']) }}">
                                                            @csrf
                                                            <div class="modal-header bg-primary">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title"><i class="fa fa-wrench"></i> Update Progres Servis & Tindakan Teknisi</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Tiket: <strong>{{ $rep['ticket_code'] }}</strong> &mdash; {{ $rep['title'] }}</p>

                                                                <div class="form-group">
                                                                    <label>Status Penanganan: <span class="text-danger">*</span></label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="disetujui" {{ $rep['status'] === 'disetujui' ? 'selected' : '' }}>Disetujui / Terjadwal</option>
                                                                        <option value="dalam_pengerjaan" {{ $rep['status'] === 'dalam_pengerjaan' ? 'selected' : '' }}>Dalam Pengerjaan (Sedang Dikerjakan)</option>
                                                                        <option value="selesai" {{ $rep['status'] === 'selesai' ? 'selected' : '' }}>Selesai / Tuntas Diperbaiki</option>
                                                                        <option value="ditunda" {{ $rep['status'] === 'ditunda' ? 'selected' : '' }}>Ditunda (Menunggu Suku Cadang)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Persentase Progres Penyelesaian (%): <span class="text-danger">*</span></label>
                                                                    <input type="number" name="progress" class="form-control" min="0" max="100" value="{{ $rep['progress'] }}" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Penugasan Teknisi / Vendor:</label>
                                                                    <input type="text" name="assigned_to" class="form-control" value="{{ $rep['assigned_to'] }}" placeholder="Nama teknisi internal atau vendor rekanan...">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Realisasi Biaya Servis / Sparepart (Rp):</label>
                                                                    <input type="number" name="actual_cost" class="form-control" min="0" value="{{ $rep['actual_cost'] }}" placeholder="Masukkan total biaya riil setelah pengerjaan...">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Catatan Log Tindakan / Penyelesaian Kerusakan: <span class="text-danger">*</span></label>
                                                                    <textarea name="resolution_notes" class="form-control" rows="3" required placeholder="Jelaskan komponen yang diganti, hasil uji coba, garansi, dll...">{{ $rep['resolution_notes'] }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Progres Servis</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-check-circle fa-2x text-green"></i><br>
                                            Tidak ada laporan kerusakan sarana prasarana yang cocok dengan filter pencarian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Tim Teknisi & Vendor Rekanan --}}
                <div class="tab-pane {{ $activeTab === 'teknisi' ? 'active' : '' }}" id="tab_teknisi">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-user-circle text-primary"></i> Tim Teknisi Internal Sarpras Alia</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Nama Teknisi</th>
                                                <th>Spesialisasi Bidang</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Kasus Aktif</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Pak Joko Sutrisno</strong></td>
                                                <td>Kelistrikan, Genset & Plumbing</td>
                                                <td class="text-center"><span class="badge bg-green">Standby</span></td>
                                                <td class="text-center text-bold">2</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dwi Wahyudi</strong></td>
                                                <td>Tata Udara, AC Sentral & Refrigrasi</td>
                                                <td class="text-center"><span class="badge bg-blue">Tersedia</span></td>
                                                <td class="text-center text-bold">0</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Agus Rohman</strong></td>
                                                <td>Tukang Kayu, Mebelair & Finishing</td>
                                                <td class="text-center"><span class="badge bg-green">Standby</span></td>
                                                <td class="text-center text-bold">1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-building-o text-warning"></i> Vendor & Rekanan Eksternal Resmi</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Nama Rekanan</th>
                                                <th>Bidang Pekerjaan</th>
                                                <th>Kontak Darurat</th>
                                                <th class="text-center">SLA Tanggap</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>PT Daya Genset Nusantara</strong></td>
                                                <td>Maintenance Genset & Gardu Trafo</td>
                                                <td>0812-9988-7711</td>
                                                <td class="text-center"><span class="label label-success">&lt; 4 Jam</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>CV Karya Cipta Mandiri</strong></td>
                                                <td>Konstruksi Sipil, Atap & Renovasi</td>
                                                <td>0813-8877-6622</td>
                                                <td class="text-center"><span class="label label-primary">&lt; 24 Jam</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Bengkel Las Sportindo Jaya</strong></td>
                                                <td>Sarana Lapangan & Pagar Besi</td>
                                                <td>0817-6655-4433</td>
                                                <td class="text-center"><span class="label label-info">&lt; 48 Jam</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Rekapitulasi Biaya & Kategori Servis --}}
                <div class="tab-pane {{ $activeTab === 'rekap' ? 'active' : '' }}" id="tab_rekap">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-money text-green"></i> Ringkasan Alokasi Anggaran Pemeliharaan Sarpras</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>Komponen Biaya</th>
                                        <th class="text-right">Nominal (Rp)</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Estimasi Biaya Pemeliharaan</td>
                                        <td class="text-right text-bold">Rp {{ number_format($totalEstimatedCost, 0, ',', '.') }}</td>
                                        <td class="text-center">Perkiraan RAB Tiket</td>
                                    </tr>
                                    <tr>
                                        <td>Total Realisasi Biaya Dikeluarkan</td>
                                        <td class="text-right text-bold text-navy">Rp {{ number_format($totalActualCost, 0, ',', '.') }}</td>
                                        <td class="text-center">Tagihan Selesai & Dalam Proses</td>
                                    </tr>
                                    <tr>
                                        <td>Sisa Efisiensi Anggaran Kas</td>
                                        <td class="text-right text-bold text-green">
                                            @php $saving = max(0, $totalEstimatedCost - $totalActualCost); @endphp
                                            Rp {{ number_format($saving, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">Surplus Efisiensi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-shield text-primary"></i> Kebijakan Garansi & Prosedur Preventive Maintenance</h4>
                            <div class="callout callout-info">
                                <h4>Jadwal Servis Berkala (Preventive Routine)</h4>
                                <p>Setiap 3 bulan sekali dilakukan pemeriksaan rutin terhadap instalasi genset 100 kVA, pengurasan tandon air bersih, pengecekan tekanan freon AC kelas & laboratorium, serta uji coba hydrant pemadam kebakaran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Lapor Kerusakan Fasilitas Baru --}}
<div class="modal fade" id="modal-lapor-kerusakan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('sarpras.pemeliharaan.store') }}">
                @csrf
                <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="color: #333;"><i class="fa fa-plus-circle"></i> Buat Laporan Kerusakan & Permohonan Servis Sarpras</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Aset / Ruangan Yang Rusak: <span class="text-danger">*</span></label>
                                <select name="asset_id" class="form-control" required>
                                    @foreach($reportableAssets as $ra)
                                        <option value="{{ $ra['id'] }}">{{ $ra['name'] }} ({{ $ra['location'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Judul Masalah Kerusakan: <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Contoh: AC Bocor Menetes / Kran Air Patah / Lampu Padam" required>
                            </div>
                            <div class="form-group">
                                <label>Kategori Bidang Perbaikan: <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="kelistrikan">Kelistrikan & Penerangan</option>
                                    <option value="ac_pendingin">Tata Udara & AC</option>
                                    <option value="sipil_bangunan">Sipil & Konstruksi Gedung</option>
                                    <option value="sanitasi_plumbing">Sanitasi & Plumbing</option>
                                    <option value="elektronik_it">Elektronik & Perangkat IT</option>
                                    <option value="mebelair">Mebelair & Sarana Kampus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tingkat Urgensi / Prioritas: <span class="text-danger">*</span></label>
                                <select name="priority" class="form-control" required>
                                    <option value="darurat">Darurat (Emergency - Menghambat KBM/Bahaya Fisik)</option>
                                    <option value="tinggi" selected>Tinggi (Perlu Penanganan Cepat)</option>
                                    <option value="sedang">Sedang (Kerusakan Parsial)</option>
                                    <option value="rendah">Rendah (Dapat Dijadwalkan Berkala)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Lokasi / Titik Spesifik Kerusakan: <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="Contoh: Gedung Umar Lantai 2 Koridor Barat" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Ditemukan Kerusakan: <span class="text-danger">*</span></label>
                                <input type="date" name="report_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Pelapor (Guru/Staf/Petugas): <span class="text-danger">*</span></label>
                                <input type="text" name="reporter_name" class="form-control" placeholder="Contoh: Ust. Fauzan Azhim, S.T." required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Pelapor: <span class="text-danger">*</span></label>
                                <input type="text" name="reporter_role" class="form-control" placeholder="Contoh: Pembina Asrama / Guru IPA" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Sekolah: <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="UNIT-SMA">SMA Islam Alia</option>
                                    <option value="UNIT-SMP">SMP Islam Alia</option>
                                    <option value="UNIT-SD">SD Islam Alia</option>
                                    <option value="YAYASAN">Yayasan / Fasilitas Umum</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Langsung Tugaskan Teknisi (Opsional):</label>
                                <input type="text" name="assigned_to" class="form-control" placeholder="Contoh: Pak Joko Sutrisno (Teknisi Listrik)">
                            </div>
                            <div class="form-group">
                                <label>Perkiraan Estimasi Biaya (Rp - Opsional):</label>
                                <input type="number" name="estimated_cost" class="form-control" min="0" placeholder="Contoh: 500000">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi Detail Kerusakan & Gejala: <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="3" required placeholder="Jelaskan secara rinci kondisi fisik yang rusak, penyebab yang diketahui, dan potensi dampaknya..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-paper-plane"></i> Kirim Laporan Kerusakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

