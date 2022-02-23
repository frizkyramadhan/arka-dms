<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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

Route::get('/', function () {
    return view('home');
});

// route company
Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
Route::put('companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
Route::delete('companies/{id}', [CompanyController::class, 'delete'])->name('companies.delete');


