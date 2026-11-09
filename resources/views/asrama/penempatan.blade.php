@extends('layouts.app')

@section('title', 'Penempatan & Alokasi Kamar Santri — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-users text-primary"></i> Penempatan & Alokasi Kamar Santri
        <small>Distribusi Tempat Tidur, Nomor Lemari, Check-In & Relokasi Kamar Santri Asrama</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Asrama / Boarding</li>
        <li class="active">Penempatan Kamar</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Penempatan --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-bed"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kapasitas Tempat Tidur</span>
                <span class="info-box-number">{{ $totalKapasitasBed }} <small style="color: #cbd5e0; font-size: 11px;">Total Bed</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Daya Tampung Asrama</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-user-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Santri Mukim Aktif</span>
                <span class="info-box-number">{{ $totalSantriMukim }} <small style="color: #d1fae5; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Penghuni Menetap</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Belum Dialokasikan</span>
                <span class="info-box-number">{{ $totalBelumAlokasi }} <small style="color: #fff; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Perlu Penetapan Kamar</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sisa Bed Kosong</span>
                <span class="info-box-number">{{ $sisaBed }} <small style="color: #e9d8fd; font-size: 11px;">Bed Siap Huni</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #f3e8ff;">Tersedia untuk Santri Baru</span>
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
        <h3 class="box-title"><i class="fa fa-list text-primary"></i> Daftar Alokasi Penempatan Kamar Santri</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahPenempatan" {{ count($unassignedStudents) === 0 ? 'disabled' : '' }}>
                <i class="fa fa-plus-circle"></i> Alokasikan Santri (Check-In)
            </button>
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Daftar Roster
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="box-body" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
        <form method="GET" action="{{ route('asrama.penempatan') }}" class="form-inline">
            <div class="form-group margin-r-5">
                <label class="sr-only" for="building">Gedung</label>
                <select name="building" class="form-control input-sm">
                    <option value="">-- Semua Gedung --</option>
                    @foreach($buildings as $b)
                        <option value="{{ $b['id'] }}" {{ $buildingFilter === $b['id'] ? 'selected' : '' }}>{{ $b['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group margin-r-5">
                <label class="sr-only" for="room">Kamar</label>
                <select name="room" class="form-control input-sm">
                    <option value="">-- Semua Kamar --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r['id'] }}" {{ $roomFilter === $r['id'] ? 'selected' : '' }}>{{ $r['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group margin-r-5">
                <label class="sr-only" for="status">Status</label>
                <select name="status" class="form-control input-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ $statusFilter === 'aktif' ? 'selected' : '' }}>Aktif Menetap</option>
                    <option value="checkout" {{ $statusFilter === 'checkout' ? 'selected' : '' }}>Sudah Check-out</option>
                </select>
            </div>

            <div class="form-group margin-r-5">
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Cari santri / NIS / bed / lemari..." style="width: 220px;">
            </div>

            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-filter"></i> Filter</button>
            @if($buildingFilter || $roomFilter || $statusFilter || $search)
                <a href="{{ route('asrama.penempatan') }}" class="btn btn-default btn-sm" title="Reset filter"><i class="fa fa-refresh"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped">
            <thead>
                <tr style="background-color: #f4f6f9; color: #334155;">
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>Santri & Kelas</th>
                    <th>Gedung & Kamar</th>
                    <th>Alokasi Fasilitas Pribadi</th>
                    <th>Tanggal Masuk</th>
                    <th>Penanggung Jawab / Musyrif</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 90px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $index => $a)
                    @php
                        $std = $studentMap[$a['student_id']] ?? null;
                        $cls = $std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null;
                        $rm = $roomMap[$a['room_id']] ?? null;
                        $bld = $rm && isset($buildingMap[$rm['building_id']]) ? $buildingMap[$rm['building_id']] : null;
                    @endphp
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $std['name'] ?? 'Santri #'.$a['student_id'] }}</strong><br>
                            <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }} | {{ $cls['name'] ?? '-' }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            <span style="font-weight: 600; color: #1e3a8a;"><i class="fa fa-home"></i> {{ $rm['name'] ?? '-' }}</span><br>
                            <small class="text-muted">{{ $bld['name'] ?? ($rm['building_name'] ?? '-') }} (Lt {{ $rm['floor'] ?? '-' }})</small>
                        </td>
                        <td style="vertical-align: middle;">
                            <span class="label label-info"><i class="fa fa-bed"></i> {{ $a['bed_number'] }}</span> &nbsp;
                            <span class="label label-warning"><i class="fa fa-lock"></i> Lemari: {{ $a['wardrobe_number'] }}</span>
                        </td>
                        <td style="vertical-align: middle;">
                            <i class="fa fa-calendar"></i> {{ date('d F Y', strtotime($a['checkin_date'])) }}<br>
                            <small class="text-muted">{{ $a['notes'] ?? '-' }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            <small><i class="fa fa-user-secret text-muted"></i> {{ $a['assigned_by'] }}</small>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if($a['status'] === 'aktif')
                                <span class="label label-success"><i class="fa fa-check"></i> Menetap</span>
                            @else
                                <span class="label label-default"><i class="fa fa-sign-out"></i> Check-out</span>
                            @endif
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if($a['status'] === 'aktif')
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalTransfer{{ $a['id'] }}" title="Relokasi / Pindah Kamar">
                                        <i class="fa fa-exchange text-warning"></i>
                                    </button>
                                    <a href="{{ route('asrama.penempatan.checkout', $a['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Proses check-out santri ini dari asrama?')" title="Check-out">
                                        <i class="fa fa-sign-out text-danger"></i>
                                    </a>
                                </div>
                            @else
                                <span class="text-muted" style="font-size: 11px;">Arsip</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal Relokasi / Pindah Kamar --}}
                    @if($a['status'] === 'aktif')
                        <div class="modal fade" id="modalTransfer{{ $a['id'] }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('asrama.penempatan.transfer', $a['id']) }}">
                                        @csrf
                                        <div class="modal-header" style="background-color: #d97706; color: #fff;">
                                            <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-exchange"></i> Relokasi / Pindah Kamar Santri</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="well well-sm" style="font-size: 12px; background: #fffbeb;">
                                                <strong>Santri:</strong> {{ $std['name'] ?? '-' }} (NIS: {{ $std['nis'] ?? '-' }})<br>
                                                <strong>Kamar Saat Ini:</strong> {{ $rm['name'] ?? '-' }} | {{ $a['bed_number'] }}
                                            </div>

                                            <div class="form-group">
                                                <label for="target_room_id_{{ $a['id'] }}">Pilih Kamar Tujuan <span class="text-danger">*</span></label>
                                                <select name="target_room_id" id="target_room_id_{{ $a['id'] }}" class="form-control" required>
                                                    <option value="">-- Pilih Kamar Tujuan --</option>
                                                    @foreach($rooms as $destRoom)
                                                        @if($destRoom['id'] !== $a['room_id'] && ($destRoom['gender'] ?? 'L') === ($std['gender'] ?? 'L'))
                                                            @php
                                                                $curRes = count($destRoom['resident_student_ids'] ?? []);
                                                                $sisa = ($destRoom['capacity'] ?? 6) - $curRes;
                                                            @endphp
                                                            <option value="{{ $destRoom['id'] }}" {{ $sisa <= 0 ? 'disabled' : '' }}>
                                                                {{ $destRoom['name'] }} (Sisa {{ $sisa }} Bed)
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="bed_number_{{ $a['id'] }}">Alokasi Bed Baru <span class="text-danger">*</span></label>
                                                        <input type="text" name="bed_number" id="bed_number_{{ $a['id'] }}" value="Bed 02 (Bawah)" class="form-control" placeholder="Cth: Bed 01 (Bawah)" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="wardrobe_number_{{ $a['id'] }}">Nomor Lemari Baru <span class="text-danger">*</span></label>
                                                        <input type="text" name="wardrobe_number" id="wardrobe_number_{{ $a['id'] }}" value="LMR-BARU-01" class="form-control" placeholder="Cth: LMR-102-B" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="notes_{{ $a['id'] }}">Alasan Perpindahan</label>
                                                <textarea name="notes" id="notes_{{ $a['id'] }}" rows="2" class="form-control" placeholder="Penataan kenyamanan halaqah, pergantian semester, rekomendasi pamong..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning"><i class="fa fa-check"></i> Proses Pindah Kamar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                            <i class="fa fa-info-circle fa-2x"></i><br>
                            Belum ada rekam data alokasi kamar santri yang sesuai kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix" style="background-color: #f9fafb;">
        <span class="text-muted pull-left">Menampilkan <strong>{{ count($assignments) }}</strong> dari <strong>{{ count($allAssignments) }}</strong> rekam alokasi kamar santri.</span>
    </div>
</div>

{{-- Modal Check-In / Tambah Alokasi Santri Baru --}}
<div class="modal fade" id="modalTambahPenempatan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('asrama.penempatan.store') }}">
                @csrf
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle text-yellow"></i> Alokasikan Santri ke Kamar (Check-In)</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Pilih Santri yang Belum Dialokasikan <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($unassignedStudents as $us)
                                <option value="{{ $us['id'] }}">{{ $us['name'] }} ({{ $us['gender'] === 'L' ? 'Ikhwan' : 'Akhwat' }} | NIS: {{ $us['nis'] ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="room_id">Pilih Kamar Asrama <span class="text-danger">*</span></label>
                        <select name="room_id" id="room_id" class="form-control" required>
                            <option value="">-- Pilih Kamar yang Tersedia --</option>
                            @foreach($rooms as $rm)
                                @php
                                    $resCount = count($rm['resident_student_ids'] ?? []);
                                    $sisa = ($rm['capacity'] ?? 6) - $resCount;
                                @endphp
                                <option value="{{ $rm['id'] }}" {{ $sisa <= 0 ? 'disabled' : '' }}>
                                    {{ $rm['name'] }} ({{ $rm['category'] }} | Sisa {{ $sisa }} Bed)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bed_number">Nomor & Posisi Tempat Tidur <span class="text-danger">*</span></label>
                                <input type="text" name="bed_number" id="bed_number" value="Bed 03 (Atas)" class="form-control" placeholder="Cth: Bed 02 (Bawah)" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wardrobe_number">Nomor Lemari Pakaian <span class="text-danger">*</span></label>
                                <input type="text" name="wardrobe_number" id="wardrobe_number" value="LMR-101-C" class="form-control" placeholder="Cth: LMR-101-C" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="checkin_date">Tanggal Masuk Asrama <span class="text-danger">*</span></label>
                                <input type="date" name="checkin_date" id="checkin_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_by">Musyrif / Petugas Penempatan <span class="text-danger">*</span></label>
                                <input type="text" name="assigned_by" id="assigned_by" value="Ust. Salman Al-Farisi, S.Sos." class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan Fasilitas / Serah Terima Kunci</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Kunci lemari diserahkan, kondisi ranjang baik..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Konfirmasi Alokasi Kamar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

