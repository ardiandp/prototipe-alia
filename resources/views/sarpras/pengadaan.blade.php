@extends('layouts.app')

@section('title', 'Pengadaan Sarana & Prasarana')

@section('content_header')
    <h1>
        <i class="fa fa-shopping-cart text-primary"></i> Pengadaan Sarana & Prasarana
        <small>Usulan Pengadaan Fasilitas, Rincian Anggaran Belanja & Persetujuan Yayasan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Sarpras & Fasilitas</li>
        <li class="active">Pengadaan</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Usulan Pengadaan</span>
                <span class="info-box-number">{{ $totalRequests }} Proposal</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Tercatat di sistem sarpras</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Persetujuan</span>
                <span class="info-box-number">{{ $totalPending }} Usulan</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalRequests > 0 ? round(($totalPending / $totalRequests) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Verifikasi sarpras & yayasan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Disetujui / Selesai</span>
                <span class="info-box-number">{{ $totalApproved }} Usulan</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalRequests > 0 ? round(($totalApproved / $totalRequests) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Siap / telah dibelanjakan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Estimasi Nilai Belanja</span>
                <span class="info-box-number" style="font-size: 15px;">Rp {{ number_format($totalBudget, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Akumulasi anggaran RAPBS</span>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Daftar Usulan Pengadaan</a>
                </li>
                <li class="{{ $activeTab === 'persetujuan' ? 'active' : '' }}">
                    <a href="#tab_persetujuan" data-toggle="tab">
                        <i class="fa fa-check-square-o text-yellow"></i> Antrean Persetujuan Yayasan
                        @if($totalPending > 0)
                            <span class="badge bg-yellow" style="margin-left: 5px;">{{ $totalPending }}</span>
                        @endif
                    </a>
                </li>
                <li class="{{ $activeTab === 'rekap' ? 'active' : '' }}">
                    <a href="#tab_rekap" data-toggle="tab"><i class="fa fa-pie-chart text-green"></i> Rekapitulasi per Sumber Dana</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Daftar Usulan Pengadaan --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('sarpras.pengadaan') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Status:</label>
                                    <select name="status" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                        <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Diajukan (Draft)</option>
                                        <option value="verifikasi_sarpras" {{ $statusFilter === 'verifikasi_sarpras' ? 'selected' : '' }}>Verifikasi Sarpras</option>
                                        <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui Yayasan</option>
                                        <option value="dalam_pemesanan" {{ $statusFilter === 'dalam_pemesanan' ? 'selected' : '' }}>Dalam Pemesanan (PO)</option>
                                        <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai / Diterima</option>
                                        <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kategori:</label>
                                    <select name="category" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>-- Semua Kategori --</option>
                                        <option value="elektronik_it" {{ $categoryFilter === 'elektronik_it' ? 'selected' : '' }}>Elektronik & IT</option>
                                        <option value="mebelair" {{ $categoryFilter === 'mebelair' ? 'selected' : '' }}>Mebelair Santri</option>
                                        <option value="sarana_olahraga" {{ $categoryFilter === 'sarana_olahraga' ? 'selected' : '' }}>Sarana Olahraga</option>
                                        <option value="laboratorium" {{ $categoryFilter === 'laboratorium' ? 'selected' : '' }}>Alat Praktikum Lab</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Urgensi:</label>
                                    <select name="urgency" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $urgencyFilter === 'all' ? 'selected' : '' }}>-- Semua Urgensi --</option>
                                        <option value="mendesak" {{ $urgencyFilter === 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                                        <option value="tinggi" {{ $urgencyFilter === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                        <option value="sedang" {{ $urgencyFilter === 'sedang' ? 'selected' : '' }}>Sedang</option>
                                        <option value="rutin" {{ $urgencyFilter === 'rutin' ? 'selected' : '' }}>Rutin</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Judul, kode, pemohon..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('sarpras.pengadaan', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-tambah-pengadaan">
                                        <i class="fa fa-plus-circle"></i> Usulkan Pengadaan Baru
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Rekap
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Usulan Pengadaan --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Proposal</th>
                                    <th>Nama Usulan Pengadaan</th>
                                    <th>Pemohon & Unit</th>
                                    <th>Target Lokasi</th>
                                    <th class="text-center">Jml Item</th>
                                    <th class="text-right">Estimasi Anggaran</th>
                                    <th class="text-center">Status</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredProcurements as $prc)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $prc['request_code'] }}</span><br>
                                            <small class="text-muted"><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($prc['request_date'])) }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $prc['title'] }}</strong><br>
                                            <span class="label label-info" style="font-size: 10px;">{{ $prc['category_label'] }}</span>
                                            @if($prc['urgency'] === 'mendesak')
                                                <span class="label label-danger" style="font-size: 10px;">Mendesak</span>
                                            @elseif($prc['urgency'] === 'tinggi')
                                                <span class="label label-warning" style="font-size: 10px;">Tinggi</span>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> {{ $prc['requester_name'] }}<br>
                                            <small class="text-muted">{{ $prc['requester_role'] }} ({{ $prc['unit_id'] }})</small>
                                        </td>
                                        <td>
                                            <small><i class="fa fa-map-marker text-red"></i> {{ $prc['target_location'] }}</small><br>
                                            <span class="text-muted" style="font-size: 11px;">Sumber: {{ $prc['source_funding'] }}</span>
                                        </td>
                                        <td class="text-center text-bold">
                                            {{ $prc['total_items_count'] }} unit
                                        </td>
                                        <td class="text-right text-bold text-navy" style="font-size: 13px;">
                                            Rp {{ number_format($prc['estimated_total'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($prc['status'] === 'disetujui')
                                                <span class="label label-success"><i class="fa fa-check-circle"></i> Disetujui</span>
                                            @elseif($prc['status'] === 'verifikasi_sarpras')
                                                <span class="label label-info"><i class="fa fa-search"></i> Verifikasi Sarpras</span>
                                            @elseif($prc['status'] === 'dalam_pemesanan')
                                                <span class="label label-primary"><i class="fa fa-shopping-bag"></i> Dalam PO</span>
                                            @elseif($prc['status'] === 'selesai')
                                                <span class="label label-default"><i class="fa fa-flag-checkered"></i> Diterima Lengkap</span>
                                            @elseif($prc['status'] === 'ditolak')
                                                <span class="label label-danger"><i class="fa fa-times-circle"></i> Ditolak</span>
                                            @else
                                                <span class="label label-warning"><i class="fa fa-clock-o"></i> Diajukan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-prc-{{ $prc['id'] }}" title="Lihat Formulir Pengadaan & RAB">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                @if(in_array($prc['status'], ['diajukan', 'verifikasi_sarpras']))
                                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-approve-prc-{{ $prc['id'] }}" title="Persetujuan Yayasan">
                                                        <i class="fa fa-check-square-o text-green"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('sarpras.pengadaan.destroy', $prc['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus usulan pengadaan ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Formulir Usulan Pengadaan & Rincian RAB Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-prc-{{ $prc['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Formulir Pengajuan Pengadaan Sarana & Prasarana</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">PANITIA PENGADAAN BARANG & SARANA PRASARANA KAMPUS</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">LEMBAR USULAN PENGADAAN & ESTIMASI ANGGARAN (RAB)</h5>
                                                                <small>Nomor: <strong>{{ $prc['request_code'] }}</strong></small>
                                                            </div>

                                                            <div class="row" style="margin-bottom: 15px;">
                                                                <div class="col-sm-6">
                                                                    <table class="table table-condensed table-bordered">
                                                                        <tr><th width="40%">Judul Pengadaan</th><td><strong>{{ $prc['title'] }}</strong></td></tr>
                                                                        <tr><th>Kategori</th><td>{{ $prc['category_label'] }}</td></tr>
                                                                        <tr><th>Pemohon</th><td>{{ $prc['requester_name'] }} ({{ $prc['requester_role'] }})</td></tr>
                                                                        <tr><th>Unit Pengusul</th><td><span class="label label-info">{{ $prc['unit_id'] }}</span></td></tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <table class="table table-condensed table-bordered">
                                                                        <tr><th width="40%">Tanggal Usulan</th><td>{{ date('d F Y', strtotime($prc['request_date'])) }}</td></tr>
                                                                        <tr><th>Target Penempatan</th><td>{{ $prc['target_location'] }}</td></tr>
                                                                        <tr><th>Sumber Pembiayaan</th><td><strong>{{ $prc['source_funding'] }}</strong></td></tr>
                                                                        <tr><th>Status Persetujuan</th><td><span class="label label-primary">{{ strtoupper($prc['status']) }}</span></td></tr>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div class="well well-sm">
                                                                <strong><i class="fa fa-info-circle"></i> Alasan Kebutuhan / Justifikasi Pengadaan:</strong><br>
                                                                <p style="margin-top: 5px; margin-bottom: 0;">{{ $prc['justification'] }}</p>
                                                            </div>

                                                            <h4><i class="fa fa-shopping-cart text-primary"></i> Rincian Item Barang & Estimasi Biaya</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr class="bg-gray-light text-center">
                                                                            <th width="40">No</th>
                                                                            <th>Deskripsi Spesifikasi Barang</th>
                                                                            <th class="text-center" width="80">Qty</th>
                                                                            <th class="text-center" width="80">Satuan</th>
                                                                            <th class="text-right" width="140">Harga Satuan (Rp)</th>
                                                                            <th class="text-right" width="150">Subtotal (Rp)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($prc['items'] as $item)
                                                                            <tr>
                                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                                <td><strong>{{ $item['item_name'] }}</strong></td>
                                                                                <td class="text-center">{{ $item['quantity'] }}</td>
                                                                                <td class="text-center">{{ $item['unit'] }}</td>
                                                                                <td class="text-right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                                                                                <td class="text-right text-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        <tr class="bg-gray-light text-bold" style="font-size: 14px;">
                                                                            <td colspan="5" class="text-right">TOTAL ESTIMASI ANGGARAN (RAB):</td>
                                                                            <td class="text-right text-navy">Rp {{ number_format($prc['estimated_total'], 0, ',', '.') }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            @if($prc['approver_name'])
                                                                <div class="alert alert-success" style="font-size: 12px; margin-bottom: 0;">
                                                                    <i class="fa fa-check-circle"></i> Usulan ini telah disetujui oleh: <strong>{{ $prc['approver_name'] }}</strong> pada tanggal {{ $prc['approved_at'] }}.<br>
                                                                    Catatan Pimpinan: {{ $prc['notes'] }}
                                                                </div>
                                                            @endif

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-4 text-center">
                                                                    <small>Pemohon / Unit Pengusul,</small><br><br><br>
                                                                    <strong>({{ $prc['requester_name'] }})</strong>
                                                                </div>
                                                                <div class="col-xs-4 text-center">
                                                                    <small>Verifikator Sarpras,</small><br><br><br>
                                                                    <strong>(Koordinator Sarpras)</strong>
                                                                </div>
                                                                <div class="col-xs-4 text-center">
                                                                    <small>Menyetujui,</small><br>
                                                                    <small>Direktur Yayasan Alia,</small><br><br>
                                                                    <strong>({{ $prc['approver_name'] ?: 'Dr. H. Zulkifli, Lc., M.A.' }})</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Formulir Usulan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Persetujuan Yayasan --}}
                                            @if(in_array($prc['status'], ['diajukan', 'verifikasi_sarpras']))
                                                <div class="modal fade" id="modal-approve-prc-{{ $prc['id'] }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content text-left">
                                                            <form method="POST" action="{{ route('sarpras.pengadaan.approve', $prc['id']) }}">
                                                                @csrf
                                                                <div class="modal-header bg-green">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Persetujuan Anggaran Pengadaan Sarpras</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Proses usulan: <strong>{{ $prc['title'] }}</strong></p>
                                                                    <p>Estimasi Anggaran: <strong class="text-navy">Rp {{ number_format($prc['estimated_total'], 0, ',', '.') }}</strong></p>

                                                                    <div class="form-group">
                                                                        <label>Keputusan Tindak Lanjut: <span class="text-danger">*</span></label>
                                                                        <select name="status" class="form-control" required id="select-status-prc-{{ $prc['id'] }}" onchange="toggleRejectionPrc(this.value, '{{ $prc['id'] }}')">
                                                                            <option value="disetujui">DISETUJUI YAYASAN (Alokasikan Anggaran Belanja)</option>
                                                                            <option value="verifikasi_sarpras">VERIFIKASI SARPRAS (Butuh Survei Vendor/Spesifikasi)</option>
                                                                            <option value="dalam_pemesanan">DALAM PEMESANAN (Terbitkan Purchase Order - PO)</option>
                                                                            <option value="ditolak">DITOLAK (Anggaran Tidak Mencukupi / Ditunda)</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group" id="rejection-box-prc-{{ $prc['id'] }}" style="display: none;">
                                                                        <label>Alasan Penolakan / Catatan Revisi:</label>
                                                                        <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Sebutkan pertimbangan penolakan..."></textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Pejabat Yang Menyetujui: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="approver_name" class="form-control" value="Dr. H. Zulkifli, Lc., M.A. (Direktur Yayasan)" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Catatan Rekomendasi / Instruksi Pembelian:</label>
                                                                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Pembelian via rekanan resmi bergaransi 2 tahun..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Keputusan</button>
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
                                            <i class="fa fa-shopping-cart fa-2x"></i><br>
                                            Tidak ada usulan pengadaan sarana yang cocok dengan filter pencarian Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Antrean Persetujuan Yayasan --}}
                <div class="tab-pane {{ $activeTab === 'persetujuan' ? 'active' : '' }}" id="tab_persetujuan">
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-inbox text-yellow"></i> Usulan Masuk Menunggu Keputusan Anggaran</h4>
                            @php
                                $pendingPrcList = array_filter($procurements, fn($p) => in_array($p['status'] ?? '', ['diajukan', 'verifikasi_sarpras']));
                            @endphp

                            @if(count($pendingPrcList) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Kode & Tanggal</th>
                                                <th>Nama Usulan Sarpras</th>
                                                <th>Unit & Pemohon</th>
                                                <th>Sumber Pembiayaan</th>
                                                <th class="text-right">Estimasi Biaya</th>
                                                <th class="text-center" width="160">Aksi Persetujuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingPrcList as $pp)
                                                <tr>
                                                    <td>
                                                        <span class="label label-default" style="font-family: monospace;">{{ $pp['request_code'] }}</span><br>
                                                        <small>{{ date('d M Y', strtotime($pp['request_date'])) }}</small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-primary">{{ $pp['title'] }}</strong><br>
                                                        <small class="text-muted">{{ $pp['category_label'] }} ({{ $pp['total_items_count'] }} unit)</small>
                                                    </td>
                                                    <td>
                                                        {{ $pp['requester_name'] }}<br>
                                                        <span class="label label-info">{{ $pp['unit_id'] }}</span>
                                                    </td>
                                                    <td><strong>{{ $pp['source_funding'] }}</strong></td>
                                                    <td class="text-right text-bold text-navy">
                                                        Rp {{ number_format($pp['estimated_total'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-approve-prc-{{ $pp['id'] }}">
                                                            <i class="fa fa-check"></i> Proses Izin
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> Tidak ada antrean usulan pengadaan sarana. Semua proposal telah diproses pimpinan!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Rekapitulasi per Sumber Dana --}}
                <div class="tab-pane {{ $activeTab === 'rekap' ? 'active' : '' }}" id="tab_rekap">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-pie-chart text-green"></i> Distribusi Anggaran Berdasarkan Sumber Dana</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Sumber Pembiayaan</th>
                                                <th class="text-center">Jumlah Usulan</th>
                                                <th class="text-right">Total Anggaran (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>BOS Kinerja & Yayasan Alia</strong></td>
                                                <td class="text-center">1 Usulan</td>
                                                <td class="text-right text-bold">Rp 179.850.000</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dana Sumbangan Wakaf & Event</strong></td>
                                                <td class="text-center">1 Usulan</td>
                                                <td class="text-right text-bold">Rp 112.500.000</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Anggaran Investasi Sarpras Yayasan</strong></td>
                                                <td class="text-center">1 Usulan</td>
                                                <td class="text-right text-bold">Rp 48.000.000</td>
                                            </tr>
                                            <tr>
                                                <td><strong>BOS Reguler SMP Islam Alia</strong></td>
                                                <td class="text-center">1 Usulan</td>
                                                <td class="text-right text-bold">Rp 19.900.000</td>
                                            </tr>
                                            <tr class="bg-gray-light text-bold">
                                                <td>TOTAL ALOKASI PENGADAAN</td>
                                                <td class="text-center">{{ $totalRequests }} Usulan</td>
                                                <td class="text-right text-navy">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-balance-scale text-primary"></i> Tata Kelola & SOP Pengadaan Sarpras</h4>
                            <div class="callout callout-info">
                                <h4>Prinsip Akuntabilitas & Transparansi Pesantren</h4>
                                <p>Setiap pengadaan barang/sarana bernilai di atas Rp 10.000.000 wajib menyertakan minimal 2 surat penawaran harga perbandingan dari vendor resmi berbadan hukum (CV/PT), serta melalui verifikasi kelayakan teknis dari Bagian Sarpras sebelum ditandatangani oleh Direktur Yayasan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ajukan Usulan Pengadaan Baru --}}
<div class="modal fade" id="modal-tambah-pengadaan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('sarpras.pengadaan.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Buat Usulan Pengadaan Sarana & Prasarana Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Usulan Pengadaan: <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Contoh: Pengadaan 30 Unit Komputer & Server CBT" required>
                            </div>
                            <div class="form-group">
                                <label>Kategori Pengadaan: <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="elektronik_it">Elektronik & Perangkat Komputer</option>
                                    <option value="mebelair">Mebelair & Furnitur Santri</option>
                                    <option value="sarana_olahraga">Sarana & Alat Olahraga</option>
                                    <option value="utilitas_daya">Utilitas & Kelistrikan</option>
                                    <option value="laboratorium">Alat & Bahan Praktikum Lab</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tingkat Urgensi: <span class="text-danger">*</span></label>
                                <select name="urgency" class="form-control" required>
                                    <option value="mendesak">Mendesak (Kebutuhan Kritis KBM)</option>
                                    <option value="tinggi" selected>Tinggi (Prioritas Semester Ini)</option>
                                    <option value="sedang">Sedang (Peremajaan Normal)</option>
                                    <option value="rutin">Rutin / Terjadwal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Pemohon: <span class="text-danger">*</span></label>
                                <input type="text" name="requester_name" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom." required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Pemohon: <span class="text-danger">*</span></label>
                                <input type="text" name="requester_role" class="form-control" placeholder="Contoh: Kepala Lab Komputer" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit Sekolah Pengusul: <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="UNIT-SMA">SMA Islam Alia</option>
                                    <option value="UNIT-SMP">SMP Islam Alia</option>
                                    <option value="UNIT-SD">SD Islam Alia</option>
                                    <option value="YAYASAN">Yayasan / Pesantren Terpadu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Target Penempatan / Ruangan: <span class="text-danger">*</span></label>
                                <input type="text" name="target_location" class="form-control" placeholder="Contoh: Lab Komputer Gedung Ali Lt 2" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengajuan: <span class="text-danger">*</span></label>
                                <input type="date" name="request_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Rencana Sumber Pembiayaan: <span class="text-danger">*</span></label>
                                <input type="text" name="source_funding" class="form-control" value="Anggaran Yayasan Alia & BOS" required>
                            </div>
                            <div class="form-group">
                                <label>Catatan Tambahan Kepada Panitia:</label>
                                <input type="text" name="notes" class="form-control" placeholder="Contoh: Spesifikasi minimal Core i5 gen 12...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Justifikasi Kebutuhan / Manfaat Pengadaan: <span class="text-danger">*</span></label>
                                <textarea name="justification" class="form-control" rows="2" required placeholder="Jelaskan secara mendalam urgensi pengadaan barang ini bagi peningkatan mutu pendidikan santri..."></textarea>
                            </div>
                        </div>
                    </div>

                    <hr style="margin-top: 5px; margin-bottom: 15px;">
                    <h4><i class="fa fa-list text-primary"></i> Rincian Barang Yang Diusulkan</h4>

                    <div id="item-rows-container">
                        <div class="row item-row" style="margin-bottom: 10px;">
                            <div class="col-sm-5">
                                <label>Nama Barang / Spesifikasi: <span class="text-danger">*</span></label>
                                <input type="text" name="item_names[]" class="form-control input-sm" placeholder="Contoh: PC Desktop Core i5 RAM 16GB" required>
                            </div>
                            <div class="col-sm-2">
                                <label>Jumlah: <span class="text-danger">*</span></label>
                                <input type="number" name="item_quantities[]" class="form-control input-sm qty-input" min="1" value="1" required>
                            </div>
                            <div class="col-sm-2">
                                <label>Satuan: <span class="text-danger">*</span></label>
                                <input type="text" name="item_units[]" class="form-control input-sm" value="Unit" required>
                            </div>
                            <div class="col-sm-3">
                                <label>Harga Satuan (Rp): <span class="text-danger">*</span></label>
                                <input type="number" name="item_prices[]" class="form-control input-sm price-input" min="0" placeholder="Contoh: 7500000" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-default btn-sm" id="btn-add-item"><i class="fa fa-plus"></i> Tambah Baris Barang</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim Usulan Pengadaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRejectionPrc(val, id) {
        var box = document.getElementById('rejection-box-prc-' + id);
        if (box) {
            box.style.display = (val === 'ditolak') ? 'block' : 'none';
        }
    }

    document.getElementById('btn-add-item').addEventListener('click', function() {
        var container = document.getElementById('item-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row item-row';
        newRow.style.marginBottom = '10px';
        newRow.innerHTML = `
            <div class="col-sm-5">
                <input type="text" name="item_names[]" class="form-control input-sm" placeholder="Nama barang / spesifikasi..." required>
            </div>
            <div class="col-sm-2">
                <input type="number" name="item_quantities[]" class="form-control input-sm qty-input" min="1" value="1" required>
            </div>
            <div class="col-sm-2">
                <input type="text" name="item_units[]" class="form-control input-sm" value="Unit" required>
            </div>
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="number" name="item_prices[]" class="form-control input-sm price-input" min="0" placeholder="Harga satuan (Rp)..." required>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-times"></i></button>
                    </span>
                </div>
            </div>
        `;
        container.appendChild(newRow);

        newRow.querySelector('.btn-remove-item').addEventListener('click', function() {
            newRow.remove();
        });
    });
</script>
@endpush
@endsection

