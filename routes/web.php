<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Livewire\Admin\PerfumeManager;
use App\Http\Controllers\PerfumeController;

Route::get('/', [PerfumeController::class, 'index'])
    ->name('perfumes.index');

Route::get('/perfumes/category/{id}', [PerfumeController::class, 'category'])
    ->name('perfumes.category');

Route::get('/perfumes/{id}', [PerfumeController::class, 'show'])
    ->name('perfumes.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/perfumes', function () {
        return view('admin.perfumes');
    })->name('admin.perfumes');

    Route::get('/categories', function () {
        return view('admin.categories');
    })->name('admin.categories');

});

