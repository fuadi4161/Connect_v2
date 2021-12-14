<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiBonusController extends Controller
{
    // Untuk menampilkan semua data bonus
    public function getBonus(){

        $bulan = Carbon::now()->isoFormat('MMMM');
        $tahun = date('Y');

        $bonus = DB::table('bonus')->where([['bulan', $bulan],['tahun', $tahun]])
                ->leftJoin('users', 'bonus.user_id','=','users.id')
                ->select('bonus.*', 'users.avatar')
                ->orderBy('id', 'DESC')->get();

        return response()->json($bonus,200);

    }

     // Untuk menampilkan jumlah data bonus
    public function countBonus(){

        $bulan = Carbon::now()->isoFormat('MMMM');
        $tahun = date('Y');

        $bonus = DB::table('bonus')->where([['bulan', $bulan],['tahun', $tahun]])->count();

        return response()->json($bonus,200);

    }

    // untuk menambahkan bonus 
    public function postBonus(Request $request){
        $bulan = Carbon::now()->isoFormat('MMMM');
        
        DB::table('bonus')
            ->insert([
                'title' =>  $request->speed,
                'deskripsi' => 'ambil bonus untuk menambah kecepatan internet anda. "berlaku sampai akhir bulan '. $bulan. '"',
                'bulan' => $bulan,
                'tahun' => date('Y'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        return response()->json([
            'success' => true,
            'pesan' => 'Berhasil'
        ], 200);

    }
    // Untuk mengeclaim bonus
    public function claimBonus($id){

        $bulan = Carbon::now()->isoFormat('MMMM');

        $user = Auth::user()->id;
        $date = date('Y-m');
        $tahun = date('Y');

        $check = DB::table('bonus')->where([['bulan', $bulan],['tahun', $tahun],['user_id', $user]])->get();

        $payS = DB::table('pembayaran')->where([['cek', $date],['user_id', $user]])->get();


        foreach ($payS as $key) {
            $status = $key->status;
        }
         // return response()->json($check);

        if ( $check->isEmpty()) {

            if($payS == []){
                return response()->json(
                    [
                        'success' => true,
                        'pesan' => 'Anda belum melakukan iuran',
                    ],202
                );
            }

            if ($status == false) {
                return response()->json(
                    [
                        'success' => true,
                        'pesan' => 'Anda belum melakukan iuran',
                    ],202
                );
            } else {
                DB::table('bonus')->where('id', $id)
                ->update([
                    'claim' => Auth::user()->name,
                    'user_id' => Auth::user()->id,
                    'status' => false,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // $notifID = DB::table('users')->get();

                // $SERVER_API_KEY = 'AAAAXwc3hQ0:APA91bGWHOSNXP2oxdwLGq7e6tLx9H7IY4cFkPBuZzIRaqTMzZo5EDdyUlC6_TCgrtwasgfQUmArnLOJe-wqoAY0yn02Dpu_sjPORMT7KLFcRxF0FtQRiCHo87afnXOTwWixOb2OFezM';

                // foreach ($notifID as $key) {
                //     if (!empty($key)) {
                //         $fcm_key = $key->notif_fcm;

                //                 $token_1 = $fcm_key;

                //                 $data = [

                //                     "registration_ids" => [
                //                         $token_1
                //                     ],

                //                     "notification" => [

                //                         "title" => 'Bonus Sudah diclaim!!!' ,

                //                         "body" => 'Bonus berhasil di claim oleh '.Auth::user()->name,

                //                         "sound"=> "default" // required for sound on ios

                //                     ],

                //                 ];

                //                 $dataString = json_encode($data);

                //                 $headers = [

                //                     'Authorization: key=' . $SERVER_API_KEY,

                //                     'Content-Type: application/json',

                //                 ];

                //                 $ch = curl_init();

                //                 curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

                //                 curl_setopt($ch, CURLOPT_POST, true);

                //                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                //                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                //                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //                 curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                //                 $response = curl_exec($ch);

                //                 // dd($response);
                //         }
                // }
                return response()->json(
                    [
                        'success' => true,
                        'pesan' => 'berhasil claim bonus',
                    ]
                );
            }
            
            
        } else {
            return response()->json(
                [
                    'success' => true,
                    'pesan' => 'sudah claim bonus',
                ],201
            );
        }

    }

    // Untuk menghapus Bonus
    public function hapusBonus($id){

        DB::table('bonus')->where('id', $id)->delete();

    }
    // Untuk mengedit bonus
    public function editBonus($id){

    }
    // Untuk aprove Bonus sekaligus Notifikasi
    public function aproveBonus($id){

         DB::table('bonus')->where('id', $id)->update([
                'aprove' => true,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
       
    }

    public function myBonus(){
        $bulan = Carbon::now()->isoFormat('MMMM');

        $mybonus = DB::table('bonus')->where([['status', false],['bulan',$bulan],['user_id', Auth::user()->id]])->latest()->get();

        if ($mybonus->isEmpty() ){

            $data['title'] = '0';
            $data['deskripsi'] = '0';
            $data['bulan'] = '0';

            return response()->json(
            [
                'success' => true,
                'data' => $data,
                'pesan' => 'data tidak ada',
            ],201
        );
        } else {
            foreach ($mybonus as $value) {
            $title = $value->title;
            $deskripsi = $value->deskripsi;
            $bulan = $value->bulan;

            }

            $data['title'] = $title;
            $data['deskripsi'] = $deskripsi;
            $data['bulan'] = $bulan;

            return response()->json($data,200);
        }
    }
}
