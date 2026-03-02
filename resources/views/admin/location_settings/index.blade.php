@extends('layouts.admin')

@section('title', 'Lokasi QR Radius')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 class="m-0">Daftar Lokasi</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('location_settings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Lokasi
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
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Radius (m)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $loc)
                        <tr>
                            <td>{{ ($locations->currentPage()-1)*$locations->perPage() + $loop->iteration }}</td>
                            <td>{{ $loc->name }}</td>
                            <td>{{ $loc->latitude }}</td>
                            <td>{{ $loc->longitude }}</td>
                            <td>{{ $loc->radius }}</td>
                            <td>
                                <a href="{{ route('location_settings.edit', $loc) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('location_settings.destroy', $loc) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus lokasi ini?')"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada lokasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $locations->links() }}
        </div>
    </div>
</div>
@endsection