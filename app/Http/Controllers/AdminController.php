<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class AdminController extends Controller
{
    public function index(){
        $data = array(
            'alternatif'=>DB::table('tbl_alternatif')->count(),
            'kriteria'=>DB::table('tbl_kriteria')->count(),
            'class'=>'dashboard'
        );
        return view('pages.admin',$data);
    }
    public function login(Request $request){
        return view('pages.auth');
    }
    public function dataAdmin(Request $request){
        if($request->ajax()){
            $data = DB::table('tbl_admin')->get();
            return response()->json($data);
        }
    }
    public function saveAdmin(Request $request){
        if($request->ajax()){
            $check = DB::table('tbl_admin')->where('username','=',$request->username)->get();
            if(count($check)>0){
                $response = array(
                    'message'=>'Username sudah terdaftar',
                    'status'=>'error'
                );
            }else{
                $data = array(
                    'username'=>$request->username,
                    'password'=>bcrypt($request->password)
                );
                DB::table('tbl_admin')->insert($data);

                $response = array(
                    'message'=>'Username Berhasil di Simpan',
                    'status'=>'info'
                );
            }
            return response()->json($response);
        }
    }
    public function deleteAdmin(Request $request){
        if($request->ajax()){
            DB::table('tbl_admin')->where('id_admin','=',$request->id_admin)->delete();
            $response = array(
                'message'=>'Admin Berhasil di Hapus',
                'status'=>'info'
            );
            return response()->json($response);
        }
    }
    public function doLogin(Request $request){
        $data = DB::table('tbl_admin')->where('username','=',$request->username)->first();
        if(count($data)>0){
            if(password_verify($request->password,$data->password)){
                session(['username'=>$data->username]);
                session(['status'=>'admin']);
                $response = array(
                    'message'=>'Anda Berhasil Login Sebagai Admin<br>Mohon Tunggu Sebentar',
                    'status'=>'success'
                );
            }else{
                $response = array(
                    'message'=>'Password yang anda masukan salah',
                    'status'=>'error'
                );
            }
        }else{
            $response = array(
                'message'=>'Username tidak ditemukan',
                'status'=>'error'
            );
        }
        return response()->json($response);
    }
    public function logout(Request $request){
        $request->session()->regenerate();
        $request->session()->flush();
        return redirect('/login');
    }
}
