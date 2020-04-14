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
        <div id="app" class="h-screen flex">
            {{-- sidebar / filters / nav --}}

            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" @v-if="sidebarOpen"
                class="fixed inset-y-0 left-0 z-10 w-64 px-6 py-4 bg-gray-100 border-r overflow-auto">
                {{-- logo --}}
                <div class="flex items-center justify-between flex-shrink-0 text-indigo-900">
                    <a href="/" class="flex items-center">
                        <img class="w-12" src="/img/logo.svg" alt="Imaginary" />
                        <p class="pl-1 font-bold text-xl tracking-tighter text-indigo-900">
                            taskbee.
                        </p>
                    </a>

                    <button @click="sidebarOpen = false">
                        <svg class="w-6 h-6 text-gray-600" fill="none" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16">
                            <path
                                d="M9.06 8l2.97-2.969a.75.75 0 00-1.061-1.062L8 6.938l-2.969-2.97A.751.751 0 103.97 5.032L6.938 8l-2.97 2.969a.75.75 0 101.063 1.062L8 9.062l2.969 2.97a.751.751 0 101.062-1.063L9.061 8z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>

                {{-- nav / filters --}}
                <nav class="mt-10">
                    <h2 class="uppercase font-semibold text-xs text-gray-700 tracking-wide">tasks</h2>

                    <div class="mt-2 -mx-3">
                        <a href="#" class="mt-1 flex justify-between bg-gray-300 px-3 py-2 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">all</span>
                            <span class="text-xs text-gray-700 font-semibold">35</span>
                        </a>

                        <a href="#" class="mt-1 flex justify-between hover:bg-gray-200 px-3 py-2 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">assigned to me</span>
                            <span class="text-xs text-gray-700 font-semibold">12</span>
                        </a>

                        <a href="#" class="mt-1 flex justify-between hover:bg-gray-200 px-3 py-2 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">assigned by me</span>
                            <span class="text-xs text-gray-700 font-semibold">7</span>
                        </a>

                        <a href="#" class="mt-1 flex justify-between hover:bg-gray-200 px-3 py-2 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">archived</span>
                            <span class="text-xs text-gray-700 font-semibold"></span>
                        </a>
                    </div>

                    <h2 class="uppercase font-semibold text-xs text-gray-700 tracking-wide pt-10">workspaces</h2>

                    <div class="mt-2 -mx-3">
                        <a href="#" class="mt-1 flex justify-between bg-gray-300 px-3 py-2 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">workspace 1</span>
                        </a>

                        <a href="#" class="mt-1 flex justify-between px-3 py-2 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">workspace 2</span>
                        </a>
                    </div>

                    <button
                        class="flex items-center uppercase justify-center font-medium text-xs text-gray-600 tracking-wide mt-10">
                        <svg fill="none" class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 17">
                            <path
                                d="M14.544 6.494H9.507V1.457c0-1.943-3.013-1.943-3.013 0v5.037H1.457c-1.943 0-1.943 3.013 0 3.013h5.037v5.037c0 1.943 3.013 1.943 3.013 0V9.507h5.037c1.942 0 1.942-3.013 0-3.013z"
                                fill="currentColor" />
                        </svg>

                        <span class="ml-2">new task</span>
                    </button>
                </nav>

            </div>

            {{-- main area --}}
            <div class="flex-1 min-w-0 bg-white flex flex-col">

                {{-- header --}}
                <header class="flex-shrink-0 border-b-2 border-gray-300 px-6">
                    {{-- top header section --}}
                    <div class="border-b flex justify-between items-center py-2">
                        {{-- hamburger --}}
                        <button class="mr-3" @click="sidebarOpen = true">
                            <svg class="h-6 w-6 text-gray-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 16 16">
                                <path d="M2.75 4.75h10.5M2.75 8h10.5M2.75 11.25h10.5" stroke="currentColor"
                                    stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                            </svg>
                        </button>
                        <div class="flex-1">
                            <div class="relative max-w-xs">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center">
                                    <svg fill="none" class="h-4 w-4 mr-2 text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M11 19a8 8 0 100-16 8 8 0 000 16zM21 21l-4.35-4.35"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>

                                <input
                                    class="block w-full border rounded-lg pl-10 pr-4 py-2 px-3 text-sm text-gray-900 placeholder-gray-600 focus:outline-none"
                                    type="text" placeholder="Search">
                            </div>
                        </div>
                        <div>
                            <account-dropdown></account-dropdown>
                        </div>
                    </div>

                    {{-- bottom header section --}}
                    <div class=" flex items-center justify-between py-2">
                        {{-- left --}}
                        <div class="flex items-center">
                            <h2 class="text-2xl font-semibold text-gray-700">All Tasks</h2>
                            <div class="flex ml-6">
                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                <img class="h-8 w-8 rounded-full -ml-2 object-cover border-2 border-white"
                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                <img class="h-8 w-8 rounded-full -ml-2 object-cover border-2 border-white"
                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                <img class="h-8 w-8 rounded-full -ml-2 object-cover border-2 border-white"
                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                <img class="h-8 w-8 rounded-full -ml-2 object-cover border-2 border-white"
                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                            </div>
                        </div>
                        {{-- right --}}
                        <div class="flex items-center">
                            <span
                                class="inline-flex justify-between bg-gray-200 border border-gray-300 rounded-lg px-1 py-1">
                                <button class="text-gray-600 px-3 py-2 mr-1">
                                    <svg class="w-6 h-6" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24">
                                        <path d="M4 18h17v-6H4v6zM4 5v6h17V5H4z" fill="currentColor" />
                                    </svg>
                                </button>
                                <button class="text-gray-600 px-3 py-2 ml-1 bg-white rounded-lg shadow">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6 5H3C2.45 5 2 5.45 2 6V18C2 18.55 2.45 19 3 19H6C6.55 19 7 18.55 7 18V6C7 5.45 6.55 5 6 5ZM20 5H17C16.45 5 16 5.45 16 6V18C16 18.55 16.45 19 17 19H20C20.55 19 21 18.55 21 18V6C21 5.45 20.55 5 20 5ZM13 5H10C9.45 5 9 5.45 9 6V18C9 18.55 9.45 19 10 19H13C13.55 19 14 18.55 14 18V6C14 5.45 13.55 5 13 5Z"
                                            fill="currentColor" />
                                    </svg>
                                </button>
                            </span>
                            <div>
                                {{-- new task button --}}
                                <button
                                    class="flex items-center uppercase bg-indigo-900 hover:bg-indigo-700 font-medium shadow ml-5 text-xs text-white px-4 py-4 rounded-lg tracking-wide">
                                    <svg fill="none" class="w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 17">
                                        <path
                                            d="M14.544 6.494H9.507V1.457c0-1.943-3.013-1.943-3.013 0v5.037H1.457c-1.943 0-1.943 3.013 0 3.013h5.037v5.037c0 1.943 3.013 1.943 3.013 0V9.507h5.037c1.942 0 1.942-3.013 0-3.013z"
                                            fill="currentColor" />
                                    </svg>

                                    <span class="ml-2">new task</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- tasks --}}
                <div class="flex-1 overflow-auto">
                    <main class="p-3 inline-flex h-full overflow-hidden">

                        {{-- task category --}}
                        <div class="w-80 bg-gray-100 flex flex-col mr-3 rounded flex-shrink-0">
                            <div class="flex items-center justify-between px-3 py-2 border-b">
                                <h3 class="text-xl font-semibold text-gray-700 flex-shrink-0">Urgent</h3>
                                <svg viewbox="0 0 8 8" class="h-4 w-4 text-red-500">
                                    <circle cx="4" cy="4" r="4" fill="currentColor" />
                                </svg>
                            </div>

                            <div class="flex-1 min-h-0 overflow-y-auto">
                                <ul class="p-3">
                                    {{-- single task --}}
                                    <li class="bg-white shadow rounded-lg w-full mb-3">
                                        <a href="" class="block p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                                <span>
                                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                        src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                                </span>
                                            </div>


                                            <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                                <div class="text-gray-600">
                                                    <time datetime="2019-12-21">Dec 21</time>
                                                </div>
                                                <div>feature / smt</div>
                                            </div>
                                        </a>
                                    </li>
                                    {{-- single task --}}
                                    <li class="bg-white shadow rounded-lg w-full mb-3">
                                        <a href="" class="block p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                                <span>
                                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                        src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                                </span>
                                            </div>


                                            <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                                <div class="text-gray-600">
                                                    <time datetime="2019-12-21">Dec 21</time>
                                                </div>
                                                <div>feature / smt</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="bg-white shadow rounded-lg w-full mb-3">
                                        <a href="" class="block p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                                <span>
                                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                        src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                                </span>
                                            </div>


                                            <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                                <div class="text-gray-600">
                                                    <time datetime="2019-12-21">Dec 21</time>
                                                </div>
                                                <div>feature / smt</div>
                                            </div>
                                        </a>
                                    </li>
                                    {{-- single task --}}
                                    <li class="bg-white shadow rounded-lg w-full mb-3">
                                        <a href="" class="block p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                                <span>
                                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                        src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                                </span>
                                            </div>


                                            <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                                <div class="text-gray-600">
                                                    <time datetime="2019-12-21">Dec 21</time>
                                                </div>
                                                <div>feature / smt</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="bg-white shadow rounded-lg w-full mb-3">
                                        <a href="" class="block p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                                <span>
                                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                        src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                                </span>
                                            </div>


                                            <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                                <div class="text-gray-600">
                                                    <time datetime="2019-12-21">Dec 21</time>
                                                </div>
                                                <div>feature / smt</div>
                                            </div>
                                        </a>
                                    </li>
                                    {{-- single task --}}
                                    <li class="bg-white shadow rounded-lg w-full mb-3">
                                        <a href="" class="block p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                                <span>
                                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                        src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                                </span>
                                            </div>


                                            <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                                <div class="text-gray-600">
                                                    <time datetime="2019-12-21">Dec 21</time>
                                                </div>
                                                <div>feature / smt</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="w-80 bg-gray-100 mr-3 rounded flex flex-col flex-shrink-0">
                            <div class="flex items-center justify-between px-3 py-2 border-b">
                                <h3 class="text-xl font-semibold text-gray-700">Pending</h3>
                                <svg viewbox="0 0 8 8" class="h-4 w-4 text-orange-500">
                                    <circle cx="4" cy="4" r="4" fill="currentColor" />
                                </svg>
                            </div>

                            <ul class="p-3 overflow-y-auto">
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                {{-- single task --}}
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                {{-- single task --}}
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="w-80 bg-gray-100 mr-3 rounded flex flex-col flex-shrink-0">
                            <div class="flex items-center justify-between px-3 py-2 border-b">
                                <h3 class="text-xl font-semibold text-gray-700">Ready for review</h3>
                                <svg viewbox="0 0 8 8" class="h-4 w-4 text-yellow-500">
                                    <circle cx="4" cy="4" r="4" fill="currentColor" />
                                </svg>
                            </div>

                            <ul class="p-3 overflow-y-auto">
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                {{-- single task --}}
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                {{-- single task --}}
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="w-80 bg-gray-100 mr-3 rounded flex flex-col flex-shrink-0">
                            <div class="flex items-center justify-between px-3 py-2 border-b">
                                <h3 class="text-xl font-semibold text-gray-700">Done</h3>
                                <svg viewbox="0 0 8 8" class="h-4 w-4 text-green-500">
                                    <circle cx="4" cy="4" r="4" fill="currentColor" />
                                </svg>
                            </div>

                            <ul class="p-3 overflow-y-auto">
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                {{-- single task --}}
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                                {{-- single task --}}
                                <li class="bg-white shadow rounded-lg w-full mb-3">
                                    <a href="" class="block p-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-gray-800 text-sm font-medium">Go to the store</p>
                                            <span>
                                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                                    src="{{ asset('img/placeholder-avatar.svg') }}" alt="">
                                            </span>
                                        </div>


                                        <div class="flex justify-between text-gray-800 text-sm font-medium py-2">
                                            <div class="text-gray-600">
                                                <time datetime="2019-12-21">Dec 21</time>
                                            </div>
                                            <div>feature / smt</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </main>

                </div>
            </div>
        </div>
    </body>

</html>