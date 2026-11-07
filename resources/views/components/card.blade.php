@props([
  'title' => '',
  'badge' => null,
  'badgeColor' => 'primary',
  'type' => 'default', // default, primary, info, success, warning, danger, irnis-blue, irnis-purple
  'solid' => false,
  'tools' => null,
  'footer' => null,
  'collapse' => false
])

<div {{ $attributes->merge(['class' => 'box ' . ($solid ? 'box-solid ' : '') . 'box-' . $type]) }}>
  @if($title || $tools || $badge)
    <div class="box-header with-border">
      <h3 class="box-title">
        {{ $title }}
        @if($badge)
          <span class="label label-{{ $badgeColor }}">{{ $badge }}</span>
        @endif
      </h3>
      <div class="box-tools pull-right">
        @if($tools)
          {!! $tools !!}
        @endif
        @if($collapse)
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        @endif
      </div>
    </div>
  @endif

  <div class="box-body">
    {{ $slot }}
  </div>

  @if($footer)
    <div class="box-footer">
      {{ $footer }}
    </div>
  @endif
</div>

