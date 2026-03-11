@extends('layouts.user')

@section('title', 'WFO Check-in')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
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
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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

    .status-box {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        border: 1px solid transparent;
    }

    .status-box.loading { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .status-box.ready { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .status-box.error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

    .qr-section {
        margin: 25px 0;
    }

    .qr-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.5px;
    }

    #qr-reader {
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: var(--shadow);
    }

    #qr-reader__dashboard_section_codeFormat { display: none; }

    .btn-premium {
        background: var(--primary);
        color: white;
        border: 0;
        border-radius: 12px;
        padding: 16px 20px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        box-shadow: var(--shadow);
        font-size: 16px;
    }

    .btn-premium:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .btn-premium:disabled { background: #cbd5e1; cursor: not-allowed; box-shadow: none; }

    .info-note {
        background: #f1f5f9;
        padding: 15px;
        border-radius: 12px;
        border-left: 4px solid var(--primary);
        margin-top: 25px;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold mb-1">WFO Check-in</h1>
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
            
            @if (session('error'))
                <div class="alert alert-danger border-0 mb-4 shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success border-0 mb-4 shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="glass-card">
                <!-- Location Status -->
                <div id="location-status" class="status-box loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    Sedang mendeteksi lokasi kantor...
                </div>

                <!-- QR Status -->
                <div id="qr-status" class="status-box loading">
                    <i class="fas fa-qrcode"></i>
                    Siapkan QR Code kantor untuk scan
                </div>

                <!-- Scanner Section -->
                <div class="qr-section">
                    <div class="qr-title">
                        <i class="fas fa-camera"></i> Scan Quick Response Code
                    </div>
                    <div id="qr-reader"></div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('attendance.wfo.store') }}" id="wfo-form">
                    @csrf
                    <input type="hidden" name="qr_code" id="qr_code">
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">

                    <button type="submit" class="btn-premium" id="submit-btn" disabled>
                        <i class="fas fa-sign-in-alt"></i>
                        Check-in Sekarang
                    </button>
                </form>

                <!-- Note -->
                <div class="info-note">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Penting:</strong> Anda harus berada dalam radius kantor yang ditentukan agar tombol check-in aktif. Pastikan GPS Anda aktif dan akurat.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const latInput = document.getElementById('lat');
const lngInput = document.getElementById('lng');
const qrInput = document.getElementById('qr_code');
const form = document.getElementById('wfo-form');
const submitBtn = document.getElementById('submit-btn');
const locationStatus = document.getElementById('location-status');
const qrStatus = document.getElementById('qr-status');

const officeLat = {{ $location ? $location->latitude : 'null' }};
const officeLng = {{ $location ? $location->longitude : 'null' }};
const officeRadius = {{ $location ? $location->radius : 'null' }};

let locationReady = false;
let qrReady = false;
let html5QrcodeScanner;

function checkReady() {
    if (locationReady && qrReady) {
        submitBtn.disabled = false;
    }
}

function haversineDistance(lat1, lon1, lat2, lon2) {
    function toRad(x) { return x * Math.PI / 180; }
    var R = 6371000;
    var dLat = toRad(lat2 - lat1);
    var dLon = toRad(lon2 - lon1);
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function setPosition(pos) {
    const userLat = pos.coords.latitude;
    const userLng = pos.coords.longitude;

    latInput.value = userLat;
    lngInput.value = userLng;
    locationReady = true;

    if (officeLat !== null && officeLng !== null && officeRadius !== null) {
        const dist = haversineDistance(userLat, userLng, officeLat, officeLng);
        if (dist > officeRadius) {
            locationStatus.innerHTML = '<i class="fas fa-times-circle"></i> Di luar area (' + dist.toFixed(0) + 'm dari kantor)';
            locationStatus.className = 'status-box error';
            submitBtn.disabled = true;
            return;
        }
    }

    initScanner();
    locationStatus.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi Valid Terdeteksi';
    locationStatus.className = 'status-box ready';
    checkReady();
}

function geoError(err) {
    locationStatus.innerHTML = '<i class="fas fa-times-circle"></i> GPS tidak aktif / Gagal akses lokasi';
    locationStatus.className = 'status-box error';
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition, geoError, { 
        enableHighAccuracy: true,
        timeout: 10000
    });
}

function onScanSuccess(decodedText) {
    qrInput.value = decodedText;
    if(html5QrcodeScanner) html5QrcodeScanner.clear();
    qrStatus.innerHTML = '<i class="fas fa-check-circle"></i> QR Code Berhasil Di-scan';
    qrStatus.className = 'status-box ready';
    qrReady = true;
    checkReady();
}

function initScanner() {
    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 15, qrbox: 250 }
        );
        html5QrcodeScanner.render(onScanSuccess);
    }
}

form.addEventListener('submit', function() {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
});
</script>
@endsection