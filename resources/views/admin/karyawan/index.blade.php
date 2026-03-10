@extends('layouts.admin')

@section('title', 'Kelola Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 class="m-0">Daftar Karyawan</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('karyawan.index') }}" class="form-inline">
                <div class="form-group mr-3">
                    <label for="jabatan" class="mr-2">Jabatan:</label>
                    <select name="jabatan" id="jabatan" class="form-control form-control-sm">
                        <option value="">All Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                            <option value="{{ $jabatan }}" {{ request('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mr-3">
                    <label for="departemen" class="mr-2">Departemen:</label>
                    <select name="departemen" id="departemen" class="form-control form-control-sm">
                        <option value="">All Departemen</option>
                        @foreach($departemenList as $departemen)
                            <option value="{{ $departemen }}" {{ request('departemen') == $departemen ? 'selected' : '' }}>{{ $departemen }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            </form>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <strong>Total Karyawan: {{ $karyawan->total() }}</strong>
                @if(request('jabatan') || request('departemen'))
                    <span class="text-muted ml-3">
                        Filtered by:
                        @if(request('jabatan')) Jabatan: {{ request('jabatan') }} @endif
                        @if(request('jabatan') && request('departemen')) | @endif
                        @if(request('departemen')) Departemen: {{ request('departemen') }} @endif
                    </span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="4%">#</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Departemen</th>
                            <th>Email</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan as $k)
                            <tr>
                                <td>{{ ($karyawan->currentPage() - 1) * $karyawan->perPage() + $loop->iteration }}</td>
                                <td>{{ $k->nama }}</td>
                                <td>{{ $k->nip }}</td>
                                <td>{{ $k->jabatan }}</td>
                                <td>{{ $k->departemen }}</td>
                                <td>{{ $k->email }}</td>
                                <td>
                                    <a href="{{ route('karyawan.edit', $k) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('karyawan.destroy', $k) }}" method="POST" style="display:inline;">
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
                                <td colspan="7" class="text-center py-4">Tidak ada data karyawan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $karyawan->links() }}
        </div>
    </div>
</div>
@endsection
