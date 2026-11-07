@extends('layouts.app')

@section('title', 'Dashboard PPDB Online')
@section('page_title', 'Dashboard PPDB Online')
@section('page_description', 'Penerimaan Peserta Didik Baru TA 2027/2028 Alia Islamic School')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li class="active">Dashboard</li>
@endsection

@section('content')

  <!-- Filter Bar Unit -->
  <div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
      <div class="box box-solid" style="border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div class="box-body" style="padding: 12px 20px;">
          <form action="{{ route('ppdb.dashboard') }}" method="GET" class="form-inline">
            <span style="font-weight: 600; color: #2b6cb0; margin-right: 15px;">
              <i class="fa fa-filter"></i> Filter Unit PPDB:
            </span>

            <div class="form-group" style="margin-right: 15px;">
              <select name="unit" class="form-control input-sm select2" style="width: 240px;" onchange="this.form.submit()">
                <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Jenjang (SD, SMP, SMA)</option>
                <option value="SD" {{ $unitFilter == 'SD' ? 'selected' : '' }}>SD Islam Alia</option>
                <option value="SMP" {{ $unitFilter == 'SMP' ? 'selected' : '' }}>SMP Islam Alia</option>
                <option value="SMA" {{ $unitFilter == 'SMA' ? 'selected' : '' }}>SMA Islam Alia Boarding School</option>
              </select>
            </div>

            <button type="submit" class="btn btn-default btn-sm btn-flat"><i class="fa fa-refresh"></i> Terapkan</button>
            <span class="pull-right text-muted hidden-xs" style="margin-top: 5px;">
              <i class="fa fa-bullhorn text-blue"></i> Gelombang 1 Dibuka: 01 Oktober 2026 s/d 15 Desember 2026
            </span>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Primary Stat Cards (Corong PPDB) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ $totalRegistered }}</h3>
          <p>Formulir Pendaftar Masuk</p>
        </div>
        <div class="icon"><i class="fa fa-user-plus"></i></div>
        <a href="{{ route('ppdb.pendaftar') }}" class="small-box-footer">Semua Pendaftar <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $totalPendingDoc }}</h3>
          <p>Menunggu Verifikasi Berkas</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <a href="{{ route('ppdb.verifikasi') }}" class="small-box-footer">Verifikasi Berkas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ $totalAccepted }}</h3>
          <p>Lulus Seleksi Akademik & Tes</p>
        </div>
        <div class="icon"><i class="fa fa-trophy"></i></div>
        <a href="{{ route('ppdb.seleksi') }}" class="small-box-footer">Hasil Seleksi <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $totalReRegistered }}</h3>
          <p>Selesai Daftar Ulang (Lunas)</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <a href="{{ route('ppdb.daftar-ulang') }}" class="small-box-footer">Daftar Ulang <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Row: Kuota & Jalur Penerimaan -->
  <div class="row">
    <div class="col-md-7">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart text-blue"></i> Distribusi Pendaftar Berdasarkan Jalur Masuk</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 text-center">
              <canvas id="trackChart" height="200"></canvas>
            </div>
            <div class="col-md-6" style="padding-top: 15px;">
              <ul class="chart-legend clearfix" style="list-style: none; padding-left: 0; line-height: 2.2;">
                <li><i class="fa fa-circle text-blue"></i> <b>Jalur Reguler</b>: {{ $tracks['Reguler'] }} Pendaftar</li>
                <li><i class="fa fa-circle text-purple"></i> <b>Jalur Prestasi Tahfidz</b>: {{ $tracks['Prestasi Tahfidz'] }} Pendaftar</li>
                <li><i class="fa fa-circle text-green"></i> <b>Jalur Prestasi Akademik</b>: {{ $tracks['Prestasi Akademik'] }} Pendaftar</li>
                <li><i class="fa fa-circle text-yellow"></i> <b>Jalur Afirmasi / Yatim</b>: {{ $tracks['Afirmasi / Yatim'] }} Pendaftar</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kotak Kuota & Konversi Pendaftar -->
    <div class="col-md-5">
      <div class="box box-solid box-primary" style="background:#1a365d; border-color:#1a365d;">
        <div class="box-header with-border">
          <h3 class="box-title" style="color:#fff;"><i class="fa fa-bullseye"></i> Capaian Kuota Santri Baru</h3>
        </div>
        <div class="box-body" style="background:#fff; color:#333;">
          <p><strong>Target Kuota Total:</strong> <span class="pull-right"><b>{{ $quotaTarget }} Santri Baru</b></span></p>
          <div class="progress xs" style="height: 10px; margin-bottom: 12px;">
            @php $fillPct = round(($totalReRegistered / $quotaTarget) * 100); @endphp
            <div class="progress-bar progress-bar-green" style="width: {{ $fillPct }}%"></div>
          </div>
          <p class="text-muted" style="font-size:12px;">Sudah Terpenuhi: <b>{{ $totalReRegistered }} Santri ({{ $fillPct }}%)</b></p>
          <hr style="margin: 10px 0;">
          <p><strong>Tingkat Konversi (Daftar Ulang / Pendaftar):</strong> <span class="badge bg-green pull-right" style="font-size:13px;">{{ $conversionRate }}%</span></p>
          <p class="text-muted" style="font-size:12px;">Efektivitas konversi calon santri dari pendaftaran awal hingga menyelesaikan kewajiban daftar ulang.</p>
        </div>
        <div class="box-footer" style="background:#f4f4f4;">
          <a href="{{ route('ppdb.pendaftar') }}" class="btn btn-block btn-primary btn-sm btn-flat" style="background:#2b6cb0;">
            <i class="fa fa-list"></i> Lihat Data Pendaftar Terbaru
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Pendaftar Masuk Terakhir -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-clock-o text-muted"></i> Pendaftar Terbaru Hari Ini</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('ppdb.pendaftar') }}" class="btn btn-xs btn-default">Lihat Semua Data</a>
          </div>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped table-hover">
            <thead>
              <tr style="background:#f4f6f9;">
                <th>No. Registrasi</th>
                <th>Nama Calon Santri</th>
                <th>Jenjang / Unit Sasaran</th>
                <th>Jalur Seleksi</th>
                <th>Wali Calon Santri</th>
                <th class="text-center">Status Berkas</th>
                <th class="text-center">Hasil Seleksi</th>
                <th class="text-center">Daftar Ulang</th>
                <th class="text-center">Aksi Cepat</th>
              </tr>
            </thead>
            <tbody>
              @foreach(array_slice($applicants, 0, 5) as $ap)
                <tr>
                  <td><code>{{ $ap['reg_number'] }}</code></td>
                  <td>
                    <strong style="color: #2b6cb0;">{{ $ap['name'] }}</strong>
                    <small class="text-muted d-block">
                      <i class="fa {{ $ap['gender'] == 'L' ? 'fa-male text-blue' : 'fa-female text-maroon' }}"></i> {{ $ap['gender'] == 'L' ? 'Ikhwan' : 'Akhwat' }}
                    </small>
                  </td>
                  <td><span class="label label-info">{{ $ap['unit_target'] }}</span></td>
                  <td><b>{{ $ap['track'] }}</b></td>
                  <td>
                    {{ $ap['guardian_name'] }}<br>
                    <small class="text-muted"><i class="fa fa-whatsapp text-green"></i> {{ $ap['guardian_phone'] }}</small>
                  </td>
                  <td class="text-center">
                    @if($ap['doc_status'] === 'diverifikasi')
                      <span class="label label-success"><i class="fa fa-check"></i> Diverifikasi</span>
                    @elseif($ap['doc_status'] === 'ditolak')
                      <span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>
                    @else
                      <span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($ap['selection_status'] === 'lulus')
                      <span class="label label-success">LULUS</span>
                    @elseif($ap['selection_status'] === 'tidak_lulus')
                      <span class="label label-danger">TIDAK LULUS</span>
                    @else
                      <span class="label label-default">DALAM PROSES</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <x-badge :status="$ap['payment_status']" />
                  </td>
                  <td class="text-center">
                    <a href="{{ route('ppdb.verifikasi') }}" class="btn btn-xs btn-primary btn-flat" title="Verifikasi Berkas"><i class="fa fa-check-square-o"></i> Verifikasi</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script src="{{ asset('AdminLTE/bower_components/chart.js/Chart.js') }}"></script>
