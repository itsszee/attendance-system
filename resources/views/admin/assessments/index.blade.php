@extends('layouts.admin')

@section('title', 'Dashboard Penilaian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Beri Penilaian</li>
@endsection

@push('styles')
<style>
    .progress-wrapper {
        background: white;
        padding: 25px;
        border-radius: var(--radius-lg);
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .employee-card {
        border: 0;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
    }
    .employee-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 10;
    }
    .status-done {
        background: #dcfce7;
        color: #166534;
    }
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    .btn-premium {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        transition: var(--transition);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="font-weight-bold mb-4" style="color: var(--dark);">Penilaian Kinerja <span class="text-primary">{{ $currentPeriod }}</span></h2>
        
        <div class="progress-wrapper">
            <h5 class="mb-3 font-weight-bold" style="color: var(--secondary); font-size: 15px;">
                <i class="fas fa-tasks mr-2"></i> PROGRES PENILAIAN: <strong>{{ $assessedCount }} dari {{ $totalEmployees }}</strong> staf selesai dinilai
            </h5>
            <div class="progress shadow-sm" style="height: 12px; border-radius: 10px; background: #f1f5f9;">
                <div class="progress-bar bg-primary" 
                     role="progressbar" 
                     style="width: {{ $progressPercentage }}%; border-radius: 10px;" 
                     aria-valuenow="{{ $progressPercentage }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                </div>
            </div>
            <div class="mt-2 text-right small font-weight-bold text-primary">{{ $progressPercentage }}% Completed</div>
        </div>
    </div>
</div>

<div class="row">
    @forelse($employees as $employee)
        @php
            $isAssessed = in_array($employee->id, $assessedIds);
        @endphp
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card employee-card h-100 position-relative">
                @if($isAssessed)
                    <div class="status-badge status-done"><i class="fas fa-check-circle mr-1"></i> Sudah Dinilai</div>
                @else
                    <div class="status-badge status-pending"><i class="fas fa-clock mr-1"></i> Belum Dinilai</div>
                @endif
                
                <div class="card-body text-center pt-5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=6366f1&color=fff" 
                         class="rounded-circle mb-3 shadow-sm" alt="User Image" width="70" height="70">
                    <h5 class="card-title w-100 font-weight-bold mb-1" style="color: var(--dark);">{{ $employee->name }}</h5>
                    <p class="text-muted small mb-4">
                        <i class="fas fa-id-badge mr-1"></i> {{ strtoupper($employee->role) }}
                    </p>
                    
                    <a href="{{ route('admin.assessments.create', $employee->id) }}" class="btn btn-block btn-premium {{ $isAssessed ? 'btn-outline-primary' : 'btn-primary' }}">
                        @if($isAssessed)
                            <i class="fas fa-edit mr-1"></i> Update
                        @else
                            <i class="fas fa-star mr-1"></i> Beri Nilai
                        @endif
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i> Belum ada data staf/karyawan untuk dinilai.
            </div>
        </div>
    @endforelse
</div>
@endsection
