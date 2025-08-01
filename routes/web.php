<?php

use App\Http\Controllers\SchoolOwner\AdmissionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SchoolOwner\BannerController;
use App\Http\Controllers\SchoolOwner\BranchController;
use App\Http\Controllers\SchoolOwner\CarController;
use App\Http\Controllers\SchoolOwner\CouponController;
use App\Http\Controllers\SchoolOwner\CourseController;
use App\Http\Controllers\SchoolOwner\DashboardController as SchoolOwnerDashboardController;
use App\Http\Controllers\SchoolOwner\ExpenseController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SchoolOwner\InstructorController;
use App\Http\Controllers\SchoolOwner\InvoiceController;
use App\Http\Controllers\SchoolOwner\StudentController;
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
    Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'dashboard'])->name('superadmin.dashboard');
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
   
    Route::get('/schoolowner/dashboard', [SchoolOwnerDashboardController::class, 'dashboard'])->name('schoolowner.dashboard');
   
    Route::get('/schoolowner/branches', [BranchController::class, 'branches'])->name('schoolowner.branches');
    Route::post('/schoolowner/branch/add_branch', [BranchController::class, 'addBranch'])->name('schoolowner.branches.add_branch');
    Route::post('/schoolowner/branch/update_branch', [BranchController::class, 'updateBranch'])->name('schoolowner.branches.update_branch');
    Route::post('/schoolowner/branch/delete_branch', [BranchController::class, 'deleteBranch'])->name('schoolowner.branches.delete_branch');

    Route::get('/schoolowner/admissions', [AdmissionController::class, 'admissions'])->name('schoolowner.admissions');

    Route::get('/schoolowner/cars', [CarController::class, 'cars'])->name('schoolowner.cars');

    Route::post('/schoolowner/cars/add_model', [CarController::class, 'addCarModel'])->name('schoolowner.cars.add_model');
    Route::post('/schoolowner/cars/update_model', [CarController::class, 'updatedCarModel'])->name('schoolowner.cars.update_model');
    Route::post('/schoolowner/cars/delete_model', [CarController::class, 'deleteCarModel'])->name('schoolowner.cars.delete_model');

    Route::post('/schoolowner/cars/add_car', [CarController::class, 'addCar'])->name('schoolowner.cars.add_car');
    Route::post('/schoolowner/cars/update_car', [CarController::class, 'updatedCar'])->name('schoolowner.cars.update_car');
    Route::post('/schoolowner/cars/delete_car', [CarController::class, 'deleteCar'])->name('schoolowner.cars.delete_car');


    Route::get('/schoolowner/courses', [CourseController::class, 'courses'])->name('schoolowner.courses');
    Route::post('/schoolowner/courses/add_Course', [CourseController::class, 'addCourse'])->name('schoolowner.courses.add_course');
    Route::post('/schoolowner/courses/update_Course', [CourseController::class, 'updateCourse'])->name('schoolowner.courses.update_course');
    Route::post('/schoolowner/courses/delete_Course', [CourseController::class, 'deleteCourse'])->name('schoolowner.courses.delete_course');

    Route::get('/schoolowner/instructors', [InstructorController::class, 'instructors'])->name('schoolowner.instructors');
    Route::get('/schoolowner/instructors/show_add_instructor', [InstructorController::class, 'showAddInstructorForm'])->name('schoolowner.instructors.show_add_instructor_form');
    Route::post('/schoolowner/instructors/add_instructor', [InstructorController::class, 'addInstructor'])->name('schoolowner.instructors.add_instructor');
    Route::get('/schoolowner/instructors/show_edit_instructor/{id}', [InstructorController::class, 'showEditInstructorForm'])->name('schoolowner.instructors.show_edit_instructor_form');
    Route::post('/schoolowner/instructors/update_instructor', [InstructorController::class, 'updateInstructor'])->name('schoolowner.instructors.update_instructor');
    Route::post('/schoolowner/instructors/update_instructor', [InstructorController::class, 'updateInstructor'])->name('schoolowner.instructors.update_instructor');
    Route::post('/schoolowner/instructors/delete_instructor', [InstructorController::class, 'deleteInstructor'])->name('schoolowner.instructors.delete_instructor');


    Route::get('/schoolowner/students', [StudentController::class, 'students'])->name('schoolowner.students');
    Route::get('/schoolowner/students/show_add_student_form', [StudentController::class, 'showAddStudentForm'])->name('schoolowner.students.show_add_student_form');
    Route::post('/schoolowner/students/add_student', [StudentController::class, 'addStudent'])->name('schoolowner.students.add_student');
    Route::get('/schoolowner/students/show_edit_student_form', [StudentController::class, 'showEditStudentForm'])->name('schoolowner.students.show_edit_student_form');
    Route::post('/schoolowner/students/delete_student', [StudentController::class, 'deleteStudent'])->name('schoolowner.students.delete_student');

    Route::get('/schoolowner/invoices',[InvoiceController::class, 'invoices'])->name('schoolowner.invoices');
    Route::get('/schoolowner/invoices/view_invoice/{id}',[InvoiceController::class, 'viewInvoice'])->name('schoolowner.invoices.view_invoice');
    Route::post('/schoolowner/invoices/update',[InvoiceController::class, 'updateInvoice'])->name('schoolowner.invoice.update');

    Route::get('/schoolowner/banners',[BannerController::class, 'banners'])->name('schoolowner.banners');
    Route::post('/schoolowner/banners/add_banner',[BannerController::class, 'addBanner'])->name('schoolowner.banner.add_banner');
    Route::post('/schoolowner/banners/update_banner',[BannerController::class, 'updateBanner'])->name('schoolowner.banner.update_banner');
    Route::post('/schoolowner/banners/delete_banner',[BannerController::class, 'deleteBanner'])->name('schoolowner.banner.delete_banner');

    Route::get('/schoolowner/expenses',[ExpenseController::class, 'expenses'])->name('schoolowner.expenses');
// Car Expense
    Route::post('/schoolowner/expenses/car/add', [ExpenseController::class, 'addCarExpense'])->name('schoolowner.expenses.add_car_expense');
    Route::post('/schoolowner/expenses/car/update', [ExpenseController::class, 'updateCarExpense'])->name('schoolowner.expenses.update_car_expense');
    Route::post('/schoolowner/expenses/car/delete', [ExpenseController::class, 'deleteCarExpense'])->name('schoolowner.expenses.delete_car_expense');

    // Other Expense
    Route::post('/schoolowner/expenses/other/add', [ExpenseController::class, 'addOtherExpense'])->name('schoolowner.expenses.add_other_expense');
    Route::post('/schoolowner/expenses/other/update', [ExpenseController::class, 'updateOtherExpense'])->name('schoolowner.expenses.update_other_expense');
    Route::post('/schoolowner/expenses/other/delete', [ExpenseController::class, 'deleteOtherExpense'])->name('schoolowner.expenses.delete_other_expense');

    Route::get('/schoolowner/attendance', function () {
        return view('pages.schoolowner.attendance');
    })->name('schoolowner.attendance');

    Route::get('/schoolowner/classes', function () {
        return view('pages.schoolowner.classes');
    })->name('schoolowner.classes');

    Route::get('/schoolowner/leaves', function () {
        return view('pages.schoolowner.leaves');
    })->name('schoolowner.leaves');

    Route::get('/schoolowner/coupons', [CouponController::class, 'coupons'])->name('schoolowner.coupons');

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
