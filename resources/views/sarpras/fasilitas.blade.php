@extends('layouts.app')

@section('title', 'Registrasi Sarana & Prasarana Kampus')

@section('content_header')
    <h1>
        <i class="fa fa-university text-primary"></i> Sarana & Prasarana Kampus
        <small>Registrasi Master Tanah, Bangunan Gedung, Ruangan Laboratorium & Utilitas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Sarpras & Aset</li>
        <li class="active">Fasilitas</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-institution"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Aset Terdaftar</span>
                <span class="info-box-number">{{ $totalAssets }} Unit/Bidang</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Tanah, Gedung, Lapangan & Utilitas</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Nilai Aset</span>
                <span class="info-box-number">Rp {{ number_format($totalValue, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Inventaris Sarpras Alia Islamic</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kondisi Baik/Optimal</span>
                <span class="info-box-number">{{ $totalGoodCondition }} Fasilitas</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalAssets > 0 ? round(($totalGoodCondition / $totalAssets) * 100) : 0 }}%"></div></div>
                <span class="progress-description">{{ $totalAssets > 0 ? round(($totalGoodCondition / $totalAssets) * 100) : 0 }}% dalam kondisi prima</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-wrench"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Perlu Pemeliharaan</span>
                <span class="info-box-number">{{ $totalNeedRepair }} Fasilitas</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalAssets > 0 ? round(($totalNeedRepair / $totalAssets) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Perbaikan & servis rutin</span>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-th-list text-primary"></i> Buku Register Aset Sarpras</a>
                </li>
                <li class="{{ $activeTab === 'ruangan' ? 'active' : '' }}">
                    <a href="#tab_ruangan" data-toggle="tab"><i class="fa fa-cubes text-purple"></i> Ruangan & Fasilitas Khusus ({{ count($rooms) }})</a>
                </li>
                <li class="{{ $activeTab === 'statistik' ? 'active' : '' }}">
                    <a href="#tab_statistik" data-toggle="tab"><i class="fa fa-pie-chart text-green"></i> Rekapitulasi & Valuasi Aset</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Buku Register Aset Sarpras --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('sarpras.fasilitas') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">
                                
                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kategori:</label>
                                    <select name="category" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>-- Semua Kategori --</option>
                                        <option value="tanah" {{ $categoryFilter === 'tanah' ? 'selected' : '' }}>Tanah & Lahan</option>
                                        <option value="gedung" {{ $categoryFilter === 'gedung' ? 'selected' : '' }}>Bangunan Gedung</option>
                                        <option value="lapangan" {{ $categoryFilter === 'lapangan' ? 'selected' : '' }}>Lapangan Olahraga</option>
                                        <option value="ruangan" {{ $categoryFilter === 'ruangan' ? 'selected' : '' }}>Laboratorium & Aula</option>
                                        <option value="utilitas" {{ $categoryFilter === 'utilitas' ? 'selected' : '' }}>Utilitas & Daya</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kondisi:</label>
                                    <select name="condition" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $conditionFilter === 'all' ? 'selected' : '' }}>-- Semua Kondisi --</option>
                                        <option value="sangat_baik" {{ $conditionFilter === 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $conditionFilter === 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak_ringan" {{ $conditionFilter === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak_berat" {{ $conditionFilter === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Pencarian:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Nama, kode, atau PIC..." value="{{ $search }}" style="width: 180px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('sarpras.fasilitas', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah-aset">
                                        <i class="fa fa-plus-circle"></i> Daftarkan Aset Baru
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Register Aset --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode & Nama Aset</th>
                                    <th>Kategori & Lokasi</th>
                                    <th>Luas / Kapasitas</th>
                                    <th>Status Hak & Thn</th>
                                    <th class="text-right">Nilai Perolehan</th>
                                    <th class="text-center">Kondisi Fisik</th>
                                    <th>Penanggung Jawab</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredAssets as $idx => $asset)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $asset['name'] }}</strong><br>
                                            <span class="label label-default" style="font-family: monospace;">{{ $asset['asset_code'] }}</span>
                                        </td>
                                        <td>
                                            <span class="label {{ $asset['category'] === 'tanah' ? 'label-success' : ($asset['category'] === 'gedung' ? 'label-primary' : ($asset['category'] === 'utilitas' ? 'label-danger' : 'label-info')) }}">
                                                {{ $asset['category_label'] }}
                                            </span><br>
                                            <small class="text-muted"><i class="fa fa-map-marker text-red"></i> {{ $asset['location'] }}</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-arrows-alt text-muted"></i> {{ $asset['area_size'] }}<br>
                                            <small class="text-muted"><i class="fa fa-users"></i> Kapasitas: {{ number_format($asset['capacity']) }} orang</small>
                                        </td>
                                        <td>
                                            <span class="text-bold">{{ $asset['ownership_status'] }}</span><br>
                                            <small class="text-muted">Tahun: {{ $asset['procurement_year'] }}</small>
                                        </td>
                                        <td class="text-right text-bold text-navy" style="font-size: 13px;">
                                            Rp {{ number_format($asset['asset_value'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $condBadges = [
                                                    'sangat_baik' => ['badge' => 'label-success', 'icon' => 'fa-check-circle', 'text' => 'Sangat Baik'],
                                                    'baik' => ['badge' => 'label-info', 'icon' => 'fa-thumbs-up', 'text' => 'Baik'],
                                                    'rusak_ringan' => ['badge' => 'label-warning', 'icon' => 'fa-exclamation-triangle', 'text' => 'Rusak Ringan'],
                                                    'rusak_berat' => ['badge' => 'label-danger', 'icon' => 'fa-times-circle', 'text' => 'Rusak Berat'],
                                                ];
                                                $c = $condBadges[$asset['condition']] ?? ['badge' => 'label-default', 'icon' => 'fa-question', 'text' => $asset['condition']];
                                            @endphp
                                            <span class="label {{ $c['badge'] }}" style="font-size: 11px;">
                                                <i class="fa {{ $c['icon'] }}"></i> {{ $c['text'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> {{ $asset['person_in_charge'] }}<br>
                                            <small class="text-muted">{{ $asset['notes'] ?? '-' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-{{ $asset['id'] }}" title="Lihat Kartu Aset">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-kondisi-{{ $asset['id'] }}" title="Pembaruan Kondisi Fisik">
                                                    <i class="fa fa-wrench text-warning"></i>
                                                </button>
                                                <a href="{{ route('sarpras.fasilitas.destroy', $asset['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini dari register sesi demo?')" title="Hapus Aset">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Kartu Detail Aset --}}
                                            <div class="modal fade" id="modal-detail-{{ $asset['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Kartu Induk Fasilitas & Sarpras Sekolah</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="well well-sm text-center" style="background-color: #f7fafc; border-left: 4px solid #1a365d;">
                                                                <h3 style="margin-top: 5px; color: #1a365d; font-weight: bold;">{{ $asset['name'] }}</h3>
                                                                <p class="text-muted" style="margin-bottom: 0;">Nomor Register Induk: <strong>{{ $asset['asset_code'] }}</strong></p>
                                                            </div>

                                                            <table class="table table-striped table-bordered" style="margin-top: 15px;">
                                                                <tr>
                                                                    <th width="35%">Kategori Aset</th>
                                                                    <td>{{ $asset['category_label'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Lokasi Kampus</th>
                                                                    <td><i class="fa fa-map-marker text-red"></i> {{ $asset['location'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Dimensi / Luas Fisik</th>
                                                                    <td>{{ $asset['area_size'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Daya Tampung / Kapasitas</th>
                                                                    <td><strong>{{ number_format($asset['capacity']) }}</strong> Orang / Pengguna</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status Kepemilikan</th>
                                                                    <td><span class="label label-primary">{{ $asset['ownership_status'] }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tahun Pengadaan / Pembangunan</th>
                                                                    <td>Tahun {{ $asset['procurement_year'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nilai Perolehan / Buku</th>
                                                                    <td class="text-bold text-green" style="font-size: 14px;">Rp {{ number_format($asset['asset_value'], 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kondisi Fisik Saat Ini</th>
                                                                    <td>
                                                                        <span class="label {{ $c['badge'] }}"><i class="fa {{ $c['icon'] }}"></i> {{ $c['text'] }}</span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Penanggung Jawab (PIC)</th>
                                                                    <td><i class="fa fa-user"></i> {{ $asset['person_in_charge'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Catatan Spesifikasi</th>
                                                                    <td>{{ $asset['notes'] ?? '-' }}</td>
                                                                </tr>
                                                            </table>

                                                            <div class="alert alert-info" style="font-size: 12px; margin-bottom: 0;">
                                                                <i class="fa fa-info-circle"></i> Aset ini terdaftar resmi dalam Sistem Informasi Inventaris dan Sarana Prasarana Alia Islamic School.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Kartu Aset</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Update Kondisi --}}
                                            <div class="modal fade" id="modal-kondisi-{{ $asset['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-sm" role="document">
                                                    <div class="modal-content text-left">
                                                        <form method="POST" action="{{ route('sarpras.fasilitas.kondisi', $asset['id']) }}">
                                                            @csrf
                                                            <div class="modal-header bg-yellow">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title"><i class="fa fa-wrench"></i> Update Kondisi</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Nama Aset:</label>
                                                                    <p class="form-control-static text-bold">{{ $asset['name'] }}</p>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Status Kondisi Fisik: <span class="text-danger">*</span></label>
                                                                    <select name="condition" class="form-control" required>
                                                                        <option value="sangat_baik" {{ $asset['condition'] === 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                                                        <option value="baik" {{ $asset['condition'] === 'baik' ? 'selected' : '' }}>Baik</option>
                                                                        <option value="rusak_ringan" {{ $asset['condition'] === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan (Perlu Servis)</option>
                                                                        <option value="rusak_berat" {{ $asset['condition'] === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat (Off / Renovasi)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Catatan Pemeriksaan / Kerusakan:</label>
                                                                    <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Plafon bocor sudut utara atau AC perlu isi freon...">{{ $asset['notes'] }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Simpan Status</button>
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
                                            <i class="fa fa-info-circle fa-2x"></i><br>
                                            Tidak ada aset sarana prasarana yang cocok dengan filter pencarian Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Ruangan & Fasilitas Khusus --}}
                <div class="tab-pane {{ $activeTab === 'ruangan' ? 'active' : '' }}" id="tab_ruangan">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Menampilkan master ruangan kelas belajar, laboratorium komputer, aula pertemuan, laboratorium sains, dan fasilitas asrama santri.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Ruang</th>
                                    <th>Nama Ruangan</th>
                                    <th>Gedung & Lokasi</th>
                                    <th class="text-center">Kapasitas</th>
                                    <th>Status Operasional</th>
                                    <th>Keterangan Fasilitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $idx => $room)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td><span class="label label-default" style="font-family: monospace;">{{ $room['code'] ?? 'R-'.$room['id'] }}</span></td>
                                        <td><strong class="text-purple">{{ $room['name'] }}</strong></td>
                                        <td><i class="fa fa-building-o text-muted"></i> {{ $room['building'] ?? 'Kampus Utama' }} (Lantai {{ $room['floor'] ?? '1' }})</td>
                                        <td class="text-center text-bold">{{ $room['capacity'] ?? 30 }} Santri</td>
                                        <td class="text-center">
                                            <span class="label label-success"><i class="fa fa-check"></i> Siap Pakai</span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><i class="fa fa-desktop"></i> Proyektor, AC Sentral, Sound System & Whiteboard</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 3: Rekapitulasi & Valuasi Aset --}}
                <div class="tab-pane {{ $activeTab === 'statistik' ? 'active' : '' }}" id="tab_statistik">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-pie-chart text-green"></i> Distribusi Nilai Buku per Kategori</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>Kategori Sarpras</th>
                                        <th class="text-center">Jumlah Unit/Bidang</th>
                                        <th class="text-right">Total Valuasi (Rp)</th>
                                        <th class="text-center">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categorySummary as $catKey => $catData)
                                        @php
                                            $pct = $totalValue > 0 ? round(($catData['value'] / $totalValue) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $catData['label'] }}</strong></td>
                                            <td class="text-center">{{ $catData['count'] }}</td>
                                            <td class="text-right text-bold">Rp {{ number_format($catData['value'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <div class="progress progress-xs" style="margin-bottom: 2px;">
                                                    <div class="progress-bar progress-bar-primary" style="width: {{ $pct }}%"></div>
                                                </div>
                                                <small>{{ $pct }}%</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-light text-bold">
                                        <td>TOTAL SELURUH SARPRAS</td>
                                        <td class="text-center">{{ $totalAssets }}</td>
                                        <td class="text-right text-navy">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                                        <td class="text-center">100%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fa fa-balance-scale text-primary"></i> Status Kepemilikan & Legalitas Tanah/Gedung</h4>
                            <div class="callout callout-info" style="border-left-color: #1a365d;">
                                <h4>Sertifikat Hak Milik & Wakaf Yayasan</h4>
                                <p>Seluruh tanah dan bangunan gedung kampus Alia Islamic School tercatat di atas tanah berstatus Sertifikat Wakaf Produktif Yayasan Alia Insan Mulia seluas 12.500 m², bebas sengketa dan memiliki Izin Mendirikan Bangunan (IMB) Sekolah Resmi dari Dinas Penanaman Modal & PTSP.</p>
                            </div>
                            <div class="box box-solid box-default">
                                <div class="box-body">
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Sertifikat Tanah Wakaf (SHM Wakaf No. 102/Alia)</b> <span class="pull-right badge bg-green">100% Terverifikasi</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>IMB Bangunan Gedung Umar & Khadijah</b> <span class="pull-right badge bg-blue">Sesuai Perda</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>SLF (Sertifikat Laik Fungsi) Bangunan</b> <span class="pull-right badge bg-purple">Aktif s/d 2028</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Sistem Pemadam Kebakaran & Hydrant</b> <span class="pull-right badge bg-yellow">Uji Laik Berkala</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Pendaftaran Aset Baru --}}
<div class="modal fade" id="modal-tambah-aset" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('sarpras.fasilitas.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Pendaftaran Aset Fasilitas & Sarpras Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Aset Fasilitas / Sarana: <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Gedung Laboratorium Biologi & Bahasa" required>
                            </div>
                            <div class="form-group">
                                <label>Kategori Sarpras: <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="tanah">Tanah & Lahan Kampus</option>
                                    <option value="gedung">Bangunan Gedung Bertingkat</option>
                                    <option value="lapangan">Sarana Lapangan Olahraga</option>
                                    <option value="ruangan">Ruang Laboratorium & Fasilitas Khusus</option>
                                    <option value="utilitas">Utilitas & Sumber Daya Kampus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Lokasi / Area Kampus: <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="Contoh: Kampus Utama Blok C Lantai 2" required>
                            </div>
                            <div class="form-group">
                                <label>Dimensi / Luas Fisik: <span class="text-danger">*</span></label>
                                <input type="text" name="area_size" class="form-control" placeholder="Contoh: 120 m² atau 2.500 m²" required>
                            </div>
                            <div class="form-group">
                                <label>Daya Tampung / Kapasitas (Orang): <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" class="form-control" min="1" value="40" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Legalitas & Kepemilikan: <span class="text-danger">*</span></label>
                                <input type="text" name="ownership_status" class="form-control" value="Milik Yayasan (Wakaf)" required>
                            </div>
                            <div class="form-group">
                                <label>Tahun Perolehan / Bangun: <span class="text-danger">*</span></label>
                                <input type="number" name="procurement_year" class="form-control" min="1990" max="2030" value="2024" required>
                            </div>
                            <div class="form-group">
                                <label>Nilai Perolehan / Buku (Rp): <span class="text-danger">*</span></label>
                                <input type="number" name="asset_value" class="form-control" min="0" placeholder="Contoh: 350000000" required>
                            </div>
                            <div class="form-group">
                                <label>Kondisi Fisik Saat Pendaftaran: <span class="text-danger">*</span></label>
                                <select name="condition" class="form-control" required>
                                    <option value="sangat_baik">Sangat Baik (Prima / Baru)</option>
                                    <option value="baik" selected>Baik (Siap Pakai)</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Penanggung Jawab (PIC): <span class="text-danger">*</span></label>
                                <input type="text" name="person_in_charge" class="form-control" placeholder="Contoh: Bagian Sarpras & Kepala Lab" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Pengguna Utama: <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="YAYASAN">Yayasan / Seluruh Unit (Umum)</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Catatan Tambahan & Spesifikasi Teknis:</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan fasilitas pendukung, vendor pembangunan, atau masa garansi..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Daftarkan Aset ke Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

