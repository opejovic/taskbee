<nav class="flex items-center justify-between shadow flex-wrap bg-gray-100 p-5">
	<div class="flex items-center flex-shrink-0 text-indigo-900 mr-6">
		<span class="font-semibold text-xl tracking-tight"><a href="{{ url('/home') }}">taskmonkey.</a></span>
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
			@auth
			@if (auth()->user()->workspace_id !== null)
			<a href="/workspaces/{{ auth()->user()->workspace_id }}/tasks"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				{{ __('All tasks') }}
			</a>
			<a href="/workspaces/{{ auth()->user()->workspace_id }}/tasks?my"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				{{ __('My Tasks') }}
			</a>
			<a href="/workspaces/{{ auth()->user()->workspace_id }}/tasks?creator={{ auth()->user()->id }}"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				{{ __('Tasks I have created') }}
			</a>

			<new-task :workspace="{{ auth()->user()->workspace }}"></new-task>
			@endif
			@endauth

			<a href="#responsive-header"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				Features
			</a>
			<a href="#responsive-header"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				Pricing
			</a>
		</div>
		@guest
		<div>
			<a href="{{ route('login') }}" class="block mt-4 text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				{{ __('Sign in') }}
			</a>
			<a href="{{ route('signup') }}"
				class="inline-block text-sm px-4 py-2 leading-none border rounded text-indigo-800 border-indigo-700 hover:border-transparent hover:text-white hover:bg-indigo-900 mt-4 lg:mt-0">
				{{ __('Sign up') }}
			</a>
		</div>
	</div>

		@else
		<user-notifications :user="{{ Auth::user() }}"></user-notifications>

		<li class="nav-item dropdown">
			<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false" v-pre>
				<img class="mr-2" src="{{ auth()->user()->avatar_path }}" width="35px" height="35px" alt="avatar"
					style="border-radius: 50%;">
				{{ Auth::user()->first_name }} <span class="caret"></span>
			</a>

			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="{{ route('dashboard') }}">
					{{ __('Dashboard') }}
				</a>

				<a class="dropdown-item" href="{{ route('profile', auth()->user()) }}">
					{{ __('My Profile') }}
				</a>

				<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
			                                             document.getElementById('logout-form').submit();">
					{{ __('Logout') }}
				</a>

				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
				</form>
			</div>
		</li>
		@endguest
	</ul>
</nav>