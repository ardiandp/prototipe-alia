@extends('layouts.app')

@section('title', 'Penerimaan Barang Masuk Logistik')

@section('content_header')
    <h1>
        <i class="fa fa-truck text-primary"></i> Penerimaan Barang Masuk
        <small>Pencatatan Surat Jalan / BASTB Logistik, Penambahan Stok Fisik & Audit Asal Barang</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Inventaris & Logistik</li>
        <li class="active">Penerimaan Masuk</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-truck"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Transaksi Masuk</span>
                <span class="info-box-number">{{ $totalTransactions }} Pengiriman</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Surat jalan logistik terdaftar</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Volume Barang Masuk</span>
                <span class="info-box-number">{{ number_format($totalItemsReceived) }} Unit/Pcs</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Telah bertambah ke stok</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Nilai Perolehan</span>
                <span class="info-box-number" style="font-size: 15px;">Rp {{ number_format($totalReceiptValue, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Nilai buku penerimaan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-handshake-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Vendor / Rekanan</span>
                <span class="info-box-number">{{ $vendorCount }} Rekanan Aktif</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Supplier & donatur terverifikasi</span>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Buku Jurnal Penerimaan Barang (Inbound)</a>
                </li>
                <li class="{{ $activeTab === 'rekap_sumber' ? 'active' : '' }}">
                    <a href="#tab_rekap_sumber" data-toggle="tab"><i class="fa fa-pie-chart text-green"></i> Rekapitulasi Berdasarkan Sumber Pengadaan</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Buku Jurnal Penerimaan Barang --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('inventaris.penerimaan') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Sumber Asal:</label>
                                    <select name="source_type" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $sourceFilter === 'all' ? 'selected' : '' }}>-- Semua Sumber --</option>
                                        <option value="pembelian_vendor" {{ $sourceFilter === 'pembelian_vendor' ? 'selected' : '' }}>Pembelian Vendor</option>
                                        <option value="dana_bos" {{ $sourceFilter === 'dana_bos' ? 'selected' : '' }}>Dana BOS</option>
                                        <option value="hibah_donatur" {{ $sourceFilter === 'hibah_donatur' ? 'selected' : '' }}>Hibah / Wakaf</option>
                                        <option value="yayasan" {{ $sourceFilter === 'yayasan' ? 'selected' : '' }}>Yayasan Alia</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Tanggal:</label>
                                    <input type="date" name="date" class="form-control input-sm" value="{{ $dateFilter }}" onchange="this.form.submit()">
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="No. DO, barang, rekanan..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('inventaris.penerimaan', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah-penerimaan">
                                        <i class="fa fa-plus-circle"></i> Catat Penerimaan Barang
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Jurnal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Penerimaan Barang --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>No. Surat Jalan / DO</th>
                                    <th>Tanggal Terima</th>
                                    <th>Nama Barang & Kode SKU</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-right">Total Nilai</th>
                                    <th>Sumber / Vendor</th>
                                    <th>Petugas Penerima</th>
                                    <th width="110" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredReceipts as $rcv)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $rcv['receipt_code'] }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-navy">{{ $rcv['delivery_order_no'] }}</strong>
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar text-blue"></i> {{ date('d M Y', strtotime($rcv['receipt_date'])) }}
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $rcv['item_name'] }}</strong><br>
                                            <small class="text-muted"><code>{{ $rcv['item_code'] }}</code></small>
                                        </td>
                                        <td class="text-center text-bold text-green" style="font-size: 13px;">
                                            +{{ $rcv['quantity'] }} {{ $rcv['unit'] }}
                                        </td>
                                        <td class="text-right text-bold">
                                            Rp {{ number_format($rcv['total_amount'], 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="label label-info" style="font-size: 10px;">{{ $rcv['source_label'] }}</span><br>
                                            <small class="text-bold">{{ $rcv['source_name'] }}</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> {{ $rcv['officer_name'] }}<br>
                                            <small class="text-muted"><i class="fa fa-map-marker text-red"></i> {{ $rcv['storage_location'] }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-rcv-{{ $rcv['id'] }}" title="Lihat BASTB & Surat Terima">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                <a href="{{ route('inventaris.penerimaan.destroy', $rcv['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus data transaksi penerimaan ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Berita Acara Serah Terima Barang (BASTB) Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-rcv-{{ $rcv['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Berita Acara Serah Terima Barang (BASTB)</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">BAGIAN GUDANG, INVENTARIS & LOGISTIK KAMPUS TERPADU</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">BUKTI PENERIMAAN BARANG LOGISTIK MASUK</h5>
                                                                <small>Nomor: <strong>{{ $rcv['receipt_code'] }}</strong></small>
                                                            </div>

                                                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                                <tr>
                                                                    <th width="35%">Nomor Surat Jalan (DO)</th>
                                                                    <td><strong>{{ $rcv['delivery_order_no'] }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tanggal Penerimaan</th>
                                                                    <td>{{ date('d F Y', strtotime($rcv['receipt_date'])) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nama Barang Diterima</th>
                                                                    <td><strong class="text-primary">{{ $rcv['item_name'] }}</strong> ({{ $rcv['item_code'] }})</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jumlah / Kuantitas Fisik</th>
                                                                    <td class="text-bold text-green" style="font-size: 14px;">
                                                                        {{ $rcv['quantity'] }} {{ $rcv['unit'] }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Harga Satuan / Total Nilai</th>
                                                                    <td>
                                                                        Rp {{ number_format($rcv['unit_price'], 0, ',', '.') }} per {{ $rcv['unit'] }}<br>
                                                                        <strong>Total: Rp {{ number_format($rcv['total_amount'], 0, ',', '.') }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Asal Sumber / Rekanan</th>
                                                                    <td>
                                                                        <strong>{{ $rcv['source_name'] }}</strong><br>
                                                                        <span class="label label-info">{{ $rcv['source_label'] }}</span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Lokasi Penempatan Rak Gudang</th>
                                                                    <td><i class="fa fa-map-marker text-red"></i> {{ $rcv['storage_location'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kondisi Fisik Saat Tiba</th>
                                                                    <td>
                                                                        @if($rcv['condition_received'] === 'baik')
                                                                            <span class="label label-success"><i class="fa fa-check"></i> Baik & Segel Utuh</span>
                                                                        @elseif($rcv['condition_received'] === 'cacat_sebagian')
                                                                            <span class="label label-warning">Cacat Sebagian</span>
                                                                        @else
                                                                            <span class="label label-danger">Rusak / Retur</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Catatan Pemeriksaan</th>
                                                                    <td><small class="text-muted">{{ $rcv['notes'] }}</small></td>
                                                                </tr>
                                                            </table>

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Yang Menyerahkan (Vendor/Kurir),</small><br><br><br>
                                                                    <strong>({{ $rcv['source_name'] }})</strong>
                                                                </div>
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Yang Menerima (Petugas Logistik),</small><br><br><br>
                                                                    <strong>({{ $rcv['officer_name'] }})</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak BASTB Resmi</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-truck fa-2x"></i><br>
                                            Tidak ada catatan penerimaan barang masuk yang sesuai dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Rekapitulasi Berdasarkan Sumber Pengadaan --}}
                <div class="tab-pane {{ $activeTab === 'rekap_sumber' ? 'active' : '' }}" id="tab_rekap_sumber">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-pie-chart text-green"></i> Distribusi Logistik Masuk Berdasarkan Asal Sumber</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Sumber Pasokan</th>
                                                <th class="text-center">Transaksi</th>
                                                <th class="text-center">Total Volume</th>
                                                <th class="text-right">Total Nilai (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sourceSummary as $ss)
                                                <tr>
                                                    <td><strong>{{ $ss['label'] }}</strong></td>
                                                    <td class="text-center">{{ $ss['count'] }}</td>
                                                    <td class="text-center">{{ $ss['quantity'] }} unit</td>
                                                    <td class="text-right text-bold">Rp {{ number_format($ss['amount'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-gray-light text-bold">
                                                <td>TOTAL LOGISTIK MASUK</td>
                                                <td class="text-center">{{ $totalTransactions }}</td>
                                                <td class="text-center">{{ $totalItemsReceived }} unit</td>
                                                <td class="text-right text-navy">Rp {{ number_format($totalReceiptValue, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-shield text-primary"></i> Verifikasi Fisik & Quality Control (QC)</h4>
                            <div class="callout callout-info">
                                <h4>Protokol Penerimaan Barang Masuk</h4>
                                <p>Setiap logistik yang tiba di gudang wajib diperiksa kecocokan nomor Surat Jalan / Delivery Order (DO) dengan Purchase Order (PO). Petugas gudang melakukan sampling kondisi fisik barang sebelum menandatangani Berita Acara Serah Terima Barang (BASTB).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Catat Penerimaan Barang Masuk Baru --}}
<div class="modal fade" id="modal-tambah-penerimaan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventaris.penerimaan.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Catat Penerimaan Barang Masuk (Inbound Logistik)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Barang Yang Diterima: <span class="text-danger">*</span></label>
                                <select name="item_id" class="form-control" required id="select-inbound-item" onchange="updateItemInfo(this)">
                                    <option value="">-- Pilih Barang dari Master Inventaris --</option>
                                    @foreach($items as $it)
                                        <option value="{{ $it['id'] }}" data-location="{{ $it['location'] }}" data-unit="{{ $it['unit'] }}">
                                            {{ $it['name'] }} (Stok saat ini: {{ $it['stock'] }} {{ $it['unit'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nomor Surat Jalan / Delivery Order (DO): <span class="text-danger">*</span></label>
                                <input type="text" name="delivery_order_no" class="form-control" placeholder="Contoh: DO/SNW/2026/1105 atau SJ/8821" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Penerimaan: <span class="text-danger">*</span></label>
                                <input type="date" name="receipt_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Kuantitas Barang Diterima: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="quantity" class="form-control" min="1" value="10" required>
                                    <span class="input-group-addon" id="span-item-unit">Unit</span>
                                </div>
                                <small class="text-muted"><i class="fa fa-info-circle"></i> Kuantitas ini akan otomatis ditambahkan ke stok fisik barang.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Asal Sumber Pengadaan: <span class="text-danger">*</span></label>
                                <select name="source_type" class="form-control" required>
                                    <option value="pembelian_vendor">Pembelian Vendor / Supplier Rekanan</option>
                                    <option value="dana_bos">Dana Bantuan Operasional Sekolah (BOS)</option>
                                    <option value="hibah_donatur">Hibah / Wakaf Donatur & Komite</option>
                                    <option value="yayasan">Anggaran Yayasan Alia</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Vendor / Donatur / Pihak Pengirim: <span class="text-danger">*</span></label>
                                <input type="text" name="source_name" class="form-control" placeholder="Contoh: PT Toko Buku Barokah / H. Ahmad" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Petugas Pemeriksa / Penerima: <span class="text-danger">*</span></label>
                                <input type="text" name="officer_name" class="form-control" value="H. Mulyadi (Staf Gudang & Logistik)" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Rak Penyimpanan di Gudang: <span class="text-danger">*</span></label>
                                <input type="text" name="storage_location" class="form-control" id="input-storage-location" placeholder="Contoh: Gudang Utama Rak A-1" required>
                            </div>
                            <div class="form-group">
                                <label>Kondisi Fisik Saat Diterima: <span class="text-danger">*</span></label>
                                <select name="condition_received" class="form-control" required>
                                    <option value="baik" selected>Baik (Segel Utuh & Lengkap)</option>
                                    <option value="cacat_sebagian">Cacat Sebagian</option>
                                    <option value="rusak">Rusak / Tidak Sesuai Spesifikasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Catatan Pemeriksaan Logistik:</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan kardus pembungkus, garansi distributor, nama kurir ekspedisi..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Catat Penerimaan & Tambah Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateItemInfo(select) {
        var opt = select.options[select.selectedIndex];
        var loc = opt.getAttribute('data-location');
        var unit = opt.getAttribute('data-unit');
        if (loc) {
            document.getElementById('input-storage-location').value = loc;
        }
        if (unit) {
            document.getElementById('span-item-unit').textContent = unit;
        }
    }
</script>
@endpush
@endsection
