<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeRequest;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = EmployeeRequest::with('user')->latest()->get();
        return view('admin.requests.index', compact('requests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeRequest $employeeRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $employeeRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
        ]);

        $statusText = $validated['status'] == 'approved' ? 'disetujui' : 'ditolak';

        return redirect()->route('admin.requests.index')
            ->with('success', "Pengajuan atas nama {$employeeRequest->user->name} berhasil {$statusText}.");
    }
}
