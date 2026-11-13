@extends('layouts.app')

@section('title', 'Daftar Ulang & Konversi Santri')
@section('page_title', 'Administrasi Daftar Ulang PPDB')
@section('page_description', 'Validasi Pembayaran Biaya Masuk, Penerbitan SK Kelulusan, dan Konversi Calon Santri ke Buku Induk')

@section('breadcrumbs')
  <li><a href="{{ route('ppdb.dashboard') }}">PPDB</a></li>
  <li><a href="{{ route('ppdb.pendaftar') }}">Data Pendaftar</a></li>
  <li class="active">Daftar Ulang</li>
@endsection

@section('content')

  <!-- Summary Statistics Row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['payment_status'] ?? '') === 'lunas')) }}</h3>
          <p>Daftar Ulang Lunas</p>
        </div>
        <div class="icon"><i class="fa fa-check-circle"></i></div>
        <a href="{{ route('ppdb.daftar-ulang', ['payment' => 'lunas']) }}" class="small-box-footer">Filter Lunas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['payment_status'] ?? '') === 'menunggu')) }}</h3>
          <p>Konfirmasi Kasir Menunggu</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <a href="{{ route('ppdb.daftar-ulang', ['payment' => 'menunggu']) }}" class="small-box-footer">Validasi Kasir <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => ($a['payment_status'] ?? '') === 'belum_bayar')) }}</h3>
          <p>Belum Bayar Daftar Ulang</p>
        </div>
        <div class="icon"><i class="fa fa-times-circle"></i></div>
        <a href="{{ route('ppdb.daftar-ulang', ['payment' => 'belum_bayar']) }}" class="small-box-footer">Kirim Notifikasi WA <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-purple" style="background:#6b46c1 !important;">
        <div class="inner">
          <h3>{{ count(array_filter($applicants, fn($a) => !empty($a['is_converted']))) }}</h3>
          <p>Telah Masuk Buku Induk</p>
        </div>
        <div class="icon"><i class="fa fa-user-plus"></i></div>
        <a href="{{ route('master.siswa') }}" class="small-box-footer">Lihat Buku Induk <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Main Card with Filter Toolbar & Table -->
  <div class="row" id="table-daftar-ulang">
    <div class="col-md-12">
      <x-card title="Daftar Calon Santri Diterima & Status Daftar Ulang" type="primary">
        <x-slot:tools>
          <button type="button" class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
        </x-slot:tools>

        <!-- Filter & Search Toolbar -->
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-8 col-sm-12">
            <form action="{{ route('ppdb.daftar-ulang') }}" method="GET" class="form-inline">
              <div class="input-group" style="width: 240px;">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama / reg / wali...">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
              </div>

              <!-- Filter Status Pembayaran -->
              <div class="form-group" style="margin-left: 8px;">
                <select name="payment" class="form-control" onchange="this.form.submit()">
                  <option value="all" {{ $paymentFilter == 'all' ? 'selected' : '' }}>Semua Status Pembayaran</option>
                  <option value="lunas" {{ $paymentFilter == 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                  <option value="menunggu" {{ $paymentFilter == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi Kasir</option>
                  <option value="belum_bayar" {{ $paymentFilter == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                </select>
              </div>

              @if($search || $paymentFilter !== 'all')
                <a href="{{ route('ppdb.daftar-ulang') }}" class="btn btn-default text-muted" title="Reset filter" style="margin-left: 5px;"><i class="fa fa-times"></i> Reset</a>
              @endif
            </form>
          </div>
          <div class="col-md-4 col-sm-12 text-right">
            <a href="{{ route('master.siswa') }}" class="btn btn-default btn-flat">
              <i class="fa fa-users"></i> Buka Buku Induk Siswa Aktif
            </a>
          </div>
        </div>

        @if(count($applicants) === 0)
          <x-empty-state 
            title="Tidak Ada Calon Santri Lulus"
            message="Tidak ada catatan calon santri yang lulus seleksi sesuai parameter filter Anda."
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
                  <th>Wali Santri & Telepon</th>
                  <th class="text-center">Nominal Infaq / Biaya</th>
                  <th class="text-center">Status Pembayaran</th>
                  <th style="width: 170px;" class="text-center">Aksi / Konversi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($applicants as $ap)
                  @php
                    $fee = 14500000; // Standar biaya masuk & seragam
                    if ($ap['track'] === 'Prestasi Tahfidz') {
                      $fee = 7250000; // Diskon 50%
                    } elseif ($ap['track'] === 'Afirmasi / Yatim') {
                      $fee = 0; // Beasiswa penuh
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
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </x-card>
    </div>
  </div>

  <!-- Detail Modals Section (Moved outside table) -->
  @foreach($applicants as $ap)
    @php
      $fee = 14500000;
      if ($ap['track'] === 'Prestasi Tahfidz') {
        $fee = 7250000;
      } elseif ($ap['track'] === 'Afirmasi / Yatim') {
        $fee = 0;
      }
    @endphp

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

    @if(($ap['payment_status'] ?? '') !== 'lunas')
      <!-- Modal Kasir Bayar Daftar Ulang -->
      <div class="modal fade" id="modal-pay-{{ $ap['id'] }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary" style="background:#00a65a; color:#fff;">
              <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
              <h4 class="modal-title"><i class="fa fa-credit-card"></i> Kasir Pembayaran Daftar Ulang: {{ $ap['name'] }}</h4>
            </div>
            <form action="{{ route('ppdb.daftar-ulang.bayar', ['id' => $ap['id']]) }}" method="POST">
              @csrf
              <div class="modal-body">
                <div class="well well-sm" style="background:#f4f6f9;">
                  <table class="table table-condensed" style="margin:0;">
                    <tr><th style="width:40%;">Nomor Registrasi</th><td><code>{{ $ap['reg_number'] }}</code></td></tr>
                    <tr><th>Nama Calon Santri</th><td><b>{{ $ap['name'] }}</b></td></tr>
                    <tr><th>Unit Target</th><td>{{ $ap['unit_target'] }} ({{ $ap['track'] }})</td></tr>
                    <tr><th>Total Tagihan Masuk</th><td><h4 style="margin:0; color:#00a65a; font-weight:bold;">Rp {{ number_format($fee, 0, ',', '.') }}</h4></td></tr>
                  </table>
                </div>

                <div class="form-group">
                  <label>Metode Pembayaran Kasir <span class="text-danger">*</span></label>
                  <select name="method" class="form-control" required>
                    <option value="Virtual Account BSI">Virtual Account Bank Syariah Indonesia (BSI)</option>
                    <option value="Transfer Bank Muamalat">Transfer Bank Muamalat</option>
                    <option value="Tunai / Kasir Sekolah">Tunai di Kasir Keuangan Sekolah</option>
                    <option value="Beasiswa Yayasan">Alokasi Beasiswa / Afirmasi Yayasan</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Nomor Referensi Transaksi / Bukti Bayar <span class="text-danger">*</span></label>
                  <input type="text" name="ref_number" class="form-control" value="KW-PPDB-{{ date('Ymd') }}-{{ rand(100, 999) }}" required>
                </div>

                <div class="form-group">
                  <label>Catatan Pembayaran</label>
                  <textarea name="notes" class="form-control" rows="2" placeholder="Catatan kuitansi...">Pelunasan biaya pendaftaran dan uang pangkal PPDB.</textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-check"></i> Konfirmasi Pembayaran Lunas
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif
  @endforeach

@endsection
