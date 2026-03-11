@extends('layouts.admin')

@section('title', 'Form Penilaian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.assessments.index') }}">Beri Penilaian</a></li>
    <li class="breadcrumb-item active">Form</li>
@endsection

@push('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        cursor: pointer;
        width: 30px;
        height: 30px;
        background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23ccc"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100%;
        transition: transform 0.2s;
        margin: 0 2px;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23ffc107"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>');
    }
    .star-rating label:hover {
        transform: scale(1.2);
    }
    .category-box {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .category-box:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Profil Singkat -->
    <div class="col-md-4">
        <div class="card card-primary card-outline shadow-sm sticky-top" style="top: 20px;">
            <div class="card-body box-profile text-center">
                <div class="text-center mb-3">
                    <img class="profile-user-img img-fluid img-circle"
                         src="https://ui-avatars.com/api/?name={{ urlencode($evaluatee->name) }}&background=667eea&color=fff&size=128"
                         alt="User profile picture">
                </div>

                <h3 class="profile-username text-center font-weight-bold">{{ $evaluatee->name }}</h3>
                <p class="text-muted text-center">{{ ucfirst($evaluatee->role) }}</p>

                <ul class="list-group list-group-unbordered mb-3 text-left">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right text-dark">{{ $evaluatee->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Periode Penilaian</b> <a class="float-right text-primary font-weight-bold">{{ $currentPeriod }}</a>
                    </li>
                </ul>
                
                @if($nextEmployeeId)
                    <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle"></i> Setelah ini, Anda dapat langsung menilai staf berikutnya.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Penilaian -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h4 class="card-title font-weight-bold"><i class="fas fa-clipboard-check text-primary mr-2"></i> Form Evaluasi Sikap & Kinerja</h4>
            </div>
            <div class="card-body">
                
                @if($categories->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada kategori penilaian yang aktif. 
                        Silakan tambahkan indikator di menu <a href="{{ route('assessment-categories.index') }}" class="font-weight-bold text-dark">Kategori Penilaian</a> terlebih dahulu.
                    </div>
                @else
                    <form action="{{ route('admin.assessments.store', $evaluatee->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">1. Indikator Penilaian</h5>
                            @foreach($categories as $category)
                                <div class="category-box row align-items-center">
                                    <div class="col-md-7">
                                        <h6 class="mb-1 font-weight-bold">{{ $category->name }}</h6>
                                        @if($category->description)
                                            <p class="text-muted small mb-0">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <!-- Star Rating UI -->
                                        <div class="star-rating justify-content-md-end justify-content-start mt-2 mt-md-0">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star{{ $i }}_{{ $category->id }}" name="scores[{{ $category->id }}]" value="{{ $i }}" required>
                                                <label for="star{{ $i }}_{{ $category->id }}" title="{{ $i }} Bintang"></label>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">2. Catatan / Feedback Umum</h5>
                            <div class="form-group">
                                <textarea class="form-control" name="general_notes" rows="4" placeholder="Tuliskan apresiasi, saran perbaikan, atau poin penting lainnya untuk {{ $evaluatee->name }}..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <a href="{{ route('admin.assessments.index') }}" class="btn btn-light border"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                            
                            <div>
                                <button type="submit" name="save_only" value="1" class="btn btn-primary mr-2">
                                    <i class="fas fa-save mr-1"></i> Simpan
                                </button>
                                
                                @if($nextEmployeeId)
                                    <input type="hidden" name="next_employee_id" value="{{ $nextEmployeeId }}">
                                    <button type="submit" name="save_and_next" value="1" class="btn btn-success">
                                        <i class="fas fa-forward mr-1"></i> Simpan & Lanjut
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
