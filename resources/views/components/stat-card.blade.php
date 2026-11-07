@props([
  'count' => '0',
  'label' => '',
  'icon' => 'fa-star',
  'bg' => 'aqua', // aqua, green, yellow, red, purple, blue
  'url' => '#'
])

<div class="small-box bg-{{ $bg }}">
  <div class="inner">
    <h3>{{ $count }}</h3>
    <p>{{ $label }}</p>
  </div>
  <div class="icon">
    <i class="fa {{ $icon }}"></i>
  </div>
  <a href="{{ $url }}" class="small-box-footer">
    Lihat Rincian <i class="fa fa-arrow-circle-right"></i>
  </a>
</div>

