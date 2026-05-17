@extends('layout.layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Daftar Tugas Saya</h1>
    <p class="text-gray-600 mt-2">Berikut adalah daftar penugasan yang diberikan kepada Anda.</p>
</div>

<div class="grid grid-cols-1 gap-6">
    @forelse($penugasan as $p)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">
                            {{ $p->kodetugas }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Dibuat: {{ $p->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        {{ $p->tugas->nama_tugas ?? 'Tanpa Nama Tugas' }}
                    </h2>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $p->tugas->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>
                </div>

                <div class="flex flex-col items-start md:items-end gap-3">
                    <div class="flex -space-x-2">
                        @foreach($p->anggota as $anggota)
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode($anggota->user->name) }}&background=random&color=fff" 
                                 title="{{ $anggota->user->name }}">
                        @endforeach
                    </div>
                    <a href="{{ route('laporan.create', ['id_penugasan' => $p->id]) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Kirim Laporan
                    </a>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                Deadline: {{ $p->tugas->deadline ?? 'Tidak ditentukan' }}
            </div>
            <span class="text-sm font-medium text-blue-600 hover:underline">
                <a href="{{ route('penugasan.show', $p->id) }}">Lihat Detail →</a>
            </span>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada tugas</h3>
        <p class="mt-1 text-sm text-gray-500">Saat ini Anda belum memiliki daftar penugasan aktif.</p>
    </div>
    @endforelse
</div>
@endsection