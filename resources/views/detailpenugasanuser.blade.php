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
        <h1 class="text-3xl font-extrabold text-gray-900">{{ $p->tugas->nama_tugas ?? 'Detail Penugasan' }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Deskripsi Tugas</h3>
                <div class="prose prose-blue text-gray-700 max-w-none">
                    {!! nl2br(e($p->tugas->deskripsi)) !!}
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Lampiran Pendukung</h3>
                @if($p->tugas->lampiran)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <svg class="w-8 h-8 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">File Dokumen Tugas</p>
                            <p class="text-xs text-gray-500">Klik tombol di samping untuk melihat file.</p>
                        </div>
                        <a href="{{ asset('storage/' . $p->tugas->lampiran) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                            Buka File
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Tidak ada lampiran file untuk tugas ini.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Waktu Pengerjaan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Mulai</p>
                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($p->tugas->tanggal_mulai)->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Batas Laporan (Deadline)</p>
                        <p class="text-sm font-semibold text-red-600">{{ \Carbon\Carbon::parse($p->batas_waktu_lapor)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Rekan Tim</h3>
                <div class="space-y-3">
                    @foreach($p->anggota as $anggota)
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-full border border-gray-200" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($anggota->user->name) }}&background=random&color=fff" alt="">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $anggota->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $anggota->jabatan->nama_jabatan ?? 'Anggota' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-2">Sudah Selesai?</h3>
                <p class="text-sm text-blue-100 mb-4">Segera kirimkan laporan pengerjaan Anda sebelum melewati batas waktu.</p>
                <a href="{{ route('laporan.create', ['id_penugasan' => $p->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition shadow-md">
                    Kirim Laporan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection