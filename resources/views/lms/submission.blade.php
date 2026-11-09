@extends('layouts.app')

@section('title', 'Monitoring Submisi: ' . $task['title'])
@section('page_title', 'Monitoring & Penilaian Submisi')
@section('page_description', $task['title'] . ' | ' . ($dc['name'] ?? 'Kelas Digital'))

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">LMS</a></li>
  <li><a href="{{ route('lms.tugas') }}">Tugas</a></li>
  <li class="active">Monitoring Submisi</li>
@endsection

@section('content')

  <!-- Metric Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ $submittedCount }} / {{ $totalStudents }}</h3>
          <p>Santri Sudah Mengumpulkan</p>
        </div>
        <div class="icon"><i class="fa fa-upload"></i></div>
        @php
          $pct = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
        @endphp
        <span class="small-box-footer">{{ $pct }}% Tingkat Partisipasi</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $gradedCount }}</h3>
          <p>Sudah Dinilai Guru</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <span class="small-box-footer">{{ max(0, $submittedCount - $gradedCount) }} Menunggu Koreksi</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ $avgScore }}</h3>
          <p>Rata-Rata Nilai Tugas</p>
        </div>
        <div class="icon"><i class="fa fa-line-chart"></i></div>
        <span class="small-box-footer">Maksimal: {{ $task['max_score'] }} Poin</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $maxScore }}</h3>
          <p>Nilai Tertinggi Submisi</p>
        </div>
        <div class="icon"><i class="fa fa-trophy"></i></div>
        <span class="small-box-footer">Bobot Rapor: {{ $task['weight_pct'] }}%</span>
      </div>
    </div>
  </div>

  <!-- Detail Tugas Banner -->
  <div class="row">
    <div class="col-md-12">
      <div class="callout callout-info" style="border-left-color: #2b6cb0; background: #f0f7fd !important; color: #1a365d !important; margin-bottom: 20px;">
        <div class="row">
          <div class="col-sm-8">
            <h4 style="margin: 0 0 5px 0; font-weight: bold;">
              <i class="fa fa-tasks"></i> {{ $task['title'] }}
            </h4>
            <p style="margin: 0 0 8px 0; font-size: 13px;">
              <b>Kelas Digital:</b> {{ $dc['name'] ?? '-' }} &bull;
              <b>Guru Pengampu:</b> {{ $teacher['name'] ?? 'Ust. Pengampu' }} &bull;
              <b>Batas Waktu (Deadline):</b> <code style="color: #c7254e;">{{ date('d F Y, H:i', strtotime($task['deadline'])) }} WIB</code>
            </p>
            <p style="margin: 0; font-size: 12px; color: #555;">
              <b>Instruksi Tugas:</b> {{ $task['description'] }}
            </p>
          </div>
          <div class="col-sm-4 text-right">
            @if(!empty($task['attachment_name']))
              <button class="btn btn-sm btn-default btn-flat" onclick="alert('Simulasi unduhan panduan: {{ $task['attachment_name'] }}')">
                <i class="fa fa-paperclip"></i> Unduh Lampiran Panduan
              </button>
            @endif
            <button class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0; margin-left: 5px;" data-toggle="modal" data-target="#modal-simulate-upload">
              <i class="fa fa-upload"></i> Simulasi Kumpul Tugas
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Roster Submisi & Lembar Penilaian Santri -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list"></i> Lembar Penilaian Submisi Santri</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-xs btn-default btn-flat" onclick="window.print()">
              <i class="fa fa-print"></i> Cetak Lembar Nilai Tugas
            </button>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr style="background: #f4f6f9;">
                <th style="width: 40px;" class="text-center">No</th>
                <th>NIS</th>
                <th>Nama Lengkap Santri</th>
                <th>Status Asrama</th>
                <th class="text-center" style="width: 140px;">Status Submisi</th>
                <th>Berkas Jawaban Terkumpul</th>
                <th class="text-center" style="width: 90px;">Nilai Skor</th>
                <th>Catatan / Feedback Guru</th>
                <th style="width: 110px;" class="text-center">Aksi Penilaian</th>
              </tr>
            </thead>
            <tbody>
              @forelse($classStudents as $idx => $st)
                @php
                  $sub = $submissionsMap[$st['id']] ?? null;
                  $hasSub = !empty($sub);
                  $isGraded = $hasSub && ($sub['status'] === 'dinilai');
                @endphp
                <tr>
                  <td class="text-center">{{ $idx + 1 }}</td>
                  <td><code>{{ $st['nis'] ?? '-' }}</code></td>
                  <td>
                    <strong style="color: #2b6cb0;">{{ $st['name'] }}</strong>
                  </td>
                  <td>
                    @if(!empty($st['is_boarding']))
                      <span class="label label-purple" style="background:#6b46c1;"><i class="fa fa-bed"></i> Boarding</span>
                    @else
                      <span class="label label-default">Full Day</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($isGraded)
                      <span class="label label-success" style="font-size: 11px;"><i class="fa fa-check"></i> Sudah Dinilai</span>
                    @elseif($hasSub)
                      <span class="label label-warning" style="font-size: 11px;"><i class="fa fa-clock-o"></i> Menunggu Nilai</span>
                    @else
                      <span class="label label-danger" style="font-size: 11px;"><i class="fa fa-times"></i> Belum Mengumpulkan</span>
                    @endif
                  </td>
                  <td>
                    @if($hasSub)
                      <a href="javascript:void(0);" onclick="alert('Simulasi unduhan berkas jawaban santri: {{ $sub['file_name'] }} ({{ $sub['file_size'] }})')" class="text-blue font-bold">
                        <i class="fa fa-file-pdf-o text-red"></i> {{ $sub['file_name'] }}
                      </a>
                      <br>
                      <small class="text-muted">
                        <i class="fa fa-clock-o"></i> {{ date('d M Y, H:i', strtotime($sub['submitted_at'])) }} ({{ $sub['file_size'] }})
                      </small>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($isGraded)
                      <span class="badge bg-green" style="font-size: 14px; font-weight: bold;">
                        {{ $sub['score'] }}
                      </span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td style="font-size: 12px;">
                    {{ $sub['feedback'] ?? ($hasSub ? 'Menunggu koreksi pengampu.' : 'Belum mengumpulkan berkas.') }}
                  </td>
                  <td class="text-center irnis-actions">
                    <!-- Tombol Nilai / Buka Modal Grading -->
                    <button class="btn btn-xs btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-grade-{{ $st['id'] }}">
                      <i class="fa fa-pencil"></i> {{ $isGraded ? 'Edit Nilai' : 'Beri Nilai' }}
                    </button>
                  </td>
                </tr>

                <!-- Modal Penilaian Submisi Per Santri -->
                <div class="modal fade" id="modal-grade-{{ $st['id'] }}" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
                        <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Penilaian Tugas: {{ $st['name'] }}</h4>
                      </div>
                      <form action="{{ route('lms.tugas.grade', ['assignmentId' => $task['id'], 'studentId' => $st['id']]) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                          <div class="callout callout-info" style="border-left-color: #2b6cb0; background: #f0f7fd; color: #1a365d; margin-bottom: 15px;">
                            <p style="margin:0;">Santri: <b>{{ $st['name'] }}</b> (NIS: {{ $st['nis'] }})</p>
                            <p style="margin:0;">Tugas: <b>{{ $task['title'] }}</b> &bull; Maksimal: <b>{{ $task['max_score'] }} Poin</b></p>
                            @if($hasSub)
                              <p style="margin:5px 0 0 0;">
                                Berkas Terlampir: <code>{{ $sub['file_name'] }}</code>
                                <a href="javascript:void(0);" onclick="alert('Membuka file jawaban santri {{ $sub['file_name'] }}')" class="btn btn-xs btn-default btn-flat" style="margin-left: 5px;">
                                  <i class="fa fa-eye"></i> Periksa Berkas
                                </a>
                              </p>
                            @else
                              <p style="margin:5px 0 0 0; color:#dd4b39;">
                                <i class="fa fa-info-circle"></i> Santri belum mengunggah file. Memberikan nilai sekarang akan otomatis membuat submisi terverifikasi.
                              </p>
                            @endif
                          </div>

                          <div class="form-group">
                            <label>Nilai Skor (0 - {{ $task['max_score'] }}) <span class="text-danger">*</span></label>
                            <input type="number" step="1" name="score" class="form-control" value="{{ $sub['score'] ?? 85 }}" min="0" max="{{ $task['max_score'] }}" required>
                          </div>

                          <div class="form-group">
                            <label>Catatan Koreksi / Feedback Evaluasi Guru</label>
                            <textarea name="feedback" class="form-control" rows="3" placeholder="Tuliskan apresiasi, koreksi jawaban, atau arahan perbaikan untuk santri...">{{ $sub['feedback'] ?? 'Analisis tugas sangat baik dan sistematis. Pertahankan prestasimu!' }}</textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-success btn-flat">
                            <i class="fa fa-save"></i> Simpan Penilaian
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                    Belum ada santri terdaftar di kelas digital ini.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Simulasi Upload Tugas Santri -->
  <div class="modal fade" id="modal-simulate-upload" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success" style="background:#28a745; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-upload"></i> Simulasi Pengumpulan Tugas Santri</h4>
        </div>
        <form action="{{ route('lms.tugas.simulate', ['assignmentId' => $task['id']]) }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="callout callout-info" style="margin-bottom: 15px; padding: 10px;">
              <p style="margin:0;">Pilih salah satu santri yang belum mengumpulkan tugas untuk mensimulasikan proses upload PR / tugas daring.</p>
            </div>

            <div class="form-group">
              <label>Pilih Santri Pengumpul <span class="text-danger">*</span></label>
              <select name="student_id" class="form-control select2" style="width: 100%;" required>
                @foreach($classStudents as $st)
                  <option value="{{ $st['id'] }}">
                    {{ $st['name'] }} (NIS: {{ $st['nis'] }}) - Status: {{ isset($submissionsMap[$st['id']]) ? 'Sudah Kumpul' : 'Belum Kumpul' }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Nama Berkas Jawaban (PDF / Doc)</label>
              <input type="text" name="file_name" class="form-control" placeholder="Contoh: Tugas-Resume-AhmadFauzan.pdf">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success btn-flat">
              <i class="fa fa-cloud-upload"></i> Kirim Berkas Submisi
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

