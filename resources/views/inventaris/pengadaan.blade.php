@extends('layouts.app')

@section('title', 'Simulasi Pengadaan Barang & PO')

@section('content_header')
    <h1>
        <i class="fa fa-shopping-bag text-primary"></i> Pengadaan Logistik & Purchase Order (PO)
        <small>Simulasi Usulan Belanja, Persetujuan Pimpinan, Penerbitan PO Resmi & Penerimaan Barang</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Inventaris & Logistik</li>
        <li class="active">Pengadaan Logistik (PO)</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pesanan Pengadaan</span>
                <span class="info-box-number">{{ $totalOrders }} Berkas</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Siklus pengadaan logistik</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Persetujuan</span>
                <span class="info-box-number">{{ $totalPending }} Usulan</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalOrders > 0 ? round(($totalPending / $totalOrders) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Verifikasi Kepala Sekolah / Yayasan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">PO Terbit / Diterima</span>
                <span class="info-box-number">{{ $totalPOIssued }} Pesanan</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalOrders > 0 ? round(($totalPOIssued / $totalOrders) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Surat pesanan resmi terbit</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Nilai Anggaran PO</span>
                <span class="info-box-number" style="font-size: 15px;">Rp {{ number_format($totalBudget, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Alokasi anggaran belanja</span>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Daftar Pengadaan & Status Purchase Order</a>
                </li>
                <li class="{{ $activeTab === 'persetujuan' ? 'active' : '' }}">
                    <a href="#tab_persetujuan" data-toggle="tab">
                        <i class="fa fa-check-square-o text-yellow"></i> Antrean Approval Pimpinan
                        @if($totalPending > 0)
                            <span class="badge bg-yellow" style="margin-left: 5px;">{{ $totalPending }}</span>
                        @endif
                    </a>
                </li>
                <li class="{{ $activeTab === 'alur' ? 'active' : '' }}">
                    <a href="#tab_alur" data-toggle="tab"><i class="fa fa-random text-green"></i> Diagram Alur Siklus Pengadaan (Workflow)</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Daftar Pengadaan & Status PO --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('inventaris.pengadaan') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Status:</label>
                                    <select name="status" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                        <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Diajukan (Draft)</option>
                                        <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui Pimpinan</option>
                                        <option value="terbit_po" {{ $statusFilter === 'terbit_po' ? 'selected' : '' }}>Terbit PO Resmi</option>
                                        <option value="diterima" {{ $statusFilter === 'diterima' ? 'selected' : '' }}>Barang Diterima (Selesai)</option>
                                        <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Unit:</label>
                                    <select name="unit" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $unitFilter === 'all' ? 'selected' : '' }}>-- Semua Unit --</option>
                                        <option value="UNIT-SMA" {{ $unitFilter === 'UNIT-SMA' ? 'selected' : '' }}>SMA Islam Alia</option>
                                        <option value="UNIT-SMP" {{ $unitFilter === 'UNIT-SMP' ? 'selected' : '' }}>SMP Islam Alia</option>
                                        <option value="UNIT-SD" {{ $unitFilter === 'UNIT-SD' ? 'selected' : '' }}>SD Islam Alia</option>
                                        <option value="YAYASAN" {{ $unitFilter === 'YAYASAN' ? 'selected' : '' }}>Yayasan / Asrama</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Judul, No. PO, vendor..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('inventaris.pengadaan', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah-po">
                                        <i class="fa fa-plus-circle"></i> Buat Usulan Pengadaan
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Daftar PO
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Pengadaan & PO --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Permintaan & PO</th>
                                    <th>Judul Pengadaan Barang</th>
                                    <th>Unit & Pemohon</th>
                                    <th>Vendor Rekanan</th>
                                    <th class="text-center">Jml Item</th>
                                    <th class="text-right">Total Anggaran</th>
                                    <th class="text-center">Status</th>
                                    <th width="140" class="text-center">Aksi & Pratinjau</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredProcurements as $po)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($po['po_number'])
                                                <strong class="text-green" style="font-family: monospace;">{{ $po['po_number'] }}</strong><br>
                                            @endif
                                            <span class="label label-default" style="font-family: monospace;">{{ $po['request_code'] }}</span><br>
                                            <small class="text-muted"><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($po['request_date'])) }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $po['title'] }}</strong><br>
                                            <small class="text-muted">Sumber: <strong>{{ $po['source_funding'] }}</strong></small>
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> {{ $po['requester_name'] }}<br>
                                            <small class="text-muted">{{ $po['requester_role'] }}</small><br>
                                            <span class="label label-info" style="font-size: 10px;">{{ $po['unit_id'] }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $po['vendor_name'] }}</strong><br>
                                            <small class="text-muted">{{ $po['vendor_contact'] }}</small>
                                        </td>
                                        <td class="text-center text-bold">
                                            {{ count($po['items']) }} jenis
                                        </td>
                                        <td class="text-right text-bold text-navy" style="font-size: 13px;">
                                            Rp {{ number_format($po['total_amount'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($po['status'] === 'diterima')
                                                <span class="label label-success"><i class="fa fa-check-circle"></i> Selesai Diterima</span>
                                            @elseif($po['status'] === 'terbit_po')
                                                <span class="label label-primary"><i class="fa fa-file-text-o"></i> PO Resmi Terbit</span>
                                            @elseif($po['status'] === 'disetujui')
                                                <span class="label label-info"><i class="fa fa-thumbs-up"></i> Disetujui</span>
                                            @elseif($po['status'] === 'ditolak')
                                                <span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>
                                            @else
                                                <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu Approval</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-po-{{ $po['id'] }}" title="Pratinjau Purchase Order (PO)">
                                                    <i class="fa fa-file-pdf-o text-primary"></i> Pratinjau PO
                                                </button>
                                                @if($po['status'] === 'diajukan')
                                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-approve-po-{{ $po['id'] }}" title="Proses Approval">
                                                        <i class="fa fa-check text-green"></i>
                                                    </button>
                                                @endif
                                                @if(in_array($po['status'], ['disetujui', 'terbit_po']))
                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-receive-po-{{ $po['id'] }}" title="Konfirmasi Terima Barang">
                                                        <i class="fa fa-truck"></i> Terima
                                                    </button>
                                                @endif
                                                <a href="{{ route('inventaris.pengadaan.destroy', $po['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus data pengadaan ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Pratinjau Surat Purchase Order (PO) Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-po-{{ $po['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-shopping-cart"></i> Surat Pesanan Resmi / Purchase Order (PO)</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">BAGIAN PENGADAAN BARANG, LOGISTIK & SARANA PRASARANA KAMPUS</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8660-1234</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">PURCHASE ORDER (SURAT PESANAN PEMBELIAN)</h5>
                                                                <small>Nomor PO: <strong>{{ $po['po_number'] ?: '(Draft Permintaan: ' . $po['request_code'] . ')' }}</strong></small>
                                                            </div>

                                                            <div class="row" style="margin-bottom: 15px;">
                                                                <div class="col-sm-6">
                                                                    <div class="well well-sm">
                                                                        <strong>Kepada Yth. Rekanan / Vendor:</strong><br>
                                                                        <strong class="text-primary">{{ $po['vendor_name'] }}</strong><br>
                                                                        {{ $po['vendor_contact'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <table class="table table-condensed table-bordered">
                                                                        <tr><th width="45%">Tanggal Pengajuan</th><td>{{ date('d F Y', strtotime($po['request_date'])) }}</td></tr>
                                                                        <tr><th>Unit Pengusul</th><td><span class="label label-info">{{ $po['unit_id'] }}</span> ({{ $po['requester_name'] }})</td></tr>
                                                                        <tr><th>Sumber Pembiayaan</th><td><strong>{{ $po['source_funding'] }}</strong></td></tr>
                                                                        <tr><th>Status Pesanan</th><td><span class="label label-primary">{{ strtoupper($po['status']) }}</span></td></tr>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <h4><i class="fa fa-list text-primary"></i> Daftar Rincian Barang Yang Dipesan</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr class="bg-gray-light text-center">
                                                                            <th width="40">No</th>
                                                                            <th>Deskripsi Barang & Spesifikasi</th>
                                                                            <th class="text-center" width="90">Qty</th>
                                                                            <th class="text-center" width="80">Satuan</th>
                                                                            <th class="text-right" width="140">Harga Satuan (Rp)</th>
                                                                            <th class="text-right" width="150">Subtotal (Rp)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($po['items'] as $item)
                                                                            <tr>
                                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                                <td><strong>{{ $item['item_name'] }}</strong></td>
                                                                                <td class="text-center text-bold">{{ $item['quantity'] }}</td>
                                                                                <td class="text-center">{{ $item['unit'] }}</td>
                                                                                <td class="text-right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                                                                                <td class="text-right text-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        <tr class="bg-gray-light text-bold" style="font-size: 14px;">
                                                                            <td colspan="5" class="text-right">TOTAL NILAI PEMBELIAN (PO):</td>
                                                                            <td class="text-right text-navy">Rp {{ number_format($po['total_amount'], 0, ',', '.') }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            @if($po['approver_name'])
                                                                <div class="alert alert-success" style="font-size: 12px; margin-bottom: 0;">
                                                                    <i class="fa fa-check-circle"></i> Telah diverifikasi & disetujui pimpinan: <strong>{{ $po['approver_name'] }}</strong> pada tanggal {{ $po['approved_at'] }}.
                                                                </div>
                                                            @endif

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Disiapkan Oleh / Bagian Logistik,</small><br><br><br>
                                                                    <strong>(H. Mulyadi)</strong>
                                                                </div>
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Disetujui Oleh / Direktur Yayasan,</small><br><br><br>
                                                                    <strong>({{ $po['approver_name'] ?: 'Dr. H. Zulkifli, Lc., M.A.' }})</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Dokumen PO</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Keputusan Approval Pimpinan --}}
                                            @if($po['status'] === 'diajukan')
                                                <div class="modal fade" id="modal-approve-po-{{ $po['id'] }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content text-left">
                                                            <form method="POST" action="{{ route('inventaris.pengadaan.approve', $po['id']) }}">
                                                                @csrf
                                                                <div class="modal-header bg-green">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Persetujuan Pengadaan & Penerbitan PO</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Permintaan: <strong>{{ $po['title'] }}</strong></p>
                                                                    <p>Total Anggaran: <strong class="text-navy">Rp {{ number_format($po['total_amount'], 0, ',', '.') }}</strong></p>

                                                                    <div class="form-group">
                                                                        <label>Tindakan Persetujuan: <span class="text-danger">*</span></label>
                                                                        <select name="status" class="form-control" required id="select-status-po-{{ $po['id'] }}" onchange="toggleRejectionPo(this.value, '{{ $po['id'] }}')">
                                                                            <option value="terbit_po">SETUJUI & TERBITKAN PURCHASE ORDER (PO)</option>
                                                                            <option value="disetujui">SETUJUI (Menunggu Penjadwalan Kasir)</option>
                                                                            <option value="ditolak">TOLAK (Anggaran Tidak Mencukupi)</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group" id="rejection-box-po-{{ $po['id'] }}" style="display: none;">
                                                                        <label>Alasan Penolakan / Catatan:</label>
                                                                        <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Sebutkan pertimbangan penolakan..."></textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Nama Pejabat Yang Menyetujui: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="approver_name" class="form-control" value="Dr. H. Zulkifli, Lc., M.A. (Yayasan)" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan & Terbitkan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Modal Konfirmasi Terima Barang (Receipt PO) --}}
                                            @if(in_array($po['status'], ['disetujui', 'terbit_po']))
                                                <div class="modal fade" id="modal-receive-po-{{ $po['id'] }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content text-left">
                                                            <form method="POST" action="{{ route('inventaris.pengadaan.receive', $po['id']) }}">
                                                                @csrf
                                                                <div class="modal-header bg-green">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><i class="fa fa-truck"></i> Konfirmasi Penerimaan Barang dari PO</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert-info">
                                                                        <i class="fa fa-info-circle"></i> Mengonfirmasi penerimaan barang akan secara otomatis:
                                                                        <ul>
                                                                            <li>Mengubah status PO menjadi <strong>DITERIMA LENGKAP</strong>.</li>
                                                                            <li>Menambahkan kuantitas barang ke <strong>stok fisik di Master Barang</strong>.</li>
                                                                            <li>Mencatat bukti transaksi masuk ke <strong>Buku Penerimaan Barang Logistik</strong>.</li>
                                                                        </ul>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Nomor Surat Jalan / Delivery Order (DO) Vendor: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="delivery_order_no" class="form-control" placeholder="Contoh: DO/SJ-{{ date('Ymd') }}-01" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Nama Petugas Pemeriksa & Penerima: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="officer_name" class="form-control" value="H. Mulyadi (Staf Gudang & Logistik)" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Catatan Pemeriksaan Barang Masuk:</label>
                                                                        <textarea name="notes" class="form-control" rows="2" placeholder="Barang telah diperiksa lengkap 100% dan kondisi fisik prima..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Konfirmasi Terima & Tambah Stok</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-shopping-bag fa-2x"></i><br>
                                            Tidak ada pesanan pengadaan logistik yang cocok dengan filter pencarian Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Antrean Approval Pimpinan --}}
                <div class="tab-pane {{ $activeTab === 'persetujuan' ? 'active' : '' }}" id="tab_persetujuan">
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-inbox text-yellow"></i> Usulan Masuk Menunggu Penerbitan Purchase Order (PO)</h4>
                            @php
                                $pendingOrders = array_filter($procurements, fn($p) => ($p['status'] ?? '') === 'diajukan');
                            @endphp

                            @if(count($pendingOrders) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Kode & Tanggal</th>
                                                <th>Nama Usulan Pengadaan</th>
                                                <th>Unit & Pemohon</th>
                                                <th>Vendor Calon</th>
                                                <th class="text-right">Total Anggaran</th>
                                                <th class="text-center" width="160">Aksi Persetujuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingOrders as $po)
                                                <tr>
                                                    <td>
                                                        <span class="label label-default" style="font-family: monospace;">{{ $po['request_code'] }}</span><br>
                                                        <small>{{ date('d M Y', strtotime($po['request_date'])) }}</small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-primary">{{ $po['title'] }}</strong><br>
                                                        <small class="text-muted">{{ count($po['items']) }} jenis barang</small>
                                                    </td>
                                                    <td>
                                                        {{ $po['requester_name'] }}<br>
                                                        <span class="label label-info">{{ $po['unit_id'] }}</span>
                                                    </td>
                                                    <td><strong>{{ $po['vendor_name'] }}</strong></td>
                                                    <td class="text-right text-bold text-navy">
                                                        Rp {{ number_format($po['total_amount'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-approve-po-{{ $po['id'] }}">
                                                            <i class="fa fa-check"></i> Proses Izin & PO
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> Tidak ada antrean usulan pengadaan yang tertunda. Seluruh permohonan telah diproses pimpinan!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Diagram Alur Siklus Pengadaan (Workflow) --}}
                <div class="tab-pane {{ $activeTab === 'alur' ? 'active' : '' }}" id="tab_alur">
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-random text-green"></i> Siklus Lengkap Alur Pengadaan Barang Gudang & Logistik</h4>
                            <div class="callout callout-info" style="border-left-color: #1a365d;">
                                <h4>SOP Pengadaan Barang Pesantren Alia</h4>
                                <p>Sistem inventaris IRNIS mengintegrasikan 4 tahapan alur belanja sekolah terpadu secara otomatis dan tertib administrasi.</p>
                            </div>

                            <div class="row text-center" style="margin-top: 20px;">
                                <div class="col-md-3">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header">
                                            <h3 class="box-title">1. Permintaan (Request)</h3>
                                        </div>
                                        <div class="box-body">
                                            <i class="fa fa-file-text-o fa-3x text-primary"></i>
                                            <p style="margin-top: 10px;">Guru / unit membuat usulan kebutuhan barang beserta estimasi harga dan vendor calon.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="box box-solid box-warning">
                                        <div class="box-header">
                                            <h3 class="box-title">2. Approval Pimpinan</h3>
                                        </div>
                                        <div class="box-body">
                                            <i class="fa fa-check-square-o fa-3x text-yellow"></i>
                                            <p style="margin-top: 10px;">Kepala Sekolah / Yayasan memeriksa ketersediaan anggaran RAPBS dan menyetujui usulan.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="box box-solid box-info">
                                        <div class="box-header">
                                            <h3 class="box-title">3. Penerbitan PO</h3>
                                        </div>
                                        <div class="box-body">
                                            <i class="fa fa-shopping-cart fa-3x text-aqua"></i>
                                            <p style="margin-top: 10px;">Bagian Logistik menerbitkan dokumen Purchase Order (PO) resmi ke supplier rekanan.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="box box-solid box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">4. Penerimaan & Stok</h3>
                                        </div>
                                        <div class="box-body">
                                            <i class="fa fa-truck fa-3x text-green"></i>
                                            <p style="margin-top: 10px;">Barang tiba diperiksa, BASTB ditandatangani, dan kuantitas stok bertambah otomatis.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Buat Usulan Pengadaan Barang Baru --}}
<div class="modal fade" id="modal-tambah-po" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventaris.pengadaan.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Buat Permintaan Pengadaan Barang (Purchase Request)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Pengadaan Barang: <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Contoh: Pengadaan Kertas HVS & Spidol Semester Baru" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Pemohon: <span class="text-danger">*</span></label>
                                <input type="text" name="requester_name" class="form-control" placeholder="Contoh: Ahmad Fauzi, S.Pd." required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Pemohon: <span class="text-danger">*</span></label>
                                <input type="text" name="requester_role" class="form-control" placeholder="Contoh: Wakasek Kurikulum / Koordinator Lab" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Sekolah Pemohon: <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="UNIT-SMA">SMA Islam Alia</option>
                                    <option value="UNIT-SMP">SMP Islam Alia</option>
                                    <option value="UNIT-SD">SD Islam Alia</option>
                                    <option value="YAYASAN">Yayasan / Pesantren Terpadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pengajuan: <span class="text-danger">*</span></label>
                                <input type="date" name="request_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Vendor / Supplier Rekanan: <span class="text-danger">*</span></label>
                                <input type="text" name="vendor_name" class="form-control" placeholder="Contoh: PT Toko Buku Barokah" required>
                            </div>
                            <div class="form-group">
                                <label>Kontak / Alamat Vendor:</label>
                                <input type="text" name="vendor_contact" class="form-control" placeholder="Contoh: Jl. Matraman No. 45 | 021-85901122">
                            </div>
                            <div class="form-group">
                                <label>Rencana Sumber Pembiayaan: <span class="text-danger">*</span></label>
                                <input type="text" name="source_funding" class="form-control" value="BOS Reguler & Yayasan Alia" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Catatan Tambahan Kepada Panitia:</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan batas waktu pengiriman atau spesifikasi khusus..."></textarea>
                            </div>
                        </div>
                    </div>

                    <hr style="margin-top: 5px; margin-bottom: 15px;">
                    <h4><i class="fa fa-list text-primary"></i> Rincian Barang Yang Dipesan</h4>

                    <div id="po-items-container">
                        <div class="row po-item-row" style="margin-bottom: 10px;">
                            <div class="col-sm-5">
                                <label>Pilih Barang / Masukkan Nama: <span class="text-danger">*</span></label>
                                <select name="item_ids[]" class="form-control input-sm select-catalog-item" onchange="autoFillPoItem(this)">
                                    <option value="">-- Ketik Nama Manual atau Pilih Katalog --</option>
                                    @foreach($items as $it)
                                        <option value="{{ $it['id'] }}" data-name="{{ $it['name'] }}" data-unit="{{ $it['unit'] }}" data-price="{{ $it['unit_price'] }}">
                                            {{ $it['name'] }} (Katalog Master)
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="item_names[]" class="form-control input-sm item-name-field" style="margin-top: 4px;" placeholder="Nama barang / spesifikasi lengkap..." required>
                            </div>
                            <div class="col-sm-2">
                                <label>Qty: <span class="text-danger">*</span></label>
                                <input type="number" name="item_quantities[]" class="form-control input-sm" min="1" value="1" required>
                            </div>
                            <div class="col-sm-2">
                                <label>Satuan: <span class="text-danger">*</span></label>
                                <input type="text" name="item_units[]" class="form-control input-sm item-unit-field" value="Unit" required>
                            </div>
                            <div class="col-sm-3">
                                <label>Harga Satuan (Rp): <span class="text-danger">*</span></label>
                                <input type="number" name="item_prices[]" class="form-control input-sm item-price-field" min="0" placeholder="Contoh: 54000" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-default btn-sm" id="btn-add-po-item"><i class="fa fa-plus"></i> Tambah Item Barang</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim Permintaan Pengadaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRejectionPo(val, id) {
        var box = document.getElementById('rejection-box-po-' + id);
        if (box) {
            box.style.display = (val === 'ditolak') ? 'block' : 'none';
        }
    }

    function autoFillPoItem(select) {
        var opt = select.options[select.selectedIndex];
        var row = select.closest('.po-item-row');
        if (opt.value) {
            row.querySelector('.item-name-field').value = opt.getAttribute('data-name');
            row.querySelector('.item-unit-field').value = opt.getAttribute('data-unit');
            row.querySelector('.item-price-field').value = opt.getAttribute('data-price');
        }
    }

    document.getElementById('btn-add-po-item').addEventListener('click', function() {
        var container = document.getElementById('po-items-container');
        var newRow = document.createElement('div');
        newRow.className = 'row po-item-row';
        newRow.style.marginBottom = '10px';
        newRow.innerHTML = `
            <div class="col-sm-5">
                <input type="text" name="item_names[]" class="form-control input-sm" placeholder="Nama barang / spesifikasi lengkap..." required>
            </div>
            <div class="col-sm-2">
                <input type="number" name="item_quantities[]" class="form-control input-sm" min="1" value="1" required>
            </div>
            <div class="col-sm-2">
                <input type="text" name="item_units[]" class="form-control input-sm" value="Unit" required>
            </div>
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="number" name="item_prices[]" class="form-control input-sm" min="0" placeholder="Harga satuan (Rp)..." required>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-po-item"><i class="fa fa-times"></i></button>
                    </span>
                </div>
            </div>
        `;
        container.appendChild(newRow);

        newRow.querySelector('.btn-remove-po-item').addEventListener('click', function() {
            newRow.remove();
        });
    });
</script>
@endpush
@endsection
