@extends('layouts.app')

@section('title', 'Prestasi & Penghargaan Santri — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-trophy text-yellow"></i> Prestasi & Penghargaan Santri
        <small>Rekam Jejak Kejuaraan, Lomba, Portofolio & Apresiasi Bakat Santri</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Kesiswaan</li>
        <li class="active">Prestasi Siswa</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Prestasi --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-trophy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Prestasi</span>
                <span class="info-box-number">{{ $totalPrestasi }} <small style="color: #cbd5e0; font-size: 11px;">Kejuaraan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Buku Induk Prestasi</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-globe"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Nasional / Inter</span>
                <span class="info-box-number">{{ $totalNasional }} <small style="color: #fff; font-size: 11px;">Medali / Juara</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Tingkat Nasional & Internasional</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-map-marker"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Provinsi & Daerah</span>
                <span class="info-box-number">{{ $totalProvinsi }} <small style="color: #e9d8fd; font-size: 11px;">Kejuaraan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Tingkat Jawa Barat & Kota</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-star"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Poin Apresiasi</span>
                <span class="info-box-number">{{ number_format($totalPoinApresiasi, 0, ',', '.') }} <small style="color: #d1fae5; font-size: 11px;">Poin</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Akumulasi Reward Portofolio</span>
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
    {{-- Kolom Kiri: Tabel Daftar Prestasi --}}
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt text-primary"></i> Daftar Rekam Prestasi Santri</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahPrestasi">
                        <i class="fa fa-plus-circle"></i> Catat Prestasi Baru
                    </button>
                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                        <i class="fa fa-print"></i> Cetak Rekap
                    </button>
                </div>
            </div>

            {{-- Filter Box --}}
            <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <form method="GET" action="{{ route('kesiswaan.prestasi') }}" class="form-inline">
                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="category">Kategori</label>
                        <select name="category" class="form-control input-sm">
                            <option value="">-- Semua Bidang --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="level">Tingkat</label>
                        <select name="level" class="form-control input-sm">
                            <option value="">-- Semua Tingkat --</option>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl }}" {{ $levelFilter === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <label class="sr-only" for="year">Tahun</label>
                        <select name="year" class="form-control input-sm">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ $yearFilter == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group margin-r-5">
                        <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari santri / lomba / panitia..." style="width: 170px;">
                    </div>

                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
                    @if($categoryFilter || $levelFilter || $yearFilter || $search)
                        <a href="{{ route('kesiswaan.prestasi') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
                    @endif
                </form>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr style="background-color: #f4f6f9; color: #334155;">
                            <th style="width: 40px; text-align: center;">#</th>
                            <th>Santri & Rombel</th>
                            <th>Prestasi & Kejuaraan</th>
                            <th>Tingkat & Peringkat</th>
                            <th>Penyelenggara & Pembimbing</th>
                            <th style="text-align: center;">Apresiasi</th>
                            <th style="width: 85px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($achievements as $index => $ach)
                            @php
                                $std = $studentMap[$ach['student_id']] ?? null;
                                $cls = $std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null;
                                
                                $levelBadgeClass = match($ach['level']) {
                                    'Internasional' => 'label-danger',
                                    'Nasional' => 'label-warning',
                                    'Provinsi' => 'label-primary',
                                    'Kota/Kab' => 'label-info',
                                    default => 'label-default'
                                };
                            @endphp
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $std['name'] ?? 'Santri #'.$ach['student_id'] }}</strong><br>
                                    <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span style="font-weight: 600; color: #1e3a8a;">{{ $ach['title'] }}</span><br>
                                    <small class="text-muted"><i class="fa fa-tag"></i> {{ $ach['competition_name'] }} ({{ $ach['category'] }})</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="label {{ $levelBadgeClass }}">{{ $ach['level'] }}</span>
                                    <div style="font-weight: bold; margin-top: 3px; color: #d97706;">
                                        <i class="fa fa-medal"></i> {{ $ach['rank'] }}
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <small><strong>Penyelenggara:</strong> {{ $ach['organizer'] }}</small><br>
                                    <small class="text-muted"><i class="fa fa-user-secret"></i> Pembimbing: {{ $ach['coach_name'] }}</small>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <span class="badge bg-green" style="font-size: 11px;">+{{ $ach['points'] }} Poin</span><br>
                                    <small class="text-muted" style="font-size: 10px;">{{ date('d/m/Y', strtotime($ach['date'])) }}</small>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailPrestasi{{ $ach['id'] }}" title="Lihat Sertifikat & Portofolio">
                                            <i class="fa fa-eye text-primary"></i>
                                        </button>
                                        <a href="{{ route('kesiswaan.prestasi.destroy', $ach['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus arsip prestasi ini?')" title="Hapus Prestasi">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Detail Sertifikat & Rincian Prestasi --}}
                            <div class="modal fade" id="modalDetailPrestasi{{ $ach['id'] }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                                            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-certificate text-yellow"></i> Rincian Arsip Prestasi & Portofolio</h4>
                                        </div>
                                        <div class="modal-body" style="background-color: #fdfdfd;">
                                            <div class="text-center" style="margin-bottom: 20px; padding: 15px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 6px;">
                                                <i class="fa fa-trophy fa-3x text-yellow"></i>
                                                <h4 style="margin: 10px 0 5px 0; font-weight: bold; color: #1e293b;">{{ $ach['title'] }}</h4>
                                                <span class="label {{ $levelBadgeClass }}" style="font-size: 12px;">Tingkat {{ $ach['level'] }}</span> &nbsp;
                                                <span class="label label-warning" style="font-size: 12px;"><i class="fa fa-award"></i> {{ $ach['rank'] }}</span>
                                            </div>

                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th style="width: 35%;">Nama Santri</th>
                                                    <td><strong>{{ $std['name'] ?? '-' }}</strong> (NIS: {{ $std['nis'] ?? '-' }})</td>
                                                </tr>
                                                <tr>
                                                    <th>Rombel / Kelas</th>
                                                    <td>{{ $cls['name'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ajang / Kompetisi</th>
                                                    <td>{{ $ach['competition_name'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Bidang / Kategori</th>
                                                    <td>{{ $ach['category'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Penyelenggara</th>
                                                    <td>{{ $ach['organizer'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Waktu Pelaksanaan</th>
                                                    <td>{{ date('d F Y', strtotime($ach['date'])) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Guru Pembimbing</th>
                                                    <td>{{ $ach['coach_name'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Hadiah / Reward</th>
                                                    <td><span class="text-green" style="font-weight: bold;">{{ $ach['reward'] }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Nomor Sertifikat/SK</th>
                                                    <td><code>{{ $ach['certificate_number'] }}</code></td>
                                                </tr>
                                                <tr>
                                                    <th>Poin Apresiasi</th>
                                                    <td><span class="badge bg-green">+{{ $ach['points'] }} Poin Portofolio</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Catatan Dewan Juri / Pembina</th>
                                                    <td><em>"{{ $ach['notes'] }}"</em></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Lembar Portofolio</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-info-circle fa-2x"></i><br>
                                    Belum ada rekam data prestasi yang cocok dengan kriteria pencarian/filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix" style="background-color: #f9fafb;">
                <span class="text-muted pull-left">Menampilkan <strong>{{ count($achievements) }}</strong> dari <strong>{{ count($allAchievements) }}</strong> arsip kejuaraan santri.</span>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Top Peraih Prestasi & Panduan Poin Apresiasi --}}
    <div class="col-md-4">
        {{-- Peringkat Santri Berprestasi --}}
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-star text-yellow"></i> Top Santri Berprestasi</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="users-list clearfix">
                    @foreach(array_slice($studentAchievementCounts, 0, 4) as $item)
                        @php $s = $item['student']; @endphp
                        <li style="width: 50%; float: left; text-align: center; padding: 10px;">
                            <img src="{{ asset('assets/adminlte/dist/img/avatar' . (($s['gender'] ?? 'L') === 'L' ? '5' : '3') . '.png') }}" alt="Foto Santri" style="border-radius: 50%; width: 55px; height: 55px; border: 2px solid #f59e0b; padding: 2px; margin-bottom: 5px;">
                            <a class="users-list-name" href="javascript:void(0)" style="font-weight: 600; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">{{ $s['name'] ?? '-' }}</a>
                            <span class="users-list-date"><span class="badge bg-yellow">{{ $item['count'] }} Medali</span></span>
                            <span class="badge bg-green" style="font-size: 10px; margin-top: 3px;">{{ $item['total_points'] }} Poin</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="box-footer text-center" style="background: #fffdf5;">
                <small class="text-muted"><i class="fa fa-info-circle"></i> Santri dengan akumulasi poin apresiasi tertinggi berhak mendapatkan beasiswa syahriah berprestasi.</small>
            </div>
        </div>

        {{-- Tabel Matriks Standar Bobot Poin Prestasi --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bookmark text-aqua"></i> Standar Bobot Poin Apresiasi</h3>
            </div>
            <div class="box-body no-padding">
                <table class="table table-condensed table-striped" style="font-size: 12px;">
                    <thead>
                        <tr style="background: #e0f2fe; color: #0369a1;">
                            <th>Tingkat Kejuaraan</th>
                            <th>Peringkat 1</th>
                            <th>Peringkat 2 / 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="label label-danger">Internasional</span></td>
                            <td><strong>150 Poin</strong></td>
                            <td>120 / 100 Poin</td>
                        </tr>
                        <tr>
                            <td><span class="label label-warning">Nasional</span></td>
                            <td><strong>100 Poin</strong></td>
                            <td>75 / 50 Poin</td>
                        </tr>
                        <tr>
                            <td><span class="label label-primary">Provinsi</span></td>
                            <td><strong>50 Poin</strong></td>
                            <td>35 / 25 Poin</td>
                        </tr>
                        <tr>
                            <td><span class="label label-info">Kota / Kabupaten</span></td>
                            <td><strong>30 Poin</strong></td>
                            <td>20 / 15 Poin</td>
                        </tr>
                        <tr>
                            <td><span class="label label-default">Sekolah / Internal</span></td>
                            <td><strong>15 Poin</strong></td>
                            <td>10 / 5 Poin</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-footer" style="background-color: #f8fafc; font-size: 11px; color: #64748b;">
                Poin prestasi diintegrasikan ke dalam Buku Rapor Karakter & Portofolio Siswa untuk seleksi PTN / Beasiswa Luar Negeri.
            </div>
        </div>
    </div>
</div>

{{-- Modal Catat Prestasi Baru --}}
<div class="modal fade" id="modalTambahPrestasi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('kesiswaan.prestasi.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Catat Prestasi & Penghargaan Santri</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Pilih Santri <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri Penerima Penghargaan --</option>
                            @foreach($students as $s)
                                <option value="{{ $s['id'] }}">{{ $s['name'] }} (NIS: {{ $s['nis'] ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Prestasi / Penghargaan <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Cth: Juara 1 MHQ 30 Juz Tingkat Nasional" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Bidang / Kategori <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="Tahfidz & Keagamaan">Tahfidz & Keagamaan</option>
                                    <option value="Akademik & Sains">Akademik & Sains</option>
                                    <option value="Bahasa & Sastra">Bahasa & Sastra</option>
                                    <option value="Olahraga & Seni Bela Diri">Olahraga & Seni Bela Diri</option>
                                    <option value="Seni & Kaligrafi">Seni & Kaligrafi</option>
                                    <option value="Teknologi & Robotik">Teknologi & Robotik</option>
                                    <option value="Kepemimpinan & Organisasi">Kepemimpinan & Organisasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="level">Tingkat Penyelenggaraan <span class="text-danger">*</span></label>
                                <select name="level" id="level" class="form-control" required>
                                    <option value="Sekolah">Sekolah / Internal</option>
                                    <option value="Kota/Kab">Kota / Kabupaten</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional" selected>Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="competition_name">Nama Kompetisi / Ajang <span class="text-danger">*</span></label>
                                <input type="text" name="competition_name" id="competition_name" class="form-control" placeholder="Cth: Festival Sains Nasional 2026" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rank">Peringkat / Juara <span class="text-danger">*</span></label>
                                <input type="text" name="rank" id="rank" class="form-control" placeholder="Cth: Juara 1 / Medali Emas" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="points">Bobot Poin Apresiasi <span class="text-danger">*</span></label>
                                <input type="number" name="points" id="points" value="50" min="5" max="200" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="organizer">Lembaga Penyelenggara <span class="text-danger">*</span></label>
                                <input type="text" name="organizer" id="organizer" class="form-control" placeholder="Cth: Kementerian Agama RI" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coach_name">Guru Pembimbing / Pelatih <span class="text-danger">*</span></label>
                                <input type="text" name="coach_name" id="coach_name" class="form-control" placeholder="Cth: Dr. H. Zulkifli, Lc." required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reward">Bentuk Hadiah / Reward</label>
                                <input type="text" name="reward" id="reward" class="form-control" placeholder="Cth: Trophy + Uang Pembinaan Rp 5.000.000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificate_number">No. Sertifikat / Piagam</label>
                                <input type="text" name="certificate_number" id="certificate_number" class="form-control" placeholder="Cth: SK-PIAGAM/2026/099">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan & Ulasan Portofolio</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Komentar dewan juri atau pembina prestasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan ke Buku Prestasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
