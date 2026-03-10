<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WFO Check-in</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
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

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        .status-box {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .status-box.loading {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .status-box.ready {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-box.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .qr-section {
            margin: 20px 0;
        }

        .qr-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #qr-reader {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        #qr-reader__dashboard_section_codeFormat {
            display: none;
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

        .info-note {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            border-left: 3px solid #667eea;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
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
            <i class="fas fa-building"></i>
            WFO Check-in
        </h2>
        <p>Work From Office - {{ now()->format('l, d F Y') }}</p>
    </div>

    <!-- Alerts -->
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

    <!-- Check-in Card -->
    <div class="card">
        
        <!-- Location Status -->
        <div id="location-status" class="status-box loading">
            <i class="fas fa-spinner fa-spin"></i>
            Mendapatkan lokasi Anda...
        </div>

        <!-- QR Status -->
        <div id="qr-status" class="status-box loading">
            <i class="fas fa-qrcode"></i>
            Siapkan QR Code untuk di-scan
        </div>

        <!-- QR Scanner Section -->
        <div class="qr-section">
            <h3>
                <i class="fas fa-camera"></i>
                Scan QR Code
            </h3>
            <div id="qr-reader"></div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('attendance.wfo.store') }}" id="wfo-form">
            @csrf

            <input type="hidden" name="qr_code" id="qr_code">
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lng">

            <button type="submit" class="submit-btn" id="submit-btn" disabled>
                <i class="fas fa-sign-in-alt"></i>
                Check-in Sekarang
            </button>
        </form>

        <!-- Info Note -->
        <div class="info-note">
            <i class="fas fa-info-circle"></i>
            Pastikan Anda berada di area kantor dan scan QR code yang valid untuk check-in WFO.
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

// Office config from backend
const officeLat = {{ $location ? $location->latitude : 'null' }};
const officeLng = {{ $location ? $location->longitude : 'null' }};
const officeRadius = {{ $location ? $location->radius : 'null' }}; // meters

let locationReady = false;
let qrReady = false;
let html5QrcodeScanner;

function checkReady() {
    if (locationReady && qrReady) {
        submitBtn.disabled = false;
    }
}

// Calculate distance in meters between two lat/lng points
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

    // Compute distance to office if config available
    if (officeLat !== null && officeLng !== null && officeRadius !== null) {
        const dist = haversineDistance(userLat, userLng, officeLat, officeLng);
        if (dist > officeRadius) {
            locationStatus.innerHTML = '<i class="fas fa-times-circle"></i> Anda di luar area kantor (' + 
                                       dist.toFixed(0) + 'm > ' + officeRadius + 'm)';
            locationStatus.className = 'status-box error';
            submitBtn.disabled = true;
            return;
        }
    }

    // Inside allowed range -> start scanner
    initScanner();

    locationStatus.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi terdeteksi: ' + 
                               userLat.toFixed(4) + ', ' + userLng.toFixed(4);
    locationStatus.className = 'status-box ready';

    checkReady();
}

function geoError(err) {
    locationStatus.innerHTML = '<i class="fas fa-times-circle"></i> Gagal mendapatkan lokasi. Aktifkan GPS!';
    locationStatus.className = 'status-box error';
    alert('Lokasi wajib diaktifkan untuk check-in WFO');
}

// Get geolocation
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
    html5QrcodeScanner.clear();
    
    qrStatus.innerHTML = '<i class="fas fa-check-circle"></i> QR Code terdeteksi: ' + decodedText;
    qrStatus.className = 'status-box ready';
    
    qrReady = true;
    checkReady();
    
    if (locationReady) {
        submitBtn.focus();
    }
}

function onScanFailure(error) {
    // Continue scanning
}

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
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
});

// Auto-refresh CSRF token every 5 minutes
setInterval(function() {
    fetch('{{ route('attendance.wfo.form') }}')
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

</body>
</html>