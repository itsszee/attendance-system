@extends('layouts.admin')

@section('title', 'Helpdesk - Antrian Tiket')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Helpdesk</li>
@endsection

@push('styles')
<style>
    .stat-card {
        border-radius: 16px;
        padding: 22px 20px;
        border: 0;
        box-shadow: var(--shadow);
        transition: var(--transition);
        color: white;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card .stat-number { font-size: 2.2rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-label  { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; margin-top: 4px; }
    .stat-card .stat-icon   { font-size: 2.2rem; opacity: 0.18; }

    .ticket-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 0;
        padding: 14px 16px;
    }
    .ticket-table tbody td { vertical-align: middle; padding: 14px 16px; border-color: #f1f5f9; }
    .ticket-table tbody tr { transition: all 0.2s ease; }
    .ticket-table tbody tr:hover { background: #f8fafc; }

    .filter-bar {
        background: white;
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }
    .filter-select {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 14px;
        background: #f8fafc;
        font-family:'Outfit',sans-serif;
    }
    .filter-select:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }

    .priority-dot { width:10px; height:10px; border-radius:50%; display:inline-block; margin-right:6px; }
    .priority-dot.high { background:#ef4444; }
    .priority-dot.mid  { background:#f59e0b; }
    .priority-dot.low  { background:#64748b; }

    .link-to-analytics {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 10px 22px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }
    .link-to-analytics:hover { color:white; transform:translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.4); }
</style>
@endpush

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold m-0 h2" style="color:var(--dark);">Helpdesk</h1>
        <p class="text-muted mb-0">Kelola antrian tiket kendala teknis & operasional karyawan.</p>
    </div>
    <div class="col-md-6 text-md-right mt-3 mt-md-0">
        <a href="{{ route('admin.helpdesk.dashboard') }}" class="link-to-analytics">
            <i class="fas fa-chart-line mr-2"></i> Dashboard Analitik
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Tiket</div>
                </div>
                <i class="fas fa-ticket-alt stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['open'] }}</div>
                    <div class="stat-label">Open</div>
                </div>
                <i class="fas fa-folder-open stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['in_progress'] }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <i class="fas fa-spinner stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['closed'] }}</div>
                    <div class="stat-label">Closed</div>
                </div>
                <i class="fas fa-check-circle stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar d-flex align-items-center flex-wrap gap-3" style="gap:12px;">
    <form method="GET" action="{{ route('admin.helpdesk.index') }}" class="d-flex align-items-center flex-wrap" style="gap:10px;">
        <span class="small font-weight-bold text-muted mr-2"><i class="fas fa-filter mr-1"></i>Filter:</span>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="open"        {{ request('status') === 'open'        ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="closed"      {{ request('status') === 'closed'      ? 'selected' : '' }}>Closed</option>
        </select>
        <select name="priority" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Prioritas</option>
            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
            <option value="mid"  {{ request('priority') === 'mid'  ? 'selected' : '' }}>Mid</option>
            <option value="low"  {{ request('priority') === 'low'  ? 'selected' : '' }}>Low</option>
        </select>
        @if(request()->hasAny(['status','priority']))
        <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;">
            <i class="fas fa-times mr-1"></i>Reset
        </a>
        @endif
    </form>
</div>

{{-- Ticket Table --}}
<div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg);">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ticket-table mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Subject</th>
                        <th>Pelapor</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Operator</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <span class="small font-weight-bold" style="font-family:monospace; color:#6366f1;">{{ $ticket->ticket_code }}</span>
                        </td>
                        <td style="max-width:220px;">
                            <div class="font-weight-bold" style="color:#1e293b;" title="{{ $ticket->subject }}">{{ Str::limit($ticket->subject,40) }}</div>
                            <small class="text-muted"><i class="fas fa-comment-dots mr-1"></i>{{ $ticket->responses->count() }} balasan</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center font-weight-bold" style="width:32px;height:32px;background:rgba(99,102,241,0.1);color:#6366f1;font-size:13px;">
                                    {{ substr($ticket->reporter->name, 0, 1) }}
                                </div>
                                <div class="small font-weight-bold" style="color:#1e293b;">{{ $ticket->reporter->name }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="priority-dot {{ $ticket->priority }}"></span>
                            <span class="small font-weight-bold text-uppercase" style="color:{{ $ticket->priority === 'high' ? '#991b1b' : ($ticket->priority === 'mid' ? '#92400e' : '#475569') }};">
                                {{ $ticket->priority }}
                            </span>
                        </td>
                        <td>{!! $ticket->status_badge !!}</td>
                        <td class="small text-muted">{{ $ticket->operator?->name ?? '—' }}</td>
                        <td class="small text-muted">
                            {{ $ticket->created_at->format('d M Y') }}<br>{{ $ticket->created_at->format('H:i') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.helpdesk.show', $ticket) }}" class="btn btn-sm shadow-sm" style="background:var(--primary);color:white;border-radius:8px;padding:6px 14px;">
                                <i class="fas fa-eye mr-1"></i> Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2" style="color:#cbd5e1;"></i>
                            Belum ada tiket masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
