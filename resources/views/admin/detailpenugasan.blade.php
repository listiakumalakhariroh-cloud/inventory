@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Penugasan</h2>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap mengenai delegasi tugas dan tim pelaksana.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.penugasan.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <a href="{{ route('admin.penugasan.create', ['kodetugas' => $p->kodetugas]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Penugasan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Informasi Tugas & Status --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Card Utama Penugasan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Informasi Penugasan</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-500 block">Status Penugasan</label>
                        @php
                            $today = \Carbon\Carbon::today();
                            $batas = \Carbon\Carbon::parse($p->batas_waktu_lapor);
                            $isOverdue = $today->gt($batas);
                        @endphp
                        @if ($isOverdue)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span>
                                Terlewat
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span>
                                Aktif
                            </span>
                        @endif
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">Batas Waktu Lapor</label>
                        <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($p->batas_waktu_lapor)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">Pemberi Tugas (Admin)</label>
                        <p class="text-sm font-medium text-gray-800">{{ $p->admin->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $p->admin->email ?? '' }}</p>
                    </div>
                    <div class="pt-4 border-t border-gray-50">
                        <label class="text-xs text-gray-500 block italic">Dibuat pada: {{ $p->created_at->format('d/m/Y H:i') }}</label>
                    </div>
                </div>
            </div>

            {{-- Card Informasi Master Tugas --}}
            <div class="bg-blue-600 rounded-xl shadow-md p-6 text-white">
                <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-4">Detail Master Tugas</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-blue-200 block">Kode Tugas</label>
                        <p class="font-mono font-bold text-lg">{{ $p->kodetugas }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-blue-200 block">Nama Tugas</label>
                        <p class="font-semibold">{{ $p->tugas->nama_tugas ?? 'Tugas Tidak Ditemukan' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-blue-200 block">Deskripsi Tugas</label>
                        <p class="text-sm text-blue-50/80 leading-relaxed">{{ $p->tugas->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Anggota --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Anggota Tim Pelaksana</h3>
                    <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                        {{ $p->anggota->count() }} Orang
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Nama Anggota</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Jabatan Dalam Tugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($p->anggota as $item)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3 text-xs">
                                                {{ strtoupper(substr($item->user->name ?? '?', 0, 2)) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-800">{{ $item->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $item->user->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-md bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-200">
                                            {{ $item->jabatan->nama_jabatan ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($p->anggota->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-gray-400 text-sm italic">Belum ada anggota yang ditugaskan untuk tugas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection