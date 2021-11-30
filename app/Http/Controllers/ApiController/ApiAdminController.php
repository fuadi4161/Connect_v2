<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiAdminController extends Controller
{
    //menampilkan data admin

    public function adminuser()
    {


        $data = DB::table('model_has_roles')->where('model_has_roles.role_id', 2)
            ->leftJoin('users', 'model_has_roles.model_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar')
            ->get();

            

        if ($data == '[]') {
            return response()->json([
                'success' => false,
            ], 201);
        } else {
            return response()->json($data);
        }
    }
}
