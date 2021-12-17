<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class ApiBeritaController extends Controller
{
    public function getBerita(){

        $data = DB::table('berita')->get();

        return response()->json($data);
    }

    public function postBerita(Request $request){
         if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = $request->judul . '_' . time() . '.' . $file->getClientOriginalName();
                $avatar = 'https://connect.ip2sr.site/assets/images/berita/' . $request->judul . '_' . time() . '.' . $file->getClientOriginalName();
                $file->move('assets/images/berita', $filename);

                $files = $request->file('file');
           DB::table('berita')
            ->insert([
                'judul' =>  $request->judul,
                'deskripsi' => $request->deskripsi,
                'image' => 'https://connect.ip2sr.site/assets/images/berita/' . $request->judul . '_' . time() . '.' . $files->getClientOriginalName(),
                'link' => $request->link,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

                return response()->json(200);
        }
        


    }

    public function deleteBerita($id){

    }
}