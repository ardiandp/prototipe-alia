@extends('layouts.app')

@section('title', 'Peminjaman & Jadwal Ruangan')

@section('content_header')
    <h1>
        <i class="fa fa-calendar-check-o text-primary"></i> Peminjaman & Jadwal Ruangan
        <small>Peminjaman Laboratorium Komputer, Sains, Bahasa, Aula Serbaguna & Lapangan Kampus</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Sarpras & Fasilitas</li>
        <li class="active">Peminjaman Ruangan</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Permohonan</span>
                <span class="info-box-number">{{ $totalBookings }} Kegiatan</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Tercatat di sistem Sarpras</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Persetujuan</span>
                <span class="info-box-number">{{ $totalPending }} Permohonan</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalBookings > 0 ? round(($totalPending / $totalBookings) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Perlu verifikasi jadwal sarpras</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Disetujui / Terjadwal</span>
                <span class="info-box-number">{{ $totalApproved }} Jadwal</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalBookings > 0 ? round(($totalApproved / $totalBookings) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Ruangan siap digunakan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-flag-checkered"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kegiatan Selesai</span>
                <span class="info-box-number">{{ $totalCompleted }} Acara</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalBookings > 0 ? round(($totalCompleted / $totalBookings) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Telah rampung & dirapikan</span>
            </div>
        </div>
    </div>
</div>

{{-- Feedback Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan</h4>
        {{ session('info') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Daftar Peminjaman & Status</a>
                </li>
                <li class="{{ $activeTab === 'jadwal' ? 'active' : '' }}">
                    <a href="#tab_jadwal" data-toggle="tab"><i class="fa fa-calendar text-green"></i> Jadwal Pemakaian Ruangan (Timeline)</a>
                </li>
                <li class="{{ $activeTab === 'persetujuan' ? 'active' : '' }}">
                    <a href="#tab_persetujuan" data-toggle="tab">
                        <i class="fa fa-check-square-o text-yellow"></i> Antrean Persetujuan Sarpras
                        @if($totalPending > 0)
                            <span class="badge bg-yellow" style="margin-left: 5px;">{{ $totalPending }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Daftar Peminjaman & Status --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('sarpras.peminjaman') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Status:</label>
                                    <select name="status" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                        <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Diajukan (Pending)</option>
                                        <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui (Approved)</option>
                                        <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai Digunakan</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Ruangan:</label>
                                    <select name="room" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $roomFilter === 'all' ? 'selected' : '' }}>-- Semua Ruangan --</option>
                                        @foreach($bookableVenues as $v)
                                            <option value="{{ $v['id'] }}" {{ $roomFilter === $v['id'] ? 'selected' : '' }}>{{ $v['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Tanggal:</label>
                                    <input type="date" name="date" class="form-control input-sm" value="{{ $dateFilter }}" onchange="this.form.submit()">
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Kegiatan, pemohon, kode..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('sarpras.peminjaman', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-ajukan-peminjaman">
                                        <i class="fa fa-plus-circle"></i> Ajukan Peminjaman
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Jadwal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Peminjaman Ruangan --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Booking</th>
                                    <th>Ruangan / Fasilitas</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Keperluan Acara / Agenda</th>
                                    <th>Pemohon & Unit</th>
                                    <th class="text-center">Peserta</th>
                                    <th class="text-center">Status</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredBookings as $bkg)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $bkg['booking_code'] }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-purple">{{ $bkg['room_name'] }}</strong><br>
                                            <small class="text-muted"><i class="fa fa-map-marker text-red"></i> {{ $bkg['room_location'] }}</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar text-blue"></i> {{ date('d M Y', strtotime($bkg['booking_date'])) }}<br>
                                            <small class="text-bold text-navy"><i class="fa fa-clock-o"></i> {{ $bkg['start_time'] }} - {{ $bkg['end_time'] }} WIB</small>
                                        </td>
                                        <td>
                                            <span class="text-bold">{{ $bkg['purpose'] }}</span><br>
                                            <small class="text-muted"><i class="fa fa-wrench"></i> {{ $bkg['equipment_needed'] }}</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> {{ $bkg['requester_name'] }}<br>
                                            <span class="label label-primary" style="font-size: 10px;">{{ $bkg['requester_role'] }}</span>
                                        </td>
                                        <td class="text-center text-bold">
                                            {{ $bkg['attendees_count'] }} org
                                        </td>
                                        <td class="text-center">
                                            @if($bkg['status'] === 'diajukan')
                                                <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu Approval</span>
                                            @elseif($bkg['status'] === 'disetujui')
                                                <span class="label label-success"><i class="fa fa-check-circle"></i> Disetujui</span>
                                            @elseif($bkg['status'] === 'ditolak')
                                                <span class="label label-danger"><i class="fa fa-times-circle"></i> Ditolak</span>
                                            @elseif($bkg['status'] === 'selesai')
                                                <span class="label label-default"><i class="fa fa-flag-checkered"></i> Selesai</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-bkg-{{ $bkg['id'] }}" title="Lihat Surat Izin Peminjaman">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                @if($bkg['status'] === 'diajukan')
                                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-approve-bkg-{{ $bkg['id'] }}" title="Proses Persetujuan">
                                                        <i class="fa fa-check-square-o text-green"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('sarpras.peminjaman.destroy', $bkg['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus permohonan peminjaman ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Surat Izin Peminjaman Ruangan Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-bkg-{{ $bkg['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Surat Izin Pemakaian Fasilitas Kampus</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">BAGIAN SARANA & PRASARANA (SARPRAS) KAMPUS TERPADU</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8660-1234</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">FORMULIR PERSETUJUAN PEMAKAIAN RUANGAN</h5>
                                                                <small>Nomor: <strong>{{ $bkg['booking_code'] }}</strong></small>
                                                            </div>

                                                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                                <tr>
                                                                    <th width="35%">Ruangan / Sarana</th>
                                                                    <td><strong class="text-purple">{{ $bkg['room_name'] }}</strong> ({{ $bkg['room_location'] }})</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nama Pemohon</th>
                                                                    <td>{{ $bkg['requester_name'] }} ({{ $bkg['requester_role'] }})</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Unit Penyelenggara</th>
                                                                    <td><span class="label label-info">{{ $bkg['unit_id'] }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Waktu Pelaksanaan</th>
                                                                    <td>
                                                                        <strong>{{ date('d F Y', strtotime($bkg['booking_date'])) }}</strong><br>
                                                                        Pukul {{ $bkg['start_time'] }} s/d {{ $bkg['end_time'] }} WIB
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Keperluan / Agenda</th>
                                                                    <td>{{ $bkg['purpose'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Estimasi Jumlah Hadir</th>
                                                                    <td>{{ $bkg['attendees_count'] }} Orang</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kebutuhan Sarana & Logistik</th>
                                                                    <td>{{ $bkg['equipment_needed'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status Verifikasi</th>
                                                                    <td>
                                                                        @if($bkg['status'] === 'disetujui')
                                                                            <span class="label label-success"><i class="fa fa-check"></i> DISETUJUI & SIAP PAKAI</span>
                                                                        @elseif($bkg['status'] === 'diajukan')
                                                                            <span class="label label-warning"><i class="fa fa-clock-o"></i> MENUNGGU PERSETUJUAN SARPRAS</span>
                                                                        @elseif($bkg['status'] === 'ditolak')
                                                                            <span class="label label-danger"><i class="fa fa-times"></i> TIDAK DISETUJUI / DITOLAK</span>
                                                                        @else
                                                                            <span class="label label-default">SELESAI</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @if($bkg['approver_name'])
                                                                    <tr>
                                                                        <th>Pejabat Yang Menyetujui</th>
                                                                        <td>
                                                                            <i class="fa fa-check-circle text-green"></i> {{ $bkg['approver_name'] }}<br>
                                                                            <small class="text-muted">Tanggal: {{ $bkg['approved_at'] }}</small>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if($bkg['rejection_reason'])
                                                                    <tr class="danger">
                                                                        <th>Alasan Penolakan</th>
                                                                        <td class="text-danger"><strong>{{ $bkg['rejection_reason'] }}</strong></td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <th>Catatan / Tata Tertib</th>
                                                                    <td><small>{{ $bkg['notes'] }}</small></td>
                                                                </tr>
                                                            </table>

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Pemohon Fasilitas,</small><br><br><br>
                                                                    <strong>({{ $bkg['requester_name'] }})</strong>
                                                                </div>
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Mengetahui / Menyetujui,</small><br>
                                                                    <small>Wakabid Sarpras Alia,</small><br><br>
                                                                    <strong>({{ $bkg['approver_name'] ?: 'Koordinator Sarpras' }})</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Surat Izin</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Keputusan Approval --}}
                                            @if($bkg['status'] === 'diajukan')
                                                <div class="modal fade" id="modal-approve-bkg-{{ $bkg['id'] }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content text-left">
                                                            <form method="POST" action="{{ route('sarpras.peminjaman.approve', $bkg['id']) }}">
                                                                @csrf
                                                                <div class="modal-header bg-green">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Verifikasi & Keputusan Sarpras</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Verifikasi permohonan peminjaman ruangan <strong>{{ $bkg['room_name'] }}</strong> untuk keperluan <em>"{{ $bkg['purpose'] }}"</em> pada tanggal <strong>{{ date('d F Y', strtotime($bkg['booking_date'])) }}</strong>.</p>
                                                                    <div class="form-group">
                                                                        <label>Keputusan Persetujuan: <span class="text-danger">*</span></label>
                                                                        <select name="status" class="form-control" required id="select-status-{{ $bkg['id'] }}" onchange="toggleRejection(this.value, '{{ $bkg['id'] }}')">
                                                                            <option value="disetujui">DISETUJUI (Ruangan Tersedia & Siap Pakai)</option>
                                                                            <option value="ditolak">DITOLAK (Jadwal Bentrok / Perbaikan Sarpras)</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group" id="rejection-box-{{ $bkg['id'] }}" style="display: none;">
                                                                        <label>Alasan Penolakan / Catatan Reschedule: <span class="text-danger">*</span></label>
                                                                        <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Sebutkan alasan penolakan, contoh: ruangan sedang dipakai ujian atau perawatan AC..."></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Pejabat Pemeriksa / Penyetujui: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="approver_name" class="form-control" value="Dr. H. Zulkifli, Lc., M.A. (Wakabid Sarpras)" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Keputusan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-calendar-times-o fa-2x"></i><br>
                                            Tidak ada jadwal peminjaman ruangan yang cocok dengan kriteria filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Jadwal Pemakaian Ruangan (Timeline) --}}
                <div class="tab-pane {{ $activeTab === 'jadwal' ? 'active' : '' }}" id="tab_jadwal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Visualisasi linimasa penggunaan fasilitas kampus (Laboratorium, Aula, Masjid & Lapangan) untuk mencegah bentrok pemakaian.
                            </div>

                            <div class="box box-solid box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-clock-o text-primary"></i> Linimasa Agenda Pemakaian Pekan Ini</h3>
                                </div>
                                <div class="box-body">
                                    <ul class="timeline">
                                        @php
                                            $sortedBookings = $bookings;
                                            usort($sortedBookings, fn($a, $b) => strcmp($a['booking_date'] . ' ' . $a['start_time'], $b['booking_date'] . ' ' . $b['start_time']));
                                            $currentDate = '';
                                        @endphp

                                        @foreach($sortedBookings as $sb)
                                            @if($sb['booking_date'] !== $currentDate)
                                                @php $currentDate = $sb['booking_date']; @endphp
                                                <li class="time-label">
                                                    <span class="bg-navy">
                                                        <i class="fa fa-calendar"></i> {{ date('l, d F Y', strtotime($currentDate)) }}
                                                    </span>
                                                </li>
                                            @endif

                                            <li>
                                                @if($sb['status'] === 'disetujui')
                                                    <i class="fa fa-check bg-green"></i>
                                                @elseif($sb['status'] === 'diajukan')
                                                    <i class="fa fa-clock-o bg-yellow"></i>
                                                @elseif($sb['status'] === 'ditolak')
                                                    <i class="fa fa-times bg-red"></i>
                                                @else
                                                    <i class="fa fa-flag bg-gray"></i>
                                                @endif

                                                <div class="timeline-item">
                                                    <span class="time"><i class="fa fa-clock-o"></i> {{ $sb['start_time'] }} - {{ $sb['end_time'] }} WIB</span>
                                                    <h3 class="timeline-header">
                                                        <a href="#">{{ $sb['room_name'] }}</a> &mdash; <strong>{{ $sb['purpose'] }}</strong>
                                                    </h3>
                                                    <div class="timeline-body">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <small><i class="fa fa-user"></i> Pemohon: <strong>{{ $sb['requester_name'] }}</strong> ({{ $sb['requester_role'] }})</small><br>
                                                                <small><i class="fa fa-users"></i> Estimasi Peserta: {{ $sb['attendees_count'] }} orang</small>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <small><i class="fa fa-wrench"></i> Kebutuhan: {{ $sb['equipment_needed'] }}</small><br>
                                                                <small><i class="fa fa-map-marker text-red"></i> Lokasi: {{ $sb['room_location'] }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-footer">
                                                        @if($sb['status'] === 'disetujui')
                                                            <span class="label label-success">Terkonfirmasi</span>
                                                        @elseif($sb['status'] === 'diajukan')
                                                            <span class="label label-warning">Menunggu Konfirmasi Sarpras</span>
                                                        @elseif($sb['status'] === 'ditolak')
                                                            <span class="label label-danger">Ditolak: {{ $sb['rejection_reason'] }}</span>
                                                        @else
                                                            <span class="label label-default">Selesai</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        <li>
                                            <i class="fa fa-clock-o bg-gray"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Antrean Persetujuan Sarpras --}}
                <div class="tab-pane {{ $activeTab === 'persetujuan' ? 'active' : '' }}" id="tab_persetujuan">
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-inbox text-yellow"></i> Permohonan Masuk Menunggu Persetujuan Sarpras</h4>
                            <p class="text-muted">Daftar kegiatan guru/unit yang memerlukan izin pemakaian sarana prasarana sekolah.</p>

                            @php
                                $pendingList = array_filter($bookings, fn($b) => ($b['status'] ?? '') === 'diajukan');
                            @endphp

                            @if(count($pendingList) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Kode & Waktu</th>
                                                <th>Fasilitas Diminta</th>
                                                <th>Agenda & Tujuan</th>
                                                <th>Pemohon / Unit</th>
                                                <th>Logistik / Alat</th>
                                                <th class="text-center" width="160">Aksi Verifikasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingList as $p)
                                                <tr>
                                                    <td>
                                                        <span class="label label-default" style="font-family: monospace;">{{ $p['booking_code'] }}</span><br>
                                                        <small><i class="fa fa-calendar"></i> {{ date('d M Y', strtotime($p['booking_date'])) }}</small><br>
                                                        <small class="text-bold text-navy"><i class="fa fa-clock-o"></i> {{ $p['start_time'] }} - {{ $p['end_time'] }}</small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-purple">{{ $p['room_name'] }}</strong><br>
                                                        <small class="text-muted">{{ $p['room_location'] }}</small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-primary">{{ $p['purpose'] }}</strong><br>
                                                        <small><i class="fa fa-users"></i> {{ $p['attendees_count'] }} santri/peserta</small>
                                                    </td>
                                                    <td>
                                                        {{ $p['requester_name'] }}<br>
                                                        <span class="label label-info">{{ $p['unit_id'] }}</span>
                                                    </td>
                                                    <td><small>{{ $p['equipment_needed'] }}</small></td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-approve-bkg-{{ $p['id'] }}">
                                                                <i class="fa fa-check"></i> Proses Izin
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> Tidak ada permohonan peminjaman ruangan yang tertunda. Semua permohonan telah diproses!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ajukan Peminjaman Ruangan Baru --}}
<div class="modal fade" id="modal-ajukan-peminjaman" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('sarpras.peminjaman.store') }}">
                @csrf
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Pengajuan Peminjaman Ruangan & Fasilitas Kampus</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Ruangan / Sarana Fasilitas: <span class="text-danger">*</span></label>
                                <select name="room_id" class="form-control select2" style="width: 100%;" required>
                                    @foreach($bookableVenues as $bv)
                                        <option value="{{ $bv['id'] }}">{{ $bv['name'] }} (Kapasitas: {{ $bv['capacity'] }} orang)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Pemohon (Guru/Pegawai/Panitia): <span class="text-danger">*</span></label>
                                <input type="text" name="requester_name" class="form-control" placeholder="Contoh: Ust. Ahmad Dahlan, S.Pd." required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan / Peran Pemohon: <span class="text-danger">*</span></label>
                                <input type="text" name="requester_role" class="form-control" placeholder="Contoh: Pembina Robotika SMA" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Penyelenggara: <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                    @endforeach
                                    <option value="YAYASAN">Yayasan / Pesantren Terpadu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Estimasi Jumlah Peserta / Santri: <span class="text-danger">*</span></label>
                                <input type="number" name="attendees_count" class="form-control" min="1" value="30" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pemakaian Ruangan: <span class="text-danger">*</span></label>
                                <input type="date" name="booking_date" class="form-control" value="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Jam Mulai: <span class="text-danger">*</span></label>
                                        <input type="time" name="start_time" class="form-control" value="08:00" required>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Jam Selesai: <span class="text-danger">*</span></label>
                                        <input type="time" name="end_time" class="form-control" value="11:30" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Agenda / Keperluan Acara: <span class="text-danger">*</span></label>
                                <input type="text" name="purpose" class="form-control" placeholder="Contoh: Workshop Pemrograman Python Santri SMA" required>
                            </div>
                            <div class="form-group">
                                <label>Perlengkapan / Fasilitas Tambahan Yang Diperlukan:</label>
                                <input type="text" name="equipment_needed" class="form-control" placeholder="Contoh: Proyektor HD, Sound System, 2 Mic Wireless, Kabel Roll">
                            </div>
                            <div class="form-group">
                                <label>Catatan Tambahan Kepada Petugas Sarpras:</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan khusus, request AC dihidupkan 30 menit sebelum acara, dll..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim Permohonan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRejection(val, id) {
        var box = document.getElementById('rejection-box-' + id);
        if (box) {
            box.style.display = (val === 'ditolak') ? 'block' : 'none';
        }
    }
</script>
@endpush
@endsection

