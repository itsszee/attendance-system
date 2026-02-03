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


Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/attendance', [AdminController::class, 'attendance']);
    Route::get('/attendance/{id}', [AdminController::class, 'show']);
    Route::get('/qr', [AdminQrController::class, 'index'])->name('admin.qr.index');
    Route::post('/qr/generate', [AdminQrController::class, 'generate'])->name('admin.qr.generate');
});

Route::middleware(['auth', 'admin'])->get('/admin/export', function () {
    return Excel::download(new AttendanceExport, 'attendance.xlsx');
});

Route::middleware(['auth'])->post(
    '/attendance/check-out',
    [AttendanceController::class, 'checkOut']
);


require __DIR__ . '/auth.php';
