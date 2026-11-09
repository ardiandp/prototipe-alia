@extends('layouts.app')

@section('title', 'Master Pos Biaya & Tarif')

@section('content_header')
    <h1>
        <i class="fa fa-tags text-purple"></i> Master Data Pos Biaya & Tarif Sekolah
        <small>Struktur SPP, Uang Masuk / Daftar Ulang, Biaya Asrama, Seragam, & Kegiatan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Pos Biaya & Tarif</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Statistik Pos Biaya -->
<div class="row">
    <!-- Stat 1: Total Pos Tarif -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-tags"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pos Biaya</span>
                <span class="info-box-number">{{ $totalTarif }} <small style="color:#eee; font-weight:normal;">Struktur Tarif</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Semua Jenjang Terdaftar</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Tarif SPP Rutin -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pos SPP Bulanan</span>
                <span class="info-box-number">{{ $totalSpp }} <small style="color:#eee; font-weight:normal;">Unit Pendidikan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 75%"></div></div>
                <span class="progress-description">SD, SMP, & SMA Boarding</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Non-SPP (Uang Masuk / Seragam) -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Non-SPP / Insidental</span>
                <span class="info-box-number">{{ $totalNonSpp }} <small style="color:#eee; font-weight:normal;">Pos Biaya</small></span>
                <div class="progress"><div class="progress-bar" style="width: 80%"></div></div>
                <span class="progress-description">Daftar Ulang, Seragam, Rihlah</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Penagihan Berkala Bulanan -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Siklus Bulanan</span>
                <span class="info-box-number">{{ $totalBulanan }} <small style="color:#eee; font-weight:normal;">Pos Tagihan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 85%"></div></div>
                <span class="progress-description">Auto-Generate Invoice Tiap Bulan</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        {{ session('error') }}
    </div>
@endif

