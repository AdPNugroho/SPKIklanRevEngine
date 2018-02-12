@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Report Perbandingan</a>
</div>
@endsection
@section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box" style="display:block;" id="perbandingan">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Nilai Perbandingan Preferensi WP dan SAW</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="row-fluid">
					<div class="span8">
						<table class="table table-bordered" border='1'>
							<thead>
								<tr>
									<th>Nama Kriteria</th>
									<th>Nilai Vektor S WP</th>
									<th>Nilai Vektor V WP</th>
									<th>Rank WP</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data)>0)
									@foreach($data as $item)
										<tr>
											<td>{{ $item['nama_alternatif'] }}</td>
											<td><center>{{ $item['nilai_vektor_s'] }}<center></td>
											<td><center>{{ $item['nilai_vektor_v_wp'] }}<center></td>
											<td><center>{{ $item['ranking_wp'] }}<center></td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="4"><center>No Data WP</center></td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
					<div class="span4">
						<table class="table table-bordered" border='1'>
							<thead>
								<tr>
									<th>Nilai Vektor V SAW</th>
									<th>Rank SAW</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data)>0)
									@foreach($data as $item)
										<tr>
											<td><center>{{ $item['nilai_vektor_v_saw'] }}</center></td>
											<td><center>{{ $item['ranking_saw'] }}</center></td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="2"><center>No Data SAW</center></td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span8">
		<div class="widget-box" style="display:block;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Perbandingan Waktu Perhitungan Metode</h5>
			</div>
			<div class="widget-content nopadding">
				<div class="row-fluid">
					<div class="span12">
						<table class="table table-bordered" border='1'>
							<thead>
								<tr>
									<th>Metode</th>
									<th>Waktu Eksekusi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Weighted Product</td>
									<td>
										@if(count($data)>0)
											<center>{{ $time['time_wp'] }}</center>
										@else
											Tidak Ada Waktu Kalkulasi
										@endif
									</td>
								</tr>
								<tr>
									<td>Simple Additive Weighting</td>
									<td>
										@if(count($data)>0)
											<center>{{ $time['time_saw'] }}</center>
										@else
											Tidak Ada Waktu Kalkulasi
										@endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>
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
	});
</script>
@endsection