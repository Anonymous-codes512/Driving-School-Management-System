<?php

use App\Http\Controllers\SchoolOwner\AdmissionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SchoolOwner\CarController;
use App\Http\Controllers\SchoolOwner\CourseController;
use App\Http\Controllers\SchoolOwner\DashboardController;
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
    Route::get('/schoolowner/dashboard', [DashboardController::class, 'dashboard'])->name('schoolowner.dashboard');
    Route::get('/schoolowner/admissions', [AdmissionController::class, 'admissions'])->name('schoolowner.admissions');


    Route::get('/schoolowner/cars', [CarController::class, 'cars'])->name('schoolowner.cars');

    Route::post('/schoolowner/cars/add_model', [CarController::class, 'addCarModel'])->name('schoolowner.cars.add_model');
    Route::post('/schoolowner/cars/update_model', [CarController::class, 'updatedCarModel'])->name('schoolowner.cars.update_model');
    Route::post('/schoolowner/cars/delete_model', [CarController::class, 'deleteCarModel'])->name('schoolowner.cars.delete_model');

    Route::post('/schoolowner/cars/add_car', [CarController::class, 'addCar'])->name('schoolowner.cars.add_car');
    Route::post('/schoolowner/cars/update_car', [CarController::class, 'updatedCar'])->name('schoolowner.cars.update_car');
    Route::post('/schoolowner/cars/delete_car', [CarController::class, 'deleteCar'])->name('schoolowner.cars.delete_car');


    Route::get('/schoolowner/courses',[CourseController::class, 'courses'])->name('schoolowner.courses');
    Route::post('/schoolowner/courses/add_Course',[CourseController::class, 'addCourse'])->name('schoolowner.courses.add_course');
    Route::post('/schoolowner/courses/update_Course',[CourseController::class, 'updateCourse'])->name('schoolowner.courses.update_course');
    Route::post('/schoolowner/courses/delete_Course',[CourseController::class, 'deleteCourse'])->name('schoolowner.courses.delete_course');


    Route::get('/schoolowner/students', function () {
        return view('pages.schoolowner.students');
    })->name('schoolowner.students');

    Route::get('/schoolowner/instructors', function () {
        return view('pages.schoolowner.instructors');
    })->name('schoolowner.instructors');

    Route::get('/schoolowner/invoices', function () {
        return view('pages.schoolowner.invoices');
    })->name('schoolowner.invoices');

    Route::get('/schoolowner/banners', function () {
        return view('pages.schoolowner.banners');
    })->name('schoolowner.banners');

    Route::get('/schoolowner/expenses', function () {
        return view('pages.schoolowner.expenses');
    })->name('schoolowner.expenses');

    Route::get('/schoolowner/attendance', function () {
        return view('pages.schoolowner.attendance');
    })->name('schoolowner.attendance');

    Route::get('/schoolowner/classes', function () {
        return view('pages.schoolowner.classes');
    })->name('schoolowner.classes');

    Route::get('/schoolowner/leaves', function () {
        return view('pages.schoolowner.leaves');
    })->name('schoolowner.leaves');

    Route::get('/schoolowner/coupons', function () {
        return view('pages.schoolowner.coupons');
    })->name('schoolowner.coupons');

    Route::get('/schoolowner/inquiries', function () {
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
