<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class ApiProfileController extends Controller
{
    public function updateUsername(Request $request){
        $data =  $request->username;

        DB::table('users')->where('id', Auth::user()->id)
                ->update([
                    'name' => $request->username,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json($data);
    }
    public function updateEmail(Request $request){

        $data =  $request->email;

        DB::table('users')->where('id', Auth::user()->id)
                ->update([
                    'email' => $request->email,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json($data);
    }
    public function updatePassword(Request $request){
        
    }
    public function updateKecamatan(Request $request){
        $data =  $request->kecamatan;

        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([
                    'kecamatan' => $request->kecamatan,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json($data);
    }
    public function updateDesa(Request $request){
        $data =  $request->desa;

        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([
                    'desa' => $request->desa,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json($data);
    }
    public function updateDusun(Request $request){
        $data =  $request->dusun;

        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([
                    'dusun' => $request->dusun,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json($data);
    }
    public function updateRtRw(Request $request){
        $data =  $request->rt;

        DB::table('alamat')->where('user_id', Auth::user()->id)
                ->update([
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        return response()->json($data);
    }

    public function postAvatar(Request $request) {
        if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = $request->username . '_' . time() . '.' . $file->getClientOriginalName();
                $avatar = 'https://connect.ip2sr.site/assets/images/avatars/' . $request->username . '_' . time() . '.' . $file->getClientOriginalName();
                $file->move('assets/images/avatars', $filename);

                $files = $request->file('file');
            DB::table('users')->where('name', $request->username)
                ->update([
                    'avatar' => 'https://connect.ip2sr.site/assets/images/avatars/' . $request->username . '_' . time() . '.' . $files->getClientOriginalName(),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                return response()->json(200);
        }
            
    }

}
