@extends('layouts.admin')

@section('title', 'Dashboard Analitik Helpdesk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.helpdesk.index') }}">Helpdesk</a></li>
    <li class="breadcrumb-item active">Analitik</li>
@endsection

@push('styles')
<style>
    .stat-card {
        border-radius: var(--radius-lg);
        padding: 24px 22px;
        border: 0;
        box-shadow: var(--shadow);
        transition: var(--transition);
        color: white;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card .stat-number { font-size: 2.4rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-label  { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; margin-top: 4px; }
    .stat-card .stat-icon   { font-size: 2.4rem; opacity: 0.15; }

    .chart-card {
        border: 0;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .chart-card .card-header {
        background: white;
        border-bottom: 1px solid #f1f5f9;
        padding: 18px 24px;
    }

    /* Ranking table */
    .rank-table thead th { background:#f8fafc; color:#64748b; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; border:0; padding:14px 16px; }
    .rank-table tbody td { vertical-align:middle; padding:14px 16px; border-color:#f1f5f9; }
    .rank-table tbody tr { transition: all 0.2s; }
    .rank-table tbody tr:hover { background:#f8fafc; }

    .rank-badge {
        width:34px; height:34px; border-radius:10px;
        display:inline-flex; align-items:center; justify-content:center;
        font-weight:800; font-size:14px;
    }
    .rank-1 { background:linear-gradient(135deg,#fceabb,#f8b500); color:white; }
    .rank-2 { background:linear-gradient(135deg,#e2e2e2,#9f9f9f); color:white; }
    .rank-3 { background:linear-gradient(135deg,#f4a460,#8b4513); color:white; }
    .rank-n { background:#f1f5f9; color:#64748b; }

    .time-pill {
        display:inline-flex; align-items:center; gap:5px;
        padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700;
    }
    .rating-stars { color:#f59e0b; font-size:15px; }
</style>
@endpush

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold m-0 h2" style="color:var(--dark);">Analitik Helpdesk</h1>
        <p class="text-muted mb-0">Pantau performa operator dan kualitas layanan helpdesk.</p>
    </div>
    <div class="col-md-6 text-md-right mt-3 mt-md-0">
        <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-light" style="border-radius:12px; font-weight:600; padding:10px 22px;">
            <i class="fas fa-arrow-left mr-2"></i> Tiket Masuk
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $summary['total'] }}</div>
                    <div class="stat-label">Total Tiket</div>
                </div>
                <i class="fas fa-ticket-alt stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $summary['closed'] }}</div>
                    <div class="stat-label">Diselesaikan</div>
                </div>
                <i class="fas fa-check-double stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">
                        {{ $summary['avg_response'] ? round($summary['avg_response']) . 'm' : '—' }}
                    </div>
                    <div class="stat-label">Avg Response</div>
                </div>
                <i class="fas fa-stopwatch stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">
                        {{ $summary['avg_rating'] ? number_format($summary['avg_rating'],1) : '—' }}
                    </div>
                    <div class="stat-label">Avg Rating ⭐</div>
                </div>
                <i class="fas fa-star stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row mb-4">
    {{-- Tickets per Month --}}
    <div class="col-lg-7 mb-4">
        <div class="chart-card card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-chart-bar mr-2 text-primary"></i>
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Tiket per Bulan (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="ticketsChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Rating Distribution --}}
    <div class="col-lg-5 mb-4">
        <div class="chart-card card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-chart-pie mr-2 text-warning"></i>
                <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Distribusi Rating Kepuasan</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="ratingChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Operator Ranking --}}
<div class="card border-0 shadow-sm" style="border-radius:var(--radius-lg);">
    <div class="card-header border-0 d-flex align-items-center" style="padding:18px 24px;">
        <i class="fas fa-medal mr-2 text-warning"></i>
        <h6 class="font-weight-bold mb-0" style="color:#1e293b;">Ranking Performa Operator</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table rank-table mb-0">
                <thead>
                    <tr>
                        <th width="60">Rank</th>
                        <th>Nama Operator</th>
                        <th class="text-center">Tiket Selesai</th>
                        <th class="text-center">Avg Response</th>
                        <th class="text-center">Avg Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operatorStats as $i => $op)
                    <tr>
                        <td>
                            <span class="rank-badge {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-n')) }}">
                                @if($i === 0) <i class="fas fa-crown"></i>
                                @else {{ $i + 1 }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="font-weight-bold" style="color:#1e293b;">{{ $op['name'] }}</div>
                            <small class="text-muted">Operator Helpdesk</small>
                        </td>
                        <td class="text-center">
                            <span class="time-pill" style="background:#dcfce7; color:#166534;">
                                <i class="fas fa-check-circle"></i> {{ $op['total_handled'] }} tiket
                            </span>
                        </td>
                        <td class="text-center">
                            @if($op['avg_response'] !== '-')
                            <span class="time-pill" style="background:#dbeafe; color:#1e40af;">
                                <i class="fas fa-stopwatch"></i> {{ $op['avg_response'] }} mnt
                            </span>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($op['avg_rating'] !== '-')
                            <div class="rating-stars">
                                @for($s = 1; $s <= 5; $s++)
                                    {{ $s <= round($op['avg_rating']) ? '★' : '☆' }}
                                @endfor
                            </div>
                            <small class="text-muted">{{ $op['avg_rating'] }}/5</small>
                            @else
                            <span class="text-muted small">Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fa-2x d-block mb-2" style="color:#cbd5e1;"></i>
                            Belum ada data operator.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Tickets per Month
    const ticketLabels = @json($ticketsPerMonth->keys());
    const ticketData   = @json($ticketsPerMonth->values());

    new Chart(document.getElementById('ticketsChart'), {
        type: 'bar',
        data: {
            labels: ticketLabels,
            datasets: [{
                label: 'Jumlah Tiket',
                data: ticketData,
                backgroundColor: 'rgba(99,102,241,0.2)',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Rating Distribution
    const ratingLabels = ['1 ⭐', '2 ⭐⭐', '3 ⭐⭐⭐', '4 ⭐⭐⭐⭐', '5 ⭐⭐⭐⭐⭐'];
    const ratingData   = [1,2,3,4,5].map(s => {{ json_encode($ratingDistribution) }}[s] || 0);

    new Chart(document.getElementById('ratingChart'), {
        type: 'doughnut',
        data: {
            labels: ratingLabels,
            datasets: [{
                data: ratingData,
                backgroundColor: ['#fee2e2','#fef3c7','#dbeafe','#dcfce7','#d1fae5'],
                borderColor:     ['#ef4444','#f59e0b','#3b82f6','#22c55e','#10b981'],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
        }
    });
</script>
@endpush
