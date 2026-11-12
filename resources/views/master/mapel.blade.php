@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page_title', 'Mata Pelajaran & Kurikulum')
@section('page_description', 'Kelola Mata Pelajaran Nasional, Kepesantrenan/Diniyah, Muatan Lokal, dan Standar KKM')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Mata Pelajaran</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count($subjects) }}</h3>
          <p>Total Mata Pelajaran</p>
        </div>
        <div class="icon"><i class="fa fa-book"></i></div>
        <a href="#table-mapel" class="small-box-footer">Lihat Mapel <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($subjects, fn($s) => ($s['group'] ?? '') === 'Kepesantrenan')) }}</h3>
          <p>Mapel Diniyah / Pesantren</p>
        </div>
        <div class="icon"><i class="fa fa-moon-o"></i></div>
        <a href="{{ route('master.mapel', ['group' => 'Kepesantrenan']) }}" class="small-box-footer">Filter Diniyah <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($subjects, fn($s) => ($s['group'] ?? '') === 'Nasional')) }}</h3>
          <p>Kurikulum Nasional</p>
        </div>
        <div class="icon"><i class="fa fa-flag"></i></div>
        <a href="{{ route('master.mapel', ['group' => 'Nasional']) }}" class="small-box-footer">Filter Nasional <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          @php
            $avgKkm = count($subjects) > 0 ? round(array_sum(array_column($subjects, 'kkm')) / count($subjects), 1) : 0;
          @endphp
          <h3>{{ $avgKkm }}</h3>
          <p>Rata-rata Standar KKM</p>
        </div>
        <div class="icon"><i class="fa fa-check-square-o"></i></div>
        <a href="{{ route('akademik.nilai') }}" class="small-box-footer">Input Nilai <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-mapel">
    <div class="col-md-12">
      <x-card title="Daftar Mata Pelajaran Alia Islamic School" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('master.mapel') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 240px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / singkatan mapel...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Kelompok Kurikulum -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="group" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $groupFilter == 'all' ? 'selected' : '' }}>Semua Kelompok Kurikulum</option>
                  <option value="Nasional" {{ $groupFilter == 'Nasional' ? 'selected' : '' }}>Kurikulum Nasional</option>
                  <option value="Kepesantrenan" {{ $groupFilter == 'Kepesantrenan' ? 'selected' : '' }}>Kepesantrenan / Diniyah</option>
                  <option value="Muatan Lokal" {{ $groupFilter == 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal & Koding</option>
                </select>
              </div>

              @if($search || $groupFilter !== 'all')
                <a href="{{ route('master.mapel') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-mapel">
              <i class="fa fa-plus-circle"></i> Tambah Mata Pelajaran
            </button>
          </div>
        </div>

        @if(count($subjects) === 0)
          <x-empty-state 
            title="Tidak Ada Mata Pelajaran"
            message="Tidak ada mata pelajaran yang cocok dengan filter pencarian Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('master.mapel') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>Kode Mapel</th>
                  <th>Nama Mata Pelajaran</th>
                  <th>Singkatan</th>
                  <th>Kelompok Kurikulum</th>
                  <th class="text-center">Alokasi Jam / Pekan</th>
                  <th class="text-center">Standar KKM</th>
                  <th>Unit Sasaran</th>
                  <th class="text-center">Status</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($subjects as $s)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $s['id'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $s['name'] }}</strong>
                    </td>
                    <td><b>{{ $s['short_name'] }}</b></td>
                    <td>
                      @if(($s['group'] ?? '') === 'Kepesantrenan')
                        <span class="label label-primary" style="background:#6b46c1;"><i class="fa fa-moon-o"></i> Kepesantrenan</span>
                      @elseif(($s['group'] ?? '') === 'Nasional')
                        <span class="label label-success"><i class="fa fa-flag"></i> Nasional</span>
                      @else
                        <span class="label label-warning"><i class="fa fa-puzzle-piece"></i> Muatan Lokal</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="badge bg-navy" style="background:#1a365d !important; font-size: 12px;">{{ $s['hours_per_week'] }} JP</span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-green" style="font-size: 13px; font-weight:bold;">{{ $s['kkm'] }}</span>
                    </td>
                    <td><small class="text-muted">{{ $s['unit_target'] }}</small></td>
                    <td class="text-center">
                      <x-badge :status="$s['status'] ?? 'aktif'" />
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Detail Mapel" data-toggle="modal" data-target="#modal-detail-mp-{{ $s['id'] }}">
                        <i class="fa fa-eye"></i>
                      </button>
                      <button class="btn btn-xs btn-warning btn-flat" title="Edit Mapel" onclick="alert('Simulasi form edit mapel {{ $s['name'] }}')">
                        <i class="fa fa-pencil"></i>
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Mapel" onclick="confirmAction('{{ route('master.mapel.destroy', ['id' => $s['id']]) }}', 'Apakah Anda yakin ingin menghapus mapel {{ $s['name'] }} dari sesi demo?')">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </x-card>
    </div>
  </div>

  <!-- Detail Mapel Modals (Moved outside table) -->
  @foreach($subjects as $s)
    <div class="modal fade" id="modal-detail-mp-{{ $s['id'] }}" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-book"></i> Detail Mata Pelajaran: {{ $s['name'] }}</h4>
          </div>
          <div class="modal-body">
            <table class="table table-bordered table-striped">
              <tr>
                <th style="width: 35%;">Kode Mapel</th>
                <td><code>{{ $s['id'] }}</code></td>
              </tr>
              <tr>
                <th>Nama Mata Pelajaran</th>
                <td><strong>{{ $s['name'] }}</strong> ({{ $s['short_name'] }})</td>
              </tr>
              <tr>
                <th>Kelompok Kurikulum</th>
                <td><span class="label label-info">{{ $s['group'] }}</span></td>
              </tr>
              <tr>
                <th>Alokasi Jam Mengajar</th>
                <td><b>{{ $s['hours_per_week'] }}</b> Jam Pelajaran (JP) per Pekan</td>
              </tr>
              <tr>
                <th>Kriteria Ketuntasan Minimal (KKM)</th>
                <td><span class="badge bg-green">{{ $s['kkm'] }}</span></td>
              </tr>
              <tr>
                <th>Sasaran Jenjang Unit</th>
                <td>{{ $s['unit_target'] }}</td>
              </tr>
              <tr>
                <th>Status Mata Pelajaran</th>
                <td><x-badge :status="$s['status'] ?? 'aktif'" /></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <a href="{{ route('akademik.jadwal') }}" class="btn btn-info btn-flat pull-left"><i class="fa fa-calendar"></i> Lihat di Jadwal Pelajaran</a>
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <!-- Modal Tambah Mata Pelajaran Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-mapel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Mata Pelajaran Baru</h4>
        </div>
        <form action="{{ route('master.mapel.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Nama Mata Pelajaran <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Contoh: Ushul Fikih & Qawaid Fiqhiyyah" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Singkatan / Alias <span class="text-danger">*</span></label>
                  <input type="text" name="short_name" class="form-control" placeholder="Contoh: Ushul Fikih" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kelompok Kurikulum <span class="text-danger">*</span></label>
                  <select name="group" class="form-control" required>
                    <option value="Nasional">Kurikulum Nasional</option>
                    <option value="Kepesantrenan" selected>Kepesantrenan / Diniyah</option>
                    <option value="Muatan Lokal">Muatan Lokal & Keterampilan</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sasaran Unit Pendidikan <span class="text-danger">*</span></label>
                  <input type="text" name="unit_target" class="form-control" value="Semua Unit" placeholder="Contoh: SMP, SMA" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kriteria Ketuntasan Minimal (KKM) <span class="text-danger">*</span></label>
                  <input type="number" name="kkm" class="form-control" value="75" min="50" max="100" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Alokasi Jam / Pekan (JP) <span class="text-danger">*</span></label>
                  <input type="number" name="hours_per_week" class="form-control" value="3" min="1" max="12" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Mapel Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
