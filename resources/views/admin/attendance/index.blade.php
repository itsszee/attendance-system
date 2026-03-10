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

    <!-- Filter Form -->
    <div class="card-header">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="form-inline">
            <div class="form-group mr-3">
                <label for="mode" class="mr-2">Mode:</label>
                <select name="mode" id="mode" class="form-control form-control-sm">
                    <option value="">All Modes</option>
                    <option value="WFH" {{ request('mode') == 'WFH' ? 'selected' : '' }}>WFH</option>
                    <option value="WFO" {{ request('mode') == 'WFO' ? 'selected' : '' }}>WFO</option>
                </select>
            </div>

            <div class="form-group mr-3">
                <label for="status" class="mr-2">Status:</label>
                <select name="status" id="status" class="form-control form-control-sm">
                    <option value="">All Status</option>
                    <option value="on_time" {{ request('status') == 'on_time' ? 'selected' : '' }}>On Time</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mr-2">
                <i class="fas fa-filter"></i> Filter
            </button>

            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Clear
            </a>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="p-3">
            <strong>Total Records: {{ $attendances->total() }}</strong>
            @if(request('mode') || request('status'))
                <span class="text-muted ml-3">
                    Filtered by:
                    @if(request('mode')) Mode: {{ request('mode') }} @endif
                    @if(request('mode') && request('status')) | @endif
                    @if(request('status')) Status: {{ request('status') == 'on_time' ? 'On Time' : 'Late' }} @endif
                </span>
            @endif
        </div>
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