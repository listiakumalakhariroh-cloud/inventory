<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan</title>

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

<body class="bg-gray-100 flex flex-col min-h-screen antialiased">

    <header class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">

            <div class="flex items-center">
                <a href="#" class="text-xl font-bold tracking-wider">Sistem Pengaduan</a>
            </div>

            <nav class="hidden md:flex space-x-6 items-center">
                <a href="#" class="hover:text-blue-200 transition font-medium">Home</a>
                <a href="#" class="hover:text-blue-200 transition font-medium">Daftar Aduan</a>
                <a href="#" class="hover:text-blue-200 transition font-medium">Progres Aduan</a>
                <a href="#" class="hover:text-blue-200 transition font-medium">Laporan Pengaduan</a>
            </nav>

            <div class="relative flex items-center space-x-4">
                <span class="text-white font-medium hidden sm:block">Halo, {{ Auth::user()->name }}!</span>

                <button id="profileBtn" class="flex items-center focus:outline-none">
                    <div
                        class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-600 overflow-hidden border-2 border-transparent hover:border-blue-300 transition shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </button>

                <div id="dropdownMenu"
                    class="hidden absolute right-0 top-12 mt-2 w-48 bg-white rounded-md shadow-lg py-1 text-gray-700 z-10 border">

                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2 text-sm font-medium hover:bg-blue-50 hover:text-blue-600 transition border-b border-gray-100">
                            Panel Admin
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block px-4 py-2 text-sm font-medium hover:bg-red-50 hover:text-red-600 transition">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow w-full max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-auto">
        <p class="text-sm">&copy; {{ date('Y') }} Sistem Pengaduan. Hak Cipta Dilindungi.</p>
    </footer>

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
