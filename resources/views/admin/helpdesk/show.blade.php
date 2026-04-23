@extends('layouts.admin')

@section('title')

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
        padding: 28px 32px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(99,102,241,0.25);
    }
    .ticket-header-card::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .ticket-header-card::after {
        content: '';
        position: absolute;
        bottom: -30px; left: 40px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .sla-chip {
        display:inline-flex; align-items:center; gap:6px;
        padding:5px 12px; border-radius:20px; font-size:12px; font-weight:600;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        gap: 12px;
    }
    .info-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .info-row:first-child { padding-top: 0; }
    .info-icon {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
    }
    .info-row-label {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 2px;
    }
    .info-row-value {
        font-size: 14px; font-weight: 600; color: #1e293b; line-height: 1.3;
    }
    .info-row-sub { font-size: 12px; color: #94a3b8; margin-top: 1px; }

    .chat-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 4px 0;
    }
    .chat-scroll { max-height: 480px; overflow-y: auto; padding: 4px 4px; }
    .chat-scroll::-webkit-scrollbar { width: 5px; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .chat-row { display: flex; align-items: flex-end; gap: 10px; }
    .chat-row.from-user  { flex-direction: row-reverse; }
    .chat-row.from-admin { flex-direction: row; }

    .chat-avatar {
        width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px; letter-spacing: -0.5px;
    }

    .chat-body { max-width: 72%; display: flex; flex-direction: column; }
    .from-user  .chat-body { align-items: flex-end; }
    .from-admin .chat-body { align-items: flex-start; }

    .chat-name {
        font-size: 11px; font-weight: 700; color: #64748b;
        margin-bottom: 4px; letter-spacing: 0.3px;
    }
    .from-user  .chat-name { text-align: right; }
    .from-admin .chat-name { text-align: left; }

    .chat-bubble-msg {
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 14px; line-height: 1.6;
        word-break: break-word;
        white-space: pre-wrap;
        position: relative;
    }
    .from-user  .chat-bubble-msg {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 12px rgba(99,102,241,0.25);
    }
    .from-admin .chat-bubble-msg {
        background: #f1f5f9;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .chat-time {
        font-size: 10px; color: #94a3b8; margin-top: 4px;
        font-weight: 500;
    }

    .auto-reply-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10px; font-weight: 700; letter-spacing: 0.5px;
        background: #e0e7ff; color: #4f46e5;
        border-radius: 20px; padding: 2px 8px;
        margin-bottom: 5px;
    }

    .thread-divider {
        display: flex; align-items: center; gap: 10px; color: #94a3b8;
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
        margin: 8px 0;
    }
    .thread-divider::before, .thread-divider::after {
        content: ''; flex: 1; height: 1px; background: #f1f5f9;
    }

    .reply-textarea {
        border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;
        background: #f8fafc; resize: none; transition: all 0.2s; font-size: 14px;
        width: 100%; font-family: 'Outfit', sans-serif; line-height: 1.6;
    }
    .reply-textarea:focus {
        border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        background: #fff; outline: none;
    }

    .suggestion-card {
        border: 1.5px solid #e0e7ff; border-radius: 12px; padding: 12px 15px;
        background: #f5f3ff; cursor: pointer; transition: all 0.2s ease; margin-bottom: 8px;
    }
    .suggestion-card:hover { border-color: #6366f1; background: #eef2ff; transform: translateX(4px); }
    .suggestion-card .suggestion-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6366f1; margin-bottom: 3px; }
    .suggestion-card .suggestion-preview { font-size: 12px; color: #475569; line-height: 1.5; }

    .status-select {
        border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 10px 14px;
        font-size: 14px; background: #f8fafc; font-family: 'Outfit', sans-serif; width: 100%;
    }
    .status-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); outline: none; }

    .btn-send {
        background: linear-gradient(135deg, #6366f1, #a855f7); border: 0; border-radius: 12px;
        padding: 11px 26px; font-weight: 700; color: white; font-family: 'Outfit', sans-serif;
        box-shadow: 0 4px 14px rgba(99,102,241,0.3); transition: all 0.3s; font-size: 14px;
    }
    .btn-send:hover { color: white; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,0.4); }

    .section-label {
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
        color: #64748b; margin-bottom: 10px; display: block;
    }
</style>
@endpush

@section('content')

