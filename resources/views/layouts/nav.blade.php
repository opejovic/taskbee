<nav class="flex items-center justify-between shadow flex-wrap bg-gray-100 p-5">
	<div class="flex items-center flex-shrink-0 text-indigo-900 ml-4">
		<span class="font-semibold text-xl tracking-tight"><a
				href="{{ url('/home') }}">{{ config('app.name', 'Laravel') }}</a></span>
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
	<div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto items-center justify-center text-sm">
			@auth
				@if (auth()->user()->workspace_id !== null)
				<task-filter-dropdown class=""></task-filter-dropdown>

				<new-task-modal class="ml-auto" :workspace="{{ auth()->user()->workspace }}"></new-task-modal>
				@endif
			@endauth

			@guest
			<a href="#responsive-header"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				Features
			</a>
			<a href="#responsive-header"
				class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				Pricing
			</a>
			@endguest

		@guest
		<div>
			<a href="{{ route('login') }}"
				class="block mt-4 text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4">
				{{ __('Sign in') }}
			</a>
			<a href="{{ route('signup') }}"
				class="inline-block text-sm px-4 py-2 leading-none border rounded text-indigo-800 border-indigo-700 hover:border-transparent hover:text-white hover:bg-indigo-900 mt-4 lg:mt-0">
				{{ __('Sign up') }}
			</a>
		</div>
	</div>
	@else
	<user-notifications class="-mb-2 ml-auto" :user="{{ Auth::user() }}"></user-notifications>
		<div class="flex items-center">
			<img
			class="object-contain -mr-12 -mb-2 -mt-2"
			src="{{ Auth::user()->avatar_path }}"
			width="35px"
			height="35px"
			alt="avatar"
			style="border-radius: 50%;"
			/>
			<user-menu-dropdown class="text-right"></user-menu-dropdown>
		</div>
	@endguest
	</ul>
</nav>