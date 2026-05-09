<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pointify - Sistem Pengaduan</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 font-sans antialiased flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-2xl font-extrabold text-blue-600 tracking-tight hover:opacity-90 transition-opacity">
                        <div class="bg-blue-600 text-white p-1 rounded-lg shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span>Pointify<span class="text-gray-800">.</span></span>
                    </a>
                </div>

                <div class="hidden sm:flex sm:items-center sm:space-x-3">
                    
                    <a href="{{ route('penugasan.index') }}" 
                       class="{{ request()->routeIs('penugasan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-600' }} inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 group">
                        <svg class="w-5 h-5 mr-2 {{ request()->routeIs('penugasan.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Tugas
                    </a>
                    
                    <a href="{{ route('laporan.index') }}" 
                       class="{{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-600' }} inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 group">
                        <svg class="w-5 h-5 mr-2 {{ request()->routeIs('laporan.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Laporan
                    </a>
                    
                    <div class="h-6 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                    @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin'))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2.5 ml-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm hover:from-blue-700 hover:to-indigo-700 hover:shadow transform hover:-translate-y-0.5 transition-all duration-200">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                       </svg>
                       Admin Panel
                    </a>
                    @endif

                    <div class="relative ml-2" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" class="flex items-center gap-2 text-sm border-2 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-blue-100 transition p-1 hover:bg-gray-50">
                                <span class="hidden md:block font-bold text-gray-700 ml-2">{{ Auth::user()->name }}</span>
                                <img class="h-9 w-9 rounded-full object-cover border border-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&bold=true" alt="User profile">
                                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                        
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95 translate-y-[-10px]"
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="origin-top-right absolute right-0 mt-3 w-48 rounded-xl shadow-lg py-2 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-xs text-gray-500 font-medium">Login sebagai</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors"> 
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar Sistem 
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 overflow-hidden sm:px-6 lg:px-8">
            <p class="text-center text-sm font-medium text-gray-400 flex justify-center items-center gap-1">
                &copy; {{ date('Y') }} Pointify. Crafted with <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
            </p>
        </div>
    </footer>

</body>
</html>