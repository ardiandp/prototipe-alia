@extends('layouts.app')

@section('title', 'Tahun Ajaran & Semester')
@section('page_title', 'Tahun Ajaran & Semester')
@section('page_description', 'Kelola Periode Akademik dan Penentuan Semester Aktif Sekolah')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Tahun Ajaran</li>
@endsection

@section('content')

  <!-- Active Academic Year Banner -->
  @php
    $currentActive = collect($academicYears)->firstWhere('is_current', true) ?? ($academicYears[0] ?? null);
  @endphp
  @if($currentActive)
    <div class="callout callout-info" style="border-left-color: #6b46c1; background:#fbfaff;">
      <div class="row">
        <div class="col-md-8">
          <h4 style="color:#44337a; font-weight:700;">
            <i class="fa fa-calendar-check-o text-purple"></i> Periode Akademik Aktif Saat Ini: {{ $currentActive['name'] }}
          </h4>
          <p style="color:#555;">
            Tanggal Operasional: <b>{{ date('d M Y', strtotime($currentActive['start_date'])) }}</b> s/d <b>{{ date('d M Y', strtotime($currentActive['end_date'])) }}</b>
            &bull; Seluruh pencatatan absensi, input nilai, jadwal, dan tagihan santri saat ini mengacu pada periode ini.
          </p>
        </div>
        <div class="col-md-4 text-right" style="padding-top: 10px;">
          <span class="label label-success" style="font-size: 14px; padding: 6px 14px;">
            <i class="fa fa-check-circle"></i> SEDANG AKTIF
          </span>
        </div>
      </div>
    </div>
  @endif

  <!-- Main Card with Toolbar & Table -->
  <div class="row">
    <div class="col-md-12">
      <x-card title="Daftar Periode Tahun Ajaran & Semester" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-7 col-sm-12">
            <form action="{{ route('master.tahun-ajaran') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari tahun ajaran (contoh: 2026)...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <div class="form-group" style="margin-left: 10px;">
                <select name="semester" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $semesterFilter == 'all' ? 'selected' : '' }}>Semua Semester</option>
                  <option value="Ganjil" {{ $semesterFilter == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                  <option value="Genap" {{ $semesterFilter == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                </select>
              </div>

              @if($search || $semesterFilter !== 'all')
                <a href="{{ route('master.tahun-ajaran') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-5 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-ta">
              <i class="fa fa-plus-circle"></i> Tambah Periode Akademik
            </button>
          </div>
        </div>

        @if(count($academicYears) === 0)
          <x-empty-state 
            title="Tidak Ada Periode Akademik"
            message="Tidak ada data tahun ajaran & semester yang sesuai dengan filter pencarian Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('master.tahun-ajaran') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>ID Periode</th>
                  <th>Tahun Ajaran & Semester</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Selesai</th>
                  <th>Keterangan</th>
                  <th class="text-center">Status Periode</th>
                  <th style="width: 170px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($academicYears as $ay)
                  <tr class="{{ $ay['is_current'] ? 'success' : '' }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $ay['id'] }}</code></td>
                    <td>
                      <strong style="font-size: 14px; color: #2b6cb0;">{{ $ay['name'] }}</strong>
                      @if($ay['is_current'])
                        <span class="label label-success" style="margin-left: 5px;"><i class="fa fa-check"></i> Aktif</span>
                      @endif
                    </td>
                    <td>{{ date('d F Y', strtotime($ay['start_date'])) }}</td>
                    <td>{{ date('d F Y', strtotime($ay['end_date'])) }}</td>
                    <td><small class="text-muted">{{ $ay['description'] ?? '-' }}</small></td>
                    <td class="text-center">
                      @if($ay['is_current'])
                        <span class="label label-success">Sedang Berjalan</span>
                      @elseif(($ay['status'] ?? '') === 'selesai')
                        <span class="label label-default">Telah Selesai</span>
                      @else
                        <span class="label label-warning">Draft / Mendatang</span>
                      @endif
                    </td>
                    <td class="text-center irnis-actions">
                      @if(!$ay['is_current'])
                        <a href="{{ route('master.tahun-ajaran.activate', ['id' => $ay['id']]) }}" class="btn btn-xs btn-primary btn-flat" title="Jadikan Periode Aktif" onclick="return confirm('Apakah Anda yakin ingin mengalihkan periode aktif ke {{ $ay['name'] }}?')">
                          <i class="fa fa-check-circle"></i> Aktifkan
                        </a>
                      @else
                        <button class="btn btn-xs btn-success btn-flat" disabled title="Sedang Aktif">
                          <i class="fa fa-check"></i> Terpilih
                        </button>
                      @endif

                      <button class="btn btn-xs btn-warning btn-flat" onclick="alert('Simulasi form edit {{ $ay['name'] }}')" title="Edit Periode">
                        <i class="fa fa-pencil"></i>
                      </button>

                      @if(!$ay['is_current'])
                        <button class="btn btn-xs btn-danger btn-flat" onclick="confirmAction('{{ route('master.tahun-ajaran.destroy', ['id' => $ay['id']]) }}', 'Hapus tahun ajaran {{ $ay['name'] }} dari sesi demo?')" title="Hapus">
                          <i class="fa fa-trash"></i>
                        </button>
                      @endif
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

  <!-- Modal Tambah Periode Akademik Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-ta" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Tambah Tahun Ajaran & Semester Baru</h4>
        </div>
        <form action="{{ route('master.tahun-ajaran.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tahun Ajaran <span class="text-danger">*</span></label>
                  <input type="text" name="year" class="form-control" placeholder="Contoh: 2027/2028" value="2027/2028" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Semester <span class="text-danger">*</span></label>
                  <select name="semester" class="form-control" required>
                    <option value="Ganjil">Semester Ganjil</option>
                    <option value="Genap">Semester Genap</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Mulai Periode <span class="text-danger">*</span></label>
                  <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Selesai Periode <span class="text-danger">*</span></label>
                  <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+6 months')) }}" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Keterangan / Catatan Periode</label>
              <textarea name="description" class="form-control" rows="2" placeholder="Catatan agenda atau target semester..."></textarea>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" name="set_as_active" value="1"> <strong>Langsung jadikan sebagai Tahun Ajaran Aktif</strong>
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Periode Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

