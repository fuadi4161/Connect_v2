<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class ApiAktivitasController extends Controller
{
    // Menampilkan semua aktivitas users
    public function getAktif(){

        $data = DB::table('aktivitas')
        ->leftJoin('users', 'users.id','=','aktivitas.user_id')
        ->select('users.name', 'aktivitas.*')
        ->get();

        return response()->json($data);

    }

    // Menampilkan Aktivitas berdasarkan users tersebut
    public function getByUsers(){
         $data = DB::table('aktivitas')->where('user_id', Auth::user()->id)->get();
         return response()->json($data);
    }
}

