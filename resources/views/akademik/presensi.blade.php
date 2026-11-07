@extends('layouts.app')

@section('title', 'Presensi Santri & Rekap Kehadiran')
@section('page_title', 'Presensi Santri')
@section('page_description', 'Pencatatan Kehadiran Harian, Izin, Sakit, Terlambat, dan Rekapitulasi Presensi Per Rombel')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li><a href="{{ route('akademik.rombel') }}">Rombel</a></li>
  <li class="active">Presensi Santri</li>
@endsection

@section('content')

  <!-- Metric Statistics Row -->
  <div class="row">
    <div class="col-lg-2 col-xs-4">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $counts['H'] }}</h3>
          <p>Hadir Tepat Waktu</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <span class="small-box-footer">Status Hadir (H)</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-4">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $counts['T'] }}</h3>
          <p>Terlambat Masuk</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <span class="small-box-footer">Status Terlambat (T)</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-4">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{ $counts['S'] }}</h3>
          <p>Sakit (UKS / Dokter)</p>
        </div>
        <div class="icon"><i class="fa fa-medkit"></i></div>
        <span class="small-box-footer">Status Sakit (S)</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-4">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ $counts['I'] }}</h3>
          <p>Izin Kepentingan</p>
        </div>
        <div class="icon"><i class="fa fa-envelope-o"></i></div>
        <span class="small-box-footer">Status Izin (I)</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-4">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ $counts['A'] }}</h3>
          <p>Tanpa Keterangan</p>
        </div>
        <div class="icon"><i class="fa fa-exclamation-circle"></i></div>
        <span class="small-box-footer">Status Alpha (A)</span>
      </div>
    </div>
    <div class="col-lg-2 col-xs-4">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          @php
            $tot = count($classStudents);
            $present = $counts['H'] + $counts['T'];
            $pct = $tot > 0 ? round(($present / $tot) * 100, 1) : 100;
          @endphp
          <h3>{{ $pct }}%</h3>
          <p>Tingkat Kehadiran</p>
        </div>
        <div class="icon"><i class="fa fa-pie-chart"></i></div>
        <span class="small-box-footer">{{ $tot }} Santri Terdaftar</span>
      </div>
    </div>
  </div>

  <!-- Filter & Kontrol Presensi -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('akademik.presensi') }}" class="form-inline clearfix">
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
              <label style="margin-right: 8px;"><i class="fa fa-calendar"></i> Tanggal Presensi:</label>
              <input type="date" name="date" class="form-control input-sm" value="{{ $selectedDate }}" onchange="this.form.submit()">
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-eye"></i> Tampilan:</label>
              <div class="btn-group" role="group">
                <a href="{{ route('akademik.presensi', ['class_id' => $selectedClassId, 'date' => $selectedDate, 'view' => 'daily']) }}"
                   class="btn btn-sm {{ $viewMode === 'daily' ? 'btn-primary active' : 'btn-default' }}">
                  <i class="fa fa-check-square-o"></i> Input Harian
                </a>
                <a href="{{ route('akademik.presensi', ['class_id' => $selectedClassId, 'date' => $selectedDate, 'view' => 'recap']) }}"
                   class="btn btn-sm {{ $viewMode === 'recap' ? 'btn-primary active' : 'btn-default' }}">
                  <i class="fa fa-table"></i> Rekap Matriks Kehadiran
                </a>
              </div>
            </div>

            <div class="pull-right">
              @if($viewMode === 'daily')
                <button type="button" class="btn btn-sm btn-info btn-flat" onclick="setAllAttendance('H')">
                  <i class="fa fa-check-all"></i> Hadir Semua
                </button>
              @endif
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Presensi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if($currentClass)
    @if($viewMode === 'daily')
      <!-- VIEW 1: Form Lembar Presensi Harian Santri -->
      <form action="{{ route('akademik.presensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="box box-primary" style="border-top-color: #2b6cb0;">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-pencil-square-o"></i> Lembar Presensi Harian — <b>{{ $currentClass['name'] }}</b>
              <span class="text-muted" style="font-size: 13px;">({{ date('l, d F Y', strtotime($selectedDate)) }})</span>
            </h3>
            <div class="box-tools pull-right">
              <span class="label label-info">Wali Kelas: {{ $currentClass['room'] ?? 'Ruang Belajar' }}</span>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            @if(count($classStudents) === 0)
              <div style="padding: 40px; text-align: center;">
                <p class="text-muted">Belum ada santri yang terdaftar di rombel ini.</p>
                <a href="{{ route('akademik.rombel', ['class_id' => $selectedClassId]) }}" class="btn btn-sm btn-primary btn-flat">
                  <i class="fa fa-user-plus"></i> Kelola Roster Santri
                </a>
              </div>
            @else
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background: #f4f6f9;">
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>NIS</th>
                    <th>Nama Santri</th>
                    <th class="text-center" style="width: 60px;">JK</th>
                    <th>Status Asrama</th>
                    <th class="text-center" style="width: 280px;">Status Kehadiran</th>
                    <th>Jam Tiba</th>
                    <th style="width: 250px;">Keterangan / Alasan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($classStudents as $idx => $st)
                    @php
                      $currentAtt = $attendanceMap[$st['id']] ?? null;
                      $curStatus = $currentAtt['status'] ?? 'H';
                      $curNote = $currentAtt['note'] ?? '';
                      $curTime = $currentAtt['time_in'] ?? '06:50';
                    @endphp
                    <tr>
                      <td class="text-center">{{ $idx + 1 }}</td>
                      <td><code>{{ $st['nis'] ?? '-' }}</code></td>
                      <td>
                        <strong style="color: #2b6cb0;">{{ $st['name'] }}</strong>
                      </td>
                      <td class="text-center">
                        <span class="label {{ ($st['gender'] ?? '') === 'L' ? 'label-primary' : 'label-danger' }}">
                          {{ $st['gender'] ?? 'L' }}
                        </span>
                      </td>
                      <td>
                        @if(!empty($st['is_boarding']))
                          <span class="label label-purple" style="background:#6b46c1;"><i class="fa fa-bed"></i> Asrama</span>
                        @else
                          <span class="label label-default">Full Day</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-xs btn-default att-btn {{ $curStatus === 'H' ? 'active btn-success' : '' }}" style="font-weight: bold; width: 50px;">
                            <input type="radio" name="attendance[{{ $st['id'] }}]" value="H" {{ $curStatus === 'H' ? 'checked' : '' }} onchange="updateBtnStyle(this)"> H
                          </label>
                          <label class="btn btn-xs btn-default att-btn {{ $curStatus === 'T' ? 'active btn-warning' : '' }}" style="font-weight: bold; width: 50px;">
                            <input type="radio" name="attendance[{{ $st['id'] }}]" value="T" {{ $curStatus === 'T' ? 'checked' : '' }} onchange="updateBtnStyle(this)"> T
                          </label>
                          <label class="btn btn-xs btn-default att-btn {{ $curStatus === 'S' ? 'active btn-info' : '' }}" style="font-weight: bold; width: 50px;">
                            <input type="radio" name="attendance[{{ $st['id'] }}]" value="S" {{ $curStatus === 'S' ? 'checked' : '' }} onchange="updateBtnStyle(this)"> S
                          </label>
                          <label class="btn btn-xs btn-default att-btn {{ $curStatus === 'I' ? 'active btn-primary' : '' }}" style="font-weight: bold; width: 50px;">
                            <input type="radio" name="attendance[{{ $st['id'] }}]" value="I" {{ $curStatus === 'I' ? 'checked' : '' }} onchange="updateBtnStyle(this)"> I
                          </label>
                          <label class="btn btn-xs btn-default att-btn {{ $curStatus === 'A' ? 'active btn-danger' : '' }}" style="font-weight: bold; width: 50px;">
                            <input type="radio" name="attendance[{{ $st['id'] }}]" value="A" {{ $curStatus === 'A' ? 'checked' : '' }} onchange="updateBtnStyle(this)"> A
                          </label>
                        </div>
                      </td>
                      <td>
                        <small class="text-muted"><i class="fa fa-clock-o"></i> {{ $curTime ?: '-' }}</small>
                      </td>
                      <td>
                        <input type="text" name="notes[{{ $st['id'] }}]" class="form-control input-sm" placeholder="Catatan (demam, izin nikah, dll)" value="{{ $curNote }}">
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
          @if(count($classStudents) > 0)
            <div class="box-footer clearfix text-right" style="background: #fdfdfd;">
              <span class="text-muted pull-left" style="line-height: 30px;">
                <i class="fa fa-info-circle"></i> Keterangan Status: <b>H</b> = Hadir, <b>T</b> = Terlambat, <b>S</b> = Sakit, <b>I</b> = Izin, <b>A</b> = Alpha.
              </span>
              <button type="submit" class="btn btn-success btn-flat">
                <i class="fa fa-save"></i> Simpan Presensi Harian
              </button>
            </div>
          @endif
        </div>
      </form>

    @else
      <!-- VIEW 2: Rekap Matriks Kehadiran Mingguan/Bulanan -->
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa fa-table"></i> Rekapitulasi Presensi Santri — <b>{{ $currentClass['name'] }}</b>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-xs btn-default btn-flat" onclick="window.print()">
              <i class="fa fa-download"></i> Ekspor / Cetak Rekap
            </button>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-bordered table-striped table-hover text-center" style="margin-bottom: 0;">
            <thead>
              <tr style="background: #1a365d; color: #fff;">
                <th rowspan="2" style="vertical-align: middle; width: 40px;">No</th>
                <th rowspan="2" style="vertical-align: middle; text-align: left;">Nama Santri</th>
                <th colspan="{{ count($recentDates) }}">Tanggal Presensi</th>
                <th colspan="5">Rekap Total</th>
                <th rowspan="2" style="vertical-align: middle; width: 80px;">% Hadir</th>
              </tr>
              <tr style="background: #2b6cb0; color: #fff;">
                @foreach($recentDates as $d)
                  <th style="font-size: 11px; width: 45px;">{{ date('d/m', strtotime($d)) }}</th>
                @endforeach
                <th style="width: 35px; background: #00a65a;">H</th>
                <th style="width: 35px; background: #f39c12;">T</th>
                <th style="width: 35px; background: #00c0ef;">S</th>
                <th style="width: 35px; background: #3c8dbc;">I</th>
                <th style="width: 35px; background: #dd4b39;">A</th>
              </tr>
            </thead>
            <tbody>
              @forelse($matrixRecap as $idx => $row)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td style="text-align: left;">
                    <strong style="color: #2b6cb0;">{{ $row['student']['name'] }}</strong><br>
                    <small class="text-muted">{{ $row['student']['nis'] }}</small>
                  </td>
                  @foreach($recentDates as $d)
                    @php
                      $st = $row['dates'][$d] ?? '-';
                      $badgeClass = ($st === 'H') ? 'bg-green' : (($st === 'T') ? 'bg-yellow' : (($st === 'S') ? 'bg-aqua' : (($st === 'I') ? 'bg-blue' : 'bg-red')));
                    @endphp
                    <td>
                      <span class="badge {{ $badgeClass }}" style="font-size: 10px; padding: 2px 5px;">{{ $st }}</span>
                    </td>
                  @endforeach
                  <td style="font-weight: bold; color: #00a65a;">{{ $row['total_H'] }}</td>
                  <td style="font-weight: bold; color: #f39c12;">{{ $row['total_T'] }}</td>
                  <td style="font-weight: bold; color: #00c0ef;">{{ $row['total_S'] }}</td>
                  <td style="font-weight: bold; color: #3c8dbc;">{{ $row['total_I'] }}</td>
                  <td style="font-weight: bold; color: #dd4b39;">{{ $row['total_A'] }}</td>
                  <td>
                    <strong class="{{ $row['percentage'] >= 90 ? 'text-green' : ($row['percentage'] >= 75 ? 'text-yellow' : 'text-red') }}">
                      {{ $row['percentage'] }}%
                    </strong>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ count($recentDates) + 8 }}" class="text-center text-muted" style="padding: 30px;">
                    Tidak ada santri di rombel ini.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @endif
  @endif

  <script>
    function updateBtnStyle(radio) {
      var container = $(radio).closest('.btn-group');
      container.find('label').removeClass('btn-success btn-warning btn-info btn-primary btn-danger active').addClass('btn-default');

      var val = $(radio).val();
      var label = $(radio).parent();
      label.addClass('active');

      if (val === 'H') label.addClass('btn-success');
      else if (val === 'T') label.addClass('btn-warning');
      else if (val === 'S') label.addClass('btn-info');
      else if (val === 'I') label.addClass('btn-primary');
      else if (val === 'A') label.addClass('btn-danger');
    }

    function setAllAttendance(status) {
      $('input[type="radio"][value="' + status + '"]').each(function() {
        this.checked = true;
        updateBtnStyle(this);
      });
    }
  </script>

@endsection

