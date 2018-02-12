<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Datatables;
class KriteriaController extends Controller
{
    public function index(){
        $data = array(
            'alternatif'=>DB::table('tbl_alternatif')->count(),
            'kriteria'=>DB::table('tbl_kriteria')->count(),
            'class'=>'kriteria'
        );
        return view('pages.kriteria',$data);
    }
    public function saveKriteria(Request $request){
        if($request->ajax()){
            $insertMatriks = array();
            
            $data = $request->all();
            $message = array(
                'nama_kriteria.required'=>'Nama Kriteria Harus di Isi',
                'nama_kriteria.max'=>'Nama Kriteria Maksimal 100 Karakter',
                'type_kriteria.required'=>'Type Kriteria Harus di Isi',
                'nilai_kriteria.required'=>'Nilai Kriteria Harus di Isi',
                'nilai_kriteria.numeric'=>'Nilai Kriteria Harus Berisi Angka'
            ); 
            Validator::make($data,[
                'nama_kriteria'=>['required','max:100'],
                'type_kriteria'=>['required'],
                'nilai_kriteria'=>['required','numeric']
            ],$message)->validate();

            $id = DB::table('tbl_kriteria')->insertGetId($data);
            $alternatif = DB::table('tbl_alternatif')->get();
            foreach($alternatif as $row){
                $idEvaluasi = DB::table('tbl_evaluasi')->insertGetId(array(
                    'id_alternatif'=>$row->id_alternatif,
                    'id_kriteria'=>$id,
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
                'message'=>'Data Kriteria Sudah Di Simpan',
                'status'=>'info',
                'kriteria'=>DB::table('tbl_kriteria')->count()
            );
            return response()->json($response);
        }
    }
    public function getKriteria(Request $request){
        if($request->ajax()){
            $kriteria = DB::table('tbl_kriteria')->where('id_kriteria','=',$request->id_kriteria)->first();
            $response = array(
                'id_kriteria'=>$kriteria->id_kriteria,
                'nama_kriteria'=>$kriteria->nama_kriteria,
                'type_kriteria'=>$kriteria->type_kriteria,
                'nilai_kriteria'=>$kriteria->nilai_kriteria
            );
            return response()->json($response);
        }
    }
    public function updateKriteria(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $message = array(
                'nama_kriteria.required'=>'Nama Kriteria Harus di Isi',
                'nama_kriteria.max'=>'Nama Kriteria Maksimal 100 Karakter',
                'type_kriteria.required'=>'Type Kriteria Harus di Isi',
                'nilai_kriteria.required'=>'Nilai Kriteria Harus di Isi',
                'nilai_kriteria.numeric'=>'Nilai Kriteria Harus Berisi Angka'
            ); 
            Validator::make($data,[
                'nama_kriteria'=>['required','max:100'],
                'type_kriteria'=>['required'],
                'nilai_kriteria'=>['required','numeric']
            ],$message)->validate();
            
            DB::table('tbl_kriteria')->where('id_kriteria',$request->id_kriteria)->update($data);
            $response = array(
                'message'=>'Data Kriteria Sudah Di Ubah',
                'status'=>'info'
            );
            return response()->json($response);
        }
    }
    public function deleteKriteria(Request $request){
        if($request->ajax()){
            DB::table('tbl_kriteria')->where('id_kriteria','=',$request->id_kriteria)->delete();
            $response = array(
                'message'=>'Data Kriteria Sudah Di Hapus',
                'status'=>'info',
                'id'=>$request->id_kriteria,
                'kriteria'=>DB::table('tbl_kriteria')->count()
            );
            return response()->json($response);
        }
    }
    public function allKriteria(){
        $data = DB::table('tbl_kriteria')->get();
        return response()->json($data);
    }
}
