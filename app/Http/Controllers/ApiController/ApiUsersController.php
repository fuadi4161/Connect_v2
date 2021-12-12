<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Spatie\Permission\Traits\HasRoles;

use App\User;
use App\HasRole;
use App\Alamat;
use App\Client;

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
        ->leftJoin('alamat', 'users.id', '=', 'alamat.user_id')
        ->select('users.*','client.internet','client.catv','client.nominal','alamat.kecamatan','alamat.desa','alamat.dusun','alamat.rt','alamat.rw')
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
        $data = DB::table('users')
        ->leftJoin('client', 'users.id', '=', 'client.id_user')
        ->leftJoin('alamat', 'users.id', '=', 'alamat.user_id')
        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->select('users.*','alamat.*','client.internet','client.catv','client.nominal','model_has_roles.role_id')
        ->get();

        return response()->json($data, 200);
    }
    // Menambahkan users dengan role Client/pelanggan
    public function addUsersClient(Request $request){

        $data = $request->all();

        $email = $data['email'];

        $userwifi = new User;
        $userwifi->name = $data['name'];
        $userwifi->email = $data['email'];
        $userwifi->password = bcrypt($data['password']);
        $userwifi->save();

        $hasrole = new HasRole;
        $hasrole->role_id = 3;
        $hasrole->model_type = 'App\User';
        $hasrole->model_id = $userwifi->id;
        $hasrole->save();

        $alamat = new Alamat;
        $alamat->user_id = $userwifi->id;
        $alamat->kecamatan = $request->kecamatan;
        $alamat->desa = $request->desa;
        $alamat->dusun = $request->dusun;
        $alamat->rt = $request->rt;
        $alamat->rw = $request->rw;
        $alamat->created_at = date('d-m-Y H:i:s');
        $alamat->save();


        $client = new Client;
        $client->id_user = $userwifi->id;
        $client->internet = $request->internet;
        $client->catv = $request->catv;
        $client->nominal = $request->nominal;
        $client->isActive = false;
        $client->created_at = date('d-m-Y H:i:s');

        // \Mail::to($email)->send(new \App\Mail\NewUserNotification($detail));

        return response()->json(200);


    }
    // Menambahkan users role Admin
    public function addUsersAdmin(Request $request){
        $data = $request->all();

        $email = $data['email'];

        $userwifi = new User;
        $userwifi->name = $data['name'];
        $userwifi->email = $data['email'];
        $userwifi->password = bcrypt($data['password']);
        $userwifi->save();

        $hasrole = new HasRole;
        $hasrole->role_id = 2;
        $hasrole->model_type = 'App\User';
        $hasrole->model_id = $userwifi->id;
        $hasrole->save();

        $alamat = new Alamat;
        $alamat->user_id = $userwifi->id;
        $alamat->kecamatan = $request->kecamatan;
        $alamat->desa = $request->desa;
        $alamat->dusun = $request->dusun;
        $alamat->rt = $request->rt;
        $alamat->rw = $request->rw;
        $alamat->created_at = date('d-m-Y H:i:s');
        $alamat->save();


        $client = new Client;
        $client->id_user = $userwifi->id;
        $client->internet = $request->internet;
        $client->catv = $request->catv;
        $client->nominal = $request->nominal;
        $client->isActive = false;
        $client->created_at = date('d-m-Y H:i:s');
        $alamat->save();

        // \Mail::to($email)->send(new \App\Mail\NewUserNotification($detail));

        return back();
    }
    
    public function editUser(Request $request){
        
        DB::table('users')
              ->where('id', $request->id)
              ->update([
                'votes' => 1,

            ]);
    }

    public function hapusUser($id){

    }


    public function approveUser($id){

    }
}
