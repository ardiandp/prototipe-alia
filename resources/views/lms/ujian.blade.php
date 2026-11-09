@extends('layouts.app')

@section('title', 'Ujian Online & CBT (Computer Based Test)')

@push('styles')
<style>
    .metric-card-cbt {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-cbt:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-cbt {
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
    .quiz-card {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }
    .quiz-card-header {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
    }
    .quiz-card-body {
        padding: 18px;
    }
    .badge-cbt-pts { background-color: #6b46c1; color: #fff; }
    .badge-cbt-kuis { background-color: #2b6cb0; color: #fff; }
    .badge-cbt-tryout { background-color: #d97706; color: #fff; }
    .question-box {
        background: #f8fafc;
        border-left: 4px solid #2b6cb0;
        padding: 12px 16px;
        margin-bottom: 15px;
        border-radius: 0 4px 4px 0;
    }
    .option-list {
        list-style-type: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .option-list li {
        padding: 6px 12px;
        margin-bottom: 6px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        background: #fff;
    }
    .option-list li.correct {
        border-color: #38a169;
        background-color: #f0fff4;
        font-weight: 600;
        color: #22543d;
    }
    .timer-badge {
        font-family: 'Courier New', monospace;
        font-size: 13px;
        letter-spacing: 0.5px;
    }
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
            <i class="fa fa-laptop text-primary"></i> LMS: Ujian Online & CBT (Computer Based Test)
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Bank soal digital, simulasi Computer-Based Test (CBT), evaluasi otomatis kuis pilihan ganda, dan rekapitulasi nilai kelulusan santri.
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahUjian" style="border-radius: 4px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Buat Paket Ujian / CBT Baru
        </button>
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak
        </button>
    </div>
</div>

<!-- Metrik Statistik CBT -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-cbt clearfix">
            <div class="metric-icon-cbt" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-database"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Total Paket CBT</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_quizzes'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Semua Rombel & Jenjang</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-cbt clearfix">
            <div class="metric-icon-cbt" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-play-circle"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Ujian Aktif</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['active_quizzes'] }}</div>
                <div class="text-success" style="font-size: 11px;"><i class="fa fa-check"></i> Siap dikerjakan santri</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-cbt clearfix">
            <div class="metric-icon-cbt" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-check-square-o"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Total Butir Soal</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_questions'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Bank Soal Terdaftar</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-cbt clearfix">
            <div class="metric-icon-cbt" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-users"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Riwayat Ujian Santri</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_attempts'] }}</div>
                <div class="text-warning" style="font-size: 11px;"><i class="fa fa-clock-o"></i> Percobaan terselesaikan</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; margin-bottom: 20px;">
    <div class="box-body" style="padding: 15px;">
        <form method="GET" action="{{ route('lms.ujian') }}" class="form-inline">
            <div class="form-group" style="margin-right: 10px;">
                <label for="filter_unit" style="font-weight: 600; margin-right: 5px;">Jenjang Unit:</label>
                <select name="unit" id="filter_unit" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u['id'] }}" {{ $activeUnit === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-right: 10px;">
                <label for="filter_status" style="font-weight: 600; margin-right: 5px;">Status CBT:</label>
                <select name="status" id="filter_status" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ $activeStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ $activeStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="selesai" {{ $activeStatus === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="form-group" style="margin-right: 10px;">
                <label for="filter_q" style="font-weight: 600; margin-right: 5px;">Cari:</label>
                <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Judul, kode CBT, mapel..." value="{{ $search }}" style="width: 220px;">
            </div>
            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-filter"></i> Terapkan</button>
            @if($activeUnit || $activeStatus || $search)
                <a href="{{ route('lms.ujian') }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<!-- Daftar Paket Kuis CBT -->
<div class="row">
    @forelse($quizzes as $quiz)
        <div class="col-md-6 col-xs-12">
            <div class="quiz-card">
                <div class="quiz-card-header clearfix">
                    <div class="pull-left">
                        @if($quiz['category'] === 'PTS')
                            <span class="badge badge-cbt-pts">PTS Semester</span>
                        @elseif($quiz['category'] === 'Kuis Harian')
                            <span class="badge badge-cbt-kuis">Kuis Harian</span>
                        @else
                            <span class="badge badge-cbt-tryout">Tryout Mandiri</span>
                        @endif
                        <span class="label label-default" style="font-family: monospace; font-size: 11px;">{{ $quiz['code'] }}</span>
                    </div>
                    <div class="pull-right">
                        @if($quiz['status'] === 'aktif')
                            <span class="label label-success"><i class="fa fa-check-circle"></i> Aktif</span>
                        @elseif($quiz['status'] === 'draft')
                            <span class="label label-warning"><i class="fa fa-pencil"></i> Draft</span>
                        @else
                            <span class="label label-default">Selesai</span>
                        @endif
                    </div>
                </div>
                <div class="quiz-card-body">
                    <h4 style="margin-top: 0; font-weight: 700; color: #1a365d; min-height: 44px;">
                        {{ $quiz['title'] }}
                    </h4>
                    
                    <div style="font-size: 13px; color: #4a5568; margin-bottom: 12px;">
                        <div><i class="fa fa-book text-muted" style="width: 18px;"></i> <strong>Mapel:</strong> {{ $quiz['subject_name'] }}</div>
                        <div><i class="fa fa-users text-muted" style="width: 18px;"></i> <strong>Rombel:</strong> {{ $quiz['class_name'] }}</div>
                        <div><i class="fa fa-user-circle text-muted" style="width: 18px;"></i> <strong>Guru Pembuat:</strong> {{ $quiz['teacher_name'] }}</div>
                    </div>

                    <div class="well well-sm" style="background: #f7fafc; border: 1px solid #edf2f7; margin-bottom: 14px; font-size: 12px;">
                        <div class="row text-center">
                            <div class="col-xs-3" style="border-right: 1px solid #e2e8f0;">
                                <div class="text-muted"><i class="fa fa-clock-o"></i> Durasi</div>
                                <strong class="timer-badge text-primary">{{ $quiz['duration_minutes'] }} Menit</strong>
                            </div>
                            <div class="col-xs-3" style="border-right: 1px solid #e2e8f0;">
                                <div class="text-muted"><i class="fa fa-list-ol"></i> Soal</div>
                                <strong class="text-purple" style="color: #6b46c1;">{{ $quiz['questions_count'] }} Butir</strong>
                            </div>
                            <div class="col-xs-3" style="border-right: 1px solid #e2e8f0;">
                                <div class="text-muted"><i class="fa fa-flag-checkered"></i> KKM</div>
                                <strong class="text-danger">{{ $quiz['passing_score'] }} Poin</strong>
                            </div>
                            <div class="col-xs-3">
                                <div class="text-muted"><i class="fa fa-graduation-cap"></i> Peserta</div>
                                <strong class="text-success">{{ $quiz['attempts_count'] }} Santri</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Nilai Peserta -->
                    <div style="margin-bottom: 15px; font-size: 12px;">
                        <span class="text-muted">Statistik Nilai:</span>
                        @if($quiz['attempts_count'] > 0)
                            <span class="badge" style="background-color: #2b6cb0;">Rata-rata: {{ $quiz['avg_score'] }}</span>
                            <span class="badge" style="background-color: #38a169;">Tertinggi: {{ $quiz['highest_score'] }}</span>
                            <span class="badge" style="background-color: #e53e3e;">Terendah: {{ $quiz['lowest_score'] }}</span>
                            <span class="badge" style="background-color: #805ad5;">Lulus: {{ $quiz['passed_count'] }}/{{ $quiz['attempts_count'] }}</span>
                        @else
                            <span class="text-muted font-italic">Belum ada santri yang menyelesaikan ujian.</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalPreviewSoal-{{ $quiz['id'] }}">
                                <i class="fa fa-eye text-primary"></i> Pratinjau Butir Soal
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRekapNilai-{{ $quiz['id'] }}">
                                <i class="fa fa-bar-chart text-success"></i> Rekap Nilai ({{ $quiz['attempts_count'] }})
                            </button>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('lms.ujian.destroy', $quiz['id']) }}" class="btn btn-default btn-sm text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus paket kuis ini?')">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Pratinjau Butir Soal -->
        <div class="modal fade" id="modalPreviewSoal-{{ $quiz['id'] }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius: 6px;">
                    <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                        <h4 class="modal-title font-weight-bold">
                            <i class="fa fa-file-text-o"></i> Bank Soal: {{ $quiz['title'] }} ({{ $quiz['code'] }})
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" style="font-size: 13px; margin-bottom: 20px;">
                            <strong>Petunjuk Ujian:</strong> {{ $quiz['instructions'] ?? 'Kerjakan dengan mandiri dan teliti.' }}
                            <div style="margin-top: 5px;">
                                <span class="badge" style="background-color: #2b6cb0;">Durasi: {{ $quiz['duration_minutes'] }} Menit</span>
                                <span class="badge" style="background-color: #d97706;">KKM: {{ $quiz['passing_score'] }}</span>
                                <span class="badge" style="background-color: #38a169;">Acak Soal: {{ $quiz['randomize_questions'] ? 'Ya' : 'Tidak' }}</span>
                            </div>
                        </div>

                        <h5 style="font-weight: 700; color: #2d3748; margin-bottom: 15px;">Daftar Butir Soal CBT:</h5>

                        @forelse($quiz['questions'] as $qItem)
                            <div class="question-box">
                                <div class="clearfix" style="margin-bottom: 8px;">
                                    <strong style="font-size: 14px; color: #1a365d;">Nomor {{ $qItem['number'] }}.</strong>
                                    <span class="label label-primary" style="margin-left: 5px; text-transform: uppercase;">{{ str_replace('_', ' ', $qItem['type']) }}</span>
                                    <span class="pull-right text-muted" style="font-size: 12px;">Bobot Skor: <strong>{{ $qItem['score_weight'] }} Poin</strong></span>
                                </div>
                                <p style="font-size: 14px; color: #2d3748; line-height: 1.5;">
                                    {{ $qItem['question'] }}
                                </p>

                                @if($qItem['type'] === 'pilihan_ganda' && !empty($qItem['options']))
                                    <ul class="option-list">
                                        @foreach($qItem['options'] as $optKey => $optVal)
                                            <li class="{{ $optKey === $qItem['correct_answer'] ? 'correct' : '' }}">
                                                <span class="badge" style="background-color: {{ $optKey === $qItem['correct_answer'] ? '#38a169' : '#a0aec0' }}; margin-right: 6px;">{{ $optKey }}</span>
                                                {{ $optVal }}
                                                @if($optKey === $qItem['correct_answer'])
                                                    <span class="pull-right text-success"><i class="fa fa-check-circle"></i> Kunci Jawaban Benar</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div style="background: #fff; padding: 10px; border: 1px dashed #cbd5e0; border-radius: 4px; font-size: 13px;">
                                        <strong>Rubrik / Kunci Jawaban:</strong>
                                        <p class="text-muted" style="margin-bottom: 0;">{{ $qItem['correct_answer'] }}</p>
                                    </div>
                                @endif

                                @if(!empty($qItem['explanation']))
                                    <div style="margin-top: 8px; font-size: 12px; color: #718096; background: #edf2f7; padding: 6px 10px; border-radius: 4px;">
                                        <i class="fa fa-info-circle text-primary"></i> <strong>Pembahasan:</strong> {{ $qItem['explanation'] }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-muted" style="padding: 30px;">
                                <i class="fa fa-folder-open-o fa-3x"></i>
                                <p>Belum ada butir soal pada paket ujian ini.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Rekap Nilai Peserta -->
        <div class="modal fade" id="modalRekapNilai-{{ $quiz['id'] }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius: 6px;">
                    <div class="modal-header" style="background: #2b6cb0; color: #fff; border-radius: 6px 6px 0 0;">
                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                        <h4 class="modal-title font-weight-bold">
                            <i class="fa fa-bar-chart"></i> Rekapitulasi Hasil Ujian Santri: {{ $quiz['title'] }}
                        </h4>
                    </div>
                    <div class="modal-body">
                        @php
                            $attempts = \App\Support\DemoData\DemoDataManager::getInstance()->getQuizAttempts($quiz['id']);
                        @endphp

                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-6">
                                <strong>Rombel Target:</strong> {{ $quiz['class_name'] }}<br>
                                <strong>Passing Grade (KKM):</strong> <span class="label label-danger">{{ $quiz['passing_score'] }} Poin</span>
                            </div>
                            <div class="col-md-6 text-right">
                                <strong>Total Peserta Mengerjakan:</strong> <span class="label label-success">{{ count($attempts) }} Santri</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th>Nama Santri</th>
                                        <th class="text-center">NIS</th>
                                        <th class="text-center">Waktu Pengerjaan</th>
                                        <th class="text-center">Durasi</th>
                                        <th class="text-center">Benar / Salah</th>
                                        <th class="text-center">Skor Akhir</th>
                                        <th class="text-center">Hasil KKM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attempts as $idx => $att)
                                        <tr>
                                            <td class="text-center">{{ $idx + 1 }}</td>
                                            <td><strong>{{ $att['student_name'] }}</strong></td>
                                            <td class="text-center font-monospace">{{ $att['nis'] }}</td>
                                            <td class="text-center" style="font-size: 12px;">{{ date('d/m/Y H:i', strtotime($att['started_at'])) }}</td>
                                            <td class="text-center">{{ $att['duration_used'] }}</td>
                                            <td class="text-center">
                                                <span class="text-success"><i class="fa fa-check"></i> {{ $att['correct_count'] }}</span>
                                                &nbsp;/&nbsp;
                                                <span class="text-danger"><i class="fa fa-times"></i> {{ $att['wrong_count'] }}</span>
                                            </td>
                                            <td class="text-center" style="font-weight: 700; font-size: 15px; color: {{ $att['total_score'] >= $quiz['passing_score'] ? '#2b6cb0' : '#e53e3e' }};">
                                                {{ $att['total_score'] }}
                                            </td>
                                            <td class="text-center">
                                                @if($att['is_passed'])
                                                    <span class="label label-success"><i class="fa fa-check"></i> Lulus</span>
                                                @else
                                                    <span class="label label-danger"><i class="fa fa-times"></i> Remedial</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                                Belum ada data pengerjaan ujian dari santri.
                                            </td>
                                        </tr>
                                    @endforelse
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
        <div class="col-xs-12">
            <div class="box box-solid" style="border-radius: 6px; padding: 40px; text-align: center;">
                <i class="fa fa-laptop fa-4x text-muted" style="margin-bottom: 15px;"></i>
                <h4 style="font-weight: 700; color: #4a5568;">Tidak ada paket ujian CBT ditemukan</h4>
                <p class="text-muted">Silakan sesuaikan filter pencarian atau buat paket ujian online baru.</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahUjian">
                    <i class="fa fa-plus-circle"></i> Buat Paket Ujian Baru
                </button>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal Tambah Paket Ujian Baru -->
<div class="modal fade" id="modalTambahUjian" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('lms.ujian.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-plus-circle"></i> Buat Paket Ujian Online / CBT Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label for="title">Judul Ujian / Kuis <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: PTS Ganjil Fikih Ibadah" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="code">Kode CBT <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Contoh: CBT-FKH-10" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="subject_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id" class="form-control" required>
                                    @foreach($subjects as $sbj)
                                        <option value="{{ $sbj['id'] }}">{{ $sbj['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="class_id">Target Rombel <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    @foreach($classes as $c)
                                        <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="teacher_id">Guru Pengampu <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    @foreach($teachers as $tch)
                                        <option value="{{ $tch['id'] }}">{{ $tch['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="category">Kategori Evaluasi <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="Kuis Harian">Kuis Harian</option>
                                    <option value="PTS">PTS (Penilaian Tengah Semester)</option>
                                    <option value="PAS">PAS (Penilaian Akhir Semester)</option>
                                    <option value="Tryout">Try Out Ujian Mandiri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="duration_minutes">Durasi Waktu (Menit) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="60" min="5" max="180" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="passing_score">KKM / Passing Grade <span class="text-danger">*</span></label>
                                <input type="number" name="passing_score" id="passing_score" class="form-control" value="75" min="0" max="100" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="instructions">Petunjuk Pengerjaan Santri</label>
                        <textarea name="instructions" id="instructions" class="form-control" rows="2" placeholder="Tuliskan panduan singkat pengerjaan ujian online bagi santri...">Bacalah basmalah sebelum memulai ujian. Kerjakan soal secara mandiri dan jujur.</textarea>
                    </div>

                    <div class="form-group">
                        <label for="sample_question">Butir Soal Pertama (Contoh Generator)</label>
                        <textarea name="sample_question" id="sample_question" class="form-control" rows="2" placeholder="Tuliskan pertanyaan pembuka untuk bank soal kuis..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save"></i> Terbitkan Paket Ujian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

