@extends('layouts.user')

@section('title', 'Buat Tiket Baru - Helpdesk')

@push('styles')
<style>
    :root { --primary: #6366f1; --radius: 20px; --shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }

    .hero-section {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        padding: 50px 0 110px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }
    .dashboard-content { margin-top: -80px; padding-bottom: 50px; }

    .glass-card {
        background: rgba(255,255,255,0.97);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(255,255,255,0.4);
    }
    .form-label-custom {
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        margin-bottom: 8px;
    }
    .form-control-custom {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        transition: all 0.2s ease;
        font-size: 15px;
    }
    .form-control-custom:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        background: #fff;
    }
    .priority-option { display: none; }
    .priority-label {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
    }
    .priority-option:checked + .priority-label { transform: scale(1.05); }
    .priority-option[value="low"]:checked + .priority-label   { background: #f1f5f9; border-color: #64748b; color: #475569; }
    .priority-option[value="mid"]:checked + .priority-label   { background: #fef3c7; border-color: #f59e0b; color: #92400e; }
    .priority-option[value="high"]:checked + .priority-label  { background: #fee2e2; border-color: #ef4444; color: #991b1b; }

    .btn-submit {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border: 0;
        border-radius: 12px;
        padding: 14px 36px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 15px rgba(99,102,241,0.35);
        transition: all 0.3s ease;
    }
    .btn-submit:hover { color: white; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,0.4); }

    /* Similar Tickets Section */
    #similar-section { display: none; }
    .similar-ticket-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .similar-ticket-item:hover { border-color: #6366f1; background: #eef2ff; }
    .similar-ticket-item .code { font-family: monospace; font-size: 12px; color: #6366f1; font-weight: 700; min-width: 140px; }
    .similar-ticket-item .subject { flex: 1; font-weight: 600; font-size: 14px; color: #1e293b; }

    .spinner-search { display: none; }
    #search-loading .spinner-search { display: inline-block; }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2" style="font-size:13px; opacity:0.8;">
                <li class="breadcrumb-item"><a href="{{ route('user.tickets.index') }}" class="text-white">Tiket Saya</a></li>
                <li class="breadcrumb-item active text-white">Buat Tiket Baru</li>
            </ol>
        </nav>
        <h1 class="h4 font-weight-bold mb-1">Buat Laporan Kendala</h1>
        <p class="mb-0" style="opacity:0.8;">Deskripsikan masalah Anda dan tim helpdesk akan segera merespons.</p>
    </div>
</div>

<div class="container dashboard-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Similar Tickets Alert --}}
            <div id="similar-section" class="glass-card mb-4">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width:40px;height:40px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-search-plus" style="color:#f59e0b;"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0" style="color:#92400e;">Tiket Serupa Ditemukan</h6>
                            <small class="text-muted">Apakah masalah Anda sudah pernah dilaporkan? Cek dulu sebelum membuat tiket baru.</small>
                        </div>
                    </div>
                    <div id="similar-list"></div>
                </div>
            </div>

            {{-- Form --}}
            <div class="glass-card">
                <div class="p-4 p-md-5">
                    <h5 class="font-weight-bold mb-4" style="color:#1e293b;"><i class="fas fa-edit mr-2 text-primary"></i>Detail Laporan</h5>

                    @if($errors->any())
                    <div class="alert border-0 mb-4" style="border-radius:12px; background:#fee2e2; color:#991b1b;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Terdapat {{ $errors->count() }} kesalahan:</strong>
                        <ul class="mb-0 mt-2 pl-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('user.tickets.store') }}" method="POST">
                        @csrf

                        {{-- Subject --}}
                        <div class="form-group mb-4">
                            <label class="form-label-custom">Judul Kendala <span class="text-danger">*</span></label>
                            <input type="text"
                                name="subject"
                                id="subject-input"
                                class="form-control form-control-custom @error('subject') is-invalid @enderror"
                                value="{{ old('subject') }}"
                                placeholder="Contoh: Gagal scan QR Code saat absen WFO"
                                autocomplete="off"
                                required>
                            <div id="search-loading" class="mt-2 small text-muted" style="display:none;">
                                <span class="spinner-border spinner-border-sm spinner-search mr-1"></span> Mencari tiket serupa...
                            </div>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Priority --}}
                        <div class="form-group mb-4">
                            <label class="form-label-custom">Tingkat Prioritas <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-4">
                                    <input type="radio" name="priority" id="p-low" value="low" class="priority-option" {{ old('priority','low') == 'low' ? 'checked' : '' }}>
                                    <label for="p-low" class="priority-label w-100">
                                        <i class="fas fa-arrow-down d-block mb-1" style="color:#64748b;"></i>
                                        Rendah
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" name="priority" id="p-mid" value="mid" class="priority-option" {{ old('priority') == 'mid' ? 'checked' : '' }}>
                                    <label for="p-mid" class="priority-label w-100">
                                        <i class="fas fa-minus d-block mb-1" style="color:#f59e0b;"></i>
                                        Sedang
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" name="priority" id="p-high" value="high" class="priority-option" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                    <label for="p-high" class="priority-label w-100">
                                        <i class="fas fa-arrow-up d-block mb-1" style="color:#ef4444;"></i>
                                        Tinggi
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group mb-4">
                            <label class="form-label-custom">Deskripsi Lengkap <span class="text-danger">*</span></label>
                            <textarea
                                name="description"
                                class="form-control form-control-custom @error('description') is-invalid @enderror"
                                rows="6"
                                placeholder="Jelaskan kendala secara detail: kapan terjadi, langkah yang sudah dicoba, pesan error yang muncul, dll."
                                required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-2">
                            <a href="{{ route('user.tickets.index') }}" class="btn btn-light" style="border-radius:12px; padding:12px 24px; font-weight:600;">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimer = null;

    document.getElementById('subject-input').addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(searchTimer);

        if (q.length < 4) {
            document.getElementById('similar-section').style.display = 'none';
            return;
        }

        document.getElementById('search-loading').style.display = 'block';

        searchTimer = setTimeout(function() {
            fetch(`{{ route('user.tickets.search') }}?q=` + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('search-loading').style.display = 'none';
                const section = document.getElementById('similar-section');
                const list    = document.getElementById('similar-list');

                if (data.length === 0) {
                    section.style.display = 'none';
                    return;
                }

                const labels = { open: 'OPEN', in_progress: 'IN PROGRESS', closed: 'CLOSED' };
                const colors = {
                    open:        '#fef3c7;color:#92400e',
                    in_progress: '#dbeafe;color:#1e40af',
                    closed:      '#dcfce7;color:#166534',
                };

                list.innerHTML = data.map(t => `
                    <a href="/tickets/${t.id}" target="_blank" class="similar-ticket-item text-decoration-none d-flex align-items-center">
                        <span class="code">${t.ticket_code}</span>
                        <span class="subject flex-1 mx-3">${t.subject}</span>
                        <span class="badge" style="background:${colors[t.status]};font-size:10px;">${labels[t.status]||t.status}</span>
                    </a>
                `).join('');

                section.style.display = 'block';
            })
            .catch(() => {
                document.getElementById('search-loading').style.display = 'none';
            });
        }, 500);
    });
</script>
@endpush
