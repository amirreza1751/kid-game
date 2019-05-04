<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
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
//        Log::create([
//            'msisdn' => 'test',
//            'client_input' => 'test',
//            'server_response' => $request->all()
//        ]);

        Notification::create($request);

        if ($request['EventType'] == 1.1){     /** yani karbar subscribe shode */
            $user = User::wheremid('mobile_number', $request['Msisdn'])->first();
            if (!isset($user)){     /** dare check mikone ke age in karbar ghablan sabt shode bude dobare nasazesh. */
                User::create([
                    'mobile_number' => $request['Msisdn']
                ]);
            }
        }
        if ($request['EventType'] == 1.2){     /** yani karbar un-subscribe shode */
            $user = User::where('mobile_number', $request['Msisdn'])->first();
            if (isset($user)){     /** dare check mikone ke age in karbar vojud dare pakesh kone. */
                $user->delete();
            }
        }
        return response()->json(['status' => 'notification received.'], 200);



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
