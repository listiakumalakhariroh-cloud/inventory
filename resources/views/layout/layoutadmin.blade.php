<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Sistem Pengaduan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex h-screen antialiased overflow-hidden">

    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-lg flex-shrink-0">
        <div class="h-16 flex items-center px-6 bg-gray-950 border-b border-gray-800">
            <span class="text-xl font-bold tracking-wider text-blue-400">Admin<span
                    class="text-white">Panel</span></span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Dashboard
            </a>

            <p class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul Utama</p>

            <a href="{{ route('admin.tugas.index') }}"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('admin.tugas.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span class="font-medium">Manajemen Tugas</span>
            </a>

            <a href="{{ route('admin.pengaduan') }}"
                class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.pengaduan') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Manajemen Pengaduan
            </a>

            <a href="#"
                class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Manajemen Laporan
            </a>

            <p class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengaturan</p>

            <a href="#"
                class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Data Pengguna
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
            <button class="text-gray-500 focus:outline-none lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

            <div class="flex-1"></div>

            <div class="relative flex items-center space-x-4">
                <span class="text-gray-700 font-medium text-sm hidden sm:block">Halo, {{ Auth::user()->name }}</span>

                <button id="profileBtn" class="flex items-center focus:outline-none">
                    <div
                        class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 border-2 border-transparent hover:border-blue-300 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </button>

                <div id="dropdownMenu"
                    class="hidden absolute right-0 top-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1 text-gray-700 border border-gray-100">
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-50 transition border-b border-gray-50">Lihat Web
                        User</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 p-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Sistem Pengaduan. Dikembangkan oleh Tim IT.
        </footer>

    </div>

    <script>
        const profileBtn = document.getElementById('profileBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        profileBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', (event) => {
            if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
