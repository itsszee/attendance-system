@extends('layouts.admin')

@section('title', 'Attendance Detail')
@section('page-title', 'Attendance Detail')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Basic Information</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">User:</th>
                        <td>{{ $attendance->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td>{{ $attendance->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Mode:</th>
                        <td>
                            <span class="badge badge-{{ $attendance->mode == 'WFH' ? 'info' : 'success' }}">
                                {{ $attendance->mode }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Check-in:</th>
                        <td>{{ $attendance->check_in_at->format('H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Check-out:</th>
                        <td>{{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i:s') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge badge-{{ $attendance->status == 'on_time' ? 'success' : 'danger' }}">
                                {{ $attendance->status == 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Approval:</th>
                        <td>
                            <span class="badge badge-warning">{{ ucfirst($attendance->approval_status) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if($attendance->mode === 'WFH')
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">WFH Details</h3>
            </div>
            <div class="card-body">
                <p><strong>Task:</strong></p>
                <p>{{ $attendance->task ?? '-' }}</p>

                @if($attendance->selfie_path)
                <p><strong>Selfie:</strong></p>
                <img src="{{ asset('storage/' . $attendance->selfie_path) }}" class="img-fluid rounded" style="max-width: 300px;">
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@if($attendance->latitude && $attendance->longitude)
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lokasi</h3>
    </div>
    <div class="card-body">
        <p><strong>Koordinat:</strong> {{ $attendance->latitude }}, {{ $attendance->longitude }}</p>
        <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" 
           target="_blank" class="btn btn-success">
            <i class="fas fa-map-marker-alt"></i> Lihat di Google Maps
        </a>
    </div>
</div>
@endif

<a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

@endsection