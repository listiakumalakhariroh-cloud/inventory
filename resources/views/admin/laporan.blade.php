@extends('layout.layoutadmin')

@section('title', 'Manajemen Laporan')

@section('content')
<style>
    @keyframes fadeInUp { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .hud-panel { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
</style>

<div class="container mx-auto px-4 py-6 font-sans space-y-6">
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end border-b border-gray-300 pb-4 hud-panel">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-7 h-7 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"></path>
                </svg>
                Manajemen Laporan Masuk
            </h2>
            <p class="text-sm text-gray-500 mt-1">Audit berkas fisik dan rekapitulasi capaian infrastruktur DPU Kabupaten Semarang.</p>
        </div>
        
        <div class="mt-4 md:mt-0 w-full md:w-auto">
            <form action="" method="GET" class="flex gap-2">
                <div class="relative w-full md:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-9 pr-4 py-2 bg-white border border-gray-200 text-xs font-medium text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all outline-none"
                        placeholder="Cari NIP, Nama Agen, atau Kode...">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow transition uppercase tracking-wider">
                    CARI
                </button>
            </form>
        </div>
    </div>

    <div class="hud-panel delay-100 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-black px-4 py-3 flex items-center justify-between rounded-t-xl">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
                </svg>
                <h3 class="text-white text-sm font-semibold">Arsip Lembar Kerja Masuk</h3>
            </div>
            <div>
                <span class="text-[10px] font-bold text-gray-400 uppercase font-mono bg-gray-900 px-2 py-1 rounded">
                    Total Sesi: {{ count($laporan ?? []) }} Log
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">
                        <th class="py-3.5 px-4 text-left w-12">No</th>
                        <th class="py-3.5 px-4 text-left">Proyek / Tugas Teknis</th>
                        <th class="py-3.5 px-4 text-left">Pengirim (Pelaksana)</th>
                        <th class="py-3.5 px-4">Tanggal Masuk</th>
                        <th class="py-3.5 px-4">Status Audit</th>
                        <th class="py-3.5 px-4 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 text-xs font-medium">
                    @forelse($laporan ?? [] as $index => $l)
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150 group">
                            <td class="py-4 px-4 text-center text-gray-400 font-bold">
                                {{ $index + 1 }}
                            </td>
                            
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    <span class="font-mono font-bold text-blue-600 text-[10px] bg-blue-50 border border-blue-100 px-1.5 py-0.5 rounded shadow-inner">
                                        {{ $l->penugasan->kodetugas ?? '-' }}
                                    </span>
                                    <p class="text-gray-900 font-bold truncate max-w-xs md:max-w-md group-hover:text-blue-600 transition-colors mt-1">
                                        {{ $l->tugas->nama_tugas ?? $l->penugasan->tugas->nama_tugas ?? 'Tugas Tidak Diketahui' }}
                                    </p>
                                </div>
                            </td>
                            
                            <td class="py-4 px-4">
                                @php
                                    $namaPengirim = $l->user->name ?? '-';
                                    $nipPengirim = $l->user->nip ?? '-';
                                    if ($namaPengirim === '-' && $l->penugasan && $l->penugasan->anggota->first()) {
                                        $namaPengirim = $l->penugasan->anggota->first()->user->name ?? 'Pegawai';
                                        $nipPengirim = $l->penugasan->anggota->first()->user->nip ?? '-';
                                    }
                                @endphp
                                <div>
                                    <p class="text-gray-900 font-black tracking-wide">{{ $namaPengirim }}</p>
                                    <p class="text-[10px] font-mono text-gray-400 mt-0.5">NIP. {{ $nipPengirim }}</p>
                                </div>
                            </td>
                            
                            <td class="py-4 px-4 text-center text-gray-500 font-mono text-[11px]">
                                {{ $l->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            
                            <td class="py-4 px-4 text-center">
                                @if($l->status === 'disetujui')
                                    <span class="px-2.5 py-1 text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-md uppercase tracking-wider">Disetujui</span>
                                @elseif($l->status === 'revisi')
                                    <span class="px-2.5 py-1 text-[10px] font-black text-amber-700 bg-amber-50 border border-amber-100 rounded-md uppercase tracking-wider animate-pulse">Revisi</span>
                                @elseif($l->status === 'menunggu' || $l->status === 'diajukan')
                                    <span class="px-2.5 py-1 text-[10px] font-black text-blue-700 bg-blue-50 border border-blue-100 rounded-md uppercase tracking-wider">Menunggu</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-black text-gray-600 bg-gray-50 border border-gray-200 rounded-md uppercase tracking-wider">{{ strtoupper($l->status) }}</span>
                                @endif
                            </td>
                            
                            <td class="py-4 px-4 text-center">
                                <a href="{{ url('admin/manajemen-laporan/'.$l->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow-sm transition uppercase tracking-wider transform hover:scale-105">
                                    REVIEW
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400 font-medium italic text-xs">
                                Belum ada berkas data laporan kemajuan proyek masuk yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($laporan, 'links'))
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                {{ $laporan->links() }}
            </div>
        @endif
    </div>

</div>
@endsection