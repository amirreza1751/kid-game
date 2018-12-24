<?php

namespace App\Http\Controllers\API;

use App\OtpTransaction;
use App\TempTransactionId;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Webpatser\Uuid\Uuid;

class ChargingConfirmationController extends Controller
{
    /**
     * Display a listing of the resource.
     *                                                          displays user's transactions
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mobile_number = auth('api')->user()->mobile_number;
        $response = Curl::to('https://charging.atiehcom.ir/user?query=getLastTransactions&phone='.$mobile_number.'&shortcode=984068210')
            ->withHeader('user: coachland456')
            ->withHeader('password: 9a51a663fa7c')
            ->get();

        return response()->json($response, 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'transactionPIN' => 'required'
        ]);
        $user = auth('api')->user();

        $otp_transaction = OtpTransaction::where('user_id',$user->id)->latest()->first();
        $request = $request->all();
        $array = [
            "servicekey" => 'e5f2ba4fad93434b80aac53be1eaf321',
            "msisdn" => $user->msisdn,
            "otpTransactionId" => $otp_transaction->otp_transaction_id,
            "referenceCode" => 'test',
            "shortCode" => '984068210',
            "transactionPIN" => $request['transactionPIN'],
        ];


        $response = Curl::to('https://charging.hub.ir/otp/confirm')
            ->withHeader('user: coachland456')
            ->withHeader('password: 9a51a663fa7c')
            ->withData($array)->asJson()->post();

        if (!$response) {
            return response()->json($response, $response['statusCode']);
        }
        else
            return response()->json('Bad request.', 400);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe_confirm(Request $request)
    {
        $request->validate([
            'msisdn' => 'required',
            'transactionPIN' => 'required'
        ]);

        $request = $request->all();
        $otp_transaction = TempTransactionId::where('msisdn',$request['msisdn'])->latest()->first();
        $array = [
            "servicekey" => 'e5f2ba4fad93434b80aac53be1eaf321',
            "msisdn" => $request['msisdn'],
            "otpTransactionId" => $otp_transaction->otp_transaction_id,
            "referenceCode" => Uuid::generate()->string,
            "shortCode" => '984068210',
            "transactionPIN" => $request['transactionPIN'],
        ];


        $response = Curl::to('https://charging.hub.ir/otp/confirm')
            ->withHeader('user: coachland456')
            ->withHeader('password: 9a51a663fa7c')
            ->withData($array)->asJson()->post();

        if (!$response) {
            if ($response['statusCode'] == 200){
                $user = User::where('mobile_number', $request['msisdn'])->first();
                if (!isset($user)){     /** dare check mikone ke age in karbar ghablan sabt shode bude dobare nasazesh. */
                    $new_user = User::create([
                        'mobile_number' => $request['msisdn']
                    ]);
                    $created_user = [ /** inja mikhaym user ro login konim ke behesh token ekhtesas bedim. */
                        'mobile_number' => $new_user->mobile_number,
                        'remember_me' => '1'
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($created_user);
                    $login_response = app('App\Http\Controllers\API\Auth\AuthController')->login($new_request);

                    return response()->json($login_response, 200);

                }
                /** momkene baadan bekhaym inja ham user ro login konim. */
                return response()->json('User subscribed in service and created successfully.', 200);
            }
            else /** halati ke response miad ama momkene subscribe nashode bashe. */
                return response()->json($response, $response['statusCode']);
        }
        else
            return response()->json('Bad request.', 400);
    }




    /**
     *
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
