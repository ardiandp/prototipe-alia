@extends('layouts.app')

@section('title', 'Tugas & Submission Santri')
@section('page_title', 'Penugasan & Submission Santri')
@section('page_description', 'Manajemen Tugas Terstruktur, Batas Waktu (Deadline), Monitoring Pengumpulan PR, dan Penilaian Santri')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">LMS</a></li>
  <li><a href="{{ route('lms.kelas-digital') }}">Kelas Digital</a></li>
  <li class="active">Tugas & PR</li>
@endsection

@section('content')

  <!-- Metric Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count($enrichedAssignments) }}</h3>
          <p>Total Penugasan Terjadwal</p>
        </div>
        <div class="icon"><i class="fa fa-tasks"></i></div>
        <a href="#tasks-table" class="small-box-footer">Daftar Tugas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column($enrichedAssignments, 'submitted_count')) }}</h3>
          <p>Total Berkas Terkumpul</p>
        </div>
        <div class="icon"><i class="fa fa-file-text-o"></i></div>
        <span class="small-box-footer">Submission Santri</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column($enrichedAssignments, 'graded_count')) }}</h3>
          <p>Sudah Dinilai Guru</p>
        </div>
        <div class="icon"><i class="fa fa-check-square-o"></i></div>
        <span class="small-box-footer">Ternilai</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          @php
            $totalSub = array_sum(array_column($enrichedAssignments, 'submitted_count'));
            $totalGrd = array_sum(array_column($enrichedAssignments, 'graded_count'));
            $pending = max(0, $totalSub - $totalGrd);
          @endphp
          <h3>{{ $pending }}</h3>
          <p>Menunggu Penilaian</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <span class="small-box-footer">Perlu Diperiksa</span>
      </div>
    </div>
  </div>

  <!-- Filter & Kontrol Action Bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('lms.tugas') }}" class="form-inline clearfix">
            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-laptop"></i> Kelas Digital:</label>
              <select name="class_id" class="form-control input-sm select2" onchange="this.form.submit()">
                <option value="all" {{ $selectedClassId === 'all' ? 'selected' : '' }}>-- Semua Kelas Digital --</option>
                @foreach($digitalClasses as $dc)
                  <option value="{{ $dc['id'] }}" {{ $selectedClassId === $dc['id'] ? 'selected' : '' }}>
                    {{ $dc['name'] }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari tugas..." value="{{ $search }}">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </div>

            <div class="pull-right">
              <button type="button" class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#modal-add-tugas">
                <i class="fa fa-plus-circle"></i> Buat Penugasan Baru
              </button>
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Daftar Tugas
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Penugasan & Monitoring Pengumpulan -->
  <div class="row" id="tasks-table">
    <div class="col-md-12">
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tasks"></i> Daftar Penugasan & Status Pengumpulan</h3>
          <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($enrichedAssignments) }} Penugasan Aktif</span>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          @if(count($enrichedAssignments) === 0)
            <div style="padding: 40px; text-align: center;">
              <i class="fa fa-tasks fa-3x text-muted" style="margin-bottom: 15px;"></i>
              <h4 class="text-muted">Tidak Ada Penugasan Ditemukan</h4>
              <p class="text-muted">Silakan buat penugasan atau PR baru untuk santri di kelas digital.</p>
              <button type="button" class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#modal-add-tugas">
                <i class="fa fa-plus-circle"></i> Buat Penugasan Sekarang
              </button>
            </div>
          @else
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background: #f4f6f9;">
                  <th style="width: 40px;" class="text-center">No</th>
                  <th>Judul Tugas & Instruksi</th>
                  <th>Kelas Digital & Mapel</th>
                  <th class="text-center" style="width: 140px;">Batas Waktu (Deadline)</th>
                  <th class="text-center" style="width: 75px;">Bobot Nilai</th>
                  <th>Lampiran Panduan</th>
                  <th class="text-center" style="width: 110px;">Progres Terkumpul</th>
                  <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($enrichedAssignments as $idx => $row)
                  @php
                    $tsk = $row['task'];
                    $dc = $row['digital_class'];
                    $sb = $row['subject'];
                    $tch = $row['teacher'];
                    $pct = ($row['total_students'] > 0) ? round(($row['submitted_count'] / $row['total_students']) * 100) : 0;
                  @endphp
                  <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $tsk['title'] }}</strong>
                      <p style="margin: 3px 0 0 0; font-size: 11px; color: #555; max-width: 320px;">
                        {{ $tsk['description'] }}
                      </p>
                    </td>
                    <td>
                      <span class="label label-info">{{ $dc['name'] ?? $tsk['digital_class_id'] }}</span><br>
                      <small class="text-muted">{{ $tch['name'] ?? 'Ust. Pengampu' }}</small>
                    </td>
                    <td class="text-center">
                      <code>{{ date('d M Y, H:i', strtotime($tsk['deadline'])) }}</code><br>
                      @if(strtotime($tsk['deadline']) > time())
                        <small class="text-green"><i class="fa fa-hourglass-half"></i> Berjalan</small>
                      @else
                        <small class="text-danger"><i class="fa fa-times"></i> Berakhir</small>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="badge bg-purple" style="background:#6b46c1;">{{ $tsk['weight_pct'] }}%</span><br>
                      <small class="text-muted">Maks: {{ $tsk['max_score'] }}</small>
                    </td>
                    <td>
                      @if(!empty($tsk['attachment_name']))
                        <a href="javascript:void(0);" onclick="alert('Simulasi unduhan panduan tugas: {{ $tsk['attachment_name'] }}')" class="text-blue">
                          <i class="fa fa-paperclip"></i> {{ $tsk['attachment_name'] }}
                        </a>
                      @else
                        <span class="text-muted">Tidak ada lampiran</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="clearfix">
                        <small class="pull-left font-bold">{{ $row['submitted_count'] }} / {{ $row['total_students'] }}</small>
                        <small class="pull-right font-bold">{{ $pct }}%</small>
                      </div>
                      <div class="progress xs" style="height: 6px; margin-top: 3px; margin-bottom: 2px;">
                        <div class="progress-bar {{ $pct >= 80 ? 'progress-bar-green' : ($pct >= 50 ? 'progress-bar-yellow' : 'progress-bar-red') }}" style="width: {{ $pct }}%"></div>
                      </div>
                      <small class="text-muted" style="font-size: 10px;">{{ $row['graded_count'] }} sudah dinilai</small>
                    </td>
                    <td class="text-center irnis-actions">
                      <!-- Tombol Buka Modal Monitoring Submisi -->
                      <button class="btn btn-xs btn-primary btn-flat" style="background:#2b6cb0;" title="Periksa Submisi Santri" data-toggle="modal" data-target="#modal-submissions-{{ $tsk['id'] }}">
                        <i class="fa fa-users"></i> Submisi
                      </button>

                      <!-- Tombol Hapus Tugas -->
                      <a href="{{ route('lms.tugas.destroy', ['id' => $tsk['id']]) }}"
                         class="btn btn-xs btn-danger btn-flat"
                         title="Hapus Tugas"
                         onclick="return confirm('Hapus penugasan {{ $tsk['title'] }}?');">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>

                  <!-- Modal Monitoring Submisi Santri -->
                  <div class="modal fade" id="modal-submissions-{{ $tsk['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-tasks"></i> Submisi Santri: {{ $tsk['title'] }}</h4>
                        </div>
                        <div class="modal-body">
                          <div class="callout callout-info" style="border-left-color: #2b6cb0; background: #f0f7fd; color: #1a365d; margin-bottom: 15px;">
                            <div class="row">
                              <div class="col-xs-8">
                                <h5 style="margin:0 0 4px 0; font-weight:bold;">Instruksi Tugas:</h5>
                                <p style="margin:0; font-size:12px;">{{ $tsk['description'] }}</p>
                              </div>
                              <div class="col-xs-4 text-right">
                                <span class="badge bg-green">{{ count($row['submissions']) }} Berkas Dikumpulkan</span>
                              </div>
                            </div>
                          </div>

                          <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                            <thead>
                              <tr style="background:#f4f6f9;">
                                <th style="width: 40px;" class="text-center">No</th>
                                <th>Nama Santri</th>
                                <th>Berkas Submission</th>
                                <th>Waktu Kirim</th>
                                <th class="text-center" style="width: 80px;">Nilai</th>
                                <th>Catatan / Feedback Guru</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse($row['submissions'] as $sIdx => $sub)
                                <tr>
                                  <td class="text-center">{{ $sIdx + 1 }}</td>
                                  <td>
                                    <strong style="color: #2b6cb0;">{{ $sub['student_name'] }}</strong>
                                  </td>
                                  <td>
                                    <a href="javascript:void(0);" onclick="alert('Simulasi unduhan berkas submission: {{ $sub['file_name'] }}')" class="text-blue">
                                      <i class="fa fa-download"></i> {{ $sub['file_name'] }}
                                    </a>
                                    <br><small class="text-muted">{{ $sub['file_size'] }}</small>
                                  </td>
                                  <td>
                                    <small class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d M Y, H:i', strtotime($sub['submitted_at'])) }}</small>
                                  </td>
                                  <td class="text-center">
                                    @if($sub['status'] === 'dinilai')
                                      <span class="badge bg-green" style="font-size: 13px;">{{ $sub['score'] }}</span>
                                    @else
                                      <span class="label label-warning">Belum Dinilai</span>
                                    @endif
                                  </td>
                                  <td style="font-size: 11px;">
                                    {{ $sub['feedback'] ?? 'Menunggu koreksi ust. pengampu.' }}
                                  </td>
                                </tr>
                              @empty
                                <tr>
                                  <td colspan="6" class="text-center text-muted" style="padding: 25px;">
                                    Belum ada santri yang mengumpulkan tugas ini.
                                  </td>
                                </tr>
                              @endforelse
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Buat Penugasan Baru -->
  <div class="modal fade" id="modal-add-tugas" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success" style="background:#28a745; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Buat Penugasan / PR Santri Baru</h4>
        </div>
        <form action="{{ route('lms.tugas.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Ruang Kelas Digital Target <span class="text-danger">*</span></label>
              <select name="digital_class_id" class="form-control select2" style="width:100%;" required>
                @foreach($digitalClasses as $dc)
                  <option value="{{ $dc['id'] }}">{{ $dc['name'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Judul Penugasan / PR <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Contoh: Tugas Mandiri: Analisis Hadits Arba'in Ke-1" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Batas Waktu Pengumpulan (Deadline) <span class="text-danger">*</span></label>
                  <input type="datetime-local" name="deadline" class="form-control" value="{{ date('Y-m-d\T23:59', strtotime('+7 days')) }}" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Nilai Maksimal <span class="text-danger">*</span></label>
                  <input type="number" name="max_score" class="form-control" value="100" min="10" max="100" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Bobot Rapor (%) <span class="text-danger">*</span></label>
                  <input type="number" name="weight_pct" class="form-control" value="20" min="5" max="50" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Nama Lampiran Panduan / Soal (Opsional)</label>
              <input type="text" name="attachment_name" class="form-control" placeholder="Contoh: Lembar-Kerja-Siswa-Bab-1.pdf">
            </div>

            <div class="form-group">
              <label>Instruksi Lengkap Pengerjaan Tugas <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan petunjuk pengerjaan tugas, format berkas yang diterima (PDF/Doc/Foto), dan kriteria penilaian..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success btn-flat">
              <i class="fa fa-save"></i> Terbitkan Penugasan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

