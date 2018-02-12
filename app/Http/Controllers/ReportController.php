<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ReportController extends Controller
{
    public function index(){
        
        $alternatif= DB::table('tbl_alternatif')->count();
        $kriteria = DB::table('tbl_kriteria')->count();
        if($alternatif > 0 && $kriteria > 0){
            $status = true;
        }else{
            $status = false;
        }
        $data = array(
            'class'=>'report',
            'status'=>$status,
            'kriteria'=>$kriteria,
            'alternatif'=>$alternatif
        );
        return view('pages.report',$data);
    }
    public function normalisasiBobot(){
        $dataKriteria = array();
        $total = DB::table('tbl_kriteria')->sum('nilai_kriteria');
        $kriteria = DB::table('tbl_kriteria')->get();
        foreach($kriteria as $key=>$value){
            $dataKriteria[$key]['id_kriteria'] = $value->id_kriteria;
            $dataKriteria[$key]['nama_kriteria'] = $value->nama_kriteria;
            $dataKriteria[$key]['nilai_kriteria'] = $value->nilai_kriteria;
            $nilai_bobot = $value->nilai_kriteria/$total;
            $dataKriteria[$key]['nilai_bobot'] = $nilai_bobot;
        }
        foreach($dataKriteria as $key=>$value){
            DB::table('tbl_kriteria')
                ->where('id_kriteria',$value['id_kriteria'])
                ->update(['nilai_bobot'=>$value['nilai_bobot']]);
        }
    }
    public function normalisasiMatriks(){
        $alternatif = DB::table('tbl_alternatif')->get();
        $kriteria = DB::table('tbl_kriteria')->get();
        $arrayInsert = array();
        foreach($kriteria as $rowKriteria){
            if($rowKriteria->type_kriteria=="benefit"){
                $max = DB::table('tbl_evaluasi')
                    ->where('id_kriteria',$rowKriteria->id_kriteria)
                    ->max('nilai_evaluasi');
                foreach($alternatif as $rowAlternatif){
                    $nilai = DB::table('tbl_evaluasi')
                        ->where('id_alternatif',$rowAlternatif->id_alternatif)
                        ->where('id_kriteria',$rowKriteria->id_kriteria)->first();
                    $x = array(
                        'id_evaluasi'=>$nilai->id_evaluasi,
                        'nilai_matriks'=>$nilai->nilai_evaluasi/$max
                    );
                        array_push($arrayInsert,$x);
                }
            }else{
                $min = DB::table('tbl_evaluasi')
                    ->where('id_kriteria',$rowKriteria->id_kriteria)
                    ->min('nilai_evaluasi');
                foreach($alternatif as $rowAlternatif){
                    $nilai = DB::table('tbl_evaluasi')
                        ->where('id_alternatif',$rowAlternatif->id_alternatif)
                        ->where('id_kriteria',$rowKriteria->id_kriteria)->first();
                    $x = array(
                        'id_evaluasi'=>$nilai->id_evaluasi,
                        'nilai_matriks'=>$min/$nilai->nilai_evaluasi
                    );
                    array_push($arrayInsert,$x);
                }
            }
        }
        DB::table('tbl_matriks_saw')->truncate();
        DB::table('tbl_matriks_saw')->insert($arrayInsert);
    }
    public function preferensiVektorWP(){
        $alternatif = DB::table('tbl_alternatif')->get();
        foreach($alternatif as $rowAlternatif=>$value){
            $evaluasi = DB::table('tbl_evaluasi')
                ->join('tbl_kriteria','tbl_evaluasi.id_kriteria','=','tbl_kriteria.id_kriteria')
                ->where('id_alternatif',$value->id_alternatif)
                ->get();
            $s = 1;
            foreach($evaluasi as $rowEvaluasi){
                if($rowEvaluasi->type_kriteria=="benefit"){
                    $s *= pow($rowEvaluasi->nilai_evaluasi,$rowEvaluasi->nilai_bobot);
                }else{
                    $s *= pow($rowEvaluasi->nilai_evaluasi,-$rowEvaluasi->nilai_bobot);
                }
            }
            DB::table('tbl_vektor_wp')
                ->where('id_alternatif',$value->id_alternatif)
                ->update(array('nilai_vektor_s'=>$s));
        }
        $TotalVektor = DB::table('tbl_vektor_wp')->sum('nilai_vektor_s');
        $vektorWP = DB::table('tbl_vektor_wp')->get();
        foreach($vektorWP as $rowVektorWP){
            DB::table('tbl_vektor_wp')
                ->where('id_vektor_wp',$rowVektorWP->id_vektor_wp)
                ->update(array('nilai_vektor_v'=>$rowVektorWP->nilai_vektor_s/$TotalVektor));
        }
    }
    public function preferensiVektorSAW(){
        $alternatif = DB::table('tbl_alternatif')->get();
        $kriteria = DB::table('tbl_kriteria')->get();
        foreach($alternatif as $rowAlternatif){
            $nilaiPreferensi = 0;
            foreach($kriteria as $rowKriteria){
                $nilai = DB::table('tbl_evaluasi')
                    ->join('tbl_matriks_saw','tbl_evaluasi.id_evaluasi','=','tbl_matriks_saw.id_evaluasi')
                    ->where('id_alternatif',$rowAlternatif->id_alternatif)
                    ->where('id_kriteria',$rowKriteria->id_kriteria)
                    ->first();
                $nilaiPreferensi = $nilaiPreferensi + ($nilai->nilai_matriks*$rowKriteria->nilai_bobot);
            }
            DB::table('tbl_vektor_saw')
                ->where('id_alternatif',$rowAlternatif->id_alternatif)
                ->update(array('nilai_vektor_v'=>$nilaiPreferensi));
        }
    }
    public function getNormalisasiKriteria(){
        $this->normalisasiBobot();
        $data = DB::table('tbl_kriteria')->get();
        return response()->json($data);
    }
    public function getMatrikSAW(){
        $this->normalisasiMatriks();

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
                        ->join('tbl_matriks_saw','tbl_evaluasi.id_evaluasi','=','tbl_matriks_saw.id_evaluasi')
                        ->where('id_alternatif',$rowAlt->id_alternatif)
                        ->where('id_kriteria',$rowKr->id_kriteria)
                        ->first();
                $arrEvaluasi[$x][$y] = $data->nilai_matriks;
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
    public function getPreferensiWP(){
        $this->preferensiVektorWP();
        $relateWP = array();
        $dataWP = DB::table('tbl_alternatif')->join('tbl_vektor_wp','tbl_alternatif.id_alternatif','=','tbl_vektor_wp.id_alternatif')->get();
        if(count($dataWP)>0){
            foreach($dataWP as $key=>$row){
                $nilai_vektor_v_wp[$key] = $row->nilai_vektor_v;
                $wp[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
                array_multisort($nilai_vektor_v_wp,SORT_DESC,$wp);
                foreach($wp as $key=>$value){
                    $relateWP[$value['id_alternatif']]['rank'] = $key+1;
                }
            }
            foreach($dataWP as $key=>$rowWP){
                $dataReturn[$key] = array(
                    'nama_alternatif'=>$rowWP->nama_alternatif,
                    'nilai_vektor_s'=>$rowWP->nilai_vektor_s,
                    'nilai_vektor_v'=>$rowWP->nilai_vektor_v,
                    'ranking_wp'=>$relateWP[$rowWP->id_alternatif]['rank']
                );
            }
            return response()->json($dataReturn);
        }else{
            return response()->json(['message'=>'Data Alternatif dan Kriteria tidak lengkap'],500);
        }
    }
    public function getPreferensiSAW(){
        $this->preferensiVektorSAW();
        $dataSAW = DB::table('tbl_alternatif')->join('tbl_vektor_saw','tbl_alternatif.id_alternatif','=','tbl_vektor_saw.id_alternatif')->get();
        if(count($dataSAW)>0){
            foreach($dataSAW as $key=>$row){
                $nilai_vektor_v_saw[$key] = $row->nilai_vektor_v;
                $saw[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
                array_multisort($nilai_vektor_v_saw,SORT_DESC,$saw);
                foreach($saw as $key=>$value){
                    $relateSAW[$value['id_alternatif']]['rank'] = $key+1;
                }
            }
            foreach($dataSAW as $key=>$rowSAW){
                $dataReturn[$key] = array(
                    'nama_alternatif'=>$rowSAW->nama_alternatif,
                    'nilai_vektor_v'=>$rowSAW->nilai_vektor_v,
                    'ranking'=>$relateSAW[$rowSAW->id_alternatif]['rank']
                );
            }
            return response()->json($dataReturn);
        }else{
            return response()->json(['message'=>'Data Alternatif dan Kriteria tidak lengkap'],500);
        }
    }
    public function getAll(){
        $this->normalisasiBobot();
        $this->normalisasiMatriks();
        $this->preferensiVektorWP();
        $this->preferensiVektorSAW();
        $dataWP = DB::table('tbl_alternatif')->select('tbl_alternatif.id_alternatif','tbl_vektor_wp.nilai_vektor_v')->join('tbl_vektor_wp','tbl_alternatif.id_alternatif','=','tbl_vektor_wp.id_alternatif')->get();
        $dataSAW = DB::table('tbl_alternatif')->select('tbl_alternatif.id_alternatif','tbl_vektor_saw.nilai_vektor_v')->join('tbl_vektor_saw','tbl_alternatif.id_alternatif','=','tbl_vektor_saw.id_alternatif')->get();
        if(count($dataWP)>0){
            foreach($dataWP as $key=>$row){
                $nilai_vektor_v_wp[$key] = $row->nilai_vektor_v;
                $wp[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
            }
            foreach($dataSAW as $key=>$row){
                $nilai_vektor_v_saw[$key] = $row->nilai_vektor_v;
                $saw[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
            }
            array_multisort($nilai_vektor_v_wp,SORT_DESC,$wp);
            array_multisort($nilai_vektor_v_saw,SORT_DESC,$saw);
            foreach($wp as $key=>$value){
                $relateWP[$value['id_alternatif']] = array(
                    'rank'=>$key+1
                );
            }
            foreach($saw as $key=>$value){
                $relateSAW[$value['id_alternatif']] = array(
                    'rank'=>$key+1
                );
            }
            $dataTable = DB::table('tbl_alternatif')
                            ->select('tbl_alternatif.id_alternatif','tbl_alternatif.nama_alternatif','tbl_vektor_wp.nilai_vektor_s','tbl_vektor_wp.nilai_vektor_v as nilai_vektor_v_wp','tbl_vektor_saw.nilai_vektor_v as nilai_vektor_v_saw')
                            ->join('tbl_vektor_wp','tbl_alternatif.id_alternatif','=','tbl_vektor_wp.id_alternatif')
                            ->join('tbl_vektor_saw','tbl_alternatif.id_alternatif','=','tbl_vektor_saw.id_alternatif')
                            ->get();
            foreach($dataTable as $key=>$value){
                $dataReturn[$key] = array(
                    'id_alternatif'=>$value->id_alternatif,
                    'nama_alternatif'=>$value->nama_alternatif,
                    'nilai_vektor_s'=>$value->nilai_vektor_s,
                    'nilai_vektor_v_wp'=>$value->nilai_vektor_v_wp,
                    'ranking_wp'=>$relateWP[$value->id_alternatif]['rank'],
                    'nilai_vektor_v_saw'=>$value->nilai_vektor_v_saw,
                    'ranking_saw'=>$relateSAW[$value->id_alternatif]['rank']
                );
            }
        }else{
            $dataReturn = array();
        }
        return response()->json($dataReturn);
    }
    public function WPIndex(){
        //Status Jumlah Alternatif & Kriteria
        $alternatif= DB::table('tbl_alternatif')->count();
        $kriteria = DB::table('tbl_kriteria')->count();
        //Normalisasi Kriteria
        $this->normalisasiBobot();
        //Preferensi Kriteria
        $this->preferensiVektorWP();
        $relateWP = array();
        $dataWP = DB::table('tbl_alternatif')->join('tbl_vektor_wp','tbl_alternatif.id_alternatif','=','tbl_vektor_wp.id_alternatif')->get();
        if(count($dataWP)>0){
            foreach($dataWP as $key=>$row){
                $nilai_vektor_v_wp[$key] = $row->nilai_vektor_v;
                $wp[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
                array_multisort($nilai_vektor_v_wp,SORT_DESC,$wp);
                foreach($wp as $key=>$value){
                    $relateWP[$value['id_alternatif']]['rank'] = $key+1;
                }
            }
            foreach($dataWP as $key=>$rowWP){
                $dataReturn[$key] = array(
                    'nama_alternatif'=>$rowWP->nama_alternatif,
                    'nilai_vektor_s'=>$rowWP->nilai_vektor_s,
                    'nilai_vektor_v'=>$rowWP->nilai_vektor_v,
                    'ranking_wp'=>$relateWP[$rowWP->id_alternatif]['rank']
                );
            }
        }else{
            $dataReturn = array();
        }
        //Data Passing Value
        $bobotKriteria = DB::table('tbl_kriteria')->get();
        $data = array(
            'class'=>'reportwp',
            'kriteria'=>$kriteria,
            'alternatif'=>$alternatif,
            'bobotKriteria'=>$bobotKriteria,
            'preferensi'=>$dataReturn
        );
        return view('pages.reportwp',$data);
    }
    public function SAWIndex(){
        $dataReturn = array();

        $countAlternatif= DB::table('tbl_alternatif')->count();
        $countKriteria = DB::table('tbl_kriteria')->count();
        
        $awal = microtime(true);
        //Normalisasi Kriteria
        $this->normalisasiBobot();
        //Preferensi Matriks SAW
        $this->normalisasiMatriks();
        //Preferensi Vektor SAW
        $this->preferensiVektorSAW();
        $akhir = microtime(true);
        $totalwaktu = $akhir  - $awal;
        
        // Data 1 - Kriteria
        $bobotKriteria = DB::table('tbl_kriteria')->get();

        //Data 2 - Matriks
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
                        ->join('tbl_matriks_saw','tbl_evaluasi.id_evaluasi','=','tbl_matriks_saw.id_evaluasi')
                        ->where('id_alternatif',$rowAlt->id_alternatif)
                        ->where('id_kriteria',$rowKr->id_kriteria)
                        ->first();
                $arrEvaluasi[$x][$y] = $data->nilai_matriks;
                $y++;
            }
            $x++;
        }
        $arrAlternatif = json_decode(json_encode($alternatif));
        $arrKriteria = json_decode(json_encode($kriteria));

        // //Data 3 - Preferensi SAW
        $dataSAW = DB::table('tbl_alternatif')->join('tbl_vektor_saw','tbl_alternatif.id_alternatif','=','tbl_vektor_saw.id_alternatif')->get();
        if(count($dataSAW)>0){
            foreach($dataSAW as $key=>$row){
                $nilai_vektor_v_saw[$key] = $row->nilai_vektor_v;
                $saw[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
                array_multisort($nilai_vektor_v_saw,SORT_DESC,$saw);
                foreach($saw as $key=>$value){
                    $relateSAW[$value['id_alternatif']]['rank'] = $key+1;
                }
            }
            foreach($dataSAW as $key=>$rowSAW){
                $dataReturn[$key] = array(
                    'nama_alternatif'=>$rowSAW->nama_alternatif,
                    'nilai_vektor_v'=>$rowSAW->nilai_vektor_v,
                    'ranking'=>$relateSAW[$rowSAW->id_alternatif]['rank']
                );
            }
            $status = true;
        }else{
            $status = false;
        }

        // //Data Collection
        $data = array(
            'class'=>'reportsaw',
            'kriteria'=>$countKriteria,
            'alternatif'=>$countAlternatif,
            'bobotKriteria'=>$bobotKriteria,
            'time'=>number_format($totalwaktu, 10, '.', ''),
            'arrKriteria'=>$arrKriteria,
            'arrAlternatif'=>$arrAlternatif,
            'arrEvaluasi'=>$arrEvaluasi,
            'preferensi'=>$dataReturn
        );
        return view('pages.reportsaw',$data);
        // return response()->json($data);
        // echo "Halaman ini di eksekusi dalam waktu " . number_format($totalwaktu, 10, '.', '') . " detik!";
    }
    public function ReportCompare(){
        $countAlternatif= DB::table('tbl_alternatif')->count();
        $countKriteria = DB::table('tbl_kriteria')->count();

        $this->normalisasiBobot();
        $this->normalisasiMatriks();
        $this->preferensiVektorWP();
        $this->preferensiVektorSAW();

        //Benchmark SAW        
        $arrKriteriaSAW = array();
        $arrEvaluasiSAW = array();
        $arrAlternatifSAW = array();
        $arrMatriksSAW = array();
        $dataKriteriaSAW = DB::table('tbl_kriteria')->get();
        $sumKriteriaSAW = DB::table('tbl_kriteria')->sum('nilai_kriteria');
        $dataEvaluasiSAW = DB::table('tbl_evaluasi')->join('tbl_alternatif','tbl_evaluasi.id_alternatif','=','tbl_alternatif.id_alternatif')->get();
        $dataAlternatifSAW = DB::table('tbl_alternatif')->get();

        $startSAW = microtime(true);
        if(count($dataEvaluasiSAW)>0){
            foreach($dataKriteriaSAW as $item){
                $arrKriteriaSAW[$item->id_kriteria]['nilai_bobot']=$item->nilai_kriteria/$sumKriteriaSAW;
                $arrKriteriaSAW[$item->id_kriteria]['type_kriteria'] = $item->type_kriteria;
            }
            foreach($dataEvaluasiSAW as $item){
                $arrNilai[$item->id_kriteria][] = $item->nilai_evaluasi;
            }
            foreach($dataEvaluasiSAW as $item){
                if($arrKriteriaSAW[$item->id_kriteria]['type_kriteria']=="cost"){
                    $arrEvaluasiSAW[$item->id_alternatif][$item->id_kriteria] = min($arrNilai[$item->id_kriteria])/$item->nilai_evaluasi;
                }else{
                    $arrEvaluasiSAW[$item->id_alternatif][$item->id_kriteria] = $item->nilai_evaluasi/max($arrNilai[$item->id_kriteria]);
                }
            }
            foreach($dataAlternatifSAW as $keyAlternatif=>$itemAlternatif){
                $arrAlternatifSAW[$keyAlternatif]['id_alternatif'] = $itemAlternatif->id_alternatif;
                $arrAlternatifSAW[$keyAlternatif]['nama_alternatif'] = $itemAlternatif->nama_alternatif;
                $s = 0;
                foreach($arrKriteriaSAW as $keyKriteria=>$itemKriteria){
                    $s += $itemKriteria['nilai_bobot'] * $arrEvaluasiSAW[$itemAlternatif->id_alternatif][$keyKriteria];
                }
                $arrAlternatifSAW[$keyAlternatif]['vektor_v']=$s;
            }
        }
        $stopSAW = microtime(true);
        //End Benchmark

        //Benchmark WP
        $arrKriteriaWP = array();
        $arrEvaluasiWP = array();
        $arrAlternatifWP = array();
        $dataKriteriaWP = DB::table('tbl_kriteria')->get();
        $sumKriteriaWP = DB::table('tbl_kriteria')->sum('nilai_kriteria');
        $dataEvaluasiWP = DB::table('tbl_evaluasi')->get();
        $dataAlternatifWP = DB::table('tbl_alternatif')->get();
        $startWP = microtime(true);
        if(count($dataEvaluasiWP)>0){
            foreach($dataEvaluasiWP as $item){
                $arrEvaluasiWP[$item->id_alternatif][$item->id_kriteria]=$item->nilai_evaluasi;
            }   
            foreach($dataKriteriaWP as $item){
                if($item->type_kriteria=="cost"){
                    $arrKriteriaWP[$item->id_kriteria]['nilai_bobot']=-$item->nilai_kriteria/$sumKriteriaWP;
                }else{
                    $arrKriteriaWP[$item->id_kriteria]['nilai_bobot']=$item->nilai_kriteria/$sumKriteriaWP;
                }
            }
            foreach($dataAlternatifWP as $key=>$item){
                $s = 1;
                $arrAlternatifWP[$item->id_alternatif]['nama_alternatif'] = $item->nama_alternatif;
                foreach($arrKriteriaWP as $key =>$itemKriteriaWP){
                    $s *= pow($arrEvaluasiWP[$item->id_alternatif][$key],$itemKriteriaWP['nilai_bobot']);
                }
                $arrAlternatifWP[$item->id_alternatif]['vektor_s'] = $s;
            }
            $sum = 0;
            foreach($arrAlternatifWP as $item){
                $sum += $item['vektor_s'];
            }
            foreach($arrAlternatifWP as $key=>$item){
                $arrAlternatifWP[$key]['vektor_v'] = $item['vektor_s']/$sum;
            }
        }        
        $stopWP = microtime(true);
        //End Benchmark

        $dataWP = DB::table('tbl_alternatif')->select('tbl_alternatif.id_alternatif','tbl_vektor_wp.nilai_vektor_v')->join('tbl_vektor_wp','tbl_alternatif.id_alternatif','=','tbl_vektor_wp.id_alternatif')->get();
        $dataSAW = DB::table('tbl_alternatif')->select('tbl_alternatif.id_alternatif','tbl_vektor_saw.nilai_vektor_v')->join('tbl_vektor_saw','tbl_alternatif.id_alternatif','=','tbl_vektor_saw.id_alternatif')->get();
        if(count($dataWP)>0){
            foreach($dataWP as $key=>$row){
                $nilai_vektor_v_wp[$key] = $row->nilai_vektor_v;
                $wp[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
            }
            foreach($dataSAW as $key=>$row){
                $nilai_vektor_v_saw[$key] = $row->nilai_vektor_v;
                $saw[$key] = array(
                    'id_alternatif'=>$row->id_alternatif,
                    'nilai_vektor_v'=>$row->nilai_vektor_v
                );
            }
            array_multisort($nilai_vektor_v_wp,SORT_DESC,$wp);
            array_multisort($nilai_vektor_v_saw,SORT_DESC,$saw);
            foreach($wp as $key=>$value){
                $relateWP[$value['id_alternatif']] = array(
                    'rank'=>$key+1
                );
            }
            foreach($saw as $key=>$value){
                $relateSAW[$value['id_alternatif']] = array(
                    'rank'=>$key+1
                );
            }
            $dataTable = DB::table('tbl_alternatif')
                            ->select('tbl_alternatif.id_alternatif','tbl_alternatif.nama_alternatif','tbl_vektor_wp.nilai_vektor_s','tbl_vektor_wp.nilai_vektor_v as nilai_vektor_v_wp','tbl_vektor_saw.nilai_vektor_v as nilai_vektor_v_saw')
                            ->join('tbl_vektor_wp','tbl_alternatif.id_alternatif','=','tbl_vektor_wp.id_alternatif')
                            ->join('tbl_vektor_saw','tbl_alternatif.id_alternatif','=','tbl_vektor_saw.id_alternatif')
                            ->get();
            foreach($dataTable as $key=>$value){
                $dataReturn[$key] = array(
                    'id_alternatif'=>$value->id_alternatif,
                    'nama_alternatif'=>$value->nama_alternatif,
                    'nilai_vektor_s'=>$value->nilai_vektor_s,
                    'nilai_vektor_v_wp'=>$value->nilai_vektor_v_wp,
                    'ranking_wp'=>$relateWP[$value->id_alternatif]['rank'],
                    'nilai_vektor_v_saw'=>$value->nilai_vektor_v_saw,
                    'ranking_saw'=>$relateSAW[$value->id_alternatif]['rank']
                );
            }
        }else{
            $dataReturn = array();
        }

        $time['time_wp'] = number_format($stopWP-$startWP, 10, '.', '');
        $time['time_saw'] = number_format($stopSAW-$startSAW, 10, '.', '');
        $data = array(
            'class'=>'reportcompare',
            'kriteria'=>$countKriteria,
            'alternatif'=>$countAlternatif,
            'data'=>$dataReturn,
            'time'=>$time
        );
        return view('pages.reportcompare',$data);
    }
    public function benchmarkTestWP(){
        $arrKriteria = array();
        $arrEvaluasi = array();
        $arrAlternatif = array();
        $dataKriteria = DB::table('tbl_kriteria')->get();
        $sumKriteria = DB::table('tbl_kriteria')->sum('nilai_kriteria');
        $dataEvaluasi = DB::table('tbl_evaluasi')->join('tbl_alternatif','tbl_evaluasi.id_alternatif','=','tbl_alternatif.id_alternatif')->get();
        $dataAlternatif = DB::table('tbl_alternatif')->get();

        foreach($dataEvaluasi as $item){
            $arrEvaluasi[$item->id_alternatif][$item->id_kriteria]=$item->nilai_evaluasi;
        }   
        foreach($dataKriteria as $item){
            if($item->type_kriteria=="cost"){
                $arrKriteria[$item->id_kriteria]['nilai_bobot']=-$item->nilai_kriteria/$sumKriteria;
            }else{
                $arrKriteria[$item->id_kriteria]['nilai_bobot']=$item->nilai_kriteria/$sumKriteria;
            }
        }
        foreach($dataAlternatif as $key=>$item){
            $s = 1;
            $arrAlternatif[$item->id_alternatif]['nama_alternatif'] = $item->nama_alternatif;
            foreach($arrKriteria as $key =>$itemKriteria){
                $s *= pow($arrEvaluasi[$item->id_alternatif][$key],$itemKriteria['nilai_bobot']);
            }
            $arrAlternatif[$item->id_alternatif]['vektor_s'] = $s;
        }
        $sum = array_sum(array_column($arrAlternatif, 'vektor_s'));
        foreach($arrAlternatif as $key=>$item){
            $arrAlternatif[$key]['vektor_v'] = $item['vektor_s']/$sum;
        }
    }
    public function benchmarkTestSAW(){
        $arrKriteria = array();
        $arrEvaluasi = array();
        $arrAlternatif = array();
        $dataKriteriaSAW = DB::table('tbl_kriteria')->get();
        $sumKriteria = DB::table('tbl_kriteria')->sum('nilai_kriteria');
        $dataEvaluasi = DB::table('tbl_evaluasi')->join('tbl_alternatif','tbl_evaluasi.id_alternatif','=','tbl_alternatif.id_alternatif')->get();
        $dataAlternatif = DB::table('tbl_alternatif')->get();

        foreach($dataKriteria as $item){
            $arrKriteria[$item->id_kriteria]['nilai_bobot']=$item->nilai_kriteria/$sumKriteria;
            $arrKriteria[$item->id_kriteria]['type_kriteria'] = $item->type_kriteria;
        }
        foreach($dataEvaluasi as $item){
            $arrNilai[$item->id_kriteria][] = $item->nilai_evaluasi;
        }
        foreach($dataEvaluasi as $item){
            if($arrKriteria[$item->id_kriteria]['type_kriteria']=="cost"){
                $arrEvaluasi[$item->id_alternatif][$item->id_kriteria] = min($arrNilai[$item->id_kriteria])/$item->nilai_evaluasi;
            }else{
                $arrEvaluasi[$item->id_alternatif][$item->id_kriteria] = $item->nilai_evaluasi/max($arrNilai[$item->id_kriteria]);
            }
        }
        foreach($dataAlternatif as $keyAlternatif=>$itemAlternatif){
            $arrAlternatif[$keyAlternatif]['id_alternatif'] = $itemAlternatif->id_alternatif;
            $arrAlternatif[$keyAlternatif]['nama_alternatif'] = $itemAlternatif->nama_alternatif;
            $s = 0;
            foreach($arrKriteria as $keyKriteria=>$itemKriteria){
                $s += $itemKriteria['nilai_bobot'] * $arrEvaluasi[$itemAlternatif->id_alternatif][$keyKriteria];
            }
            $arrAlternatif[$keyAlternatif]['vektor_v']=$s;
        }
        $ret = array(
            'kriteria'=>$arrKriteria,
            'evaluasi'=>$dataEvaluasi,
            'arrNilai'=>$arrNilai,
            'arrEvaluasi'=>$arrEvaluasi,
            'arrAlternatif'=>$arrAlternatif
        );
    }
}
