@extends('layout.layoutadmin')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengaduan</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola semua data laporan dan penugasan perbaikan di sini.</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-lg hover:bg-emerald-100 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Template .xlsx
            </a>

            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Data
            </button>

            <a href="{{ route('admin.pengaduan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pengaduan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div class="relative">
                <input type="text" placeholder="Cari pengaduan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b w-10 text-center">No</th>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Tgl Lapor</th>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Keterangan</th>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Pelapor (Admin)</th>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b">Petugas</th>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b text-center">Status</th>
                        <th class="py-3 px-4 bg-gray-50 font-semibold text-sm text-gray-600 border-b text-center">Aksi</th>
                    </tr>
                </thead>
               <tbody class="divide-y divide-gray-100">
                    @forelse($pengaduans as $index => $pengaduan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-sm text-gray-600 text-center">
                            {{ $pengaduans->firstItem() + $index }}
                        </td>
                        
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($pengaduan->tanggal_lapor)->format('d M Y') }}
                        </td>
                        
                        <td class="py-3 px-4 text-sm text-gray-800 font-medium truncate max-w-xs">
                            {{ $pengaduan->judul_pengaduan }}
                        </td>
                        
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $pengaduan->admin->name ?? '-' }}
                        </td>
                        
                        <td class="py-3 px-4 text-sm text-gray-600">
                            @if($pengaduan->petugas)
                                {{ $pengaduan->petugas->name }}
                            @else
                                <span class="italic text-gray-400">Belum ditugaskan</span>
                            @endif
                        </td>
                        
                        <td class="py-3 px-4 text-sm text-center">
                            @if($pengaduan->status == 'menunggu')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                            @elseif($pengaduan->status == 'ditugaskan')
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">Ditugaskan</span>
                            @elseif($pengaduan->status == 'diproses')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Diproses</span>
                            @elseif($pengaduan->status == 'selesai')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Selesai</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Dibatalkan</span>
                            @endif
                        </td>
                        
                        <td class="py-3 px-4 text-sm text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="#" class="text-amber-500 hover:text-amber-700" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-sm text-gray-500">
                            Belum ada data pengaduan yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 text-sm text-gray-500 flex justify-between items-center">
            <span>Menampilkan 1 sampai 2 dari 2 data</span>
            <div class="flex space-x-1">
                <button class="px-3 py-1 border border-gray-300 rounded text-gray-500 bg-gray-50 hover:bg-gray-100 disabled:opacity-50">Sebelumnnya</button>
                <button class="px-3 py-1 border border-blue-500 rounded text-white bg-blue-600">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded text-gray-500 bg-white hover:bg-gray-50 disabled:opacity-50">Selanjutnya</button>
            </div>
        </div>
    </div>

    <div id="importModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-xl transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Import Data Pengaduan</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Excel (.xlsx, .xls)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition bg-gray-50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Pilih file</span>
                                    <input id="file-upload" name="file_excel" type="file" class="sr-only" accept=".xlsx, .xls, .csv">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">Format yang didukung: XLSX, XLS</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">Import Sekarang</button>
                </div>
            </form>
        </div>
    </div>
@endsection