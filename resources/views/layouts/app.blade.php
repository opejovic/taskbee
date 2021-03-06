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
	</head>

	<body>
		<div id="app" class="min-h-screen">
			@auth
				<navbar :workspace="{{ auth()->user()->workspaceOwned() }}"></navbar>
			@endauth

			<main class="container mx-auto">
				@yield('content')
			</main>
		</div>
	</body>

</html>