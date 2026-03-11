@extends('layouts.admin')

@section('title', 'Dashboard Penilaian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Beri Penilaian</li>
@endsection

@push('styles')
<style>
    .progress-wrapper {
        background: #f4f6f9;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,.1);
    }
    .employee-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .employee-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,.1);
    }
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-done {
        background: #d4edda;
        color: #155724;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Penilaian Kinerja Periode: <span class="text-primary">{{ $currentPeriod }}</span></h2>
        
        <div class="progress-wrapper">
            <h5 class="mb-3">
                Progres Penilaian: <strong>{{ $assessedCount }} dari {{ $totalEmployees }} staf</strong> telah dinilai bulan ini
            </h5>
            <div class="progress" style="height: 25px; border-radius: 15px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                     role="progressbar" 
                     style="width: {{ $progressPercentage }}%; font-weight: bold; font-size: 1rem;" 
                     aria-valuenow="{{ $progressPercentage }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    {{ $progressPercentage }}%
                </div>
            </div>
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
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=random" 
                         class="img-circle mb-3 shadow" alt="User Image" width="80" height="80">
                    <h5 class="card-title w-100 font-weight-bold mb-1">{{ $employee->name }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-envelope mr-1"></i> {{ $employee->email }}<br>
                        <i class="fas fa-id-badge mr-1"></i> {{ ucfirst($employee->role) }}
                    </p>
                    
                    <a href="{{ route('admin.assessments.create', $employee->id) }}" class="btn btn-block {{ $isAssessed ? 'btn-outline-primary' : 'btn-primary' }}">
                        @if($isAssessed)
                            <i class="fas fa-edit mr-1"></i> Edit Penilaian
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
