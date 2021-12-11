<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\HasRole;
use App\Alamat;
use App\Client;

class ApiRegisterController extends Controller
{
    // Registrasi users dengan role guest
   public function register(Request $request){
    
        $data = $request->all();

        $email = $data['email'];

        $userwifi = new User;
        $userwifi->name = $data['name'];
        $userwifi->email = $data['email'];
        $userwifi->password = bcrypt($data['password']);
        $userwifi->save();

        $hasrole = new HasRole;
        $hasrole->role_id = 4;
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
        $client->internet = 'guest';
        $client->catv = ' ';
        $client->nominal = '000';
        $client->isActive = false;
        $client->created_at = date('d-m-Y H:i:s');

        // \Mail::to($email)->send(new \App\Mail\NewUserNotification($detail));

        return back();
   }

}
