@extends('layouts.app')

@section('title', 'Manajemen Rombel & Pembagian Kelas')
@section('page_title', 'Manajemen Rombongan Belajar')
@section('page_description', 'Distribusi Santri, Penetapan Wali Kelas, Monitoring Kapasitas, dan Pengelompokan Belajar')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li class="active">Manajemen Rombel</li>
@endsection

@section('content')

  <!-- Metric Overview -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count($classes) }}</h3>
          <p>Total Rombongan Belajar</p>
        </div>
        <div class="icon"><i class="fa fa-sitemap"></i></div>
        <a href="#class-list-section" class="small-box-footer">Pilih Rombel <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($classStudents) }}</h3>
          <p>Santri di Rombel Aktif</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="#roster-section" class="small-box-footer">Roster Siswa <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $currentClass['capacity'] ?? 30 }}</h3>
          <p>Kapasitas Kursi Rombel</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <span class="small-box-footer">{{ ($currentClass['capacity'] ?? 30) - count($classStudents) }} Kursi Tersedia</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $activeYear['name'] ?? '2026/2027 Ganjil' }}</h3>
          <p>Tahun Ajaran Aktif</p>
        </div>
        <div class="icon"><i class="fa fa-calendar"></i></div>
        <a href="{{ route('master.tahun-ajaran') }}" class="small-box-footer">Kelola Periode <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <div class="row" id="class-list-section">
    <!-- Kolom Kiri: Navigasi & Pemilih Rombel -->
    <div class="col-md-4">
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list-ul"></i> Daftar Rombongan Belajar</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-xs btn-primary btn-flat" data-toggle="modal" data-target="#modal-add-rombel">
              <i class="fa fa-plus"></i> Rombel Baru
            </button>
          </div>
        </div>
        <div class="box-body" style="padding-bottom: 0;">
          <!-- Filter Jenjang / Unit -->
          <form method="GET" action="{{ route('akademik.rombel') }}">
            <div class="form-group">
              <label>Pilih Unit Pendidikan:</label>
              <select name="unit" class="form-control input-sm" onchange="this.form.submit()">
                <option value="all" {{ $selectedUnit == 'all' ? 'selected' : '' }}>-- Semua Jenjang Unit --</option>
                @foreach($units as $u)
                  <option value="{{ $u['id'] }}" {{ $selectedUnit == $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="input-group input-group-sm" style="margin-bottom: 15px;">
              <input type="text" name="search" class="form-control" placeholder="Cari rombel..." value="{{ $search }}">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
        </div>
        <div class="box-body no-padding" style="max-height: 480px; overflow-y: auto;">
          <ul class="nav nav-pills nav-stacked">
            @forelse($filteredClasses as $c)
              @php
                $isActive = ($c['id'] === ($currentClass['id'] ?? ''));
                $tch = collect($teachers)->firstWhere('id', $c['homeroom_teacher_id'] ?? '');
              @endphp
              <li class="{{ $isActive ? 'active' : '' }}">
                <a href="{{ route('akademik.rombel', ['class_id' => $c['id'], 'unit' => $selectedUnit, 'search' => $search]) }}" style="border-left: 3px solid {{ $isActive ? '#2b6cb0' : 'transparent' }};">
                  <strong>{{ $c['name'] }}</strong>
                  <span class="pull-right badge {{ $isActive ? 'bg-navy' : 'bg-blue' }}">{{ $c['student_count'] ?? 0 }} / {{ $c['capacity'] ?? 30 }}</span>
                  <br>
                  <small class="{{ $isActive ? 'text-white' : 'text-muted' }}">
                    <i class="fa fa-user-circle"></i> Wali: {{ $tch['name'] ?? 'Ust. Ahmad Dahlan' }}
                  </small>
                </a>
              </li>
            @empty
              <li style="padding: 15px; text-align: center;" class="text-muted">
                Tidak ada rombel yang cocok.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Roster Santri & Detail Rombel Terpilih -->
    <div class="col-md-8" id="roster-section">
      @if($currentClass)
        @php
          $cap = $currentClass['capacity'] ?? 30;
          $cnt = count($classStudents);
          $pct = $cap > 0 ? round(($cnt / $cap) * 100) : 0;
        @endphp

        <!-- Panel Detail Info Rombel -->
        <div class="box box-solid" style="border-left: 4px solid #6b46c1; margin-bottom: 15px;">
          <div class="box-body">
            <div class="row">
              <div class="col-sm-8">
                <h3 style="margin-top: 0; color: #1a365d; font-weight: bold;">
                  {{ $currentClass['name'] }}
                  <span class="label label-primary" style="font-size: 13px; vertical-align: middle;">{{ $currentClass['id'] }}</span>
                </h3>
                <p style="margin-bottom: 5px; color: #555;">
                  <strong>Wali Kelas:</strong> {{ $homeroomTeacher['name'] ?? 'Ust. Belum Ditentukan' }} &bull;
                  <strong>Ruangan:</strong> {{ $currentClass['room'] ?? 'Ruang Belajar' }} &bull;
                  <strong>Tingkat:</strong> Kelas {{ $currentClass['grade'] ?? '-' }}
                </p>
                <p style="margin-bottom: 0;" class="text-muted">
                  Kurikulum Terpadu: Nasional Kemenag & Pondok Pesantren Tahfidz Alia Islamic School.
                </p>
              </div>
              <div class="col-sm-4 text-right">
                <div style="font-size: 12px; font-weight: bold; margin-bottom: 3px;">
                  Okupansi Rombel: {{ $cnt }} / {{ $cap }} ({{ $pct }}%)
                </div>
                <div class="progress xs" style="height: 10px; margin-bottom: 10px;">
                  <div class="progress-bar {{ $pct >= 90 ? 'progress-bar-red' : ($pct >= 75 ? 'progress-bar-yellow' : 'progress-bar-green') }}" style="width: {{ min(100, $pct) }}%"></div>
                </div>
                <button type="button" class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#modal-add-student-to-class">
                  <i class="fa fa-user-plus"></i> Tambah Santri ke Rombel
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Roster Santri di Rombel Ini -->
        <div class="box box-info" style="border-top-color: #6b46c1;">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-users"></i> Daftar Santri Terdaftar (Roster Kelas)</h3>
            <div class="box-tools pull-right">
              <a href="{{ route('akademik.presensi') }}" class="btn btn-xs btn-default btn-flat" title="Presensi Harian">
                <i class="fa fa-check-square-o"></i> Presensi
              </a>
              <a href="{{ route('akademik.jadwal') }}" class="btn btn-xs btn-default btn-flat" title="Jadwal Pelajaran">
                <i class="fa fa-calendar"></i> Jadwal
              </a>
              <button class="btn btn-xs btn-default btn-flat" onclick="window.print()" title="Cetak Roster">
                <i class="fa fa-print"></i> Cetak Roster
              </button>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            @if(count($classStudents) === 0)
              <div style="padding: 40px; text-align: center;">
                <i class="fa fa-users fa-3x text-muted" style="margin-bottom: 15px;"></i>
                <h4 class="text-muted">Belum Ada Santri di Rombel Ini</h4>
                <p class="text-muted">Silakan tambahkan santri baru melalui tombol di bawah atau konversi dari PPDB.</p>
                <button type="button" class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#modal-add-student-to-class">
                  <i class="fa fa-user-plus"></i> Masukkan Santri Sekarang
                </button>
              </div>
            @else
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background: #f8fafc;">
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>NIS / NISN</th>
                    <th>Nama Lengkap Santri</th>
                    <th class="text-center" style="width: 60px;">JK</th>
                    <th>Status Asrama</th>
                    <th>Hafalan Quran</th>
                    <th class="text-center" style="width: 80px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($classStudents as $idx => $st)
                    <tr>
                      <td class="text-center">{{ $idx + 1 }}</td>
                      <td>
                        <code>{{ $st['nis'] ?? '-' }}</code><br>
                        <small class="text-muted">{{ $st['nisn'] ?? '-' }}</small>
                      </td>
                      <td>
                        <strong style="color: #2b6cb0;">{{ $st['name'] }}</strong><br>
                        <small class="text-muted"><i class="fa fa-phone"></i> Wali: {{ $st['guardian_name'] ?? '-' }}</small>
                      </td>
                      <td class="text-center">
                        @if(($st['gender'] ?? '') === 'L')
                          <span class="label label-primary" title="Laki-laki (Ikhwan)">L</span>
                        @else
                          <span class="label label-danger" title="Perempuan (Akhwat)">P</span>
                        @endif
                      </td>
                      <td>
                        @if(!empty($st['is_boarding']))
                          <span class="label label-purple" style="background:#6b46c1;"><i class="fa fa-bed"></i> Boarding</span><br>
                          <small class="text-muted">{{ $st['boarding_room_id'] ?? 'Asrama Pusat' }}</small>
                        @else
                          <span class="label label-default">Full Day</span>
                        @endif
                      </td>
                      <td>
                        <span class="badge bg-green">{{ $st['tahfidz_juz'] ?? 0 }} Juz</span><br>
                        <small class="text-muted">{{ $st['tahfidz_predicate'] ?? 'Jayyid' }}</small>
                      </td>
                      <td class="text-center irnis-actions">
                        <a href="{{ route('akademik.rombel.student.remove', ['classId' => $currentClass['id'], 'studentId' => $st['id']]) }}"
                           class="btn btn-xs btn-danger btn-flat"
                           title="Keluarkan dari Rombel"
                           onclick="return confirm('Keluarkan santri {{ $st['name'] }} dari rombel {{ $currentClass['name'] }}?');">
                          <i class="fa fa-times"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
        </div>

      @else
        <div class="callout callout-info">
          <h4>Belum Ada Rombel Dipilih</h4>
          <p>Silakan buat atau pilih rombongan belajar pada panel di sebelah kiri.</p>
        </div>
      @endif
    </div>
  </div>

  <!-- Modal Tambah Rombel Baru -->
  <div class="modal fade" id="modal-add-rombel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Rombongan Belajar Baru</h4>
        </div>
        <form action="{{ route('akademik.rombel.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kode Rombel <span class="text-danger">*</span></label>
                  <input type="text" name="id" class="form-control" placeholder="Contoh: CLS-SMA-10C" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Unit Pendidikan <span class="text-danger">*</span></label>
                  <select name="unit_id" class="form-control" required>
                    @foreach($units as $u)
                      <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Nama Rombel / Kelas <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Kelas X MIPA 3 (Boarding)" required>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Tingkat / Grade <span class="text-danger">*</span></label>
                  <input type="text" name="grade" class="form-control" placeholder="Contoh: 10" required>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Wali Kelas Terpilih <span class="text-danger">*</span></label>
                  <select name="homeroom_teacher_id" class="form-control" required>
                    @foreach($teachers as $t)
                      <option value="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['subject'] ?? 'Guru' }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Ruangan Belajar <span class="text-danger">*</span></label>
                  <input type="text" name="room" class="form-control" placeholder="Contoh: Ruang Kelas B-201" value="Ruang Kelas A-101" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kapasitas Kuota Santri <span class="text-danger">*</span></label>
                  <input type="number" name="capacity" class="form-control" value="30" min="5" max="50" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Rombel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($currentClass)
    <!-- Modal Alokasi Santri ke Rombel -->
    <div class="modal fade" id="modal-add-student-to-class" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success" style="background:#28a745; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-user-plus"></i> Alokasikan Santri ke {{ $currentClass['name'] }}</h4>
          </div>
          <form action="{{ route('akademik.rombel.student.add', ['classId' => $currentClass['id']]) }}" method="POST">
            @csrf
            <div class="modal-body">
              <div class="callout callout-info" style="margin-bottom: 15px; padding: 10px;">
                <p style="margin:0;">Pilih santri dari jenjang yang sama yang belum masuk ke rombel ini atau akan dipindahkan.</p>
              </div>

              @if(count($availableStudents) === 0)
                <p class="text-center text-muted" style="padding: 20px;">
                  Semua santri pada jenjang ini sudah terdistribusi ke rombel atau belum ada data santri lain.
                </p>
              @else
                <div class="form-group">
                  <label>Pilih Santri <span class="text-danger">*</span></label>
                  <select name="student_id" class="form-control select2" style="width: 100%;" required>
                    @foreach($availableStudents as $ast)
                      <option value="{{ $ast['id'] }}">
                        {{ $ast['name'] }} (NIS: {{ $ast['nis'] ?? '-' }}) - Rombel Sekarang: {{ $ast['class_id'] ?: 'Belum Ada' }}
                      </option>
                    @endforeach
                  </select>
                </div>
              @endif
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
              @if(count($availableStudents) > 0)
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-check"></i> Masukkan ke Rombel
                </button>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

@endsection

