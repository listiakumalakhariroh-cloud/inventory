@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Panel Administrator</h2>
        <p class="text-gray-600 mt-2">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>! Anda memiliki akses penuh untuk mengelola Sistem Pengaduan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500 flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pengguna</p>
                <p class="text-2xl font-bold text-gray-800">0</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Aduan Masuk</p>
                <p class="text-2xl font-bold text-gray-800">0</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500 flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-800">0</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500 flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Aduan Selesai</p>
                <p class="text-2xl font-bold text-gray-800">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Aduan Terbaru</h3>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua Data &rarr;</a>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Tanggal</th>
                            <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Pelapor</th>
                            <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Isi Aduan Ringkas</th>
                            <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Status</th>
                            <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 border-b text-sm text-gray-600">Belum ada data</td>
                            <td class="py-3 px-4 border-b text-sm text-gray-600">-</td>
                            <td class="py-3 px-4 border-b text-sm text-gray-600">-</td>
                            <td class="py-3 px-4 border-b text-sm">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">-</span>
                            </td>
                            <td class="py-3 px-4 border-b text-sm text-center">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition">Proses</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection