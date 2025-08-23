<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BursarController;
use App\Http\Controllers\ApplicationFeeController;
use App\Http\Controllers\AptitudeTestFeeController;
use App\Http\Controllers\SchoolFeeController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\CourseController;

use App\Http\Controllers\DepartmentController;

use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class ,'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [ApplicationController::class, 'landing'])->name('application.landing');

// Payment routes that don't require authentication
Route::post('/payment/initialize', [PaymentController::class, 'initialize'])->name('payment.initialize');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success', function() { return view('payment.success'); })->name('payment.success');

// Protected payment routes
Route::middleware('auth')->group(function () {
    Route::post('/payment/school-fee/initialize', [PaymentController::class, 'initializeSchoolFee'])->name('payment.school-fee.initialize');
    

});

Route::get('/application/apply/{tx_ref}', [ApplicationController::class, 'create'])->name('application.create');
Route::post('/application/{tx_ref}', [ApplicationController::class, 'store'])->name('application.store');
Route::get('/application/continue/{tx_ref}', [ApplicationController::class, 'continueApplication'])->name('application.continue');
Route::get('/application/retrieve/{application_number}', [ApplicationController::class, 'retrieveApplication'])->name('application.retrieve');
Route::get('/application/printout/{application}', [ApplicationController::class, 'printout'])->name('application.printout');
Route::get('/application/printout/{application}/download', [ApplicationController::class, 'downloadPrintout'])->name('application.downloadPrintout');

Route::prefix('superadmin')->middleware(['auth', 'role:Superadmin'])->group(function () {
   Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');
   
   Route::get('/users', [UserManagementController::class, 'index'])->name('superadmin.users.index');
   Route::get('/users/create', [UserManagementController::class, 'create'])->name('superadmin.users.create');
   Route::post('/users', [UserManagementController::class, 'store'])->name('superadmin.users.store');
   Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('superadmin.users.show');
   Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('superadmin.users.edit');
   Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('superadmin.users.update');
   Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('superadmin.users.destroy');
   Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('superadmin.users.reset-password');
   Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('superadmin.users.toggle-status');
   
   Route::resource('school-sessions', SchoolSessionController::class, [
       'names' => [
           'index' => 'superadmin.school-sessions.index',
           'create' => 'superadmin.school-sessions.create',
           'store' => 'superadmin.school-sessions.store',
           'show' => 'superadmin.school-sessions.show',
           'edit' => 'superadmin.school-sessions.edit',
           'update' => 'superadmin.school-sessions.update',
           'destroy' => 'superadmin.school-sessions.destroy',
       ]
   ]);
   Route::patch('/school-sessions/{schoolSession}/set-current', [SchoolSessionController::class, 'setCurrent'])->name('superadmin.school-sessions.set-current');
   
   Route::resource('semesters', SemesterController::class, [
       'names' => [
           'index' => 'superadmin.semesters.index',
           'create' => 'superadmin.semesters.create',
           'store' => 'superadmin.semesters.store',
           'show' => 'superadmin.semesters.show',
           'edit' => 'superadmin.semesters.edit',
           'update' => 'superadmin.semesters.update',
           'destroy' => 'superadmin.semesters.destroy',
       ]
   ]);
   Route::patch('/semesters/{semester}/set-current', [SemesterController::class, 'setCurrent'])->name('superadmin.semesters.set-current');
   
   Route::resource('levels', LevelController::class, [
       'names' => [
           'index' => 'superadmin.levels.index',
           'create' => 'superadmin.levels.create',
           'store' => 'superadmin.levels.store',
           'show' => 'superadmin.levels.show',
           'edit' => 'superadmin.levels.edit',
           'update' => 'superadmin.levels.update',
           'destroy' => 'superadmin.levels.destroy',
       ]
   ]);

   Route::prefix('departments')->name('superadmin.departments.')->group(function () {
       Route::get('/', [DepartmentController::class, 'index'])->name('index');
       Route::get('/create', [DepartmentController::class, 'create'])->name('create');
       Route::post('/', [DepartmentController::class, 'store'])->name('store');
       Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
       Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
       Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
       Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
   });
   
   Route::resource('courses', CourseController::class, [
       'names' => [
           'index' => 'superadmin.courses.index',
           'create' => 'superadmin.courses.create',
           'store' => 'superadmin.courses.store',
           'show' => 'superadmin.courses.show',
           'edit' => 'superadmin.courses.edit',
           'update' => 'superadmin.courses.update',
           'destroy' => 'superadmin.courses.destroy',
       ]
   ]);
});

