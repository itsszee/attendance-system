@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Informasi Profil</div>
                <div class="card-body">
                    @if(isset($karyawan) && $karyawan)
                <div class="mb-4">
                    <h5>Data Karyawan</h5>
                    <table class="table table-sm">
                        <tr><th>Nama</th><td>{{ $karyawan->nama }}</td></tr>
                        <tr><th>NIP</th><td>{{ $karyawan->nip }}</td></tr>
                        <tr><th>Jabatan</th><td>{{ $karyawan->jabatan }}</td></tr>
                        <tr><th>Departemen</th><td>{{ $karyawan->departemen }}</td></tr>
                        <tr><th>No. Telepon</th><td>{{ $karyawan->no_telepon ?? '-' }}</td></tr>
                        <tr><th>Alamat</th><td>{{ $karyawan->alamat ?? '-' }}</td></tr>
                        @if($karyawan->shift)
                        <tr><th>Shift</th><td>{{ $karyawan->shift->name }}</td></tr>
                        @endif
                    </table>
                </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        @if(session('status') === 'profile-updated')
                            <span class="text-success ml-2">Tersimpan!</span>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ubah Password</div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="current_password">Password Sekarang</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" required>
                            @error('current_password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-danger">Hapus Akun</div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="form-group">
                            <label for="password_delete">Masukkan password untuk menghapus akun</label>
                            <input id="password_delete" name="password" type="password" class="form-control" required>
                            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection