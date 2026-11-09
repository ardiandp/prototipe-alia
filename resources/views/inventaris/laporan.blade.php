@extends('layouts.app')

@section('title', 'Laporan & Kartu Stok Inventaris')

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      Laporan Logistik & Kartu Stok
      <small>Rekapitulasi Arus Mutasi, Kartu Stok Barang, & Valuasi Aset Sekolah</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="{{ route('inventaris.barang') }}">Inventaris & Logistik</a></li>
      <li class="active">Laporan & Kartu Stok</li>
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

    <!-- 4 Info Boxes Rekapitulasi Logistik -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Jenis Barang</span>
            <span class="info-box-number">{{ number_format($totalItemsCount) }} SKU</span>
            <div class="progress" style="margin: 5px 0; height: 4px;">
              <div class="progress-bar bg-aqua" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              Tersebar di 7 kategori aset
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Valuasi Aset</span>
            <span class="info-box-number" style="font-size: 16px;">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</span>
            <div class="progress" style="margin: 5px 0; height: 4px;">
              <div class="progress-bar bg-green" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              Nilai perolehan stok fisik
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-exchange"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Volume Keluar/Masuk</span>
            <span class="info-box-number">+{{ number_format($totalReceiptQty) }} / -{{ number_format($totalIssueQty) }}</span>
            <div class="progress" style="margin: 5px 0; height: 4px;">
              <div class="progress-bar bg-blue" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              Mutasi logistik semester berjalan
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-purple"><i class="fa fa-wrench"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Biaya Pemeliharaan</span>
            <span class="info-box-number" style="font-size: 16px;">Rp {{ number_format($totalMaintenanceCost, 0, ',', '.') }}</span>
            <div class="progress" style="margin: 5px 0; height: 4px;">
              <div class="progress-bar bg-purple" style="width: 100%"></div>
            </div>
            <span class="progress-description text-muted" style="font-size: 11px;">
              Realisasi servis & kalibrasi
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs & Actions -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="{{ $activeTab === 'kartu_stok' ? 'active' : '' }}">
          <a href="{{ route('inventaris.laporan', ['tab' => 'kartu_stok', 'item_id' => $selectedItemId, 'category' => $categoryFilter]) }}">
            <i class="fa fa-credit-card text-blue"></i> Kartu Stok Per Barang
          </a>
        </li>
        <li class="{{ $activeTab === 'mutasi' ? 'active' : '' }}">
          <a href="{{ route('inventaris.laporan', ['tab' => 'mutasi', 'category' => $categoryFilter]) }}">
            <i class="fa fa-list-alt text-green"></i> Jurnal Mutasi Keluar-Masuk
          </a>
        </li>
        <li class="{{ $activeTab === 'valuasi_aset' ? 'active' : '' }}">
          <a href="{{ route('inventaris.laporan', ['tab' => 'valuasi_aset', 'category' => $categoryFilter]) }}">
            <i class="fa fa-pie-chart text-purple"></i> Valuasi Aset & Kategori
          </a>
        </li>
        <li class="{{ $activeTab === 'kondisi_alat' ? 'active' : '' }}">
          <a href="{{ route('inventaris.laporan', ['tab' => 'kondisi_alat', 'category' => $categoryFilter]) }}">
            <i class="fa fa-heartbeat text-red"></i> Rekap Kondisi Fisik Alat
          </a>
        </li>
        <li class="pull-right">
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()" style="margin-top: 7px; margin-right: 10px;">
            <i class="fa fa-print"></i> Cetak Laporan
          </button>
        </li>
      </ul>

      <div class="tab-content" style="padding: 20px 15px;">

        <!-- ========================================== -->
        <!-- TAB 1: KARTU STOK PER BARANG (BIN CARD)   -->
        <!-- ========================================== -->
        @if($activeTab === 'kartu_stok')
          <div class="tab-pane active" id="tab_kartu_stok">
            <!-- Selector Barang & Filter -->
            <div class="well well-sm" style="background-color: #fcfcfc;">
              <form action="{{ route('inventaris.laporan') }}" method="GET" class="form-inline">
                <input type="hidden" name="tab" value="kartu_stok">
                <div class="form-group" style="margin-right: 15px;">
                  <label for="select_item_id" style="margin-right: 8px;">Pilih Barang Inventaris:</label>
                  <select name="item_id" id="select_item_id" class="form-control" onchange="this.form.submit()" style="min-width: 320px;">
                    @foreach($items as $it)
                      <option value="{{ $it['id'] }}" {{ ($selectedItem['id'] ?? '') === $it['id'] ? 'selected' : '' }}>
                        [{{ $it['item_code'] }}] {{ $it['name'] }} (Stok: {{ $it['stock'] }} {{ $it['unit'] }})
                      </option>
                    @endforeach
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Muat Kartu Stok</button>
                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalCetakKartuStok">
                  <i class="fa fa-file-text-o"></i> Format Fisik Kartu Stok
                </button>
              </form>
            </div>

            @if($selectedItem)
              <!-- Detail Profil Barang Terpilih -->
              <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-8">
                  <div class="box box-solid box-default" style="margin-bottom: 0;">
                    <div class="box-body">
                      <table class="table table-condensed table-bordered" style="margin-bottom: 0;">
                        <tr>
                          <th style="width: 25%; background: #f9f9f9;">Nama Barang</th>
                          <td><strong>{{ $selectedItem['name'] }}</strong></td>
                          <th style="width: 20%; background: #f9f9f9;">Kode / SKU</th>
                          <td><span class="label label-primary">{{ $selectedItem['item_code'] }}</span></td>
                        </tr>
                        <tr>
                          <th style="background: #f9f9f9;">Kategori Logistik</th>
                          <td><span class="label label-default">{{ strtoupper($selectedItem['category']) }}</span></td>
                          <th style="background: #f9f9f9;">Lokasi Penyimpanan</th>
                          <td><i class="fa fa-map-marker text-red"></i> {{ $selectedItem['storage_location'] ?? 'Gudang Utama' }}</td>
                        </tr>
                        <tr>
                          <th style="background: #f9f9f9;">Harga Satuan</th>
                          <td>Rp {{ number_format($selectedItem['unit_price'] ?? 0, 0, ',', '.') }} / {{ $selectedItem['unit'] }}</td>
                          <th style="background: #f9f9f9;">Stok Minimum Alert</th>
                          <td>{{ $selectedItem['min_stock'] ?? 5 }} {{ $selectedItem['unit'] }}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="small-box {{ ($selectedItem['stock'] ?? 0) <= ($selectedItem['min_stock'] ?? 5) ? 'bg-red' : 'bg-green' }}" style="margin-bottom: 0; border-radius: 3px;">
                    <div class="inner">
                      <h3>{{ $selectedItem['stock'] ?? 0 }} <sup style="font-size: 20px">{{ $selectedItem['unit'] }}</sup></h3>
                      <p>Sisa Saldo Stok Fisik Tersedia</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-archive"></i>
                    </div>
                    <a href="{{ route('inventaris.barang') }}" class="small-box-footer">
                      Kelola di Master Barang <i class="fa fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Tabel Mutasi Kartu Stok (Bin Card) -->
              <h4 style="font-weight: 600; margin-top: 10px; margin-bottom: 15px; border-bottom: 2px solid #3c8dbc; padding-bottom: 5px;">
                <i class="fa fa-history text-blue"></i> Riwayat Mutasi Masuk & Keluar (Kartu Stok)
              </h4>

              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr style="background-color: #f4f6f9; color: #333;">
                      <th class="text-center" style="width: 40px;">No</th>
                      <th class="text-center" style="width: 100px;">Tanggal</th>
                      <th style="width: 140px;">No. Dokumen / Ref</th>
                      <th>Keterangan / Sumber & Tujuan</th>
                      <th class="text-center" style="width: 90px; color: #00a65a;">Masuk (In)</th>
                      <th class="text-center" style="width: 90px; color: #dd4b39;">Keluar (Out)</th>
                      <th class="text-center" style="width: 90px; font-weight: 700;">Saldo Akhir</th>
                      <th style="width: 130px;">Petugas</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      // Kalkulasi saldo mundur atau maju
                      // Untuk simulasi kartu stok demo, hitung saldo berjalan
                      $runningBalance = 0;
                    @endphp

                    @if(count($stockCardMutations) > 0)
                      @foreach($stockCardMutations as $idx => $mut)
                        @php
                          $runningBalance += ($mut['qty_in'] - $mut['qty_out']);
                        @endphp
                        <tr>
                          <td class="text-center">{{ $idx + 1 }}</td>
                          <td class="text-center">{{ date('d/m/Y', strtotime($mut['date'])) }}</td>
                          <td>
                            @if($mut['type'] === 'in')
                              <span class="label label-success"><i class="fa fa-arrow-down"></i> {{ $mut['ref_number'] }}</span>
                            @else
                              <span class="label label-warning"><i class="fa fa-arrow-up"></i> {{ $mut['ref_number'] }}</span>
                            @endif
                          </td>
                          <td>
                            <strong>{{ $mut['type_label'] }}</strong><br>
                            <small class="text-muted"><i class="fa fa-info-circle"></i> {{ $mut['source_destination'] }} | {{ $mut['notes'] }}</small>
                          </td>
                          <td class="text-center" style="font-weight: 600; color: #00a65a;">
                            {{ $mut['qty_in'] > 0 ? '+' . number_format($mut['qty_in']) : '-' }}
                          </td>
                          <td class="text-center" style="font-weight: 600; color: #dd4b39;">
                            {{ $mut['qty_out'] > 0 ? '-' . number_format($mut['qty_out']) : '-' }}
                          </td>
                          <td class="text-center" style="font-weight: 700; background-color: #f9f9f9;">
                            {{ number_format($runningBalance) }} {{ $mut['unit'] }}
                          </td>
                          <td>
                            <small><i class="fa fa-user"></i> {{ $mut['officer'] }}</small>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 25px;">
                          <i class="fa fa-folder-open-o fa-2x"></i><br>
                          Belum ada transaksi mutasi penerimaan atau pengeluaran untuk barang ini.
                        </td>
                      </tr>
                    @endif
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #edf2f7; font-weight: 700;">
                      <td colspan="4" class="text-right">Posisi Saldo Fisik Gudang Terakhir:</td>
                      <td class="text-center text-green">+{{ number_format(array_sum(array_column($stockCardMutations, 'qty_in'))) }}</td>
                      <td class="text-center text-red">-{{ number_format(array_sum(array_column($stockCardMutations, 'qty_out'))) }}</td>
                      <td class="text-center" style="background-color: #e2e8f0; font-size: 15px;">
                        {{ number_format($selectedItem['stock'] ?? 0) }} {{ $selectedItem['unit'] }}
                      </td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            @endif
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 2: JURNAL MUTASI LOGISTIK             -->
        <!-- ========================================== -->
        @if($activeTab === 'mutasi')
          <div class="tab-pane active" id="tab_mutasi">
            <div class="row" style="margin-bottom: 15px;">
              <div class="col-md-6">
                <h4 style="margin: 0; font-weight: 600;">
                  <i class="fa fa-list-alt text-green"></i> Jurnal Arus Logistik Keseluruhan
                </h4>
                <p class="text-muted" style="font-size: 12px; margin-top: 4px;">
                  Pencatatan kronologis arus barang masuk (Inbound BASTB) dan pengeluaran barang (Outbound BPPB).
                </p>
              </div>
              <div class="col-md-6 text-right">
                <a href="{{ route('inventaris.penerimaan') }}" class="btn btn-success btn-sm">
                  <i class="fa fa-plus-circle"></i> Catat Penerimaan Masuk
                </a>
                <a href="{{ route('inventaris.pengeluaran') }}" class="btn btn-warning btn-sm">
                  <i class="fa fa-minus-circle"></i> Catat Pengeluaran Keluar
                </a>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="background-color: #f4f6f9; color: #333;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th class="text-center" style="width: 100px;">Tanggal</th>
                    <th style="width: 130px;">No. Transaksi</th>
                    <th class="text-center" style="width: 90px;">Tipe</th>
                    <th>Nama Barang Inventaris</th>
                    <th class="text-center" style="width: 100px;">Volume</th>
                    <th>Rekan Kerja / Unit Tujuan</th>
                    <th style="width: 140px;">Petugas</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($allMutations as $idx => $row)
                    <tr>
                      <td class="text-center">{{ $idx + 1 }}</td>
                      <td class="text-center">{{ date('d/m/Y', strtotime($row['date'])) }}</td>
                      <td><span class="label label-default">{{ $row['code'] }}</span></td>
                      <td class="text-center">
                        @if($row['type'] === 'Masuk')
                          <span class="label label-success"><i class="fa fa-arrow-down"></i> Masuk</span>
                        @else
                          <span class="label label-warning"><i class="fa fa-arrow-up"></i> Keluar</span>
                        @endif
                      </td>
                      <td>
                        <strong>{{ $row['item_name'] }}</strong><br>
                        <span class="badge" style="background:#805ad5; font-size:10px;">{{ strtoupper($row['category']) }}</span>
                      </td>
                      <td class="text-center" style="font-weight: 700; color: {{ $row['type'] === 'Masuk' ? '#00a65a' : '#dd4b39' }}">
                        {{ $row['type'] === 'Masuk' ? '+' : '-' }}{{ number_format($row['qty']) }} {{ $row['unit'] }}
                      </td>
                      <td>{{ $row['partner'] }}</td>
                      <td><small><i class="fa fa-user"></i> {{ $row['officer'] }}</small></td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center text-muted" style="padding: 20px;">
                        Belum ada rekaman arus mutasi barang.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 3: VALUASI ASET & REKAP KATEGORI      -->
        <!-- ========================================== -->
        @if($activeTab === 'valuasi_aset')
          <div class="tab-pane active" id="tab_valuasi">
            <h4 style="font-weight: 600; margin-bottom: 15px;">
              <i class="fa fa-pie-chart text-purple"></i> Rekapitulasi Nilai Valuasi Aset Logistik Per Kategori
            </h4>

            <div class="row">
              <div class="col-md-7">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr style="background-color: #f4f6f9;">
                        <th>Kategori Logistik</th>
                        <th class="text-center" style="width: 90px;">Jumlah SKU</th>
                        <th class="text-center" style="width: 100px;">Total Unit Fisik</th>
                        <th class="text-right" style="width: 170px;">Total Nilai Buku (Rp)</th>
                        <th class="text-center" style="width: 80px;">Porsi (%)</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($categorySummary as $catKey => $catData)
                        @php
                          $percentage = $totalAssetValue > 0 ? ($catData['total_value'] / $totalAssetValue) * 100 : 0;
                        @endphp
                        <tr>
                          <td>
                            <strong>{{ strtoupper($catKey) }}</strong>
                          </td>
                          <td class="text-center">{{ number_format($catData['count']) }}</td>
                          <td class="text-center font-bold">{{ number_format($catData['total_stock']) }}</td>
                          <td class="text-right" style="font-weight: 600;">
                            Rp {{ number_format($catData['total_value'], 0, ',', '.') }}
                          </td>
                          <td class="text-center">
                            <span class="badge bg-purple">{{ number_format($percentage, 1) }}%</span>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr style="background-color: #edf2f7; font-weight: 700;">
                        <td>TOTAL KESELURUHAN</td>
                        <td class="text-center">{{ number_format($totalItemsCount) }} SKU</td>
                        <td class="text-center">{{ number_format(array_sum(array_column($categorySummary, 'total_stock'))) }} Unit</td>
                        <td class="text-right text-green" style="font-size: 15px;">
                          Rp {{ number_format($totalAssetValue, 0, ',', '.') }}
                        </td>
                        <td class="text-center">100%</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <div class="col-md-5">
                <div class="box box-solid box-primary">
                  <div class="box-header with-border" style="background-color: #1a365d;">
                    <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-info-circle"></i> Ringkasan Kebijakan Aset Sekolah</h3>
                  </div>
                  <div class="box-body" style="font-size: 13px; line-height: 1.6;">
                    <p>
                      <strong>Sistem Inventarisasi Alia Islamic School:</strong>
                    </p>
                    <ul style="padding-left: 20px;">
                      <li>Valuasi aset menggunakan metode FIFO (<em>First In First Out</em>) berbasis harga perolehan pengadaan.</li>
                      <li>Kategori <strong>Laboratorium & Multimedia</strong> diklasifikasikan sebagai <em>Capital Assets</em> dengan jadwal kalibrasi tahunan berkala.</li>
                      <li>Kategori <strong>ATK & Sanitasi</strong> diklasifikasikan sebagai <em>Barang Habis Pakai (Consumables)</em> dengan mekanisme <em>Safety Stock Buffer</em>.</li>
                      <li>Seluruh mutasi tercatat otomatis saat penerimaan fisik barang dan penerbitan bon pengeluaran unit.</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 4: REKAP KONDISI FISIK ALAT           -->
        <!-- ========================================== -->
        @if($activeTab === 'kondisi_alat')
          <div class="tab-pane active" id="tab_kondisi">
            <h4 style="font-weight: 600; margin-bottom: 15px;">
              <i class="fa fa-heartbeat text-red"></i> Distribusi Status Kelayakan Fisik Barang & Sarana Praktik
            </h4>

            <div class="row">
              <div class="col-md-4">
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3>{{ $conditionSummary['baik'] }} <sup style="font-size: 16px">SKU</sup></h3>
                    <p>Kondisi Baik (Layak Pakai Penuh)</p>
                  </div>
                  <div class="icon"><i class="fa fa-check-circle"></i></div>
                  <div class="small-box-footer">Tersedia operasional harian</div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="small-box bg-yellow">
                  <div class="inner">
                    <h3>{{ $conditionSummary['rusak_ringan'] }} <sup style="font-size: 16px">SKU</sup></h3>
                    <p>Rusak Ringan (Perlu Servis / Kalibrasi)</p>
                  </div>
                  <div class="icon"><i class="fa fa-warning"></i></div>
                  <a href="{{ route('inventaris.pemeliharaan') }}" class="small-box-footer">
                    Jadwalkan Servis <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>

              <div class="col-md-4">
                <div class="small-box bg-red">
                  <div class="inner">
                    <h3>{{ $conditionSummary['rusak_berat'] }} <sup style="font-size: 16px">SKU</sup></h3>
                    <p>Rusak Berat (Usulan Afkir / Ganti)</p>
                  </div>
                  <div class="icon"><i class="fa fa-times-circle"></i></div>
                  <a href="{{ route('inventaris.pengadaan') }}" class="small-box-footer">
                    Buat Usulan Pengadaan <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>

            <!-- Tabel Daftar Barang dengan Kondisi Rusak/Perlu Perhatian -->
            <h5 style="font-weight: 700; margin-top: 15px; margin-bottom: 10px;">
              Daftar Barang yang Memerlukan Perhatian Khusus (Rusak Ringan / Rusak Berat):
            </h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr style="background-color: #f4f6f9;">
                    <th class="text-center" style="width: 40px;">No</th>
                    <th style="width: 120px;">Kode Barang</th>
                    <th>Nama Barang</th>
                    <th style="width: 140px;">Kategori</th>
                    <th class="text-center" style="width: 100px;">Stok Fisik</th>
                    <th class="text-center" style="width: 120px;">Kondisi Terkini</th>
                    <th>Lokasi Penyimpanan</th>
                    <th class="text-center" style="width: 140px;">Tindakan Disarankan</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $criticalItems = array_filter($items, fn($it) => in_array($it['condition'] ?? '', ['rusak_ringan', 'rusak_berat']));
                    $cNo = 1;
                  @endphp
                  @forelse($criticalItems as $crit)
                    <tr>
                      <td class="text-center">{{ $cNo++ }}</td>
                      <td><span class="label label-default">{{ $crit['item_code'] }}</span></td>
                      <td><strong>{{ $crit['name'] }}</strong></td>
                      <td><span class="badge" style="background:#805ad5;">{{ strtoupper($crit['category']) }}</span></td>
                      <td class="text-center font-bold">{{ $crit['stock'] }} {{ $crit['unit'] }}</td>
                      <td class="text-center">
                        @if($crit['condition'] === 'rusak_ringan')
                          <span class="label label-warning">Rusak Ringan</span>
                        @else
                          <span class="label label-danger">Rusak Berat</span>
                        @endif
                      </td>
                      <td><i class="fa fa-map-marker text-red"></i> {{ $crit['storage_location'] ?? 'Gudang' }}</td>
                      <td class="text-center">
                        @if($crit['condition'] === 'rusak_ringan')
                          <a href="{{ route('inventaris.pemeliharaan') }}" class="btn btn-xs btn-primary"><i class="fa fa-wrench"></i> Servis Alat</a>
                        @else
                          <a href="{{ route('inventaris.pengadaan') }}" class="btn btn-xs btn-danger"><i class="fa fa-shopping-cart"></i> Pengadaan Baru</a>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center text-success" style="padding: 20px;">
                        <i class="fa fa-check-circle fa-2x"></i><br>
                        Alhamdulillah, seluruh barang dan peralatan inventaris saat ini dalam kondisi baik!
                      </td>
                    </tr>
                  @endforelse
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

