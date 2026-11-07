@extends('layouts.app')

@section('title', 'Jadwal Pelajaran & Matriks Waktu Belajar')
@section('page_title', 'Jadwal Pelajaran Per Rombel')
@section('page_description', 'Matriks Waktu Belajar Mingguan, Distribusi Guru Pengampu, Alokasi Ruangan, dan Sesi Jam Pelajaran')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li><a href="{{ route('akademik.rombel') }}">Rombel</a></li>
  <li class="active">Jadwal Pelajaran</li>
@endsection

@section('content')

  <!-- Filter & Kontrol Header Bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('akademik.jadwal') }}" class="form-inline clearfix">
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
              <label style="margin-right: 8px;"><i class="fa fa-eye"></i> Tampilan:</label>
              <div class="btn-group" role="group">
                <a href="{{ route('akademik.jadwal', ['class_id' => $selectedClassId, 'view' => 'matrix']) }}"
                   class="btn btn-sm {{ $viewMode === 'matrix' ? 'btn-primary active' : 'btn-default' }}">
                  <i class="fa fa-calendar"></i> Matriks Mingguan
                </a>
                <a href="{{ route('akademik.jadwal', ['class_id' => $selectedClassId, 'view' => 'list']) }}"
                   class="btn btn-sm {{ $viewMode === 'list' ? 'btn-primary active' : 'btn-default' }}">
                  <i class="fa fa-list"></i> Tabel Rinci
                </a>
              </div>
            </div>

            <div class="pull-right">
              <button type="button" class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#modal-add-jadwal">
                <i class="fa fa-plus"></i> Tambah / Ganti Jadwal
              </button>
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Jadwal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if($currentClass)
    <!-- Header Informasi Rombel Aktif -->
    <div class="row">
      <div class="col-md-12">
        <div class="callout callout-info" style="border-left-color: #2b6cb0; background: #f0f7fd !important; color: #1a365d !important; margin-bottom: 20px;">
          <div class="row">
            <div class="col-sm-8">
              <h4 style="margin: 0 0 5px 0; font-weight: bold;">
                <i class="fa fa-calendar-check-o"></i> {{ $currentClass['name'] }}
                <span class="label label-primary" style="font-size: 11px; vertical-align: middle;">{{ $currentClass['id'] }}</span>
              </h4>
              <p style="margin: 0; font-size: 13px;">
                Ruangan Tetap: <b>{{ $currentClass['room'] ?? 'Ruang Belajar' }}</b> &bull;
                Kapasitas: <b>{{ $currentClass['student_count'] ?? 0 }} / {{ $currentClass['capacity'] ?? 30 }} Santri</b> &bull;
                Tahun Ajaran: <b>2026/2027 - Semester Ganjil</b>
              </p>
            </div>
            <div class="col-sm-4 text-right">
              <span class="badge bg-purple" style="background:#6b46c1; font-size:12px; padding:6px 12px;">
                {{ count($classTimetables) }} Sesi Jam Belajar Terjadwal
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if($viewMode === 'matrix')
      <!-- VIEW 1: Matriks Mingguan (Senin - Jumat) -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary" style="border-top-color: #2b6cb0;">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-th"></i> Matriks Kalender Jadwal Mingguan</h3>
              <div class="box-tools pull-right">
                <span class="text-muted"><i class="fa fa-info-circle"></i> Klik tombol edit pada slot untuk menyesuaikan guru & mapel</span>
              </div>
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                <thead>
                  <tr style="background: #1a365d; color: #fff;">
                    <th style="width: 140px;" class="text-center">Waktu & Sesi</th>
                    @foreach($days as $day)
                      <th class="text-center" style="width: 20%;">{{ $day }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach($timeSlots as $slotNum => $slotData)
                    <tr>
                      <td class="text-center" style="background: #f4f6f9; vertical-align: middle;">
                        <strong style="color: #2b6cb0;">{{ $slotData['time'] }}</strong><br>
                        <small class="text-muted">{{ $slotData['label'] }}</small>
                      </td>
                      @foreach($days as $day)
                        @php
                          $item = $scheduleMatrix[$day][$slotNum] ?? null;
                          $subj = $item ? collect($subjects)->firstWhere('id', $item['subject_id']) : null;
                          $tch = $item ? collect($teachers)->firstWhere('id', $item['teacher_id']) : null;
                        @endphp
                        <td style="vertical-align: top; height: 110px; background: {{ $item ? '#ffffff' : '#fafafa' }};">
                          @if($day === 'Jumat' && $slotNum > 3)
                            <div class="text-center text-muted" style="padding-top: 35px;">
                              <small><i class="fa fa-moon-o"></i> Persiapan Sholat / Diniyah</small>
                            </div>
                          @elseif($item && $subj)
                            <div style="border-left: 3px solid {{ $subj['group'] === 'Nasional' ? '#2b6cb0' : '#6b46c1' }}; padding-left: 8px; position: relative;">
                              <div style="font-weight: bold; color: #1a365d; font-size: 13px; line-height: 1.2; margin-bottom: 3px;">
                                {{ $subj['name'] }}
                              </div>
                              <div style="font-size: 11px; color: #555; margin-bottom: 2px;">
                                <i class="fa fa-user-circle-o text-muted"></i> {{ $tch['name'] ?? 'Ust. Pengampu' }}
                              </div>
                              <div style="font-size: 11px; color: #777;">
                                <i class="fa fa-map-marker text-muted"></i> {{ $item['room'] ?? $currentClass['room'] }}
                              </div>
                              <div style="margin-top: 5px;">
                                <span class="label {{ $subj['group'] === 'Nasional' ? 'label-primary' : 'label-purple' }}" style="{{ $subj['group'] !== 'Nasional' ? 'background:#6b46c1;' : '' }} font-size: 10px;">
                                  {{ $subj['short_name'] ?? 'Mapel' }}
                                </span>
                                <a href="{{ route('akademik.jadwal.destroy', ['id' => $item['id'], 'class_id' => $selectedClassId]) }}"
                                   class="pull-right text-danger"
                                   style="font-size: 12px; margin-left: 5px;"
                                   title="Hapus slot"
                                   onclick="return confirm('Kosongkan slot jadwal ini?');">
                                  <i class="fa fa-trash-o"></i>
                                </a>
                              </div>
                            </div>
                          @else
                            <div class="text-center" style="padding-top: 25px;">
                              <button type="button" class="btn btn-xs btn-default btn-flat"
                                      data-toggle="modal"
                                      data-target="#modal-add-jadwal"
                                      onclick="setScheduleSlot('{{ $day }}', '{{ $slotNum }}')">
                                <i class="fa fa-plus text-muted"></i> Isi Slot
                              </button>
                            </div>
                          @endif
                        </td>
                      @endforeach
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    @else
      <!-- VIEW 2: Tabel Rinci List Pelajaran -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary" style="border-top-color: #2b6cb0;">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Sesi Jam Pelajaran Terjadwal</h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background: #f4f6f9;">
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Hari</th>
                    <th>Jam / Waktu</th>
                    <th>Sesi</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelompok</th>
                    <th>Guru Pengampu</th>
                    <th>Ruangan</th>
                    <th style="width: 80px;" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($classTimetables as $idx => $tt)
                    @php
                      $subj = collect($subjects)->firstWhere('id', $tt['subject_id']);
                      $tch = collect($teachers)->firstWhere('id', $tt['teacher_id']);
                    @endphp
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td><strong>{{ $tt['day'] }}</strong></td>
                      <td><code>{{ $tt['time'] }}</code></td>
                      <td>{{ $tt['session_name'] ?? 'Sesi ' . $tt['slot'] }}</td>
                      <td>
                        <strong style="color: #2b6cb0;">{{ $subj['name'] ?? $tt['subject_id'] }}</strong>
                      </td>
                      <td>
                        <span class="label {{ ($subj['group'] ?? '') === 'Nasional' ? 'label-primary' : 'label-purple' }}" style="{{ ($subj['group'] ?? '') !== 'Nasional' ? 'background:#6b46c1;' : '' }}">
                          {{ $subj['group'] ?? 'Umum' }}
                        </span>
                      </td>
                      <td>
                        <strong>{{ $tch['name'] ?? 'Ust. Pengampu' }}</strong><br>
                        <small class="text-muted"><i class="fa fa-id-badge"></i> {{ $tch['nip'] ?? '-' }}</small>
                      </td>
                      <td><i class="fa fa-building-o text-muted"></i> {{ $tt['room'] ?? $currentClass['room'] }}</td>
                      <td class="text-center irnis-actions">
                        <a href="{{ route('akademik.jadwal.destroy', ['id' => $tt['id'], 'class_id' => $selectedClassId]) }}"
                           class="btn btn-xs btn-danger btn-flat"
                           title="Hapus Slot"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus slot jadwal ini?');">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                        Belum ada jadwal yang dimasukkan untuk rombel ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif

  @endif

  <!-- Modal Tambah / Modifikasi Jadwal Pelajaran -->
  <div class="modal fade" id="modal-add-jadwal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Alokasikan Sesi Jadwal Pelajaran</h4>
        </div>
        <form action="{{ route('akademik.jadwal.store') }}" method="POST">
          @csrf
          <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
          <div class="modal-body">
            <div class="callout callout-info" style="margin-bottom: 15px; padding: 10px;">
              <p style="margin: 0;">Rombel Target: <b>{{ $currentClass['name'] ?? '-' }}</b> ({{ $selectedClassId }})</p>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Hari Pembelajaran <span class="text-danger">*</span></label>
                  <select name="day" id="input-schedule-day" class="form-control" required>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sesi Jam Belajar <span class="text-danger">*</span></label>
                  <select name="slot" id="input-schedule-slot" class="form-control" required>
                    <option value="1">Sesi 1 (07:30 - 08:50)</option>
                    <option value="2">Sesi 2 (09:05 - 10:25)</option>
                    <option value="3">Sesi 3 (10:40 - 12:00)</option>
                    <option value="4">Sesi 4 (13:15 - 14:35)</option>
                    <option value="5">Sesi 5 (14:45 - 16:00)</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Mata Pelajaran <span class="text-danger">*</span></label>
              <select name="subject_id" class="form-control select2" style="width: 100%;" required>
                @foreach($subjects as $sb)
                  <option value="{{ $sb['id'] }}">{{ $sb['name'] }} ({{ $sb['group'] }})</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Guru Pengampu <span class="text-danger">*</span></label>
              <select name="teacher_id" class="form-control select2" style="width: 100%;" required>
                @foreach($teachers as $t)
                  <option value="{{ $t['id'] }}">{{ $t['name'] }} &bull; {{ $t['subject'] ?? 'Pendidik' }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Ruangan Belajar / Laboratorium <span class="text-danger">*</span></label>
              <input type="text" name="room" class="form-control" value="{{ $currentClass['room'] ?? 'Ruang Belajar' }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Pasang Jadwal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function setScheduleSlot(day, slot) {
      var daySelect = document.getElementById('input-schedule-day');
      var slotSelect = document.getElementById('input-schedule-slot');
      if (daySelect) daySelect.value = day;
      if (slotSelect) slotSelect.value = slot;
    }
  </script>

@endsection

