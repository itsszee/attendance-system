@extends('layouts.admin')

@section('title', 'Kelola Shift')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola Shift</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 class="m-0">Daftar Shift</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('shifts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Shift
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td>{{ $shift->name }}</td>
                                <td>{{ $shift->start_time->format('H:i') }}</td>
                                <td>{{ $shift->end_time->format('H:i') }}</td>
                                <td>{{ $shift->description ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('shifts.destroy', $shift) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus shift ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada shift yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $shifts->links() }}
        </div>
    </div>
</div>
@endsection