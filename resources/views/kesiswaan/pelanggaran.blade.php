@extends('layouts.app')

@section('title', 'Poin Pelanggaran & Tata Tertib Santri')

@push('styles')
<style>
    .metric-card-pelanggaran {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-pelanggaran:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-pelanggaran {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 14px;
        float: left;
    }
    .severity-ringan { background-color: #ebf8ff; color: #2b6cb0; border: 1px solid #bee3f8; }
    .severity-sedang { background-color: #fffaf0; color: #c05621; border: 1px solid #feebc8; }
    .severity-berat { background-color: #fff5f5; color: #c53030; border: 1px solid #fed7d7; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xs-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" style="border-radius: 4px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" style="border-radius: 4px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-ban"></i> {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<!-- Header Page -->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-8 col-xs-12">
        <h3 style="margin-top: 0; font-weight: 700; color: #1a365d;">
            <i class="fa fa-shield text-primary"></i> Kesiswaan: Buku Saku Tata Tertib & Poin Pelanggaran Santri
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Pencatatan kasus kedisiplinan santri, akumulasi poin tarhib, klasifikasi sanksi (Ringan, Sedang, Berat), tindak lanjut pembinaan, dan penerbitan Surat Peringatan (SP).
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCatatPelanggaran" style="border-radius: 4px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Catat Kasus Pelanggaran
        </button>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalTambahAturan" style="border-radius: 4px;">
            <i class="fa fa-book text-purple" style="color: #6b46c1;"></i> Tambah Tata Tertib
        </button>
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak
        </button>
    </div>
</div>

<!-- Metrik Statistik Pelanggaran -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-pelanggaran clearfix">
            <div class="metric-icon-pelanggaran" style="background: rgba(229, 62, 62, 0.1); color: #e53e3e;">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Total Kasus</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_cases'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Insiden tercatat</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-pelanggaran clearfix">
            <div class="metric-icon-pelanggaran" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-tags"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Akumulasi Poin</div>
                <div style="font-size: 24px; font-weight: 700; color: #d97706;">{{ $stats['total_points'] }} Poin</div>
                <div class="text-warning" style="font-size: 11px;">Poin tarhib santri</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-pelanggaran clearfix">
            <div class="metric-icon-pelanggaran" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-users"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Santri Terlibat</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_students_involved'] }} Santri</div>
                <div class="text-muted" style="font-size: 11px;">Pernah tercatat melanggar</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-pelanggaran clearfix">
            <div class="metric-icon-pelanggaran" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-envelope-o"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Ambang SP</div>
                <div style="font-size: 24px; font-weight: 700; color: #6b46c1;">{{ $stats['total_need_sp'] }} Santri</div>
                <div class="text-purple" style="font-size: 11px;">&ge; 20 poin (SP 1 / SP 2)</div>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi Tab & Filter -->
<div class="nav-tabs-custom" style="border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 20px;">
    <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'kasus' ? 'active' : '' }}">
            <a href="{{ route('kesiswaan.pelanggaran', ['tab' => 'kasus', 'severity' => $severityFilter, 'status' => $statusFilter]) }}">
                <i class="fa fa-list text-primary"></i> <strong>Jurnal Catatan Kasus ({{ count($violations) }})</strong>
            </a>
        </li>
        <li class="{{ $activeTab === 'rekap' ? 'active' : '' }}">
            <a href="{{ route('kesiswaan.pelanggaran', ['tab' => 'rekap']) }}">
                <i class="fa fa-bar-chart text-warning" style="color: #d97706;"></i> <strong>Rekapitulasi Poin Santri ({{ count($rekapSantri) }})</strong>
            </a>
        </li>
        <li class="{{ $activeTab === 'aturan' ? 'active' : '' }}">
            <a href="{{ route('kesiswaan.pelanggaran', ['tab' => 'aturan']) }}">
                <i class="fa fa-book text-purple" style="color: #6b46c1;"></i> <strong>Buku Saku Tata Tertib ({{ count($rules) }})</strong>
            </a>
        </li>
    </ul>

    <div class="tab-content" style="padding: 15px;">
        <!-- Filter Bar -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <form method="GET" action="{{ route('kesiswaan.pelanggaran') }}" class="form-inline">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    @if($activeTab === 'kasus')
                        <div class="form-group" style="margin-right: 12px;">
                            <label for="filter_sev" style="font-weight: 600; margin-right: 6px;">Tingkat Sanksi:</label>
                            <select name="severity" id="filter_sev" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Tingkat --</option>
                                <option value="Ringan" {{ $severityFilter === 'Ringan' ? 'selected' : '' }}>Ringan (5-10 Poin)</option>
                                <option value="Sedang" {{ $severityFilter === 'Sedang' ? 'selected' : '' }}>Sedang (15-25 Poin)</option>
                                <option value="Berat" {{ $severityFilter === 'Berat' ? 'selected' : '' }}>Berat (30-50 Poin)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-right: 12px;">
                            <label for="filter_stat" style="font-weight: 600; margin-right: 6px;">Status Tindak Lanjut:</label>
                            <select name="status" id="filter_stat" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Status --</option>
                                <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="pembinaan" {{ $statusFilter === 'pembinaan' ? 'selected' : '' }}>Dalam Pembinaan</option>
                                <option value="panggilan_ortu" {{ $statusFilter === 'panggilan_ortu' ? 'selected' : '' }}>Panggilan Orang Tua</option>
                            </select>
                        </div>
                    @endif
                    <div class="form-group" style="margin-right: 12px;">
                        <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari:</label>
                        <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Nama santri, pelanggaran..." value="{{ $search }}" style="width: 240px;">
                    </div>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Terapkan</button>
                    @if($severityFilter || $statusFilter || $search)
                        <a href="{{ route('kesiswaan.pelanggaran', ['tab' => $activeTab]) }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
                    @endif
                </form>
            </div>
        </div>

        @if($activeTab === 'kasus')
            <!-- TAB 1: JURNAL CATATAN KASUS -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                    <thead>
                        <tr style="background: #edf2f7; color: #2d3748;">
                            <th style="width: 40px;" class="text-center">No</th>
                            <th style="width: 120px;" class="text-center">Tanggal & Waktu</th>
                            <th>Identitas Santri</th>
                            <th>Pelanggaran & Kategori</th>
                            <th class="text-center" style="width: 90px;">Tingkat</th>
                            <th class="text-center" style="width: 80px;">Poin</th>
                            <th>Tindakan / Sanksi</th>
                            <th class="text-center" style="width: 110px;">Status Kasus</th>
                            <th class="text-center" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $idx => $v)
                            @php
                                $sevClass = 'severity-' . strtolower($v['severity']);
                            @endphp
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <strong>{{ date('d/m/Y', strtotime($v['date'])) }}</strong><br>
                                    <span class="text-muted" style="font-size: 11px;">{{ date('H:i', strtotime($v['date'])) }} WIB</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #1a365d; font-size: 14px;">{{ $v['student_name'] }}</strong><br>
                                    <span class="text-muted" style="font-size: 11px;">
                                        NIS: {{ $v['nis'] }} &bull; Rombel: {{ $v['class_name'] }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #c53030;">{{ $v['violation_name'] }}</strong><br>
                                    <span class="text-muted" style="font-size: 11px;">
                                        Kategori: {{ $v['category'] }}
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label {{ $sevClass }}">{{ $v['severity'] }}</span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="badge" style="background-color: #e53e3e; font-size: 13px;">+{{ $v['points'] }}</span>
                                </td>
                                <td style="vertical-align: middle; font-size: 12px;">
                                    <div><i class="fa fa-gavel text-muted"></i> <strong>{{ $v['action_taken'] }}</strong></div>
                                    <div class="text-muted" style="margin-top: 2px;">
                                        Pelapor: {{ $v['reporter_name'] }}
                                    </div>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @if($v['status'] === 'selesai')
                                        <span class="label label-success"><i class="fa fa-check"></i> Selesai</span>
                                    @elseif($v['status'] === 'pembinaan')
                                        <span class="label label-warning"><i class="fa fa-refresh"></i> Pembinaan</span>
                                    @else
                                        <span class="label label-danger"><i class="fa fa-phone"></i> Panggilan Ortu</span>
                                    @endif
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <a href="{{ route('kesiswaan.pelanggaran.destroy', $v['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus catatan kasus ini?')" title="Hapus Kasus">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-shield fa-3x text-muted" style="margin-bottom: 10px;"></i>
                                    <p>Tidak ada catatan pelanggaran santri.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($activeTab === 'rekap')
            <!-- TAB 2: REKAPITULASI POIN PER SANTRI -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                    <thead>
                        <tr style="background: #edf2f7; color: #2d3748;">
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>Identitas Santri</th>
                            <th>Rombel / Kelas</th>
                            <th class="text-center" style="width: 120px;">Jumlah Kasus</th>
                            <th class="text-center" style="width: 130px;">Total Poin Tarhib</th>
                            <th class="text-center" style="width: 220px;">Status Tindak Lanjut Sanksi</th>
                            <th class="text-center" style="width: 90px;">Rincian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapSantri as $idx => $r)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #1a365d; font-size: 14px;">{{ $r['name'] }}</strong><br>
                                    <span class="text-muted" style="font-size: 11px;">NIS: {{ $r['nis'] }}</span>
                                </td>
                                <td style="vertical-align: middle;">{{ $r['class_name'] }}</td>
                                <td class="text-center" style="vertical-align: middle; font-weight: 600;">
                                    {{ $r['case_count'] }} Kasus
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span style="font-size: 16px; font-weight: 800; color: {{ $r['total_points'] >= 20 ? '#e53e3e' : '#d97706' }};">
                                        {{ $r['total_points'] }} Poin
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label label-{{ $r['sp_color'] }}" style="font-size: 11px; padding: 4px 8px;">
                                        {{ $r['sp_status'] }}
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailSantriPoin-{{ $r['student_id'] }}" title="Lihat Rekap Kasus">
                                        <i class="fa fa-search text-primary"></i> Rincian
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail Kasus Santri -->
                            <div class="modal fade" id="modalDetailSantriPoin-{{ $r['student_id'] }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content" style="border-radius: 6px;">
                                        <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                                            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                                            <h4 class="modal-title font-weight-bold">
                                                <i class="fa fa-history"></i> Rekam Jejak Pelanggaran: {{ $r['name'] }} (NIS: {{ $r['nis'] }})
                                            </h4>
                                        </div>
                                        <div class="modal-body" style="font-size: 13px;">
                                            <div class="well well-sm" style="background: #f8fafc; border: 1px solid #edf2f7; margin-bottom: 15px;">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <strong>Rombel:</strong> {{ $r['class_name'] }}<br>
                                                        <strong>Total Pelanggaran:</strong> {{ $r['case_count'] }} Kali
                                                    </div>
                                                    <div class="col-xs-6 text-right">
                                                        <strong>Akumulasi Poin:</strong> <span style="font-size: 18px; font-weight: 700; color: #e53e3e;">{{ $r['total_points'] }} Poin</span><br>
                                                        <strong>Rekomendasi:</strong> <span class="label label-{{ $r['sp_color'] }}">{{ $r['sp_status'] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" style="font-size: 12px;">
                                                    <thead>
                                                        <tr style="background: #edf2f7;">
                                                            <th>Tanggal</th>
                                                            <th>Pasal Pelanggaran</th>
                                                            <th class="text-center">Poin</th>
                                                            <th>Tindakan & Sanksi</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($r['cases'] as $cItem)
                                                            <tr>
                                                                <td>{{ date('d/m/Y H:i', strtotime($cItem['date'])) }}</td>
                                                                <td><strong>{{ $cItem['violation_name'] }}</strong></td>
                                                                <td class="text-center"><span class="badge" style="background-color: #e53e3e;">+{{ $cItem['points'] }}</span></td>
                                                                <td>{{ $cItem['action_taken'] }}</td>
                                                                <td class="text-center">
                                                                    <span class="label label-{{ $cItem['status'] === 'selesai' ? 'success' : 'warning' }}">
                                                                        {{ $cItem['status'] }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 25px;">
                                    Belum ada santri yang memiliki akumulasi poin pelanggaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @else
            <!-- TAB 3: BUKU SAKU ATURAN TATA TERTIB -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                    <thead>
                        <tr style="background: #edf2f7; color: #2d3748;">
                            <th style="width: 40px;" class="text-center">No</th>
                            <th style="width: 100px;" class="text-center">Kode Pasal</th>
                            <th>Nama Pelanggaran & Tata Tertib</th>
                            <th>Kategori Kedisiplinan</th>
                            <th class="text-center" style="width: 100px;">Tingkat</th>
                            <th class="text-center" style="width: 90px;">Bobot Poin</th>
                            <th>Konsekuensi / Tindakan Standar</th>
                            <th class="text-center" style="width: 80px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $idx => $rul)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                <td class="text-center font-monospace" style="vertical-align: middle;"><strong>{{ $rul['id'] }}</strong></td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #1a365d;">{{ $rul['name'] }}</strong>
                                </td>
                                <td style="vertical-align: middle;">{{ $rul['category'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label label-{{ $rul['severity'] === 'Ringan' ? 'info' : ($rul['severity'] === 'Sedang' ? 'warning' : 'danger') }}">
                                        {{ $rul['severity'] }}
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="badge" style="background-color: #e53e3e; font-size: 13px;">{{ $rul['points'] }} Poin</span>
                                </td>
                                <td style="vertical-align: middle; font-size: 12px; color: #4a5568;">
                                    {{ $rul['consequence'] }}
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                    Tidak ada data aturan tata tertib.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Catat Kasus Pelanggaran Baru -->
<div class="modal fade" id="modalCatatPelanggaran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('kesiswaan.pelanggaran.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-exclamation-triangle"></i> Catat Kasus Pelanggaran Santri Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="vio_student">Santri Yang Melanggar <span class="text-danger">*</span></label>
                                <select name="student_id" id="vio_student" class="form-control" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($students as $std)
                                        <option value="{{ $std['id'] }}">
                                            {{ $std['name'] }} (NIS: {{ $std['nis'] ?? '-' }}) &bull; {{ $std['class_id'] ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="vio_rule">Pasal Pelanggaran (Buku Saku) <span class="text-danger">*</span></label>
                                <select name="rule_id" id="vio_rule" class="form-control" required>
                                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                                    @foreach($rules as $r)
                                        <option value="{{ $r['id'] }}">
                                            [{{ $r['id'] }}] {{ $r['name'] }} (+{{ $r['points'] }} Poin - {{ $r['severity'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="vio_date">Tanggal Kejadian <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="vio_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="vio_reporter">Petugas / Pelapor <span class="text-danger">*</span></label>
                                <input type="text" name="reporter_name" id="vio_reporter" class="form-control" placeholder="Contoh: Ust. Budi Santoso (Pamong Asrama)" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="vio_status">Status Kasus <span class="text-danger">*</span></label>
                                <select name="status" id="vio_status" class="form-control" required>
                                    <option value="selesai">Selesai (Sanksi Telah Dijalankan)</option>
                                    <option value="pembinaan">Dalam Pembinaan BK / Walas</option>
                                    <option value="panggilan_ortu">Perlu Panggilan Orang Tua</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vio_action">Tindakan / Sanksi Yang Diberikan <span class="text-danger">*</span></label>
                        <input type="text" name="action_taken" id="vio_action" class="form-control" placeholder="Contoh: Teguran lisan, menulis istighfar 100x, piket musholla" required>
                    </div>

                    <div class="form-group">
                        <label for="vio_notes">Keterangan Tambahan / Kronologi</label>
                        <textarea name="notes" id="vio_notes" class="form-control" rows="2" placeholder="Tuliskan kronologi singkat kejadian..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save"></i> Simpan Catatan Pelanggaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Aturan Tata Tertib Baru -->
<div class="modal fade" id="modalTambahAturan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('kesiswaan.pelanggaran.rule.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #6b46c1; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-book"></i> Daftarkan Aturan Tata Tertib Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label for="rule_name">Nama Pelanggaran <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="rule_name" class="form-control" placeholder="Contoh: Mengotori dinding asrama dengan coretan" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="rule_cat">Kategori Tata Tertib <span class="text-danger">*</span></label>
                                <select name="category" id="rule_cat" class="form-control" required>
                                    <option value="Kedisiplinan & Waktu">Kedisiplinan & Waktu</option>
                                    <option value="Kerapihan & Seragam">Kerapihan & Seragam</option>
                                    <option value="Ketertiban Asrama">Ketertiban Asrama</option>
                                    <option value="Barang Terlarang & Gadget">Barang Terlarang & Gadget</option>
                                    <option value="Akhlak & Moral">Akhlak & Moral</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="rule_sev">Tingkat Sanksi <span class="text-danger">*</span></label>
                                <select name="severity" id="rule_sev" class="form-control" required>
                                    <option value="Ringan">Ringan (5 - 10 Poin)</option>
                                    <option value="Sedang">Sedang (15 - 25 Poin)</option>
                                    <option value="Berat">Berat (30 - 50 Poin)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="rule_pts">Bobot Poin Tarhib <span class="text-danger">*</span></label>
                                <input type="number" name="points" id="rule_pts" class="form-control" value="10" min="1" max="100" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="rule_cons">Konsekuensi Standar <span class="text-danger">*</span></label>
                        <textarea name="consequence" id="rule_cons" class="form-control" rows="2" placeholder="Contoh: Membersihkan dinding dan mengecat ulang..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background-color: #6b46c1; border-color: #553c9a;">
                        <i class="fa fa-save"></i> Simpan Aturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

