@extends('layouts.app')

@section('title', 'Gedung & Kamar Asrama — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-bed text-primary"></i> Manajemen Gedung & Kamar Asrama
        <small>Tata Kelola Gedung, Blok Lantai, Kapasitas Tempat Tidur & Fasilitas Kamar Santri</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Asrama / Boarding</li>
        <li class="active">Kamar & Gedung</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Asrama --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-building-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Gedung Asrama</span>
                <span class="info-box-number">{{ $totalGedung }} <small style="color: #cbd5e0; font-size: 11px;">Gedung</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Kompleks Putra & Putri</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-door-open fa-bed"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Kamar Santri</span>
                <span class="info-box-number">{{ $totalKamar }} <small style="color: #bfdbfe; font-size: 11px;">Kamar Aktif</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Seluruh Blok Lantai</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Okupansi Santri</span>
                <span class="info-box-number">{{ $totalTerisi }} / {{ $totalKapasitasBed }} <small style="color: #d1fae5; font-size: 11px;">Bed ({{ $okupansiPersen }}%)</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $okupansiPersen }}%"></div></div>
                <span class="progress-description">Tingkat Keterisian Asrama</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-inbox"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sisa Tempat Tidur</span>
                <span class="info-box-number">{{ $totalSisaBed }} <small style="color: #e9d8fd; font-size: 11px;">Bed Kosong</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Siap Ditempati Santri Baru</span>
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

