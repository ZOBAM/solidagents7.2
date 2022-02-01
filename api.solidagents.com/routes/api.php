<?php

use App\Classes\PropertyDataClass;
use App\Http\Controllers\Api\{CallRequestController, LikesController, LoginController, ChatsController, NotificationsController, RegisterController, PropertiesController, PropertyRequestController, SearchController, VerifyController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Property_request;

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
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'signup']);
Route::middleware(['auth:api'])->group(function () {
    Route::get('login', "Api\LoginController@index");
    Route::post('request_call', [CallRequestController::class, 'request_call']);
    Route::post('request_property', [PropertyRequestController::class, 'request_property']);
    Route::post('verify', [VerifyController::class, 'verify']);
    Route::post('/properties', [PropertiesController::class, 'store']);
    Route::post('/like', [LikesController::class, 'like']);
    Route::post('/messages', [ChatsController::class, 'message']);
    Route::get('/messages/{user_id?}', [ChatsController::class, 'get_messages']);
    Route::post('/notifications', [NotificationsController::class, 'create']);
    Route::post('/notifications/{user_id}', [NotificationsController::class, 'get_notifications']);
    Route::delete('/properties/{id}', [PropertiesController::class, 'delete']);
});
Route::get('requests/{id?}', [PropertyRequestController::class, 'get_requests']);
Route::get('/properties/{type?}/{id?}/{user_id?}', [PropertiesController::class, 'index']);
Route::get('search', [SearchController::class, 'search']);
Route::get('states', function () {
    $data_class = new PropertyDataClass;
    return $data_class->get_states();
});
