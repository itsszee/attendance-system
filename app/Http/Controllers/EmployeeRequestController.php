<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = EmployeeRequest::where('user_id', Auth::id())->latest()->get();
        return view('attendance.requests.index', compact('requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:izin,sakit,cuti',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        EmployeeRequest::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Pengajuan berhasil dikirim dan sedang menunggu respon admin.');
    }
}
