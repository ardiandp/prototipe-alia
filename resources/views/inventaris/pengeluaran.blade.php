@extends('layouts.app')

@section('title', 'Pengeluaran & Distribusi Logistik')

@section('content_header')
    <h1>
        <i class="fa fa-paper-plane text-primary"></i> Pengeluaran & Distribusi Barang
        <small>Penerbitan Bon Permintaan Pengeluaran Barang (BPPB), Unit Pemohon & Pengurangan Stok Otomatis</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Inventaris & Logistik</li>
        <li class="active">Pengeluaran Keluar</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-send"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Transaksi Keluar</span>
                <span class="info-box-number">{{ $totalTransactions }} Bon (BPPB)</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Distribusi logistik tercatat</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Volume Barang Keluar</span>
                <span class="info-box-number">{{ number_format($totalItemsIssued) }} Unit/Pcs</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Telah diserahterimakan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Alokasi Unit SMA</span>
                <span class="info-box-number">{{ $smaCount }} Distribusi</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTransactions > 0 ? round(($smaCount / $totalTransactions) * 100) : 0 }}%"></div></div>
                <span class="progress-description">KBM, Ujian & OSIS SMA</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Alokasi SMP & Asrama</span>
                <span class="info-box-number">{{ $smpCount + count(array_filter($issues, fn($i) => ($i['unit_id'] ?? '') === 'YAYASAN')) }} Distribusi</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTransactions > 0 ? round((($smpCount + 1) / $totalTransactions) * 100) : 0 }}%"></div></div>
                <span class="progress-description">SMP, Tahfidz & Kebersihan</span>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Buku Jurnal Pengeluaran Barang (Outbound)</a>
                </li>
                <li class="{{ $activeTab === 'rekap_unit' ? 'active' : '' }}">
                    <a href="#tab_rekap_unit" data-toggle="tab"><i class="fa fa-pie-chart text-green"></i> Distribusi Berdasarkan Unit Pemohon</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Buku Jurnal Pengeluaran Barang --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('inventaris.pengeluaran') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Unit Pemohon:</label>
                                    <select name="unit" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $unitFilter === 'all' ? 'selected' : '' }}>-- Semua Unit --</option>
                                        <option value="UNIT-SMA" {{ $unitFilter === 'UNIT-SMA' ? 'selected' : '' }}>SMA Islam Alia</option>
                                        <option value="UNIT-SMP" {{ $unitFilter === 'UNIT-SMP' ? 'selected' : '' }}>SMP Islam Alia</option>
                                        <option value="UNIT-SD" {{ $unitFilter === 'UNIT-SD' ? 'selected' : '' }}>SD Islam Alia</option>
                                        <option value="YAYASAN" {{ $unitFilter === 'YAYASAN' ? 'selected' : '' }}>Yayasan / Asrama</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Tanggal:</label>
                                    <input type="date" name="date" class="form-control input-sm" value="{{ $dateFilter }}" onchange="this.form.submit()">
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="No. Bon, barang, penerima..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('inventaris.pengeluaran', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-tambah-pengeluaran">
                                        <i class="fa fa-plus-circle"></i> Catat Pengeluaran Barang
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Jurnal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Pengeluaran Barang --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>No. Bon BPPB</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Nama Barang & Kode SKU</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th>Penerima & Unit</th>
                                    <th>Tujuan & Keperluan</th>
                                    <th>Petugas Gudang</th>
                                    <th width="110" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredIssues as $iss)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $iss['issue_code'] }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-purple">{{ $iss['request_number'] }}</strong>
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar text-blue"></i> {{ date('d M Y', strtotime($iss['issue_date'])) }}
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $iss['item_name'] }}</strong><br>
                                            <small class="text-muted"><code>{{ $iss['item_code'] }}</code></small>
                                        </td>
                                        <td class="text-center text-bold text-red" style="font-size: 13px;">
                                            -{{ $iss['quantity'] }} {{ $iss['unit'] }}
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> <strong>{{ $iss['recipient_name'] }}</strong><br>
                                            <small class="text-muted">{{ $iss['recipient_role'] }}</small><br>
                                            <span class="label label-info" style="font-size: 10px;">{{ $iss['unit_id'] }}</span>
                                        </td>
                                        <td>
                                            <small><i class="fa fa-map-marker text-red"></i> {{ $iss['destination'] }}</small><br>
                                            <span class="text-muted" style="font-size: 11px;">{{ $iss['purpose'] }}</span>
                                        </td>
                                        <td>
                                            <small><i class="fa fa-shield"></i> {{ $iss['officer_name'] }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-iss-{{ $iss['id'] }}" title="Lihat Bon BPPB">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                <a href="{{ route('inventaris.pengeluaran.destroy', $iss['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Bon Permintaan Pengeluaran Barang (BPPB) Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-iss-{{ $iss['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Bon Permintaan & Pengeluaran Barang (BPPB)</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">BAGIAN GUDANG, INVENTARIS & LOGISTIK KAMPUS TERPADU</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">BUKTI PENGELUARAN LOGISTIK GUDANG (BPPB)</h5>
                                                                <small>Nomor Bon: <strong>{{ $iss['request_number'] }}</strong> | Transaksi: <strong>{{ $iss['issue_code'] }}</strong></small>
                                                            </div>

                                                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                                <tr>
                                                                    <th width="35%">Tanggal Pengeluaran</th>
                                                                    <td>{{ date('d F Y', strtotime($iss['issue_date'])) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nama Barang Diserahkan</th>
                                                                    <td><strong class="text-primary">{{ $iss['item_name'] }}</strong> ({{ $iss['item_code'] }})</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jumlah / Kuantitas Dikeluarkan</th>
                                                                    <td class="text-bold text-red" style="font-size: 14px;">
                                                                        {{ $iss['quantity'] }} {{ $iss['unit'] }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Unit Pemohon</th>
                                                                    <td><span class="label label-info">{{ $iss['unit_id'] }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Penerima / Pemakai</th>
                                                                    <td><strong>{{ $iss['recipient_name'] }}</strong> ({{ $iss['recipient_role'] }})</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tujuan Penempatan Fisik</th>
                                                                    <td><i class="fa fa-map-marker text-red"></i> {{ $iss['destination'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Keperluan / Alasan Penggunaan</th>
                                                                    <td>{{ $iss['purpose'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Petugas Penyerah Gudang</th>
                                                                    <td>{{ $iss['officer_name'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Catatan Khusus</th>
                                                                    <td><small class="text-muted">{{ $iss['notes'] }}</small></td>
                                                                </tr>
                                                            </table>

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Yang Meminta / Menerima,</small><br><br><br>
                                                                    <strong>({{ $iss['recipient_name'] }})</strong>
                                                                </div>
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Petugas Gudang / Penyerah,</small><br><br><br>
                                                                    <strong>({{ $iss['officer_name'] }})</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Bon BPPB Resmi</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-paper-plane fa-2x"></i><br>
                                            Tidak ada catatan pengeluaran barang yang cocok dengan filter pencarian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Distribusi Berdasarkan Unit Pemohon --}}
                <div class="tab-pane {{ $activeTab === 'rekap_unit' ? 'active' : '' }}" id="tab_rekap_unit">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-pie-chart text-green"></i> Distribusi Logistik Berdasarkan Unit Pemohon</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Unit Pemohon</th>
                                                <th class="text-center">Jumlah Bon (BPPB)</th>
                                                <th class="text-center">Total Volume Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unitSummary as $us)
                                                <tr>
                                                    <td><strong>{{ $us['label'] }}</strong></td>
                                                    <td class="text-center">{{ $us['count'] }} kali</td>
                                                    <td class="text-center text-bold text-red">-{{ $us['quantity'] }} unit/pcs</td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-gray-light text-bold">
                                                <td>TOTAL LOGISTIK KELUAR</td>
                                                <td class="text-center">{{ $totalTransactions }} kali</td>
                                                <td class="text-center text-red">-{{ $totalItemsIssued }} unit/pcs</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-shield text-primary"></i> Prosedur Bon Pengeluaran Barang (BPPB)</h4>
                            <div class="callout callout-info">
                                <h4>SOP Pengeluaran Barang Gudang</h4>
                                <p>Setiap pengeluaran barang logistik dari gudang wajib menggunakan formulir Bon BPPB yang ditandatangani oleh pemohon (guru/wali kelas/staf) serta disetujui oleh Kepala Unit masing-masing guna mencegah kebocoran aset persediaan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Catat Pengeluaran Barang Baru --}}
<div class="modal fade" id="modal-tambah-pengeluaran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventaris.pengeluaran.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Catat Pengeluaran & Distribusi Barang (Outbound Logistik)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Barang Yang Dikeluarkan: <span class="text-danger">*</span></label>
                                <select name="item_id" class="form-control" required id="select-outbound-item" onchange="updateOutboundItemInfo(this)">
                                    <option value="">-- Pilih Barang dari Gudang --</option>
                                    @foreach($items as $it)
                                        <option value="{{ $it['id'] }}" data-unit="{{ $it['unit'] }}" data-stock="{{ $it['stock'] }}">
                                            {{ $it['name'] }} (Stok tersedia: {{ $it['stock'] }} {{ $it['unit'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nomor Bon Permintaan (BPPB): <span class="text-danger">*</span></label>
                                <input type="text" name="request_number" class="form-control" placeholder="Contoh: BON/BPPB/2026/015" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengeluaran: <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah / Kuantitas Diminta: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="quantity" class="form-control" min="1" value="1" required id="input-outbound-qty">
                                    <span class="input-group-addon" id="span-outbound-unit">Unit</span>
                                </div>
                                <small class="text-muted" id="text-available-stock"><i class="fa fa-info-circle"></i> Stok fisik akan otomatis dikurangi setelah disimpan.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Penerima / Pemohon: <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name" class="form-control" placeholder="Contoh: Ust. Ahmad Fauzi, S.Pd." required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Penerima: <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_role" class="form-control" placeholder="Contoh: Wakasek Kurikulum / Pembina OSIS" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Sekolah Penerima: <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="UNIT-SMA">SMA Islam Alia</option>
                                    <option value="UNIT-SMP">SMP Islam Alia</option>
                                    <option value="UNIT-SD">SD Islam Alia</option>
                                    <option value="YAYASAN">Yayasan / Pesantren Terpadu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tujuan Penempatan Ruangan: <span class="text-danger">*</span></label>
                                <input type="text" name="destination" class="form-control" placeholder="Contoh: Ruang Kurikulum / Lab Sains / Asrama Putra" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Petugas Gudang / Penyerah: <span class="text-danger">*</span></label>
                                <input type="text" name="officer_name" class="form-control" value="H. Mulyadi (Staf Gudang & Logistik)" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keperluan / Peruntukan Barang: <span class="text-danger">*</span></label>
                                <input type="text" name="purpose" class="form-control" placeholder="Contoh: Penggandaan naskah soal asesmen sumatif semester ganjil" required>
                            </div>
                            <div class="form-group">
                                <label>Catatan Tambahan:</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan kondisi saat serah terima atau estimasi pengembalian jika bersifat pinjam..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Terbitkan Bon & Kurangi Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateOutboundItemInfo(select) {
        var opt = select.options[select.selectedIndex];
        var unit = opt.getAttribute('data-unit');
        var stock = opt.getAttribute('data-stock');
        if (unit) {
            document.getElementById('span-outbound-unit').textContent = unit;
        }
        if (stock) {
            document.getElementById('input-outbound-qty').setAttribute('max', stock);
            document.getElementById('text-available-stock').innerHTML = '<i class="fa fa-info-circle text-navy"></i> Maksimal pengambilan: <strong>' + stock + ' ' + unit + '</strong> (sesuai sisa stok fisik).';
        }
    }
</script>
@endpush
@endsection
