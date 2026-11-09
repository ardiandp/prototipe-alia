@extends('layouts.app')

@section('title', 'Buku Rapor Tahfidz Al-Qur\'an Resmi')

@push('styles')
<style>
    .rapor-sheet {
        background: #fff;
        border: 1px solid #d2d6de;
        padding: 30px 40px;
        margin: 0 auto 30px auto;
        max-width: 900px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        border-radius: 4px;
        position: relative;
    }
    .kop-sekolah {
        border-bottom: 3px double #1a365d;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .kop-logo {
        width: 75px;
        height: auto;
        float: left;
    }
    .kop-text {
        margin-left: 90px;
        text-align: center;
    }
    .kop-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a365d;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .kop-subtitle {
        font-size: 13px;
        color: #4a5568;
        margin: 2px 0 0 0;
    }
    .kop-contact {
        font-size: 11px;
        color: #718096;
        margin-top: 4px;
    }
    .rapor-title-box {
        text-align: center;
        margin-bottom: 25px;
    }
    .rapor-title-box h4 {
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #2b6cb0;
        margin: 0;
    }
    .rapor-title-box span {
        font-size: 13px;
        color: #4a5568;
    }
    .table-rapor-info td {
        padding: 4px 8px;
        border: none !important;
        font-size: 13px;
    }
    .table-penilaian-tahfidz {
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #2d3748;
    }
    .table-penilaian-tahfidz th {
        background-color: #f7fafc;
        border: 1px solid #2d3748 !important;
        padding: 8px 10px;
        font-size: 12px;
        text-align: center;
    }
    .table-penilaian-tahfidz td {
        border: 1px solid #2d3748 !important;
        padding: 8px 10px;
        font-size: 13px;
    }
    .signature-area {
        margin-top: 40px;
    }
    .sig-line {
        margin-top: 60px;
        border-top: 1px solid #2d3748;
        font-weight: 700;
        display: inline-block;
        padding-top: 4px;
        min-width: 180px;
    }
    @media print {
        body { background: #fff !important; }
        .main-header, .main-sidebar, .main-footer, .btn-print-hide, .filter-box-rapor {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            background: #fff !important;
            padding: 0 !important;
        }
        .rapor-sheet {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            max-width: 100% !important;
            margin: 0 !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row btn-print-hide">
    <div class="col-xs-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" style="border-radius: 4px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
    </div>
</div>

<!-- Selector Santri & Tombol Cetak -->
<div class="box box-solid filter-box-rapor btn-print-hide" style="border-radius: 6px; border: 1px solid #e1e8ed; margin-bottom: 20px;">
    <div class="box-body" style="padding: 15px;">
        <div class="row">
            <div class="col-md-8 col-xs-12">
                <form method="GET" action="{{ route('tahfidz.rapor') }}" class="form-inline">
                    <div class="form-group" style="margin-right: 15px;">
                        <label for="student_select" style="font-weight: 600; margin-right: 8px;">Pilih Santri:</label>
                        <select name="student_id" id="student_select" class="form-control" onchange="this.form.submit()" style="font-weight: 600; color: #1a365d; min-width: 320px;">
                            @foreach($students as $st)
                                <option value="{{ $st['id'] }}" {{ $selectedStudentId === $st['id'] ? 'selected' : '' }}>
                                    {{ $st['name'] }} (NIS: {{ $st['nis'] ?? '-' }}) &bull; {{ $st['class_id'] ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Muat Rapor</button>
                </form>
            </div>
            <div class="col-md-4 col-xs-12 text-right">
                <button type="button" class="btn btn-primary" onclick="window.print()" style="border-radius: 4px; font-weight: 600;">
                    <i class="fa fa-print"></i> Cetak Dokumen Rapor Resmi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- LEMBAR RAPOR RESMI BERKOP -->
<div class="rapor-sheet">
    <!-- KOP RESMI SEKOLAH -->
    <div class="kop-sekolah clearfix">
        <div class="kop-logo text-center">
            <i class="fa fa-mortar-board" style="font-size: 55px; color: #1a365d;"></i>
        </div>
        <div class="kop-text">
            <h2 class="kop-title">YAYASAN ALIA ISLAMIC SCHOOL</h2>
            <h3 class="kop-title" style="font-size: 17px; color: #2b6cb0; margin-top: 2px;">
                LEMBAGA PENDIDIKAN & PENGEMBANGAN TAHFIDZ AL-QUR'AN
            </h3>
            <p class="kop-subtitle">
                Kampus Alia Islamic School &mdash; Islamic Boarding & Full Day School
            </p>
            <p class="kop-contact">
                Jl. Pendidikan Karakter No. 10, Jakarta Timur &bull; Telp: (021) 8690-1234 &bull; Email: tahfidz@alia.sch.id &bull; Website: www.alia.sch.id
            </p>
        </div>
    </div>

    <!-- JUDUL RAPOR -->
    <div class="rapor-title-box">
        <h4>LAPORAN HASIL EVALUASI TAHFIDZ AL-QUR'AN</h4>
        <span>Tahun Ajaran 2026/2027 &mdash; Semester Ganjil</span>
    </div>

    <!-- IDENTITAS SANTRI -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-6">
            <table class="table-rapor-info">
                <tr>
                    <td style="width: 140px; font-weight: 600;">Nama Santri</td>
                    <td style="width: 10px;">:</td>
                    <td><strong>{{ $selectedStudent['name'] }}</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Nomor Induk (NIS)</td>
                    <td>:</td>
                    <td><span class="font-monospace">{{ $selectedStudent['nis'] ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Rombel / Kelas</td>
                    <td>:</td>
                    <td>{{ $class['name'] ?? ($selectedStudent['class_id'] ?? '-') }}</td>
                </tr>
            </table>
        </div>
        <div class="col-xs-6">
            <table class="table-rapor-info">
                <tr>
                    <td style="width: 140px; font-weight: 600;">Program Tahfidz</td>
                    <td style="width: 10px;">:</td>
                    <td>{{ $program['name'] ?? 'Program Reguler' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Kelompok Halaqah</td>
                    <td>:</td>
                    <td>{{ $halaqah['name'] ?? 'Halaqah Al-Qur\'an' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Musyrif Pengampu</td>
                    <td>:</td>
                    <td>{{ $halaqah['supervisor_name'] ?? 'Dr. H. Zulkifli, Lc., M.A.' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- TABEL 1: CAPAIAN KUANTITATIF HAFALAN -->
    <div style="font-weight: 700; color: #1a365d; margin-bottom: 8px; font-size: 14px;">
        A. Capaian Kuantitatif Hafalan Al-Qur'an (Target vs Realisasi)
    </div>
    <table class="table-penilaian-tahfidz">
        <thead>
            <tr>
                <th style="width: 25%;">Target Program Semester</th>
                <th style="width: 25%;">Ziyadah Masuk (Hafalan Baru)</th>
                <th style="width: 25%;">Hafalan Mutqin (Teruji)</th>
                <th style="width: 25%;">Status Tasmi' Bil Ghoib</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center" style="font-size: 14px;">
                <td><strong>{{ $targetJuz }} Juz</strong></td>
                <td style="color: #2b6cb0; font-weight: 700;">{{ $juzZiyadah }} Juz</td>
                <td style="color: #276749; font-weight: 700;">{{ $juzMutqin }} Juz</td>
                <td><span class="label label-success" style="font-size: 11px;">{{ $tasmiStatus }}</span></td>
            </tr>
        </tbody>
    </table>

    <!-- TABEL 2: PENILAIAN 4 PILAR MUTU TAHFIDZ -->
    <div style="font-weight: 700; color: #1a365d; margin-bottom: 8px; font-size: 14px;">
        B. Penilaian Kualitatif 4 Aspek Mutu Tahfidz
    </div>
    <table class="table-penilaian-tahfidz">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Aspek Penilaian Mutu</th>
                <th style="width: 100px;">Bobot</th>
                <th style="width: 110px;">Nilai Angka</th>
                <th style="width: 160px;">Predikat Kualitatif</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>Kelancaran Hafalan (Al-Hifzh wal Itqan)</strong><br>
                    <span class="text-muted" style="font-size: 11px;">Kelancaran tilawah tanpa keraguan, tidak terbolak-balik urutan ayat.</span>
                </td>
                <td class="text-center">35%</td>
                <td class="text-center font-weight-bold" style="font-size: 15px;">{{ $hifzhScore }}</td>
                <td class="text-center">
                    <span class="label label-{{ $hifzhScore >= 90 ? 'success' : 'primary' }}">
                        {{ $hifzhScore >= 90 ? 'Mumtaz' : 'Jayyid Jiddan' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>
                    <strong>Kaidah Tajwid (Ahkamut Tajwid)</strong><br>
                    <span class="text-muted" style="font-size: 11px;">Penerapan hukum mad, ghunnah, idgham, ikhfa', dan waqaf/ibtida'.</span>
                </td>
                <td class="text-center">25%</td>
                <td class="text-center font-weight-bold" style="font-size: 15px;">{{ $tajwidScore }}</td>
                <td class="text-center">
                    <span class="label label-{{ $tajwidScore >= 90 ? 'success' : 'primary' }}">
                        {{ $tajwidScore >= 90 ? 'Mumtaz' : 'Jayyid Jiddan' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>
                    <strong>Makharijul Huruf & Shifat (Fashahah)</strong><br>
                    <span class="text-muted" style="font-size: 11px;">Ketepatan tempat keluar huruf hijaiyyah dan kefasihan pelafalan.</span>
                </td>
                <td class="text-center">25%</td>
                <td class="text-center font-weight-bold" style="font-size: 15px;">{{ $makhrajScore }}</td>
                <td class="text-center">
                    <span class="label label-{{ $makhrajScore >= 90 ? 'success' : 'primary' }}">
                        {{ $makhrajScore >= 90 ? 'Mumtaz' : 'Jayyid Jiddan' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td>
                    <strong>Kedisiplinan & Adab Halaqah (Al-Inthibath wal Adab)</strong><br>
                    <span class="text-muted" style="font-size: 11px;">Kehadiran tepat waktu sesi halaqah, adab terhadap mushaf dan guru.</span>
                </td>
                <td class="text-center">15%</td>
                <td class="text-center font-weight-bold" style="font-size: 15px;">{{ $disciplineScore }}</td>
                <td class="text-center">
                    <span class="label label-{{ $disciplineScore >= 90 ? 'success' : 'primary' }}">
                        {{ $disciplineScore >= 90 ? 'Mumtaz' : 'Jayyid Jiddan' }}
                    </span>
                </td>
            </tr>
            <tr style="background: #f7fafc; font-weight: 700;">
                <td colspan="3" class="text-right" style="font-size: 14px;">NILAI AKHIR KUMULATIF TAHFIDZ :</td>
                <td class="text-center" style="font-size: 18px; color: #2b6cb0;">{{ $finalScore }}</td>
                <td class="text-center" style="font-size: 13px;">
                    <span class="badge" style="background-color: #276749; padding: 5px 10px; font-size: 12px;">{{ $predicate }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- CATATAN PEMBINAAN MUSYRIF -->
    <div style="font-weight: 700; color: #1a365d; margin-bottom: 8px; font-size: 14px;">
        C. Catatan Pembinaan & Evaluasi Musyrif Halaqah
    </div>
    <div style="border: 1px solid #2d3748; padding: 12px 15px; border-radius: 4px; font-size: 13px; line-height: 1.6; background: #fff; margin-bottom: 25px;">
        <p style="margin-bottom: 0; color: #2d3748; font-style: italic;">
            "{{ $musyrifNote }}"
        </p>
    </div>

    <!-- FORM EDIT CATATAN MUSYRIF (TIDAK DICETAK) -->
    <div class="btn-print-hide well well-sm" style="background: #edf2f7; border: 1px dashed #cbd5e0; margin-bottom: 30px;">
        <form action="{{ route('tahfidz.rapor.catatan', $selectedStudentId) }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 10px;">
                <label for="input_notes" style="font-size: 12px; font-weight: 600;">
                    <i class="fa fa-pencil"></i> Perbarui Catatan Musyrif (Simulasi Interaktif):
                </label>
                <textarea name="notes" id="input_notes" class="form-control" rows="2" style="font-size: 12px;">{{ $musyrifNote }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan Catatan Rapor</button>
        </form>
    </div>

    <!-- TANDA TANGAN RESMI -->
    <div class="signature-area">
        <div class="row text-center" style="font-size: 13px;">
            <div class="col-xs-4">
                Mengetahui,<br>
                Orang Tua / Wali Santri
                <div class="sig-line">
                    ( .................................................. )
                </div>
            </div>
            <div class="col-xs-4">
                Musyrif Pembimbing Halaqah
                <div class="sig-line">
                    {{ $halaqah['supervisor_name'] ?? 'Dr. H. Zulkifli, Lc., M.A.' }}
                </div>
            </div>
            <div class="col-xs-4">
                Jakarta, {{ date('d F Y') }}<br>
                Mudir Ma'had / Kepala Sekolah
                <div class="sig-line">
                    Ust. Muhammad Ihsan, S.Pd.I.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

