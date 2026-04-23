@extends('layouts.user')

@section('title', '{{ $ticket->ticket_code }} - Detail Tiket')

@push('styles')
<style>
    :root { --primary: #6366f1; --radius: 20px; --shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }

    .hero-section {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        padding: 50px 0 110px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }
    .dashboard-content { margin-top: -80px; padding-bottom: 50px; }

    .glass-card {
        background: rgba(255,255,255,0.97);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(255,255,255,0.4);
    }

    /* Info badges */
    .info-item { border-radius: 12px; padding: 12px 16px; background: #f8fafc; }
    .info-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
    .info-value { font-weight: 700; color: #1e293b; margin-top: 4px; }

    /* Chat thread */
    .thread-container { max-height: 450px; overflow-y: auto; padding: 4px 0; }
    .thread-container::-webkit-scrollbar { width: 5px; }
    .thread-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .chat-bubble {
        display: flex;
        margin-bottom: 20px;
        align-items: flex-start;
        gap: 12px;
    }
    .chat-bubble.user-bubble { flex-direction: row-reverse; }

    .bubble-avatar {
        width: 38px; height: 38px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 16px;
        flex-shrink: 0;
    }
    .bubble-content {
        max-width: 70%;
        padding: 14px 18px;
        border-radius: 16px;
        position: relative;
    }
    .bubble-meta { font-size: 11px; margin-top: 6px; opacity: 0.7; }

    .operator-bubble .bubble-content {
        background: #f1f5f9;
        border-top-left-radius: 4px;
        color: #1e293b;
    }
    .user-bubble .bubble-content {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-top-right-radius: 4px;
        color: white;
    }
    .user-bubble .bubble-meta { color: rgba(255,255,255,0.75); }

    .auto-reply-tag {
        display: inline-block;
        font-size: 10px;
        background: #e0e7ff;
        color: #4f46e5;
        border-radius: 20px;
        padding: 2px 8px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    /* Reply form */
    .reply-textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        background: #f8fafc;
        resize: none;
        transition: all 0.2s;
        font-size: 15px;
    }
    .reply-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        background: #fff;
    }
    .btn-send {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border: 0; border-radius: 12px;
        padding: 12px 28px; font-weight: 700;
        color: white; box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        transition: all 0.3s ease;
    }
    .btn-send:hover { color: white; transform: translateY(-2px); }

    /* Star rating */
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 6px; }
    .star-rating input { display: none; }
    .star-rating label {
        font-size: 32px;
        cursor: pointer;
        color: #e2e8f0;
        transition: color 0.15s ease;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #f59e0b; }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2" style="font-size:13px; opacity:0.8;">
                <li class="breadcrumb-item"><a href="{{ route('user.tickets.index') }}" class="text-white">Tiket Saya</a></li>
                <li class="breadcrumb-item active text-white">{{ $ticket->ticket_code }}</li>
            </ol>
        </nav>
        <h1 class="h4 font-weight-bold mb-1">{{ $ticket->subject }}</h1>
        <div class="d-flex align-items-center gap-3 mt-2 flex-wrap" style="gap:10px;">
            {!! $ticket->status_badge !!}
            {!! $ticket->priority_badge !!}
            <span class="small" style="opacity:0.8;"><i class="far fa-clock mr-1"></i>{{ $ticket->created_at->format('d M Y, H:i') }}</span>
        </div>
    </div>
</div>

<div class="container dashboard-content">

    @if(session('success'))
    <div class="alert border-0 shadow-sm alert-dismissible fade show mb-4" style="border-radius:14px; background:#dcfce7; color:#166534;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    <div class="row">
        {{-- Main Thread Column --}}
        <div class="col-lg-8 mb-4">

            {{-- Conversation Thread --}}
            <div class="glass-card mb-4">
                <div class="p-4" style="border-bottom:1px solid #f1f5f9;">
                    <h6 class="font-weight-bold mb-0" style="color:#1e293b;"><i class="fas fa-comments mr-2 text-primary"></i>Thread Percakapan</h6>
                </div>
                <div class="p-4">
                    {{-- Opening message --}}
                    <div class="chat-bubble user-bubble">
                        <div class="bubble-avatar" style="background:linear-gradient(135deg,#6366f1,#a855f7);color:white;">
                            {{ substr($ticket->reporter->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="bubble-content">
                                <div style="font-size:13px; font-weight:700; margin-bottom:6px; opacity:0.85;">Laporan Kendala</div>
                                <div style="white-space:pre-wrap;">{{ $ticket->description }}</div>
                            </div>
                            <div class="bubble-meta text-right">
                                {{ $ticket->reporter->name }} &bull; {{ $ticket->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    @if($ticket->responses->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-hourglass-half fa-2x mb-2 d-block" style="color:#cbd5e1;"></i>
                        <small>Menunggu respons dari tim helpdesk...</small>
                    </div>
                    @else
                        <div class="thread-container">
                            @foreach($ticket->responses as $response)
                            @php $isUser = $response->responder_id === $ticket->reporter_id; @endphp
                            <div class="chat-bubble {{ $isUser ? 'user-bubble' : 'operator-bubble' }}">
                                <div class="bubble-avatar" style="{{ $isUser ? 'background:linear-gradient(135deg,#6366f1,#a855f7);color:white;' : 'background:#e0e7ff;color:#4f46e5;' }}">
                                    {{ substr($response->responder->name, 0, 1) }}
                                </div>
                                <div>
                                    @if($response->is_auto_reply)
                                        <div><span class="auto-reply-tag"><i class="fas fa-robot mr-1"></i>Auto-Reply</span></div>
                                    @endif
                                    <div class="bubble-content">
                                        <div style="white-space:pre-wrap;">{{ $response->message }}</div>
                                    </div>
                                    <div class="bubble-meta {{ $isUser ? 'text-right' : '' }}">
                                        {{ $response->responder->name }} &bull; {{ $response->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Reply Form --}}
                    @if($ticket->status !== 'closed')
                    <div class="mt-4 pt-4" style="border-top:1px solid #f1f5f9;">
                        <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST">
                            @csrf
                            <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing:1px;">Tambah Balasan</label>
                            <textarea name="message" class="form-control reply-textarea mb-3" rows="4" placeholder="Tulis pesan atau informasi tambahan..." required></textarea>
                            <div class="text-right">
                                <button type="submit" class="btn-send">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Rating Form (only if closed & not yet rated) --}}
            @if($ticket->status === 'closed' && !$ticket->rating)
            <div class="glass-card" style="border-top: 4px solid #f59e0b;">
                <div class="p-4">
                    <h6 class="font-weight-bold mb-1" style="color:#1e293b;"><i class="fas fa-star mr-2 text-warning"></i>Beri Penilaian</h6>
                    <p class="text-muted small mb-4">Tiket Anda telah ditutup. Berikan penilaian untuk membantu kami meningkatkan layanan helpdesk.</p>

                    <form action="{{ route('user.tickets.rate', $ticket) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted text-uppercase d-block mb-2" style="letter-spacing:1px;">Tingkat Kepuasan</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="score" value="{{ $i }}" required>
                                <label for="star{{ $i }}" title="{{ $i }} bintang">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted text-uppercase mb-2 d-block" style="letter-spacing:1px;">Masukan (Opsional)</label>
                            <textarea name="feedback" class="form-control reply-textarea" rows="3" placeholder="Ceritakan pengalaman Anda dengan layanan helpdesk..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning font-weight-bold" style="border-radius:12px; padding:12px 28px; color:white; border:0; box-shadow: 0 4px 12px rgba(245,158,11,0.35);">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Penilaian
                        </button>
                    </form>
                </div>
            </div>
            @elseif($ticket->rating)
            <div class="glass-card" style="border-top: 4px solid #22c55e;">
                <div class="p-4 text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                    <h6 class="font-weight-bold" style="color:#166534;">Penilaian Sudah Diberikan</h6>
                    <div class="my-2" style="font-size:28px; color:#f59e0b;">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $ticket->rating->score ? '★' : '☆' }}
                        @endfor
                    </div>
                    @if($ticket->rating->feedback)
                    <p class="text-muted small mb-0 fst-italic">"{{ $ticket->rating->feedback }}"</p>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4 mb-4">
            <div class="glass-card p-4">
                <h6 class="font-weight-bold mb-3" style="color:#1e293b; font-size:13px; text-transform:uppercase; letter-spacing:1px;"><i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Tiket</h6>

                <div class="info-item mb-3">
                    <div class="info-label">Kode Tiket</div>
                    <div class="info-value" style="font-family:monospace; color:#6366f1;">{{ $ticket->ticket_code }}</div>
                </div>
                <div class="info-item mb-3">
                    <div class="info-label">Status</div>
                    <div class="mt-1">{!! $ticket->status_badge !!}</div>
                </div>
                <div class="info-item mb-3">
                    <div class="info-label">Prioritas</div>
                    <div class="mt-1">{!! $ticket->priority_badge !!}</div>
                </div>
                <div class="info-item mb-3">
                    <div class="info-label">Dibuat pada</div>
                    <div class="info-value small">{{ $ticket->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="info-item mb-3">
                    <div class="info-label">Ditangani oleh</div>
                    <div class="info-value small">{{ $ticket->operator?->name ?? 'Belum ditugaskan' }}</div>
                </div>
                @if($ticket->first_response_at)
                <div class="info-item mb-3">
                    <div class="info-label">Pertama Direspons</div>
                    <div class="info-value small">
                        {{ $ticket->first_response_at->format('d M Y, H:i') }}
                        <div class="small text-muted">({{ $ticket->response_time_minutes }} menit)</div>
                    </div>
                </div>
                @endif
                @if($ticket->resolved_at)
                <div class="info-item">
                    <div class="info-label">Diselesaikan</div>
                    <div class="info-value small">
                        {{ $ticket->resolved_at->format('d M Y, H:i') }}
                        <div class="small text-muted">({{ $ticket->resolution_time_minutes }} menit)</div>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-3">
                <a href="{{ route('user.tickets.index') }}" class="btn btn-light w-100" style="border-radius:12px; font-weight:600; padding:12px;">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Tiket
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
