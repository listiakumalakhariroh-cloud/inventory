@extends('layout.layout')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">
    
    <div class="space-y-3 animate-slide-up">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <li>
                    <a href="{{ route('penugasan.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Tugas Saya
                    </a>
                </li>
                <li>
                    <svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </li>
                <li class="text-slate-800 font-bold">Kirim Rekapitulasi</li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">Kirim Laporan Proyek</h1>
                <p class="text-xs font-medium text-slate-400 mt-1">Isi rincian capaian fisik dan lampirkan berkas bukti dokumentasi lapangan DPU.</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm animate-slide-up" style="animation-delay: 50ms;">
        <div class="flex items-start gap-4">
            <div class="bg-slate-900 p-3 rounded-xl text-yellow-400 shadow-sm shrink-0">
                <svg class="w-5 h-5 shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="min-w-0 space-y-1">
                <span class="font-mono font-bold text-blue-600 text-[11px] bg-blue-50 border border-blue-100 px-2 py-0.5 rounded shadow-inner">
                    {{ $penugasan->kodetugas }}
                </span>
                <h3 class="text-sm font-black text-slate-800 truncate mt-1">
                    {{ $penugasan->tugas->nama_tugas ?? 'Nama Tugas Tidak Ditemukan' }}
                </h3>
                <p class="text-[11px] font-medium text-slate-400">
                    Batas Waktu Pelaporan: <span class="font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100 font-mono text-xs">{{ \Carbon\Carbon::parse($penugasan->batas_waktu_lapor)->locale('id')->translatedFormat('d F Y') }}</span>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-slide-up" style="animation-delay: 100ms;">
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <input type="hidden" name="id_penugasan" value="{{ $penugasan->id }}">

            <div class="space-y-2">
                <label for="teks_laporan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Keterangan / Catatan Laporan <span class="text-red-500">*</span></label>
                <textarea id="teks_laporan" name="teks_laporan" rows="6" required
                    class="w-full px-4 py-3 text-xs font-medium text-slate-800 rounded-xl border border-slate-200 focus:bg-white bg-slate-50/50 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all outline-none resize-none leading-relaxed"
                    placeholder="Jelaskan secara logis, singkat, dan terstruktur terkait poin kemajuan fisik yang telah selesai dikerjakan..."></textarea>
                @error('teks_laporan')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Upload File Bukti Lapangan <span class="text-red-500">*</span></label>
                <p class="text-[11px] font-medium text-slate-400">Ekstensi dokumen yang didukung: Gambar (.jpg, .png), PDF, Word, atau Excel. Batas ukuran maks. 5MB per file.</p>
                
                <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-slate-200 bg-slate-50/50 rounded-2xl hover:bg-slate-50 hover:border-blue-500 transition-all cursor-pointer relative group">
                    <div class="space-y-2 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:scale-110 group-hover:text-blue-500 transition-all" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-xs text-slate-600 justify-center font-semibold">
                            <label for="files" class="relative cursor-pointer bg-white rounded-md text-blue-600 hover:text-blue-700 focus-within:outline-none">
                                <span>Pilih Dokumen</span>
                                <input id="files" name="files[]" type="file" class="sr-only" multiple required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                            </label>
                            <p class="pl-1 text-slate-400">atau seret dan lepas berkas ke sini</p>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" id="file-info">Belum ada file yang dipilih</p>
                    </div>
                </div>
                @error('files.*')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
                @error('files')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('penugasan.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                    BATALKAN
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md shadow-blue-500/10 transition-all duration-200 hover:-translate-y-0.5">
                    KIRIM DATA LAPORAN
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
            infoText.innerHTML = "<span class='font-black text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded uppercase tracking-wide'>" + fileCount + " berkas siap diunggah</span>";
        } else {
            infoText.textContent = "Belum ada file yang dipilih";
        }
    });
</script>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection