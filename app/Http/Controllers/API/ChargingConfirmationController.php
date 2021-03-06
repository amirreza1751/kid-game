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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe_confirm(Request $request)
    {
        $request->validate([
            'TransactionPin' => 'required',
            'msisdn' => 'required'
        ]);

        $trace_id = TempTransactionId::where('msisdn', $request->msisdn)->latest()->first();
        if ($trace_id == null){
            return response()->json(['status'=> '111','message'=> 'phone number is incorrect.'], 400);
        }
        $trace_id = $trace_id->otp_transaction_id;


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
            return response()->json(['status'=> '112','message'=> 'trouble in request.'], 400) ;
        } else {

            /** log */
            Log::create([
                'msisdn' => $request->msisdn,
                'client_input' => '{"TransactionPin": "'.$request->TransactionPin.'", "traceId": "'.$trace_id.'"}',
                'server_response' => $response
            ]);
            /** end log */

            if (\GuzzleHttp\json_decode($response)->status == '1'){  /** if success */
                $this->send_sms($request->msisdn, 'به سرویس کیدگیم خوش آمدید.');
                $user = User::where('mobile_number', $request->msisdn)->first();
                if ($user == null){  /** user subscribe shode va tu db ham nist. pas bayad sign up beshe */
                    $user_to_create = [
                        'mobile_number' => $request->msisdn,
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($user_to_create);
                    $signup_response = app('App\Http\Controllers\API\Auth\AuthController')->signup($new_request);
                    return $signup_response;
                } else { /** user subscribe shode vali tu db e ma bude. pas bayad login beshe faghat */
                    $created_user = [
                        'mobile_number' => $user->mobile_number,
                        'remember_me' => '1'
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($created_user);
                    $login_response = app('App\Http\Controllers\API\Auth\AuthController')->login($new_request);
                    return $login_response;
                }
            } else if (\GuzzleHttp\json_decode($response)->status == '2' && \GuzzleHttp\json_decode($response)->message == 'Service Error(Subscription already exists.)') {
                $this->send_sms($request->msisdn, 'به سرویس کیدگیم خوش آمدید.');
                $user = User::where('mobile_number', $request->msisdn)->first();
                if ($user != null){ /** user subscribe bude az ghabl va tu db ham hast. pas bayad login beshe. */
                    $created_user = [
                        'mobile_number' => $user->mobile_number,
                        'remember_me' => '1'
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($created_user);
                    $login_response = app('App\Http\Controllers\API\Auth\AuthController')->login($new_request);
                    return $login_response;
                } else { /** user subscribe bude az ghabl ama tu db nist. pas bayad sign up beshe */
                    $user_to_create = [
                        'mobile_number' => $request->msisdn,
                    ];
                    $new_request = new \Illuminate\Http\Request();
                    $new_request->replace($user_to_create);
                    $signup_response = app('App\Http\Controllers\API\Auth\AuthController')->signup($new_request);
                    return $signup_response;
                }
            } else
                return response()->json(['status'=> '113','message'=> 'otp code is incorrect'], 400) ;

        }


    }


    public function send_sms($msisdn, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sdp.rashin.org/api/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"Msisdn": "'.$msisdn.'", "TraceId": "'.mt_rand(1000000000000,9999999999999).'", "Message" : "'.$message.'" }',
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

        /** log */
        Log::create([
            'msisdn' => $msisdn,
            'client_input' => '{"Msisdn": "'.$msisdn.'", "TraceId": "'.mt_rand(1000000000000,9999999999999).'", "Message" : "'.$message.'" }',
            'server_response' => $response
        ]);
        /** end log */

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }


}
