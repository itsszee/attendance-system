<table>
    <tr>
        <th>User</th>
        <th>Tanggal</th>
        <th>Mode</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach ($attendances as $a)
        <tr>
            <td>{{ $a->user->name }}</td>
            <td>{{ $a->date }}</td>
            <td>{{ $a->mode }}</td>
            <td>
                {{ $a->status }}

                @if ($a->status === 'late')
                    <span style="color:red; font-weight:bold;">⚠ Terlambat</span>
                @endif
            </td>
            <td><a href="/admin/attendance/{{ $a->id }}">Detail</a></td>
        </tr>
    @endforeach
</table>
