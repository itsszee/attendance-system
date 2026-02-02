@if($alreadyAbsent)
    <div class="alert alert-info">
        Kamu sudah absen hari ini ✅
    </div>

    @if($attendance->check_out_at)
        <p>Check-out: {{ $attendance->check_out_at }}</p>
    @else
        <form method="POST" action="/attendance/check-out">
            @csrf
            <button type="submit">
                Check-Out
            </button>
        </form>
    @endif
@else

<form method="POST" enctype="multipart/form-data">
    @csrf

    <textarea name="task" placeholder="Task hari ini" required></textarea>

    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">

    <input type="file" name="selfie" accept="image/*" required>

    <button type="submit">Absen WFH</button>
</form>
@endif

<script>
navigator.geolocation.getCurrentPosition(pos => {
    document.getElementById('lat').value = pos.coords.latitude;
    document.getElementById('lng').value = pos.coords.longitude;
});
</script>
