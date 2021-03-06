<div class="h-full flex flex-col">
	{{-- header --}}
	<header class="px-5 w-full">
		<img class="w-32 mx-auto" src="/img/dashboard.svg" alt="">
		<div class="text-2xl text-indigo-700 text-light pb-5">
			<a href="{{ route('workspaces.show', $workspace) }}">{{ $workspace->name }}</a>
		</div>
	</header>

	{{-- body --}}
	<div class="px-5 py-5 w-full flex-1 h-full overflow-y-hidden">
		<div class="text-left px-10 text-gray-800 text-sm">
			<div class="flex items-center justify-between pb-2">
				<div class="w-full">
					{{ $workspace->creator->full_name }}
				</div>
				<span class="bg-indigo-500 text-white text-xs font-semibold px-2 rounded-full">
					Admin
				</span>
			</div>

			@forelse($invitations as $invitation)
			<li style="list-style: none;">
				<div class="flex items-center justify-between pb-2">
					{{ $invitation->full_name }}
					<span
						class="text-gray-700 text-xs font-semibold px-2 rounded-full {{ $invitation->hasBeenUsed() ? 'bg-green-300' : 'bg-orange-300'}}">
						{{ $invitation->hasBeenUsed() ? 'Active' : 'Invited' }}
					</span>
				</div>
			</li>
			@empty
			<p class="text-gray-600">Still no invited members</p>
			@endforelse
		</div>
		
	</div>

	{{-- footer --}}
	<div class="w-full bottom-0 pb-5">
		@if (auth()->user()->owns($workspace))
			@if ($workspace->authorization->invites_remaining > 0)

			<a href="{{ route('workspace-setup.show', $workspace->authorization->code) }}"
				class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
				<svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
					<path
						d="M2 6H0v2h2v2h2V8h2V6H4V4H2v2zm7 0a3 3 0 0 1 6 0v2a3 3 0 0 1-6 0V6zm11 9.14A15.93 15.93 0 0 0 12 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z" />
					<span>Invite Members</span>
			</a>

			<p class="pt-2 text-xs text-gray-600">You can invite {{ $workspace->authorization->invites_remaining }}
				more {{ str_plural('member', $workspace->authorization->invites_remaining) }}</p>
			@else
			<member-slot-checkout :workspace="{{ $workspace }}" style="vertical-align: bottom;"></member-slot-checkout>
			<p class="pt-2 text-xs text-gray-600">You have used all your invites. Purchase more slots <a
					class="border-b-2 border-indigo-300" href="{{ route('workspace-members.index', $workspace) }}">here</a>.
			</p>
			@endif
		@endif
	</div>
</div>