@extends('layout.layout')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200 text-gray-800">
            <h2 class="text-2xl font-semibold mb-4">Selamat Datang di Dashboard!</h2>
            <p class="mb-4">Anda telah berhasil login. Ini adalah halaman utama Sistem Pengaduan Anda.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                    <h3 class="text-lg font-bold text-blue-800">Total Aduan</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
                </div>
                <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                    <h3 class="text-lg font-bold text-green-800">Aduan Diproses</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">0</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg border border-purple-100">
                    <h3 class="text-lg font-bold text-purple-800">Aduan Selesai</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">0</p>
                </div>
            </div>
        </div>
    </div>
@endsection