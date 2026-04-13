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

        $latestLedgers = \App\Models\PointLedger::select('user_id', \Illuminate\Support\Facades\DB::raw('MAX(id) as max_id'))
            ->groupBy('user_id');
            
        $leaderboard = \App\Models\PointLedger::joinSub($latestLedgers, 'latest', function ($join) {
                $join->on('point_ledgers.id', '=', 'latest.max_id');
            })
            ->with('user')
            ->orderBy('current_balance', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('attendanceToday', 'leaderboard'));
    }
}