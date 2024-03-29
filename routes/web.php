<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use PHPUnit\TextUI\XmlConfiguration\Group;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TransmittalController;
use App\Http\Controllers\DeliveryOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('register', [RegisterController::class, 'store']);

Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('transmittals/data', [TransmittalController::class, 'data'])->name('transmittals.data');
    Route::get('transmittals/list', [TransmittalController::class, 'getTransmittals'])->name('transmittals.list');
    Route::get('transmittals/trash', [TransmittalController::class, 'trash'])->name('transmittals.trash');
    Route::get('transmittals/restore/{id?}', [TransmittalController::class, 'restore'])->name('transmittals.restore');
    Route::get('transmittals/delete/{id?}', [TransmittalController::class, 'delete'])->name('transmittals.delete');
    Route::get('transmittals/print/{id?}', [TransmittalController::class, 'print']);
    Route::get('transmittals/email/{id?}', [TransmittalController::class, 'email']);
    Route::get('transmittals/getReceiver', [TransmittalController::class, 'getReceiver'])->name('transmittals.getReceiver');
    Route::resource('transmittals', TransmittalController::class);

    // route tracking transmittal
    Route::get('/trackings', [TrackingController::class, 'index'])->name('trackings.index');
    Route::get('/trackings/json', [TrackingController::class, 'json_trackings'])->name('trackings.json');
    // Route::resource('trackings', TrackingController::class);

    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('units', UnitController::class)->except(['show']);

    Route::get('deliveries/send', [DeliveryController::class, 'send'])->name('deliveries.send');
    Route::get('deliveries/receive', [DeliveryController::class, 'receive'])->name('deliveries.receive');
    Route::get('deliveries/searchGet/{receiptNo}', [DeliveryController::class, 'searchGet']);
    Route::post('deliveries/search', [DeliveryController::class, 'search'])->name('deliveries.search');
    Route::get('deliveries/getRole/{id}', [DeliveryController::class, 'getRole']);
    Route::post('deliveries', [DeliveryController::class, 'store'])->name('deliveries.store');
    Route::patch('deliveries/{delivery}', [DeliveryController::class, 'update'])->name('deliveries.update');
    Route::delete('deliveries/{delivery}', [DeliveryController::class, 'destroy'])->name('deliveries.destroy');

    Route::get('delivery_orders/data', [DeliveryOrderController::class, 'data'])->name('delivery_orders.data');
    Route::get('delivery_orders/list', [DeliveryOrderController::class, 'getDeliveryOrders'])->name('delivery_orders.list');
    Route::resource('delivery_orders', DeliveryOrderController::class);
});
