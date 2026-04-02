@extends('layout.layoutadmin')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Pengaduan Baru</h2>
        <p class="text-gray-600 text-sm mt-1">Isi formulir di bawah ini untuk mendaftarkan perbaikan baru.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-4xl">
        <form action="{{ route('admin.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lapor <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lapor" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul_pengaduan" required placeholder="Contoh: AC Ruang Rapat Mati" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" required placeholder="Jelaskan detail kerusakannya di sini..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai (Opsional)</label>
                            <input type="date" name="tanggal_mulai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (Opsional)</label>
                            <input type="date" name="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tugaskan Kepada (Opsional)</label>
                        <select name="petugas_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Petugas / Admin --</option>
                            @foreach($petugas as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Role: {{ ucfirst($p->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Bukti</label>
                        <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titik Lokasi Perbaikan</label>
                    <p class="text-xs text-gray-500 mb-2">Geser marker (pin biru) pada peta untuk menentukan titik koordinat lokasi.</p>
                    
                    <div id="map" class="h-64 w-full rounded-lg border-2 border-gray-300 mb-4 z-0"></div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Latitude</label>
                            <input type="text" id="lat" name="latitude" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg bg-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Longitude</label>
                            <input type="text" id="lng" name="longitude" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg bg-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.pengaduan') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">Simpan Pengaduan</button>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Koordinat default (Misal: Jawa Tengah)
            var defaultLat = -7.250445; 
            var defaultLng = 112.768845;

            // Inisialisasi Peta
            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Buat marker
            var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

            // Ambil elemen input
            var latInput = document.getElementById('lat');
            var lngInput = document.getElementById('lng');

            // Fungsi untuk mengupdate angka di kolom input
            function updateInputs(lat, lng) {
                latInput.value = lat.toFixed(8);
                lngInput.value = lng.toFixed(8);
            }

            // Set nilai awal
            updateInputs(defaultLat, defaultLng);

            // 1. JIKA MARKER DIGESER MANUAL DI PETA
            marker.on('dragend', function (e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            // 2. JIKA PETA DIKLIK
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // 3. JIKA ANGKA LATITUDE DIKETIK / DI-PASTE MANUAL
            latInput.addEventListener('input', function() {
                var newLat = parseFloat(this.value);
                var currentLng = parseFloat(lngInput.value);
                
                // Pastikan yang dimasukkan adalah angka valid
                if (!isNaN(newLat) && !isNaN(currentLng)) {
                    marker.setLatLng([newLat, currentLng]); // Geser pin merah
                    map.panTo([newLat, currentLng]);        // Pindahkan kamera peta
                }
            });

            // 4. JIKA ANGKA LONGITUDE DIKETIK / DI-PASTE MANUAL
            lngInput.addEventListener('input', function() {
                var currentLat = parseFloat(latInput.value);
                var newLng = parseFloat(this.value);
                
                // Pastikan yang dimasukkan adalah angka valid
                if (!isNaN(currentLat) && !isNaN(newLng)) {
                    marker.setLatLng([currentLat, newLng]); // Geser pin merah
                    map.panTo([currentLat, newLng]);        // Pindahkan kamera peta
                }
            });
        });
    </script>
@endsection