@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Report SAW</a>
</div>
@endsection
@section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box" style="display:block;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>1. Normalisaasi Bobot</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped">
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
				<h5>2. Normalisasi Matriks SAW</h5>
			</div>
			<div class="widget-content">
				@if(count($arrKriteria)>0 && count($arrAlternatif)>0 && count($arrEvaluasi)>0)
					<table class="table table-bordered table-striped">
						<thead id="tableHeadingMatriks">
							@if(count($arrKriteria)>0)
								<tr>
									<th>Nama Alternatif</th>
									@foreach($arrKriteria as $item)
										<th><center>{{ $item->nama_kriteria }}</center></th>
									@endforeach
								</tr>
							@endif
						</thead>
						<tbody id="tableBodyMatriks">
							@if(count($arrAlternatif) > 0 && count($arrEvaluasi)>0)
								@foreach($arrAlternatif as $key=>$itemAlternatif)
									<tr>
										<td>{{ $itemAlternatif->nama_alternatif }}</td>
										@for($i=0;$i<count($arrEvaluasi[$key]);$i++)
											<td><center>{{ $arrEvaluasi[$key][$i] }}</center></td>
										@endfor
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				@else
					<center><h1>Data Matriks Tidak Ada</h1></center>
					<center><h2>Data Alternatif & Kriteria harus lengkap</h2></center>
				@endif
			</div>
		</div>
		<div class="widget-box" style="display:block;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>3. Perhitungan Nilai Preferensi Metode SimpleAdditiveWeighting</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nama Alternatif</th>
							<th>Nilai Vektor V</th>
							<th>Ranking</th>
						</tr>
					</thead>
					<tbody>
						@if(count($preferensi)>0)
							@foreach($preferensi as $item)
								<tr>
									<td>{{ $item['nama_alternatif'] }}</td>
									<td><center>{{ $item['nilai_vektor_v'] }}</center></td>
									<td><center>{{ $item['ranking'] }}</center></td>
								</tr>
							@endforeach
						@else
							<tr><td colspan="3"><center>Tidak Ada Data Kalkulasi SAW</center></td></tr>
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