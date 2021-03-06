<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletSharesController;
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
// create new wallet
Route::resource('wallets', WalletsController::class);
// add/update/delete information about shares in wallet, in function show display wallet details
Route::resource('wallet', WalletSharesController::class);
// table with all stocks info
Route::get('/table', [TableController::class, 'index'])->name('table');

Auth::routes();


