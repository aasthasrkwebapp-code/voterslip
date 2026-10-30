
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PdfImportController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\VoterSlipController;

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Public Admin Login
    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.submit');

    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {

        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])
            ->name('logout');

        Route::get('/pdf-imports/create', [PdfImportController::class, 'create'])
            ->name('pdf-imports.create');

        Route::post('/pdf-imports', [PdfImportController::class, 'store'])
            ->name('pdf-imports.store');
    });
});

// Voter search and e-slip
Route::get('/voter-search', [VoterSlipController::class, 'search'])
    ->name('voter.search');

Route::get('/voters/{voter}/slip', [VoterSlipController::class, 'slip'])
    ->name('voter.slip');

// Homepage
Route::get('/', function () {
    return view('welcome');
});
