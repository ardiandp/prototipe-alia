@extends('layouts.app')

@section('title', 'Daftar Ulang PPDB')
@section('page_title', 'Registrasi Ulang & Biaya Masuk')
@section('page_description', 'Penyelesaian Administrasi Keuangan, Konfirmasi Masuk, dan Penerbitan Kwitansi')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li class="active">Daftar Ulang</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-blue" style="background:#2b6cb0 !important;">
        <div class="inner">
          <h3>{{ count($acceptedApplicants) }}</h3>
          <p>Santri Lulus Berhak Daftar Ulang</p>
        </div>
        <div class="icon"><i class="fa fa-user-check"></i></div>
        <a href="#table-du" class="small-box-footer">Daftar Santri Lulus <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($acceptedApplicants, fn($a) => ($a['payment_status'] ?? '') === 'lunas')) }}</h3>
          <p>Lunas Daftar Ulang (Siap Konversi)</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <a href="{{ route('ppdb.daftar-ulang', ['payment' => 'lunas']) }}" class="small-box-footer">Filter Lunas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($acceptedApplicants, fn($a) => ($a['payment_status'] ?? '') !== 'lunas')) }}</h3>
          <p>Menunggu Konfirmasi Pembayaran</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <a href="{{ route('ppdb.daftar-ulang', ['payment' => 'belum_lunas']) }}" class="small-box-footer">Filter Menunggu <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-du">
    <div class="col-md-12">
      <x-card title="Buku Administrasi Daftar Ulang Calon Santri Baru" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak Laporan</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('ppdb.daftar-ulang') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 250px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / nomor registrasi...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Unit -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="unit" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $unitFilter == 'all' ? 'selected' : '' }}>Semua Unit</option>
                  <option value="SD" {{ $unitFilter == 'SD' ? 'selected' : '' }}>SD Islam Alia</option>
                  <option value="SMP" {{ $unitFilter == 'SMP' ? 'selected' : '' }}>SMP Islam Alia</option>
                  <option value="SMA" {{ $unitFilter == 'SMA' ? 'selected' : '' }}>SMA Islam Alia Boarding</option>
                </select>
              </div>

              <!-- Filter Status Pembayaran -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="payment" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $paymentFilter == 'all' ? 'selected' : '' }}>Semua Status Pembayaran</option>
                  <option value="lunas" {{ $paymentFilter == 'lunas' ? 'selected' : '' }}>Lunas</option>
                  <option value="belum_lunas" {{ $paymentFilter == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                  <option value="menunggu" {{ $paymentFilter == 'menunggu' ? 'selected' : '' }}>Menunggu Validasi</option>
                </select>
              </div>

              @if($search || $unitFilter !== 'all' || $paymentFilter !== 'all')
                <a href="{{ route('ppdb.daftar-ulang') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <span class="text-muted"><i class="fa fa-info-circle text-blue"></i> Standar Biaya Masuk: Rp 12.500.000 s/d Rp 18.000.000</span>
          </div>
        </div>

        @if(count($acceptedApplicants) === 0)
          <x-empty-state 
            title="Tidak Ada Calon Santri Dalam Kriteria"
            message="Tidak ada santri lulus seleksi yang cocok dengan filter pencarian Anda."
            actionLabel="Reset Filter"
            actionUrl="{{ route('ppdb.daftar-ulang') }}"
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
                  <th>Wali Santri & WhatsApp</th>
                  <th class="text-center">Total Biaya Masuk</th>
                  <th class="text-center">Status Pembayaran</th>
                  <th style="width: 190px;" class="text-center">Aksi Administrasi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($acceptedApplicants as $ap)
                  @php
                    $fee = ($ap['unit_level'] === 'SMA') ? 18500000 : (($ap['unit_level'] === 'SMP') ? 15000000 : 12500000);
                    if ($ap['track'] === 'Prestasi Tahfidz') {
                      $fee = $fee * 0.5; // Beasiswa 50%
                    } elseif ($ap['track'] === 'Afirmasi / Yatim') {
                      $fee = 0; // Beasiswa 100%
                    }
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><code>{{ $ap['reg_number'] }}</code></td>
                    <td>
                      <strong style="color: #2b6cb0; font-size: 14px;">{{ $ap['name'] }}</strong><br>
                      <small class="text-muted"><i class="fa fa-trophy text-green"></i> Lulus Seleksi (Skor: {{ $ap['exam_score'] ?? '85' }})</small>
                    </td>
                    <td><span class="label label-info">{{ $ap['unit_target'] }}</span></td>
                    <td>
                      @if($ap['track'] === 'Prestasi Tahfidz')
                        <span class="label label-purple" style="background:#6b46c1;">Tahfidz (Diskon 50%)</span>
                      @elseif($ap['track'] === 'Afirmasi / Yatim')
                        <span class="label label-warning">Beasiswa Penuh</span>
                      @else
                        <span class="label label-default">Reguler</span>
                      @endif
                    </td>
                    <td>
                      {{ $ap['guardian_name'] ?? '-' }}<br>
                      @if(!empty($ap['guardian_phone']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ap['guardian_phone']) }}" target="_blank" class="text-green"><i class="fa fa-whatsapp"></i> {{ $ap['guardian_phone'] }}</a>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <strong>Rp {{ number_format($fee, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center">
                      @if(($ap['payment_status'] ?? '') === 'lunas')
                        <span class="label label-success" style="font-size:12px;"><i class="fa fa-check-circle"></i> LUNAS</span><br>
                        <small class="text-muted">{{ $ap['payment_receipt'] ?? 'KW-PPDB-2026' }}</small>
                      @elseif(($ap['payment_status'] ?? '') === 'menunggu')
                        <span class="label label-warning" style="font-size:12px;"><i class="fa fa-clock-o"></i> Menunggu Validasi</span>
                      @else
                        <span class="label label-danger" style="font-size:12px;">BELUM BAYAR</span>
                      @endif
                    </td>
                    <td class="text-center irnis-actions">
                      <!-- Surat Pengumuman Resmi Button -->
                      <button class="btn btn-xs btn-info btn-flat" title="Surat Kelulusan" data-toggle="modal" data-target="#modal-letter-{{ $ap['id'] }}">
                        <i class="fa fa-file-pdf-o"></i> Surat SK
                      </button>

                      @if(($ap['payment_status'] ?? '') !== 'lunas')
                        <!-- Tombol Bayar / Validasi Kasir -->
                        <button class="btn btn-xs btn-success btn-flat" title="Validasi Pembayaran" data-toggle="modal" data-target="#modal-pay-{{ $ap['id'] }}">
                          <i class="fa fa-credit-card"></i> Kasir Bayar
                        </button>
                      @else
                        @if(!empty($ap['is_converted']))
                          <!-- Label Sudah Dikonversi -->
                          <a href="{{ route('master.siswa') }}" class="btn btn-xs btn-default btn-flat text-green" title="Lihat di Buku Induk (NIS: {{ $ap['converted_nis'] ?? '-' }})">
                            <i class="fa fa-check-circle text-green"></i> Santri Aktif
                          </a>
                        @else
                          <!-- Form Konversi Santri Langsung -->
                          <form action="{{ route('ppdb.daftar-ulang.konversi', ['id' => $ap['id']]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Konversi calon santri {{ $ap['name'] }} menjadi siswa aktif dan terbitkan NIS baru?');">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-primary btn-flat" style="background:#2b6cb0;" title="Terbitkan NIS & Masukkan ke Buku Induk">
                              <i class="fa fa-user-plus"></i> Konversi Santri
                            </button>
                          </form>
                        @endif
                      @endif
                    </td>
                  </tr>

                  <!-- Modal Surat Keputusan Pengumuman Kelulusan Resmi -->
                  <div class="modal fade" id="modal-letter-{{ $ap['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary" style="background:#1a365d; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-envelope-open-o"></i> Surat Keputusan Kelulusan: {{ $ap['name'] }}</h4>
                        </div>
                        <div class="modal-body" style="padding: 25px; background: #fff;">
                          <!-- Kop Surat Alia Islamic School -->
                          <div class="text-center" style="border-bottom: 3px double #1a365d; padding-bottom: 12px; margin-bottom: 15px;">
                            <h3 style="margin: 0; font-weight: 800; color: #1a365d; letter-spacing: 1px;">YAYASAN PENDIDIKAN ALIA ISLAMIC SCHOOL</h3>
                            <h4 style="margin: 3px 0 0 0; color: #6b46c1; font-weight: 600;">PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB) TA 2027/2028</h4>
                            <p style="margin: 0; font-size: 11px; color: #666;">Kampus Pusat: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur &bull; Telp: (021) 86901236 &bull; Web: www.alia.sch.id</p>
                          </div>

                          <div class="text-center" style="margin-bottom: 20px;">
                            <h4 style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">SURAT KEPUTUSAN KELULUSAN SELEKSI</h4>
                            <p class="text-muted">Nomor: 082/PPDB-ALIA/SK/XI/2026</p>
                          </div>

                          <p>Berdasarkan hasil Rapat Pleno Dewan Penguji dan Panitia PPDB Alia Islamic School mengenai Ujian Masuk, dengan ini menyatakan bahwa:</p>
                          <table class="table table-condensed" style="margin-left: 20px; width: 90%;">
                            <tr><td style="width: 30%;">Nomor Registrasi</td><td>: <b>{{ $ap['reg_number'] }}</b></td></tr>
                            <tr><td>Nama Calon Santri</td><td>: <b>{{ $ap['name'] }}</b></td></tr>
                            <tr><td>Jenis Kelamin</td><td>: {{ $ap['gender'] == 'L' ? 'Laki-Laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</td></tr>
                            <tr><td>Pilihan Unit Sekolah</td><td>: <b>{{ $ap['unit_target'] }}</b></td></tr>
                            <tr><td>Jalur Pendaftaran</td><td>: {{ $ap['track'] }}</td></tr>
                            <tr><td>Nilai Skor Akhir</td><td>: <b>{{ $ap['exam_score'] ?? '85' }} / 100</b></td></tr>
                          </table>

                          <div class="alert alert-success text-center" style="font-size: 16px; font-weight: bold; margin: 15px 0;">
                            DINYATAKAN: DITERIMA SEBAGAI SANTRI BARU ALIA ISLAMIC SCHOOL
                          </div>

                          <p>Bagi calon santri yang dinyatakan lulus dimohon untuk segera menyelesaikan administrasi <b>Daftar Ulang</b> selambat-lambatnya 7 (tujuh) hari kerja setelah surat ini diterbitkan.</p>

                          <div class="row" style="margin-top: 30px;">
                            <div class="col-xs-6 text-center">
                              <p>Mengetahui,<br><b>Ketua Yayasan</b></p>
                              <br><br>
                              <p><b>Dr. H. Zulkifli, Lc., M.A.</b></p>
                            </div>
                            <div class="col-xs-6 text-center">
                              <p>Jakarta, {{ date('d F Y') }}<br><b>Ketua Panitia PPDB</b></p>
                              <br><br>
                              <p><b>Ust. Muhammad Ihsan, S.Pd.I.</b></p>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default btn-flat" onclick="window.print()"><i class="fa fa-print"></i> Cetak SK</button>
                          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Input Pembayaran Daftar Ulang (Kasir) -->
                  <div class="modal fade" id="modal-pay-{{ $ap['id'] }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-success" style="background:#28a745; color:#fff;">
                          <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                          <h4 class="modal-title"><i class="fa fa-credit-card"></i> Pembayaran Daftar Ulang: {{ $ap['name'] }}</h4>
                        </div>
                        <form action="{{ route('ppdb.daftar-ulang.pay', ['id' => $ap['id']]) }}" method="POST">
                          @csrf
                          <div class="modal-body">
                            <div class="callout callout-success" style="background:#f4f9f4; border-left-color:#28a745; padding:10px;">
                              <p style="margin:0;">No. Registrasi: <b>{{ $ap['reg_number'] }}</b> | Unit: <b>{{ $ap['unit_target'] }}</b></p>
                              <p style="margin:0;">Total Tagihan Daftar Ulang: <b style="color:#28a745; font-size:16px;">Rp {{ number_format($fee, 0, ',', '.') }}</b></p>
                            </div>

                            <div class="form-group">
                              <label>Metode Pembayaran <span class="text-danger">*</span></label>
                              <select name="payment_method" class="form-control" required>
                                <option value="BSI Virtual Account">BSI Virtual Account (Otomatis)</option>
                                <option value="Transfer Mandiri">Transfer Bank Mandiri</option>
                                <option value="Kasir Tunai">Pembayaran Tunai di Kasir Sekolah</option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label>Nominal Pembayaran (Rp) <span class="text-danger">*</span></label>
                              <input type="number" name="amount" class="form-control" value="{{ $fee }}" min="1000000" required>
                            </div>

                            <div class="form-group">
                              <label>Nomor Bukti Kuitansi / Referensi</label>
                              <input type="text" name="receipt_number" class="form-control" placeholder="Kosongkan untuk otomatis terbit KW-PPDB-xxx">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success btn-flat">
                              <i class="fa fa-check"></i> Konfirmasi Lunas & Terbitkan Kuitansi
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
