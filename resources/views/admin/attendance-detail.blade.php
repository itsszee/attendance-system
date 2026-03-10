@extends('layouts.admin')

@section('title', 'Attendance Detail')
@section('page-title', 'Detail Absensi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
    .info-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .info-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .info-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 15px 20px;
    }

    .info-card .card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .info-table {
        margin: 0;
    }

    .info-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table th {
        font-weight: 600;
        color: #667eea;
        padding: 10px 0;
        width: 140px;
        font-size: 14px;
    }

    .info-table td {
        padding: 10px 0;
        font-size: 14px;
    }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 12px;
    }

    .selfie-image {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 200px;
        height: auto;
        margin-top: 10px;
    }

    .approval-section {
        background: #fff3cd;
        border: 2px solid #ffc107;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .approval-section h5 {
        font-size: 15px;
        font-weight: 600;
        color: #856404;
        margin-bottom: 12px;
    }

    .btn-approve {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .map-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .map-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .task-box {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        border-left: 3px solid #667eea;
        margin-bottom: 12px;
        font-size: 14px;
        line-height: 1.5;
    }

    .status-badge {
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }

    .status-badge.approved {
        background: #d4edda;
        color: #155724;
        border: 1px solid #28a745;
    }

    .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #dc3545;
    }

    .section-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<!-- Approval Status Badge -->
@if($attendance->mode === 'WFH')
    <div class="status-badge {{ $attendance->approval_status }}">
        @if($attendance->approval_status == 'pending')
            <i class="fas fa-clock"></i>
            <strong>Menunggu Approval</strong> - WFH ini belum di-review
        @elseif($attendance->approval_status == 'approved')
            <i class="fas fa-check-circle"></i>
            <strong>Approved</strong> - WFH telah disetujui
        @else
            <i class="fas fa-times-circle"></i>
            <strong>Rejected</strong> - WFH telah ditolak
        @endif
    </div>
@endif

<div class="row">
    <!-- Basic Information -->
    <div class="col-md-6">
        <div class="card info-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Informasi Dasar
                </h3>
            </div>
            <div class="card-body">
                <table class="table info-table table-borderless">
                    <tr>
                        <th><i class="fas fa-user"></i> User:</th>
                        <td>{{ $attendance->user->name }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar"></i> Tanggal:</th>
                        <td>{{ $attendance->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-briefcase"></i> Mode:</th>
                        <td>
                            <span class="badge badge-custom badge-{{ $attendance->mode == 'WFH' ? 'info' : 'success' }}">
                                {{ $attendance->mode == 'WFH' ? '🏠 WFH' : '🏢 WFO' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-sign-in-alt"></i> Check-in:</th>
                        <td><strong>{{ $attendance->check_in_at->format('H:i:s') }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-sign-out-alt"></i> Check-out:</th>
                        <td><strong>{{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i:s') : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-traffic-light"></i> Status:</th>
                        <td>
                            <span class="badge badge-custom badge-{{ $attendance->status == 'on_time' ? 'success' : 'danger' }}">
                                {{ $attendance->status == 'on_time' ? '✅ Tepat Waktu' : '⚠️ Terlambat' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-clipboard-check"></i> Approval:</th>
                        <td>
                            @if($attendance->approval_status == 'pending')
                                <span class="badge badge-custom badge-warning">⏳ Pending</span>
                            @elseif($attendance->approval_status == 'approved')
                                <span class="badge badge-custom badge-success">✅ Approved</span>
                            @else
                                <span class="badge badge-custom badge-danger">❌ Rejected</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- WFH Details -->
    @if($attendance->mode === 'WFH')
    <div class="col-md-6">
        <div class="card info-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-home"></i> WFH Details
                </h3>
            </div>
            <div class="card-body">
                <p class="section-title">
                    <i class="fas fa-tasks"></i> Task Hari Ini:
                </p>
                <div class="task-box">
                    {{ $attendance->task ?? 'Tidak ada task yang dicatat' }}
                </div>

                @if($attendance->selfie_path)
                <p class="section-title">
                    <i class="fas fa-camera"></i> Selfie Check-in:
                </p>
                <img src="{{ asset('storage/' . $attendance->selfie_path) }}" 
                     class="selfie-image" 
                     alt="Selfie"
                     loading="lazy">
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Location -->
@if($attendance->latitude && $attendance->longitude)
<div class="card info-card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-map-marker-alt"></i> Lokasi Check-in
        </h3>
    </div>
    <div class="card-body">
        <p style="margin-bottom: 12px;">
            <strong><i class="fas fa-globe"></i> Koordinat:</strong>
            <code style="font-size: 13px;">{{ $attendance->latitude }}, {{ $attendance->longitude }}</code>
        </p>
        <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" 
           target="_blank" 
           class="map-btn">
            <i class="fas fa-map-marked-alt"></i> Buka di Google Maps
        </a>
    </div>
</div>
@endif

<!-- Approval Actions -->
@if($attendance->mode === 'WFH' && $attendance->approval_status == 'pending')
<div class="approval-section">
    <h5><i class="fas fa-user-check"></i> Action Required</h5>
    <p class="mb-3" style="font-size: 14px; color: #856404;">Periksa task dan selfie sebelum memberikan approval.</p>
    
    <form action="{{ route('admin.attendance.approve', $attendance->id) }}" method="POST" class="d-inline mr-2">
        @csrf
        <button type="submit" class="btn btn-approve" onclick="return confirm('Approve WFH ini?')">
            <i class="fas fa-check"></i> Approve
        </button>
    </form>
    
    <form action="{{ route('admin.attendance.reject', $attendance->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-reject" onclick="return confirm('Reject WFH ini?')">
            <i class="fas fa-times"></i> Reject
        </button>
    </form>
</div>
@endif

<!-- Navigation -->
<div class="mb-3">
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@endsection