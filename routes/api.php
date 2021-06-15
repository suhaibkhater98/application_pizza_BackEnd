<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
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
Route::post('register' , [UserController::class , 'register']);
Route::post('login' , [UserController::class , 'login']);

Route::get('get_items' , [ItemController::class , 'get_items']);

Route::post('make_order' , [OrderController::class , 'store_order']);

//getting order just for who logged in and authorized
Route::post('get_orders' , [OrderController::class , 'get_orders'])->middleware('auth:api');
