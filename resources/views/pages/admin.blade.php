@extends('template') 
@section('content-header')
<div id="breadcrumb">
	<a href="{{ url('/') }}" class="tip-bottom" data-original-title="Go to Dashboard"><i class="icon-home"></i> Dashboard</a>
	<div class="tooltip fade bottom in" style="top: 36px; left: 0.5px; display: none;">
		<div class="tooltip-arrow"></div>
		<div class="tooltip-inner">Go to Dashboard</div>
	</div>
	<a href="#" class="current">Admin Page</a>
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
				<h5>Admin Control DSS</h5>
			</div>
			<div class="widget-content nopadding">
				<p style="margin:10px">
					<buton class="btn btn-mini btn-primary" id="tambahAdmin">Tambah Admin</buton>
				</p>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width:10%">ID Admin</th>
							<th>Username</th>
							<th style="width:10%">Action</th>
						</tr>
					</thead>
					<tbody id="tableBodyAdmin"></tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="modalTambah" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h3>Tambah Admin</h3>
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
					<form class="form-horizontal" id="formTambahAdmin">
						<div class="control-group">
							<label class="control-label">Username :</label>
							<div class="controls">
								<input type="text" class="span11" placeholder="Username" name="username">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Password :</label>
							<div class="controls">
								<input type="password" class="span11" placeholder="Password" name="password">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn btn-primary" href="#" id="saveAdmin">Save</a>
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
    $(document).on('click','#tambahAdmin',function(){
        $('#formTambahAdmin').trigger('reset');
        $('#modalTambah').modal('show');
    });
    $(document).on('click','#saveAdmin',function(){
        var data = $('#formTambahAdmin').serializeArray();
        $.post("{{ url('/admin/save') }}",data,function(data){
            $.toast({
                heading: 'Information',
                text: data.message,
                position: 'bottom-right',
                stack: false,
                showHideTransition: 'slide',
                icon: data.status
            });
            loadTable()
        },"json").done(function(){
            $('#modalTambah').modal('hide');
        });
    });
    $(document).on('click','.deleteAdmin',function(){
        var id = $(this).attr('data-id');
        $.post("{{ url('/admin/delete') }}",{id_admin:id},function(data){
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
        });
    });
    function loadTable(){
        $('#tableBodyAdmin').empty();
        $.post("{{ url('/admin/data') }}",function(data){
            if(data.length>0){
                $.each(data,function(key,item){
                    $('#tableBodyAdmin').append('<tr><td>'+item.id_admin+'</td><td>'+item.username+'</td><td><button class="deleteAdmin btn btn-mini btn-danger btn-block" data-id="'+item.id_admin+'">Delete</button></td></tr>');
                });
            }else{
                $('#tableBodyAdmin').append('<tr><td colspan="3"><center>Tidak Ada Admin Terdaftar</center></td></tr>');
            }
        },"json");
    }
</script>
@endsection