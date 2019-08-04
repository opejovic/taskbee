<div class="h-full flex flex-col">
	{{-- header --}}
	<header class="px-5 w-full">
		<img class="w-32 mx-auto" src="/img/tasks.svg" alt="">
		<div class="text-2xl text-indigo-700 text-light pb-5">
			<a href="{{ route('workspaces.show', $workspace) }}">{{ __('Tasks Overview') }}</a>
		</div>
	</header>

	{{-- body --}}
	<div class="px-5 py-5 w-full flex-1 items-center overflow-y-auto">
		{{-- items-center justify-between --}}
		<div class="text-left text-gray-800 h-full text-sm xl:flex">

			{{-- left section of the tasks overview --}}
			<div class="flex xl:w-1/3 lg:w-full mx-1 h-full flex flex-wrap">
				{{-- top --}}
				<div class="flex w-full">
					<div class="w-full px-1 py-1 text-indigo-800">
						<div class="w-full h-full border shadow flex flex-col items-center justify-center py-8">
							<p class="">
								Urgent
							</p>
							<p class="text-xl text-center">
								{{ $tasks->where('status', 'Urgent')->count() }}
							</p>
						</div>
					</div>
					<div class="w-full px-1 py-1 text-indigo-800">
						<div class="w-full h-full border shadow flex flex-col items-center justify-center py-8">
							<p class="">
								Pending
							</p>
							<p class="text-xl text-center">
								{{ $tasks->where('status', 'Pending')->count() }}
							</p>
						</div>
					</div>
				</div>
				{{-- bottom --}}
				<div class="flex w-full">
					<div class="w-full px-1 py-1 text-indigo-800">
						<div class="w-full h-full border shadow flex flex-col items-center justify-center">
							<p class="">
								Completed
							</p>
							<p class="text-xl text-center">
								{{ $tasks->where('status', 'Completed')->count() }}
							</p>
						</div>
					</div>
					<div class="w-full px-1 py-1 text-indigo-800">
						<div class="w-full h-full border shadow flex flex-col items-center justify-center py-8">
							<p class="">
								All
							</p>
							<p class="text-xl text-center">
								{{ $tasks->count() }}
							</p>
						</div>
					</div>
				</div>
			</div>

			{{-- middle section of the tasks overview --}}
			<div class="flex xl:w-1/3 w-full mx-1 h-full flex flex-wrap bg-green-400">
				b
			</div>

			{{-- right section --}}
			<div class="flex xl:w-1/3 w-full mx-1 h-full flex flex-wrap bg-red-400">
				c
			</div>
		</div>
	</div>

	{{-- footer --}}
	<div class="w-full bottom-0 pb-5">
		footer
	</div>
</div>