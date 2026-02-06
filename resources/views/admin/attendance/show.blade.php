<!DOCTYPE html>
<html>
<head>
    <title>Detail Attendance</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .info-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { margin: 10px 0; }
        .label { font-weight: bold; display: inline-block; width: 150px; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .badge-wfh { background: #d1ecf1; color: #0c5460; }
        .badge-wfo { background: #d4edda; color: #155724; }
        .badge-late { background: #f8d7da; color: #721c24; }
        .badge-ontime { background: #d4edda; color: #155724; }
        img { max-width: 300px; border-radius: 8px; margin: 10px 0; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2>Detail Attendance - {{ $attendance->user->name }}</h2>

<div class="info-box">
    <div class="info-row">
        <span class="label">Tanggal:</span>
        {{ $attendance->date->format('d M Y') }}
    </div>
    
    <div class="info-row">
        <span class="label">Mode:</span>
        <span class="badge badge-{{ strtolower($attendance->mode) }}">
            {{ $attendance->mode }}
        </span>
    </div>
    
    <div class="info-row">
        <span class="label">Check-in:</span>
        {{ $attendance->check_in_at->format('H:i:s') }}
    </div>
    
    <div class="info-row">
        <span class="label">Check-out:</span>
        {{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i:s') : '-' }}
    </div>
    
    <div class="info-row">
        <span class="label">Status:</span>
        <span class="badge badge-{{ $attendance->status == 'on_time' ? 'ontime' : 'late' }}">
            {{ $attendance->status == 'on_time' ? 'Tepat Waktu' : 'Terlambat' }}
        </span>
    </div>
    
    <div class="info-row">
        <span class="label">Approval:</span>
        {{ ucfirst($attendance->approval_status) }}
    </div>
</div>

{{-- Khusus WFH --}}
@if($attendance->mode === 'WFH')
    <div class="info-box">
        <h3>📝 WFH Details</h3>
        
        <div class="info-row">
            <span class="label">Task:</span>
            {{ $attendance->task ?? '-' }}
        </div>
        
        @if($attendance->selfie_path)
            <div class="info-row">
                <span class="label">Selfie:</span><br>
                <img src="{{ asset('storage/' . $attendance->selfie_path) }}" alt="Selfie">
            </div>
        @endif
    </div>
@endif

{{-- Lokasi (WFH & WFO) --}}
@if($attendance->latitude && $attendance->longitude)
    <div class="info-box">
        <h3>📍 Lokasi</h3>
        
        <div class="info-row">
            <span class="label">Koordinat:</span>
            {{ $attendance->latitude }}, {{ $attendance->longitude }}
        </div>
        
        <div class="info-row">
            <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" 
               target="_blank">
                🗺️ Lihat di Google Maps
            </a>
        </div>
    </div>
@endif

<br>
<a href="{{ route('admin.attendance.index') }}">← Kembali</a>

</body>
</html>
