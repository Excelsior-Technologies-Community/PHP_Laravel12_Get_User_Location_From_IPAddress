<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/user');
});

// Live IP Location Tracker & Leaflet Map Studio
Route::get('/user', [UserController::class, 'index'])->name('user');

// Dashboard & Global Multi-Pin Map Studio
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// History & Filter
Route::get('/history', [UserController::class, 'history'])->name('history');

// Bulk Batch IP Lookup Parser
Route::post('/bulk-lookup', [UserController::class, 'bulkLookup'])->name('bulk.lookup');

// CIDR Subnet Calculator
Route::get('/subnet', [UserController::class, 'subnetCalculator'])->name('subnet');

// Custom IP Blacklist Panel
Route::get('/blacklist', [UserController::class, 'blacklistIndex'])->name('blacklist.index');
Route::post('/blacklist', [UserController::class, 'blacklistStore'])->name('blacklist.store');
Route::delete('/blacklist/{id}', [UserController::class, 'blacklistDestroy'])->name('blacklist.delete');

// Map & Multi-Format Exporters
Route::get('/export/geojson', [UserController::class, 'exportGeoJson'])->name('export.geojson');
Route::get('/export/kml', [UserController::class, 'exportKml'])->name('export.kml');
Route::get('/history/export/csv', [UserController::class, 'exportCsv'])->name('history.export.csv');
Route::get('/history/export/pdf', [UserController::class, 'exportPdf'])->name('history.export.pdf');
Route::get('/api/history', [UserController::class, 'jsonApi'])->name('api.history');

// Delete History
Route::delete('/history/{id}', [UserController::class, 'destroy'])->name('history.delete');