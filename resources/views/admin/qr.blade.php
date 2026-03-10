@extends('layouts.admin')

@section('title', 'QR Code Management')
@section('page-title', 'QR Code Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">QR Code Management</li>
@endsection

@section('content')



    <!-- Auto-Generate QR Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sync-alt"></i> Auto-Generate QR Code
                    </h3>
                </div>
                <div class="card-body text-center">
                    @if ($activeQr && $activeQr->auto_generate)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Auto-generate AKTIF - QR berganti setiap 5 menit
                        </div>

                        <div id="qr-display">
                            <img src="{{ asset('storage/' . $activeQr->qr_image_path) }}" alt="QR Code"
                                class="img-fluid rounded shadow" style="max-width: 300px;" id="qr-image">

                            <p class="mt-3">
                                <strong>Code:</strong>
                                <span class="badge badge-lg badge-primary" id="qr-code">{{ $activeQr->code }}</span>
                            </p>

                            <div class="countdown-box mt-3">
                                <h3 class="text-primary">Sisa waktu:</h3>
                                <h1 id="countdown" class="display-4 text-bold">Loading...</h1>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.qr.stop') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-stop"></i> Stop Auto-Generate
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-pause-circle"></i> Auto-generate TIDAK AKTIF
                        </div>

                        <i class="fas fa-qrcode fa-5x text-muted mb-4"></i>

                        <form method="POST" action="{{ route('admin.qr.start') }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-play"></i> Start Auto-Generate
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Manual Generate Section -->
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> Generate Manual (Single Use)
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.qr.generate') }}">
                        @csrf
                        <div class="form-group">
                            <label>Valid Minutes:</label>
                            <input type="number" name="minutes" class="form-control" min="1" max="60"
                                value="10" placeholder="Enter minutes">
                            <small class="form-text text-muted">
                                QR code akan valid selama waktu yang ditentukan
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-qrcode"></i> Generate QR Code
                        </button>
                    </form>

                    <div class="info-box bg-light mt-4">
                        <div class="info-box-content">
                            <span class="info-box-text">Info</span>
                            <span class="info-box-number">
                                Manual QR untuk kebutuhan khusus atau sementara
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i> Recent QR Codes (Last 50)
            </h3>
        </div>

        <!-- Filter Form -->
        <div class="card-header border-0">
            <form method="GET" action="{{ route('admin.qr.index') }}" class="form-inline">
                <div class="form-group mr-3">
                    <label for="status" class="mr-2">Status:</label>
                    <select name="status" id="status" class="form-control form-control-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <div class="form-group mr-3">
                    <label for="auto" class="mr-2">Auto Generate:</label>
                    <select name="auto" id="auto" class="form-control form-control-sm">
                        <option value="">All Types</option>
                        <option value="yes" {{ request('auto') == 'yes' ? 'selected' : '' }}>Auto</option>
                        <option value="no" {{ request('auto') == 'no' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <a href="{{ route('admin.qr.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="p-3">
                <strong>Total QR Codes: {{ $codes->count() }}</strong>
                @if (request('status') || request('auto'))
                    <span class="text-muted ml-3">
                        Filtered by:
                        @if (request('status'))
                            Status: {{ request('status') }}
                        @endif
                        @if (request('status') && request('auto'))
                            |
                        @endif
                        @if (request('auto'))
                            Type: {{ request('auto') == 'yes' ? 'Auto' : 'Manual' }}
                        @endif
                    </span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>QR Image</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                            <th>Auto</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($codes as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>
                                    <code>{{ $c->code }}</code>
                                </td>
                                <td>
                                    @if ($c->qr_image_path)
                                        <img src="{{ asset('storage/' . $c->qr_image_path) }}" alt="QR"
                                            class="img-thumbnail" style="max-width: 80px;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $c->valid_from->format('d M Y H:i') }}</td>
                                <td>{{ $c->valid_until->format('d M Y H:i') }}</td>
                                <td>
                                    @if ($c->is_active && $c->valid_until > now())
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times"></i> Expired
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($c->auto_generate)
                                        <span class="badge badge-primary">
                                            <i class="fas fa-sync-alt"></i> Auto
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($c->creator)->name ?? $c->created_by }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No QR codes generated yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Polling untuk update QR otomatis
        @if ($activeQr && $activeQr->auto_generate)
            let countdownInterval;

            function updateQR() {
                fetch('{{ route('admin.qr.active') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('qr-image').src = data.image_url + '?t=' + Date.now();
                            document.getElementById('qr-code').textContent = data.code;
                            startCountdown(data.seconds_remaining);
                        } else {
                            // Auto-generate stopped
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            function startCountdown(seconds) {
                clearInterval(countdownInterval);
                let remaining = seconds;

                countdownInterval = setInterval(() => {
                    remaining--;

                    if (remaining <= 0) {
                        document.getElementById('countdown').textContent = 'Refreshing...';
                        document.getElementById('countdown').className = 'display-4 text-bold text-warning';
                        updateQR();
                        return;
                    }

                    const minutes = Math.floor(remaining / 60);
                    const secs = remaining % 60;
                    const timeString = `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    document.getElementById('countdown').textContent = timeString;

                    // Change color when time is running out
                    if (remaining <= 30) {
                        document.getElementById('countdown').className = 'display-4 text-bold text-danger';
                    } else {
                        document.getElementById('countdown').className = 'display-4 text-bold text-primary';
                    }
                }, 1000);
            }

            // Initial countdown
            const validUntil = new Date('{{ $activeQr->valid_until->toIso8601String() }}');
            const now = new Date();
            const initialSeconds = Math.floor((validUntil - now) / 1000);
            startCountdown(initialSeconds);

            // Poll every 10 seconds untuk cek QR baru
            setInterval(updateQR, 10000);
        @endif
    </script>
@endpush

@push('styles')
    <style>
        .countdown-box {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
    </style>
@endpush
