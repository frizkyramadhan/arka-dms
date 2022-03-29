<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SeriesController;
use PHPUnit\TextUI\XmlConfiguration\Group;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TransmittalController;

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

Route::middleware('auth')->group(function(){
    Route::get('/', function () {
        return view('home' , ['title' => 'Dashboard']);
    });
    Route::get('transmittals/data', [TransmittalController::class, 'data'])->name('transmittals.data');
    Route::get('transmittals/list', [TransmittalController::class, 'getTransmittals'])->name('transmittals.list');
    Route::get('transmittals/trash', [TransmittalController::class, 'trash'])->middleware('admin');
    Route::get('transmittals/restore/{id?}', [TransmittalController::class, 'restore'])->middleware('admin');
    Route::get('transmittals/delete/{id?}', [TransmittalController::class, 'delete'])->middleware('admin');
    Route::get('transmittals/print/{id?}', [TransmittalController::class, 'print']);
    Route::post('transmittals/{id?}/delivery', [TransmittalController::class, 'add_delivery']);
    Route::put('transmittals/{transmittal_id?}/delivery/{id?}', [TransmittalController::class, 'edit_delivery']);
    Route::get('transmittals/{transmittal_id?}/delivery/delete/{id?}', [TransmittalController::class, 'delete_delivery']);
    Route::get('transmittals/getReceiver', [TransmittalController::class, 'getReceiver'])->name('transmittals.getReceiver');
    Route::resource('transmittals', TransmittalController::class);

    // route tracking transmittal
    Route::get('/trackings', [TrackingController::class, 'index'])->name('trackings.index');
    Route::get('/trackings/json', [TrackingController::class, 'json_trackings'])->name('trackings.json');
    // Route::resource('trackings', TrackingController::class);

});

Route::middleware('admin')->group(function(){
    Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::put('companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('companies/{id}', [CompanyController::class, 'delete'])->name('companies.delete');
        
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('series', SeriesController::class)->except(['show']);

    Route::resource('users', UserController::class)->except(['show']);
    
});