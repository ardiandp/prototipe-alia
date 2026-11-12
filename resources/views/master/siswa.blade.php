@extends('layouts.app')

@section('title', 'Data Santri / Buku Induk')
@section('page_title', 'Buku Induk Santri')
@section('page_description', 'Basis Data Siswa Terpadu: Akademik, Asrama, Tahfidz, Keuangan, dan Data Orang Tua')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Master Data</a></li>
  <li class="active">Data Siswa</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($students) }}</h3>
          <p>Santri Terdata (Sampel)</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="#table-siswa" class="small-box-footer">Tabel Santri <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($students, fn($s) => ($s['is_boarding'] ?? false))) }}</h3>
          <p>Santri Mukim (Boarding)</p>
        </div>
        <div class="icon"><i class="fa fa-bed"></i></div>
        <a href="{{ route('asrama.kamar') }}" class="small-box-footer">Kamar Asrama <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($students, fn($s) => ($s['spp_status'] ?? '') === 'lunas')) }}</h3>
          <p>SPP Terbayar Lunas</p>
        </div>
        <div class="icon"><i class="fa fa-money"></i></div>
        <a href="{{ route('keuangan.tagihan') }}" class="small-box-footer">Status SPP <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          @php
            $avgJuz = count($students) > 0 ? round(array_sum(array_column($students, 'tahfidz_juz')) / count($students), 1) : 0;
          @endphp
          <h3>{{ $avgJuz }} Juz</h3>
          <p>Rerata Capaian Tahfidz</p>
        </div>
        <div class="icon"><i class="fa fa-book"></i></div>
        <a href="{{ route('tahfidz.setoran') }}" class="small-box-footer">Monitoring Tahfidz <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-siswa">
    <div class="col-md-12">
      <x-card title="Buku Induk Santri Alia Islamic School" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('master.siswa') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 220px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / NIS / NISN / wali...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Unit -->
              <div class="form-group" style="margin-left: 6px;">
                <select name="unit" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Unit</option>
                  <option value="SD" {{ $unitFilter == 'SD' ? 'selected' : '' }}>Unit SD</option>
                  <option value="SMP" {{ $unitFilter == 'SMP' ? 'selected' : '' }}>Unit SMP</option>
                  <option value="SMA" {{ $unitFilter == 'SMA' ? 'selected' : '' }}>Unit SMA</option>
                </select>
              </div>

              <!-- Filter Status Asrama -->
              <div class="form-group" style="margin-left: 6px;">
                <select name="residence" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $residenceFilter == 'all' ? 'selected' : '' }}>Semua Hunian</option>
                  <option value="boarding" {{ $residenceFilter == 'boarding' ? 'selected' : '' }}>Santri Boarding (Asrama)</option>
                  <option value="fullday" {{ $residenceFilter == 'fullday' ? 'selected' : '' }}>Non-Boarding (Full Day)</option>
                </select>
              </div>

              @if($search || $unitFilter !== 'all' || $residenceFilter !== 'all')
                <a href="{{ route('master.siswa') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <button class="btn btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-siswa">
              <i class="fa fa-user-plus"></i> Pendaftaran Santri Baru
            </button>
          </div>
        </div>

        @if(count($students) === 0)
          <x-empty-state 
            title="Tidak Ada Data Santri"
            message="Tidak ada catatan santri yang sesuai dengan kriteria filter Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('master.siswa') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 45px;" class="text-center">No</th>
                  <th style="width: 55px;" class="text-center">Foto</th>
                  <th>NIS & NISN</th>
                  <th>Nama Lengkap Santri</th>
                  <th>Unit & Kelas</th>
                  <th>Tipe Hunian</th>
                  <th>Wali Santri & Telepon</th>
                  <th class="text-center">Tahfidz</th>
                  <th class="text-center">Status SPP</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($students as $s)
                  @php
                    $clsObj = collect($classes)->firstWhere('id', $s['class_id'] ?? '');
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                      <img src="{{ asset('AdminLTE/dist/img/' . ($s['avatar'] ?? 'avatar5.png')) }}" class="img-circle" style="width: 36px; height: 36px; border: 1px solid #ddd;" alt="Avatar">
                    </td>
                    <td>
                      <strong>{{ $s['nis'] }}</strong><br>
                      <small class="text-muted">NISN: {{ $s['nisn'] }}</small>
                    </td>
                    <td>
                      <span style="font-weight: 600; color: #2b6cb0; font-size: 14px;">{{ $s['name'] }}</span>
                      <small class="text-muted d-block">
                        <i class="fa {{ $s['gender'] == 'L' ? 'fa-male text-blue' : 'fa-female text-maroon' }}"></i> {{ $s['gender'] == 'L' ? 'Ikhwan' : 'Akhwat' }} &bull; {{ $s['pob'] ?? 'Jakarta' }}
                      </small>
                    </td>
                    <td>
                      <span class="label label-info">{{ $s['unit_level'] }}</span>
                      <span class="label label-default">{{ $clsObj['name'] ?? $s['class_id'] }}</span>
                    </td>
                    <td>
                      @if($s['is_boarding'])
                        <span class="label label-purple" style="background:#6b46c1;"><i class="fa fa-bed"></i> Boarding</span>
                        <small class="text-muted d-block">{{ $s['boarding_room_id'] ?? 'Kmr. Asrama' }}</small>
                      @else
                        <span class="label label-default"><i class="fa fa-home"></i> Full Day</span>
                      @endif
                    </td>
                    <td>
                      <strong>{{ $s['guardian_name'] }}</strong><br>
                      <small class="text-muted"><i class="fa fa-whatsapp text-green"></i> {{ $s['guardian_phone'] }}</small>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-green" style="font-size: 12px;"><i class="fa fa-book"></i> {{ $s['tahfidz_juz'] }} Juz</span>
                    </td>
                    <td class="text-center">
                      <x-badge :status="$s['spp_status'] ?? 'lunas'" />
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-info btn-flat" title="Profil Lengkap Santri" data-toggle="modal" data-target="#modal-profile-{{ $s['id'] }}">
                        <i class="fa fa-eye"></i> Profil
                      </button>
                      <button class="btn btn-xs btn-danger btn-flat" title="Hapus Siswa" onclick="confirmAction('{{ route('master.siswa.destroy', ['id' => $s['id']]) }}', 'Apakah Anda yakin ingin menghapus santri {{ $s['name'] }} dari sesi demo?')">
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

  <!-- Detail Profil Santri Modals (Moved outside table) -->
  @foreach($students as $s)
    @php
      $clsObj = collect($classes)->firstWhere('id', $s['class_id'] ?? '');
    @endphp
    <div class="modal fade" id="modal-profile-{{ $s['id'] }}" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-id-card-o"></i> Buku Induk Santri: {{ $s['name'] }} (NIS: {{ $s['nis'] }})</h4>
          </div>
          <div class="modal-body" style="padding: 10px;">
            
            <!-- Header Mini Profile -->
            <div class="well well-sm" style="background:#f4f6f9; margin-bottom: 15px;">
              <div class="row">
                <div class="col-sm-2 text-center">
                  <img src="{{ asset('AdminLTE/dist/img/' . ($s['avatar'] ?? 'avatar5.png')) }}" class="img-thumbnail img-circle" style="width: 85px; height: 85px;" alt="Avatar">
                </div>
                <div class="col-sm-6">
                  <h4 style="margin-top: 5px; color:#2b6cb0; font-weight: bold;">{{ $s['name'] }}</h4>
                  <p style="margin-bottom: 3px;">
                    NIS: <b>{{ $s['nis'] }}</b> | NISN: <b>{{ $s['nisn'] }}</b><br>
                    Kelas: <b>{{ $clsObj['name'] ?? $s['class_id'] }}</b> | Jenjang: <b>{{ $s['unit_level'] }}</b>
                  </p>
                </div>
                <div class="col-sm-4 text-right">
                  <span class="label label-success" style="font-size:12px;">Status: {{ ucfirst($s['status']) }}</span><br>
                  <span class="label label-purple" style="background:#6b46c1; margin-top:5px; display:inline-block;">{{ $s['residence_type'] }}</span>
                </div>
              </div>
            </div>

            <!-- Multi-Tab Details -->
            <div class="nav-tabs-custom" style="box-shadow: none; border: 1px solid #ddd;">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-bio-{{ $s['id'] }}" data-toggle="tab"><i class="fa fa-user"></i> Biodata</a></li>
                <li><a href="#tab-wali-{{ $s['id'] }}" data-toggle="tab"><i class="fa fa-users"></i> Orang Tua / Wali</a></li>
                <li><a href="#tab-akademik-{{ $s['id'] }}" data-toggle="tab"><i class="fa fa-graduation-cap"></i> Akademik</a></li>
                <li><a href="#tab-tahfidz-{{ $s['id'] }}" data-toggle="tab"><i class="fa fa-book"></i> Tahfidz ({{ $s['tahfidz_juz'] }} Juz)</a></li>
                <li><a href="#tab-keuangan-{{ $s['id'] }}" data-toggle="tab"><i class="fa fa-money"></i> Keuangan SPP</a></li>
                <li><a href="#tab-asrama-{{ $s['id'] }}" data-toggle="tab"><i class="fa fa-bed"></i> Asrama</a></li>
              </ul>
              <div class="tab-content" style="padding: 15px;">
                <!-- Tab 1: Biodata -->
                <div class="tab-pane active" id="tab-bio-{{ $s['id'] }}">
                  <table class="table table-bordered table-striped">
                    <tr><th style="width: 30%;">Nama Lengkap</th><td>{{ $s['name'] }}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>{{ $s['gender'] == 'L' ? 'Laki-Laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</td></tr>
                    <tr><th>Tempat, Tanggal Lahir</th><td>{{ $s['pob'] ?? 'Jakarta' }}, {{ date('d F Y', strtotime($s['dob'] ?? '2010-01-01')) }}</td></tr>
                    <tr><th>Agama & Kewarganegaraan</th><td>Islam / WNI</td></tr>
                    <tr><th>Status Santri</th><td><x-badge :status="$s['status']" /></td></tr>
                  </table>
                </div>

                <!-- Tab 2: Wali -->
                <div class="tab-pane" id="tab-wali-{{ $s['id'] }}">
                  <table class="table table-bordered table-striped">
                    <tr><th style="width: 30%;">Nama Orang Tua/Wali</th><td><strong>{{ $s['guardian_name'] }}</strong></td></tr>
                    <tr><th>Nomor Telepon / WhatsApp</th><td><i class="fa fa-whatsapp text-green"></i> {{ $s['guardian_phone'] }}</td></tr>
                    <tr><th>Pekerjaan</th><td>Wiraswasta / Profesional Swasta</td></tr>
                    <tr><th>Alamat Tempat Tinggal</th><td>Jl. Alia Raya No. 12, Duren Sawit, Jakarta Timur</td></tr>
                  </table>
                </div>

                <!-- Tab 3: Akademik -->
                <div class="tab-pane" id="tab-akademik-{{ $s['id'] }}">
                  <p><strong>Rerata Nilai Rapor Semester Berjalan:</strong> <span class="badge bg-green" style="font-size:14px;">{{ $s['gpa'] ?? 88.5 }}</span></p>
                  <p><strong>Tingkat Kehadiran Kelas:</strong> <span class="badge bg-blue" style="font-size:14px;">{{ $s['attendance_pct'] ?? 98 }}%</span></p>
                  <a href="{{ route('akademik.rapor') }}" class="btn btn-sm btn-default"><i class="fa fa-file-text-o"></i> Lihat E-Rapor Lengkap</a>
                </div>

                <!-- Tab 4: Tahfidz -->
                <div class="tab-pane" id="tab-tahfidz-{{ $s['id'] }}">
                  <h4>Capaian Hafalan: <span class="text-green">{{ $s['tahfidz_juz'] }} Juz Mutqin</span></h4>
                  <p>Setoran Terakhir: <b>{{ $s['tahfidz_last_surah'] ?? 'Juz ' . $s['tahfidz_juz'] }}</b></p>
                  <p>Predikat Kelancaran: <span class="label label-primary">{{ $s['tahfidz_predicate'] ?? 'Mumtaz' }}</span></p>
                  <a href="{{ route('tahfidz.setoran') }}" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;"><i class="fa fa-microphone"></i> Catat Ziyadah Santri</a>
                </div>

                <!-- Tab 5: Keuangan -->
                <div class="tab-pane" id="tab-keuangan-{{ $s['id'] }}">
                  <p>Tagihan SPP & Asrama Bulanan: <b>Rp {{ number_format($s['spp_nominal'] ?? 1850000, 0, ',', '.') }}</b></p>
                  <p>Status Pembayaran Bulan Ini: <x-badge :status="$s['spp_status'] ?? 'lunas'" /></p>
                  <a href="{{ route('keuangan.tagihan') }}" class="btn btn-sm btn-success btn-flat"><i class="fa fa-credit-card"></i> Riwayat Transaksi & Kwitansi</a>
                </div>

                <!-- Tab 6: Asrama -->
                <div class="tab-pane" id="tab-asrama-{{ $s['id'] }}">
                  @if($s['is_boarding'])
                    <p>Alokasi Kamar Asrama: <b>{{ $s['boarding_room_id'] }}</b></p>
                    <p>Status Santri: <span class="label label-success">Mukim di Asrama</span></p>
                    <a href="{{ route('asrama.perizinan') }}" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-ticket"></i> Izin Keluar / Pulang</a>
                  @else
                    <div class="alert alert-info">Santri terdaftar pada program Non-Boarding (Full Day School).</div>
                  @endif
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <!-- Modal Pendaftaran Santri Baru (Bootstrap 3) -->
  <div class="modal fade" id="modal-add-siswa" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Pendaftaran Santri Baru (Buku Induk)</h4>
        </div>
        <form action="{{ route('master.siswa.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <h5 style="font-weight: bold; color: #2b6cb0; border-bottom: 2px solid #2b6cb0; padding-bottom: 5px;">A. Identitas Calon Santri</h5>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Nama Lengkap Santri <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Nama lengkap sesuai akta kelahiran" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="gender" class="form-control" required>
                    <option value="L">Laki-Laki (Ikhwan)</option>
                    <option value="P">Perempuan (Akhwat)</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tempat Lahir</label>
                  <input type="text" name="pob" class="form-control" placeholder="Kota kelahiran">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Lahir</label>
                  <input type="date" name="dob" class="form-control" value="2010-05-15">
                </div>
              </div>
            </div>

            <h5 style="font-weight: bold; color: #2b6cb0; border-bottom: 2px solid #2b6cb0; padding-bottom: 5px; margin-top: 15px;">B. Penempatan Akademik & Hunian</h5>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Unit Pendidikan <span class="text-danger">*</span></label>
                  <select name="unit_level" class="form-control" required>
                    <option value="SD">SD Islam Alia</option>
                    <option value="SMP">SMP Islam Alia</option>
                    <option value="SMA" selected>SMA Islam Alia (Boarding)</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Penempatan Rombel / Kelas <span class="text-danger">*</span></label>
                  <select name="class_id" class="form-control" required>
                    @foreach($classes as $c)
                      <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Kamar Asrama</label>
                  <select name="boarding_room_id" class="form-control">
                    <option value="">-- Bukan Boarding --</option>
                    @foreach($boardingRooms as $br)
                      <option value="{{ $br['id'] }}">{{ $br['name'] }} ({{ $br['category'] }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" name="is_boarding" value="1" checked> <strong>Santri Mukim di Asrama Pesantren (Boarding)</strong>
              </label>
            </div>

            <h5 style="font-weight: bold; color: #2b6cb0; border-bottom: 2px solid #2b6cb0; padding-bottom: 5px; margin-top: 15px;">C. Data Orang Tua / Wali</h5>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                  <input type="text" name="guardian_name" class="form-control" placeholder="Nama ayah/ibu/wali santri" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                  <input type="text" name="guardian_phone" class="form-control" placeholder="0812xxxxxxxx" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Daftarkan Santri Baru
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
