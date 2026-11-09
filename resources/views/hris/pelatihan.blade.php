@extends('layouts.app')

@section('title', 'Pelatihan & Pengembangan Kompetensi Pegawai')

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Pengembangan Kompetensi & Pelatihan (HRIS)
      <small>Sertifikasi Pendidik, Workshop, Pelatihan Berkelanjutan, dan Rencana Karir SDM</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>SDM / HRIS</li>
      <li class="active">Pelatihan & Kompetensi</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
      </div>
    @endif

    @if(session('info'))
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info"></i> Informasi</h4>
        {{ session('info') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Callout Prototipe -->
    <div class="callout callout-info" style="border-left-color: #2b6cb0;">
      <h4><i class="fa fa-graduation-cap"></i> Program Peningkatan Mutu SDM Yayasan Alia</h4>
      <p>Yayasan memfasilitasi program diklat terpadu mencakup: <b>Sertifikasi Pendidik (PPG Kemdikbud)</b>, <b>Sanad Tahfidz Matan Jazariyah</b>, <b>Workshop AI & Koding Pendidikan</b>, <b>Bimtek Kurikulum Merdeka</b>, serta <b>In-House Training (IHT) Penguatan Karakter Islam</b>. Setiap program terdokumentasi lengkap dengan sertifikat resmi dan capaian output kompetensi.</p>
    </div>

    <!-- 4 Info Box Ringkasan Diklat -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-book"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Program Diklat</span>
            <span class="info-box-number">{{ $totalProgram }} <small style="font-size: 13px; font-weight: normal;">Program</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-navy" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Tahun Akademik 2026/2027</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Telah Selesai</span>
            <span class="info-box-number text-green">{{ $totalSelesai }} <small style="font-size: 13px; font-weight: normal;">Pelatihan</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-green" style="width: {{ $totalProgram > 0 ? ($totalSelesai/$totalProgram)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Sertifikat Telah Diterbitkan</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-clock-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Akumulasi Jam Belajar</span>
            <span class="info-box-number text-blue">{{ $totalHours }} <small style="font-size: 13px; font-weight: normal;">Jam (JP)</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-blue" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Pengembangan Keprofesian</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-purple"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Investasi Pelatihan</span>
            <span class="info-box-number text-purple" style="font-size: 15px;">Rp {{ number_format($totalInvestment, 0, ',', '.') }}</span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-purple" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Dukungan Dana Yayasan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
          <a href="{{ route('hris.pelatihan', ['tab' => 'daftar', 'status' => $statusFilter, 'category' => $categoryFilter, 'unit' => $unitFilter, 'search' => $search]) }}">
            <i class="fa fa-list text-primary"></i> <b>Riwayat & Program Pelatihan Pegawai</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredTrainings) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'sertifikasi' ? 'active' : '' }}">
          <a href="{{ route('hris.pelatihan', ['tab' => 'sertifikasi']) }}">
            <i class="fa fa-certificate text-green"></i> <b>Buku Registrasi Sertifikat & Sanad</b>
            <span class="label label-success pull-right" style="margin-left: 6px;">{{ count($certifiedStaff) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'rencana' ? 'active' : '' }}">
          <a href="{{ route('hris.pelatihan', ['tab' => 'rencana']) }}">
            <i class="fa fa-road text-purple"></i> <b>Roadmap Pengembangan Karir Guru</b>
          </a>
        </li>
        <li class="pull-right header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-training">
            <i class="fa fa-plus"></i> Daftarkan Pelatihan Baru
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Rekapitulasi Diklat
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: RIWAYAT & PROGRAM PELATIHAN -->
        <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
          
          <!-- Filter Saringan -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.pelatihan') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="daftar">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Status Pelatihan:</label>
                <select name="status" class="form-control input-sm">
                  <option value="all">-- Semua Status --</option>
                  <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Telah Selesai (Bersertifikat)</option>
                  <option value="berjalan" {{ $statusFilter === 'berjalan' ? 'selected' : '' }}>Sedang Berlangsung</option>
                  <option value="terdaftar" {{ $statusFilter === 'terdaftar' ? 'selected' : '' }}>Terdaftar / Menunggu Mulai</option>
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Kategori Diklat:</label>
                <select name="category" class="form-control input-sm">
                  <option value="all">-- Semua Kategori --</option>
                  <option value="sertifikasi" {{ $categoryFilter === 'sertifikasi' ? 'selected' : '' }}>Sertifikasi Profesi</option>
                  <option value="workshop" {{ $categoryFilter === 'workshop' ? 'selected' : '' }}>Workshop & Sanad</option>
                  <option value="kurikulum" {{ $categoryFilter === 'kurikulum' ? 'selected' : '' }}>Bimtek Kurikulum</option>
                  <option value="inhouse" {{ $categoryFilter === 'inhouse' ? 'selected' : '' }}>In-House Training (IHT)</option>
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Unit:</label>
                <select name="unit" class="form-control input-sm">
                  <option value="all">-- Semua Unit --</option>
                  @foreach($units as $u)
                    <option value="{{ $u['id'] }}" {{ $unitFilter === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Cari:</label>
                <input type="text" name="search" class="form-control input-sm" placeholder="Judul, Nama, Lembaga..." value="{{ $search }}" style="width: 170px;">
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.pelatihan') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Pelatihan -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>No Reg & Program Pelatihan</th>
                  <th>Nama Pegawai Peserta</th>
                  <th>Lembaga Penyelenggara & Lokasi</th>
                  <th style="text-align: center;">Rentang Tanggal</th>
                  <th style="text-align: center;">Durasi</th>
                  <th style="text-align: right;">Biaya & Sumber</th>
                  <th style="text-align: center;">Status</th>
                  <th style="width: 130px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredTrainings as $index => $trn)
                  @php
                    $uName = 'Yayasan';
                    foreach($units as $u) {
                      if ($u['id'] === ($trn['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <b style="font-size: 13px; color: #1a365d;">{{ $trn['title'] }}</b>
                      <div style="font-size: 11px; color: #718096; margin-top: 2px;">
                        <span><i class="fa fa-tag"></i> <code>{{ $trn['doc_number'] }}</code></span> &bull; 
                        <span class="label label-primary" style="font-size: 10px;">{{ $trn['category_label'] }}</span>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      <b style="font-size: 13px; color: #2d3748;">{{ $trn['name'] }}</b>
                      <div style="font-size: 11px; color: #718096;">
                        <span>NIP: {{ $trn['nip'] ?? '-' }}</span> &bull; <span>{{ $uName }}</span>
                      </div>
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                      <div><i class="fa fa-university text-muted"></i> <b>{{ $trn['organizer'] }}</b></div>
                      <div class="text-muted"><i class="fa fa-map-marker text-red"></i> {{ $trn['location'] }}</div>
                    </td>
                    <td class="text-center" style="vertical-align: middle; font-size: 11px;">
                      <b>{{ date('d/m/Y', strtotime($trn['start_date'])) }}</b><br>
                      <small class="text-muted">s/d {{ date('d/m/Y', strtotime($trn['end_date'])) }}</small>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <span class="badge bg-blue">{{ $trn['duration_hours'] }} Jam</span>
                    </td>
                    <td class="text-right" style="vertical-align: middle; font-size: 12px;">
                      @if($trn['cost'] > 0)
                        <b style="color: #276749;">Rp {{ number_format($trn['cost'], 0, ',', '.') }}</b>
                      @else
                        <span class="label label-success">Gratis / Beasiswa</span>
                      @endif
                      <div style="font-size: 10px; color: #718096; margin-top: 2px;">{{ $trn['cost_source'] }}</div>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(($trn['status'] ?? '') === 'selesai')
                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check"></i> Selesai
                        </span>
                        @if(!empty($trn['certificate_number']))
                          <div style="font-size: 10px; color: #718096; margin-top: 2px;">No: {{ $trn['certificate_number'] }}</div>
                        @endif
                      @elseif(($trn['status'] ?? '') === 'berjalan')
                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-spinner fa-spin"></i> Berjalan
                        </span>
                      @else
                        <span class="label label-info" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-clock-o"></i> Terdaftar
                        </span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-trn-{{ $trn['id'] }}" title="Lihat Surat Tugas & Silabus">
                          <i class="fa fa-file-text-o text-primary"></i>
                        </button>

                        @if(($trn['status'] ?? '') !== 'selesai')
                          <button type="button" class="btn btn-default btn-xs text-green" data-toggle="modal" data-target="#modal-complete-trn-{{ $trn['id'] }}" title="Tandai Selesai & Input Sertifikat">
                            <i class="fa fa-certificate"></i>
                          </button>
                        @endif

                        <a href="{{ route('hris.pelatihan.destroy', $trn['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus program pelatihan {{ $trn['title'] }}?')" title="Hapus Data">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal Detail Surat Tugas & Silabus Diklat -->
                  <div class="modal fade" id="modal-view-trn-{{ $trn['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-graduation-cap"></i> Surat Tugas & Portofolio Pelatihan Pegawai</h4>
                        </div>
                        <div class="modal-body" style="background: #fff; padding: 25px;">
                          
                          <!-- KOP ALIA -->
                          <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                            <h3 style="margin: 0; color: #1a365d; font-weight: bold; font-size: 18px;">YAYASAN PENDIDIKAN ISLAM ALIA</h3>
                            <h4 style="margin: 3px 0; color: #2b6cb0; font-weight: bold; font-size: 15px;">ALIA ISLAMIC SCHOOL JAKARTA</h4>
                            <p style="margin: 0; font-size: 11px; color: #718096;">
                              Kampus Asrama Terpadu: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8690-1234
                            </p>
                          </div>

                          <div class="text-center" style="margin-bottom: 15px;">
                            <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 3px; color: #1a365d;">
                              SURAT TUGAS PENGEMBANGAN KEPROFESIAN BERKELANJUTAN
                            </h4>
                            <small class="text-muted">Nomor Registrasi: <b>{{ $trn['doc_number'] }}</b></small>
                          </div>

                          <table class="table table-bordered table-striped" style="font-size: 12px;">
                            <tr>
                              <th style="width: 35%; background: #f9f9f9;">Nama Pegawai Ditugaskan</th>
                              <td><b>{{ $trn['name'] }}</b></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">NIP / Unit Tugas</th>
                              <td>{{ $trn['nip'] ?? '-' }} / {{ $uName }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Nama Kegiatan Diklat</th>
                              <td><b>{{ $trn['title'] }}</b></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Kategori Program</th>
                              <td>{{ $trn['category_label'] }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Lembaga Penyelenggara</th>
                              <td>{{ $trn['organizer'] }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Tempat Pelaksanaan</th>
                              <td>{{ $trn['location'] }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Waktu Pelaksanaan</th>
                              <td>
                                {{ date('d F Y', strtotime($trn['start_date'])) }} s/d {{ date('d F Y', strtotime($trn['end_date'])) }}
                                (<b>{{ $trn['duration_hours'] }} Jam Pelajaran</b>)
                              </td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Deskripsi Substansi Materi</th>
                              <td>{{ $trn['description'] }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Target Output / Luaran</th>
                              <td><b>{{ $trn['output_target'] }}</b></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Nomor Sertifikat / Sanad</th>
                              <td>
                                @if(!empty($trn['certificate_number']))
                                  <span class="label label-success"><i class="fa fa-certificate"></i> {{ $trn['certificate_number'] }}</span>
                                @else
                                  <span class="text-muted"><i class="fa fa-clock-o"></i> Menunggu pelatihan selesai disahkan</span>
                                @endif
                              </td>
                            </tr>
                          </table>

                          <div class="row" style="margin-top: 25px;">
                            <div class="col-xs-6 text-center">
                              <small>Pegawai Peserta Diklat,</small><br><br><br>
                              <b><u>{{ $trn['name'] }}</u></b><br>
                              <small class="text-muted">NIP: {{ $trn['nip'] ?? '-' }}</small>
                            </div>
                            <div class="col-xs-6 text-center">
                              <small>Mengesahkan: Ketua Yayasan / HRD,</small><br><br><br>
                              <b><u>Dr. H. Zulkifli, Lc., M.A.</u></b><br>
                              <small class="text-muted">Ketua Yayasan Pendidikan Islam Alia</small>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Surat Tugas</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Selesaikan Pelatihan & Input Sertifikat -->
                  <div class="modal fade" id="modal-complete-trn-{{ $trn['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-sm" role="document">
                      <div class="modal-content">
                        <form action="{{ route('hris.pelatihan.complete', $trn['id']) }}" method="POST">
                          @csrf
                          <div class="modal-header" style="background-color: #00a65a; color: #fff;">
                            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-certificate"></i> Konfirmasi Sertifikasi</h4>
                          </div>
                          <div class="modal-body">
                            <p>Konfirmasi penyelesaian pelatihan untuk <b>{{ $trn['name'] }}</b>:</p>
                            <div class="form-group">
                              <label>Nomor Sertifikat / Ijazah Sanad <span class="text-danger">*</span></label>
                              <input type="text" name="certificate_number" class="form-control" placeholder="Contoh: CERT-2026-XXXX" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan Sertifikat</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                @empty
                  <tr>
                    <td colspan="9" class="text-center" style="padding: 30px;">
                      <i class="fa fa-folder-open-o text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data program pelatihan yang sesuai dengan kriteria filter.</p>
                      <a href="{{ route('hris.pelatihan') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: BUKU REGISTRASI SERTIFIKAT & SANAD -->
        <div class="tab-pane {{ $activeTab === 'sertifikasi' ? 'active' : '' }}" id="tab_sertifikasi">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-certificate text-green"></i> Direktori Pegawai Bersertifikat Pendidik, Ijazah Sanad, & Keahlian Khusus</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background: #f7fafc;">
                    <th>Nama Pendidik & NIP</th>
                    <th>Nama Sertifikasi / Sanad</th>
                    <th>Lembaga Penguji / Penerbit</th>
                    <th style="text-align: center;">Nomor Sertifikat</th>
                    <th style="width: 110px; text-align: center;">Status Verifikasi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($certifiedStaff as $cs)
                    <tr>
                      <td>
                        <b>{{ $cs['name'] }}</b>
                        <div style="font-size: 11px; color: #718096;">NIP: {{ $cs['nip'] ?? '-' }}</div>
                      </td>
                      <td>
                        <b style="color: #1a365d;">{{ $cs['title'] }}</b>
                        <div style="font-size: 11px; color: #4a5568;">{{ $cs['category_label'] }}</div>
                      </td>
                      <td><b>{{ $cs['organizer'] }}</b></td>
                      <td class="text-center">
                        <code style="font-size: 12px; font-weight: bold;">{{ $cs['certificate_number'] }}</code>
                      </td>
                      <td class="text-center">
                        <span class="label label-success"><i class="fa fa-check-circle"></i> Terverifikasi</span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-muted" style="padding: 20px;">Belum ada sertifikat yang tercatat.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 3: ROADMAP PENGEMBANGAN KARIR GURU -->
        <div class="tab-pane {{ $activeTab === 'rencana' ? 'active' : '' }}" id="tab_rencana">
          <div class="row">
            
            <div class="col-md-7">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-road text-primary"></i> Roadmap Peningkatan Kualifikasi Guru Alia (2026 - 2028)</h3>
                </div>
                <div class="box-body" style="font-size: 13px;">
                  <ul class="timeline">
                    <li class="time-label">
                      <span class="bg-navy">Tahun 2026 (Fase Fondasi Mutu)</span>
                    </li>
                    <li>
                      <i class="fa fa-graduation-cap bg-blue"></i>
                      <div class="timeline-item">
                        <h3 class="timeline-header"><b>Sertifikasi Pendidik 100% GTY</b></h3>
                        <div class="timeline-body">
                          Pemberangkatan seluruh Guru Tetap Yayasan (GTY) ke program Pendidikan Profesi Guru (PPG) Dalam Jabatan Kemdikbudristek.
                        </div>
                      </div>
                    </li>
                    <li>
                      <i class="fa fa-book bg-green"></i>
                      <div class="timeline-item">
                        <h3 class="timeline-header"><b>Standardisasi Sanad Al-Qur'an 30 Juz</b></h3>
                        <div class="timeline-body">
                          Daurah sanad Matan Jazariyah untuk seluruh asatidz halaqah tahfidz putri dan putra demi menjaga keaslian bacaan tartil.
                        </div>
                      </div>
                    </li>

                    <li class="time-label">
                      <span class="bg-purple">Tahun 2027 (Fase Transformasi Digital)</span>
                    </li>
                    <li>
                      <i class="fa fa-laptop bg-purple"></i>
                      <div class="timeline-item">
                        <h3 class="timeline-header"><b>Google Certified Educator & AI Pedagogi</b></h3>
                        <div class="timeline-body">
                          Pelatihan penerapan Google Workspace for Education Level 1 & 2 serta pemanfaatan asisten pembelajaran AI adaptif.
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-md-5">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-info-circle text-info"></i> Kebijakan Beasiswa Yayasan</h3>
                </div>
                <div class="box-body" style="font-size: 13px; line-height: 1.8;">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check text-green"></i> Yayasan mengalokasikan <b>5% dari total anggaran operasional</b> untuk dana pengembangan SDM guru.</li>
                    <li><i class="fa fa-check text-green"></i> Guru yang mengikuti sertifikasi berhak mendapatkan penyesuaian tunjangan fungsional pasca kelulusan.</li>
                    <li><i class="fa fa-check text-green"></i> Peserta diklat wajib mengimbaskan ilmu (*diseminasi materi*) kepada rekan guru dalam forum MGMP sekolah.</li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

  </section>
</div>

<!-- Modal Daftarkan Pelatihan Baru -->
<div class="modal fade" id="modal-add-training" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.pelatihan.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-graduation-cap"></i> Pendaftaran Program Pelatihan & Diklat Guru Baru</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Pegawai Peserta <span class="text-danger">*</span></label>
                <select name="teacher_id" class="form-control" required>
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['position'] }})</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Judul Kegiatan / Nama Pelatihan <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Workshop Pembelajaran Berdiferensiasi Kurikulum Merdeka" required>
              </div>

              <div class="form-group">
                <label>Kategori Program <span class="text-danger">*</span></label>
                <select name="category" class="form-control" required>
                  <option value="sertifikasi">Sertifikasi Profesi Pendidik (PPG)</option>
                  <option value="workshop">Workshop & Sanad Tahfidz Quran</option>
                  <option value="kurikulum">Bimtek Kurikulum & Asesmen</option>
                  <option value="inhouse">In-House Training (IHT) Yayasan</option>
                  <option value="seminar">Seminar & Lokakarya Ilmiah</option>
                </select>
              </div>

              <div class="form-group">
                <label>Lembaga Penyelenggara <span class="text-danger">*</span></label>
                <input type="text" name="organizer" class="form-control" placeholder="Contoh: Balai Diklat Kemdikbudristek / LPTQ" required>
              </div>

              <div class="form-group">
                <label>Tempat / Lokasi Pelaksanaan <span class="text-danger">*</span></label>
                <input type="text" name="location" class="form-control" placeholder="Contoh: Hotel Balairung Jakarta / Daring Zoom" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="2026-11-20" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="2026-11-22" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Durasi Jam Pelajaran (JP) <span class="text-danger">*</span></label>
                    <input type="number" name="duration_hours" class="form-control" value="24" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Biaya Pelatihan (Rp)</label>
                    <input type="number" name="cost" class="form-control" value="500000">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Sumber Pembiayaan <span class="text-danger">*</span></label>
                <input type="text" name="cost_source" class="form-control" value="Anggaran Yayasan Alia (HRD)" required>
              </div>

              <div class="form-group">
                <label>Deskripsi Materi Pelatihan <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="2" placeholder="Uraikan fokus materi pelatihan..." required></textarea>
              </div>

              <div class="form-group">
                <label>Target Output / Luaran Yang Dihasilkan <span class="text-danger">*</span></label>
                <input type="text" name="output_target" class="form-control" placeholder="Contoh: Sertifikat kompetensi & modul ajar sekolah" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Daftarkan Pelatihan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
