@extends('layouts.app')

@section('title', 'Buku Jurnal Murojaah & Ujian - Tahfidz')

@push('styles')
<style>
    .metric-card-murojaah {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-murojaah:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-murojaah {
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
    .badge-mutqin { background-color: #276749; color: #fff; }
    .badge-cukup-lancar { background-color: #2b6cb0; color: #fff; }
    .badge-perlu-ulang { background-color: #c53030; color: #fff; }
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
            <i class="fa fa-repeat text-primary"></i> Tahfidz: Pemantauan Murojaah & Ujian Kelancaran Mutqin
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Buku mutaba'ah pengulangan hafalan mutqin santri (Harian Rubu', Mingguan Setengah Juz, Tasmi' Bil Ghoib 1 Juz Sekali Duduk, dan Ujian Kenaikan Juz).
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInputMurojaah" style="border-radius: 4px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Catat Murojaah / Tasmi'
        </button>
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak Jurnal
        </button>
    </div>
</div>

<!-- Metrik Statistik Murojaah -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-murojaah clearfix">
            <div class="metric-icon-murojaah" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-book"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Total Catatan Murojaah</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_murojaah'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Sesi penyimakan musyrif</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-murojaah clearfix">
            <div class="metric-icon-murojaah" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-check-circle"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Hafalan Mutqin</div>
                <div style="font-size: 24px; font-weight: 700; color: #38a169;">{{ $stats['total_mutqin'] }} Sesi</div>
                <div class="text-success" style="font-size: 11px;"><i class="fa fa-star"></i> Teruji sangat lancar</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-murojaah clearfix">
            <div class="metric-icon-murojaah" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-microphone"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Tasmi' 1 Juz Penuh</div>
                <div style="font-size: 24px; font-weight: 700; color: #6b46c1;">{{ $stats['total_tasmi'] }} Santri</div>
                <div class="text-purple" style="font-size: 11px;">Ujian sekali duduk</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-murojaah clearfix">
            <div class="metric-icon-murojaah" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-refresh"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Frekuensi Pengulangan</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_repetitions'] }}x</div>
                <div class="text-warning" style="font-size: 11px;">Akumulasi tikrar mandiri</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; margin-bottom: 20px;">
    <div class="box-body" style="padding: 15px;">
        <form method="GET" action="{{ route('tahfidz.murojaah') }}" class="form-inline">
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
                <label for="filter_type" style="font-weight: 600; margin-right: 6px;">Jenis Ujian:</label>
                <select name="type" id="filter_type" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis --</option>
                    <option value="Harian (Rubu')" {{ $typeFilter === "Harian (Rubu')" ? 'selected' : '' }}>Harian (Rubu')</option>
                    <option value="Mingguan (1/2 Juz)" {{ $typeFilter === "Mingguan (1/2 Juz)" ? 'selected' : '' }}>Mingguan (1/2 Juz)</option>
                    <option value="Tasmi' Bil Ghoib (1 Juz Penuh)" {{ $typeFilter === "Tasmi' Bil Ghoib (1 Juz Penuh)" ? 'selected' : '' }}>Tasmi' Bil Ghoib (1 Juz Penuh)</option>
                    <option value="Ujian Kenaikan Juz" {{ $typeFilter === "Ujian Kenaikan Juz" ? 'selected' : '' }}>Ujian Kenaikan Juz</option>
                </select>
            </div>
            <div class="form-group" style="margin-right: 12px;">
                <label for="filter_fluency" style="font-weight: 600; margin-right: 6px;">Kelancaran:</label>
                <select name="fluency" id="filter_fluency" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="Mutqin" {{ $fluencyFilter === 'Mutqin' ? 'selected' : '' }}>Mutqin</option>
                    <option value="Cukup Lancar" {{ $fluencyFilter === 'Cukup Lancar' ? 'selected' : '' }}>Cukup Lancar</option>
                    <option value="Perlu Pengulangan" {{ $fluencyFilter === 'Perlu Pengulangan' ? 'selected' : '' }}>Perlu Pengulangan</option>
                </select>
            </div>
            <div class="form-group" style="margin-right: 12px;">
                <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari:</label>
                <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Santri, rentang surat..." value="{{ $search }}" style="width: 200px;">
            </div>
            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-filter"></i> Terapkan</button>
            @if($selectedHalaqahId || $typeFilter || $fluencyFilter || $search)
                <a href="{{ route('tahfidz.murojaah') }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Jurnal Murojaah & Ujian -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <div class="box-header with-border" style="padding: 12px 18px; background: #f8fafc;">
        <h4 class="box-title font-weight-bold" style="color: #1a365d; font-size: 15px;">
            <i class="fa fa-list"></i> Riwayat Pengulangan Murojaah & Ujian Kelancaran
        </h4>
        <div class="box-tools pull-right" style="margin-top: 3px;">
            <span class="label label-primary">{{ count($records) }} Catatan Murojaah</span>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 13px; margin-bottom: 0;">
                <thead>
                    <tr style="background: #edf2f7; color: #2d3748;">
                        <th style="width: 40px;" class="text-center">No</th>
                        <th style="width: 110px;" class="text-center">Tanggal & Sesi</th>
                        <th>Santri & Halaqah</th>
                        <th>Jenis Evaluasi</th>
                        <th>Juz & Rentang Surat</th>
                        <th class="text-center" style="width: 90px;">Tikrar</th>
                        <th class="text-center" style="width: 130px;">Status Kelancaran</th>
                        <th class="text-center" style="width: 110px;">Skor & Nilai</th>
                        <th>Musyrif & Evaluasi Catatan</th>
                        <th class="text-center" style="width: 70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $idx => $r)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                            <td class="text-center" style="vertical-align: middle;">
                                <strong>{{ date('d/m/Y', strtotime($r['date'])) }}</strong><br>
                                <span class="label label-default" style="font-size: 10px;">Sesi {{ $r['session'] }}</span>
                            </td>
                            <td style="vertical-align: middle;">
                                <strong style="color: #1a365d; font-size: 14px;">{{ $r['student_name'] }}</strong><br>
                                <span class="text-muted" style="font-size: 11px;">
                                    NIS: {{ $r['nis'] }} &bull; {{ $r['halaqah_name'] }}
                                </span>
                            </td>
                            <td style="vertical-align: middle;">
                                @if(str_contains($r['type'], 'Tasmi'))
                                    <span class="label label-warning" style="font-size: 11px;"><i class="fa fa-microphone"></i> {{ $r['type'] }}</span>
                                @elseif(str_contains($r['type'], 'Mingguan'))
                                    <span class="label label-primary" style="font-size: 11px;">{{ $r['type'] }}</span>
                                @else
                                    <span class="label label-default" style="font-size: 11px;">{{ $r['type'] }}</span>
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                <span class="badge" style="background-color: #6b46c1;">Juz {{ $r['juz'] }}</span>
                                <strong style="color: #2b6cb0; margin-left: 4px;">{{ $r['surah_range'] }}</strong>
                            </td>
                            <td class="text-center" style="vertical-align: middle; font-weight: 600;">
                                {{ $r['repetition_count'] }}x Tikrar
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if($r['fluency_status'] === 'Mutqin')
                                    <span class="badge badge-mutqin"><i class="fa fa-check-circle"></i> Mutqin</span>
                                @elseif($r['fluency_status'] === 'Cukup Lancar')
                                    <span class="badge badge-cukup-lancar">Cukup Lancar</span>
                                @else
                                    <span class="badge badge-perlu-ulang"><i class="fa fa-refresh"></i> Perlu Ulang</span>
                                @endif
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span style="font-size: 15px; font-weight: 700; color: {{ $r['score'] >= 80 ? '#276749' : '#c53030' }};">
                                    {{ $r['score'] }}
                                </span><br>
                                <span class="text-muted" style="font-size: 11px;">{{ $r['grade'] }}</span>
                            </td>
                            <td style="vertical-align: middle; font-size: 12px;">
                                <div><i class="fa fa-user-circle text-muted"></i> <strong>{{ $r['supervisor_name'] }}</strong></div>
                                <div class="text-muted font-italic" style="margin-top: 2px; color: #4a5568;">
                                    "{{ $r['evaluation_notes'] }}"
                                </div>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <a href="{{ route('tahfidz.murojaah.destroy', $r['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus riwayat murojaah ini?')" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                <i class="fa fa-repeat fa-3x text-muted" style="margin-bottom: 10px;"></i>
                                <p>Tidak ada catatan murojaah atau tasmi' ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Murojaah Baru -->
<div class="modal fade" id="modalInputMurojaah" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('tahfidz.murojaah.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-repeat"></i> Catat Evaluasi Murojaah / Tasmi' Santri
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_student">Santri Yang Murojaah <span class="text-danger">*</span></label>
                                <select name="student_id" id="mrj_student" class="form-control" required>
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
                                <label for="mrj_hlq">Kelompok Halaqah <span class="text-danger">*</span></label>
                                <select name="halaqah_id" id="mrj_hlq" class="form-control" required>
                                    @foreach($halaqahs as $hlq)
                                        <option value="{{ $hlq['id'] }}">
                                            {{ $hlq['name'] }} (Musyrif: {{ $hlq['supervisor_name'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_date">Tanggal Evaluasi <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="mrj_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_session">Sesi Waktu <span class="text-danger">*</span></label>
                                <select name="session" id="mrj_session" class="form-control" required>
                                    <option value="Ba'da Subuh">Ba'da Subuh</option>
                                    <option value="Pagi">Pagi Dhuha</option>
                                    <option value="Ashar">Ba'da Ashar</option>
                                    <option value="Maghrib">Ba'da Maghrib</option>
                                    <option value="Isya">Ba'da Isya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_teacher">Musyrif Penguji <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="mrj_teacher" class="form-control" required>
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
                                <label for="mrj_type">Jenis Evaluasi Murojaah <span class="text-danger">*</span></label>
                                <select name="type" id="mrj_type" class="form-control" required>
                                    <option value="Harian (Rubu')">Harian (Rubu')</option>
                                    <option value="Mingguan (1/2 Juz)">Mingguan (1/2 Juz)</option>
                                    <option value="Tasmi' Bil Ghoib (1 Juz Penuh)">Tasmi' Bil Ghoib (1 Juz Penuh)</option>
                                    <option value="Ujian Kenaikan Juz">Ujian Kenaikan Juz</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="mrj_juz">Juz <span class="text-danger">*</span></label>
                                <input type="number" name="juz" id="mrj_juz" class="form-control" value="30" min="1" max="30" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_range">Rentang Surat / Ayat <span class="text-danger">*</span></label>
                                <input type="text" name="surah_range" id="mrj_range" class="form-control" placeholder="Contoh: An-Naba' s/d An-Nas" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_rep">Frekuensi Pengulangan (Tikrar) <span class="text-danger">*</span></label>
                                <input type="number" name="repetition_count" id="mrj_rep" class="form-control" value="4" min="1" max="50" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_fluency">Status Kelancaran <span class="text-danger">*</span></label>
                                <select name="fluency_status" id="mrj_fluency" class="form-control" required>
                                    <option value="Mutqin">Mutqin (Sangat Lancar & Tanpa Ragu)</option>
                                    <option value="Cukup Lancar">Cukup Lancar (1-2 Kali Teguran)</option>
                                    <option value="Perlu Pengulangan">Perlu Pengulangan (&ge;3 Kali Teguran)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="mrj_score">Skor Nilai (0 - 100) <span class="text-danger">*</span></label>
                                <input type="number" name="score" id="mrj_score" class="form-control" value="95" min="0" max="100" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mrj_notes">Catatan & Rekomendasi Musyrif</label>
                        <textarea name="evaluation_notes" id="mrj_notes" class="form-control" rows="2" placeholder="Catatan waqaf, mutasyabihat, atau kesiapan tasmi' juz berikutnya..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save"></i> Simpan Catatan Murojaah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

