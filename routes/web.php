<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/attendance/wfh', [AttendanceController::class, 'wfhForm']);
    Route::post('/attendance/wfh', [AttendanceController::class, 'storeWfh']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/attendance', [AdminController::class, 'attendance']);
    Route::get('/attendance/{id}', [AdminController::class, 'show']);
});

Route::middleware(['auth', 'admin'])->get('/admin/export', function () {
    return Excel::download(new AttendanceExport, 'attendance.xlsx');
});

Route::middleware(['auth'])->post(
    '/attendance/check-out',
    [AttendanceController::class, 'checkOut']
);


require __DIR__.'/auth.php';
