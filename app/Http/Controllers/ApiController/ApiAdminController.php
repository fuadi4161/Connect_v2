<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiAdminController extends Controller
{
    //data admin

    public function adminuser()
    {


        $data = DB::table('model_has_roles')
            ->leftJoin('users', 'model_has_roles.model_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar')
            ->orderBy('id', 'DESC')
            ->limit(2)->get();
            ->get();

            foreach($data as $data){
                $item = $data->name;
            }

        if ($data == '[]') {
            return response()->json([
                'success' => false,
            ], 201);
        } else {
            return response()->json([$item]);
        }
    }
}
