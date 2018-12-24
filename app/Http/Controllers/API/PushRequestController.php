<?php

namespace App\Http\Controllers\API;

use App\OtpTransaction;
use App\TempTransactionId;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Webpatser\Uuid\Uuid;

class PushRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->all();
        $array = [
                "servicekey" => 'e5f2ba4fad93434b80aac53be1eaf321',
                "msisdn" => $request['msisdn'],
                "serviceName" => 'CESFCOACHLAND',
                "referenceCode" => Uuid::generate()->string, // unique mal mast
                "shortCode" => '984068210',
                "contentId" => Uuid::generate()->string, // unique mal mast
                "code" => '',  // nt charge code
                "amount" => $request['amount'],
                "description" => $request['description']
        ];

        $response = Curl::to('https://charging.atiehcom.ir/otp/request')
            ->withHeader('user: coachland456')
            ->withHeader('password: 9a51a663fa7c')
            ->withData($array)->asJson()->post();


        if ($response != false) {
            if ($response['statusCode'] == 200){
                $user_id = auth('api')->user()->id;
                OtpTransaction::create([
                    'user_id' => $user_id,
                    'otp_transaction_id' => $response['OTPTransactionId']
                ]);
                return response()->json('otp_transaction_id received.', 200);
            }
            return response()->json($response, $response['statusCode']);
        }
        else
            return response()->json('Bad request.', 400);
    }


    public function subscribe_request(Request $request)
    {
        $request->validate([
            'msisdn' => 'required'
        ]);
        $request = $request->all();
        $array = [
            "servicekey" => 'e5f2ba4fad93434b80aac53be1eaf321',
            "msisdn" => $request['msisdn'],
            "serviceName" => 'CESFCOACHLAND',
            "referenceCode" => Uuid::generate()->string, // unique mal mast
            "shortCode" => '984068210',
            "contentId" => Uuid::generate()->string, // unique mal mast
            "code" => '',  // nt charge code
            "amount" => '0',
            "description" => ''
        ];

        $response = Curl::to('https://charging.atiehcom.ir/otp/request')
            ->withHeader('user: coachland456')
            ->withHeader('password: 9a51a663fa7c')
            ->withData($array)->asJson()->post();


        if ($response != false) {
            if ($response['statusCode'] == 200){
                TempTransactionId::create([
                    'msisdn' => $request['msisdn'],
                    'otp_transaction_id' => $response['OTPTransactionId']
                ]);
                return response()->json('otp_transaction_id received.', 200);
            }
            return response()->json($response, $response['statusCode']);
        }
        else
            return response()->json('Bad request.', 400);
    }







    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
