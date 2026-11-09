@extends('layouts.app')

@section('title', 'Beasiswa & Potongan Biaya')

@section('content_header')
    <h1>
        <i class="fa fa-graduation-cap text-purple"></i> Manajemen Beasiswa & Keringanan Biaya
        <small>Beasiswa Tahfidz, Prestasi Sains/Olahraga, Yatim & Dhuafa, Sibling Discount, & Alokasi Infaq</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('keuangan.dashboard') }}">Keuangan</a></li>
        <li class="active">Beasiswa & Potongan</li>
    </ol>
@stop

@section('content')
<!-- Ringkasan Statistik Beasiswa -->
<div class="row">
    <!-- Stat 1: Total Penerima -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Santri Penerima</span>
                <span class="info-box-number">{{ $totalBeasiswa }} <small style="color:#eee; font-weight:normal;">Santri</small></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Tahfidz, Prestasi, & Yatim</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Beasiswa Disetujui -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Telah Disetujui</span>
                <span class="info-box-number">{{ $totalApproved }} <small style="color:#eee; font-weight:normal;">SK Beasiswa</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalBeasiswa > 0 ? round(($totalApproved / $totalBeasiswa)*100) : 0 }}%"></div></div>
                <span class="progress-description">Aktif Memotong Tagihan</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Menunggu Verifikasi -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-hourglass-half"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pengajuan Baru</span>
                <span class="info-box-number">{{ $totalPending }} <small style="color:#eee; font-weight:normal;">Menunggu</small></span>
                <div class="progress"><div class="progress-bar" style="width: {{ $totalBeasiswa > 0 ? round(($totalPending / $totalBeasiswa)*100) : 0 }}%"></div></div>
                <span class="progress-description">Menunggu Sidang Pleno</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Total Alokasi Bantuan Biaya -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Alokasi Subsidi</span>
                <span class="info-box-number">Rp {{ number_format($totalAlokasiNominal, 0, ',', '.') }}</span>
                <div class="progress"><div class="progress-bar" style="width: 85%"></div></div>
                <span class="progress-description">Dana ZISWAF & CSR Yayasan</span>
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

