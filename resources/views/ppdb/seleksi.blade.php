@extends('layouts.app')

@section('title', 'Seleksi & Pengumuman PPDB')
@section('page_title', 'Hasil Ujian Seleksi & Pengumuman')
@section('page_description', 'Kelola Skor Tes Potensi Akademik, Baca Al-Qur\'an, Wawancara, dan Status Kelulusan')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li><a href="{{ route('ppdb.pendaftar') }}">Data Pendaftar</a></li>
  <li class="active">Seleksi & Kelulusan</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($applicants) }}</h3>
          <p>Total Peserta Ujian</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i></div>
        <a href="#table-seleksi" class="small-box-footer">Tabel Seleksi <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['selection_status'] ?? '') === 'lulus')) }}</h3>
          <p>Dinyatakan Diterima (Lulus)</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <a href="{{ route('ppdb.seleksi', ['status' => 'lulus']) }}" class="small-box-footer">Filter Lulus <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['selection_status'] ?? '') === 'proses')) }}</h3>
          <p>Dalam Proses Rapat Pleno</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <a href="{{ route('ppdb.seleksi', ['status' => 'proses']) }}" class="small-box-footer">Filter Proses <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['selection_status'] ?? '') === 'tidak_lulus')) }}</h3>
          <p>Tidak Memenuhi Syarat</p>
        </div>
        <div class="icon"><i class="fa fa-times-circle"></i></div>
        <a href="{{ route('ppdb.seleksi', ['status' => 'tidak_lulus']) }}" class="small-box-footer">Filter Ditolak <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-seleksi">
    <div class="col-md-12">
      <x-card title="Daftar Nilai & Keputusan Kelulusan Masuk Alia" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('ppdb.seleksi') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 240px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / no. reg...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Status Kelulusan -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="status" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Hasil Seleksi</option>
                  <option value="lulus" {{ $statusFilter == 'lulus' ? 'selected' : '' }}>Lulus (Diterima)</option>
                  <option value="proses" {{ $statusFilter == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                  <option value="tidak_lulus" {{ $statusFilter == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                </select>
              </div>

              <!-- Filter Unit Target -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="unit" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Unit</option>
                  <option value="SD" {{ $unitFilter == 'SD' ? 'selected' : '' }}>SD</option>
                  <option value="SMP" {{ $unitFilter == 'SMP' ? 'selected' : '' }}>SMP</option>
                  <option value="SMA" {{ $unitFilter == 'SMA' ? 'selected' : '' }}>SMA</option>
                </select>
              </div>

              @if($search || $statusFilter !== 'all' || $unitFilter !== 'all')
                <a href="{{ route('ppdb.seleksi') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <a href="{{ route('ppdb.daftar-ulang') }}" class="btn btn-success btn-flat">
              <i class="fa fa-credit-card"></i> Menuju Kasir & Konversi Daftar Ulang
            </a>
          </div>
        </div>

        @if(count($applicants) === 0)
          <x-empty-state 
            title="Tidak Ada Calon Santri"
            message="Tidak ada pendaftar yang cocok dengan filter parameter seleksi yang Anda tentukan."
            actionLabel="Reset Filter"
            actionUrl="{{ route('ppdb.seleksi') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 45px;" class="text-center">No</th>
                  <th>No. Registrasi</th>
                  <th>Nama Calon Santri</th>
                  <th>Unit Pilihan</th>
                  <th>Jalur Masuk</th>
                  <th class="text-center">Skor Ujian Masuk</th>
                  <th class="text-center">Hasil Keputusan</th>
                  <th>Catatan Hasil Sidang</th>
                  <th style="width: 150px;" class="text-center">Aksi Penilaian</th>
                </tr>
              </thead>
              <tbody>
                @foreach($applicants as $ap)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $ap['reg_number'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $ap['name'] }}</strong>
                      <small class="text-muted d-block">
                        Wali: {{ $ap['guardian_name'] }} (<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ap['guardian_phone']) }}" target="_blank" class="text-green"><i class="fa fa-whatsapp"></i> Hubungi</a>)
                      </small>
                    </td>
                    <td><span class="label label-info">{{ $ap['unit_target'] }}</span></td>
                    <td>
                      @if($ap['track'] === 'Prestasi Tahfidz')
                        <span class="label label-purple" style="background:#6b46c1;"><i class="fa fa-book"></i> Tahfidz</span>
                      @elseif($ap['track'] === 'Prestasi Akademik')
                        <span class="label label-success"><i class="fa fa-trophy"></i> Akademik</span>
                      @elseif($ap['track'] === 'Afirmasi / Yatim')
                        <span class="label label-warning"><i class="fa fa-heart"></i> Afirmasi</span>
                      @else
                        <span class="label label-default">Reguler</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($ap['exam_score'])
                        <span class="badge {{ $ap['exam_score'] >= 80 ? 'bg-green' : ($ap['exam_score'] >= 70 ? 'bg-yellow' : 'bg-red') }}" style="font-size:13px; font-weight:bold;">
                          {{ $ap['exam_score'] }}
                        </span>
                      @else
                        <span class="label label-default">Belum Dinilai</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if(($ap['selection_status'] ?? '') === 'lulus')
                        <span class="label label-success" style="font-size:12px; padding: 4px 8px;"><i class="fa fa-check"></i> LULUS</span>
                      @elseif(($ap['selection_status'] ?? '') === 'tidak_lulus')
                        <span class="label label-danger" style="font-size:12px; padding: 4px 8px;"><i class="fa fa-times"></i> TIDAK LULUS</span>
                      @else
                        <span class="label label-warning" style="font-size:12px; padding: 4px 8px;"><i class="fa fa-clock-o"></i> DALAM PROSES</span>
                      @endif
                    </td>
                    <td><small class="text-muted">{{ $ap['notes'] ?? 'Tahapan seleksi berkas & wawancara' }}</small></td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-grade-{{ $ap['id'] }}">
                        <i class="fa fa-pencil-square-o"></i> Input Nilai & Status
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </x-card>
    </div>
  </div>

  <!-- Detail Penilaian Seleksi Calon Santri Modals (Moved outside table) -->
  @foreach($applicants as $ap)
    <div class="modal fade" id="modal-grade-{{ $ap['id'] }}" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-pencil"></i> Penilaian Ujian Seleksi: {{ $ap['name'] }}</h4>
          </div>
          <form action="{{ route('ppdb.seleksi.update', ['id' => $ap['id']]) }}" method="POST">
            @csrf
            <div class="modal-body">
              <div class="callout callout-info" style="background:#f4f6f9; border-left-color:#2b6cb0; padding:10px;">
                <p style="margin:0;">No. Registrasi: <b>{{ $ap['reg_number'] }}</b> | Jalur Masuk: <b>{{ $ap['track'] }}</b></p>
                <p style="margin:0;">Pilihan Unit: <b>{{ $ap['unit_target'] }}</b></p>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Tes Potensi Akademik (40%) <span class="text-danger">*</span></label>
                    <input type="number" name="academic_score" class="form-control" value="{{ $ap['exam_score'] ? round($ap['exam_score'] * 0.95) : 85 }}" min="0" max="100" step="0.5" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Tes Baca Al-Qur'an (40%) <span class="text-danger">*</span></label>
                    <input type="number" name="quran_score" class="form-control" value="{{ $ap['exam_score'] ?: 90 }}" min="0" max="100" step="0.5" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Wawancara Santri (20%) <span class="text-danger">*</span></label>
                    <input type="number" name="interview_score" class="form-control" value="{{ $ap['exam_score'] ? round($ap['exam_score'] * 1.02) : 88 }}" min="0" max="100" step="0.5" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Keputusan Dewan Penguji / Panitia <span class="text-danger">*</span></label>
                <select name="selection_status" class="form-control" required>
                  <option value="lulus" {{ ($ap['selection_status'] ?? '') === 'lulus' ? 'selected' : '' }}>
                    LULUS SELEKSI (Diterima Sebagai Calon Santri Baru)
                  </option>
                  <option value="tidak_lulus" {{ ($ap['selection_status'] ?? '') === 'tidak_lulus' ? 'selected' : '' }}>
                    TIDAK LULUS (Nilai Di Bawah Batas Ambang Minimal)
                  </option>
                  <option value="proses" {{ ($ap['selection_status'] ?? '') === 'proses' ? 'selected' : '' }}>
                    DALAM PROSES (Menunggu Ujian Susulan / Hasil Rapat)
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label>Catatan Hasil Ujian & Pengumuman</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan penguji untuk surat pengumuman...">{{ $ap['notes'] }}</textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
                <i class="fa fa-save"></i> Simpan Keputusan Seleksi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach

@endsection
