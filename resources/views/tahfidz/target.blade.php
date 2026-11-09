@extends('layouts.app')

@section('title', 'Target & Halaqah Al-Qur\'an')

@push('styles')
<style>
    .metric-card-tahfidz {
        border-radius: 6px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e8ed;
        margin-bottom: 20px;
        transition: transform .2s ease;
    }
    .metric-card-tahfidz:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .metric-icon-tahfidz {
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
    .halaqah-card {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }
    .halaqah-card-header {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
    }
    .halaqah-card-body {
        padding: 18px;
    }
    .student-badge-pill {
        display: inline-block;
        background: #edf2f7;
        color: #2d3748;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-right: 6px;
        margin-bottom: 6px;
        border: 1px solid #e2e8f0;
    }
    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #2b6cb0;
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
            <i class="fa fa-book text-primary"></i> Tahfidz: Target Program & Pembagian Halaqah Al-Qur'an
        </h3>
        <p class="text-muted" style="margin-bottom: 0;">
            Manajemen kelompok halaqah bimbingan hafalan Al-Qur'an, alokasi musyrif/musyrifah, penetapan target juz, serta jadwal sesi setoran ziyadah & murojaah.
        </p>
    </div>
    <div class="col-md-4 col-xs-12 text-right" style="padding-top: 10px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahHalaqah" style="border-radius: 4px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Buat Halaqah Baru
        </button>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalTambahProgram" style="border-radius: 4px;">
            <i class="fa fa-bookmark text-purple" style="color: #6b46c1;"></i> Tambah Program
        </button>
        <button type="button" class="btn btn-default" onclick="window.print()" style="border-radius: 4px;">
            <i class="fa fa-print"></i> Cetak
        </button>
    </div>
</div>

<!-- Metrik Statistik Tahfidz -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tahfidz clearfix">
            <div class="metric-icon-tahfidz" style="background: rgba(43, 108, 176, 0.1); color: #2b6cb0;">
                <i class="fa fa-users"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Kelompok Halaqah</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_halaqah'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Aktif Membimbing</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tahfidz clearfix">
            <div class="metric-icon-tahfidz" style="background: rgba(107, 70, 193, 0.1); color: #6b46c1;">
                <i class="fa fa-bookmark"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Program Tahfidz</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_program'] }}</div>
                <div class="text-muted" style="font-size: 11px;">Reguler, Intensif, Takhassus</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tahfidz clearfix">
            <div class="metric-icon-tahfidz" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                <i class="fa fa-graduation-cap"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Santri Terdaftar</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_santri'] }}</div>
                <div class="text-success" style="font-size: 11px;"><i class="fa fa-check"></i> Masuk dalam halaqah</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="metric-card-tahfidz clearfix">
            <div class="metric-icon-tahfidz" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <i class="fa fa-user-secret"></i>
            </div>
            <div style="margin-left: 62px;">
                <div class="text-muted" style="font-size: 12px; text-transform: uppercase; font-weight: 600;">Musyrif / Pengampu</div>
                <div style="font-size: 24px; font-weight: 700; color: #2d3748;">{{ $stats['total_musyrif'] }}</div>
                <div class="text-warning" style="font-size: 11px;">Ustadz & Ustadzah</div>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi Tab & Filter -->
<div class="nav-tabs-custom" style="border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 20px;">
    <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'halaqah' ? 'active' : '' }}">
            <a href="{{ route('tahfidz.target', ['tab' => 'halaqah', 'unit' => $activeUnit]) }}">
                <i class="fa fa-users text-primary"></i> <strong>Daftar Kelompok Halaqah ({{ count($halaqahs) }})</strong>
            </a>
        </li>
        <li class="{{ $activeTab === 'program' ? 'active' : '' }}">
            <a href="{{ route('tahfidz.target', ['tab' => 'program', 'unit' => $activeUnit]) }}">
                <i class="fa fa-bookmark text-purple" style="color: #6b46c1;"></i> <strong>Kurikulum & Program Tahfidz ({{ count($programs) }})</strong>
            </a>
        </li>
    </ul>

    <div class="tab-content" style="padding: 15px;">
        <!-- Filter Bar -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <form method="GET" action="{{ route('tahfidz.target') }}" class="form-inline">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
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
                        <label for="filter_q" style="font-weight: 600; margin-right: 6px;">Cari:</label>
                        <input type="text" name="q" id="filter_q" class="form-control input-sm" placeholder="Nama halaqah, musyrif, program..." value="{{ $search }}" style="width: 240px;">
                    </div>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Terapkan</button>
                    @if($activeUnit || $search)
                        <a href="{{ route('tahfidz.target', ['tab' => $activeTab]) }}" class="btn btn-link btn-sm text-danger"><i class="fa fa-times"></i> Reset</a>
                    @endif
                </form>
            </div>
        </div>

        @if($activeTab === 'halaqah')
            <!-- TAB 1: KELOMPOK HALAQAH -->
            <div class="row">
                @forelse($halaqahs as $hlq)
                    <div class="col-md-6 col-xs-12">
                        <div class="halaqah-card">
                            <div class="halaqah-card-header clearfix">
                                <div class="pull-left">
                                    <span class="label" style="background-color: {{ $hlq['gender_target'] === 'L' ? '#2b6cb0' : '#805ad5' }};">
                                        <i class="fa {{ $hlq['gender_target'] === 'L' ? 'fa-mars' : 'fa-venus' }}"></i>
                                        {{ $hlq['gender_target'] === 'L' ? 'Halaqah Ikhwan' : 'Halaqah Akhwat' }}
                                    </span>
                                    <span class="label label-default" style="font-family: monospace;">{{ $hlq['id'] }}</span>
                                </div>
                                <div class="pull-right">
                                    <span class="badge" style="background-color: #38a169;">Target: {{ $hlq['target_juz'] }} Juz</span>
                                </div>
                            </div>
                            <div class="halaqah-card-body">
                                <h4 style="margin-top: 0; font-weight: 700; color: #1a365d; min-height: 28px;">
                                    {{ $hlq['name'] }}
                                </h4>

                                <div style="font-size: 13px; color: #4a5568; margin-bottom: 12px;">
                                    <div><i class="fa fa-user-circle text-primary" style="width: 18px;"></i> <strong>Musyrif:</strong> {{ $hlq['supervisor_name'] }}</div>
                                    <div><i class="fa fa-bookmark text-purple" style="width: 18px; color: #6b46c1;"></i> <strong>Program:</strong> {{ $hlq['program_name'] }} ({{ $hlq['unit_name'] }})</div>
                                    <div><i class="fa fa-map-marker text-danger" style="width: 18px;"></i> <strong>Lokasi:</strong> {{ $hlq['location'] }}</div>
                                    <div><i class="fa fa-clock-o text-warning" style="width: 18px;"></i> <strong>Jadwal Sesi:</strong> {{ $hlq['session_schedule'] }}</div>
                                </div>

                                <div style="background: #f8fafc; border: 1px solid #edf2f7; border-radius: 4px; padding: 10px; margin-bottom: 14px;">
                                    <div style="font-size: 12px; font-weight: 600; color: #2d3748; margin-bottom: 6px;">
                                        Santri Anggota Halaqah ({{ $hlq['student_count'] }} Santri):
                                    </div>
                                    <div>
                                        @forelse($hlq['students'] as $sItem)
                                            <span class="student-badge-pill">
                                                <i class="fa fa-user text-muted"></i> {{ $sItem['name'] }} ({{ $sItem['nis'] ?? '-' }})
                                            </span>
                                        @empty
                                            <span class="text-muted font-italic" style="font-size: 12px;">Belum ada santri dialokasikan ke halaqah ini.</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="btn-group btn-group-justified">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalAssignSantri-{{ $hlq['id'] }}">
                                            <i class="fa fa-user-plus text-primary"></i> Alokasi Santri
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('tahfidz.target', ['tab' => 'program', 'unit' => $hlq['unit_id']]) }}" class="btn btn-default btn-sm">
                                            <i class="fa fa-info-circle text-purple" style="color: #6b46c1;"></i> Detail Target
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('tahfidz.halaqah.destroy', $hlq['id']) }}" class="btn btn-default btn-sm text-danger" onclick="return confirm('Hapus kelompok halaqah ini?')">
                                            <i class="fa fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Alokasi Santri -->
                    <div class="modal fade" id="modalAssignSantri-{{ $hlq['id'] }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content" style="border-radius: 6px;">
                                <form action="{{ route('tahfidz.halaqah.assign', $hlq['id']) }}" method="POST">
                                    @csrf
                                    <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                                        <h4 class="modal-title font-weight-bold">
                                            <i class="fa fa-user-plus"></i> Alokasi Santri ke {{ $hlq['name'] }}
                                        </h4>
                                    </div>
                                    <div class="modal-body" style="font-size: 13px;">
                                        <div class="form-group">
                                            <label for="assign_student_{{ $hlq['id'] }}">Pilih Santri:</label>
                                            <select name="student_id" id="assign_student_{{ $hlq['id'] }}" class="form-control" required>
                                                <option value="">-- Pilih Santri dari Buku Induk --</option>
                                                @foreach($students as $std)
                                                    <option value="{{ $std['id'] }}">
                                                        {{ $std['name'] }} (NIS: {{ $std['nis'] ?? '-' }}) &bull; {{ $std['class_id'] ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="help-block" style="font-size: 11px;">
                                                Santri yang dipilih akan langsung terdaftar di bawah bimbingan musyrif {{ $hlq['supervisor_name'] }}.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary font-weight-bold">
                                            <i class="fa fa-check"></i> Tambahkan Santri
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-xs-12">
                        <div class="box box-solid" style="border-radius: 6px; padding: 40px; text-align: center;">
                            <i class="fa fa-book fa-4x text-muted" style="margin-bottom: 15px;"></i>
                            <h4 style="font-weight: 700; color: #4a5568;">Tidak ada kelompok halaqah ditemukan</h4>
                            <p class="text-muted">Silakan sesuaikan filter unit atau bentuk kelompok halaqah Al-Qur'an baru.</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahHalaqah">
                                <i class="fa fa-plus-circle"></i> Buat Halaqah Baru
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

        @else
            <!-- TAB 2: KURIKULUM & TARGET PROGRAM -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 13px;">
                    <thead>
                        <tr style="background: #edf2f7; color: #2d3748;">
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>Nama Program Tahfidz</th>
                            <th class="text-center" style="width: 90px;">Jenjang</th>
                            <th class="text-center" style="width: 120px;">Target Mutqin</th>
                            <th class="text-center" style="width: 150px;">Target Harian</th>
                            <th class="text-center" style="width: 160px;">Jadwal Sesi</th>
                            <th>Deskripsi Capaian & Standar Mutu</th>
                            <th class="text-center" style="width: 90px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $idx => $p)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                <td style="vertical-align: middle;">
                                    <strong style="color: #1a365d; font-size: 14px;">{{ $p['name'] }}</strong><br>
                                    <span class="text-muted" style="font-size: 11px;">ID: {{ $p['id'] }} &bull; Periode: {{ $p['period'] }}</span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label label-primary">{{ $p['level'] }}</span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="badge" style="background-color: #38a169; font-size: 13px;">
                                        {{ $p['target_juz'] }} Juz
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle; font-weight: 600; color: #2b6cb0;">
                                    {{ $p['daily_target'] }}
                                </td>
                                <td class="text-center" style="vertical-align: middle; font-size: 12px;">
                                    {{ $p['session_time'] }}
                                </td>
                                <td style="vertical-align: middle; font-size: 12px; color: #4a5568;">
                                    {{ $p['target_description'] }}
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                                    Tidak ada data program tahfidz.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Kelompok Halaqah Baru -->
<div class="modal fade" id="modalTambahHalaqah" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('tahfidz.halaqah.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #1a365d; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-plus-circle"></i> Buat Kelompok Halaqah Al-Qur'an Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_name">Nama Halaqah <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="hlq_name" class="form-control" placeholder="Contoh: Halaqah Abu Bakar Ash-Shiddiq" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_gender">Target Ikhwan / Akhwat <span class="text-danger">*</span></label>
                                <select name="gender_target" id="hlq_gender" class="form-control" required>
                                    <option value="L">Ikhwan (Santri Putra)</option>
                                    <option value="P">Akhwat (Santri Putri)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_program">Program Tahfidz <span class="text-danger">*</span></label>
                                <select name="program_id" id="hlq_program" class="form-control" required>
                                    @foreach($allPrograms as $prg)
                                        <option value="{{ $prg['id'] }}">{{ $prg['name'] }} ({{ $prg['level'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_unit">Unit Sekolah <span class="text-danger">*</span></label>
                                <select name="unit_id" id="hlq_unit" class="form-control" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_teacher">Musyrif / Musyrifah <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="hlq_teacher" class="form-control" required>
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
                                <label for="hlq_target_juz">Target Mutqin (Juz) <span class="text-danger">*</span></label>
                                <input type="number" name="target_juz" id="hlq_target_juz" class="form-control" value="5" min="1" max="30" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_location">Lokasi Bimbingan <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="hlq_location" class="form-control" placeholder="Contoh: Masjid Lantai 1 Sayap Kanan" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="hlq_session">Jadwal Sesi <span class="text-danger">*</span></label>
                                <input type="text" name="session_schedule" id="hlq_session" class="form-control" placeholder="Contoh: Ba'da Subuh (05.00 - 06.30)" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hlq_desc">Catatan / Deskripsi Halaqah</label>
                        <textarea name="description" id="hlq_desc" class="form-control" rows="2" placeholder="Keterangan khusus kelompok halaqah..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-save"></i> Terbitkan Halaqah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Program Tahfidz Baru -->
<div class="modal fade" id="modalTambahProgram" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 6px;">
            <form action="{{ route('tahfidz.program.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #6b46c1; color: #fff; border-radius: 6px 6px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title font-weight-bold">
                        <i class="fa fa-bookmark"></i> Tambah Program & Standar Target Tahfidz Baru
                    </h4>
                </div>
                <div class="modal-body" style="font-size: 13px;">
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label for="prg_name">Nama Program <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="prg_name" class="form-control" placeholder="Contoh: Program Daurah Tahfidz Intensif Liburan" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="prg_period">Periode / Semester <span class="text-danger">*</span></label>
                                <input type="text" name="period" id="prg_period" class="form-control" value="2026/2027 Ganjil" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="prg_unit">Unit Sekolah <span class="text-danger">*</span></label>
                                <select name="unit_id" id="prg_unit" class="form-control" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="prg_level">Tingkatan Level <span class="text-danger">*</span></label>
                                <select name="level" id="prg_level" class="form-control" required>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="Semua">Semua Jenjang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="prg_target_juz">Target Mutqin (Juz) <span class="text-danger">*</span></label>
                                <input type="number" name="target_juz" id="prg_target_juz" class="form-control" value="5" min="1" max="30" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="prg_daily">Target Harian Santri <span class="text-danger">*</span></label>
                                <input type="text" name="daily_target" id="prg_daily" class="form-control" placeholder="Contoh: 1 Halaman / Hari" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="prg_session">Waktu Sesi Halaqah <span class="text-danger">*</span></label>
                                <input type="text" name="session_time" id="prg_session" class="form-control" placeholder="Contoh: Ba'da Subuh & Ba'da Ashar" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prg_desc">Deskripsi Capaian & Standar Mutu</label>
                        <textarea name="target_description" id="prg_desc" class="form-control" rows="2" placeholder="Kaidah tajwid, kelancaran makhraj, dan ketentuan evaluasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background-color: #6b46c1; border-color: #553c9a;">
                        <i class="fa fa-save"></i> Simpan Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

