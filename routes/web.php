<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'view']);


    Route::middleware('is_manager')->group(function () {
        Route::get('users', [ManagersController::class, 'users']);
        Route::post('users/add', [ManagersController::class, 'add_user']);
        Route::post('users/save', [ManagersController::class, 'save_user']);
        Route::post('users/outofoffice', [ManagersController::class, 'outofoffice_user']);
    });

    Route::middleware('is_workshop')->group(function () {
        Route::middleware('is_manager')->group(function () {
        });
    });

    Route::middleware('is_manufacture')->group(function () {
        Route::middleware('is_manager')->group(function () {
        });
    });
});
