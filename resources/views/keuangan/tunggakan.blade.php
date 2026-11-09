@extends('layouts.app')

@section('title', 'Tunggakan & Piutang Santri')

@section('content_header')
    <h1>
        <i class="fa fa-exclamation-triangle text-purple"></i> Manajemen Piutang & Tunggakan SPP Santri
        <small>Laporan Penuaan Piutang (Aging), Monitoring Jatuh Tempo, Penerbitan Surat Peringatan SP-1/SP-2</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Tunggakan & Piutang</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Statistik Penuaan Piutang (Aging Summary) -->
<div class="row">
    <!-- Stat 1: Total Piutang Belum Lunas -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Piutang Berjalan</span>
                <span class="info-box-number">Rp {{ number_format($totalArrearsAmount, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">{{ $totalArrearsCount }} Tagihan Belum Lunas</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Aging 1 - 30 Hari -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Lewat 1 - 30 Hari</span>
                <span class="info-box-number">Rp {{ number_format($agingSummary['1_30']['amount'], 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 50%"></div></div>
                <span class="progress-description">{{ $agingSummary['1_30']['count'] }} Tagihan Lewat Tempo</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Aging 31 - 60 Hari -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-orange" style="color: #fff !important;">
            <span class="info-box-icon"><i class="fa fa-calendar-times-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Lewat 31 - 60 Hari</span>
                <span class="info-box-number">Rp {{ number_format($agingSummary['31_60']['amount'], 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 75%"></div></div>
                <span class="progress-description">{{ $agingSummary['31_60']['count'] }} Tagihan Perlu SP-1</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Aging > 60 Hari (Kritis) -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-exclamation-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Lewat > 60 Hari (Kritis)</span>
                <span class="info-box-number">Rp {{ number_format($agingSummary['over_60']['amount'], 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 90%"></div></div>
                <span class="progress-description">{{ $agingSummary['over_60']['count'] }} Tagihan Perlu SP-2 / Rapat</span>
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

<!-- Filter Bar & Saring Data -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.tunggakan') }}" method="GET" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-university"></i> Unit:</label>
                <select name="unit_id" class="form-control input-sm">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u['id'] }}" {{ $unitFilter === $u['id'] ? 'selected' : '' }}>{{ $u['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-hourglass-half"></i> Penuaan (Aging):</label>
                <select name="aging" class="form-control input-sm">
                    <option value="">-- Semua Kategori Aging --</option>
                    <option value="current" {{ $agingFilter === 'current' ? 'selected' : '' }}>Belum Jatuh Tempo</option>
                    <option value="1_30" {{ $agingFilter === '1_30' ? 'selected' : '' }}>1 - 30 Hari</option>
                    <option value="31_60" {{ $agingFilter === '31_60' ? 'selected' : '' }}>31 - 60 Hari</option>
                    <option value="over_60" {{ $agingFilter === 'over_60' ? 'selected' : '' }}>> 60 Hari (Kritis)</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-users"></i> Kelas:</label>
                <select name="class_id" class="form-control input-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c['id'] }}" {{ $classFilter === $c['id'] ? 'selected' : '' }}>{{ $c['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="No. invoice, nama santri, wali..." style="width: 200px;">
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Saring Data
            </button>
            <a href="{{ route('keuangan.tunggakan') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>

            <div class="pull-right">
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Laporan Piutang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Laporan Penuaan Piutang (Aging Report Table) -->
<div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
            <i class="fa fa-list-alt text-purple"></i> Daftar Tagihan Menunggak & Penuaan Piutang Santri
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-danger">{{ count($enrichedArrears) }} Rekaman Piutang</span>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #1a365d; color: #fff;">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Nomor Invoice</th>
                    <th>Nama Santri & Rombel</th>
                    <th>Wali Santri & Kontak</th>
                    <th>Pos Tagihan Biaya</th>
                    <th class="text-right">Sisa Piutang (Rp)</th>
                    <th class="text-center">Jatuh Tempo</th>
                    <th class="text-center">Penuaan (Aging)</th>
                    <th>Status Surat Peringatan</th>
                    <th style="width: 140px;" class="text-center">Aksi Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrichedArrears as $idx => $arr)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                        <code style="color: #6b46c1; font-weight: bold; font-size: 13px;">{{ $arr['invoice_number'] }}</code><br>
                        <small class="text-muted"><i class="fa fa-calendar"></i> {{ $arr['period_month'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 14px;">{{ $arr['student_name'] }}</strong><br>
                        <small class="text-muted">NISN: {{ $arr['nisn'] }} | Kelas: {{ $arr['class_name'] }}</small>
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                        <i class="fa fa-user text-muted"></i> <strong>{{ $arr['guardian_name'] }}</strong><br>
                        <i class="fa fa-phone text-muted"></i> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $arr['guardian_phone']) }}" target="_blank" class="text-green">{{ $arr['guardian_phone'] }}</a>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong>{{ $arr['fee_title'] }}</strong><br>
                        <small class="text-muted">{{ $arr['unit_name'] }}</small>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <strong class="text-red" style="font-size: 15px;">
                            Rp {{ number_format($arr['unpaid_amount'], 0, ',', '.') }}
                        </strong>
                        @if($arr['paid_amount'] > 0)
                            <br><small class="text-muted">Telah dicicil: Rp {{ number_format($arr['paid_amount'], 0, ',', '.') }}</small>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle; font-size: 12px;">
                        <span class="text-red" style="font-weight: bold;"><i class="fa fa-calendar-times-o"></i> {{ $arr['due_date'] }}</span>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if($arr['aging_bucket'] === 'over_60')
                            <span class="label label-danger" style="font-size: 12px;"><i class="fa fa-ban"></i> > 60 Hari (Kritis)</span>
                        @elseif($arr['aging_bucket'] === '31_60')
                            <span class="label label-warning" style="background-color: #dd6b20; font-size: 12px;"><i class="fa fa-warning"></i> 31 - 60 Hari</span>
                        @elseif($arr['aging_bucket'] === '1_30')
                            <span class="label label-warning" style="font-size: 12px;"><i class="fa fa-clock-o"></i> 1 - 30 Hari</span>
                        @else
                            <span class="label label-info" style="font-size: 12px;"><i class="fa fa-calendar-check-o"></i> Mendatang</span>
                        @endif
                        <br><small class="text-muted">{{ $arr['overdue_days'] }} hari terlambat</small>
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                        @if($arr['notice_sent'])
                            <span class="text-purple" style="font-weight: bold;"><i class="fa fa-envelope-open"></i> {{ $arr['notice_sent']['type'] }}</span><br>
                            <small class="text-muted"><i class="fa fa-check"></i> {{ $arr['notice_sent']['sent_at'] }}</small>
                        @else
                            <span class="text-muted" style="font-style: italic;">Belum diterbitkan</span>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type="button" class="btn btn-xs btn-warning" onclick='openNoticeModal(@json($arr))' title="Terbitkan Surat Peringatan">
                            <i class="fa fa-send"></i> SP / Notif
                        </button>
                        <button type="button" class="btn btn-xs btn-info" onclick='showNoticeLetter(@json($arr))' title="Cetak Surat Tagihan Resmi">
                            <i class="fa fa-print"></i> Cetak
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-check-circle fa-2x text-green"></i><br>
                        Alhamdulillah! Tidak ada data tunggakan atau piutang santri yang memenuhi kriteria saringan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: Terbitkan Surat Peringatan / Notifikasi Tagihan -->
<div class="modal fade" id="modal-kirim-notif" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('keuangan.tunggakan.notice') }}" method="POST">
            @csrf
            <input type="hidden" name="invoice_id" id="notif_invoice_id">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-envelope-o"></i> Penerbitan Surat Peringatan Tunggakan SPP</h4>
                </div>
                <div class="modal-body">
                    <div class="callout callout-warning" style="font-size: 13px;">
                        Santri: <strong id="notif_student_name">-</strong> | Piutang: <strong id="notif_amount" class="text-red">-</strong><br>
                        Wali Santri: <strong id="notif_guardian">-</strong> (<span id="notif_phone">-</span>)
                    </div>

                    <div class="form-group">
                        <label>Tingkat Surat Peringatan / Kanal <span class="text-red">*</span></label>
                        <select name="notice_type" class="form-control" required>
                            <option value="Surat Peringatan I (SP-1)">Surat Peringatan I (SP-1) — Keterlambatan > 14 Hari</option>
                            <option value="Surat Peringatan II (SP-2)">Surat Peringatan II (SP-2) — Keterlambatan > 30 Hari</option>
                            <option value="Surat Peringatan III (SP-3)">Surat Peringatan III (SP-3) — Keterlambatan Kritis / Undangan Wali</option>
                            <option value="Pemberitahuan Tagihan WhatsApp">Pemberitahuan Pengingat WhatsApp Resmi Alia</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pesan Penegasan / Instruksi Penyelesaian</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Contoh: Mengharap kehadiran wali santri ke kantor Tata Usaha Keuangan untuk musyawarah pelunasan atau keringanan cicilan."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-paper-plane"></i> Terbitkan & Catat Notifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Surat Pemberitahuan Tunggakan Resmi (Printable Notice Letter) -->
<div class="modal fade" id="modal-surat-tunggakan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Surat Pemberitahuan Tunggakan Resmi — Alia Islamic School</h4>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <!-- Kop Resmi Surat Alia -->
                <div class="row" style="border-bottom: 2px solid #1a365d; padding-bottom: 15px; margin-bottom: 20px;">
                    <div class="col-xs-8">
                        <h3 style="margin-top: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA ISLAMIC SCHOOL</h3>
                        <p style="margin-bottom: 0; font-size: 12px; color: #4a5568;">
                            Badan Pengelola Keuangan & Administrasi Pendidikan Pesantren<br>
                            Jl. Pendidikan Islam No. 99, Karawang / Jakarta Timur | Telp: (021) 8899-7711
                        </p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <h4 style="color: #c53030; font-weight: bold; margin-top: 0;">SURAT PEMBERITAHUAN</h4>
                        <code id="sp_inv_num" style="font-size: 13px;">-</code>
                    </div>
                </div>

                <div style="font-size: 13px; color: #2d3748; line-height: 1.6;">
                    <p>Kepada Yth.<br>
                    Bapak/Ibu <strong><span id="sp_guardian">-</span></strong><br>
                    Wali dari santri: <strong><span id="sp_student">-</span></strong> (Kelas: <span id="sp_class">-</span>, NISN: <span id="sp_nisn">-</span>)<br>
                    Di Tempat</p>

                    <p><em>Assalamu'alaikum Warahmatullahi Wabarakatuh,</em></p>

                    <p>Semoga Bapak/Ibu senantiasa dalam limpahan taufik, hidayah, dan keberkahan dari Allah Subhanahu Wa Ta'ala dalam mendidik ananda.</p>

                    <p>Bersama surat ini, kami dari bagian Administrasi Keuangan Alia Islamic School bermaksud menyampaikan rincian kewajiban administrasi pembiayaan pendidikan ananda yang saat ini telah melewati batas jatuh tempo:</p>

                    <table class="table table-bordered" style="margin: 15px 0;">
                        <tr style="background-color: #f7fafc;">
                            <th style="width: 40%;">Pos Tagihan Biaya</th>
                            <td><strong id="sp_title">-</strong></td>
                        </tr>
                        <tr>
                            <th>Batas Akhir Jatuh Tempo</th>
                            <td id="sp_due" class="text-red" style="font-weight: bold;">-</td>
                        </tr>
                        <tr>
                            <th>Keterlambatan (Penuaan Piutang)</th>
                            <td id="sp_overdue" style="font-weight: bold;">-</td>
                        </tr>
                        <tr style="background-color: #fff5f5;">
                            <th style="color: #c53030; font-size: 15px;">Jumlah Sisa Tunggakan</th>
                            <td id="sp_amount" style="font-size: 18px; font-weight: bold; color: #c53030;">-</td>
                        </tr>
                    </table>

                    <p>Kami memohon kesediaan Bapak/Ibu untuk dapat segera menyelesaikan kewajiban tersebut melalui transfer <strong>Virtual Account BSI: 98888 + NISN</strong> atau secara langsung di loket kasir Tata Usaha Sekolah. Apabila terdapat kendala ekonomi khusus, kami mengundang Bapak/Ibu untuk bermusyawarah bersama bagian keuangan guna mencari solusi kemudahan.</p>

                    <p>Demikian pemberitahuan ini kami sampaikan. Atas perhatian, pengertian, dan kerjasama Bapak/Ibu, kami haturkan <em>Jazakumullah Khairan Katsiran</em>.</p>

                    <div class="row" style="margin-top: 30px;">
                        <div class="col-xs-6"></div>
                        <div class="col-xs-6 text-center">
                            Karawang, 09 November 2026<br>
                            <strong>Kepala Tata Usaha & Bendahara Sekolah</strong><br>
                            <br><br><br>
                            <u><strong>Hj. Nurul Hidayati, S.E.</strong></u><br>
                            NIP. 19820514 200801 2 003
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-purple" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Surat Pemberitahuan
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function openNoticeModal(item) {
        document.getElementById('notif_invoice_id').value = item.id;
        document.getElementById('notif_student_name').innerText = item.student_name + ' (' + item.class_name + ')';
        document.getElementById('notif_amount').innerText = 'Rp ' + Number(item.unpaid_amount).toLocaleString('id-ID');
        document.getElementById('notif_guardian').innerText = item.guardian_name;
        document.getElementById('notif_phone').innerText = item.guardian_phone;

        $('#modal-kirim-notif').modal('show');
    }

    function showNoticeLetter(item) {
        document.getElementById('sp_inv_num').innerText = item.invoice_number;
        document.getElementById('sp_guardian').innerText = item.guardian_name;
        document.getElementById('sp_student').innerText = item.student_name;
        document.getElementById('sp_class').innerText = item.class_name;
        document.getElementById('sp_nisn').innerText = item.nisn;
        document.getElementById('sp_title').innerText = item.fee_title;
        document.getElementById('sp_due').innerText = item.due_date;
        document.getElementById('sp_overdue').innerText = item.overdue_days + ' Hari Lewat Jatuh Tempo (' + item.aging_label + ')';
        document.getElementById('sp_amount').innerText = 'Rp ' + Number(item.unpaid_amount).toLocaleString('id-ID');

        $('#modal-surat-tunggakan').modal('show');
    }
</script>
@stop