Route::prefix('department')->middleware(['auth', 'role:Department Admin'])->group(function () {
    Route::get('/dashboard', function () { return view('department.dashboard'); })->name('department.dashboard');
});

Route::prefix('admissions')->middleware(['auth', 'role:Admission Officer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admissions.dashboard');
    Route::get('/applications', [ApplicationController::class, 'applications'])->name('admissions.applications');
    Route::get('/application/{application}/show', [ApplicationController::class, 'show'])->name('admissions.application.show');
});

Route::prefix('bursar')->middleware(['auth', 'role:Bursar'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('bursar.dashboard');
    Route::get('/payments/application', [BursarController::class, 'payments'])->name('bursar.payments.application');
    Route::get('/payment/{payment}', [BursarController::class, 'showPayment'])->name('bursar.payment.show');
    Route::get('/transactions', [BursarController::class, 'transactions'])->name('bursar.transactions');
    Route::get('/transaction/{transaction}', [BursarController::class, 'showTransaction'])->name('bursar.transaction.show');
    Route::patch('/transaction/{transaction}/reconcile', [BursarController::class, 'reconcileTransaction'])->name('bursar.transaction.reconcile');
    
    Route::resource('application-fees', ApplicationFeeController::class, [
        'names' => [
            'index' => 'bursar.application-fees.index',
            'create' => 'bursar.application-fees.create',
            'store' => 'bursar.application-fees.store',
            'show' => 'bursar.application-fees.show',
            'edit' => 'bursar.application-fees.edit',
            'update' => 'bursar.application-fees.update',
            'destroy' => 'bursar.application-fees.destroy',
        ]
    ]);
    
    Route::resource('aptitude-test-fees', AptitudeTestFeeController::class, [
        'names' => [
            'index' => 'bursar.aptitude-test-fees.index',
            'create' => 'bursar.aptitude-test-fees.create',
            'store' => 'bursar.aptitude-test-fees.store',
            'show' => 'bursar.aptitude-test-fees.show',
            'edit' => 'bursar.aptitude-test-fees.edit',
            'update' => 'bursar.aptitude-test-fees.update',
            'destroy' => 'bursar.aptitude-test-fees.destroy',
        ]
    ]);
    Route::patch('aptitude-test-fees/{aptitudeTestFee}/toggle-status', [AptitudeTestFeeController::class, 'toggleStatus'])
          ->name('bursar.aptitude-test-fees.toggle-status');
    
    // School Fee Management Routes
    Route::resource('school-fees', SchoolFeeController::class, [
        'names' => [
            'index' => 'bursar.school-fees.index',
            'create' => 'bursar.school-fees.create',
            'store' => 'bursar.school-fees.store',
            'show' => 'bursar.school-fees.show',
            'edit' => 'bursar.school-fees.edit',
            'update' => 'bursar.school-fees.update',
            'destroy' => 'bursar.school-fees.destroy',
        ]
    ]);
});

Route::prefix('it')->middleware(['auth', 'role:IT Admin'])->group(function () {
    Route::get('/dashboard', function () { return view('it.dashboard'); })->name('it.dashboard');
});

Route::prefix('student')->middleware(['auth', 'role:Student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    
    // Biodata routes
    Route::get('/biodata', [StudentController::class, 'biodata'])->name('student.biodata');
    Route::put('/biodata', [StudentController::class, 'updateBiodata'])->name('student.biodata.update');
    Route::post('/biodata/photo', [StudentController::class, 'updatePhoto'])->name('student.biodata.photo');
    
    // Course registration routes
    Route::get('/course-registration', [StudentController::class, 'courseRegistration'])->name('student.course-registration.current');
    Route::post('/course-registration', [StudentController::class, 'storeCourseRegistration'])->name('student.course-registration.store');
    Route::get('/course-registration/history', [StudentController::class, 'courseRegistrationHistory'])->name('student.course-registration.history');
    
    // Payment routes
    Route::get('/payments/fees', [StudentController::class, 'feePayments'])->name('student.payments.fees');
    Route::post('/payments/process', [StudentController::class, 'processPayment'])->name('student.payments.process');
    Route::get('/payments/history', [StudentController::class, 'paymentHistory'])->name('student.payments.history');
    Route::get('/payments/receipt/{payment}', [StudentController::class, 'paymentReceipt'])->name('student.payments.receipt');
    
    // Results routes
    Route::get('/results', [StudentController::class, 'results'])->name('student.results');
    Route::get('/results/{semester}', [StudentController::class, 'semesterResults'])->name('student.results.semester');
    
    // Support route
    Route::get('/support', [StudentController::class, 'support'])->name('student.support');
});

require __DIR__.'/auth.php';