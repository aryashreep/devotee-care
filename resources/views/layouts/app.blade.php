<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md w-full">
        <div class="flex justify-between items-center py-4 px-6">
            <div class="p-4">
                <a href="{{ route('dashboard') }}" class="text-gray-800 text-2xl font-bold uppercase">Devotee Care</a>
            </div>
            <div class="flex items-center">
                <button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex items-center ml-auto">
                    <button class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-2">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="mr-2">{{ auth()->user()->name }}</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                            <div class="px-4 py-2 text-white bg-blue-500 font-bold rounded-t-md">Account</div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b"><i class="fas fa-user mr-3"></i>Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-sign-out-alt mr-3"></i>Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gray-800 text-white w-64 min-h-screen fixed md:relative -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <nav class="mt-4">
                <h2 class="px-6 text-gray-500 uppercase tracking-wide font-bold text-xs">Content</h2>
                @auth
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Management'))
                        <a href="{{ route('users.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : '' }}">
                            <i class="fas fa-users mr-3"></i>
                            <span class="mx-3">Users</span>
                        </a>
                    @endif
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Management') || auth()->user()->hasRole('Finance') || auth()->user()->hasRole('Bhakti Sadan Leader'))
                        <div class="mt-4">
                            <h2 class="px-6 text-gray-500 uppercase tracking-wide font-bold text-xs">Masters</h2>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('educations.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('educations.*') ? 'bg-blue-600 text-white' : '' }}">
                                    <span class="mx-3">Education</span>
                                </a>
                                <a href="{{ route('professions.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('professions.*') ? 'bg-blue-600 text-white' : '' }}">
                                    <span class="mx-3">Profession</span>
                                </a>
                                <a href="{{ route('bhakti-sadans.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('bhakti-sadans.*') ? 'bg-blue-600 text-white' : '' }}">
                                    <span class="mx-3">Bhakti Sadan</span>
                                </a>
                                <a href="{{ route('sevas.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('sevas.*') ? 'bg-blue-600 text-white' : '' }}">
                                    <span class="mx-3">Seva</span>
                                </a>
                                <a href="{{ route('shiksha-levels.index') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('shiksha-levels.*') ? 'bg-blue-600 text-white' : '' }}">
                                    <span class="mx-3">Shiksha Level</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                <h2 class="px-6 mt-4 text-gray-500 uppercase tracking-wide font-bold text-xs">Settings</h2>
                @auth
                    @if (Route::has('profile.show'))
                        <a href="{{ route('profile.show') }}" class="flex items-center py-2 px-6 text-gray-400 hover:bg-blue-600 hover:text-white {{ request()->routeIs('profile.show') ? 'bg-blue-600 text-white' : '' }}">
                            <i class="fas fa-user-circle mr-3"></i>
                            <span class="mx-3">Manage access</span>
                        </a>
                    @endif
                @endauth
            </nav>
            <div class="absolute bottom-0 w-full">
                <button class="w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
            <footer class="bg-white p-4 text-center text-sm text-gray-600">
                Powered by <a href="#" class="text-blue-500">Devotee Care</a>
            </footer>
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
    @stack('styles')
    @stack('scripts')
</body>
</html>
