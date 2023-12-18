<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
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
Route::get('/upload', [SalesController::class, 'index']);
Route::post('/uploadCsv', [SalesController::class, 'uploadCsv']);
Route::get('/getAllBatches', [SalesController::class, 'getAllBatches']);