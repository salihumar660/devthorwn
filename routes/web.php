<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//REgister
Route::get('/register/form', [AuthController::class, 'registerForm'])->name('registration.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
//Login
Route::get('/', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
});
