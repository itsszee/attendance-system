<?php

namespace App\Http\Controllers;

use App\Models\LocationSetting;
use Illuminate\Http\Request;

class LocationSettingController extends Controller
{
    public function index()
    {
        $locations = LocationSetting::paginate(10);
        return view('admin.location_settings.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.location_settings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:0',
        ]);

        LocationSetting::create($data);
        return redirect()->route('location_settings.index')->with('success', 'Lokasi berhasil disimpan');
    }

    public function edit(LocationSetting $location_setting)
    {
        return view('admin.location_settings.edit', compact('location_setting'));
    }

    public function update(Request $request, LocationSetting $location_setting)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:0',
        ]);

        $location_setting->update($data);
        return redirect()->route('location_settings.index')->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(LocationSetting $location_setting)
    {
        $location_setting->delete();
        return redirect()->route('location_settings.index')->with('success', 'Lokasi berhasil dihapus');
    }
}
