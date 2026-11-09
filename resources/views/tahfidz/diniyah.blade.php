@extends('layouts.app')

@section('title', 'Kurikulum Diniyah & Jadwal Pengajian')

@push('styles')
<style>
    .metric-card-diniyah {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-diniyah:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-diniyah {
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
    .kitab-card {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }
    .kitab-card-header {
        padding: 12px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
    }
    .kitab-card-body {
        padding: 18px;
    }
    .day-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .day-senin { background-color: #ebf8ff; color: #2b6cb0; border: 1px solid #bee3f8; }
    .day-selasa { background-color: #faf5ff; color: #6b46c1; border: 1px solid #e9d8fd; }
    .day-rabu { background-color: #f0fff4; color: #276749; border: 1px solid #c6f6d5; }
    .day-kamis { background-color: #fffaf0; color: #c05621; border: 1px solid #feebc8; }
    .day-jumat { background-color: #f7fafc; color: #2d3748; border: 1px solid #e2e8f0; }
    .day-sabtu { background-color: #fff5f5; color: #c53030; border: 1px solid #fed7d7; }
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
            <i class="fa fa-book text-primary"></i> Diniyah: Kurikulum Kitab Kuning & Jadwal Pengajian Pesantren
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Manajemen mata pelajaran kepesantrenan (Fikih, Nahwu Sharaf, Hadits, Adab, Tajwid), daftar kitab turats rujukan, dan jadwal ta'lim asrama.
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahJadwal" style="border-radius: 4px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Tambah Jadwal Pengajian
        </button>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalTambahKitab" style="border-radius: 4px;">
            <i class="fa fa-bookmark text-purple" style="color: #6b46c1;"></i> Daftarkan Kitab
        </button>
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak
        </button>
    </div>
</div>

<!-- Metrik Statistik Diniyah -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-diniyah clearfix">
            <div class="metric-icon-diniyah" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-book"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Kitab Rujukan</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_kitab'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Mata pelajaran diniyah</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-diniyah clearfix">
            <div class="metric-icon-diniyah" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-calendar-check-o"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Sesi Pengajian</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_schedules'] }}</div>
                <div class="text-purple" style="font-size: 11px;">Jadwal ta'lim per pekan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-diniyah clearfix">
            <div class="metric-icon-diniyah" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-user-secret"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Asatidz Pengampu</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_asatim'] }}</div>
                <div class="text-success" style="font-size: 11px;">Pengajar kitab berlisensi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-diniyah clearfix">
            <div class="metric-icon-diniyah" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-tags"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Bidang Keilmuan</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_categories'] }}</div>
                <div class="text-warning" style="font-size: 11px;">Fikih, Nahwu, Hadits, Adab</div>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi Tab & Filter -->
<div class="nav-tabs-custom" style="border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 20px;">
    <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'jadwal' ? 'active' : '' }}">
            <a href="{{ route('tahfidz.diniyah', ['tab' => 'jadwal', 'category' => $categoryFilter, 'day' => $dayFilter]) }}">
                <i class="fa fa-calendar text-primary"></i> <strong>Jadwal Pengajian Diniyah ({{ count($schedules) }})</strong>
            </a>
        </li>
        <li class="{{ $activeTab === 'kitab' ? 'active' : '' }}">
            <a href="{{ route('tahfidz.diniyah', ['tab' => 'kitab', 'category' => $categoryFilter, 'day' => $dayFilter]) }}">
                <i class="fa fa-book text-purple" style="color: #6b46c1;"></i> <strong>Katalog Kitab Kuning / Rujukan ({{ count($subjects) }})</strong>
            </a>
        </li>
    </ul>

    <div class="tab-content" style="padding: 15px;">
        <!-- Filter Bar -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <form method="GET" action="{{ route('tahfidz.diniyah') }}" class="form-inline">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    @if($activeTab === 'jadwal')
                        <div class="form-group" style="margin-right: 12px;">
                            <label for="filter_day" style="font-weight: 600; margin-right: 6px;">Hari:</label>
                            <select name="day" id="filter_day" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Hari --</option>
                                <option value="Senin" {{ $dayFilter === 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ $dayFilter === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ $dayFilter === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ $dayFilter === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ $dayFilter === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ $dayFilter === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            </select>
                        </div>
                    @else
                        <div class="form-group" style="margin-right: 12px;">
                            <label for="filter_cat" style="font-weight: 600; margin-right: 6px;">Bidang:</label>
                            <select name="category" id="filter_cat" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Bidang --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group" style="margin-right: 12px;">
                        <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari:</label>
                        <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Nama kitab, ustadz, ruangan..." value="{{ $search }}" style="width: 240px;">
                    </div>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Terapkan</button>
                    @if($dayFilter || $categoryFilter || $search)
                        <a href="{{ route('tahfidz.diniyah', ['tab' => $activeTab]) }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
                    @endif
                </form>
            </div>
        </div>

        @if($activeTab === 'jadwal')
            <!-- TAB 1: JADWAL PENGAJIAN DINIYAH -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                    <thead>
                        <tr style="background: #edf2f7; color: #2d3748;">
                            <th style="width: 40px;" class="text-center">No</th>
                            <th style="width: 100px;" class="text-center">Hari & Waktu</th>
                            <th style="width: 120px;" class="text-center">Sesi Waktu</th>
                            <th>Mata Pelajaran & Kitab</th>
                            <th>Ustadz Pengampu</th>
                            <th>Target Santri / Rombel</th>
                            <th>Ruangan / Lokasi</th>
                            <th class="text-center" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $idx => $sch)
                            @php
                                $dayClass = 'day-' . strtolower($sch['day']);
                            @endphp
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="day-badge {{ $dayClass }}">{{ $sch['day'] }}</span><br>
                                    <strong style="font-size: 13px; color: #2d3748; margin-top: 4px; display: inline-block;">
                                        {{ $sch['time_start'] }} - {{ $sch['time_end'] }}
                                    </strong>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label label-default" style="font-size: 11px;">{{ $sch['session_label'] }}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #1a365d; font-size: 14px;">{{ $sch['subject_name'] }}</strong><br>
                                    <span class="text-muted" style="font-size: 11px;">
                                        Bidang: {{ $sch['subject_category'] }} &bull; Muallif: {{ $sch['kitab_author'] }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <i class="fa fa-user-circle text-primary"></i> <strong>{{ $sch['teacher_name'] }}</strong>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span style="font-weight: 600; color: #4a5568;">{{ $sch['class_target'] }}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <i class="fa fa-map-marker text-danger"></i> {{ $sch['room'] }}
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <a href="{{ route('tahfidz.diniyah.jadwal.destroy', $sch['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus jadwal pengajian ini?')" title="Hapus Jadwal">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-calendar-times-o fa-3x text-muted" style="margin-bottom: 10px;"></i>
                                    <p>Tidak ada jadwal pengajian diniyah ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @else
            <!-- TAB 2: KATALOG KITAB KUNING -->
            <div class="row">
                @forelse($subjects as $sbj)
                    <div class="col-md-6 col-xs-12">
                        <div class="kitab-card">
                            <div class="kitab-card-header clearfix">
                                <div class="pull-left">
                                    <span class="label label-primary" style="font-family: monospace;">{{ $sbj['code'] }}</span>
                                    <span class="badge" style="background-color: #6b46c1; margin-left: 4px;">{{ $sbj['category'] }}</span>
                                </div>
                                <div class="pull-right">
                                    <span class="label label-default">{{ $sbj['level'] }}</span>
                                </div>
                            </div>
                            <div class="kitab-card-body">
                                <h4 style="margin-top: 0; font-weight: 700; color: #1a365d; min-height: 24px;">
                                    {{ $sbj['name'] }}
                                </h4>
                                <div style="font-size: 13px; color: #4a5568; margin-bottom: 8px;">
                                    <strong><i class="fa fa-pencil text-muted" style="width: 18px;"></i> Mu'allif / Pengarang:</strong> {{ $sbj['kitab_author'] }}
                                </div>
                                <p class="text-muted" style="font-size: 12px; line-height: 1.5; margin-bottom: 0;">
                                    {{ $sbj['description'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-xs-12">
                        <div class="box box-solid" style="border-radius: 6px; padding: 40px; text-align: center;">
                            <i class="fa fa-book fa-3x text-muted" style="margin-bottom: 10px;"></i>
                            <p>Tidak ada data kitab rujukan ditemukan.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Jadwal Pengajian Baru -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('tahfidz.diniyah.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-plus-circle"></i> Tambah Jadwal Pengajian Diniyah Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="sch_subject">Mata Pelajaran Kitab <span class="text-danger">*</span></label>
                                <select name="subject_id" id="sch_subject" class="form-control" required>
                                    @foreach($allSubjects as $sbj)
                                        <option value="{{ $sbj['id'] }}">{{ $sbj['name'] }} ({{ $sbj['category'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="sch_teacher">Ustadz Pengampu <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="sch_teacher" class="form-control" required>
                                    @foreach($teachers as $tch)
                                        <option value="{{ $tch['id'] }}">{{ $tch['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="sch_target">Target Santri / Rombel Asrama <span class="text-danger">*</span></label>
                                <input type="text" name="class_target" id="sch_target" class="form-control" placeholder="Contoh: Santri Putra Kelas X & XI Boarding" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="sch_room">Ruangan / Tempat Kajian <span class="text-danger">*</span></label>
                                <input type="text" name="room" id="sch_room" class="form-control" placeholder="Contoh: Masjid Kampus Alia Lantai 1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="sch_day">Hari <span class="text-danger">*</span></label>
                                <select name="day" id="sch_day" class="form-control" required>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Ahad">Ahad</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="sch_session">Label Sesi <span class="text-danger">*</span></label>
                                <select name="session_label" id="sch_session" class="form-control" required>
                                    <option value="Ba'da Subuh">Ba'da Subuh</option>
                                    <option value="Pagi Dhuha">Pagi Dhuha</option>
                                    <option value="Ba'da Ashar">Ba'da Ashar</option>
                                    <option value="Ba'da Maghrib">Ba'da Maghrib</option>
                                    <option value="Ba'da Isya">Ba'da Isya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="time_start">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="time_start" id="time_start" class="form-control" value="18:30" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="time_end">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="time_end" id="time_end" class="form-control" value="19:45" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save"></i> Terbitkan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kitab Baru -->
<div class="modal fade" id="modalTambahKitab" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('tahfidz.diniyah.kitab.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #6b46c1; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-bookmark"></i> Daftarkan Kitab Rujukan Diniyah Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label for="ktb_name">Nama Kitab / Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="ktb_name" class="form-control" placeholder="Contoh: Fikih Dakwah (Kitab Fiqhud Dakwah)" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="ktb_code">Kode Kitab <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="ktb_code" class="form-control" placeholder="Contoh: KTB-DKW-01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="ktb_cat">Bidang Keilmuan <span class="text-danger">*</span></label>
                                <select name="category" id="ktb_cat" class="form-control" required>
                                    <option value="Fikih & Ibadah">Fikih & Ibadah</option>
                                    <option value="Bahasa & Kaidah">Bahasa & Kaidah</option>
                                    <option value="Hadits & Sunnah">Hadits & Sunnah</option>
                                    <option value="Aqidah & Akhlak">Aqidah & Akhlak</option>
                                    <option value="Al-Qur'an & Tajwid">Al-Qur'an & Tajwid</option>
                                    <option value="Sirah Nabawiyyah">Sirah Nabawiyyah</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="ktb_level">Tingkatan Santri <span class="text-danger">*</span></label>
                                <input type="text" name="level" id="ktb_level" class="form-control" placeholder="Contoh: SMA Boarding / Semua Jenjang" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="ktb_author">Mu'allif / Pengarang <span class="text-danger">*</span></label>
                                <input type="text" name="kitab_author" id="ktb_author" class="form-control" placeholder="Contoh: Syaikh Dr. Mustafa As-Siba'i" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ktb_desc">Ringkasan Silabus & Pembahasan</label>
                        <textarea name="description" id="ktb_desc" class="form-control" rows="2" placeholder="Tuliskan pokok bahasan kitab..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background-color: #6b46c1; border-color: #553c9a;">
                        <i class="fa fa-save"></i> Simpan Kitab
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

