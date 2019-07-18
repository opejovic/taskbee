<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav">
                @auth
                    @if (auth()->user()->workspace_id !== null)
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Browse <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/workspaces/{{ auth()->user()->workspace_id }}/tasks">
                                    {{ __('All tasks') }}
                                </a>
                                <a class="dropdown-item" href="/workspaces/{{ auth()->user()->workspace_id }}/tasks?my">
                                    {{ __('My Tasks') }}
                                </a>
                                <a class="dropdown-item"
                                   href="/workspaces/{{ auth()->user()->workspace_id }}/tasks?creator={{ auth()->user()->id }}">
                                    {{ __('Tasks I have created') }}
                                </a>
                            </div>
                        </li>

                        {{--                     <li class="nav-item">
                                                <a class="nav-link"
                                                    href="/workspaces/{{ auth()->user()->workspace_id }}/tasks?responsibility={{ auth()->user()->id }}">
                                                    {{ __('Tasks I have created') }}
                                                </a>
                                            </li> --}}
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ml-auto mr-auto">
                @auth
                    @if (auth()->user()->workspace_id !== null)
                        <new-task :workspace="{{ auth()->user()->workspace }}"></new-task>
                    @endif
                @endauth
            </ul>


            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('signup') }}">{{ __('Sign up') }}</a>
					</li>
                @else
					<user-notifications :user="{{ Auth::user() }}"></user-notifications>

                    <li class="nav-item dropdown">
						<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
						<img class="mr-2" src="{{ auth()->user()->avatar_path }}" width="35px" height="35px" alt="avatar" style="border-radius: 50%;">
                            {{ Auth::user()->first_name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                {{ __('Dashboard') }}
							</a>
							
                            <a class="dropdown-item" href="{{ route('profile', auth()->user()) }}">
                                {{ __('My Profile') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
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
        </div>
    </div>
</nav>
