<?php

use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengajuan beasiswa
    Route::get('/beasiswa/{id}/apply', [PengajuanController::class, 'create'])
        ->whereNumber('id')
        ->name('applications.create');
    Route::post('/beasiswa/{id}/apply', [PengajuanController::class, 'store'])
        ->whereNumber('id')
        ->name('applications.store');
});

require __DIR__.'/auth.php';