<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\OtpTransaction;
use App\TempTransactionId;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Psr\Http\Message\StreamInterface;
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
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $user = auth('api')->user();
        $request = $request->all();
        $array = [
                "servicekey" => 'e5f2ba4fad93434b80aac53be1eaf321',
                "msisdn" => $user->mobile_number,
                "serviceName" => 'CESFCOACHLAND',
                "referenceCode" => Uuid::generate()->string, // unique mal mast
                "shortCode" => '984068210',
                "contentId" => Uuid::generate()->string, // unique mal mast
                "code" => '',  // nt charge code
                "amount" => $request['amount'],
                "description" => 'request for charging'
        ];


        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://31.47.36.141:10443/otp/request', ['auth' =>  ['coachland456', '9a51a663fa7c']]);
        echo $res->getStatusCode(); // 200
        return $res->getBody();



//        if ($response != false) {
//            if ($response['statusCode'] == 200){
//                OtpTransaction::create([
//                    'user_id' => $user->id,
//                    'otp_transaction_id' => $response['OTPTransactionId']
//                ]);
//                return response()->json('otp_transaction_id received.', 200);
//            }
//            return response()->json($response, $response['statusCode']);
//        }
//        else
//            return response()->json('Bad request.', 400);
    }


    public function subscribe_request(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sdp.rashin.org/api/Otp/Push",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"msisdn": "989126774496", "traceId": "'.mt_rand(1000000000000,9999999999999).'", "contentId": "'.mt_rand(1000000000000,9999999999999).'", "serviceName": "کید گیم","amount": 5000, "chargeCode": "HUBSUBCHUBKIDGAME", "description": "test"}',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "apikey: 5E6FA16F-9AC6-4F70-98CA-24092D3B1030",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo \GuzzleHttp\json_decode($response)->message;
        }
echo "test";

return;









//        $request->validate([
//            'msisdn' => 'required'
//        ]);
//
//
//        $request = $request->all();
        $array = [
            "msisdn" => '989126774496',
//            "traceId" => uniqid(), // unique mal mast
            "traceId" => '', // unique mal mast
//            "contentId" => uniqid(), // unique mal mast
            "contentId" => '', // unique mal mast
            "serviceName" => 'کید گیم',
            "amount" => '5000',
            "chargeCode" => 'HUBSUBCHUBKIDGAME',  //  charge code register
            "description" => 'tst message from IMI'
//            "shortCode" => '98405576',
//            "servicekey" => '393311374e7640b1977f5368e3e9f13a',
        ];


$array = \GuzzleHttp\json_encode($array);

        $client1 =  new Client();
        $r = $client1->request(
            'POST',
            'http://sdp.rashin.org/api/Otp/Push',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'apikey' => '5E6FA16F-9AC6-4F70-98CA-24092D3B1030'
                ],
                'form_params' => $array,
                'verify' => false
            ]
        );
        return response()->json(['status' => 'otp-transaction id received.', 'response' => $r], 200);

        /** log */
        Log::create([
            'msisdn' => $request['msisdn'],
            'client_input' => \GuzzleHttp\json_encode($array),
            'server_response' => $r->getBody()->getContents()
        ]);
        /** end log */

        $r = \GuzzleHttp\json_decode($r->getBody());
        if ($r->statusInfo->statusCode != 200){
            return response()->json(['status' => 'trouble in request.', 'response' => $r], 500);
        }
        $response = $r->statusInfo;
        TempTransactionId::create([
            'msisdn' => $request['msisdn'],
            'otp_transaction_id' => $response->OTPTransactionId
        ]);



        return response()->json(['status' => 'otp-transaction id received.', 'response' => $r], 200);

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
