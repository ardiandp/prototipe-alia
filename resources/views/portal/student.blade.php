@extends('layouts.app')

@section('title', 'Portal Mandiri Santri')

@push('styles')
<style>
  .student-hero-banner {
    background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 70%, #44337a 100%);
    border-radius: 8px;
    padding: 22px;
    color: #ffffff;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(43, 108, 176, 0.2);
  }
  .student-avatar {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    object-fit: cover;
    background: #ffffff;
    box-shadow: 0 3px 8px rgba(0,0,0,0.25);
  }
  .timetable-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 10px;
    border-left: 4px solid #3182ce;
    transition: all 0.2s ease;
  }
  .timetable-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    border-left-color: #6b46c1;
  }
  .schedule-badge-time {
    background: #ebf8ff;
    color: #2b6cb0;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    display: inline-block;
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Portal Santri — Alia Islamic School
      <small>Ruang Belajar Mandiri, Jadwal Pelajaran, & e-Learning Terpadu</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="{{ route('portal.dashboard') }}">Portal</a></li>
      <li class="active">Santri</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Flash Message -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
      </div>
    @endif

    <!-- Student Hero Banner -->
    <div class="student-hero-banner">
      <div class="row">
        <div class="col-sm-8 col-xs-12">
          <div class="media">
            <div class="media-left" style="vertical-align: middle;">
              <img src="{{ asset('AdminLTE/dist/img/' . ($selectedStudent['avatar'] ?? 'avatar5.png')) }}" class="student-avatar" alt="Santri">
            </div>
            <div class="media-body" style="padding-left: 15px;">
              <span class="label label-warning" style="font-size: 11px; text-transform: uppercase;">Akun Santri Aktif</span>
              <h2 style="margin: 4px 0 2px 0; font-weight: 800; font-size: 22px;">
                {{ $selectedStudent['name'] ?? 'Ahmad Fauzan Al-Ghifari' }}
              </h2>
              <p style="margin: 0; opacity: 0.95; font-size: 13px;">
                NIS: <strong>{{ $selectedStudent['nis'] ?? '20260001' }}</strong> | 
                NISN: <strong>{{ $selectedStudent['nisn'] ?? '0089123456' }}</strong> |
                {{ $selectedStudent['unit_level'] ?? 'SMA' }} - Kelas {{ $selectedStudent['grade'] ?? '10' }} 
                ({{ $selectedStudent['class_id'] ?? 'CLS-SMA-10A' }})
              </p>
              <div style="margin-top: 10px;">
                <span class="label label-info"><i class="fa fa-bed"></i> {{ $selectedStudent['residence_type'] ?? 'Boarding' }}</span>
                <span class="label label-success"><i class="fa fa-book"></i> Tahfidz: {{ $selectedStudent['tahfidz_juz'] ?? 5 }} Juz Mutqin</span>
                <span class="label label-primary"><i class="fa fa-star"></i> IPK: {{ number_format($selectedStudent['gpa'] ?? 89.2, 1) }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-4 col-xs-12 text-right">
          <div style="margin-top: 10px;">
            <!-- Switcher Demo Santri -->
            <label style="font-size: 11px; text-transform: uppercase; color: #e2e8f0; display: block;">Ganti Profil Santri Demo:</label>
            <form action="{{ route('portal.student') }}" method="GET" class="form-inline">
              <select name="student_id" class="form-control input-sm" onchange="this.form.submit()" style="background: rgba(255,255,255,0.9); font-weight: 600; min-width: 200px;">
                @foreach($students as $st)
                  <option value="{{ $st['id'] }}" {{ ($selectedStudent['id'] ?? '') === $st['id'] ? 'selected' : '' }}>
                    {{ $st['name'] }} ({{ $st['unit_level'] }})
                  </option>
                @endforeach
              </select>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- 4 Widget Utama Santri -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="fa fa-book"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Kelas Digital Saya</span>
            <span class="info-box-number">{{ count($classes) }} Kelas</span>
            <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Materi & Tugas Terpadu</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa fa-tasks"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Tugas Aktif (Pending)</span>
            <span class="info-box-number">{{ count($myAssignments) }} Tugas</span>
            <div class="progress"><div class="progress-bar" style="width: 70%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Segera selesaikan sebelum deadline</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Presensi Hadir</span>
            <span class="info-box-number">{{ number_format($selectedStudent['attendance_pct'] ?? 98.5, 1) }}%</span>
            <div class="progress"><div class="progress-bar" style="width: 98%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Disiplin shalat & halaqah</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
          <span class="info-box-icon"><i class="fa fa-laptop"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">e-Learning Alia</span>
            <span class="info-box-number">Aktif</span>
            <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">
              <a href="{{ route('portal.student.lms') }}" style="color: #fff; text-decoration: underline;">Buka LMS &raquo;</a>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'beranda' ? 'active' : '' }}">
          <a href="{{ route('portal.student', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'beranda']) }}">
            <i class="fa fa-dashboard text-blue"></i> Jadwal Hari Ini & Pengumuman
          </a>
        </li>
        <li class="{{ $activeTab === 'jadwal' ? 'active' : '' }}">
          <a href="{{ route('portal.student', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'jadwal']) }}">
            <i class="fa fa-calendar text-green"></i> Jadwal Mingguan Lengkap
          </a>
        </li>
        <li class="{{ $activeTab === 'tugas' ? 'active' : '' }}">
          <a href="{{ route('portal.student', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001', 'tab' => 'tugas']) }}">
            <i class="fa fa-edit text-yellow"></i> Daftar Tugas & Kuis LMS
          </a>
        </li>
        <li class="pull-right">
          <a href="{{ route('portal.student.lms', ['student_id' => $selectedStudent['id'] ?? 'SIS-0001']) }}" class="btn btn-primary btn-sm" style="color: #fff; margin-top: 5px; margin-right: 10px;">
            <i class="fa fa-graduation-cap"></i> Buka Portal LMS Lengkap
          </a>
        </li>
      </ul>

      <div class="tab-content" style="padding: 20px;">

        <!-- ========================================== -->
        <!-- TAB 1: BERANDA & JADWAL HARI INI          -->
        <!-- ========================================== -->
        @if($activeTab === 'beranda')
          <div class="tab-pane active" id="tab_beranda">
            <div class="row">
              <div class="col-md-7">
                <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
                  <i class="fa fa-calendar-check-o text-blue"></i> Jadwal Pelajaran Hari Ini ({{ date('l, d F Y') }})
                </h4>

                @php
                  // Default jadwal hari ini (Senin atau hari aktif)
                  $todaySchedules = $scheduleByDay['Senin'] ?? [];
                @endphp

                @forelse($todaySchedules as $sched)
                  <div class="timetable-card">
                    <div class="row">
                      <div class="col-xs-8">
                        <span class="schedule-badge-time">
                          <i class="fa fa-clock-o"></i> {{ $sched['time_slot'] ?? '07:30 - 09:00' }}
                        </span>
                        <h4 style="margin: 6px 0 2px 0; font-weight: 700; color: #1a365d;">
                          {{ $sched['subject_name'] ?? 'Mata Pelajaran' }}
                        </h4>
                        <div style="font-size: 12px; color: #666;">
                          <i class="fa fa-user"></i> {{ $sched['teacher_name'] ?? 'Guru Pengampu' }} &nbsp;|&nbsp;
                          <i class="fa fa-map-marker text-red"></i> {{ $sched['room_name'] ?? 'Ruang Kelas' }}
                        </div>
                      </div>
                      <div class="col-xs-4 text-right">
                        <a href="{{ route('portal.student.lms') }}" class="btn btn-default btn-xs" style="margin-top: 10px;">
                          <i class="fa fa-book"></i> Materi LMS
                        </a>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Tidak ada jam pelajaran terjadwal untuk hari ini.
                  </div>
                @endforelse
              </div>

              <!-- Agenda Tugas Mandiri Segera Kumpul -->
              <div class="col-md-5">
                <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
                  <i class="fa fa-bell text-yellow"></i> Tugas Yang Harus Diselesaikan
                </h4>

                <div class="box box-solid box-default">
                  <div class="box-body no-padding">
                    <ul class="products-list product-list-in-box">
                      @foreach($myAssignments as $asg)
                        <li class="item" style="padding: 10px 15px;">
                          <div class="product-info" style="margin-left: 0;">
                            <span class="label label-danger pull-right">
                              Deadline: {{ date('d M', strtotime($asg['due_date'] ?? date('Y-m-d'))) }}
                            </span>
                            <a href="{{ route('portal.student.lms') }}" class="product-title" style="font-weight: 700; color: #1a365d;">
                              {{ $asg['title'] ?? 'Tugas Pembelajaran' }}
                            </a>
                            <span class="product-description" style="font-size: 12px; color: #666;">
                              Bobot: {{ $asg['points'] ?? 100 }} Poin | {{ $asg['type'] ?? 'Tugas Mandiri' }}
                            </span>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="box-footer text-center">
                    <a href="{{ route('portal.student.lms') }}" class="uppercase" style="font-weight: 600;">Lihat Semua Tugas di LMS &raquo;</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 2: JADWAL MINGGUAN LENGKAP            -->
        <!-- ========================================== -->
        @if($activeTab === 'jadwal')
          <div class="tab-pane active" id="tab_jadwal">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-calendar text-green"></i> Jadwal Pelajaran Mingguan Santri (Rombel {{ $selectedStudent['class_id'] ?? '10A' }})
            </h4>

            <div class="row">
              @foreach($scheduleByDay as $dayName => $dayList)
                <div class="col-md-4 col-sm-6">
                  <div class="box box-solid box-primary" style="margin-bottom: 20px;">
                    <div class="box-header with-border" style="background: #1a365d;">
                      <h3 class="box-title" style="font-size: 14px; font-weight: 700;"><i class="fa fa-calendar-o"></i> Hari {{ $dayName }}</h3>
                    </div>
                    <div class="box-body no-padding">
                      <table class="table table-striped table-condensed" style="font-size: 12px; margin-bottom: 0;">
                        @forelse($dayList as $sItem)
                          <tr>
                            <td style="width: 35%; font-weight: 600; color: #2b6cb0;">
                              <i class="fa fa-clock-o"></i> {{ $sItem['time_slot'] ?? '-' }}
                            </td>
                            <td>
                              <strong>{{ $sItem['subject_name'] ?? '-' }}</strong><br>
                              <small class="text-muted">{{ $sItem['teacher_name'] ?? '-' }}</small>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="2" class="text-center text-muted" style="padding: 15px;">Tidak ada jadwal.</td>
                          </tr>
                        @endforelse
                      </table>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 3: DAFTAR TUGAS & KUIS                -->
        <!-- ========================================== -->
        @if($activeTab === 'tugas')
          <div class="tab-pane active" id="tab_tugas">
            <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
              <i class="fa fa-tasks text-yellow"></i> Daftar Tugas & Kuis Belajar e-Learning
            </h4>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Judul Tugas / Evaluasi</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center" style="width: 130px;">Tenggat Waktu</th>
                    <th class="text-center" style="width: 90px;">Bobot Poin</th>
                    <th class="text-center" style="width: 120px;">Status Kumpul</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($myAssignments as $tIdx => $asg)
                    <tr>
                      <td class="text-center">{{ $tIdx + 1 }}</td>
                      <td>
                        <strong>{{ $asg['title'] ?? 'Tugas Pembelajaran' }}</strong><br>
                        <small class="text-muted">{{ $asg['description'] ?? 'Silakan baca instruksi sebelum mengunggah jawaban.' }}</small>
                      </td>
                      <td><span class="label label-primary">{{ $asg['subject_id'] ?? 'Mapel' }}</span></td>
                      <td class="text-center">
                        <i class="fa fa-calendar-times-o text-red"></i> {{ date('d/m/Y', strtotime($asg['due_date'] ?? date('Y-m-d'))) }}
                      </td>
                      <td class="text-center font-bold">{{ $asg['points'] ?? 100 }} Poin</td>
                      <td class="text-center">
                        <span class="label label-warning">Belum Kumpul</span>
                      </td>
                      <td class="text-center">
                        <a href="{{ route('portal.student.lms', ['assignment_id' => $asg['id'] ?? '']) }}" class="btn btn-xs btn-primary">
                          <i class="fa fa-upload"></i> Kumpulkan
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif

      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
@endsection

