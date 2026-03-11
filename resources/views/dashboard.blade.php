@extends('layouts.user')

@section('title', 'Dashboard Karyawan')

@push('styles')
<style>
    .dashboard-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 30px 0;
    }

    .welcome-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .welcome-card h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .welcome-card p {
        color: #666;
        font-size: 15px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon i {
        font-size: 28px;
        color: white;
    }

    .stat-label {
        font-size: 13px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .stat-detail {
        font-size: 13px;
        color: #666;
    }

    .action-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .action-btn {
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .btn-wfh {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-wfo {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-checkout {
        background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
        color: white;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-top: 30px;
    }

    .detail-card h5 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .detail-item strong {
        color: #667eea;
        font-weight: 600;
    }

    .selfie-preview {
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        max-width: 250px;
        margin-top: 10px;
    }

    .badge-custom {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger { background: #f8d7da; color: #721c24; }

    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-header {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-profile {
        background: #667eea;
        color: white;
    }

    .btn-logout {
        background: #dc3545;
        color: white;
    }

    .btn-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 20px 0;
        }

        .welcome-card, .stat-card, .action-card, .detail-card {
            padding: 20px;
        }

        .stat-value {
            font-size: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        
        <!-- Welcome Section -->
        <div class="welcome-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2>👋 Selamat Datang, {{ auth()->user()->name }}</h2>
                    <p>{{ now()->format('l, d F Y') }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('employee.assessments.index') }}" class="btn btn-info btn-header">
                        <i class="fas fa-star"></i> Penilaian
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-profile btn-header">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout btn-header">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-modern alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-modern alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- Stats & Actions Row -->
        <div class="row">
            <!-- Status Card -->
            <div class="col-md-6 mb-4">
                <div class="stat-card">
                    @if ($attendanceToday)
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-label">Status Hari Ini</div>
                        <div class="stat-value">
                            {{ $attendanceToday->status == 'on_time' ? 'Tepat Waktu ✅' : 'Terlambat ⚠️' }}
                        </div>
                        <div class="stat-detail">
                            <i class="fas fa-{{ $attendanceToday->mode == 'WFH' ? 'home' : 'building' }}"></i>
                            {{ $attendanceToday->mode }} • 
                            Check-in: {{ $attendanceToday->check_in_at->format('H:i') }}
                        </div>
                        @if ($attendanceToday->check_out_at)
                            <div class="stat-detail mt-2">
                                <i class="fas fa-sign-out-alt"></i>
                                Check-out: {{ $attendanceToday->check_out_at->format('H:i') }}
                            </div>
                        @endif
                    @else
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-label">Status Hari Ini</div>
                        <div class="stat-value">Belum Absen</div>
                        <div class="stat-detail">
                            Silakan lakukan check-in untuk memulai hari Anda
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Card -->
            <div class="col-md-6 mb-4">
                <div class="action-card">
                    <h5 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin-bottom: 20px;">
                        <i class="fas fa-hand-pointer"></i> Aksi Cepat
                    </h5>
                    
                    @if (!$attendanceToday)
                        <a href="{{ route('attendance.wfh.form') }}" class="action-btn btn-wfh mb-3">
                            <i class="fas fa-home"></i>
                            <span>Check-in Work From Home</span>
                        </a>
                        <a href="{{ route('attendance.wfo.form') }}" class="action-btn btn-wfo">
                            <i class="fas fa-building"></i>
                            <span>Check-in Work From Office</span>
                        </a>
                    @else
                        @if (!$attendanceToday->check_out_at)
                            <form method="POST" action="{{ route('attendance.checkout') }}">
                                @csrf
                                <button type="submit" class="action-btn btn-checkout w-100">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Check Out Sekarang</span>
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success alert-modern mb-0">
                                <i class="fas fa-check-double"></i>
                                <strong>Selesai!</strong> Anda sudah check-out pada {{ $attendanceToday->check_out_at->format('H:i') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- WFH Details -->
        @if ($attendanceToday && $attendanceToday->mode === 'WFH')
            <div class="detail-card">
                <h5>
                    <i class="fas fa-clipboard-list"></i>
                    Detail Work From Home
                </h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <strong><i class="fas fa-tasks"></i> Task Hari Ini:</strong><br>
                            <p class="mb-0 mt-2">{{ $attendanceToday->task ?? 'Tidak ada task' }}</p>
                        </div>

                        <div class="detail-item">
                            <strong><i class="fas fa-check-circle"></i> Status Approval:</strong><br>
                            <span class="badge-custom badge-{{ $attendanceToday->approval_status == 'approved' ? 'success' : ($attendanceToday->approval_status == 'rejected' ? 'danger' : 'warning') }} mt-2">
                                {{ ucfirst($attendanceToday->approval_status) }}
                            </span>
                        </div>
                    </div>

                    @if ($attendanceToday->selfie_path)
                        <div class="col-md-6 text-center">
                            <strong class="d-block mb-3"><i class="fas fa-camera"></i> Selfie Check-in:</strong>
                            <img src="{{ asset('storage/' . $attendanceToday->selfie_path) }}" 
                                 class="img-fluid selfie-preview" 
                                 alt="Selfie">
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection