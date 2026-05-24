<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


// Redireciona a raiz para o dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
