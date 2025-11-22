<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
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
        Route::resource('employees', EmployeeController::class)->except('show');
        Route::put('/employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])
    ->name('employees.reactivate');
        Route::resource('attendances', AttendanceController::class)->only(['index']);
    });

    // ------------------- EMPLOYEE -------------------
    Route::middleware('role:employee|hr')->group(function () {
        Route::resource('attendances', AttendanceController::class)->only(['store', 'update']);

    });
    // Route::middleware('role:admin')->group(function(){
    //     Route::get("hirednotification", [HiringController::class, "index"]);
    // });
    // Route::middleware('role:hr')->group(function () {
    //     Route::resource('attendances', AttendanceController::class)->only(['index', 'store', 'update']);
    // });
});

require __DIR__.'/settings.php';
