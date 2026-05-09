@extends('layout.layout')

@section('content')
<div class="max-w-5xl mx-auto flex flex-col h-[calc(100vh-140px)] bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    
    <div class="p-4 md:px-6 border-b bg-white flex justify-between items-center z-10 shadow-sm">
        <div class="flex flex-col">
            <h3 class="font-bold text-gray-900 text-lg">{{ $laporan->penugasan->tugas->nama_tugas ?? 'Diskusi Laporan' }}</h3>
            <p class="text-xs text-gray-500 font-medium">Kode Tugas: <span class="uppercase text-blue-600">{{ $laporan->penugasan->kodetugas }}</span></p>
        </div>

        <div class="flex items-center gap-3">
            @if($laporan->status == 'diajukan')
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 uppercase">Diajukan</span>
            @elseif($laporan->status == 'revisi')
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 uppercase animate-pulse">Revisi</span>
            @elseif($laporan->status == 'disetujui')
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 uppercase">Disetujui</span>
            @endif

            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                @if($laporan->status != 'disetujui')
                    @if($laporan->status != 'revisi')
                    <form action="{{ route('laporan.set_revision', $laporan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-lg border border-yellow-200 hover:bg-yellow-100 transition">
                            Minta Revisi
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('laporan.approve', $laporan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 shadow-sm transition">
                            Setujui
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50/50 space-y-6" id="chat-box">
        
        @php
            $isAdmin = Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin';
        @endphp
        <div class="flex {{ $isAdmin ? 'justify-start' : 'justify-end' }}">
            <div class="max-w-[90%] md:max-w-[75%]">
                <div class="flex items-center mb-1 mx-1 {{ $isAdmin ? '' : 'justify-end' }}">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        LAPORAN AWAL • {{ $laporan->created_at->format('H:i') }}
                    </span>
                </div>
                <div class="p-4 shadow-sm text-sm {{ $isAdmin ? 'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-tl-none' : 'bg-blue-600 text-white rounded-2xl rounded-tr-none' }}">
                    <p class="whitespace-pre-line leading-relaxed">{{ $laporan->teks_laporan }}</p>
                    
                    @php
                        $initialFiles = $laporan->files->filter(function($f) use ($laporan) {
                            return $f->created_at->format('Y-m-d H:i:s') === $laporan->created_at->format('Y-m-d H:i:s');
                        });
                    @endphp

                    @if($initialFiles->count() > 0)
                    <div class="mt-3 space-y-2 {{ $isAdmin ? 'border-gray-100' : 'border-blue-500' }} border-t pt-3">
                        @foreach($initialFiles as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center p-2.5 rounded-lg transition {{ $isAdmin ? 'bg-gray-50 hover:bg-blue-50 border border-gray-200 text-blue-600 group' : 'bg-blue-700 hover:bg-blue-800 text-white border border-blue-500' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span class="text-xs font-medium truncate {{ $isAdmin ? 'group-hover:text-blue-700 text-gray-700' : '' }}">{{ $file->file_name }}</span>
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @foreach($laporan->chats as $chat)
        <div class="flex {{ $chat->id_user == Auth::id() ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[90%] md:max-w-[75%]">
                <div class="flex items-center mb-1 mx-1 {{ $chat->id_user == Auth::id() ? 'justify-end' : '' }}">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        {{ $chat->user->name }} • {{ $chat->created_at->format('H:i') }}
                    </span>
                </div>
                <div class="p-4 shadow-sm text-sm {{ $chat->id_user == Auth::id() ? 'bg-blue-600 text-white rounded-2xl rounded-tr-none' : 'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-tl-none' }}">
                    <p class="whitespace-pre-line leading-relaxed">{{ $chat->pesan }}</p>
                    
                    @php
                        $chatFiles = $laporan->files->filter(function($f) use ($chat) {
                            return $f->created_at->format('Y-m-d H:i:s') === $chat->created_at->format('Y-m-d H:i:s');
                        });
                    @endphp

                    @if($chatFiles->count() > 0)
                    <div class="mt-3 space-y-2 {{ $chat->id_user == Auth::id() ? 'border-blue-500' : 'border-gray-100' }} border-t pt-3">
                        @foreach($chatFiles as $file)
                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center p-2.5 rounded-lg transition {{ $chat->id_user == Auth::id() ? 'bg-blue-700 hover:bg-blue-800 text-white border border-blue-500' : 'bg-gray-50 hover:bg-blue-50 border border-gray-200 text-blue-600 group' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span class="text-xs font-medium truncate {{ $chat->id_user != Auth::id() ? 'group-hover:text-blue-700 text-gray-700' : '' }}">{{ $file->file_name }}</span>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($laporan->status != 'disetujui')
    <div class="p-3 md:p-4 bg-white border-t border-gray-200">
        <form action="{{ route('laporan.chat.store', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-2 relative">
            @csrf
            
            <label class="cursor-pointer flex-shrink-0 w-11 h-11 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full hover:bg-blue-100 hover:text-blue-600 transition" title="Lampirkan File">
                <svg class="w-5 h-5 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                <input type="file" name="file_baru" class="hidden" id="file_input" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx" onchange="showFileName(this)">
            </label>

            <div class="flex-1 relative">
                <div id="file_indicator" class="hidden absolute -top-10 left-0 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-blue-200 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    <span id="file_name_display" class="truncate max-w-[150px] md:max-w-[300px]"></span>
                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700 ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <textarea name="pesan" rows="1" required placeholder="Ketik pesan balasan atau catatan revisi..." 
                    class="w-full bg-gray-100 border-transparent focus:border-blue-500 focus:bg-white focus:ring-1 rounded-2xl px-5 py-3 text-sm resize-none overflow-hidden" 
                    style="min-height: 44px;"></textarea>
            </div>

            <button type="submit" class="flex-shrink-0 w-11 h-11 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition shadow-md">
                <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
            </button>
        </form>
    </div>
    @else
    <div class="p-4 bg-green-50 border-t border-green-200 text-center flex flex-col items-center justify-center h-[73px]">
        <p class="text-sm font-bold text-green-700 uppercase tracking-widest flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Laporan Telah Disetujui
        </p>
    </div>
    @endif
</div>

<script>
    // Selalu gulir otomatis ke pesan chat paling bawah saat halaman dimuat
    var chatBox = document.getElementById("chat-box");
    chatBox.scrollTop = chatBox.scrollHeight;

    // Fungsi untuk menampilkan nama file yang akan diupload di atas kotak teks
    function showFileName(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file_name_display').textContent = input.files[0].name;
            document.getElementById('file_indicator').classList.remove('hidden');
        }
    }

    // Fungsi untuk membatalkan pilihan file lampiran
    function clearFile() {
        document.getElementById('file_input').value = "";
        document.getElementById('file_indicator').classList.add('hidden');
    }
</script>
@endsection