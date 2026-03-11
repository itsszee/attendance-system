@extends('layouts.admin')

@section('title', 'Tambah Kategori Penilaian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assessment-categories.index') }}">Kategori Penilaian</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0"><i class="fas fa-plus mr-2"></i>Tambah Indikator Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('assessment-categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="name">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Disiplin, Tanggung Jawab">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Deskripsi Indikator</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Penjelasan singkat indikator...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="type">Tipe <small class="text-muted">(Opsional, untuk filterisasi)</small></label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type') ?? 'General' }}" placeholder="Contoh: Karyawan, Siswa, General">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active">Aktif (Tampil di form penilaian)</label>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('assessment-categories.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
