<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    Route::resource('dashboard', DashboardController::class)->only(['index']);

    // ------------------- HR | Admin -------------------
    Route::middleware('role:hr|admin')->group(function () {
        Route::resource('employees', EmployeeController::class)->only(['index', 'show', 'edit', 'update']);
        Route::resource('attendances', AttendanceController::class)->only(['index']);
    });

    // ------------------- EMPLOYEE -------------------
    Route::middleware('role:employee')->group(function () {
        Route::resource('attendances', AttendanceController::class)->only(['index', 'store', 'update']);
    });
});

require __DIR__.'/settings.php';
