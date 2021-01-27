<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StocksController;
use \App\Http\Controllers\TableController;
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

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/wallet',[WalletsController::class,'index'])->name('wallet');
Route::get('/create',[WalletsController::class,'create'])->name('create');
Route::post('/create',[WalletsController::class,'store'])->name('store');
Route::get('/wallet/{name}', [WalletsController::class, 'show'])->name('show');
Route::get('/add',[StocksController::class,'create'])->name('create-transaction');
Route::post('/add',[StocksController::class,'store'])->name('store-transaction');
Route::get('/table', [TableController::class, 'index'])->name('table');
Route::put('/edit', [StocksController::class,'update'])->name('update');
Route::get('/edit', [StocksController::class,'edit'])->name('edit');
Auth::routes();


