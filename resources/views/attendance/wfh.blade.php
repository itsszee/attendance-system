@extends('layouts.user')

@section('title', 'WFH Check-in')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #6366f1;
        --secondary: #64748b;
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
    }

    .info-item {
        padding: 15px;
        background: #f1f5f9;
        border-radius: 12px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
    }

    .info-item strong {
        color: var(--primary);
        display: block;
        font-size: 13px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 30px;
    }

    .location-status {
        padding: 12px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid transparent;
    }

    .location-status.loading { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .location-status.ready { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .location-status.error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

    .btn-premium {
        background: var(--primary);
        color: white;
        border: 0;
        border-radius: 12px;
        padding: 14px 20px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        box-shadow: var(--shadow);
    }

    .btn-premium:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .btn-premium:disabled { background: #cbd5e1; cursor: not-allowed; box-shadow: none; }

    .btn-retake { background: #f59e0b; color: white; }
    .btn-retake:hover { background: #d97706; color: white; }

    textarea.form-control {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 15px;
        background: #f8fafc;
        resize: none;
    }

    textarea.form-control:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    #camera-container {
        border-radius: 15px;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: var(--shadow);
        background: #000;
    }

    .selfie-preview-img {
        border-radius: 15px;
        width: 100%;
        border: 4px solid white;
        box-shadow: var(--shadow);
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold mb-1">WFH Check-in</h1>
                <p class="mb-0 opacity-75">{{ now()->format('l, d F Y') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                <i class="fas fa-chevron-left mr-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="container page-content">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            @if(isset($attendanceToday) && $attendanceToday && $attendanceToday->mode == 'WFH')
                <div class="glass-card text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    <h3 class="font-weight-bold mb-4">Sudah Check-in</h3>

                    <div class="row text-left">
                        <div class="col-6">
                            <div class="info-item">
                                <strong><i class="fas fa-clock"></i> Waktu</strong>
                                {{ $attendanceToday->check_in_at->format('H:i') }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <strong><i class="fas fa-traffic-light"></i> Status</strong>
                                <span class="status-badge text-primary" style="background: rgba(99, 102, 241, 0.1);">
                                    {{ $attendanceToday->status == 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($attendanceToday->selfie_path)
                    <div class="mt-4">
                        <strong class="d-block mb-3 small text-muted text-uppercase">Foto Check-in</strong>
                        <img src="{{ asset('storage/' . $attendanceToday->selfie_path) }}" 
                             alt="Selfie" 
                             class="selfie-preview-img"
                             style="max-width: 300px;">
                    </div>
                    @endif
                </div>
            @else
                <div class="glass-card">
                    @if (session('error'))
                        <div class="alert alert-danger border-0 mb-4" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div id="location-status" class="location-status loading mb-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        Mendapatkan lokasi...
                    </div>

                    <form method="POST" action="{{ route('attendance.wfh.store') }}" id="wfh-form">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted text-uppercase">
                                <i class="fas fa-tasks mr-1"></i> Task Hari Ini
                            </label>
                            <textarea name="task" id="task" class="form-control" rows="3" placeholder="Apa yang akan Anda kerjakan hari ini?" required>{{ old('task') }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted text-uppercase">
                                <i class="fas fa-camera mr-1"></i> Selfie Check-in
                            </label>
                            
                            <div id="camera-container" style="position: relative; margin-bottom: 20px;">
                                <video id="video" width="100%" height="auto" autoplay playsinline style="display: block;"></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                                
                                <div id="camera-overlay" style="position: absolute; bottom: 15px; left: 15px; color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.8); font-size: 11px; font-weight: 500; pointer-events: none;">
                                    <div id="overlay-date"></div>
                                    <div id="overlay-location">Menunggu GPS...</div>
                                </div>
                            </div>

                            <div id="preview-container" style="display: none; margin-bottom: 20px;">
                                <img id="selfie-preview-img" src="" class="selfie-preview-img">
                            </div>

                            <button type="button" id="capture-btn" class="btn-premium">
                                <i class="fas fa-camera"></i> Ambil Foto
                            </button>
                            
                            <button type="button" id="retake-btn" class="btn-premium btn-retake" style="display: none;">
                                <i class="fas fa-redo"></i> Foto Ulang
                            </button>
                            
                            <input type="hidden" name="selfie" id="selfie-data">
                        </div>

                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="lng">

                        <button type="submit" class="btn-premium py-3" id="submit-btn" disabled>
                            <i class="fas fa-sign-in-alt"></i>
                            Check-in Sekarang
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@if(!isset($attendanceToday) || !$attendanceToday)
<script>
    const form = document.getElementById('wfh-form');
    const submitBtn = document.getElementById('submit-btn');
    const status = document.getElementById('location-status');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('capture-btn');
    const retakeBtn = document.getElementById('retake-btn');
    const selfieData = document.getElementById('selfie-data');
    const cameraContainer = document.getElementById('camera-container');
    const previewContainer = document.getElementById('preview-container');
    const previewImg = document.getElementById('selfie-preview-img');
    const overlayDate = document.getElementById('overlay-date');
    const overlayLocation = document.getElementById('overlay-location');

    let currentLat = null;
    let currentLng = null;

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'user' }, 
                audio: false 
            });
            video.srcObject = stream;
        } catch (err) {
            console.error("Camera error:", err);
            status.className = 'location-status error';
            status.innerHTML = '<i class="fas fa-times-circle"></i> Gagal mengakses kamera. Mohon izinkan akses kamera.';
        }
    }

    startCamera();

    function updateOverlayTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        overlayDate.innerText = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateOverlayTime, 1000);
    updateOverlayTime();

    captureBtn.addEventListener('click', () => {
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const fontSize = Math.floor(canvas.width / 35);
        ctx.font = `bold ${fontSize}px 'Outfit', sans-serif`;
        ctx.fillStyle = "white";
        ctx.textAlign = "left";
        ctx.shadowColor = "rgba(0, 0, 0, 0.8)";
        ctx.shadowBlur = 4;
        ctx.shadowOffsetX = 2;
        ctx.shadowOffsetY = 2;

        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        const timeStr = now.toLocaleTimeString('id-ID');
        const locStr = currentLat ? `Lat: ${currentLat.toFixed(6)}, Lng: ${currentLng.toFixed(6)}` : "GPS: Unknown";

        ctx.fillStyle = "rgba(0,0,0,0.4)";
        ctx.fillRect(0, canvas.height - (fontSize * 4), canvas.width, fontSize * 4);
        
        ctx.fillStyle = "white";
        ctx.fillText(dateStr + " " + timeStr, 20, canvas.height - (fontSize * 2.3));
        ctx.fillText(locStr, 20, canvas.height - (fontSize * 0.8));

        const data = canvas.toDataURL('image/jpeg', 0.85);
        selfieData.value = data;
        previewImg.src = data;
        
        cameraContainer.style.display = 'none';
        previewContainer.style.display = 'block';
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'flex';
        
        checkSubmitStatus();
    });

    retakeBtn.addEventListener('click', () => {
        cameraContainer.style.display = 'block';
        previewContainer.style.display = 'none';
        captureBtn.style.display = 'flex';
        retakeBtn.style.display = 'none';
        selfieData.value = '';
        checkSubmitStatus();
    });

    function checkSubmitStatus() {
        submitBtn.disabled = !(currentLat && currentLng && selfieData.value);
    }

    navigator.geolocation.getCurrentPosition(
        function(pos) {
            currentLat = pos.coords.latitude;
            currentLng = pos.coords.longitude;
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            if(latInput) latInput.value = currentLat;
            if(lngInput) lngInput.value = currentLng;
            if(overlayLocation) overlayLocation.innerText = `GPS: ${currentLat.toFixed(6)}, ${currentLng.toFixed(6)}`;
            if(status) {
                status.className = 'location-status ready';
                status.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi terdeteksi secara akurat';
            }
            checkSubmitStatus();
        },
        function(error) {
            if(status) {
                status.className = 'location-status error';
                status.innerHTML = '<i class="fas fa-exclamation-circle"></i> Aktifkan GPS untuk melanjutkan check-in.';
            }
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );

    if(form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });
    }
</script>
@endif
@endsection