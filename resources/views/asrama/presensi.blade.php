@extends('layouts.app')

@section('title', 'Presensi & Monitoring Asrama — IRNIS School')

@section('content_header')
    <h1>
        <i class="fa fa-calendar-check-o text-primary"></i> Presensi & Monitoring Ibadah Santri Asrama
        <small>Pemantauan Shalat Berjamaah 5 Waktu di Masjid, Qiyamul Lail & Keberadaan Santri Asrama</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Asrama / Boarding</li>
        <li class="active">Presensi Asrama</li>
    </ol>
@endsection

@section('content')
<div class="row">
    {{-- Metrik Ringkasan Presensi Sesi --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Target Santri Mukim</span>
                <span class="info-box-number">{{ count($activeAssignments) }} <small style="color: #cbd5e0; font-size: 11px;">Santri Aktif</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description text-muted" style="color: #e2e8f0;">Wajib Mengikuti Sesi</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Hadir Berjamaah</span>
                <span class="info-box-number">{{ $totalHadir }} <small style="color: #d1fae5; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Di Masjid / Tempat Ibadah</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sakit / Izin</span>
                <span class="info-box-number">{{ $totalSakit + $totalIzin }} <small style="color: #fff; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">{{ $totalSakit }} Sakit / {{ $totalIzin }} Izin</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Alpa / Terlambat</span>
                <span class="info-box-number">{{ $totalAlpa }} <small style="color: #fecaca; font-size: 11px;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description" style="color: #fef2f2;">Perlu Pembinaan Musyrif</span>
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

{{-- Filter Sesi & Quick Actions --}}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-filter text-primary"></i> Pilih Tanggal & Sesi Ibadah Asrama</h3>
        <div class="box-tools pull-right">
            <form method="POST" action="{{ route('asrama.presensi.quick') }}" style="display: inline-block;">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <input type="hidden" name="session" value="{{ $selectedSession }}">
                <button type="submit" class="btn btn-success btn-sm margin-r-5" onclick="return confirm('Tandai seluruh santri aktif HADIR pada sesi ini?')">
                    <i class="fa fa-check-square-o"></i> Hadirkan Semua (Quick Present)
                </button>
            </form>
            <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Lembar Presensi
            </button>
        </div>
    </div>
    <div class="box-body" style="background-color: #f9fafb;">
        <form method="GET" action="{{ route('asrama.presensi') }}" class="form-inline">
            <div class="form-group margin-r-5">
                <label for="date" class="control-label margin-r-5"><i class="fa fa-calendar"></i> Tanggal:</label>
                <input type="date" name="date" id="date" value="{{ $selectedDate }}" class="form-control input-sm">
            </div>

            <div class="form-group margin-r-5">
                <label for="session" class="control-label margin-r-5"><i class="fa fa-clock-o"></i> Sesi Ibadah:</label>
                <select name="session" id="session" class="form-control input-sm">
                    @foreach($availableSessions as $s)
                        <option value="{{ $s }}" {{ $selectedSession === $s ? 'selected' : '' }}>Sesi {{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group margin-r-5">
                <select name="room" class="form-control input-sm">
                    <option value="">-- Semua Kamar Asrama --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r['id'] }}" {{ $roomFilter === $r['id'] ? 'selected' : '' }}>{{ $r['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-search"></i> Tampilkan Lembar Presensi</button>
        </form>
    </div>
</div>

{{-- Formulir Presensi Santri Asrama --}}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-list-alt text-primary"></i> 
            Lembar Presensi Sesi <strong>{{ $selectedSession }}</strong> — {{ date('l, d F Y', strtotime($selectedDate)) }}
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-navy" style="font-size: 11px;"><i class="fa fa-map-marker"></i> Lokasi Utama: Masjid Jami' Kampus Alia</span>
        </div>
    </div>

    <form method="POST" action="{{ route('asrama.presensi.store') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $selectedDate }}">
        <input type="hidden" name="session" value="{{ $selectedSession }}">

        <div class="box-body" style="padding-bottom: 0;">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-5">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="supervisor_name">Musyrif / Imam Pembina Sesi Ini <span class="text-danger">*</span></label>
                        <input type="text" name="supervisor_name" id="supervisor_name" value="Ust. Salman Al-Farisi, S.Sos." class="form-control input-sm" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr style="background-color: #f4f6f9; color: #334155;">
                        <th style="width: 40px; text-align: center;">No</th>
                        <th>Santri & NIS</th>
                        <th>Kamar & Rombel</th>
                        <th style="width: 320px; text-align: center;">Status Kehadiran Sesi</th>
                        <th>Catatan Kedisiplinan Musyrif</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Indexing attendances yang sudah ada
                        $attendanceMap = [];
                        foreach ($attendances as $att) {
                            $attendanceMap[$att['student_id']] = $att;
                        }
                    @endphp

                    @forelse($activeAssignments as $index => $asn)
                        @php
                            $sid = $asn['student_id'];
                            $std = $studentMap[$sid] ?? null;
                            $cls = $std && isset($classMap[$std['class_id']]) ? $classMap[$std['class_id']] : null;
                            $rm = $roomMap[$asn['room_id']] ?? null;
                            
                            $savedAtt = $attendanceMap[$sid] ?? null;
                            $curStatus = $savedAtt['status'] ?? 'hadir';
                            $curNotes = $savedAtt['notes'] ?? '';
                        @endphp
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                            <td style="vertical-align: middle;">
                                <strong>{{ $std['name'] ?? 'Santri #'.$sid }}</strong><br>
                                <small class="text-muted"><i class="fa fa-id-badge"></i> NIS: {{ $std['nis'] ?? '-' }}</small>
                            </td>
                            <td style="vertical-align: middle;">
                                <i class="fa fa-home text-muted"></i> {{ $rm['name'] ?? '-' }}<br>
                                <small class="text-muted">{{ $cls['name'] ?? '-' }}</small>
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default btn-sm {{ $curStatus === 'hadir' ? 'active btn-success' : '' }}">
                                        <input type="radio" name="attendance[{{ $sid }}][status]" value="hadir" {{ $curStatus === 'hadir' ? 'checked' : '' }}> 
                                        <i class="fa fa-check"></i> Hadir
                                    </label>
                                    <label class="btn btn-default btn-sm {{ $curStatus === 'terlambat' ? 'active btn-warning' : '' }}">
                                        <input type="radio" name="attendance[{{ $sid }}][status]" value="terlambat" {{ $curStatus === 'terlambat' ? 'checked' : '' }}> 
                                        <i class="fa fa-clock-o"></i> Masbuq
                                    </label>
                                    <label class="btn btn-default btn-sm {{ $curStatus === 'sakit' ? 'active btn-info' : '' }}">
                                        <input type="radio" name="attendance[{{ $sid }}][status]" value="sakit" {{ $curStatus === 'sakit' ? 'checked' : '' }}> 
                                        <i class="fa fa-medkit"></i> Sakit
                                    </label>
                                    <label class="btn btn-default btn-sm {{ $curStatus === 'izin' ? 'active btn-primary' : '' }}">
                                        <input type="radio" name="attendance[{{ $sid }}][status]" value="izin" {{ $curStatus === 'izin' ? 'checked' : '' }}> 
                                        <i class="fa fa-ticket"></i> Izin
                                    </label>
                                    <label class="btn btn-default btn-sm {{ $curStatus === 'alpa' ? 'active btn-danger' : '' }}">
                                        <input type="radio" name="attendance[{{ $sid }}][status]" value="alpa" {{ $curStatus === 'alpa' ? 'checked' : '' }}> 
                                        <i class="fa fa-times"></i> Alpa
                                    </label>
                                </div>
                            </td>
                            <td style="vertical-align: middle;">
                                <input type="text" name="attendance[{{ $sid }}][notes]" value="{{ $curNotes }}" class="form-control input-sm" placeholder="Catatan saf / ketepatan waktu...">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 30px;">
                                Belum ada santri mukim aktif yang dialokasikan di asrama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix" style="background-color: #f9fafb;">
            <button type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Simpan Presensi Sesi {{ $selectedSession }}
            </button>
            <span class="text-muted pull-left" style="line-height: 32px;">
                Total santri yang diabsen pada sesi ini: <strong>{{ count($activeAssignments) }}</strong> orang santri.
            </span>
        </div>
    </form>
</div>
@endsection

