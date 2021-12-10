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
        $data = DB::table('pembayaran')->where([['user_id','=', $users]])
            ->leftjoin('users', 'users.id', '=', 'pembayaran.author_id')
            ->select('users.name', 'pembayaran.*')->orderBy('id', 'DESC')
            ->get();

        return response()->json($data, 200);

    }
    // Utuk mnampilkan semua list payment di halaman home aplikasi berdasarkan user id
    public function getAllPayments(){
        $users = Auth::user()->id;
        $data = DB::table('pembayaran')->where('user_id','=', $users)
            ->leftjoin('users', 'users.id', '=', 'pembayaran.author_id')
            ->select('users.name', 'pembayaran.*')->orderBy('id', 'DESC')
            ->limit(3)->get();

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
                'data' => "Menunggu Konfirmasi Admin",
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
    // public function editPayment($id){

    // }

    // Untuk input otomatis payment users yang dilakukan oleh admin dari panding ke lunas
    public function adminPostPayment(Request $request){

        $userid = $request->userid;
        
            $cek = DB::table('pembayaran')->where([
                ['user_id', '=', $userid],
                ['cek', '=', Carbon::now()->format('Y-m')],
            ])
                ->select('pembayaran.cek')
                ->get();
            // return response()->json($users);

            if (!empty($userid)) {
                
                if ($cek->isEmpty()) {
                    

                    $users = DB::table('users')->where('users.id', $request->userid)
                            ->leftjoin('client', 'users.id', '=', 'client.id_user')
                            ->select('users.*', 'client.nominal')
                            ->get();

                    foreach($users as $id){
                        $datausers = $id;
                    }

                    // membuat input pembayaran ketika users tergenerate lunas
                    DB::table('pembayaran')->insert([
                        'user_id' => $userid,
                        'nominal' => $datausers->nominal,
                        'bulan' => Carbon::now()->isoformat('MMMM'),
                        'tahun' => date('Y'),
                        'author_id' => Auth::user()->id,
                        'created_at' => date('d-m-Y H:i:s'),
                        'updated_at' => date('d-m-Y H:i:s'),
                        'cek' => date('Y-m'),
                        'status' => true,
                    ]);

                    // mengubah isActive menjadi true agar tidak tersecan cutoff server jaringan.
                    DB::table('client')->where('client.id_user' , $userid)
                         ->update([
                            'isActive' => 1,
                            'updated_at' => date('d-m-Y H:i:s'),
                            
                        ]);

                     $date = Carbon::now()->format('d-MM-YYYY');

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
                            'user_id' => $userid,
                            'judul' => 'Iuran',
                            'deskripsi' => 'Terima kasih.. Iuran anda sudah di terima admin.',
                            'created_at' => date('d-m-Y H:i:s'),
                            'updated_at' => date('d-m-Y H:i:s'),
                            'status' => false,
                        ]);

                    DB::table('aktivitas')->insert([
                        'user_id' => Auth::user()->id,
                        'judul' => 'Konfirmasi Iuran',
                        'deskripsi' => Auth::user()->name +' Telah mengkonfirmasi iuran'+ $datausers->name,
                        'created_at' => date('d-m-Y H:i:s'),
                        'updated_at' => date('d-m-Y H:i:s'),
                    ]);


                    DB::table('client')->where('client.id_user' , $userid)
                         ->update([
                            'isActive' => 1,
                            'updated_at' => date('d-m-Y H:i:s'),
                            
                        ]);

                    DB::table('pembayaran')->where([
                        ['user_id', '=', $userid],
                        ['cek', '=', Carbon::now()->format('Y-m')],
                    ])    
                    ->update([
                        'status' => true,
                        'updated_at' => date('d-m-Y H:i:s'),
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
    public function usersAddPayment(Request $request){
        $users = Auth::user()->id;

        $cek = DB::table('pembayaran')->where([
            ['user_id', '=', $users],
            ['cek', '=', Carbon::now()->format('Y-m')],
        ])
            ->select('pembayaran.cek')
            ->get();

        if ($cek->isEmpty()) {
            $userID = Auth::user()->id;
            $userName = Auth::user()->name;
            $userAvatar = Auth::user()->avatar;

            $nominal = DB::table('users')->where('users.id', $userID)
                ->leftJoin('client', 'users.id', '=', 'client.id_user')
                ->select('client.nominal')
                ->get();
            foreach ($nominal as $detail) {
                $items = $detail->nominal;
            }

            DB::table('pembayaran')->insert([
                'user_id' => $userID,
                'nominal' => $items,
                'bulan' => Carbon::now()->isoformat('MMMM'),
                'tahun' => date('Y'),
                'author_id' => $request->id,
                'created_at' => date('d-m-Y H:i:s'),
                'updated_at' => date('d-m-Y H:i:s'),
                'cek' => date('Y-m'),
                'status' => false,
            ]);

             DB::table('notifikasi')->insert([
                'user_id' => $userID,
                'judul' => 'Iuran',
                'deskripsi' => 'Permintaan konfirmasi iuran anda berhasil di kirim ke admin',
                'created_at' => date('d-m-Y H:i:s'),
                'updated_at' => date('d-m-Y H:i:s'),
                'status' => false,
            ]);

              DB::table('aktivitas')->insert([
                'user_id' => $userID,
                'judul' => 'Iuran Request',
                'deskripsi' => 'Mengirim permintaan konfirmasi ke admin',
                'created_at' => date('d-m-Y H:i:s'),
                'updated_at' => date('d-m-Y H:i:s'),
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

    // Untuk admin menambahkan data secara manual

    public function adminAddPayment(Request $request){

        $users = DB::table('users')->where('users.id', $request->userid)
        ->leftjoin('client', 'users.id', '=', 'client.id_user')
        ->select('users.*', 'client.nominal')
        ->get();

        foreach($users as $id){
            $datausers = $id;
        }

        

        $cek = DB::table('pembayaran')->where([['user_id', '=',  $request->userid ],['cek', '=', Carbon::now()->format('Y-m')]])
            ->select('pembayaran.cek')
            ->get();

        if ($cek->isEmpty()) {
            $userID =  $datausers->id;
            $bulan =   $request->bulan;
            $status =  $request->status;
            $items = $datausers->nominal;
           
            DB::table('pembayaran')->insert([
                'user_id' => $userID,
                'nominal' => $items,
                'bulan' => $bulan,
                'tahun' => date('Y'),
                'author_id' => Auth::user()->id,
                'created_at' => date('d-m-Y H:i:s'),
                'updated_at' => date('d-m-Y H:i:s'),
                'cek' => date('Y-m'),
                'status' => $status,
            ]);

             


            return response()->json([
                'success' => true,
                'pesan' => 'Berhasil post data'
            ],200);

        }else {
            return response()->json([
                'success' => false,
                'pesan' => 'Data sudah ada !'
            ],201);
        }
    }

    // Menampilkan detail iuran/payment pada card iuran
    public function paymentDetail(){
        $today = Carbon::now()->isoFormat('Y-MM');
        $bulan = Carbon::now()->isoFormat('MMMM');

        $data = DB::table('pembayaran')->where([
            ['user_id', '=', Auth::user()->id],
            ['cek', '=', $today]
        ])
        ->leftjoin('users', 'pembayaran.author_id','=','users.id')
        ->select('users.name', 'pembayaran.*')
        ->get();

        foreach($data as $data){
            $value = $data;
        }

        return response()->json($value);

        
    }




    //Admin Page

    //Menampilkan riquest iuran
    public function reqIuran(){

        $data = DB::table('pembayaran')->where([['status', '=', false],['author_id', '=', Auth::user()->id]])
        ->leftjoin('users', 'pembayaran.user_id','=','users.id')
        ->select('pembayaran.*', 'users.name', 'users.avatar')
        ->get();

        return response()->json($data);

    }

    //mengkonfirmasi request iuran dari users
    public function adminAccPayment(Request $request){
        DB::table('pembayaran')
              ->where('id', $request->id)
              ->update(
                ['status' => true],
                ['updated_at' => date('d-m-Y H:i:s')]
          );

        return response()->json(['pesan' => 'berhasil acc iuran']);
    }

    // Menampilkan jumlah Iuran yang di bawa admin berdasarkan id admin
    public function laporanIuran()
    {
        $cekdate = Carbon::now()->format('Y-m');


        $data = Pembayaran::where([['author_id', '=', Auth::user()->id], ['status','=', true],['cek', '=', $cekdate]])
            ->leftjoin('users','users.id','=','pembayaran.user_id')
            ->select('users.name', 'pembayaran.*','users.avatar')
            ->get();

        $total_iuran = DB::table('pembayaran')->where([
            ['author_id', '=', Auth::user()->id],
            ['cek', '=', $cekdate]
        ])->sum('pembayaran.nominal');

        return response()->json([
            'success' => true,
            'data' => $data,
            'total_harga' => $total_iuran,
            'pesan' => 'Berhasil ambil data'
        ]);
    }
}
