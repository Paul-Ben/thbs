<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class ,'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('superadmin')->middleware(['auth', 'role:Superadmin'])->group(function () {
    Route::get('/dashboard', function () { return view('superadmin.dashboard'); })->name('superadmin.dashboard');
});

Route::prefix('college')->middleware(['auth', 'role:College Admin'])->group(function () {
    Route::get('/dashboard', function () { return view('college.dashboard'); })->name('college.dashboard');
});

Route::prefix('admissions')->middleware(['auth', 'role:Admissions Officer'])->group(function () {
    Route::get('/dashboard', function () { return view('admissions.dashboard'); })->name('admissions.dashboard');
});

Route::prefix('bursar')->middleware(['auth', 'role:Bursar'])->group(function () {
    Route::get('/dashboard', function () { return view('bursar.dashboard'); })->name('bursar.dashboard');
});

Route::prefix('it')->middleware(['auth', 'role:IT Admin'])->group(function () {
    Route::get('/dashboard', function () { return view('it.dashboard'); })->name('it.dashboard');
});

Route::prefix('student')->middleware(['auth', 'role:Student'])->group(function () {
    Route::get('/dashboard', function () { return view('student.dashboard'); })->name('student.dashboard');
});

require __DIR__.'/auth.php';
