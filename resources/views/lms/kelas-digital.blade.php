@extends('layouts.app')

@section('title', 'LMS Kelas Digital & E-Learning')
@section('page_title', 'Kelas Digital Terintegrasi')
@section('page_description', 'Portal Pembelajaran Daring, Modul Ajar, Forum Diskusi Santri, dan Penugasan Terstruktur')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">LMS</a></li>
  <li class="active">Kelas Digital</li>
@endsection

@section('styles')
  <style>
    .lms-card {
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.12);
      transition: all 0.2s ease-in-out;
      margin-bottom: 20px;
      background: #fff;
      border: 1px solid #e2e8f0;
    }
    .lms-card:hover {
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
      transform: translateY(-2px);
    }
    .lms-card-header {
      padding: 18px 20px;
      color: #fff;
      position: relative;
    }
    .lms-card-header h4 {
      margin: 0 0 5px 0;
      font-weight: 700;
      font-size: 16px;
    }
    .lms-card-body {
      padding: 15px 20px;
    }
    .lms-card-footer {
      padding: 12px 20px;
      background: #f8fafc;
      border-top: 1px solid #edf2f7;
    }
  </style>
@endsection

@section('content')

  <!-- Metric Overview -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($classesData) }}</h3>
          <p>Kelas Digital Aktif</p>
        </div>
        <div class="icon"><i class="fa fa-laptop"></i></div>
        <a href="#lms-grid" class="small-box-footer">Lihat Kelas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column(array_column($classesData, 'info'), 'materials_count')) }}</h3>
          <p>Materi & Modul Digital</p>
        </div>
        <div class="icon"><i class="fa fa-file-text-o"></i></div>
        <a href="{{ route('lms.materi') }}" class="small-box-footer">Modul Ajar <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ array_sum(array_column(array_column($classesData, 'info'), 'assignments_count')) }}</h3>
          <p>Tugas & PR Terjadwal</p>
        </div>
        <div class="icon"><i class="fa fa-tasks"></i></div>
        <a href="{{ route('lms.tugas') }}" class="small-box-footer">Penugasan <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ array_sum(array_column(array_column($classesData, 'info'), 'quizzes_count')) }}</h3>
          <p>Kuis & Ujian CBT Online</p>
        </div>
        <div class="icon"><i class="fa fa-question-circle"></i></div>
        <a href="{{ route('lms.ujian') }}" class="small-box-footer">Ujian CBT <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Filter & Action Bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('lms.kelas-digital') }}" class="form-inline clearfix">
            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-filter"></i> Filter Rombel:</label>
              <select name="class_id" class="form-control input-sm select2" onchange="this.form.submit()">
                <option value="all" {{ $selectedClassId === 'all' ? 'selected' : '' }}>-- Semua Rombongan Belajar --</option>
                @foreach($classes as $c)
                  <option value="{{ $c['id'] }}" {{ $selectedClassId === $c['id'] ? 'selected' : '' }}>
                    {{ $c['name'] }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari nama kelas..." value="{{ $search }}">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </div>

            <div class="pull-right">
              <button type="button" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-lms">
                <i class="fa fa-plus-circle"></i> Buat Kelas Digital Baru
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Grid Card Ruang Kelas Digital -->
  <div class="row" id="lms-grid">
    @forelse($classesData as $item)
      @php
        $dc = $item['info'];
        $tch = $item['teacher'];
        $sb = $item['subject'];
        $cls = $item['class'];
      @endphp
      <div class="col-md-4 col-sm-6">
        <div class="lms-card">
          <!-- Card Header with Theme Color -->
          <div class="lms-card-header" style="background: {{ $dc['theme_color'] ?? '#2b6cb0' }};">
            <span class="pull-right badge" style="background: rgba(255,255,255,0.25); color: #fff; font-size: 11px;">
              {{ $cls['name'] ?? $dc['class_id'] }}
            </span>
            <h4>
              <i class="fa {{ $dc['icon'] ?? 'fa-book' }}"></i> {{ $dc['name'] }}
            </h4>
            <div style="font-size: 12px; opacity: 0.9;">
              <i class="fa fa-user-circle-o"></i> {{ $tch['name'] ?? 'Ust. Pengampu' }}
            </div>
          </div>

          <!-- Card Body Description & Meta -->
          <div class="lms-card-body">
            <p style="font-size: 12px; color: #555; height: 36px; overflow: hidden; margin-bottom: 12px; line-height: 1.4;">
              {{ $dc['description'] }}
            </p>

            <div style="font-size: 12px; margin-bottom: 8px;">
              <i class="fa fa-clock-o text-muted"></i> Jadwal: <b>{{ $dc['schedule_time'] }}</b>
            </div>
            <div style="font-size: 12px;">
              <i class="fa fa-key text-muted"></i> Kode Gabung: <code>{{ $dc['join_code'] }}</code>
            </div>

            <!-- Activity Pill Counters -->
            <div class="row text-center" style="margin-top: 15px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
              <div class="col-xs-4">
                <span style="font-size: 16px; font-weight: bold; color: #2b6cb0;">{{ $dc['materials_count'] }}</span><br>
                <small class="text-muted" style="font-size: 10px;">Materi</small>
              </div>
              <div class="col-xs-4">
                <span style="font-size: 16px; font-weight: bold; color: #00a65a;">{{ $dc['assignments_count'] }}</span><br>
                <small class="text-muted" style="font-size: 10px;">Tugas</small>
              </div>
              <div class="col-xs-4">
                <span style="font-size: 16px; font-weight: bold; color: #6b46c1;">{{ $item['participant_count'] }}</span><br>
                <small class="text-muted" style="font-size: 10px;">Santri</small>
              </div>
            </div>
          </div>

          <!-- Card Footer Actions -->
          <div class="lms-card-footer clearfix">
            <a href="{{ route('lms.kelas-digital.show', ['id' => $dc['id']]) }}" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-folder-open-o"></i> Masuk Kelas
            </a>

            <div class="pull-right">
              <a href="{{ route('lms.kelas-digital.destroy', ['id' => $dc['id']]) }}"
                 class="btn btn-sm btn-default text-danger btn-flat"
                 title="Tutup Kelas Digital"
                 onclick="return confirm('Tutup dan arsipkan kelas digital {{ $dc['name'] }}?');">
                <i class="fa fa-trash-o"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-md-12">
        <div class="callout callout-info text-center" style="padding: 40px;">
          <i class="fa fa-laptop fa-3x text-muted" style="margin-bottom: 15px;"></i>
          <h4>Tidak Ada Kelas Digital Ditemukan</h4>
          <p class="text-muted">Silakan buat kelas digital baru untuk memulai pembelajaran daring.</p>
          <button type="button" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-lms">
            <i class="fa fa-plus-circle"></i> Buat Kelas Digital Sekarang
          </button>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Modal Tambah Kelas Digital Baru -->
  <div class="modal fade" id="modal-add-lms" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Buka Kelas Digital Baru</h4>
        </div>
        <form action="{{ route('lms.kelas-digital.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Ruang Kelas Digital <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Fikih Muamalah & Zakat Kontemporer X" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Rombel Target <span class="text-danger">*</span></label>
                  <select name="class_id" class="form-control select2" style="width:100%;" required>
                    @foreach($classes as $c)
                      <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Mata Pelajaran <span class="text-danger">*</span></label>
                  <select name="subject_id" class="form-control select2" style="width:100%;" required>
                    @foreach($subjects as $sb)
                      <option value="{{ $sb['id'] }}">{{ $sb['name'] }} ({{ $sb['group'] }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Guru Pengampu <span class="text-danger">*</span></label>
                  <select name="teacher_id" class="form-control select2" style="width:100%;" required>
                    @foreach($teachers as $t)
                      <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jadwal Pertemuan</label>
                  <input type="text" name="schedule_time" class="form-control" placeholder="Contoh: Senin 07:30 - 08:50" value="Senin 07:30">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Tema Warna Header</label>
              <select name="theme_color" class="form-control">
                <option value="#2b6cb0">Alia Royal Blue (#2b6cb0)</option>
                <option value="#6b46c1">Alia Deep Purple (#6b46c1)</option>
                <option value="#00a65a">Islamic Green (#00a65a)</option>
                <option value="#f39c12">Amber Yellow (#f39c12)</option>
                <option value="#dd4b39">Crimson Red (#dd4b39)</option>
              </select>
            </div>

            <div class="form-group">
              <label>Deskripsi & Rencana Pembelajaran</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Tujuan pembelajaran dan target capaian santri..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Buka Kelas Digital
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

