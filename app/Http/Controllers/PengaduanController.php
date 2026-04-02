<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        // Cek hak akses (hanya admin/superadmin)
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        // Ambil data pengaduan beserta relasi user (admin dan petugas)
        // latest() = urutkan dari yang terbaru
        // paginate(10) = tampilkan maksimal 10 data per halaman
        $pengaduans = Pengaduan::with(['admin', 'petugas'])->latest()->paginate(10);
        
        return view('admin.manajemenpengaduan', compact('pengaduans'));
    }
    
    // Menampilkan halaman form tambah pengaduan
    public function create()
    {
        // Mengambil seluruh user yang terdaftar di database 
        // (Termasuk admin, superadmin, dan user biasa)
        $petugas = User::all(); 
        
        return view('admin.tambahpengaduan', compact('petugas'));
    }

    // Memproses penyimpanan data ke database
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul_pengaduan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_lapor' => 'required|date',
            'tanggal_mulai' => 'nullable|date', // Tambahan validasi tanggal mulai
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai', // Validasi tanggal selesai
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'petugas_id' => 'nullable|exists:users,id',
        ]);

        // 2. Proses Upload Foto (jika ada)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pengaduan', 'public');
        }

        // 3. Simpan ke Database
        Pengaduan::create([
            'judul_pengaduan' => $request->judul_pengaduan,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'tanggal_lapor' => $request->tanggal_lapor,
            'tanggal_mulai' => $request->tanggal_mulai,     // Simpan ke database
            'tanggal_selesai' => $request->tanggal_selesai, // Simpan ke database
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'admin_id' => Auth::id(), // Ini memastikan ID admin yang sedang login terekam otomatis
            'petugas_id' => $request->petugas_id,
            'status' => $request->petugas_id ? 'ditugaskan' : 'menunggu',
        ]);

        return redirect()->route('admin.pengaduan')->with('success', 'Data pengaduan berhasil ditambahkan!');
    }
}