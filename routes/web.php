<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManagersController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manufacture\JobsController;
use App\Http\Controllers\Manufacture\LabsController;
use App\Http\Controllers\Manufacture\BatchesController;
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
        Route::get('settings', [ManagersController::class, 'setting']);
        Route::post('settings/save', [ManagersController::class, 'save_settings']);
    });

    Route::middleware('is_workshop')->group(function () {
        Route::middleware('is_manager')->group(function () {
        });
    });
    
    Route::middleware('is_manufacture')->group(function () {
        Route::middleware('is_manager')->group(function () {
            /* Suppliers */
            Route::get('suppliers', [SupplierController::class, 'suppliers']);
            Route::post('suppliers/add', [SupplierController::class, 'add_supplier']);
            Route::post('suppliers/save', [SupplierController::class, 'save_supplier']);
            Route::post('suppliers/delete', [SupplierController::class, 'delete_supplier']);

            /* Customers */
            Route::get('customers', [CustomerController::class, 'customers']);
            Route::post('customers/add', [CustomerController::class, 'add_customer']);
            Route::post('customers/save', [CustomerController::class, 'save_customer']);
            Route::post('customers/delete', [CustomerController::class, 'delete_customer']);
        });

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

            /* Batches */
            Route::get('batches', [BatchesController::class, 'batches']);
            Route::get('batches/create', [BatchesController::class, 'create_batch']);
            Route::post('batches/create', [BatchesController::class, 'add_batch']);

            Route::get('batch/{batch}', [BatchesController::class, 'view_batch']);
            Route::post('batch/{batch}', [BatchesController::class, 'save_batch']);


            /* Production */
            Route::get('productions', [ProductionController::class, 'productions']);
            Route::get('productions/create', [ProductionController::class, 'create_batch']);

            /* Labs */
            Route::get('labs/batches', [LabsController::class, 'list_batches']);
            // Route::get('labs/create', [LabsController::class, 'create_lab']);
            Route::get('labs/batch/{batch}', [LabsController::class, 'view_batch']);
            Route::post('labs/add', [LabsController::class, 'add_lab']);

            /* Dispatch */
            Route::get('dispatches/new', [DispatchController::class, 'new']);

            Route::get('goods-receive/new', [DispatchController::class, 'new_goods']);

            Route::get('dispatches/delete/{dispatch}', [DispatchController::class, 'delete_dispatch']);
            Route::post('dispatches/new', [DispatchController::class, 'add_dispatch']);
            Route::post('dispatches/out/{dispatch}', [DispatchController::class, 'out_dispatch']);
            Route::post('dispatches/return/{dispatch}', [DispatchController::class, 'return_dispatch']);
            Route::post('dispatches/transfer/{dispatch}', [DispatchController::class, 'transfer_dispatch']);
            Route::get('dispatches/print/{dispatch}', [DispatchController::class, 'print_dispatch']);
            Route::post('dispatches/receiving-goods', [DispatchController::class, 'receiving_goods']);
            Route::post('dispatches/return-goods', [DispatchController::class, 'return_goods']);
            Route::get('dispatches/return-goods/{transaction}/print', [DispatchController::class, 'print_return']);

            Route::post('dispatches/received-goods/{transaction}', [DispatchController::class, 'received_goods']);
            // Route::get('dispatches/received-goods/{transaction}/print', [DispatchController::class, 'print_receipt']);

            //Route::get('dispatches/archive', [DispatchController::class, 'archive']);
            // Route::get('dispatch/{batch}', [DispatchController::class, 'batch_dispatch']);

            /* Reports */
            Route::get('report/stock-reports', [ManufactureReportsController::class, 'report_stock']);
            Route::get('report/order-reports', [ManufactureReportsController::class, 'report_order']);
            Route::get('report/lab-reports', [ManufactureReportsController::class, 'report_lab']);
            Route::get('report/dispatch-reports', [ManufactureReportsController::class, 'report_dispatch']);
            Route::post('report/dispatch-reports/print', [ManufactureReportsController::class, 'dispatchByDateReport']);
        });
    });    
});
