@extends('layouts.app')

@section('title', 'Pengaturan Sistem & Profil Institusi')
@section('page_title', 'Pengaturan Sistem & Konfigurasi')
@section('page_description', 'Profil Institusi, Struktur Unit/TA, Switcher Peran Demo, & Reset Data')
@section('breadcrumbs')
  <li class="active">Pengaturan Sistem</li>
@endsection

@push('styles')
<style>
  .settings-hero {
    background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 60%, #44337a 100%);
    color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
  }
  .role-card-item {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 15px;
    background: #ffffff;
    transition: all 0.25s ease;
  }
  .role-card-item:hover, .role-card-item.active {
    border-color: #2b6cb0;
    box-shadow: 0 4px 14px rgba(43, 108, 176, 0.15);
  }
  .role-avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #ebf8ff;
    color: #2b6cb0;
    line-height: 45px;
    text-align: center;
    font-size: 18px;
    margin-right: 12px;
  }
</style>
@endpush

@section('content')

    <h1>
      Pengaturan Sistem & Konfigurasi
      <small>Profil Institusi, Struktur Unit/TA, Switcher Peran Demo, & Reset Data</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Pengaturan Sistem</li>
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
    @if(session('info'))
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info-circle"></i> Info!</h4>
        {{ session('info') }}
      </div>
    @endif

    <!-- Settings Hero Banner -->
    <div class="settings-hero">
      <div class="row">
        <div class="col-sm-8">
          <h2 style="margin: 0 0 5px 0; font-weight: 800; font-size: 22px;">
            <i class="fa fa-cogs"></i> Sistem Konfigurasi & Identitas Alia Islamic School
          </h2>
          <p style="margin: 0; opacity: 0.95; font-size: 13px;">
            Kelola identitas resmi lembaga, unit sekolah, tahun ajaran aktif, simulasi hak akses multi-peran, dan preferensi notifikasi prototype.
          </p>
        </div>
        <div class="col-sm-4 text-right" style="padding-top: 10px;">
          <span class="label label-warning" style="font-size: 11px; text-transform: uppercase;">Peran Aktif:</span>
          <div style="font-weight: 700; font-size: 15px; margin-top: 4px;">
            {{ session('current_role', 'Yayasan / Administrator') }}
          </div>
          <small style="opacity: 0.85;">{{ session('user_name', 'Ust. Dr. H. Zulkifli, Lc., M.A.') }}</small>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'institusi' ? 'active' : '' }}">
          <a href="{{ route('settings.index', ['tab' => 'institusi']) }}">
            <i class="fa fa-university text-blue"></i> T16.1 Profil Institusi
          </a>
        </li>
        <li class="{{ $activeTab === 'unit_tahun' ? 'active' : '' }}">
          <a href="{{ route('settings.index', ['tab' => 'unit_tahun']) }}">
            <i class="fa fa-calendar-check-o text-green"></i> T16.2 Unit & Tahun Ajaran
          </a>
        </li>
        <li class="{{ $activeTab === 'role_switcher' ? 'active' : '' }}">
          <a href="{{ route('settings.index', ['tab' => 'role_switcher']) }}">
            <i class="fa fa-users text-purple"></i> T16.3 Switcher Peran Demo
          </a>
        </li>
        <li class="{{ $activeTab === 'notifikasi' ? 'active' : '' }}">
          <a href="{{ route('settings.index', ['tab' => 'notifikasi']) }}">
            <i class="fa fa-bell text-yellow"></i> T16.4 Preferensi Notifikasi
          </a>
        </li>
        <li class="{{ $activeTab === 'reset_demo' ? 'active' : '' }}">
          <a href="{{ route('settings.index', ['tab' => 'reset_demo']) }}">
            <i class="fa fa-refresh text-red"></i> T16.5 Reset Data Sesi Demo
          </a>
        </li>
      </ul>

      <div class="tab-content" style="padding: 20px;">

        <!-- ========================================== -->
        <!-- TAB 1: PROFIL INSTITUSI (T16.1)           -->
        <!-- ========================================== -->
        @if($activeTab === 'institusi')
          <div class="tab-pane active" id="tab_institusi">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-university text-blue"></i> Profil & Legalitas Lembaga Pendidikan
            </h4>

            <form action="{{ route('settings.institution.update') }}" method="POST">
              @csrf
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Nama Lembaga / Sekolah:</label>
                  <input type="text" name="name" class="form-control" value="{{ $institution['name'] }}" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Badan Hukum Penyelenggara (Yayasan):</label>
                  <input type="text" name="legal_name" class="form-control" value="{{ $institution['legal_name'] }}" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Ketua Yayasan:</label>
                  <input type="text" name="foundation_chairman" class="form-control" value="{{ $institution['foundation_chairman'] }}" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Direktur Pendidikan / Mudir:</label>
                  <input type="text" name="director" class="form-control" value="{{ $institution['director'] }}" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group">
                  <label>Telepon Sekretariat:</label>
                  <input type="text" name="phone" class="form-control" value="{{ $institution['phone'] }}" required>
                </div>
                <div class="col-md-4 form-group">
                  <label>Email Resmi:</label>
                  <input type="email" name="email" class="form-control" value="{{ $institution['email'] }}" required>
                </div>
                <div class="col-md-4 form-group">
                  <label>Akreditasi Sekolah:</label>
                  <input type="text" class="form-control" value="{{ $institution['accreditation'] }}" readonly>
                </div>
              </div>

              <div class="form-group">
                <label>Alamat Lengkap Kampus:</label>
                <textarea name="address" class="form-control" rows="2" required>{{ $institution['address'] }}</textarea>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Tagline / Moto Sekolah:</label>
                  <input type="text" name="tagline" class="form-control" value="{{ $institution['tagline'] }}">
                </div>
                <div class="col-md-6 form-group">
                  <label>Visi Utama:</label>
                  <textarea name="vision" class="form-control" rows="2">{{ $institution['vision'] }}</textarea>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan Perubahan Profil Institusi
              </button>
            </form>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 2: UNIT & TAHUN AJARAN (T16.2)        -->
        <!-- ========================================== -->
        @if($activeTab === 'unit_tahun')
          <div class="tab-pane active" id="tab_unit_tahun">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-calendar-check-o text-green"></i> Struktur Unit Sekolah & Kalender Tahun Ajaran
            </h4>

            <div class="row">
              <!-- Kolom Unit Sekolah -->
              <div class="col-md-6">
                <div class="box box-solid box-primary">
                  <div class="box-header with-border" style="background: #1a365d;">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-sitemap"></i> Unit Pendidikan Aktif</h3>
                  </div>
                  <div class="box-body no-padding">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Kode</th>
                          <th>Nama Unit</th>
                          <th>Kepala Sekolah</th>
                          <th class="text-center">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($units as $u)
                          <tr>
                            <td><code>{{ $u['code'] }}</code></td>
                            <td><strong>{{ $u['name'] }}</strong></td>
                            <td>{{ $u['headmaster'] }}</td>
                            <td class="text-center"><span class="label label-success">Aktif</span></td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Kolom Tahun Ajaran -->
              <div class="col-md-6">
                <div class="box box-solid box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-calendar"></i> Tahun Ajaran & Semester</h3>
                  </div>
                  <div class="box-body no-padding">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Tahun Ajaran</th>
                          <th>Periode Belajar</th>
                          <th class="text-center">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($academicYears as $ay)
                          <tr>
                            <td><strong>{{ $ay['name'] }}</strong></td>
                            <td><small>{{ $ay['start_date'] }} s/d {{ $ay['end_date'] }}</small></td>
                            <td class="text-center">
                              @if(!empty($ay['is_current']))
                                <span class="label label-success">AKTIF BERJALAN</span>
                              @else
                                <span class="label label-default">Arsip</span>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 3: ROLE SWITCHER DEMO (T16.3)         -->
        <!-- ========================================== -->
        @if($activeTab === 'role_switcher')
          <div class="tab-pane active" id="tab_role_switcher">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-users text-purple"></i> Simulasi Multi-Peran Pengguna Prototype (Role Switcher)
            </h4>
            <p class="text-muted" style="margin-bottom: 20px;">
              Pilih salah satu peran demo di bawah ini untuk menguji hak akses, sudut pandang dasbor, dan kapabilitas otorisasi yang berbeda secara instan:
            </p>

            <div class="row">
              @php
                $currentRoleKey = session('active_role_key', 'yayasan');
              @endphp

              @foreach($rolesList as $rKey => $rData)
                <div class="col-md-4 col-sm-6">
                  <div class="role-card-item {{ $currentRoleKey === $rKey ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                      <div class="role-avatar-circle">
                        <i class="fa {{ $rKey === 'wali' ? 'fa-user-circle' : ($rKey === 'guru' ? 'fa-mortar-board' : 'fa-user') }}"></i>
                      </div>
                      <div>
                        <span class="label label-primary pull-right" style="font-size: 10px;">{{ $rData['badge'] }}</span>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #1a365d;">
                          {{ $rData['title'] }}
                        </h4>
                        <div style="font-size: 12px; font-weight: 600; color: #4a5568;">{{ $rData['name'] }}</div>
                      </div>
                    </div>
                    <p style="font-size: 12px; color: #666; min-height: 38px; margin-bottom: 12px;">
                      {{ $rData['desc'] }}
                    </p>
                    <div>
                      @if($currentRoleKey === $rKey)
                        <button class="btn btn-block btn-success btn-sm" disabled>
                          <i class="fa fa-check"></i> Peran Sedang Aktif
                        </button>
                      @else
                        <a href="{{ route('demo.switch-role', ['role' => $rKey]) }}" class="btn btn-block btn-primary btn-sm">
                          <i class="fa fa-exchange"></i> Beralih ke Peran Ini
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 4: PREFERENSI NOTIFIKASI (T16.4)      -->
        <!-- ========================================== -->
        @if($activeTab === 'notifikasi')
          <div class="tab-pane active" id="tab_notifikasi">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-bell text-yellow"></i> Konfigurasi Notifikasi Gateway WhatsApp & Email
            </h4>

            <form action="{{ route('settings.notifications.update') }}" method="POST">
              @csrf
              <div class="box box-solid box-default">
                <div class="box-body">
                  <h5 style="font-weight: 700; color: #1a365d; margin-top: 0;">Saluran Notifikasi Otomatis Orang Tua / Wali:</h5>
                  
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="wa_guardian_spp" {{ !empty($notificationPrefs['wa_guardian_spp']) ? 'checked' : '' }}>
                      <strong>Kirim Tagihan & Kuitansi SPP via WhatsApp:</strong> Otomatis meneruskan rincian Virtual Account dan konfirmasi pelunasan.
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="wa_guardian_attendance" {{ !empty($notificationPrefs['wa_guardian_attendance']) ? 'checked' : '' }}>
                      <strong>Peringatan Presensi Santri Harian:</strong> Notifikasi jika santri sakit, izin, atau tanpa keterangan jam pertama.
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="wa_guardian_tahfidz" {{ !empty($notificationPrefs['wa_guardian_tahfidz']) ? 'checked' : '' }}>
                      <strong>Laporan Kemajuan Tahfidz Al-Qur'an Pekanan:</strong> Rangkuman ayat ziyadah dan murojaah tasmi'.
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="wa_guardian_permission" {{ !empty($notificationPrefs['wa_guardian_permission']) ? 'checked' : '' }}>
                      <strong>Konfirmasi Izin Keluar & Kepulangan Asrama:</strong> Notifikasi persetujuan musyrif dan waktu check-in.
                    </label>
                  </div>

                  <hr style="margin: 15px 0;">
                  <h5 style="font-weight: 700; color: #1a365d;">Saluran Internal Manajemen & Sarpras:</h5>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="alert_min_stock" {{ !empty($notificationPrefs['alert_min_stock']) ? 'checked' : '' }}>
                      <strong>Peringatan Buffer Stok Logistik Gudang:</strong> Alarm jika kuantitas barang fisik mencapai stok minimum.
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="alert_maintenance_due" {{ !empty($notificationPrefs['alert_maintenance_due']) ? 'checked' : '' }}>
                      <strong>Jadwal Pemeliharaan & Kalibrasi Alat:</strong> Pengingat otomatis servis berkala semesteran alat lab dan multimedia.
                    </label>
                  </div>
                </div>
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan Pengaturan Notifikasi
                  </button>
                </div>
              </div>
            </form>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 5: RESET DATA DEMO (T16.5)            -->
        <!-- ========================================== -->
        @if($activeTab === 'reset_demo')
          <div class="tab-pane active" id="tab_reset_demo">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #dd4b39;">
              <i class="fa fa-refresh text-red"></i> Pemulihan & Reset Sesi Data Demo Baseline
            </h4>

            <div class="callout callout-warning" style="background-color: #fff9e6 !important; border-color: #f39c12; color: #8a6d3b !important;">
              <h4 style="color: #b7791f; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> Konsekuensi Aksi Reset Data:</h4>
              <p>
                Aksi ini akan menghapus seluruh rekaman mutasi buatan Anda selama demonstrasi berlangsung (seperti pengeluaran gudang baru, servis baru, riwayat chat AI, serta pengajuan izin) dan mengembalikannya secara bersih ke <strong>dataset baseline deterministik Faker</strong>.
              </p>
            </div>

            <div class="well well-sm text-center" style="padding: 25px; background: #ffffff;">
              <p style="font-size: 14px; margin-bottom: 15px;">
                Apakah Anda ingin membersihkan sesi demo sekarang dan memulihkan profil pimpinan ke Ketua Yayasan?
              </p>
              <a href="{{ route('demo.reset') }}" class="btn btn-danger btn-lg" onclick="return confirm('Apakah Anda yakin ingin mereset seluruh mutasi sesi demo ke awal?')">
                <i class="fa fa-refresh"></i> Reset Seluruh Data Sesi Demo Sekarang
              </a>
            </div>
          </div>
        @endif

      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
@endsection

