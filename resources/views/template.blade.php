<!DOCTYPE html>
<html lang="en">
<head>
	<title>Decision Support System</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/bootstrap-responsive.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/uniform.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/fullcalendar.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/matrix-style.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/matrix-media.css') }}" />
	<link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
	<link rel="stylesheet" href="{{ asset('css/jquery.gritter.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/jquery.toast.css') }}" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
	<style>
	.no-js #loader { display: none;  }
	.js #loader { display: block; position: absolute; left: 100px; top: 0; }
	.se-pre-con {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 999999999999999;
        background: url("{{ url('loader/Preloader_2.gif') }}") center no-repeat #fff;
	}
	</style>
</head>
<body>
	<div class="se-pre-con"></div>
	<!--Header-part-->
	<div id="header">
		<h1>
			<a href="{{ url('/') }}">DSS Admin</a>
		</h1>
	</div>
	<!--close-Header-part-->
	<!--top-Header-menu-->
	<div id="user-nav" class="navbar navbar-inverse">
		<ul class="nav">
			<li class="">
				<a title="" href="#">
					<i class="icon icon-user"></i>
					<span class="text">Welcome {{ session('username') }}</span>
				</a>
			</li>
			<li class="">
				<a title="" href="{{ url('/admin') }}">
					<i class="icon icon-user"></i>
					<span class="text">Admin Panel</span>
				</a>
			</li>
			<li class="">
				<a title="" href="{{ url('/admin/logout') }}">
					<i class="icon icon-share-alt"></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</div>
	<div id="sidebar">
		<a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
		<ul>
			<li @if($class == 'dashboard') class="active" @endif>
				<a href="{{ url('/') }}">
					<i class="icon icon-home"></i>
					<span>Dashboard</span>
				</a>
			</li>
			<li @if($class == 'kriteria') class="active" @endif>
				<a href="{{ url('/kriteria') }}">
					<i class="icon icon-bookmark"></i>
					<span>Kriteria</span>
					<span class="label label-important" id="labelKriteria">{{ $kriteria }}</span>
				</a>
			</li>
			<li @if($class == 'alternatif') class="active" @endif>
				<a href="{{ url('/alternatif') }}">
					<i class="icon icon-user-md"></i>
					<span>Alternatif</span>
					<span class="label label-important" id="labelAlternatif">{{ $alternatif }}</span>
				</a>
			</li>
			<li @if($class == 'evaluasi') class="active" @endif>
				<a href="{{ url('/evaluasi') }}">
					<i class="icon icon-edit"></i>
					<span>Penilaian Alternatif</span>
				</a>
			</li>
			<li @if($class== 'reportwp' || $class == 'reportsaw' || $class == 'reportcompare') class="submenu open" @else class="submenu" @endif> 
				<a href="#">
					<i class="icon icon-bar-chart"></i> Hasil
				</a>
				<ul>
					<li @if($class == 'reportwp') class="active" @endif><a href="{{ url('/report/wp') }}">Perhitungan WP</a></li>
					<li @if($class == 'reportsaw') class="active" @endif><a href="{{ url('/report/saw') }}">Perhitungan SAW</a></li>
					<li @if($class == 'reportcompare') class="active" @endif><a href="{{ url('/report/compare') }}">Hasil Perbandingan</a></li>
				</ul>
			</li>

			{{--  <li @if($class == 'report') class="active" @endif>
				<a href="{{ url('/report') }}">
					<i class="icon icon-bar-chart"></i>
					<span>Report</span>
				</a>
			</li>  --}}
		</ul>
	</div>
	<div id="content">
		<div id="content-header">
			@yield('content-header')
		</div>
		<div class="container-fluid">
			@yield('content')
		</div>
	</div>
	<div class="row-fluid">
		<div id="footer" class="span12"> 2017 &copy; Adi Prasetyo Nugroho - 14.01.074 - <a href="http://stmikbpn.ac.id">STMIK Balikpapan</a>
		</div>
	</div>
	<script src="{{ asset('js/excanvas.min.js') }}"></script>
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/jquery.ui.custom.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/jquery.peity.min.js') }}"></script>
	<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
	<script src="{{ asset('js/matrix.js') }}"></script>
	<script src="{{ asset('js/jquery.gritter.min.js') }}"></script>
	<script src="{{ asset('js/matrix.interface.js') }}"></script>
	<script src="{{ asset('js/matrix.chat.js') }}"></script>
	<script src="{{ asset('js/jquery.validate.js') }}"></script>
	<script src="{{ asset('js/jquery.wizard.js') }}"></script>
	<script src="{{ asset('js/jquery.uniform.js') }}"></script>
	<script src="{{ asset('js/matrix.popover.js') }}"></script>
	<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/jquery.toast.js') }}"></script>
	@yield('js')
</body>
</html>