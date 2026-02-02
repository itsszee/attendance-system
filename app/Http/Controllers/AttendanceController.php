<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function wfhForm()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->first();

        return view('attendance.wfh', [
            'attendance' => $attendance,
            'alreadyAbsent' => (bool) $attendance
        ]);
    }
    public function storeWfh(Request $request)
    {

        $exists = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah absen hari ini');
        }

        $request->validate([
            'task' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'selfie' => 'required|image|max:2048',
        ]);

        $photoPath = $request->file('selfie')->store('selfies', 'public');

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'check_in_at' => now(),
            'mode' => 'WFH',
            'status' => now()->format('H:i') <= '09:00' ? 'on_time' : 'late',
            'approval_status' => 'pending',
        ]);


        return redirect()->back()->with('success', 'Absen WFH berhasil');
    }

    public function checkOut()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->first();


        if (!$attendance) {
            return back()->with('error', 'Kamu belum check-in hari ini');
        }


        if ($attendance->check_out_at) {
            return back()->with('error', 'Kamu sudah check-out');
        }


        $attendance->update([
            'check_out_at' => now(),
        ]);

        return back()->with('success', 'Check-out berhasil');
    }
}
