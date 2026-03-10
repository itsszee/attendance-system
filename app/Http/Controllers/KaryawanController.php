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
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:karyawans',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        Karyawan::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:karyawans,nip,' . $karyawan->id,
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
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
