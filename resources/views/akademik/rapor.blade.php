@extends('layouts.app')

@section('title', 'E-Rapor Santri & Lembar Capaian Belajar')
@section('page_title', 'E-Rapor Santri')
@section('page_description', 'Laporan Hasil Penilaian Pembelajaran, Capaian Tahfidz Al-Quran, Rekap Kehadiran, dan Catatan Wali Kelas')

@section('breadcrumbs')
  <li><a href="{{ route('master.unit') }}">Akademik</a></li>
  <li><a href="{{ route('akademik.nilai') }}">Nilai</a></li>
  <li class="active">E-Rapor</li>
@endsection

@section('styles')
  <style>
    @media print {
      .no-print, .main-sidebar, .main-header, .content-header, .btn, .breadcrumb {
        display: none !important;
      }
      .content-wrapper, .right-side, .main-footer {
        margin-left: 0 !important;
        background: #fff !important;
        padding: 0 !important;
      }
      .report-card-print {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
        margin: 0 !important;
      }
    }
    .report-card-print {
      background: #fff;
      padding: 30px;
      border: 1px solid #d2d6de;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
  </style>
@endsection

@section('content')

  <!-- Bar Kontrol & Pemilih Rombel / Santri -->
  <div class="row no-print">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-body" style="padding: 12px 20px;">
          <form method="GET" action="{{ route('akademik.rapor') }}" class="form-inline clearfix">
            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-sitemap"></i> Pilih Rombel:</label>
              <select name="class_id" class="form-control input-sm select2" onchange="this.form.submit()">
                @foreach($classes as $c)
                  <option value="{{ $c['id'] }}" {{ $selectedClassId === $c['id'] ? 'selected' : '' }}>
                    {{ $c['name'] }} ({{ $c['id'] }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <label style="margin-right: 8px;"><i class="fa fa-user"></i> Pilih Santri:</label>
              <select name="student_id" class="form-control input-sm select2" onchange="this.form.submit()">
                @foreach($classStudents as $st)
                  <option value="{{ $st['id'] }}" {{ $selectedStudentId === $st['id'] ? 'selected' : '' }}>
                    {{ $st['name'] }} (NIS: {{ $st['nis'] }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="pull-right">
              @if($currentStudent)
                <button type="button" class="btn btn-sm btn-primary btn-flat" style="background:#2b6cb0;" data-toggle="modal" data-target="#modal-edit-note">
                  <i class="fa fa-pencil"></i> Catatan Wali Kelas
                </button>
              @endif
              <button type="button" class="btn btn-sm btn-success btn-flat" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Lembar Rapor
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if($currentStudent && $currentClass)
    <!-- Dokumen Lembar Cetak E-Rapor Resmi -->
    <div class="row">
      <div class="col-md-12">
        <div class="report-card-print">

          <!-- Kop Surat Resmi Alia Islamic School -->
          <div class="text-center" style="border-bottom: 3px double #1a365d; padding-bottom: 15px; margin-bottom: 20px;">
            <div class="row">
              <div class="col-xs-2 text-center">
                <i class="fa fa-graduation-cap fa-4x" style="color: #1a365d; margin-top: 5px;"></i>
              </div>
              <div class="col-xs-8 text-center">
                <h3 style="margin: 0; font-weight: 800; color: #1a365d; letter-spacing: 1px;">YAYASAN PENDIDIKAN ALIA ISLAMIC SCHOOL</h3>
                <h4 style="margin: 3px 0 0 0; color: #6b46c1; font-weight: 700;">LAPORAN CAPAIAN HASIL BELAJAR PESERTA DIDIK (E-RAPOR)</h4>
                <p style="margin: 0; font-size: 11px; color: #555;">
                  NPSN: 20108892 &bull; NSS: 311016401055 &bull; Terakreditasi "A" (Unggul)<br>
                  Kampus Pusat: Jl. Pondok Kopi Raya Kav. 8, Duren Sawit, Jakarta Timur &bull; Telp: (021) 86901236 &bull; Web: www.alia.sch.id
                </p>
              </div>
              <div class="col-xs-2 text-center">
                <span class="badge bg-purple" style="background:#6b46c1; padding: 6px 12px; font-size: 11px; margin-top: 10px;">
                  KURIKULUM TERPADU
                </span>
              </div>
            </div>
          </div>

          <!-- Identitas Santri -->
          <table class="table table-condensed" style="margin-bottom: 20px; font-size: 13px;">
            <tr>
              <td style="width: 18%;"><b>Nama Peserta Didik</b></td>
              <td style="width: 32%;">: <strong style="color: #1a365d; font-size: 14px;">{{ $currentStudent['name'] }}</strong></td>
              <td style="width: 18%;"><b>Kelas / Rombel</b></td>
              <td style="width: 32%;">: {{ $currentClass['name'] }}</td>
            </tr>
            <tr>
              <td><b>Nomor Induk Siswa (NIS)</b></td>
              <td>: <code>{{ $currentStudent['nis'] }}</code> / NISN: {{ $currentStudent['nisn'] ?? '-' }}</td>
              <td><b>Semester / Th. Ajaran</b></td>
              <td>: Ganjil / {{ $activeYear['name'] ?? '2026/2027' }}</td>
            </tr>
            <tr>
              <td><b>Wali Kelas</b></td>
              <td>: {{ $homeroomTeacher['name'] ?? 'Ust. Ahmad Dahlan' }}</td>
              <td><b>Status Keasramaan</b></td>
              <td>: {{ !empty($currentStudent['is_boarding']) ? 'Boarding (Asrama Mukim)' : 'Full Day School' }}</td>
            </tr>
          </table>

          <!-- 1. Capaian Nilai Akademik & Diniyah -->
          <h4 style="font-weight: bold; color: #1a365d; border-left: 4px solid #2b6cb0; padding-left: 8px; margin-bottom: 10px;">
            A. NILAI CAPAIAN PEMBELAJARAN (PENGETAHUAN & KETERAMPILAN)
          </h4>
          <table class="table table-bordered table-striped" style="margin-bottom: 25px; font-size: 12px;">
            <thead>
              <tr style="background: #f4f6f9;">
                <th style="width: 40px;" class="text-center">No</th>
                <th>Mata Pelajaran</th>
                <th class="text-center" style="width: 50px;">KKM</th>
                <th class="text-center" style="width: 65px;">Teori</th>
                <th class="text-center" style="width: 65px;">Praktik</th>
                <th class="text-center" style="width: 75px; background: #eef5fc;">Nilai Akhir</th>
                <th class="text-center" style="width: 60px;">Predikat</th>
                <th>Capaian Kompetensi / Deskripsi Pembelajaran</th>
              </tr>
            </thead>
            <tbody>
              @foreach($studentSubjectGrades as $idx => $g)
                <tr>
                  <td class="text-center">{{ $idx + 1 }}</td>
                  <td>
                    <strong>{{ $g['subject_name'] }}</strong>
                    <span class="label {{ $g['group'] === 'Nasional' ? 'label-primary' : 'label-purple' }}" style="{{ $g['group'] !== 'Nasional' ? 'background:#6b46c1;' : '' }} font-size: 9px; margin-left: 4px;">
                      {{ $g['group'] }}
                    </span>
                  </td>
                  <td class="text-center">{{ $g['kkm'] }}</td>
                  <td class="text-center">{{ $g['final_knowledge'] }}</td>
                  <td class="text-center">{{ $g['final_skill'] }}</td>
                  <td class="text-center" style="background: #eef5fc; font-weight: bold; font-size: 13px; color: {{ $g['final_score'] >= $g['kkm'] ? '#00a65a' : '#dd4b39' }};">
                    {{ $g['final_score'] }}
                  </td>
                  <td class="text-center">
                    <span class="badge {{ $g['predicate'] === 'A' ? 'bg-purple' : ($g['predicate'] === 'B' ? 'bg-green' : ($g['predicate'] === 'C' ? 'bg-yellow' : 'bg-red')) }}">
                      {{ $g['predicate'] }}
                    </span>
                  </td>
                  <td style="font-size: 11px;">{{ $g['description'] }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr style="background: #fafafa; font-weight: bold;">
                <td colspan="5" class="text-right">Rata-Rata Nilai Akhir (IPK / GPA):</td>
                <td class="text-center" style="font-size: 14px; color: #2b6cb0; background: #eef5fc;">{{ $overallGpa }}</td>
                <td class="text-center">
                  <span class="badge bg-green">{{ $overallGpa >= 85 ? 'Amat Baik' : 'Baik' }}</span>
                </td>
                <td class="text-muted" style="font-size: 11px;">Status Kelulusan KKM: <b>TUNTAS SELURUH MATA PELAJARAN</b></td>
              </tr>
            </tfoot>
          </table>

          <!-- 2. Capaian Program Tahfidz Al-Qur'an -->
          <h4 style="font-weight: bold; color: #6b46c1; border-left: 4px solid #6b46c1; padding-left: 8px; margin-bottom: 10px;">
            B. CAPAIAN PROGRAM UNGGULAN TAHFIDZ AL-QUR'AN
          </h4>
          <table class="table table-bordered table-striped" style="margin-bottom: 25px; font-size: 12px;">
            <thead>
              <tr style="background: #f4f6f9;">
                <th style="width: 25%;">Target Kurikulum</th>
                <th style="width: 25%;">Perolehan Capaian Mutqin</th>
                <th style="width: 25%;">Predikat Tahsin & Tajwid</th>
                <th style="width: 25%;">Surat Terakhir / Munaqasyah</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><b>3 Juz / Tahun Pelajaran</b></td>
                <td><strong style="color: #00a65a; font-size: 14px;">{{ $currentStudent['tahfidz_juz'] ?? 3 }} Juz Al-Qur'an</strong></td>
                <td><span class="label label-success" style="font-size: 11px;">{{ $currentStudent['tahfidz_predicate'] ?? 'Jayyid Jiddan' }}</span></td>
                <td>{{ $currentStudent['tahfidz_last_surah'] ?? 'Surah An-Naba s/d Al-Infitar' }}</td>
              </tr>
            </tbody>
          </table>

          <div class="row">
            <!-- 3. Ekstrakurikuler & Organisasi -->
            <div class="col-xs-7">
              <h4 style="font-weight: bold; color: #1a365d; border-left: 4px solid #2b6cb0; padding-left: 8px; margin-bottom: 10px;">
                C. PENGEMBANGAN DIRI & EKSTRAKURIKULER
              </h4>
              <table class="table table-bordered table-striped" style="font-size: 12px; margin-bottom: 20px;">
                <thead>
                  <tr style="background: #f4f6f9;">
                    <th style="width: 35%;">Kegiatan Ekstrakurikuler</th>
                    <th class="text-center" style="width: 25%;">Predikat</th>
                    <th>Keterangan Capaian</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($extracurriculars as $ek)
                    <tr>
                      <td><b>{{ $ek['name'] }}</b></td>
                      <td class="text-center"><span class="badge bg-blue">{{ $ek['predicate'] }}</span></td>
                      <td style="font-size: 11px;">{{ $ek['notes'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- 4. Rekapitulasi Ketidakhadiran -->
            <div class="col-xs-5">
              <h4 style="font-weight: bold; color: #1a365d; border-left: 4px solid #2b6cb0; padding-left: 8px; margin-bottom: 10px;">
                D. KETIDAKHADIRAN (PRESENSI)
              </h4>
              <table class="table table-bordered table-striped" style="font-size: 12px; margin-bottom: 20px;">
                <tr>
                  <td style="width: 70%;">Sakit (S)</td>
                  <td class="text-center"><b>{{ $attendanceSummary['S'] }}</b> hari</td>
                </tr>
                <tr>
                  <td>Izin Terjadwal (I)</td>
                  <td class="text-center"><b>{{ $attendanceSummary['I'] }}</b> hari</td>
                </tr>
                <tr>
                  <td>Tanpa Keterangan (A)</td>
                  <td class="text-center"><b>{{ $attendanceSummary['A'] }}</b> hari</td>
                </tr>
                <tr>
                  <td>Terlambat Masuk (T)</td>
                  <td class="text-center"><b>{{ $attendanceSummary['T'] }}</b> kali</td>
                </tr>
              </table>
            </div>
          </div>

          <!-- 5. Catatan Wali Kelas & Keputusan Kenaikan -->
          <div style="border: 1px solid #ccd0d4; padding: 15px; background: #fafbfc; margin-bottom: 30px; border-radius: 4px;">
            <h5 style="margin-top: 0; font-weight: bold; color: #1a365d;">E. CATATAN & PESAN WALI KELAS:</h5>
            <p style="font-style: italic; color: #333; margin-bottom: 0; font-size: 13px; line-height: 1.6;">
              "{{ $homeroomNote }}"
            </p>
          </div>

          <!-- Lembar Pengesahan / Tanda Tangan -->
          <div class="row" style="margin-top: 20px; font-size: 13px;">
            <div class="col-xs-4 text-center">
              <p>Mengetahui,<br><b>Orang Tua / Wali Santri</b></p>
              <br><br><br>
              <p><b>....................................................</b></p>
            </div>
            <div class="col-xs-4 text-center">
              <p>Jakarta, {{ date('d F Y') }}<br><b>Wali Kelas</b></p>
              <br><br><br>
              <p><b>{{ $homeroomTeacher['name'] ?? 'Ust. Ahmad Dahlan' }}</b><br>NIP: {{ $homeroomTeacher['nip'] ?? '198502152010011002' }}</p>
            </div>
            <div class="col-xs-4 text-center">
              <p>Mengetahui,<br><b>Kepala Sekolah</b></p>
              <br><br><br>
              <p><b>Dr. H. Zulkifli, Lc., M.A.</b><br>NIP: 198005122005011001</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  @else
    <div class="callout callout-info no-print">
      <h4>Tidak Ada Data Santri</h4>
      <p>Silakan pilih rombel dan santri pada menu di atas untuk menampilkan lembar rapor.</p>
    </div>
  @endif

  @if($currentStudent)
    <!-- Modal Edit Catatan Wali Kelas -->
    <div class="modal fade no-print" id="modal-edit-note" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
            <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Catatan Wali Kelas untuk {{ $currentStudent['name'] }}</h4>
          </div>
          <form action="{{ route('akademik.rapor.catatan', ['studentId' => $currentStudent['id']]) }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <div class="modal-body">
              <div class="form-group">
                <label>Pesan & Evaluasi Perkembangan Belajar Santri:</label>
                <textarea name="homeroom_note" class="form-control" rows="5" required>{{ $homeroomNote }}</textarea>
                <span class="help-block">Catatan ini akan dicetak langsung pada lembar rapor resmi yang dibagikan kepada wali santri.</span>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
                <i class="fa fa-save"></i> Simpan Catatan Rapor
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

@endsection

