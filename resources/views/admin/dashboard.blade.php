@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total Karyawan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['today_attendance'] }}</h3>
                <p>Absen Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['wfh_today'] }} / {{ $stats['wfo_today'] }}</h3>
                <p>WFH / WFO</p>
            </div>
            <div class="icon">
                <i class="fas fa-home"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['late_today'] }}</h3>
                <p>Terlambat</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
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