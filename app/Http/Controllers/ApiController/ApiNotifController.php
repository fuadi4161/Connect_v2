<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class ApiNotifController extends Controller
{

    public function getNotif(){
        $userid = Auth::user()->id;
        $data = DB::table('notifikasi')->where('user_id','=',$userid)->get();

         return response()->json($data);
    }

    public function readNotif(Request $request){
        DB::table('notifikasi')
              ->where('id', $request->id)
              ->update(
                ['status' => true],
                ['updated_at' => date('d-m-Y H:i:s')]
          );

        return response()->json(['pesan' => 'berhasil read notif']);
    }
    
}
