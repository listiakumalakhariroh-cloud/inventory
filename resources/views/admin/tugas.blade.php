@extends('layout.layoutadmin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Penugasan</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tugas.template') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Template Excel
            </a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Tambah Tugas</button>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3 justify-between items-center">
        <div class="flex gap-2">
            <a href="{{ route('admin.tugas.export') }}" class="border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm flex items-center text-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
            <button class="border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm flex items-center text-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                Import Tugas
            </button>
        </div>
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm flex items-center transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            Filter
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Nama Tugas</th>
                    <th class="px-6 py-4">Admin</th>
                    <th class="px-6 py-4">Tgl Mulai</th>
                    <th class="px-6 py-4">Tgl Selesai</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @foreach($tugas as $t)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm text-blue-600">{{ $t->kodetugas }}</td>
                    <td class="px-6 py-4 font-medium truncate max-w-xs">{{ $t->nama_tugas }}</td>
                    <td class="px-6 py-4 text-sm">{{ $t->admin->name ?? 'Admin' }}</td>
                    
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($t->tanggal_mulai)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($t->tanggal_selesai)->format('d/m/Y') }}</td>
                    
                    <td class="px-6 py-4">
                        @php
                            $today = \Carbon\Carbon::today();
                            $start = \Carbon\Carbon::parse($t->tanggal_mulai);
                            $end = \Carbon\Carbon::parse($t->tanggal_selesai);
                        @endphp
                        @if($today->lt($start))
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Mendatang</span>
                        @elseif($today->gt($end))
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Selesai</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center space-x-3">
                            <button class="text-blue-500 hover:text-blue-700 transition" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <button class="text-yellow-500 hover:text-yellow-700 transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection