<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class EvaluasiController extends Controller
{
    public function index(){
        $data = array(
            'alternatif'=>DB::table('tbl_alternatif')->count(),
            'kriteria'=>DB::table('tbl_kriteria')->count(),
            'class'=>'evaluasi'
        );
        return view('pages.evaluasi',$data);
    }
    public function allEvaluasi(Request $request){
        if($request->ajax()){
            $alternatif = DB::table('tbl_alternatif')->get();
            $kriteria = DB::table('tbl_kriteria')->get();
    
            $arrAlternatif = array();
            $arrKriteria = array();
            $arrEvaluasi = array();
    
            $x = 0;
            foreach($alternatif as $rowAlt){
                $y = 0;
                foreach($kriteria as $rowKr){
                    $data = DB::table('tbl_evaluasi')
                            ->where('id_alternatif',$rowAlt->id_alternatif)
                            ->where('id_kriteria',$rowKr->id_kriteria)
                            ->first();
                    $arrEvaluasi[$x][$y] = $data->nilai_evaluasi;
                    $y++;
                }
                $x++;
            }
            $arrAlternatif = json_decode(json_encode($alternatif));
            $arrKriteria = json_decode(json_encode($kriteria));
    
            return response()->json(array(
                'kriteria'=>$arrKriteria,
                'alternatif'=>$arrAlternatif,
                'evaluasi'=>$arrEvaluasi
            ));
        }
    }
    public function getEvaluasi(Request $request){
        if($request->ajax()){
            $arrEvaluasi = array();
            $id = $request->id_alternatif;
            $alternatif = DB::table('tbl_alternatif')->where('id_alternatif','=',$id)->first();
            $kriteria = DB::table('tbl_kriteria')->get();
            foreach($kriteria as $row){
                $evaluasi = DB::table('tbl_evaluasi')
                        ->where('tbl_evaluasi.id_kriteria','=',$row->id_kriteria)
                        ->where('tbl_evaluasi.id_alternatif','=',$id)
                        ->join('tbl_kriteria','tbl_evaluasi.id_kriteria','=','tbl_kriteria.id_kriteria')
                        ->first();
                array_push($arrEvaluasi,$evaluasi);
            }
            return response()->json(array(
                'alternatif'=>$alternatif,
                'evaluasi'=>$arrEvaluasi
            ));
        }
    }
    public function updateEvaluasi(Request $request){
        if($request->ajax()){
            $evaluasi = $request->kode_evaluasi;
            $nilai_evaluasi = $request->nilai_evaluasi;
            foreach($evaluasi as $key=>$value){
                if(is_numeric($nilai_evaluasi[$key])){
                    DB::table('tbl_evaluasi')->where('id_evaluasi','=',$value)->update(['nilai_evaluasi'=>$nilai_evaluasi[$key]]);
                }else{
                    DB::table('tbl_evaluasi')->where('id_evaluasi','=',$value)->update(['nilai_evaluasi'=>0]);
                }
            }
            $response = array('message'=>'Data Alternatif Sudah Di Ubah','status'=>'info');
            return response()->json($response);
        }
    }
}