<!-- Filter & Actions Box -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.pos-biaya') }}" method="GET" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-university"></i> Unit:</label>
                <select name="unit_id" class="form-control input-sm">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u['id'] }}" {{ $unitFilter === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-tag"></i> Kategori:</label>
                <select name="category" class="form-control input-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-repeat"></i> Siklus Tagihan:</label>
                <select name="period_type" class="form-control input-sm">
                    <option value="">-- Semua Siklus --</option>
                    <option value="bulanan" {{ $periodTypeFilter === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ $periodTypeFilter === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="sekali" {{ $periodTypeFilter === 'sekali' ? 'selected' : '' }}>Sekali Bayar</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Nama biaya, kode, deskripsi..." style="width: 180px;">
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Saring Data
            </button>
            <a href="{{ route('keuangan.pos-biaya') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>

            <div class="pull-right">
                <button type="button" class="btn btn-purple btn-sm" data-toggle="modal" data-target="#modal-tambah-pos">
                    <i class="fa fa-plus-circle"></i> Tambah Pos Biaya Baru
                </button>
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Tarif
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Master Pos Biaya & Tarif -->
<div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
            <i class="fa fa-list text-purple"></i> Daftar Struktur Pos Biaya & Tarif Pendidikan
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($filteredFees) }} Pos Terdaftar</span>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #1a365d; color: #fff;">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Kode Pos</th>
                    <th>Nama Pos Biaya</th>
                    <th>Kategori Biaya</th>
                    <th>Unit Pendidikan Sasaran</th>
                    <th>Siklus Tagihan</th>
                    <th class="text-right">Besaran Tarif (Rp)</th>
                    <th class="text-center">Status</th>
                    <th style="width: 100px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredFees as $idx => $f)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                        <code style="color: #6b46c1; font-weight: bold; font-size: 13px;">{{ $f['code'] }}</code>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 14px;">{{ $f['name'] }}</strong><br>
                        <small class="text-muted">{{ $f['description'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        @if($f['category'] === 'SPP')
                            <span class="label label-primary"><i class="fa fa-bookmark"></i> SPP</span>
                        @elseif($f['category'] === 'Asrama')
                            <span class="label label-success"><i class="fa fa-bed"></i> Asrama & Makan</span>
                        @elseif($f['category'] === 'Daftar Ulang')
                            <span class="label label-warning"><i class="fa fa-refresh"></i> Daftar Ulang</span>
                        @elseif($f['category'] === 'Perlengkapan')
                            <span class="label label-info"><i class="fa fa-shopping-bag"></i> Perlengkapan</span>
                        @else
                            <span class="label label-default"><i class="fa fa-tag"></i> {{ $f['category'] }}</span>
                        @endif
                    </td>
                    <td style="vertical-align: middle;">
                        <i class="fa fa-university text-muted"></i> {{ $f['unit_name'] }}
                    </td>
                    <td style="vertical-align: middle;">
                        @if($f['period_type'] === 'bulanan')
                            <span class="label label-info"><i class="fa fa-calendar"></i> Tiap Bulan</span>
                        @elseif($f['period_type'] === 'tahunan')
                            <span class="label label-warning"><i class="fa fa-calendar-check-o"></i> Tiap Tahun</span>
                        @else
                            <span class="label label-default"><i class="fa fa-check"></i> Sekali Bayar</span>
                        @endif
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <strong style="font-size: 15px; color: #2b6cb0;">
                            Rp {{ number_format($f['amount'], 0, ',', '.') }}
                        </strong>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(($f['status'] ?? 'aktif') === 'aktif')
                            <span class="label label-success"><i class="fa fa-check-circle"></i> AKTIF</span>
                        @else
                            <span class="label label-danger"><i class="fa fa-times-circle"></i> NONAKTIF</span>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type="button" class="btn btn-xs btn-info" onclick='showFeeDetail(@json($f))' title="Lihat Rincian Pos">
                            <i class="fa fa-eye"></i>
                        </button>
                        <a href="{{ route('keuangan.pos-biaya.destroy', $f['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus pos biaya {{ $f['name'] }}?')" title="Hapus Tarif">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-tags fa-2x"></i><br>
                        Belum ada struktur pos biaya yang cocok dengan kriteria filter pencarian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: Tambah Pos Biaya & Tarif Baru -->
<div class="modal fade" id="modal-tambah-pos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('keuangan.pos-biaya.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Pos Biaya & Tarif Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Pos Tarif <span class="text-red">*</span></label>
                                <input type="text" name="code" class="form-control" placeholder="Contoh: SPP-SD-2026 / UANG-MAKAN" required style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori Biaya <span class="text-red">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="SPP">SPP / Pendidikan</option>
                                    <option value="Asrama">Asrama & Makan</option>
                                    <option value="Daftar Ulang">Daftar Ulang Tahunan</option>
                                    <option value="Perlengkapan">Perlengkapan & Seragam</option>
                                    <option value="Kegiatan">Kegiatan Santri / Outing</option>
                                    <option value="Lainnya">Lainnya / Ekstrakurikuler</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Pos Biaya <span class="text-red">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: SPP Bulanan SD Islam Alia Full Day" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit Pendidikan Sasaran <span class="text-red">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="ALL">Semua Unit (Lintas Jenjang)</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Siklus Penagihan <span class="text-red">*</span></label>
                                <select name="period_type" class="form-control" required>
                                    <option value="bulanan">Bulanan (Tiap Bulan)</option>
                                    <option value="tahunan">Tahunan (Awal Tahun Ajaran)</option>
                                    <option value="sekali">Sekali Bayar (Insidental)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Besaran Tarif Nominal (Rp) <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="number" name="amount" class="form-control" placeholder="1500000" min="0" step="50000" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status Pos</label>
                                <select name="status" class="form-control">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi & Keterangan</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Uraian peruntukan biaya operasional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-save"></i> Simpan Tarif Biaya
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Detail Pos Biaya -->
<div class="modal fade" id="modal-detail-pos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6b46c1; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-tags"></i> Rincian Master Pos Biaya</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 35%; background-color: #f7fafc;">Kode Pos</th>
                        <td id="dt_code" style="font-weight: bold; color: #6b46c1;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Nama Pos Biaya</th>
                        <td id="dt_name" style="font-weight: bold; color: #1a365d;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Kategori</th>
                        <td id="dt_category">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Unit Sasaran</th>
                        <td id="dt_unit">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Siklus Penagihan</th>
                        <td id="dt_period">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Tarif Nominal</th>
                        <td id="dt_amount" style="font-weight: bold; color: #2b6cb0; font-size: 15px;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Deskripsi</th>
                        <td id="dt_desc">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Status</th>
                        <td id="dt_status">-</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function showFeeDetail(item) {
        document.getElementById('dt_code').innerText = item.code;
        document.getElementById('dt_name').innerText = item.name;
        document.getElementById('dt_category').innerText = item.category;
        document.getElementById('dt_unit').innerText = item.unit_name;
        document.getElementById('dt_period').innerText = item.period_type.toUpperCase();
        document.getElementById('dt_amount').innerText = 'Rp ' + Number(item.amount).toLocaleString('id-ID');
        document.getElementById('dt_desc').innerText = item.description || '-';
        document.getElementById('dt_status').innerHTML = item.status === 'aktif' ? '<span class="label label-success">AKTIF</span>' : '<span class="label label-danger">NONAKTIF</span>';

        $('#modal-detail-pos').modal('show');
    }
</script>
@stop

