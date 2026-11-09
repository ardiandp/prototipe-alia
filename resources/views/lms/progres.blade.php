@extends('layouts.app')

@section('title', 'Progres Belajar Santri - LMS')

@push('styles')
<style>
    .metric-card-progress {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-progress:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-progress {
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
    .progress-compact {
        height: 12px;
        margin-bottom: 4px;
        border-radius: 6px;
        background-color: #edf2f7;
    }
    .progress-bar-primary { background-color: #2b6cb0; }
    .progress-bar-purple { background-color: #6b46c1; }
    .progress-bar-success { background-color: #38a169; }
    .progress-bar-warning { background-color: #d97706; }
    .progress-bar-danger { background-color: #e53e3e; }
    .stat-pill {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Header Page -->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-8 col-xs-12">
        <h3 style="margin-top: 0; font-weight: 700; color: #1a365d;">
            <i class="fa fa-line-chart text-primary"></i> LMS: Monitoring Kemajuan Belajar Santri
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Pantau rasio penyelesaian materi modul bacaan, kepatuhan pengumpulan tugas & PR, serta capaian evaluasi CBT per rombel.
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak Rekapitulasi Progres
        </button>
    </div>
</div>

<!-- Metrik Statistik Progres -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-progress clearfix">
            <div class="metric-icon-progress" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-users"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Santri di Rombel</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_students'] }}</div>
                <div class="text-muted" style="font-size: 11px;">{{ $selectedClass['name'] ?? 'Rombel Terpilih' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-progress clearfix">
            <div class="metric-icon-progress" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-percent"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Rata-rata Kelengkapan</div>
                <div style="font-size: 24px; font-weight: 700; color: #6b46c1;">{{ $stats['avg_completion'] }}%</div>
                <div class="text-purple" style="font-size: 11px;">Materi + Tugas + CBT</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-progress clearfix">
            <div class="metric-icon-progress" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-exclamation-circle"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Tugas Tertunda</div>
                <div style="font-size: 24px; font-weight: 700; color: #d97706;">{{ $stats['total_pending_tasks'] }}</div>
                <div class="text-warning" style="font-size: 11px;">Belum dikumpulkan santri</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-progress clearfix">
            <div class="metric-icon-progress" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-check-circle"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Evaluasi Lulus KKM</div>
                <div style="font-size: 24px; font-weight: 700; color: #38a169;">{{ $stats['total_passed'] }}</div>
                <div class="text-success" style="font-size: 11px;">Kelulusan CBT mandiri</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Rombel & Pencarian -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; margin-bottom: 20px;">
    <div class="box-body" style="padding: 15px;">
        <form method="GET" action="{{ route('lms.progres') }}" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label for="filter_class" style="font-weight: 600; margin-right: 6px;">Pilih Rombel / Kelas:</label>
                <select name="class_id" id="filter_class" class="form-control input-sm" onchange="this.form.submit()" style="font-weight: 600; color: #1a365d;">
                    @foreach($classes as $c)
                        <option value="{{ $c['id'] }}" {{ $selectedClassId === $c['id'] ? 'selected' : '' }}>
                            {{ $c['name'] }} ({{ $c['unit_id'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-right: 15px;">
                <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari Santri:</label>
                <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Nama atau NIS santri..." value="{{ $search }}" style="width: 220px;">
            </div>
            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Cari</button>
            @if($search)
                <a href="{{ route('lms.progres', ['class_id' => $selectedClassId]) }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Monitoring Progress Santri -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <div class="box-header with-border" style="padding: 12px 18px; background: #f8fafc;">
        <h4 class="box-title font-weight-bold" style="color: #1a365d; font-size: 15px;">
            <i class="fa fa-list"></i> Matriks Capaian Belajar Santri &mdash; {{ $selectedClass['name'] ?? '' }}
        </h4>
        <div class="box-tools pull-right" style="font-size: 12px; color: #718096; margin-top: 4px;">
            <span class="label label-default" style="margin-right: 5px;"><i class="fa fa-book"></i> {{ $totalMaterialCount }} Modul Materi</span>
            <span class="label label-primary" style="margin-right: 5px;"><i class="fa fa-tasks"></i> {{ $totalAssignmentCount }} Penugasan</span>
            <span class="label label-warning"><i class="fa fa-laptop"></i> {{ $totalQuizCount }} Paket CBT</span>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 13px; margin-bottom: 0;">
                <thead>
                    <tr style="background: #edf2f7; color: #2d3748;">
                        <th style="width: 40px;" class="text-center">No</th>
                        <th>Identitas Santri</th>
                        <th class="text-center" style="width: 140px;">Materi Terbaca</th>
                        <th class="text-center" style="width: 160px;">Pengumpulan Tugas</th>
                        <th class="text-center" style="width: 150px;">Hasil Evaluasi CBT</th>
                        <th style="width: 180px;">Tingkat Kelengkapan</th>
                        <th class="text-center" style="width: 130px;">Status Capaian</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $idx => $st)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                            <td style="vertical-align: middle;">
                                <strong style="color: #1a365d; font-size: 14px;">{{ $st['name'] }}</strong><br>
                                <span class="text-muted" style="font-size: 11px;">
                                    NIS: <span class="font-monospace">{{ $st['nis'] }}</span> &bull; 
                                    Gender: <span class="badge" style="background-color: {{ $st['gender'] === 'L' ? '#2b6cb0' : '#805ad5' }}; font-size: 10px;">{{ $st['gender'] === 'L' ? 'Ikhwan' : 'Akhwat' }}</span>
                                </span>
                            </td>
                            <!-- Materi Terbaca -->
                            <td class="text-center" style="vertical-align: middle;">
                                <span style="font-weight: 700; color: #2b6cb0;">{{ $st['completed_materials'] }}</span> / {{ $st['total_materials'] }} Modul<br>
                                <span class="text-muted" style="font-size: 11px;">
                                    ({{ $st['total_materials'] > 0 ? round(($st['completed_materials'] / $st['total_materials']) * 100) : 100 }}% selesai)
                                </span>
                            </td>
                            <!-- Tugas & PR -->
                            <td class="text-center" style="vertical-align: middle;">
                                <span style="font-weight: 700; color: #38a169;">{{ $st['submitted_assignments'] }}</span> / {{ $st['total_assignments'] }} Tugas<br>
                                @if($st['pending_assignments'] > 0)
                                    <span class="label label-warning" style="font-size: 10px;">
                                        <i class="fa fa-clock-o"></i> {{ $st['pending_assignments'] }} Pending
                                    </span>
                                @else
                                    <span class="label label-success" style="font-size: 10px;">
                                        <i class="fa fa-check"></i> Lengkap
                                    </span>
                                @endif
                                <div style="font-size: 11px; color: #718096; margin-top: 2px;">
                                    Rata-rata: <strong>{{ $st['avg_assignment_score'] }}</strong>
                                </div>
                            </td>
                            <!-- Kuis CBT -->
                            <td class="text-center" style="vertical-align: middle;">
                                <span style="font-weight: 700; color: #6b46c1;">{{ $st['quizzes_passed'] }}</span> / {{ $st['total_quizzes'] }} Lulus KKM<br>
                                <span class="badge" style="background-color: {{ $st['avg_quiz_score'] >= 75 ? '#38a169' : '#e53e3e' }}; font-size: 11px;">
                                    Skor CBT: {{ $st['avg_quiz_score'] }}
                                </span>
                            </td>
                            <!-- Progress Bar -->
                            <td style="vertical-align: middle;">
                                <div class="clearfix" style="font-size: 12px; margin-bottom: 2px;">
                                    <span class="pull-left font-weight-bold" style="color: #2d3748;">{{ $st['overall_pct'] }}%</span>
                                    <span class="pull-right text-muted" style="font-size: 11px;">Target: 100%</span>
                                </div>
                                <div class="progress progress-compact">
                                    <div class="progress-bar progress-bar-{{ $st['badge_color'] }}" role="progressbar" style="width: {{ $st['overall_pct'] }}%;"></div>
                                </div>
                            </td>
                            <!-- Status Capaian -->
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="label label-{{ $st['badge_color'] }}" style="font-size: 11px; padding: 4px 8px;">
                                    {{ $st['status_badge'] }}
                                </span>
                            </td>
                            <!-- Aksi -->
                            <td class="text-center" style="vertical-align: middle;">
                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailProgress-{{ $st['id'] }}" title="Lihat Rekap Lengkap Santri">
                                    <i class="fa fa-search text-primary"></i> Rincian
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail Progress Santri -->
                        <div class="modal fade" id="modalDetailProgress-{{ $st['id'] }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content" style="border-radius: 6px;">
                                    <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                                        <h4 class="modal-title font-weight-bold">
                                            <i class="fa fa-user-circle"></i> Rekap Belajar: {{ $st['name'] }}
                                        </h4>
                                    </div>
                                    <div class="modal-body" style="font-size: 13px;">
                                        <div class="well well-sm" style="background: #f8fafc; border: 1px solid #edf2f7; margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <strong>NIS:</strong> {{ $st['nis'] }}<br>
                                                    <strong>Rombel:</strong> {{ $selectedClass['name'] ?? '' }}
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <strong>Total Completion:</strong><br>
                                                    <span style="font-size: 20px; font-weight: 700; color: #2b6cb0;">{{ $st['overall_pct'] }}%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 style="font-weight: 700; color: #1a365d; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                            1. Akses Modul Ajar Digital
                                        </h5>
                                        <p class="text-muted" style="margin-bottom: 8px;">
                                            Santri telah membuka dan menyelesaikan <strong>{{ $st['completed_materials'] }}</strong> dari <strong>{{ $st['total_materials'] }}</strong> materi e-learning yang diterbitkan guru pengampu.
                                        </p>

                                        <h5 style="font-weight: 700; color: #1a365d; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-top: 15px;">
                                            2. Portofolio & Submisi Tugas
                                        </h5>
                                        <p class="text-muted" style="margin-bottom: 4px;">
                                            - Tugas Terkumpul: <strong>{{ $st['submitted_assignments'] }} tugas</strong><br>
                                            - Tugas Tertunda: <strong class="text-danger">{{ $st['pending_assignments'] }} tugas</strong><br>
                                            - Rata-rata Nilai Tugas: <strong class="text-success">{{ $st['avg_assignment_score'] }} poin</strong>
                                        </p>

                                        <h5 style="font-weight: 700; color: #1a365d; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-top: 15px;">
                                            3. Computer Based Test (CBT)
                                        </h5>
                                        <p class="text-muted" style="margin-bottom: 4px;">
                                            - Paket Ujian Diselesaikan: <strong>{{ $st['quizzes_taken'] }} paket</strong><br>
                                            - Kelulusan KKM: <strong>{{ $st['quizzes_passed'] }} paket</strong><br>
                                            - Rata-rata Nilai Ujian: <strong class="text-purple" style="color: #6b46c1;">{{ $st['avg_quiz_score'] }} poin</strong>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                                <i class="fa fa-info-circle fa-2x"></i>
                                <p style="margin-top: 10px;">Tidak ada data santri ditemukan di rombel ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

