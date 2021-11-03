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

        require_once 'HTTP/Request2.php';
        $request = new HTTP_Request2();
        $request->setUrl('https://sandbox.ipaymu.com/api/v2/payment');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
        'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
        'Content-Type' => 'application/json',
        'signature' => '[object Object]',
        'va' => '0000001294658309',
        'timestamp' => '20191209155701'
        ));
        $request->addPostParameter(array(
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
        'pickupAddress' => 'Jakarta'
        ));
        try {
        $response = $request->send();
        if ($response->getStatus() == 200) {
            echo $response->getBody();
        }
        else {
            echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
            $response->getReasonPhrase();
        }
        }
        catch(HTTP_Request2_Exception $e) {
        echo 'Error: ' . $e->getMessage();
        }
                
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
