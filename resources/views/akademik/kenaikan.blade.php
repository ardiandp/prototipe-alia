@extends('layouts.app')

@section('title', 'Simulasi Kenaikan Kelas & Kelulusan Santri')
@section('page_title', 'Simulasi Kenaikan Kelas & Kelulusan')
@section('page_description', 'Evaluasi Kriteria Ketuntasan Minimal (KKM), Rekap Kehadiran, Capaian Tahfidz, dan Pengesahan Rapat Pleno Dewan Guru')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li><a href="{{ route('akademik.rombel') }}">Rombel</a></li>
  <li class="active">Simulasi Kenaikan</li>
@endsection

@section('content')

  <!-- Metric Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $isGraduatingGrade ? $statGraduated : $statPromoted }}</h3>
          <p>{{ $isGraduatingGrade ? 'Santri Dinyatakan Lulus' : 'Santri Naik Tingkat' }}</p>
        </div>
        <div class="icon"><i class="fa fa-graduation-cap"></i></div>
        <span class="small-box-footer">Kriteria Terpenuhi</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ $statRetained }}</h3>
          <p>{{ $isGraduatingGrade ? 'Belum Memenuhi Syarat Lulus' : 'Tinggal di Kelas Asal' }}</p>
        </div>
        <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
        <span class="small-box-footer">Perlu Pembinaan Khusus</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count($classStudents) }}</h3>
          <p>Total Santri di Rombel</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <span class="small-box-footer">{{ $currentClass['name'] ?? '-' }}</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ $activeYear['name'] ?? '2026/2027' }}</h3>
          <p>Tahun Ajaran Evaluasi</p>
        </div>
        <div class="icon"><i class="fa fa-calendar"></i></div>
        <span class="small-box-footer">Semester Genap / Akhir Tahun</span>
      </div>
    </div>
  </div>

  <!-- Filter & Kontrol Header Bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('akademik.kenaikan') }}" class="form-inline clearfix">
            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-sitemap"></i> Pilih Rombel:</label>
              <select name="class_id" class="form-control input-sm select2" onchange="this.form.submit()">
                @foreach($classes as $c)
                  <option value="{{ $c['id'] }}" {{ $selectedClassId === $c['id'] ? 'selected' : '' }}>
                    {{ $c['name'] }} (Tingkat {{ $c['grade'] ?? '-' }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="pull-right">
              <button type="button" class="btn btn-sm btn-info btn-flat" onclick="setAllDecisions('{{ $isGraduatingGrade ? 'lulus' : 'naik' }}')">
                <i class="fa fa-check-double"></i> Set Semua {{ $isGraduatingGrade ? 'Lulus' : 'Naik Kelas' }}
              </button>
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Berita Acara Pleno
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if($currentClass)
    <!-- Panel Form Kenaikan Kelas -->
    <form action="{{ route('akademik.kenaikan.store') }}" method="POST">
      @csrf
      <input type="hidden" name="class_id" value="{{ $selectedClassId }}">

      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa fa-legal"></i> Sidang Pleno Kenaikan Kelas — <b>{{ $currentClass['name'] }}</b>
          </h3>
          <div class="box-tools pull-right">
            <span class="label label-primary">Wali Kelas: {{ $homeroomTeacher['name'] ?? 'Ust. Ahmad Dahlan' }}</span>
          </div>
        </div>

        <div class="box-body" style="background: #fafbfc; border-bottom: 1px solid #eee; padding: 12px 20px;">
          <p style="margin: 0; font-size: 13px; color: #444;">
            <strong>Kriteria Kenaikan / Kelulusan Alia Islamic School:</strong>
            (1) Nilai di bawah KKM tidak lebih dari 2 mata pelajaran;
            (2) Persentase kehadiran minimal 80%;
            (3) Memenuhi target tahfidz Al-Qur'an dan berkelakuan baik (akhlak mulia).
          </p>
        </div>

        <div class="box-body table-responsive no-padding">
          @if(count($classStudents) === 0)
            <div style="padding: 40px; text-align: center;">
              <p class="text-muted">Belum ada santri terdaftar di rombel ini.</p>
              <a href="{{ route('akademik.rombel', ['class_id' => $selectedClassId]) }}" class="btn btn-sm btn-primary btn-flat">
                <i class="fa fa-user-plus"></i> Kelola Roster Santri
              </a>
            </div>
          @else
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background: #1a365d; color: #fff;">
                  <th style="width: 40px;" class="text-center">No</th>
                  <th>NIS</th>
                  <th>Nama Santri</th>
                  <th class="text-center" style="width: 80px;">Rata-Rata Nilai</th>
                  <th class="text-center" style="width: 90px;">Di Bawah KKM</th>
                  <th class="text-center" style="width: 80px;">Kehadiran</th>
                  <th class="text-center" style="width: 80px;">Tahfidz</th>
                  <th class="text-center" style="width: 100px;">Rekomendasi</th>
                  <th class="text-center" style="width: 140px;">Keputusan Pleno</th>
                  <th style="width: 170px;">Rombel Tujuan</th>
                  <th style="width: 200px;">Catatan Rapat Pleno</th>
                </tr>
              </thead>
              <tbody>
                @foreach($studentPromotions as $idx => $p)
                  @php
                    $st = $p['student'];
                    $isAutoPass = ($p['recommended_status'] !== 'tinggal');
                  @endphp
                  <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><code>{{ $st['nis'] ?? '-' }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0;">{{ $st['name'] }}</strong><br>
                      <small class="text-muted">{{ $st['residence_type'] ?? 'Full Day' }}</small>
                    </td>
                    <td class="text-center">
                      <strong style="color: {{ $p['gpa'] >= 80 ? '#00a65a' : '#f39c12' }}; font-size: 14px;">
                        {{ $p['gpa'] }}
                      </strong>
                    </td>
                    <td class="text-center">
                      @if($p['below_kkm_count'] == 0)
                        <span class="label label-success">0 Mapel</span>
                      @elseif($p['below_kkm_count'] <= 2)
                        <span class="label label-warning">{{ $p['below_kkm_count'] }} Mapel</span>
                      @else
                        <span class="label label-danger">{{ $p['below_kkm_count'] }} Mapel</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="{{ $p['attendance_pct'] >= 90 ? 'text-green' : 'text-yellow' }} font-bold">
                        {{ $p['attendance_pct'] }}%
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-purple" style="background:#6b46c1;">{{ $p['tahfidz_juz'] }} Juz</span>
                    </td>
                    <td class="text-center">
                      @if($p['recommended_status'] === 'naik')
                        <span class="label label-success"><i class="fa fa-arrow-up"></i> Naik Kelas</span>
                      @elseif($p['recommended_status'] === 'lulus')
                        <span class="label label-success"><i class="fa fa-graduation-cap"></i> Lulus</span>
                      @else
                        <span class="label label-danger"><i class="fa fa-times-circle"></i> Tinggal</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <select name="decision[{{ $st['id'] }}]" class="form-control input-sm decision-select" style="font-weight: bold; color: {{ $p['decision_status'] === 'tinggal' ? '#dd4b39' : '#00a65a' }};">
                        @if($isGraduatingGrade)
                          <option value="lulus" {{ $p['decision_status'] === 'lulus' ? 'selected' : '' }}>Lulus (Alumni)</option>
                          <option value="tinggal" {{ $p['decision_status'] === 'tinggal' ? 'selected' : '' }}>Tidak Lulus</option>
                        @else
                          <option value="naik" {{ $p['decision_status'] === 'naik' ? 'selected' : '' }}>Naik Kelas</option>
                          <option value="tinggal" {{ $p['decision_status'] === 'tinggal' ? 'selected' : '' }}>Tinggal Kelas</option>
                        @endif
                      </select>
                    </td>
                    <td>
                      <input type="text" name="target_class[{{ $st['id'] }}]" class="form-control input-sm" value="{{ $p['target_class'] }}" placeholder="Kelas tujuan...">
                    </td>
                    <td>
                      <input type="text" name="notes[{{ $st['id'] }}]" class="form-control input-sm" value="{{ $p['notes'] }}" placeholder="Alasan keputusan...">
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>

        @if(count($classStudents) > 0)
          <div class="box-footer clearfix" style="background: #fdfdfd;">
            <div class="pull-left" style="line-height: 32px;">
              <span class="text-muted">
                <i class="fa fa-info-circle"></i> Keputusan yang disahkan akan otomatis dialihkan ke Buku Induk Siswa saat pergantian tahun ajaran baru.
              </span>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-success btn-flat">
                <i class="fa fa-check-circle"></i> Sahkan & Simpan Hasil Pleno
              </button>
            </div>
          </div>
        @endif
      </div>
    </form>
  @endif

  <script>
    function setAllDecisions(status) {
      $('.decision-select').val(status).trigger('change');
    }

    $(document).on('change', '.decision-select', function() {
      var val = $(this).val();
      if (val === 'tinggal') {
        $(this).css('color', '#dd4b39');
      } else {
        $(this).css('color', '#00a65a');
      }
    });
  </script>

@endsection

