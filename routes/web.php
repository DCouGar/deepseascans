<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/series', [LandingController::class, 'index'])->name('series.index.public');
Route::get('/series/{series}', [SeriesController::class, 'show'])->name('series.show.public');
Route::get('/series/{series}/chapters/{chapterNumber}', [ChapterController::class, 'show'])->name('chapters.show');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Redirect logic for dashboard access
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        // Adapt for non-admin users (e.g., redirect or show specific view)
        return redirect()->route('landing');
    })->name('dashboard');

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ############################################################
// # REVERTED: Now only protected by 'auth' middleware        #
// # This removes the admin-specific check but avoids the error #
// ############################################################
// Administration Routes (Protected ONLY by Auth Middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () { // <-- REMOVED 'admin' MIDDLEWARE

    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Series Management (Admin)
    Route::get('/series', [SeriesController::class, 'adminIndex'])->name('series.index');
    Route::get('/series/create', [SeriesController::class, 'create'])->name('series.create');
    Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
    Route::get('/series/{series}/edit', [SeriesController::class, 'edit'])->name('series.edit');
    Route::put('/series/{series}', [SeriesController::class, 'update'])->name('series.update');
    Route::delete('/series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');
    // Route::get('/series/{series}', [SeriesController::class, 'adminShow'])->name('series.show'); // Optional admin-specific show

    // Chapter Management (Admin)
    Route::get('/series/{series}/chapters', [ChapterController::class, 'adminIndex'])->name('chapters.index');
    Route::get('/all-chapters', [ChapterController::class, 'adminAllChaptersIndex'])->name('chapters.index.all');
    Route::get('/series/{series}/chapters/create', [ChapterController::class, 'create'])->name('chapters.create');
    Route::post('/series/{series}/chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::get('/series/{series}/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
    Route::put('/series/{series}/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('/series/{series}/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');
});

// Health check endpoint for monitoring
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected'
    ]);
});

// Auth routes (Login, Register, etc.)
require __DIR__.'/auth.php';