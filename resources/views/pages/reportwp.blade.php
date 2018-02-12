@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Report WP</a>
</div>
@endsection
@section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box" style="display:block;" id="stepSatuWPSingle">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>1. Normalisaasi Bobot</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped" id="kriteriaWPSingle">
					<thead>
						<tr>
							<th style="width:10%">ID Kriteria</th>
							<th>Nama Kriteria</th>
							<th style="width:10%">Nilai Bobot</th>
							<th style="width:30%">Normalisasi Bobot</th>
						</tr>
					</thead>
					<tbody>
						@if(count($bobotKriteria)>0)
							@php
								$sum = 0;
							@endphp
							@foreach($bobotKriteria as $item)
								<tr>
									<td><center>{{ $item->id_kriteria }}</center></td>
									<td>{{ $item->nama_kriteria }}</td>
									<td><center>{{ $item->nilai_kriteria }}</center></td>
									<td><center>{{ $item->nilai_bobot }}</center></td>
								</tr>
								@php
									$sum = $sum + $item->nilai_bobot;
								@endphp
							@endforeach
							<tr><td colspan="2"></td><td><center>Jumlah</center></td><td><center>{{ $sum }}</center></td></tr>
						@else
							<tr><td colspan="4"><center>Tidak Ada Bobot Kriteria</center></td></tr>
						@endif
                    </tbody>
				</table>
			</div>
		</div>
		<div class="widget-box" style="display:block;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>2. Perhitungan Preferensi Metode Weighted Product</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nama Alternatif</th>
							<th>Nilai Vektor S</th>
							<th>Nilai Vektor V</th>
							<th>Ranking</th>
						</tr>
					</thead>
					<tbody>
						@if(count($preferensi)>0)
							@foreach($preferensi as $item)
								<tr>
									<td>{{ $item['nama_alternatif'] }}</td>
									<td><center>{{ $item['nilai_vektor_s'] }}</center></td>
									<td><center>{{ $item['nilai_vektor_v'] }}</center></td>
									<td><center>{{ $item['ranking_wp'] }}</center></td>
								</tr>
							@endforeach
						@else
							<tr><td colspan="4"><center>Tidak Ada Data Kalkulasi WP</center></td></tr>
						@endif
                    </tbody>				
				</table>
			</div>
		</div>		
	</div>
</div>
@endsection 
@section('js')
<script>
	$(document).ready(function(){
		$(".se-pre-con").fadeOut("slow");
	});
</script>
@endsection