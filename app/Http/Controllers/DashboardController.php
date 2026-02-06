<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', now())
            ->first();

        return view('dashboard', compact('attendanceToday'));
    }
}