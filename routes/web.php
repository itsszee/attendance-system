<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQrController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationSettingController;
use App\Http\Controllers\Auth\GoogleAuthController;
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
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
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

    // Employee Assessments
    Route::get('/my-assessments', [App\Http\Controllers\EmployeeAssessmentController::class, 'index'])
        ->name('employee.assessments.index');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance.index');
    Route::get('/attendance/{id}', [AdminController::class, 'show'])->name('admin.attendance.show');
     Route::post('/attendance/{id}/approve', [AdminController::class, 'approve'])->name('admin.attendance.approve');
    Route::post('/attendance/{id}/reject', [AdminController::class, 'reject'])->name('admin.attendance.reject');
    Route::get('/qr', [AdminQrController::class, 'index'])->name('admin.qr.index');
    Route::post('/qr/generate', [AdminQrController::class, 'generate'])->name('admin.qr.generate');
    Route::post('/qr/start-auto', [AdminQrController::class, 'startAutoGenerate'])->name('admin.qr.start');
    Route::post('/qr/stop-auto', [AdminQrController::class, 'stopAutoGenerate'])->name('admin.qr.stop');
    Route::get('/qr/active', [AdminQrController::class, 'getActiveQr'])->name('admin.qr.active');

    Route::get('/export', function () {
        return Excel::download(new AttendanceExport, 'attendance.xlsx');
    })->name('admin.export');
});

// Admin CRUD User, Karyawan & lokasi
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('location_settings', LocationSettingController::class)->except(['show']);
    Route::resource('shifts', App\Http\Controllers\ShiftController::class);
    Route::resource('assessment-categories', App\Http\Controllers\Admin\AssessmentCategoryController::class)->except(['show']);
    
    Route::get('assessments', [App\Http\Controllers\Admin\AssessmentController::class, 'index'])->name('admin.assessments.index');
    Route::get('assessments/create/{evaluatee}', [App\Http\Controllers\Admin\AssessmentController::class, 'create'])->name('admin.assessments.create');
    Route::post('assessments/{evaluatee}', [App\Http\Controllers\Admin\AssessmentController::class, 'store'])->name('admin.assessments.store');

});

// Admin Request Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/requests', [App\Http\Controllers\Admin\RequestController::class, 'index'])->name('admin.requests.index');
    Route::put('admin/requests/{employeeRequest}', [App\Http\Controllers\Admin\RequestController::class, 'update'])->name('admin.requests.update');
});

// Auth Employee Routes
Route::middleware('auth')->group(function () {
    // Employee Request Routes
    Route::get('/requests', [App\Http\Controllers\EmployeeRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests', [App\Http\Controllers\EmployeeRequestController::class, 'store'])->name('requests.store');
});

// Google OAuth routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});


require __DIR__ . '/auth.php';
