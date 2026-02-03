<!DOCTYPE html>
<html>
<head>
    <title>Admin - QR Codes</title>
    <style>
        table { border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .qr-img { max-width: 150px; height: auto; }
        .success { color: green; margin: 10px 0; }
        .error { color: red; margin: 10px 0; }
        form { margin: 20px 0; }
    </style>
</head>
<body>
    <h1>QR Codes</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.qr.generate') }}">
        @csrf
        <label>Valid minutes (default 10):</label>
        <input type="number" name="minutes" min="1" max="60" value="10">
        <button type="submit">Generate Token</button>
    </form>

    <h2>Recent Codes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>QR Code Image</th>
                <th>Valid From</th>
                <th>Valid Until</th>
                <th>Active</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($codes as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->code }}</td>
                    <td>
                        @if($c->qr_image_path)
                            <img src="{{ asset('storage/' . $c->qr_image_path) }}" alt="QR Code" class="qr-img">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $c->valid_from }}</td>
                    <td>{{ $c->valid_until }}</td>
                    <td>{{ $c->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ optional($c->creator)->name ?? $c->created_by }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No codes</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
