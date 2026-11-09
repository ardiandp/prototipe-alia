@extends('layouts.app')

@section('title', 'Tagihan & Invoice Santri')

@section('content_header')
    <h1>
        <i class="fa fa-file-invoice-dollar text-purple"></i> Manajemen Tagihan & Invoice Santri
        <small>Penerbitan Tagihan SPP, Status Pembayaran, Jatuh Tempo, & Cetak Invoice</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Tagihan Santri</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Statistik Tagihan -->
<div class="row">
    <!-- Stat 1: Total Invoice Diterbitkan -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-files-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Tagihan Diterbitkan</span>
                <span class="info-box-number">{{ $totalTagihan }} <small style="color:#eee; font-weight:normal;">Invoice</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Rp {{ number_format($nominalTotalTagihan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Invoice Lunas -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Invoice Telah Lunas</span>
                <span class="info-box-number">{{ $totalLunas }} <small style="color:#eee; font-weight:normal;">Invoice</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTagihan > 0 ? round(($totalLunas / $totalTagihan) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Pembayaran Selesai Terverifikasi</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Belum Lunas / Sebagian -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-hourglass-half"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Pembayaran</span>
                <span class="info-box-number">{{ $totalBelumLunas }} <small style="color:#eee; font-weight:normal;">Invoice</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTagihan > 0 ? round(($totalBelumLunas / $totalTagihan) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Belum Lunas / Dicicil</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Nominal Tunggakan Piutang -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sisa Piutang Berjalan</span>
                <span class="info-box-number">Rp {{ number_format($nominalTunggakan, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 80%"></div></div>
                <span class="progress-description">Perlu Ditindaklanjuti</span>
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

<!-- Filter Bar & Aksi Terpadu -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.tagihan') }}" method="GET" class="form-inline">
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
                <label style="color: #1a365d;"><i class="fa fa-check-square-o"></i> Status:</label>
                <select name="status" class="form-control input-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="lunas" {{ $statusFilter === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="sebagian" {{ $statusFilter === 'sebagian' ? 'selected' : '' }}>Sebagian (Cicilan)</option>
                    <option value="belum_lunas" {{ $statusFilter === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="jatuh_tempo" {{ $statusFilter === 'jatuh_tempo' ? 'selected' : '' }}>Jatuh Tempo</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-calendar"></i> Periode Bulan:</label>
                <select name="month" class="form-control input-sm">
                    <option value="">-- Semua Periode --</option>
                    <option value="2026-11" {{ $monthFilter === '2026-11' ? 'selected' : '' }}>November 2026</option>
                    <option value="2026-10" {{ $monthFilter === '2026-10' ? 'selected' : '' }}>Oktober 2026</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="No. invoice, nama santri, NISN..." style="width: 200px;">
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Saring Data
            </button>
            <a href="{{ route('keuangan.tagihan') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>

            <div class="pull-right">
                <button type="button" class="btn btn-purple btn-sm" data-toggle="modal" data-target="#modal-terbitkan-tagihan">
                    <i class="fa fa-plus-circle"></i> Terbitkan Invoice Baru
                </button>
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Rekap Tagihan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Tagihan Santri -->
<div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
            <i class="fa fa-file-text-o text-purple"></i> Daftar Tagihan & Invoice Santri
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($enrichedInvoices) }} Tagihan Ditampilkan</span>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #1a365d; color: #fff;">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Nomor Invoice</th>
                    <th>Nama Santri & Rombel</th>
                    <th>Uraian Pos Tagihan</th>
                    <th class="text-right">Nominal Tagihan</th>
                    <th class="text-right">Terbayar</th>
                    <th class="text-center">Jatuh Tempo</th>
                    <th class="text-center">Status</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrichedInvoices as $idx => $inv)
                @php
                    $sisaTagihan = $inv['amount'] - ($inv['paid_amount'] ?? 0);
                @endphp
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                        <code style="color: #6b46c1; font-weight: bold; font-size: 13px;">{{ $inv['invoice_number'] }}</code><br>
                        <small class="text-muted"><i class="fa fa-calendar"></i> {{ $inv['created_date'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 14px;">{{ $inv['student_name'] }}</strong><br>
                        <small class="text-muted">NISN: {{ $inv['nisn'] }} | Kelas: {{ $inv['class_name'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong>{{ $inv['fee_title'] }}</strong><br>
                        <small class="text-muted">{{ $inv['unit_name'] }}</small>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 14px;">Rp {{ number_format($inv['amount'], 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        @if($inv['paid_amount'] > 0)
                            <strong class="text-green">Rp {{ number_format($inv['paid_amount'], 0, ',', '.') }}</strong>
                        @else
                            <span class="text-muted">Rp 0</span>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle; font-size: 12px;">
                        @if($inv['status'] === 'jatuh_tempo')
                            <span class="text-red" style="font-weight: bold;"><i class="fa fa-warning"></i> {{ $inv['due_date'] }}</span>
                        @else
                            <i class="fa fa-clock-o text-muted"></i> {{ $inv['due_date'] }}
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if($inv['status'] === 'lunas')
                            <span class="label label-success"><i class="fa fa-check"></i> LUNAS</span>
                        @elseif($inv['status'] === 'sebagian')
                            <span class="label label-info"><i class="fa fa-clock-o"></i> SEBAGIAN</span>
                        @elseif($inv['status'] === 'belum_lunas')
                            <span class="label label-warning"><i class="fa fa-hourglass-half"></i> BELUM LUNAS</span>
                        @else
                            <span class="label label-danger"><i class="fa fa-warning"></i> JATUH TEMPO</span>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type="button" class="btn btn-xs btn-info" onclick='showInvoiceDetail(@json($inv))' title="Lihat Tagihan Resmi">
                            <i class="fa fa-eye"></i> Invoice
                        </button>
                        <a href="{{ route('keuangan.tagihan.destroy', $inv['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Batalkan invoice {{ $inv['invoice_number'] }}?')" title="Batalkan Tagihan">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-file-invoice-dollar fa-2x"></i><br>
                        Belum ada data tagihan santri yang cocok dengan kriteria filter pencarian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: Terbitkan Invoice Tagihan Baru -->
<div class="modal fade" id="modal-terbitkan-tagihan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('keuangan.tagihan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Terbitkan Invoice Tagihan Santri Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Santri Sasaran <span class="text-red">*</span></label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri Terdaftar --</option>
                            @foreach($students as $st)
                                <option value="{{ $st['id'] }}">
                                    {{ $st['name'] }} (NISN: {{ $st['nisn'] ?? $st['nis'] }}) — {{ $st['unit_level'] ?? 'Santri' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Pilih Pos Biaya & Tarif <span class="text-red">*</span></label>
                                <select name="fee_category_id" id="input_fee_category" class="form-control" required onchange="updateFeeAmount()">
                                    <option value="">-- Pilih Pos Biaya --</option>
                                    @foreach($feeCategories as $f)
                                        <option value="{{ $f['id'] }}" data-amount="{{ $f['amount'] }}">
                                            {{ $f['name'] }} — Rp {{ number_format($f['amount'], 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Periode Bulan Buku <span class="text-red">*</span></label>
                                <input type="month" name="period_month" class="form-control" value="2026-11" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nominal Tagihan (Rp) <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="number" name="amount" id="input_amount" class="form-control" placeholder="1850000" min="1000" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Batas Akhir Jatuh Tempo <span class="text-red">*</span></label>
                                <input type="date" name="due_date" class="form-control" value="2026-11-10" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan / Instruksi Pembayaran</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Pembayaran dapat melalui transfer VA BSI atau Loket Kasir Sekolah."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-send"></i> Terbitkan & Simpan Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Surat Invoice Resmi (Printable & Detail) -->
<div class="modal fade" id="modal-invoice-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-invoice-dollar"></i> Surat Invoice Tagihan Pendidikan — Alia Islamic School</h4>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <!-- Header Kop Invoice Resmi -->
                <div class="row" style="border-bottom: 2px solid #1a365d; padding-bottom: 15px; margin-bottom: 20px;">
                    <div class="col-xs-8">
                        <h3 style="margin-top: 0; font-weight: bold; color: #1a365d;">ALIA ISLAMIC SCHOOL</h3>
                        <p style="margin-bottom: 0; font-size: 12px; color: #4a5568;">
                            Kampus Terpadu: Jl. Pendidikan Islam No. 99, Karawang / Jakarta Timur<br>
                            Telepon: (021) 8899-7711 | Email: keuangan@alia.sch.id | Website: www.alia.sch.id
                        </p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <h4 style="color: #6b46c1; font-weight: bold; margin-top: 0;">INVOICE TAGIHAN</h4>
                        <code id="inv_dt_number" style="font-size: 14px; font-weight: bold;">-</code><br>
                        <small class="text-muted">Tanggal: <span id="inv_dt_created">-</span></small>
                    </div>
                </div>

                <!-- Informasi Pembayar & Tagihan -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-xs-6">
                        <h5 style="font-weight: bold; color: #718096; text-transform: uppercase; margin-bottom: 5px;">Ditagihkan Kepada:</h5>
                        <strong id="inv_dt_student" style="font-size: 16px; color: #1a365d;">-</strong><br>
                        <span class="text-muted">NISN: <span id="inv_dt_nisn">-</span> | Kelas: <span id="inv_dt_class">-</span></span><br>
                        <span class="text-muted">Wali Santri: <span id="inv_dt_guardian">-</span> (<span id="inv_dt_phone">-</span>)</span>
                    </div>
                    <div class="col-xs-6 text-right">
                        <h5 style="font-weight: bold; color: #718096; text-transform: uppercase; margin-bottom: 5px;">Status & Jatuh Tempo:</h5>
                        <div id="inv_dt_badge" style="margin-bottom: 5px;">-</div>
                        <span class="text-muted">Jatuh Tempo: <strong id="inv_dt_due" class="text-red">-</strong></span><br>
                        <span class="text-muted">Periode: <span id="inv_dt_period">-</span></span>
                    </div>
                </div>

                <!-- Tabel Item Tagihan -->
                <table class="table table-bordered table-striped" style="margin-bottom: 20px;">
                    <thead>
                        <tr style="background-color: #f7fafc; color: #1a365d;">
                            <th>Uraian Deskripsi Tagihan</th>
                            <th class="text-right" style="width: 25%;">Jumlah Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong id="inv_dt_title" style="color: #2b6cb0; font-size: 14px;">-</strong><br>
                                <small id="inv_dt_notes" class="text-muted">-</small>
                            </td>
                            <td class="text-right" style="vertical-align: middle;">
                                <strong id="inv_dt_amount" style="font-size: 15px;">-</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Telah Dibayarkan (Kredit):</strong></td>
                            <td class="text-right text-green"><strong id="inv_dt_paid">-</strong></td>
                        </tr>
                        <tr style="background-color: #faf5ff;">
                            <td class="text-right" style="font-size: 15px;"><strong>SISA PIUTANG YANG HARUS DIBAYAR:</strong></td>
                            <td class="text-right" style="font-size: 16px; color: #6b46c1;"><strong id="inv_dt_remaining">-</strong></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Petunjuk Pembayaran -->
                <div class="well well-sm" style="background-color: #ebf8ff; border: 1px solid #bee3f8; color: #2c5282; font-size: 12px; margin-bottom: 0;">
                    <i class="fa fa-info-circle"></i> <strong>Instruksi Pembayaran Digital:</strong>
                    Transfer melalui <strong>Bank Syariah Indonesia (BSI) Virtual Account: 98888 + NISN Santri</strong> atau tunai di loket Tata Usaha Keuangan Alia Islamic School.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-purple" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Invoice Resmi
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function updateFeeAmount() {
        var sel = document.getElementById('input_fee_category');
        var opt = sel.options[sel.selectedIndex];
        var amt = opt.getAttribute('data-amount');
        if (amt) {
            document.getElementById('input_amount').value = amt;
        }
    }

    function showInvoiceDetail(inv) {
        document.getElementById('inv_dt_number').innerText = inv.invoice_number;
        document.getElementById('inv_dt_created').innerText = inv.created_date;
        document.getElementById('inv_dt_student').innerText = inv.student_name;
        document.getElementById('inv_dt_nisn').innerText = inv.nisn;
        document.getElementById('inv_dt_class').innerText = inv.class_name;
        document.getElementById('inv_dt_guardian').innerText = inv.guardian_name;
        document.getElementById('inv_dt_phone').innerText = inv.guardian_phone;
        document.getElementById('inv_dt_due').innerText = inv.due_date;
        document.getElementById('inv_dt_period').innerText = inv.period_month;
        document.getElementById('inv_dt_title').innerText = inv.fee_title;
        document.getElementById('inv_dt_notes').innerText = inv.notes || '-';
        document.getElementById('inv_dt_amount').innerText = 'Rp ' + Number(inv.amount).toLocaleString('id-ID');
        document.getElementById('inv_dt_paid').innerText = 'Rp ' + Number(inv.paid_amount || 0).toLocaleString('id-ID');

        var remaining = inv.amount - (inv.paid_amount || 0);
        document.getElementById('inv_dt_remaining').innerText = 'Rp ' + Number(remaining).toLocaleString('id-ID');

        var badgeHtml = '';
        if (inv.status === 'lunas') {
            badgeHtml = '<span class="label label-success" style="font-size: 13px;"><i class="fa fa-check"></i> LUNAS</span>';
        } else if (inv.status === 'sebagian') {
            badgeHtml = '<span class="label label-info" style="font-size: 13px;"><i class="fa fa-clock-o"></i> SEBAGIAN</span>';
        } else if (inv.status === 'belum_lunas') {
            badgeHtml = '<span class="label label-warning" style="font-size: 13px;"><i class="fa fa-hourglass-half"></i> BELUM LUNAS</span>';
        } else {
            badgeHtml = '<span class="label label-danger" style="font-size: 13px;"><i class="fa fa-warning"></i> JATUH TEMPO</span>';
        }
        document.getElementById('inv_dt_badge').innerHTML = badgeHtml;

        $('#modal-invoice-detail').modal('show');
    }
</script>
@stop

