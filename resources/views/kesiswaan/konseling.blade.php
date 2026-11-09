@extends('layouts.app')

@section('title', 'Bimbingan Konseling (BK) — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-comments text-primary"></i> Layanan Bimbingan & Konseling (BK)
        <small>Pendampingan Psikologis, Pembinaan Karakter, Bimbingan Belajar & Konsultasi Karier Santri</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Kesiswaan</li>
        <li class="active">Bimbingan Konseling</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Layanan BK --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-folder-open-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Sesi BK</span>
                <span class="info-box-number">{{ $totalSesi }} <small style="color: #cbd5e0; font-size: 11px;">Konsultasi</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Buku Log Layanan BK</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-hourglass-half"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dalam Proses / RTL</span>
                <span class="info-box-number">{{ $totalDalamProses }} <small style="color: #fff; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Rencana Tindak Lanjut Aktif</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tuntas / Selesai</span>
                <span class="info-box-number">{{ $totalSelesai }} <small style="color: #d1fae5; font-size: 11px;">Sesi</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Masalah Terselesaikan Baik</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-exchange"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rujukan Terpadu</span>
                <span class="info-box-number">{{ $totalRujukan }} <small style="color: #e9d8fd; font-size: 11px;">Kasus</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Dari Asrama & Tata Tertib</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert Notifikasi Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
    </div>
@endif

<div class="row">
    {{-- Kolom Kiri: Tabel Jurnal Konseling --}}
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book text-primary"></i> Jurnal Layanan Konseling & Pembinaan</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKonseling">
                        <i class="fa fa-plus-circle"></i> Catat Sesi Konseling Baru
                    </button>
                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                        <i class="fa fa-print"></i> Cetak Jurnal
                    </button>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <form method="GET" action="{{ route('kesiswaan.konseling') }}" class="form-inline">
                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="category">Bidang Masalah</label>
                        <select name="category" class="form-control input-sm">
                            <option value="">-- Semua Bidang --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="status">Status</label>
                        <select name="status" class="form-control input-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="dijadwalkan" {{ $statusFilter === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="dalam_proses" {{ $statusFilter === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses / RTL</option>
                            <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dirujuk" {{ $statusFilter === 'dirujuk' ? 'selected' : '' }}>Dirujuk Ahli</option>
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="service_type">Layanan</label>
                        <select name="service_type" class="form-control input-sm">
                            <option value="">-- Semua Jenis Layanan --</option>
                            @foreach($serviceTypes as $st)
                                <option value="{{ $st }}" {{ $serviceTypeFilter === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari santri / konselor / masalah..." style="width: 170px;">
                    </div>

                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
                    @if($categoryFilter || $statusFilter || $serviceTypeFilter || $search)
                        <a href="{{ route('kesiswaan.konseling') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
                    @endif
                </form>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr style="background-color: #f4f6f9; color: #334155;">
                            <th style="width: 40px; text-align: center;">#</th>
                            <th>Santri & Kelas</th>
                            <th>Bidang & Jenis Layanan</th>
                            <th>Konselor & Waktu</th>
                            <th>Ringkasan Masalah & RTL</th>
                            <th style="text-align: center;">Status</th>
                            <th style="width: 85px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $index => $cs)
                            @php
                                $std = $studentMap[$cs['student_id']] ?? null;
                                $cls = $std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null;
                                
                                $statusBadge = match($cs['status']) {
                                    'selesai' => '<span class="label label-success"><i class="fa fa-check"></i> Selesai</span>',
                                    'dalam_proses' => '<span class="label label-warning"><i class="fa fa-clock-o"></i> Dalam Proses</span>',
                                    'dijadwalkan' => '<span class="label label-info"><i class="fa fa-calendar"></i> Dijadwalkan</span>',
                                    'dirujuk' => '<span class="label label-danger"><i class="fa fa-ambulance"></i> Dirujuk</span>',
                                    default => '<span class="label label-default">Draft</span>'
                                };
                            @endphp
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $std['name'] ?? 'Santri #'.$cs['student_id'] }}</strong><br>
                                    <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</small><br>
                                    <small class="text-info"><i class="fa fa-share-alt"></i> {{ $cs['referral_from'] }}</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span style="font-weight: 600; color: #1e3a8a;">{{ $cs['category'] }}</span><br>
                                    <span class="badge bg-purple" style="font-size: 10px; margin-top: 3px;">{{ $cs['service_type'] }}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $cs['counselor_name'] }}</strong><br>
                                    <small class="text-muted"><i class="fa fa-calendar"></i> {{ date('d/m/Y', strtotime($cs['date'])) }}</small><br>
                                    <small class="text-muted"><i class="fa fa-clock-o"></i> {{ $cs['time'] }} WIB</small>
                                </td>
                                <td style="vertical-align: middle; max-width: 250px;">
                                    <div style="font-size: 12px; margin-bottom: 4px; color: #1f2937;">
                                        <strong>Kasus:</strong> {{ Str::limit($cs['problem_summary'], 75) }}
                                    </div>
                                    <div style="font-size: 11px; color: #047857;">
                                        <strong>RTL:</strong> {{ Str::limit($cs['action_plan'], 70) }}
                                    </div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    {!! $statusBadge !!}<br>
                                    <small class="text-muted" style="font-size: 10px;">Eval: {{ date('d/m', strtotime($cs['follow_up_date'])) }}</small>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailKonseling{{ $cs['id'] }}" title="Lihat Rekam Lengkap Konseling">
                                            <i class="fa fa-eye text-primary"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalUpdateStatus{{ $cs['id'] }}" title="Perbarui Status & RTL">
                                            <i class="fa fa-edit text-warning"></i>
                                        </button>
                                        <a href="{{ route('kesiswaan.konseling.destroy', $cs['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus berkas konseling ini?')" title="Hapus Berkas">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Detail Rekam Konseling Santri --}}
                            <div class="modal fade" id="modalDetailKonseling{{ $cs['id'] }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                                            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-user-md text-yellow"></i> Berkas Layanan Bimbingan & Konseling [{{ $cs['id'] }}]</h4>
                                        </div>
                                        <div class="modal-body" style="background-color: #fdfdfd;">
                                            <div class="text-center" style="margin-bottom: 15px; padding: 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px;">
                                                <h4 style="margin: 0 0 5px 0; font-weight: bold; color: #1e3a8a;">{{ $std['name'] ?? '-' }}</h4>
                                                <span class="label label-primary">{{ $cls['name'] ?? '-' }}</span> &nbsp;
                                                <span class="label label-info">NIS: {{ $std['nis'] ?? '-' }}</span> &nbsp;
                                                {!! $statusBadge !!}
                                            </div>

                                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                <tr>
                                                    <th style="width: 35%;">Guru BK / Konselor</th>
                                                    <td><strong>{{ $cs['counselor_name'] }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <th>Waktu Pertemuan</th>
                                                    <td>{{ date('l, d F Y', strtotime($cs['date'])) }} | Pukul {{ $cs['time'] }} WIB</td>
                                                </tr>
                                                <tr>
                                                    <th>Bidang Masalah</th>
                                                    <td><span class="label label-warning">{{ $cs['category'] }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Layanan</th>
                                                    <td><span class="badge bg-purple">{{ $cs['service_type'] }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Asal Rujukan</th>
                                                    <td>{{ $cs['referral_from'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Uraian Permasalahan / Keluhan</th>
                                                    <td style="background: #fff; line-height: 1.5;">{{ $cs['problem_summary'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Rencana Tindak Lanjut (RTL) & Solusi</th>
                                                    <td style="background: #f0fdf4; color: #166534; font-weight: 500;">{{ $cs['action_plan'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Evaluasi Lanjutan</th>
                                                    <td><strong>{{ date('d F Y', strtotime($cs['follow_up_date'])) }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <th>Catatan Kerahasiaan Konselor</th>
                                                    <td><em>"{{ $cs['notes'] }}"</em></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Berkas Konseling</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Update Status & RTL --}}
                            <div class="modal fade" id="modalUpdateStatus{{ $cs['id'] }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('kesiswaan.konseling.update-status', $cs['id']) }}">
                                            @csrf
                                            <div class="modal-header" style="background-color: #3b82f6; color: #fff;">
                                                <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                                <h4 class="modal-title"><i class="fa fa-edit"></i> Update Progres & RTL Konseling [{{ $cs['id'] }}]</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="status_{{ $cs['id'] }}">Status Penanganan <span class="text-danger">*</span></label>
                                                    <select name="status" id="status_{{ $cs['id'] }}" class="form-control" required>
                                                        <option value="dijadwalkan" {{ $cs['status'] === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                                                        <option value="dalam_proses" {{ $cs['status'] === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses (Pendampingan Berjalan)</option>
                                                        <option value="selesai" {{ $cs['status'] === 'selesai' ? 'selected' : '' }}>Selesai / Tuntas</option>
                                                        <option value="dirujuk" {{ $cs['status'] === 'dirujuk' ? 'selected' : '' }}>Dirujuk ke Psikolog Luar / Orang Tua</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="action_plan_{{ $cs['id'] }}">Perbarui Rencana Tindak Lanjut (RTL)</label>
                                                    <textarea name="action_plan" id="action_plan_{{ $cs['id'] }}" rows="3" class="form-control">{{ $cs['action_plan'] }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="notes_{{ $cs['id'] }}">Catatan Tambahan Konselor</label>
                                                    <textarea name="notes" id="notes_{{ $cs['id'] }}" rows="2" class="form-control">{{ $cs['notes'] }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Pembaruan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-info-circle fa-2x"></i><br>
                                    Belum ada rekam data layanan konseling yang cocok dengan kriteria pencarian/filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix" style="background-color: #f9fafb;">
                <span class="text-muted pull-left">Menampilkan <strong>{{ count($sessions) }}</strong> dari <strong>{{ count($allSessions) }}</strong> rekam layanan BK santri.</span>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: 4 Pilar Layanan BK & Alur Rujukan Terpadu --}}
    <div class="col-md-4">
        {{-- 4 Pilar Bimbingan & Konseling Islam --}}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-compass text-primary"></i> 4 Bidang Layanan BK Islam</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li style="border-bottom: 1px solid #f1f5f9;">
                        <a href="javascript:void(0)" style="color: #334155;">
                            <span class="badge bg-blue pull-right">Akademik</span>
                            <i class="fa fa-graduation-cap text-blue"></i> <strong>Bimbingan Belajar</strong>
                            <p style="margin: 3px 0 0 0; font-size: 11px; color: #64748b;">Gaya belajar santri, manajemen waktu tahfidz vs sains, reduksi kecemasan ujian.</p>
                        </a>
                    </li>
                    <li style="border-bottom: 1px solid #f1f5f9;">
                        <a href="javascript:void(0)" style="color: #334155;">
                            <span class="badge bg-green pull-right">Karakter</span>
                            <i class="fa fa-heartbeat text-green"></i> <strong>Bimbingan Pribadi</strong>
                            <p style="margin: 3px 0 0 0; font-size: 11px; color: #64748b;">Pengendalian emosi, muhasabah diri, tazkiyatun nafs, adiksi gawai & kebiasaan buruk.</p>
                        </a>
                    </li>
                    <li style="border-bottom: 1px solid #f1f5f9;">
                        <a href="javascript:void(0)" style="color: #334155;">
                            <span class="badge bg-yellow pull-right">Ukhuwah</span>
                            <i class="fa fa-users text-yellow"></i> <strong>Bimbingan Sosial</strong>
                            <p style="margin: 3px 0 0 0; font-size: 11px; color: #64748b;">Adaptasi kehidupan asrama (homesick), mediasi konflik teman sebaya, adab berteman.</p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" style="color: #334155;">
                            <span class="badge bg-purple pull-right">Masa Depan</span>
                            <i class="fa fa-briefcase text-purple"></i> <strong>Bimbingan Karier & Minat</strong>
                            <p style="margin: 3px 0 0 0; font-size: 11px; color: #64748b;">Eksplorasi minat bakat, persiapan beasiswa Timur Tengah (Madinah/Al-Azhar) & PTN.</p>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-footer" style="background: #f8fafc; font-size: 11px; color: #64748b;">
                <i class="fa fa-shield text-green"></i> <strong>Asas Kerahasiaan:</strong> Seluruh data konsultasi bersifat rahasia dan hanya dapat diakses oleh konselor serta pimpinan pondok.
            </div>
        </div>

        {{-- Alur Rujukan Terpadu (Asrama & Tata Tertib) --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-link text-aqua"></i> Alur Integrasi Rujukan BK</h3>
            </div>
            <div class="box-body" style="font-size: 12px; line-height: 1.6;">
                <div style="margin-bottom: 12px; padding: 10px; background: #f0fdf4; border-left: 3px solid #22c55e; border-radius: 4px;">
                    <strong>1. Rujukan Tata Tertib & Poin:</strong><br>
                    Santri yang mencapai akumulasi poin $\ge 20$ (SP 1) atau pelanggaran sedang/berat otomatis dijadwalkan sesi pembinaan BK intensif.
                </div>
                <div style="margin-bottom: 12px; padding: 10px; background: #eff6ff; border-left: 3px solid #3b82f6; border-radius: 4px;">
                    <strong>2. Rujukan Asrama (Pamong/Musyrif):</strong><br>
                    Masalah adaptasi kamar, perundungan, atau penurunan motivasi ibadah dilaporkan pamong asrama langsung ke agenda konselor.
                </div>
                <div style="padding: 10px; background: #fdf4ff; border-left: 3px solid #a855f7; border-radius: 4px;">
                    <strong>3. Konferensi Kasus & Wali Santri:</strong><br>
                    Jika masalah membutuhkan penanganan holistik, tim BK mengundang wali kelas, musyrif, dan orang tua santri.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Catat Sesi Konseling Baru --}}
<div class="modal fade" id="modalTambahKonseling" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('kesiswaan.konseling.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Catat Sesi Layanan Bimbingan & Konseling Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Pilih Santri Konseli <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($students as $s)
                                <option value="{{ $s['id'] }}">{{ $s['name'] }} (NIS: {{ $s['nis'] ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="counselor_name">Nama Guru BK / Konselor <span class="text-danger">*</span></label>
                                <input type="text" name="counselor_name" id="counselor_name" value="Drs. H. Mulyadi, M.Pd., Kons." class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="referral_from">Asal Rujukan / Inisiatif <span class="text-danger">*</span></label>
                                <input type="text" name="referral_from" id="referral_from" class="form-control" placeholder="Cth: Pamong Asrama / Mandiri / Wali Kelas" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal Pertemuan <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time">Waktu Pertemuan <span class="text-danger">*</span></label>
                                <input type="text" name="time" id="time" value="09:00 - 10:00" class="form-control" placeholder="Cth: 10:00 - 11:00" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Bidang Masalah <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_type">Jenis Layanan BK <span class="text-danger">*</span></label>
                                <select name="service_type" id="service_type" class="form-control" required>
                                    @foreach($serviceTypes as $st)
                                        <option value="{{ $st }}">{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="problem_summary">Ringkasan Uraian Masalah / Keluhan Santri <span class="text-danger">*</span></label>
                        <textarea name="problem_summary" id="problem_summary" rows="3" class="form-control" placeholder="Deskripsikan latar belakang masalah, gejala yang tampak, dan keluhan konseli..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="action_plan">Rencana Tindak Lanjut (RTL) & Solusi Pembinaan <span class="text-danger">*</span></label>
                        <textarea name="action_plan" id="action_plan" rows="3" class="form-control" placeholder="Langkah konkrit penanganan, komitmen santri, dan pendampingan..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status Penanganan Awal <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="dalam_proses" selected>Dalam Proses (Pendampingan Berjalan)</option>
                                    <option value="dijadwalkan">Dijadwalkan (Pertemuan Lanjutan)</option>
                                    <option value="selesai">Selesai (Kasus Tuntas)</option>
                                    <option value="dirujuk">Dirujuk (Rujukan Ahli Eksternal)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="follow_up_date">Tanggal Evaluasi Berkala</label>
                                <input type="date" name="follow_up_date" id="follow_up_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan Rahasia Konselor</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Observasi non-verbal, dinamika psikologis konseli..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan ke Jurnal BK</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

