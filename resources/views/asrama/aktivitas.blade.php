@extends('layouts.app')

@section('title', 'Jadwal Aktivitas Santri Asrama — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-clock-o text-primary"></i> Jadwal Rutinitas & Aktivitas Harian Santri
        <small>Qiyamul Lail, Shalat Berjamaah 5 Waktu, Halaqah Al-Qur'an, Diniyah & Waktu Istirahat Santri</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Asrama / Boarding</li>
        <li class="active">Aktivitas Asrama</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Aktivitas --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Agenda Harian</span>
                <span class="info-box-number">{{ $totalAktivitas }} <small style="color: #cbd5e0; font-size: 11px;">Sesi / Hari</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Jadwal Rutinitas Santri</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-heartbeat"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ibadah & Spiritual</span>
                <span class="info-box-number">{{ $totalIbadah }} <small style="color: #d1fae5; font-size: 11px;">Agenda</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Shalat Berjamaah & Qiyam</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tahfidz & Diniyah</span>
                <span class="info-box-number">{{ $totalDiniyahTahfidz }} <small style="color: #e9d8fd; font-size: 11px;">Sesi</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Setoran Quran & Kitab</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-shield"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Agenda Wajib</span>
                <span class="info-box-number">{{ $totalWajib }} <small style="color: #fecaca; font-size: 11px;">Wajib Hadir</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #fef2f2;">Presensi Terpantau Musyrif</span>
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
    {{-- Kolom Kiri: Tabel Timeline & Detail Agenda Harian --}}
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt text-primary"></i> Tabel Jadwal Rutinitas Santri Asrama (24 Jam)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahAktivitas">
                        <i class="fa fa-plus-circle"></i> Tambah Aktivitas
                    </button>
                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                        <i class="fa fa-print"></i> Cetak Jadwal
                    </button>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <form method="GET" action="{{ route('asrama.aktivitas') }}" class="form-inline">
                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="category">Kategori</label>
                        <select name="category" class="form-control input-sm">
                            <option value="">-- Semua Kategori Aktivitas --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari nama agenda / lokasi..." style="width: 220px;">
                    </div>

                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
                    @if($categoryFilter || $search)
                        <a href="{{ route('asrama.aktivitas') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
                    @endif
                </form>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr style="background-color: #f4f6f9; color: #334155;">
                            <th style="width: 40px; text-align: center;">#</th>
                            <th style="width: 110px;">Rentang Waktu</th>
                            <th>Nama Agenda & Deskripsi</th>
                            <th>Kategori & Lokasi</th>
                            <th>Pembina / Musyrif</th>
                            <th style="text-align: center; width: 80px;">Sifat</th>
                            <th style="width: 50px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $index => $act)
                            @php
                                $badgeColor = match($act['category']) {
                                    'Ibadah & Spiritual' => 'success',
                                    'Tahfidz & Al-Qur\'an' => 'primary',
                                    'Diniyah & Turats' => 'purple',
                                    'Akademik & Sekolah' => 'info',
                                    'Kerapihan & Kedisiplinan' => 'warning',
                                    default => 'default'
                                };
                            @endphp
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td style="vertical-align: middle;">
                                    <span class="label label-navy" style="background-color: #1a365d; font-size: 11px;">
                                        <i class="fa fa-clock-o"></i> {{ $act['time_start'] }} - {{ $act['time_end'] }}
                                    </span><br>
                                    <small class="text-muted">{{ $act['days'] }}</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #1e3a8a; font-size: 13px;">{{ $act['name'] }}</strong><br>
                                    <small class="text-muted">{{ $act['description'] }}</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="label label-{{ $badgeColor }}">{{ $act['category'] }}</span><br>
                                    <small class="text-muted"><i class="fa fa-map-marker text-red"></i> {{ $act['location'] }}</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <small><i class="fa fa-user-secret text-muted"></i> {{ $act['supervisor'] }}</small>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    @if($act['mandatory'])
                                        <span class="badge bg-red" style="font-size: 10px;">Wajib</span>
                                    @else
                                        <span class="badge bg-gray" style="font-size: 10px;">Pilihan</span>
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{ route('asrama.aktivitas.destroy', $act['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus agenda aktivitas ini?')" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-info-circle fa-2x"></i><br>
                                    Belum ada agenda aktivitas yang sesuai dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix" style="background-color: #f9fafb;">
                <span class="text-muted pull-left">Menampilkan <strong>{{ count($activities) }}</strong> agenda rutinitas harian asrama.</span>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Timeline Visual 24 Jam & Aturan Rutinitas --}}
    <div class="col-md-4">
        {{-- Visual Alur Rutinitas Santri --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-hourglass-half text-aqua"></i> Alur 5 Waktu Asrama</h3>
            </div>
            <div class="box-body" style="font-size: 12px; line-height: 1.6;">
                <ul class="timeline" style="margin-bottom: 0;">
                    <li class="time-label">
                        <span class="bg-navy">03:45 WIB</span>
                    </li>
                    <li>
                        <i class="fa fa-moon-o bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 03:45 - 06:30</span>
                            <h3 class="timeline-header" style="font-size: 13px;"><strong>Sesi Subuh & Tahfidz Pagi</strong></h3>
                            <div class="timeline-body" style="font-size: 11px;">
                                Qiyamul lail, shalat Subuh berjamaah, dzikir Al-Ma'tsurat, dan ziyadah hafalan baru.
                            </div>
                        </div>
                    </li>
                    <li class="time-label">
                        <span class="bg-green">07:15 WIB</span>
                    </li>
                    <li>
                        <i class="fa fa-graduation-cap bg-green"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 07:15 - 15:15</span>
                            <h3 class="timeline-header" style="font-size: 13px;"><strong>Sesi KBM Sekolah Formal</strong></h3>
                            <div class="timeline-body" style="font-size: 11px;">
                                Pembelajaran di kelas teori, laboratorium, dan shalat Dzuhur berjamaah di masjid.
                            </div>
                        </div>
                    </li>
                    <li class="time-label">
                        <span class="bg-yellow">15:15 WIB</span>
                    </li>
                    <li>
                        <i class="fa fa-soccer-ball-o bg-yellow"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 15:15 - 17:30</span>
                            <h3 class="timeline-header" style="font-size: 13px;"><strong>Sesi Ashar & Olahraga</strong></h3>
                            <div class="timeline-body" style="font-size: 11px;">
                                Shalat Ashar berjamaah, panahan tradisional, silat tapak suci, dan sanitasi diri.
                            </div>
                        </div>
                    </li>
                    <li class="time-label">
                        <span class="bg-purple">17:45 WIB</span>
                    </li>
                    <li>
                        <i class="fa fa-book bg-purple"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 17:45 - 22:00</span>
                            <h3 class="timeline-header" style="font-size: 13px;"><strong>Sesi Maghrib, Ta'lim & Belajar</strong></h3>
                            <div class="timeline-body" style="font-size: 11px;">
                                Pengajian kitab kuning, makan malam, mudzakarah belajar mandiri tugas LMS, dan murajaah.
                            </div>
                        </div>
                    </li>
                    <li class="time-label">
                        <span class="bg-red">22:00 WIB</span>
                    </li>
                    <li>
                        <i class="fa fa-bed bg-red"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header" style="font-size: 13px;"><strong>Jam Wajib Istirahat (Naum)</strong></h3>
                            <div class="timeline-body" style="font-size: 11px;">
                                Seluruh lampu kamar dipadamkan, patroli musyrif piket, dan istirahat optimal.
                            </div>
                        </div>
                    </li>
                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                </ul>
            </div>
            <div class="box-footer" style="background-color: #f8fafc; font-size: 11px; color: #64748b;">
                <i class="fa fa-info-circle text-blue"></i> Kedisiplinan rutinitas asrama dinilai langsung oleh musyrif asrama dalam Buku Rapor Karakter Santri.
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Aktivitas Harian Baru --}}
<div class="modal fade" id="modalTambahAktivitas" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('asrama.aktivitas.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Tambah Agenda Aktivitas Asrama Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time_start">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="text" name="time_start" id="time_start" value="16:00" class="form-control" placeholder="Cth: 04:30" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time_end">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="text" name="time_end" id="time_end" value="17:00" class="form-control" placeholder="Cth: 05:30" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Agenda / Aktivitas <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Cth: Bimbingan Karakter & Adab Makan Islami" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Kategori Aktivitas <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="Ibadah & Spiritual">Ibadah & Spiritual</option>
                                    <option value="Tahfidz & Al-Qur'an">Tahfidz & Al-Qur'an</option>
                                    <option value="Diniyah & Turats">Diniyah & Turats</option>
                                    <option value="Akademik & Sekolah">Akademik & Sekolah</option>
                                    <option value="Olahraga & Bakat">Olahraga & Bakat</option>
                                    <option value="Bahasa & Sosial">Bahasa & Sosial</option>
                                    <option value="Belajar Mandiri">Belajar Mandiri</option>
                                    <option value="Kerapihan & Kedisiplinan">Kerapihan & Kedisiplinan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="days">Hari Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="text" name="days" id="days" value="Setiap Hari" class="form-control" placeholder="Cth: Setiap Hari / Senin - Jumat" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Lokasi Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" value="Masjid Kampus Alia" class="form-control" placeholder="Cth: Gedung Asrama / Masjid" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supervisor">Pembina / Musyrif Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" name="supervisor" id="supervisor" value="Ust. Salman Al-Farisi, S.Sos." class="form-control" placeholder="Cth: Pamong Asrama" required>
                            </div>
                        </div>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="mandatory" value="1" checked> <strong>Agenda Wajib Diikuti Santri</strong> (Ada pengecekan presensi kehadiran)
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi & Standar Operasional Kegiatan <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="2" class="form-control" placeholder="Uraikan tata tertib, perlengkapan yang wajib dibawa, dan adab santri..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Daftarkan ke Jadwal Harian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

