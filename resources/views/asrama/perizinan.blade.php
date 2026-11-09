@extends('layouts.app')

@section('title', 'Perizinan Keluar & Pulang Santri — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-ticket text-primary"></i> Workflow Perizinan Keluar & Pulang Santri
        <small>Pengajuan Izin, Verifikasi Musyrif, Penjemputan Orang Tua & Gate Pass Pos Keamanan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Asrama / Boarding</li>
        <li class="active">Perizinan Santri</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Perizinan --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-ticket"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Berkas Izin</span>
                <span class="info-box-number">{{ $totalIzin }} <small style="color: #cbd5e0; font-size: 11px;">Pengajuan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Buku Register Perizinan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Verifikasi</span>
                <span class="info-box-number">{{ $totalDiajukan }} <small style="color: #fff; font-size: 11px;">Pengajuan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Persetujuan Musyrif / Mudir</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-id-badge"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Gate Pass Aktif (Di Luar)</span>
                <span class="info-box-number">{{ $totalDisetujui }} <small style="color: #d1fae5; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Sedang Izin / Belum Kembali</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Telah Kembali di Asrama</span>
                <span class="info-box-number">{{ $totalKembali }} <small style="color: #e9d8fd; font-size: 11px;">Check-In</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Tuntas di Pos Keamanan</span>
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

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list-alt text-primary"></i> Daftar Pengajuan Surat Izin & Gate Pass Santri</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahPerizinan">
                <i class="fa fa-plus-circle"></i> Buat Surat Izin Baru
            </button>
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Rekap Izin
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
        <form method="GET" action="{{ route('asrama.perizinan') }}" class="form-inline">
            <div class="form-group margin-r-5">
                <label class="sr-only" for="type">Jenis Izin</label>
                <select name="type" class="form-control input-sm">
                    <option value="">-- Semua Jenis Izin --</option>
                    <option value="keluar_sebentar" {{ $typeFilter === 'keluar_sebentar' ? 'selected' : '' }}>Izin Keluar Sebentar (Beberapa Jam)</option>
                    <option value="pulang" {{ $typeFilter === 'pulang' ? 'selected' : '' }}>Izin Pulang Bermalam</option>
                    <option value="darurat" {{ $typeFilter === 'darurat' ? 'selected' : '' }}>Izin Darurat / Kesehatan</option>
                </select>
            </div>

            <div class="form-group margin-r-5">
                <label class="sr-only" for="status">Status</label>
                <select name="status" class="form-control input-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui (Sedang di Luar)</option>
                    <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="kembali" {{ $statusFilter === 'kembali' ? 'selected' : '' }}>Sudah Kembali</option>
                    <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="form-group margin-r-5">
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari santri / No izin / penjemput..." style="width: 220px;">
            </div>

            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
            @if($typeFilter || $statusFilter || $search)
                <a href="{{ route('asrama.perizinan') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped">
            <thead>
                <tr style="background-color: #f4f6f9; color: #334155;">
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>Nomor Izin & Gate Pass</th>
                    <th>Santri & Kelas</th>
                    <th>Jenis Izin</th>
                    <th>Waktu Berangkat s/d Kembali</th>
                    <th>Penjemput / Pendamping</th>
                    <th>Alasan Keperluan</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 95px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $index => $p)
                    @php
                        $std = $studentMap[$p['student_id']] ?? null;
                        $cls = $std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null;
                        
                        $typeBadge = match($p['type']) {
                            'keluar_sebentar' => '<span class="label label-info"><i class="fa fa-clock-o"></i> Keluar Sebentar</span>',
                            'pulang' => '<span class="label label-primary"><i class="fa fa-home"></i> Pulang Bermalam</span>',
                            'darurat' => '<span class="label label-danger"><i class="fa fa-ambulance"></i> Izin Darurat</span>',
                            default => '<span class="label label-default">Lainnya</span>'
                        };

                        $statusBadge = match($p['approval_status']) {
                            'disetujui' => '<span class="label label-warning"><i class="fa fa-sign-out"></i> Di Luar Kampus</span>',
                            'diajukan' => '<span class="label label-info"><i class="fa fa-hourglass-start"></i> Verifikasi</span>',
                            'kembali' => '<span class="label label-success"><i class="fa fa-check"></i> Sudah Kembali</span>',
                            'ditolak' => '<span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>',
                            default => '<span class="label label-default">Draft</span>'
                        };
                    @endphp
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;">
                            <strong><code>{{ $p['permit_number'] }}</code></strong><br>
                            <span class="badge bg-navy" style="font-size: 10px; margin-top: 2px;"><i class="fa fa-barcode"></i> {{ $p['gate_pass_code'] }}</span>
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $std['name'] ?? 'Santri #'.$p['student_id'] }}</strong><br>
                            <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            {!! $typeBadge !!}
                        </td>
                        <td style="vertical-align: middle; font-size: 12px;">
                            <i class="fa fa-sign-out text-danger"></i> {{ date('d/m/Y H:i', strtotime($p['date_start'])) }}<br>
                            <i class="fa fa-sign-in text-success"></i> {{ date('d/m/Y H:i', strtotime($p['date_end'])) }}
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $p['pickup_person'] }}</strong><br>
                            <small class="text-muted"><i class="fa fa-phone"></i> {{ $p['pickup_phone'] }}</small>
                        </td>
                        <td style="vertical-align: middle; max-width: 220px;">
                            <div style="font-size: 12px; color: #1f2937;">
                                {{ Str::limit($p['reason'], 75) }}
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            {!! $statusBadge !!}
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalGatePass{{ $p['id'] }}" title="Lihat Surat & Gate Pass Satpam">
                                    <i class="fa fa-eye text-primary"></i>
                                </button>
                                @if($p['approval_status'] === 'disetujui')
                                    <a href="{{ route('asrama.perizinan.checkin', $p['id']) }}" class="btn btn-success btn-xs" onclick="return confirm('Konfirmasi bahwa santri telah kembali di asrama?')" title="Konfirmasi Kembali (Check-In)">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @endif
                                <a href="{{ route('asrama.perizinan.destroy', $p['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Hapus berkas izin ini?')" title="Hapus">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Gate Pass & Surat Izin Satpam --}}
                    <div class="modal fade" id="modalGatePass{{ $p['id'] }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                    <h4 class="modal-title"><i class="fa fa-id-card text-yellow"></i> Surat Izin & Gate Pass Keamanan Pondok</h4>
                                </div>
                                <div class="modal-body" style="background-color: #fdfdfd;">
                                    {{-- Tiket Gate Pass Mini --}}
                                    <div class="text-center" style="background: #eff6ff; border: 2px dashed #3b82f6; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                                        <span class="badge bg-blue" style="font-size: 12px; padding: 6px 12px; text-transform: uppercase;">
                                            GATE PASS SANTRI: {{ $p['gate_pass_code'] }}
                                        </span>
                                        <h3 style="margin: 10px 0 3px 0; font-weight: bold; color: #1e3a8a;">{{ $std['name'] ?? '-' }}</h3>
                                        <p style="margin: 0; color: #64748b; font-size: 12px;">NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</p>
                                    </div>

                                    <table class="table table-bordered table-striped" style="font-size: 13px;">
                                        <tr>
                                            <th style="width: 35%;">Nomor Surat Izin</th>
                                            <td><strong>{{ $p['permit_number'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Izin</th>
                                            <td>{!! $typeBadge !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Keperluan / Alasan</th>
                                            <td><em>"{{ $p['reason'] }}"</em></td>
                                        </tr>
                                        <tr>
                                            <th>Batas Waktu Keluar</th>
                                            <td><strong>{{ date('d F Y H:i', strtotime($p['date_start'])) }} WIB</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Batas Waktu Kembali</th>
                                            <td><strong class="text-red">{{ date('d F Y H:i', strtotime($p['date_end'])) }} WIB</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Penjemput / Kontak</th>
                                            <td>{{ $p['pickup_person'] }} (Telp: {{ $p['pickup_phone'] }})</td>
                                        </tr>
                                        <tr>
                                            <th>Diverifikasi Oleh</th>
                                            <td><strong>{{ $p['approver_name'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Waktu Keluar Pos Satpam</th>
                                            <td>{{ !empty($p['security_check_out']) ? date('d/m/Y H:i:s', strtotime($p['security_check_out'])) : 'Belum Melintasi Pos Satpam' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Waktu Kembali Pos Satpam</th>
                                            <td>{{ !empty($p['security_check_in']) ? date('d/m/Y H:i:s', strtotime($p['security_check_in'])) : 'Masih di luar kampus' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Catatan Khusus</th>
                                            <td>{{ $p['notes'] }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Lembar Gate Pass</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                            <i class="fa fa-info-circle fa-2x"></i><br>
                            Belum ada riwayat perizinan keluar/pulang yang sesuai filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix" style="background-color: #f9fafb;">
        <span class="text-muted pull-left">Menampilkan <strong>{{ count($permissions) }}</strong> dari <strong>{{ count($allPermissions) }}</strong> berkas perizinan santri.</span>
    </div>
</div>

{{-- Modal Ajukan Surat Izin Baru --}}
<div class="modal fade" id="modalTambahPerizinan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('asrama.perizinan.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Buat Surat Izin Keluar / Pulang Santri</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Pilih Santri Pemohon <span class="text-danger">*</span></label>
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
                                <label for="type">Jenis Perizinan <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="keluar_sebentar">Izin Keluar Sebentar (Beberapa Jam)</option>
                                    <option value="pulang">Izin Pulang Bermalam (Keluarga)</option>
                                    <option value="darurat">Izin Darurat / Medis Kesehatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="approver_name">Pejabat yang Menyetujui <span class="text-danger">*</span></label>
                                <input type="text" name="approver_name" id="approver_name" value="Ust. Salman Al-Farisi, S.Sos. (Musyrif Asrama)" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_start">Waktu Berangkat / Keluar <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_start" id="date_start" value="{{ date('Y-m-d\TH:i') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_end">Batas Waktu Kembali di Asrama <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_end" id="date_end" value="{{ date('Y-m-d\TH:i', strtotime('+4 hours')) }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pickup_person">Nama Penjemput / Wali <span class="text-danger">*</span></label>
                                <input type="text" name="pickup_person" id="pickup_person" class="form-control" placeholder="Cth: Orang Tua Kandung / Mandiri" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pickup_phone">Nomor Telepon Penjemput <span class="text-danger">*</span></label>
                                <input type="text" name="pickup_phone" id="pickup_phone" class="form-control" placeholder="Cth: 081234567890" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason">Alasan & Keperluan Izin <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="2" class="form-control" placeholder="Uraikan keperluan izin santri secara lengkap..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan Tambahan untuk Pos Satpam</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Instruksi penggeledahan barang bawaan saat kembali, dll..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Terbitkan Surat Izin & Gate Pass</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

