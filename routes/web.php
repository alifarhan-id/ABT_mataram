<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WajibPajakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerhitunganController;

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



Route::get('/',[DashboardController::class, 'index'])->name('dashboard');
Route::get('/wajib-pajak/index',[WajibPajakController::class, 'index'])->name('wp-index');
Route::get('/wajib-pajak/findByNpwp/{npwp}',[WajibPajakController::class, 'doGetWajibPajakByNpwp'])->name('wp-findByNpwp');
Route::get('/perhitungan',[PerhitunganController::class, 'index'])->name('perhitungan');
Route::get('/perhitungan/list',[PerhitunganController::class, 'doGetPerhitungan'])->name('perhitungan_list');
Route::post('/perhitungan/hitung',[PerhitunganController::class, 'doHitung'])->name('perhitungan_hitung');
Route::post('/perhitungan/create',[PerhitunganController::class, 'doCreate'])->name('perhitungan_create');
