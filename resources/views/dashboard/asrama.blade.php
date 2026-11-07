@extends('layouts.app')

@section('title', 'Dashboard Pembina Asrama')
@section('page_title', 'Dashboard Pembina Asrama')
@section('page_description', 'Disiplin Santri Mukim, Perizinan Keluar, dan Pemantauan Ibadah')

@section('breadcrumbs')
  <li class="active">Dashboard Asrama</li>
@endsection

@section('content')

  <!-- Role Notification Alert -->
  <div class="callout callout-danger" style="border-left-color: #800000;">
    <h4><i class="fa fa-bed"></i> Mode Peran: Mudir / Pembina Asrama ({{ session('user_name', 'Ust. Salman Al-Farisi') }})</h4>
    <p>Pantau kegiatan santri mukim 24 jam: alokasi kamar, mutasi izin keluar/pesiar, apel malam, dan katering dapur santri.</p>
  </div>

  <!-- Primary Stat Cards -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="382" label="Santri Mukim di Asrama" icon="fa-bed" bg="purple" :url="route('asrama.kamar')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="8" label="Santri Sedang Izin Keluar" icon="fa-ticket" bg="yellow" :url="route('asrama.perizinan')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="99.2%" label="Kehadiran Shalat Subuh Berjamaah" icon="fa-check-circle" bg="green" :url="route('asrama.aktivitas')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="4 Kamar" label="Sisa Kuota Kamar Tersedia" icon="fa-home" bg="aqua" :url="route('asrama.kamar')" />
    </div>
  </div>

  <!-- Row: Status Izin Keluar & Menu Katering -->
  <div class="row">
    <div class="col-md-7">
      <div class="box box-maroon" style="border-top-color: #800000;">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-ticket text-maroon"></i> Permohonan Izin Keluar / Pulang Terkini</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('asrama.perizinan') }}" class="btn btn-xs btn-default">Semua Izin</a>
          </div>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped table-hover">
            <thead>
              <tr style="background:#f4f6f9;">
                <th>Santri / Kamar</th>
                <th>Keperluan</th>
                <th>Jadwal Kembali</th>
                <th>Status</th>
                <th>Aksi Pembina</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Ahmad Fauzan</strong> (Kamar 101)</td>
                <td>Berobat Dokter Gigi</td>
                <td>Hari ini, 17:00 WIB</td>
                <td><span class="label label-warning">Sedang Diluar</span></td>
                <td><button class="btn btn-xs btn-success btn-flat" onclick="alert('Konfirmasi kedatangan santri berhasil dicatat!')"><i class="fa fa-check"></i> Konfirmasi Kembali</button></td>
              </tr>
              <tr>
                <td><strong>Rayhan Pratama</strong> (Kamar 102)</td>
                <td>Pernikahan Kakak Kandung</td>
                <td>Ahad, 08 Nov 2026</td>
                <td><span class="label label-info">Izin Pulang Resmi</span></td>
                <td><button class="btn btn-xs btn-default btn-flat" disabled><i class="fa fa-clock-o"></i> Menunggu Jadwal</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Jadwal Menu Konsumsi & Apel -->
    <div class="col-md-5">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-cutlery text-blue"></i> Jadwal Menu Dapur Hari Ini</h3>
        </div>
        <div class="box-body">
          <p><strong>Sarapan (06:30):</strong> Nasi Kuning Komplit, Telur Balado, Timun, Susu Kedelai</p>
          <hr style="margin: 8px 0;">
          <p><strong>Makan Siang (12:30):</strong> Nasi Putih, Ayam Goreng Lengkuas, Sayur Asem, Tahu Tempe, Buah Semangka</p>
          <hr style="margin: 8px 0;">
          <p><strong>Makan Malam (18:30):</strong> Nasi Putih, Daging Rendang Padang, Sayur Sop Bening, Kerupuk</p>
        </div>
        <div class="box-footer">
          <a href="{{ route('asrama.katering') }}" class="btn btn-block btn-default btn-sm btn-flat"><i class="fa fa-calendar"></i> Kelola Siklus Menu Katering</a>
        </div>
      </div>
    </div>
  </div>

@endsection

