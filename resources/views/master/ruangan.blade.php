@extends('layouts.app')

@section('title', 'Ruangan & Gedung')
@section('page_title', 'Ruangan & Fasilitas Gedung')
@section('page_description', 'Inventaris Ruang Kelas Teori, Laboratorium, Perpustakaan, dan Fasilitas Umum')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Ruangan & Gedung</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($rooms) }}</h3>
          <p>Total Ruangan Terdaftar</p>
        </div>
        <div class="icon"><i class="fa fa-building-o"></i></div>
        <a href="#table-ruangan" class="small-box-footer">Daftar Ruangan <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($rooms, fn($r) => ($r['condition'] ?? '') === 'baik')) }}</h3>
          <p>Kondisi Baik & Siap Pakai</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <a href="{{ route('master.ruangan', ['condition' => 'baik']) }}" class="small-box-footer">Filter Siap Pakai <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($rooms, fn($r) => ($r['condition'] ?? '') === 'perbaikan')) }}</h3>
          <p>Dalam Perbaikan / Servis</p>
        </div>
        <div class="icon"><i class="fa fa-wrench"></i></div>
        <a href="{{ route('sarpras.pemeliharaan') }}" class="small-box-footer">Log Sarpras <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column($rooms, 'capacity')) }}</h3>
          <p>Total Kapasitas Kursi</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="{{ route('master.kelas') }}" class="small-box-footer">Alokasi Rombel <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-ruangan">
    <div class="col-md-12">
      <x-card title="Inventaris Ruangan & Fasilitas Kampus Alia" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('master.ruangan') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 240px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama ruang/gedung...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Jenis Ruang -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="type" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $typeFilter == 'all' ? 'selected' : '' }}>Semua Tipe Ruang</option>
                  <option value="Kelas Teori" {{ $typeFilter == 'Kelas Teori' ? 'selected' : '' }}>Kelas Teori</option>
                  <option value="Laboratorium" {{ $typeFilter == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                  <option value="Perpustakaan" {{ $typeFilter == 'Perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                  <option value="Aula" {{ $typeFilter == 'Aula' ? 'selected' : '' }}>Aula Pertemuan</option>
                  <option value="Tempat Ibadah" {{ $typeFilter == 'Tempat Ibadah' ? 'selected' : '' }}>Masjid / Musholla</option>
                  <option value="Kesehatan" {{ $typeFilter == 'Kesehatan' ? 'selected' : '' }}>Ruang UKS</option>
                </select>
              </div>

              <!-- Filter Kondisi -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="condition" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $conditionFilter == 'all' ? 'selected' : '' }}>Semua Kondisi</option>
                  <option value="baik" {{ $conditionFilter == 'baik' ? 'selected' : '' }}>Kondisi Baik</option>
                  <option value="perbaikan" {{ $conditionFilter == 'perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                  <option value="rusak" {{ $conditionFilter == 'rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
              </div>

              @if($search || $typeFilter !== 'all' || $conditionFilter !== 'all')
                <a href="{{ route('master.ruangan') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-ruangan">
              <i class="fa fa-plus-circle"></i> Tambah Ruangan Baru
            </button>
          </div>
        </div>

        @if(count($rooms) === 0)
          <x-empty-state 
            title="Tidak Ada Data Ruangan"
            message="Tidak ada ruangan atau gedung yang sesuai dengan filter pencarian Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('master.ruangan') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>Kode Ruang</th>
                  <th>Nama Ruangan / Fasilitas</th>
                  <th>Tipe Ruang</th>
                  <th>Lokasi Gedung / Lantai</th>
                  <th>Unit Pengguna</th>
                  <th class="text-center">Kapasitas</th>
                  <th class="text-center">Kondisi Fasilitas</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rooms as $r)
                  @php
                    $unitObj = collect($units)->firstWhere('id', $r['unit_id'] ?? '');
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $r['id'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $r['name'] }}</strong>
                    </td>
                    <td><span class="label label-info">{{ $r['type'] }}</span></td>
                    <td><i class="fa fa-map-marker text-muted"></i> {{ $r['location'] }}</td>
                    <td>{{ $unitObj['name'] ?? 'Umum / Semua Unit' }}</td>
                    <td class="text-center">
                      <span class="badge bg-aqua" style="font-size: 12px;">{{ $r['capacity'] }} Orang</span>
                    </td>
                    <td class="text-center">
                      @if(($r['condition'] ?? '') === 'baik')
                        <span class="label label-success"><i class="fa fa-check"></i> Kondisi Baik</span>
                      @elseif(($r['condition'] ?? '') === 'perbaikan')
                        <span class="label label-warning"><i class="fa fa-wrench"></i> Dalam Perbaikan</span>
                      @else
                        <span class="label label-danger"><i class="fa fa-ban"></i> Rusak Berat</span>
                      @endif
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Detail Ruangan" data-toggle="modal" data-target="#modal-detail-rng-{{ $r['id'] }}">
                        <i class="fa fa-eye"></i>
                      </button>
                      <button class="btn btn-xs btn-warning btn-flat" title="Edit Ruangan" onclick="alert('Simulasi form edit ruangan {{ $r['name'] }}')">
                        <i class="fa fa-pencil"></i>
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Ruangan" onclick="confirmAction('{{ route('master.ruangan.destroy', ['id' => $r['id']]) }}', 'Apakah Anda yakin ingin menghapus ruangan {{ $r['name'] }} dari sesi demo?')">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>

                  <!-- Modal Detail Ruangan -->
                  <div class="modal fade" id="modal-detail-rng-{{ $r['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-building-o"></i> Detail Ruangan: {{ $r['name'] }}</h4>
                        </div>
                        <div class="modal-body">
                          <table class="table table-bordered table-striped">
                            <tr>
                              <th style="width: 35%;">Kode Ruang</th>
                              <td><code>{{ $r['id'] }}</code></td>
                            </tr>
                            <tr>
                              <th>Nama Fasilitas</th>
                              <td><strong>{{ $r['name'] }}</strong></td>
                            </tr>
                            <tr>
                              <th>Tipe / Kategori</th>
                              <td><span class="label label-info">{{ $r['type'] }}</span></td>
                            </tr>
                            <tr>
                              <th>Lokasi Kampus</th>
                              <td>{{ $r['location'] }}</td>
                            </tr>
                            <tr>
                              <th>Unit Pengguna</th>
                              <td>{{ $unitObj['name'] ?? 'Umum / Terpadu' }}</td>
                            </tr>
                            <tr>
                              <th>Kapasitas Tampung</th>
                              <td><b>{{ $r['capacity'] }}</b> Santri / Pengguna</td>
                            </tr>
                            <tr>
                              <th>Status Kelayakan</th>
                              <td>
                                @if(($r['condition'] ?? '') === 'baik')
                                  <span class="label label-success">Baik & Layak Pakai</span>
                                @elseif(($r['condition'] ?? '') === 'perbaikan')
                                  <span class="label label-warning">Sedang Pemeliharaan</span>
                                @else
                                  <span class="label label-danger">Perlu Perbaikan</span>
                                @endif
                              </td>
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

  <!-- Modal Tambah Ruangan Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-ruangan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Ruangan / Fasilitas Baru</h4>
        </div>
        <form action="{{ route('master.ruangan.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Ruangan / Fasilitas <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Contoh: Lab Robotika & IoT" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tipe Ruang <span class="text-danger">*</span></label>
                  <select name="type" class="form-control" required>
                    <option value="Kelas Teori">Kelas Teori</option>
                    <option value="Laboratorium" selected>Laboratorium</option>
                    <option value="Perpustakaan">Perpustakaan</option>
                    <option value="Aula">Aula Pertemuan</option>
                    <option value="Tempat Ibadah">Tempat Ibadah</option>
                    <option value="Kesehatan">Ruang UKS</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Lokasi Gedung & Lantai <span class="text-danger">*</span></label>
              <input type="text" name="location" class="form-control" placeholder="Contoh: Gedung Ibnu Sina Lt. 2" required>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Kapasitas (Kursi/Orang) <span class="text-danger">*</span></label>
                  <input type="number" name="capacity" class="form-control" value="32" min="1" max="1000" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Kondisi Ruangan <span class="text-danger">*</span></label>
                  <select name="condition" class="form-control" required>
                    <option value="baik" selected>Kondisi Baik</option>
                    <option value="perbaikan">Dalam Perbaikan</option>
                    <option value="rusak">Rusak</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Unit Pendidikan <span class="text-danger">*</span></label>
                  <select name="unit_id" class="form-control" required>
                    @foreach($units as $u)
                      <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Ruangan Demo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

