<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ session('user_name', 'Ust. Ahmad Dahlan') }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> {{ session('current_role', 'Yayasan') }}</a>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">NAVIGASI UTAMA</li>

      <!-- Dashboard Group -->
      <li class="{{ request()->is('dashboard*') || request()->is('/') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard Eksekutif</span>
        </a>
      </li>

      <!-- Master Data -->
      <li class="treeview {{ request()->is('master*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-database"></i> <span>Master Data Sekolah</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('master/unit*') ? 'active' : '' }}"><a href="{{ route('master.unit') }}"><i class="fa fa-circle-o"></i> Unit Pendidikan</a></li>
          <li class="{{ request()->is('master/tahun-ajaran*') ? 'active' : '' }}"><a href="{{ route('master.tahun-ajaran') }}"><i class="fa fa-circle-o"></i> Tahun Ajaran & Semester</a></li>
          <li class="{{ request()->is('master/kelas*') ? 'active' : '' }}"><a href="{{ route('master.kelas') }}"><i class="fa fa-circle-o"></i> Kelas & Rombel</a></li>
          <li class="{{ request()->is('master/ruangan*') ? 'active' : '' }}"><a href="{{ route('master.ruangan') }}"><i class="fa fa-circle-o"></i> Ruangan / Gedung</a></li>
          <li class="{{ request()->is('master/mapel*') ? 'active' : '' }}"><a href="{{ route('master.mapel') }}"><i class="fa fa-circle-o"></i> Mata Pelajaran</a></li>
          <li class="{{ request()->is('master/siswa*') ? 'active' : '' }}"><a href="{{ route('master.siswa') }}"><i class="fa fa-circle-o"></i> Data Siswa</a></li>
          <li class="{{ request()->is('master/pegawai*') ? 'active' : '' }}"><a href="{{ route('master.pegawai') }}"><i class="fa fa-circle-o"></i> Guru & Pegawai</a></li>
          <li class="{{ request()->is('master/orang-tua*') ? 'active' : '' }}"><a href="{{ route('master.orang-tua') }}"><i class="fa fa-circle-o"></i> Orang Tua / Wali</a></li>
        </ul>
      </li>

      <!-- PPDB Online -->
      <li class="treeview {{ request()->is('ppdb*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-user-plus"></i> <span>PPDB Online</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('ppdb/dashboard*') ? 'active' : '' }}"><a href="{{ route('ppdb.dashboard') }}"><i class="fa fa-circle-o"></i> Dashboard PPDB</a></li>
          <li class="{{ request()->is('ppdb/pendaftar*') ? 'active' : '' }}"><a href="{{ route('ppdb.pendaftar') }}"><i class="fa fa-circle-o"></i> Data Pendaftar</a></li>
          <li class="{{ request()->is('ppdb/verifikasi*') ? 'active' : '' }}"><a href="{{ route('ppdb.verifikasi') }}"><i class="fa fa-circle-o"></i> Verifikasi Berkas</a></li>
          <li class="{{ request()->is('ppdb/seleksi*') ? 'active' : '' }}"><a href="{{ route('ppdb.seleksi') }}"><i class="fa fa-circle-o"></i> Seleksi & Pengumuman</a></li>
          <li class="{{ request()->is('ppdb/daftar-ulang*') ? 'active' : '' }}"><a href="{{ route('ppdb.daftar-ulang') }}"><i class="fa fa-circle-o"></i> Daftar Ulang</a></li>
        </ul>
      </li>

      <!-- Akademik -->
      <li class="treeview {{ request()->is('akademik*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-graduation-cap"></i> <span>Akademik & Belajar</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('akademik/rombel*') ? 'active' : '' }}"><a href="{{ route('akademik.rombel') }}"><i class="fa fa-circle-o"></i> Manajemen Rombel</a></li>
          <li class="{{ request()->is('akademik/jadwal*') ? 'active' : '' }}"><a href="{{ route('akademik.jadwal') }}"><i class="fa fa-circle-o"></i> Jadwal Pelajaran</a></li>
          <li class="{{ request()->is('akademik/kalender*') ? 'active' : '' }}"><a href="{{ route('akademik.kalender') }}"><i class="fa fa-circle-o"></i> Kalender Akademik</a></li>
          <li class="{{ request()->is('akademik/presensi*') ? 'active' : '' }}"><a href="{{ route('akademik.presensi') }}"><i class="fa fa-circle-o"></i> Presensi Siswa</a></li>
          <li class="{{ request()->is('akademik/nilai*') ? 'active' : '' }}"><a href="{{ route('akademik.nilai') }}"><i class="fa fa-circle-o"></i> Input Nilai</a></li>
          <li class="{{ request()->is('akademik/rapor*') ? 'active' : '' }}"><a href="{{ route('akademik.rapor') }}"><i class="fa fa-circle-o"></i> E-Rapor</a></li>
          <li class="{{ request()->is('akademik/kenaikan*') ? 'active' : '' }}"><a href="{{ route('akademik.kenaikan') }}"><i class="fa fa-circle-o"></i> Simulasi Kenaikan</a></li>
        </ul>
      </li>

      <!-- LMS E-Learning -->
      <li class="treeview {{ request()->is('lms*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-laptop"></i> <span>LMS / E-Learning</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('lms/kelas-digital*') ? 'active' : '' }}"><a href="{{ route('lms.kelas-digital') }}"><i class="fa fa-circle-o"></i> Kelas Digital</a></li>
          <li class="{{ request()->is('lms/materi*') ? 'active' : '' }}"><a href="{{ route('lms.materi') }}"><i class="fa fa-circle-o"></i> Materi Pembelajaran</a></li>
          <li class="{{ request()->is('lms/tugas*') ? 'active' : '' }}"><a href="{{ route('lms.tugas') }}"><i class="fa fa-circle-o"></i> Tugas & Submission</a></li>
          <li class="{{ request()->is('lms/ujian*') ? 'active' : '' }}"><a href="{{ route('lms.ujian') }}"><i class="fa fa-circle-o"></i> Ujian Online / CBT</a></li>
          <li class="{{ request()->is('lms/progres*') ? 'active' : '' }}"><a href="{{ route('lms.progres') }}"><i class="fa fa-circle-o"></i> Progres Belajar</a></li>
        </ul>
      </li>

      <!-- Tahfidz & Diniyah -->
      <li class="treeview {{ request()->is('tahfidz*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-book"></i> <span>Tahfidz & Diniyah</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('tahfidz/target*') ? 'active' : '' }}"><a href="{{ route('tahfidz.target') }}"><i class="fa fa-circle-o"></i> Target & Halaqah</a></li>
          <li class="{{ request()->is('tahfidz/setoran*') ? 'active' : '' }}"><a href="{{ route('tahfidz.setoran') }}"><i class="fa fa-circle-o"></i> Setoran Hafalan (Ziyadah)</a></li>
          <li class="{{ request()->is('tahfidz/murojaah*') ? 'active' : '' }}"><a href="{{ route('tahfidz.murojaah') }}"><i class="fa fa-circle-o"></i> Murojaah & Ujian</a></li>
          <li class="{{ request()->is('tahfidz/progres*') ? 'active' : '' }}"><a href="{{ route('tahfidz.progres') }}"><i class="fa fa-circle-o"></i> Capaian & Progres</a></li>
          <li class="{{ request()->is('tahfidz/diniyah*') ? 'active' : '' }}"><a href="{{ route('tahfidz.diniyah') }}"><i class="fa fa-circle-o"></i> Kurikulum Diniyah</a></li>
          <li class="{{ request()->is('tahfidz/rapor*') ? 'active' : '' }}"><a href="{{ route('tahfidz.rapor') }}"><i class="fa fa-circle-o"></i> Rapor Tahfidz</a></li>
        </ul>
      </li>

      <!-- Kesiswaan -->
      <li class="treeview {{ request()->is('kesiswaan*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-heartbeat"></i> <span>Kesiswaan & Karakter</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('kesiswaan/pelanggaran*') ? 'active' : '' }}"><a href="{{ route('kesiswaan.pelanggaran') }}"><i class="fa fa-circle-o"></i> Poin & Pelanggaran</a></li>
          <li class="{{ request()->is('kesiswaan/prestasi*') ? 'active' : '' }}"><a href="{{ route('kesiswaan.prestasi') }}"><i class="fa fa-circle-o"></i> Prestasi Siswa</a></li>
          <li class="{{ request()->is('kesiswaan/konseling*') ? 'active' : '' }}"><a href="{{ route('kesiswaan.konseling') }}"><i class="fa fa-circle-o"></i> Bimbingan Konseling (BK)</a></li>
          <li class="{{ request()->is('kesiswaan/ekskul*') ? 'active' : '' }}"><a href="{{ route('kesiswaan.ekskul') }}"><i class="fa fa-circle-o"></i> Ekstrakurikuler</a></li>
          <li class="{{ request()->is('kesiswaan/uks*') ? 'active' : '' }}"><a href="{{ route('kesiswaan.uks') }}"><i class="fa fa-circle-o"></i> UKS & Kesehatan</a></li>
          <li class="{{ request()->is('kesiswaan/mutasi*') ? 'active' : '' }}"><a href="{{ route('kesiswaan.mutasi') }}"><i class="fa fa-circle-o"></i> Mutasi Santri</a></li>
        </ul>
      </li>

      <!-- Asrama / Boarding -->
      <li class="treeview {{ request()->is('asrama*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-bed"></i> <span>Asrama / Boarding</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('asrama/kamar*') ? 'active' : '' }}"><a href="{{ route('asrama.kamar') }}"><i class="fa fa-circle-o"></i> Gedung & Kamar</a></li>
          <li class="{{ request()->is('asrama/penempatan*') ? 'active' : '' }}"><a href="{{ route('asrama.penempatan') }}"><i class="fa fa-circle-o"></i> Penempatan Kamar</a></li>
          <li class="{{ request()->is('asrama/aktivitas*') ? 'active' : '' }}"><a href="{{ route('asrama.aktivitas') }}"><i class="fa fa-circle-o"></i> Jadwal Aktivitas Asrama</a></li>
          <li class="{{ request()->is('asrama/presensi*') ? 'active' : '' }}"><a href="{{ route('asrama.presensi') }}"><i class="fa fa-circle-o"></i> Presensi & Ibadah</a></li>
          <li class="{{ request()->is('asrama/perizinan*') ? 'active' : '' }}"><a href="{{ route('asrama.perizinan') }}"><i class="fa fa-circle-o"></i> Perizinan Keluar/Pulang</a></li>
          <li class="{{ request()->is('asrama/katering*') ? 'active' : '' }}"><a href="{{ route('asrama.katering') }}"><i class="fa fa-circle-o"></i> Konsumsi / Dapur</a></li>
          <li class="{{ request()->is('asrama/laporan*') ? 'active' : '' }}"><a href="{{ route('asrama.laporan') }}"><i class="fa fa-circle-o"></i> Laporan Asrama</a></li>
        </ul>
      </li>

      <!-- Keuangan & SPP -->
      <li class="treeview {{ request()->is('keuangan*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-money"></i> <span>Keuangan & SPP</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('keuangan/dashboard*') ? 'active' : '' }}"><a href="{{ route('keuangan.dashboard') }}"><i class="fa fa-circle-o"></i> Dashboard Keuangan</a></li>
          <li class="{{ request()->is('keuangan/pos-biaya*') ? 'active' : '' }}"><a href="{{ route('keuangan.pos-biaya') }}"><i class="fa fa-circle-o"></i> Pos Biaya & Tarif</a></li>
          <li class="{{ request()->is('keuangan/tagihan*') ? 'active' : '' }}"><a href="{{ route('keuangan.tagihan') }}"><i class="fa fa-circle-o"></i> Tagihan Santri</a></li>
          <li class="{{ request()->is('keuangan/transaksi*') ? 'active' : '' }}"><a href="{{ route('keuangan.transaksi') }}"><i class="fa fa-circle-o"></i> Penerimaan Pembayaran</a></li>
          <li class="{{ request()->is('keuangan/arus-kas*') ? 'active' : '' }}"><a href="{{ route('keuangan.arus-kas') }}"><i class="fa fa-circle-o"></i> Arus Kas (Masuk/Keluar)</a></li>
          <li class="{{ request()->is('keuangan/tunggakan*') ? 'active' : '' }}"><a href="{{ route('keuangan.tunggakan') }}"><i class="fa fa-circle-o"></i> Tunggakan & Piutang</a></li>
          <li class="{{ request()->is('keuangan/beasiswa*') ? 'active' : '' }}"><a href="{{ route('keuangan.beasiswa') }}"><i class="fa fa-circle-o"></i> Beasiswa & Potongan</a></li>
          <li class="{{ request()->is('keuangan/laporan*') ? 'active' : '' }}"><a href="{{ route('keuangan.laporan') }}"><i class="fa fa-circle-o"></i> Laporan Keuangan</a></li>
        </ul>
      </li>

      <!-- HRIS Pegawai -->
      <li class="treeview {{ request()->is('hris*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-users"></i> <span>SDM / HRIS</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('hris/pegawai*') ? 'active' : '' }}"><a href="{{ route('hris.pegawai') }}"><i class="fa fa-circle-o"></i> Data Pegawai & Struktur</a></li>
          <li class="{{ request()->is('hris/presensi*') ? 'active' : '' }}"><a href="{{ route('hris.presensi') }}"><i class="fa fa-circle-o"></i> Presensi Pegawai</a></li>
          <li class="{{ request()->is('hris/cuti*') ? 'active' : '' }}"><a href="{{ route('hris.cuti') }}"><i class="fa fa-circle-o"></i> Pengajuan Cuti / Izin</a></li>
          <li class="{{ request()->is('hris/lembur*') ? 'active' : '' }}"><a href="{{ route('hris.lembur') }}"><i class="fa fa-circle-o"></i> Lembur & SPKL</a></li>
          <li class="{{ request()->is('hris/evaluasi*') ? 'active' : '' }}"><a href="{{ route('hris.evaluasi') }}"><i class="fa fa-circle-o"></i> Evaluasi Kinerja (PKG)</a></li>
          <li class="{{ request()->is('hris/penggajian*') ? 'active' : '' }}"><a href="{{ route('hris.penggajian') }}"><i class="fa fa-circle-o"></i> Penggajian (Payroll)</a></li>
          <li class="{{ request()->is('hris/pelatihan*') ? 'active' : '' }}"><a href="{{ route('hris.pelatihan') }}"><i class="fa fa-circle-o"></i> Pelatihan & Kompetensi</a></li>
        </ul>
      </li>

      <!-- Sarpras & Inventaris -->
      <li class="treeview {{ request()->is('sarpras*') || request()->is('inventaris*') ? 'active menu-open' : '' }}">
        <a href="#">
          <i class="fa fa-cubes"></i> <span>Sarpras & Inventaris</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('sarpras/fasilitas*') ? 'active' : '' }}"><a href="{{ route('sarpras.fasilitas') }}"><i class="fa fa-circle-o"></i> Fasilitas & Aset</a></li>
          <li class="{{ request()->is('sarpras/peminjaman*') ? 'active' : '' }}"><a href="{{ route('sarpras.peminjaman') }}"><i class="fa fa-circle-o"></i> Peminjaman Ruangan</a></li>
          <li class="{{ request()->is('sarpras/pemeliharaan*') ? 'active' : '' }}"><a href="{{ route('sarpras.pemeliharaan') }}"><i class="fa fa-circle-o"></i> Pemeliharaan & Servis</a></li>
          <li class="{{ request()->is('sarpras/pengadaan*') ? 'active' : '' }}"><a href="{{ route('sarpras.pengadaan') }}"><i class="fa fa-circle-o"></i> Pengadaan Sarpras</a></li>
          <li class="{{ request()->is('inventaris/barang*') ? 'active' : '' }}"><a href="{{ route('inventaris.barang') }}"><i class="fa fa-circle-o"></i> Master Barang</a></li>
          <li class="{{ request()->is('inventaris/penerimaan*') ? 'active' : '' }}"><a href="{{ route('inventaris.penerimaan') }}"><i class="fa fa-circle-o"></i> Penerimaan Masuk</a></li>
          <li class="{{ request()->is('inventaris/pengeluaran*') ? 'active' : '' }}"><a href="{{ route('inventaris.pengeluaran') }}"><i class="fa fa-circle-o"></i> Pengeluaran Keluar</a></li>
          <li class="{{ request()->is('inventaris/pengadaan*') ? 'active' : '' }}"><a href="{{ route('inventaris.pengadaan') }}"><i class="fa fa-circle-o"></i> Pengadaan Logistik (PO)</a></li>
          <li class="{{ request()->is('inventaris/pemeliharaan*') ? 'active' : '' }}"><a href="{{ route('inventaris.pemeliharaan') }}"><i class="fa fa-circle-o"></i> Pemeliharaan Alat</a></li>
          <li class="{{ request()->is('inventaris/laporan*') || request()->is('inventaris/stok*') ? 'active' : '' }}"><a href="{{ route('inventaris.laporan') }}"><i class="fa fa-circle-o"></i> Laporan & Kartu Stok</a></li>
        </ul>
      </li>

      <li class="header">PORTAL & INTEGRASI</li>

      <!-- Portal Orang Tua / Santri -->
      <li class="treeview {{ request()->is('portal*') ? 'active' : '' }}">
        <a href="#">
          <i class="fa fa-mobile-phone text-aqua" style="font-size:18px;"></i> <span>Portal Terpadu</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('portal/dashboard*') || request()->is('portal/wali*') ? 'active' : '' }}">
            <a href="{{ route('portal.dashboard') }}"><i class="fa fa-circle-o"></i> Portal Orang Tua/Wali</a>
          </li>
          <li class="{{ request()->is('portal/santri*') ? 'active' : '' }}">
            <a href="{{ route('portal.student') }}"><i class="fa fa-circle-o"></i> Portal Mandiri Santri</a>
          </li>
        </ul>
      </li>

      <!-- AI School Assistant -->
      <li class="{{ request()->is('ai-assistant*') ? 'active' : '' }}">
        <a href="{{ route('ai.assistant') }}">
          <i class="fa fa-magic text-yellow"></i> <span>AI School Assistant</span>
        </a>
      </li>

      <!-- Laporan & Ekspor -->
      <li class="{{ request()->is('laporan*') ? 'active' : '' }}">
        <a href="{{ route('laporan.index') }}">
          <i class="fa fa-file-pdf-o text-red"></i> <span>Laporan & Ekspor</span>
        </a>
      </li>

      <!-- Pengaturan -->
      <li class="{{ request()->is('settings*') ? 'active' : '' }}">
        <a href="{{ route('settings.index') }}">
          <i class="fa fa-cogs"></i> <span>Pengaturan Sistem</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

