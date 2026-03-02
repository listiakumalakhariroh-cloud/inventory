<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi - Masuk ke Akun Anda</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 antialiased">

    <div class="flex min-h-screen">
        
        <div class="hidden md:flex md:w-1/2 lg:w-2/3 bg-linear-to-br from-blue-700 via-indigo-800 to-purple-900 p-12 items-center justify-center relative overflow-hidden">
            <svg class="absolute inset-0 opacity-10 w-full h-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 1600 800">
                <defs>
                    <pattern id="a" height="20" width="20" patternUnits="userSpaceOnUse">
                        <circle cx="10" cy="10" r="1" fill="#fff"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#a)"/>
            </svg>
            
            <div class="relative z-10 text-center max-w-lg">
                <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center backdrop-blur-sm mx-auto mb-10 shadow-xl border border-white/20">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight mb-6">
                    Kelola Platform Anda dengan Cepat & Mudah
                </h1>
                <p class="text-xl text-blue-100/90 font-light leading-relaxed">
                    Sistem informasi terintegrasi untuk meningkatkan efisiensi kerja dan pengambilan keputusan yang lebih baik.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/3 bg-white flex items-center justify-center p-8 sm:p-12 lg:p-16">
            <div class="w-full max-w-sm mx-auto">
                
                <div class="text-center md:text-left mb-12">
                    <div class="md:hidden flex justify-center mb-6">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-950 tracking-tight">Selamat Datang!</h2>
                    <p class="text-gray-600 mt-2.5">Masukkan detail akun Anda untuk mengakses sistem.</p>
                </div>

                <form action="/login" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="email" class="text-sm font-semibold text-gray-800">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" 
                                class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-950 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition duration-200 shadow-inner-sm" 
                                placeholder="nama@perusahaan.com" required autofocus>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="text-sm font-semibold text-gray-800">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" 
                                class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-950 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition duration-200 shadow-inner-sm" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" 
                                class="w-4.5 h-4.5 text-blue-600 border-gray-300 rounded-md focus:ring-blue-500 cursor-pointer">
                            <label for="remember" class="ml-2.5 text-sm text-gray-700 cursor-pointer user-select-none">Ingat saya</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                            Lupa password?
                        </a>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg focus:ring-4 focus:ring-blue-200 active:scale-[0.98]">
                            Masuk Ke Sistem
                        </button>
                    </div>
                </form>

                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-500">
                        Butuh bantuan? <a href="#" class="text-blue-600 font-medium hover:underline">Hubungi Tim IT</a>
                    </p>
                    <p class="text-xs text-gray-400 mt-6">© {{ date('Y') }} PT. Nama Perusahaan Anda. All rights reserved.</p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>