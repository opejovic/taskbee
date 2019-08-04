<div class="h-full flex flex-col">
	{{-- header --}}
	<header class="px-5 w-full">
		<img class="w-32 mx-auto" src="/img/tasks.svg" alt="">
		<div class="text-2xl text-indigo-700 text-light pb-5">
			<a href="{{ route('workspaces.show', $workspace) }}">{{ __('Tasks Overview') }}</a>
		</div>
	</header>

	{{-- body --}}
	<div class="px-5 py-5 w-full flex-1">
		<div class="text-left px-10 text-gray-800 text-sm">
			body
		</div>
	</div>

	{{-- footer --}}
	<div class="w-full bottom-0 pb-5">
		footer
	</div>
</div>