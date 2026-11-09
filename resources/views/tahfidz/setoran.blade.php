@extends('layouts.app')

@section('title', 'Buku Jurnal Setoran Ziyadah - Tahfidz')

@push('styles')
<style>
    .metric-card-setoran {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-setoran:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-setoran {
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
    .grade-badge-mumtaz { background-color: #276749; color: #fff; }
    .grade-badge-jayyid-jiddan { background-color: #2b6cb0; color: #fff; }
    .grade-badge-jayyid { background-color: #d69e2e; color: #fff; }
    .grade-badge-maqbul { background-color: #c53030; color: #fff; }
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
            <i class="fa fa-microphone text-primary"></i> Tahfidz: Jurnal Buku Setoran Hafalan Baru (Ziyadah)
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Pencatatan mutaba'ah harian setoran surat & ayat baru santri, penilaian kelancaran, makharijul huruf, kaidah tajwid, dan feedback musyrif halaqah.
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInputSetoran" style="border-radius: 4px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Input Setoran Baru
        </button>
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak Jurnal
        </button>
    </div>
</div>

<!-- Metrik Statistik Setoran Ziyadah -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-setoran clearfix">
            <div class="metric-icon-setoran" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-bookmark"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Total Riwayat Setoran</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_setoran'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Entri mutaba'ah ziyadah</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-setoran clearfix">
            <div class="metric-icon-setoran" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-book"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Akumulasi Ayat</div>
                <div style="font-size: 24px; font-weight: 700; color: #6b46c1;">{{ $stats['total_ayat'] }} Ayat</div>
                <div class="text-purple" style="font-size: 11px;">Tersimak oleh musyrif</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-setoran clearfix">
            <div class="metric-icon-setoran" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-star"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Predikat Mumtaz</div>
                <div style="font-size: 24px; font-weight: 700; color: #38a169;">{{ $stats['total_mumtaz'] }}</div>
                <div class="text-success" style="font-size: 11px;"><i class="fa fa-check"></i> Skor 90 - 100 poin</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-setoran clearfix">
            <div class="metric-icon-setoran" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-line-chart"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Rata-rata Skor</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['avg_score'] }}</div>
                <div class="text-warning" style="font-size: 11px;">Skala 100 poin</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; margin-bottom: 20px;">
    <div class="box-body" style="padding: 15px;">
        <form method="GET" action="{{ route('tahfidz.setoran') }}" class="form-inline">
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
                <label for="filter_grade" style="font-weight: 600; margin-right: 6px;">Predikat:</label>
                <select name="grade" id="filter_grade" class="form-control input-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Predikat --</option>
                    <option value="Mumtaz" {{ $gradeFilter === 'Mumtaz' ? 'selected' : '' }}>Mumtaz (90-100)</option>
                    <option value="Jayyid Jiddan" {{ $gradeFilter === 'Jayyid Jiddan' ? 'selected' : '' }}>Jayyid Jiddan (80-89)</option>
                    <option value="Jayyid" {{ $gradeFilter === 'Jayyid' ? 'selected' : '' }}>Jayyid (70-79)</option>
                    <option value="Maqbul" {{ $gradeFilter === 'Maqbul' ? 'selected' : '' }}>Maqbul (&lt; 70)</option>
                </select>
            </div>
            <div class="form-group" style="margin-right: 12px;">
                <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari:</label>
                <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Nama santri, surat, musyrif..." value="{{ $search }}" style="width: 220px;">
            </div>
            <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-filter"></i> Terapkan</button>
            @if($selectedHalaqahId || $gradeFilter || $search)
                <a href="{{ route('tahfidz.setoran') }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Jurnal Setoran Ziyadah -->
<div class="box box-solid" style="border-radius: 6px; border: 1px solid #e1e8ed; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <div class="box-header with-border" style="padding: 12px 18px; background: #f8fafc;">
        <h4 class="box-title font-weight-bold" style="color: #1a365d; font-size: 15px;">
            <i class="fa fa-list"></i> Buku Jurnal Mutaba'ah Setoran Santri
        </h4>
        <div class="box-tools pull-right" style="margin-top: 3px;">
            <span class="label label-success">{{ count($records) }} Catatan Tersaring</span>
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
                        <th>Materi Hafalan (Surat & Ayat)</th>
                        <th class="text-center" style="width: 90px;">Juz</th>
                        <th class="text-center" style="width: 90px;">Jml Ayat</th>
                        <th class="text-center" style="width: 140px;">Predikat & Skor</th>
                        <th>Musyrif & Catatan Tajwid</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
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
                                <span style="font-weight: 700; color: #2b6cb0; font-size: 14px;">
                                    QS. {{ $r['surah_name'] }}
                                </span><br>
                                <span class="text-muted" style="font-size: 12px;">
                                    Ayat <strong>{{ $r['ayat_start'] }}</strong> s/d <strong>{{ $r['ayat_end'] }}</strong>
                                </span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="badge" style="background-color: #6b46c1;">Juz {{ $r['juz'] }}</span>
                            </td>
                            <td class="text-center" style="vertical-align: middle; font-weight: 600;">
                                {{ $r['total_ayat'] }} Ayat
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if($r['grade'] === 'Mumtaz')
                                    <span class="badge grade-badge-mumtaz" style="font-size: 12px;">Mumtaz ({{ $r['score'] }})</span>
                                @elseif($r['grade'] === 'Jayyid Jiddan')
                                    <span class="badge grade-badge-jayyid-jiddan" style="font-size: 12px;">Jayyid Jiddan ({{ $r['score'] }})</span>
                                @elseif($r['grade'] === 'Jayyid')
                                    <span class="badge grade-badge-jayyid" style="font-size: 12px;">Jayyid ({{ $r['score'] }})</span>
                                @else
                                    <span class="badge grade-badge-maqbul" style="font-size: 12px;">Maqbul ({{ $r['score'] }})</span>
                                @endif
                                <div style="font-size: 10px; color: #718096; margin-top: 2px;">
                                    Tajwid: {{ $r['tajwid_quality'] }}
                                </div>
                            </td>
                            <td style="vertical-align: middle; font-size: 12px;">
                                <div><i class="fa fa-user-circle text-muted"></i> <strong>{{ $r['supervisor_name'] }}</strong></div>
                                <div class="text-muted font-italic" style="margin-top: 2px; color: #4a5568;">
                                    "{{ $r['notes'] }}"
                                </div>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <a href="{{ route('tahfidz.setoran.destroy', $r['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus riwayat setoran ini?')" title="Hapus Setoran">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                <i class="fa fa-book fa-3x text-muted" style="margin-bottom: 10px;"></i>
                                <p>Tidak ada catatan setoran hafalan ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Setoran Ziyadah Baru -->
<div class="modal fade" id="modalInputSetoran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('tahfidz.setoran.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-microphone"></i> Catat Setoran Hafalan Baru (Ziyadah)
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="setoran_student">Santri Yang Menyetor <span class="text-danger">*</span></label>
                                <select name="student_id" id="setoran_student" class="form-control" required>
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
                                <label for="setoran_hlq">Kelompok Halaqah <span class="text-danger">*</span></label>
                                <select name="halaqah_id" id="setoran_hlq" class="form-control" required>
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
                                <label for="setoran_date">Tanggal Setoran <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="setoran_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="setoran_session">Sesi Waktu <span class="text-danger">*</span></label>
                                <select name="session" id="setoran_session" class="form-control" required>
                                    <option value="Subuh">Ba'da Subuh</option>
                                    <option value="Pagi">Pagi Dhuha</option>
                                    <option value="Ashar">Ba'da Ashar</option>
                                    <option value="Maghrib">Ba'da Maghrib</option>
                                    <option value="Isya">Ba'da Isya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="setoran_teacher">Musyrif Penyimak <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="setoran_teacher" class="form-control" required>
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
                                <label for="setoran_surah">Nama Surat <span class="text-danger">*</span></label>
                                <input type="text" name="surah_name" id="setoran_surah" class="form-control" placeholder="Contoh: Al-Baqarah / Ar-Rahman" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="setoran_juz">Juz <span class="text-danger">*</span></label>
                                <input type="number" name="juz" id="setoran_juz" class="form-control" value="1" min="1" max="30" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="ayat_start">Ayat Awal <span class="text-danger">*</span></label>
                                <input type="number" name="ayat_start" id="ayat_start" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="ayat_end">Ayat Akhir <span class="text-danger">*</span></label>
                                <input type="number" name="ayat_end" id="ayat_end" class="form-control" value="20" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="setoran_score">Skor Nilai (0 - 100) <span class="text-danger">*</span></label>
                                <input type="number" name="score" id="setoran_score" class="form-control" value="90" min="0" max="100" required>
                                <span class="help-block" style="font-size: 11px;">
                                    &ge;90: Mumtaz, 80-89: Jayyid Jiddan, 70-79: Jayyid, &lt;70: Maqbul.
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="setoran_tajwid">Kualitas Tajwid <span class="text-danger">*</span></label>
                                <select name="tajwid_quality" id="setoran_tajwid" class="form-control" required>
                                    <option value="Sangat Baik">Sangat Baik (Kaidah Lengkap)</option>
                                    <option value="Baik">Baik (Sedikit Catatan)</option>
                                    <option value="Cukup">Cukup (Perlu Pengulangan)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="setoran_makhraj">Kualitas Makharijul Huruf <span class="text-danger">*</span></label>
                                <select name="makhraj_quality" id="setoran_makhraj" class="form-control" required>
                                    <option value="Fasih & Jelas">Fasih & Jelas</option>
                                    <option value="Jelas">Jelas</option>
                                    <option value="Kurang Fasih">Kurang Fasih</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="setoran_notes">Catatan Pembinaan Musyrif</label>
                        <textarea name="notes" id="setoran_notes" class="form-control" rows="2" placeholder="Tuliskan catatan kelancaran, waqaf/ibtida', atau ayat yang perlu diperkuat..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save"></i> Simpan Catatan Setoran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

