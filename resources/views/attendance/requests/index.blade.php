@extends('layouts.user')

@section('title', 'Pengajuan Izin / Cuti')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #6366f1;
        --radius: 20px;
        --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .hero-section {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        padding: 40px 0 80px;
        color: white;
    }

    .page-content {
        margin-top: -50px;
        padding-bottom: 50px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 30px;
        height: 100%;
    }

    .btn-premium {
        background: var(--primary);
        color: white;
        border: 0;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 30px;
    }

    .table thead th {
        background: #f1f5f9;
        border: 0;
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold mb-1">Pengajuan Izin & Cuti</h1>
                <p class="mb-0 opacity-75">Kelola permohonan ketidakhadiran Anda.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                <i class="fas fa-chevron-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="container page-content">
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" style="border-radius: 15px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Form -->
        <div class="col-lg-4 mb-4">
            <div class="glass-card">
                <h5 class="font-weight-bold mb-4">
                    <i class="fas fa-edit text-primary mr-2"></i> Buat Pengajuan
                </h5>
                
                <form action="{{ route('requests.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Tipe Pengajuan</label>
                        <select name="type" class="form-control" required>
                            <option value="" disabled selected>Pilih Tipe</option>
                            <option value="izin">Izin Pribadi</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti Tahunan</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="small font-weight-bold text-muted text-uppercase">Mulai</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="form-group col-6">
                            <label class="small font-weight-bold text-muted text-uppercase">Berakhir</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Alasan</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Tuliskan alasan lengkap..."></textarea>
                    </div>

                    <button type="submit" class="btn-premium w-100 mt-2">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

        <!-- History -->
        <div class="col-lg-8 mb-4">
            <div class="glass-card">
                <h5 class="font-weight-bold mb-4">
                    <i class="fas fa-clock-rotate-left text-primary mr-2"></i> Riwayat Pengajuan
                </h5>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-0">Tipe</th>
                                <th>Rentang Tanggal</th>
                                <th>Status</th>
                                <th class="text-right px-0">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td class="px-0">
                                        <div class="font-weight-bold" style="color: var(--dark);">{{ strtoupper($req->type) }}</div>
                                        <div class="small text-muted">{{ $req->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <span class="font-weight-bold text-primary">{{ \Carbon\Carbon::parse($req->start_date)->format('d M') }}</span> - 
                                            <span class="font-weight-bold text-primary">{{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge" style="background: {{ 
                                            $req->status == 'approved' ? '#dcfce7' : ($req->status == 'rejected' ? '#fee2e2' : '#fef3c7') 
                                        }}; color: {{
                                            $req->status == 'approved' ? '#166534' : ($req->status == 'rejected' ? '#991b1b' : '#92400e')
                                        }}">
                                            {{ strtoupper($req->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right px-0">
                                        <span class="small text-muted" title="{{ $req->admin_notes ?: 'Tidak ada catatan' }}">
                                            {{ $req->admin_notes ? \Illuminate\Support\Str::limit($req->admin_notes, 15) : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Belum ada riwayat pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



