<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $request = $request->all();
        $array = [
                "servicekey" => $request['servicekey'],
                "msisdn" => $request['msisdn'],
                "serviceName" => $request['serviceName'],
                "referenceCode" => $request['referenceCode'],
                "shortCode" => $request['shortCode'],
                "contentId" => $request['contentId'],
                "code" => $request['code'],
                "amount" => $request['amount'],
                "description" => $request['description']
        ];

        $response = Curl::to('http://site.com/api/?user=USER&password=PASSWORD')
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
