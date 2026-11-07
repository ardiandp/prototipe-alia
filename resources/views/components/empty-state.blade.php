@props([
  'title' => 'Belum Ada Data',
  'message' => 'Belum ada catatan atau data yang tersedia untuk ditampilkan pada tampilan ini.',
  'icon' => 'fa-folder-open-o',
  'actionUrl' => null,
  'actionLabel' => 'Tambah Data Baru'
])

<div class="text-center" style="padding: 40px 20px; background: #fafafa; border: 1px dashed #ddd; border-radius: 4px; margin: 15px 0;">
  <i class="fa {{ $icon }} text-muted" style="font-size: 48px; margin-bottom: 15px; color: #a0aec0;"></i>
  <h4 style="font-weight: 600; color: #4a5568; margin-top: 5px;">{{ $title }}</h4>
  <p class="text-muted" style="max-width: 450px; margin: 0 auto 15px auto;">{{ $message }}</p>
  @if($actionUrl)
    <a href="{{ $actionUrl }}" class="btn btn-primary btn-flat" style="background:#2b6cb0;">
      <i class="fa fa-plus"></i> {{ $actionLabel }}
    </a>
  @endif
</div>

