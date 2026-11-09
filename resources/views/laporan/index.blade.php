@extends('layouts.app')

@section('title', 'Pusat Laporan & Ekspor Terpadu')

@push('styles')
<style>
  .report-center-header {
    background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 60%, #6b46c1 100%);
    color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
  }
  .report-stat-card {
    background: #ffffff;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    padding: 14px 18px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  }
  .report-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #ffffff;
    margin-right: 15px;
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Pusat Laporan & Ekspor Terpadu
      <small>Laporan Eksekutif Yayasan, Akademik, PPDB, Keuangan, SDM, & Sarpras</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Pusat Laporan</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Flash Message -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
      </div>
    @endif

    <!-- Header Banner -->
    <div class="report-center-header">
      <div class="row">
        <div class="col-sm-8">
          <h2 style="margin: 0 0 5px 0; font-weight: 800; font-size: 22px;">
            <i class="fa fa-file-text-o"></i> Executive Report Center — Alia Islamic School
          </h2>
          <p style="margin: 0; opacity: 0.95; font-size: 13px;">
            Pusat konsolidasi pelaporan resmi sekolah yang mencakup seluruh pilar data operasional, siap dicetak dalam format resmi berkop yayasan atau diekspor ke lembar kerja Excel/PDF.
          </p>
        </div>
        <div class="col-sm-4 text-right" style="padding-top: 10px;">
          <a href="{{ route('laporan.cetak', ['module' => $activeTab]) }}" target="_blank" class="btn btn-warning" style="font-weight: 700; color: #1a365d;">
            <i class="fa fa-print"></i> Cetak Lembar Eksekutif (Kop Resmi)
          </a>
          <a href="{{ route('laporan.index', ['tab' => $activeTab, 'format' => 'excel']) }}" class="btn btn-success" style="font-weight: 700; margin-left: 6px;">
            <i class="fa fa-file-excel-o"></i> Ekspor Excel
          </a>
        </div>
      </div>
    </div>

    <!-- 4 Strip Ringkasan Metrik Konsolidasi -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="report-stat-card">
          <div class="report-stat-icon bg-blue"><i class="fa fa-graduation-cap"></i></div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #718096; font-weight: 700;">Siswa Aktif</div>
            <div style="font-size: 20px; font-weight: 800; color: #1a365d;">{{ number_format($totalStudents) }} Santri</div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="report-stat-card">
          <div class="report-stat-icon bg-green"><i class="fa fa-book"></i></div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #718096; font-weight: 700;">Hafalan Mutqin</div>
            <div style="font-size: 20px; font-weight: 800; color: #00a65a;">{{ number_format($totalTahfidzJuz) }} Juz</div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="report-stat-card">
          <div class="report-stat-icon bg-purple"><i class="fa fa-users"></i></div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #718096; font-weight: 700;">Tenaga Pendidik</div>
            <div style="font-size: 20px; font-weight: 800; color: #6b46c1;">{{ number_format($totalTeachers) }} Ustadz/ah</div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="report-stat-card">
          <div class="report-stat-icon bg-orange"><i class="fa fa-archive"></i></div>
          <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #718096; font-weight: 700;">Valuasi Aset Fisik</div>
            <div style="font-size: 18px; font-weight: 800; color: #d69e2e;">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs Konsolidasi Modul (T15.2 s/d T15.7) -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'akademik' ? 'active' : '' }}">
          <a href="{{ route('laporan.index', ['tab' => 'akademik']) }}">
            <i class="fa fa-graduation-cap text-blue"></i> T15.2 Laporan Akademik
          </a>
        </li>
        <li class="{{ $activeTab === 'ppdb' ? 'active' : '' }}">
          <a href="{{ route('laporan.index', ['tab' => 'ppdb']) }}">
            <i class="fa fa-user-plus text-green"></i> T15.3 Laporan PPDB
          </a>
        </li>
        <li class="{{ $activeTab === 'keuangan' ? 'active' : '' }}">
          <a href="{{ route('laporan.index', ['tab' => 'keuangan']) }}">
            <i class="fa fa-money text-yellow"></i> T15.4 Laporan Keuangan SPP
          </a>
        </li>
        <li class="{{ $activeTab === 'hris' ? 'active' : '' }}">
          <a href="{{ route('laporan.index', ['tab' => 'hris']) }}">
            <i class="fa fa-users text-purple"></i> T15.5 Laporan SDM / HRIS
          </a>
        </li>
        <li class="{{ $activeTab === 'tahfidz_asrama' ? 'active' : '' }}">
          <a href="{{ route('laporan.index', ['tab' => 'tahfidz_asrama']) }}">
            <i class="fa fa-bed text-teal"></i> T15.6 Tahfidz & Asrama
          </a>
        </li>
        <li class="{{ $activeTab === 'sarpras_logistik' ? 'active' : '' }}">
          <a href="{{ route('laporan.index', ['tab' => 'sarpras_logistik']) }}">
            <i class="fa fa-cubes text-red"></i> T15.7 Sarpras & Logistik
          </a>
        </li>
      </ul>

      <div class="tab-content" style="padding: 20px;">

        <!-- ========================================== -->
        <!-- TAB 1: LAPORAN AKADEMIK (T15.2)           -->
        <!-- ========================================== -->
        @if($activeTab === 'akademik')
          <div class="tab-pane active" id="tab_akademik">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-graduation-cap text-blue"></i> Rekapitulasi Nilai & Kinerja Akademik Santri Per Unit
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th style="width: 100px;">NIS</th>
                    <th>Nama Lengkap Santri</th>
                    <th style="width: 90px;" class="text-center">Jenjang</th>
                    <th style="width: 90px;" class="text-center">Rombel</th>
                    <th class="text-center" style="width: 110px;">Rata-rata Nilai</th>
                    <th class="text-center" style="width: 110px;">Presensi Hadir</th>
                    <th class="text-center" style="width: 110px;">Status Akademik</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($students as $idx => $s)
                    <tr>
                      <td class="text-center">{{ $idx + 1 }}</td>
                      <td><code>{{ $s['nis'] }}</code></td>
                      <td><strong>{{ $s['name'] }}</strong></td>
                      <td class="text-center"><span class="label label-primary">{{ $s['unit_level'] }}</span></td>
                      <td class="text-center">{{ $s['class_id'] }}</td>
                      <td class="text-center font-bold" style="color: #2b6cb0;">{{ number_format($s['gpa'], 1) }}</td>
                      <td class="text-center font-bold text-green">{{ number_format($s['attendance_pct'], 1) }}%</td>
                      <td class="text-center"><span class="label label-success">Lulus / Naik</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 2: LAPORAN PPDB (T15.3)               -->
        <!-- ========================================== -->
        @if($activeTab === 'ppdb')
          <div class="tab-pane active" id="tab_ppdb">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-user-plus text-green"></i> Rekapitulasi Seleksi & Penerimaan Siswa Baru (PPDB Online)
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th style="width: 130px;">No. Pendaftaran</th>
                    <th>Nama Calon Santri</th>
                    <th class="text-center" style="width: 90px;">Unit</th>
                    <th>Jalur Masuk</th>
                    <th class="text-center" style="width: 90px;">Skor Tes</th>
                    <th class="text-center" style="width: 120px;">Hasil Seleksi</th>
                    <th class="text-center" style="width: 120px;">Daftar Ulang</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ppdb as $pIdx => $p)
                    <tr>
                      <td class="text-center">{{ $pIdx + 1 }}</td>
                      <td><code>{{ $p['reg_number'] ?? $p['id'] }}</code></td>
                      <td><strong>{{ $p['name'] }}</strong></td>
                      <td class="text-center"><span class="label label-info">{{ $p['unit_level'] ?? ($p['unit'] ?? 'SMA') }}</span></td>
                      <td>{{ $p['track'] ?? ($p['jalur'] ?? 'Reguler') }}</td>
                      <td class="text-center font-bold">{{ $p['exam_score'] ?? ($p['total_score'] ?? '-') }}</td>
                      <td class="text-center">
                        @if(($p['selection_status'] ?? ($p['status_kelulusan'] ?? '')) === 'lulus')
                          <span class="label label-success">DITERIMA</span>
                        @elseif(($p['selection_status'] ?? ($p['status_kelulusan'] ?? '')) === 'proses')
                          <span class="label label-warning">PROSES SELEKSI</span>
                        @else
                          <span class="label label-danger">TIDAK LOLOS</span>
                        @endif
                      </td>
                      <td class="text-center">
                        @if(($p['payment_status'] ?? ($p['status_pembayaran'] ?? '')) === 'lunas')
                          <span class="label label-success">LUNAS</span>
                        @else
                          <span class="label label-default">MENUNGGU</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 3: LAPORAN KEUANGAN & SPP (T15.4)     -->
        <!-- ========================================== -->
        @if($activeTab === 'keuangan')
          <div class="tab-pane active" id="tab_keuangan">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-money text-yellow"></i> Rekapitulasi Tagihan SPP & Kolektibilitas Keuangan Santri
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Nama Santri</th>
                    <th class="text-center" style="width: 90px;">Jenjang</th>
                    <th>Nama Orang Tua / Wali</th>
                    <th class="text-right" style="width: 150px;">Tarif SPP Bulanan</th>
                    <th class="text-center" style="width: 130px;">Status Pembayaran</th>
                    <th class="text-center" style="width: 140px;">Metode Bayar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($students as $kIdx => $st)
                    <tr>
                      <td class="text-center">{{ $kIdx + 1 }}</td>
                      <td><strong>{{ $st['name'] }}</strong></td>
                      <td class="text-center"><span class="label label-primary">{{ $st['unit_level'] }}</span></td>
                      <td>{{ $st['guardian_name'] }}</td>
                      <td class="text-right font-bold">Rp {{ number_format($st['spp_nominal'] ?? 1850000, 0, ',', '.') }}</td>
                      <td class="text-center">
                        @if(($st['spp_status'] ?? 'lunas') === 'lunas')
                          <span class="label label-success">Lunas</span>
                        @else
                          <span class="label label-danger">Belum Lunas</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <small><i class="fa fa-bank text-blue"></i> BSI Virtual Account</small>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 4: LAPORAN SDM / HRIS (T15.5)         -->
        <!-- ========================================== -->
        @if($activeTab === 'hris')
          <div class="tab-pane active" id="tab_hris">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-users text-purple"></i> Daftar Tenaga Pendidik & Kepegawaian Alia Islamic School
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th style="width: 120px;">NIP / NIK</th>
                    <th>Nama Guru / Ustadz</th>
                    <th>Mata Pelajaran / Tugas</th>
                    <th class="text-center" style="width: 90px;">Pendidikan</th>
                    <th>Kontak Telepon</th>
                    <th class="text-center" style="width: 110px;">Status Pegawai</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($teachers as $tIdx => $tc)
                    <tr>
                      <td class="text-center">{{ $tIdx + 1 }}</td>
                      <td><code>{{ $tc['nip'] }}</code></td>
                      <td><strong>{{ $tc['name'] }}</strong></td>
                      <td>{{ $tc['subject_specialty'] ?? 'Pengampu Mata Pelajaran' }}</td>
                      <td class="text-center"><span class="label label-default">{{ $tc['education'] ?? 'S1' }}</span></td>
                      <td><i class="fa fa-phone"></i> {{ $tc['phone'] ?? '08123456789' }}</td>
                      <td class="text-center"><span class="label label-success">Pegawai Tetap</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 5: TAHFIDZ & ASRAMA (T15.6)           -->
        <!-- ========================================== -->
        @if($activeTab === 'tahfidz_asrama')
          <div class="tab-pane active" id="tab_tahfidz_asrama">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-bed text-teal"></i> Laporan Konsolidasi Tahfidz & Okupansi Kamar Asrama Santri
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Nama Santri</th>
                    <th class="text-center" style="width: 120px;">Capaian Tahfidz</th>
                    <th>Surah Terakhir</th>
                    <th class="text-center" style="width: 120px;">Predikat</th>
                    <th class="text-center" style="width: 130px;">Tipe Mukim</th>
                    <th>Kamar Asrama</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($students as $taIdx => $tas)
                    <tr>
                      <td class="text-center">{{ $taIdx + 1 }}</td>
                      <td><strong>{{ $tas['name'] }}</strong></td>
                      <td class="text-center font-bold text-green">{{ $tas['tahfidz_juz'] }} Juz Mutqin</td>
                      <td>{{ $tas['tahfidz_last_surah'] }}</td>
                      <td class="text-center"><span class="label label-success">{{ $tas['tahfidz_predicate'] }}</span></td>
                      <td class="text-center"><span class="label label-info">{{ $tas['residence_type'] }}</span></td>
                      <td><i class="fa fa-bed text-purple"></i> {{ $tas['boarding_room_id'] ?? 'Non-Boarding' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 6: SARPRAS & LOGISTIK (T15.7)         -->
        <!-- ========================================== -->
        @if($activeTab === 'sarpras_logistik')
          <div class="tab-pane active" id="tab_sarpras_logistik">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-cubes text-red"></i> Laporan Aset Logistik & Kondisi Sarana Praktikum
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th style="width: 120px;">Kode Barang</th>
                    <th>Nama Barang Inventaris</th>
                    <th>Kategori</th>
                    <th class="text-center" style="width: 100px;">Stok Fisik</th>
                    <th class="text-right" style="width: 140px;">Harga Satuan</th>
                    <th class="text-right" style="width: 160px;">Nilai Total (Rp)</th>
                    <th class="text-center" style="width: 110px;">Kondisi Alat</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($items as $iIdx => $it)
                    <tr>
                      <td class="text-center">{{ $iIdx + 1 }}</td>
                      <td><code>{{ $it['item_code'] }}</code></td>
                      <td><strong>{{ $it['name'] }}</strong></td>
                      <td><span class="badge" style="background:#805ad5;">{{ strtoupper($it['category']) }}</span></td>
                      <td class="text-center font-bold">{{ $it['stock'] }} {{ $it['unit'] }}</td>
                      <td class="text-right">Rp {{ number_format($it['unit_price'] ?? 0, 0, ',', '.') }}</td>
                      <td class="text-right font-bold text-green">
                        Rp {{ number_format(($it['stock'] ?? 0) * ($it['unit_price'] ?? 0), 0, ',', '.') }}
                      </td>
                      <td class="text-center">
                        @if(($it['condition'] ?? 'baik') === 'baik')
                          <span class="label label-success">Baik</span>
                        @elseif(($it['condition'] ?? '') === 'rusak_ringan')
                          <span class="label label-warning">Rusak Ringan</span>
                        @else
                          <span class="label label-danger">Rusak Berat</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
@endsection
