@extends('layouts.app')

@section('title', 'Laporan Keuangan Sekolah')

@section('content_header')
    <h1>
        <i class="fa fa-balance-scale text-purple"></i> Pusat Laporan Keuangan Terpadu
        <small>Rekapitulasi Penerimaan SPP, Laporan Piutang & Tunggakan, Arus Kas (Cash Flow), & Kinerja Unit</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Laporan Keuangan</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Eksekutif Keuangan -->
<div class="row">
    <!-- Stat 1: Total Penerimaan Periode Ini -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Penerimaan SPP & Kas</span>
                <span class="info-box-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">{{ count($collectionsReport) }} Transaksi Masuk</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Sisa Tunggakan Periode Ini -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sisa Piutang Berjalan</span>
                <span class="info-box-number">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 70%"></div></div>
                <span class="progress-description">{{ count($arrearsReport) }} Tagihan Belum Selesai</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Arus Kas Bersih (Net Cash Flow) -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box {{ $netOperatingCash >= 0 ? 'bg-navy' : 'bg-orange' }}">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Arus Kas Bersih (Net)</span>
                <span class="info-box-number">Rp {{ number_format($netOperatingCash, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 85%"></div></div>
                <span class="progress-description">Debit: Rp {{ number_format($totalDebit, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Beban Operasional Kredit -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Beban Operasional</span>
                <span class="info-box-number">Rp {{ number_format($totalKredit, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 90%"></div></div>
                <span class="progress-description">Dapur, Utilitas, Pemeliharaan</span>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi 4 Tab Laporan Keuangan Terpadu -->
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li><a href="#tab-unit" data-toggle="tab"><i class="fa fa-university"></i> Ringkasan per Unit</a></li>
                <li><a href="#tab-cashflow" data-toggle="tab"><i class="fa fa-exchange"></i> Arus Kas (Cash Flow)</a></li>
                <li><a href="#tab-tunggakan" data-toggle="tab"><i class="fa fa-clock-o"></i> Laporan Piutang</a></li>
                <li class="active"><a href="#tab-penerimaan" data-toggle="tab"><i class="fa fa-check-square-o"></i> Rekap Penerimaan SPP</a></li>
                <li class="pull-left header">
                    <span style="font-size: 18px; font-weight: bold; color: #1a365d;">
                        <i class="fa fa-file-text text-purple"></i> Laporan Pertanggungjawaban Keuangan Pesantren
                    </span>
                </li>
            </ul>

            <div class="tab-content" style="padding: 20px;">
                <!-- TAB 1: REKAP PENERIMAAN SPP & KASIR -->
                <div class="tab-pane active" id="tab-penerimaan">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-download text-green"></i> Laporan Penerimaan Pembayaran Santri
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Rekapitulasi transaksi kasir masuk melalui loket tunai, Virtual Account BSI, dan kanal QRIS resmi.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Laporan Penerimaan
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Nomor Kuitansi</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Nama Santri & Rombel</th>
                                    <th>Metode Pembayaran</th>
                                    <th class="text-right">Jumlah Dibayar (Rp)</th>
                                    <th>Petugas Kasir / Sistem</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collectionsReport as $idx => $c)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;">
                                        <code style="color: #6b46c1; font-weight: bold;">{{ $c['receipt_number'] }}</code>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">{{ $c['payment_date'] }}</td>
                                    <td style="vertical-align: middle;">
                                        <strong style="color: #1a365d;">{{ $c['student_name'] }}</strong><br>
                                        <small class="text-muted">NISN: {{ $c['nisn'] }} | Kelas: {{ $c['class_name'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <span class="label label-default" style="font-weight: normal;">
                                            <i class="fa fa-credit-card"></i> {{ $c['payment_method'] }}
                                        </span>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle;">
                                        <strong class="text-green" style="font-size: 14px;">Rp {{ number_format($c['amount'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">{{ $c['cashier_name'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="label label-success"><i class="fa fa-check"></i> BERHASIL</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                        Belum ada penerimaan pembayaran tercatat pada periode ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f7fafc; font-weight: bold;">
                                    <td colspan="5" class="text-right">TOTAL PENERIMAAN:</td>
                                    <td class="text-right text-green" style="font-size: 15px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: LAPORAN PIUTANG & TUNGGAKAN -->
                <div class="tab-pane" id="tab-tunggakan">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-warning text-red"></i> Laporan Piutang & Tagihan Menunggak
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Rekapitulasi kewajiban SPP dan biaya asrama santri yang belum terselesaikan.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Laporan Piutang
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Nomor Invoice</th>
                                    <th>Nama Santri & Rombel</th>
                                    <th>Wali Santri & Kontak</th>
                                    <th>Uraian Tagihan</th>
                                    <th class="text-right">Sisa Piutang (Rp)</th>
                                    <th class="text-center">Jatuh Tempo</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($arrearsReport as $idx => $arr)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle;"><code style="color: #6b46c1;">{{ $arr['invoice_number'] }}</code></td>
                                    <td style="vertical-align: middle;">
                                        <strong style="color: #1a365d;">{{ $arr['student_name'] }}</strong><br>
                                        <small class="text-muted">NISN: {{ $arr['nisn'] }} | Kelas: {{ $arr['class_name'] }}</small>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 12px;">
                                        <strong>{{ $arr['guardian_name'] }}</strong><br>
                                        <span class="text-muted">{{ $arr['guardian_phone'] }}</span>
                                    </td>
                                    <td style="vertical-align: middle;">{{ $arr['fee_title'] }}</td>
                                    <td class="text-right" style="vertical-align: middle;">
                                        <strong class="text-red" style="font-size: 14px;">Rp {{ number_format($arr['unpaid_amount'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle; font-size: 12px;">{{ $arr['due_date'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        @if($arr['status'] === 'jatuh_tempo')
                                            <span class="label label-danger">JATUH TEMPO</span>
                                        @elseif($arr['status'] === 'sebagian')
                                            <span class="label label-info">SEBAGIAN</span>
                                        @else
                                            <span class="label label-warning">BELUM LUNAS</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                        Tidak ada tunggakan aktif pada periode ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f7fafc; font-weight: bold;">
                                    <td colspan="5" class="text-right">TOTAL SISA PIUTANG:</td>
                                    <td class="text-right text-red" style="font-size: 15px;">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: ARUS KAS (CASH FLOW IN/OUT) -->
                <div class="tab-pane" id="tab-cashflow">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-exchange text-blue"></i> Laporan Arus Kas Operasional (Cash Flow Statement)
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Komparasi mutasi debit (penerimaan kas) dan kredit (belanja operasional) periode berjalan.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Arus Kas
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>Tanggal</th>
                                    <th>No. Ref</th>
                                    <th>Uraian Mutasi</th>
                                    <th>Pos Akun</th>
                                    <th class="text-right" style="width: 150px;">Kas Masuk / Debit (Rp)</th>
                                    <th class="text-right" style="width: 150px;">Kas Keluar / Kredit (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashFlowReport as $idx => $cf)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: middle; font-size: 12px;">{{ $cf['date'] }}</td>
                                    <td style="vertical-align: middle;"><code>{{ $cf['ref'] }}</code></td>
                                    <td style="vertical-align: middle;"><strong>{{ $cf['title'] }}</strong></td>
                                    <td style="vertical-align: middle;"><span class="label label-default">{{ $cf['category'] }}</span></td>
                                    <td class="text-right" style="vertical-align: middle;">
                                        @if($cf['debit'] > 0)
                                            <strong class="text-green">Rp {{ number_format($cf['debit'], 0, ',', '.') }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right" style="vertical-align: middle;">
                                        @if($cf['kredit'] > 0)
                                            <strong class="text-red">Rp {{ number_format($cf['kredit'], 0, ',', '.') }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f7fafc; font-weight: bold;">
                                    <td colspan="5" class="text-right">TOTAL MUTASI ARUS KAS:</td>
                                    <td class="text-right text-green" style="font-size: 15px;">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                                    <td class="text-right text-red" style="font-size: 15px;">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="background-color: #faf5ff; font-weight: bold; font-size: 15px;">
                                    <td colspan="5" class="text-right">SALDO SURPLUS / (DEFISIT) BERSIH:</td>
                                    <td colspan="2" class="text-center" style="color: {{ $netOperatingCash >= 0 ? '#276749' : '#c53030' }};">
                                        Rp {{ number_format($netOperatingCash, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- TAB 4: REKAPITULASI KINERJA PER UNIT -->
                <div class="tab-pane" id="tab-unit">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-sm-8">
                            <h4 style="font-weight: bold; color: #1a365d; margin-top: 0;">
                                <i class="fa fa-university text-purple"></i> Ringkasan Kinerja Keuangan per Unit Pendidikan
                            </h4>
                            <p class="text-muted" style="margin-bottom: 0;">
                                Komparasi target tagihan, realisasi penerimaan, dan tunggakan SD, SMP, & SMA Islam Alia.
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Rekap Unit
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th class="text-left">Unit Lembaga Pendidikan</th>
                                    <th class="text-right">Target Tagihan (Rp)</th>
                                    <th class="text-right">Penerimaan Terkumpul (Rp)</th>
                                    <th class="text-right">Sisa Piutang (Rp)</th>
                                    <th style="width: 180px;">Rasio Kolektibilitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitSummaries as $idx => $us)
                                <tr>
                                    <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td class="text-left" style="vertical-align: middle;">
                                        <strong style="color: #1a365d; font-size: 14px;">{{ $us['unit_name'] }}</strong><br>
                                        <small class="text-muted">Kode: {{ $us['unit_id'] }}</small>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle;">
                                        Rp {{ number_format($us['target'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right text-green" style="vertical-align: middle;">
                                        <strong>Rp {{ number_format($us['collected'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-right text-red" style="vertical-align: middle;">
                                        <strong>Rp {{ number_format($us['arrears'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div class="progress progress-sm" style="margin-bottom: 3px;">
                                            <div class="progress-bar {{ $us['rate'] >= 75 ? 'progress-bar-success' : 'progress-bar-warning' }}" style="width: {{ $us['rate'] }}%"></div>
                                        </div>
                                        <small style="font-weight: bold;">{{ $us['rate'] }}% Tercapai</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
