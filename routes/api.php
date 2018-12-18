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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'API\Auth\AuthController@login');
    Route::post('signup', 'API\Auth\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'API\Auth\AuthController@logout');
        Route::get('user', 'API\Auth\AuthController@user');
    });
});

//doc 1
Route::post('/sub_unsub', 'API\EventController@store');
Route::post('/renewal_charges', 'API\EventController@store');


//doc2
Route::post('/notification', 'API\NotificationController@store');



//doc3
Route::get('/push_request', 'API\PushRequestController@store');
Route::get('/confirm', 'API\ChargingConfirmationController@store');




