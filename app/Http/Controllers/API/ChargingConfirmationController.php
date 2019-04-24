<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\OtpTransaction;
use App\TempTransactionId;
use App\User;
use function Composer\Autoload\includeFile;
use GuzzleHttp\Client;
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
    public function index(Request $request)
    {
        $mobile_number = $request['msisdn'];
        $client1 =  new Client();
        $r = $client1->request('GET', 'https://31.47.36.141:10443/user?query=getLastTransactions&phone=' . $mobile_number . '&shortcode=984068210', ['auth'=>['coachland456', '9a51a663fa7c'], 'verify'=> false]);
        $r =  preg_replace('/\\\/','',$r->getBody()->getContents());
        $r =  preg_replace('/\\"{/','{',$r);
        $r =  preg_replace('/\\}"/','}',$r);

        return $r;


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $request->validate([
//            'transactionPIN' => 'required'
//        ]);
//        $user = auth('api')->user();

//        $otp_transaction = OtpTransaction::where('user_id',$user->id)->latest()->first();
        $request = $request->all();
        $array = [
            "servicekey" => 'e5f2ba4fad93434b80aac53be1eaf321',
//            "msisdn" => $user->msisdn,
            "msisdn" => '989126774496',
//            "otpTransactionId" => $otp_transaction->otp_transaction_id,
            "otpTransactionId" => '15466473208690',
            "referenceCode" => 'test',
            "shortCode" => '984068210',
//            "transactionPIN" => $request['transactionPIN'],
            "transactionPIN" => '0530',
        ];


        $client1 = new Client();
        $r = $client1->request('POST', 'https://31.47.36.141:10443/otp/confirm', ['auth' => ['coachland456', '9a51a663fa7c'], 'form_params' => $array, 'verify' => false]);
        return $r->getBody();

        if (!$response) {
            return response()->json($response, $response['statusCode']);
        } else
            return response()->json('Bad request.', 400);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe_confirm(Request $request)
    {
        $request->validate([
            'TransactionPin' => 'required',
            'msisdn' => 'required'
        ]);



        $trace_id = TempTransactionId::where('msisdn', $request->msisdn)->latest()->first()->otp_transaction_id;


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sdp.rashin.org/api/Otp/Charge",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"TransactionPin": "'.$request->TransactionPin.'", "traceId": "'.$trace_id.'"}',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "apikey: 5E6FA16F-9AC6-4F70-98CA-24092D3B1030",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['status'=> '0','message'=> 'trouble in request.'], 400) ;
        } else {
            if (\GuzzleHttp\json_decode($response)->status == '1'){  /** if success */
//                return \GuzzleHttp\json_decode($response)->traceId;
                TempTransactionId::create([
                    'msisdn' => $request->msisdn,
                    'otp_transaction_id' => \GuzzleHttp\json_decode($response)->traceId
                ]);
                return response()->json(['status'=> '1','message'=> 'successful'], 200) ;
            } else
//                return response()->json(['status'=> '0','message'=> 'trouble in request.'], 400) ;
            dd($response);
        }

        /** log */
        Log::create([
            'msisdn' => $request['msisdn'],
            'client_input' => \GuzzleHttp\json_encode($array),
            'server_response' => $r->getBody()->getContents()
        ]);
        /** end log */
        $r = \GuzzleHttp\json_decode($r->getBody());
        if ($r->statusInfo->statusCode != 200){
            return response()->json(['status' => 'trouble in confirmation.', 'response' => $r], 500);
        }



                $user = User::where('mobile_number', $request['msisdn'])->first();
                if (!isset($user)) {
                    /** dare check mikone ke age in karbar ghablan sabt shode bude dobare nasazesh. */
                    $new_user = User::create([
                        'mobile_number' => $request['msisdn']
                    ]);
                    $created_user = [/** inja mikhaym user ro login konim ke behesh token ekhtesas bedim. */
                        'mobile_number' => $new_user->mobile_number,
                        'remember_me' => '1'
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($created_user);
                    $login_response = app('App\Http\Controllers\API\Auth\AuthController')->login($new_request);

                    return response()->json(['login response' => $login_response->original, 'status' => 'Welcome to coachland!'], 200);

                } else {
                    /** useri ke ghablan bude ama masalan uninstall karde va dobare bargashte. */
                    $existing_user = [
                        'mobile_number' => $user->mobile_number,
                        'remember_me' => '1'
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($existing_user);
                    $login_response = app('App\Http\Controllers\API\Auth\AuthController')->login($new_request);

//                    array_push($login_response, 'status', 'Welcome back to coachland!');
                    return response()->json(['login response' => $login_response->original, 'status' => 'Welcome back to coachland!'], 200);
                }


    }


    /**
     *
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
