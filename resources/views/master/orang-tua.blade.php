@extends('layouts.app')

@section('title', 'Data Orang Tua / Wali')
@section('page_title', 'Data Orang Tua & Wali Santri')
@section('page_description', 'Kelola Hubungan Keluarga, Kontak Telepon/WA, Alamat, dan Akses Portal Wali')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Orang Tua / Wali</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($guardians) }}</h3>
          <p>Total Kepala Keluarga Terdata</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="#table-wali" class="small-box-footer">Daftar Wali <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>100%</h3>
          <p>Terhubung ke Nomor WhatsApp</p>
        </div>
        <div class="icon"><i class="fa fa-whatsapp"></i></div>
        <a href="{{ route('keuangan.tagihan') }}" class="small-box-footer">Broadcast Tagihan <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>Aktif</h3>
          <p>Akses Portal Orang Tua</p>
        </div>
        <div class="icon"><i class="fa fa-mobile-phone"></i></div>
        <a href="{{ route('portal.dashboard') }}" class="small-box-footer">Buka Portal Wali <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-wali">
    <div class="col-md-12">
      <x-card title="Buku Induk Orang Tua & Wali Santri Alia" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('master.orang-tua') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 280px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama wali / santri / telepon...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              @if($search)
                <a href="{{ route('master.orang-tua') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-wali">
              <i class="fa fa-user-plus"></i> Tambah Orang Tua / Wali
            </button>
          </div>
        </div>

        @if(count($guardians) === 0)
          <x-empty-state 
            title="Tidak Ada Data Orang Tua / Wali"
            message="Tidak ada catatan wali santri yang sesuai dengan kata kunci pencarian Anda."
            actionLabel="Reset Pencarian"
            actionUrl="{{ route('master.orang-tua') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 45px;" class="text-center">No</th>
                  <th>ID Wali</th>
                  <th>Nama Lengkap Orang Tua / Wali</th>
                  <th>Santri Terhubung</th>
                  <th>Nomor Kontak WhatsApp</th>
                  <th>Pekerjaan / Profesi</th>
                  <th>Alamat Domisili</th>
                  <th class="text-center">Akses Portal</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($guardians as $g)
                  @php
                    $stu = collect($students)->firstWhere('id', $g['student_id'] ?? '');
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $g['id'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $g['name'] }}</strong><br>
                      <small class="text-muted"><i class="fa fa-envelope-o"></i> {{ $g['email'] ?? '-' }}</small>
                    </td>
                    <td>
                      @if($stu)
                        <strong>{{ $stu['name'] }}</strong><br>
                        <small class="text-muted">{{ $stu['unit_level'] }} (NIS: {{ $stu['nis'] }})</small>
                      @else
                        <span>{{ $g['student_name'] ?? 'Santri Alia' }}</span>
                      @endif
                    </td>
                    <td>
                      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $g['phone']) }}" target="_blank" class="text-green" style="font-weight: bold;">
                        <i class="fa fa-whatsapp"></i> {{ $g['phone'] }}
                      </a>
                    </td>
                    <td>{{ $g['occupation'] }}</td>
                    <td><small class="text-muted"><i class="fa fa-map-marker"></i> {{ $g['address'] }}</small></td>
                    <td class="text-center">
                      <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Detail Hubungan Wali" data-toggle="modal" data-target="#modal-detail-wli-{{ $g['id'] }}">
                        <i class="fa fa-eye"></i> Detail
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Data Wali" onclick="confirmAction('{{ route('master.orang-tua.destroy', ['id' => $g['id']]) }}', 'Hapus data wali {{ $g['name'] }} dari sesi demo?')">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>

                  <!-- Modal Detail Wali -->
                  <div class="modal fade" id="modal-detail-wli-{{ $g['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-user"></i> Detail Profil Wali: {{ $g['name'] }}</h4>
                        </div>
                        <div class="modal-body">
                          <table class="table table-bordered table-striped">
                            <tr><th style="width: 35%;">ID Wali</th><td><code>{{ $g['id'] }}</code></td></tr>
                            <tr><th>Nama Lengkap</th><td><strong>{{ $g['name'] }}</strong></td></tr>
                            <tr><th>Santri yang Dinaungi</th><td><strong>{{ $stu['name'] ?? $g['student_name'] ?? '-' }}</strong></td></tr>
                            <tr><th>Nomor WhatsApp Aktif</th><td><i class="fa fa-whatsapp text-green"></i> {{ $g['phone'] }}</td></tr>
                            <tr><th>Email Akun Portal</th><td>{{ $g['email'] }}</td></tr>
                            <tr><th>Pekerjaan / Instansi</th><td>{{ $g['occupation'] }}</td></tr>
                            <tr><th>Alamat Rumah Lengkap</th><td>{{ $g['address'] }}</td></tr>
                            <tr><th>Status Akun Portal Orang Tua</th><td><span class="label label-success">Terverifikasi & Aktif</span></td></tr>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <a href="{{ route('portal.dashboard') }}" class="btn btn-info btn-flat pull-left"><i class="fa fa-mobile-phone"></i> Simulasi Portal Wali</a>
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

  <!-- Modal Tambah Orang Tua Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-wali" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah Orang Tua / Wali Santri Baru</h4>
        </div>
        <form action="{{ route('master.orang-tua.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Pilih Santri Terhubung <span class="text-danger">*</span></label>
              <select name="student_id" class="form-control select2" style="width: 100%;" required>
                @foreach($students as $st)
                  <option value="{{ $st['id'] }}">{{ $st['name'] }} (NIS: {{ $st['nis'] }} - {{ $st['unit_level'] }})</option>
                @endforeach
              </select>
            </div>

            <div class="row">
              <div class="col-md-7">
                <div class="form-group">
                  <label>Nama Lengkap Orang Tua / Wali <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Contoh: Ir. H. Bambang Sudiro" required>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                  <input type="text" name="phone" class="form-control" placeholder="0812xxxxxxxx" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Profesi / Pekerjaan <span class="text-danger">*</span></label>
                  <input type="text" name="occupation" class="form-control" placeholder="Contoh: Wiraswasta / Arsitek" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Alamat Email</label>
                  <input type="email" name="email" class="form-control" placeholder="wali@gmail.com">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Tempat Tinggal <span class="text-danger">*</span></label>
              <textarea name="address" class="form-control" rows="2" placeholder="Alamat domisili lengkap" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Data Wali Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