<script>
  $(function () {
    // Jalur Pendaftaran Chart
    var chartCanvas = $("#trackChart").get(0).getContext("2d");
    var trackChart = new Chart(chartCanvas);
    var chartData = [
      {
        value: {{ $tracks['Reguler'] }},
        color: "#2b6cb0",
        highlight: "#3182ce",
        label: "Reguler"
      },
      {
        value: {{ $tracks['Prestasi Tahfidz'] }},
        color: "#6b46c1",
        highlight: "#805ad5",
        label: "Prestasi Tahfidz"
      },
      {
        value: {{ $tracks['Prestasi Akademik'] }},
        color: "#38a169",
        highlight: "#48bb78",
        label: "Prestasi Akademik"
      },
      {
        value: {{ $tracks['Afirmasi / Yatim'] }},
        color: "#d69e2e",
        highlight: "#ecc94b",
        label: "Afirmasi / Yatim"
      }
    ];
    var options = {
      segmentShowStroke: true,
      segmentStrokeColor: "#fff",
      segmentStrokeWidth: 2,
      percentageInnerCutout: 45,
      animationSteps: 60,
      animationEasing: "easeOutBounce",
      animateRotate: true,
      responsive: true
    };
    trackChart.Doughnut(chartData, options);
  });
</script>
@endpush

