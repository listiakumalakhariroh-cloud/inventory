@extends('layout.layout')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Laporan Saya</h1>
        <p class="text-gray-600 mt-2">Pantau status dan progres laporan penugasan Anda di sini.</p>
    </div>
    <div>
        <a href="{{ route('penugasan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Laporan Baru
        </a>
    </div>
</div>

<div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tugas</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Kirim</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporans as $l)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-blue-600 uppercase">{{ $l->penugasan->kodetugas }}</div>
                        <div class="text-sm font-medium text-gray-900">{{ $l->penugasan->tugas->nama_tugas ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $l->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($l->status == 'diajukan')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-blue-500"></span>
                                Diajukan
                            </span>
                        @elseif($l->status == 'revisi')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span>
                                Revisi
                            </span>
                        @elseif($l->status == 'disetujui')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-500"></span>
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('laporan.show', $l->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 font-semibold px-3 py-1 bg-blue-50 rounded-md transition">
                            Lihat Detail & Chat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                        <div class="flex flex-col items-center">
                            <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Belum ada laporan yang dikirimkan.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection