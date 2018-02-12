@extends('template') 
@section('content-header')
    <div id="breadcrumb"> 
        <a href="{{ url('/') }}" title="Go to Dashboard" class="tip-bottom"><i class="icon-home"></i> Home</a></a> 
    </div>
@endsection
@section('content')
<div class="row-fluid">
	<div class="span4">
		<ul class="site-stats">
			<li class="bg_lb">
				<i class="icon-user"></i>
				<strong>{{$alternatif}}</strong>
				<small>Total Alternatif</small>
			</li>
		</ul>
	</div>
	<div class="span4">
		<ul class="site-stats">
			<li class="bg_lb">
				<i class="icon-user"></i>
				<strong>{{$wp}}</strong>
				<small>Alternatif Preferensi WP</small>
			</li>
		</ul>
	</div>
	<div class="span4">
		<ul class="site-stats">
			<li class="bg_lb">
				<i class="icon-user"></i>
				<strong>{{$saw}}</strong>
				<small>Alternatif Preferensi SAW</small>
			</li>
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="widget-box">
		<div class="widget-title bg_lg">
			<span class="icon">
				<i class="icon-signal"></i>
			</span>
			<h5>Penjelasan Metode</h5>
		</div>
		<div class="widget-content">
			<div class="row-fluid nopadding">
				<div class="span12">					
					<h3>Simple Additive Weighting</h3>
					<p>
						Metode SAW sering dikenal dengan istilah metode penjumlahan terbobot. 
						Konsep dasar metode SAW (Simple Additive Weighting) adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut. 
						Metode SAW dapat membantu dalam pengambilan keputusan suatu kasus, akan tetapi perhitungan dengan menggunakan metode SAW ini hanya yang menghasilkan nilai terbesar yang akan terpilih sebagai alternatif yang terbaik. 
						Perhitungan akan sesuai dengan metode ini apabila alternatif yang terpilih memenuhi kriteria yang telah ditentukan. 
						Metode SAW ini lebih efisien karena waktu yang dibutuhkan dalam perhitungan lebih singkat. 
						Metode SAW membutuhkan proses normalisasi matriks keputusan (X) ke suatu skala yang dapat diperbandingkan dengan semua rating alternatif yang ada.
					</p>
					<br>
					<h3>Weighted Product</h3>
                <p>Metode Weighted Product (WP) adalah salah satu metode penyelesaian pada sistem pendukung keputusan. Metode ini mengevaluasi beberapa alternatif terhadap sekumpulan atribuat atau kriteria, dimana setiap atribut saling tidak bergantung satu dengan yang lainnya. Menurut Yoon (dalam buku Kusumadewi, 2006), metode weighted product menggunakan teknik perkalian untuk menghubungkan rating atribut, dimana rating tiap atribut harus dipangkatkan terlebih dahulu dengan bobot atribut yang bersangkutan.</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
<script>
	$(document).ready(function(){
		$(".se-pre-con").fadeOut("slow");
		$.ajaxSetup({
			beforeSend: function(){
            	$(".se-pre-con").fadeIn();
            },
            complete: function(){
            	$(".se-pre-con").fadeOut("slow");
            }	
		});		
	});
</script>
@endsection