<?php
namespace App\Http\Controllers\API\Auth;
use App\OauthAccessTokens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;
use Illuminate\Support\Facades\Route;


class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile_number' => 'required|unique:users',
        ]);
        $user = new User([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
        ]);
        $user->save();
        $created_user = [
            'mobile_number' => $user->mobile_number,
            'remember_me' => '1'
        ];
        $new_request = new \Illuminate\Http\Request();
        $new_request->replace($created_user);
        $login_response = app('App\Http\Controllers\API\Auth\AuthController')->login($new_request);
        return $login_response;
//        return response()->json([
//            'message' => 'Successfully created user!'
//        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        if(!Auth::attempt(['mobile_number' => $request->mobile_number]))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = User::where('mobile_number', $request->mobile_number)->first();

        /** inja miad token haye ghabliye in karbar ro age vojud dasht revoke mikone */
        $existing_token = OauthAccessTokens::where('user_id', $user->id)->where('revoked', '0')->get();
        if (count($existing_token)>0){
            foreach ($existing_token as $item) {
                $item->revoked = 1;
                $item->save();
            }
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(300);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}