<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Scripts -->
		@stack('beforeScripts')
		<script src="{{ asset('js/app.js') }}" defer></script>
		<script>
			window.auth = {!! json_encode(Auth::user(), JSON_HEX_TAG) !!};
		</script>

		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			.bg-curve {
				background-image: url("img/curve-yellow.svg");
				background-position-x: center;
				background-position-y: bottom;
				background-size: 5153px 200px;
				background-repeat-x: no-repeat;
				background-repeat-y: no-repeat;
				background-attachment: fixed;
				background-origin: initial;
				background-clip: initial;
			}
		</style>
	</head>

	<body>
		<div id="app" class="min-h-screen bg-curve">
			@if (! request()->routeIs(['login', 'signup', 'welcome']))
				@include('layouts.nav')
			@endif

			<main class="container mx-auto px-6">
				@yield('content')
			</main>
		</div>
	</body>

</html>