{{-- Dwi-Tab: Kamar Asrama & Profil Gedung --}}
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'kamar' ? 'active' : '' }}">
            <a href="{{ route('asrama.kamar', ['tab' => 'kamar']) }}"><i class="fa fa-bed text-primary"></i> <strong>Kamar Asrama Santri</strong></a>
        </li>
        <li class="{{ $activeTab === 'gedung' ? 'active' : '' }}">
            <a href="{{ route('asrama.kamar', ['tab' => 'gedung']) }}"><i class="fa fa-building text-yellow"></i> <strong>Gedung & Blok Asrama</strong></a>
        </li>
        <li class="pull-right">
            @if($activeTab === 'kamar')
                <button type="button" class="btn btn-primary btn-sm margin-r-5" data-toggle="modal" data-target="#modalTambahKamar">
                    <i class="fa fa-plus-circle"></i> Tambah Kamar Baru
                </button>
            @else
                <button type="button" class="btn btn-success btn-sm margin-r-5" data-toggle="modal" data-target="#modalTambahGedung">
                    <i class="fa fa-plus-circle"></i> Tambah Gedung Baru
                </button>
            @endif
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Denah & Okupansi
            </button>
        </li>
    </ul>

    <div class="tab-content" style="padding: 15px 0 0 0;">
        @if($activeTab === 'kamar')
            {{-- TAB 1: DAFTAR KAMAR ASRAMA --}}
            <div class="tab-pane active" id="tab_kamar">
                {{-- Filter Bar --}}
                <div style="background-color: #f9fafb; padding: 12px 15px; border-bottom: 1px solid #e5e7eb; margin-top: -15px; margin-bottom: 15px;">
                    <form method="GET" action="{{ route('asrama.kamar') }}" class="form-inline">
                        <input type="hidden" name="tab" value="kamar">

                        <div class="form-group margin-r-5">
                            <label class="sr-only" for="building">Gedung</label>
                            <select name="building" class="form-control input-sm">
                                <option value="">-- Semua Gedung --</option>
                                @foreach($buildings as $b)
                                    <option value="{{ $b['id'] }}" {{ $buildingFilter === $b['id'] ? 'selected' : '' }}>{{ $b['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group margin-r-5">
                            <label class="sr-only" for="gender">Kategori</label>
                            <select name="gender" class="form-control input-sm">
                                <option value="">-- Semua Kategori --</option>
                                <option value="L" {{ $genderFilter === 'L' ? 'selected' : '' }}>Putra (Ikhwan)</option>
                                <option value="P" {{ $genderFilter === 'P' ? 'selected' : '' }}>Putri (Akhwat)</option>
                            </select>
                        </div>

                        <div class="form-group margin-r-5">
                            <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari nama kamar / nomor..." style="width: 200px;">
                        </div>

                        <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
                        @if($buildingFilter || $genderFilter || $search)
                            <a href="{{ route('asrama.kamar', ['tab' => 'kamar']) }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Grid Tampilan Kamar Asrama --}}
                <div class="row" style="padding: 0 15px;">
                    @forelse($rooms as $r)
                        @php
                            $residentsCount = count($r['resident_student_ids'] ?? []);
                            $cap = $r['capacity'] ?? 6;
                            $pct = $cap > 0 ? min(100, round(($residentsCount / $cap) * 100)) : 0;
                            $isFull = $residentsCount >= $cap;
                            
                            $bldColor = $r['gender'] === 'L' ? 'primary' : 'purple';
                        @endphp
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="box box-{{ $bldColor }}" style="border-top-width: 3px;">
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="font-size: 15px; font-weight: bold;">
                                        <i class="fa fa-home text-{{ $bldColor }}"></i> {{ $r['name'] }}
                                    </h3>
                                    <div class="box-tools pull-right">
                                        @if($isFull)
                                            <span class="label label-danger">Penuh</span>
                                        @else
                                            <span class="label label-success">Tersedia {{ $cap - $residentsCount }} Bed</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="box-body" style="font-size: 12px; min-height: 180px;">
                                    <table class="table table-condensed table-striped" style="margin-bottom: 10px;">
                                        <tr>
                                            <td style="width: 35%;"><i class="fa fa-building-o text-muted"></i> <strong>Gedung</strong></td>
                                            <td>{{ $r['building_name'] }} (Lt {{ $r['floor'] }})</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-venus-mars text-muted"></i> <strong>Kategori</strong></td>
                                            <td>
                                                <span class="label label-{{ $r['gender'] === 'L' ? 'info' : 'warning' }}">
                                                    {{ $r['category'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-bed text-muted"></i> <strong>Kapasitas</strong></td>
                                            <td><strong>{{ $residentsCount }}</strong> dari {{ $cap }} Santri Penghuni</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-check-circle-o text-green"></i> <strong>Fasilitas</strong></td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ implode(', ', array_slice($r['facilities'] ?? [], 0, 3)) }}...
                                                </small>
                                            </td>
                                        </tr>
                                    </table>

                                    <div>
                                        <small class="pull-right"><strong>{{ $pct }}%</strong> Terisi</small>
                                        <small><strong>Kapasitas Tempat Tidur:</strong></small>
                                        <div class="progress xs" style="margin-top: 4px; margin-bottom: 5px;">
                                            <div class="progress-bar progress-bar-{{ $pct >= 100 ? 'danger' : ($pct >= 70 ? 'yellow' : 'green') }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer" style="background-color: #f8fafc;">
                                    <div class="btn-group btn-group-justified">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRosterKamar{{ $r['id'] }}">
                                                <i class="fa fa-users text-primary"></i> Roster Penghuni
                                            </button>
                                        </div>
                                        <div class="btn-group" style="width: 40px;">
                                            <a href="{{ route('asrama.kamar.destroy', $r['id']) }}" class="btn btn-default btn-sm text-danger" onclick="return confirm('Hapus kamar asrama ini?')" title="Hapus Kamar">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Roster Penghuni Kamar --}}
                        <div class="modal fade" id="modalRosterKamar{{ $r['id'] }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                                        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                        <h4 class="modal-title"><i class="fa fa-users text-yellow"></i> Roster Penghuni: {{ $r['name'] }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="well well-sm" style="margin-bottom: 15px; font-size: 12px; background: #f8fafc;">
                                            <strong>Lokasi:</strong> {{ $r['building_name'] }} | Lantai {{ $r['floor'] }} | No. Kamar: {{ $r['number'] }}<br>
                                            <strong>Fasilitas Lengkap:</strong> {{ implode(', ', $r['facilities'] ?? []) }}
                                        </div>

                                        <h5><strong>Daftar Santri Penghuni ({{ $residentsCount }} dari {{ $cap }} Bed):</strong></h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" style="font-size: 12px;">
                                                <thead>
                                                    <tr style="background: #e2e8f0;">
                                                        <th style="width: 35px; text-align: center;">No</th>
                                                        <th>Nama Lengkap Santri</th>
                                                        <th>NIS</th>
                                                        <th>Kelas / Rombel</th>
                                                        <th style="text-align: center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($r['resident_student_ids'] ?? [] as $idx => $sid)
                                                        @php
                                                            $s = $studentMap[$sid] ?? null;
                                                            $cls = $s && isset($classMap[$s['class_id']]) ? $classMap[$s['class_id']] : null;
                                                        @endphp
                                                        <tr>
                                                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                                                            <td><strong>{{ $s['name'] ?? 'Santri #'.$sid }}</strong></td>
                                                            <td>{{ $s['nis'] ?? '-' }}</td>
                                                            <td>{{ $cls['name'] ?? '-' }}</td>
                                                            <td style="text-align: center;"><span class="label label-success">Menetap</span></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted" style="padding: 20px;">
                                                                Kamar ini saat ini masih kosong belum ada alokasi santri penghuni.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Kartu Kamar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-xs-12">
                            <div class="well text-center text-muted" style="background: #fff; padding: 40px;">
                                <i class="fa fa-bed fa-3x"></i><br><br>
                                Tidak ada kamar asrama yang cocok dengan kriteria filter pencarian.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            {{-- TAB 2: DAFTAR GEDUNG ASRAMA --}}
            <div class="tab-pane active" id="tab_gedung">
                <div class="table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr style="background-color: #f4f6f9; color: #334155;">
                                <th style="width: 40px; text-align: center;">#</th>
                                <th>Kode & Nama Gedung</th>
                                <th>Peruntukan</th>
                                <th style="text-align: center;">Jumlah Lantai</th>
                                <th>Musyrif / Pembina Asrama</th>
                                <th>Kontak Darurat</th>
                                <th>Keterangan Kompleks</th>
                                <th style="text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buildings as $index => $b)
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ $b['name'] }}</strong><br>
                                        <small class="text-muted"><i class="fa fa-tag"></i> Kode: <code>{{ $b['code'] }}</code></small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <span class="label label-{{ $b['gender'] === 'L' ? 'primary' : 'purple' }}">
                                            <i class="fa fa-{{ $b['gender'] === 'L' ? 'male' : 'female' }}"></i> {{ $b['gender'] === 'L' ? 'Santri Putra (Ikhwan)' : 'Santri Putri (Akhwat)' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="badge bg-navy">{{ $b['total_floors'] }} Lantai</span>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <strong>{{ $b['supervisor_name'] }}</strong>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <i class="fa fa-phone text-green"></i> {{ $b['supervisor_phone'] }}
                                    </td>
                                    <td style="vertical-align: middle; max-width: 250px;">
                                        <small class="text-muted">{{ $b['description'] }}</small>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="label label-success">Aktif</span>
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

{{-- Modal Tambah Kamar Baru --}}
<div class="modal fade" id="modalTambahKamar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('asrama.kamar.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Tambah Kamar Asrama Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="building_id">Pilih Gedung Asrama <span class="text-danger">*</span></label>
                        <select name="building_id" id="building_id" class="form-control" required>
                            @foreach($buildings as $b)
                                <option value="{{ $b['id'] }}">{{ $b['name'] }} ({{ $b['gender'] === 'L' ? 'Putra' : 'Putri' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Nomor Kamar <span class="text-danger">*</span></label>
                                <input type="text" name="number" id="number" class="form-control" placeholder="Cth: 103 / 204" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="floor">Posisi Lantai <span class="text-danger">*</span></label>
                                <input type="number" name="floor" id="floor" value="1" min="1" max="10" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Nama Kamar (Tokoh Islam / Sahabat) <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Cth: Kamar 103 Sa'ad bin Abi Waqqas" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacity">Kapasitas Bed <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" id="capacity" value="6" min="1" max="30" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="facilities">Fasilitas Kamar (Pisahkan dengan koma)</label>
                        <textarea name="facilities" id="facilities" rows="2" class="form-control" placeholder="Ranjang Tingkat 3 Set, Lemari Pribadi 6 Pintu, AC Split 1.5 PK, Kamar Mandi Dalam"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Daftarkan Kamar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Gedung Baru --}}
<div class="modal fade" id="modalTambahGedung" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('asrama.gedung.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #047857; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Gedung Asrama Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bld_name">Nama Gedung Asrama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="bld_name" class="form-control" placeholder="Cth: Gedung Asrama Abu Bakar Ash-Shiddiq" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">Kode Gedung <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Cth: BLD-AB" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">Peruntukan <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="L">Putra (Ikhwan)</option>
                                    <option value="P">Putri (Akhwat)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_floors">Total Lantai <span class="text-danger">*</span></label>
                                <input type="number" name="total_floors" id="total_floors" value="3" min="1" max="10" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supervisor_name">Nama Musyrif / Pembina <span class="text-danger">*</span></label>
                                <input type="text" name="supervisor_name" id="supervisor_name" class="form-control" placeholder="Cth: Ust. Ahmad Shobirin, Lc." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supervisor_phone">Nomor Telepon Musyrif <span class="text-danger">*</span></label>
                                <input type="text" name="supervisor_phone" id="supervisor_phone" class="form-control" placeholder="Cth: 081299887766" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Kompleks Asrama <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="2" class="form-control" placeholder="Informasi fasilitas pendukung (musholla, dapur, tempat jemur)..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Daftarkan Gedung</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

