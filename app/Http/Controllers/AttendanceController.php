<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OfficeQrCode;

/**
 * @class AttendanceController
 * @brief Controller untuk menangani alur absensi WFH dan WFO.
 * 
 * Class ini bertanggung jawab untuk menampilkan form absensi, memvalidasi lokasi,
 * serta menyimpan data absensi ke database.
 * 
 * @author Ujikom Student
 */
class AttendanceController extends Controller
{
    /**
     * @brief Menampilkan form absensi WFH.
     * 
     * @return \Illuminate\View\View
     */
    public function wfhForm()
    {
        // Object / Instance
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
            'selfie' => 'required',
        ]);

        // Condition
        if ($request->hasFile('selfie')) {
            $photoPath = $request->file('selfie')->store('selfies', 'public');
        } else {
            $imageData = $request->selfie;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); 

                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    throw new \Exception('invalid image type');
                }
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('base64_decode failed');
                }
            } else {
                throw new \Exception('did not match data URI in image data');
            }

            $fileName = 'selfies/' . uniqid() . '.' . $type;
            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageData);
            $photoPath = $fileName;
        }

        $user = Auth::user();
        $shiftTime = '09:00';
        if ($user && $user->karyawan && $user->karyawan->shift) {
            $shiftTime = $user->karyawan->shift->start_time->format('H:i');
        }
        $status = now()->format('H:i') <= $shiftTime ? 'on_time' : 'late';

        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'check_in_at' => now(),
            'mode' => 'WFH',
            'status' => $status,
            'approval_status' => 'pending',
            'task' => $request->task,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'selfie_path' => $photoPath,
        ]);

        app(\App\Services\IntegrityService::class)->processAttendance($attendance, $user);




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

    /**
     * @brief Menampilkan form absensi WFO.
     * 
     * Fungsi ini mengambil pengaturan lokasi kantor untuk divalidasi di sisi klien.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function wfoForm()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->first();

        if ($attendance) {
            return redirect()->route('dashboard')->with('error', 'Kamu sudah absen hari ini');
        }

        $location = \App\Models\LocationSetting::first();

        // Debugging: Lihat isi data lokasi kantor
        // dd($location);

        return view('attendance.wfo', [
            'attendance' => $attendance,
            'alreadyAbsent' => (bool) $attendance,
            'location' => $location,
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

        // Exception handling untuk QR code yang tidak valid atau kadaluarsa
        if (!$qr) {
            return back()->with('error', 'QR tidak valid / kadaluarsa');
        }

        $location = \App\Models\LocationSetting::first();

        if ($location) {
            $officeLat = $location->latitude;
            $officeLng = $location->longitude;
            $maxRadius = $location->radius;
        } else {
            // fallback to previous hardcoded values
            $officeLat = -6.8268;
            $officeLng = 107.1370;
            $maxRadius = 100;
        }

        if (!$this->withinRadius(
            $request->latitude,
            $request->longitude,
            $officeLat,
            $officeLng,
            $maxRadius
        )) {
            return back()->with('error', 'Kamu di luar area kantor');
        }

        $user = Auth::user();
        $shiftTime = '09:00';
        if ($user && $user->karyawan && $user->karyawan->shift) {
            $shiftTime = $user->karyawan->shift->start_time->format('H:i');
        }
        $status = now()->format('H:i') <= $shiftTime ? 'on_time' : 'late';

        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'check_in_at' => now(),
            'mode' => 'WFO',
            'status' => $status,
            'approval_status' => 'approved',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        app(\App\Services\IntegrityService::class)->processAttendance($attendance, $user);

        return redirect()->route('dashboard')->with('success', 'Absen WFO berhasil');
    }

    /**
     * @brief Menghitung apakah koordinat user berada dalam radius kantor.
     * 
     * Menggunakan rumus Haversine untuk menghitung jarak antara dua koordinat lat/lng.
     * 
     * @param float $lat1 Latitude user
     * @param float $lng1 Longitude user
     * @param float $lat2 Latitude kantor
     * @param float $lng2 Longitude kantor
     * @param int $radius Radius maksimal (meter)
     * @return bool True jika di dalam radius, False jika di luar
     */
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
