<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Resmi Eksekutif — Alia Islamic School</title>
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #ffffff;
      color: #2d3748;
      padding: 30px;
    }
    .print-kop {
      border-bottom: 3px double #1a365d;
      padding-bottom: 15px;
      margin-bottom: 25px;
    }
    .print-logo {
      width: 80px;
      height: 80px;
      background: #1a365d;
      border-radius: 50%;
      color: #ffffff;
      line-height: 80px;
      text-align: center;
      font-size: 26px;
      font-weight: 800;
      margin: 0 auto;
    }
    .table-print th {
      background-color: #f7fafc !important;
      color: #1a365d !important;
      border: 1px solid #cbd5e0 !important;
      text-align: center;
      font-size: 11px;
    }
    .table-print td {
      border: 1px solid #cbd5e0 !important;
      font-size: 11px;
      padding: 6px 8px !important;
    }
    @media print {
      .no-print { display: none !important; }
      body { padding: 0; }
    }
  </style>
</head>
<body>

  <!-- Floating Print Button -->
  <div class="no-print text-right" style="margin-bottom: 20px;">
    <button class="btn btn-primary" onclick="window.print()">
      <i class="glyphicon glyphicon-print"></i> Cetak / Simpan PDF
    </button>
    <button class="btn btn-default" onclick="window.close()">Tutup</button>
  </div>

  <!-- Kop Surat Resmi Yayasan -->
  <div class="print-kop">
    <div class="row">
      <div class="col-xs-2 text-center">
        <div class="print-logo">ALIA</div>
      </div>
      <div class="col-xs-10">
        <h3 style="margin: 0; font-weight: 800; color: #1a365d; text-transform: uppercase;">
          YAYASAN AIRIS PENDIDIKAN ISLAM INDONESIA
        </h3>
        <h4 style="margin: 3px 0; font-weight: 700; color: #6b46c1;">
          ALIA ISLAMIC SCHOOL — MANAGEMENT INFORMATION SYSTEM
        </h4>
        <p style="margin: 0; font-size: 11px; color: #4a5568;">
          Kampus Cibubur: Jl. Pendidikan Karakter No. 99, Jakarta Timur | Telp: (021) 876-5432 | Email: sekretariat@alia.sch.id
        </p>
      </div>
    </div>
  </div>

  <!-- Judul Laporan -->
  <div class="text-center" style="margin-bottom: 25px;">
    <h3 style="margin: 0; font-weight: 700; text-transform: uppercase; text-decoration: underline; color: #1a365d;">
      LAPORAN EKSEKUTIF KONSOLIDASI: {{ strtoupper(str_replace('_', ' & ', $module)) }}
    </h3>
    <p style="margin: 5px 0 0 0; font-size: 12px; color: #718096;">
      Tahun Ajaran 2026/2027 &bull; Dicetak pada: {{ date('d F Y, H:i') }} WIB &bull; Sistem IRNIS Alia Dev
    </p>
  </div>

  <!-- Tabel Data Sesuai Modul Terpilih -->
  @if($module === 'akademik')
    <table class="table table-bordered table-print">
      <thead>
        <tr>
          <th style="width: 30px;">No</th>
          <th style="width: 80px;">NIS</th>
          <th>Nama Siswa / Santri</th>
          <th style="width: 60px;">Jenjang</th>
          <th style="width: 80px;">Rombel</th>
          <th style="width: 70px;">Nilai Rata2</th>
          <th style="width: 70px;">Presensi</th>
          <th style="width: 90px;">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($students as $idx => $s)
          <tr>
            <td class="text-center">{{ $idx + 1 }}</td>
            <td class="text-center">{{ $s['nis'] }}</td>
            <td><strong>{{ $s['name'] }}</strong></td>
            <td class="text-center">{{ $s['unit_level'] }}</td>
            <td class="text-center">{{ $s['class_id'] }}</td>
            <td class="text-center font-bold">{{ number_format($s['gpa'], 1) }}</td>
            <td class="text-center">{{ number_format($s['attendance_pct'], 1) }}%</td>
            <td class="text-center">Lulus / Naik</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @elseif($module === 'ppdb')
    <table class="table table-bordered table-print">
      <thead>
        <tr>
          <th style="width: 30px;">No</th>
          <th>No. Registrasi</th>
          <th>Nama Calon Santri</th>
          <th style="width: 60px;">Unit</th>
          <th>Jalur Masuk</th>
          <th style="width: 70px;">Skor Tes</th>
          <th style="width: 90px;">Hasil</th>
          <th style="width: 90px;">Pembayaran</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ppdb as $pIdx => $p)
          <tr>
            <td class="text-center">{{ $pIdx + 1 }}</td>
            <td>{{ $p['reg_number'] ?? $p['id'] }}</td>
            <td><strong>{{ $p['name'] }}</strong></td>
            <td class="text-center">{{ $p['unit_level'] ?? ($p['unit'] ?? 'SMA') }}</td>
            <td>{{ $p['track'] ?? ($p['jalur'] ?? 'Reguler') }}</td>
            <td class="text-center font-bold">{{ $p['exam_score'] ?? ($p['total_score'] ?? '-') }}</td>
            <td class="text-center">{{ strtoupper($p['selection_status'] ?? ($p['status_kelulusan'] ?? 'LULUS')) }}</td>
            <td class="text-center">{{ strtoupper($p['payment_status'] ?? ($p['status_pembayaran'] ?? 'LUNAS')) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @elseif($module === 'sarpras_logistik')
    <table class="table table-bordered table-print">
      <thead>
        <tr>
          <th style="width: 30px;">No</th>
          <th>Kode Barang</th>
          <th>Nama Barang Inventaris</th>
          <th>Kategori</th>
          <th style="width: 60px;">Stok</th>
          <th>Harga Satuan</th>
          <th>Nilai Total Aset</th>
          <th style="width: 80px;">Kondisi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $iIdx => $it)
          <tr>
            <td class="text-center">{{ $iIdx + 1 }}</td>
            <td>{{ $it['item_code'] }}</td>
            <td><strong>{{ $it['name'] }}</strong></td>
            <td>{{ strtoupper($it['category']) }}</td>
            <td class="text-center">{{ $it['stock'] }} {{ $it['unit'] }}</td>
            <td class="text-right">Rp {{ number_format($it['unit_price'] ?? 0, 0, ',', '.') }}</td>
            <td class="text-right font-bold">Rp {{ number_format(($it['stock'] ?? 0) * ($it['unit_price'] ?? 0), 0, ',', '.') }}</td>
            <td class="text-center">{{ strtoupper($it['condition'] ?? 'BAIK') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <table class="table table-bordered table-print">
      <thead>
        <tr>
          <th style="width: 30px;">No</th>
          <th>Nama Personil / Santri</th>
          <th>Unit / Jenjang</th>
          <th>Keterangan Capaian</th>
          <th style="width: 100px;">Status Terkini</th>
        </tr>
      </thead>
      <tbody>
        @foreach($students as $sIdx => $st)
          <tr>
            <td class="text-center">{{ $sIdx + 1 }}</td>
            <td><strong>{{ $st['name'] }}</strong></td>
            <td>{{ $st['unit_level'] }}</td>
            <td>Capaian Tahfidz: {{ $st['tahfidz_juz'] }} Juz Mutqin ({{ $st['tahfidz_last_surah'] }})</td>
            <td class="text-center">Aktif Mukim</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- Pengesahan / Lembar Tanda Tangan 2 Pihak -->
  <div class="row" style="margin-top: 45px; font-size: 12px;">
    <div class="col-xs-6 text-center">
      <p>Mengetahui,<br><strong>Ketua Pengurus Yayasan AIRIS</strong></p>
      <div style="height: 65px;"></div>
      <p style="margin: 0; text-decoration: underline; font-weight: bold;">( Dr. Ir. H. Muhammad Ridwan, M.M. )</p>
      <p style="margin: 0; font-size: 11px; color: #555;">NIPY: 201201-YYS-001</p>
    </div>
    <div class="col-xs-6 text-center">
      <p>Jakarta Timur, {{ date('d F Y') }}<br><strong>Direktur Pendidikan & Kepala Sekolah</strong></p>
      <div style="height: 65px;"></div>
      <p style="margin: 0; text-decoration: underline; font-weight: bold;">( Drs. H. Mulyadi, M.Pd. )</p>
      <p style="margin: 0; font-size: 11px; color: #555;">NIPY: 201507-DIR-003</p>
    </div>
  </div>

</body>
</html>
