<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin']);

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout.perform');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('register', [AuthController::class, 'register'])->name('register.perform');

    Route::get('login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('login', [AuthController::class, 'login'])->name('login.perform');

    // Route::post('logout', [AuthController::class, 'logout'])->name('logout.perform');

    Route::get('password_reset', [AuthController::class, 'showResetPassword'])->name('password_reset.show');
    Route::post('password_reset', [AuthController::class, 'sendResetLink'])->name('password_reset.send');
    Route::get('password_reset/{token}', [AuthController::class, 'showResetForm'])->name('password_reset.form');
    Route::post('password_reset/{token}', [AuthController::class, 'resetPassword'])->name('password_reset.perform');
});
// Use custom auth middleware with role checks here
Route::middleware(['custom.auth:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/schools/export-pdf', [SchoolController::class, 'exportPdf'])->name('schools.exportPdf');

    Route::get('/superadmin/school', [SchoolController::class, 'school'])->name('superadmin.school');
    Route::post('/superadmin/school', [SchoolController::class, 'storeSchool'])->name('superadmin.school.store');
    Route::post('/superadmin/school/{id}/update', [SchoolController::class, 'update'])->name('superadmin.school.update');
    Route::post('/superadmin/school/{id}/delete', [SchoolController::class, 'deleteSchool'])->name('superadmin.school.delete');

    Route::get('/superadmin/subscription', [SubscriptionController::class, 'subscription'])->name('superadmin.subscription');
    Route::post('/subscriptions/store', [SubscriptionController::class, 'store'])->name('superadmin.subscriptions.store');
    Route::post('/subscriptions/update', [SubscriptionController::class, 'update'])->name('superadmin.subscriptions.update');
    Route::post('/subscriptions/delete', [SubscriptionController::class, 'delete'])->name('superadmin.subscriptions.delete');
    Route::get('/subscriptions/export-pdf', [SubscriptionController::class, 'exportPdf'])->name('superadmin.subscriptions.exportPdf');

    Route::get('/superadmin/subscription_request', [SubscriptionController::class, 'subscriptionRequests'])->name('superadmin.subscription_request');
});

Route::middleware(['custom.auth:schoolowner'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('pages.schoolowner.dashboard');
    })->name('schoolowner.dashboard');

    Route::get('/admin/admissions', function () {
        return view('pages.schoolowner.admissions');
    })->name('schoolowner.admissions');

    Route::get('/admin/students', function () {
        return view('pages.schoolowner.students');
    })->name('schoolowner.students');

    Route::get('/admin/instructors', function () {
        return view('pages.schoolowner.instructors');
    })->name('schoolowner.instructors');

    Route::get('/admin/invoices', function () {
        return view('pages.schoolowner.invoices');
    })->name('schoolowner.invoices');

    Route::get('/admin/courses', function () {
        return view('pages.schoolowner.courses');
    })->name('schoolowner.courses');

    Route::get('/admin/banners', function () {
        return view('pages.schoolowner.banners');
    })->name('schoolowner.banners');

    Route::get('/admin/expenses', function () {
        return view('pages.schoolowner.expenses');
    })->name('schoolowner.expenses');

    Route::get('/admin/attendance', function () {
        return view('pages.schoolowner.attendance');
    })->name('schoolowner.attendance');

    Route::get('/admin/classes', function () {
        return view('pages.schoolowner.classes');
    })->name('schoolowner.classes');

    Route::get('/admin/leaves', function () {
        return view('pages.schoolowner.leaves');
    })->name('schoolowner.leaves');

    Route::get('/admin/cars', function () {
        return view('pages.schoolowner.cars');
    })->name('schoolowner.cars');

    Route::get('/admin/coupons', function () {
        return view('pages.schoolowner.coupons');
    })->name('schoolowner.coupons');

    Route::get('/admin/inquiries', function () {
        return view('pages.schoolowner.inquiries');
    })->name('schoolowner.inquiries');
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
