<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WFH Check-in</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .back-btn {
            background: white;
            border: 2px solid #e0e0e0;
            color: #667eea;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 5px 0;
        }

        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }

        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .info-item strong {
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }

        .selfie-preview {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            max-width: 100%;
            margin-top: 10px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .location-status {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .location-status.loading {
            background: #fff3cd;
            color: #856404;
        }

        .location-status.ready {
            background: #d4edda;
            color: #155724;
        }

        .location-status.error {
            background: #f8d7da;
            color: #721c24;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafafa;
            resize: vertical;
            min-height: 100px;
        }

        textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-label {
            padding: 14px;
            background: #fafafa;
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .file-input-label i {
            font-size: 24px;
            color: #667eea;
        }

        input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .file-name {
            font-size: 14px;
            color: #666;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        @media (max-width: 480px) {
            .container {
                padding: 0;
            }

            .header, .card {
                padding: 20px;
            }

            .header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- Back Button -->
    <a href="{{ route('dashboard') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Dashboard
    </a>

    <!-- Header -->
    <div class="header">
        <h2>
            <i class="fas fa-home"></i>
            WFH Check-in
        </h2>
        <p>Work From Home - {{ now()->format('l, d F Y') }}</p>
    </div>

    <!-- Already Checked In -->
    @if(isset($attendance) && $attendance)
        <div class="card">
            <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 20px;">
                <i class="fas fa-check-circle" style="color: #28a745;"></i>
                Sudah Check-in
            </h3>

            <div class="info-item">
                <strong><i class="fas fa-clock"></i> Waktu Check-in</strong>
                {{ $attendance->check_in_at->format('H:i:s') }}
            </div>

            <div class="info-item">
                <strong><i class="fas fa-traffic-light"></i> Status</strong>
                <span class="status-badge {{ $attendance->status == 'on_time' ? 'success' : 'warning' }}">
                    {{ $attendance->status == 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                </span>
            </div>

            <div class="info-item">
                <strong><i class="fas fa-clipboard-check"></i> Approval Status</strong>
                <span class="status-badge {{ $attendance->approval_status == 'approved' ? 'success' : 'warning' }}">
                    {{ ucfirst($attendance->approval_status) }}
                </span>
            </div>

            @if($attendance->task)
            <div class="info-item">
                <strong><i class="fas fa-tasks"></i> Task Hari Ini</strong>
                {{ $attendance->task }}
            </div>
            @endif

            @if($attendance->selfie_path)
            <div class="info-item">
                <strong><i class="fas fa-camera"></i> Selfie</strong>
                <img src="{{ asset('storage/' . $attendance->selfie_path) }}" 
                     alt="Selfie" 
                     class="selfie-preview">
            </div>
            @endif
        </div>
    @else
        <!-- Check-in Form -->
        <div class="card">
            
            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div id="location-status" class="location-status loading">
                <i class="fas fa-spinner fa-spin"></i>
                Mendapatkan lokasi...
            </div>

            <form method="POST" action="{{ route('attendance.wfh.store') }}" enctype="multipart/form-data" id="wfh-form">
                @csrf

                <div class="form-group">
                    <label for="task">
                        <i class="fas fa-tasks"></i> Task Hari Ini
                    </label>
                    <textarea name="task" 
                              id="task" 
                              placeholder="Deskripsikan task yang akan dikerjakan hari ini..."
                              required></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-camera"></i> Selfie Check-in
                    </label>
                    <div class="file-input-wrapper">
                        <label for="selfie" class="file-input-label">
                            <i class="fas fa-camera"></i>
                            <span class="file-name" id="file-name">Pilih foto atau ambil selfie</span>
                        </label>
                        <input type="file" 
                               name="selfie" 
                               id="selfie" 
                               accept="image/*" 
                               capture="user"
                               required>
                    </div>
                </div>

                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="lng">

                <button type="submit" class="submit-btn" id="submit-btn" disabled>
                    <i class="fas fa-sign-in-alt"></i>
                    Check-in Sekarang
                </button>
            </form>
        </div>
    @endif

</div>

@if(!isset($attendance) || !$attendance)
<script>
    const form = document.getElementById('wfh-form');
    const submitBtn = document.getElementById('submit-btn');
    const status = document.getElementById('location-status');
    const fileInput = document.getElementById('selfie');
    const fileName = document.getElementById('file-name');

    // File input preview
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            fileName.textContent = e.target.files[0].name;
        }
    });

    // Get location
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('lng').value = pos.coords.longitude;
            
            // Enable button setelah dapet lokasi
            submitBtn.disabled = false;
            status.className = 'location-status ready';
            status.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi terdeteksi: ' + 
                               pos.coords.latitude.toFixed(4) + ', ' + 
                               pos.coords.longitude.toFixed(4);
        },
        function(error) {
            status.className = 'location-status error';
            status.innerHTML = '<i class="fas fa-times-circle"></i> Gagal mendapatkan lokasi. Aktifkan GPS!';
            alert('Lokasi wajib diaktifkan untuk check-in WFH');
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );

    // Validate sebelum submit
    form.addEventListener('submit', function(e) {
        if (!document.getElementById('lat').value || !document.getElementById('lng').value) {
            e.preventDefault();
            alert('Tunggu lokasi terdeteksi dulu!');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    });

    // Auto-refresh CSRF token every 5 minutes
    setInterval(function() {
        fetch('{{ route('attendance.wfh.form') }}')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('input[name="_token"]');
                
                if (newToken) {
                    document.querySelector('input[name="_token"]').value = newToken.value;
                }
            })
            .catch(err => console.error('Failed to refresh token:', err));
    }, 5 * 60 * 1000);
</script>
@endif

</body>
</html>