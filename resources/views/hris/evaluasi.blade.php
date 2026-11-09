@extends('layouts.app')

@section('title', 'Evaluasi Kinerja Pegawai (PKG)')

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Evaluasi Kinerja Pegawai & Guru (PKG)
      <small>Penilaian Kinerja 4 Pilar Kompetensi, Rapor Kinerja, dan Rekomendasi Karir</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>SDM / HRIS</li>
      <li class="active">Evaluasi Kinerja</li>
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
    <div class="callout callout-info" style="border-left-color: #6b46c1;">
      <h4><i class="fa fa-star"></i> Sistem Penilaian Kinerja Guru (PKG) & Tendik Berkelanjutan</h4>
      <p>Evaluasi berkala menggunakan 4 pilar kompetensi terakreditasi: <b>Pedagogik (Bobot 30%)</b>, <b>Profesional (Bobot 30%)</b>, <b>Kepribadian & Akhlak Islami (Bobot 20%)</b>, dan <b>Sosial (Bobot 20%)</b>. Hasil evaluasi menghasilkan Surat Hasil Evaluasi Kinerja resmi berkop Alia Islamic School sebagai dasar promosi dan kenaikan tunjangan kinerja.</p>
    </div>

    <!-- 4 Info Box Ringkasan Kinerja -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-file-text"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Evaluasi</span>
            <span class="info-box-number">{{ $totalEvaluasi }} <small style="font-size: 13px; font-weight: normal;">Rapor</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-navy" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Semester Ganjil 2026/2027</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-trophy"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Predikat Amat Baik (A)</span>
            <span class="info-box-number text-green">{{ $totalGradeA }} <small style="font-size: 13px; font-weight: normal;">Pegawai</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-green" style="width: {{ $totalEvaluasi > 0 ? ($totalGradeA/$totalEvaluasi)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Skor 91.0 - 100.0</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-thumbs-up"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Predikat Baik (B)</span>
            <span class="info-box-number text-blue">{{ $totalGradeB }} <small style="font-size: 13px; font-weight: normal;">Pegawai</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-blue" style="width: {{ $totalEvaluasi > 0 ? ($totalGradeB/$totalEvaluasi)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Skor 76.0 - 90.9</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-purple"><i class="fa fa-line-chart"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Rata-rata Skor Total</span>
            <span class="info-box-number text-purple">{{ $avgScore }} <small style="font-size: 13px; font-weight: normal;">/ 100</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-purple" style="width: {{ $avgScore }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">Indeks Kinerja Komposit</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
          <a href="{{ route('hris.evaluasi', ['tab' => 'daftar', 'predicate' => $predicateFilter, 'unit' => $unitFilter, 'search' => $search]) }}">
            <i class="fa fa-list text-primary"></i> <b>Buku Rekap Penilaian Kinerja (PKG)</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredEvaluations) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'rubrik' ? 'active' : '' }}">
          <a href="{{ route('hris.evaluasi', ['tab' => 'rubrik']) }}">
            <i class="fa fa-book text-green"></i> <b>Rubrik 4 Pilar Kompetensi & Bobot</b>
          </a>
        </li>
        <li class="{{ $activeTab === 'komparasi' ? 'active' : '' }}">
          <a href="{{ route('hris.evaluasi', ['tab' => 'komparasi']) }}">
            <i class="fa fa-pie-chart text-purple"></i> <b>Daftar Pegawai Belum Dievaluasi</b>
            @if(count($unevaluatedTeachers) > 0)
              <span class="label label-warning pull-right" style="margin-left: 6px;">{{ count($unevaluatedTeachers) }}</span>
            @endif
          </a>
        </li>
        <li class="pull-right header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-evaluation">
            <i class="fa fa-plus"></i> Input Penilaian Kinerja Baru
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Rekapitulasi PKG
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: BUKU REKAP PENILAIAN KINERJA -->
        <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
          
          <!-- Filter Saringan -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.evaluasi') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="daftar">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Predikat Kinerja:</label>
                <select name="predicate" class="form-control input-sm">
                  <option value="all">-- Semua Predikat --</option>
                  <option value="Amat Baik" {{ $predicateFilter === 'Amat Baik' ? 'selected' : '' }}>Amat Baik (A)</option>
                  <option value="Baik" {{ $predicateFilter === 'Baik' ? 'selected' : '' }}>Baik (B)</option>
                  <option value="Cukup" {{ $predicateFilter === 'Cukup' ? 'selected' : '' }}>Cukup (C)</option>
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
                <input type="text" name="search" class="form-control input-sm" placeholder="Nama, NIP, Asesor..." value="{{ $search }}" style="width: 170px;">
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.evaluasi') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Penilaian Kinerja -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>No Dokumen & Pegawai</th>
                  <th>Jabatan & Unit</th>
                  <th style="text-align: center;">Pedagogik (30%)</th>
                  <th style="text-align: center;">Profesional (30%)</th>
                  <th style="text-align: center;">Kepribadian (20%)</th>
                  <th style="text-align: center;">Sosial (20%)</th>
                  <th style="text-align: center;">Skor Akhir</th>
                  <th style="text-align: center;">Predikat</th>
                  <th style="width: 110px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredEvaluations as $index => $evl)
                  @php
                    $uName = 'Yayasan';
                    foreach($units as $u) {
                      if ($u['id'] === ($evl['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <b style="font-size: 13px; color: #1a365d;">{{ $evl['name'] }}</b>
                      <div style="font-size: 11px; color: #718096;">
                        <span>NIP: {{ $evl['nip'] ?? '-' }}</span> &bull; <code>{{ $evl['doc_number'] }}</code>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      <span class="label label-default" style="font-size: 11px;">{{ $evl['position'] ?? 'Pendidik' }}</span>
                      <div style="font-size: 11px; color: #718096; margin-top: 2px;">{{ $uName }}</div>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <b>{{ $evl['pedagogik_score'] }}</b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <b>{{ $evl['profesional_score'] }}</b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <b>{{ $evl['kepribadian_score'] }}</b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <b>{{ $evl['sosial_score'] }}</b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <b style="font-size: 14px; color: #1a365d;">{{ number_format($evl['final_score'], 1) }}</b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      @if(stripos($evl['predicate'], 'Amat Baik') !== false)
                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-trophy"></i> {{ $evl['predicate'] }}
                        </span>
                      @elseif(stripos($evl['predicate'], 'Baik') !== false)
                        <span class="label label-primary" style="font-size: 11px; padding: 4px 8px;">
                          <i class="fa fa-thumbs-up"></i> {{ $evl['predicate'] }}
                        </span>
                      @else
                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">
                          {{ $evl['predicate'] }}
                        </span>
                      @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-evl-{{ $evl['id'] }}" title="Cetak Rapor Kinerja">
                          <i class="fa fa-file-text-o text-primary"></i>
                        </button>
                        <a href="{{ route('hris.evaluasi.destroy', $evl['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Hapus lembar evaluasi {{ $evl['name'] }}?')" title="Hapus Dokumen">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal Rapor Penilaian Kinerja Guru (PKG) Resmi -->
                  <div class="modal fade" id="modal-view-evl-{{ $evl['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-graduation-cap"></i> Lembar Penilaian Kinerja Pendidik & Tenaga Kependidikan (PKG)</h4>
                        </div>
                        <div class="modal-body" style="background: #fff; padding: 25px;">
                          
                          <!-- KOP RESMI ALIA -->
                          <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                            <h3 style="margin: 0; color: #1a365d; font-weight: bold; font-size: 18px;">YAYASAN PENDIDIKAN ISLAM ALIA</h3>
                            <h4 style="margin: 3px 0; color: #2b6cb0; font-weight: bold; font-size: 15px;">ALIA ISLAMIC SCHOOL JAKARTA</h4>
                            <p style="margin: 0; font-size: 11px; color: #718096;">
                              Kampus Asrama Terpadu: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur | Telp: (021) 8690-1234
                            </p>
                          </div>

                          <div class="text-center" style="margin-bottom: 15px;">
                            <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 3px; color: #1a365d;">
                              LEMBAR HASIL PENILAIAN KINERJA GURU & PEGAWAI
                            </h4>
                            <small class="text-muted">Nomor Registrasi: <b>{{ $evl['doc_number'] }}</b> &bull; Periode: <b>{{ $evl['period'] }}</b></small>
                          </div>

                          <!-- Identitas Pegawai & Penilai -->
                          <div class="row" style="margin-bottom: 15px;">
                            <div class="col-xs-6">
                              <table class="table table-condensed table-borderless" style="font-size: 12px; margin-bottom: 0;">
                                <tr>
                                  <td style="width: 35%;"><b>Nama Pegawai</b></td>
                                  <td>: {{ $evl['name'] }}</td>
                                </tr>
                                <tr>
                                  <td><b>NIP / ID</b></td>
                                  <td>: {{ $evl['nip'] ?? '-' }} / {{ $evl['teacher_id'] }}</td>
                                </tr>
                                <tr>
                                  <td><b>Jabatan / Tugas</b></td>
                                  <td>: {{ $evl['position'] }}</td>
                                </tr>
                              </table>
                            </div>
                            <div class="col-xs-6">
                              <table class="table table-condensed table-borderless" style="font-size: 12px; margin-bottom: 0;">
                                <tr>
                                  <td style="width: 35%;"><b>Unit Sekolah</b></td>
                                  <td>: {{ $uName }}</td>
                                </tr>
                                <tr>
                                  <td><b>Nama Penilai / Asesor</b></td>
                                  <td>: {{ $evl['evaluator_name'] }}</td>
                                </tr>
                                <tr>
                                  <td><b>Tanggal Evaluasi</b></td>
                                  <td>: {{ date('d F Y', strtotime($evl['evaluation_date'])) }}</td>
                                </tr>
                              </table>
                            </div>
                          </div>

                          <!-- Matriks 4 Pilar Kompetensi -->
                          <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                              <tr style="background-color: #f7fafc; color: #1a365d;">
                                <th style="width: 40px; text-align: center;">No</th>
                                <th>Dimensi Kompetensi Kerja</th>
                                <th style="width: 80px; text-align: center;">Bobot</th>
                                <th style="width: 100px; text-align: center;">Skor Mentah (0-100)</th>
                                <th style="width: 100px; text-align: center;">Nilai Tertimbang</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="text-center">1</td>
                                <td><b>Kompetensi Pedagogik:</b> Penguasaan kurikulum, desain pembelajaran berdiferensiasi, evaluasi formatif/sumatif santri, dan manajemen kelas.</td>
                                <td class="text-center">30%</td>
                                <td class="text-center">{{ $evl['pedagogik_score'] }}</td>
                                <td class="text-center"><b>{{ number_format($evl['pedagogik_score'] * 0.30, 1) }}</b></td>
                              </tr>
                              <tr>
                                <td class="text-center">2</td>
                                <td><b>Kompetensi Profesional:</b> Kedalaman substansi mata pelajaran, literasi teknologi pembelajaran CBT, dan pengembangan keilmuan berkelanjutan.</td>
                                <td class="text-center">30%</td>
                                <td class="text-center">{{ $evl['profesional_score'] }}</td>
                                <td class="text-center"><b>{{ number_format($evl['profesional_score'] * 0.30, 1) }}</b></td>
                              </tr>
                              <tr>
                                <td class="text-center">3</td>
                                <td><b>Kompetensi Kepribadian & Akhlak:</b> Ketaatan beribadah, integritas moral, keteladanan akhlakul karimah di lingkungan asrama, dan kedisiplinan jam kerja.</td>
                                <td class="text-center">20%</td>
                                <td class="text-center">{{ $evl['kepribadian_score'] }}</td>
                                <td class="text-center"><b>{{ number_format($evl['kepribadian_score'] * 0.20, 1) }}</b></td>
                              </tr>
                              <tr>
                                <td class="text-center">4</td>
                                <td><b>Kompetensi Sosial:</b> Komunikasi santun dan efektif dengan wali santri, kolaborasi tim pengajar, dan kepedulian terhadap kemaslahatan santri.</td>
                                <td class="text-center">20%</td>
                                <td class="text-center">{{ $evl['sosial_score'] }}</td>
                                <td class="text-center"><b>{{ number_format($evl['sosial_score'] * 0.20, 1) }}</b></td>
                              </tr>
                            </tbody>
                            <tfoot>
                              <tr style="background: #ebf8ff; font-weight: bold; font-size: 13px;">
                                <td colspan="3" class="text-right">TOTAL NILAI KINERJA AKHIR:</td>
                                <td class="text-center" colspan="2" style="font-size: 16px; color: #1a365d;">
                                  {{ number_format($evl['final_score'], 1) }} &bull; {{ $evl['predicate'] }}
                                </td>
                              </tr>
                            </tfoot>
                          </table>

                          <!-- Catatan Kekuatan & Rekomendasi -->
                          <div class="row" style="margin-top: 15px;">
                            <div class="col-md-6">
                              <div class="panel panel-default">
                                <div class="panel-heading" style="background: #f0fff4; font-weight: bold; color: #276749;">
                                  <i class="fa fa-star text-green"></i> Aspek Keunggulan & Kekuatan (Strengths)
                                </div>
                                <div class="panel-body" style="font-size: 12px;">
                                  {{ $evl['strengths'] }}
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="panel panel-default">
                                <div class="panel-heading" style="background: #ebf8ff; font-weight: bold; color: #2b6cb0;">
                                  <i class="fa fa-lightbulb-o text-blue"></i> Rekomendasi Pengembangan Keprofesian
                                </div>
                                <div class="panel-body" style="font-size: 12px;">
                                  {{ $evl['recommendations'] }}
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Tanda Tangan -->
                          <div class="row" style="margin-top: 20px;">
                            <div class="col-xs-6 text-center">
                              <small>Pegawai Yang Dinilai,</small><br><br><br>
                              <b><u>{{ $evl['name'] }}</u></b><br>
                              <small class="text-muted">NIP: {{ $evl['nip'] ?? '-' }}</small>
                            </div>
                            <div class="col-xs-6 text-center">
                              <small>Asesor / Pejabat Penilai,</small><br><br><br>
                              <b><u>{{ $evl['evaluator_name'] }}</u></b><br>
                              <small class="text-muted">Kepala Unit / Asesor Yayasan</small>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Lembar Rapor PKG</button>
                        </div>
                      </div>
                    </div>
                  </div>

                @empty
                  <tr>
                    <td colspan="10" class="text-center" style="padding: 30px;">
                      <i class="fa fa-folder-open-o text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data evaluasi kinerja yang sesuai dengan saringan.</p>
                      <a href="{{ route('hris.evaluasi') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: RUBRIK KOMPETENSI & BOBOT -->
        <div class="tab-pane {{ $activeTab === 'rubrik' ? 'active' : '' }}" id="tab_rubrik">
          <div class="row">
            
            <div class="col-md-6">
              <div class="box box-solid box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-sliders"></i> Pembobotan Pilar Penilaian Kinerja Guru (PKG)</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Pilar Dimensi</th>
                        <th style="width: 80px; text-align: center;">Bobot</th>
                        <th>Indikator Utama Keberhasilan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b>Pedagogik</b></td>
                        <td class="text-center"><span class="badge bg-blue">30%</span></td>
                        <td>Kesiapan RPP/modul ajar, inovasi metode active learning, pengelolaan kelas kondusif.</td>
                      </tr>
                      <tr>
                        <td><b>Profesional</b></td>
                        <td class="text-center"><span class="badge bg-blue">30%</span></td>
                        <td>Penguasaan kurikulum nasional & diniyah, literasi digital CBT, karya tulis/modul ajar.</td>
                      </tr>
                      <tr>
                        <td><b>Kepribadian</b></td>
                        <td class="text-center"><span class="badge bg-purple">20%</span></td>
                        <td>Kedisiplinan kehadiran, integritas amanah, teladan ibadah yaumiyah & akhlakul karimah.</td>
                      </tr>
                      <tr>
                        <td><b>Sosial</b></td>
                        <td class="text-center"><span class="badge bg-purple">20%</span></td>
                        <td>Kerjasama tim musyawarah guru mata pelajaran (MGMP), komunikasi harmonis dengan wali santri.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="box box-solid box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-star"></i> Konversi Skala Nilai & Predikat Kinerja</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Rentang Nilai</th>
                        <th>Predikat</th>
                        <th>Tindak Lanjut & Dampak Karir</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b>91.0 - 100.0</b></td>
                        <td><span class="label label-success">Amat Baik (A)</span></td>
                        <td>Direkomendasikan untuk promosi jabatan struktural & reward tunjangan kinerja maksimal.</td>
                      </tr>
                      <tr>
                        <td><b>76.0 - 90.9</b></td>
                        <td><span class="label label-primary">Baik (B)</span></td>
                        <td>Memenuhi standar kualifikasi pendidik, berhak atas tunjangan kinerja penuh.</td>
                      </tr>
                      <tr>
                        <td><b>61.0 - 75.9</b></td>
                        <td><span class="label label-warning">Cukup (C)</span></td>
                        <td>Wajib mengikuti program pendampingan & pelatihan kompetensi oleh tim kurikulum.</td>
                      </tr>
                      <tr>
                        <td><b>< 60.9</b></td>
                        <td><span class="label label-danger">Kurang (D)</span></td>
                        <td>Mendapatkan surat peringatan pembinaan khusus dari Kepala Sekolah & Yayasan.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB 3: DAFTAR PEGAWAI BELUM DIEVALUASI -->
        <div class="tab-pane {{ $activeTab === 'komparasi' ? 'active' : '' }}" id="tab_komparasi">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-clock-o text-yellow"></i> Daftar Pegawai / Guru Belum Dievaluasi Periode Ganjil 2026/2027</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background: #f7fafc;">
                    <th>Nama Pegawai & NIP</th>
                    <th>Bidang / Mata Pelajaran</th>
                    <th>Jabatan & Posisi</th>
                    <th>Unit Penempatan</th>
                    <th style="width: 140px; text-align: center;">Aksi Cepat</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($unevaluatedTeachers as $ut)
                    @php
                      $uName = 'Yayasan';
                      foreach($units as $u) {
                        if ($u['id'] === ($ut['unit_id'] ?? '')) {
                          $uName = $u['name'];
                          break;
                        }
                      }
                    @endphp
                    <tr>
                      <td>
                        <b>{{ $ut['name'] }}</b>
                        <div style="font-size: 11px; color: #718096;">NIP: {{ $ut['nip'] ?? '-' }}</div>
                      </td>
                      <td><b>{{ $ut['subject'] }}</b></td>
                      <td><span class="label label-default">{{ $ut['position'] }}</span></td>
                      <td>{{ $uName }}</td>
                      <td class="text-center">
                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-add-evaluation" onclick="document.getElementById('select-teacher-id').value = '{{ $ut['id'] }}'">
                          <i class="fa fa-pencil"></i> Beri Penilaian
                        </button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-success" style="padding: 25px;">
                        <i class="fa fa-check-circle fa-2x"></i>
                        <p style="margin-top: 5px;">Alhamdulillah, seluruh pegawai telah selesai dievaluasi untuk periode ini!</p>
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

<!-- Modal Input Penilaian Kinerja Baru -->
<div class="modal fade" id="modal-add-evaluation" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.evaluasi.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Form Instrumen Penilaian Kinerja Guru & Pegawai (PKG)</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Pegawai Yang Dinilai <span class="text-danger">*</span></label>
                <select name="teacher_id" id="select-teacher-id" class="form-control" required>
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['position'] }})</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Periode Penilaian <span class="text-danger">*</span></label>
                <input type="text" name="period" class="form-control" value="Tahun Ajaran 2026/2027 Ganjil" required>
              </div>

              <div class="form-group">
                <label>Nama Asesor / Pejabat Penilai <span class="text-danger">*</span></label>
                <input type="text" name="evaluator_name" class="form-control" value="Drs. H. Mulyadi, M.Pd. (Kepala Sekolah)" required>
              </div>

              <div class="form-group">
                <label>Tanggal Pelaksanaan Evaluasi <span class="text-danger">*</span></label>
                <input type="date" name="evaluation_date" class="form-control" value="2026-11-09" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="well well-sm" style="background: #f7fafc;">
                <label style="color: #1a365d; font-size: 13px;"><i class="fa fa-star"></i> Penilaian 4 Pilar Skor (Skala 0 - 100):</label>

                <div class="row" style="margin-bottom: 8px;">
                  <div class="col-xs-8"><label style="font-weight: normal;">1. Kompetensi Pedagogik (30%):</label></div>
                  <div class="col-xs-4">
                    <input type="number" name="pedagogik_score" class="form-control input-sm" placeholder="0-100" min="0" max="100" value="88" required>
                  </div>
                </div>

                <div class="row" style="margin-bottom: 8px;">
                  <div class="col-xs-8"><label style="font-weight: normal;">2. Kompetensi Profesional (30%):</label></div>
                  <div class="col-xs-4">
                    <input type="number" name="profesional_score" class="form-control input-sm" placeholder="0-100" min="0" max="100" value="90" required>
                  </div>
                </div>

                <div class="row" style="margin-bottom: 8px;">
                  <div class="col-xs-8"><label style="font-weight: normal;">3. Kompetensi Kepribadian (20%):</label></div>
                  <div class="col-xs-4">
                    <input type="number" name="kepribadian_score" class="form-control input-sm" placeholder="0-100" min="0" max="100" value="92" required>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-8"><label style="font-weight: normal;">4. Kompetensi Sosial (20%):</label></div>
                  <div class="col-xs-4">
                    <input type="number" name="sosial_score" class="form-control input-sm" placeholder="0-100" min="0" max="100" value="89" required>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kekuatan & Aspek Keunggulan Pegawai <span class="text-danger">*</span></label>
                <textarea name="strengths" class="form-control" rows="2" placeholder="Uraikan dedikasi, keunggulan mengajar, dan keteladanan yang ditunjukkan..." required></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Rekomendasi Pengembangan Keprofesian <span class="text-danger">*</span></label>
                <textarea name="recommendations" class="form-control" rows="2" placeholder="Uraikan pelatihan atau aspek yang perlu ditingkatkan untuk periode berikutnya..." required></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Hasil Penilaian</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
