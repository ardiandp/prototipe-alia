@extends('layouts.app')

@section('title', 'Dashboard Bendahara Keuangan')
@section('page_title', 'Dashboard Bendahara Keuangan')
@section('page_description', 'Manajemen Arus Kas, Validasi Pembayaran SPP, dan Piutang Santri')

@section('breadcrumbs')
  <li class="active">Dashboard Bendahara</li>
@endsection

@section('content')

  <!-- Role Notification Alert -->
  <div class="callout callout-warning" style="border-left-color: #d69e2e;">
    <h4><i class="fa fa-money"></i> Mode Peran: Bendahara Sekolah ({{ session('user_name', 'Hj. Nurul Hidayati, S.E.') }})</h4>
    <p>Kelola kasir & penerimaan: validasi transfer bank/VA, cetak kwitansi, rekap tunggakan santri, dan laporan arus kas.</p>
  </div>

  <!-- Primary Stat Cards -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="Rp 642.5 Jt" label="Penerimaan SPP Bulan Ini" icon="fa-money" bg="green" :url="route('keuangan.transaksi')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="Rp 46.8 Jt" label="Total Tunggakan Santri" icon="fa-exclamation-triangle" bg="red" :url="route('keuangan.tagihan')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="14" label="Transfer Perlu Validasi Kasir" icon="fa-check-circle" bg="yellow" :url="route('keuangan.transaksi')" />
    </div>
    <div class="col-lg-3 col-xs-6">
      <x-stat-card count="93.2%" label="Kolektibilitas Tagihan" icon="fa-line-chart" bg="aqua" :url="route('keuangan.laporan')" />
    </div>
  </div>

  <!-- Row: Pembayaran Terbaru & Aksi Kasir Cepat -->
  <div class="row">
    <div class="col-md-8">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-exchange text-green"></i> Transaksi Penerimaan Terbaru Hari Ini</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('keuangan.transaksi') }}" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-plus"></i> Input Kasir Cepat</a>
          </div>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped table-hover">
            <thead>
              <tr style="background:#f4f6f9;">
                <th>No. Kuitansi</th>
                <th>Santri / NIS</th>
                <th>Jenis Pembayaran</th>
                <th>Metode</th>
                <th>Jumlah</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>KW-202611-0084</strong></td>
                <td>Ahmad Fauzan (202601001)</td>
                <td>SPP + Uang Makan Nov 2026</td>
                <td><span class="label label-info">BSI Virtual Account</span></td>
                <td><strong>Rp 1.850.000</strong></td>
                <td><span class="label label-success">Lunas Terverifikasi</span></td>
              </tr>
              <tr>
                <td><strong>KW-202611-0083</strong></td>
                <td>Siti Fatimah (202601002)</td>
                <td>Daftar Ulang Semester Ganjil</td>
                <td><span class="label label-default">Transfer Manual Mandiri</span></td>
                <td><strong>Rp 2.500.000</strong></td>
                <td><span class="label label-warning">Menunggu Validasi</span></td>
              </tr>
              <tr>
                <td><strong>KW-202611-0082</strong></td>
                <td>Faris Abdurrahman (202601005)</td>
                <td>Uang Gedung & Seragam SD</td>
                <td><span class="label label-primary">Tunai (Kasir Sekolah)</span></td>
                <td><strong>Rp 3.200.000</strong></td>
                <td><span class="label label-success">Lunas Terverifikasi</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Panel Quick Billing & Broadcast Tagihan -->
    <div class="col-md-4">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bell-o text-red"></i> Pengingat Tagihan SPP</h3>
        </div>
        <div class="box-body">
          <p class="text-muted" style="font-size:13px;">Batas akhir jatuh tempo pembayaran SPP setiap tanggal 10 bulan berjalan.</p>
          <div class="callout callout-danger" style="margin-bottom: 10px; padding: 10px;">
            <p><strong>24 Santri</strong> belum melunasi kewajiban bulan November 2026.</p>
          </div>
          <button class="btn btn-block btn-danger btn-flat" onclick="alert('Simulasi pengiriman pesan pengingat tagihan ke nomor WhatsApp 24 wali santri telah diproses!')">
            <i class="fa fa-whatsapp"></i> Broadcast Notifikasi WA Tagihan
          </button>
        </div>
      </div>
    </div>
  </div>

@endsection

