@extends('layouts.app')

@section('title', 'Capaian & Progres Tahfidz Al-Qur\'an')

@push('styles')
<style>
    .metric-card-tfz-prog {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-tfz-prog:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-tfz-prog {
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
    .progress-tahfidz {
        height: 14px;
        border-radius: 7px;
        background-color: #edf2f7;
        margin-bottom: 4px;
    }
    .progress-bar-primary { background-color: #2b6cb0; }
    .progress-bar-purple { background-color: #6b46c1; }
    .progress-bar-success { background-color: #38a169; }
    .progress-bar-warning { background-color: #d97706; }
    .progress-bar-danger { background-color: #e53e3e; }
    .juz-grid-box {
        display: inline-block;
        width: 28px;
        height: 28px;
        line-height: 26px;
        text-align: center;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        margin: 2px;
        border: 1px solid #cbd5e0;
    }
    .juz-mutqin {
        background-color: #276749;
        color: #fff;
        border-color: #22543d;
    }
    .juz-ziyadah {
        background-color: #2b6cb0;
        color: #fff;
        border-color: #2c5282;
    }
    .juz-target {
        background-color: #f7fafc;
        color: #a0aec0;
    }
</style>
@endpush

@section('content')
<!-- Header Page -->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-8 col-xs-12">
        <h3 style="margin-top: 0; font-weight: 700; color: #1a365d;">
            <i class="fa fa-line-chart text-primary"></i> Tahfidz: Visual Dashboard & Progress Bar Capaian Santri
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Monitoring akumulasi hafalan mutqin, perbandingan target kurikulum vs realisasi setoran ziyadah, serta riwayat kelulusan tasmi' bil ghoib.
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak Rekap Capaian
        </button>
    </div>
</div>

<!-- Metrik Statistik Capaian Tahfidz -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tfz-prog clearfix">
            <div class="metric-icon-tfz-prog" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-users"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Santri Tahfidz</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_students'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Aktif dalam bimbingan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tfz-prog clearfix">
            <div class="metric-icon-tfz-prog" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-book"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Total Juz Mutqin</div>
                <div style="font-size: 24px; font-weight: 700; color: #38a169;">{{ $stats['total_juz_mutqin'] }} Juz</div>
                <div class="text-success" style="font-size: 11px;"><i class="fa fa-check"></i> Teruji mutaba'ah murojaah</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tfz-prog clearfix">
            <div class="metric-icon-tfz-prog" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-pie-chart"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Rata-rata Capaian</div>
                <div style="font-size: 24px; font-weight: 700; color: #6b46c1;">{{ $stats['avg_juz'] }} Juz / Santri</div>
                <div class="text-purple" style="font-size: 11px;">Akumulasi hafalan lancar</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tfz-prog clearfix">
            <div class="metric-icon-tfz-prog" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-trophy"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Lulus Tasmi' 1 Juz</div>
                <div style="font-size: 24px; font-weight: 700; color: #d97706;">{{ $stats['total_tasmi_passed'] }} Santri</div>
                <div class="text-warning" style="font-size: 11px;">Sekali duduk predikat Mumtaz</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; margin-bottom: 20px;">
    <div class="box-body" style="padding: 15px;">
        <form method="GET" action="{{ route('tahfidz.progres') }}" class="form-inline">
            <div class="form-group" style="margin-right: 12px;">
                <label for="filter_unit" style="font-weight: 600; margin-right: 6px;">Unit:</label>
                <select name="unit" id="filter_unit" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u['id'] }}" {{ $activeUnit === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-right: 12px;">
                <label for="filter_hlq" style="font-weight: 600; margin-right: 6px;">Halaqah:</label>
                <select name="halaqah_id" id="filter_hlq" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Halaqah --</option>
                    @foreach($halaqahs as $h)
                        <option value="{{ $h['id'] }}" {{ $selectedHalaqahId === $h['id'] ? 'selected' : '' }}>
                            {{ $h['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-right: 12px;">
                <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari:</label>
                <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Nama, NIS, atau halaqah..." value="{{ $search }}" style="width: 220px;">
            </div>
            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-filter"></i> Terapkan</button>
            @if($activeUnit || $selectedHalaqahId || $search)
                <a href="{{ route('tahfidz.progres') }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Matriks Capaian Tahfidz Santri -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <div class="box-header with-border" style="padding: 12px 18px; background: #f8fafc;">
        <h4 class="box-title font-weight-bold" style="color: #1a365d; font-size: 15px;">
            <i class="fa fa-list"></i> Matriks Capaian Hafalan Al-Qur'an Santri
        </h4>
        <div class="box-tools pull-right" style="margin-top: 3px;">
            <span class="label label-primary">{{ count($students) }} Santri Terpantau</span>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 13px; margin-bottom: 0;">
                <thead>
                    <tr style="background: #edf2f7; color: #2d3748;">
                        <th style="width: 40px;" class="text-center">No</th>
                        <th>Identitas Santri</th>
                        <th>Halaqah & Musyrif</th>
                        <th class="text-center" style="width: 100px;">Target Program</th>
                        <th class="text-center" style="width: 120px;">Ziyadah Masuk</th>
                        <th class="text-center" style="width: 120px;">Hafalan Mutqin</th>
                        <th style="width: 200px;">Rasio Capaian Target</th>
                        <th class="text-center" style="width: 120px;">Status Evaluasi</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $idx => $st)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                            <td style="vertical-align: middle;">
                                <strong style="color: #1a365d; font-size: 14px;">{{ $st['name'] }}</strong><br>
                                <span class="text-muted" style="font-size: 11px;">
                                    NIS: {{ $st['nis'] }} &bull; Rombel: {{ $st['class_name'] }}
                                </span>
                            </td>
                            <td style="vertical-align: middle;">
                                <span style="font-weight: 600; color: #2d3748;">{{ $st['halaqah_name'] }}</span><br>
                                <span class="text-muted" style="font-size: 11px;">
                                    <i class="fa fa-user-circle"></i> {{ $st['supervisor_name'] }}
                                </span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="badge" style="background-color: #6b46c1; font-size: 12px;">{{ $st['target_juz'] }} Juz</span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <strong style="color: #2b6cb0; font-size: 14px;">{{ $st['juz_ziyadah'] }} Juz</strong><br>
                                <span class="text-muted" style="font-size: 11px;">{{ $st['last_surah'] }}</span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <strong style="color: #276749; font-size: 15px;">{{ $st['juz_mutqin'] }} Juz</strong><br>
                                @if($st['has_tasmi'])
                                    <span class="label label-warning" style="font-size: 10px;"><i class="fa fa-star"></i> Tasmi' 1 Juz</span>
                                @endif
                            </td>
                            <!-- Progress Bar -->
                            <td style="vertical-align: middle;">
                                <div class="clearfix" style="font-size: 12px; margin-bottom: 2px;">
                                    <span class="pull-left font-weight-bold" style="color: #2d3748;">{{ $st['progress_pct'] }}%</span>
                                    <span class="pull-right text-muted" style="font-size: 11px;">{{ $st['juz_mutqin'] }}/{{ $st['target_juz'] }} Juz</span>
                                </div>
                                <div class="progress progress-tahfidz">
                                    <div class="progress-bar progress-bar-{{ $st['status_color'] }}" role="progressbar" style="width: {{ $st['progress_pct'] }}%;"></div>
                                </div>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="label label-{{ $st['status_color'] }}" style="font-size: 11px; padding: 4px 8px;">
                                    {{ $st['status_label'] }}
                                </span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailTahfidz-{{ $st['id'] }}" title="Lihat Peta Juz & Riwayat">
                                    <i class="fa fa-search text-primary"></i> Peta Juz
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail Peta Hafalan Santri -->
                        <div class="modal fade" id="modalDetailTahfidz-{{ $st['id'] }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content" style="border-radius: 6px;">
                                    <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                                        <h4 class="modal-title font-weight-bold">
                                            <i class="fa fa-bookmark"></i> Peta Capaian 30 Juz: {{ $st['name'] }}
                                        </h4>
                                    </div>
                                    <div class="modal-body" style="font-size: 13px;">
                                        <div class="well well-sm" style="background: #f8fafc; border: 1px solid #edf2f7; margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <strong>NIS:</strong> {{ $st['nis'] }} &bull; <strong>Rombel:</strong> {{ $st['class_name'] }}<br>
                                                    <strong>Halaqah:</strong> {{ $st['halaqah_name'] }} (Musyrif: {{ $st['supervisor_name'] }})
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <strong>Target Program:</strong> <span class="badge" style="background-color: #6b46c1;">{{ $st['target_juz'] }} Juz</span><br>
                                                    <strong>Total Mutqin:</strong> <span style="font-size: 16px; font-weight: 700; color: #276749;">{{ $st['juz_mutqin'] }} Juz ({{ $st['progress_pct'] }}%)</span>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 style="font-weight: 700; color: #1a365d; margin-bottom: 8px;">
                                            Matriks Peta 30 Juz Al-Qur'an:
                                        </h5>
                                        <div style="margin-bottom: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; text-align: center;">
                                            @for($j = 1; $j <= 30; $j++)
                                                @php
                                                    $isMutqin = $j <= floor($st['juz_mutqin']) || ($j == 30 && $st['juz_mutqin'] >= 1);
                                                    $isZiyadah = !$isMutqin && ($j <= ceil($st['juz_ziyadah']) || ($j == 29 && $st['juz_ziyadah'] >= 2));
                                                @endphp
                                                <span class="juz-grid-box {{ $isMutqin ? 'juz-mutqin' : ($isZiyadah ? 'juz-ziyadah' : 'juz-target') }}" title="Juz {{ $j }}: {{ $isMutqin ? 'Mutqin' : ($isZiyadah ? 'Ziyadah' : 'Belum') }}">
                                                    {{ $j }}
                                                </span>
                                            @endfor
                                            <div style="margin-top: 10px; font-size: 11px;" class="text-muted text-center">
                                                <span class="badge" style="background-color: #276749; margin-right: 5px;">&nbsp;</span> Hijau: Juz Mutqin &bull;
                                                <span class="badge" style="background-color: #2b6cb0; margin-left: 10px; margin-right: 5px;">&nbsp;</span> Biru: Ziyadah Baru &bull;
                                                <span class="badge" style="background-color: #edf2f7; color: #718096; margin-left: 10px; margin-right: 5px;">&nbsp;</span> Abu-abu: Target Selanjutnya
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 style="font-weight: 700; color: #2b6cb0;">Riwayat Setoran Ziyadah Terakhir:</h5>
                                                @forelse($st['recent_ziyadah'] as $zItem)
                                                    <div style="font-size: 12px; border-bottom: 1px dashed #e2e8f0; padding: 4px 0;">
                                                        <strong>{{ date('d/m/Y', strtotime($zItem['date'])) }}:</strong> QS. {{ $zItem['surah_name'] }} (ayat {{ $zItem['ayat_start'] }}-{{ $zItem['ayat_end'] }})
                                                        <span class="pull-right badge" style="background-color: #2b6cb0;">{{ $zItem['score'] }} pts</span>
                                                    </div>
                                                @empty
                                                    <span class="text-muted font-italic" style="font-size: 12px;">Belum ada entri ziyadah.</span>
                                                @endforelse
                                            </div>
                                            <div class="col-md-6">
                                                <h5 style="font-weight: 700; color: #6b46c1;">Riwayat Ujian Murojaah Terakhir:</h5>
                                                @forelse($st['recent_murojaah'] as $mItem)
                                                    <div style="font-size: 12px; border-bottom: 1px dashed #e2e8f0; padding: 4px 0;">
                                                        <strong>{{ date('d/m/Y', strtotime($mItem['date'])) }}:</strong> Juz {{ $mItem['juz'] }} ({{ $mItem['surah_range'] }})
                                                        <span class="pull-right badge" style="background-color: {{ $mItem['fluency_status'] === 'Mutqin' ? '#276749' : '#d97706' }};">{{ $mItem['fluency_status'] }}</span>
                                                    </div>
                                                @empty
                                                    <span class="text-muted font-italic" style="font-size: 12px;">Belum ada entri murojaah.</span>
                                                @endforelse
                                            </div>
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
                            <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                <i class="fa fa-line-chart fa-3x text-muted" style="margin-bottom: 10px;"></i>
                                <p>Tidak ada data progres capaian santri ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

