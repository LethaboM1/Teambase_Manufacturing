<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manufacture\JobsController;
use App\Http\Controllers\Manufacture\LabsController;
use App\Http\Controllers\Manufacture\DispatchController;
use App\Http\Controllers\Manufacture\ProductsController;
use App\Http\Controllers\Manufacture\ProductionController;
use App\Http\Controllers\Manufacture\Report\ManufactureReportsController;

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
        Route::post('users/delete', [ManagersController::class, 'delete_user']);
        Route::post('users/outofoffice', [ManagersController::class, 'outofoffice_user']);
    });

    Route::middleware('is_workshop')->group(function () {
        Route::middleware('is_manager')->group(function () {
        });
    });

    Route::middleware('is_manufacture')->group(function () {
        Route::middleware('is_products')->group(function () {
            /* */
            Route::get('products', [ProductsController::class, 'products']);
            Route::post('products/add', [ProductsController::class, 'add_product']);
            Route::post('products/save', [ProductsController::class, 'save_product']);
            Route::post('products/adjust', [ProductsController::class, 'adjust_product']);
            Route::post('products/delete', [ProductsController::class, 'delete_product']);

            /* Job Cards */
            Route::get('jobs', [JobsController::class, 'jobs']);
            Route::get('jobs/create', [JobsController::class, 'create_job']);
            Route::post('jobs/create', [JobsController::class, 'add_job']);

            Route::get('job/{job}', [JobsController::class, 'view_job']);
            Route::post('job/{job}', [JobsController::class, 'save_job']);

            /* Production */
            Route::get('productions', [ProductionController::class, 'productions']);
            Route::get('productions/create', [ProductionController::class, 'create_batch']);

            /* Labs */
            Route::get('labs/create', [LabsController::class, 'create_lab']);

            /* Dispatch */
            Route::get('dispatch/ready', [DispatchController::class, 'ready']);
            Route::get('dispatch/orders', [DispatchController::class, 'orders']);

            /* Reports */
            Route::get('report/stock-reports', [ManufactureReportsController::class, 'report_stock']);
            Route::get('report/order-reports', [ManufactureReportsController::class, 'report_order']);
            Route::get('report/lab-reports', [ManufactureReportsController::class, 'report_lab']);
            Route::get('report/dispatch-reports', [ManufactureReportsController::class, 'report_dispatch']);
        });
    });
});
