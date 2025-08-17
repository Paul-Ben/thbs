<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BursarController;
use App\Http\Controllers\ApplicationFeeController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class ,'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [ApplicationController::class, 'landing'])->name('application.landing');

// Payment routes
Route::post('/payment/initialize', [PaymentController::class, 'initialize'])->name('payment.initialize');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success', function() { return view('payment.success'); })->name('payment.success');

// Application routes
Route::get('/application/apply/{tx_ref}', [ApplicationController::class, 'create'])->name('application.create');
Route::post('/application/{tx_ref}', [ApplicationController::class, 'store'])->name('application.store');
Route::get('/application/continue/{tx_ref}', [ApplicationController::class, 'continueApplication'])->name('application.continue');
Route::get('/application/retrieve/{application_number}', [ApplicationController::class, 'retrieveApplication'])->name('application.retrieve');
Route::get('/application/printout/{application}', [ApplicationController::class, 'printout'])->name('application.printout');
Route::get('/application/printout/{application}/download', [ApplicationController::class, 'downloadPrintout'])->name('application.downloadPrintout');

Route::prefix('superadmin')->middleware(['auth', 'role:Superadmin'])->group(function () {
   // Route::get('/dashboard', function () { return view('superadmin.dashboard'); })->name('superadmin.dashboard');
   Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');
   
   // User Management Routes
   Route::get('/users', [UserManagementController::class, 'index'])->name('superadmin.users.index');

   Route::get('/users/create', [UserManagementController::class, 'create'])->name('superadmin.users.create');
   Route::post('/users', [UserManagementController::class, 'store'])->name('superadmin.users.store');
   Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('superadmin.users.show');
   Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('superadmin.users.edit');
   Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('superadmin.users.update');
   Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('superadmin.users.destroy');
   Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('superadmin.users.reset-password');
   Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('superadmin.users.toggle-status');
   
   // School Session Management Routes
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
   
   // Semester Management Routes
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
});

Route::prefix('college')->middleware(['auth', 'role:College Admin'])->group(function () {
    Route::get('/dashboard', function () { return view('college.dashboard'); })->name('college.dashboard');
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
    
    // Application Fee Management Routes
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
});

Route::prefix('it')->middleware(['auth', 'role:IT Admin'])->group(function () {
    Route::get('/dashboard', function () { return view('it.dashboard'); })->name('it.dashboard');
});

Route::prefix('student')->middleware(['auth', 'role:Student'])->group(function () {
    Route::get('/dashboard', function () { return view('student.dashboard'); })->name('student.dashboard');
});

require __DIR__.'/auth.php';
