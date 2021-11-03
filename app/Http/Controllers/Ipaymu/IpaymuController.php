<?php

namespace App\Http\Controllers\Ipaymu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ipaymu.com/api/v2/payment',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('product[]' => 'Baju','qty[]' => '1','price[]' => '10000','description[]' => 'Baju1','returnUrl' => 'https://ipaymu.com/return','notifyUrl' => 'https://ipaymu.com/notify','cancelUrl' => 'https://ipaymu.com/cancel','referenceId' => 'ID1234','weight[]' => '1','dimension[]' => '1:1:1','buyerName' => 'putu','buyerEmail' => 'putu@mail.com','buyerPhone' => '08123456789','pickupArea' => '80117','pickupAddress' => 'Jakarta'),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'signature: [object Object]',
            'va: 1179000899',
            'timestamp: 20191209155701'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        
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
