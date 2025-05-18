<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/auth/store-token', [SessionController::class, 'storeToken']);
Route::get('/auth/logout', [SessionController::class, 'logout'])->name('logout');

Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');
Route::get('analytics', [UserController::class, 'analytics'])->name('analytics');