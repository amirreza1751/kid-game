<?php

use App\OtpTransaction;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

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
//Route::post('/charging_request', 'API\PushRequestController@store');
//Route::post('/charging_confirm', 'API\ChargingConfirmationController@store');
Route::get('/subscribe_request', 'API\PushRequestController@subscribe_request');
Route::post('/subscribe_confirm', 'API\ChargingConfirmationController@subscribe_confirm');
Route::get('/tlist', 'API\ChargingConfirmationController@index');

//Route::middleware('auth:api')->post('/test', function (){
//    $user = auth('api')->user();
////    $temp = OtpTransaction::where('user_id',$user->id)->latest()->first();
//    return response()->json($user->mobile_number, 200);
//});

//Route::get('/uuid','PusRequestController@subscribe_controller');
Route::get('/uuid', function (){
    return mt_rand(1000000000000,9999999999999);
});






Route::get('/test',function (){
    $client1 =  new Client();
//$r = $client1->request('GET', 'http://uinames.com/api/');
$r = $client1->request('GET', 'https://github.com/amirreza1751');
//$r = \GuzzleHttp\json_decode($r->getBody());
//return $r->name;
return $r->getBody();

});

Route::get('/check',function (){
    $check_user = auth('api')->user();
    if (!isset($check_user)){
        return response()->json(['status'=>'0', 'description'=> 'mano bega.', 'imageUrl'=> 'https://test-ipv6.com/images/hires_ok.png','updateUrl'=>'update-url', 'appVersion'=> '3.1' ], 200);
    }
    else
        return response()->json(['status'=>'1', 'description'=> 'mano bega.', 'imageUrl'=> 'https://test-ipv6.com/images/hires_ok.png','updateUrl'=>'update-url', 'appVersion'=> '3.1'], 200);
});


Route::get('/getnotification', function(){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://sdp.rashin.org/api/Test/Notification",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Accept: application/json",
            "apikey: 5E6FA16F-9AC6-4F70-98CA-24092D3B1030",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return \GuzzleHttp\json_encode($response);
    }
});

