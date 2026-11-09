@extends('layouts.app')

@section('title', 'Penerimaan Pembayaran Kasir')

@section('content_header')
    <h1>
        <i class="fa fa-credit-card text-purple"></i> Simulasi Penerimaan Pembayaran Kasir & Virtual Account
        <small>Pencatatan Transaksi Kasir, Validasi Pembayaran, Kuitansi Resmi, & Update Otomatis Invoice</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Penerimaan Pembayaran</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Statistik Penerimaan Pembayaran -->
<div class="row">
    <!-- Stat 1: Total Transaksi -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Penerimaan Kas</span>
                <span class="info-box-number">Rp {{ number_format($totalNominalTrx, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">{{ $totalTrx }} Kuitansi Pembayaran Terverifikasi</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Virtual Account BSI -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-university"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">VA Bank Syariah (BSI)</span>
                <span class="info-box-number">{{ $countVaBsi }} <small style="color:#eee; font-weight:normal;">Transaksi</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTrx > 0 ? round(($countVaBsi / $totalTrx) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Integrasi Virtual Account Dinamis</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Loket Kasir Tunai -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kasir Tunai / Loket</span>
                <span class="info-box-number">{{ $countTunai }} <small style="color:#eee; font-weight:normal;">Transaksi</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTrx > 0 ? round(($countTunai / $totalTrx) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Pembayaran Tunai di TU Sekolah</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: QRIS & Transfer Lainnya -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal">
            <span class="info-box-icon"><i class="fa fa-qrcode"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">QRIS & Transfer Bank</span>
                <span class="info-box-number">{{ $countQris }} <small style="color:#eee; font-weight:normal;">Transaksi</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalTrx > 0 ? round(($countQris / $totalTrx) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Scan QRIS & Bank Muamalat/BCA</span>
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

<!-- Callout Simulasi Pembayaran Prototype -->
<div class="callout callout-info" style="border-left-color: #6b46c1; background-color: #f7fafc !important; color: #2d3748 !important; border: 1px solid #cbd5e0; border-left: 5px solid #6b46c1;">
    <h4 style="color: #6b46c1; font-weight: bold;"><i class="fa fa-info-circle"></i> Catatan Simulasi Prototipe (Demonstrasi Interaktif)</h4>
    <p style="margin-bottom: 0; font-size: 13px;">
        Halaman ini mensimulasikan pencatatan penerimaan kasir sekolah dan validasi gateway Virtual Account. Setiap kali Anda melakukan input transaksi baru, <strong>status invoice santri terkait akan otomatis ter-update (Lunas / Sebagian)</strong> dan saldo penerimaan pada <strong>Dashboard Keuangan</strong> akan bertambah secara realtime menggunakan session storage tanpa database SQL.
    </p>
</div>

<!-- Filter Bar & Aksi Transaksi -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.transaksi') }}" method="GET" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-credit-card"></i> Metode Bayar:</label>
                <select name="method" class="form-control input-sm">
                    <option value="">-- Semua Metode --</option>
                    <option value="VA Bank Syariah Indonesia" {{ $methodFilter === 'VA Bank Syariah Indonesia' ? 'selected' : '' }}>VA Bank Syariah Indonesia (BSI)</option>
                    <option value="Tunai / Kasir Sekolah" {{ $methodFilter === 'Tunai / Kasir Sekolah' ? 'selected' : '' }}>Tunai / Kasir Sekolah</option>
                    <option value="Transfer Bank Muamalat" {{ $methodFilter === 'Transfer Bank Muamalat' ? 'selected' : '' }}>Transfer Bank Muamalat</option>
                    <option value="QRIS Kasir Sekolah" {{ $methodFilter === 'QRIS Kasir Sekolah' ? 'selected' : '' }}>QRIS Kasir Sekolah</option>
                    <option value="VA Bank BCA" {{ $methodFilter === 'VA Bank BCA' ? 'selected' : '' }}>VA Bank BCA</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-calendar"></i> Tanggal:</label>
                <input type="date" name="date" value="{{ $dateFilter }}" class="form-control input-sm">
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="No. kuitansi, nama, kasir..." style="width: 200px;">
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Saring Data
            </button>
            <a href="{{ route('keuangan.transaksi') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>

            <div class="pull-right">
                <button type="button" class="btn btn-purple btn-sm" data-toggle="modal" data-target="#modal-simulasi-bayar">
                    <i class="fa fa-plus-circle"></i> Catat Pembayaran Baru (Simulasi)
                </button>
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Rekap Kuitansi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat Pembayaran Kasir -->
<div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
            <i class="fa fa-history text-purple"></i> Log Kuitansi Penerimaan Pembayaran Kasir
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($enrichedTransactions) }} Transaksi Ditampilkan</span>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #1a365d; color: #fff;">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Nomor Kuitansi</th>
                    <th>Nama Santri & Rombel</th>
                    <th>Invoice & Uraian Tagihan</th>
                    <th class="text-right">Nominal Bayar (Rp)</th>
                    <th>Kanal / Metode Bayar</th>
                    <th>Waktu & Kasir Penerima</th>
                    <th class="text-center">Status</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrichedTransactions as $idx => $trx)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                        <code style="color: #6b46c1; font-weight: bold; font-size: 13px;">{{ $trx['receipt_number'] }}</code><br>
                        <small class="text-muted"><i class="fa fa-barcode"></i> Valid Trx</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 14px;">{{ $trx['student_name'] }}</strong><br>
                        <small class="text-muted">NISN: {{ $trx['nisn'] }} | Kelas: {{ $trx['class_name'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <code style="color: #2b6cb0;">{{ $trx['invoice_number'] }}</code><br>
                        <small style="color: #4a5568;">{{ $trx['fee_title'] }}</small>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <strong class="text-green" style="font-size: 15px;">
                            Rp {{ number_format($trx['amount'], 0, ',', '.') }}
                        </strong>
                    </td>
                    <td style="vertical-align: middle;">
                        <span class="label label-default" style="font-size: 12px; font-weight: normal;">
                            <i class="fa fa-credit-card"></i> {{ $trx['payment_method'] }}
                        </span>
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                        <i class="fa fa-calendar-check-o text-muted"></i> {{ $trx['payment_date'] }}<br>
                        <i class="fa fa-user-circle text-muted"></i> {{ $trx['cashier_name'] }}
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <span class="label label-success"><i class="fa fa-check"></i> BERHASIL</span>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type="button" class="btn btn-xs btn-info" onclick='showReceiptModal(@json($trx))' title="Lihat Kuitansi Resmi">
                            <i class="fa fa-print"></i> Kuitansi
                        </button>
                        <a href="{{ route('keuangan.transaksi.destroy', $trx['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Batalkan transaksi kuitansi {{ $trx['receipt_number'] }}?')" title="Batalkan Pembayaran">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-credit-card fa-2x"></i><br>
                        Belum ada riwayat transaksi pembayaran kasir yang cocok dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: Simulasi Catat Pembayaran Tagihan Baru -->
<div class="modal fade" id="modal-simulasi-bayar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('keuangan.transaksi.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Input Simulasi Pembayaran Tagihan Santri</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Tagihan Invoice Santri <span class="text-red">*</span></label>
                        <select name="invoice_id" id="sel_invoice" class="form-control" required onchange="updateInvoicePayAmount()">
                            <option value="">-- Pilih Invoice yang Belum Lunas --</option>
                            @foreach($pendingInvoices as $pInv)
                                @php
                                    $stName = $studentMap[$pInv['student_id']]['name'] ?? 'Santri';
                                    $sisa = $pInv['amount'] - ($pInv['paid_amount'] ?? 0);
                                @endphp
                                <option value="{{ $pInv['id'] }}" data-amount="{{ $sisa }}" data-title="{{ $pInv['fee_title'] }}" data-student="{{ $stName }}">
                                    [{{ $pInv['invoice_number'] }}] {{ $stName }} — Sisa: Rp {{ number_format($sisa, 0, ',', '.') }} ({{ $pInv['fee_title'] }})
                                </option>
                            @endforeach
                        </select>
                        <small class="help-block text-muted">Hanya menampilkan invoice berstatus Belum Lunas, Sebagian, atau Jatuh Tempo.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Jumlah yang Dibayarkan (Rp) <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="number" name="amount" id="input_pay_amount" class="form-control" placeholder="1850000" min="1000" required>
                                </div>
                                <small class="help-block text-muted">Dapat membayar penuh atau sebagian (cicilan).</small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Kanal Pembayaran <span class="text-red">*</span></label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="VA Bank Syariah Indonesia">VA Bank Syariah (BSI)</option>
                                    <option value="Tunai / Kasir Sekolah">Tunai / Loket Kasir</option>
                                    <option value="Transfer Bank Muamalat">Transfer Bank Muamalat</option>
                                    <option value="QRIS Kasir Sekolah">QRIS Kasir Dinamis</option>
                                    <option value="VA Bank BCA">VA Bank BCA</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Petugas Kasir / Sistem Penerima <span class="text-red">*</span></label>
                        <input type="text" name="cashier_name" class="form-control" value="Hj. Nurul Hidayati, S.E. (Bendahara Utama)" required>
                    </div>

                    <div class="form-group">
                        <label>Catatan Transaksi</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Pembayaran pelunasan SPP via transfer online, telah divalidasi mutasi bank."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-check-circle"></i> Proses Pembayaran & Validasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Kuitansi Bukti Pembayaran Resmi (Printable Receipt) -->
<div class="modal fade" id="modal-receipt-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-print"></i> Kuitansi Resmi Pembayaran — Alia Islamic School</h4>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <!-- Kop Kuitansi Resmi -->
                <div class="row" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                    <div class="col-xs-7">
                        <h4 style="margin: 0; font-weight: bold; color: #1a365d;">ALIA ISLAMIC SCHOOL</h4>
                        <small class="text-muted">Badan Pengelola Keuangan & SPP Pesantren</small>
                    </div>
                    <div class="col-xs-5 text-right">
                        <h5 style="color: #6b46c1; font-weight: bold; margin: 0;">BUKTI KUITANSI</h5>
                        <code id="rec_number" style="font-weight: bold; font-size: 13px;">-</code>
                    </div>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th style="width: 35%; background-color: #f7fafc;">Telah Diterima Dari</th>
                        <td id="rec_student" style="font-weight: bold; color: #1a365d;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">NISN & Rombel</th>
                        <td id="rec_nisn_class">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Untuk Pembayaran</th>
                        <td id="rec_title" style="color: #2b6cb0; font-weight: bold;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Referensi No. Invoice</th>
                        <td id="rec_invoice">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Metode & Waktu Bayar</th>
                        <td id="rec_method_date">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Kasir Penerima</th>
                        <td id="rec_cashier">-</td>
                    </tr>
                    <tr style="background-color: #faf5ff;">
                        <th style="background-color: #faf5ff; font-size: 15px; color: #6b46c1;">Jumlah Uang Dibayar</th>
                        <td id="rec_amount" style="font-weight: bold; font-size: 18px; color: #276749;">-</td>
                    </tr>
                </table>

                <div class="well well-sm" style="background-color: #f0fff4; border: 1px solid #c6f6d5; color: #22543d; font-size: 12px; margin-bottom: 0;">
                    <i class="fa fa-check-circle text-green"></i> <strong>Status Terverifikasi:</strong> Kuitansi ini adalah bukti pembayaran sah yang dikeluarkan oleh Sistem Administrasi Keuangan Terpadu Alia Islamic School.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-purple" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Kuitansi Fisik
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function updateInvoicePayAmount() {
        var sel = document.getElementById('sel_invoice');
        var opt = sel.options[sel.selectedIndex];
        var amt = opt.getAttribute('data-amount');
        if (amt) {
            document.getElementById('input_pay_amount').value = amt;
        }
    }

    function showReceiptModal(trx) {
        document.getElementById('rec_number').innerText = trx.receipt_number;
        document.getElementById('rec_student').innerText = trx.student_name + ' (Wali: ' + (trx.guardian_name || '-') + ')';
        document.getElementById('rec_nisn_class').innerText = 'NISN: ' + trx.nisn + ' | Kelas: ' + trx.class_name;
        document.getElementById('rec_title').innerText = trx.fee_title;
        document.getElementById('rec_invoice').innerText = trx.invoice_number;
        document.getElementById('rec_method_date').innerText = trx.payment_method + ' — ' + trx.payment_date;
        document.getElementById('rec_cashier').innerText = trx.cashier_name;
        document.getElementById('rec_amount').innerText = 'Rp ' + Number(trx.amount).toLocaleString('id-ID');

        $('#modal-receipt-detail').modal('show');
    }
</script>
@stop

