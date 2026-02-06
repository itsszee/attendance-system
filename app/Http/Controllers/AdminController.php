<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Stats untuk admin
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'today_attendance' => Attendance::whereDate('date', now())->count(),
            'wfh_today' => Attendance::whereDate('date', now())->where('mode', 'WFH')->count(),
            'wfo_today' => Attendance::whereDate('date', now())->where('mode', 'WFO')->count(),
            'late_today' => Attendance::whereDate('date', now())->where('status', 'late')->count(),
        ];

        // Recent attendance
        $recentAttendance = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttendance'));
    }

    public function attendance()
    {
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.attendance.index', compact('attendances'));
    }

    // AdminAttendanceController.php
    public function show($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }
}
