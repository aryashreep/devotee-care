<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Devotee Care') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex flex-col md:flex-row">
        <div id="sidebar" class="bg-gray-800 shadow-lg h-screen w-64 fixed md:relative -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold">{{ config('app.name', 'Devotee Care') }}</a>
            </div>
            <div class="px-4 py-6">
                <ul class="text-gray-300">
                    <li class="mb-4">
                        <a href="{{ route('users.index') }}" class="flex items-center hover:text-white">
                            <span class="ml-2">Users</span>
                        </a>
                    </li>
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Management') || auth()->user()->hasRole('Finance') || auth()->user()->hasRole('Bhakti Sadan Leader'))
                    <li class="mb-4">
                        <h2 class="text-gray-500 uppercase tracking-wide font-bold text-xs">Masters</h2>
                        <ul class="mt-2 space-y-2">
                            <li><a href="{{ route('educations.index') }}" class="block hover:text-white">Education</a></li>
                            <li><a href="{{ route('professions.index') }}" class="block hover:text-white">Profession</a></li>
                            <li><a href="{{ route('bhakti-sadans.index') }}" class="block hover:text-white">Bhakti Sadan</a></li>
                            <li><a href="{{ route('sevas.index') }}" class="block hover:text-white">Seva</a></li>
                            <li><a href="{{ route('shiksha-levels.index') }}" class="block hover:text-white">Shiksha Level</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-md">
                <div class="flex justify-between items-center py-4 px-6">
                    <button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                    </form>
                </div>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
