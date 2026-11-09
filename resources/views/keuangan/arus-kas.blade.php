@extends('layouts.app')

@section('title', 'Buku Kas Umum & Arus Kas')

@section('content_header')
    <h1>
        <i class="fa fa-exchange text-purple"></i> Manajemen Arus Kas Masuk & Kas Keluar (Buku Kas Umum)
        <small>Pencatatan Jurnal Penerimaan SPP & Belanja Operasional (Dapur, Utilitas, Pemeliharaan)</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Arus Kas Masuk & Keluar</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Statistik Buku Kas Umum -->
<div class="row">
    <!-- Stat 1: Total Kas Masuk -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-arrow-down"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Kas Masuk (Debit)</span>
                <span class="info-box-number">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Penerimaan SPP & Pembayaran Santri</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Kas Keluar -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-arrow-up"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kas Keluar (Kredit)</span>
                <span class="info-box-number">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 75%"></div></div>
                <span class="progress-description">Beban Dapur, Listrik, & Pemeliharaan</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Saldo Kas Berjalan -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box {{ $saldoKas >= 0 ? 'bg-navy' : 'bg-orange' }}">
            <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Saldo Kas Berjalan</span>
                <span class="info-box-number">Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">{{ $saldoKas >= 0 ? 'Surplus Operasional' : 'Defisit Anggaran' }}</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Total Mutasi Jurnal -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Mutasi Jurnal Kas</span>
                <span class="info-box-number">{{ $totalTransaksiBuku }} <small style="color:#eee; font-weight:normal;">Catatan</small></span>
                <div class="progress"><div class="progress-bar" style="width: 90%"></div></div>
                <span class="progress-description">Buku Kas Terintegrasi</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
        {{ session('error') }}
    </div>
@endif

