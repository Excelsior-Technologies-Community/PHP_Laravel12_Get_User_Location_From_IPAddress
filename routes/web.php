<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/user');
});

// Search IP Address
Route::get('/user', [UserController::class, 'index'])->name('user');

// Dashboard
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// History
Route::get('/history', [UserController::class, 'history'])->name('history');

// Export CSV
Route::get('/history/export/csv', [UserController::class, 'exportCsv'])->name('history.export.csv');

// Delete History
Route::delete('/history/{id}', [UserController::class, 'destroy'])->name('history.delete');