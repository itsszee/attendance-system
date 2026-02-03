<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\AttendanceSelfie;
use App\Models\AttendanceTask;
use App\Models\OfficeQrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function index()
    {
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        return view('attendance.checkin', compact('todayAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:WFH,WFO',
        ]);

        
        if ($this->alreadyCheckedIn()) {
            return back()->withErrors('Kamu sudah check-in hari ini');
        }

        DB::transaction(function () use ($request) {
            $attendance = Attendance::create([
                'user_id' => Auth::id(),
                'date' => today(),
                'mode' => $request->mode,
                'check_in_at' => now(),
                'status' => $this->attendanceStatus(),
                'approval_status' => 'pending',
            ]);

            if ($request->mode === 'WFO') {
                $this->handleWFO($request, $attendance);
            }

            if ($request->mode === 'WFH') {
                $this->handleWFH($request, $attendance);
            }
        });

        return redirect('/dashboard')->with('success', 'Check-in berhasil');
    }

    private function alreadyCheckedIn(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->exists();
    }

    private function attendanceStatus(): string
    {
        $checkInTime = Carbon::now()->format('H:i');

        return $checkInTime > '08:00'
            ? 'late'
            : 'on_time';
    }

    private function handleWFO(Request $request, Attendance $attendance)
    {
        $request->validate([
            'qr_code' => 'required',
        ]);

        $qr = OfficeQrCode::where('code', $request->qr_code)
            ->where('is_active', true)
            ->where('valid_until', '>=', now())
            ->firstOrFail();

        AttendanceLocation::create([
            'attendance_id' => $attendance->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'address' => $request->address,
        ]);
    }

    private function handleWFH(Request $request, Attendance $attendance)
    {
        $request->validate([
            'selfie' => 'required|image',
            'task' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $selfiePath = $request->file('selfie')->store('selfies', 'public');

        AttendanceSelfie::create([
            'attendance_id' => $attendance->id,
            'type' => 'check_in',
            'image_path' => $selfiePath,
        ]);

        AttendanceLocation::create([
            'attendance_id' => $attendance->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'address' => $request->address,
        ]);

        AttendanceTask::create([
            'attendance_id' => $attendance->id,
            'description' => $request->task,
            'progress_status' => 'in_progress',
        ]);
    }
}
