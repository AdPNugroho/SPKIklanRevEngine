@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Evaluasi</a>
</div>
@endsection 
@section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box span6" id="divFormEvaluasi" style="display:none;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Edit Nilai Evaluasi</h5>
			</div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" id="formEvaluasi">
					<div class="control-group">
						<label class="control-label">ID Alternatif :</label>
						<div class="controls">
							<input type="text" class="span11" placeholder="ID Alternatif" id="id_alternatif" name="id_alternatif" readonly>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Nama Alternatif :</label>
						<div class="controls">
							<input type="text" class="span11" placeholder="Nama Alternatif" id="nama_alternatif" readonly>
						</div>
					</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Nama Kriteria</th>
								<th style="width:30px;">Nilai</th>
							</tr>
						</thead>
						<tbody id="nilaiEvaluasi"></tbody>
					</table>
				</form>
			</div>
		</div>
		<div class="widget-box" id="tableEvaluasi">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Data Evaluasi Media Cetak</h5>
			</div>
			<div class="widget-content nopadding">
				<div style="display:none;" id="errorEvaluasi">
					<p>
						<center><h3>Data Tidak Lengkap</h3></center>
						<center><h4>Harap melakukan input data kriteria dan alternatif.</h4></center>
					</p>
				</div>
				<table class="table table-bordered table-striped">
					<thead id="tableHeading"></thead>
					<tbody id="tableBody"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection @section('js')
<script>
	$(document).ready(function(){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function(){
            	$(".se-pre-con").fadeIn();
            },
            complete: function(){
            	$(".se-pre-con").fadeOut("slow");
            }
		});
		loadTable()
		$(".se-pre-con").fadeOut("slow");
	});
	$(document).on('click','.editEvaluasi',function(){
		var id = $(this).attr('data-id');
		$.post("{{ url('/evaluasi/get') }}",{id_alternatif:id},function(data){
			var alternatif = data.alternatif;
			var evaluasi = data.evaluasi;
			$('#id_alternatif').val(alternatif.id_alternatif);
			$('#nama_alternatif').val(alternatif.nama_alternatif);
			$('#nilaiEvaluasi').empty();
			$.each(evaluasi,function(key,value){
				$('#nilaiEvaluasi').append('<tr><td>'+value.nama_kriteria+'</td><td><input type="hidden" name="kode_evaluasi[]" value="'+value.id_evaluasi+'"><input type="number" name="nilai_evaluasi[]" value="'+value.nilai_evaluasi+'"></td></tr>');
			});
			$('#nilaiEvaluasi').append('<tr><td colspan="2"><button class="btn btn-mini btn-primary" id="updateEvaluasi" type="button">Save</button> <button class="btn btn-mini btn-danger" id="cancelEvaluasi" type="button">Cancel</button></td></tr>');
		},"json").done(function(){
			$('#divFormEvaluasi').show();
			$('#tableEvaluasi').hide();
		});
	});
	$(document).on('click','#updateEvaluasi',function(){
		var data = $('#formEvaluasi').serializeArray();
		$.post("{{ url('/evaluasi/update') }}",data,function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
		},"json").done(function(){
			loadTable()
			$('#divFormEvaluasi').hide();
			$('#tableEvaluasi').show();
		});
	});
	$(document).on('click','#cancelEvaluasi',function(){
		$('#divFormEvaluasi').hide();
		$('#tableEvaluasi').show();
	});
	function loadTable(){
		$.ajax({
        	url:"evaluasi/all",
			cache:false,
			dataType:'json',
			type:'post',
			success:function(response){
				var heading = "";
				var rows = "";
				$('#tableHeading').empty();
				$('#tableBody').empty();
				var alternatif = response.alternatif;
				var kriteria = response.kriteria;
				var evaluasi = response.evaluasi;
				heading += '<tr><th><center>Nama Alternatif</center></th>';
				if(alternatif.length>0 && kriteria.length>0){
					$.each(kriteria,function(i,item){
						heading += '<th><center>'+ item.nama_kriteria +'</center></th>';
					});
					$.each(alternatif,function(i,item){ 
						rows = "";
						rows += '<tr><td>'+item.nama_alternatif+'</td>';
						for(e=0;e<evaluasi[i].length;e++){
							rows += '<td><center>'+evaluasi[i][e]+'</center></td>';
						}
						rows += '<td><center><button class="editEvaluasi btn btn-mini btn-success" data-id='+item.id_alternatif+ '><i class="glyph-icon icon-pencil"></i>  Edit</button></center></td>';
						rows += '</tr>';
						$('#tableBody').append(rows);
					});
					heading += '<th width="7%"><center>Action</center></th></tr>';
					$('#tableHeading').append(heading);
				}else{
					$('#errorEvaluasi').show();
				}
				console.log(response);
			}
		});
	}
</script>
@endsection