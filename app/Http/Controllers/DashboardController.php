<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        
        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        return view('dashboard', compact('attendanceToday'));
    }
}
