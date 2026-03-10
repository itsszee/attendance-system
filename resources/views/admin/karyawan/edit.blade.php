@extends('layouts.admin')

@section('title', 'Edit Karyawan: ' . $karyawan->nama)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Edit Karyawan: {{ $karyawan->nama }}</h2>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan!</h4>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('karyawan.update', $karyawan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $karyawan->nama) }}" required>
                    @error('nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip', $karyawan->nip) }}" required>
                    @error('nip')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $karyawan->jabatan) }}" required>
                    @error('jabatan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="departemen">Departemen</label>
                    <input type="text" class="form-control @error('departemen') is-invalid @enderror" id="departemen" name="departemen" value="{{ old('departemen', $karyawan->departemen) }}" required>
                    @error('departemen')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email (pilih dari user yang sudah ada)</label>
                    <select class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                        <option value="">-- pilih email --</option>
                        @foreach($users as $email)
                            <option value="{{ $email }}" {{ old('email', $karyawan->email) == $email ? 'selected' : '' }}>{{ $email }}</option>
                        @endforeach
                    </select>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_telepon">No. Telepon <small>(Opsional)</small></label>
                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $karyawan->no_telepon) }}">
                    @error('no_telepon')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat <small>(Opsional)</small></label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="shift_id">Shift <small>(Opsional)</small></label>
                    <select class="form-control @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id">
                        <option value="">Pilih Shift</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_id', $karyawan->shift_id) == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }} ({{ $shift->start_time->format('H:i') }} - {{ $shift->end_time->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                    @error('shift_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
