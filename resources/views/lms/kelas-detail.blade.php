@extends('layouts.app')

@section('title', 'Ruang Kelas Digital: ' . $dc['name'])
@section('page_title', $dc['name'])
@section('page_description', 'Ruang Kelas Digital: ' . ($class['name'] ?? $dc['class_id']) . ' | Guru Pengampu: ' . ($teacher['name'] ?? 'Ust. Pengampu'))

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">LMS</a></li>
  <li><a href="{{ route('lms.kelas-digital') }}">Kelas Digital</a></li>
  <li class="active">{{ $dc['name'] }}</li>
@endsection

@section('content')

  <!-- Banner Header Kelas Digital -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid" style="background: {{ $dc['theme_color'] ?? '#2b6cb0' }}; color: #fff; border-radius: 4px; margin-bottom: 20px;">
        <div class="box-body" style="padding: 25px 30px;">
          <div class="row">
            <div class="col-sm-9">
              <span class="badge bg-navy" style="font-size: 11px; margin-bottom: 8px;">
                {{ $class['name'] ?? $dc['class_id'] }} &bull; {{ $subject['group'] ?? 'Kurikulum Terpadu' }}
              </span>
              <h2 style="margin: 5px 0 10px 0; font-weight: 800; font-size: 24px;">{{ $dc['name'] }}</h2>
              <p style="margin-bottom: 0; opacity: 0.95; font-size: 13px; max-width: 750px;">
                {{ $dc['description'] }}
              </p>
            </div>
            <div class="col-sm-3 text-right">
              <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 4px; display: inline-block; text-align: left;">
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Kode Kelas</div>
                <div style="font-size: 18px; font-weight: bold; font-family: monospace;">{{ $dc['join_code'] }}</div>
                <small><i class="fa fa-clock-o"></i> {{ $dc['schedule_time'] }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Kolom Kiri: Aliran Aktivitas / Materi & Tugas -->
    <div class="col-md-8">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-stream" data-toggle="tab"><i class="fa fa-comments-o"></i> Forum & Alur Belajar</a></li>
          <li><a href="#tab-materials" data-toggle="tab"><i class="fa fa-book"></i> Modul & Materi ({{ $dc['materials_count'] }})</a></li>
          <li><a href="#tab-tasks" data-toggle="tab"><i class="fa fa-tasks"></i> Tugas Santri ({{ $dc['assignments_count'] }})</a></li>
        </ul>

        <div class="tab-content" style="padding: 20px;">
          <!-- 1. Forum & Alur Belajar -->
          <div class="tab-pane active" id="tab-stream">
            <!-- Box Buat Pengumuman Guru -->
            <div class="box box-default" style="border: 1px solid #e2e8f0; margin-bottom: 20px;">
              <div class="box-body" style="padding: 15px;">
                <div class="media">
                  <div class="media-left">
                    <img src="{{ asset('AdminLTE/dist/img/avatar5.png') }}" class="img-circle" style="width: 42px;" alt="Avatar">
                  </div>
                  <div class="media-body">
                    <input type="text" class="form-control input-sm" placeholder="Bagikan pengumuman atau bahan diskusi dengan santri di kelas ini..." onclick="alert('Simulasi form pengumuman kelas digital.')">
                  </div>
                </div>
              </div>
            </div>

            <!-- Feed Item 1 -->
            <div class="box box-solid" style="border: 1px solid #e2e8f0; margin-bottom: 15px;">
              <div class="box-body" style="padding: 15px;">
                <div class="user-block" style="margin-bottom: 10px;">
                  <img class="img-circle" src="{{ asset('AdminLTE/dist/img/avatar5.png') }}" alt="User Image">
                  <span class="username"><a href="#">{{ $teacher['name'] ?? 'Ust. Pengampu' }}</a></span>
                  <span class="description">Diposting kemarin pukul 08:30 WIB</span>
                </div>
                <p style="margin-bottom: 10px; font-size: 13px;">
                  Assalamu'alaikum warahmatullah wabarakatuh. Silakan seluruh santri mengunduh modul pengantar materi pekan ini dan mencatat poin-poin penting sebelum pertemuan tatap muka di kelas.
                </p>
                <div class="attachment-block clearfix" style="background:#f8fafc; border:1px solid #edf2f7; padding:10px; border-radius:3px;">
                  <i class="fa fa-file-pdf-o fa-2x text-red pull-left" style="margin-right:12px; margin-top:2px;"></i>
                  <div class="attachment-pushed">
                    <h5 class="attachment-heading" style="margin:0 0 2px 0;">
                      <a href="{{ route('lms.materi') }}">Modul-Pembelajaran-Pekan-1.pdf</a>
                    </h5>
                    <div class="attachment-text text-muted" style="font-size:11px;">
                      Dokumen PDF ringkasan kaidah & latihan mandiri santri (2.4 MB)
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Feed Item 2 -->
            <div class="box box-solid" style="border: 1px solid #e2e8f0;">
              <div class="box-body" style="padding: 15px;">
                <div class="user-block" style="margin-bottom: 10px;">
                  <span class="badge bg-green pull-right">TUGAS BARU</span>
                  <img class="img-circle" src="{{ asset('AdminLTE/dist/img/avatar5.png') }}" alt="User Image">
                  <span class="username"><a href="#">{{ $teacher['name'] ?? 'Ust. Pengampu' }}</a></span>
                  <span class="description">Tenggat Pengumpulan: Ahad, 23:59 WIB</span>
                </div>
                <p style="margin-bottom: 0; font-size: 13px;">
                  <b>Tugas Praktik 1:</b> Resume 3 studi kasus fiqih ibadah kontemporer dan buat rangkuman dalil Al-Qur'an serta As-Sunnah.
                  <a href="{{ route('lms.tugas') }}" class="text-blue">Lihat Rincian Tugas & Submission &rarr;</a>
                </p>
              </div>
            </div>
          </div>

          <!-- 2. Modul & Materi -->
          <div class="tab-pane" id="tab-materials">
            <div class="text-right" style="margin-bottom: 15px;">
              <a href="{{ route('lms.materi') }}" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;">
                <i class="fa fa-plus"></i> Upload Materi Baru
              </a>
            </div>
            <ul class="products-list product-list-in-box">
              <li class="item" style="padding: 12px 0;">
                <div class="product-img">
                  <i class="fa fa-file-pdf-o fa-2x text-red"></i>
                </div>
                <div class="product-info">
                  <a href="{{ route('lms.materi') }}" class="product-title font-bold">Modul Kaidah Dasar & Pengantar Ilmu</a>
                  <span class="label label-success pull-right">Terbit</span>
                  <span class="product-description text-muted">
                    Format: PDF &bull; Diunggah oleh {{ $teacher['name'] ?? 'Ust. Pengampu' }} &bull; 2.4 MB
                  </span>
                </div>
              </li>
              <li class="item" style="padding: 12px 0;">
                <div class="product-img">
                  <i class="fa fa-video-camera fa-2x text-blue"></i>
                </div>
                <div class="product-info">
                  <a href="{{ route('lms.materi') }}" class="product-title font-bold">Rekaman Video Pembahasan & Talaqqi</a>
                  <span class="label label-success pull-right">Terbit</span>
                  <span class="product-description text-muted">
                    Format: Video MP4 / Link YouTube Unlisted &bull; Durasi 24 menit
                  </span>
                </div>
              </li>
            </ul>
          </div>

          <!-- 3. Tugas Santri -->
          <div class="tab-pane" id="tab-tasks">
            <div class="text-right" style="margin-bottom: 15px;">
              <a href="{{ route('lms.tugas') }}" class="btn btn-sm btn-success btn-flat">
                <i class="fa fa-plus"></i> Buat Tugas Baru
              </a>
            </div>
            <table class="table table-bordered table-striped">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th>Judul Tugas</th>
                  <th>Batas Pengumpulan</th>
                  <th class="text-center">Terkumpul</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <strong>Tugas Formatif 1: Analisis Ayat Ahkam</strong><br>
                    <small class="text-muted">Bobot nilai 20%</small>
                  </td>
                  <td><code>Ahad, 23:59 WIB</code></td>
                  <td class="text-center"><span class="badge bg-green">{{ count($participants) }} / {{ count($participants) }}</span></td>
                  <td class="text-center">
                    <a href="{{ route('lms.tugas') }}" class="btn btn-xs btn-primary btn-flat" style="background:#2b6cb0;">
                      <i class="fa fa-check"></i> Periksa
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Daftar Partisipan Santri & Profil Pendidik -->
    <div class="col-md-4">
      <!-- Box Pendidik -->
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-id-badge"></i> Tenaga Pendidik Pengampu</h3>
        </div>
        <div class="box-body">
          <div class="text-center" style="margin-bottom: 12px;">
            <img src="{{ asset('AdminLTE/dist/img/avatar5.png') }}" class="img-circle" style="width: 70px; border: 3px solid #e2e8f0;" alt="Avatar">
            <h4 style="margin: 8px 0 2px 0; font-weight: bold; color: #1a365d;">{{ $teacher['name'] ?? 'Ust. Pengampu' }}</h4>
            <p class="text-muted" style="margin: 0; font-size: 12px;">{{ $teacher['subject'] ?? 'Pendidik' }} &bull; {{ $teacher['employment_status'] ?? 'GTY' }}</p>
          </div>
          <table class="table table-condensed" style="font-size: 12px; margin-bottom: 0;">
            <tr><td>NIP</td><td>: <code>{{ $teacher['nip'] ?? '-' }}</code></td></tr>
            <tr><td>Email</td><td>: {{ $teacher['email'] ?? 'guru@alia.sch.id' }}</td></tr>
            <tr><td>WhatsApp</td><td>: {{ $teacher['phone'] ?? '0812xxxx' }}</td></tr>
          </table>
        </div>
      </div>

      <!-- Box Daftar Santri Partisipan -->
      <div class="box box-info" style="border-top-color: #6b46c1;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Santri Terdaftar ({{ count($participants) }})</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('akademik.rombel', ['class_id' => $dc['class_id']]) }}" class="btn btn-xs btn-default btn-flat" title="Kelola Rombel">
              <i class="fa fa-cog"></i>
            </a>
          </div>
        </div>
        <div class="box-body no-padding" style="max-height: 400px; overflow-y: auto;">
          <ul class="products-list product-list-in-box" style="padding: 0 10px;">
            @forelse($participants as $st)
              <li class="item" style="padding: 8px 0;">
                <div class="product-img">
                  <img src="{{ asset('AdminLTE/dist/img/' . ($st['avatar'] ?? 'avatar5.png')) }}" class="img-circle" alt="User Image">
                </div>
                <div class="product-info">
                  <strong style="color: #2b6cb0; font-size: 13px;">{{ $st['name'] }}</strong>
                  <span class="pull-right badge {{ !empty($st['is_boarding']) ? 'bg-purple' : 'bg-gray' }}" style="{{ !empty($st['is_boarding']) ? 'background:#6b46c1;' : '' }} font-size: 9px;">
                    {{ !empty($st['is_boarding']) ? 'Boarding' : 'Full Day' }}
                  </span>
                  <div class="text-muted" style="font-size: 11px;">
                    NIS: <code>{{ $st['nis'] ?? '-' }}</code> &bull; Tahfidz: {{ $st['tahfidz_juz'] ?? 0 }} Juz
                  </div>
                </div>
              </li>
            @empty
              <li class="item text-center text-muted" style="padding: 20px;">
                Belum ada santri terdaftar di kelas ini.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

@endsection

