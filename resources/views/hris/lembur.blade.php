@extends('layouts.app')

@section('title', 'Lembur Pegawai & SPKL')
@section('page_title', 'Manajemen Lembur & SPKL (HRIS)')
@section('page_description', 'Surat Perintah Kerja Lembur, Jam Kegiatan Tambahan, & Kompensasi Honor Lembur')
@section('breadcrumbs')
  <li>SDM / HRIS</li>
      <li class="active">Lembur & SPKL</li>
@endsection

@section('content')

    <h1>
      Manajemen Lembur & SPKL (HRIS)
      <small>Surat Perintah Kerja Lembur, Jam Kegiatan Tambahan, & Kompensasi Honor Lembur</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>SDM / HRIS</li>
      <li class="active">Lembur & SPKL</li>
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
      <h4><i class="fa fa-briefcase"></i> Regulasi Surat Perintah Kerja Lembur (SPKL)</h4>
      <p>Kegiatan lembur diperuntukkan bagi agenda di luar jam kerja efektif (setelah pukul 15:30 WIB atau akhir pekan), seperti: <b>Piket Malam Asrama</b>, <b>Maintenance Lab Komputer & Server Ujian</b>, <b>Klinik Bimbingan OSN</b>, dan <b>Kepanitiaan PPDB</b>. Kompensasi honor dihitung per jam secara transparan.</p>
    </div>

    <!-- 4 Info Box Ringkasan Lembur -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-file-text-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total SPKL Terbit</span>
            <span class="info-box-number">{{ $totalSPKL }} <small style="font-size: 13px; font-weight: normal;">Surat</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-navy" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Bulan Berjalan (November)</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-clock-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Jam Lembur</span>
            <span class="info-box-number text-blue">{{ $totalHours }} <small style="font-size: 13px; font-weight: normal;">Jam</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-blue" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Akumulasi Seluruh Unit</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Kompensasi Honor</span>
            <span class="info-box-number text-green" style="font-size: 16px;">Rp {{ number_format($totalCompensation, 0, ',', '.') }}</span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-green" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Estimasi Beban Anggaran</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-hourglass-half"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Menunggu Approval</span>
            <span class="info-box-number text-yellow">{{ $totalDiajukan }} <small style="font-size: 13px; font-weight: normal;">SPKL</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-yellow" style="width: {{ $totalSPKL > 0 ? ($totalDiajukan/$totalSPKL)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Perlu Verifikasi Kepala Unit</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
          <a href="{{ route('hris.lembur', ['tab' => 'daftar', 'status' => $statusFilter, 'category' => $categoryFilter, 'unit' => $unitFilter, 'search' => $search]) }}">
            <i class="fa fa-list text-primary"></i> <b>Daftar Surat Perintah Kerja Lembur (SPKL)</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredOvertimes) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'rekap' ? 'active' : '' }}">
          <a href="{{ route('hris.lembur', ['tab' => 'rekap']) }}">
            <i class="fa fa-users text-green"></i> <b>Rekapitulasi Jam & Honor per Pegawai</b>
          </a>
        </li>
        <li class="{{ $activeTab === 'tarif' ? 'active' : '' }}">
          <a href="{{ route('hris.lembur', ['tab' => 'tarif']) }}">
            <i class="fa fa-tag text-purple"></i> <b>Ketentuan Tarif Lembur Yayasan</b>
          </a>
        </li>
        <li class="pull-right header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-overtime">
            <i class="fa fa-plus"></i> Terbitkan SPKL Baru
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Rekap SPKL
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: DAFTAR SPKL -->
        <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
          
          <!-- Filter Saringan -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.lembur') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="daftar">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Status Approval:</label>
                <select name="status" class="form-control input-sm">
                  <option value="all">-- Semua Status --</option>
                  <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Diajukan (Menunggu Review)</option>
                  <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui Pimpinan</option>
                  <option value="dibayarkan" {{ $statusFilter === 'dibayarkan' ? 'selected' : '' }}>Telah Dibayarkan</option>
                  <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Kategori Aktivitas:</label>
                <select name="category" class="form-control input-sm">
                  <option value="all">-- Semua Kategori --</option>
                  <option value="it_lab" {{ $categoryFilter === 'it_lab' ? 'selected' : '' }}>Lab IT & Jaringan</option>
                  <option value="asrama" {{ $categoryFilter === 'asrama' ? 'selected' : '' }}>Piket Malam Asrama</option>
                  <option value="ppdb" {{ $categoryFilter === 'ppdb' ? 'selected' : '' }}>Panitia PPDB</option>
                  <option value="ujian" {{ $categoryFilter === 'ujian' ? 'selected' : '' }}>Bimbingan & Ujian</option>
                  <option value="acara" {{ $categoryFilter === 'acara' ? 'selected' : '' }}>Event & Kesiswaan</option>
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
                <input type="text" name="search" class="form-control input-sm" placeholder="No SPKL, Nama, Tugas..." value="{{ $search }}" style="width: 170px;">
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.lembur') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Daftar SPKL -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>Nomor SPKL & Tanggal</th>
                  <th>Nama Pegawai Bertugas</th>
                  <th>Kategori Kegiatan</th>
                  <th style="text-align: center;">Jam Kerja Lembur</th>
                  <th style="text-align: center;">Durasi</th>
                  <th style="text-align: right;">Estimasi Honor</th>
                  <th style="text-align: center;">Status Approval</th>
                  <th style="width: 130px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredOvertimes as $index => $ovt)
                  @php
                    $uName = 'Yayasan';
                    foreach($units as $u) {
                      if ($u['id'] === ($ovt['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <b style="font-family: monospace; font-size: 13px; color: #1a365d;">{{ $ovt['spkl_number'] }}</b>
                      <div style="font-size: 11px; color: #718096;">
                        <i class="fa fa-calendar"></i> {{ date('d F Y', strtotime($ovt['date'])) }} &bull; <code>{{ $ovt['id'] }}</code>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      <b style="font-size: 13px; color: #2d3748;">{{ $ovt['name'] }}</b>
                      <div style="font-size: 11px; color: #718096;">
                        <span>NIP: {{ $ovt['nip'] ?? '-' }}</span> &bull; <span>{{ $uName }}</span>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      @if(($ovt['activity_category'] ?? '') === 'it_lab')
                        <span class="label label-primary"><i class="fa fa-laptop"></i> {{ $ovt['activity_category_label'] }}</span>
                      @elseif(($ovt['activity_category'] ?? '') === 'asrama')
                        <span class="label label-purple" style="background-color: #6b46c1;"><i class="fa fa-moon-o"></i> {{ $ovt['activity_category_label'] }}</span>
                      @elseif(($ovt['activity_category'] ?? '') === 'ppdb')
                        <span class="label label-info"><i class="fa fa-id-card-o"></i> {{ $ovt['activity_category_label'] }}</span>
                      @elseif(($ovt['activity_category'] ?? '') === 'ujian')
                        <span class="label label-warning"><i class="fa fa-pencil-square-o"></i> {{ $ovt['activity_category_label'] }}</span>
                      @else
                        <span class="label label-default"><i class="fa fa-tasks"></i> {{ $ovt['activity_category_label'] }}</span>
                      @endif
                      <div style="font-size: 11px; color: #4a5568; margin-top: 3px; max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $ovt['task_description'] }}
                      </div>
                    </td>
                    <td class="text-center" style="vertical-align: middle; font-family: monospace; font-size: 12px;">
                      <b>{{ $ovt['start_time'] }} - {{ $ovt['end_time'] }}</b> WIB
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <span class="badge bg-blue" style="font-size: 12px;">{{ $ovt['duration_hours'] }} Jam</span>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                      <b style="color: #276749; font-size: 13px;">Rp {{ number_format($ovt['compensation_amount'], 0, ',', '.') }}</b>
                      <div style="font-size: 10px; color: #718096;">@ Rp {{ number_format($ovt['hourly_rate'], 0, ',', '.') }}/jam</div>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(($ovt['status'] ?? '') === 'dibayarkan')
                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check-circle"></i> Dibayarkan
                        </span>
                      @elseif(($ovt['status'] ?? '') === 'disetujui')
                        <span class="label label-primary" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check"></i> Disetujui
                        </span>
                      @elseif(($ovt['status'] ?? '') === 'diajukan')
                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-clock-o"></i> Diajukan
                        </span>
                      @else
                        <span class="label label-danger" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-times"></i> Ditolak
                        </span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-spkl-{{ $ovt['id'] }}" title="Lihat Fisik SPKL Resmi">
                          <i class="fa fa-file-text-o text-primary"></i>
                        </button>

                        @if(($ovt['status'] ?? '') === 'diajukan' || ($ovt['status'] ?? '') === 'disetujui')
                          <button type="button" class="btn btn-default btn-xs text-green" data-toggle="modal" data-target="#modal-approve-spkl-{{ $ovt['id'] }}" title="Approval & Pembayaran">
                            <i class="fa fa-check-square-o"></i>
                          </button>
                        @endif

                        <a href="{{ route('hris.lembur.destroy', $ovt['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus surat perintah lembur {{ $ovt['spkl_number'] }}?')" title="Batalkan / Hapus">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center" style="padding: 30px;">
                      <i class="fa fa-folder-open-o text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data surat perintah kerja lembur yang sesuai dengan kriteria saringan.</p>
                      <a href="{{ route('hris.lembur') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: REKAPITULASI JAM & HONOR PER PEGAWAI -->
        <div class="tab-pane {{ $activeTab === 'rekap' ? 'active' : '' }}" id="tab_rekap">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users text-primary"></i> Rekapitulasi Jam Lembur & Hak Kompensasi per Pegawai</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background: #f7fafc;">
                    <th>Nama Pegawai & NIP</th>
                    <th>Jabatan & Unit Penempatan</th>
                    <th style="width: 100px; text-align: center;">Frekuensi SPKL</th>
                    <th style="width: 120px; text-align: center;">Total Jam Lembur</th>
                    <th style="width: 180px; text-align: right;">Total Hak Honorarium</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($staffOvertimeSummary as $sum)
                    <tr>
                      <td>
                        <b>{{ $sum['teacher']['name'] }}</b>
                        <div style="font-size: 11px; color: #718096;">NIP: {{ $sum['teacher']['nip'] ?? '-' }}</div>
                      </td>
                      <td>
                        <span class="label label-default" style="font-size: 11px;">{{ $sum['teacher']['position'] }}</span>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-blue">{{ $sum['count'] }} Kali</span>
                      </td>
                      <td class="text-center">
                        <b>{{ $sum['hours'] }} Jam</b>
                      </td>
                      <td class="text-right">
                        <b style="color: #276749; font-size: 14px;">Rp {{ number_format($sum['compensation'], 0, ',', '.') }}</b>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-muted" style="padding: 20px;">Belum ada data lembur yang tercatat.</td>
                    </tr>
                  @endforelse
                </tbody>
                <tfoot>
                  <tr style="background: #ebf8ff; font-weight: bold;">
                    <td colspan="3" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-center">{{ $totalHours }} Jam</td>
                    <td class="text-right" style="color: #276749; font-size: 15px;">Rp {{ number_format($totalCompensation, 0, ',', '.') }}</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 3: KETENTUAN TARIF LEMBUR -->
        <div class="tab-pane {{ $activeTab === 'tarif' ? 'active' : '' }}" id="tab_tarif">
          <div class="row">
            
            <div class="col-md-6">
              <div class="box box-solid box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-money"></i> Standar Tarif Honor Lembur Yayasan Alia</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Kategori Waktu & Penugasan</th>
                        <th style="text-align: right;">Tarif per Jam</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b>Lembur Reguler Sore (Senin - Jum'at)</b></td>
                        <td class="text-right text-green"><b>Rp 35.000</b></td>
                        <td>Mulai pukul 15:30 s/d 20:00 WIB</td>
                      </tr>
                      <tr>
                        <td><b>Lembur Malam / Piket Asrama</b></td>
                        <td class="text-right text-green"><b>Rp 40.000</b></td>
                        <td>Pukul 20:00 s/d 05:00 WIB (termasuk konsumsi)</td>
                      </tr>
                      <tr>
                        <td><b>Lembur Hari Libur / Akhir Pekan (Sabtu/Ahad)</b></td>
                        <td class="text-right text-green"><b>Rp 45.000</b></td>
                        <td>Kepanitiaan event, seleksi PPDB, atau turnamen</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-shield text-info"></i> Syarat & Ketentuan SPKL</h3>
                </div>
                <div class="box-body" style="font-size: 13px; line-height: 1.8;">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check text-green"></i> SPKL wajib diterbitkan sebelum kegiatan lembur dimulai.</li>
                    <li><i class="fa fa-check text-green"></i> Lembur dihitung minimal 1 jam efektif pelaksanaan tugas.</li>
                    <li><i class="fa fa-check text-green"></i> Pembayaran honorarium lembur disalurkan bersamaan dengan slip payroll bulanan atau voucher kas operasional.</li>
                    <li><i class="fa fa-check text-green"></i> Bukti hasil pekerjaan (output lembur) dilaporkan ke atasan langsung.</li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

  

<!-- Modal Terbitkan SPKL Baru -->
<div class="modal fade" id="modal-add-overtime" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.lembur.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Penerbitan Surat Perintah Kerja Lembur (SPKL) Baru</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Pegawai Yang Ditugaskan <span class="text-danger">*</span></label>
                <select name="teacher_id" class="form-control" required>
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['position'] }})</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Kategori Kegiatan Lembur <span class="text-danger">*</span></label>
                <select name="activity_category" class="form-control" required>
                  <option value="it_lab">Instalasi & Maintenance Lab IT / CBT</option>
                  <option value="asrama">Piket Pengawasan & Pembinaan Asrama Malam</option>
                  <option value="ppdb">Kepanitiaan & Rekonsiliasi PPDB</option>
                  <option value="ujian">Klinik Bimbingan OSN & Remedial Ujian</option>
                  <option value="sarpras">Perbaikan & Pemeliharaan Sarpras Gedung</option>
                  <option value="acara">Event Sekolah & Ekstrakurikuler Sabtu/Ahad</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Pelaksanaan Lembur <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control" value="2026-11-10" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control" value="16:00" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control" value="19:30" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Tarif per Jam (Opsional - Otomatis bila kosong)</label>
                <input type="number" name="hourly_rate" class="form-control" placeholder="Contoh: 35000">
              </div>

              <div class="form-group">
                <label>Deskripsi Tugas & Output Yang Diharapkan <span class="text-danger">*</span></label>
                <textarea name="task_description" class="form-control" rows="3" placeholder="Uraikan target pekerjaan yang harus diselesaikan dalam lembur..." required></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Terbitkan SPKL</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal-modal SPKL (Ditempatkan Diluar Tabel) -->
@foreach($filteredOvertimes as $ovt)
  @php
    $uNameModal = 'Yayasan';
    foreach($units as $u) {
      if ($u['id'] === ($ovt['unit_id'] ?? '')) {
        $uNameModal = $u['name'];
        break;
      }
    }
  @endphp
  <!-- Modal Fisik Surat Perintah Kerja Lembur (SPKL) Resmi -->
  <div class="modal fade" id="modal-view-spkl-{{ $ovt['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-briefcase"></i> Surat Perintah Kerja Lembur (SPKL)</h4>
        </div>
        <div class="modal-body" style="background: #fff; padding: 25px;">
          
          <!-- KOP SURAT ALIA -->
          <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
            <h3 style="margin: 0; color: #1a365d; font-weight: bold; font-size: 18px;">YAYASAN PENDIDIKAN ISLAM ALIA</h3>
            <h4 style="margin: 3px 0; color: #2b6cb0; font-weight: bold; font-size: 15px;">ALIA ISLAMIC SCHOOL JAKARTA</h4>
            <p style="margin: 0; font-size: 11px; color: #718096;">
              Kampus Asrama Terpadu: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8690-1234
            </p>
          </div>

          <div class="text-center" style="margin-bottom: 15px;">
            <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 3px; color: #1a365d;">
              SURAT PERINTAH KERJA LEMBUR (SPKL)
            </h4>
            <small class="text-muted">Nomor Surat: <b>{{ $ovt['spkl_number'] }}</b></small>
          </div>

          <table class="table table-bordered table-striped" style="font-size: 12px;">
            <tr>
              <th style="width: 38%; background: #f9f9f9;">Nama Pegawai Yang Ditugaskan</th>
              <td><b>{{ $ovt['name'] }}</b></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">NIP / Unit Tugas</th>
              <td>{{ $ovt['nip'] ?? '-' }} / {{ $uNameModal }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Hari, Tanggal Pelaksanaan</th>
              <td><b>{{ date('l, d F Y', strtotime($ovt['date'])) }}</b></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Alokasi Jam Kerja Lembur</th>
              <td>{{ $ovt['start_time'] }} s/d {{ $ovt['end_time'] }} WIB (<b>{{ $ovt['duration_hours'] }} Jam Kerja</b>)</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Kategori Kegiatan Lembur</th>
              <td><b>{{ $ovt['activity_category_label'] }}</b></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Rincian Tugas & Output</th>
              <td>{{ $ovt['task_description'] }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Kompensasi Honor Lembur</th>
              <td>
                <b style="font-size: 14px; color: #276749;">Rp {{ number_format($ovt['compensation_amount'], 0, ',', '.') }}</b>
                <span class="text-muted">(@ Rp {{ number_format($ovt['hourly_rate'], 0, ',', '.') }} per jam)</span>
              </td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Status Pengesahan</th>
              <td>
                @if(($ovt['status'] ?? '') === 'dibayarkan')
                  <span class="label label-success"><i class="fa fa-check-circle"></i> Selesai & Telah Dibayarkan</span>
                @elseif(($ovt['status'] ?? '') === 'disetujui')
                  <span class="label label-primary"><i class="fa fa-check"></i> Disetujui Pimpinan</span>
                @elseif(($ovt['status'] ?? '') === 'diajukan')
                  <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu Verifikasi</span>
                @else
                  <span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>
                @endif
                <div style="font-size: 11px; color: #718096; margin-top: 3px;">
                  Oleh: <b>{{ $ovt['approved_by'] ?: 'Drs. H. Mulyadi, M.Pd.' }}</b>
                </div>
              </td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Catatan Pimpinan</th>
              <td><i>"{{ $ovt['approver_notes'] ?? '-' }}"</i></td>
            </tr>
          </table>

          <div class="row" style="margin-top: 25px;">
            <div class="col-xs-6 text-center">
              <small>Penerima Tugas Lembur,</small><br><br><br>
              <b><u>{{ $ovt['name'] }}</u></b><br>
              <small class="text-muted">NIP: {{ $ovt['nip'] ?? '-' }}</small>
            </div>
            <div class="col-xs-6 text-center">
              <small>Pemberi Perintah Kerja,</small><br><br><br>
              <b><u>{{ $ovt['approved_by'] ?: 'Drs. H. Mulyadi, M.Pd.' }}</u></b><br>
              <small class="text-muted">{{ $ovt['approver_role'] ?? 'Kepala Sekolah' }}</small>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Dokumen SPKL</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Approval & Bayar SPKL -->
  <div class="modal fade" id="modal-approve-spkl-{{ $ovt['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="{{ route('hris.lembur.approve', $ovt['id']) }}" method="POST">
          @csrf
          <div class="modal-header" style="background-color: #2b6cb0; color: #fff;">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-check-square"></i> Verifikasi & Approval SPKL</h4>
          </div>
          <div class="modal-body">
            <p>Tindakan untuk SPKL <b>{{ $ovt['spkl_number'] }}</b> atas nama <b>{{ $ovt['name'] }}</b>:</p>
            
            <div class="form-group">
              <label>Keputusan Approval <span class="text-danger">*</span></label>
              <select name="status" class="form-control" required>
                <option value="disetujui">Setujui Pelaksanaan Lembur</option>
                <option value="dibayarkan">Setujui & Tandai Telah Dibayarkan</option>
                <option value="ditolak">Tolak Perintah Lembur</option>
              </select>
            </div>

            <div class="form-group">
              <label>Catatan / Instruksi Tambahan</label>
              <textarea name="approver_notes" class="form-control" rows="2" placeholder="Contoh: Tugas lembur telah diverifikasi dan disetujui."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan Status</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

@endsection

