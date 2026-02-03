<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OfficeQrCode;

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


        return redirect()->route('dashboard')->with('success', 'WFH Check-in berhasil');
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

    public function wfoForm()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->first();

        if ($attendance) {
            return redirect()->route('dashboard')->with('error', 'Kamu sudah absen hari ini');
        }

        return view('attendance.wfo', [
            'attendance' => $attendance,
            'alreadyAbsent' => (bool) $attendance
        ]);
    }

    public function storeWfo(Request $request)
    {

        $exists = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah absen hari ini');
        }

        $request->validate([
            'qr_code' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);


        $qr = OfficeQrCode::where('code', $request->qr_code)
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();

        if (!$qr) {
            return back()->with('error', 'QR tidak valid / kadaluarsa');
        }


        $officeLat = -6.8268;
        $officeLng = 107.1370;
        $maxRadius = 100;

        if (!$this->withinRadius(
            $request->latitude,
            $request->longitude,
            $officeLat,
            $officeLng,
            $maxRadius
        )) {
            return back()->with('error', 'Kamu di luar area kantor');
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'check_in_at' => now(),
            'mode' => 'WFO',
            'status' => now()->format('H:i') <= '09:00' ? 'on_time' : 'late',
            'approval_status' => 'approved',
        ]);

        return redirect()->route('dashboard')->with('success', 'Absen WFO berhasil');
    }

    private function withinRadius($lat1, $lng1, $lat2, $lng2, $radius)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance <= $radius;
    }
}
