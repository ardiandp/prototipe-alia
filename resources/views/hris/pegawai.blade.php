@extends('layouts.app')

@section('title', 'Data Pegawai & Struktur Organisasi')
@section('page_title', 'SDM & Kepegawaian (HRIS)')
@section('page_description', 'Profil Pegawai, Penempatan Unit, dan Struktur Organisasi Alia Islamic School')

@section('breadcrumbs')
  <li>SDM / HRIS</li>
  <li class="active">Pegawai & Struktur</li>
@endsection

@section('content')

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
      <h4><i class="fa fa-info-circle"></i> Modul Manajemen SDM & Struktur Organisasi</h4>
      <p>Modul HRIS ini mengelola basis data tenaga pendidik (guru) dan tenaga kependidikan (tendik), lengkap dengan bagan hierarki kepemimpinan yayasan/sekolah, rekapitulasi status kepegawaian (GTY, Kontrak, Tendik), kualifikasi pendidikan, serta kontak resmi civitas akademika Alia Islamic School.</p>
    </div>

    <!-- 4 Info Box Ringkasan SDM -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-navy"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Pegawai</span>
            <span class="info-box-number">{{ $totalStaff }} <small style="font-size: 13px; font-weight: normal;">Orang</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-navy" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              L: {{ $totalLaki }} | P: {{ $totalPerempuan }}
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-graduation-cap"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Guru Tetap (GTY)</span>
            <span class="info-box-number">{{ $totalGTY }} <small style="font-size: 13px; font-weight: normal;">Guru</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-blue" style="width: {{ $totalStaff > 0 ? ($totalGTY/$totalStaff)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              {{ $totalStaff > 0 ? round(($totalGTY/$totalStaff)*100, 1) : 0 }}% dari total SDM
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-purple"><i class="fa fa-briefcase"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Guru Kontrak</span>
            <span class="info-box-number">{{ $totalKontrak }} <small style="font-size: 13px; font-weight: normal;">Guru</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-purple" style="width: {{ $totalStaff > 0 ? ($totalKontrak/$totalStaff)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              Pendidik spesialis & muatan lokal
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-building-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Tenaga Kependidikan</span>
            <span class="info-box-number">{{ $totalTendik }} <small style="font-size: 13px; font-weight: normal;">Staf</small></span>
            <div class="progress" style="height: 3px; margin: 5px 0;">
              <div class="progress-bar bg-green" style="width: {{ $totalStaff > 0 ? ($totalTendik/$totalStaff)*100 : 0 }}%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              Keuangan, TU, & Operasional
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'daftar' ? 'active' : '' }}">
          <a href="{{ route('hris.pegawai', ['tab' => 'daftar', 'unit' => $unitFilter, 'status' => $statusFilter, 'gender' => $genderFilter, 'search' => $search]) }}">
            <i class="fa fa-list text-primary"></i> <b>Daftar Profil Pegawai</b>
            <span class="label label-primary pull-right" style="margin-left: 6px;">{{ count($filteredStaff) }}</span>
          </a>
        </li>
        <li class="{{ $activeTab === 'struktur' ? 'active' : '' }}">
          <a href="{{ route('hris.pegawai', ['tab' => 'struktur']) }}">
            <i class="fa fa-sitemap text-purple"></i> <b>Struktur Organisasi Sekolah</b>
          </a>
        </li>
        <li class="{{ $activeTab === 'statistik' ? 'active' : '' }}">
          <a href="{{ route('hris.pegawai', ['tab' => 'statistik']) }}">
            <i class="fa fa-pie-chart text-green"></i> <b>Distribusi & Analitik SDM</b>
          </a>
        </li>
        <li class="pull-right header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-staff">
            <i class="fa fa-user-plus"></i> Tambah Pegawai Baru
          </button>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Dokumen
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 15px;">

        <!-- TAB 1: DAFTAR PROFIL PEGAWAI -->
        <div class="tab-pane {{ $activeTab === 'daftar' ? 'active' : '' }}" id="tab_daftar">
          
          <!-- Filter Panel -->
          <div class="well well-sm" style="background-color: #fcfcfc;">
            <form action="{{ route('hris.pegawai') }}" method="GET" class="form-inline">
              <input type="hidden" name="tab" value="daftar">

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="search" class="form-control input-sm" placeholder="Nama, NIP, Jabatan..." value="{{ $search }}" style="width: 190px;">
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
                <label style="font-weight: normal; margin-right: 5px;">Status Kerja:</label>
                <select name="status" class="form-control input-sm">
                  <option value="all">-- Semua Status --</option>
                  <option value="Guru Tetap Yayasan (GTY)" {{ $statusFilter === 'Guru Tetap Yayasan (GTY)' ? 'selected' : '' }}>Guru Tetap Yayasan (GTY)</option>
                  <option value="Guru Kontrak" {{ $statusFilter === 'Guru Kontrak' ? 'selected' : '' }}>Guru Kontrak</option>
                  <option value="Tenaga Kependidikan" {{ $statusFilter === 'Tenaga Kependidikan' ? 'selected' : '' }}>Tenaga Kependidikan</option>
                </select>
              </div>

              <div class="form-group" style="margin-right: 10px;">
                <label style="font-weight: normal; margin-right: 5px;">Gender:</label>
                <select name="gender" class="form-control input-sm">
                  <option value="all">-- Semua Gender --</option>
                  <option value="L" {{ $genderFilter === 'L' ? 'selected' : '' }}>Laki-laki (Ikhwan)</option>
                  <option value="P" {{ $genderFilter === 'P' ? 'selected' : '' }}>Perempuan (Akhwat)</option>
                </select>
              </div>

              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Saring Data</button>
              <a href="{{ route('hris.pegawai') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
            </form>
          </div>

          <!-- Tabel Pegawai -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead>
                <tr style="background-color: #f7fafc; color: #1a365d;">
                  <th style="width: 40px; text-align: center;">No</th>
                  <th>Profil & NIP</th>
                  <th>Jabatan & Tugas</th>
                  <th>Bidang / Mata Pelajaran</th>
                  <th>Penempatan Unit</th>
                  <th>Status Kepegawaian</th>
                  <th>Kontak</th>
                  <th style="width: 140px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filteredStaff as $index => $staff)
                  @php
                    $uName = 'Yayasan / Lintas Unit';
                    foreach($units as $u) {
                      if ($u['id'] === ($staff['unit_id'] ?? '')) {
                        $uName = $u['name'];
                        break;
                      }
                    }
                  @endphp
                  <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                      <div class="media" style="margin-top: 0;">
                        <div class="media-left">
                          <img src="{{ asset('adminlte/dist/img/' . ($staff['avatar'] ?? ($staff['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png'))) }}" class="img-circle" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid #cbd5e0;" alt="{{ $staff['name'] }}">
                        </div>
                        <div class="media-body" style="vertical-align: middle;">
                          <b style="font-size: 14px; color: #1a365d;">{{ $staff['name'] }}</b>
                          <div style="font-size: 11px; color: #718096;">
                            <span><i class="fa fa-id-card-o"></i> NIP: {{ $staff['nip'] ?? '-' }}</span>
                            <span style="margin-left: 6px;"><i class="fa fa-tag"></i> ID: <code>{{ $staff['id'] }}</code></span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      <span class="label label-primary" style="font-size: 11px; padding: 4px 8px;">
                        <i class="fa fa-briefcase"></i> {{ $staff['position'] }}
                      </span>
                      <div style="font-size: 11px; color: #4a5568; margin-top: 3px;">
                        <i class="fa fa-graduation-cap"></i> {{ $staff['education'] ?? 'S1 Pendidikan' }}
                      </div>
                    </td>
                    <td style="vertical-align: middle;">
                      <b>{{ $staff['subject'] }}</b>
                    </td>
                    <td style="vertical-align: middle;">
                      <span class="label label-default" style="font-size: 11px;">
                        <i class="fa fa-building"></i> {{ $uName }}
                      </span>
                    </td>
                    <td style="vertical-align: middle;">
                      @if(($staff['employment_status'] ?? '') === 'Guru Tetap Yayasan (GTY)')
                        <span class="label label-success"><i class="fa fa-check-circle"></i> GTY Tetap</span>
                      @elseif(($staff['employment_status'] ?? '') === 'Guru Kontrak')
                        <span class="label label-warning"><i class="fa fa-clock-o"></i> Kontrak</span>
                      @else
                        <span class="label label-info"><i class="fa fa-user"></i> Tenaga Kependidikan</span>
                      @endif
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                      <div><i class="fa fa-phone text-muted"></i> {{ $staff['phone'] ?? '-' }}</div>
                      <div><i class="fa fa-envelope-o text-muted"></i> <a href="mailto:{{ $staff['email'] ?? '' }}">{{ $staff['email'] ?? '-' }}</a></div>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-view-{{ $staff['id'] }}" title="Lihat Profil Lengkap">
                          <i class="fa fa-eye text-primary"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-edit-{{ $staff['id'] }}" title="Edit Data">
                          <i class="fa fa-pencil text-warning"></i>
                        </button>
                        <a href="{{ route('hris.pegawai.destroy', $staff['id']) }}" class="btn btn-default btn-xs text-danger" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan pegawai {{ $staff['name'] }}?')" title="Hapus/Nonaktifkan">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center" style="padding: 30px;">
                      <i class="fa fa-users text-muted fa-3x" style="margin-bottom: 10px;"></i>
                      <p class="text-muted">Tidak ditemukan data pegawai yang sesuai dengan parameter saringan pencarian Anda.</p>
                      <a href="{{ route('hris.pegawai') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>

        <!-- TAB 2: STRUKTUR ORGANISASI SEKOLAH -->
        <div class="tab-pane {{ $activeTab === 'struktur' ? 'active' : '' }}" id="tab_struktur">
          
          <div class="alert alert-default" style="background-color: #f7fafc; border-color: #e2e8f0;">
            <h4><i class="fa fa-sitemap text-purple"></i> Bagan Struktur Hierarki Organisasi Alia Islamic School</h4>
            <p>Struktur kepemimpinan sekolah terbagi dalam 4 tingkatan koordinasi: Pembina Yayasan, Kepala Unit Sekolah, Koordinator Bidang Strategis & Mudir Asrama, serta Pendidik & Wali Kelas.</p>
          </div>

          <!-- LEVEL 1: YAYASAN & PEMBINA -->
          <div class="text-center" style="margin-bottom: 25px;">
            <div style="display: inline-block; width: 340px; border: 2px solid #1a365d; border-radius: 8px; background: #fff; padding: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.08);">
              <span class="label label-primary" style="font-size: 11px;">LEVEL 1: YAYASAN / DEWAN PEMBINA</span>
              @forelse($orgStructure['pembina'] as $p)
                <div style="margin-top: 8px;">
                  <img src="{{ asset('adminlte/dist/img/' . ($p['avatar'] ?? 'avatar5.png')) }}" class="img-circle" style="width: 48px; height: 48px; border: 2px solid #1a365d;">
                  <h4 style="margin: 5px 0 2px 0; font-weight: bold; color: #1a365d;">{{ $p['name'] }}</h4>
                  <span class="text-muted" style="font-size: 12px;">{{ $p['position'] }}</span>
                  <div style="font-size: 11px; color: #718096; margin-top: 3px;"><i class="fa fa-envelope"></i> {{ $p['email'] }}</div>
                </div>
              @empty
                <p class="text-muted" style="margin-top: 10px;">Belum ada penugasan dewan pembina.</p>
              @endforelse
            </div>
          </div>

          <!-- CONNECTOR LINE -->
          <div class="text-center" style="margin-top: -15px; margin-bottom: 10px;">
            <i class="fa fa-arrow-down fa-2x text-muted"></i>
          </div>

          <!-- LEVEL 2: KEPALA SEKOLAH (UNIT LEVEL) -->
          <div style="margin-bottom: 25px;">
            <h4 class="text-center" style="color: #2b6cb0; font-weight: bold; margin-bottom: 15px;">
              <span class="label label-info" style="font-size: 12px; padding: 5px 12px;">LEVEL 2: PIMPINAN UNIT SEKOLAH (KEPALA SEKOLAH)</span>
            </h4>
            <div class="row">
              @foreach($orgStructure['kepsek'] as $ks)
                <div class="col-md-4">
                  <div class="box box-solid box-primary" style="border-top-color: #2b6cb0;">
                    <div class="box-header with-border text-center" style="background: #ebf8ff; color: #2b6cb0;">
                      <h3 class="box-title" style="font-size: 13px; font-weight: bold;">{{ $ks['position'] }}</h3>
                    </div>
                    <div class="box-body text-center">
                      <img src="{{ asset('adminlte/dist/img/' . ($ks['avatar'] ?? ($ks['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png'))) }}" class="img-circle" style="width: 50px; height: 50px; border: 2px solid #2b6cb0; margin-bottom: 6px;">
                      <h4 style="margin: 2px 0; font-weight: bold; font-size: 14px;">{{ $ks['name'] }}</h4>
                      <p class="text-muted" style="font-size: 11px; margin-bottom: 4px;">NIP: {{ $ks['nip'] ?? '-' }}</p>
                      <span class="label label-success" style="font-size: 10px;">{{ $ks['employment_status'] }}</span>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- CONNECTOR LINE -->
          <div class="text-center" style="margin-top: -15px; margin-bottom: 10px;">
            <i class="fa fa-arrow-down fa-2x text-muted"></i>
          </div>

          <!-- LEVEL 3: WAKA & KOORDINATOR STRATEGIS -->
          <div style="margin-bottom: 25px;">
            <h4 class="text-center" style="color: #6b46c1; font-weight: bold; margin-bottom: 15px;">
              <span class="label label-warning" style="font-size: 12px; padding: 5px 12px; background-color: #6b46c1 !important;">LEVEL 3: KOORDINATOR BIDANG & KEPALA UNIT KHUSUS</span>
            </h4>
            <div class="row">
              @foreach($orgStructure['pimpinan_unit'] as $pu)
                <div class="col-md-3 col-sm-6">
                  <div class="box box-solid" style="border: 1px solid #e2e8f0; border-top: 3px solid #6b46c1;">
                    <div class="box-body text-center" style="padding: 12px;">
                      <img src="{{ asset('adminlte/dist/img/' . ($pu['avatar'] ?? ($pu['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png'))) }}" class="img-circle" style="width: 44px; height: 44px; border: 2px solid #6b46c1; margin-bottom: 5px;">
                      <h5 style="margin: 3px 0; font-weight: bold; font-size: 13px; color: #1a365d;">{{ $pu['name'] }}</h5>
                      <span class="label label-purple" style="background-color: #6b46c1; font-size: 10px;">{{ $pu['position'] }}</span>
                      <div style="font-size: 11px; color: #718096; margin-top: 5px;"><i class="fa fa-phone"></i> {{ $pu['phone'] }}</div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- LEVEL 4: GURU & WALI KELAS -->
          <div>
            <h4 class="text-center" style="color: #2b6cb0; font-weight: bold; margin-bottom: 15px;">
              <span class="label label-default" style="font-size: 12px; padding: 5px 12px;">LEVEL 4: GURU MATA PELAJARAN, WALI KELAS & PEMBINA</span>
            </h4>
            <div class="row">
              @foreach($orgStructure['guru_wali'] as $gw)
                <div class="col-md-3 col-sm-6">
                  <div class="box box-solid" style="border: 1px solid #edf2f7; border-top: 2px solid #4a5568;">
                    <div class="box-body" style="padding: 10px;">
                      <div class="media">
                        <div class="media-left">
                          <img src="{{ asset('adminlte/dist/img/' . ($gw['avatar'] ?? ($gw['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png'))) }}" class="img-circle" style="width: 38px; height: 38px; border: 1px solid #cbd5e0;">
                        </div>
                        <div class="media-body">
                          <b style="font-size: 12px; color: #2d3748;">{{ $gw['name'] }}</b>
                          <div style="font-size: 11px; color: #718096;">{{ $gw['position'] }}</div>
                          <span class="label label-default" style="font-size: 10px;">{{ $gw['subject'] }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

        </div>

        <!-- TAB 3: STATISTIK & ANALITIK SDM -->
        <div class="tab-pane {{ $activeTab === 'statistik' ? 'active' : '' }}" id="tab_statistik">
          <div class="row">
            
            <!-- Distribusi per Unit -->
            <div class="col-md-6">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-building text-primary"></i> Sebaran Pegawai per Unit Pendidikan</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #f7fafc;">
                        <th>Nama Unit Pendidikan</th>
                        <th style="width: 80px; text-align: center;">Jumlah</th>
                        <th style="width: 140px;">Rasio SDM</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($unitDistribution as $uId => $data)
                        @php
                          $pct = $totalStaff > 0 ? round(($data['count'] / $totalStaff) * 100, 1) : 0;
                        @endphp
                        <tr>
                          <td><b>{{ $data['unit']['name'] }}</b> ({{ $data['unit']['level'] }})</td>
                          <td class="text-center"><span class="badge bg-blue">{{ $data['count'] }} Org</span></td>
                          <td>
                            <div class="progress progress-xs" style="margin-top: 5px; margin-bottom: 2px;">
                              <div class="progress-bar progress-bar-primary" style="width: {{ $pct }}%"></div>
                            </div>
                            <small class="text-muted">{{ $pct }}%</small>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Komposisi Status Kerja & Gender -->
            <div class="col-md-6">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pie-chart text-info"></i> Komposisi Status Kepegawaian</h3>
                </div>
                <div class="box-body">
                  <div style="margin-bottom: 15px;">
                    <label>Guru Tetap Yayasan (GTY): {{ $totalGTY }} Orang ({{ $totalStaff > 0 ? round(($totalGTY/$totalStaff)*100, 1) : 0 }}%)</label>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" style="width: {{ $totalStaff > 0 ? ($totalGTY/$totalStaff)*100 : 0 }}%"></div>
                    </div>
                  </div>

                  <div style="margin-bottom: 15px;">
                    <label>Guru Kontrak: {{ $totalKontrak }} Orang ({{ $totalStaff > 0 ? round(($totalKontrak/$totalStaff)*100, 1) : 0 }}%)</label>
                    <div class="progress">
                      <div class="progress-bar progress-bar-warning" style="width: {{ $totalStaff > 0 ? ($totalKontrak/$totalStaff)*100 : 0 }}%"></div>
                    </div>
                  </div>

                  <div style="margin-bottom: 15px;">
                    <label>Tenaga Kependidikan: {{ $totalTendik }} Orang ({{ $totalStaff > 0 ? round(($totalTendik/$totalStaff)*100, 1) : 0 }}%)</label>
                    <div class="progress">
                      <div class="progress-bar progress-bar-info" style="width: {{ $totalStaff > 0 ? ($totalTendik/$totalStaff)*100 : 0 }}%"></div>
                    </div>
                  </div>

                  <hr>

                  <div class="row text-center">
                    <div class="col-xs-6" style="border-right: 1px solid #f4f4f4">
                      <input type="text" class="knob" value="{{ $totalLaki }}" data-width="60" data-height="60" data-fgColor="#3c8dbc" readonly style="display:none;">
                      <h4 style="margin: 0; color: #1a365d; font-weight: bold;"><i class="fa fa-male text-blue"></i> {{ $totalLaki }}</h4>
                      <div class="knob-label">Laki-laki (Ikhwan)</div>
                    </div>
                    <div class="col-xs-6">
                      <h4 style="margin: 0; color: #6b46c1; font-weight: bold;"><i class="fa fa-female text-purple"></i> {{ $totalPerempuan }}</h4>
                      <div class="knob-label">Perempuan (Akhwat)</div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

<!-- Modals Section Placed Outside of Table Structure -->
@foreach($filteredStaff as $staff)
  @php
    $uName = 'Yayasan / Lintas Unit';
    foreach($units as $u) {
      if ($u['id'] === ($staff['unit_id'] ?? '')) {
        $uName = $u['name'];
        break;
      }
    }
  @endphp
  <!-- Modal View Detail Pegawai -->
  <div class="modal fade" id="modal-view-{{ $staff['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-user"></i> Kartu Profil Pegawai</h4>
        </div>
        <div class="modal-body">
          <div class="text-center" style="margin-bottom: 20px;">
            <img src="{{ asset('adminlte/dist/img/' . ($staff['avatar'] ?? ($staff['gender'] === 'L' ? 'avatar5.png' : 'avatar3.png'))) }}" class="img-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #2b6cb0;" alt="{{ $staff['name'] }}">
            <h3 style="margin-top: 10px; margin-bottom: 2px; color: #1a365d;"><b>{{ $staff['name'] }}</b></h3>
            <p class="text-muted"><i class="fa fa-id-badge"></i> {{ $staff['position'] }} &bull; {{ $staff['employment_status'] }}</p>
          </div>

          <table class="table table-bordered table-striped">
            <tr>
              <th style="width: 35%; background: #f9f9f9;">Nomor Induk Pegawai (NIP)</th>
              <td><b>{{ $staff['nip'] ?? '-' }}</b></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">ID Sistem</th>
              <td><code>{{ $staff['id'] }}</code></td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Jenis Kelamin</th>
              <td>{{ ($staff['gender'] ?? 'L') === 'L' ? 'Laki-laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Bidang / Mata Pelajaran</th>
              <td>{{ $staff['subject'] }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Pendidikan Terakhir</th>
              <td>{{ $staff['education'] ?? 'S1 / S2 Pendidikan' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Penempatan Unit</th>
              <td>{{ $uName }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Nomor Telepon / WhatsApp</th>
              <td><i class="fa fa-whatsapp text-green"></i> {{ $staff['phone'] ?? '-' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Email Resmi</th>
              <td><i class="fa fa-envelope text-blue"></i> {{ $staff['email'] ?? '-' }}</td>
            </tr>
            <tr>
              <th style="background: #f9f9f9;">Status Keaktifan</th>
              <td><span class="label label-success"><i class="fa fa-check"></i> Aktif Bertugas</span></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Kartu Profil</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit Pegawai -->
  <div class="modal fade" id="modal-edit-{{ $staff['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form action="{{ route('hris.pegawai.update', $staff['id']) }}" method="POST">
          @csrf
          <div class="modal-header" style="background-color: #2b6cb0; color: #fff;">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Edit Profil Pegawai - {{ $staff['name'] }}</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" value="{{ $staff['name'] }}" required>
                </div>
                <div class="form-group">
                  <label>NIP (Nomor Induk Pegawai) <span class="text-danger">*</span></label>
                  <input type="text" name="nip" class="form-control" value="{{ $staff['nip'] ?? '' }}" required>
                </div>
                <div class="form-group">
                  <label>Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="gender" class="form-control" required>
                    <option value="L" {{ ($staff['gender'] ?? '') === 'L' ? 'selected' : '' }}>Laki-laki (Ikhwan)</option>
                    <option value="P" {{ ($staff['gender'] ?? '') === 'P' ? 'selected' : '' }}>Perempuan (Akhwat)</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Jabatan Organisasi <span class="text-danger">*</span></label>
                  <input type="text" name="position" class="form-control" value="{{ $staff['position'] }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Bidang Studi / Mata Pelajaran / Tugas <span class="text-danger">*</span></label>
                  <input type="text" name="subject" class="form-control" value="{{ $staff['subject'] }}" required>
                </div>
                <div class="form-group">
                  <label>Status Kepegawaian <span class="text-danger">*</span></label>
                  <select name="employment_status" class="form-control" required>
                    <option value="Guru Tetap Yayasan (GTY)" {{ ($staff['employment_status'] ?? '') === 'Guru Tetap Yayasan (GTY)' ? 'selected' : '' }}>Guru Tetap Yayasan (GTY)</option>
                    <option value="Guru Kontrak" {{ ($staff['employment_status'] ?? '') === 'Guru Kontrak' ? 'selected' : '' }}>Guru Kontrak</option>
                    <option value="Tenaga Kependidikan" {{ ($staff['employment_status'] ?? '') === 'Tenaga Kependidikan' ? 'selected' : '' }}>Tenaga Kependidikan</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Penempatan Unit Sekolah <span class="text-danger">*</span></label>
                  <select name="unit_id" class="form-control" required>
                    @foreach($units as $u)
                      <option value="{{ $u['id'] }}" {{ ($staff['unit_id'] ?? '') === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nomor Telepon / WA <span class="text-danger">*</span></label>
                      <input type="text" name="phone" class="form-control" value="{{ $staff['phone'] ?? '' }}" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Pendidikan Terakhir</label>
                      <input type="text" name="education" class="form-control" value="{{ $staff['education'] ?? 'S1 Pendidikan' }}">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Email Resmi</label>
                  <input type="email" name="email" class="form-control" value="{{ $staff['email'] ?? '' }}">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<!-- Modal Tambah Pegawai Baru -->
<div class="modal fade" id="modal-add-staff" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('hris.pegawai.store') }}" method="POST">
        @csrf
        <div class="modal-header" style="background-color: #1a365d; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Pendaftaran Pegawai / Tenaga Pendidik Baru</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Lengkap & Gelar Akademik <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Ust. Ahmad Fauzi, M.Pd." required>
              </div>
              <div class="form-group">
                <label>NIP (Opsional - Dibuat Otomatis bila kosong)</label>
                <input type="text" name="nip" class="form-control" placeholder="Nomor Induk Pegawai">
              </div>
              <div class="form-group">
                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="gender" class="form-control" required>
                  <option value="L">Laki-laki (Ikhwan)</option>
                  <option value="P">Perempuan (Akhwat)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Jabatan / Posisi Kerja <span class="text-danger">*</span></label>
                <input type="text" name="position" class="form-control" placeholder="Contoh: Guru Matematika / Wali Kelas VII" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Mata Pelajaran / Bidang Tugas <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control" placeholder="Contoh: Matematika IPA / Administrasi Keuangan" required>
              </div>
              <div class="form-group">
                <label>Status Kepegawaian <span class="text-danger">*</span></label>
                <select name="employment_status" class="form-control" required>
                  <option value="Guru Tetap Yayasan (GTY)">Guru Tetap Yayasan (GTY)</option>
                  <option value="Guru Kontrak">Guru Kontrak</option>
                  <option value="Tenaga Kependidikan">Tenaga Kependidikan</option>
                </select>
              </div>
              <div class="form-group">
                <label>Penempatan Unit <span class="text-danger">*</span></label>
                <select name="unit_id" class="form-control" required>
                  @foreach($units as $u)
                    <option value="{{ $u['id'] }}">{{ $u['name'] }} ({{ $u['level'] }})</option>
                  @endforeach
                </select>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Pendidikan Terakhir</label>
                    <input type="text" name="education" class="form-control" placeholder="Contoh: S1 / S2 PAI" value="S1 Pendidikan">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Email Resmi (Opsional)</label>
                <input type="email" name="email" class="form-control" placeholder="nama@alia.sch.id">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Daftarkan Pegawai</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
