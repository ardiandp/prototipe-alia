@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Yayasan')
@section('page_title', 'Dashboard Eksekutif')
@section('page_description', 'Tinjauan Komprehensif Manajemen & Keuangan Alia Islamic School')

@section('breadcrumbs')
  <li class="active">Dashboard</li>
@endsection

@section('content')

  <!-- Filter Bar: Unit & Tahun Ajaran -->
  <div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
      <div class="box box-solid" style="border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div class="box-body" style="padding: 12px 20px;">
          <form action="{{ route('dashboard') }}" method="GET" class="form-inline">
            <span style="font-weight: 600; color: #2b6cb0; margin-right: 15px;">
              <i class="fa fa-filter"></i> Filter Tinjauan:
            </span>

            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;">Unit Pendidikan:</label>
              <select name="unit" class="form-control input-sm select2" style="width: 220px;" onchange="this.form.submit()">
                <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Unit (SD, SMP, SMA)</option>
                @foreach($units as $u)
                  <option value="{{ $u['id'] }}" {{ $unitFilter == $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;">Tahun Ajaran:</label>
              <select name="ta" class="form-control input-sm" style="width: 180px;" onchange="this.form.submit()">
                @foreach($academicYears as $ay)
                  <option value="{{ $ay['id'] }}" {{ $academicYear == $ay['id'] ? 'selected' : '' }}>
                    {{ $ay['name'] }} {{ $ay['is_current'] ? '(Aktif)' : '' }}
                  </option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="btn btn-default btn-sm btn-flat"><i class="fa fa-refresh"></i> Terapkan</button>
            <span class="pull-right text-muted hidden-xs" style="margin-top: 5px;">
              <i class="fa fa-info-circle text-blue"></i> Data tersinkronisasi realtime lintas modul demo
            </span>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Primary Metric Cards -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <x-stat-card 
        :count="number_format($metrics['total_students'])" 
        label="Total Santri Aktif" 
        icon="fa-users" 
        bg="aqua" 
        :url="route('master.siswa')" 
      />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card 
        :count="number_format($metrics['total_teachers'])" 
        label="Guru & Tenaga Kependidikan" 
        icon="fa-graduation-cap" 
        bg="green" 
        :url="route('master.pegawai')" 
      />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card 
        :count="$metrics['attendance_rate'] . '%'" 
        label="Tingkat Kehadiran Santri" 
        icon="fa-check-circle-o" 
        bg="yellow" 
        :url="route('akademik.presensi')" 
      />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card 
        :count="number_format($metrics['boarding_residents']) . ' / ' . $metrics['boarding_capacity']" 
        label="Kapasitas Santri Asrama" 
        icon="fa-bed" 
        bg="purple" 
        :url="route('asrama.kamar')" 
      />
    </div>
  </div>

  <!-- Financial & PPDB Metric Strip -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-navy" style="background:#1a365d !important;"><i class="fa fa-university"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Penerimaan SPP Bulan Ini</span>
          <span class="info-box-number" style="font-size:16px; color:#1a365d;">{{ $metrics['spp_collected'] }}</span>
          <div class="progress" style="height:4px; margin:5px 0;">
            <div class="progress-bar bg-green" style="width: {{ $metrics['collection_rate'] }}%"></div>
          </div>
          <span class="progress-description text-muted" style="font-size:11px;">
            Efektivitas Tagihan: <b>{{ $metrics['collection_rate'] }}%</b>
          </span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Tunggakan SPP Berjalan</span>
          <span class="info-box-number" style="font-size:16px; color:#d9534f;">{{ $metrics['spp_arrears'] }}</span>
          <span class="text-muted" style="font-size:11px;">
            <a href="{{ route('keuangan.tagihan') }}" style="color:#d9534f;">Lihat 24 Santri Menunggak <i class="fa fa-arrow-right"></i></a>
          </span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-purple" style="background:#6b46c1 !important;"><i class="fa fa-book"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Rerata Hafalan Santri</span>
          <span class="info-box-number" style="font-size:16px; color:#6b46c1;">{{ $metrics['tahfidz_average_juz'] }} Juz</span>
          <span class="text-muted" style="font-size:11px;">
            Target Tahunan: <b>2 Juz / Santri</b>
          </span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-teal"><i class="fa fa-user-plus"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Progres Pendaftar PPDB</span>
          <span class="info-box-number" style="font-size:16px;">{{ $metrics['ppdb_registered'] }} <small style="font-size:11px;">/ {{ $metrics['ppdb_target'] }} Kuota</small></span>
          <div class="progress" style="height:4px; margin:5px 0;">
            <div class="progress-bar bg-teal" style="width: {{ round(($metrics['ppdb_registered'] / $metrics['ppdb_target']) * 100) }}%"></div>
          </div>
          <span class="progress-description text-muted" style="font-size:11px;">
            Lulus Seleksi: <b>{{ $metrics['ppdb_accepted'] }} Calon</b>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row">
    <!-- Student Distribution by Unit -->
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart text-blue"></i> Distribusi Santri Berdasarkan Unit</h3>
          <div class="box-tools pull-right">
            <span class="label label-primary">Total: {{ $metrics['total_students'] }}</span>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-7 text-center">
              <canvas id="studentDistChart" height="200"></canvas>
            </div>
            <div class="col-md-5" style="padding-top: 25px;">
              <ul class="chart-legend clearfix" style="list-style: none; padding-left: 0;">
                <li style="margin-bottom: 8px;"><i class="fa fa-circle text-blue"></i> <b>SD Islam Alia</b>: {{ $metrics['sd_students'] }} Santri (38%)</li>
                <li style="margin-bottom: 8px;"><i class="fa fa-circle text-green"></i> <b>SMP Islam Alia</b>: {{ $metrics['smp_students'] }} Santri (33%)</li>
                <li style="margin-bottom: 8px;"><i class="fa fa-circle text-purple"></i> <b>SMA Boarding</b>: {{ $metrics['sma_students'] }} Santri (29%)</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Finance Collection Trend -->
    <div class="col-md-6">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-line-chart text-green"></i> Tren Penerimaan SPP & Operasional (Semester Ini)</h3>
          <div class="box-tools pull-right">
            <span class="label label-success">Dalam Juta Rupiah</span>
          </div>
        </div>
        <div class="box-body">
          <canvas id="financeTrendChart" height="135"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activities & Upcoming Agenda -->
  <div class="row">
    <!-- Audit Trail / Aktivitas Terkini -->
    <div class="col-md-7">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-clock-o text-muted"></i> Aktivitas Operasional Terkini</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('dashboard') }}" class="btn btn-box-tool"><i class="fa fa-refresh"></i></a>
          </div>
        </div>
        <div class="box-body">
          <ul class="timeline" style="margin-top: 10px;">
            @foreach($recentActivities as $act)
              <li>
                <i class="fa {{ $act['icon'] }}"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> {{ $act['time'] }}</span>
                  <h3 class="timeline-header" style="font-size:14px; font-weight:600;">{{ $act['title'] }}</h3>
                  <div class="timeline-body" style="font-size:13px; color:#555;">
                    {{ $act['desc'] }}
                  </div>
                </div>
              </li>
            @endforeach
            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
        </div>
        <div class="box-footer text-center">
          <a href="{{ route('master.siswa') }}" class="text-primary font-weight-bold">Lihat Semua Riwayat Aktivitas &raquo;</a>
        </div>
      </div>
    </div>

    <!-- Upcoming Academic Agenda -->
    <div class="col-md-5">
      <div class="box box-solid box-primary" style="background:#1a365d; border-color:#1a365d;">
        <div class="box-header with-border">
          <h3 class="box-title" style="color:#fff;"><i class="fa fa-calendar-check-o"></i> Agenda & Ujian Terdekat</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('akademik.kalender') }}" class="btn btn-xs btn-default btn-flat">Buka Kalender</a>
          </div>
        </div>
        <div class="box-body" style="background:#fff; color:#333;">
          <table class="table table-hover">
            <tbody>
              @foreach($upcomingEvents as $evt)
                <tr>
                  <td style="width: 100px; font-weight: bold; color: #2b6cb0;">
                    <i class="fa fa-clock-o"></i> {{ $evt['date'] }}
                  </td>
                  <td>
                    <span>{{ $evt['title'] }}</span>
                  </td>
                  <td class="text-right" style="width: 80px;">
                    <span class="label label-{{ $evt['color'] }}">{{ $evt['badge'] }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Quick Action Panel for Foundation -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bolt text-aqua"></i> Akses Cepat Eksekutif</h3>
        </div>
        <div class="box-body">
          <a href="{{ route('master.siswa') }}" class="btn btn-app"><i class="fa fa-users text-blue"></i> Buku Induk</a>
          <a href="{{ route('ppdb.pendaftar') }}" class="btn btn-app"><i class="fa fa-user-plus text-aqua"></i> Seleksi PPDB</a>
          <a href="{{ route('keuangan.laporan') }}" class="btn btn-app"><i class="fa fa-file-text-o text-green"></i> Laporan Keuangan</a>
          <a href="{{ route('tahfidz.target') }}" class="btn btn-app"><i class="fa fa-book text-purple"></i> Monitoring Tahfidz</a>
          <a href="{{ route('ai.assistant') }}" class="btn btn-app"><i class="fa fa-magic text-yellow"></i> AI Assistant</a>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<!-- Chart.js 1.0.x (AdminLTE 2.4.2 bundled version) -->
<script src="{{ asset('AdminLTE/bower_components/chart.js/Chart.js') }}"></script>
<script>
  $(function () {
    // 1. Student Distribution Chart (Doughnut / Pie)
    var pieChartCanvas = $("#studentDistChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      {
        value: {{ $metrics['sd_students'] }},
        color: "#2b6cb0",
        highlight: "#3182ce",
        label: "SD Islam Alia"
      },
      {
        value: {{ $metrics['smp_students'] }},
        color: "#38a169",
        highlight: "#48bb78",
        label: "SMP Islam Alia"
      },
      {
        value: {{ $metrics['sma_students'] }},
        color: "#6b46c1",
        highlight: "#805ad5",
        label: "SMA Boarding School"
      }
    ];
    var pieOptions = {
      segmentShowStroke: true,
      segmentStrokeColor: "#fff",
      segmentStrokeWidth: 2,
      percentageInnerCutout: 50,
      animationSteps: 60,
      animationEasing: "easeOutBounce",
      animateRotate: true,
      responsive: true
    };
    pieChart.Doughnut(PieData, pieOptions);

    // 2. Finance Collection Trend (Bar / Line)
    var areaChartCanvas = $("#financeTrendChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas);
    var areaChartData = {
      labels: ["Juli", "Agustus", "September", "Oktober", "November (Bulan Ini)"],
      datasets: [
        {
          label: "Target SPP (Juta)",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          data: [650, 650, 670, 680, 690]
        },
        {
          label: "Realisasi Penerimaan (Juta)",
          fillColor: "rgba(43, 108, 176, 0.8)",
          strokeColor: "rgba(43, 108, 176, 1)",
          pointColor: "#2b6cb0",
          data: [635, 642, 658, 665, 642.5]
        }
      ]
    };
    var areaChartOptions = {
      scaleBeginAtZero: true,
      scaleShowGridLines: true,
      scaleGridLineColor: "rgba(0,0,0,.05)",
      scaleGridLineWidth: 1,
      barShowStroke: true,
      barStrokeWidth: 2,
      barValueSpacing: 5,
      barDatasetSpacing: 1,
      responsive: true
    };
    areaChart.Bar(areaChartData, areaChartOptions);
  });
</script>
@endpush

