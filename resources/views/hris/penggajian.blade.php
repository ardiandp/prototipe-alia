@extends('layouts.app')

@section('title', 'Penggajian & Payroll Pegawai')

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Penggajian & Payroll Pegawai (HRIS)
      <small>Simulasi Gaji Pokok, Tunjangan, Potongan BPJS/Infaq, dan Slip Gaji Digital Resmi</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>SDM / HRIS</li>
      <li class="active">Penggajian (Payroll)</li>
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
    <div class="callout callout-info" style="border-left-color: #00a65a;">
      <h4><i class="fa fa-money"></i> Sistem Payroll & Remunerasi Syariah Alia Islamic School</h4>
      <p>Modul penggajian mengintegrasikan <b>Gaji Pokok</b>, <b>Tunjangan Jabatan & Struktural</b>, <b>Tunjangan Fungsional Pendidik</b>, <b>Tunjangan Keluarga & Makan/Transport</b>, kompensasi <b>Lembur SPKL</b>, serta potongan syariah (<b>Zakat/Infaq 2.5%</b>), <b>BPJS Kesehatan & Ketenagakerjaan</b>, dan denda presensi keterlambatan.</p>
    </div>

    <!-- 4 Info Box Ringkasan Finansial Payroll -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Slip Diterbitkan</span>
            <span class="info-box-number">{{ $totalSlips }} <small style="font-size: 13px; font-weight: normal;">Slip</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-navy" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">{{ $totalDibayarkan }} Telah Ditransfer</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-calculator"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Gaji Bruto (Kotor)</span>
            <span class="info-box-number text-blue" style="font-size: 15px;">Rp {{ number_format($totalGross, 0, ',', '.') }}</span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-blue" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Gaji Pokok + Tunjangan + Lembur</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-scissors"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Potongan</span>
            <span class="info-box-number text-red" style="font-size: 15px;">Rp {{ number_format($totalDeductions, 0, ',', '.') }}</span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-red" style="width: {{ $totalGross > 0 ? ($totalDeductions/$totalGross)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">BPJS, Infaq, Pinjaman, Presensi</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Gaji Bersih (Take Home)</span>
            <span class="info-box-number text-green" style="font-size: 16px;">Rp {{ number_format($totalNetPayroll, 0, ',', '.') }}</span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-green" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Beban Kas Penggajian Sekolah</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
          <a href="{{ route('hris.penggajian', ['tab' => 'daftar', 'month' => $monthFilter, 'unit' => $unitFilter, 'status' => $statusFilter, 'search' => $search]) }}">
            <i class="fa fa-list text-primary"></i> <b>Buku Penggajian & Slip Gaji</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredPayrolls) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'komponen' ? 'active' : '' }}">
          <a href="{{ route('hris.penggajian', ['tab' => 'komponen']) }}">
            <i class="fa fa-cubes text-green"></i> <b>Struktur Komponen Gaji & Tunjangan</b>
          </a>
        </li>
        <li class="{{ $activeTab === 'rekap' ? 'active' : '' }}">
          <a href="{{ route('hris.penggajian', ['tab' => 'rekap', 'month' => $monthFilter]) }}">
            <i class="fa fa-pie-chart text-purple"></i> <b>Pegawai Belum Diproses Payroll</b>
            @if(count($unprocessedStaff) > 0)
              <span class="label label-warning pull-right" style="margin-left: 6px;">{{ count($unprocessedStaff) }}</span>
            @endif
          </a>
        </li>
        <li class="pull-right header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-payroll">
            <i class="fa fa-plus"></i> Terbitkan Slip Gaji Baru
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Rekapitulasi Gaji
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: BUKU PENGGAJIAN & SLIP GAJI -->
        <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
          
          <!-- Filter Saringan -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.penggajian') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="daftar">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;"><i class="fa fa-calendar"></i> Periode Bulan:</label>
                <input type="month" name="month" class="form-control input-sm" value="{{ $monthFilter }}">
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Status Pembayaran:</label>
                <select name="status" class="form-control input-sm">
                  <option value="all">-- Semua Status --</option>
                  <option value="dibayarkan" {{ $statusFilter === 'dibayarkan' ? 'selected' : '' }}>Telah Ditransfer (Dibayarkan)</option>
                  <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui Bendahara</option>
                  <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
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
                <input type="text" name="search" class="form-control input-sm" placeholder="No Slip, Nama, Bank..." value="{{ $search }}" style="width: 160px;">
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.penggajian') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Buku Payroll -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>Nomor Slip & Pegawai</th>
                  <th>Jabatan & Rekening Bank</th>
                  <th style="text-align: right;">Gaji Pokok</th>
                  <th style="text-align: right;">Tunjangan & Lembur</th>
                  <th style="text-align: right;">Potongan</th>
                  <th style="text-align: right;">Gaji Bersih (THP)</th>
                  <th style="text-align: center;">Status</th>
                  <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredPayrolls as $index => $pay)
                  @php
                    $uName = 'Yayasan';
                    foreach($units as $u) {
                      if ($u['id'] === ($pay['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                    $totalAllowances = ($pay['position_allowance'] ?? 0) + ($pay['functional_allowance'] ?? 0) + ($pay['family_allowance'] ?? 0) + ($pay['transport_meal_allowance'] ?? 0) + ($pay['overtime_pay'] ?? 0) + ($pay['bonus_incentive'] ?? 0);
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <b style="font-family: monospace; font-size: 13px; color: #1a365d;">{{ $pay['slip_number'] }}</b>
                      <div style="font-size: 13px; font-weight: bold; color: #2d3748; margin-top: 2px;">{{ $pay['name'] }}</div>
                      <div style="font-size: 11px; color: #718096;">NIP: {{ $pay['nip'] ?? '-' }} &bull; {{ $pay['period_label'] }}</div>
                    </td>
                    <td style="vertical-align: middle;">
                      <span class="label label-default" style="font-size: 11px;">{{ $pay['position'] }}</span>
                      <div style="font-size: 11px; color: #4a5568; margin-top: 3px;">
                        <i class="fa fa-university text-muted"></i> <b>{{ $pay['bank_name'] }}</b><br>
                        <code>{{ $pay['account_number'] }}</code>
                      </div>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                      Rp {{ number_format($pay['basic_salary'], 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="vertical-align: middle; color: #2b6cb0;">
                      + Rp {{ number_format($totalAllowances, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="vertical-align: middle; color: #e53e3e;">
                      - Rp {{ number_format($pay['total_deductions'], 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                      <b style="font-size: 14px; color: #276749;">
                        Rp {{ number_format($pay['net_salary'], 0, ',', '.') }}
                      </b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(($pay['status'] ?? '') === 'dibayarkan')
                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check-circle"></i> Ditransfer
                        </span>
                        <div style="font-size: 10px; color: #718096; margin-top: 2px;">{{ date('d/m/Y', strtotime($pay['payment_date'])) }}</div>
                      @elseif(($pay['status'] ?? '') === 'disetujui')
                        <span class="label label-primary" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-check"></i> Disetujui
                        </span>
                      @else
                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">
                          Draft
                        </span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-slip-{{ $pay['id'] }}" title="Cetak Slip Gaji Digital">
                          <i class="fa fa-file-text-o text-primary"></i>
                        </button>

                        @if(($pay['status'] ?? '') !== 'dibayarkan')
                          <form action="{{ route('hris.penggajian.approve', $pay['id']) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-default btn-xs text-green" title="Konfirmasi Ditransfer" onclick="return confirm('Tandai slip gaji {{ $pay['name'] }} telah berhasil ditransfer?')">
                              <i class="fa fa-paper-plane"></i>
                            </button>
                          </form>
                        @endif

                        <a href="{{ route('hris.penggajian.destroy', $pay['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus slip gaji {{ $pay['slip_number'] }}?')" title="Hapus Slip">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal Slip Gaji Digital Resmi Berkop Alia -->
                  <div class="modal fade" id="modal-view-slip-{{ $pay['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-file-text"></i> Slip Gaji & Remunerasi Pegawai (Digital)</h4>
                        </div>
                        <div class="modal-body" style="background: #fff; padding: 25px;">
                          
                          <!-- KOP SLIP GAJI -->
                          <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                            <h3 style="margin: 0; color: #1a365d; font-weight: bold; font-size: 18px;">YAYASAN PENDIDIKAN ISLAM ALIA</h3>
                            <h4 style="margin: 3px 0; color: #2b6cb0; font-weight: bold; font-size: 15px;">ALIA ISLAMIC SCHOOL JAKARTA</h4>
                            <p style="margin: 0; font-size: 11px; color: #718096;">
                              Kampus Asrama Terpadu: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8690-1234
                            </p>
                          </div>

                          <div class="text-center" style="margin-bottom: 15px;">
                            <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 2px; color: #1a365d;">
                              SLIP GAJI DAN PENGHASILAN PEGAWAI
                            </h4>
                            <small class="text-muted">Nomor Registrasi: <b>{{ $pay['slip_number'] }}</b> &bull; Periode: <b>{{ $pay['period_label'] }}</b></small>
                          </div>

                          <!-- Identitas Penerima -->
                          <div class="row" style="margin-bottom: 12px; font-size: 12px;">
                            <div class="col-xs-6">
                              <div><b>Nama Lengkap:</b> {{ $pay['name'] }}</div>
                              <div><b>NIP / ID:</b> {{ $pay['nip'] ?? '-' }} / {{ $pay['teacher_id'] }}</div>
                              <div><b>Jabatan:</b> {{ $pay['position'] }}</div>
                            </div>
                            <div class="col-xs-6">
                              <div><b>Status Kerja:</b> {{ $pay['employment_status'] }}</div>
                              <div><b>Rekening Bank:</b> {{ $pay['bank_name'] }}</div>
                              <div><b>No. Rekening:</b> <code>{{ $pay['account_number'] }}</code></div>
                            </div>
                          </div>

                          <!-- Rincian Penghasilan & Potongan -->
                          <div class="row">
                            <div class="col-xs-6">
                              <div class="panel panel-default" style="margin-bottom: 10px;">
                                <div class="panel-heading" style="background: #ebf8ff; color: #2b6cb0; font-weight: bold; font-size: 12px; padding: 6px 10px;">
                                  A. PENGHASILAN (EARNINGS)
                                </div>
                                <table class="table table-condensed" style="font-size: 11px; margin-bottom: 0;">
                                  <tr>
                                    <td>Gaji Pokok</td>
                                    <td class="text-right">Rp {{ number_format($pay['basic_salary'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Tunjangan Jabatan</td>
                                    <td class="text-right">Rp {{ number_format($pay['position_allowance'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Tunjangan Fungsional</td>
                                    <td class="text-right">Rp {{ number_format($pay['functional_allowance'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Tunjangan Keluarga</td>
                                    <td class="text-right">Rp {{ number_format($pay['family_allowance'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Tunj. Makan & Transport</td>
                                    <td class="text-right">Rp {{ number_format($pay['transport_meal_allowance'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Honor Lembur (SPKL)</td>
                                    <td class="text-right">Rp {{ number_format($pay['overtime_pay'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Insentif & Bonus Prestasi</td>
                                    <td class="text-right">Rp {{ number_format($pay['bonus_incentive'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr style="font-weight: bold; background: #f7fafc;">
                                    <td>TOTAL PENGHASILAN BRUTO</td>
                                    <td class="text-right text-blue">Rp {{ number_format($pay['gross_salary'], 0, ',', '.') }}</td>
                                  </tr>
                                </table>
                              </div>
                            </div>

                            <div class="col-xs-6">
                              <div class="panel panel-default" style="margin-bottom: 10px;">
                                <div class="panel-heading" style="background: #fff5f5; color: #c53030; font-weight: bold; font-size: 12px; padding: 6px 10px;">
                                  B. POTONGAN (DEDUCTIONS)
                                </div>
                                <table class="table table-condensed" style="font-size: 11px; margin-bottom: 0;">
                                  <tr>
                                    <td>BPJS Kesehatan (1%)</td>
                                    <td class="text-right">Rp {{ number_format($pay['bpjs_kesehatan'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>BPJS Ketenagakerjaan (2%)</td>
                                    <td class="text-right">Rp {{ number_format($pay['bpjs_ketenagakerjaan'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Zakat Profesi & Infaq</td>
                                    <td class="text-right">Rp {{ number_format($pay['infaq_sedekah'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Potongan Koperasi / Kas</td>
                                    <td class="text-right">Rp {{ number_format($pay['loan_deduction'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td>Denda Keterlambatan Presensi</td>
                                    <td class="text-right">Rp {{ number_format($pay['late_deduction'], 0, ',', '.') }}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" style="color: transparent;">-</td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" style="color: transparent;">-</td>
                                  </tr>
                                  <tr style="font-weight: bold; background: #f7fafc;">
                                    <td>TOTAL POTONGAN</td>
                                    <td class="text-right text-red">Rp {{ number_format($pay['total_deductions'], 0, ',', '.') }}</td>
                                  </tr>
                                </table>
                              </div>
                            </div>
                          </div>

                          <!-- Take Home Pay Highlight -->
                          <div class="well well-sm text-center" style="background: #f0fff4; border: 2px dashed #38a169; margin-top: 10px; margin-bottom: 15px;">
                            <span style="font-size: 12px; color: #276749; text-transform: uppercase; font-weight: bold;">
                              Penghasilan Bersih Diterima (Take Home Pay):
                            </span>
                            <h3 style="margin: 5px 0 2px 0; color: #276749; font-weight: bold;">
                              Rp {{ number_format($pay['net_salary'], 0, ',', '.') }}
                            </h3>
                            <small class="text-muted">Status: <b>Telah Ditransfer ke Rekening Pegawai ({{ date('d F Y', strtotime($pay['payment_date'])) }})</b></small>
                          </div>

                          <!-- Tanda Tangan Dokumen -->
                          <div class="row" style="margin-top: 20px;">
                            <div class="col-xs-6 text-center">
                              <small>Pegawai Penerima,</small><br><br><br>
                              <b><u>{{ $pay['name'] }}</u></b><br>
                              <small class="text-muted">NIP: {{ $pay['nip'] ?? '-' }}</small>
                            </div>
                            <div class="col-xs-6 text-center">
                              <small>Bendahara Yayasan Alia,</small><br><br><br>
                              <b><u>Hj. Nurul Hidayati, S.E.</u></b><br>
                              <small class="text-muted">Bendahara Utama Sekolah</small>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Slip Gaji</button>
                        </div>
                      </div>
                    </div>
                  </div>

                @empty
                  <tr>
                    <td colspan="9" class="text-center" style="padding: 30px;">
                      <i class="fa fa-folder-open-o text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data slip penggajian yang sesuai dengan filter.</p>
                      <a href="{{ route('hris.penggajian') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: STRUKTUR KOMPONEN GAJI & TUNJANGAN -->
        <div class="tab-pane {{ $activeTab === 'komponen' ? 'active' : '' }}" id="tab_komponen">
          <div class="row">
            
            <div class="col-md-6">
              <div class="box box-solid box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-plus-circle"></i> Komponen Penerimaan & Tunjangan</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Komponen Gaji</th>
                        <th>Kategori Pegawai</th>
                        <th>Standar Nilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b>Gaji Pokok</b></td>
                        <td>GTY, Kontrak, Tendik</td>
                        <td>Rp 3.500.000 - Rp 7.500.000 (sesuai jenjang & masa kerja)</td>
                      </tr>
                      <tr>
                        <td><b>Tunjangan Jabatan</b></td>
                        <td>Pimpinan, Waka, Mudir, Kepala Lab</td>
                        <td>Rp 800.000 - Rp 3.000.000</td>
                      </tr>
                      <tr>
                        <td><b>Tunjangan Fungsional</b></td>
                        <td>Sertifikasi Pendidik / Koordinator</td>
                        <td>Rp 600.000 - Rp 1.500.000</td>
                      </tr>
                      <tr>
                        <td><b>Tunjangan Keluarga</b></td>
                        <td>Menikah & Anak Kandung (Maks. 2)</td>
                        <td>Rp 450.000 - Rp 800.000</td>
                      </tr>
                      <tr>
                        <td><b>Uang Makan & Transport</b></td>
                        <td>Seluruh Pendidik & Tendik</td>
                        <td>Rp 35.000 / hari kehadiran efektif</td>
                      </tr>
                      <tr>
                        <td><b>Kompensasi Lembur SPKL</b></td>
                        <td>Sesuai realisasi lembur disetujui</td>
                        <td>Rp 35.000 - Rp 45.000 / jam</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="box box-solid box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-minus-circle"></i> Komponen Potongan Remunerasi</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Komponen Potongan</th>
                        <th>Ketentuan Potongan</th>
                        <th>Tujuan Alokasi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b>BPJS Kesehatan</b></td>
                        <td>1% dari Gaji Bruto (Pemberi kerja 4%)</td>
                        <td>Jaminan pemeliharaan kesehatan keluarga</td>
                      </tr>
                      <tr>
                        <td><b>BPJS Ketenagakerjaan</b></td>
                        <td>2% JHT (Pemberi kerja 3.7%)</td>
                        <td>Jaminan Hari Tua & Kecelakaan Kerja</td>
                      </tr>
                      <tr>
                        <td><b>Zakat Profesi & Infaq</b></td>
                        <td>2.5% penghasilan bersih / sukarela</td>
                        <td>Pengelolaan BAZIS Yayasan Alia</td>
                      </tr>
                      <tr>
                        <td><b>Denda Keterlambatan</b></td>
                        <td>Rp 5.000 / 15 menit keterlambatan</td>
                        <td>Disiplin presensi harian pendidik</td>
                      </tr>
                      <tr>
                        <td><b>Pinjaman Koperasi</b></td>
                        <td>Sesuai tenor angsuran pinjaman pegawai</td>
                        <td>Koperasi Syariah Karyawan Alia</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB 3: PEGAWAI BELUM DIPROSES PAYROLL -->
        <div class="tab-pane {{ $activeTab === 'rekap' ? 'active' : '' }}" id="tab_rekap">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-clock-o text-yellow"></i> Daftar Pegawai Belum Diterbitkan Slip Gaji (Periode: {{ $monthFilter }})</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background: #f7fafc;">
                    <th>Nama Pegawai & NIP</th>
                    <th>Status Kepegawaian</th>
                    <th>Jabatan & Posisi</th>
                    <th>Unit Penempatan</th>
                    <th style="width: 150px; text-align: center;">Aksi Cepat</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($unprocessedStaff as $up)
                    @php
                      $uName = 'Yayasan';
                      foreach($units as $u) {
                        if ($u['id'] === ($up['unit_id'] ?? '')) {
                          $uName = $u['name'];
                          break;
                        }
                      }
                    @endphp
                    <tr>
                      <td>
                        <b>{{ $up['name'] }}</b>
                        <div style="font-size: 11px; color: #718096;">NIP: {{ $up['nip'] ?? '-' }}</div>
                      </td>
                      <td><span class="label label-default">{{ $up['employment_status'] }}</span></td>
                      <td>{{ $up['position'] }}</td>
                      <td>{{ $uName }}</td>
                      <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-add-payroll" onclick="document.getElementById('select-payroll-teacher').value = '{{ $up['id'] }}'">
                          <i class="fa fa-calculator"></i> Buat Slip Gaji
                        </button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-success" style="padding: 25px;">
                        <i class="fa fa-check-circle fa-2x"></i>
                        <p style="margin-top: 5px;">Alhamdulillah, seluruh pegawai telah selesai diproses payroll pada periode bulan ini!</p>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>

  </section>
</div>

<!-- Modal Terbitkan Slip Gaji Baru -->
<div class="modal fade" id="modal-add-payroll" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.penggajian.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-calculator"></i> Penerbitan Slip Gaji & Remunerasi Pegawai Baru</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Pegawai Penerima Gaji <span class="text-danger">*</span></label>
                <select name="teacher_id" id="select-payroll-teacher" class="form-control" required>
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['position'] }})</option>
                  @endforeach
                </select>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Periode Bulan <span class="text-danger">*</span></label>
                    <input type="month" name="month" class="form-control" value="{{ $monthFilter }}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tanggal Transfer <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control" value="2026-11-25" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Bank Penyalur <span class="text-danger">*</span></label>
                    <input type="text" name="bank_name" class="form-control" value="BSI (Bank Syariah Indonesia)" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nomor Rekening Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="account_number" class="form-control" value="712-8890-123" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="basic_salary" class="form-control" value="4500000" required>
              </div>

              <div class="form-group">
                <label>Tunjangan Jabatan (Rp)</label>
                <input type="number" name="position_allowance" class="form-control" value="1200000">
              </div>

              <div class="form-group">
                <label>Tunjangan Fungsional (Rp)</label>
                <input type="number" name="functional_allowance" class="form-control" value="800000">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Tunjangan Keluarga (Rp)</label>
                <input type="number" name="family_allowance" class="form-control" value="450000">
              </div>

              <div class="form-group">
                <label>Uang Makan & Transport (Rp)</label>
                <input type="number" name="transport_meal_allowance" class="form-control" value="850000">
              </div>

              <div class="form-group">
                <label>Honor Lembur SPKL (Rp)</label>
                <input type="number" name="overtime_pay" class="form-control" value="0">
              </div>

              <div class="form-group">
                <label>Insentif / Bonus Kinerja (Rp)</label>
                <input type="number" name="bonus_incentive" class="form-control" value="250000">
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Zakat & Infaq Pegawai (Rp)</label>
                    <input type="number" name="infaq_sedekah" class="form-control" value="100000">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Potongan Pinjaman/Denda (Rp)</label>
                    <input type="number" name="loan_deduction" class="form-control" value="0">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Catatan / Keterangan Khusus Slip</label>
                <input type="text" name="notes" class="form-control" placeholder="Contoh: Gaji bulanan dan bonus kinerja semester">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Terbitkan Slip Gaji</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
