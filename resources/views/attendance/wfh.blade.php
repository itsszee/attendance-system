<!DOCTYPE html>
<html>

<head>
    <title>WFH Check-in</title>
</head>

<body>

    <h2>WFH Check-in</h2>

    @if (session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <p id="location-status">🔍 Mendapatkan lokasi...</p>

    <form method="POST" action="{{ route('attendance.wfh.store') }}" enctype="multipart/form-data" id="wfh-form">
        @csrf

        <label>Task Hari Ini</label><br>
        <textarea name="task" required></textarea><br><br>

        <label>Selfie</label><br>
        <input type="file" name="selfie" accept="image/*" required><br><br>

        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lng">

        <button type="submit" id="submit-btn" disabled>Check-in</button>
    </form>

    <script>
        const form = document.getElementById('wfh-form');
        const submitBtn = document.getElementById('submit-btn');
        const status = document.getElementById('location-status');

        // Get location dulu
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
                
                // Enable button setelah dapet lokasi
                submitBtn.disabled = false;
                status.textContent = '✅ Lokasi terdeteksi: ' + pos.coords.latitude.toFixed(4) + ', ' + pos.coords.longitude.toFixed(4);
                status.style.color = 'green';
            },
            function(error) {
                status.textContent = '❌ Gagal mendapatkan lokasi. Aktifkan GPS!';
                status.style.color = 'red';
                alert('Lokasi wajib diaktifkan untuk check-in WFH');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );

        // Validate sebelum submit
        form.addEventListener('submit', function(e) {
            if (!document.getElementById('lat').value || !document.getElementById('lng').value) {
                e.preventDefault();
                alert('Tunggu lokasi terdeteksi dulu!');
            }
        });
    </script>

</body>

</html>