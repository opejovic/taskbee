<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>Document</title>
		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	</head>

	<body class="bg-gray-100">
		<nav class="flex items-center justify-between shadow flex-wrap bg-gray-100 p-5">
			<div class="flex items-center flex-shrink-0 text-indigo-900 mr-6">
				<span class="font-semibold text-xl tracking-tight">taskmonkey.</span>
			</div>
			<div class="block lg:hidden">
				<button
					class="flex items-center px-3 py-2 border rounded text-indigo-800 border-indigo-700 hover:text-indigo-400 hover:border-indigo-400">
					<svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<title>Menu</title>
						<path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
					</svg>
				</button>
			</div>
			<div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
				<div class="text-sm lg:flex-grow">
					<a href="#responsive-header"
						class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
						Features
					</a>
					<a href="#responsive-header"
						class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
						Pricing
					</a>
				</div>
				<div>
					<a href="#"
						class="block mt-4 text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
						Sign In
					</a>
					<a href="#"
						class="inline-block text-sm px-4 py-2 leading-none border rounded text-indigo-800 border-indigo-700 hover:border-transparent hover:text-white hover:bg-indigo-900 mt-4 lg:mt-0">
						Subscribe
					</a>
				</div>
			</div>
		</nav>
	</body>

</html>