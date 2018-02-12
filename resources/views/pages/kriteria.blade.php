@extends('template') @section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard">
		<i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Kriteria</a>
</div>
@endsection @section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Kriteria Pemilihan Media Cetak</h5>
			</div>
			<div class="widget-content nopadding">
				<p style="margin:10px">
					<buton class="btn btn-mini btn-primary" href="#modalTambah" data-toggle="modal" id="tambahKriteria">Tambah Kriteria</buton>
				</p>
				<table class="table table-bordered data-table" id="dataKriteria">
					<thead>
						<tr>
							<th style="width:7%">ID Kriteria</th>
							<th>Nama Kriteria</th>
							<th style="width:10%">Type Kriteria</th>
							<th style="width:10%">Bobot Kriteria</th>
							<th style="width:10%">Action</th>
						</tr>
					</thead>
					<tbody id="tableBodyKriteria"></tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="modalTambah" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3>Tambah Kriteria</h3>
		</div>
		<div class="modal-body">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="icon-align-justify"></i>
					</span>
					<h5>Data Info</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal" id="formAddKriteria">
						<div class="control-group">
							<label class="control-label">Nama Kriteria :</label>
							<div class="controls">
								<input type="text" class="span11" name="nama_kriteria" placeholder="Nama Kriteria">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Tipe Kriteria</label>
							<div class="controls">
								<select name="type_kriteria" class="span11">
									<option value="Benefit">Benefit</option>
									<option value="Cost">Cost</option>
								</select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Nilai Kriteria</label>
							<div class="controls">
								<select name="nilai_kriteria" class="span11">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn btn-primary" href="#" id="saveKriteria">Save</a>
			<a data-dismiss="modal" class="btn" href="#">Cancel</a>
		</div>
	</div>
	<div id="modalEdit" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3>Edit Kriteria</h3>
		</div>
		<div class="modal-body">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="icon-align-justify"></i>
					</span>
					<h5>Data Info</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal" id="formEditKriteria">
						<div class="control-group">
							<label class="control-label">ID Kriteria :</label>
							<div class="controls">
								<input type="text" class="span11" name="id_kriteria" placeholder="ID Kriteria" id="id_kriteria" readonly>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Nama Kriteria :</label>
							<div class="controls">
								<input type="text" class="span11" name="nama_kriteria" placeholder="Nama Kriteria" id="nama_kriteria">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Tipe Kriteria</label>
							<div class="controls">
								<select name="type_kriteria" class="span11" id="type_kriteria">
									<option value="benefit">Benefit</option>
									<option value="cost">Cost</option>
								</select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Nilai Kriteria</label>
							<div class="controls">
								<select name="nilai_kriteria" class="span11" id="nilai_kriteria">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn btn-primary" href="#" id="updateKriteria">Save</a>
			<a data-dismiss="modal" class="btn" href="#">Cancel</a>
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
	$(document).on('click','#saveKriteria',function(){
		var data = $('#formAddKriteria').serializeArray();
		console.log(data);
		$.post("{{url('/kriteria/save')}}",data,function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
			$('#labelKriteria').text(data.kriteria);
		},"json").done(function(){
			$('#modalTambah').modal('hide');
			loadTable()
		}).fail(function(xhr, ajaxOptions, thrownError){
			var responseText = JSON.parse(xhr.responseText);
			var error = responseText.errors;
			var errorArray = [];
			var no = 0;
			$.each(error, function (key, value) {
            	errorArray[no] = value[0];
                no++;
            });
            $.toast({
            	heading: 'Kesalahan!',
                text: errorArray,
                icon: 'error',
                position: 'bottom-right'
            });
		},"json");
    });
	$(document).on('click','.deleteKriteria',function(){
		var id = $(this).attr('data-id');
		$.post("{{ url('/kriteria/delete') }}",{id_kriteria:id},function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
			$('#labelKriteria').text(data.kriteria);
		},"json").done(function(){
			loadTable()
		});
	});
	$(document).on('click','.editKriteria',function(){
		var id = $(this).attr('data-id');
		$.post("{{ url('/kriteria/get') }}",{id_kriteria:id},function(data){
			$('#id_kriteria').val(data.id_kriteria);
			$('#nama_kriteria').val(data.nama_kriteria);
			$('#type_kriteria').val(data.type_kriteria);
			$('#nilai_kriteria').val(data.nilai_kriteria);
		},"json").done(function(){
			$('#modalEdit').modal('show');
		});
	});
	$(document).on('click','#updateKriteria',function(){
		var data = $('#formEditKriteria').serializeArray();
		$.post("{{ url('/kriteria/update') }}",data,function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
		},"json").done(function(){
			$('#modalEdit').modal('hide');
			loadTable()
		}).fail(function(xhr, ajaxOptions, thrownError){
			var responseText = JSON.parse(xhr.responseText);
			var error = responseText.errors;
			var errorArray = [];
			var no = 0;
			$.each(error, function (key, value) {
            	errorArray[no] = value[0];
                no++;
            });
            $.toast({
            	heading: 'Kesalahan!',
                text: errorArray,
                icon: 'error',
                position: 'bottom-right'
            });
		},"json");
	});
	function loadTable(){
		$.post("{{ url('/kriteria/all') }}",function(data){
			$('#tableBodyKriteria').empty();
			if(data.length>0){
				$.each(data,function(key,value){
					$('#tableBodyKriteria').append('<tr class="odd gradeX">'+
														'<td>'+value.id_kriteria+'</td>'+
														'<td>'+value.nama_kriteria+'</td>'+
														'<td>'+value.type_kriteria+'</td>'+
														'<td>'+value.nilai_kriteria+'</td>'+
														'<td class="center">'+
															'<button class="editKriteria btn btn-mini btn-primary" data-id="'+value.id_kriteria+'">Edit</button> '+
															'<button class="deleteKriteria btn btn-mini btn-danger" data-id="'+value.id_kriteria+'">Delete</button>'+
														'</td>'+
													'</tr>');
				});
			}else{
				$('#tableBodyKriteria').append('<tr><td colspan="5"><center>Tidak ada data kriteria tersimpan</center></td></tr>');
			}
		});
	};
</script>
@endsection