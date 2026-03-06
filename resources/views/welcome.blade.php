<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris - Masuk ke Akun Anda</title>

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

<body class="bg-gray-50 antialiased">

    <div class="flex min-h-screen">

        <div class="hidden md:flex md:w-1/2 lg:w-2/3 bg-gradient-to-br from-blue-700 via-indigo-800 to-purple-900 p-12 items-center justify-center relative overflow-hidden">
            <svg class="absolute inset-0 opacity-10 w-full h-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 1600 800">
                <defs>
                    <pattern id="a" height="20" width="20" patternUnits="userSpaceOnUse">
                        <circle cx="10" cy="10" r="1" fill="#fff" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#a)" />
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

        <div class="w-full md:w-1/2 lg:w-1/3 bg-white flex items-center justify-center p-8 sm:p-12 lg:p-16 shadow-2xl z-20">
            <div class="w-full max-w-sm mx-auto">

                <div class="text-center md:text-left mb-10">
                    <div class="md:hidden flex justify-center mb-6">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang!</h2>
                    <p class="text-gray-500 mt-2">Masukkan detail akun Anda untuk mengakses sistem.</p>
                </div>

                <form action="/login" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all @error('email') border-red-500 @enderror" 
                            placeholder="nama@email.com">
                        
                        @error('email')
                            <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all" 
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                                Ingat saya
                            </label>
                        </div>
                        
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Lupa password?</a>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2">
                        Masuk
                    </button>
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