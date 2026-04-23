@extends('layouts.admin')

@section('title', '{{ $ticket->ticket_code }} - Kelola Tiket')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.helpdesk.index') }}">Helpdesk</a></li>
    <li class="breadcrumb-item active">{{ $ticket->ticket_code }}</li>
@endsection

@push('styles')
<style>
    .ticket-header-card {
        background: linear-gradient(135deg, #6366f1 0%, #764ba2 100%);
        border-radius: var(--radius-lg);
        padding: 30px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .ticket-header-card::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 12px;
        border: 1px solid #f1f5f9;
    }
    .info-box .info-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#64748b; }
    .info-box .info-value { font-weight:700; color:#1e293b; margin-top:4px; font-size:15px; }

    /* Chat Thread */
    .thread-container { max-height: 500px; overflow-y: auto; padding: 4px 0; }
    .thread-container::-webkit-scrollbar { width:5px; }
    .thread-container::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:10px; }

    .chat-bubble { display:flex; margin-bottom:18px; align-items:flex-start; gap:12px; }
    .chat-bubble.user-bubble { flex-direction: row-reverse; }

    .bubble-avatar {
        width:38px; height:38px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:16px; flex-shrink:0;
    }
    .bubble-content {
        max-width:70%; padding:14px 18px; border-radius:16px; position:relative;
    }
    .operator-bubble .bubble-content { background:#f1f5f9; border-top-left-radius:4px; color:#1e293b; }
    .user-bubble    .bubble-content { background:linear-gradient(135deg,#6366f1,#a855f7); border-top-right-radius:4px; color:white; }
    .bubble-meta { font-size:11px; margin-top:6px; opacity:0.7; }
    .user-bubble .bubble-meta { text-align:right; }

    .auto-reply-tag {
        display:inline-block; font-size:10px; background:#e0e7ff; color:#4f46e5;
        border-radius:20px; padding:2px 8px; font-weight:700; margin-bottom:6px;
    }

    /* Reply area */
    .reply-textarea {
        border:1.5px solid #e2e8f0; border-radius:14px; padding:14px 16px;
        background:#f8fafc; resize:none; transition:all 0.2s; font-size:15px; width:100%;
    }
    .reply-textarea:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); background:#fff; outline:none; }

    /* Auto reply suggestions */
    .suggestion-card {
        border: 1.5px solid #e0e7ff;
        border-radius: 12px;
        padding: 14px 16px;
        background: #f5f3ff;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 10px;
    }
    .suggestion-card:hover { border-color: #6366f1; background: #eef2ff; }
    .suggestion-card .suggestion-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6366f1; margin-bottom: 5px; }
    .suggestion-card .suggestion-preview { font-size: 13px; color: #475569; line-height: 1.5; }

    /* Status form */
    .status-select {
        border:1.5px solid #e2e8f0; border-radius:10px; padding:10px 14px;
        font-size:14px; background:#f8fafc; font-family:'Outfit',sans-serif;
        width:100%;
    }
    .status-select:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); outline:none; }

    .btn-send {
        background:linear-gradient(135deg,#6366f1,#a855f7); border:0; border-radius:12px;
        padding:12px 28px; font-weight:700; color:white;
        box-shadow:0 4px 15px rgba(99,102,241,0.3); transition:all 0.3s;
    }
    .btn-send:hover { color:white; transform:translateY(-2px); }

    .sla-chip {
        display:inline-flex; align-items:center; gap:6px;
        padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700;
    }
</style>
@endpush

@section('content')

{{-- Ticket Header --}}
<div class="ticket-header-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
        <div>
            <div class="mb-2" style="font-size:13px; opacity:0.75;">
                <i class="fas fa-ticket-alt mr-2"></i>{{ $ticket->ticket_code }}
            </div>
            <h2 class="font-weight-bold mb-2" style="font-size:1.4rem;">{{ $ticket->subject }}</h2>
            <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                {!! $ticket->status_badge !!}
                {!! $ticket->priority_badge !!}
                <span class="sla-chip" style="background:rgba(255,255,255,0.15);">
                    <i class="fas fa-user"></i> {{ $ticket->reporter->name }}
                </span>
                <span class="sla-chip" style="background:rgba(255,255,255,0.15);">
                    <i class="far fa-clock"></i> {{ $ticket->created_at->format('d M Y, H:i') }}
                </span>
                @if($ticket->response_time_minutes !== null)
                <span class="sla-chip" style="background:rgba(255,255,255,0.15);">
                    <i class="fas fa-stopwatch"></i> Respons: {{ $ticket->response_time_minutes }}m
                </span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.15); color:white; border-radius:10px; border:1px solid rgba(255,255,255,0.3);">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert border-0 shadow-sm alert-dismissible fade show mb-4" style="border-radius:14px;background:#dcfce7;color:#166534;">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="row">
    {{-- LEFT: Thread & Reply --}}
    <div class="col-lg-8 mb-4">

        {{-- Thread --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0 d-flex align-items-center" style="padding:18px 24px;">
                <i class="fas fa-comments mr-2 text-primary"></i>
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Thread Percakapan</h6>
            </div>
            <div class="card-body p-4">

                {{-- Opening Message --}}
                <div class="chat-bubble user-bubble">
                    <div class="bubble-avatar" style="background:linear-gradient(135deg,#6366f1,#a855f7);color:white;">
                        {{ substr($ticket->reporter->name,0,1) }}
                    </div>
                    <div>
                        <div class="bubble-content">
                            <div style="font-size:13px;font-weight:700;margin-bottom:6px;opacity:0.85;">Laporan Awal</div>
                            <div style="white-space:pre-wrap;">{{ $ticket->description }}</div>
                        </div>
                        <div class="bubble-meta">
                            {{ $ticket->reporter->name }} &bull; {{ $ticket->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                @if($ticket->responses->isNotEmpty())
                <div class="thread-container mt-3">
                    @foreach($ticket->responses as $response)
                    @php $isReporter = $response->responder_id === $ticket->reporter_id; @endphp
                    <div class="chat-bubble {{ $isReporter ? 'user-bubble' : 'operator-bubble' }}">
                        <div class="bubble-avatar" style="{{ $isReporter ? 'background:linear-gradient(135deg,#6366f1,#a855f7);color:white;' : 'background:#e0e7ff;color:#4f46e5;' }}">
                            {{ substr($response->responder->name,0,1) }}
                        </div>
                        <div>
                            @if($response->is_auto_reply)
                                <div><span class="auto-reply-tag"><i class="fas fa-robot mr-1"></i>Auto-Reply Template</span></div>
                            @endif
                            <div class="bubble-content">
                                <div style="white-space:pre-wrap;">{{ $response->message }}</div>
                            </div>
                            <div class="bubble-meta">
                                {{ $response->responder->name }} &bull; {{ $response->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-reply fa-2x d-block mb-2" style="color:#cbd5e1;"></i>
                    <small>Belum ada balasan. Jadilah yang pertama merespons!</small>
                </div>
                @endif
            </div>
        </div>

        {{-- Reply Form --}}
        @if($ticket->status !== 'closed')
        <div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0 d-flex align-items-center" style="padding:18px 24px;">
                <i class="fas fa-reply mr-2 text-primary"></i>
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Kirim Balasan</h6>
            </div>
            <div class="card-body p-4">

                {{-- Auto-Reply Suggestions --}}
                @if(count($suggestions) > 0)
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-magic mr-2 text-primary"></i>
                        <span class="small font-weight-bold text-muted text-uppercase" style="letter-spacing:1px;">Saran Auto-Reply</span>
                        <span class="badge badge-primary ml-2" style="font-size:10px;">{{ count($suggestions) }} tersedia</span>
                    </div>
                    @foreach($suggestions as $s)
                    <div class="suggestion-card" onclick="applyAutoReply({{ json_encode($s['text']) }})">
                        <div class="suggestion-label"><i class="fas fa-lightbulb mr-1"></i>{{ $s['label'] }}</div>
                        <div class="suggestion-preview">{{ Str::limit($s['text'], 100) }}</div>
                        <div class="mt-2"><small class="text-primary font-weight-bold">Klik untuk gunakan template ini →</small></div>
                    </div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('admin.helpdesk.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-2 d-block" style="letter-spacing:1px;">Pesan Balasan</label>
                        <textarea id="reply-message" name="message" class="reply-textarea" rows="5"
                            placeholder="Tulis balasan Anda di sini..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn-send">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT: Info & Actions --}}
    <div class="col-lg-4 mb-4">

        {{-- Update Status --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0" style="padding:16px 20px;">
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;font-size:13px;text-transform:uppercase;letter-spacing:1px;">
                    <i class="fas fa-cog mr-2 text-primary"></i>Update Status
                </h6>
            </div>
            <div class="card-body p-4 pt-2">
                <form action="{{ route('admin.helpdesk.status', $ticket) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="status-select mb-3">
                        <option value="open"        {{ $ticket->status === 'open'        ? 'selected' : '' }}>📂 Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>⚙️ In Progress</option>
                        <option value="closed"      {{ $ticket->status === 'closed'      ? 'selected' : '' }}>✅ Closed</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100" style="border-radius:10px; font-weight:700;">
                        <i class="fas fa-save mr-2"></i> Simpan Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Ticket Info --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0" style="padding:16px 20px;">
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;font-size:13px;text-transform:uppercase;letter-spacing:1px;">
                    <i class="fas fa-info-circle mr-2 text-primary"></i>Info Tiket
                </h6>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="info-box">
                    <div class="info-label">Kode Tiket</div>
                    <div class="info-value" style="font-family:monospace;color:#6366f1;">{{ $ticket->ticket_code }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Pelapor</div>
                    <div class="info-value">{{ $ticket->reporter->name }}</div>
                    <div class="small text-muted">{{ $ticket->reporter->email }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Operator</div>
                    <div class="info-value">{{ $ticket->operator?->name ?? 'Belum ditugaskan' }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Dibuat</div>
                    <div class="info-value small">{{ $ticket->created_at->format('d M Y, H:i') }}</div>
                    <div class="small text-muted">{{ $ticket->created_at->diffForHumans() }}</div>
                </div>
                @if($ticket->first_response_at)
                <div class="info-box">
                    <div class="info-label">⏱ Response Time</div>
                    <div class="info-value text-success">{{ $ticket->response_time_minutes }} menit</div>
                </div>
                @endif
                @if($ticket->resolved_at)
                <div class="info-box">
                    <div class="info-label">✅ Resolution Time</div>
                    <div class="info-value text-primary">{{ $ticket->resolution_time_minutes }} menit</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Rating --}}
        @if($ticket->rating)
        <div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg); border-top:4px solid #f59e0b !important;">
            <div class="card-body p-4 text-center">
                <div class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing:1px;"><i class="fas fa-star mr-1 text-warning"></i>Rating Kepuasan</div>
                <div style="font-size:28px; color:#f59e0b; margin:6px 0;">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $ticket->rating->score ? '★' : '☆' }}
                    @endfor
                </div>
                <div class="font-weight-bold mb-1">{{ $ticket->rating->score }}/5</div>
                @if($ticket->rating->feedback)
                <p class="text-muted small mb-0" style="font-style:italic;">"{{ $ticket->rating->feedback }}"</p>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
function applyAutoReply(text) {
    const area = document.getElementById('reply-message');
    if (area) {
        area.value = text;
        area.focus();
        area.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
</script>
@endpush
