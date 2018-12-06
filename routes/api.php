<?php

use App\User;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//doc 1
Route::get('/sub_unsub', 'API\SubscribeController@store');


//doc2
Route::get('/notification', 'API\NotificationController@store');



//doc3
Route::get('/push_request', 'API\PushRequestController@store');
Route::get('/confirm', 'API\ChargingConfirmationController@store');




