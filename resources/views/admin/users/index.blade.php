@extends('layouts.admin')

@section('title', 'Kelola User')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola User</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 class="m-0">Daftar User</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('users.index') }}" class="form-inline">
                <div class="form-group mr-3">
                    <label for="role" class="mr-2">Role:</label>
                    <select name="role" id="role" class="form-control form-control-sm">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            </form>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <strong>Total Users: {{ $users->total() }}</strong>
                @if(request('role'))
                    <span class="text-muted ml-3">
                        Filtered by Role: {{ request('role') }}
                    </span>
                @endif
            </div>
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge badge-danger">Admin</span>
                                @elseif($user->role === 'karyawan')
                                    <span class="badge badge-primary">Karyawan</span>
                                @else
                                    <span class="badge badge-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
