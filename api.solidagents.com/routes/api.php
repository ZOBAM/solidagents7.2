<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\PropertiesController;
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

Route::middleware('auth:api')->get('/users', function (Request $request) {
    //return ['Name' => 'Donzoby'];
    return $request->user()->id;
});
Route::post('login', [LoginController::class,'login']);
Route::post('register', [RegisterController::class,'signup']);
Route::middleware('auth:api')->get('login', "Api\LoginController@index");
Route::get('/properties/{type?}/{id?}',[PropertiesController::class, 'index']);
Route::middleware('auth:api')->post('/properties', [PropertiesController::class, 'store']);
Route::middleware('auth:api')->delete('/properties/{id}', [PropertiesController::class, 'delete']);
