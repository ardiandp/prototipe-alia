<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'Beranda') | IRNIS School Management System - Alia Islamic School</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome 4.7.0 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/select2/dist/css/select2.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  
  <!-- Theme style AdminLTE 2.4.2 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/skins/skin-blue.min.css') }}">

  <!-- Custom Alia / Irnis Dev Theme Styling -->
  <link rel="stylesheet" href="{{ asset('css/irnis-custom.css') }}">

  @stack('styles')

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Header -->
  @include('layouts.header')

  <!-- Sidebar -->
  @include('layouts.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('page_title', 'Dashboard')
        <small>@yield('page_description', 'Sistem Manajemen Terpadu Alia Islamic School')</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        @yield('breadcrumbs')
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!-- Flash Notifications -->
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

      @if(session('info'))
        <div class="alert alert-info alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-info"></i> Informasi Demo:</h4>
          {{ session('info') }}
        </div>
      @endif

      <!-- Page Main Content Slot -->
      @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  @include('layouts.footer')

  <!-- Standard Bootstrap 3 Action Confirmation Modal -->
  <div class="modal fade" id="irnis-confirm-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background:#2b6cb0; color:#fff;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="irnis-confirm-title"><i class="fa fa-question-circle"></i> Konfirmasi</h4>
        </div>
        <div class="modal-body" id="irnis-confirm-message">
          Apakah Anda yakin ingin memproses aksi ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <a href="#" id="irnis-confirm-btn" class="btn btn-primary btn-flat" style="background:#2b6cb0;">Ya, Lanjutkan</a>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 3.2.1 -->
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('AdminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>

<script>
  $(document).ready(function() {
    // Inisialisasi plugin dasar yang umum
    if ($.fn.select2) {
      $('.select2').select2({ width: '100%' });
    }

    // Modal konfirmasi helper
    window.confirmAction = function(url, message, title) {
      $('#irnis-confirm-title').html('<i class="fa fa-question-circle"></i> ' + (title || 'Konfirmasi'));
      $('#irnis-confirm-message').text(message || 'Apakah Anda yakin ingin melanjutkan aksi ini?');
      $('#irnis-confirm-btn').attr('href', url);
      $('#irnis-confirm-modal').modal('show');
    };
  });
</script>

@stack('scripts')
</body>
</html>

