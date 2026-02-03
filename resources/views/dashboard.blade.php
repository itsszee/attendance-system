<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Halo, {{ auth()->user()->name }}</h2>

@if ($attendanceToday)
    <p><strong>Status hari ini:</strong> {{ $attendanceToday->status }}</p>

    @if (!$attendanceToday->check_out_at)
        <form method="POST" action="{{ route('attendance.checkout') }}">
            @csrf
            <button type="submit">Check Out</button>
        </form>
    @else
        <p>✅ Sudah check-out</p>
    @endif
@else
    <a href="{{ route('attendance.wfh.form') }}">Check-in WFH</a>
    <br><br>
    <a href="{{ route('attendance.wfo.form') }}">Check-in WFO</a>
@endif

@if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif


</body>
</html>
