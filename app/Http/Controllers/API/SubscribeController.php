<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;

class SubscribeController extends Controller
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
        $array = [
            'Sub_unsub' => [
                "sid" => $request['sid'],
                "trans-id" => $request['trans-id'],
                "status" => $request['status'],
                "base-price-point" => $request['base-price-point'],
                "msisdn" => $request['msisdn'],
                "keyword" => $request['keyword'],
                "validity" => $request['validity'],
                "next_renewal_date" => $request['next_renewal_date'],
                "shortcode" => $request['shortcode'],
                "billed-price-point" => $request['billed-price-point'],
                "trans-status" => $request['trans-status'],
                "chargeCode" => $request['chargeCode'],
                "datetime" => $request['datetime'],
                "event-type" => $request['event-type'],
                "channel" => $request['channel']
            ]
        ];

        $response = Curl::to('')->withData($array)->get();

        return response()->json($response, 200);


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
