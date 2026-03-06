<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Inventaris</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 antialiased">

    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-blue-700">InventarisKu</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">Halo, {{ Auth::user()->name }}!</span>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 text-gray-800">
                <h2 class="text-2xl font-semibold mb-4">Selamat Datang di Dashboard!</h2>
                <p class="mb-4">Anda telah berhasil login. Ini adalah halaman utama sistem inventaris Anda.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-bold text-blue-800">Total Barang</h3>
                        <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                        <h3 class="text-lg font-bold text-green-800">Barang Masuk</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">0</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-100">
                        <h3 class="text-lg font-bold text-purple-800">Barang Keluar</h3>
                        <p class="text-3xl font-bold text-purple-600 mt-2">0</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>