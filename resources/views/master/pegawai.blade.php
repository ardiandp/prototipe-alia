@extends('layouts.app')

@section('title', 'Data Guru & Pegawai')
@section('page_title', 'Guru & Tenaga Kependidikan')
@section('page_description', 'Kelola Data Pendidik, Asatidz, Jabatan Struktural, dan Status Kepegawaian')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Guru & Pegawai</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count($teachers) }}</h3>
          <p>Total Pendidik & Asatidz</p>
        </div>
        <div class="icon"><i class="fa fa-graduation-cap"></i></div>
        <a href="#table-pegawai" class="small-box-footer">Daftar Guru <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($teachers, fn($t) => ($t['employment_status'] ?? '') === 'Guru Tetap Yayasan (GTY)')) }}</h3>
          <p>Guru Tetap Yayasan (GTY)</p>
        </div>
        <div class="icon"><i class="fa fa-id-card-o"></i></div>
        <a href="{{ route('master.pegawai', ['status' => 'Guru Tetap Yayasan (GTY)']) }}" class="small-box-footer">Filter GTY <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($teachers, fn($t) => stripos(($t['subject'] ?? ''), 'tahfidz') !== false || stripos(($t['subject'] ?? ''), 'arab') !== false || stripos(($t['subject'] ?? ''), 'tafsir') !== false)) }}</h3>
          <p>Asatidz Diniyah & Tahfidz</p>
        </div>
        <div class="icon"><i class="fa fa-book"></i></div>
        <a href="{{ route('tahfidz.target') }}" class="small-box-footer">Halaqah Asatidz <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>98.2%</h3>
          <p>Tingkat Presensi Mengajar</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle-o"></i></div>
        <a href="{{ route('hris.presensi') }}" class="small-box-footer">Presensi SDM <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-pegawai">
    <div class="col-md-12">
      <x-card title="Daftar Guru & Tenaga Kependidikan Alia Islamic School" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('master.pegawai') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 240px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / NIP / mapel...">
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

              <!-- Filter Status Kepegawaian -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="status" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status Pegawai</option>
                  <option value="Guru Tetap Yayasan (GTY)" {{ $statusFilter == 'Guru Tetap Yayasan (GTY)' ? 'selected' : '' }}>GTY (Tetap)</option>
                  <option value="Guru Kontrak" {{ $statusFilter == 'Guru Kontrak' ? 'selected' : '' }}>Guru Kontrak</option>
                  <option value="Tenaga Kependidikan" {{ $statusFilter == 'Tenaga Kependidikan' ? 'selected' : '' }}>Tenaga Kependidikan</option>
                </select>
              </div>

              @if($search || $unitFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('master.pegawai') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-pegawai">
              <i class="fa fa-user-plus"></i> Tambah Guru / Pegawai
            </button>
          </div>
        </div>

        @if(count($teachers) === 0)
          <x-empty-state 
            title="Tidak Ada Data Pendidik"
            message="Tidak ada catatan pegawai atau guru yang sesuai dengan kriteria filter Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('master.pegawai') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 45px;" class="text-center">No</th>
                  <th style="width: 55px;" class="text-center">Foto</th>
                  <th>NIP Pegawai</th>
                  <th>Nama Lengkap & Gelar</th>
                  <th>Mata Pelajaran / Bidang</th>
                  <th>Jabatan & Tugas Tambahan</th>
                  <th>Unit Penempatan</th>
                  <th>Status Kepegawaian</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($teachers as $t)
                  @php
                    $unitObj = collect($units)->firstWhere('id', $t['unit_id'] ?? '');
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                      <img src="{{ asset('AdminLTE/dist/img/' . ($t['avatar'] ?? 'avatar5.png')) }}" class="img-circle" style="width: 36px; height: 36px; border: 1px solid #ddd;" alt="Avatar">
                    </td>
                    <td><code>{{ $t['nip'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $t['name'] }}</strong><br>
                      <small class="text-muted"><i class="fa fa-whatsapp text-green"></i> {{ $t['phone'] }}</small>
                    </td>
                    <td><b>{{ $t['subject'] }}</b></td>
                    <td>{{ $t['position'] ?? 'Guru Mata Pelajaran' }}</td>
                    <td><span class="label label-info">{{ $unitObj['name'] ?? $t['unit_id'] }}</span></td>
                    <td>
                      @if(($t['employment_status'] ?? '') === 'Guru Tetap Yayasan (GTY)')
                        <span class="label label-success"><i class="fa fa-check"></i> GTY</span>
                      @elseif(($t['employment_status'] ?? '') === 'Tenaga Kependidikan')
                        <span class="label label-primary">Kependidikan</span>
                      @else
                        <span class="label label-warning">Kontrak</span>
                      @endif
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Detail Profil Guru" data-toggle="modal" data-target="#modal-detail-gur-{{ $t['id'] }}">
                        <i class="fa fa-eye"></i> Detail
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Pegawai" onclick="confirmAction('{{ route('master.pegawai.destroy', ['id' => $t['id']]) }}', 'Hapus data pegawai {{ $t['name'] }} dari sesi demo?')">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>

                  <!-- Modal Detail Pegawai -->
                  <div class="modal fade" id="modal-detail-gur-{{ $t['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-user-circle"></i> Profil Pendidik: {{ $t['name'] }}</h4>
                        </div>
                        <div class="modal-body">
                          <div class="text-center" style="margin-bottom: 15px;">
                            <img src="{{ asset('AdminLTE/dist/img/' . ($t['avatar'] ?? 'avatar5.png')) }}" class="img-circle img-thumbnail" style="width: 80px; height: 80px;" alt="Avatar">
                            <h4 style="color:#2b6cb0; font-weight:bold; margin-top:5px;">{{ $t['name'] }}</h4>
                            <p class="text-muted">{{ $t['position'] ?? 'Pendidik' }} &bull; {{ $unitObj['name'] ?? '-' }}</p>
                          </div>
                          <table class="table table-bordered table-striped">
                            <tr><th style="width: 35%;">Nomor Induk Pegawai (NIP)</th><td><code>{{ $t['nip'] }}</code></td></tr>
                            <tr><th>Mata Pelajaran Utama</th><td><b>{{ $t['subject'] }}</b></td></tr>
                            <tr><th>Status Kepegawaian</th><td><span class="label label-success">{{ $t['employment_status'] }}</span></td></tr>
                            <tr><th>Pendidikan Terakhir</th><td>{{ $t['education'] ?? 'S1 Pendidikan' }}</td></tr>
                            <tr><th>Email Resmi Sekolah</th><td>{{ $t['email'] }}</td></tr>
                            <tr><th>Nomor Kontak WhatsApp</th><td><i class="fa fa-whatsapp text-green"></i> {{ $t['phone'] }}</td></tr>
                            <tr><th>Status Presensi & Jadwal</th><td><span class="label label-info">Aktif Mengajar Semester Ini</span></td></tr>
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

  <!-- Modal Tambah Guru/Pegawai Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-pegawai" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah Pendidik / Pegawai Baru</h4>
        </div>
        <form action="{{ route('master.pegawai.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Contoh: Ust. Farhan Al-Makky, Lc." required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="gender" class="form-control" required>
                    <option value="L">Laki-Laki (Ikhwan)</option>
                    <option value="P">Perempuan (Akhwat)</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Mata Pelajaran / Bidang Ajar <span class="text-danger">*</span></label>
                  <input type="text" name="subject" class="form-control" placeholder="Contoh: Bahasa Arab & Tahfidz" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jabatan / Tugas Tambahan <span class="text-danger">*</span></label>
                  <input type="text" name="position" class="form-control" placeholder="Contoh: Wali Kelas / Pembina Halaqah" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status Kepegawaian <span class="text-danger">*</span></label>
                  <select name="employment_status" class="form-control" required>
                    <option value="Guru Tetap Yayasan (GTY)">Guru Tetap Yayasan (GTY)</option>
                    <option value="Guru Kontrak">Guru Kontrak</option>
                    <option value="Tenaga Kependidikan">Tenaga Kependidikan</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Unit Penempatan <span class="text-danger">*</span></label>
                  <select name="unit_id" class="form-control" required>
                    @foreach($units as $u)
                      <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                  <input type="text" name="phone" class="form-control" placeholder="0812xxxxxxxx" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email Resmi</label>
                  <input type="email" name="email" class="form-control" placeholder="pegawai@alia.sch.id">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Pegawai Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

