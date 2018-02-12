<?php

Route::group(['middleware'=>'adminCheck'],function(){
    Route::get('/','DashboardController@index');
    Route::get('/kriteria','KriteriaController@index');
    Route::get('/alternatif','AlternatifController@index');
    Route::get('/evaluasi','EvaluasiController@index');
    Route::get('/report','ReportController@index');
    Route::get('/report/wp','ReportController@WPIndex');
    Route::get('/report/saw','ReportController@SAWIndex');
    Route::get('/report/compare','ReportController@ReportCompare');
    Route::get('/admin','AdminController@index');
    Route::get('/admin/logout','AdminController@logout');

    Route::post('/kriteria/save','KriteriaController@saveKriteria');
    Route::post('/kriteria/get','KriteriaController@getKriteria');
    Route::post('/kriteria/update','KriteriaController@updateKriteria');
    Route::post('/kriteria/delete','KriteriaController@deleteKriteria');
    Route::post('/kriteria/all','KriteriaController@allKriteria');
    
    Route::post('/alternatif/save','AlternatifController@saveAlternatif');
    Route::post('/alternatif/get','AlternatifController@getAlternatif');
    Route::post('/alternatif/update','AlternatifController@updateAlternatif');
    Route::post('/alternatif/delete','AlternatifController@deleteAlternatif');
    Route::post('/alternatif/all','AlternatifController@allAlternatif');
    
    Route::post('/evaluasi/all','EvaluasiController@allEvaluasi');
    Route::post('/evaluasi/get','EvaluasiController@getEvaluasi');
    Route::post('/evaluasi/update','EvaluasiController@updateEvaluasi');
    
    Route::post('/report/kriteria','ReportController@getNormalisasiKriteria');
    Route::post('/report/matriks','ReportController@getMatrikSAW');
    Route::post('/report/wp','ReportController@getPreferensiWP');
    Route::post('/report/saw','ReportController@getPreferensiSAW');
    Route::post('/report/all','ReportController@getAll');
    
    Route::post('/admin/save','AdminController@saveAdmin');
    Route::post('/admin/delete','AdminController@deleteAdmin');
    Route::post('/admin/data','AdminController@dataAdmin');
});
Route::group(['middleware'=>'loginCheck'],function(){
    Route::get('/login','AdminController@login');
    Route::post('/login','AdminController@doLogin'); 
});
Route::get('/testBench','ReportController@benchmarkTest');