<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
class AlternatifController extends Controller
{
    public function index(){
        $data = array(
            'alternatif'=>DB::table('tbl_alternatif')->count(),
            'kriteria'=>DB::table('tbl_kriteria')->count(),
            'class'=>'alternatif'
        );
        return view('pages.alternatif',$data);
    }
    public function saveAlternatif(Request $request){
        $insertMatriks = array();
        $data = $request->all();
        $message = array(
            'nama_alternatif.required'=>'Nama Alternatif Harus di Isi',
            'nama_alternatif.max'=>'Nama Alternatif Tidak Boleh Lebih Dari 100 Karakter'
        ); 
        Validator::make($data,[
            'nama_alternatif'=>['required','max:100']
        ],$message)->validate();
        $id = DB::table('tbl_alternatif')->insertGetId($data);
        DB::table('tbl_vektor_wp')->insert(['id_alternatif'=>$id,'nilai_vektor_s'=>0,'nilai_vektor_v'=>0]);
        DB::table('tbl_vektor_saw')->insert(['id_alternatif'=>$id,'nilai_vektor_v'=>0]);
        $kriteria = DB::table('tbl_kriteria')->get();
        foreach($kriteria as $row){
            $idEvaluasi = DB::table('tbl_evaluasi')->insertGetId(array(
                'id_alternatif'=>$id,
                'id_kriteria'=>$row->id_kriteria,
                'nilai_evaluasi'=>'1'
            ));
            $r = array(
                'id_evaluasi'=>$idEvaluasi,
                'nilai_matriks'=>1
            );
            array_push($insertMatriks,$r);
        }
        DB::table('tbl_matriks_saw')->insert($insertMatriks);
        $response = array(
            'message'=>'Data Alternatif Berhasil Di Input',
            'status'=>'info',
            'alternatif'=>DB::table('tbl_alternatif')->count()
        );
        return response()->json($response);
    }
    public function getAlternatif(Request $request){
        $alternatif = DB::table('tbl_alternatif')->where('id_alternatif','=',$request->id_alternatif)->first();
        $response = array(
            'id_alternatif'=>$alternatif->id_alternatif,
            'nama_alternatif'=>$alternatif->nama_alternatif
        );
        return response()->json($response);
    }
    public function updateAlternatif(Request $request){        
        $data = $request->all();
        $message = array(
            'id_alternatif.required'=>'ID Alternatif tidak boleh di ubah',
            'id_alternatif.numeric'=>'ID Alternatif harus angka',
            'nama_alternatif.required'=>'Nama Alternatif Harus di Isi',
            'nama_alternatif.max'=>'Nama Alternatif Tidak Boleh Lebih Dari 100 Karakter'
        ); 
        Validator::make($data,[
            'nama_alternatif'=>['required','max:100'],
            'id_alternatif'=>['required','numeric']
        ],$message)->validate();
        DB::table('tbl_alternatif')->where('id_alternatif',$request->id_alternatif)->update($data);
        $response = array('message'=>'Data Alternatif Sudah Di Ubah','status'=>'info');
        return response()->json($response);
    }
    public function deleteAlternatif(Request $request){
        DB::table('tbl_alternatif')->where('id_alternatif', '=',$request->id_alternatif)->delete();
        $response = array(
            'message'=>'Alternatif Berhasil Di Hapus',
            'status'=>'error',
            'alternatif'=>DB::table('tbl_alternatif')->count()
        );
        return response()->json($response);
    }
    public function allAlternatif(Request $request){
        $data = DB::table('tbl_alternatif')->get();
        return response()->json($data);
    }
}
