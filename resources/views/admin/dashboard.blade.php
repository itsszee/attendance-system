<h2>Admin Dashboard</h2>

<ul>
    <li>Hadir Hari Ini: {{ $hadir }}</li>
    <li>Terlambat: {{ $late }}</li>
    <li>Belum Absen: {{ $belum_absen }}</li>
    <a href="/admin/export" style="margin-bottom:10px; display:inline-block;">
        Export Excel
    </a>
</ul>
