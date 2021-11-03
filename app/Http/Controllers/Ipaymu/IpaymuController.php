<?php

namespace App\Http\Controllers\Ipaymu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use DB;

class IpaymuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $va           = '0000001294658309'; //get on iPaymu dashboard
        $secret       = 'SANDBOX93D35640-3D9B-4D79-BDC8-0DBDCCEA7625-20211103065253'; //get on iPaymu dashboard

        $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method

        //Request Body//
        $body['product']    = array('headset', 'softcase');
        $body['qty']        = array('1', '3');
        $body['price']      = array('100000', '20000');
        $body['returnUrl']  = 'https://mywebsite.com/thankyou';
        $body['cancelUrl']  = 'https://mywebsite.com/cancel';
        $body['notifyUrl']  = 'https://mywebsite.com/notify';
        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'signature' => $signature ,
            'va' => '1179000899',
            'timestamp' => '20191209155701'
        ])->post('https://sandbox.ipaymu.com/api/v2/payment', [
            'product[]' => 'Baju',
            'qty[]' => '1',
            'price[]' => '10000',
            'description[]' => 'Baju1',
            'returnUrl' => 'https://ipaymu.com/return',
            'notifyUrl' => 'https://ipaymu.com/notify',
            'cancelUrl' => 'https://ipaymu.com/cancel',
            'referenceId' => 'ID1234',
            'weight[]' => '1',
            'dimension[]' => '1:1:1',
            'buyerName' => 'putu',
            'buyerEmail' => 'putu@mail.com',
            'buyerPhone' => '08123456789',
            'pickupArea' => '80117',
            'pickupAddress' => 'Jakarta',
        ]);

        print $response;

        
                
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
