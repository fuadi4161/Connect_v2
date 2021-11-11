<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class ApiUsersController extends Controller
{
    public function getProfilUser(){

        $data = DB::table('users')->where('users.id', '=',  Auth::user()->id)
        ->leftJoin('client', 'users.id', '=', 'client.id_user')
        ->select('users.*','client.internet','client.catv','client.nominal')
        ->get();
        foreach ($data as $value) {
            $user = $value;
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'pesan' => 'get data Berhasil'
        ], 200);

    }

    public function getUsers(){
        $data = DB::table('users')->leftJoin('client', 'users.id', '=', 'client.id_user')
        ->select('users.*','client.internet','client.catv','client.nominal')
        ->get();

        return response()->json($data, 200);
    }

    public function addUsers(Request $request){

    }
    
    public function editUser($id){
        
    }

    public function hapusUser($id){

    }
}
