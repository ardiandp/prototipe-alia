@extends('layouts.app')

@section('title', 'Materi & Modul Pembelajaran Digital')
@section('page_title', 'Materi Pembelajaran Digital')
@section('page_description', 'Modul Ajar PDF, Video Pembahasan, Rekaman Audio, Link Interaktif, dan Status Publikasi')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">LMS</a></li>
  <li><a href="{{ route('lms.kelas-digital') }}">Kelas Digital</a></li>
  <li class="active">Materi Pembelajaran</li>
@endsection

@section('content')

  <!-- Metric Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count($enrichedMaterials) }}</h3>
          <p>Total Modul & Materi Ajar</p>
        </div>
        <div class="icon"><i class="fa fa-book"></i></div>
        <a href="#materials-table" class="small-box-footer">Daftar Materi <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($enrichedMaterials, fn($m) => !empty($m['material']['is_published']))) }}</h3>
          <p>Materi Terbit (Dapat Diunduh)</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <span class="small-box-footer">Dapat Diakses Santri</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($enrichedMaterials, fn($m) => empty($m['material']['is_published']))) }}</h3>
          <p>Draf Belum Terbit</p>
        </div>
        <div class="icon"><i class="fa fa-edit"></i></div>
        <span class="small-box-footer">Perlu Peninjauan Guru</span>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ array_sum(array_column(array_column($enrichedMaterials, 'material'), 'downloads_count')) }}</h3>
          <p>Total Akses / Unduhan</p>
        </div>
        <div class="icon"><i class="fa fa-download"></i></div>
        <span class="small-box-footer">Interaksi Santri</span>
      </div>
    </div>
  </div>

  <!-- Filter & Kontrol Action Bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('lms.materi') }}" class="form-inline clearfix">
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
              <label style="margin-right: 8px;"><i class="fa fa-tag"></i> Format Tipe:</label>
              <select name="type" class="form-control input-sm" onchange="this.form.submit()">
                <option value="all" {{ $selectedType === 'all' ? 'selected' : '' }}>-- Semua Tipe File --</option>
                <option value="PDF" {{ $selectedType === 'PDF' ? 'selected' : '' }}>Dokumen PDF</option>
                <option value="Video" {{ $selectedType === 'Video' ? 'selected' : '' }}>Video Pembelajaran</option>
                <option value="Audio" {{ $selectedType === 'Audio' ? 'selected' : '' }}>Audio Listening</option>
                <option value="Link" {{ $selectedType === 'Link' ? 'selected' : '' }}>Tautan / Web Interaktif</option>
                <option value="Dokumen" {{ $selectedType === 'Dokumen' ? 'selected' : '' }}>Word / Office Document</option>
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari materi..." value="{{ $search }}">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </div>

            <div class="pull-right">
              <button type="button" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-materi">
                <i class="fa fa-upload"></i> Upload Materi Baru
              </button>
              <button type="button" class="btn btn-sm btn-default btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Katalog
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Daftar Materi Pembelajaran -->
  <div class="row" id="materials-table">
    <div class="col-md-12">
      <div class="box box-primary" style="border-top-color: #2b6cb0;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list"></i> Katalog Bahan Ajar & Modul Digital</h3>
          <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($enrichedMaterials) }} Modul Tersedia</span>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          @if(count($enrichedMaterials) === 0)
            <div style="padding: 40px; text-align: center;">
              <i class="fa fa-folder-open-o fa-3x text-muted" style="margin-bottom: 15px;"></i>
              <h4 class="text-muted">Tidak Ada Materi Pembelajaran</h4>
              <p class="text-muted">Silakan unggah bahan ajar PDF atau tautkan video pembelajaran untuk santri.</p>
              <button type="button" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-add-materi">
                <i class="fa fa-upload"></i> Upload Materi Sekarang
              </button>
            </div>
          @else
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background: #f4f6f9;">
                  <th style="width: 40px;" class="text-center">No</th>
                  <th>Judul Materi & Deskripsi</th>
                  <th>Kelas Digital & Mapel</th>
                  <th class="text-center" style="width: 80px;">Format</th>
                  <th>Berkas / Link Lampiran</th>
                  <th>Guru Pengunggah</th>
                  <th class="text-center" style="width: 70px;">Unduhan</th>
                  <th class="text-center" style="width: 90px;">Status</th>
                  <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($enrichedMaterials as $idx => $row)
                  @php
                    $m = $row['material'];
                    $dc = $row['digital_class'];
                    $sb = $row['subject'];
                    $tch = $row['teacher'];
                  @endphp
                  <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $m['title'] }}</strong>
                      <p style="margin: 3px 0 0 0; font-size: 11px; color: #666; max-width: 320px;">
                        {{ $m['description'] }}
                      </p>
                    </td>
                    <td>
                      <span class="label label-info">{{ $dc['name'] ?? $m['digital_class_id'] }}</span><br>
                      <small class="text-muted">{{ $sb['name'] ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                      @if($m['type'] === 'PDF')
                        <span class="label label-danger"><i class="fa fa-file-pdf-o"></i> PDF</span>
                      @elseif($m['type'] === 'Video')
                        <span class="label label-primary"><i class="fa fa-video-camera"></i> Video</span>
                      @elseif($m['type'] === 'Audio')
                        <span class="label label-warning"><i class="fa fa-headphones"></i> Audio</span>
                      @elseif($m['type'] === 'Link')
                        <span class="label label-success"><i class="fa fa-link"></i> Link</span>
                      @else
                        <span class="label label-default"><i class="fa fa-file-word-o"></i> Dok</span>
                      @endif
                    </td>
                    <td>
                      @if(!empty($m['file_name']))
                        <a href="javascript:void(0);" onclick="alert('Simulasi unduhan berkas demo: {{ $m['file_name'] }} ({{ $m['file_size'] ?? '2 MB' }})')" class="text-blue">
                          <i class="fa fa-paperclip"></i> {{ $m['file_name'] }}
                        </a>
                        <br><small class="text-muted">{{ $m['file_size'] ?? '' }}</small>
                      @elseif(!empty($m['external_url']))
                        <a href="{{ $m['external_url'] }}" target="_blank" class="text-green">
                          <i class="fa fa-external-link"></i> Buka Tautan Eksternal
                        </a>
                        @if(!empty($m['duration']))
                          <br><small class="text-muted"><i class="fa fa-clock-o"></i> {{ $m['duration'] }}</small>
                        @endif
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>
                      <strong>{{ $tch['name'] ?? 'Ust. Pengampu' }}</strong><br>
                      <small class="text-muted">{{ date('d M Y', strtotime($m['published_at'] ?? '2026-10-15')) }}</small>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-purple" style="background:#6b46c1;">{{ $m['downloads_count'] }} x</span>
                    </td>
                    <td class="text-center">
                      @if(!empty($m['is_published']))
                        <span class="label label-success" style="font-size:11px;"><i class="fa fa-check"></i> Terbit</span>
                      @else
                        <span class="label label-default" style="font-size:11px;"><i class="fa fa-clock-o"></i> Draf</span>
                      @endif
                    </td>
                    <td class="text-center irnis-actions">
                      <!-- Tombol Preview / Buka Modal Detail -->
                      <button class="btn btn-xs btn-info btn-flat" title="Lihat Materi" data-toggle="modal" data-target="#modal-view-mat-{{ $m['id'] }}">
                        <i class="fa fa-eye"></i>
                      </button>

                      <!-- Toggle Terbit / Draf -->
                      <a href="{{ route('lms.materi.toggle', ['id' => $m['id']]) }}"
                         class="btn btn-xs {{ !empty($m['is_published']) ? 'btn-warning' : 'btn-success' }} btn-flat"
                         title="{{ !empty($m['is_published']) ? 'Jadikan Draf' : 'Terbitkan' }}">
                        <i class="fa {{ !empty($m['is_published']) ? 'fa-pause' : 'fa-play' }}"></i>
                      </a>

                      <!-- Hapus Materi -->
                      <a href="{{ route('lms.materi.destroy', ['id' => $m['id']]) }}"
                         class="btn btn-xs btn-danger btn-flat"
                         title="Hapus Materi"
                         onclick="return confirm('Hapus materi pembelajaran {{ $m['title'] }}?');">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>

                  <!-- Modal Detail / Baca Materi Pembelajaran -->
                  <div class="modal fade" id="modal-view-mat-{{ $m['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-book"></i> {{ $m['title'] }}</h4>
                        </div>
                        <div class="modal-body">
                          <table class="table table-bordered table-striped" style="margin-bottom: 15px;">
                            <tr>
                              <th style="width: 35%;">Kelas Digital</th>
                              <td>{{ $dc['name'] ?? $m['digital_class_id'] }}</td>
                            </tr>
                            <tr>
                              <th>Mata Pelajaran</th>
                              <td>{{ $sb['name'] ?? '-' }}</td>
                            </tr>
                            <tr>
                              <th>Guru Pengampu</th>
                              <td>{{ $tch['name'] ?? 'Ust. Pengampu' }}</td>
                            </tr>
                            <tr>
                              <th>Format / Tipe</th>
                              <td><span class="label label-primary">{{ $m['type'] }}</span></td>
                            </tr>
                            <tr>
                              <th>Status Publikasi</th>
                              <td>
                                @if(!empty($m['is_published']))
                                  <span class="label label-success">Terbit (Dapat diakses santri)</span>
                                @else
                                  <span class="label label-default">Draf (Hanya guru pengampu)</span>
                                @endif
                              </td>
                            </tr>
                          </table>

                          <div class="callout callout-info" style="border-left-color: #2b6cb0; background: #f0f7fd; color: #1a365d;">
                            <h5 style="margin-top:0; font-weight:bold;">Ringkasan / Sinopsis Modul:</h5>
                            <p style="margin:0; font-size:12px;">{{ $m['description'] }}</p>
                          </div>

                          @if(!empty($m['file_name']))
                            <div class="text-center" style="padding: 15px; background: #fafbfc; border: 1px dashed #ccd0d4; border-radius: 4px;">
                              <i class="fa fa-file-pdf-o fa-3x text-red" style="margin-bottom: 10px;"></i>
                              <h5>{{ $m['file_name'] }} ({{ $m['file_size'] ?? '2.5 MB' }})</h5>
                              <button type="button" class="btn btn-sm btn-success btn-flat" onclick="alert('Simulasi unduhan berkas digital materi: {{ $m['file_name'] }}')">
                                <i class="fa fa-download"></i> Unduh Berkas Materi Sekarang
                              </button>
                            </div>
                          @elseif(!empty($m['external_url']))
                            <div class="text-center" style="padding: 15px; background: #fafbfc; border: 1px dashed #ccd0d4; border-radius: 4px;">
                              <i class="fa fa-globe fa-3x text-green" style="margin-bottom: 10px;"></i>
                              <h5>Tautan Web Pembelajaran Interaktif</h5>
                              <a href="{{ $m['external_url'] }}" target="_blank" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;">
                                <i class="fa fa-external-link"></i> Kunjungi Tautan Modul
                              </a>
                            </div>
                          @endif
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

  <!-- Modal Tambah Materi Baru -->
  <div class="modal fade" id="modal-add-materi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-upload"></i> Unggah Materi Pembelajaran Baru</h4>
        </div>
        <form action="{{ route('lms.materi.store') }}" method="POST">
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
              <label>Judul Modul / Bahan Ajar <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Contoh: Modul Fikih Zakat & Wakaf Produktif Bab 2" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Format Berkas / Tipe <span class="text-danger">*</span></label>
                  <select name="type" class="form-control" required>
                    <option value="PDF">Dokumen PDF (.pdf)</option>
                    <option value="Video">Video Pembelajaran (.mp4 / stream)</option>
                    <option value="Audio">Audio Listening / Talaqqi (.mp3)</option>
                    <option value="Link">Tautan Luar / Simulator Interaktif</option>
                    <option value="Dokumen">Word / Presentasi (.docx / .pptx)</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Berkas Lampiran</label>
                  <input type="text" name="file_name" class="form-control" placeholder="Contoh: Modul-Fikih-Zakat.pdf">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Ukuran Berkas / Estimasi</label>
                  <input type="text" name="file_size" class="form-control" placeholder="Contoh: 3.5 MB" value="2.5 MB">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Durasi (Untuk Audio/Video)</label>
                  <input type="text" name="duration" class="form-control" placeholder="Contoh: 30 Menit">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Tautan URL Eksternal (Opsional)</label>
              <input type="url" name="external_url" class="form-control" placeholder="https://youtube.com/... atau https://drive.google.com/...">
            </div>

            <div class="form-group">
              <label>Deskripsi & Petunjuk Belajar</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Poin penting yang wajib dipelajari santri..."></textarea>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" name="is_published" value="1" checked> <b>Terbitkan Langsung</b> (Dapat langsung diakses dan diunduh santri)
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
              <i class="fa fa-save"></i> Simpan Bahan Ajar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

