<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class ApiUsersController extends Controller
{
    use HasRoles;

    // Untuk memberi akses ke halaman admin
    public function aksesAdmin(Request $request){
        $user = Auth::user();
        $admin = $user->getRoleNames();
        foreach($admin as $value){
            $isAdmin = $value;
        }

        $data = DB::table('token_akses')->where('token_akses.user_id', $user->id)->get();
        foreach($data as $data){
            $params = $data;
        }

        // return response()->json($data);

        if( $params->isAdmin == $isAdmin && $data->password == $request->password){
            return response()->json([
                'success' => true,
            ], 200); 
        }

        return response()->json([
            'success' => false,
        ], 201);


        

    }

    // Menampilkan data profil users yang login
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

    // Menampikan data semua users
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


    public function approveUser($id){

    }
}
