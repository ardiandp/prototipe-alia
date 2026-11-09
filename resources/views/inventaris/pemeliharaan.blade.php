@extends('layouts.app')

@section('title', 'Pemeliharaan & Servis Alat Inventaris')

@section('content_header')
    <h1>
        <i class="fa fa-wrench text-warning"></i> Pemeliharaan & Servis Alat Inventaris
        <small>Pencatatan Servis Alat Praktikum/Elektronik, Kalibrasi Sensor, Biaya & Riwayat Pemeliharaan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li>Inventaris & Logistik</li>
        <li class="active">Pemeliharaan Alat</li>
    </ol>
@stop

@section('content')
<div class="row">
    {{-- Info Boxes --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-wrench"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Tindakan Servis</span>
                <span class="info-box-number">{{ $totalServices }} Kasus</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Log perawatan alat terdaftar</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dalam Pengerjaan</span>
                <span class="info-box-number">{{ $inServiceCount }} Alat</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalServices > 0 ? round(($inServiceCount / $totalServices) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Sedang di bengkel / lab</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai & Prima</span>
                <span class="info-box-number">{{ $completedCount }} Alat</span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalServices > 0 ? round(($completedCount / $totalServices) * 100) : 0 }}%"></div></div>
                <span class="progress-description">Kondisi baik siap pakai</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Biaya Servis</span>
                <span class="info-box-number" style="font-size: 15px;">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Realisasi belanja pemeliharaan</span>
            </div>
        </div>
    </div>
</div>

{{-- Feedback Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Sukses Berhasil!</h4>
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
                    <a href="#tab_daftar" data-toggle="tab"><i class="fa fa-list text-primary"></i> Buku Riwayat Pemeliharaan Alat</a>
                </li>
                <li class="{{ $activeTab === 'jadwal_kalibrasi' ? 'active' : '' }}">
                    <a href="#tab_jadwal_kalibrasi" data-toggle="tab"><i class="fa fa-calendar-check-o text-green"></i> Jadwal Kalibrasi & Servis Berkala</a>
                </li>
                <li class="{{ $activeTab === 'rekap_biaya' ? 'active' : '' }}">
                    <a href="#tab_rekap_biaya" data-toggle="tab"><i class="fa fa-pie-chart text-purple"></i> Rekapitulasi Efisiensi Biaya Perbaikan</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: Buku Riwayat Pemeliharaan Alat --}}
                <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
                    {{-- Filter & Action Bar --}}
                    <div class="box box-solid box-default" style="margin-bottom: 15px;">
                        <div class="box-body">
                            <form method="GET" action="{{ route('inventaris.pemeliharaan') }}" class="form-inline">
                                <input type="hidden" name="tab" value="daftar">

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Status Servis:</label>
                                    <select name="status" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                        <option value="dalam_servis" {{ $statusFilter === 'dalam_servis' ? 'selected' : '' }}>Sedang Diservis</option>
                                        <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai Diperbaiki</option>
                                        <option value="afkir" {{ $statusFilter === 'afkir' ? 'selected' : '' }}>Afkir / Rusak Permanen</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Kondisi Pasca-Servis:</label>
                                    <select name="condition" class="form-control input-sm" onchange="this.form.submit()">
                                        <option value="all" {{ $conditionFilter === 'all' ? 'selected' : '' }}>-- Semua Kondisi --</option>
                                        <option value="baik" {{ $conditionFilter === 'baik' ? 'selected' : '' }}>Baik (Normal)</option>
                                        <option value="rusak_ringan" {{ $conditionFilter === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak_berat" {{ $conditionFilter === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="margin-right: 5px;">Cari:</label>
                                    <input type="text" name="search" class="form-control input-sm" placeholder="Nama alat, kode, teknisi..." value="{{ $search }}" style="width: 170px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring</button>
                                <a href="{{ route('inventaris.pemeliharaan', ['tab' => 'daftar']) }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-tambah-servis">
                                        <i class="fa fa-plus-circle"></i> Catat Servis / Kalibrasi
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                                        <i class="fa fa-print"></i> Cetak Log Servis
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Riwayat Pemeliharaan --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light text-center">
                                    <th width="40">No</th>
                                    <th>Kode Servis</th>
                                    <th>Nama Alat & Serial No</th>
                                    <th>Tanggal Servis</th>
                                    <th>Gejala / Masalah</th>
                                    <th>Tindakan / Suku Cadang</th>
                                    <th>Teknisi / Bengkel</th>
                                    <th class="text-right">Biaya (Rp)</th>
                                    <th class="text-center">Status</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filteredMaintenances as $imn)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="label label-default" style="font-family: monospace;">{{ $imn['service_code'] }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $imn['item_name'] }}</strong><br>
                                            <small class="text-muted"><i class="fa fa-barcode"></i> SN: {{ $imn['serial_number'] }}</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar text-blue"></i> {{ date('d M Y', strtotime($imn['service_date'])) }}<br>
                                            <small class="text-muted">Next: {{ date('M Y', strtotime($imn['next_maintenance_date'])) }}</small>
                                        </td>
                                        <td>
                                            <span class="text-bold text-danger">{{ $imn['issue_description'] }}</span><br>
                                            <small class="text-muted">Awal: {{ strtoupper($imn['condition_before']) }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $imn['action_taken'] }}</small>
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle text-muted"></i> <strong>{{ $imn['technician_name'] }}</strong><br>
                                            <small class="text-muted">{{ $imn['vendor_service'] }}</small>
                                        </td>
                                        <td class="text-right text-bold text-navy">
                                            Rp {{ number_format($imn['cost'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($imn['status'] === 'selesai')
                                                <span class="label label-success"><i class="fa fa-check"></i> Selesai ({{ strtoupper($imn['condition_after']) }})</span>
                                            @elseif($imn['status'] === 'dalam_servis')
                                                <span class="label label-warning"><i class="fa fa-cogs"></i> Dalam Servis</span>
                                            @else
                                                <span class="label label-danger">Afkir</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-detail-imn-{{ $imn['id'] }}" title="Lihat Lembar Servis & Kartu Alat">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </button>
                                                @if($imn['status'] === 'dalam_servis')
                                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-update-imn-{{ $imn['id'] }}" title="Update Selesai Servis">
                                                        <i class="fa fa-check text-green"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('inventaris.pemeliharaan.destroy', $imn['id']) }}" class="btn btn-default btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan servis alat ini dari sesi demo?')" title="Hapus">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>

                                            {{-- Modal Lembar Kerja Servis & Kalibrasi Berkop Alia --}}
                                            <div class="modal fade" id="modal-detail-imn-{{ $imn['id'] }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-warning">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title" style="color: #333;"><i class="fa fa-wrench"></i> Lembar Catatan Servis & Kalibrasi Alat</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                                                                <h4 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA INSAN MULIA</h4>
                                                                <h5 style="margin: 2px 0; font-weight: bold;">ALIA ISLAMIC SCHOOL JAKARTA</h5>
                                                                <small class="text-muted">BAGIAN PEMELIHARAAN INVENTARIS & ALAT LABORATORIUM</small><br>
                                                                <small class="text-muted">Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur</small>
                                                            </div>

                                                            <div class="text-center" style="margin-bottom: 15px;">
                                                                <h5 style="text-decoration: underline; font-weight: bold; margin-bottom: 3px;">BUKTI TINDAKAN SERVIS & KALIBRASI INVENTARIS</h5>
                                                                <small>Nomor Tiket: <strong>{{ $imn['service_code'] }}</strong></small>
                                                            </div>

                                                            <table class="table table-bordered table-striped" style="font-size: 13px;">
                                                                <tr>
                                                                    <th width="35%">Nama Alat / Perangkat</th>
                                                                    <td><strong class="text-primary">{{ $imn['item_name'] }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nomor Seri (Serial Number)</th>
                                                                    <td><code>{{ $imn['serial_number'] }}</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tanggal Pengerjaan</th>
                                                                    <td>{{ date('d F Y', strtotime($imn['service_date'])) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kondisi Sebelum Diservis</th>
                                                                    <td><span class="label label-warning">{{ strtoupper($imn['condition_before']) }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Gejala / Masalah Kerusakan</th>
                                                                    <td class="text-danger">{{ $imn['issue_description'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tindakan / Komponen Diganti</th>
                                                                    <td>{{ $imn['action_taken'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Teknisi / Bengkel Servis</th>
                                                                    <td>
                                                                        <strong>{{ $imn['technician_name'] }}</strong><br>
                                                                        <small class="text-muted">{{ $imn['vendor_service'] }}</small>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Biaya Servis / Kalibrasi</th>
                                                                    <td class="text-bold text-navy" style="font-size: 14px;">Rp {{ number_format($imn['cost'], 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kondisi Pasca-Servis</th>
                                                                    <td>
                                                                        @if($imn['condition_after'] === 'baik')
                                                                            <span class="label label-success"><i class="fa fa-check"></i> Baik (Normal Siap Pakai)</span>
                                                                        @elseif($imn['condition_after'] === 'rusak_ringan')
                                                                            <span class="label label-warning">Rusak Ringan</span>
                                                                        @else
                                                                            <span class="label label-danger">Rusak Berat / Afkir</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jadwal Servis Berkala Berikutnya</th>
                                                                    <td><i class="fa fa-calendar-check-o text-green"></i> <strong>{{ date('d F Y', strtotime($imn['next_maintenance_date'])) }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Catatan Hasil Pengujian</th>
                                                                    <td><small class="text-muted">{{ $imn['notes'] }}</small></td>
                                                                </tr>
                                                            </table>

                                                            <div class="row" style="margin-top: 25px;">
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Teknisi / Pelaksana Servis,</small><br><br><br>
                                                                    <strong>({{ $imn['technician_name'] }})</strong>
                                                                </div>
                                                                <div class="col-xs-6 text-center">
                                                                    <small>Petugas Logistik / Penanggung Jawab,</small><br><br><br>
                                                                    <strong>(H. Mulyadi)</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Lembar Servis</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Update Status Servis Selesai --}}
                                            @if($imn['status'] === 'dalam_servis')
                                                <div class="modal fade" id="modal-update-imn-{{ $imn['id'] }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content text-left">
                                                            <form method="POST" action="{{ route('inventaris.pemeliharaan.status', $imn['id']) }}">
                                                                @csrf
                                                                <div class="modal-header bg-green">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title"><i class="fa fa-check-circle"></i> Konfirmasi Penyelesaian Servis Alat</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Alat: <strong>{{ $imn['item_name'] }}</strong> (SN: {{ $imn['serial_number'] }})</p>

                                                                    <div class="form-group">
                                                                        <label>Status Penyelesaian: <span class="text-danger">*</span></label>
                                                                        <select name="status" class="form-control" required>
                                                                            <option value="selesai">SELESAI (Alat Siap Digunakan Kembali)</option>
                                                                            <option value="afkir">AFKIR (Tidak Dapat Diperbaiki / Dihapuskan)</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Kondisi Pasca-Servis: <span class="text-danger">*</span></label>
                                                                        <select name="condition_after" class="form-control" required>
                                                                            <option value="baik">Baik (Sangat Layak & Normal)</option>
                                                                            <option value="rusak_ringan">Rusak Ringan (Perlu Penanganan Tambahan)</option>
                                                                            <option value="rusak_berat">Rusak Berat (Tidak Berfungsi)</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Biaya Servis Akhir (Rp):</label>
                                                                        <input type="number" name="actual_cost" class="form-control" min="0" value="{{ $imn['cost'] }}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Catatan Pengujian Akhir:</label>
                                                                        <textarea name="notes" class="form-control" rows="2" placeholder="Uraikan hasil uji fungsi perangkat..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Status Penyelesaian</button>
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
                                        <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                            <i class="fa fa-wrench fa-2x"></i><br>
                                            Tidak ada riwayat pemeliharaan alat inventaris yang cocok dengan filter pencarian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Jadwal Kalibrasi & Servis Berkala --}}
                <div class="tab-pane {{ $activeTab === 'jadwal_kalibrasi' ? 'active' : '' }}" id="tab_jadwal_kalibrasi">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Jadwal pemeliharaan preventif (Preventive Maintenance) dan kalibrasi alat sensitif (laboratorium sains, multimedia proyektor, peralatan medis UKS) guna menjaga akurasi serta memperpanjang usia pakai aset sekolah.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="bg-gray-light">
                                            <th>Nama Alat Inventaris</th>
                                            <th>Serial Number</th>
                                            <th>Interval Pemeliharaan</th>
                                            <th>Servis Terakhir</th>
                                            <th>Jadwal Kalibrasi Berikutnya</th>
                                            <th class="text-center">Status Jadwal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($maintenances as $m)
                                            <tr>
                                                <td><strong class="text-primary">{{ $m['item_name'] }}</strong></td>
                                                <td><code>{{ $m['serial_number'] }}</code></td>
                                                <td>Setiap 6 Bulan</td>
                                                <td>{{ date('d M Y', strtotime($m['service_date'])) }}</td>
                                                <td><strong><i class="fa fa-calendar-check-o text-green"></i> {{ date('d F Y', strtotime($m['next_maintenance_date'])) }}</strong></td>
                                                <td class="text-center">
                                                    <span class="label label-success"><i class="fa fa-shield"></i> Terkalibrasi Aktif</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Rekapitulasi Efisiensi Biaya Perbaikan --}}
                <div class="tab-pane {{ $activeTab === 'rekap_biaya' ? 'active' : '' }}" id="tab_rekap_biaya">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-money text-purple"></i> Ringkasan Biaya Pemeliharaan Inventaris</h4>
                            <div class="box box-solid box-default">
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="bg-gray-light">
                                                <th>Nama Alat</th>
                                                <th>Bengkel / Teknisi</th>
                                                <th class="text-right">Biaya Servis (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($maintenances as $m)
                                                <tr>
                                                    <td>{{ $m['item_name'] }}</td>
                                                    <td>{{ $m['technician_name'] }}</td>
                                                    <td class="text-right text-bold">Rp {{ number_format($m['cost'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-gray-light text-bold">
                                                <td colspan="2">TOTAL BIAYA PEMELIHARAAN INVENTARIS</td>
                                                <td class="text-right text-navy">Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4><i class="fa fa-recycle text-green"></i> Manfaat Pemeliharaan Preventif Terjadwal</h4>
                            <div class="callout callout-success">
                                <h4>Efisiensi Anggaran Yayasan</h4>
                                <p>Dengan melakukan servis pembersihan berkala pada bohlam proyektor dan kalibrasi tensimeter/mikroskop, sekolah menghemat hingga <strong>70% biaya pengadaan unit baru</strong> serta meminimalkan downtime pembelajaran santri di kelas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Catat Servis / Kalibrasi Baru --}}
<div class="modal fade" id="modal-tambah-servis" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventaris.pemeliharaan.store') }}">
                @csrf
                <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="color: #333;"><i class="fa fa-plus-circle"></i> Catat Pemeliharaan / Kalibrasi Alat Inventaris</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Alat Yang Diservis: <span class="text-danger">*</span></label>
                                <select name="item_id" class="form-control" required>
                                    @foreach($items as $it)
                                        <option value="{{ $it['id'] }}">{{ $it['name'] }} ({{ $it['category_label'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nomor Seri Alat (Serial Number):</label>
                                <input type="text" name="serial_number" class="form-control" placeholder="Contoh: SN-882910-ID">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pemeliharaan / Servis: <span class="text-danger">*</span></label>
                                <input type="date" name="service_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Kondisi Sebelum Diservis: <span class="text-danger">*</span></label>
                                <select name="condition_before" class="form-control" required>
                                    <option value="rusak_ringan" selected>Rusak Ringan (Macet / Perlu Kalibrasi)</option>
                                    <option value="rusak_berat">Rusak Berat (Mati Total / Patah)</option>
                                    <option value="baik">Baik (Servis Preventif Rutin)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Gejala Masalah / Deskripsi Kerusakan: <span class="text-danger">*</span></label>
                                <textarea name="issue_description" class="form-control" rows="2" placeholder="Contoh: Bohlam proyektor redup dan filter debu tersumbat..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tindakan Perbaikan / Kalibrasi: <span class="text-danger">*</span></label>
                                <textarea name="action_taken" class="form-control" rows="2" placeholder="Contoh: Penggantian bohlam halogen OEM dan pembersihan lensa optik..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Nama Teknisi Pelaksana: <span class="text-danger">*</span></label>
                                <input type="text" name="technician_name" class="form-control" placeholder="Contoh: Dwi Wahyudi (Teknisi Multimedia)" required>
                            </div>
                            <div class="form-group">
                                <label>Bengkel / Vendor Servis Rekanan:</label>
                                <input type="text" name="vendor_service" class="form-control" placeholder="Contoh: Bengkel Logistik Internal Alia">
                            </div>
                            <div class="form-group">
                                <label>Biaya Servis / Kalibrasi (Rp): <span class="text-danger">*</span></label>
                                <input type="number" name="cost" class="form-control" min="0" value="0" required>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Kondisi Pasca-Servis: <span class="text-danger">*</span></label>
                                        <select name="condition_after" class="form-control" required>
                                            <option value="baik" selected>Baik (Optimal)</option>
                                            <option value="rusak_ringan">Rusak Ringan</option>
                                            <option value="rusak_berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Status Servis: <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="selesai" selected>Selesai</option>
                                            <option value="dalam_servis">Dalam Servis</option>
                                            <option value="afkir">Afkir</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jadwal Servis Berkala Berikutnya:</label>
                                <input type="date" name="next_maintenance_date" class="form-control" value="{{ date('Y-m-d', strtotime('+6 months')) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Catatan Tambahan:</label>
                                <input type="text" name="notes" class="form-control" placeholder="Contoh: Garansi servis 3 bulan...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Simpan Catatan Servis</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
