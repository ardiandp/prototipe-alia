@extends('layouts.app')

@section('title', 'Data Pendaftar PPDB Online')
@section('page_title', 'Data Calon Santri Baru')
@section('page_description', 'Kelola Berkas Pendaftaran, Hasil Seleksi, dan Rincian Calon Siswa')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li class="active">Data Pendaftar</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($applicants) }}</h3>
          <p>Total Calon Santri Terdata</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="#table-pendaftar" class="small-box-footer">Tabel Pendaftar <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['selection_status'] ?? '') === 'lulus')) }}</h3>
          <p>Dinyatakan Lulus Seleksi</p>
        </div>
        <div class="icon"><i class="fa fa-trophy"></i></div>
        <a href="{{ route('ppdb.pendaftar', ['status' => 'lulus']) }}" class="small-box-footer">Filter Lulus <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['doc_status'] ?? '') === 'menunggu')) }}</h3>
          <p>Berkas Butuh Verifikasi</p>
        </div>
        <div class="icon"><i class="fa fa-check-square-o"></i></div>
        <a href="{{ route('ppdb.verifikasi') }}" class="small-box-footer">Buka Verifikasi <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['track'] ?? '') === 'Prestasi Tahfidz')) }}</h3>
          <p>Jalur Beasiswa Tahfidz</p>
        </div>
        <div class="icon"><i class="fa fa-book"></i></div>
        <a href="{{ route('ppdb.pendaftar', ['track' => 'Prestasi Tahfidz']) }}" class="small-box-footer">Filter Tahfidz <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-pendaftar">
    <div class="col-md-12">
      <x-card title="Buku Register Formulir Masuk PPDB TA 2027/2028" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-9 col-sm-12">
            <form action="{{ route('ppdb.pendaftar') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 220px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / no. reg / wali...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Unit -->
              <div class="form-group" style="margin-left: 6px;">
                <select name="unit" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Unit</option>
                  <option value="SD" {{ $unitFilter == 'SD' ? 'selected' : '' }}>SD Islam Alia</option>
                  <option value="SMP" {{ $unitFilter == 'SMP' ? 'selected' : '' }}>SMP Islam Alia</option>
                  <option value="SMA" {{ $unitFilter == 'SMA' ? 'selected' : '' }}>SMA Islam Alia Boarding</option>
                </select>
              </div>

              <!-- Filter Jalur -->
              <div class="form-group" style="margin-left: 6px;">
                <select name="track" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $trackFilter == 'all' ? 'selected' : '' }}>Semua Jalur Masuk</option>
                  <option value="Reguler" {{ $trackFilter == 'Reguler' ? 'selected' : '' }}>Jalur Reguler</option>
                  <option value="Prestasi Tahfidz" {{ $trackFilter == 'Prestasi Tahfidz' ? 'selected' : '' }}>Prestasi Tahfidz</option>
                  <option value="Prestasi Akademik" {{ $trackFilter == 'Prestasi Akademik' ? 'selected' : '' }}>Prestasi Akademik</option>
                  <option value="Afirmasi / Yatim" {{ $trackFilter == 'Afirmasi / Yatim' ? 'selected' : '' }}>Afirmasi / Yatim</option>
                </select>
              </div>

              <!-- Filter Status Seleksi -->
              <div class="form-group" style="margin-left: 6px;">
                <select name="status" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status</option>
                  <option value="lulus" {{ $statusFilter == 'lulus' ? 'selected' : '' }}>Lulus</option>
                  <option value="proses" {{ $statusFilter == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                  <option value="tidak_lulus" {{ $statusFilter == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                </select>
              </div>

              @if($search || $unitFilter !== 'all' || $trackFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('ppdb.pendaftar') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-3 col-sm-12 text-right">
            <a href="{{ route('ppdb.verifikasi') }}" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-check-square-o"></i> Pusat Verifikasi Berkas
            </a>
          </div>
        </div>

        @if(count($applicants) === 0)
          <x-empty-state 
            title="Tidak Ada Pendaftar"
            message="Tidak ada catatan calon santri yang sesuai dengan kriteria filter pencarian Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('ppdb.pendaftar') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 45px;" class="text-center">No</th>
                  <th>No. Registrasi</th>
                  <th>Nama Calon Santri</th>
                  <th>Jenjang / Unit</th>
                  <th>Jalur Masuk</th>
                  <th>Orang Tua / Wali</th>
                  <th class="text-center">Nilai Tes</th>
                  <th class="text-center">Status Berkas</th>
                  <th class="text-center">Hasil Seleksi</th>
                  <th class="text-center">Daftar Ulang</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($applicants as $ap)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $ap['reg_number'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $ap['name'] }}</strong>
                      <small class="text-muted d-block">
                        <i class="fa {{ $ap['gender'] == 'L' ? 'fa-male text-blue' : 'fa-female text-maroon' }}"></i> {{ $ap['gender'] == 'L' ? 'Ikhwan' : 'Akhwat' }}
                      </small>
                    </td>
                    <td><span class="label label-info">{{ $ap['unit_target'] }}</span></td>
                    <td>
                      @if($ap['track'] === 'Prestasi Tahfidz')
                        <span class="label label-purple" style="background:#6b46c1;"><i class="fa fa-book"></i> Tahfidz</span>
                      @elseif($ap['track'] === 'Prestasi Akademik')
                        <span class="label label-success"><i class="fa fa-trophy"></i> Akademik</span>
                      @elseif($ap['track'] === 'Afirmasi / Yatim')
                        <span class="label label-warning"><i class="fa fa-heart"></i> Afirmasi</span>
                      @else
                        <span class="label label-default">Reguler</span>
                      @endif
                    </td>
                    <td>
                      {{ $ap['guardian_name'] }}<br>
                      <small class="text-muted"><i class="fa fa-whatsapp text-green"></i> {{ $ap['guardian_phone'] }}</small>
                    </td>
                    <td class="text-center">
                      <span class="badge {{ ($ap['exam_score'] ?? 0) >= 80 ? 'bg-green' : 'bg-yellow' }}" style="font-size:12px;">{{ $ap['exam_score'] ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                      @if($ap['doc_status'] === 'diverifikasi')
                        <span class="label label-success"><i class="fa fa-check"></i> Lengkap</span>
                      @elseif($ap['doc_status'] === 'ditolak')
                        <span class="label label-danger"><i class="fa fa-ban"></i> Tidak Sesuai</span>
                      @else
                        <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($ap['selection_status'] === 'lulus')
                        <span class="label label-success">LULUS</span>
                      @elseif($ap['selection_status'] === 'tidak_lulus')
                        <span class="label label-danger">TIDAK LULUS</span>
                      @else
                        <span class="label label-default">PROSES</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <x-badge :status="$ap['payment_status']" />
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Rincian Berkas Formulir" data-toggle="modal" data-target="#modal-detail-ap-{{ $ap['id'] }}">
                        <i class="fa fa-eye"></i> Detail
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Pendaftar" onclick="confirmAction('{{ route('ppdb.pendaftar.destroy', ['id' => $ap['id']]) }}', 'Hapus pendaftar {{ $ap['name'] }} dari sesi demo?')">
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

  <!-- Detail Formulir Calon Santri Modals (Moved outside table) -->
  @foreach($applicants as $ap)
    <div class="modal fade" id="modal-detail-ap-{{ $ap['id'] }}" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-user-plus"></i> Berkas Formulir PPDB: {{ $ap['name'] }} ({{ $ap['reg_number'] }})</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h5 style="font-weight:bold; color:#2b6cb0; border-bottom:1px solid #ddd; padding-bottom:5px;">A. Data Pribadi Calon Santri</h5>
                <table class="table table-bordered table-striped">
                  <tr><th style="width: 40%;">Nomor Registrasi</th><td><code>{{ $ap['reg_number'] }}</code></td></tr>
                  <tr><th>Nama Lengkap</th><td><strong>{{ $ap['name'] }}</strong></td></tr>
                  <tr><th>Jenis Kelamin</th><td>{{ $ap['gender'] == 'L' ? 'Laki-Laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</td></tr>
                  <tr><th>Unit Pilihan</th><td>{{ $ap['unit_target'] }}</td></tr>
                  <tr><th>Jalur Seleksi</th><td><span class="label label-primary">{{ $ap['track'] }}</span></td></tr>
                  <tr><th>Waktu Registrasi</th><td>{{ $ap['registered_at'] }}</td></tr>
                </table>
              </div>
              <div class="col-md-6">
                <h5 style="font-weight:bold; color:#2b6cb0; border-bottom:1px solid #ddd; padding-bottom:5px;">B. Data Orang Tua & Hasil Ujian</h5>
                <table class="table table-bordered table-striped">
                  <tr><th style="width: 40%;">Nama Orang Tua/Wali</th><td>{{ $ap['guardian_name'] }}</td></tr>
                  <tr><th>WhatsApp Aktif</th><td><i class="fa fa-whatsapp text-green"></i> {{ $ap['guardian_phone'] }}</td></tr>
                  <tr><th>Nilai Tes Seleksi</th><td><span class="badge bg-green" style="font-size:13px;">{{ $ap['exam_score'] ?? '-' }}</span></td></tr>
                  <tr><th>Verifikasi Berkas</th><td><span class="label label-info">{{ ucfirst($ap['doc_status']) }}</span></td></tr>
                  <tr><th>Keputusan Seleksi</th><td><span class="label label-success">{{ strtoupper($ap['selection_status']) }}</span></td></tr>
                  <tr><th>Status Pembayaran</th><td><x-badge :status="$ap['payment_status']" /></td></tr>
                </table>
              </div>
            </div>
            
            <div class="well well-sm" style="margin-top:10px;">
              <strong>Catatan Petugas Panitia PPDB:</strong><br>
              <span class="text-muted">{{ $ap['notes'] ?? 'Belum ada catatan khusus dari tim verifikasi.' }}</span>
            </div>
          </div>
          <div class="modal-footer">
            @if($ap['selection_status'] === 'lulus' && $ap['payment_status'] === 'lunas')
              <a href="{{ route('ppdb.daftar-ulang') }}" class="btn btn-success btn-flat pull-left">
                <i class="fa fa-user-check"></i> Proses Konversi Menjadi Santri Aktif
              </a>
            @endif
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach

@endsection
