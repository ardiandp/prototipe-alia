@extends('layouts.app')

@section('title', 'Mutasi Santri — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-exchange text-primary"></i> Mutasi Santri (Pindahan Masuk & Keluar)
        <small>Pengelolaan Santri Pindahan, Mutasi Keluar, Surat Keterangan Pindah & Buku Induk Registrasi</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Kesiswaan</li>
        <li class="active">Mutasi Santri</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Mutasi --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-folder-open-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Rekam Mutasi</span>
                <span class="info-box-number">{{ $totalMutasi }} <small style="color: #cbd5e0; font-size: 11px;">Berkas SK</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Buku Registrasi Mutasi</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-sign-in"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pindahan Masuk</span>
                <span class="info-box-number">{{ $totalMasuk }} <small style="color: #d1fae5; font-size: 11px;">Santri Baru</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Santri Pindahan dari Luar</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-sign-out"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pindahan Keluar</span>
                <span class="info-box-number">{{ $totalKeluar }} <small style="color: #fff; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Pindah Sekolah / Pondok Lain</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">SK Disetujui</span>
                <span class="info-box-number">{{ $totalDisetujui }} <small style="color: #e9d8fd; font-size: 11px;">Disahkan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Tercatat Resmi di Buku Induk</span>
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

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list-alt text-primary"></i> Daftar Berkas Mutasi Santri</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahMutasi">
                <i class="fa fa-plus-circle"></i> Proses Mutasi Baru
            </button>
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Rekap Mutasi
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
        <form method="GET" action="{{ route('kesiswaan.mutasi') }}" class="form-inline">
            <div class="form-group margin-r-5">
                <label class="sr-only" for="type">Jenis Mutasi</label>
                <select name="type" class="form-control input-sm">
                    <option value="">-- Semua Jenis Mutasi --</option>
                    <option value="masuk" {{ $typeFilter === 'masuk' ? 'selected' : '' }}>Pindahan Masuk</option>
                    <option value="keluar" {{ $typeFilter === 'keluar' ? 'selected' : '' }}>Pindahan Keluar</option>
                    <option value="lulus" {{ $typeFilter === 'lulus' ? 'selected' : '' }}>Lulus / Alumni</option>
                    <option value="dikeluarkan" {{ $typeFilter === 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan (Drop Out)</option>
                </select>
            </div>

            <div class="form-group margin-r-5">
                <label class="sr-only" for="status">Status Pengesahan</label>
                <select name="status" class="form-control input-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui / Sah</option>
                    <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Dalam Proses Verifikasi</option>
                    <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="form-group margin-r-5">
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari santri / No SK / sekolah..." style="width: 220px;">
            </div>

            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
            @if($typeFilter || $statusFilter || $search)
                <a href="{{ route('kesiswaan.mutasi') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped">
            <thead>
                <tr style="background-color: #f4f6f9; color: #334155;">
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>Nomor SK & Tanggal</th>
                    <th>Santri & Rombel</th>
                    <th>Jenis Mutasi</th>
                    <th>Sekolah Asal / Tujuan</th>
                    <th>Alasan Mutasi</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 85px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutations as $index => $m)
                    @php
                        $std = $studentMap[$m['student_id']] ?? null;
                        $cls = isset($classMap[$m['class_id']]) ? $classMap[$m['class_id']] : ($std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null);
                        
                        $typeBadge = match($m['type']) {
                            'masuk' => '<span class="label label-success"><i class="fa fa-arrow-down"></i> Pindahan Masuk</span>',
                            'keluar' => '<span class="label label-warning"><i class="fa fa-arrow-up"></i> Pindahan Keluar</span>',
                            'lulus' => '<span class="label label-primary"><i class="fa fa-graduation-cap"></i> Lulus</span>',
                            'dikeluarkan' => '<span class="label label-danger"><i class="fa fa-ban"></i> Dikeluarkan</span>',
                            default => '<span class="label label-default">Lainnya</span>'
                        };
                    @endphp
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;">
                            <strong><code>{{ $m['mutation_number'] }}</code></strong><br>
                            <small class="text-muted"><i class="fa fa-calendar"></i> {{ date('d F Y', strtotime($m['date'])) }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $std['name'] ?? 'Santri #'.$m['student_id'] }}</strong><br>
                            <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            {!! $typeBadge !!}
                        </td>
                        <td style="vertical-align: middle;">
                            @if($m['type'] === 'masuk')
                                <small><strong>Asal Sekolah:</strong></small><br>
                                <span class="text-primary">{{ $m['origin_school'] ?? '-' }}</span>
                            @else
                                <small><strong>Sekolah Tujuan:</strong></small><br>
                                <span class="text-warning" style="font-weight: 500;">{{ $m['destination_school'] ?? '-' }}</span>
                            @endif
                        </td>
                        <td style="vertical-align: middle; max-width: 250px;">
                            <div style="font-size: 12px; color: #1f2937;">
                                {{ Str::limit($m['reason'], 85) }}
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <span class="label label-success"><i class="fa fa-check"></i> Disetujui</span><br>
                            <small class="text-muted" style="font-size: 10px;">Oleh Pimpinan</small>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailMutasi{{ $m['id'] }}" title="Lihat Berkas & Cetak SK">
                                    <i class="fa fa-eye text-primary"></i>
                                </button>
                                <a href="{{ route('kesiswaan.mutasi.destroy', $m['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Hapus berkas mutasi ini?')" title="Hapus">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Detail SK Mutasi & Cetak Surat --}}
                    <div class="modal fade" id="modalDetailMutasi{{ $m['id'] }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                    <h4 class="modal-title"><i class="fa fa-file-pdf-o text-yellow"></i> Surat Keterangan Mutasi Santri</h4>
                                </div>
                                <div class="modal-body" style="background-color: #fdfdfd;">
                                    {{-- Kop Surat Mini --}}
                                    <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 12px; margin-bottom: 15px;">
                                        <h4 style="margin: 0; font-weight: bold; color: #1a365d; text-transform: uppercase;">PESANTREN ISLAM TERPADU ALIA</h4>
                                        <h5 style="margin: 3px 0 0 0; font-weight: bold; color: #475569;">ALIA ISLAMIC BOARDING SCHOOL</h5>
                                        <small style="color: #64748b; font-size: 11px;">Jl. Raya Pesantren No. 01, Telukjambe Timur, Karawang, Jawa Barat | Telp: (0267) 845-1926</small>
                                    </div>

                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 3px;">SURAT KETERANGAN PINDAH / MUTASI</h4>
                                        <code style="font-size: 13px;">Nomor: {{ $m['mutation_number'] }}</code>
                                    </div>

                                    <p style="font-size: 13px; line-height: 1.6;">
                                        Yang bertanda tangan di bawah ini Pimpinan Pondok Pesantren Alia Islamic School menerangkan bahwa santri:
                                    </p>

                                    <table class="table table-bordered table-striped" style="font-size: 13px;">
                                        <tr>
                                            <th style="width: 35%;">Nama Lengkap Santri</th>
                                            <td><strong>{{ $std['name'] ?? '-' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Nomor Induk Santri (NIS)</th>
                                            <td>{{ $std['nis'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tingkat / Rombongan Belajar</th>
                                            <td>{{ $cls['name'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Permohonan Mutasi</th>
                                            <td>{!! $typeBadge !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Efektif Mutasi</th>
                                            <td><strong>{{ date('d F Y', strtotime($m['date'])) }}</strong></td>
                                        </tr>
                                        @if($m['origin_school'])
                                            <tr>
                                                <th>Madrasah / Sekolah Asal</th>
                                                <td>{{ $m['origin_school'] }}</td>
                                            </tr>
                                        @endif
                                        @if($m['destination_school'])
                                            <tr>
                                                <th>Madrasah / Sekolah Tujuan</th>
                                                <td><strong class="text-primary">{{ $m['destination_school'] }}</strong></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Alasan Pindah / Mutasi</th>
                                            <td><em>"{{ $m['reason'] }}"</em></td>
                                        </tr>
                                        <tr>
                                            <th>Pejabat yang Mengesahkan</th>
                                            <td><strong>{{ $m['approver_name'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Catatan Administrasi</th>
                                            <td>{{ $m['notes'] }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Surat Keterangan Resmi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                            <i class="fa fa-info-circle fa-2x"></i><br>
                            Belum ada rekam data mutasi santri yang sesuai kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix" style="background-color: #f9fafb;">
        <span class="text-muted pull-left">Menampilkan <strong>{{ count($mutations) }}</strong> dari <strong>{{ count($allMutations) }}</strong> berkas mutasi santri.</span>
    </div>
</div>

{{-- Modal Proses Mutasi Baru --}}
<div class="modal fade" id="modalTambahMutasi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('kesiswaan.mutasi.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Proses Berkas Mutasi Santri Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Pilih Santri <span class="text-danger">*</span></label>
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
                                <label for="type">Jenis Mutasi <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="keluar">Pindahan Keluar (Pindah Pondok/Sekolah Lain)</option>
                                    <option value="masuk">Pindahan Masuk (Santri Pindahan Masuk)</option>
                                    <option value="lulus">Lulus / Selesai Pendidikan</option>
                                    <option value="dikeluarkan">Dikeluarkan / Drop Out</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_id">Rombel / Kelas <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    @foreach($classes as $c)
                                        <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal Mutasi Efektif <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="approver_name">Pejabat Pengesah <span class="text-danger">*</span></label>
                                <input type="text" name="approver_name" id="approver_name" value="Dr. H. Zulkifli, Lc., M.A. (Mudir Pesantren)" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="origin_school">Sekolah Asal (Jika Pindahan Masuk)</label>
                                <input type="text" name="origin_school" id="origin_school" class="form-control" placeholder="Cth: SMP IT Nurul Fikri Depok">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="destination_school">Sekolah Tujuan (Jika Pindah Keluar)</label>
                                <input type="text" name="destination_school" id="destination_school" class="form-control" placeholder="Cth: Pesantren Husnul Khotimah Kuningan">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason">Alasan Permohonan Mutasi <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="3" class="form-control" placeholder="Uraikan alasan mutasi, rekomendasi keluarga, atau perpindahan domisili wali..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan & Kelengkapan Berkas</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Kelengkapan buku rapor, surat bebas tanggungan syahriah & perpustakaan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Terbitkan SK & Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

