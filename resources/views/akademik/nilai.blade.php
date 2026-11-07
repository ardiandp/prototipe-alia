@extends('layouts.app')

@section('title', 'Input Nilai & Penilaian Pembelajaran')
@section('page_title', 'Input & Pengelolaan Nilai Santri')
@section('page_description', 'Penilaian Harian (Formatif), Tugas, PTS, PAS, Keterampilan, dan Predikat Capaian KKM')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li><a href="{{ route('akademik.rombel') }}">Rombel</a></li>
  <li class="active">Input Nilai</li>
@endsection

@section('content')

  <!-- Summary Statistic Strip -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ $avgScore }}</h3>
          <p>Rata-Rata Nilai Rombel</p>
        </div>
        <div class="icon"><i class="fa fa-line-chart"></i></div>
        <span class="small-box-footer">KKM Mapel: {{ $currentSubject['kkm'] ?? 75 }}</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $passCount }} / {{ count($classStudents) }}</h3>
          <p>Santri Tuntas KKM</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <span class="small-box-footer">{{ $passPct }}% Ketuntasan Belajar</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ $maxScore }}</h3>
          <p>Nilai Tertinggi Kelas</p>
        </div>
        <div class="icon"><i class="fa fa-trophy"></i></div>
        <span class="small-box-footer">Predikat A</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $minScore }}</h3>
          <p>Nilai Terendah Kelas</p>
        </div>
        <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
        <span class="small-box-footer">{{ $minScore < ($currentSubject['kkm'] ?? 75) ? 'Perlu Remedial' : 'Lulus KKM' }}</span>
      </div>
    </div>
  </div>

  <!-- Filter Kelas & Mata Pelajaran -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('akademik.nilai') }}" class="form-inline clearfix">
            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-sitemap"></i> Pilih Rombel:</label>
              <select name="class_id" class="form-control input-sm select2" onchange="this.form.submit()">
                @foreach($classes as $c)
                  <option value="{{ $c['id'] }}" {{ $selectedClassId === $c['id'] ? 'selected' : '' }}>
                    {{ $c['name'] }} ({{ $c['id'] }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-book"></i> Mata Pelajaran:</label>
              <select name="subject_id" class="form-control input-sm select2" onchange="this.form.submit()">
                @foreach($subjects as $sb)
                  <option value="{{ $sb['id'] }}" {{ $selectedSubjectId === $sb['id'] ? 'selected' : '' }}>
                    {{ $sb['name'] }} (KKM: {{ $sb['kkm'] }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="pull-right">
              <a href="{{ route('akademik.rapor') }}" class="btn btn-sm btn-info btn-flat">
                <i class="fa fa-file-text-o"></i> Lihat E-Rapor
              </a>
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Leger Nilai
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if($currentClass && $currentSubject)
    <!-- Panel Formulir Input Nilai Siswa -->
    <form action="{{ route('akademik.nilai.store') }}" method="POST">
      @csrf
      <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
      <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa fa-pencil-square-o"></i> Lembar Penilaian: <b>{{ $currentSubject['name'] }}</b>
            <span class="label label-primary" style="font-size: 11px;">KKM: {{ $currentSubject['kkm'] }}</span>
          </h3>
          <div class="box-tools pull-right">
            <span class="text-muted">
              <i class="fa fa-info-circle"></i> Bobot: Tugas 40% (20%+20%), PTS 25%, PAS 35% | Teori 60% + Praktik 40%
            </span>
          </div>
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
                  <th rowspan="2" style="vertical-align: middle; width: 40px;" class="text-center">No</th>
                  <th rowspan="2" style="vertical-align: middle;">NIS</th>
                  <th rowspan="2" style="vertical-align: middle;">Nama Lengkap Santri</th>
                  <th colspan="4" class="text-center" style="background: #2b6cb0;">Asesmen Pengetahuan (Teori)</th>
                  <th class="text-center" style="background: #6b46c1; width: 85px;">Praktik</th>
                  <th rowspan="2" class="text-center" style="vertical-align: middle; width: 75px; background: #008d4c;">Nilai Akhir</th>
                  <th rowspan="2" class="text-center" style="vertical-align: middle; width: 60px;">Predikat</th>
                  <th rowspan="2" style="vertical-align: middle; width: 220px;">Capaian Kompetensi / Catatan</th>
                </tr>
                <tr style="background: #2b6cb0; color: #fff;">
                  <th style="width: 75px;" class="text-center">Tugas 1 (20%)</th>
                  <th style="width: 75px;" class="text-center">Tugas 2 (20%)</th>
                  <th style="width: 75px;" class="text-center">PTS (25%)</th>
                  <th style="width: 75px;" class="text-center">PAS (35%)</th>
                  <th style="width: 85px; background: #6b46c1;" class="text-center">Keterampilan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($classStudents as $idx => $st)
                  @php
                    $g = $gradesMap[$st['id']] ?? null;
                    $t1 = $g['tugas_1'] ?? 80;
                    $t2 = $g['tugas_2'] ?? 80;
                    $pts = $g['pts'] ?? 80;
                    $pas = $g['pas'] ?? 80;
                    $prk = $g['praktik'] ?? 80;
                    $final = $g['final_score'] ?? 80.0;
                    $pred = $g['predicate'] ?? 'B';
                    $desc = $g['description'] ?? 'Menguasai materi pembelajaran dengan baik.';
                    $isPass = ($final >= ($currentSubject['kkm'] ?? 75));
                  @endphp
                  <tr id="row-{{ $st['id'] }}">
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><code>{{ $st['nis'] ?? '-' }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0;">{{ $st['name'] }}</strong>
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="100" name="tugas_1[{{ $st['id'] }}]" class="form-control input-sm text-center score-input" data-id="{{ $st['id'] }}" value="{{ $t1 }}" required>
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="100" name="tugas_2[{{ $st['id'] }}]" class="form-control input-sm text-center score-input" data-id="{{ $st['id'] }}" value="{{ $t2 }}" required>
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="100" name="pts[{{ $st['id'] }}]" class="form-control input-sm text-center score-input" data-id="{{ $st['id'] }}" value="{{ $pts }}" required>
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="100" name="pas[{{ $st['id'] }}]" class="form-control input-sm text-center score-input" data-id="{{ $st['id'] }}" value="{{ $pas }}" required>
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="100" name="praktik[{{ $st['id'] }}]" class="form-control input-sm text-center score-input" data-id="{{ $st['id'] }}" value="{{ $prk }}" required>
                    </td>
                    <td class="text-center">
                      <span id="final-{{ $st['id'] }}" style="font-weight: bold; font-size: 14px; color: {{ $isPass ? '#00a65a' : '#dd4b39' }};">
                        {{ $final }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span id="pred-{{ $st['id'] }}" class="badge {{ $pred === 'A' ? 'bg-purple' : ($pred === 'B' ? 'bg-green' : ($pred === 'C' ? 'bg-yellow' : 'bg-red')) }}">
                        {{ $pred }}
                      </span>
                    </td>
                    <td>
                      <input type="text" name="description[{{ $st['id'] }}]" id="desc-{{ $st['id'] }}" class="form-control input-sm" value="{{ $desc }}">
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
                <i class="fa fa-info-circle"></i> Perubahan nilai pada tabel akan menghitung nilai akhir secara live di browser. Klik simpan untuk merekam perubahan ke sesi.
              </span>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-success btn-flat">
                <i class="fa fa-save"></i> Simpan Seluruh Nilai
              </button>
            </div>
          </div>
        @endif
      </div>
    </form>
  @endif

  <script>
    // Live calculation Nilai Akhir & Predikat saat guru mengetikkan nilai
    $(document).on('input', '.score-input', function () {
      var id = $(this).data('id');
      var row = $('#row-' + id);
      var t1 = parseFloat(row.find('input[name="tugas_1[' + id + ']"]').val()) || 0;
      var t2 = parseFloat(row.find('input[name="tugas_2[' + id + ']"]').val()) || 0;
      var pts = parseFloat(row.find('input[name="pts[' + id + ']"]').val()) || 0;
      var pas = parseFloat(row.find('input[name="pas[' + id + ']"]').val()) || 0;
      var prk = parseFloat(row.find('input[name="praktik[' + id + ']"]').val()) || 0;

      var kkm = {{ $currentSubject['kkm'] ?? 75 }};

      // 20% T1 + 20% T2 + 25% PTS + 35% PAS
      var knowledge = (t1 * 0.2) + (t2 * 0.2) + (pts * 0.25) + (pas * 0.35);
      var finalScore = Math.round(((knowledge * 0.6) + (prk * 0.4)) * 10) / 10;

      var pred = 'D';
      var predClass = 'bg-red';
      if (finalScore >= 90) { pred = 'A'; predClass = 'bg-purple'; }
      else if (finalScore >= 80) { pred = 'B'; predClass = 'bg-green'; }
      else if (finalScore >= kkm) { pred = 'C'; predClass = 'bg-yellow'; }

      $('#final-' + id).text(finalScore).css('color', finalScore >= kkm ? '#00a65a' : '#dd4b39');
      $('#pred-' + id).text(pred).removeClass('bg-purple bg-green bg-yellow bg-red').addClass(predClass);
    });
  </script>

@endsection

