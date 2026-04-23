@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
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
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card .stat-number { font-size: 2.4rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-label  { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; margin-top: 4px; }
    .stat-card .stat-icon   { font-size: 2.4rem; opacity: 0.15; }
</style>
@endpush

@section('content')

<div class="row mb-4">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['total_users'] }}</div>
                    <div class="stat-label">Total Karyawan</div>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['today_attendance'] }}</div>
                    <div class="stat-label">Absen Hari Ini</div>
                </div>
                <i class="fas fa-clipboard-check stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['wfh_today'] }} / {{ $stats['wfo_today'] }}</div>
                    <div class="stat-label">WFH / WFO</div>
                </div>
                <i class="fas fa-home stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f43f5e, #e11d48);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $stats['late_today'] }}</div>
                    <div class="stat-label">Terlambat</div>
                </div>
                <i class="fas fa-exclamation-triangle stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Attendance (10 Terakhir)</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Mode</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAttendance as $a)
                    <tr>
                        <td>{{ $a->user->name }}</td>
                        <td>{{ $a->date->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $a->mode == 'WFH' ? 'info' : 'success' }}">
                                {{ $a->mode }}
                            </span>
                        </td>
                        <td>{{ $a->check_in_at->format('H:i') }}</td>
                        <td>{{ $a->check_out_at ? $a->check_out_at->format('H:i') : '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $a->status == 'on_time' ? 'success' : 'danger' }}">
                                {{ $a->status == 'on_time' ? 'On Time' : 'Late' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.attendance.show', $a->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada attendance</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection