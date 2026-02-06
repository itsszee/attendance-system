<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQrController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (login & register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    
    Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
        ->name('register');
    
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

// Auth routes (logout)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attendance routes
    Route::get('/attendance/wfh', [AttendanceController::class, 'wfhForm'])
        ->name('attendance.wfh.form');

    Route::post('/attendance/wfh', [AttendanceController::class, 'storeWfh'])
        ->name('attendance.wfh.store');

    Route::get('/attendance/wfo', [AttendanceController::class, 'wfoForm'])
        ->name('attendance.wfo.form');

    Route::post('/attendance/wfo', [AttendanceController::class, 'storeWfo'])
        ->name('attendance.wfo.store');

    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])
        ->name('attendance.checkout');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance.index');
    
    Route::get('/attendance/{id}', [AdminController::class, 'show'])->name('admin.attendance.show');
    
    Route::get('/qr', [AdminQrController::class, 'index'])->name('admin.qr.index');
    Route::post('/qr/generate', [AdminQrController::class, 'generate'])->name('admin.qr.generate');
    
    Route::get('/export', function () {
        return Excel::download(new AttendanceExport, 'attendance.xlsx');
    })->name('admin.export'); 
});


require __DIR__ . '/auth.php';