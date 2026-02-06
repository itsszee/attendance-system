<!DOCTYPE html>
<html>
<head>
    <title>WFO Check-in</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 0 20px; }
        h2 { color: #333; }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
        .status { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .status.loading { background: #fff3cd; color: #856404; }
        .status.ready { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        #qr-reader { width: 100%; max-width: 500px; margin: 20px auto; }
        #qr-reader__dashboard_section_codeFormat { display: none; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover:not(:disabled) { background: #0056b3; }
        button:disabled { background: #6c757d; cursor: not-allowed; }
    </style>
</head>
<body>

<h2>WFO Check-in</h2>

@if (session('error'))
    <p class="error">{{ session('error') }}</p>
@endif

@if (session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

<div id="location-status" class="status loading">🔍 Mendapatkan lokasi...</div>
<div id="qr-status" class="status loading">📷 Siapkan QR Code untuk di-scan</div>

<form method="POST" action="{{ route('attendance.wfo.store') }}" id="wfo-form">
    @csrf

    <div id="qr-reader"></div>

    <input type="hidden" name="qr_code" id="qr_code">
    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">

    <br>
    <button type="submit" id="submit-btn" disabled>Check-in</button>
</form>

<script>
const latInput = document.getElementById('lat');
const lngInput = document.getElementById('lng');
const qrInput = document.getElementById('qr_code');
const form = document.getElementById('wfo-form');
const submitBtn = document.getElementById('submit-btn');
const locationStatus = document.getElementById('location-status');
const qrStatus = document.getElementById('qr-status');

let locationReady = false;
let qrReady = false;

function checkReady() {
    if (locationReady && qrReady) {
        submitBtn.disabled = false;
    }
}

// Get location
function setPosition(pos) {
    latInput.value = pos.coords.latitude;
    lngInput.value = pos.coords.longitude;
    locationReady = true;
    
    locationStatus.textContent = '✅ Lokasi terdeteksi: ' + pos.coords.latitude.toFixed(4) + ', ' + pos.coords.longitude.toFixed(4);
    locationStatus.className = 'status ready';
    
    checkReady();
}

function geoError(err) {
    locationStatus.textContent = '❌ Gagal mendapatkan lokasi. Aktifkan GPS!';
    locationStatus.className = 'status error';
    alert('Lokasi wajib diaktifkan untuk check-in WFO');
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition, geoError, { 
        enableHighAccuracy: true,
        timeout: 10000
    });
} else {
    alert('Geolocation tidak didukung pada browser ini');
}

// QR Scanner
function onScanSuccess(decodedText, decodedResult) {
    qrInput.value = decodedText;
    html5QrcodeScanner.pause();
    alert('QR Code terdeteksi: ' + decodedText);
    qrStatus.textContent = '✅ QR Code terdeteksi: ' + decodedText;
    qrStatus.className = 'status ready';
    
    qrReady = true;
    checkReady();
    
    if (locationReady) {
        submitBtn.focus();
    }
}

function onScanFailure(error) {
    // Continue scanning
}

const html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader",
    { 
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    },
    false
);

html5QrcodeScanner.render(onScanSuccess, onScanFailure);

form.addEventListener('submit', function(e) {
    if (!latInput.value || !lngInput.value) {
        e.preventDefault();
        alert('Mohon tunggu lokasi terdeteksi!');
        return;
    }
    if (!qrInput.value) {
        e.preventDefault();
        alert('Mohon scan QR code terlebih dahulu!');
        return;
    }
});
</script>

</body>
</html>