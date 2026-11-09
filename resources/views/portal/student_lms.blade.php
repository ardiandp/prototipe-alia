@extends('layouts.app')

@section('title', 'e-Learning Santri (LMS)')

@push('styles')
<style>
  .lms-class-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    transition: all 0.25s ease;
    border-top: 4px solid #2b6cb0;
  }
  .lms-class-card:hover, .lms-class-card.active {
    box-shadow: 0 6px 16px rgba(43, 108, 176, 0.15);
    border-top-color: #6b46c1;
    transform: translateY(-2px);
  }
  .material-row-item {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 6px;
    padding: 12px 15px;
    margin-bottom: 10px;
    transition: background 0.2s;
  }
  .material-row-item:hover {
    background: #edf2f7;
  }
</style>
@endpush

@section('content')

    <h1>
      e-Learning & LMS Santri
      <small>Materi Pembelajaran, Video Edukasi, & Pengumpulan Tugas Mandiri</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="{{ route('portal.student') }}">Portal Santri</a></li>
      <li class="active">LMS Santri</li>
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

    <div class="row">
      <!-- Kolom Kiri: Daftar Kelas Digital Santri -->
      <div class="col-md-4">
        <h4 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1a365d;">
          <i class="fa fa-graduation-cap text-blue"></i> Kelas Digital Terdaftar
        </h4>

        @foreach($classes as $c)
          <div class="lms-class-card {{ ($activeClass['id'] ?? '') === $c['id'] ? 'active' : '' }}">
            <div class="pull-right">
              <span class="label label-primary">{{ $c['subject_code'] ?? 'MAPEL' }}</span>
            </div>
            <h4 style="margin: 0 0 5px 0; font-weight: 700;">
              <a href="{{ route('portal.student.lms', ['class_id' => $c['id'], 'student_id' => $selectedStudent['id'] ?? 'SIS-0001']) }}" style="color: #1a365d;">
                {{ $c['name'] ?? 'Mata Pelajaran' }}
              </a>
            </h4>
            <div style="font-size: 12px; color: #666; margin-bottom: 8px;">
              <i class="fa fa-user"></i> {{ $c['teacher_name'] ?? 'Ustadz Pengampu' }}
            </div>
            <div style="font-size: 11px; color: #888;">
              <i class="fa fa-users"></i> Rombel: <strong>{{ $c['class_name'] ?? 'Kelas 10 SMA' }}</strong> &nbsp;|&nbsp;
              <i class="fa fa-file-text-o"></i> Modul Terbuka
            </div>
            <div style="margin-top: 10px;">
              <a href="{{ route('portal.student.lms', ['class_id' => $c['id'], 'student_id' => $selectedStudent['id'] ?? 'SIS-0001']) }}" 
                 class="btn btn-xs {{ ($activeClass['id'] ?? '') === $c['id'] ? 'btn-primary' : 'btn-default' }}">
                <i class="fa fa-folder-open-o"></i> Masuk Kelas &raquo;
              </a>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Kolom Kanan: Detail Materi & Form Pengumpulan Tugas Kelas Terpilih -->
      <div class="col-md-8">
        @if($activeClass)
          <div class="box box-solid box-primary">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 100%);">
              <h3 class="box-title" style="font-size: 16px; font-weight: 700;">
                <i class="fa fa-book"></i> {{ $activeClass['name'] ?? 'Kelas Pembelajaran' }}
              </h3>
              <span class="pull-right" style="font-size: 12px; opacity: 0.9;">
                Pengampu: <strong>{{ $activeClass['teacher_name'] ?? ($activeClass['teacher'] ?? 'Ustadz Pengampu') }}</strong>
              </span>
            </div>
            <div class="box-body" style="padding: 20px;">

              <!-- Tab Navigasi LMS: Materi vs Tugas -->
              <div class="nav-tabs-custom" style="box-shadow: none; border: 1px solid #edf2f7; border-radius: 6px;">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_lms_materi" data-toggle="tab"><i class="fa fa-file-text-o text-blue"></i> Modul & Materi Pembelajaran</a></li>
                  <li><a href="#tab_lms_tugas" data-toggle="tab"><i class="fa fa-edit text-yellow"></i> Tugas & Lembar Kerja Mandiri</a></li>
                </ul>
                <div class="tab-content" style="padding: 15px;">

                  <!-- TAB 1: DAFTAR MATERI -->
                  <div class="tab-pane active" id="tab_lms_materi">
                    <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 12px;">Modul Bacaan & Materi Digital:</h5>
                    @forelse($classMaterials as $mat)
                      <div class="material-row-item">
                        <div class="row">
                          <div class="col-sm-9">
                            <span class="label label-info" style="font-size: 10px;">{{ strtoupper($mat['type'] ?? 'DOKUMEN') }}</span>
                            <h5 style="margin: 4px 0 2px 0; font-weight: 700; color: #1a365d;">
                              {{ $mat['title'] ?? 'Materi Pembelajaran' }}
                            </h5>
                            <p style="margin: 0; font-size: 12px; color: #555;">
                              {{ $mat['description'] ?? 'Modul materi pengayaan pembelajaran mandiri santri.' }}
                            </p>
                          </div>
                          <div class="col-sm-3 text-right" style="margin-top: 8px;">
                            <button type="button" class="btn btn-default btn-xs" onclick="alert('Mengunduh materi digital santri: {{ $mat['title'] ?? 'Materi' }}')">
                              <i class="fa fa-download"></i> Unduh Modul
                            </button>
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="material-row-item">
                        <div class="row">
                          <div class="col-sm-9">
                            <span class="label label-info">PDF MODUL</span>
                            <h5 style="margin: 4px 0 2px 0; font-weight: 700; color: #1a365d;">Bab 1: Pengantar & Landasan Keilmuan Terpadu</h5>
                            <p style="margin: 0; font-size: 12px; color: #555;">Materi silabus dan capaian kompetensi dasar semester berjalan.</p>
                          </div>
                          <div class="col-sm-3 text-right" style="margin-top: 8px;">
                            <button type="button" class="btn btn-default btn-xs" onclick="alert('Simulasi download materi PDF!')">
                              <i class="fa fa-download"></i> Unduh Modul
                            </button>
                          </div>
                        </div>
                      </div>
                    @endforelse
                  </div>

                  <!-- TAB 2: TUGAS KELAS & UPLOAD PENGUMPULAN -->
                  <div class="tab-pane" id="tab_lms_tugas">
                    <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 12px;">Tugas & Portofolio yang Harus Dikumpulkan:</h5>
                    
                    @forelse($classAssignments as $cas)
                      <div class="box box-solid box-default" style="border-left: 4px solid #f39c12; margin-bottom: 15px;">
                        <div class="box-body">
                          <div class="pull-right">
                            <span class="label label-danger">Deadline: {{ date('d F Y', strtotime($cas['due_date'] ?? date('Y-m-d'))) }}</span>
                          </div>
                          <h4 style="margin: 0 0 5px 0; font-weight: 700; color: #1a365d;">
                            {{ $cas['title'] ?? 'Tugas Pembelajaran' }}
                          </h4>
                          <p style="font-size: 12px; color: #555; margin-bottom: 12px;">
                            {{ $cas['description'] ?? 'Silakan pelajari materi lalu tulis rangkuman atau unggah berkas tugas pada formulir di bawah ini.' }}
                          </p>

                          <!-- Form Pengumpulan Tugas -->
                          <div class="well well-sm" style="background: #fafafa; margin-bottom: 0;">
                            <form action="{{ route('portal.student.tugas.submit') }}" method="POST">
                              @csrf
                              <input type="hidden" name="assignment_id" value="{{ $cas['id'] ?? 'ASG-001' }}">
                              <input type="hidden" name="student_id" value="{{ $selectedStudent['id'] ?? 'SIS-0001' }}">
                              
                              <div class="form-group">
                                <label style="font-size: 12px;">Catatan / Jawaban Santri:</label>
                                <textarea name="notes" class="form-control input-sm" rows="2" placeholder="Tuliskan ringkasan jawaban tugas atau link Google Drive/dokumen..." required></textarea>
                              </div>
                              <div class="form-group">
                                <label style="font-size: 12px;">Lampiran File Tugas (Simulasi):</label>
                                <input type="file" class="form-control input-sm">
                              </div>
                              <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-upload"></i> Kumpulkan Tugas Sekarang
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="alert alert-success" style="font-size: 12px;">
                        <i class="fa fa-check-circle"></i> Alhamdulillah! Tidak ada tugas pending untuk kelas ini. Seluruh tugas telah terselesaikan.
                      </div>
                    @endforelse

                  </div>

                </div>
              </div>

            </div>
          </div>
        @endif
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
@endsection

