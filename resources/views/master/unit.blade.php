@extends('layouts.app')

@section('title', 'Master Data Unit Pendidikan')
@section('page_title', 'Unit Pendidikan')
@section('page_description', 'Kelola Jenjang dan Cabang Sekolah Alia Islamic School')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Unit Pendidikan</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($units) }}</h3>
          <p>Total Unit Aktif</p>
        </div>
        <div class="icon"><i class="fa fa-university"></i></div>
        <a href="#table-unit" class="small-box-footer">Lihat Daftar Unit <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ array_sum(array_column($units, 'student_count')) }}</h3>
          <p>Total Santri Terdaftar</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="{{ route('master.siswa') }}" class="small-box-footer">Buka Data Santri <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column($units, 'class_count')) }}</h3>
          <p>Total Rombongan Belajar</p>
        </div>
        <div class="icon"><i class="fa fa-sitemap"></i></div>
        <a href="{{ route('master.kelas') }}" class="small-box-footer">Kelola Rombel <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Toolbar & Table -->
  <div class="row" id="table-unit">
    <div class="col-md-12">
      <x-card title="Daftar Unit Pendidikan Alia" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-7 col-sm-12">
            <form action="{{ route('master.unit') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / kepala sekolah / NPSN...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <div class="form-group" style="margin-left: 10px;">
                <select name="level" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $levelFilter == 'all' ? 'selected' : '' }}>Semua Jenjang</option>
                  <option value="SD" {{ $levelFilter == 'SD' ? 'selected' : '' }}>Jenjang SD</option>
                  <option value="SMP" {{ $levelFilter == 'SMP' ? 'selected' : '' }}>Jenjang SMP</option>
                  <option value="SMA" {{ $levelFilter == 'SMA' ? 'selected' : '' }}>Jenjang SMA</option>
                </select>
              </div>

              @if($search || $levelFilter !== 'all')
                <a href="{{ route('master.unit') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-5 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-unit">
              <i class="fa fa-plus-circle"></i> Tambah Unit Pendidikan
            </button>
          </div>
        </div>

        @if(count($units) === 0)
          <x-empty-state 
            title="Tidak Ada Unit Pendidikan"
            message="Tidak ada unit pendidikan yang sesuai dengan filter pencarian Anda."
            actionLabel="Reset Pencarian"
            actionUrl="{{ route('master.unit') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>Kode & NPSN</th>
                  <th>Nama Unit Pendidikan</th>
                  <th>Jenjang</th>
                  <th>Kepala Sekolah</th>
                  <th class="text-center">Santri</th>
                  <th class="text-center">Rombel</th>
                  <th class="text-center">Status</th>
                  <th style="width: 130px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($units as $index => $u)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                      <strong>{{ $u['code'] ?? $u['id'] }}</strong><br>
                      <small class="text-muted">NPSN: {{ $u['npsn'] ?? '-' }}</small>
                    </td>
                    <td>
                      <span style="font-weight: 600; color: #2b6cb0; font-size: 14px;">{{ $u['name'] }}</span><br>
                      <small class="text-muted"><i class="fa fa-map-marker"></i> {{ $u['address'] ?? 'Jakarta Timur' }}</small>
                    </td>
                    <td>
                      <span class="label label-info">{{ $u['level'] }}</span>
                    </td>
                    <td>
                      <strong>{{ $u['headmaster'] }}</strong><br>
                      <small class="text-muted"><i class="fa fa-phone"></i> {{ $u['phone'] ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-aqua" style="font-size: 12px;">{{ $u['student_count'] ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-purple" style="font-size: 12px;">{{ $u['class_count'] ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                      <x-badge :status="$u['status'] ?? 'aktif'" />
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" data-toggle="modal" data-target="#modal-detail-{{ $u['id'] }}" title="Lihat Detail">
                        <i class="fa fa-eye"></i>
                      </button>
                      <button class="btn btn-xs btn-warning btn-flat" onclick="alert('Simulasi form edit unit {{ $u['name'] }}')" title="Edit Unit">
                        <i class="fa fa-pencil"></i>
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" onclick="confirmAction('{{ route('master.unit.destroy', ['id' => $u['id']]) }}', 'Apakah Anda yakin ingin menghapus unit {{ $u['name'] }} dari sesi demo?')" title="Hapus Unit">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>

                  <!-- Modal Detail Unit -->
                  <div class="modal fade" id="modal-detail-{{ $u['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-university"></i> Profil Unit: {{ $u['name'] }}</h4>
                        </div>
                        <div class="modal-body">
                          <table class="table table-bordered table-striped">
                            <tr>
                              <th style="width: 35%;">ID / Kode Unit</th>
                              <td>{{ $u['id'] }} ({{ $u['code'] ?? '-' }})</td>
                            </tr>
                            <tr>
                              <th>NPSN</th>
                              <td>{{ $u['npsn'] ?? '-' }}</td>
                            </tr>
                            <tr>
                              <th>Jenjang Pendidikan</th>
                              <td>{{ $u['level'] }}</td>
                            </tr>
                            <tr>
                              <th>Kepala Sekolah</th>
                              <td><strong>{{ $u['headmaster'] }}</strong></td>
                            </tr>
                            <tr>
                              <th>Kontak Telepon / Email</th>
                              <td>{{ $u['phone'] ?? '-' }} / {{ $u['email'] ?? '-' }}</td>
                            </tr>
                            <tr>
                              <th>Alamat Kampus</th>
                              <td>{{ $u['address'] ?? '-' }}</td>
                            </tr>
                            <tr>
                              <th>Statistik Siswa & Rombel</th>
                              <td><b>{{ $u['student_count'] ?? 0 }}</b> Santri dalam <b>{{ $u['class_count'] ?? 0 }}</b> Rombel</td>
                            </tr>
                            <tr>
                              <th>Status Operasional</th>
                              <td><x-badge :status="$u['status'] ?? 'aktif'" /></td>
                            </tr>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </x-card>
    </div>
  </div>

  <!-- Modal Tambah Unit Baru (Form Bootstrap 3) -->
  <div class="modal fade" id="modal-add-unit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Unit Pendidikan Baru</h4>
        </div>
        <form action="{{ route('master.unit.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Unit Pendidikan <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Contoh: SMA Islam Alia" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Kode Unit <span class="text-danger">*</span></label>
                  <input type="text" name="code" class="form-control" placeholder="Contoh: SMA-ALIA" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Jenjang <span class="text-danger">*</span></label>
                  <select name="level" class="form-control" required>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA" selected>SMA</option>
                    <option value="SMK">SMK</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kepala Sekolah <span class="text-danger">*</span></label>
                  <input type="text" name="headmaster" class="form-control" placeholder="Nama lengkap & gelar" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>NPSN (Nomor Pokok Sekolah Nasional)</label>
                  <input type="text" name="npsn" class="form-control" placeholder="Contoh: 20108924">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>No. Telepon Sekolah</label>
                  <input type="text" name="phone" class="form-control" placeholder="021-8690xxxx">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email Resmi Unit</label>
                  <input type="email" name="email" class="form-control" placeholder="unit@alia.sch.id">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Kampus</label>
              <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap kampus unit"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Unit Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

