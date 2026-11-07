@extends('layouts.app')

@section('title', 'Verifikasi Berkas PPDB')
@section('page_title', 'Verifikasi Dokumen Pendaftaran')
@section('page_description', 'Pemeriksaan Persyaratan Akta Kelahiran, Kartu Keluarga, dan Rapor Calon Santri')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li class="active">Verifikasi Berkas</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['doc_status'] ?? '') === 'menunggu')) }}</h3>
          <p>Berkas Antre Verifikasi</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <a href="{{ route('ppdb.verifikasi', ['status' => 'menunggu']) }}" class="small-box-footer">Filter Antrean <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['doc_status'] ?? '') === 'diverifikasi')) }}</h3>
          <p>Berkas Sah & Lengkap</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <a href="{{ route('ppdb.verifikasi', ['status' => 'diverifikasi']) }}" class="small-box-footer">Filter Lengkap <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['doc_status'] ?? '') === 'ditolak')) }}</h3>
          <p>Berkas Ditolak / Tidak Sah</p>
        </div>
        <div class="icon"><i class="fa fa-times-circle"></i></div>
        <a href="{{ route('ppdb.verifikasi', ['status' => 'ditolak']) }}" class="small-box-footer">Filter Ditolak <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row">
    <div class="col-md-12">
      <x-card title="Antrean Pemeriksaan Dokumen Calon Santri" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('ppdb.verifikasi') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 260px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / nomor registrasi...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Status Berkas -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="status" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status Berkas</option>
                  <option value="menunggu" {{ $statusFilter == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                  <option value="diverifikasi" {{ $statusFilter == 'diverifikasi' ? 'selected' : '' }}>Sudah Diverifikasi</option>
                  <option value="ditolak" {{ $statusFilter == 'ditolak' ? 'selected' : '' }}>Ditolak / Belum Lengkap</option>
                </select>
              </div>

              @if($search || $statusFilter !== 'all')
                <a href="{{ route('ppdb.verifikasi') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <span class="text-muted"><i class="fa fa-info-circle text-blue"></i> Standar: Akta Kelahiran, KK, Rapor 2 Semester Terakhir</span>
          </div>
        </div>

        @if(count($applicants) === 0)
          <x-empty-state 
            title="Tidak Ada Berkas Dalam Kriteria"
            message="Tidak ada pendaftar yang cocok dengan filter status dokumen yang Anda pilih."
            actionLabel="Reset Filter"
            actionUrl="{{ route('ppdb.verifikasi') }}"
          />
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="background:#f4f6f9;">
                  <th style="width: 45px;" class="text-center">No</th>
                  <th>No. Registrasi</th>
                  <th>Nama Calon Santri</th>
                  <th>Pilihan Unit</th>
                  <th>Jalur Masuk</th>
                  <th>Kelengkapan Dokumen Lampiran</th>
                  <th class="text-center">Status Berkas</th>
                  <th style="width: 170px;" class="text-center">Aksi Verifikator</th>
                </tr>
              </thead>
              <tbody>
                @foreach($applicants as $ap)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $ap['reg_number'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $ap['name'] }}</strong><br>
                      <small class="text-muted">Wali: {{ $ap['guardian_name'] }} ({{ $ap['guardian_phone'] }})</small>
                    </td>
                    <td><span class="label label-info">{{ $ap['unit_target'] }}</span></td>
                    <td><span class="label label-default">{{ $ap['track'] }}</span></td>
                    <td>
                      <i class="fa fa-file-pdf-o text-danger"></i> Akta Lahir &bull;
                      <i class="fa fa-file-image-o text-primary"></i> Kartu Keluarga &bull;
                      <i class="fa fa-file-text-o text-success"></i> Rapor Terakhir<br>
                      <small class="text-muted"><em>Catatan: {{ $ap['notes'] ?? 'Persyaratan telah diunggah lengkap.' }}</em></small>
                    </td>
                    <td class="text-center">
                      @if(($ap['doc_status'] ?? '') === 'diverifikasi')
                        <span class="label label-success" style="font-size:12px;"><i class="fa fa-check-circle"></i> Terverifikasi</span>
                      @elseif(($ap['doc_status'] ?? '') === 'ditolak')
                        <span class="label label-danger" style="font-size:12px;"><i class="fa fa-ban"></i> Ditolak</span>
                      @else
                        <span class="label label-warning" style="font-size:12px;"><i class="fa fa-clock-o"></i> Menunggu Verifikasi</span>
                      @endif
                    </td>
                    <td class="text-center irnis-actions">
                      <button class="btn btn-xs btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-verify-{{ $ap['id'] }}">
                        <i class="fa fa-check-square-o"></i> Periksa Berkas
                      </button>
                    </td>
                  </tr>

                  <!-- Modal Verifikasi Dokumen Calon Santri -->
                  <div class="modal fade" id="modal-verify-{{ $ap['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-check-square"></i> Verifikasi Berkas: {{ $ap['name'] }}</h4>
                        </div>
                        <form action="{{ route('ppdb.verifikasi.update', ['id' => $ap['id']]) }}" method="POST">
                          @csrf
                          <div class="modal-body">
                            <div class="callout callout-info" style="background:#f4f6f9; border-left-color:#2b6cb0; padding:10px;">
                              <p style="margin:0;">No. Registrasi: <b>{{ $ap['reg_number'] }}</b> | Jalur: <b>{{ $ap['track'] }}</b></p>
                              <p style="margin:0;">Unit Pilihan: <b>{{ $ap['unit_target'] }}</b></p>
                            </div>

                            <h5 style="font-weight:bold; color:#2b6cb0;">Checklist Dokumen Persyaratan:</h5>
                            <ul class="list-group">
                              <li class="list-group-item">
                                <i class="fa fa-check-circle text-green"></i> <b>Akta Kelahiran:</b> Terunggah & Sesuai Identitas
                              </li>
                              <li class="list-group-item">
                                <i class="fa fa-check-circle text-green"></i> <b>Kartu Keluarga (KK):</b> Terunggah & Sesuai Data Wali
                              </li>
                              <li class="list-group-item">
                                <i class="fa fa-check-circle text-green"></i> <b>Rapor Sekolah Asal:</b> Rerata Nilai Memenuhi Ambang Batas
                              </li>
                              @if($ap['track'] === 'Prestasi Tahfidz')
                                <li class="list-group-item list-group-item-info">
                                  <i class="fa fa-certificate text-purple"></i> <b>Piagam/Sertifikat Tahfidz:</b> Terlampir (Min. 3 Juz)
                                </li>
                              @endif
                            </ul>

                            <div class="form-group">
                              <label>Tindakan Keputusan Panitia Verifikator <span class="text-danger">*</span></label>
                              <select name="doc_status" class="form-control" required>
                                <option value="diverifikasi" {{ ($ap['doc_status'] ?? '') === 'diverifikasi' ? 'selected' : '' }}>
                                  Lengkap & Diverifikasi (Siap Ikuti Tes Seleksi)
                                </option>
                                <option value="ditolak" {{ ($ap['doc_status'] ?? '') === 'ditolak' ? 'selected' : '' }}>
                                  Tolak Berkas (Dokumen Tidak Valid / Tidak Sesuai)
                                </option>
                                <option value="menunggu" {{ ($ap['doc_status'] ?? '') === 'menunggu' ? 'selected' : '' }}>
                                  Kembalikan ke Antrean Menunggu
                                </option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label>Catatan Hasil Pemeriksaan Verifikator</label>
                              <textarea name="notes" class="form-control" rows="2" placeholder="Catatan untuk calon santri atau instruksi revisi...">{{ $ap['notes'] }}</textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
                              <i class="fa fa-save"></i> Simpan Hasil Verifikasi
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </x-card>
    </div>
  </div>

@endsection

