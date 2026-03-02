

<!DOCTYPE html>
<html>
<head>
    <title>WFO Check-in</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 0 20px; }
        h2 { color: #333; }
        .error { color: red; margin: 10px 0; padding: 10px; background: #f8d7da; border-radius: 4px; }
        .success { color: green; margin: 10px 0; padding: 10px; background: #d4edda; border-radius: 4px; }
        .status { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .status.loading { background: #fff3cd; color: #856404; }
        .status.ready { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        #qr-reader { width: 100%; max-width: 500px; margin: 20px auto; }
        #qr-reader__dashboard_section_codeFormat { display: none; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
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
// office config from backend
const officeLat = {{ $location ? $location->latitude : 'null' }};
const officeLng = {{ $location ? $location->longitude : 'null' }};
const officeRadius = {{ $location ? $location->radius : 'null' }}; // meters

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

// calculate distance in meters between two lat/lng points
function haversineDistance(lat1, lon1, lat2, lon2) {
    function toRad(x) { return x * Math.PI / 180; }
    var R = 6371000; // metres
    var dLat = toRad(lat2 - lat1);
    var dLon = toRad(lon2 - lon1);
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Get location
function setPosition(pos) {
    const userLat = pos.coords.latitude;
    const userLng = pos.coords.longitude;

    latInput.value = userLat;
    lngInput.value = userLng;
    locationReady = true;

    // compute distance to office if config available
    if (officeLat !== null && officeLng !== null && officeRadius !== null) {
        const dist = haversineDistance(userLat, userLng, officeLat, officeLng);
        if (dist > officeRadius) {
            locationStatus.textContent = '❌ Di luar area (' + dist.toFixed(0) + ' m > ' + officeRadius + ' m)';
            locationStatus.className = 'status error';
            // disable submit (scanner not yet initialized)
            submitBtn.disabled = true;
            return; // do not enable
        }
    }

    // inside allowed range -> start scanner
    initScanner();

    locationStatus.textContent = '✅ Lokasi: ' + userLat.toFixed(4) + ', ' + userLng.toFixed(4);
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
    html5QrcodeScanner.clear(); // Stop scanner setelah scan
    
    qrStatus.textContent = '✅ QR Code: ' + decodedText;
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

let html5QrcodeScanner;
function initScanner() {
    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { 
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            },
            false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }
}


// Form submit handling
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
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses...';
});

// ✅ AUTO-REFRESH CSRF TOKEN - PENTING! 
setInterval(function() {
    fetch('{{ route('attendance.wfo.form') }}')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('input[name="_token"]');
            
            if (newToken) {
                document.querySelector('input[name="_token"]').value = newToken.value;
                console.log('✅ CSRF token refreshed');
            }
        })
        .catch(err => console.error('Failed to refresh token:', err));
}, 5 * 60 * 1000); 
</script>

</body>
</html>