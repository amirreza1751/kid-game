<?php

namespace App\Http\Controllers\API;

use App\Notification;
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
