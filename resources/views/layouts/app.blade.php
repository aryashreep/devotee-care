<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Devotee Care') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gray-900 text-white w-64 min-h-screen fixed md:relative -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-4">
                <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold uppercase">Devotee Care</a>
            </div>
            <nav class="mt-4">
                @auth
                    @if (Route::has('profile.show'))
                        <a href="{{ route('profile.show') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('profile.show') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <span class="mx-3">View My Profile</span>
                        </a>
                    @endif
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Management'))
                        <a href="{{ route('users.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <span class="mx-3">Users</span>
                        </a>
                    @endif
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Management') || auth()->user()->hasRole('Finance') || auth()->user()->hasRole('Bhakti Sadan Leader'))
                        <div class="mt-4">
                            <h2 class="px-6 text-gray-500 uppercase tracking-wide font-bold text-xs">Masters</h2>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('educations.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('educations.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                                    <span class="mx-3">Education</span>
                                </a>
                                <a href="{{ route('professions.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('professions.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                                    <span class="mx-3">Profession</span>
                                </a>
                                <a href="{{ route('bhakti-sadans.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('bhakti-sadans.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                                    <span class="mx-3">Bhakti Sadan</span>
                                </a>
                                <a href="{{ route('sevas.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('sevas.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                                    <span class="mx-3">Seva</span>
                                </a>
                                <a href="{{ route('shiksha-levels.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white {{ request()->routeIs('shiksha-levels.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                                    <span class="mx-3">Shiksha Level</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow">
                <div class="flex justify-between items-center py-4 px-6">
                    <button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="flex items-center">
                        <div class="relative">
                            <button class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span class="mr-2">{{ auth()->user()->name }}</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-700">@yield('title')</h1>
                    @yield('header-actions')
                </div>
                <div class="mt-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const userMenuButton = document.querySelector('.relative button');
            const userMenu = document.querySelector('.relative .hidden');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });

            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
