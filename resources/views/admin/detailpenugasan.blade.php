@extends('layout.layoutadmin')

@section('content')
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.penugasan.index') }}" class="hover:text-blue-600">Manajemen Penugasan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Detail Penugasan</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Detail Penugasan</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Informasi Tugas</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase">
                    {{ $p->tugas->kodetugas }}
                </span>
            </div>
            <div class="p-6">
                <h4 class="text-xl font-bold text-gray-900 mb-4">{{ $p->tugas->nama_tugas }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider mb-1">Batas Waktu Lapor</p>
                        <p class="text-gray-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($p->batas_waktu_lapor)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider mb-1">Admin Penanggung Jawab</p>
                        <p class="text-gray-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $p->admin->name ?? 'Administrator' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800">Daftar Anggota Pelaksana</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                            </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($p->anggota as $index => $a)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                        {{ substr($a->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $a->user->name ?? 'User Tidak Ditemukan' }}</span>
                                </div>
                            </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada anggota yang ditugaskan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Tindakan</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.penugasan.edit', $p->id) }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Penugasan
                </a>
                
                <form action="{{ route('admin.penugasan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition font-medium text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Penugasan
                    </button>
                </form>

                <hr class="my-4 border-gray-100">

                <a href="{{ route('admin.penugasan.index') }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="bg-blue-600 rounded-xl shadow-md p-6 text-white">
            <h3 class="font-bold mb-2">Informasi Sistem</h3>
            <p class="text-blue-100 text-sm leading-relaxed">
                Penugasan ini dibuat pada {{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('d F Y, H:i') }} WIB. Seluruh anggota yang terdaftar akan menerima notifikasi tugas di dashboard mereka masing-masing.
            </p>
        </div>
    </div>
</div>
@endsection