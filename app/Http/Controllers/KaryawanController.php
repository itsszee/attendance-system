<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();

        // Filter by jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        // Filter by departemen
        if ($request->filled('departemen')) {
            $query->where('departemen', $request->departemen);
        }

        $karyawan = $query->paginate(10)->appends($request->query());

        // Get unique jabatan and departemen for filter dropdowns
        $jabatanList = Karyawan::distinct()->pluck('jabatan')->sort();
        $departemenList = Karyawan::distinct()->pluck('departemen')->sort();

        return view('admin.karyawan.index', compact('karyawan', 'jabatanList', 'departemenList'));
    }

    public function create()
    {
        $shifts = \App\Models\Shift::all();
        // provide list of user emails not yet assigned to karyawan
        $assigned = Karyawan::pluck('email')->toArray();
        $users = \App\Models\User::whereNotIn('email', $assigned)
            ->orderBy('email')
            ->pluck('email');

        return view('admin.karyawan.create', compact('shifts', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:karyawans',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'email' => 'required|email|exists:users,email|unique:karyawans',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        Karyawan::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(Karyawan $karyawan)
    {
        $shifts = \App\Models\Shift::all();
        $assigned = Karyawan::where('id', '!=', $karyawan->id)
            ->pluck('email')
            ->toArray();
        $users = \App\Models\User::whereNotIn('email', $assigned)
            ->orWhere('email', $karyawan->email)
            ->orderBy('email')
            ->pluck('email');

        return view('admin.karyawan.edit', compact('karyawan', 'shifts', 'users'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:karyawans,nip,' . $karyawan->id,
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'email' => 'required|email|exists:users,email|unique:karyawans,email,' . $karyawan->id,
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
