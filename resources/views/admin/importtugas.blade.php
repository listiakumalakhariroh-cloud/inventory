<div id="importModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    
    <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-6xl max-h-[90vh] flex flex-col overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Import Tugas dari Excel</h3>
            <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-grow">
            
            <div id="dragDropArea" class="border-2 border-dashed border-blue-300 rounded-lg p-12 text-center hover:bg-blue-50 transition cursor-pointer relative">
                <input type="file" id="excelFile" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <svg class="mx-auto h-12 w-12 text-blue-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="text-gray-600 font-medium">Klik untuk memilih file atau Drag & Drop file Excel di sini</p>
                <p class="text-sm text-gray-400 mt-1">Format yang didukung: .xlsx, .xls</p>
            </div>

            <form id="importForm" action="{{ route('admin.tugas.importProcess') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600 font-medium">Preview Data Excel. Silakan lengkapi Kode Tugas dan Lampiran (jika ada).</p>
                    <button type="button" onclick="resetImport()" class="text-sm text-red-500 hover:text-red-700 font-medium">Ganti File</button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Kode Tugas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Tugas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Deskripsi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tgl Mulai</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tgl Selesai</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Lampiran (Opsional)</th>
                            </tr>
                        </thead>
                        <tbody id="previewTableBody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    const modal = document.getElementById('importModal');
    const dragDropArea = document.getElementById('dragDropArea');
    const importForm = document.getElementById('importForm');
    const fileInput = document.getElementById('excelFile');
    const tableBody = document.getElementById('previewTableBody');

    // Buka Modal
    function openImportModal() {
        modal.classList.remove('hidden');
        resetImport();
    }

    // Tutup Modal
    function closeImportModal() {
        modal.classList.add('hidden');
    }

    // Reset Form
    function resetImport() {
        fileInput.value = '';
        dragDropArea.classList.remove('hidden');
        importForm.classList.add('hidden');
        tableBody.innerHTML = '';
    }

    // Generate Kode Acak
    function generateKodeRow(index) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let randomPart = '';
        for (let i = 0; i < 5; i++) {
            randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(`kode_${index}`).value = 'TGS' + randomPart;
    }

    // Efek Drag & Drop
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
        if(e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            processExcel(e.dataTransfer.files[0]);
        }
    });
    fileInput.addEventListener('change', (e) => {
        if(e.target.files.length) {
            processExcel(e.target.files[0]);
        }
    });

    // Proses File Excel dengan SheetJS
    function processExcel(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array', cellDates: true});
            
            // Ambil sheet pertama
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            
            // Ubah ke JSON (Mulai dari baris ke-2 / header diabaikan jika formatnya raw array)
            const json = XLSX.utils.sheet_to_json(worksheet, {raw: false, dateNF: 'yyyy-mm-dd'});
            
            renderPreview(json);
        };
        reader.readAsArrayBuffer(file);
    }

    function renderPreview(data) {
        dragDropArea.classList.add('hidden');
        importForm.classList.remove('hidden');
        tableBody.innerHTML = '';

        data.forEach((row, index) => {
            // Mengambil nilai berdasarkan nama kolom pada Template (Sesuaikan dengan header template excel Anda)
            const namaTugas = row['Nama Tugas'] || '';
            const deskripsi = row['Deskripsi'] || '';
            const tglMulai = row['Tanggal Mulai (YYYY-MM-DD)'] || row['Tanggal Mulai'] || '';
            const tglSelesai = row['Tanggal Selesai (YYYY-MM-DD)'] || row['Tanggal Selesai'] || '';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-4 py-2">
                    <div class="flex items-center space-x-2">
                        <input type="text" name="tugas[${index}][kodetugas]" id="kode_${index}" maxlength="10" placeholder="Ketik/Isi Otomatis" class="w-28 text-xs border border-gray-300 rounded px-2 py-1">
                        <button type="button" onclick="generateKodeRow(${index})" class="text-xs bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded text-gray-700">Otomatis</button>
                    </div>
                </td>
                <td class="px-4 py-2">
                    <input type="text" name="tugas[${index}][nama_tugas]" value="${namaTugas}" required class="w-full text-xs border border-gray-300 rounded px-2 py-1">
                </td>
                <td class="px-4 py-2">
                    <input type="text" name="tugas[${index}][deskripsi]" value="${deskripsi}" required class="w-full text-xs border border-gray-300 rounded px-2 py-1">
                </td>
                <td class="px-4 py-2">
                    <input type="datetime-local" name="tugas[${index}][tanggal_mulai]" value="${tglMulai ? tglMulai + 'T00:00' : ''}" required class="w-32 text-xs border border-gray-300 rounded px-2 py-1">
                </td>
                <td class="px-4 py-2">
                    <input type="datetime-local" name="tugas[${index}][tanggal_selesai]" value="${tglSelesai ? tglSelesai + 'T00:00' : ''}" required class="w-32 text-xs border border-gray-300 rounded px-2 py-1">
                </td>
                <td class="px-4 py-2">
                    <input type="file" name="tugas[${index}][lampiran]" class="w-40 text-xs border border-gray-300 rounded px-2 py-1">
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }
</script>