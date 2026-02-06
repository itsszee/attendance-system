<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; max-width: 1200px; margin: 20px auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logout-btn { background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .logout-btn:hover { background: #c82333; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0; }
        .stat-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; }
        .stat-card h3 { margin: 0; color: #666; font-size: 14px; }
        .stat-card .number { font-size: 36px; font-weight: bold; color: #007bff; margin: 10px 0; }
        
        .nav-links { margin: 20px 0; }
        .nav-links a { display: inline-block; padding: 10px 20px; margin: 5px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .nav-links a:hover { background: #0056b3; }
        
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-wfh { background: #d1ecf1; color: #0c5460; }
        .badge-wfo { background: #d4edda; color: #155724; }
        .badge-late { background: #f8d7da; color: #721c24; }
        .badge-ontime { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

<div class="header">
    <h1>Admin Dashboard</h1>
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Karyawan</h3>
        <div class="number">{{ $stats['total_users'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Absen Hari Ini</h3>
        <div class="number">{{ $stats['today_attendance'] }}</div>
    </div>
    <div class="stat-card">
        <h3>WFH Hari Ini</h3>
        <div class="number">{{ $stats['wfh_today'] }}</div>
    </div>
    <div class="stat-card">
        <h3>WFO Hari Ini</h3>
        <div class="number">{{ $stats['wfo_today'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Terlambat Hari Ini</h3>
        <div class="number" style="color: #dc3545;">{{ $stats['late_today'] }}</div>
    </div>
</div>

<!-- Navigation -->
<div class="nav-links">
    <a href="{{ route('admin.attendance.index') }}">📋 Semua Attendance</a>
    <a href="{{ route('admin.qr.index') }}">🔲 Kelola QR Code</a>
    <a href="{{ route('admin.export') }}">📥 Export Excel</a>
</div>

<!-- Recent Attendance -->
<h2>Recent Attendance (10 Terakhir)</h2>
<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Tanggal</th>
            <th>Mode</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($recentAttendance as $a)
            <tr>
                <td>{{ $a->user->name }}</td>
                <td>{{ $a->date->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($a->mode) }}">{{ $a->mode }}</span>
                </td>
                <td>{{ $a->check_in_at->format('H:i') }}</td>
                <td>{{ $a->check_out_at ? $a->check_out_at->format('H:i') : '-' }}</td>
                <td>
                    <span class="badge badge-{{ $a->status == 'on_time' ? 'ontime' : 'late' }}">
                        {{ $a->status == 'on_time' ? 'On Time' : 'Late' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.attendance.show', $a->id) }}">Detail</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Belum ada attendance</td></tr>
        @endforelse
    </tbody>
</table>

</body>
</html>