<!-- Filter Bar & Aksi Beasiswa -->
<div class="box box-solid" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 20px;">
    <div class="box-body">
        <form action="{{ route('keuangan.beasiswa') }}" method="GET" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-tag"></i> Jenis Beasiswa:</label>
                <select name="type" class="form-control input-sm">
                    <option value="">-- Semua Jenis Beasiswa --</option>
                    <option value="tahfidz" {{ $typeFilter === 'tahfidz' ? 'selected' : '' }}>Tahfidz Al-Qur'an (Mutqin)</option>
                    <option value="prestasi" {{ $typeFilter === 'prestasi' ? 'selected' : '' }}>Prestasi Sains & Olahraga</option>
                    <option value="yatim_dhuafa" {{ $typeFilter === 'yatim_dhuafa' ? 'selected' : '' }}>Yatim & Dhuafa (ZISWAF)</option>
                    <option value="bersaudara" {{ $typeFilter === 'bersaudara' ? 'selected' : '' }}>Diskon Saudara Kandung</option>
                    <option value="pegawai" {{ $typeFilter === 'pegawai' ? 'selected' : '' }}>Putra-Putri Guru & Pegawai</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-check-square-o"></i> Status Verifikasi:</label>
                <select name="status" class="form-control input-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label style="color: #1a365d;"><i class="fa fa-search"></i> Cari:</label>
                <input type="text" name="q" value="{{ $search }}" class="form-control input-sm" placeholder="Nama program, santri, kode..." style="width: 200px;">
            </div>

            <button type="submit" class="btn btn-purple btn-sm">
                <i class="fa fa-filter"></i> Saring Data
            </button>
            <a href="{{ route('keuangan.beasiswa') }}" class="btn btn-default btn-sm">
                <i class="fa fa-refresh"></i> Reset
            </a>

            <div class="pull-right">
                <button type="button" class="btn btn-purple btn-sm" data-toggle="modal" data-target="#modal-tambah-beasiswa">
                    <i class="fa fa-plus-circle"></i> Daftarkan Beasiswa Santri
                </button>
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Rekap Beasiswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Penerima Beasiswa & Keringanan Biaya -->
<div class="box box-solid" style="border: 1px solid #cbd5e0; border-top: 3px solid #6b46c1;">
    <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: bold; color: #1a365d;">
            <i class="fa fa-list text-purple"></i> Daftar Santri Penerima Beasiswa & Keringanan Tagihan
        </h3>
        <div class="box-tools pull-right">
            <span class="label label-primary">{{ count($enrichedScholarships) }} Data Ditampilkan</span>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: #1a365d; color: #fff;">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Kode SK</th>
                    <th>Nama Santri & Rombel</th>
                    <th>Nama Program Beasiswa</th>
                    <th>Jenis Kategori</th>
                    <th class="text-center">Persentase</th>
                    <th class="text-right">Nominal Potongan (Rp)</th>
                    <th>Periode Berlaku</th>
                    <th class="text-center">Status</th>
                    <th style="width: 130px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrichedScholarships as $idx => $sch)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">
                        <code style="color: #6b46c1; font-weight: bold; font-size: 13px;">{{ $sch['code'] }}</code>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #1a365d; font-size: 14px;">{{ $sch['student_name'] }}</strong><br>
                        <small class="text-muted">NISN: {{ $sch['nisn'] }} | Kelas: {{ $sch['class_name'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        <strong style="color: #2b6cb0;">{{ $sch['name'] }}</strong><br>
                        <small class="text-muted"><i class="fa fa-tag"></i> {{ $sch['fee_name'] }}</small>
                    </td>
                    <td style="vertical-align: middle;">
                        @if($sch['type'] === 'tahfidz')
                            <span class="label label-success"><i class="fa fa-quran"></i> Tahfidz 30 Juz</span>
                        @elseif($sch['type'] === 'prestasi')
                            <span class="label label-primary"><i class="fa fa-trophy"></i> Prestasi OSN</span>
                        @elseif($sch['type'] === 'yatim_dhuafa')
                            <span class="label label-warning"><i class="fa fa-heart"></i> Yatim & ZISWAF</span>
                        @elseif($sch['type'] === 'bersaudara')
                            <span class="label label-info"><i class="fa fa-users"></i> Sibling Discount</span>
                        @else
                            <span class="label label-default"><i class="fa fa-user"></i> Anak Pegawai</span>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <span class="badge bg-purple" style="font-size: 13px;">{{ $sch['discount_percentage'] }}%</span>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <strong class="text-green" style="font-size: 15px;">
                            - Rp {{ number_format($sch['discount_amount'], 0, ',', '.') }}
                        </strong>
                    </td>
                    <td style="vertical-align: middle; font-size: 12px;">
                        <i class="fa fa-calendar text-muted"></i> {{ $sch['period'] }}<br>
                        <small class="text-muted">Oleh: {{ $sch['approved_by'] }}</small>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if($sch['approval_status'] === 'disetujui')
                            <span class="label label-success"><i class="fa fa-check"></i> DISETUJUI</span>
                        @elseif($sch['approval_status'] === 'diajukan')
                            <span class="label label-warning"><i class="fa fa-hourglass-half"></i> DIAJUKAN</span>
                        @else
                            <span class="label label-danger"><i class="fa fa-times"></i> DITOLAK</span>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type="button" class="btn btn-xs btn-info" onclick='showScholarshipDetail(@json($sch))' title="Lihat SK Beasiswa">
                            <i class="fa fa-eye"></i> SK
                        </button>
                        @if($sch['approval_status'] === 'diajukan')
                            <a href="{{ route('keuangan.beasiswa.approve', $sch['id']) }}" class="btn btn-xs btn-success" onclick="return confirm('Setujui pengajuan beasiswa {{ $sch['name'] }}?')" title="Setujui Beasiswa">
                                <i class="fa fa-check"></i>
                            </a>
                        @endif
                        <a href="{{ route('keuangan.beasiswa.destroy', $sch['id']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Hapus data beasiswa {{ $sch['name'] }}?')" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-graduation-cap fa-2x"></i><br>
                        Belum ada data beasiswa yang cocok dengan kriteria filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: Daftarkan Beasiswa Santri Baru -->
<div class="modal fade" id="modal-tambah-beasiswa" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('keuangan.beasiswa.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Pendaftaran Beasiswa & Keringanan Biaya</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Santri Penerima Beasiswa <span class="text-red">*</span></label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri Terdaftar --</option>
                            @foreach($students as $st)
                                <option value="{{ $st['id'] }}">
                                    {{ $st['name'] }} (NISN: {{ $st['nisn'] ?? $st['nis'] }}) — {{ $st['unit_level'] ?? 'Santri' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Nama Program Beasiswa <span class="text-red">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Beasiswa Tahfidz 30 Juz / Subsidi Donatur" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Kategori Beasiswa <span class="text-red">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="tahfidz">Tahfidz Al-Qur'an</option>
                                    <option value="prestasi">Prestasi Akademik / Olahraga</option>
                                    <option value="yatim_dhuafa">Yatim & Dhuafa (ZISWAF)</option>
                                    <option value="bersaudara">Diskon Saudara Kandung</option>
                                    <option value="pegawai">Putra-Putri Pegawai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Target Pos Biaya yang Dipotong <span class="text-red">*</span></label>
                                <select name="fee_category_id" class="form-control" required>
                                    @foreach($feeCategories as $fc)
                                        <option value="{{ $fc['id'] }}">{{ $fc['name'] }} (Rp {{ number_format($fc['amount'], 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Besaran Diskon (%) <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="discount_percentage" class="form-control" value="50" min="1" max="100" required>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Masa Berlaku / Periode <span class="text-red">*</span></label>
                                <input type="text" name="period" class="form-control" value="Tahun Ajaran 2026/2027" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Persetujuan <span class="text-red">*</span></label>
                                <select name="approval_status" class="form-control" required>
                                    <option value="disetujui">Disetujui (Langsung Aktif)</option>
                                    <option value="diajukan">Diajukan (Menunggu Sidang)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pejabat Pemberi Rekomendasi / Pengesah <span class="text-red">*</span></label>
                        <input type="text" name="approved_by" class="form-control" value="KH. Abdullah Gymnastiar / Pimpinan Yayasan" required>
                    </div>

                    <div class="form-group">
                        <label>Catatan Prestasi / Alasan Pertimbangan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Uraian dokumen sertifikat, prestasi juz tahfidz, atau surat keterangan tidak mampu..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fa fa-save"></i> Daftarkan & Simpan SK Beasiswa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Surat Keputusan (SK) Resmi Beasiswa (Printable) -->
<div class="modal fade" id="modal-sk-beasiswa" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a365d; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-graduation-cap"></i> Surat Keputusan Beasiswa Pendidikan — Alia Islamic School</h4>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <div class="text-center" style="border-bottom: 2px solid #1a365d; padding-bottom: 15px; margin-bottom: 20px;">
                    <h3 style="margin: 0; font-weight: bold; color: #1a365d;">YAYASAN ALIA ISLAMIC SCHOOL</h3>
                    <p style="margin-bottom: 0; font-size: 12px; color: #4a5568;">
                        Kompleks Pesantren Terpadu Alia, Karawang / Jakarta Timur<br>
                        Surat Keputusan Pemberian Beasiswa & Bantuan Pendidikan
                    </p>
                </div>

                <div class="text-center" style="margin-bottom: 20px;">
                    <h4 style="font-weight: bold; color: #6b46c1; margin-bottom: 2px;">SURAT KEPUTUSAN BEASISWA</h4>
                    <code id="sk_code" style="font-size: 14px; font-weight: bold;">-</code>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th style="width: 38%; background-color: #f7fafc;">Nama Santri</th>
                        <td id="sk_student" style="font-weight: bold; color: #1a365d;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">NISN / Kelas</th>
                        <td id="sk_nisn_class">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Program Beasiswa</th>
                        <td id="sk_name" style="color: #2b6cb0; font-weight: bold;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Kategori Keringanan</th>
                        <td id="sk_type">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Pos Biaya Ditanggung</th>
                        <td id="sk_fee">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Persentase Keringanan</th>
                        <td id="sk_pct" style="font-weight: bold; color: #6b46c1;">-</td>
                    </tr>
                    <tr style="background-color: #f0fff4;">
                        <th style="background-color: #f0fff4; color: #22543d;">Nilai Subsidi per Bulan</th>
                        <td id="sk_amount" style="font-weight: bold; font-size: 16px; color: #276749;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Masa Berlaku</th>
                        <td id="sk_period">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Keterangan Pertimbangan</th>
                        <td id="sk_notes" style="font-style: italic; color: #4a5568;">-</td>
                    </tr>
                    <tr>
                        <th style="background-color: #f7fafc;">Pengesah SK</th>
                        <td id="sk_approver" style="font-weight: bold;">-</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-purple" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak SK Beasiswa
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    function showScholarshipDetail(sch) {
        document.getElementById('sk_code').innerText = sch.code;
        document.getElementById('sk_student').innerText = sch.student_name;
        document.getElementById('sk_nisn_class').innerText = 'NISN: ' + sch.nisn + ' | Kelas: ' + sch.class_name;
        document.getElementById('sk_name').innerText = sch.name;
        document.getElementById('sk_type').innerText = sch.type.toUpperCase().replace('_', ' ');
        document.getElementById('sk_fee').innerText = sch.fee_name;
        document.getElementById('sk_pct').innerText = sch.discount_percentage + '% Potongan Biaya';
        document.getElementById('sk_amount').innerText = 'Rp ' + Number(sch.discount_amount).toLocaleString('id-ID');
        document.getElementById('sk_period').innerText = sch.period;
        document.getElementById('sk_notes').innerText = sch.notes || '-';
        document.getElementById('sk_approver').innerText = sch.approved_by;

        $('#modal-sk-beasiswa').modal('show');
    }
</script>
@stop

