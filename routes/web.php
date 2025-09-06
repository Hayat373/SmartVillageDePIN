<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AI\AIAnalysisController;
use App\Http\Controllers\TokenController;

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Contribution
    Route::get('/contribute', [ResourceController::class, 'create'])->name('contribute');
    Route::post('/contribute', [ResourceController::class, 'store']);
    
    // Village View
    Route::get('/village', [ResourceController::class, 'village'])->name('village');
    
    // AI Analysis Routes
    Route::get('/ai-analysis', [AIAnalysisController::class, 'index'])->name('ai.analysis');
    Route::get('/prediction-history', [AIAnalysisController::class, 'predictionHistory'])->name('ai.history');
});

 Route::get('/create-token', [TokenController::class, 'createToken']);

// Homepage
Route::get('/', function () {
    return view('welcome');

   

});