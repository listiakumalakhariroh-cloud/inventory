@extends('layout.layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                <li><a href="{{ route('penugasan.index') }}" class="hover:text-blue-600 transition">Tugas Saya</a></li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </li>
                <li class="text-gray-900 font-medium">Detail Penugasan</li>
            </ol>
        </nav>
        <h1 class="text-3xl font-extrabold text-gray-900">{{ $p->tugas->nama_tugas }}</h1>
        <p class="text-gray-500 mt-1">Kode Tugas: <span class="font-mono font-bold text-blue-600">{{ $p->tugas->kodetugas }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Deskripsi Tugas</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($p->tugas->deskripsi ?? 'Tidak ada deskripsi tambahan.')) !!}
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Timeline & Status</h3>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg text-red-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase">Batas Waktu</p>
                            <p class="text-gray-900 font-bold">{{ \Carbon\Carbon::parse($p->batas_waktu_lapor)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full uppercase">Sedang Berjalan</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Rekan Tim</h3>
                <div class="space-y-3">
                    @foreach($p->anggota as $anggota)
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-full border border-gray-200" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($anggota->user->name) }}&background=random&color=fff" alt="">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->user->name }}</p>
                            {{-- Baris informasi jabatan telah dihapus --}}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-2">Sudah Selesai?</h3>
                <p class="text-sm text-blue-100 mb-4">Segera kirimkan laporan pengerjaan Anda sebelum melewati batas waktu.</p>
                <a href="{{ route('laporan.create', ['id_penugasan' => $p->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white text-blue-600 rounded-xl font-bold hover:bg-blue-50 transition shadow-sm">
                    Buat Laporan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection