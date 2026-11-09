@extends('layouts.app')

@section('title', 'Katering & Dapur Asrama')

@section('content_header')
    <h1>
        <i class="fa fa-cutlery text-purple"></i> Manajemen Katering & Dapur Asrama
        <small>Menu Makan Harian Santri, Standar Gizi, Variasi Pagi/Siang/Malam, & Alergen</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('asrama.kamar') }}">Asrama</a></li>
        <li class="active">Katering & Konsumsi</li>
    </ol>
@stop

@section('content')
<div class="row">
    <!-- Stat Box 1: Total Variasi Menu -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-list-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Jadwal Menu</span>
                <span class="info-box-number">{{ $totalVariations }} <small style="color:#eee; font-weight:normal;">Sesi Makan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">7 Hari Terjadwal (3 Sesi/Hari)</span>
            </div>
        </div>
    </div>

    <!-- Stat Box 2: Menu Sarapan Pagi -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-coffee"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menu Sarapan Pagi</span>
                <span class="info-box-number">{{ $totalBreakfast }} <small style="color:#eee; font-weight:normal;">Menu</small></span>
                <div class="progress"><div class="progress-bar" style="width: 80%"></div></div>
                <span class="progress-description">Pukul 06:30 - 07:15 WIB</span>
            </div>
        </div>
    </div>

    <!-- Stat Box 3: Menu Makan Siang -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-sun-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Makan Siang (Gizi Utama)</span>
                <span class="info-box-number">{{ $totalLunch }} <small style="color:#eee; font-weight:normal;">Menu</small></span>
                <div class="progress"><div class="progress-bar" style="width: 85%"></div></div>
                <span class="progress-description">Pukul 12:15 - 13:15 WIB</span>
            </div>
        </div>
    </div>

    <!-- Stat Box 4: Makan Malam & Kalori -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal">
            <span class="info-box-icon"><i class="fa fa-moon-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Makan Malam (Rata Kalori)</span>
                <span class="info-box-number">{{ $avgCalories }} <small style="color:#eee; font-weight:normal;">kkal/sesi</small></span>
                <div class="progress"><div class="progress-bar" style="width: 90%"></div></div>
                <span class="progress-description">Pukul 19:45 - 20:30 WIB</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert Messages --}}
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
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li><a href="#tab-daftar" data-toggle="tab"><i class="fa fa-th-list"></i> Semua Daftar Menu</a></li>
                <li class="active"><a href="#tab-mingguan" data-toggle="tab"><i class="fa fa-calendar-check-o"></i> Matriks Jadwal Mingguan</a></li>
                <li class="pull-left header">
                    <span style="font-size: 18px; font-weight: bold; color: #1a365d;">
                        <i class="fa fa-cutlery text-purple"></i> Jadwal Menu Makan Santri Pesantren Alia
                    </span>
                </li>
            </ul>

            <div class="tab-content" style="padding: 20px;">
                <!-- TAB 1: Matriks Jadwal Mingguan (Weekly Matrix) -->
                <div class="tab-pane active" id="tab-mingguan">
                    <div class="well well-sm" style="background-color: #f7fafc; border-left: 4px solid #6b46c1; margin-bottom: 20px;">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 style="margin-top: 5px; font-weight: bold; color: #2d3748;">
                                    <i class="fa fa-info-circle text-purple"></i> Standar Gizi & Katering Pesantren Modern Alia
                                </h4>
                                <p style="margin-bottom: 0; color: #4a5568; font-size: 13px;">
                                    Dapur Asrama Alia menyajikan menu sehat 4 Sehat 5 Sempurna bersertifikat Halal dengan rotasi mingguan yang diawasi oleh Ahli Gizi Pesantren. Seluruh santri dipastikan menerima asupan energi, protein, kalsium, dan vitamin yang seimbang untuk menunjang aktivitas Tahfidz Al-Qur'an dan Akademik.
                                </p>
                            </div>
                            <div class="col-sm-4 text-right" style="padding-top: 10px;">
                                <button type="button" class="btn btn-purple" data-toggle="modal" data-target="#modal-tambah-menu">
                                    <i class="fa fa-plus-circle"></i> Tambah Menu Makanan
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.print()">
                                    <i class="fa fa-print"></i> Cetak Jadwal
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr style="background-color: #1a365d; color: #ffffff;">
                                    <th style="width: 12%; vertical-align: middle;">HARI / SESI</th>
                                    <th style="width: 29%; vertical-align: middle;">
                                        <i class="fa fa-coffee"></i> SARAPAN PAGI<br>
                                        <small style="color: #cbd5e0; font-weight: normal;">06:30 - 07:15 WIB</small>
                                    </th>
                                    <th style="width: 29%; vertical-align: middle;">
                                        <i class="fa fa-sun-o"></i> MAKAN SIANG<br>
                                        <small style="color: #cbd5e0; font-weight: normal;">12:15 - 13:15 WIB</small>
                                    </th>
                                    <th style="width: 29%; vertical-align: middle;">
                                        <i class="fa fa-moon-o"></i> MAKAN MALAM<br>
                                        <small style="color: #cbd5e0; font-weight: normal;">19:45 - 20:30 WIB</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daysList as $d)
                                <tr>
                                    <td style="vertical-align: middle; background-color: #edf2f7; font-weight: bold; font-size: 15px; color: #1a365d;">
                                        {{ $d }}
                                    </td>

                                    {{-- Kolom Sarapan Pagi --}}
                                    @php $pagi = $mealsByDay[$d]['Pagi'] ?? null; @endphp
                                    <td style="text-align: left; vertical-align: top; background-color: #ffffff;">
                                        @if($pagi)
                                            <div style="border-left: 3px solid #6b46c1; padding-left: 10px; margin-bottom: 5px;">
                                                <strong style="color: #6b46c1; font-size: 14px;">{{ $pagi['menu_title'] }}</strong>
                                                <span class="label label-default pull-right">{{ $pagi['calories'] }} kkal</span>
                                            </div>
                                            <ul style="padding-left: 16px; margin-bottom: 6px; font-size: 12px; color: #4a5568;">
                                                <li><strong>Utama:</strong> {{ $pagi['main_dish'] }}</li>
                                                <li><strong>Pendamping:</strong> {{ $pagi['side_dish'] }}</li>
                                                <li><strong>Sayur:</strong> {{ $pagi['vegetable'] }}</li>
                                                <li><strong>Buah/Susu:</strong> {{ $pagi['fruit_drink'] }}</li>
                                            </ul>
                                            <div style="font-size: 11px; background-color: #faf5ff; padding: 4px 6px; border-radius: 4px; color: #553c9a; margin-bottom: 6px;">
                                                <i class="fa fa-heartbeat"></i> <strong>Nutrisi:</strong> {{ $pagi['nutrition_highlight'] }}
                                            </div>
                                            <div class="clearfix">
                                                <small class="text-muted"><i class="fa fa-user"></i> {{ $pagi['chef_in_charge'] }}</small>
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-xs btn-info" onclick='showMealDetail(@json($pagi))' title="Detail Menu">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('asrama.katering.destroy', $pagi['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus menu {{ $pagi['menu_title'] }}?')" title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div style="padding: 25px 0; color: #a0aec0; font-style: italic;">
                                                <i class="fa fa-cutlery"></i> Belum ada menu terjadwal
                                                <br>
                                                <button type="button" class="btn btn-xs btn-default" style="margin-top: 5px;" data-toggle="modal" data-target="#modal-tambah-menu" onclick="setPreset('{{ $d }}', 'Pagi')">
                                                    + Tambah
                                                </button>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Kolom Makan Siang --}}
                                    @php $siang = $mealsByDay[$d]['Siang'] ?? null; @endphp
                                    <td style="text-align: left; vertical-align: top; background-color: #ffffff;">
                                        @if($siang)
                                            <div style="border-left: 3px solid #2b6cb0; padding-left: 10px; margin-bottom: 5px;">
                                                <strong style="color: #2b6cb0; font-size: 14px;">{{ $siang['menu_title'] }}</strong>
                                                <span class="label label-info pull-right">{{ $siang['calories'] }} kkal</span>
                                            </div>
                                            <ul style="padding-left: 16px; margin-bottom: 6px; font-size: 12px; color: #4a5568;">
                                                <li><strong>Utama:</strong> {{ $siang['main_dish'] }}</li>
                                                <li><strong>Pendamping:</strong> {{ $siang['side_dish'] }}</li>
                                                <li><strong>Sayur:</strong> {{ $siang['vegetable'] }}</li>
                                                <li><strong>Buah/Susu:</strong> {{ $siang['fruit_drink'] }}</li>
                                            </ul>
                                            <div style="font-size: 11px; background-color: #ebf8ff; padding: 4px 6px; border-radius: 4px; color: #2c5282; margin-bottom: 6px;">
                                                <i class="fa fa-heartbeat"></i> <strong>Nutrisi:</strong> {{ $siang['nutrition_highlight'] }}
                                            </div>
                                            <div class="clearfix">
                                                <small class="text-muted"><i class="fa fa-user"></i> {{ $siang['chef_in_charge'] }}</small>
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-xs btn-info" onclick='showMealDetail(@json($siang))' title="Detail Menu">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('asrama.katering.destroy', $siang['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus menu {{ $siang['menu_title'] }}?')" title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div style="padding: 25px 0; color: #a0aec0; font-style: italic;">
                                                <i class="fa fa-cutlery"></i> Belum ada menu terjadwal
                                                <br>
                                                <button type="button" class="btn btn-xs btn-default" style="margin-top: 5px;" data-toggle="modal" data-target="#modal-tambah-menu" onclick="setPreset('{{ $d }}', 'Siang')">
                                                    + Tambah
                                                </button>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Kolom Makan Malam --}}
                                    @php $malam = $mealsByDay[$d]['Malam'] ?? null; @endphp
                                    <td style="text-align: left; vertical-align: top; background-color: #ffffff;">
                                        @if($malam)
                                            <div style="border-left: 3px solid #319795; padding-left: 10px; margin-bottom: 5px;">
                                                <strong style="color: #319795; font-size: 14px;">{{ $malam['menu_title'] }}</strong>
                                                <span class="label label-success pull-right">{{ $malam['calories'] }} kkal</span>
                                            </div>
                                            <ul style="padding-left: 16px; margin-bottom: 6px; font-size: 12px; color: #4a5568;">
                                                <li><strong>Utama:</strong> {{ $malam['main_dish'] }}</li>
                                                <li><strong>Pendamping:</strong> {{ $malam['side_dish'] }}</li>
                                                <li><strong>Sayur:</strong> {{ $malam['vegetable'] }}</li>
                                                <li><strong>Buah/Susu:</strong> {{ $malam['fruit_drink'] }}</li>
                                            </ul>
                                            <div style="font-size: 11px; background-color: #e6fffa; padding: 4px 6px; border-radius: 4px; color: #234e52; margin-bottom: 6px;">
                                                <i class="fa fa-heartbeat"></i> <strong>Nutrisi:</strong> {{ $malam['nutrition_highlight'] }}
                                            </div>
                                            <div class="clearfix">
                                                <small class="text-muted"><i class="fa fa-user"></i> {{ $malam['chef_in_charge'] }}</small>
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-xs btn-info" onclick='showMealDetail(@json($malam))' title="Detail Menu">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('asrama.katering.destroy', $malam['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus menu {{ $malam['menu_title'] }}?')" title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div style="padding: 25px 0; color: #a0aec0; font-style: italic;">
                                                <i class="fa fa-cutlery"></i> Belum ada menu terjadwal
                                                <br>
                                                <button type="button" class="btn btn-xs btn-default" style="margin-top: 5px;" data-toggle="modal" data-target="#modal-tambah-menu" onclick="setPreset('{{ $d }}', 'Malam')">
                                                    + Tambah
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: Semua Daftar Menu (Table List with Filter) -->
                <div class="tab-pane" id="tab-daftar">
                    <!-- Filter Box -->
                    <div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                        <div class="box-body">
                            <form action="{{ route('asrama.katering') }}" method="GET" class="form-inline">
                                <div class="form-group" style="margin-right: 15px;">
                                    <label><i class="fa fa-calendar"></i> Hari:</label>
                                    <select name="day" class="form-control input-sm">
                                        <option value="">-- Semua Hari --</option>
                                        @foreach($daysList as $d)
                                            <option value="{{ $d }}" {{ $dayFilter === $d ? 'selected' : '' }}>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 15px;">
                                    <label><i class="fa fa-clock-o"></i> Waktu Makan:</label>
                                    <select name="meal_time" class="form-control input-sm">
                                        <option value="">-- Semua Waktu --</option>
                                        <option value="Pagi" {{ $timeFilter === 'Pagi' ? 'selected' : '' }}>Pagi (Sarapan)</option>
                                        <option value="Siang" {{ $timeFilter === 'Siang' ? 'selected' : '' }}>Siang (Makan Siang)</option>
                                        <option value="Malam" {{ $timeFilter === 'Malam' ? 'selected' : '' }}>Malam (Makan Malam)</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-right: 15px;">
                                    <label><i class="fa fa-search"></i> Cari:</label>
                                    <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Nama menu, lauk, sayur, koki..." style="width: 200px;">
                                </div>

                                <button type="submit" class="btn btn-purple btn-sm">
                                    <i class="fa fa-filter"></i> Saring Data
                                </button>
                                <a href="{{ route('asrama.katering') }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #1a365d; color: #fff;">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Hari & Sesi</th>
                                    <th>Nama Menu Hidangan</th>
                                    <th>Komposisi Menu (Lauk / Sayur / Buah)</th>
                                    <th class="text-center">Estimasi Kalori</th>
                                    <th>Standar Nutrisi & Alergen</th>
                                    <th>Koki Penanggung Jawab</th>
                                    <th style="width: 100px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meals as $idx => $m)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="label label-primary">{{ $m['day'] }}</span><br>
                                        @if($m['meal_time'] === 'Pagi')
                                            <span class="label label-default" style="margin-top: 3px; display: inline-block;">Pagi ({{ $m['time_range'] }})</span>
                                        @elseif($m['meal_time'] === 'Siang')
                                            <span class="label label-info" style="margin-top: 3px; display: inline-block;">Siang ({{ $m['time_range'] }})</span>
                                        @else
                                            <span class="label label-success" style="margin-top: 3px; display: inline-block;">Malam ({{ $m['time_range'] }})</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong style="color: #1a365d; font-size: 14px;">{{ $m['menu_title'] }}</strong>
                                        <div style="font-size: 11px; color: #718096;">
                                            <i class="fa fa-check-circle text-green"></i> {{ $m['halal_status'] }} | Porsi: {{ $m['portion_count'] }} santri
                                        </div>
                                    </td>
                                    <td>
                                        <ul style="padding-left: 15px; margin-bottom: 0; font-size: 12px;">
                                            <li><strong>Lauk Utama:</strong> {{ $m['main_dish'] }}</li>
                                            <li><strong>Pendamping:</strong> {{ $m['side_dish'] }}</li>
                                            <li><strong>Sayur:</strong> {{ $m['vegetable'] }}</li>
                                            <li><strong>Buah / Minuman:</strong> {{ $m['fruit_drink'] }}</li>
                                        </ul>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-purple" style="font-size: 13px;">{{ $m['calories'] }} kkal</span>
                                    </td>
                                    <td>
                                        <div style="font-size: 12px; color: #2d3748;">
                                            <i class="fa fa-heartbeat text-red"></i> {{ $m['nutrition_highlight'] }}
                                        </div>
                                        <div style="font-size: 11px; color: #c53030; margin-top: 3px;">
                                            <i class="fa fa-exclamation-triangle"></i> <strong>Alergen:</strong> {{ $m['allergy_notes'] }}
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <i class="fa fa-user-circle text-muted"></i> <strong>{{ $m['chef_in_charge'] }}</strong>
                                        <br><small class="text-muted">Divisi Dapur Asrama</small>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <button type="button" class="btn btn-xs btn-info" onclick='showMealDetail(@json($m))' title="Lihat Rincian">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a href="{{ route('asrama.katering.destroy', $m['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus menu {{ $m['menu_title'] }}?')" title="Hapus Menu">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                                        <i class="fa fa-cutlery fa-2x"></i><br>
                                        Belum ada data hidangan katering yang sesuai dengan filter pencarian.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Tambah Menu Katering Makanan Baru -->
<div class="modal fade" id="modal-tambah-menu" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('asrama.katering.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Menu Makanan Katering Asrama</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Hari Penyajian <span class="text-red">*</span></label>
                                <select name="day" id="input_day" class="form-control" required>
                                    @foreach($daysList as $d)
                                        <option value="{{ $d }}">{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sesi Waktu Makan <span class="text-red">*</span></label>
                                <select name="meal_time" id="input_meal_time" class="form-control" required onchange="updateDefaultTimeRange()">
                                    <option value="Pagi">Pagi (Sarapan)</option>
                                    <option value="Siang">Siang (Makan Siang)</option>
                                    <option value="Malam">Malam (Makan Malam)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Rentang Waktu Pelayanan</label>
                                <input type="text" name="time_range" id="input_time_range" class="form-control" value="06:30 - 07:15 WIB" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Hidangan / Tema Menu <span class="text-red">*</span></label>
                                <input type="text" name="menu_title" class="form-control" placeholder="Contoh: Paket Ayam Bakar Madu & Sayur Asam Segar" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estimasi Kalori (kkal) <span class="text-red">*</span></label>
                                <input type="number" name="calories" class="form-control" value="650" min="100" max="2000" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lauk Utama (Protein Hewani/Nabati) <span class="text-red">*</span></label>
                                <input type="text" name="main_dish" class="form-control" placeholder="Contoh: Ayam Bakar Bumbu Madu / Rendang Daging Sapi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lauk Pendamping <span class="text-red">*</span></label>
                                <input type="text" name="side_dish" class="form-control" placeholder="Contoh: Tahu & Tempe Bacem Goreng / Bakwan Sayur" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Menu Sayur / Sup <span class="text-red">*</span></label>
                                <input type="text" name="vegetable" class="form-control" placeholder="Contoh: Sayur Asem Daun Melinjo & Jagung Manis" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Buah / Minuman Pendukung <span class="text-red">*</span></label>
                                <input type="text" name="fruit_drink" class="form-control" placeholder="Contoh: Buah Semangka Segar & Teh Hangat Manis" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Highlight Nutrisi Gizi <span class="text-red">*</span></label>
                                <input type="text" name="nutrition_highlight" class="form-control" value="Kaya Protein Hewani, Vitamin A & C, Serat Tinggi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Catatan Alergi / Porsi Khusus</label>
                                <input type="text" name="allergy_notes" class="form-control" placeholder="Contoh: Mengandung kedelai. Opsi santri alergi seafood aman.">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Koki Penanggung Jawab <span class="text-red">*</span></label>
                                <input type="text" name="chef_in_charge" class="form-control" value="Chef Budi Handoko" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Porsi Santri <span class="text-red">*</span></label>
                                <input type="number" name="portion_count" class="form-control" value="450" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status Kehalalan Makanan</label>
                                <input type="text" name="halal_status" class="form-control" value="Tersertifikasi Halal LPPOM MUI" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-save"></i> Simpan ke Jadwal Dapur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Detail Rincian Menu Makanan -->
<div class="modal fade" id="modal-detail-menu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6b46c1; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-cutlery"></i> Rincian Standar Menu Katering Santri</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 35%; background-color: #f7fafc;">Nama Menu</th>
                        <td id="dt_title" style="font-weight: bold; color: #1a365d;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Hari & Sesi Waktu</th>
                        <td id="dt_day_session">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Lauk Utama</th>
                        <td id="dt_main">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Lauk Pendamping</th>
                        <td id="dt_side">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Sayuran / Sup</th>
                        <td id="dt_vegetable">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Buah / Minuman</th>
                        <td id="dt_fruit">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Kandungan Energi</th>
                        <td id="dt_calories" style="font-weight: bold; color: #b7791f;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Sorotan Gizi</th>
                        <td id="dt_nutrition" style="color: #2b6cb0;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Peringatan Alergi</th>
                        <td id="dt_allergy" style="color: #c53030;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Koki Pengawas</th>
                        <td id="dt_chef">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Kesiapan & Kehalalan</th>
                        <td id="dt_halal" style="color: #276749; font-weight: bold;">-</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function updateDefaultTimeRange() {
        var meal = document.getElementById('input_meal_time').value;
        var tr = document.getElementById('input_time_range');
        if (meal === 'Pagi') {
            tr.value = '06:30 - 07:15 WIB';
        } else if (meal === 'Siang') {
            tr.value = '12:15 - 13:15 WIB';
        } else if (meal === 'Malam') {
            tr.value = '19:45 - 20:30 WIB';
        }
    }

    function setPreset(day, time) {
        document.getElementById('input_day').value = day;
        document.getElementById('input_meal_time').value = time;
        updateDefaultTimeRange();
    }

    function showMealDetail(item) {
        document.getElementById('dt_title').innerText = item.menu_title;
        document.getElementById('dt_day_session').innerText = item.day + ' (' + item.meal_time + ' - ' + item.time_range + ')';
        document.getElementById('dt_main').innerText = item.main_dish;
        document.getElementById('dt_side').innerText = item.side_dish;
        document.getElementById('dt_vegetable').innerText = item.vegetable;
        document.getElementById('dt_fruit').innerText = item.fruit_drink;
        document.getElementById('dt_calories').innerText = item.calories + ' kkal';
        document.getElementById('dt_nutrition').innerText = item.nutrition_highlight;
        document.getElementById('dt_allergy').innerText = item.allergy_notes || 'Bebas alergen umum.';
        document.getElementById('dt_chef').innerText = item.chef_in_charge + ' (Porsi: ' + item.portion_count + ' santri)';
        document.getElementById('dt_halal').innerText = item.halal_status;

        $('#modal-detail-menu').modal('show');
    }
</script>
@stop