<div class="ticket-header-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px; position:relative; z-index:1;">
        <div>
            <div class="d-flex align-items-center mb-2" style="gap:8px; opacity:0.8; font-size:12px; font-weight:600;">
                <i class="fas fa-headset"></i>
                <span>HELPDESK</span>
                <span style="opacity:0.5;">/</span>
                <span style="font-family:monospace; letter-spacing:1px;">{{ $ticket->ticket_code }}</span>
            </div>
            <h2 class="font-weight-bold mb-3" style="font-size:1.35rem; line-height:1.3;">{{ $ticket->subject }}</h2>
            <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                {!! $ticket->status_badge !!}
                {!! $ticket->priority_badge !!}
                <span class="sla-chip"><i class="fas fa-user"></i> {{ $ticket->reporter->name }}</span>
                <span class="sla-chip"><i class="far fa-clock"></i> {{ $ticket->created_at->format('d M Y, H:i') }}</span>
                @if($ticket->response_time_minutes !== null)
                <span class="sla-chip"><i class="fas fa-stopwatch"></i> Respons: {{ $ticket->response_time_minutes }}m</span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-sm align-self-start"
            style="background:rgba(255,255,255,0.15); color:white; border-radius:10px; border:1px solid rgba(255,255,255,0.3); position:relative; z-index:1;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">

        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0 d-flex align-items-center justify-content-between" style="padding:16px 22px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-comments text-primary mr-2"></i>
                    <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Thread Percakapan</h6>
                </div>
                <span class="badge" style="background:#e0e7ff; color:#4f46e5; font-size:11px;">
                    {{ $ticket->responses->count() + 1 }} pesan
                </span>
            </div>
            <div class="card-body p-4">
                <div class="chat-scroll">
                    <div class="chat-wrapper">

                        <div class="thread-divider">Laporan Awal — {{ $ticket->created_at->format('d M Y') }}</div>
                        <div class="chat-row from-user">
                            <div class="chat-avatar" style="background:linear-gradient(135deg,#6366f1,#a855f7); color:white;">
                                {{ substr($ticket->reporter->name, 0, 1) }}
                            </div>
                            <div class="chat-body">
                                <div class="chat-name">{{ $ticket->reporter->name }} <span style="font-weight:400; color:#94a3b8;">(Pelapor)</span></div>
                                <div class="chat-bubble-msg">{{ $ticket->description }}</div>
                                <div class="chat-time">{{ $ticket->created_at->format('H:i') }} &bull; {{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        @forelse($ticket->responses as $response)
                            @php
                                $isReporter  = $response->responder_id === $ticket->reporter_id;
                                $rowClass    = $isReporter ? 'from-user' : 'from-admin';
                                $avatarStyle = $isReporter
                                    ? 'background:linear-gradient(135deg,#6366f1,#a855f7); color:white;'
                                    : 'background:#e0e7ff; color:#4f46e5;';
                                $roleLabel   = $isReporter ? 'Pelapor' : 'Operator';
                            @endphp
                            <div class="chat-row {{ $rowClass }}">
                                <div class="chat-avatar" style="{{ $avatarStyle }}">
                                    {{ substr($response->responder->name, 0, 1) }}
                                </div>
                                <div class="chat-body">
                                    <div class="chat-name">
                                        {{ $response->responder->name }}
                                        <span style="font-weight:400; color:#94a3b8;">({{ $roleLabel }})</span>
                                    </div>
                                    @if($response->is_auto_reply)
                                        <div class="auto-reply-badge"><i class="fas fa-robot"></i> Template Auto-Reply</div>
                                    @endif
                                    <div class="chat-bubble-msg">{{ $response->message }}</div>
                                    <div class="chat-time">{{ $response->created_at->format('H:i') }} &bull; {{ $response->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-reply fa-2x d-block mb-2" style="color:#e2e8f0;"></i>
                                <div class="small font-weight-600">Belum ada balasan</div>
                                <div class="small" style="color:#94a3b8;">Jadilah yang pertama merespons tiket ini!</div>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

        @if($ticket->status !== 'closed')
        <div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0 d-flex align-items-center" style="padding:16px 22px;">
                <i class="fas fa-reply text-primary mr-2"></i>
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Kirim Balasan</h6>
            </div>
            <div class="card-body p-4">

                @if(count($suggestions) > 0)
                <div class="mb-4 p-3 rounded-lg" style="background:#fafafa; border:1px solid #f1f5f9;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-magic text-primary mr-2"></i>
                        <span class="small font-weight-bold" style="color:#1e293b;">Saran Template Jawaban</span>
                        <span class="ml-auto badge" style="background:#e0e7ff; color:#4f46e5; font-size:10px;">
                            {{ count($suggestions) }} tersedia
                        </span>
                    </div>
                    @foreach($suggestions as $s)
                    <div class="suggestion-card" onclick="applyAutoReply({{ json_encode($s['text']) }})">
                        <div class="suggestion-label"><i class="fas fa-lightbulb mr-1"></i>{{ $s['label'] }}</div>
                        <div class="suggestion-preview">{{ Str::limit($s['text'], 110) }}</div>
                        <div class="mt-2 small font-weight-bold text-primary">Klik untuk gunakan →</div>
                    </div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('admin.helpdesk.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="section-label">Pesan Balasan</label>
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
        @else
        <div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg); border-top:3px solid #22c55e !important;">
            <div class="card-body p-4 text-center text-muted">
                <i class="fas fa-lock fa-lg d-block mb-2" style="color:#22c55e;"></i>
                <div class="font-weight-bold" style="color:#166534;">Tiket telah ditutup</div>
                <div class="small mt-1">Balasan tidak bisa ditambahkan pada tiket yang sudah closed.</div>
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0" style="padding:16px 20px;">
                <h6 class="font-weight-bold mb-0" style="color:#1e293b; font-size:13px; text-transform:uppercase; letter-spacing:1px;">
                    <i class="fas fa-cog mr-2 text-primary"></i>Update Status
                </h6>
            </div>
            <div class="card-body p-4 pt-3">
                <form action="{{ route('admin.helpdesk.status', $ticket) }}" method="POST">
                    @csrf @method('PATCH')
                    <label class="section-label">Status Tiket</label>
                    <select name="status" class="status-select mb-3">
                        <option value="open"        {{ $ticket->status === 'open'        ? 'selected' : '' }}>📂 Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>⚙️ In Progress</option>
                        <option value="closed"      {{ $ticket->status === 'closed'      ? 'selected' : '' }}>✅ Closed / Selesai</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100" style="border-radius:10px; font-weight:700; padding:11px;">
                        <i class="fas fa-save mr-2"></i> Simpan Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--radius-lg);">
            <div class="card-header border-0" style="padding:16px 20px;">
                <h6 class="font-weight-bold mb-0" style="color:#1e293b; font-size:13px; text-transform:uppercase; letter-spacing:1px;">
                    <i class="fas fa-info-circle mr-2 text-primary"></i>Info Tiket
                </h6>
            </div>
            <div class="card-body px-4 pb-4 pt-3">

                <div class="info-row">
                    <div class="info-icon" style="background:#e0e7ff;"><i class="fas fa-hashtag" style="color:#6366f1; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Kode Tiket</div>
                        <div class="info-row-value" style="font-family:monospace; color:#6366f1; letter-spacing:1px;">{{ $ticket->ticket_code }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon" style="background:#f0fdf4;"><i class="fas fa-signal" style="color:#22c55e; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Status & Prioritas</div>
                        <div class="mt-1 d-flex align-items-center" style="gap:6px;">
                            {!! $ticket->status_badge !!}
                            {!! $ticket->priority_badge !!}
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon" style="background:#fef3c7;"><i class="fas fa-user" style="color:#f59e0b; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Pelapor</div>
                        <div class="info-row-value">{{ $ticket->reporter->name }}</div>
                        <div class="info-row-sub">{{ $ticket->reporter->email }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon" style="background:#dbeafe;"><i class="fas fa-headset" style="color:#3b82f6; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Ditangani Operator</div>
                        <div class="info-row-value">{{ $ticket->operator?->name ?? '—' }}</div>
                        @if(!$ticket->operator) <div class="info-row-sub">Belum ditugaskan</div> @endif
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon" style="background:#f1f5f9;"><i class="far fa-calendar-alt" style="color:#64748b; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Dibuat</div>
                        <div class="info-row-value">{{ $ticket->created_at->format('d M Y, H:i') }}</div>
                        <div class="info-row-sub">{{ $ticket->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                @if($ticket->first_response_at)
                <div class="info-row">
                    <div class="info-icon" style="background:#d1fae5;"><i class="fas fa-stopwatch" style="color:#10b981; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Response Time (SLA)</div>
                        <div class="info-row-value" style="color:#059669;">{{ $ticket->response_time_minutes }} menit</div>
                        <div class="info-row-sub">{{ $ticket->first_response_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif

                @if($ticket->resolved_at)
                <div class="info-row">
                    <div class="info-icon" style="background:#ede9fe;"><i class="fas fa-flag-checkered" style="color:#7c3aed; font-size:12px;"></i></div>
                    <div>
                        <div class="info-row-label">Resolution Time</div>
                        <div class="info-row-value" style="color:#7c3aed;">{{ $ticket->resolution_time_minutes }} menit</div>
                        <div class="info-row-sub">{{ $ticket->resolved_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        @if($ticket->rating)
        <div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg); border-top:3px solid #f59e0b !important;">
            <div class="card-body p-4 text-center">
                <div class="section-label text-center"><i class="fas fa-star text-warning mr-1"></i>Rating Kepuasan</div>
                <div style="font-size:30px; color:#f59e0b; letter-spacing:3px; margin:8px 0;">
                    @for($i = 1; $i <= 5; $i++){{ $i <= $ticket->rating->score ? '★' : '☆' }}@endfor
                </div>
                <div class="font-weight-bold" style="font-size:1.2rem;">{{ $ticket->rating->score }}<span class="text-muted" style="font-size:14px; font-weight:400;">/5</span></div>
                @if($ticket->rating->feedback)
                <div class="mt-3 p-3 rounded-lg" style="background:#fffbeb; border:1px solid #fde68a;">
                    <p class="text-muted small mb-0" style="font-style:italic; color:#92400e !important;">"{{ $ticket->rating->feedback }}"</p>
                </div>
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

document.addEventListener('DOMContentLoaded', function () {
    const scroll = document.querySelector('.chat-scroll');
    if (scroll) scroll.scrollTop = scroll.scrollHeight;
});
</script>
@endpush
