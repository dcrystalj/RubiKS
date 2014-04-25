<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="RubiKS Rubik klub Slovenija">
		<title>Rubik klub Slovenija</title>

		{{-- Bootstrap --}}
		<link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/bootstrap.min.css') }}">

		{{-- Main CSS --}}
		<link rel="stylesheet" href="{{ URL::asset('assets/style.css') }}">
		<link rel="stylesheet" href="{{ URL::asset('assets/printable.css') }}">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		{{-- jQuery --}}
		<script src="{{ URL::asset('assets/jquery/jquery-1.11.0.min.js') }}"></script>

		{{-- Favicon --}}
		<link rel="shortcut icon" type="image/ico" href="{{ URL::asset('assets/favicon.ico') }}">
	</head>
	<body>
		@include('header')
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-md-3 menu">
					@include('menu')
				</div>

				<div class="col-sm-8 col-md-9 content">
					@yield('content')
				</div>
			</div>
		</div>
		@include('footer')

		{{-- Bootstrap core JavaScript --}}
		<script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
	</body>
</html>