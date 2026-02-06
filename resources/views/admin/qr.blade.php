<!DOCTYPE html>
<html>
<head>
    <title>Admin - QR Codes</title>
    <style>
        body { font-family: Arial; max-width: 1200px; margin: 20px auto; padding: 20px; }
        .qr-container { background: #f8f9fa; padding: 30px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .qr-img { max-width: 300px; height: auto; border: 2px solid #ddd; border-radius: 8px; }
        .controls { margin: 20px 0; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.9; }
        .status { padding: 15px; border-radius: 4px; margin: 10px 0; }
        .status.active { background: #d4edda; color: #155724; }
        .status.inactive { background: #f8d7da; color: #721c24; }
        .countdown { font-size: 24px; font-weight: bold; color: #007bff; margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .qr-img-small { max-width: 100px; height: auto; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>QR Code Management</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <!-- Auto-Generate Section -->
    <div class="qr-container">
        <h2>Auto-Generate QR Code</h2>
        
        @if($activeQr && $activeQr->auto_generate)
            <div class="status active">
                ✅ Auto-generate AKTIF - QR berganti setiap 5 menit
            </div>

            <div id="qr-display">
                <img src="{{ asset('storage/' . $activeQr->qr_image_path) }}" alt="QR Code" class="qr-img" id="qr-image">
                <p><strong>Code:</strong> <span id="qr-code">{{ $activeQr->code }}</span></p>
                <p class="countdown">Sisa waktu: <span id="countdown">Loading...</span></p>
            </div>

            <form method="POST" action="{{ route('admin.qr.stop') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Stop Auto-Generate</button>
            </form>
        @else
            <div class="status inactive">
                ⏸️ Auto-generate TIDAK AKTIF
            </div>

            <form method="POST" action="{{ route('admin.qr.start') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success">Start Auto-Generate</button>
            </form>
        @endif
    </div>

    <!-- Manual Generate Section -->
    <div class="controls">
        <h3>Generate Manual (Single Use)</h3>
        <form method="POST" action="{{ route('admin.qr.generate') }}" style="display: inline;">
            @csrf
            <label>Valid minutes:</label>
            <input type="number" name="minutes" min="1" max="60" value="10" style="width: 60px;">
            <button type="submit" class="btn btn-primary">Generate Manual</button>
        </form>
    </div>

    <!-- History Table -->
    <h2>Recent Codes (Last 50)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>QR Image</th>
                <th>Valid From</th>
                <th>Valid Until</th>
                <th>Active</th>
                <th>Auto</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($codes as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->code }}</td>
                    <td>
                        @if($c->qr_image_path)
                            <img src="{{ asset('storage/' . $c->qr_image_path) }}" alt="QR" class="qr-img-small">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $c->valid_from }}</td>
                    <td>{{ $c->valid_until }}</td>
                    <td>{{ $c->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $c->auto_generate ? '🔄' : '-' }}</td>
                    <td>{{ optional($c->creator)->name ?? $c->created_by }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No codes</td></tr>
            @endforelse
        </tbody>
    </table>

    <script>
        // Polling untuk update QR otomatis
        @if($activeQr && $activeQr->auto_generate)
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
                    updateQR();
                    return;
                }

                const minutes = Math.floor(remaining / 60);
                const secs = remaining % 60;
                document.getElementById('countdown').textContent = 
                    `${minutes}:${secs.toString().padStart(2, '0')}`;
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

</body>
</html>