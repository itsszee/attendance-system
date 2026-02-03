<!DOCTYPE html>
<html>
<head>
    <title>WFO Check-in</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        h2 { color: #333; }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
        #qr-reader { width: 100%; max-width: 500px; margin: 20px auto; }
        #qr-reader__dashboard_section_codeFormat { display: none; }
        input[type="hidden"] { display: none; }
        button, input[type="button"] { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
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

<form method="POST" action="{{ route('attendance.wfo.store') }}" id="wfo-form">
    @csrf

    <div id="qr-reader"></div>

    <input type="hidden" name="qr_code" id="qr_code">
    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">

    <br><br>
    <button type="submit" id="submit-btn">Check-in</button>
</form>

<script>
const latInput = document.getElementById('lat');
const lngInput = document.getElementById('lng');
const qrInput = document.getElementById('qr_code');
const form = document.getElementById('wfo-form');
const submitBtn = document.getElementById('submit-btn');

// Get location
function setPosition(pos){
    latInput.value = pos.coords.latitude;
    lngInput.value = pos.coords.longitude;
}

function geoError(err){
    alert('Lokasi wajib diaktifkan untuk check-in WFO');
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition, geoError, { enableHighAccuracy: true });
} else {
    alert('Geolocation tidak didukung pada browser ini');
}

// QR Scanner
function onScanSuccess(decodedText, decodedResult) {
    qrInput.value = decodedText;
    html5QrcodeScanner.pause();
    alert('QR Code terdeteksi: ' + decodedText);
    submitBtn.focus();
}

function onScanFailure(error) {
    // Handle scan error - usually just continue scanning
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

// Validate form before submit
form.addEventListener('submit', function(e){
    if (!latInput.value || !lngInput.value) {
        e.preventDefault();
        alert('Mohon aktifkan lokasi sebelum submit absen WFO');
    }
    if (!qrInput.value) {
        e.preventDefault();
        alert('Mohon scan QR code sebelum submit');
    }
});
</script>

</body>
</html>
