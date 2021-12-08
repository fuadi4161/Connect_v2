<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class ApiProfileController extends Controller
{
    public function updateUsername(Request $request){

        DB::table('users')->where('id', Auth::user()->id)
                ->update([,
                    'name' => $request->username,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json(['success' => true]);
    }
    public function updateEmail(Request $request){
        DB::table('users')->where('id', Auth::user()->id)
                ->update([,
                    'email' => $request->email,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json(['success' => true]);
    }
    public function updatePassword(Request $request){
        
    }
    public function updateKecamatan(Request $request){
        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([,
                    'kecamatan' => $request->kecamatan,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json(['success' => true]);
    }
    public function updateDesa(Request $request){
        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([,
                    'desa' => $request->desa,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json(['success' => true]);
    }
    public function updateDusun(Request $request){
        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([,
                    'dusun' => $request->dusun,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json(['success' => true]);
    }
    public function updateRtRw(Request $request){
        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json(['success' => true]);
    }

}
