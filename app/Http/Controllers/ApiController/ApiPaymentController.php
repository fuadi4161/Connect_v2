<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiPaymentController extends Controller
{
    // Untuk menampikan semua List payments sesuai users id
    public function getPayments(){
        $users = Auth::user()->id;
        $data = DB::table('pembayaran')->where([['user_id','=', $users],['status','=', true]])
            ->leftjoin('users', 'users.id', '=', 'pembayaran.author_id')
            ->select('users.name', 'pembayaran.*')->orderBy('id', 'DESC')
            ->get();

        return response()->json($data, 201);

    }
    // Utuk mnampilkan semua list payment di halaman home aplikasi berdasarkan user id
    public function getAllPayments(){
        $users = Auth::user()->id;
        $data = DB::table('pembayaran')->where('user_id','=', $users)
            ->leftjoin('users', 'users.id', '=', 'pembayaran.author_id')
            ->select('users.name', 'pembayaran.*')->orderBy('id', 'DESC')
            ->limit(5)->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'pesan' => 'Berhasil ambil data'
        ]);

    }

    // Untuk menampilkan setatus pada keterangan payment di home aplikasi
    public function statusPayment(){

        $today = Carbon::now()->isoFormat('Y-MM');
        $bulan = Carbon::now()->isoFormat('MMMM');

        $data = DB::table('pembayaran')->where([
            ['user_id', '=', Auth::user()->id],
            ['cek', '=', $today]
        ])->get();

        foreach ($data as $item) {
            $status = $item->status;
        }


        if ($data == '[]') {
            return response()->json([
                'success' => false,
                'data' => "Iuran Bulan $bulan Belum Lunas",
                'pesan' => "belum Iuran"
            ]);
        } elseif ($status == false) {
            return response()->json([
                'success' => true,
                'data' => "Menunggu konfirmasi Admin",
                'pesan' => "Menunggu konfirmasi"
            ]);
        } elseif ($status == true) {
            return response()->json([
                'success' => true,
                'data' => "Iuran bulan $bulan Lunas",
                'pesan' => "Iuran Lunas"
            ]);
        }

    }

    // Untuk fungsi edit payment dari panding ke lunas
    public function editPayment($id){

    }

    // Untuk input manual payment users
    public function postPayment(Request $request){

        $user = $request->user;
        $posision = $request->posision;


        
            $cek = Pembayaran::where([
                ['user_id', '=', $user],
                ['cek', '=', Carbon::now()->format('Y-m')],
            ])
                ->select('pembayaran.cek')
                ->get();
            // return response()->json($users);

            if (!empty($user)) {
                
                if ($cek->isEmpty()) {
                    
                     DB::table('posision_users')->where([['user_id','=', $user],['posision_id','=', $posision]])
                    ->update([
                        'status' => true,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $data = DB::table('users')->where([['users.id', '=', $user]])
                        ->leftJoin('langganan', 'users.status_langganan', '=', 'langganan.id')
                        ->select('langganan.harga', 'langganan.kecepatan')
                        ->get();
                    foreach ($data as $detail) {
                        $items = $detail->harga;
                    }

                    // membuat input pembayaran ketika users tergenerate lunas
                    DB::table('pembayaran')->insert([
                        'user_id' => $user,
                        'nominal' => $items,
                        'bulan' => Carbon::now()->isoformat('MMMM'),
                        'tahun' => date('Y'),
                        'author_id' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'cek' => date('Y-m'),
                        'status' => true,
                    ]);

                     $date = Carbon::now()->format('d-MM-YYYY');

                     DB::table('notifikasi')->insert([
                            'user_id' => $user,
                            'judul' => 'Terima kasih',
                            'deskripsi' => 'Iuran anda Iuran sudah di terima admin.',
                            'date' => $date,
                            'status' => false,
                        ]);

                    // $getToken = DB::table('users')->where('id', $user)->get();

                    // foreach ($getToken as $key ) {

                    //     $FCM_token = $key->notif_fcm;
                    // }

                    // $SERVER_API_KEY = 'AAAAXwc3hQ0:APA91bGWHOSNXP2oxdwLGq7e6tLx9H7IY4cFkPBuZzIRaqTMzZo5EDdyUlC6_TCgrtwasgfQUmArnLOJe-wqoAY0yn02Dpu_sjPORMT7KLFcRxF0FtQRiCHo87afnXOTwWixOb2OFezM';

                
                    // $data = [

                    //     "registration_ids" => [
                    //         $FCM_token
                    //     ],

                    //     "notification" => [

                    //         "title" => 'Terima kasih.',
    
                    //         "body" => 'Iuran anda Iuran sudah di terima admin.',
                            
                    //         "icon" => 'https://www.rumahweb.com/assets/img/accredited-id.png',
    
                    //         "sound"=> "default"  // required for sound on ios

                    //     ],

                    // ];

                    // $dataString = json_encode($data);

                    // $headers = [

                    //     'Authorization: key=' . $SERVER_API_KEY,

                    //     'Content-Type: application/json',

                    // ];

                    // $ch = curl_init();

                    // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

                    // curl_setopt($ch, CURLOPT_POST, true);

                    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                } else {


                    $date = Carbon::now()->format('d-MM-YYYY');

                     DB::table('notifikasi')->insert([
                            'user_id' => $user,
                            'judul' => 'Terima kasih',
                            'deskripsi' => 'Iuran anda Iuran sudah di terima admin.',
                            'date' => $date,
                            'status' => false,
                        ]);

                    DB::table('posision_users')->where([['user_id','=', $user],['posision_id','=', $posision]])
                    ->update([
                        'status' => true,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('pembayaran')->where([
                        ['user_id', '=', $user],
                        ['cek', '=', Carbon::now()->format('Y-m')],
                    ])    
                    ->update([
                        'status' => true,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    // $getToken = DB::table('users')->where('id', $user)->get();

                    // foreach ($getToken as $key ) {

                    //     $FCM_token = $key->notif_fcm;
                    // }

                    // $SERVER_API_KEY = 'AAAAXwc3hQ0:APA91bGWHOSNXP2oxdwLGq7e6tLx9H7IY4cFkPBuZzIRaqTMzZo5EDdyUlC6_TCgrtwasgfQUmArnLOJe-wqoAY0yn02Dpu_sjPORMT7KLFcRxF0FtQRiCHo87afnXOTwWixOb2OFezM';

                
                    // $data = [

                    //     "registration_ids" => [
                    //         $FCM_token
                    //     ],

                    //     "notification" => [

                    //         "title" => 'Terima kasih',

                    //         "body" =>  'Iuran anda Iuran sudah di terima admin.',

                    //         "sound"=> 'stoneSkimingDay4', // required for sound on ios

                    //     ],

                    // ];

                    // $dataString = json_encode($data);

                    // $headers = [

                    //     'Authorization: key=' . $SERVER_API_KEY,

                    //     'Content-Type: application/json',

                    // ];

                    // $ch = curl_init();

                    // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

                    // curl_setopt($ch, CURLOPT_POST, true);

                    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                }
            }


        return response()->json([
            'success' => true,
            'pesan' => 'Berhasil update data'
        ],201);
        
    }

    //untuk add payment from users (users melakukan pembayaran atau request pembayaran)
    public function addPayment(Request $request){
        $users = Auth::user()->id;

        $cek = Pembayaran::where([
            ['user_id', '=', $users],
            ['cek', '=', Carbon::now()->format('Y-m')],
        ])
            ->select('pembayaran.cek')
            ->get();

        if ($cek->isEmpty()) {
            $userID = Auth::user()->id;
            $userName = Auth::user()->name;
            $userAvatar = Auth::user()->avatar;

            $data = DB::table('users')->where([['users.id', '=', $userID]])
                ->leftJoin('langganan', 'users.status_langganan', '=', 'langganan.id')
                ->select('langganan.harga', 'langganan.kecepatan','users.*')
                ->get();
            foreach ($data as $detail) {
                $items = $detail->harga;
            }
            DB::table('pembayaran')->insert([
                'user_id' => $userID,
                'nominal' => $items,
                'bulan' => Carbon::now()->isoformat('MMMM'),
                'tahun' => date('Y'),
                'author_id' => $request->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'cek' => date('Y-m'),
                'status' => false,
            ]);
            DB::table('notifikasi')->insert([
                'user_id' => $request->id,
                'judul' => 'Request Iuran',
                'deskripsi' => $userName.' meminta konfirmasi Iuran',
                'status' => false,
            ]);

            DB::table('posision_users')->where('user_id', $users)
                ->update([
                    'status' => false,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // $token = DB::table('users')->where('id',  $request->id)->get();
                // foreach ( $token as $detail) {
                //     $FCM_token = $detail->notif_fcm;
                // }
                
                // $SERVER_API_KEY = 'AAAAXwc3hQ0:APA91bGWHOSNXP2oxdwLGq7e6tLx9H7IY4cFkPBuZzIRaqTMzZo5EDdyUlC6_TCgrtwasgfQUmArnLOJe-wqoAY0yn02Dpu_sjPORMT7KLFcRxF0FtQRiCHo87afnXOTwWixOb2OFezM';

                
                // $data = [

                //     "registration_ids" => [
                //         $FCM_token
                //     ],

                //     "notification" => [

                //         "title" => 'Request Iuran',

                //         "body" =>  $userName.' meminta konfirmasi Iuran',

                //         "image" =>  $userAvatar,

                //         "sound"=> 'stoneSkimingDay4', // required for sound on ios

                //     ],

                // ];

                // $dataString = json_encode($data);

                // $headers = [

                //     'Authorization: key=' . $SERVER_API_KEY,

                //     'Content-Type: application/json',

                // ];

                // $ch = curl_init();

                // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

                // curl_setopt($ch, CURLOPT_POST, true);

                // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                // $response = curl_exec($ch);

            return response()->json([
                'success' => true,
                'pesan' => 'Berhasil post data'
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'pesan' => 'Data sudah ada !'
            ],201);
        }
    }
}
