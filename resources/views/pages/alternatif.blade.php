@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Alternatif</a>
</div>@endsection @section('content')
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Kandidat Media Cetak</h5>
			</div>
			<div class="widget-content nopadding">
				<p style="margin:10px">
					<buton class="btn btn-mini btn-primary" href="#modalTambah" data-toggle="modal" id="tambahAlternatif">Tambah Alternatif</buton>
				</p>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width:10%">ID Alternatif</th>
							<th>Nama Alternatif</th>
							<th style="width:10%">Action</th>
						</tr>
					</thead>
					<tbody id="tableBodyAlternatif"></tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="modalTambah" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3>Tambah Alternatif</h3>
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
					<form class="form-horizontal" id="formTambahAlternatif">
						<div class="control-group">
							<label class="control-label">Nama Alternatif :</label>
							<div class="controls">
								<input type="text" class="span11" placeholder="Nama Alternatif" name="nama_alternatif">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn btn-primary" href="#" id="saveAlternatif">Save</a>
			<a data-dismiss="modal" class="btn" href="#">Cancel</a>
		</div>
	</div>
	<div id="modalEdit" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3>Tambah Alternatif</h3>
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
					<form class="form-horizontal" id="formEditAlternatif">
						<div class="control-group">
							<label class="control-label">ID Alternatif :</label>
							<div class="controls">
								<input type="text" class="span11" name="id_alternatif" id="id_alternatif" readonly>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Nama Alternatif :</label>
							<div class="controls">
								<input type="text" class="span11" name="nama_alternatif" id="nama_alternatif">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn btn-primary" href="#" id="updateAlternatif">Save</a>
			<a data-dismiss="modal" class="btn" href="#">Cancel</a>
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
	$(document).on('click','#saveAlternatif',function(){
		var data = $('#formTambahAlternatif').serializeArray();
		$.post("{{ url('/alternatif/save') }}",data,function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
			$('#labelAlternatif').text(data.alternatif);
		},"json").done(function(){
        	$('#modalTambah').modal('hide');
			loadTable()
		})
		.fail(function(xhr, ajaxOptions, thrownError){
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
	$(document).on('click','#updateAlternatif',function(){
        var data = $('#formEditAlternatif').serializeArray();
		$.post("{{ url('/alternatif/update') }}",data,function(data){
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
		})
		.fail(function(xhr, ajaxOptions, thrownError){
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
	$(document).on('click','.editAlternatif',function(){
		var id = $(this).attr('data-id');
		$.post("{{ url('/alternatif/get') }}",{id_alternatif:id},function(data){
			$('#id_alternatif').val(data.id_alternatif);
			$('#nama_alternatif').val(data.nama_alternatif);
		},"json").done(function(){
			$('#modalEdit').modal('show');
		});
	});
	$(document).on('click','.deleteAlternatif',function(){
		var id = $(this).attr('data-id');
		$.post("{{ url('/alternatif/delete') }}",{id_alternatif:id},function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
			$('#labelAlternatif').text(data.alternatif);
		},"json").done(function(){
			loadTable()
		});
	});
	function loadTable(){
		$.post("{{ url('/alternatif/all') }}",function(data){
			$('#tableBodyAlternatif').empty();
			if(data.length>0){
				$.each(data,function(key,value){
					$('#tableBodyAlternatif').append('<tr class="odd gradeX">'+
														'<td>'+value.id_alternatif+'</td>'+
														'<td>'+value.nama_alternatif+'</td>'+
														'<td class="center">'+
															'<button class="editAlternatif btn btn-mini btn-primary" data-id="'+value.id_alternatif+'">Edit</button> '+
															'<button class="deleteAlternatif btn btn-mini btn-danger" data-id="'+value.id_alternatif+'">Delete</button>'+
														'</td>'+
													'</tr>');
				});
			}else{
				$('#tableBodyAlternatif').append('<tr><td colspan="3"><center>Tidak ada data alternatif tersimpan</center></td></tr>');
			}
		});
	}
</script>
@endsection