@extends('layouts.admin')

@section('title', 'Edit Kategori Penilaian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assessment-categories.index') }}">Kategori Penilaian</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="m-0"><i class="fas fa-edit mr-2"></i>Edit Indikator</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('assessment-categories.update', $assessmentCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="name">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $assessmentCategory->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Deskripsi Indikator</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $assessmentCategory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="type">Tipe</label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', $assessmentCategory->type) }}">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $assessmentCategory->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif (Tampil di form penilaian)</label>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('assessment-categories.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
