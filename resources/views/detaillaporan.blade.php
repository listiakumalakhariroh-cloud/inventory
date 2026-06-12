@extends('layout.layout')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in">
    
    <div class="space-y-3 animate-slide-up">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <li>
                    <a href="{{ route('laporan.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                        </svg>
                        Riwayat Laporan
                    </a>
                </li>
                <li>
                    <svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </li>
                <li class="text-slate-800 font-bold">Rincian Berkas Pengajuan</li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <span class="text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded-md uppercase tracking-wider font-mono">
                    ID LAPORAN: #LPR-{{ $laporan->id }}
                </span>
                <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight mt-2 leading-tight">
                    {{ $laporan->penugasan->tugas->nama_tugas ?? 'Detail Laporan Penugasan' }}
                </h1>
                <p class="text-xs font-medium text-slate-400 mt-1">Diajukan ke database log sistem DPU Kabupaten Semarang.</p>
            </div>
            
            <div class="self-start md:self-auto shrink-0">
                @if($laporan->status === 'disetujui')
                    <span class="px-4 py-2 text-xs font-black text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl uppercase tracking-wider shadow-sm">VALID / DISETUJUI</span>
                @elseif($laporan->status === 'revisi')
                    <span class="px-4 py-2 text-xs font-black text-amber-700 bg-amber-50 border border-amber-100 rounded-xl uppercase tracking-wider shadow-sm animate-pulse">PERLU REVISI</span>
                @elseif($laporan->status === 'diajukan' || $laporan->status === 'menunggu')
                    <span class="px-4 py-2 text-xs font-black text-blue-700 bg-blue-50 border border-blue-100 rounded-xl uppercase tracking-wider shadow-sm">SEDANG DITINJAU</span>
                @else
                    <span class="px-4 py-2 text-xs font-black text-slate-600 bg-slate-50 border border-slate-200 rounded-xl uppercase tracking-wider shadow-sm">BELUM DIAJUKAN</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-2 space-y-6 animate-slide-up" style="animation-delay: 100ms;">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Uraian & Catatan Progress Kerja Agen
                </h3>
                <div class="text-xs font-medium text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/50 p-5 rounded-xl border border-slate-100 shadow-inner leading-relaxed">
                    {!! nl2br(e($laporan->teks_laporan)) !!}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Lampiran Dokumentasi / Berkas Pendukung Fisik
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($laporan->files ?? [] as $file)
                        <div class="flex items-center justify-between p-3.5 bg-white border border-slate-200/80 rounded-xl hover:border-blue-400 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0 font-mono text-[10px] font-black uppercase tracking-wider">
                                    {{ pathinfo($file->file_path, PATHINFO_EXTENSION) ?: 'DOC' }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors">
                                        {{ basename($file->file_path) }}
                                    </p>
                                    <p class="text-[9px] font-mono text-slate-400 uppercase mt-0.5">Berkas Lampiran</p>
                                </div>
                            </div>
                            
                            <a href="{{ asset('storage/' . $file->file_path) }}" download
                               class="p-2 bg-slate-50 group-hover:bg-slate-900 group-hover:text-white rounded-lg text-slate-400 transition-colors shadow-sm"
                               title="Unduh Berkas Lampiran Ini">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-1 sm:col-span-2 text-center py-6 text-slate-400 italic text-xs">
                            Tidak ada file lampiran berkas dukung digital dalam pengajuan laporan ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="space-y-6 animate-slide-up" style="animation-delay: 150ms;">
            
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kronologi Validasi
                </h3>
                
                <div class="space-y-4 text-xs font-medium">
                    <div class="space-y-1">
                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Tanggal Pengajuan Berkas</span>
                        <span class="text-slate-800 font-bold block bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg font-mono">
                            {{ \Carbon\Carbon::parse($laporan->created_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                        </span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Pembaruan Verifikasi Terakhir</span>
                        <span class="text-slate-800 font-bold block bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg font-mono">
                            {{ \Carbon\Carbon::parse($laporan->updated_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Rekomendasi Tindakan DSS
                </h3>

                @if($laporan->status === 'revisi')
                    <div class="bg-amber-50/50 border border-amber-100 p-4 rounded-xl text-center space-y-3 animate-pulse">
                        <p class="text-xs font-semibold text-amber-800 leading-normal">
                            Laporan membutuhkan revisi instan. Segera klik tautan di bawah untuk melakukan koreksi berkas penugasan.
                        </p>
                        <a href="{{ route('laporan.create', $laporan->id_penugasan) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-amber-500/10">
                            PERBAIKI DOKUMEN NOW
                        </a>
                    </div>
                @elseif($laporan->status === 'disetujui')
                    <div class="bg-emerald-50/40 border border-emerald-100 p-4 rounded-xl text-center space-y-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-xs font-black text-emerald-800 uppercase tracking-wider">ARSIP LAPORAN KUAT</p>
                        <p class="text-[11px] font-medium text-emerald-600/80 leading-normal">Pekerjaan divalidasi penuh. Tidak ada aksi lanjutan yang diperlukan dari akun Anda.</p>
                    </div>
                @else
                    <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl text-center space-y-2 shadow-inner">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-1 animate-spin-slow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800">Menunggu Antrean</p>
                        <p class="text-[11px] font-medium text-slate-400 leading-normal">Data laporan terkunci aman dalam antrean reviu berkas fisik DPU Kabupaten Semarang.</p>
                    </div>
                @endif
            </div>

        </div>
        
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-spin-slow { animation: spin 12s linear infinite; }
</style>
@endsection