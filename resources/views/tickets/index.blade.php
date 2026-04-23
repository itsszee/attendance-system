@extends('layouts.user')

@section('title', 'Tiket Saya - Helpdesk')

@push('styles')
<style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --radius: 20px;
        --shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
    }

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
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }
    .glass-card:hover { transform: translateY(-3px); }

    .stat-card {
        border-radius: 16px;
        padding: 22px 20px;
        border: 0;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card .stat-number { font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-label  { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.75; margin-top: 4px; }
    .stat-card .stat-icon   { font-size: 2rem; opacity: 0.2; }

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
    .ticket-table tbody tr { transition: all 0.2s ease; }
    .ticket-table tbody tr:hover { background: #f8fafc; }
    .ticket-table tbody td { vertical-align: middle; padding: 14px 16px; border-color: #f1f5f9; }

    .status-badge, .priority-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-create {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        border: 0;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99,102,241,0.35);
    }
    .btn-create:hover { color: white; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,0.4); }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h4 font-weight-bold mb-1">Tiket Saya 🎫</h1>
                <p class="mb-0" style="opacity:0.8;">Pantau dan kelola semua laporan kendala Anda di sini.</p>
            </div>
            <a href="{{ route('user.tickets.create') }}" class="btn-create d-inline-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i> Buat Tiket Baru
            </a>
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

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#6366f1,#4f46e5); color:white;">
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
            <div class="stat-card" style="background: linear-gradient(135deg,#f59e0b,#d97706); color:white;">
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
            <div class="stat-card" style="background: linear-gradient(135deg,#0ea5e9,#0284c7); color:white;">
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
            <div class="stat-card" style="background: linear-gradient(135deg,#22c55e,#16a34a); color:white;">
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

    {{-- Ticket Table --}}
    <div class="glass-card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding: 20px 24px; background:transparent; border-bottom:1px solid #f1f5f9;">
            <h5 class="font-weight-bold mb-0" style="color:#1e293b;"><i class="fas fa-list-ul mr-2 text-primary"></i>Daftar Tiket</h5>
        </div>
        <div class="table-responsive">
            <table class="table ticket-table mb-0">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <span class="font-weight-bold small" style="color:#6366f1; font-family:monospace;">{{ $ticket->ticket_code }}</span>
                        </td>
                        <td>
                            <div class="font-weight-bold" style="color:#1e293b; max-width:250px;" title="{{ $ticket->subject }}">
                                {{ Str::limit($ticket->subject, 45) }}
                            </div>
                            @if($ticket->responses->count() > 0)
                                <small class="text-muted"><i class="fas fa-comment-dots mr-1"></i>{{ $ticket->responses->count() }} balasan</small>
                            @endif
                        </td>
                        <td>{!! $ticket->priority_badge !!}</td>
                        <td>{!! $ticket->status_badge !!}</td>
                        <td class="small text-muted">
                            {{ $ticket->created_at->format('d M Y') }}<br>
                            {{ $ticket->created_at->format('H:i') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('user.tickets.show', $ticket) }}" class="btn btn-sm shadow-sm" style="background:var(--primary); color:white; border-radius:8px; padding:6px 14px;">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-inbox d-block"></i>
                                <h5 class="font-weight-bold" style="color:#94a3b8;">Belum Ada Tiket</h5>
                                <p class="text-muted mb-4">Anda belum pernah membuat laporan kendala. Buat tiket pertama Anda sekarang.</p>
                                <a href="{{ route('user.tickets.create') }}" class="btn-create">
                                    <i class="fas fa-plus-circle mr-2"></i> Buat Tiket Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
