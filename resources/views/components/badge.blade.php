@props([
  'status' => 'draft'
])

@php
  $map = [
    'aktif' => ['label' => 'Aktif', 'class' => 'label-success'],
    'nonaktif' => ['label' => 'Nonaktif', 'class' => 'label-default'],
    'lulus' => ['label' => 'Lulus', 'class' => 'label-info'],
    'diterima' => ['label' => 'Diterima', 'class' => 'label-success'],
    'diverifikasi' => ['label' => 'Terverifikasi', 'class' => 'label-primary'],
    'menunggu' => ['label' => 'Menunggu', 'class' => 'label-warning'],
    'ditolak' => ['label' => 'Ditolak', 'class' => 'label-danger'],
    'lunas' => ['label' => 'Lunas', 'class' => 'label-success'],
    'belum_lunas' => ['label' => 'Belum Lunas', 'class' => 'label-warning'],
    'terlambat' => ['label' => 'Terlambat', 'class' => 'label-danger'],
    'izin' => ['label' => 'Izin', 'class' => 'label-info'],
    'hadir' => ['label' => 'Hadir', 'class' => 'label-success'],
    'alpa' => ['label' => 'Alpa', 'class' => 'label-danger'],
    'selesai' => ['label' => 'Selesai', 'class' => 'label-success'],
    'proses' => ['label' => 'Dalam Proses', 'class' => 'label-warning'],
  ];

  $key = strtolower(str_replace(' ', '_', $status));
  $item = $map[$key] ?? ['label' => ucfirst($status), 'class' => 'label-default'];
@endphp

<span class="label {{ $item['class'] }}">{{ $item['label'] }}</span>

