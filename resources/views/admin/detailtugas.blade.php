@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Tugas</h2>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap mengenai penugasan dan pratinjau lampiran.</p>
        </div>
        <a href="{{ route('admin.tugas.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-5xl mx-auto">
        <div class="border-b border-gray-200 pb-4 mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $tugas->nama_tugas }}</h3>
                <span class="inline-block mt-2 px-3 py-1 text-xs font-mono font-semibold rounded-md bg-blue-100 text-blue-700">
                    {{ $tugas->kodetugas }}
                </span>
            </div>
            
            @php
                $today = \Carbon\Carbon::today();
                $start = \Carbon\Carbon::parse($tugas->tanggal_mulai);
                $end = \Carbon\Carbon::parse($tugas->tanggal_selesai);
            @endphp
            
            @if ($today->lt($start))
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">Status: Mendatang</span>
            @elseif($today->gt($end))
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-red-100 text-red-700 whitespace-nowrap">Status: Selesai</span>
            @else
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-700 whitespace-nowrap">Status: Aktif</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Informasi Waktu</h4>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-50 rounded-lg mr-4">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Tanggal Mulai</p>
                            <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($tugas->tanggal_mulai)->translatedFormat('l, d F Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="p-2 bg-red-50 rounded-lg mr-4">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Tanggal Selesai</p>
                            <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($tugas->tanggal_selesai)->translatedFormat('l, d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mt-8 mb-4">Informasi Pendelegasi</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Dibuat Oleh</p>
                        <p class="text-sm font-medium text-gray-800">{{ $tugas->admin->name ?? 'Admin' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Dibuat</p>
                        <p class="text-sm font-medium text-gray-800">{{ $tugas->created_at->translatedFormat('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Deskripsi Tugas</h4>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap min-h-[160px]">{{ $tugas->deskripsi }}</div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8">
            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                Pratinjau Lampiran
            </h4>
            
            @if($tugas->lampiran)
                @php
                    $extension = strtolower(pathinfo($tugas->lampiran, PATHINFO_EXTENSION));
                    $fileUrl = Storage::url($tugas->lampiran);
                    $fullUrl = url($fileUrl);
                @endphp

                <div class="mb-4 flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <span class="text-xs font-bold uppercase bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $extension }}</span>
                        <span class="ml-3 text-sm text-gray-600 truncate max-w-xs md:max-w-md">{{ basename($tugas->lampiran) }}</span>
                    </div>
                    <a href="{{ $fileUrl }}" target="_blank" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh File
                    </a>
                </div>

                <div class="bg-gray-100 rounded-lg border border-gray-300 p-2 overflow-hidden flex justify-center items-center min-h-[300px]">
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                        <img src="{{ $fileUrl }}" alt="Lampiran Tugas" class="max-w-full max-h-[800px] object-contain rounded-md shadow-sm">
                    
                    @elseif($extension === 'pdf')
                        <iframe src="{{ $fileUrl }}" class="w-full h-[700px] rounded-md border-0 bg-white" title="Pratinjau PDF"></iframe>
                        
                    @elseif(in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
                        <div class="w-full h-full flex flex-col">
                            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($fullUrl) }}" class="w-full h-[700px] rounded-md border-0 bg-white" title="Pratinjau Dokumen Office"></iframe>
                            <p class="text-xs text-amber-600 mt-3 text-center bg-amber-50 p-2 rounded">
                                *Pratinjau format Word/Excel/PowerPoint menggunakan layanan publik Microsoft. Jika file tidak muncul (contoh: karena Anda menggunakan localhost), silakan klik tombol Unduh di atas.
                            </p>
                        </div>
                        
                    @elseif($extension === 'txt')
                        <iframe src="{{ $fileUrl }}" class="w-full h-[500px] rounded-md border-0 bg-white p-4" title="Pratinjau Text"></iframe>

                    @else
                        <div class="py-16 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h5 class="text-gray-700 font-medium mb-1">Pratinjau Tidak Tersedia</h5>
                            <p class="text-gray-500 text-sm">Browser tidak dapat menampilkan pratinjau langsung untuk format file <b>.{{ $extension }}</b>.</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-lg border border-dashed border-gray-300 text-center flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">Tidak ada file lampiran yang disematkan pada tugas ini.</p>
                </div>
            @endif
        </div>

        <div class="border-t border-gray-200 pt-6 mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.tugas.edit', $tugas->kodetugas) }}" class="px-5 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium text-sm shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Tugas Ini
            </a>
        </div>
    </div>
@endsection