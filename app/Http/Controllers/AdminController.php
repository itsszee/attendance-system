<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();

        return view('admin.dashboard', [
            'hadir' => Attendance::whereDate('date', $today)->count(),
            'late' => Attendance::where('status', 'late')->whereDate('date', $today)->count(),
            'belum_absen' => User::whereDoesntHave('attendances', function ($q) use ($today) {
                $q->whereDate('date', $today);
            })->count(),
        ]);
    }

    public function attendance()
    {
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.attendance.index', compact('attendances'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);
        return view('admin.attendance.show', compact('attendance'));
    }
}
