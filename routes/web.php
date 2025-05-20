<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('register', [AuthController::class, 'register'])->name('register.perform');

    Route::get('login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('login', [AuthController::class, 'login'])->name('login.perform');

    // Route::post('logout', [AuthController::class, 'logout'])->name('logout.perform');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout.perform');

    Route::get('password_reset', [AuthController::class, 'showResetPassword'])->name('password_reset.show');
    Route::post('password_reset', [AuthController::class, 'sendResetLink'])->name('password_reset.send');
    Route::get('password_reset/{token}', [AuthController::class, 'showResetForm'])->name('password_reset.form');
    Route::post('password_reset/{token}', [AuthController::class, 'resetPassword'])->name('password_reset.perform');
});
// Use custom auth middleware with role checks here
Route::middleware(['custom.auth:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/superadmin/school', [SchoolController::class, 'school'])->name('superadmin.school');
});

Route::middleware(['custom.auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })->name('admin.dashboard');
});

Route::middleware(['custom.auth:instructor'])->group(function () {
    Route::get('/instructor/dashboard', function () {
        return view('dashboards.instructor');
    })->name('instructor.dashboard');
});

Route::middleware(['custom.auth:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('dashboards.student');
    })->name('student.dashboard');
});
