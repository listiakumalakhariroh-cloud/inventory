@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Penugasan Baru</h2>
        <p class="text-gray-600 text-sm mt-1">Ikuti langkah-langkah di bawah ini untuk mendelegasikan tugas.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-4xl">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center mb-8 border-b pb-4">
            <div class="flex items-center text-blue-600" id="indicator-step-1">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 font-bold">1</div>
                <span class="ml-2 font-medium">Informasi Tugas</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-200 mx-4"></div>
            <div class="flex items-center text-gray-400" id="indicator-step-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 font-bold">2</div>
                <span class="ml-2 font-medium">Pengaturan & Anggota</span>
            </div>
        </div>

        <form action="{{ route('admin.penugasan.store') }}" method="POST" id="penugasanForm">
            @csrf
            
            <div id="step-1" class="block">
                <div class="mb-6 max-w-xl">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tugas <span class="text-red-500">*</span></label>
                    <select name="kodetugas" id="kodetugas" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="" disabled selected>-- Pilih Tugas yang Akan Didelegasikan --</option>
                        @foreach($tugas as $t)
                            <option value="{{ $t->kodetugas }}">{{ $t->kodetugas }} - {{ $t->nama_tugas }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Pilih salah satu tugas dari daftar master tugas yang tersedia.</p>
                </div>

                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.penugasan.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</a>
                    <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm flex items-center">
                        Selanjutnya 
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <div id="step-2" class="hidden">
                
                <div class="mb-6 bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                    <label class="block text-sm font-medium text-gray-800 mb-2">Batas Waktu Lapor <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-600 mb-3">Tentukan batas waktu lapor. Tanggal ini berlaku sama untuk <b>semua anggota</b> yang ditambahkan di bawah.</p>
                    <input type="date" name="batas_waktu_lapor" id="batas_waktu_lapor" required class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    
                    <div class="border-r md:pr-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari & Tambah Anggota</label>
                        <div class="relative mb-4">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="searchInput" onkeyup="filterUsers()" placeholder="Ketik nama user..." class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden h-64 overflow-y-auto">
                            <ul id="userList" class="divide-y divide-gray-200">
                                </ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Anggota Terpilih <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-4">Tentukan jabatan masing-masing anggota untuk penugasan ini.</p>
                        
                        <div id="selectedMembersContainer" class="space-y-3">
                            <div id="emptyState" class="text-center py-8 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-gray-400 text-sm">
                                Belum ada anggota yang dipilih.<br>Silakan cari dan klik ikon (+) di sebelah kiri.
                            </div>
                            </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 flex justify-between items-center">
                    <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali
                    </button>
                    <button type="button" onclick="submitForm()" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Penugasan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const users = @json($users);
        const jabatans = @json($jabatans);
        let selectedUsers = [];

        // Inisialisasi daftar pencarian saat halaman dimuat
        document.addEventListener("DOMContentLoaded", () => {
            renderUserList(users);
        });

        // ====== NAVIGASI PROGRESSIVE FORM ======
        function goToStep(step) {
            const kodetugas = document.getElementById('kodetugas').value;

            // Validasi Step 1: Pastikan tugas sudah dipilih sebelum pindah ke Step 2
            if (step === 2) {
                if (!kodetugas) {
                    alert("Mohon pilih tugas terlebih dahulu sebelum melanjutkan!");
                    return;
                }
            }

            if (step === 1) {
                document.getElementById('step-1').classList.remove('hidden');
                document.getElementById('step-2').classList.add('hidden');
                
                document.getElementById('indicator-step-1').classList.replace('text-gray-400', 'text-blue-600');
                document.getElementById('indicator-step-1').querySelector('div').classList.replace('bg-gray-100', 'bg-blue-100');
                
                document.getElementById('indicator-step-2').classList.replace('text-blue-600', 'text-gray-400');
                document.getElementById('indicator-step-2').querySelector('div').classList.replace('bg-blue-100', 'bg-gray-100');
            } else if (step === 2) {
                document.getElementById('step-1').classList.add('hidden');
                document.getElementById('step-2').classList.remove('hidden');

                document.getElementById('indicator-step-1').classList.replace('text-blue-600', 'text-gray-400');
                document.getElementById('indicator-step-1').querySelector('div').classList.replace('bg-blue-100', 'bg-gray-100');
                
                document.getElementById('indicator-step-2').classList.replace('text-gray-400', 'text-blue-600');
                document.getElementById('indicator-step-2').querySelector('div').classList.replace('bg-gray-100', 'bg-blue-100');
            }
        }

        // ====== FITUR PENCARIAN & DAFTAR USER ======
        function filterUsers() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const filtered = users.filter(user => user.name.toLowerCase().includes(query));
            renderUserList(filtered);
        }

        function renderUserList(userArray) {
            const list = document.getElementById('userList');
            list.innerHTML = '';

            if (userArray.length === 0) {
                list.innerHTML = '<li class="p-3 text-sm text-gray-500 text-center">Data user tidak ditemukan.</li>';
                return;
            }

            userArray.forEach(user => {
                const isSelected = selectedUsers.some(su => su.id === user.id);
                
                const li = document.createElement('li');
                li.className = "flex items-center justify-between p-3 hover:bg-gray-100 transition";
                li.innerHTML = `
                    <div>
                        <p class="text-sm font-medium text-gray-800">${user.name}</p>
                        <p class="text-xs text-gray-500">${user.email}</p>
                    </div>
                    <button type="button" onclick="addMember(${user.id}, '${user.name.replace(/'/g, "\\'")}')" 
                        class="p-1.5 rounded-full ${isSelected ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-blue-100 text-blue-600 hover:bg-blue-200'} transition"
                        ${isSelected ? 'disabled' : ''}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                `;
                list.appendChild(li);
            });
        }

        // ====== MANAJEMEN ANGGOTA TERPILIH ======
        function addMember(id, name) {
            if (selectedUsers.some(user => user.id === id)) return;
            selectedUsers.push({ id: id, name: name, id_jabatan: '' });
            renderSelectedMembers();
            filterUsers();
        }

        function removeMember(id) {
            selectedUsers = selectedUsers.filter(user => user.id !== id);
            renderSelectedMembers();
            filterUsers();
        }

        function renderSelectedMembers() {
            const container = document.getElementById('selectedMembersContainer');
            const emptyState = document.getElementById('emptyState');

            Array.from(container.children).forEach(child => {
                if(child.id !== 'emptyState') child.remove();
            });

            if (selectedUsers.length === 0) {
                emptyState.style.display = 'block';
                return;
            } else {
                emptyState.style.display = 'none';
            }

            let jabatanOptions = `<option value="" disabled selected>-- Pilih Jabatan --</option>`;
            jabatans.forEach(jabatan => {
                jabatanOptions += `<option value="${jabatan.id}">${jabatan.nama_jabatan}</option>`;
            });

            selectedUsers.forEach((user, index) => {
                const div = document.createElement('div');
                div.className = "flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-white shadow-sm";
                
                div.innerHTML = `
                    <div class="flex-1 mr-4">
                        <p class="text-sm font-medium text-gray-800 mb-1">${user.name}</p>
                        <input type="hidden" name="anggota[${index}][id_user]" value="${user.id}">
                        <select name="anggota[${index}][id_jabatan]" required class="w-full text-sm px-2 py-1.5 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                            ${jabatanOptions}
                        </select>
                    </div>
                    <button type="button" onclick="removeMember(${user.id})" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        // ====== SUBMIT DATA ======
        function submitForm() {
            const batasWaktu = document.getElementById('batas_waktu_lapor').value;

            // Validasi Input Tanggal
            if (!batasWaktu) {
                alert('Mohon tentukan Batas Waktu Lapor terlebih dahulu!');
                document.getElementById('batas_waktu_lapor').focus();
                return;
            }

            // Validasi Daftar Anggota
            if (selectedUsers.length === 0) {
                alert('Peringatan: Anda belum menambahkan satupun anggota ke dalam tugas ini!');
                return;
            }
            
            // Eksekusi Form jika semua HTML5 validation (seperti select jabatan) terpenuhi
            const form = document.getElementById('penugasanForm');
            if (form.reportValidity()) {
                form.submit();
            }
        }
    </script>
@endsection