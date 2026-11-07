@extends('layouts.app')

@section('title', $title ?? 'Modul Prototipe')
@section('page_title', $title ?? 'Modul Prototipe')
@section('page_description', $subtitle ?? 'Alia Islamic School')

@section('breadcrumbs')
  <li class="active">{{ $title ?? 'Modul' }}</li>
@endsection

@section('content')

  <!-- Demo Information Callout -->
  <div class="callout callout-info" style="border-left-color: #6b46c1;">
    <h4><i class="fa fa-info-circle"></i> Modul Prototipe: {{ $title ?? 'Modul' }}</h4>
    <p>{{ $description ?? 'Halaman modul ini telah siap dan terdaftar dalam struktur navigasi skeleton IRNIS School Prototype.' }}</p>
  </div>

  <div class="row">
    <div class="col-md-12">
      <x-card title="{{ $title ?? 'Data Modul' }}" type="primary">
        <x-toolbar 
          searchAction="{{ url()->current() }}"
          searchPlaceholder="Cari data..."
          :actions="'<button class=\'btn btn-primary btn-flat\' style=\'background:#2b6cb0;\' onclick=\'alert(\'Simulasi form input modul ini\')\'><i class=\'fa fa-plus\'></i> Tambah Data Demo</button>'"
        />

        <x-empty-state 
          title="Modul {{ $title ?? 'Ini' }} Siap Digunakan"
          message="Skeleton navigasi dan integrasi layout telah selesai. Data modul akan dimuat secara otomatis dari Demo Data Provider terpusat pada tahap implementasi batch berikutnya."
          icon="{{ $icon ?? 'fa-cube' }}"
          actionLabel="Simulasikan Input Data"
          actionUrl="#"
        />
      </x-card>
    </div>
  </div>

@endsection

