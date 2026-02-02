<h3>{{ $attendance->user->name }}</h3>

<p>Task: {{ $attendance->task }}</p>
<p>Check-In : {{ $attendance->check_in_at }}</p>
<p>Check-Out: {{ $attendance->check_out_at ?? '-' }}</p>
<p>Status: {{ $attendance->status }}</p>
<p>Lokasi: {{ $attendance->latitude }}, {{ $attendance->longitude }}</p>

<img src="{{ asset('storage/'.$attendance->selfie_path) }}" width="200">
