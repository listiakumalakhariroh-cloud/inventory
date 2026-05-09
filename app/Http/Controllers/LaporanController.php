<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LaporanFile;
use App\Models\LaporanRevisiChat;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // Menampilkan daftar laporan (Admin melihat semua, Anggota melihat miliknya)
    public function index(Request $request)
    {
        // Mulai kueri dengan memuat relasi yang diperlukan
        $query = Laporan::with(['penugasan.tugas', 'penugasan.anggota.user']);

        // Jika bukan admin, hanya tampilkan laporan milik anggota tersebut
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            $query->whereHas('penugasan.anggota', function ($q) {
                $q->where('id_user', Auth::id());
            });
        }

        // Logika Filter Pencarian (Berdasarkan Nama Tugas atau Kode Tugas)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('penugasan.tugas', function ($q) use ($search) {
                $q->where('nama_tugas', 'like', "%{$search}%")
                    ->orWhere('kodetugas', 'like', "%{$search}%");
            });
        }

        // Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Urutkan berdasarkan yang terbaru diajukan
        $laporan = $query->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan', compact('laporan'));
    }

    // Detail Laporan (Halaman Chat & Riwayat File)
   // Menampilkan detail laporan dan halaman chat revisi untuk user
    public function show($id)
    {
        // Pastikan relasi yang dipanggil lengkap untuk memuat chat, file, dan data tugas
        $laporan = Laporan::with(['files', 'chats.user', 'penugasan.tugas', 'penugasan.anggota'])->findOrFail($id);

        // Jika user bukan admin, pastikan dia adalah anggota dari penugasan ini
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            $isMember = $laporan->penugasan->anggota->contains('id_user', Auth::id());
            if (!$isMember) {
                abort(403, 'Anda tidak memiliki akses ke laporan ini.');
            }
        }

        // Panggil view detaillaporan.blade.php
        return view('detaillaporan', compact('laporan'));
    }

    // Menampilkan halaman formulir
    public function create($id_penugasan)
    {
        $penugasan = Penugasan::with('tugas')->findOrFail($id_penugasan);
        return view('buatlaporan', compact('penugasan'));
    }

    // Menyimpan data laporan dari form
    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan' => 'required|exists:penugasan,id',
            'teks_laporan' => 'required',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        DB::transaction(function () use ($request) {
            // Buat atau update Laporan Utama
            $laporan = Laporan::updateOrCreate(
                ['id_penugasan' => $request->id_penugasan],
                ['status' => 'diajukan', 'teks_laporan' => $request->teks_laporan]
            );

            // Simpan setiap file yang diunggah
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('laporan_bukti', 'public');
                    LaporanFile::create([
                        'id_laporan' => $laporan->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            }
        });

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diajukan!');
    }

    // Mengirim Pesan Chat Revisi
    public function storeChat(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required',
            'file_baru' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $laporan = Laporan::findOrFail($id);
        
        // KUNCI WAKTU SAAT INI (Agar chat dan file punya detik yang 100% sama)
        $waktuSekarang = now();

        // 1. Simpan pesan chat DENGAN waktu yang dikunci
        $chat = LaporanRevisiChat::create([
            'id_laporan' => $id,
            'id_user' => Auth::id(),
            'pesan' => $request->pesan,
            'created_at' => $waktuSekarang,
            'updated_at' => $waktuSekarang,
        ]);

        // 2. Simpan file jika ada DENGAN waktu yang dikunci
        if ($request->hasFile('file_baru')) {
            $file = $request->file('file_baru');
            $path = $file->store('laporan_bukti', 'public');
            
            LaporanFile::create([
                'id_laporan' => $laporan->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'created_at' => $waktuSekarang,
                'updated_at' => $waktuSekarang,
            ]);
        }

        // 3. Update status jika yang kirim adalah anggota
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            $laporan->update(['status' => 'diajukan']);
        }

        return redirect()->back();
    }

    // Anggota mengunggah file tambahan saat revisi
    public function storeFile(Request $request, $id)
    {
        $request->validate([
            'file_baru' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $laporan = Laporan::findOrFail($id);

        $path = $request->file('file_baru')->store('laporan_bukti', 'public');
        LaporanFile::create([
            'id_laporan' => $laporan->id,
            'file_name' => $request->file('file_baru')->getClientOriginalName(),
            'file_path' => $path,
        ]);

        // Otomatis ubah status kembali ke 'diajukan' jika sebelumnya 'revisi'
        $laporan->update(['status' => 'diajukan']);

        return redirect()->back()->with('success', 'File perbaikan berhasil diunggah!');
    }

    // Admin mengubah status ke Revisi
    // Fungsi untuk mengubah status menjadi revisi
    public function setRevision($id)
    {
        $laporan = Laporan::findOrFail($id);
        
        // Ubah status menjadi revisi
        $laporan->update(['status' => 'revisi']);

        return redirect()->back()->with('success', 'Status laporan diubah menjadi Perlu Revisi.');
    }

    // Fungsi untuk menyetujui laporan
    public function approve($id)
    {
        $laporan = Laporan::findOrFail($id);
        
        // Ubah status menjadi disetujui
        $laporan->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Laporan berhasil disetujui.');
    }

    public function indexUser(Request $request)
    {
        // Mengambil data laporan yang penugasannya melibatkan user yang sedang login
        $laporans = Laporan::whereHas('penugasan.anggota', function ($query) {
            $query->where('id_user', Auth::id());
        })
            ->with(['penugasan.tugas']) // Memuat relasi tugas untuk menghemat query database (Eager Loading)
            ->orderBy('updated_at', 'desc') // Mengurutkan dari yang paling baru diupdate/dikirim
            ->get();

        // Mengirimkan variabel $laporans ke halaman laporanuser.blade.php
        return view('laporanuser', compact('laporans'));
    }
}
