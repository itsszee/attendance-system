@extends('layouts.admin')

@section('title', 'Attendance List')
@section('page-title', 'Attendance')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Attendance Records</h3>
        <div class="card-tools">
            <a href="{{ route('admin.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $a)
                    <tr>
                        <td>{{ $a->user->name }}</td>
                        <td>{{ $a->date->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $a->mode == 'WFH' ? 'info' : 'success' }}">
                                {{ $a->mode }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $a->status == 'on_time' ? 'success' : 'danger' }}">
                                {{ $a->status == 'on_time' ? 'On Time' : 'Late' }}
                            </span>
                            @if ($a->status === 'late')
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.attendance.show', $a->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $attendances->links() }}
    </div>
</div>

@endsection