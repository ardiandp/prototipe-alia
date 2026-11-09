@extends('layouts.app')

@section('title', 'Dashboard Keuangan & SPP')

@section('content_header')
    <h1>
        <i class="fa fa-line-chart text-purple"></i> Dashboard Keuangan & SPP
        <small>Arus Kas Masuk & Keluar, Penerimaan Pembayaran Kasir, & Rasio Piutang SPP</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Dashboard</li>
    </ol>
@stop

@section('content')
<!-- Filter Bar: Unit Sekolah & Periode Buku -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.dashboard') }}" method="GET" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-university"></i> Unit Sekolah:</label>
                <select name="unit_id" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Unit Pendidikan --</option>
                    @foreach($units as $u)
                        <option value="{{ $u['id'] }}" {{ $unitFilter === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-calendar"></i> Periode Bulan:</label>
                <select name="period" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="2026-11" {{ $periodFilter === '2026-11' ? 'selected' : '' }}>November 2026 (Aktif)</option>
                    <option value="2026-10" {{ $periodFilter === '2026-10' ? 'selected' : '' }}>Oktober 2026</option>
                    <option value="2026-09" {{ $periodFilter === '2026-09' ? 'selected' : '' }}>September 2026</option>
                </select>
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Perbarui Tampilan
            </button>
            <a href="{{ route('keuangan.dashboard') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>
            <button type="button" class="btn btn-default btn-sm pull-right" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Laporan Keuangan
            </button>
        </form>
    </div>
</div>

<!-- 4 Kotak KPI Utama Keuangan -->
<div class="row">
    <!-- Stat 1: Total Penerimaan Kasir -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Penerimaan Kas</span>
                <span class="info-box-number">Rp {{ number_format($totalCollected, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $collectionRate }}%"></div></div>
                <span class="progress-description">{{ $collectionRate }}% Terkumpul dari Target</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Pengeluaran Operasional -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pengeluaran & Beban</span>
                <span class="info-box-number">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 70%"></div></div>
                <span class="progress-description">Dapur, Listrik, & Operasional</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Surplus / Saldo Kas Bersih -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Saldo Kas Bersih</span>
                <span class="info-box-number">Rp {{ number_format($netBalance, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Arus Kas Operasional Positif</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Total Piutang / Tunggakan SPP -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-exclamation-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tunggakan Belum Lunas</span>
                <span class="info-box-number">Rp {{ number_format($totalArrears, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: {{ 100 - $collectionRate }}%"></div></div>
                <span class="progress-description">{{ $countBelumLunas + $countJatuhTempo + $countSebagian }} Invoice Pending</span>
            </div>
        </div>
    </div>
</div>

<!-- Baris Analisis & Komposisi Status Tagihan -->
<div class="row">
    <!-- Kolom Kiri: Breakdown Status Pembayaran Invoice Santri -->
    <div class="col-md-4">
        <div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
                    <i class="fa fa-pie-chart text-purple"></i> Status Pembayaran Invoice
                </h3>
            </div>
            <div class="box-body">
                <ul class="list-group list-group-unbordered" style="margin-bottom: 0;">
                    <li class="list-group-item">
                        <span class="badge bg-green" style="font-size: 13px;">{{ $countLunas }} Invoice</span>
                        <i class="fa fa-check-circle text-green"></i> <strong>Lunas Penuh</strong>
                    </li>
                    <li class="list-group-item">
                        <span class="badge bg-blue" style="font-size: 13px;">{{ $countSebagian }} Invoice</span>
                        <i class="fa fa-clock-o text-blue"></i> <strong>Dicicil / Sebagian</strong>
                    </li>
                    <li class="list-group-item">
                        <span class="badge bg-yellow" style="font-size: 13px;">{{ $countBelumLunas }} Invoice</span>
                        <i class="fa fa-hourglass-half text-yellow"></i> <strong>Belum Terbayar</strong>
                    </li>
                    <li class="list-group-item">
                        <span class="badge bg-red" style="font-size: 13px;">{{ $countJatuhTempo }} Invoice</span>
                        <i class="fa fa-warning text-red"></i> <strong>Jatuh Tempo / Menunggak</strong>
                    </li>
                </ul>

                <div class="well well-sm" style="margin-top: 20px; margin-bottom: 0; background-color: #f7fafc; font-size: 12px; color: #4a5568;">
                    <i class="fa fa-info-circle text-purple"></i> <strong>Simulasi Transaksi:</strong> Seluruh penerimaan terhubung dengan Virtual Account BSI, QRIS Dinamis, dan Loket Kasir Sekolah tanpa database SQL.
                </div>
            </div>
        </div>

        <!-- Box Pengeluaran Terbaru -->
        <div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #e53e3e;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
                    <i class="fa fa-arrow-circle-down text-red"></i> Pengeluaran Operasional
                </h3>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped table-condensed">
                    <thead>
                        <tr style="background-color: #edf2f7; font-size: 11px;">
                            <th>Uraian Beban</th>
                            <th class="text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredExpenses as $exp)
                        <tr>
                            <td>
                                <strong style="font-size: 12px; color: #2d3748;">{{ $exp['title'] }}</strong><br>
                                <small class="text-muted"><i class="fa fa-tag"></i> {{ $exp['category'] }} | {{ $exp['date'] }}</small>
                            </td>
                            <td class="text-right" style="vertical-align: middle;">
                                <strong class="text-red">Rp {{ number_format($exp['amount'], 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Daftar Transaksi Masuk & Tagihan Terbaru -->
    <div class="col-md-8">
        <!-- Transaksi Penerimaan Terbaru -->
        <div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #2b6cb0; margin-bottom: 20px;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
                    <i class="fa fa-money text-blue"></i> Penerimaan Pembayaran Kasir Terkini
                </h3>
                <div class="box-tools pull-right">
                    <span class="label label-primary">{{ count($filteredTransactions) }} Transaksi Masuk</span>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-striped">
                    <thead style="background-color: #1a365d; color: #fff;">
                        <tr>
                            <th>No. Kuitansi</th>
                            <th>Nama Santri</th>
                            <th>Waktu & Metode Bayar</th>
                            <th class="text-right">Jumlah Dibayar</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $trx)
                        <tr>
                            <td style="vertical-align: middle;">
                                <code style="color: #6b46c1; font-weight: bold;">{{ $trx['receipt_number'] }}</code>
                            </td>
                            <td style="vertical-align: middle;">
                                <strong style="color: #1a365d;">{{ $trx['student_name'] }}</strong><br>
                                <small class="text-muted">Kelas: {{ $trx['class_name'] }}</small>
                            </td>
                            <td style="vertical-align: middle; font-size: 12px;">
                                <i class="fa fa-calendar-check-o text-muted"></i> {{ $trx['payment_date'] }}<br>
                                <span class="label label-default" style="font-weight: normal;"><i class="fa fa-credit-card"></i> {{ $trx['payment_method'] }}</span>
                            </td>
                            <td class="text-right" style="vertical-align: middle;">
                                <strong class="text-green" style="font-size: 14px;">Rp {{ number_format($trx['amount'], 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="label label-success"><i class="fa fa-check"></i> {{ strtoupper($trx['status']) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 20px;">
                                Belum ada catatan transaksi pembayaran pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Invoice Tagihan Santri Terkini -->
        <div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #319795;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
                    <i class="fa fa-file-invoice-dollar text-teal"></i> Daftar Tagihan Santri Aktif
                </h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-bordered table-striped">
                    <thead style="background-color: #edf2f7;">
                        <tr>
                            <th>Nomor Invoice</th>
                            <th>Santri & Kelas</th>
                            <th>Pos Tagihan Biaya</th>
                            <th class="text-right">Nominal Tagihan</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $inv)
                        <tr>
                            <td style="vertical-align: middle;">
                                <code style="color: #2b6cb0;">{{ $inv['invoice_number'] }}</code>
                            </td>
                            <td style="vertical-align: middle;">
                                <strong>{{ $inv['student_name'] }}</strong><br>
                                <small class="text-muted">NISN: {{ $inv['nisn'] }}</small>
                            </td>
                            <td style="vertical-align: middle; font-size: 12px;">
                                {{ $inv['fee_title'] }}
                            </td>
                            <td class="text-right" style="vertical-align: middle;">
                                <strong>Rp {{ number_format($inv['amount'], 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center" style="vertical-align: middle; font-size: 12px;">
                                {{ $inv['due_date'] }}
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 20px;">
                                Belum ada tagihan santri tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

