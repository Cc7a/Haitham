<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuoteController::class, 'index'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/signup', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::patch('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
    Route::get('/my-impact', [ImpactController::class, 'index'])->name('impacts.index');
    Route::get('/owner', [OwnerController::class, 'index'])->middleware('owner')->name('owner.dashboard');
    Route::post('/quotes/{quote}/impacts', [ImpactController::class, 'store'])->name('impacts.store');
    Route::patch('/impacts/{impactAction}/outcome', [ImpactController::class, 'updateOutcome'])
        ->name('impacts.outcome');
});
