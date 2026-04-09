@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Tugas Baru</h2>
        <p class="text-gray-600 text-sm mt-1">Isi formulir di bawah ini untuk mendelegasikan tugas baru.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-4xl">
        <form action="{{ route('admin.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">Kode Tugas <span class="text-red-500">*</span></label>
                            
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="autoGenerateToggle" class="sr-only peer">
                                <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-2 text-xs font-medium text-gray-600">Isi Otomatis</span>
                            </label>
                        </div>
                        
                        <input type="text" name="kodetugas" id="kodetugas" required maxlength="10" placeholder="Contoh: TGS-001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 10 karakter.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tugas <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_tugas" required placeholder="Contoh: Pemeliharaan Server" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" required placeholder="Jelaskan detail tugas di sini..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Mulai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Selesai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Lampiran (Opsional)</label>
                        <input type="file" name="lampiran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.tugas.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">Simpan Tugas</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('autoGenerateToggle');
            const inputKodetugas = document.getElementById('kodetugas');

            function generateRandomCode() {
                const prefix = 'TGS';
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let randomPart = '';
                
                for (let i = 0; i < 5; i++) {
                    randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                return prefix + randomPart;
            }

            toggle.addEventListener('change', function() {
                if (this.checked) {
                    inputKodetugas.value = generateRandomCode();
                    inputKodetugas.setAttribute('readonly', true);
                    inputKodetugas.classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    inputKodetugas.value = '';
                    inputKodetugas.removeAttribute('readonly');
                    inputKodetugas.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }
            });
        });
    </script>
@endsection