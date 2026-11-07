@extends('layouts.app')

@section('title', 'Kalender Akademik & Agenda Sekolah')
@section('page_title', 'Kalender Akademik Terpadu')
@section('page_description', 'Jadwal Kegiatan Belajar Mengajar, Ujian PTS/PAS, Munaqasyah Tahfidz, Agenda Pesantren, dan Masa Libur Santri')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li class="active">Kalender Akademik</li>
@endsection

@section('styles')
  <!-- FullCalendar CSS bawaan AdminLTE 2 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/fullcalendar/dist/fullcalendar.print.min.css') }}" media="print">
  <style>
    .fc-event {
      cursor: pointer;
      padding: 3px 5px;
      font-size: 11px;
      border-radius: 3px;
    }
    .fc-header-toolbar {
      margin-bottom: 1em;
    }
    .legend-item {
      display: inline-block;
      margin-right: 15px;
      font-size: 12px;
    }
    .legend-color {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 2px;
      margin-right: 5px;
      vertical-align: middle;
    }
  </style>
@endsection

@section('content')

  <!-- Metric Strip Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($events, fn($e) => $e['category'] === 'Akademik')) }}</h3>
          <p>Agenda Belajar & KBM</p>
        </div>
        <div class="icon"><i class="fa fa-graduation-cap"></i></div>
        <a href="{{ route('akademik.kalender', ['category' => 'Akademik']) }}" class="small-box-footer">Filter KBM <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($events, fn($e) => $e['category'] === 'Ujian')) }}</h3>
          <p>Periode Asesmen & Ujian</p>
        </div>
        <div class="icon"><i class="fa fa-pencil-square-o"></i></div>
        <a href="{{ route('akademik.kalender', ['category' => 'Ujian']) }}" class="small-box-footer">Jadwal Ujian <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($events, fn($e) => $e['category'] === 'Tahfidz')) }}</h3>
          <p>Munaqasyah & Ujian Tahfidz</p>
        </div>
        <div class="icon"><i class="fa fa-book"></i></div>
        <a href="{{ route('tahfidz.murojaah') }}" class="small-box-footer">Tahfidz Quran <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ count(array_filter($events, fn($e) => $e['category'] === 'Libur')) }}</h3>
          <p>Masa Libur / Kepulangan</p>
        </div>
        <div class="icon"><i class="fa fa-home"></i></div>
        <a href="{{ route('asrama.perizinan') }}" class="small-box-footer">Perizinan Asrama <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Legend & Action Bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-body" style="padding: 12px 20px;">
          <div class="row">
            <div class="col-md-8">
              <strong>Kategori Kegiatan:</strong> &nbsp;
              <span class="legend-item"><span class="legend-color" style="background: #00a65a;"></span> Akademik (KBM)</span>
              <span class="legend-item"><span class="legend-color" style="background: #f39c12;"></span> Ujian / PTS / PAS</span>
              <span class="legend-item"><span class="legend-color" style="background: #6b46c1;"></span> Tahfidz / Munaqasyah</span>
              <span class="legend-item"><span class="legend-color" style="background: #2b6cb0;"></span> Kegiatan & Peringatan</span>
              <span class="legend-item"><span class="legend-color" style="background: #00c0ef;"></span> Rapor / Pertemuan Wali</span>
              <span class="legend-item"><span class="legend-color" style="background: #dd4b39;"></span> Libur / Kepulangan</span>
            </div>
            <div class="col-md-4 text-right">
              <button type="button" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-event">
                <i class="fa fa-plus-circle"></i> Tambah Agenda
              </button>
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Agenda
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Kolom Kiri: Kalender Visual FullCalendar -->
    <div class="col-md-8">
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-body no-padding">
          <div id="calendar" style="padding: 15px;"></div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Daftar Agenda Kronologis / List Detail -->
    <div class="col-md-4">
      <div class="box box-primary" style="border-top-color: #6b46c1;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-clock-o"></i> Agenda Mendatang</h3>
          <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($filteredEvents) }} Agenda</span>
          </div>
        </div>
        <div class="box-body" style="padding-bottom: 0;">
          <!-- Filter Kategori & Pencarian -->
          <form method="GET" action="{{ route('akademik.kalender') }}">
            <div class="form-group" style="margin-bottom: 8px;">
              <select name="category" class="form-control input-sm" onchange="this.form.submit()">
                <option value="all" {{ $selectedCategory == 'all' ? 'selected' : '' }}>-- Semua Kategori --</option>
                <option value="Akademik" {{ $selectedCategory == 'Akademik' ? 'selected' : '' }}>Akademik / KBM</option>
                <option value="Ujian" {{ $selectedCategory == 'Ujian' ? 'selected' : '' }}>Ujian / Asesmen</option>
                <option value="Tahfidz" {{ $selectedCategory == 'Tahfidz' ? 'selected' : '' }}>Tahfidz Al-Quran</option>
                <option value="Kegiatan" {{ $selectedCategory == 'Kegiatan' ? 'selected' : '' }}>Kegiatan Santri</option>
                <option value="Libur" {{ $selectedCategory == 'Libur' ? 'selected' : '' }}>Libur Santri</option>
                <option value="Rapor" {{ $selectedCategory == 'Rapor' ? 'selected' : '' }}>Pembagian Rapor</option>
              </select>
            </div>
            <div class="input-group input-group-sm" style="margin-bottom: 12px;">
              <input type="text" name="search" class="form-control" placeholder="Cari agenda..." value="{{ $search }}">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
        </div>
        <div class="box-body no-padding" style="max-height: 520px; overflow-y: auto;">
          <ul class="products-list product-list-in-box" style="padding: 0 10px;">
            @forelse($filteredEvents as $ev)
              <li class="item" style="padding: 10px 0; border-bottom: 1px dashed #e2e8f0;">
                <div class="product-info" style="margin-left: 0;">
                  <span class="pull-right badge" style="background: {{ $ev['color'] ?? '#2b6cb0' }}; font-size: 10px;">
                    {{ $ev['category'] }}
                  </span>
                  <strong style="color: #1a365d; font-size: 13px;">{{ $ev['title'] }}</strong>
                  <div class="text-muted" style="font-size: 11px; margin-top: 2px;">
                    <i class="fa fa-calendar-o text-green"></i>
                    {{ date('d M Y', strtotime($ev['start'])) }}
                    @if(!empty($ev['end']) && $ev['end'] !== $ev['start'])
                      s/d {{ date('d M Y', strtotime($ev['end'])) }}
                    @endif
                  </div>
                  <div class="text-muted" style="font-size: 11px;">
                    <i class="fa fa-map-marker text-red"></i> {{ $ev['location'] ?? 'Kampus Alia' }} &bull;
                    <i class="fa fa-users text-blue"></i> {{ $ev['target_audience'] ?? 'Semua Santri' }}
                  </div>
                  <p style="margin: 4px 0 2px 0; font-size: 11px; color: #666;">
                    {{ $ev['description'] ?? '' }}
                  </p>
                  <div class="text-right" style="margin-top: 3px;">
                    <a href="{{ route('akademik.kalender.destroy', ['id' => $ev['id']]) }}"
                       class="text-danger"
                       style="font-size: 11px;"
                       title="Hapus Agenda"
                       onclick="return confirm('Hapus agenda {{ $ev['title'] }} dari kalender?');">
                      <i class="fa fa-trash-o"></i> Hapus
                    </a>
                  </div>
                </div>
              </li>
            @empty
              <li class="item text-center text-muted" style="padding: 30px;">
                Tidak ada agenda yang cocok dengan pencarian.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Agenda Kalender Baru -->
  <div class="modal fade" id="modal-add-event" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Tambah Agenda Kalender Akademik</h4>
        </div>
        <form action="{{ route('akademik.kalender.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Agenda / Kegiatan <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Contoh: Penilaian Akhir Semester (PAS) Genap" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Mulai <span class="text-danger">*</span></label>
                  <input type="date" name="start" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Selesai (Opsional)</label>
                  <input type="date" name="end" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kategori Agenda <span class="text-danger">*</span></label>
                  <select name="category" class="form-control" required>
                    <option value="Akademik">Akademik (KBM Efektif)</option>
                    <option value="Ujian">Ujian / PTS / PAS</option>
                    <option value="Tahfidz">Tahfidz / Munaqasyah</option>
                    <option value="Kegiatan">Kegiatan Santri / Pesantren</option>
                    <option value="Rapor">Pembagian Rapor</option>
                    <option value="Libur">Libur / Cuti Santri</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Peserta / Sasaran</label>
                  <input type="text" name="target_audience" class="form-control" placeholder="Contoh: Seluruh Santri SMA" value="Seluruh Santri">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Lokasi Pelaksanaan</label>
              <input type="text" name="location" class="form-control" placeholder="Contoh: Aula Masjid Jami' Alia" value="Kampus Alia Islamic School">
            </div>

            <div class="form-group">
              <label>Keterangan / Catatan Tambahan</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Informasi detail mengenai agenda kegiatan..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Agenda
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Detail Klik Event Kalender -->
  <div class="modal fade" id="modal-event-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" id="modal-event-header" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title" id="modal-event-title">Detail Agenda</h4>
        </div>
        <div class="modal-body">
          <p><strong id="modal-event-category" class="label label-info">Kategori</strong></p>
          <p><i class="fa fa-calendar-o text-green"></i> <span id="modal-event-date">-</span></p>
          <p><i class="fa fa-map-marker text-red"></i> <span id="modal-event-location">-</span></p>
          <p id="modal-event-desc" class="text-muted" style="font-size:12px; margin-top:10px;">-</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <!-- Moment.js & FullCalendar JS bawaan AdminLTE 2 -->
  <script src="{{ asset('AdminLTE/bower_components/moment/moment.js') }}"></script>
  <script src="{{ asset('AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('AdminLTE/bower_components/fullcalendar/dist/locale/id.js') }}"></script>

  <script>
    $(function () {
      var calendarEvents = {!! json_encode($calendarJson) !!};

      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,listMonth'
        },
        buttonText: {
          today: 'Hari Ini',
          month: 'Bulan',
          week: 'Minggu',
          list: 'Daftar'
        },
        locale: 'id',
        events: calendarEvents,
        editable: false,
        droppable: false,
        eventLimit: true,
        eventClick: function(calEvent, jsEvent, view) {
          $('#modal-event-title').text(calEvent.title);
          $('#modal-event-category').text(calEvent.category || 'Kegiatan');
          $('#modal-event-header').css('background', calEvent.backgroundColor || '#2b6cb0');
          var dateStr = moment(calEvent.start).format('DD MMMM YYYY');
          if (calEvent.end) {
            var actualEnd = moment(calEvent.end).subtract(1, 'days');
            if (actualEnd.isAfter(moment(calEvent.start))) {
              dateStr += ' s/d ' + actualEnd.format('DD MMMM YYYY');
            }
          }
          $('#modal-event-date').text(dateStr);
          $('#modal-event-location').text(calEvent.location || 'Kampus Alia Islamic School');
          $('#modal-event-desc').text(calEvent.description || 'Tidak ada catatan tambahan.');
          $('#modal-event-detail').modal('show');
        }
      });
    });
  </script>
@endsection

