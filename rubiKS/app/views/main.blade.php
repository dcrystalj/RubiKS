<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Rubik klub Slovenija</title>
		<style>
			html { overflow-y: scroll; }
			body { padding-top: 70px; }

			.container { min-width: 440px; }
			.content { margin-bottom: 20px; }

			.header { background-color: #eee; }
			.header_main { width: 100%; padding: 0px 40px; }

			.footer_block { display: inline-block; vertical-align: top; margin: 0px 20px; }
			.footer { padding: 20px; background-color: #ccc; border-top: 3px solid #999; }

			.menu { vertical-align: top; }

			.competitor_home {  }
			.competitor_guest { color: #999; }

			tr.results_odd { background-color: #F9F9F9; }
			tr.gray_header { background-color: #ddd; }

			.text-left { text-align: left; }
			.text-center { text-align: center; }
			.text-right { text-align: right; }

			.block { display: inline-block; }

			.competitors_block_left {
				display: inline-block;
				width: 50%;
			}
			.competitors_block_right {
				display: inline-block;
				width: 40%;
				vertical-align: top;
				text-align: center;
			}

			td.credit {
				padding: 15px;
			}

			form.inline { display: inline; }
		</style>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
		@include('header')
		<link rel='stylesheet' type='text/css' href='{{ URL::asset("menu/styles.css") }}' />
		<script type='text/javascript' src='{{ URL::asset("menu/menu_jquery.js") }}'></script>
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-md-3">
					@include('menu_temp')
				</div>

				<div class="col-sm-8 col-md-9 content">
					@yield('content')
				</div>
			</div>
		</div>
		@include('footer')

		{{--
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		--}}

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	</body>
</html>