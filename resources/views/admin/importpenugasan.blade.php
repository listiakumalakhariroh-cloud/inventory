@php
    $tugasDict = \App\Models\Tugas::all()->keyBy('kodetugas');
    // Hapus kondisi where('role', '!=', 'admin')
    $userDict = \App\Models\User::all()->keyBy('nip');
@endphp

<div id="importModalPenugasan"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-6xl max-h-[90vh] flex flex-col overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Import Penugasan dari Excel</h3>
            <button type="button" onclick="closeImportModal()"
                class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-grow relative">

            <div id="dragDropAreaPenugasan"
                class="border-2 border-dashed border-blue-300 rounded-lg p-12 text-center hover:bg-blue-50 transition cursor-pointer relative">
                <input type="file" id="excelFilePenugasan" accept=".xlsx, .xls"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <svg class="mx-auto h-12 w-12 text-blue-400 mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                    </path>
                </svg>
                <p class="text-gray-600 font-medium">Klik atau Drag & Drop file Excel Template Penugasan di sini</p>
                <p class="text-sm text-gray-400 mt-1">Hanya mendukung format .xlsx atau .xls</p>
            </div>

            <form id="importFormPenugasan" action="{{ route('admin.penugasan.importProcess') }}" method="POST"
                class="hidden">
                @csrf
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600 font-medium">Preview Data. Pastikan data tugas dan penerima sudah
                        sesuai sebelum di-import.</p>
                    <button type="button" onclick="resetImportPenugasan()"
                        class="text-sm text-red-500 hover:text-red-700 font-medium">Ganti File</button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Kode Tugas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Tugas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Penerima (NIP & Nama)</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Batas Waktu Lapor</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="previewTableBodyPenugasan" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Proses
                        Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="detailPreviewModal"
    class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-gray-900 bg-opacity-60 transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-11/12 max-w-2xl overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-blue-50">
            <h3 class="text-lg font-bold text-blue-800">Detail Rencana Penugasan</h3>
            <button type="button" onclick="closeDetailModal()"
                class="text-blue-400 hover:text-blue-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Tugas</h4>
                    <div class="space-y-3 text-sm">
                        <div><span class="text-gray-500 block text-xs">Kode Tugas</span><span id="dtlKode"
                                class="font-mono font-semibold text-blue-600"></span></div>
                        <div><span class="text-gray-500 block text-xs">Nama Tugas</span><span id="dtlNamaTugas"
                                class="font-semibold text-gray-800"></span></div>
                        <div><span class="text-gray-500 block text-xs">Keterangan / Deskripsi</span>
                            <p id="dtlDeskripsi"
                                class="text-gray-700 mt-1 bg-gray-50 p-2 rounded border border-gray-100 text-xs"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><span class="text-gray-500 block text-xs">Tgl Mulai</span><span id="dtlTglMulai"
                                    class="text-gray-800 font-medium"></span></div>
                            <div><span class="text-gray-500 block text-xs">Tgl Selesai</span><span id="dtlTglSelesai"
                                    class="text-gray-800 font-medium"></span></div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Penerima & Batas
                    </h4>
                    <div class="space-y-3 text-sm">
                        <div><span class="text-gray-500 block text-xs">NIP Penerima</span><span id="dtlUserId"
                                class="font-mono text-gray-800 whitespace-pre-wrap"></span></div>
                        <div><span class="text-gray-500 block text-xs">Nama Lengkap</span><span id="dtlUserName"
                                class="font-bold text-gray-800 whitespace-pre-wrap"></span></div>
                        <div><span class="text-gray-500 block text-xs">Role</span><span id="dtlUserRole"
                                class="inline-block bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-semibold capitalize mt-1"></span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <span class="text-red-500 block text-xs font-bold mb-1">Batas Waktu Pengumpulan
                                (Lapor)</span>
                            <span id="dtlBatasWaktu"
                                class="text-red-700 font-bold text-lg bg-red-50 px-3 py-1 rounded inline-block border border-red-100"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-right">
            <button type="button" onclick="closeDetailModal()"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-medium text-sm">Tutup
                Preview</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    // Menyimpan data dari server ke variabel JS
    const dictTugas = @json($tugasDict);
    const dictUser = @json($userDict);

    // Menyimpan data baris untuk keperluan popup
    let previewRowData = [];

    const modalPenugasan = document.getElementById('importModalPenugasan');
    const dragDropArea = document.getElementById('dragDropAreaPenugasan');
    const importForm = document.getElementById('importFormPenugasan');
    const fileInput = document.getElementById('excelFilePenugasan');
    const tableBody = document.getElementById('previewTableBodyPenugasan');
    const detailModal = document.getElementById('detailPreviewModal');

    function openImportModal() {
        modalPenugasan.classList.remove('hidden');
        resetImportPenugasan();
    }

    function closeImportModal() {
        modalPenugasan.classList.add('hidden');
    }

    function resetImportPenugasan() {
        fileInput.value = '';
        dragDropArea.classList.remove('hidden');
        importForm.classList.add('hidden');
        tableBody.innerHTML = '';
        previewRowData = [];
    }

    // Drag & Drop Handlers
    dragDropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragDropArea.classList.add('bg-blue-50', 'border-blue-500');
    });
    dragDropArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dragDropArea.classList.remove('bg-blue-50', 'border-blue-500');
    });
    dragDropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dragDropArea.classList.remove('bg-blue-50', 'border-blue-500');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            processExcel(e.dataTransfer.files[0]);
        }
    });
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            processExcel(e.target.files[0]);
        }
    });

    // Proses File Excel
    function processExcel(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {
                type: 'array',
                cellDates: true
            });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const json = XLSX.utils.sheet_to_json(firstSheet, {
                raw: false,
                dateNF: 'yyyy-mm-dd'
            });
            renderPreview(json);
        };
        reader.readAsArrayBuffer(file);
    }

    // Render Tabel Preview
    function renderPreview(data) {
        dragDropArea.classList.add('hidden');
        importForm.classList.remove('hidden');
        tableBody.innerHTML = '';
        previewRowData = [];

        data.forEach((row, index) => {
            // Ambil kolom excel (Toleransi nama kolom)
            const kodeTugas = row['Kode Tugas'] || '';
            const nipAnggota = row['NIP Anggota (Pisahkan dengan koma)'] || row['NIP Anggota'] || '';
            const batasWaktu = row['Batas Waktu Lapor (YYYY-MM-DD)'] || row['Batas Waktu Lapor'] || row[
                'Batas Waktu'] || '';

            // Cari detail tugas dari dict
            const dtTugas = dictTugas[kodeTugas];
            const namaTugas = dtTugas ? dtTugas.nama_tugas :
                '<span class="text-red-500 font-bold">Kode Tugas Tidak Valid</span>';

            // Logika untuk menangani NIP multiple dengan koma
            let arrayNip = nipAnggota ? String(nipAnggota).split(',').map(n => n.trim()) : [];
            let namaUserList = [];

            arrayNip.forEach(nip => {
                if (dictUser[nip]) {
                    namaUserList.push(dictUser[nip].name);
                } else {
                    namaUserList.push(`<span class="text-red-500 font-bold">NIP ${nip} Invalid</span>`);
                }
            });
            const namaUser = namaUserList.length > 0 ? namaUserList.join('<br>') :
                '<span class="text-red-500 font-bold">NIP Kosong</span>';

            // Simpan ke array global untuk popup detail
            previewRowData[index] = {
                kode: kodeTugas,
                id_user: arrayNip.join(',\n'),
                batas: batasWaktu,
                tugas: dtTugas || null,
                user: arrayNip.length === 1 && dictUser[arrayNip[0]] ? dictUser[arrayNip[0]] : {
                    name: namaUserList.join(',\n').replace(/<br>/g, ',\n'),
                    role: 'Multiple User'
                }
            };

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-4 py-2 align-top">
                    <input type="text" name="penugasan[${index}][kodetugas]" value="${kodeTugas}" readonly class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 font-mono text-blue-600 font-semibold focus:outline-none">
                </td>
                <td class="px-4 py-2 text-xs text-gray-800 align-top">${namaTugas}</td>
                <td class="px-4 py-2 align-top">
                    <div class="flex flex-col space-y-1">
                        <input type="text" name="penugasan[${index}][nip_anggota]" value="${nipAnggota}" readonly class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 focus:outline-none">
                        <span class="text-xs text-gray-800 font-medium whitespace-normal mt-1 leading-relaxed">${namaUser}</span>
                    </div>
                </td>
                <td class="px-4 py-2 align-top">
                    <input type="date" name="penugasan[${index}][batas_waktu_lapor]" value="${batasWaktu}" class="w-32 text-xs border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500">
                </td>
                <td class="px-4 py-2 text-center align-top">
                    <button type="button" onclick="openDetailModal(${index})" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-md font-semibold transition inline-flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Detail
                    </button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    // Modal Popup Detail Functions
    function openDetailModal(index) {
        const data = previewRowData[index];

        // Isi info Tugas
        document.getElementById('dtlKode').textContent = data.kode;
        document.getElementById('dtlNamaTugas').innerHTML = data.tugas ? data.tugas.nama_tugas :
            '<span class="text-red-500">Tidak Valid</span>';
        document.getElementById('dtlDeskripsi').textContent = data.tugas ? data.tugas.deskripsi : '-';
        document.getElementById('dtlTglMulai').textContent = data.tugas ? (data.tugas.tanggal_mulai || '-') : '-';
        document.getElementById('dtlTglSelesai').textContent = data.tugas ? (data.tugas.tanggal_selesai || '-') : '-';

        // Isi info User
        document.getElementById('dtlUserId').textContent = data.id_user;
        document.getElementById('dtlUserName').innerHTML = data.user ? data.user.name :
            '<span class="text-red-500">Tidak Ditemukan</span>';
        document.getElementById('dtlUserRole').textContent = data.user ? data.user.role : '-';

        // Batas Waktu
        document.getElementById('dtlBatasWaktu').textContent = data.batas || '-';

        // Tampilkan popup
        detailModal.classList.remove('hidden');
    }

    function closeDetailModal() {
        detailModal.classList.add('hidden');
    }
</script>
