@extends('layout.layout')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
            <li><a href="{{ route('penugasan.index') }}" class="hover:text-blue-600 transition">Tugas Saya</a></li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium">Buat Laporan</li>
        </ol>
    </nav>

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Kirim Laporan Penugasan</h1>
        <p class="text-gray-600 mt-2">Unggah bukti pengerjaan dan berikan keterangan terkait tugas yang Anda selesaikan.</p>
    </div>

    <div class="bg-blue-50 rounded-xl border border-blue-100 p-5 mb-8">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-bold text-blue-900 uppercase">{{ $penugasan->kodetugas }}</h3>
                <p class="text-base font-semibold text-blue-800 mt-1">{{ $penugasan->tugas->nama_tugas ?? 'Nama Tugas Tidak Ditemukan' }}</p>
                <p class="text-sm text-blue-700 mt-1">Batas Waktu Lapor: <span class="font-bold text-red-600">{{ \Carbon\Carbon::parse($penugasan->batas_waktu_lapor)->format('d F Y') }}</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <input type="hidden" name="id_penugasan" value="{{ $penugasan->id }}">

            <div>
                <label for="teks_laporan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Catatan Laporan <span class="text-red-500">*</span></label>
                <textarea id="teks_laporan" name="teks_laporan" rows="5" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50 focus:bg-white resize-none" 
                    placeholder="Jelaskan secara singkat apa saja yang telah Anda kerjakan..."></textarea>
                @error('teks_laporan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File Bukti <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-500 mb-3">Anda bisa mengunggah lebih dari satu file (Gambar, PDF, Word, Excel). Maks 5MB per file.</p>
                
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition relative">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="files" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Pilih File</span>
                                <input id="files" name="files[]" type="file" class="sr-only" multiple required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                            </label>
                            <p class="pl-1">atau seret dan lepas di sini</p>
                        </div>
                        <p class="text-xs text-gray-500" id="file-info">Belum ada file yang dipilih</p>
                    </div>
                </div>
                @error('files.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('files')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="{{ route('penugasan.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('files').addEventListener('change', function(e) {
        var fileCount = e.target.files.length;
        var infoText = document.getElementById('file-info');
        if(fileCount > 0) {
            infoText.innerHTML = "<span class='font-bold text-blue-600'>" + fileCount + " file telah dipilih</span>";
        } else {
            infoText.textContent = "Belum ada file yang dipilih";
        }
    });
</script>
@endsection