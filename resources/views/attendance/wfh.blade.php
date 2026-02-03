<!DOCTYPE html>
<html>

<head>
    <title>WFH Check-in</title>
</head>

<body>

    <h2>WFH Check-in</h2>

    <form method="POST" action="{{ route('attendance.wfh.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Task Hari Ini</label><br>
        <textarea name="task" required></textarea><br><br>

        <label>Selfie</label><br>
        <input type="file" name="selfie" accept="image/*" required><br><br>

        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lng">

        <button type="submit">Check-in</button>
    </form>

    <script>
        // Ambil posisi pengguna dan isi hidden input
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
            },
            function(error) {
                alert('Lokasi wajib diaktifkan untuk check-in WFH');
            },
            { enableHighAccuracy: true }
        );
    </script>

    @if (session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif


</body>

</html>
