@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Tugas</h2>
        <p class="text-gray-600 text-sm mt-1">Perbarui informasi tugas yang sudah ada.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-4xl">
        <form action="{{ route('admin.tugas.update', $tugas->kodetugas) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Tugas</label>
                        <input type="text" name="kodetugas" value="{{ $tugas->kodetugas }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Kode tugas bersifat permanen dan tidak dapat diubah.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tugas <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_tugas" value="{{ old('nama_tugas', $tugas->nama_tugas) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Mulai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" value="{{ \Carbon\Carbon::parse($tugas->tanggal_mulai)->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Selesai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" value="{{ \Carbon\Carbon::parse($tugas->tanggal_selesai)->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Lampiran Baru (Opsional)</label>
                        <input type="file" name="lampiran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Kosongkan jika tidak ingin mengubah file lampiran sebelumnya.</p>
                        
                        @if($tugas->lampiran)
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <span class="text-xs text-blue-700 font-medium">File saat ini terlampir</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.tugas.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection