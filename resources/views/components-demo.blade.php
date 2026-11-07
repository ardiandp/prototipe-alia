@extends('layouts.app')

@section('title', 'Komponen UI Reusable (AdminLTE 2)')
@section('page_title', 'Panduan Komponen UI')
@section('page_description', 'Katalog komponen Bootstrap 3 / AdminLTE 2.4.2 untuk IRNIS School Prototype')

@section('breadcrumbs')
  <li class="active">Komponen UI</li>
@endsection

@section('content')

  <!-- Demo Alert Callout -->
  <div class="callout callout-info">
    <h4><i class="fa fa-info-circle"></i> Fondasi Desain UI</h4>
    <p>Halaman ini mendemonstrasikan komponen Blade reusable yang mematuhi Bootstrap 3 dan AdminLTE 2.4.2 tanpa kelas Bootstrap 4/5, siap digunakan di seluruh modul prototipe.</p>
  </div>

  <!-- Statistic Cards -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="1,248" label="Total Santri Aktif" icon="fa-users" bg="aqua" url="#" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="86" label="Guru & Asatidz" icon="fa-graduation-cap" bg="green" url="#" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="94.8%" label="Kelancaran SPP Bulan Ini" icon="fa-money" bg="yellow" url="#" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="382" label="Santri Asrama / Mukim" icon="fa-bed" bg="purple" url="#" />
    </div>
  </div>

  <!-- Toolbar & Table Sample in Card -->
  <div class="row">
    <div class="col-md-8">
      <x-card title="Contoh Tabel & Toolbar Interaktif" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="alert('Simulasi ekspor Excel')"><i class="fa fa-file-excel-o"></i> Ekspor</button>
        </x-slot:tools>

        <x-toolbar 
          searchAction="{{ url()->current() }}"
          searchPlaceholder="Cari siswa/NIS..."
          :actions="'<button class=\'btn btn-primary btn-flat\' style=\'background:#2b6cb0;\' data-toggle=\'modal\' data-target=\'#modal-sample-form\'><i class=\'fa fa-plus\'></i> Tambah Siswa</button>'"
        />

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr style="background:#f4f6f9;">
                <th style="width: 50px;">No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>Unit & Kelas</th>
                <th>Status</th>
                <th style="width: 140px;" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><strong>202601001</strong></td>
                <td>Ahmad Fauzan Al-Ghifari</td>
                <td>SMA - X MIPA 1</td>
                <td><x-badge status="aktif" /></td>
                <td class="text-center irnis-actions">
                  <button class="btn btn-xs btn-info btn-flat" title="Detail"><i class="fa fa-eye"></i></button>
                  <button class="btn btn-xs btn-warning btn-flat" title="Edit"><i class="fa fa-pencil"></i></button>
                  <button class="btn btn-xs btn-danger btn-flat" title="Hapus" onclick="confirmAction('#', 'Hapus data santri ini?')"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td><strong>202601002</strong></td>
                <td>Siti Fatimah Zahra</td>
                <td>SMP - VIII A</td>
                <td><x-badge status="diverifikasi" /></td>
                <td class="text-center irnis-actions">
                  <button class="btn btn-xs btn-info btn-flat" title="Detail"><i class="fa fa-eye"></i></button>
                  <button class="btn btn-xs btn-warning btn-flat" title="Edit"><i class="fa fa-pencil"></i></button>
                  <button class="btn btn-xs btn-danger btn-flat" title="Hapus" onclick="confirmAction('#', 'Hapus data santri ini?')"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td><strong>202601003</strong></td>
                <td>Muhammad Rizky Ramadhan</td>
                <td>SMA - XI IPS 2</td>
                <td><x-badge status="belum_lunas" /></td>
                <td class="text-center irnis-actions">
                  <button class="btn btn-xs btn-info btn-flat" title="Detail"><i class="fa fa-eye"></i></button>
                  <button class="btn btn-xs btn-warning btn-flat" title="Edit"><i class="fa fa-pencil"></i></button>
                  <button class="btn btn-xs btn-danger btn-flat" title="Hapus" onclick="confirmAction('#', 'Hapus data santri ini?')"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </x-card>
    </div>

    <div class="col-md-4">
      <!-- Status Badges Preview -->
      <x-card title="Status Badges" type="default">
        <p>Contoh variasi status badge yang telah dinormalisasi:</p>
        <div style="line-height: 2.2;">
          <x-badge status="aktif" />
          <x-badge status="nonaktif" />
          <x-badge status="lulus" />
          <x-badge status="diterima" />
          <x-badge status="diverifikasi" />
          <x-badge status="menunggu" />
          <x-badge status="lunas" />
          <x-badge status="belum_lunas" />
          <x-badge status="hadir" />
          <x-badge status="izin" />
          <x-badge status="alpa" />
        </div>
      </x-card>

      <!-- Empty State Preview -->
      <x-card title="Preview Empty State" type="default">
        <x-empty-state 
          title="Tidak Ada Data Ditemukan"
          message="Silakan periksa filter pencarian atau buat entri data baru."
          actionLabel="Buat Data Contoh"
          actionUrl="#"
        />
      </x-card>
    </div>
  </div>

  <!-- Modal Form Sample (Bootstrap 3 Modal) -->
  <div class="modal fade" id="modal-sample-form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah Data Siswa Demo</h4>
        </div>
        <form onsubmit="alert('Data demo berhasil disimulasikan!'); $('#modal-sample-form').modal('hide'); return false;">
          <div class="modal-body">
            <div class="form-group">
              <label>Nomor Induk Siswa (NIS)</label>
              <input type="text" class="form-control" value="202601004" required>
            </div>
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input type="text" class="form-control" placeholder="Contoh: Bilal bin Rabah" required>
            </div>
            <div class="form-group">
              <label>Pilihan Unit Pendidikan</label>
              <select class="form-control select2" style="width: 100%;">
                <option value="SD">SD Islam Alia</option>
                <option value="SMP">SMP Islam Alia</option>
                <option value="SMA">SMA Islam Alia</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;"><i class="fa fa-save"></i> Simpan Demo</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

