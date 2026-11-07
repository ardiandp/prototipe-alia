@extends('layouts.app')

@section('title', 'Formulir Pendaftaran PPDB')
@section('page_title', 'Formulir Pendaftaran Santri Baru')
@section('page_description', 'Penerimaan Peserta Didik Baru (PPDB) Online Alia Islamic School')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li class="active">Formulir Pendaftaran</li>
@endsection

@section('content')

  <!-- Petunjuk Pendaftaran Callout -->
  <div class="callout callout-info" style="border-left-color: #2b6cb0; background: #ebf8ff;">
    <h4 style="color:#2b6cb0; font-weight:700;"><i class="fa fa-info-circle"></i> Informasi Pendaftaran Calon Santri Baru</h4>
    <p style="color:#4a5568;">
      Silakan isi data formulir calon santri dan orang tua/wali dengan benar. Setelah formulir dikirim, sistem akan menerbitkan <b>Nomor Registrasi PPDB</b> resmi yang digunakan untuk proses verifikasi berkas dan jadwal tes seleksi.
    </p>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="box box-primary" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="box-header with-border" style="background:#1a365d; color:#fff; border-top-left-radius:3px; border-top-right-radius:3px;">
          <h3 class="box-title" style="color:#fff;"><i class="fa fa-edit"></i> Formulir Registrasi Online Santri Baru</h3>
          <div class="box-tools pull-right">
            <span class="label label-warning">Gelombang 1</span>
          </div>
        </div>

        <form action="{{ route('ppdb.form.store') }}" method="POST">
          @csrf
          <div class="box-body" style="padding: 25px;">

            <!-- STEP 1: Pilihan Jalur & Jenjang -->
            <h4 style="color:#2b6cb0; font-weight:bold; border-bottom:2px solid #2b6cb0; padding-bottom:6px; margin-top:0;">
              <span class="badge bg-blue" style="font-size:14px; margin-right:5px;">1</span> Pilihan Jenjang & Jalur Pendaftaran
            </h4>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pilihan Jenjang Pendidikan <span class="text-danger">*</span></label>
                  <select name="unit_level" class="form-control" required>
                    <option value="SD">SD Islam Alia (Full Day)</option>
                    <option value="SMP">SMP Islam Alia (Full Day & Pesantren)</option>
                    <option value="SMA" selected>SMA Islam Alia Boarding School (Mukim)</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jalur Seleksi Masuk <span class="text-danger">*</span></label>
                  <select name="track" class="form-control" required>
                    <option value="Reguler" selected>Jalur Reguler (Tes Standar)</option>
                    <option value="Prestasi Tahfidz">Jalur Prestasi Tahfidz (Min. 3 Juz)</option>
                    <option value="Prestasi Akademik">Jalur Prestasi Akademik / OSN</option>
                    <option value="Afirmasi / Yatim">Jalur Beasiswa Afirmasi / Dhuafa</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- STEP 2: Identitas Calon Santri -->
            <h4 style="color:#2b6cb0; font-weight:bold; border-bottom:2px solid #2b6cb0; padding-bottom:6px; margin-top:25px;">
              <span class="badge bg-blue" style="font-size:14px; margin-right:5px;">2</span> Identitas Lengkap Calon Santri
            </h4>

            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Nama Lengkap Santri <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Nama lengkap sesuai akta kelahiran" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="gender" class="form-control" required>
                    <option value="L">Laki-Laki (Ikhwan)</option>
                    <option value="P">Perempuan (Akhwat)</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tempat Lahir</label>
                  <input type="text" name="pob" class="form-control" placeholder="Kota / Kabupaten tempat lahir">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Lahir</label>
                  <input type="date" name="dob" class="form-control" value="2011-06-15">
                </div>
              </div>
            </div>

            <!-- STEP 3: Data Orang Tua / Wali -->
            <h4 style="color:#2b6cb0; font-weight:bold; border-bottom:2px solid #2b6cb0; padding-bottom:6px; margin-top:25px;">
              <span class="badge bg-blue" style="font-size:14px; margin-right:5px;">3</span> Data Orang Tua / Wali Santri
            </h4>

            <div class="row">
              <div class="col-md-7">
                <div class="form-group">
                  <label>Nama Lengkap Ayah / Ibu / Wali <span class="text-danger">*</span></label>
                  <input type="text" name="guardian_name" class="form-control" placeholder="Nama lengkap penanggung jawab" required>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                  <input type="text" name="guardian_phone" class="form-control" placeholder="0812xxxxxxxx" required>
                  <small class="text-muted">Untuk pengiriman jadwal tes seleksi dan pengumuman.</small>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Email Wali</label>
              <input type="email" name="guardian_email" class="form-control" placeholder="email.wali@gmail.com">
            </div>

            <div class="form-group">
              <label>Alamat Domisili Rumah <span class="text-danger">*</span></label>
              <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap tempat tinggal" required></textarea>
            </div>

            <div class="form-group">
              <label>Catatan Tambahan / Prestasi Calon Santri</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Sudah memiliki hafalan 3 juz, sertifikat juara pidato bahasa Arab, dsb."></textarea>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" required checked> <b>Saya menyatakan bahwa data yang diisikan adalah benar dan siap mengikuti rangkaian seleksi PPDB Alia Islamic School.</b>
              </label>
            </div>

          </div>
          <div class="box-footer text-right" style="padding: 15px 25px; background: #f8fafc;">
            <a href="{{ route('ppdb.pendaftar') }}" class="btn btn-default btn-flat"><i class="fa fa-arrow-left"></i> Kembali</a>
            <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0; padding: 7px 25px; font-weight:bold;">
              <i class="fa fa-paper-plane"></i> Kirim Formulir Pendaftaran PPDB
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

