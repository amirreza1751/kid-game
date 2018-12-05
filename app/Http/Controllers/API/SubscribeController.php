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
                "sid" => "3941",
                "trans-id" => "PROV_151858681695675611811",
                "status" => "0",
                "base-price-point" => "0",
                "msisdn" => "989140169772",
                "keyword" => "",
                "validity" => 1,
                "next_renewal_date" => "2021-07-25 23:41:46.691",
                "shortcode" => "",
                "billed-price-point" => "",
                "trans-status" => 0,
                "chargeCode" => "ATIUSUBSUBCATICHAN",
                "datetime" => "2018-02-14 09:10:20",
                "event-type" => "1.2",
                "channel" => "TAJMI"
            ]
        ];

        $response = Curl::to('')
            ->withData($array)->get();

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
