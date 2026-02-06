<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logout-btn { background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .logout-btn:hover { background: #c82333; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin: 10px 0; }
        .info-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>

<div class="header">
    <h2>Halo, {{ auth()->user()->name }}</h2>
    
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

@if (session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

<div class="info-box">
    @if ($attendanceToday)
        <p><strong>Status hari ini:</strong> {{ $attendanceToday->status == 'on_time' ? '✅ Tepat Waktu' : '⚠️ Terlambat' }}</p>
        <p><strong>Mode:</strong> {{ $attendanceToday->mode }}</p>
        <p><strong>Check-in:</strong> {{ $attendanceToday->check_in_at->format('H:i:s') }}</p>
        
        @if (!$attendanceToday->check_out_at)
            <form method="POST" action="{{ route('attendance.checkout') }}">
                @csrf
                <button type="submit" class="btn btn-success">Check Out</button>
            </form>
        @else
            <p><strong>Check-out:</strong> {{ $attendanceToday->check_out_at->format('H:i:s') }}</p>
            <p>✅ Sudah check-out</p>
        @endif
    @else
        <p>Belum absen hari ini</p>
        <a href="{{ route('attendance.wfh.form') }}" class="btn">Check-in WFH</a>
        <a href="{{ route('attendance.wfo.form') }}" class="btn">Check-in WFO</a>
    @endif
</div>


</body>
</html>