@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Report</a>
</div>
@endsection
@section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Kalkulasi Output Perhitungan Metode</h5>
			</div>
			<div class="widget-content">
				@if($status)
					<p>
						<buton class="btn btn-mini btn-primary" id="generateWP">Nilai Preferensi WP</buton>
						<buton class="btn btn-mini btn-success" id="generateSAW">Nilai Preferensi SAW</buton>
						<buton class="btn btn-mini btn-danger" id="generateAll">Nilai Perbandingan WP & SAW</buton>
					</p>
				@else
					<p><center>Data Alternatif dan Data Kriteria belum lengkap</center></p>
				@endif
			</div>
		</div>
		<div class="widget-box" style="display:none;" id="stepSatuWPSingle">
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
					<tbody id="bodyKriteriaWPSingle"></tbody>
				</table>
			</div>
		</div>
		<div class="widget-box" style="display:none;" id="stepDuaWPSingle">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>2. Perhitungan Preferensi Metode Weighted Product</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped" id="preferensiWPSingle">
					<thead>
						<tr>
							<th>Nama Alternatif</th>
							<th>Nilai Vektor S</th>
							<th>Nilai Vektor V</th>
							<th>Ranking</th>
						</tr>
					</thead>
					<tbody id="bodyPreferensiWPSingle"></tbody>				
				</table>
			</div>
		</div>
		<div class="widget-box" style="display:none;" id="stepSatuSAWSingle">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>1. Normalisaasi Bobot</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped" id="kriteriaSAWSingle">
					<thead>
						<tr>
							<th style="width:10%">ID Kriteria</th>
							<th>Nama Kriteria</th>
							<th style="width:10%">Nilai Bobot</th>
							<th style="width:30%">Normalisasi Bobot</th>
						</tr>
					</thead>
					<tbody id="bodyKriteriaSAWSingle"></tbody>
				</table>
			</div>
		</div>
		<div class="widget-box" style="display:none;" id="stepDuaSAWSingle">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>2. Normalisasi Matriks SAW</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped" id="matriksSAWSingle">
					<thead id="tableHeadingMatriks"></thead>
					<tbody id="tableBodyMatriks"></tbody>
				</table>
			</div>
		</div>
		<div class="widget-box" style="display:none;" id="stepTigaSAWSingle">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>3. Perhitungan Nilai Preferensi Metode SimpleAdditiveWeighting</h5>
			</div>
			<div class="widget-content">
				<table class="table table-bordered table-striped" id="preferensiSAWSingle">
					<thead>
						<tr>
							<th>Nama Alternatif</th>
							<th>Nilai Vektor V</th>
							<th>Ranking</th>
						</tr>
					</thead>
					<tbody id="bodyPreferensiSAWSingle"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box" style="display:none;" id="perbandingan">
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
							<tbody id="perbandinganWP"></tbody>
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
							<tbody id="perbandinganSAW"></tbody>
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
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	});
	$(document).on('click','#generateWP',function(){
		hideAllTable()
		$.post("{{ url('/report/kriteria') }}",function(data){
			$('#bodyKriteriaWPSingle').empty();
			var total = 0;
			$.each(data,function(key,item){
				$('#bodyKriteriaWPSingle').append('<tr><td><center>'+item.id_kriteria+'</center></td><td>'+item.nama_kriteria+'</td><td><center>'+item.nilai_kriteria+'</center></td><td><center>'+item.nilai_bobot+'</center></td></tr>');				
				total = total + item.nilai_bobot;
			});
			$('#bodyKriteriaWPSingle').append('<tr><td colspan="2"></td><td><center>Jumlah</center></td><td><center>'+total+'</center></td></tr>');				
			$('#stepSatuWPSingle').show();
		},"json").done(function(){
			$.post("{{ url('/report/wp') }}",function(data){
				$('#bodyPreferensiWPSingle').empty();
				$.each(data,function(key,item){
					$('#bodyPreferensiWPSingle').append('<tr><td>'+item.nama_alternatif+'</td><td>'+item.nilai_vektor_s+'</td><td>'+item.nilai_vektor_v+'</td><td>'+item.ranking_wp+'</td></tr>');
				});
			},"json").done(function(){
				$('#stepDuaWPSingle').show();
			});
		});
	});
	$(document).on('click','#generateSAW',function(){
		hideAllTable()
		$.post('{{ url('/report/kriteria') }}',function(data){
			$('#bodyKriteriaSAWSingle').empty();
			var total = 0;
			$.each(data,function(key,item){
				$('#bodyKriteriaSAWSingle').append('<tr><td><center>'+item.id_kriteria+'</center></td><td>'+item.nama_kriteria+'</td><td><center>'+item.nilai_kriteria+'</center></td><td><center>'+item.nilai_bobot+'</center></td></tr>');				
				total = total + item.nilai_bobot;
			});
			$('#bodyKriteriaSAWSingle').append('<tr><td colspan="2"></td><td><center>Jumlah</center></td><td><center>'+total+'</center></td></tr>');				
			$('#stepSatuSAWSingle').show();
		},"json").done(function(){
			$.post('{{ url('/report/matriks') }}',function(data){
				var heading = "";
				var rows = "";
				$('#tableHeadingMatriks').empty();
				$('#tableBodyMatriks').empty();
				heading += '<tr><th><center>Nama Alternatif</center></th>';
				var alternatif = data.alternatif;
				var kriteria = data.kriteria;
				var evaluasi = data.evaluasi;
				$.each(kriteria,function(i,item){
					heading += '<th><center>'+ item.nama_kriteria +'</center></th>';
				});
				$.each(alternatif,function(i,item){ 
					rows = "";
					rows += '<tr><td>'+item.nama_alternatif+'</td>';
					for(e=0;e<evaluasi[i].length;e++){
						rows += '<td><center>'+evaluasi[i][e]+'</center></td>';
					}
					rows += '</tr>';
					$('#tableBodyMatriks').append(rows);
				});
				heading += '</tr>';
				$('#tableHeadingMatriks').append(heading);
				$('#stepDuaSAWSingle').show();
			},"json").done(function(){
				$.post('{{ url('/report/saw') }}',function(data){
					$('#bodyPreferensiSAWSingle').empty();
					$.each(data,function(key,item){
						$('#bodyPreferensiSAWSingle').append('<tr><td>'+item.nama_alternatif+'</td><td>'+item.nilai_vektor_v+'</td><td>'+item.ranking+'</td></tr>');
					});
				},"json").done(function(){
					$('#stepTigaSAWSingle').show();
				});
			});
		});
	});
	$(document).on('click','#generateAll',function(){
		hideAllTable()
		$.post("{{ url('/report/all') }}",function(data){
			$('#perbandinganWP').empty();
			$('#perbandinganSAW').empty();
			$.each(data,function(key,item){
				$('#perbandinganWP').append('<tr><td>'+item.nama_alternatif+'</td><td>'+item.nilai_vektor_s+'</td><td>'+item.nilai_vektor_v_wp+'</td><td>'+item.ranking_wp+'</td></tr>');
				$('#perbandinganSAW').append('<tr><td>'+item.nilai_vektor_v_saw+'</td><td>'+item.ranking_saw+'</td></tr>');
			});
		},"json").done(function(){
			$('#perbandingan').show();
		});
	});
	function hideAllTable(){
		$('#stepSatuWPSingle').hide();
		$('#stepDuaWPSingle').hide();
		$('#stepSatuSAWSingle').hide();
		$('#stepDuaSAWSingle').hide();
		$('#stepTigaSAWSingle').hide();
		$('#perbandingan').hide();
	}
</script>
@endsection