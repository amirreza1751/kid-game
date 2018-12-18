<?php

namespace App\Http\Controllers\API;

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
        $request = $request->all();

        $notification = Notification::create($request);
        if ($request['event-type'] == 1.1){     /** yani karbar subscribe shode */
            $user = User::where('mobile_number', $request['msisdn'])->first();
            if (!isset($user)){     /** dare check mikone ke age in karbar ghablan sabt shode bude dobare nasazesh. */
                User::create([
                    'mobile_number' => $request['msisdn']
                ]);
            }
        }
        if ($request['event-type'] == 1.2){     /** yani karbar un-subscribe shode */
            $user = User::where('mobile_number', $request['msisdn'])->first();
            if (isset($user)){     /** dare check mikone ke age in karbar vojud dare pakesh kone. */
                $user->delete();
            }
        }
        return response()->json($notification, 200);

        /**
        if request array changed, we can use this array
         *
            $array = [
            "sid" => $request['sid'],
            "msisdn" => $request['msisdn'],
            "trans-id" => $request['trans-id'],
            "trans-status" => $request['trans-status'],
            "datetime" => $request['datetime'],
            "channel" => $request['channel'],
            "shortcode" => $request['shortcode'],
            "keyword" => $request['keyword'],
            "charge-code" => $request['charge-code'],
            "billed-price-point" => $request['billed-price-point'],
            "event-type" => $request['event-type'],
            "validity" => $request['validity'],
            "next_renewal_date" => $request['next_renewal_date'],
            "status" => $request['status'],
            ];
         *
         */

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
