@extends('layouts.app')

@section('title', 'Ekstrakurikuler & Klub Bakat — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-soccer-ball-o text-primary"></i> Ekstrakurikuler & Klub Bakat Santri
        <small>Pengembangan Minat, Bakat, Olahraga Sunnah, Bahasa & Seni Santri Pesantren</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Kesiswaan</li>
        <li class="active">Ekstrakurikuler</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Ekstrakurikuler --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Klub Ekskul</span>
                <span class="info-box-number">{{ $totalKlub }} <small style="color: #cbd5e0; font-size: 11px;">Klub Aktif</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Bina Bakat & Minat</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Santri Terdaftar</span>
                <span class="info-box-number">{{ $totalAnggota }} <small style="color: #d1fae5; font-size: 11px;">Partisipan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Akumulasi Anggota Klub</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-user-secret"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pelatih & Pembina</span>
                <span class="info-box-number">{{ $totalPelatih }} <small style="color: #e9d8fd; font-size: 11px;">Instruktur</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Ustadz & Tenaga Ahli</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-trophy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Prestasi</span>
                <span class="info-box-number">{{ $totalPrestasiEkskul }} <small style="color: #fff; font-size: 11px;">Kejuaraan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Medali Ajang Lomba</span>
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
        {{ session('error') }}
    </div>
@endif

{{-- Filter Bar & Quick Actions --}}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-filter text-primary"></i> Filter & Manajemen Klub Ekstrakurikuler</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahEkskul">
                <i class="fa fa-plus-circle"></i> Buka Klub Baru
            </button>
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Brosur Klub
            </button>
        </div>
    </div>
    <div class="box-body" style="background-color: #f9fafb;">
        <form method="GET" action="{{ route('kesiswaan.ekskul') }}" class="form-inline">
            <div class="form-group margin-r-5">
                <label class="sr-only" for="category">Bidang</label>
                <select name="category" class="form-control input-sm">
                    <option value="">-- Semua Bidang --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group margin-r-5">
                <label class="sr-only" for="day">Hari Latihan</label>
                <select name="day" class="form-control input-sm">
                    <option value="">-- Semua Hari --</option>
                    @foreach($days as $d)
                        <option value="{{ $d }}" {{ $dayFilter === $d ? 'selected' : '' }}>Hari {{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group margin-r-5">
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari nama klub / pembina / lokasi..." style="width: 220px;">
            </div>

            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
            @if($categoryFilter || $dayFilter || $search)
                <a href="{{ route('kesiswaan.ekskul') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Grid Kartu Klub Ekstrakurikuler --}}
<div class="row">
    @forelse($ekskuls as $ek)
        @php
            $memberCount = count($ek['student_ids'] ?? []);
            $quota = $ek['quota'] ?? 30;
            $percent = $quota > 0 ? min(100, round(($memberCount / $quota) * 100)) : 0;
            
            $catColor = match($ek['category']) {
                'Olahraga Sunnah & Bela Diri' => 'primary',
                'Seni & Kebudayaan Islam' => 'purple',
                'Bahasa & Keilmuan' => 'success',
                'Sains & Teknologi' => 'info',
                default => 'warning'
            };
        @endphp
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="box box-{{ $catColor }} box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 15px; font-weight: bold;">
                        {{ $ek['name'] }}
                    </h3>
                    <div class="box-tools pull-right">
                        <span class="label label-warning" title="Prestasi yang telah diraih"><i class="fa fa-trophy"></i> {{ $ek['achievements_count'] }} Prestasi</span>
                    </div>
                </div>
                <div class="box-body" style="min-height: 190px;">
                    <p class="text-muted" style="font-size: 12px; height: 36px; overflow: hidden; margin-bottom: 12px;">
                        {{ Str::limit($ek['description'], 110) }}
                    </p>

                    <table class="table table-condensed table-striped" style="font-size: 12px; margin-bottom: 12px;">
                        <tr>
                            <td style="width: 30%;"><i class="fa fa-user-secret text-blue"></i> <strong>Pembina</strong></td>
                            <td>{{ $ek['coach_name'] }}</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-calendar-check-o text-green"></i> <strong>Jadwal</strong></td>
                            <td>{{ $ek['schedule_day'] }}, {{ $ek['schedule_time'] }} WIB</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-map-marker text-red"></i> <strong>Lokasi</strong></td>
                            <td>{{ $ek['location'] }}</td>
                        </tr>
                    </table>

                    {{-- Progress Bar Kuota Anggota --}}
                    <div>
                        <small class="pull-right"><strong>{{ $memberCount }}</strong> / {{ $quota }} Santri ({{ $percent }}%)</small>
                        <small><strong>Kapasitas Kuota:</strong></small>
                        <div class="progress xs" style="margin-top: 4px; margin-bottom: 5px;">
                            <div class="progress-bar progress-bar-{{ $percent >= 90 ? 'danger' : ($percent >= 60 ? 'yellow' : 'green') }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="box-footer" style="background-color: #f8fafc;">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRoster{{ $ek['id'] }}">
                                <i class="fa fa-users text-blue"></i> Roster Santri
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEnroll{{ $ek['id'] }}" {{ $memberCount >= $quota ? 'disabled' : '' }}>
                                <i class="fa fa-user-plus"></i> Daftarkan
                            </button>
                        </div>
                        <div class="btn-group" style="width: 40px;">
                            <a href="{{ route('kesiswaan.ekskul.destroy', $ek['id']) }}" class="btn btn-default btn-sm text-danger" onclick="return confirm('Hapus klub ekstrakurikuler ini?')" title="Hapus Klub">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Roster Anggota Santri --}}
        <div class="modal fade" id="modalRoster{{ $ek['id'] }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-users text-yellow"></i> Roster Anggota: {{ $ek['name'] }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="well well-sm" style="margin-bottom: 15px; font-size: 12px; background: #f8fafc;">
                            <strong>Pembina / Pelatih:</strong> {{ $ek['coach_name'] }}<br>
                            <strong>Jadwal Latihan:</strong> Setiap {{ $ek['schedule_day'] }} pkl {{ $ek['schedule_time'] }} WIB ({{ $ek['location'] }})
                        </div>

                        <h5><strong>Daftar Santri Terdaftar ({{ $memberCount }} dari {{ $quota }} Kuota):</strong></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="font-size: 12px;">
                                <thead>
                                    <tr style="background: #e2e8f0;">
                                        <th style="width: 35px; text-align: center;">#</th>
                                        <th>Nama Santri</th>
                                        <th>NIS</th>
                                        <th>Kelas / Rombel</th>
                                        <th style="text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ek['student_ids'] ?? [] as $idx => $sid)
                                        @php
                                            $s = $studentMap[$sid] ?? null;
                                            $cls = $s && isset($classMap[$s['class_id']]) ? $classMap[$s['class_id']] : null;
                                        @endphp
                                        <tr>
                                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                                            <td><strong>{{ $s['name'] ?? 'Santri #'.$sid }}</strong></td>
                                            <td>{{ $s['nis'] ?? '-' }}</td>
                                            <td>{{ $cls['name'] ?? '-' }}</td>
                                            <td style="text-align: center;"><span class="label label-success">Aktif</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted" style="padding: 20px;">
                                                Belum ada santri yang terdaftar dalam klub ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Presensi Latihan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Daftarkan Santri Baru ke Klub --}}
        <div class="modal fade" id="modalEnroll{{ $ek['id'] }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('kesiswaan.ekskul.enroll', $ek['id']) }}">
                        @csrf
                        <div class="modal-header" style="background-color: #3b82f6; color: #fff;">
                            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-user-plus"></i> Daftarkan Santri ke {{ $ek['name'] }}</h4>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted" style="font-size: 12px;">
                                Pilihlah santri yang memiliki minat bakat untuk diikutsertakan dalam pembinaan intensif ekstrakurikuler ini.
                            </p>
                            <div class="form-group">
                                <label for="student_id_{{ $ek['id'] }}">Pilih Santri <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id_{{ $ek['id'] }}" class="form-control" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($students as $st)
                                        @if(!in_array($st['id'], $ek['student_ids'] ?? []))
                                            <option value="{{ $st['id'] }}">{{ $st['name'] }} (NIS: {{ $st['nis'] ?? '-' }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Konfirmasi Pendaftaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-xs-12">
            <div class="well text-center text-muted" style="background: #fff; padding: 40px;">
                <i class="fa fa-soccer-ball-o fa-3x"></i><br><br>
                Tidak ada klub ekstrakurikuler yang sesuai dengan kriteria filter pencarian.
            </div>
        </div>
    @endforelse
</div>

{{-- Modal Buat Klub Ekstrakurikuler Baru --}}
<div class="modal fade" id="modalTambahEkskul" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('kesiswaan.ekskul.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Buka Klub Ekstrakurikuler Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Klub Ekstrakurikuler <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Cth: Tenis Meja Sunnah Club" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Bidang Ekstrakurikuler <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="Olahraga Sunnah & Bela Diri">Olahraga Sunnah & Bela Diri</option>
                                    <option value="Seni & Kebudayaan Islam">Seni & Kebudayaan Islam</option>
                                    <option value="Bahasa & Keilmuan">Bahasa & Keilmuan</option>
                                    <option value="Sains & Teknologi">Sains & Teknologi</option>
                                    <option value="Media & Kepemimpinan">Media & Kepemimpinan</option>
                                    <option value="Kewirausahaan Santri">Kewirausahaan Santri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coach_name">Nama Pembina / Pelatih <span class="text-danger">*</span></label>
                                <input type="text" name="coach_name" id="coach_name" class="form-control" placeholder="Cth: Ust. Rahmat Hidayat, S.Pd." required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="target_gender">Target Peserta <span class="text-danger">*</span></label>
                                <select name="target_gender" id="target_gender" class="form-control" required>
                                    <option value="Semua">Semua (Ikhwan & Akhwat)</option>
                                    <option value="Ikhwan">Khusus Ikhwan</option>
                                    <option value="Akhwat">Khusus Akhwat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="schedule_day">Hari Latihan <span class="text-danger">*</span></label>
                                <select name="schedule_day" id="schedule_day" class="form-control" required>
                                    @foreach($days as $d)
                                        <option value="{{ $d }}">{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quota">Kapasitas Kuota <span class="text-danger">*</span></label>
                                <input type="number" name="quota" id="quota" value="30" min="5" max="100" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="schedule_time">Waktu Latihan <span class="text-danger">*</span></label>
                                <input type="text" name="schedule_time" id="schedule_time" value="16:00 - 17:30" class="form-control" placeholder="Cth: 15:30 - 17:00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Lokasi Latihan <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control" placeholder="Cth: Lapangan Futsal Asrama" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi & Tujuan Klub <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="3" class="form-control" placeholder="Jelaskan silabus pelatihan, fokus pembinaan, dan target kejuaraan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Buka Klub</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

