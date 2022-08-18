<?php

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/setClient', [Api::class, 'setClient']);
Route::post('/getClients', [Api::class, 'getClients']);

Route::post('/setGoods', [Api::class, 'setGoods']);
Route::post('/getGoods', [Api::class, 'getGoods']);

Route::post('/setOrder', [Api::class, 'setOrder']);
Route::post('/setOrderStatus', [Api::class, 'setOrderStatus']);

Route::post('/getOrders', [Api::class, 'getOrders']);
Route::post('/getPriceBook', [Api::class, 'getPriceBook']);

Route::post('/getModel', [Api::class, 'getModel']);

