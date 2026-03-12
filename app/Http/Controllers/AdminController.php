<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'today_attendance' => Attendance::whereDate('date', now())->count(),
            'wfh_today' => Attendance::whereDate('date', now())->where('mode', 'WFH')->count(),
            'wfo_today' => Attendance::whereDate('date', now())->where('mode', 'WFO')->count(),
            'late_today' => Attendance::whereDate('date', now())->where('status', 'late')->count(),
        ];

        $recentAttendance = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttendance'));
    }

    public function attendance(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('check_in_at', 'desc')
            ->paginate(50)
            ->appends($request->query());

        return view('admin.attendance.index', compact('attendances'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('admin.attendance-detail', compact('attendance'));
    }

    /**
     * Approve a WFH attendance record.
     */
    public function approve($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        if ($attendance->mode !== 'WFH') {
            return back()->with('error', 'Hanya WFH yang perlu approval!');
        }

        if ($attendance->approval_status !== 'pending') {
            return back()->with('error', 'Attendance sudah di-process sebelumnya!');
        }

        $attendance->update([
            'approval_status' => 'approved'
        ]);

        return back()->with('success', 'WFH berhasil di-approve! ✅');
    }

    /**
     * Reject a WFH attendance record.
     */
    public function reject($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        if ($attendance->mode !== 'WFH') {
            return back()->with('error', 'Hanya WFH yang perlu approval!');
        }

        if ($attendance->approval_status !== 'pending') {
            return back()->with('error', 'Attendance sudah di-process sebelumnya!');
        }

        $attendance->update([
            'approval_status' => 'rejected'
        ]);

        return back()->with();
    }
}