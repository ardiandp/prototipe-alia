@extends('layouts.app')

@section('title', 'Master Barang Inventaris Sekolah')

@section('content_header')
    <h1>
        <i class="fa fa-cubes text-primary"></i> Master Barang Inventaris
        <small>Katalog Barang Sekolah, Kode Barcode SKU, Stok Minimum & Kondisi Fisik</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Inventaris & Logistik</li>
        <li class="active">Master Barang</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-barcode"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Item Terdaftar</span>
                <span class="info-box-number">{{ $totalItems }} SKU Barang</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Katalog aktif inventaris</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-archive"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Kuantitas Fisik</span>
                <span class="info-box-number">{{ number_format($totalStockUnits) }} Unit/Pcs</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Seluruh gudang logistik</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Nilai Valuasi</span>
                <span class="info-box-number" style="font-size: 15px;">Rp {{ number_format($totalValuation, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Estimasi aset persediaan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-{{ $lowStockCount > 0 ? 'yellow' : 'blue' }}">
            <span class="info-box-icon"><i class="fa fa-warning"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Menipis (Alert)</span>
                <span class="info-box-number">{{ $lowStockCount }} Item Barang</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalItems > 0 ? round(($lowStockCount / $totalItems) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Di bawah ambang minimum</span>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-th-list text-primary"></i> Katalog Master Barang Inventaris</a>
                </li>
                <li class="{{ $activeTab === 'stok_menipis' ? 'active' : '' }}">
                    <a href="#tab_stok_menipis" data-toggle="tab">
                        <i class="fa fa-exclamation-triangle text-yellow"></i> Peringatan Stok Menipis
                        @if($lowStockCount > 0)
                            <span class="badge bg-yellow" style="margin-left: 5px;">{{ $lowStockCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="{{ $activeTab === 'valuasi' ? 'active' : '' }}">
                    <a href="#tab_valuasi" data-toggle="tab"><i class="fa fa-pie-chart text-purple"></i> Rekapitulasi Valuasi per Kategori</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Katalog Master Barang --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('inventaris.barang') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kategori:</label>
                                    <select name="category" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>-- Semua Kategori --</option>
                                        <option value="atk" {{ $categoryFilter === 'atk' ? 'selected' : '' }}>Alat Tulis Kantor (ATK)</option>
                                        <option value="elektronik" {{ $categoryFilter === 'elektronik' ? 'selected' : '' }}>Elektronik & Multimedia</option>
                                        <option value="mebelair" {{ $categoryFilter === 'mebelair' ? 'selected' : '' }}>Mebelair & Furnitur</option>
                                        <option value="kebersihan" {{ $categoryFilter === 'kebersihan' ? 'selected' : '' }}>Kebersihan & Sanitasi</option>
                                        <option value="olahraga" {{ $categoryFilter === 'olahraga' ? 'selected' : '' }}>Sarana Olahraga</option>
                                        <option value="laboratorium" {{ $categoryFilter === 'laboratorium' ? 'selected' : '' }}>Laboratorium & Sains</option>
                                        <option value="uks_medis" {{ $categoryFilter === 'uks_medis' ? 'selected' : '' }}>Kesehatan & UKS</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kondisi:</label>
                                    <select name="condition" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $conditionFilter === 'all' ? 'selected' : '' }}>-- Semua Kondisi --</option>
                                        <option value="baik" {{ $conditionFilter === 'baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                                        <option value="rusak_ringan" {{ $conditionFilter === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak_berat" {{ $conditionFilter === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Status Stok:</label>
                                    <select name="stock_status" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $stockFilter === 'all' ? 'selected' : '' }}>-- Semua Stok --</option>
                                        <option value="aman" {{ $stockFilter === 'aman' ? 'selected' : '' }}>Stok Aman</option>
                                        <option value="menipis" {{ $stockFilter === 'menipis' ? 'selected' : '' }}>Menipis (&le; Min)</option>
                                        <option value="habis" {{ $stockFilter === 'habis' ? 'selected' : '' }}>Kosong / Habis</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Nama, kode, barcode..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('inventaris.barang', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah-barang">
                                        <i class="fa fa-plus-circle"></i> Daftarkan Barang Baru
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Katalog
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Master Barang --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode SKU & Barcode</th>
                                    <th>Nama Barang & Kategori</th>
                                    <th>Lokasi Penyimpanan</th>
                                    <th class="text-center">Stok Fisik</th>
                                    <th class="text-center">Min. Stok</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Total Nilai</th>
                                    <th class="text-center">Kondisi</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredItems as $it)
                                    @php
                                        $isLowStock = $it['stock'] <= $it['min_stock'];
                                        $totalRowVal = $it['stock'] * $it['unit_price'];
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $it['item_code'] }}</span><br>
                                            <small class="text-muted"><i class="fa fa-barcode"></i> {{ $it['barcode'] }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $it['name'] }}</strong><br>
                                            <span class="label label-info" style="font-size: 10px;">{{ $it['category_label'] }}</span>
                                        </td>
                                        <td>
                                            <small><i class="fa fa-map-marker text-red"></i> {{ $it['location'] }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($isLowStock)
                                                <span class="label label-danger text-bold" style="font-size: 12px;">{{ $it['stock'] }} {{ $it['unit'] }}</span>
                                            @else
                                                <span class="text-bold text-green" style="font-size: 13px;">{{ $it['stock'] }} {{ $it['unit'] }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $it['min_stock'] }} {{ $it['unit'] }}
                                        </td>
                                        <td class="text-right text-muted">
                                            Rp {{ number_format($it['unit_price'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-bold text-navy">
                                            Rp {{ number_format($totalRowVal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($it['condition'] === 'baik')
                                                <span class="label label-success"><i class="fa fa-check"></i> Baik</span>
                                            @elseif($it['condition'] === 'rusak_ringan')
                                                <span class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Rusak Ringan</span>
                                            @else
                                                <span class="label label-danger"><i class="fa fa-times"></i> Rusak Berat</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-inv-{{ $it['id'] }}" title="Lihat Kartu Inventaris & Barcode">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-edit-inv-{{ $it['id'] }}" title="Edit Data Barang">
                                                    <i class="fa fa-pencil text-warning"></i>
                                                </button>
                                                <a href="{{ route('inventaris.barang.destroy', $it['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini dari katalog master inventaris?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Kartu Fisik Inventaris & Barcode Tag --}}
                                            <div class="modal fade" id="modal-detail-inv-{{ $it['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-barcode"></i> Label Tag & Kartu Inventaris Barang</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center well well-sm" style="background-color: #fcfcfc; border: 2px dashed #1a365d; border-radius: 6px; padding: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">ALIA ISLAMIC SCHOOL</h4>
                                                                <small class="text-muted">LABEL INVENTARIS LOGISTIK RESMI</small><br>
                                                                <div style="font-family: monospace; font-size: 28px; letter-spacing: 5px; margin: 10px 0; background: #e2e8f0; padding: 5px; display: inline-block;">
                                                                    ||| | |||| | ||| || |||
                                                                </div>
                                                                <p style="font-family: monospace; font-size: 14px; margin-bottom: 0;"><strong>{{ $it['barcode'] }}</strong></p>
                                                                <span class="label label-primary" style="font-size: 11px;">SKU: {{ $it['item_code'] }}</span>
                                                            </div>

                                                            <table class="table table-bordered table-striped" style="margin-top: 15px; font-size: 13px;">
                                                                <tr>
                                                                    <th width="35%">Nama Barang</th>
                                                                    <td><strong class="text-primary">{{ $it['name'] }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kategori</th>
                                                                    <td><span class="label label-info">{{ $it['category_label'] }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Lokasi Gudang / Rak</th>
                                                                    <td><i class="fa fa-map-marker text-red"></i> {{ $it['location'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Stok Tersedia</th>
                                                                    <td>
                                                                        <strong>{{ $it['stock'] }} {{ $it['unit'] }}</strong>
                                                                        (Ambang batas minimum: {{ $it['min_stock'] }} {{ $it['unit'] }})
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Harga Satuan / Valuasi</th>
                                                                    <td class="text-navy text-bold">
                                                                        Rp {{ number_format($it['unit_price'], 0, ',', '.') }} per {{ $it['unit'] }}<br>
                                                                        <small class="text-muted">Total Nilai Persediaan: Rp {{ number_format($totalRowVal, 0, ',', '.') }}</small>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kondisi Fisik</th>
                                                                    <td>
                                                                        @if($it['condition'] === 'baik')
                                                                            <span class="label label-success"><i class="fa fa-check"></i> Baik & Layak Pakai</span>
                                                                        @elseif($it['condition'] === 'rusak_ringan')
                                                                            <span class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Rusak Ringan</span>
                                                                        @else
                                                                            <span class="label label-danger"><i class="fa fa-times"></i> Rusak Berat</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Spesifikasi Teknis</th>
                                                                    <td>{{ $it['specification'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Catatan Khusus</th>
                                                                    <td><small>{{ $it['notes'] }}</small></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Label Barcode</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Edit Barang --}}
                                            <div class="modal fade" id="modal-edit-inv-{{ $it['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <form method="POST" action="{{ route('inventaris.barang.update', $it['id']) }}">
                                                            @csrf
                                                            <div class="modal-header bg-warning">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title" style="color: #333;"><i class="fa fa-pencil"></i> Edit Master Barang Inventaris</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Barang: <strong>{{ $it['name'] }}</strong> ({{ $it['item_code'] }})</p>
                                                                <div class="form-group">
                                                                    <label>Lokasi Penyimpanan / Rak: <span class="text-danger">*</span></label>
                                                                    <input type="text" name="location" class="form-control" value="{{ $it['location'] }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Batas Minimum Stok (Alert): <span class="text-danger">*</span></label>
                                                                    <input type="number" name="min_stock" class="form-control" min="0" value="{{ $it['min_stock'] }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Harga Satuan Perolehan (Rp): <span class="text-danger">*</span></label>
                                                                    <input type="number" name="unit_price" class="form-control" min="0" value="{{ $it['unit_price'] }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Kondisi Fisik Barang: <span class="text-danger">*</span></label>
                                                                    <select name="condition" class="form-control" required>
                                                                        <option value="baik" {{ $it['condition'] === 'baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                                                                        <option value="rusak_ringan" {{ $it['condition'] === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                                        <option value="rusak_berat" {{ $it['condition'] === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Catatan Inventaris:</label>
                                                                    <textarea name="notes" class="form-control" rows="2">{{ $it['notes'] }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-info-circle fa-2x"></i><br>
                                            Tidak ada barang inventaris yang sesuai dengan filter pencarian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Peringatan Stok Menipis --}}
                <div class="tab-pane {{ $activeTab === 'stok_menipis' ? 'active' : '' }}" id="tab_stok_menipis">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h4><i class="icon fa fa-warning"></i> Perhatian: Stok Logistik Menipis!</h4>
                                Daftar barang inventaris berikut memiliki kuantitas stok di bawah atau sama dengan ambang batas minimum. Harap segera lakukan permintaan pengadaan (restock).
                            </div>

                            @php
                                $lowItems = array_filter($items, fn($it) => $it['stock'] <= $it['min_stock']);
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="bg-gray-light">
                                            <th>Kode SKU</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Lokasi Gudang</th>
                                            <th class="text-center">Sisa Stok</th>
                                            <th class="text-center">Min. Stok</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($lowItems as $li)
                                            <tr>
                                                <td><code>{{ $li['item_code'] }}</code></td>
                                                <td><strong class="text-danger">{{ $li['name'] }}</strong></td>
                                                <td><span class="label label-info">{{ $li['category_label'] }}</span></td>
                                                <td>{{ $li['location'] }}</td>
                                                <td class="text-center text-bold text-danger">{{ $li['stock'] }} {{ $li['unit'] }}</td>
                                                <td class="text-center text-muted">{{ $li['min_stock'] }} {{ $li['unit'] }}</td>
                                                <td class="text-center">
                                                    @if($li['stock'] == 0)
                                                        <span class="label label-danger">HABIS TOTAL</span>
                                                    @else
                                                        <span class="label label-warning">PERLU RESTOCK</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-green" style="padding: 20px;">
                                                    <i class="fa fa-check-circle fa-2x"></i><br>
                                                    Semua stok barang inventaris dalam kondisi aman di atas batas minimum!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Rekapitulasi Valuasi per Kategori --}}
                <div class="tab-pane {{ $activeTab === 'valuasi' ? 'active' : '' }}" id="tab_valuasi">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-pie-chart text-purple"></i> Rekapitulasi Nilai Aset per Kategori</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Kategori Barang</th>
                                                <th class="text-center">SKU</th>
                                                <th class="text-center">Total Fisik</th>
                                                <th class="text-right">Valuasi (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categorySummary as $cs)
                                                <tr>
                                                    <td><strong>{{ $cs['label'] }}</strong></td>
                                                    <td class="text-center">{{ $cs['count'] }}</td>
                                                    <td class="text-center">{{ $cs['stock'] }}</td>
                                                    <td class="text-right text-bold">Rp {{ number_format($cs['value'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-gray-light text-bold">
                                                <td>TOTAL KESELURUHAN</td>
                                                <td class="text-center">{{ $totalItems }}</td>
                                                <td class="text-center">{{ $totalStockUnits }}</td>
                                                <td class="text-right text-navy">Rp {{ number_format($totalValuation, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-info-circle text-primary"></i> Standar Operasional Prosedur (SOP) Gudang</h4>
                            <div class="callout callout-info">
                                <h4>Metode Penilaian & Stock Opname Berkala</h4>
                                <p>Penilaian persediaan barang inventaris sekolah menggunakan metode <strong>FIFO (First In, First Out)</strong>. Stock opname fisik dilakukan setiap akhir bulan oleh Bagian Logistik bersama tim Pengawas Internal Yayasan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Barang Baru --}}
<div class="modal fade" id="modal-tambah-barang" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventaris.barang.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Pendaftaran Master Barang Inventaris Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang: <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Kertas HVS A4 80 GSM PaperOne" required>
                            </div>
                            <div class="form-group">
                                <label>Kategori Inventaris: <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="atk">Alat Tulis Kantor (ATK)</option>
                                    <option value="elektronik">Elektronik & Multimedia</option>
                                    <option value="mebelair">Mebelair & Furnitur</option>
                                    <option value="kebersihan">Kebersihan & Sanitasi</option>
                                    <option value="olahraga">Sarana & Alat Olahraga</option>
                                    <option value="laboratorium">Laboratorium & Sains</option>
                                    <option value="uks_medis">Kesehatan & UKS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Satuan Barang: <span class="text-danger">*</span></label>
                                <input type="text" name="unit" class="form-control" placeholder="Contoh: Rim, Box, Pcs, Unit, Jerigen" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Penyimpanan / Rak Gudang: <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="Contoh: Gudang Utama Rak ATK A-1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Kuantitas Stok Awal: <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" class="form-control" min="0" value="10" required>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Batas Min. Stok (Alert): <span class="text-danger">*</span></label>
                                        <input type="number" name="min_stock" class="form-control" min="0" value="5" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Harga Satuan Beli / Buku (Rp): <span class="text-danger">*</span></label>
                                <input type="number" name="unit_price" class="form-control" min="0" placeholder="Contoh: 54000" required>
                            </div>
                            <div class="form-group">
                                <label>Kondisi Fisik Barang: <span class="text-danger">*</span></label>
                                <select name="condition" class="form-control" required>
                                    <option value="baik" selected>Baik (Siap Pakai)</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Catatan Inventaris:</label>
                                <input type="text" name="notes" class="form-control" placeholder="Catatan supplier, masa kadaluarsa, dll...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Spesifikasi Teknis / Deskripsi:</label>
                                <textarea name="specification" class="form-control" rows="2" placeholder="Uraikan detail dimensi, merek, tipe, kapasitas..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Daftarkan ke Master Inventaris</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