<!-- ========================================== -->
<!-- MODAL CETAK LEMBAR KARTU STOK FISIK        -->
<!-- ========================================== -->
@if($selectedItem)
<div class="modal fade" id="modalCetakKartuStok" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #1a365d; color: #fff;">
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
        <h4 class="modal-title"><i class="fa fa-print"></i> Lembar Fisik Kartu Stok (Bin Card) — Alia Islamic School</h4>
      </div>
      <div class="modal-body" style="padding: 30px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <!-- Kop Surat -->
        <div class="row" style="border-bottom: 2px solid #1a365d; padding-bottom: 12px; margin-bottom: 20px;">
          <div class="col-xs-2 text-center">
            <div style="width: 70px; height: 70px; background: #1a365d; border-radius: 50%; color: #fff; line-height: 70px; font-size: 24px; font-weight: bold; margin: 0 auto;">
              ALIA
            </div>
          </div>
          <div class="col-xs-10">
            <h3 style="margin: 0; font-weight: 800; color: #1a365d; text-transform: uppercase; font-size: 18px;">
              YAYASAN AIRIS PENDIDIKAN ISLAM INDONESIA
            </h3>
            <h4 style="margin: 3px 0; font-weight: 700; color: #6b46c1; font-size: 16px;">
              ALIA ISLAMIC SCHOOL — UNIT LOGISTIK & INVENTARIS
            </h4>
            <p style="margin: 0; font-size: 11px; color: #555;">
              Jl. Pendidikan Karakter No. 99, Cibubur, Jakarta Timur | Telp: (021) 876-5432 | Email: sarpras@alia.sch.id
            </p>
          </div>
        </div>

        <div class="text-center" style="margin-bottom: 20px;">
          <h4 style="font-weight: 700; text-decoration: underline; margin: 0; letter-spacing: 1px;">KARTU GUDANG / KARTU STOK BARANG (BIN CARD)</h4>
          <span style="font-size: 12px; color: #666;">NOMOR REGISTRASI: {{ $selectedItem['item_code'] }}</span>
        </div>

        <table class="table table-bordered table-condensed" style="font-size: 12px; margin-bottom: 15px;">
          <tr>
            <td style="width: 20%; font-weight: bold; background: #f9f9f9;">Nama Barang</td>
            <td style="width: 30%;">{{ $selectedItem['name'] }}</td>
            <td style="width: 20%; font-weight: bold; background: #f9f9f9;">Kategori</td>
            <td style="width: 30%;">{{ strtoupper($selectedItem['category']) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background: #f9f9f9;">Satuan Fisik</td>
            <td>{{ $selectedItem['unit'] }}</td>
            <td style="font-weight: bold; background: #f9f9f9;">Lokasi Rak/Gudang</td>
            <td>{{ $selectedItem['storage_location'] ?? 'Gudang Utama' }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background: #f9f9f9;">Stok Minimum Buffer</td>
            <td>{{ $selectedItem['min_stock'] ?? 5 }} {{ $selectedItem['unit'] }}</td>
            <td style="font-weight: bold; background: #f9f9f9;">Harga Satuan Terakhir</td>
            <td>Rp {{ number_format($selectedItem['unit_price'] ?? 0, 0, ',', '.') }}</td>
          </tr>
        </table>

        <!-- Tabel Catatan Mutasi Fisik -->
        <table class="table table-bordered" style="font-size: 11px;">
          <thead>
            <tr style="background: #f4f6f9; text-align: center;">
              <th style="width: 30px; text-align: center;">No</th>
              <th style="width: 80px; text-align: center;">Tanggal</th>
              <th style="width: 120px;">No. Bukti / Ref</th>
              <th>Keterangan Transaksi</th>
              <th style="width: 70px; text-align: center;">Masuk</th>
              <th style="width: 70px; text-align: center;">Keluar</th>
              <th style="width: 80px; text-align: center;">Sisa Saldo</th>
              <th style="width: 90px; text-align: center;">Paraf</th>
            </tr>
          </thead>
          <tbody>
            @php $bBal = 0; @endphp
            @forelse($stockCardMutations as $bIdx => $bMut)
              @php $bBal += ($bMut['qty_in'] - $bMut['qty_out']); @endphp
              <tr>
                <td class="text-center">{{ $bIdx + 1 }}</td>
                <td class="text-center">{{ date('d/m/y', strtotime($bMut['date'])) }}</td>
                <td>{{ $bMut['ref_number'] }}</td>
                <td>{{ $bMut['type_label'] }} - {{ $bMut['source_destination'] }}</td>
                <td class="text-center">{{ $bMut['qty_in'] > 0 ? $bMut['qty_in'] : '-' }}</td>
                <td class="text-center">{{ $bMut['qty_out'] > 0 ? $bMut['qty_out'] : '-' }}</td>
                <td class="text-center font-bold">{{ $bBal }}</td>
                <td class="text-center" style="font-style: italic; color: #888;">Logistik</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">Belum ada catatan mutasi.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <!-- Tanda Tangan 2 Pihak -->
        <div class="row" style="margin-top: 35px; font-size: 12px;">
          <div class="col-xs-6 text-center">
            <p>Mengetahui,<br><strong>Kepala Bagian Sarpras & Logistik</strong></p>
            <div style="height: 60px;"></div>
            <p style="margin: 0; text-decoration: underline; font-weight: bold;">( Ust. Rahmat Hidayat, S.T. )</p>
            <p style="margin: 0; font-size: 11px; color: #555;">NIK: 201708-SAR-004</p>
          </div>
          <div class="col-xs-6 text-center">
            <p>Jakarta Timur, {{ date('d F Y') }}<br><strong>Petugas Gudang / Pengelola Inventaris</strong></p>
            <div style="height: 60px;"></div>
            <p style="margin: 0; text-decoration: underline; font-weight: bold;">( Ahmad Fauzi, S.Kom )</p>
            <p style="margin: 0; font-size: 11px; color: #555;">NIK: 202102-LOG-019</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Cetak Kartu Stok Ini</button>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

