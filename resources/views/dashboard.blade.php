@extends('layouts.user')

@section('title', 'Dashboard Karyawan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #6366f1;
        --primary-hover: #4f46e5;
        --secondary: #64748b;
        --success: #22c55e;
        --warning: #f59e0b;
        --danger: #ef4444;
        --glass: rgba(255, 255, 255, 0.9);
        --radius: 20px;
        --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .hero-section {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        padding: 60px 0 120px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }

    .dashboard-content {
        margin-top: -80px;
        padding-bottom: 50px;
    }

    .glass-card {
        background: var(--glass);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 30px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .avatar-circle {
        width: 70px;
        height: 70px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: var(--primary);
        box-shadow: var(--shadow);
        margin-right: 20px;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        background: rgba(255,255,255,0.15);
        border-radius: 30px;
        font-size: 14px;
        font-weight: 500;
        margin-top: 10px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .btn-premium {
        padding: 18px 25px;
        border-radius: 16px;
        font-weight: 700;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        border: 0;
        color: white;
        text-align: center;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .btn-premium i {
        font-size: 24px;
        opacity: 0.9;
    }

    .btn-premium span {
        font-size: 15px;
    }

    .btn-wfh { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    .btn-wfo { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .btn-checkout { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .btn-nav { background: white; color: var(--secondary); border: 1px solid #e2e8f0; }
    .btn-nav:hover { background: #f1f5f9; color: var(--primary); }

    .btn-premium:hover {
        transform: translateY(-5px) scale(1.02);
        filter: brightness(1.1);
    }

    .status-badge {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 30px;
    }

    .digital-clock {
        font-size: 42px;
        font-weight: 300;
        margin-top: 5px;
    }

    .section-title {
        font-weight: 700;
        color: var(--secondary);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
    }

    .alert-modern {
        border-radius: 16px;
        border: 0;
        padding: 20px;
        box-shadow: var(--shadow);
    }

    @media (max-width: 768px) {
        .hero-section { padding: 40px 0 100px; }
        .digital-clock { font-size: 32px; }
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center mb-4 mb-md-0">
                <div class="avatar-circle">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="h4 mb-1 font-weight-bold">Halo, {{ auth()->user()->name }} 👋</h1>
                    <div class="stat-pill">
                        <i class="far fa-calendar-alt mr-2"></i> {{ now()->format('l, d F Y') }}
                    </div>
                </div>
            </div>
            <div class="text-md-right">
                <div class="digital-clock" id="clock">00:00:00</div>
                <div class="header-actions mt-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm rounded-pill px-3 mr-2">
                        <i class="fas fa-user-circle mr-1"></i> Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container dashboard-content">
    @if (session('success'))
        <div class="alert alert-success alert-modern alert-dismissible fade show mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x mr-3"></i>
                <div>
                    <h6 class="mb-0 font-weight-bold">Berhasil!</h6>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="glass-card">
                <div class="section-title">Status Kehadiran</div>
                
                @if ($attendanceToday)
                    <div class="text-center py-3">
                        <div class="mb-4">
                            @if($attendanceToday->status == 'on_time')
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            @else
                                <i class="fas fa-exclamation-circle fa-4x text-warning"></i>
                            @endif
                        </div>
                        <h3 class="font-weight-bold mb-1">
                            {{ $attendanceToday->status == 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                        </h3>
                        <p class="text-muted">Anda melakukan check-in pada pukul <strong>{{ $attendanceToday->check_in_at->format('H:i') }}</strong></p>
                        
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <div class="text-center px-4">
                                <span class="small text-muted d-block">Mode</span>
                                <span class="badge bg-soft-primary px-3 text-primary">{{ $attendanceToday->mode }}</span>
                            </div>
                            <div class="text-center px-4 border-left">
                                <span class="small text-muted d-block">Check-out</span>
                                <span class="font-weight-bold">{{ $attendanceToday->check_out_at ? $attendanceToday->check_out_at->format('H:i') : '--:--' }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-4">
                            <i class="fas fa-user-clock fa-4x text-muted opacity-50"></i>
                        </div>
                        <h4 class="font-weight-bold">Belum Absen</h4>
                        <p class="text-muted">Silahkan lakukan check-in hari ini menggunakan salah satu tombol aksi cepat.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="glass-card">
                <div class="section-title">Aksi Cepat</div>
                
                @if (!$attendanceToday)
                    <div class="action-grid">
                        <a href="{{ route('attendance.wfh.form') }}" class="btn-premium btn-wfh">
                            <i class="fas fa-home"></i>
                            <span>Check-in WFH</span>
                        </a>
                        <a href="{{ route('attendance.wfo.form') }}" class="btn-premium btn-wfo">
                            <i class="fas fa-building"></i>
                            <span>Check-in WFO</span>
                        </a>
                    </div>
                @else
                    @if (!$attendanceToday->check_out_at)
                        <form method="POST" action="{{ route('attendance.checkout') }}">
                            @csrf
                            <button type="submit" class="btn-premium btn-checkout w-100">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Check-out Sekarang</span>
                            </button>
                        </form>
                    @else
                        <div class="alert alert-soft-success text-center py-4 rounded-lg border-0" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                            <i class="fas fa-check-double fa-2x mb-3"></i>
                            <h5 class="font-weight-bold mb-0">Tugas Selesai!</h5>
                            <p class="mb-0 mt-1 opacity-75">Anda sudah menyelesaikan absensi hari ini.</p>
                        </div>
                    @endif
                @endif

                <div class="action-grid mt-4">
                    <a href="{{ route('requests.index') }}" class="btn-premium btn-nav">
                        <i class="fas fa-file-signature text-warning"></i>
                        <span>Pengajuan</span>
                    </a>
                    <a href="{{ route('employee.assessments.index') }}" class="btn-premium btn-nav">
                        <i class="fas fa-star-half-alt text-info"></i>
                        <span>Nilai Saya</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($attendanceToday && $attendanceToday->mode === 'WFH')
    <div class="glass-card mt-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="section-title">Detail WFH Hari Ini</div>
                <div class="p-3 bg-light rounded-lg mb-3">
                    <i class="fas fa-tasks text-primary mr-2"></i>
                    <strong>Task:</strong>
                    <span class="text-muted ml-2">{{ $attendanceToday->task ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-muted small">Status Persetujuan:</span>
                    <span class="status-badge ml-2" style="background: {{ 
                        $attendanceToday->approval_status == 'approved' ? '#dcfce7' : 
                        ($attendanceToday->approval_status == 'rejected' ? '#fee2e2' : '#fef3c7') 
                    }}; color: {{
                        $attendanceToday->approval_status == 'approved' ? '#166534' : 
                        ($attendanceToday->approval_status == 'rejected' ? '#991b1b' : '#92400e')
                    }}">
                        {{ strtoupper($attendanceToday->approval_status) }}
                    </span>
                </div>
            </div>
            @if ($attendanceToday->selfie_path)
            <div class="col-md-4 text-center mt-4 mt-md-0">
                <img src="{{ asset('storage/' . $attendanceToday->selfie_path) }}" 
                     class="img-fluid rounded-lg shadow-sm w-100" 
                     style="max-width: 200px; border: 4px solid white;">
                <p class="small text-muted mt-2">Selfie Check-in</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('clock');
        clock.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush
@endsection