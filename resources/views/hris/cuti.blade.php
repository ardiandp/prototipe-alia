@extends('layouts.app')

@section('title', 'Pengajuan Cuti & Izin Pegawai')
@section('page_title', 'Pengajuan Cuti & Izin Pegawai (HRIS)')
@section('page_description', 'Manajemen Permohonan Cuti, Alur Persetujuan, dan Saldo Hak Cuti Tahunan')

@section('breadcrumbs')
  <li>SDM / HRIS</li>
  <li class="active">Pengajuan Cuti</li>
@endsection

@section('content')

    <!-- Callout Prototipe -->
    <div class="callout callout-info" style="border-left-color: #6b46c1;">
      <h4><i class="fa fa-envelope-open-o"></i> Alur Kerja Pengajuan & Persetujuan Cuti Institusi</h4>
      <p>Modul cuti mendukung berbagai kategori: <b>Cuti Tahunan (12 hari/tahun)</b>, <b>Cuti Sakit Rawat Jalan/Inap</b>, <b>Cuti Bersalin (3 bulan)</b>, <b>Cuti Ibadah Umroh/Haji</b>, dan <b>Izin Tugas Kedinasan (PPG/Diklat)</b>, dilengkapi pendelegasian guru pengganti (*badal mengajar*) dan surat izin resmi berkop Alia Islamic School.</p>
    </div>

    <!-- 4 Info Box Status Cuti -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-files-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Permohonan</span>
            <span class="info-box-number">{{ $totalPengajuan }} <small style="font-size: 13px; font-weight: normal;">Berkas</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-navy" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Tahun Ajaran 2026/2027</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Menunggu Approval</span>
            <span class="info-box-number text-yellow">{{ $totalDiajukan }} <small style="font-size: 13px; font-weight: normal;">Permohonan</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-yellow" style="width: {{ $totalPengajuan > 0 ? ($totalDiajukan/$totalPengajuan)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Perlu Verifikasi Pimpinan</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Telah Disetujui</span>
            <span class="info-box-number text-green">{{ $totalDisetujui }} <small style="font-size: 13px; font-weight: normal;">Cuti</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-green" style="width: {{ $totalPengajuan > 0 ? ($totalDisetujui/$totalPengajuan)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Surat Izin Resmi Terbit</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-times-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Permohonan Ditolak</span>
            <span class="info-box-number text-red">{{ $totalDitolak }} <small style="font-size: 13px; font-weight: normal;">Berkas</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-red" style="width: {{ $totalPengajuan > 0 ? ($totalDitolak/$totalPengajuan)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Jadwal Bentrok / Kuota Habis</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
          <a href="{{ route('hris.cuti', ['tab' => 'daftar', 'status' => $statusFilter, 'type' => $typeFilter, 'unit' => $unitFilter, 'search' => $search]) }}">
            <i class="fa fa-list text-primary"></i> <b>Daftar Permohonan Cuti</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredLeaves) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'saldo' ? 'active' : '' }}">
          <a href="{{ route('hris.cuti', ['tab' => 'saldo']) }}">
            <i class="fa fa-pie-chart text-green"></i> <b>Monitoring Saldo Cuti Pegawai</b>
          </a>
        </li>
        <li class="pull-right header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-leave">
            <i class="fa fa-plus"></i> Ajukan Permohonan Cuti Baru
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Rekap
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: DAFTAR PERMOHONAN CUTI -->
        <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
          
          <!-- Filter Saringan -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.cuti') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="daftar">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Status Approval:</label>
                <select name="status" class="form-control input-sm">
                  <option value="all">-- Semua Status --</option>
                  <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Menunggu Verifikasi (Diajukan)</option>
                  <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                  <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Kategori Cuti:</label>
                <select name="type" class="form-control input-sm">
                  <option value="all">-- Semua Kategori --</option>
                  <option value="tahunan" {{ $typeFilter === 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                  <option value="sakit" {{ $typeFilter === 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                  <option value="melahirkan" {{ $typeFilter === 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                  <option value="umroh_haji" {{ $typeFilter === 'umroh_haji' ? 'selected' : '' }}>Cuti Umroh / Haji</option>
                  <option value="dinas" {{ $typeFilter === 'dinas' ? 'selected' : '' }}>Izin Tugas Kedinasan</option>
                  <option value="penting" {{ $typeFilter === 'penting' ? 'selected' : '' }}>Alasan Penting</option>
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
                <input type="text" name="search" class="form-control input-sm" placeholder="No Surat, Nama, Alasan..." value="{{ $search }}" style="width: 170px;">
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.cuti') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Daftar Cuti -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>Nomor Surat & Tanggal</th>
                  <th>Nama Pegawai Pemohon</th>
                  <th>Kategori Cuti</th>
                  <th style="text-align: center;">Rentang Tanggal</th>
                  <th style="text-align: center;">Durasi</th>
                  <th>Guru Pengganti (Badal)</th>
                  <th style="text-align: center;">Status Approval</th>
                  <th style="width: 130px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredLeaves as $index => $leave)
                  @php
                    $uName = 'Yayasan';
                    foreach($units as $u) {
                      if ($u['id'] === ($leave['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <b style="font-family: monospace; font-size: 13px; color: #1a365d;">{{ $leave['leave_number'] }}</b>
                      <div style="font-size: 11px; color: #718096;"><i class="fa fa-tag"></i> ID: <code>{{ $leave['id'] }}</code></div>
                    </td>
                    <td style="vertical-align: middle;">
                      <b style="font-size: 13px; color: #2d3748;">{{ $leave['name'] }}</b>
                      <div style="font-size: 11px; color: #718096;">
                        <span>NIP: {{ $leave['nip'] ?? '-' }}</span> &bull; <span>{{ $uName }}</span>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      @if(($leave['type'] ?? '') === 'tahunan')
                        <span class="label label-primary">{{ $leave['type_label'] }}</span>
                      @elseif(($leave['type'] ?? '') === 'sakit')
                        <span class="label label-purple" style="background-color: #6b46c1;">{{ $leave['type_label'] }}</span>
                      @elseif(($leave['type'] ?? '') === 'melahirkan')
                        <span class="label label-info">{{ $leave['type_label'] }}</span>
                      @elseif(($leave['type'] ?? '') === 'umroh_haji')
                        <span class="label label-success">{{ $leave['type_label'] }}</span>
                      @elseif(($leave['type'] ?? '') === 'dinas')
                        <span class="label label-warning">{{ $leave['type_label'] }}</span>
                      @else
                        <span class="label label-default">{{ $leave['type_label'] }}</span>
                      @endif
                      <div style="font-size: 11px; color: #4a5568; margin-top: 3px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $leave['reason'] }}
                      </div>
                    </td>
                    <td class="text-center" style="vertical-align: middle; font-size: 12px;">
                      <b>{{ date('d/m/Y', strtotime($leave['start_date'])) }}</b><br>
                      <small class="text-muted">s/d {{ date('d/m/Y', strtotime($leave['end_date'])) }}</small>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <span class="badge bg-blue" style="font-size: 12px;">{{ $leave['duration_days'] }} Hari</span>
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                      @if(!empty($leave['substitute_teacher_name']) && $leave['substitute_teacher_name'] !== '-')
                        <i class="fa fa-user text-green"></i> <b>{{ $leave['substitute_teacher_name'] }}</b>
                      @else
                        <span class="text-muted"><i class="fa fa-minus"></i> Tidak ada badal</span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(($leave['status'] ?? '') === 'disetujui')
                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check"></i> Disetujui
                        </span>
                      @elseif(($leave['status'] ?? '') === 'diajukan')
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
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-leave-{{ $leave['id'] }}" title="Lihat Berkas & SK Cuti">
                          <i class="fa fa-file-text-o text-primary"></i>
                        </button>

                        @if(($leave['status'] ?? '') === 'diajukan')
                          <button type="button" class="btn btn-default btn-xs text-green" data-toggle="modal" data-target="#modal-approve-leave-{{ $leave['id'] }}" title="Proses Persetujuan">
                            <i class="fa fa-check-square-o"></i>
                          </button>
                        @endif

                        <a href="{{ route('hris.cuti.destroy', $leave['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Batalkan permohonan cuti {{ $leave['leave_number'] }}?')" title="Batalkan / Hapus">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal SK / Surat Izin Cuti Resmi -->
                  <div class="modal fade" id="modal-view-leave-{{ $leave['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-envelope-o"></i> Surat Permohonan & Keterangan Cuti Pegawai</h4>
                        </div>
                        <div class="modal-body" style="background: #fff; padding: 25px;">
                          
                          <!-- KOP SURAT RESMI ALIA -->
                          <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                            <h3 style="margin: 0; color: #1a365d; font-weight: bold; font-size: 18px;">YAYASAN PENDIDIKAN ISLAM ALIA</h3>
                            <h4 style="margin: 3px 0; color: #2b6cb0; font-weight: bold; font-size: 15px;">ALIA ISLAMIC SCHOOL JAKARTA</h4>
                            <p style="margin: 0; font-size: 11px; color: #718096;">
                              Kampus Asrama Terpadu: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8690-1234
                            </p>
                          </div>

                          <div class="text-center" style="margin-bottom: 15px;">
                            <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 3px; color: #1a365d;">
                              SURAT IZIN CUTI PEGAWAI
                            </h4>
                            <small class="text-muted">Nomor Registrasi: <b>{{ $leave['leave_number'] }}</b></small>
                          </div>

                          <table class="table table-bordered table-striped" style="font-size: 12px;">
                            <tr>
                              <th style="width: 35%; background: #f9f9f9;">Nama Pegawai</th>
                              <td><b>{{ $leave['name'] }}</b></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">NIP / ID Sistem</th>
                              <td>{{ $leave['nip'] ?? '-' }} / <code>{{ $leave['id'] }}</code></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Penempatan Unit</th>
                              <td>{{ $uName }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Jenis Permohonan</th>
                              <td><b>{{ $leave['type_label'] }}</b></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Jangka Waktu Cuti</th>
                              <td>
                                {{ date('d F Y', strtotime($leave['start_date'])) }} s/d {{ date('d F Y', strtotime($leave['end_date'])) }}
                                <span class="badge bg-blue" style="margin-left: 5px;">{{ $leave['duration_days'] }} Hari Kalender</span>
                              </td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Alasan Pengajuan</th>
                              <td>{{ $leave['reason'] }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Pendidik Pengganti (Badal)</th>
                              <td>{{ $leave['substitute_teacher_name'] ?: 'Tidak ada tugas mengajar yang ditinggalkan.' }}</td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Lampiran Dokumen</th>
                              <td><i class="fa fa-file-pdf-o text-danger"></i> <code>{{ $leave['attachment'] ?? 'dokumen_terlampir.pdf' }}</code></td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Status Persetujuan</th>
                              <td>
                                @if(($leave['status'] ?? '') === 'disetujui')
                                  <span class="label label-success"><i class="fa fa-check"></i> Disetujui Pimpinan</span>
                                  <div style="font-size: 11px; color: #718096; margin-top: 3px;">
                                    Oleh: <b>{{ $leave['approved_by'] }}</b> ({{ $leave['approved_at'] }})
                                  </div>
                                @elseif(($leave['status'] ?? '') === 'diajukan')
                                  <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu Verifikasi Atasan</span>
                                @else
                                  <span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <th style="background: #f9f9f9;">Catatan Pimpinan</th>
                              <td><i>"{{ $leave['approver_notes'] ?? '-' }}"</i></td>
                            </tr>
                          </table>

                          <div class="row" style="margin-top: 25px;">
                            <div class="col-xs-6 text-center">
                              <small>Pemohon Cuti,</small><br><br><br>
                              <b><u>{{ $leave['name'] }}</u></b><br>
                              <small class="text-muted">NIP: {{ $leave['nip'] ?? '-' }}</small>
                            </div>
                            <div class="col-xs-6 text-center">
                              <small>Mengetahui & Menyetujui,</small><br><br><br>
                              <b><u>{{ $leave['approved_by'] ?: 'Drs. H. Mulyadi, M.Pd.' }}</u></b><br>
                              <small class="text-muted">{{ $leave['approver_role'] ?? 'Kepala Sekolah' }}</small>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Surat Izin Cuti</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Approval Cuti -->
                  <div class="modal fade" id="modal-approve-leave-{{ $leave['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-sm" role="document">
                      <div class="modal-content">
                        <form action="{{ route('hris.cuti.approve', $leave['id']) }}" method="POST">
                          @csrf
                          <div class="modal-header" style="background-color: #2b6cb0; color: #fff;">
                            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-check-square"></i> Keputusan Permohonan</h4>
                          </div>
                          <div class="modal-body">
                            <p>Tindakan untuk permohonan cuti pemohon <b>{{ $leave['name'] }}</b> ({{ $leave['leave_number'] }}):</p>
                            
                            <div class="form-group">
                              <label>Status Keputusan <span class="text-danger">*</span></label>
                              <select name="status" class="form-control" required>
                                <option value="disetujui">Setujui Permohonan Cuti</option>
                                <option value="ditolak">Tolak Permohonan</option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label>Catatan / Arahan Pimpinan</label>
                              <textarea name="approver_notes" class="form-control" rows="2" placeholder="Contoh: Disetujui penuh, tugas diserahkan badal."></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan Keputusan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                @empty
                  <tr>
                    <td colspan="9" class="text-center" style="padding: 30px;">
                      <i class="fa fa-folder-open-o text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data pengajuan cuti yang sesuai dengan kriteria saringan.</p>
                      <a href="{{ route('hris.cuti') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: MONITORING SALDO CUTI PEGAWAI -->
        <div class="tab-pane {{ $activeTab === 'saldo' ? 'active' : '' }}" id="tab_saldo">
          <div class="row">
            
            <div class="col-md-8">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-table text-primary"></i> Buku Saldo Hak Cuti Tahunan Pegawai (Kuota: 12 Hari/Tahun)</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Nama Pegawai & NIP</th>
                        <th>Status Kerja</th>
                        <th style="width: 80px; text-align: center;">Hak Kuota</th>
                        <th style="width: 80px; text-align: center;">Terpakai</th>
                        <th style="width: 80px; text-align: center;">Sisa Cuti</th>
                        <th style="width: 140px;">Status Kuota</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($leaveBalances as $b)
                        @php
                          $pctRemaining = ($b['remaining'] / $b['quota']) * 100;
                        @endphp
                        <tr>
                          <td>
                            <b>{{ $b['teacher']['name'] }}</b>
                            <div style="font-size: 11px; color: #718096;">NIP: {{ $b['teacher']['nip'] ?? '-' }}</div>
                          </td>
                          <td><span class="label label-default" style="font-size: 10px;">{{ $b['teacher']['employment_status'] }}</span></td>
                          <td class="text-center"><b>{{ $b['quota'] }} Hari</b></td>
                          <td class="text-center text-red"><b>{{ $b['used'] }} Hari</b></td>
                          <td class="text-center text-green"><b style="font-size: 13px;">{{ $b['remaining'] }} Hari</b></td>
                          <td>
                            <div class="progress progress-xs" style="margin-top: 5px; margin-bottom: 2px;">
                              <div class="progress-bar progress-bar-success" style="width: {{ $pctRemaining }}%"></div>
                            </div>
                            <small class="text-muted">{{ round($pctRemaining) }}% kuota tersedia</small>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-info-circle text-info"></i> Ketentuan Cuti Yayasan Alia</h3>
                </div>
                <div class="box-body" style="font-size: 13px; line-height: 1.8;">
                  <ul class="list-unstyled">
                    <li><i class="fa fa-check text-green"></i> <b>Cuti Tahunan:</b> 12 hari kerja per tahun kalender berjalan.</li>
                    <li><i class="fa fa-check text-green"></i> <b>Cuti Sakit:</b> Diberikan sesuai surat rujukan dokter/klinik.</li>
                    <li><i class="fa fa-check text-green"></i> <b>Cuti Melahirkan:</b> 3 bulan (1.5 bulan pra dan 1.5 bulan pasca melahirkan).</li>
                    <li><i class="fa fa-check text-green"></i> <b>Cuti Ibadah Umroh:</b> Maksimal 14 hari dengan penetapan guru badal.</li>
                    <li><i class="fa fa-check text-green"></i> <b>Batas Pengajuan:</b> Diajukan minimal 3 hari sebelum tanggal cuti dimulai.</li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

<!-- Modal Ajukan Cuti Baru -->
<div class="modal fade" id="modal-add-leave" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.cuti.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Formulir Pengajuan Permohonan Cuti Pegawai</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Pegawai Pemohon <span class="text-danger">*</span></label>
                <select name="teacher_id" class="form-control" required>
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['position'] }})</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Kategori Jenis Cuti <span class="text-danger">*</span></label>
                <select name="type" class="form-control" required>
                  <option value="tahunan">Cuti Tahunan (Reguler 12 Hari)</option>
                  <option value="sakit">Cuti Sakit (Rujukan Klinik/RS)</option>
                  <option value="melahirkan">Cuti Bersalin / Melahirkan (3 Bulan)</option>
                  <option value="umroh_haji">Cuti Ibadah Umroh / Haji</option>
                  <option value="dinas">Izin Tugas Kedinasan (PPG / Diklat)</option>
                  <option value="penting">Cuti Alasan Penting / Keluarga</option>
                </select>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="2026-11-16" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="2026-11-18" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Guru / Pegawai Badal Pengganti</label>
                <select name="substitute_teacher_id" class="form-control">
                  <option value="">-- Tanpa Badal / Tidak Mengajar --</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['subject'] }})</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Lampiran Berkas Pendukung (Surat Dokter/Tugas/Tiket)</label>
                <input type="text" name="attachment" class="form-control" placeholder="Contoh: surat_keterangan_dokter.pdf">
              </div>

              <div class="form-group">
                <label>Alasan & Keperluan Lengkap Cuti <span class="text-danger">*</span></label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Tuliskan alasan permohonan cuti secara rinci dan jelas..." required></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirimkan Pengajuan Cuti</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

