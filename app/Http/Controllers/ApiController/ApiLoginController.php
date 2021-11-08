<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;


class ApiLoginController extends Controller
{
    use HasRoles;
    
    public function login(Request $request)
    {

        if (Auth::attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ]
        )) {
            $user = Auth::user();

            $rolename = $user->getRoleNames();
            foreach($rolename as $value){
                $data['role'] = $value;
            }
            $token = $user->createToken('user')->accessToken;
            $data['user'] = $user;
            $data['token'] = $token;
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'pesan' => 'login Berhasil'
            ], 200);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'pesan' => 'Login Gagal',
                ],
                401
            );
        }
    }
}
