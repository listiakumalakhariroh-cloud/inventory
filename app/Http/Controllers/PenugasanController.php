<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\AnggotaPenugasan;
use App\Models\Tugas;
use App\Models\User;
// use App\Models\Jabatan; // Baris ini dihapus
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        // Menghapus anggota.jabatan dari eager loading
        $query = Penugasan::with(['tugas', 'admin', 'anggota.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tugas', function ($q) use ($search) {
                $q->where('kodetugas', 'like', "%{$search}%")
                    ->orWhere('nama_tugas', 'like', "%{$search}%");
            })->orWhereHas('anggota.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $penugasan = $query->orderBy('created_at', 'desc')->get();
        return view('admin.penugasan', compact('penugasan'));
    }

    public function create()
    {
        $tugas = Tugas::all();
        $users = User::where('role', 'user')->get();
        // $jabatans = Jabatan::all(); // Baris ini dihapus

        // Variabel jabatans dihapus dari compact
        return view('admin.tambahpenugasan', compact('tugas', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodetugas' => 'required|exists:tugas,kodetugas',
            'batas_waktu_lapor' => 'required|date',
            'anggota' => 'required|array|min:1',
            'anggota.*.id_user' => 'required|exists:users,id',
            // Validasi id_jabatan dihapus
        ]);

        DB::beginTransaction();
        try {
            $penugasan = Penugasan::create([
                'kodetugas' => $request->kodetugas,
                'id_admin' => Auth::id(),
                'batas_waktu_lapor' => $request->batas_waktu_lapor,
            ]);

            foreach ($request->anggota as $item) {
                AnggotaPenugasan::create([
                    'id_penugasan' => $penugasan->id,
                    'id_user' => $item['id_user'],
                    // id_jabatan dihapus dari proses penyimpanan
                ]);
            }

            DB::commit();
            return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkExisting($kodetugas)
    {
        // Menghapus relasi jabatan pada pengecekan data existing
        $penugasan = Penugasan::with(['anggota.user'])
            ->where('kodetugas', $kodetugas)
            ->first();

        if ($penugasan) {
            return response()->json(['exists' => true, 'data' => $penugasan]);
        }

        return response()->json(['exists' => false]);
    }

    public function show($id)
    {
        // Menghapus anggota.jabatan dari relasi yang diambil
        $p = Penugasan::with(['tugas', 'admin', 'anggota.user'])->findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            $isMember = $p->anggota->contains('id_user', Auth::id());
            if (!$isMember) {
                abort(403, 'Anda tidak memiliki akses ke detail penugasan ini.');
            }
        }

        return view('detailpenugasanuser', compact('p'));
    }

    public function showAdmin($id)
    {
        // Menghapus anggota.jabatan dari relasi untuk view admin
        $p = Penugasan::with(['tugas', 'admin', 'anggota.user'])->findOrFail($id);

        return view('admin.detailpenugasan', compact('p'));
    }

    public function indexUser()
    {
        $penugasans = Penugasan::whereHas('anggota', function ($query) {
            $query->where('id_user', Auth::id());
        })->with(['tugas', 'admin'])->orderBy('created_at', 'desc')->get();

        return view('penugasanuser', compact('penugasans'));
    }

    public function edit($id)
    {
        $penugasan = Penugasan::with('anggota')->findOrFail($id);
        $tugas = Tugas::all();
        $users = User::where('role', 'user')->get();
        // $jabatans = Jabatan::all(); // Dihapus

        return view('admin.tambahpenugasan', compact('penugasan', 'tugas', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'batas_waktu_lapor' => 'required|date',
            'anggota' => 'required|array|min:1',
            'anggota.*.id_user' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $penugasan = Penugasan::findOrFail($id);
            $penugasan->update([
                'batas_waktu_lapor' => $request->batas_waktu_lapor,
            ]);

            // Sinkronisasi anggota: Hapus yang lama dan tambah yang baru
            AnggotaPenugasan::where('id_penugasan', $id)->delete();
            foreach ($request->anggota as $item) {
                AnggotaPenugasan::create([
                    'id_penugasan' => $id,
                    'id_user' => $item['id_user'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $penugasan = Penugasan::findOrFail($id);
        $penugasan->delete();

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil dihapus!');
    }
}