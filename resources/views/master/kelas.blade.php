@extends('layouts.app')

@section('title', 'Kelas & Rombongan Belajar')
@section('page_title', 'Kelas & Rombongan Belajar')
@section('page_description', 'Pengelolaan Rombel, Alokasi Wali Kelas, dan Kapasitas Santri')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Kelas & Rombel</li>
@endsection

@section('content')

  <!-- Summary Metric Strip -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count($classes) }}</h3>
          <p>Total Rombongan Belajar</p>
        </div>
        <div class="icon"><i class="fa fa-sitemap"></i></div>
        <a href="#table-kelas" class="small-box-footer">Lihat Rombel <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column($classes, 'student_count')) }}</h3>
          <p>Santri Dalam Rombel</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="{{ route('master.siswa') }}" class="small-box-footer">Buku Induk <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ array_sum(array_column($classes, 'capacity')) }}</h3>
          <p>Total Daya Tampung</p>
        </div>
        <div class="icon"><i class="fa fa-building-o"></i></div>
        <a href="{{ route('master.ruangan') }}" class="small-box-footer">Daftar Ruangan <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          @php
            $totalCap = array_sum(array_column($classes, 'capacity'));
            $totalStu = array_sum(array_column($classes, 'student_count'));
            $occupancy = $totalCap > 0 ? round(($totalStu / $totalCap) * 100, 1) : 0;
          @endphp
          <h3>{{ $occupancy }}%</h3>
          <p>Okupansi Kapasitas</p>
        </div>
        <div class="icon"><i class="fa fa-pie-chart"></i></div>
        <a href="#" class="small-box-footer">Rasio Rombel <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-kelas">
    <div class="col-md-12">
      <x-card title="Daftar Rombongan Belajar (Rombel) Alia" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('master.kelas') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 240px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama rombel/ruang...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Unit -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="unit" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Unit</option>
                  @foreach($units as $u)
                    <option value="{{ $u['id'] }}" {{ $unitFilter == $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Filter Tingkat -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="grade" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $gradeFilter == 'all' ? 'selected' : '' }}>Semua Tingkat</option>
                  <option value="1" {{ $gradeFilter == '1' ? 'selected' : '' }}>Kelas 1 SD</option>
                  <option value="2" {{ $gradeFilter == '2' ? 'selected' : '' }}>Kelas 2 SD</option>
                  <option value="7" {{ $gradeFilter == '7' ? 'selected' : '' }}>Kelas 7 SMP</option>
                  <option value="8" {{ $gradeFilter == '8' ? 'selected' : '' }}>Kelas 8 SMP</option>
                  <option value="10" {{ $gradeFilter == '10' ? 'selected' : '' }}>Kelas 10 SMA</option>
                  <option value="11" {{ $gradeFilter == '11' ? 'selected' : '' }}>Kelas 11 SMA</option>
                  <option value="12" {{ $gradeFilter == '12' ? 'selected' : '' }}>Kelas 12 SMA</option>
                </select>
              </div>

              @if($search || $unitFilter !== 'all' || $gradeFilter !== 'all')
                <a href="{{ route('master.kelas') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-kelas">
              <i class="fa fa-plus-circle"></i> Tambah Rombel Baru
            </button>
          </div>
        </div>

        @if(count($classes) === 0)
          <x-empty-state 
            title="Tidak Ada Rombongan Belajar"
            message="Tidak ada data kelas yang sesuai dengan filter pencarian Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('master.kelas') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>Kode Rombel</th>
                  <th>Nama Rombongan Belajar</th>
                  <th>Unit & Tingkat</th>
                  <th>Wali Kelas</th>
                  <th>Ruangan</th>
                  <th class="text-center" style="width: 140px;">Kapasitas Santri</th>
                  <th class="text-center">Status</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($classes as $c)
                  @php
                    $teacher = collect($teachers)->firstWhere('id', $c['homeroom_teacher_id'] ?? '');
                    $unitObj = collect($units)->firstWhere('id', $c['unit_id'] ?? '');
                    $pct = ($c['capacity'] > 0) ? round(($c['student_count'] / $c['capacity']) * 100) : 0;
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $c['id'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $c['name'] }}</strong>
                    </td>
                    <td>
                      <span class="label label-info">{{ $unitObj['name'] ?? $c['unit_id'] }}</span>
                      <span class="label label-default">Tk. {{ $c['grade'] ?? '-' }}</span>
                    </td>
                    <td>
                      <strong>{{ $teacher['name'] ?? 'Ust. Ahmad Dahlan' }}</strong><br>
                      <small class="text-muted"><i class="fa fa-id-badge"></i> {{ $teacher['subject'] ?? 'Pendidik' }}</small>
                    </td>
                    <td><i class="fa fa-building-o text-muted"></i> {{ $c['room'] ?? 'R. Belajar' }}</td>
                    <td>
                      <div class="clearfix">
                        <small class="pull-left"><b>{{ $c['student_count'] }}</b> / {{ $c['capacity'] }} Santri</small>
                        <small class="pull-right"><b>{{ $pct }}%</b></small>
                      </div>
                      <div class="progress xs" style="margin-top: 3px; height: 6px;">
                        <div class="progress-bar {{ $pct >= 90 ? 'progress-bar-red' : ($pct >= 70 ? 'progress-bar-yellow' : 'progress-bar-green') }}" style="width: {{ $pct }}%"></div>
                      </div>
                    </td>
                    <td class="text-center">
                      <x-badge :status="$c['status'] ?? 'aktif'" />
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Detail Rombel" data-toggle="modal" data-target="#modal-detail-cls-{{ $c['id'] }}">
                        <i class="fa fa-eye"></i>
                      </button>
                      <button class="btn btn-xs btn-warning btn-flat" title="Edit Rombel" onclick="alert('Simulasi form edit rombel {{ $c['name'] }}')">
                        <i class="fa fa-pencil"></i>
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Rombel" onclick="confirmAction('{{ route('master.kelas.destroy', ['id' => $c['id']]) }}', 'Apakah Anda yakin ingin menghapus rombel {{ $c['name'] }} dari sesi demo?')">
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

  <!-- Detail Rombel Modals (Moved outside table) -->
  @foreach($classes as $c)
    @php
      $teacher = collect($teachers)->firstWhere('id', $c['homeroom_teacher_id'] ?? '');
      $unitObj = collect($units)->firstWhere('id', $c['unit_id'] ?? '');
      $pct = ($c['capacity'] > 0) ? round(($c['student_count'] / $c['capacity']) * 100) : 0;
    @endphp
    <div class="modal fade" id="modal-detail-cls-{{ $c['id'] }}" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-sitemap"></i> Detail Rombel: {{ $c['name'] }}</h4>
          </div>
          <div class="modal-body">
            <table class="table table-bordered table-striped">
              <tr>
                <th style="width: 35%;">Kode Rombel</th>
                <td><code>{{ $c['id'] }}</code></td>
              </tr>
              <tr>
                <th>Unit Pendidikan</th>
                <td>{{ $unitObj['name'] ?? $c['unit_id'] }}</td>
              </tr>
              <tr>
                <th>Tingkat / Jenjang</th>
                <td>Tingkat {{ $c['grade'] ?? '-' }}</td>
              </tr>
              <tr>
                <th>Wali Kelas</th>
                <td><strong>{{ $teacher['name'] ?? 'Ust. Ahmad Dahlan' }}</strong></td>
              </tr>
              <tr>
                <th>Ruangan Belajar</th>
                <td>{{ $c['room'] ?? 'Ruang Belajar' }}</td>
              </tr>
              <tr>
                <th>Kapasitas & Okupansi</th>
                <td><b>{{ $c['student_count'] }}</b> santri terdaftar dari kuota maksimal <b>{{ $c['capacity'] }}</b> santri ({{ $pct }}%)</td>
              </tr>
              <tr>
                <th>Status Operasional</th>
                <td><x-badge :status="$c['status'] ?? 'aktif'" /></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <a href="{{ route('akademik.presensi') }}" class="btn btn-success btn-flat pull-left"><i class="fa fa-check-square"></i> Presensi Kelas</a>
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <!-- Modal Tambah Rombel Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-kelas" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Rombongan Belajar Baru</h4>
        </div>
        <form action="{{ route('master.kelas.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pilihan Unit Pendidikan <span class="text-danger">*</span></label>
                  <select name="unit_id" class="form-control" required>
                    @foreach($units as $u)
                      <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tingkat / Grade <span class="text-danger">*</span></label>
                  <select name="grade" class="form-control" required>
                    <option value="1">Kelas 1 SD</option>
                    <option value="2">Kelas 2 SD</option>
                    <option value="7">Kelas 7 SMP</option>
                    <option value="8">Kelas 8 SMP</option>
                    <option value="10" selected>Kelas 10 SMA</option>
                    <option value="11">Kelas 11 SMA</option>
                    <option value="12">Kelas 12 SMA</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Nama Rombongan Belajar <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Kelas X MIPA 3 (Boarding)" required>
            </div>

            <div class="form-group">
              <label>Alokasi Wali Kelas <span class="text-danger">*</span></label>
              <select name="homeroom_teacher_id" class="form-control select2" style="width: 100%;" required>
                @foreach($teachers as $t)
                  <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['subject'] }})</option>
                @endforeach
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kapasitas Santri <span class="text-danger">*</span></label>
                  <input type="number" name="capacity" class="form-control" value="30" min="10" max="50" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Lokasi Ruangan</label>
                  <input type="text" name="room" class="form-control" placeholder="Contoh: R. 204 Gedung Umar">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Rombel Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
