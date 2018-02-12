<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Login form using HTML5 and CSS3</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/jquery.toast.css">
    <style>
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('loader/Preloader_2.gif') center no-repeat #fff;
        }
    </style>
</head>
<body>
<div class="se-pre-con"></div>
	<div class="container">
		<section id="content">
			<form id="formLogin">
				<h2>Perbandingan SPK Metode WP dan SAW</h2>
				<div>
					<input type="text" name="username" placeholder="Username" required="" id="username" />
				</div>
				<div>
					<input type="password" name="password" placeholder="Password" required="" id="password" />
				</div>
				<div>
					<button type="button" id="login">Log In</button>
				</div>
			</form>
		</section>
	</div>
</body>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.toast.js"></script>
    <script>   
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");
        });
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
            $('#login').click(function(){
                var data = $('#formLogin').serializeArray();
                $.post("{{ url('/login') }}",data,function(data){
                    if(data.status=="success"){
                        $.toast({
                            heading: 'Information',
                            text: data.message,
                            position: 'bottom-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: data.status,
                            afterHidden:function(){
                                window.location.href = "{{ url('/') }}";
                            }
                        });
                    }else{
                        $.toast({
                            heading: 'Information',
                            text: data.message,
                            position: 'bottom-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: data.status
                        });
                    }
                },"json");
            });
        });
    </script>
</html>