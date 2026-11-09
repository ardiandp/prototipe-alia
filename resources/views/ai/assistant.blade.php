@extends('layouts.app')

@section('title', 'AI School Assistant Demo')
@section('page_title', 'Alia AI School Assistant')
@section('page_description', 'Asisten Cerdas Analisis Data Sekolah Berbasis Model Bahasa Terintegrasi')
@section('breadcrumbs')
  <li class="active">AI School Assistant</li>
@endsection

@push('styles')
<style>
  .ai-chat-container {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
    height: calc(100vh - 190px);
    min-height: 520px;
    border: 1px solid #e2e8f0;
  }
  .ai-chat-header {
    background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 60%, #6b46c1 100%);
    color: #ffffff;
    padding: 16px 20px;
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .ai-avatar-badge {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #ffffff;
    color: #1a365d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  }
  .ai-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background-color: #f7fafc;
  }
  .chat-bubble-wrapper {
    margin-bottom: 20px;
    display: flex;
  }
  .chat-bubble-wrapper.ai {
    justify-content: flex-start;
  }
  .chat-bubble-wrapper.user {
    justify-content: flex-end;
  }
  .chat-bubble {
    max-width: 80%;
    border-radius: 12px;
    padding: 14px 18px;
    position: relative;
    font-size: 13.5px;
    line-height: 1.6;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  }
  .chat-bubble.ai {
    background: #ffffff;
    color: #2d3748;
    border-top-left-radius: 2px;
    border: 1px solid #e2e8f0;
  }
  .chat-bubble.user {
    background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
    color: #ffffff;
    border-top-right-radius: 2px;
  }
  .chat-bubble-header {
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .chat-bubble.ai .chat-bubble-header {
    color: #2b6cb0;
  }
  .chat-bubble.user .chat-bubble-header {
    color: #bee3f8;
  }
  .ai-data-card {
    background: #f8fafc;
    border: 1px solid #cbd5e0;
    border-radius: 6px;
    padding: 10px 14px;
    margin-top: 10px;
    font-size: 12px;
  }
  .ai-data-card table {
    width: 100%;
    margin-bottom: 0;
  }
  .ai-data-card td {
    padding: 4px 6px;
    border-bottom: 1px dashed #e2e8f0;
  }
  .ai-data-card tr:last-child td {
    border-bottom: none;
  }
  .quick-prompts-bar {
    padding: 10px 16px;
    background: #edf2f7;
    border-top: 1px solid #e2e8f0;
    display: flex;
    overflow-x: auto;
    white-space: nowrap;
    gap: 8px;
  }
  .quick-prompt-pill {
    background: #ffffff;
    border: 1px solid #cbd5e0;
    color: #2d3748;
    border-radius: 16px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none !important;
    display: inline-block;
  }
  .quick-prompt-pill:hover {
    background: #2b6cb0;
    color: #ffffff !important;
    border-color: #2b6cb0;
  }
  .ai-chat-input-bar {
    padding: 14px 20px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    border-radius: 0 0 8px 8px;
  }
</style>
@endpush

@section('content')

    <h1>
      Alia AI School Assistant
      <small>Asisten Cerdas Analisis Data Sekolah Berbasis Model Bahasa Terintegrasi</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">AI School Assistant</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Flash Message -->
    @if(session('info'))
      <div class="alert alert-info alert-dismissible" style="margin-bottom: 12px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-info-circle"></i> {{ session('info') }}
      </div>
    @endif

    <div class="row">
      <!-- Kolom Obrolan AI -->
      <div class="col-md-9 col-sm-8 col-xs-12">
        <div class="ai-chat-container">
          
          <!-- Chat Header -->
          <div class="ai-chat-header">
            <div style="display: flex; align-items: center;">
              <div class="ai-avatar-badge">
                <i class="fa fa-magic text-purple"></i>
              </div>
              <div>
                <h4 style="margin: 0; font-weight: 700; font-size: 16px; letter-spacing: 0.3px;">
                  Alia Intelligent School Agent
                </h4>
                <span style="font-size: 11px; opacity: 0.9;">
                  <i class="fa fa-circle text-green" style="font-size: 9px;"></i> Online &bull; Terhubung ke 12 Modul Database Sekolah
                </span>
              </div>
            </div>
            <div>
              <a href="{{ route('ai.assistant.reset') }}" class="btn btn-default btn-xs" style="color: #1a365d; font-weight: 600;" onclick="return confirm('Reset riwayat obrolan AI ke salam pembuka?')">
                <i class="fa fa-refresh"></i> Reset Obrolan
              </a>
            </div>
          </div>

          <!-- Chat Messages Area -->
          <div class="ai-chat-messages" id="chatMessagesBox">
            @foreach($messages as $msg)
              <div class="chat-bubble-wrapper {{ $msg['sender'] === 'ai' ? 'ai' : 'user' }}">
                <div class="chat-bubble {{ $msg['sender'] === 'ai' ? 'ai' : 'user' }}">
                  
                  <div class="chat-bubble-header">
                    <span>
                      <i class="fa {{ $msg['sender'] === 'ai' ? 'fa-robot' : 'fa-user' }}"></i>
                      {{ $msg['sender_name'] }}
                    </span>
                    <span>{{ $msg['time'] }}</span>
                  </div>

                  <!-- Text Isi Chat -->
                  <div style="white-space: pre-line;">{!! nl2br(e($msg['text'])) !!}</div>

                  <!-- Optional Badge / Kategori -->
                  @if(!empty($msg['badge']))
                    <div style="margin-top: 8px;">
                      <span class="badge" style="background: #2b6cb0; font-size: 10px; font-weight: 600;">
                        <i class="fa fa-database"></i> Sumber: {{ $msg['badge'] }}
                      </span>
                    </div>
                  @endif

                  <!-- Data Card (Jika AI menyajikan metrik terstruktur) -->
                  @if(!empty($msg['data_card']))
                    <div class="ai-data-card">
                      <strong style="color: #1a365d; display: block; margin-bottom: 5px;">
                        <i class="fa fa-bar-chart"></i> {{ $msg['data_card']['title'] }}
                      </strong>
                      <table>
                        @foreach($msg['data_card']['items'] as $label => $val)
                          <tr>
                            <td style="color: #4a5568; width: 50%;">{{ $label }}</td>
                            <td style="font-weight: 700; color: #1a365d; text-align: right;">{{ $val }}</td>
                          </tr>
                        @endforeach
                      </table>
                    </div>
                  @endif

                </div>
              </div>
            @endforeach
          </div>

          <!-- Quick Prompts Bar (Pertanyaan Cepat Rekomendasi) -->
          <div class="quick-prompts-bar">
            <span style="font-size: 11px; font-weight: 700; color: #718096; line-height: 28px; margin-right: 4px;">
              <i class="fa fa-bolt text-yellow"></i> Saran:
            </span>
            @foreach($quickPrompts as $qp)
              <a href="javascript:void(0)" class="quick-prompt-pill" onclick="sendQuickPrompt('{{ addslashes($qp) }}')">
                {{ $qp }}
              </a>
            @endforeach
          </div>

          <!-- Input Bar -->
          <div class="ai-chat-input-bar">
            <form action="{{ route('ai.assistant.send') }}" method="POST" id="formAiChat">
              @csrf
              <div class="input-group">
                <input type="text" name="message" id="inputChatMessage" class="form-control" 
                       placeholder="Ketik pertanyaan terkait data santri, tahfidz, keuangan SPP, atau sarpras..." 
                       required autocomplete="off">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-primary" style="background: #1a365d; border-color: #1a365d;">
                    <i class="fa fa-paper-plane"></i> Kirim Pertanyaan
                  </button>
                </span>
              </div>
            </form>
          </div>

        </div>
      </div>

      <!-- Kolom Kanan: Panduan Kapabilitas AI -->
      <div class="col-md-3 col-sm-4 col-xs-12">
        <div class="box box-solid box-primary">
          <div class="box-header with-border" style="background: #1a365d;">
            <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-info-circle"></i> Tentang AI Assistant</h3>
          </div>
          <div class="box-body" style="font-size: 12px; line-height: 1.6;">
            <p>
              <strong>Alia AI Assistant</strong> adalah modul percontohan kecerdasan buatan berbasis LLM yang dirancang untuk membantu pimpinan yayasan:
            </p>
            <ul style="padding-left: 18px; margin-bottom: 12px;">
              <li><strong>Sintesis Cepat</strong> data santri, absensi, dan capaian hafalan Al-Qur'an.</li>
              <li><strong>Analisis Keuangan</strong> realisasi SPP & tunggakan secara instan.</li>
              <li><strong>Monitoring Fasilitas</strong> ketersediaan kamar asrama & stok inventaris logistik.</li>
            </ul>
            <div class="alert alert-warning" style="font-size: 11px; padding: 8px 10px; margin-bottom: 0;">
              <i class="fa fa-shield"></i> <strong>Aman & Privat:</strong> Seluruh kueri diproses dalam batasan privasi data internal sekolah.
            </div>
          </div>
        </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title" style="font-size: 13px;"><i class="fa fa-lightbulb-o text-yellow"></i> Contoh Pertanyaan:</h3>
          </div>
          <div class="box-body" style="font-size: 12px;">
            <p class="text-muted" style="margin-bottom: 5px;">Klik salah satu untuk langsung menanyakan:</p>
            <ul class="list-unstyled">
              <li style="margin-bottom: 6px;">
                <a href="javascript:void(0)" onclick="sendQuickPrompt('Berapa total santri aktif di seluruh unit Alia saat ini?')">
                  <i class="fa fa-arrow-circle-right text-blue"></i> Berapa total santri aktif?
                </a>
              </li>
              <li style="margin-bottom: 6px;">
                <a href="javascript:void(0)" onclick="sendQuickPrompt('Bagaimana progres capaian Tahfidz Al-Qur\'an santri semester ini?')">
                  <i class="fa fa-arrow-circle-right text-green"></i> Capaian Tahfidz santri?
                </a>
              </li>
              <li style="margin-bottom: 6px;">
                <a href="javascript:void(0)" onclick="sendQuickPrompt('Ringkasan arus kas keuangan dan status pembayaran SPP bulan ini')">
                  <i class="fa fa-arrow-circle-right text-yellow"></i> Status SPP & keuangan?
                </a>
              </li>
              <li style="margin-bottom: 6px;">
                <a href="javascript:void(0)" onclick="sendQuickPrompt('Berapa kamar asrama yang masih tersedia untuk santri baru?')">
                  <i class="fa fa-arrow-circle-right text-purple"></i> Kapasitas asrama santri?
                </a>
              </li>
              <li>
                <a href="javascript:void(0)" onclick="sendQuickPrompt('Apakah ada barang inventaris gudang yang stoknya menipis?')">
                  <i class="fa fa-arrow-circle-right text-red"></i> Stok gudang menipis?
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
@endsection

@push('scripts')
<script>
  function sendQuickPrompt(promptText) {
    var input = document.getElementById('inputChatMessage');
    var form = document.getElementById('formAiChat');
    if (input && form) {
      input.value = promptText;
      form.submit();
    }
  }

  // Auto-scroll chat box ke bagian bawah
  document.addEventListener("DOMContentLoaded", function() {
    var box = document.getElementById('chatMessagesBox');
    if (box) {
      box.scrollTop = box.scrollHeight;
    }
  });
</script>
@endpush

