<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', 'ApiController\ApiLoginController@login');
Route::post('profile/avatar','ApiController\ApiProfileController@postAvatar');
Route::post('profile/background','ApiController\ApiProfileController@postBackground');


Route::group(['middleware' => ['auth:api']], function () {

    // Konfirmasi users admin
    Route::post('admin', 'ApiController\ApiUsersController@aksesAdmin');
    // Menampilkan List Users
    Route::get('admin/list', 'ApiController\ApiAdminController@adminuser');

    
    // Url Users model 
        // Untuk menampilkan profile users yang login
    Route::get('user/profile', 'ApiController\ApiUsersController@getProfilUser'); // Ok
        // Untuk menampilkan semua data users
    Route::get('users', 'ApiController\ApiUsersController@getUsers'); // Ok
        // Untuk menambahkan users Client (admin)
    Route::post('users/addClient', 'ApiController\ApiUsersController@addUsersClient');
        // Untuk menambahkan users Admin (admin)
    Route::post('users/addAdmin', 'ApiController\ApiUsersController@addUsersAdmin');
        // Untuk mengedit data users (admin)
    Route::get('users/edit/{id}', 'ApiController\ApiUsersController@editUser');
        // Untuk menghapus users (admin)
    Route::get('users/delete/{id}', 'ApiController\ApiUsersController@hapusUser');

    // Untuk memindahkan semua users ke status belum lunas
    Route::post('AllUsersNotLunas', 'ApiController\ApiUsersController@getAllNoLunas'); 
       

        // Url update profile
    Route::post('profile/username','ApiController\ApiProfileController@updateUsername');
    Route::post('profile/email','ApiController\ApiProfileController@updateEmail');
    Route::post('profile/password','ApiController\ApiProfileController@updatePassword');

        //Url update alamat
    Route::post('profile/kecamatan','ApiController\ApiProfileController@updateKecamatan');
    Route::post('profile/desa','ApiController\ApiProfileController@updateDesa');
    Route::post('profile/dusun','ApiController\ApiProfileController@updateDusun');
    Route::post('profile/rtrw','ApiController\ApiProfileController@updateRtRw');





    // Bonus Model
        // Untuk menampilkan semua bonus pada bulan tersebut
    Route::get('bonus', 'ApiController\ApiBonusController@getBonus'); // Ok
        // Untuk menambahkan bonus (admin)
    Route::post('bonus/add', 'ApiController\ApiBonusController@postBonus'); // Ok
        // Untuk menampilkan bonus yang di claim user tersebut
    Route::get('bonus/claim', 'ApiController\ApiBonusController@myBonus'); // Ok
        // Untuk mengekalim bonus
    Route::get('bonus/claim/{id}', 'ApiController\ApiBonusController@claimBonus'); // Ok
        // Untuk mengapprove bonus yang di claim users (admin)
    Route::get('bonus/aprove/{id}', 'ApiController\ApiBonusController@aproveBonus'); // Ok
        // Untuk mendelete bonus yang sudah di input
    Route::get('bonus/delete/{id}', 'ApiController\ApiBonusController@hapusBonus'); // Ok
        // Untuk mengecek jumlah bonus
    Route::get('bonus/count', 'ApiController\ApiBonusController@countBonus');






    //payment
    Route::get('payment', 'ApiController\ApiPaymentController@getPayments'); // ok
        // Untuk menampilkan semua list payment di halaman home aplikasi berdasarkan user id
    Route::get('payment/all', 'ApiController\ApiPaymentController@getAllPayments'); // ok
        // Untuk menampilkan Status payment Users
    Route::get('payment/status', 'ApiController\ApiPaymentController@statusPayment'); // ok
        // Menampilkan detail iuran/payment pada card iuran
    Route::get('payment/status/detail', 'ApiController\ApiPaymentController@paymentDetail'); // ok
        // Untuk menambah/menginput iuran dari users atau saat users meminta riquest iuran untuk di konfirmasi
    Route::post('payment/useradd', 'ApiController\ApiPaymentController@usersAddPayment'); // ok
        // Untuk menambahkan payment secara manual oleh admin
    Route::post('payment/adminadd', 'ApiController\ApiPaymentController@adminAddPayment'); // ok
        // Untuk menampilkan data users yang belum lunas untuk generate iuran oleh admin
    Route::get('payment/noLunas','ApiController\ApiPaymentController@getUsersNotLunas');
        // Untuk admin menginput otomatis payment sesuai data dari users
    Route::post('payment/adminpost', 'ApiController\ApiPaymentController@adminPostPayment'); // ok
        // Untuk admin mengkonfirmasi iuran yang di kirim oleh users
    Route::post('payment/acc', 'ApiController\ApiPaymentController@adminAccPayment'); // ok
        // Untuk menampilkan pembayran yang meminta konfirmasi admin
    Route::get('payment/req', 'ApiController\ApiPaymentController@reqIuran'); // ok



    //Notifikasi
     Route::get('notifikasi', 'ApiController\ApiNotifController@getNotif'); // ok
     Route::post('notifikasi/read', 'ApiController\ApiNotifController@readNotif'); // ok
     Route::post('notifikasi/delete', 'ApiController\ApiNotifController@deleteNotif'); // ok


    //Aktivitas
     Route::get('aktivitas', 'ApiController\ApiAktivitasController@getAktif'); // ok
     Route::get('aktivitas/user', 'ApiController\ApiAktivitasController@getByUsers'); // ok


    //Berita
     Route::get('info', 'ApiController\ApiBeritaController@getBerita'); // 
     Route::post('info/post', 'ApiController\ApiBeritaController@postBerita'); // ok
     Route::get('info/delete/{id}', 'ApiController\ApiBeritaController@deleteBerita'); // ok




});