<!-- Filter Bar & Aksi Transaksi Kas -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.arus-kas') }}" method="GET" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-arrows-v"></i> Aliran Kas:</label>
                <select name="type" class="form-control input-sm">
                    <option value="">-- Semua Aliran Kas --</option>
                    <option value="in" {{ $typeFilter === 'in' ? 'selected' : '' }}>Kas Masuk (Inflow / Debit)</option>
                    <option value="out" {{ $typeFilter === 'out' ? 'selected' : '' }}>Kas Keluar (Outflow / Kredit)</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-tag"></i> Pos Kategori:</label>
                <select name="category" class="form-control input-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-calendar"></i> Periode Bulan:</label>
                <select name="month" class="form-control input-sm">
                    <option value="">-- Semua Periode --</option>
                    <option value="2026-11" {{ $monthFilter === '2026-11' ? 'selected' : '' }}>November 2026</option>
                    <option value="2026-10" {{ $monthFilter === '2026-10' ? 'selected' : '' }}>Oktober 2026</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Uraian, no kuitansi/bukti, PIC..." style="width: 200px;">
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Saring Data
            </button>
            <a href="{{ route('keuangan.arus-kas') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>

            <div class="pull-right">
                <button type="button" class="btn btn-purple btn-sm" data-toggle="modal" data-target="#modal-catat-keluar">
                    <i class="fa fa-plus-circle"></i> Catat Kas Keluar (Pengeluaran)
                </button>
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Buku Kas Umum
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Jurnal Buku Kas Umum -->
<div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
            <i class="fa fa-book text-purple"></i> Buku Kas Umum (General Cash Journal)
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($filteredLedger) }} Baris Transaksi</span>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #1a365d; color: #fff;">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th style="width: 95px;">Tanggal</th>
                    <th style="width: 140px;">No. Bukti / Ref</th>
                    <th>Uraian Transaksi</th>
                    <th>Kategori Akun</th>
                    <th class="text-right" style="width: 130px;">Kas Masuk (Debit)</th>
                    <th class="text-right" style="width: 130px;">Kas Keluar (Kredit)</th>
                    <th>Penerima / PIC</th>
                    <th style="width: 110px;" class="text-center">Aksi / Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredLedger as $idx => $row)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle; font-size: 12px;">
                        <i class="fa fa-calendar-check-o text-muted"></i> <strong>{{ $row['date'] }}</strong>
                    </td>
                    <td style="vertical-align: middle;">
                        <code style="color: {{ $row['type'] === 'in' ? '#276749' : '#c53030' }}; font-weight: bold;">
                            {{ $row['ref_number'] }}
                        </code><br>
                        <small class="label {{ $row['type'] === 'in' ? 'label-success' : 'label-danger' }}">
                            {{ $row['type'] === 'in' ? 'DEBIT' : 'KREDIT' }}
                        </small>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 13px;">{{ $row['title'] }}</strong><br>
                        <small class="text-muted">{{ $row['notes'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <span class="label label-default" style="font-weight: normal; font-size: 11px;">
                            <i class="fa fa-tag"></i> {{ $row['category'] }}
                        </span>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        @if($row['type'] === 'in')
                            <strong class="text-green" style="font-size: 14px;">Rp {{ number_format($row['amount'], 0, ',', '.') }}</strong>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        @if($row['type'] === 'out')
                            <strong class="text-red" style="font-size: 14px;">Rp {{ number_format($row['amount'], 0, ',', '.') }}</strong>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                        <i class="fa fa-user-circle text-muted"></i> {{ $row['recipient_pic'] }}
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type="button" class="btn btn-xs btn-info" onclick='showVoucherModal(@json($row))' title="Lihat Bukti Kas (Voucher)">
                            <i class="fa fa-file-text-o"></i> Voucher
                        </button>
                        @if($row['type'] === 'out')
                            <a href="{{ route('keuangan.arus-kas.destroy', $row['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus pengeluaran ini dari buku kas?')" title="Hapus">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-book fa-2x"></i><br>
                        Belum ada catatan mutasi arus kas yang sesuai dengan saringan pencarian.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f7fafc; font-weight: bold; font-size: 14px;">
                    <td colspan="5" class="text-right">TOTAL MUTASI PERIODE INI:</td>
                    <td class="text-right text-green">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                    <td class="text-right text-red">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                    <td colspan="2" class="text-center">
                        <span class="label {{ $saldoKas >= 0 ? 'label-success' : 'label-danger' }}">
                            SALDO: Rp {{ number_format($saldoKas, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- MODAL: Catat Pengeluaran Kas Baru (Kas Keluar) -->
<div class="modal fade" id="modal-catat-keluar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('keuangan.arus-kas.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-minus-circle"></i> Catat Pengeluaran Operasional (Kas Keluar)</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul / Uraian Beban Pengeluaran <span class="text-red">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Belanja Bahan Mentah Dapur Asrama Pekan II" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori Pos Beban <span class="text-red">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="Konsumsi & Asrama">Konsumsi & Dapur Asrama</option>
                                    <option value="Utilitas & Sarpras">Utilitas (Listrik, Air, Internet)</option>
                                    <option value="Pemeliharaan">Pemeliharaan Gedung & AC</option>
                                    <option value="Kegiatan Dakwah">Kegiatan Dakwah & Halaqah</option>
                                    <option value="ATK & Administrasi">ATK & Administrasi Tata Usaha</option>
                                    <option value="Honorium">Honorarium Guru Tamu / Penguji</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit Penanggung Beban <span class="text-red">*</span></label>
                                <select name="unit_id" class="form-control" required>
                                    <option value="ALL">Semua Unit (Yayasan Alia)</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nominal Pengeluaran (Rp) <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="number" name="amount" class="form-control" placeholder="2500000" min="1000" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Transaksi <span class="text-red">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pihak Penerima / Rekanan / Toko <span class="text-red">*</span></label>
                        <input type="text" name="recipient" class="form-control" placeholder="Contoh: CV Mitra Pangan Sejahtera / Toko Berkah ATK" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Tambahan & Nomor Bukti Fisik</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Nomor faktur, kuitansi toko, atau nota pembelian..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-save"></i> Simpan ke Buku Kas Umum
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Bukti Voucher Kas (Printable Cash Voucher) -->
<div class="modal fade" id="modal-voucher" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Voucher Bukti Kas — Alia Islamic School</h4>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <div class="row" style="border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 15px;">
                    <div class="col-xs-7">
                        <h4 style="margin: 0; font-weight: bold; color: #1a365d;">ALIA ISLAMIC SCHOOL</h4>
                        <small class="text-muted">Voucher Jurnal Kas Umum</small>
                    </div>
                    <div class="col-xs-5 text-right">
                        <h5 id="vch_type_badge" style="margin: 0; font-weight: bold;">-</h5>
                        <code id="vch_ref" style="font-size: 13px;">-</code>
                    </div>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th style="width: 35%; background-color: #f7fafc;">Tanggal</th>
                        <td id="vch_date">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Kategori Akun</th>
                        <td id="vch_cat">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Uraian Transaksi</th>
                        <td id="vch_title" style="font-weight: bold; color: #1a365d;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Pihak Terkait / PIC</th>
                        <td id="vch_pic">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Catatan & Referensi</th>
                        <td id="vch_notes">-</td>
                    </tr>
                    <tr style="background-color: #faf5ff;">
                        <th style="font-size: 15px; color: #6b46c1;">Jumlah Nominal</th>
                        <td id="vch_amount" style="font-size: 18px; font-weight: bold;">-</td>
                    </tr>
                </table>

                <div class="row text-center" style="margin-top: 25px;">
                    <div class="col-xs-6">
                        Disetujui Oleh,<br>
                        <strong>Bendahara Utama</strong><br><br><br>
                        <u>(Hj. Nurul Hidayati, S.E.)</u>
                    </div>
                    <div class="col-xs-6">
                        Penerima / Pemohon,<br>
                        <strong>Petugas Terkait</strong><br><br><br>
                        <u>(..........................................)</u>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-purple" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Bukti Voucher
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function showVoucherModal(item) {
        document.getElementById('vch_ref').innerText = item.ref_number;
        document.getElementById('vch_date').innerText = item.date;
        document.getElementById('vch_cat').innerText = item.category;
        document.getElementById('vch_title').innerText = item.title;
        document.getElementById('vch_pic').innerText = item.recipient_pic;
        document.getElementById('vch_notes').innerText = item.notes || '-';
        document.getElementById('vch_amount').innerText = 'Rp ' + Number(item.amount).toLocaleString('id-ID');

        if (item.type === 'in') {
            document.getElementById('vch_type_badge').innerHTML = '<span class="label label-success">BUKTI KAS MASUK (BKM)</span>';
            document.getElementById('vch_amount').style.color = '#276749';
        } else {
            document.getElementById('vch_type_badge').innerHTML = '<span class="label label-danger">BUKTI KAS KELUAR (BKK)</span>';
            document.getElementById('vch_amount').style.color = '#c53030';
        }

        $('#modal-voucher').modal('show');
    }
</script>
@stop
