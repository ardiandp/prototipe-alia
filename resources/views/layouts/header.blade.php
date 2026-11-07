<!-- Main Header -->
<header class="main-header">
  <!-- Logo -->
  <a href="{{ route('dashboard') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>A</b>IS</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><i class="fa fa-graduation-cap"></i> <b>ALIA</b> ISLAMIC</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <!-- Navbar Left Text / Current Role -->
    <div class="navbar-custom-menu pull-left hidden-xs">
      <span class="irnis-role-badge">
        <i class="fa fa-user-circle"></i> Peran: {{ session('current_role', 'Yayasan / Administrator') }}
      </span>
      <span class="irnis-role-badge" style="background: rgba(107, 70, 193, 0.4);">
        <i class="fa fa-calendar"></i> TA: 2026/2027 Ganjil
      </span>
    </div>

    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Role Switcher Dropdown (Demo Feature) -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-exchange"></i> <span class="hidden-xs">Ganti Peran Demo</span> <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="header" style="background:#f4f4f4; padding:8px 15px; font-weight:bold; font-size:12px;">Simulasi Hak Akses:</li>
            <li><a href="{{ route('demo.switch-role', ['role' => 'yayasan']) }}"><i class="fa fa-building-o text-purple"></i> Yayasan (Eksekutif)</a></li>
            <li><a href="{{ route('demo.switch-role', ['role' => 'kepsek']) }}"><i class="fa fa-briefcase text-blue"></i> Kepala Sekolah</a></li>
            <li><a href="{{ route('demo.switch-role', ['role' => 'guru']) }}"><i class="fa fa-pencil text-green"></i> Guru / Pengajar</a></li>
            <li><a href="{{ route('demo.switch-role', ['role' => 'bendahara']) }}"><i class="fa fa-money text-yellow"></i> Bendahara Keuangan</a></li>
            <li><a href="{{ route('demo.switch-role', ['role' => 'asrama']) }}"><i class="fa fa-bed text-maroon"></i> Pembina Asrama</a></li>
            <li><a href="{{ route('demo.switch-role', ['role' => 'wali']) }}"><i class="fa fa-users text-aqua"></i> Wali Santri (Portal)</a></li>
          </ul>
        </li>

        <!-- Reset Demo Data -->
        <li>
          <a href="{{ route('demo.reset') }}" onclick="return confirm('Kembalikan semua data demo ke kondisi awal (deterministic baseline)?')" title="Reset Data Demo">
            <i class="fa fa-refresh text-yellow"></i> <span class="hidden-xs">Reset Demo</span>
          </a>
        </li>

        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
            <span class="hidden-xs">{{ session('user_name', 'Ust. Ahmad Dahlan, M.Pd') }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header" style="background: linear-gradient(135deg, #1a365d, #44337a); color: #fff;">
              <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
              <p>
                {{ session('user_name', 'Ust. Ahmad Dahlan, M.Pd') }}
                <small>{{ session('current_role', 'Administrator Yayasan') }} - Alia Islamic School</small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ route('settings.profile') }}" class="btn btn-default btn-flat">Profil Saya</a>
              </div>
              <div class="pull-right">
                <a href="{{ route('demo.reset') }}" class="btn btn-default btn-flat">Reset Sesi</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>

