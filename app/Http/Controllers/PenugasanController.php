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
            'anggota.*.id_user' => 'required|exists:users,nip',
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

        return view('detailpenugasanuser', compact('penugasan'));
    }

    public function showAdmin($id)
    {
        // Menghapus anggota.jabatan dari relasi untuk view admin
        $p = Penugasan::with(['tugas', 'admin', 'anggota.user'])->findOrFail($id);

        return view('admin.detailpenugasan', compact('p'));
    }

    public function indexUser()
    {
        $penugasan = Penugasan::whereHas('anggota', function ($query) {
            $query->where('id_user', Auth::id());
        })->with(['tugas', 'admin'])->orderBy('created_at', 'desc')->get();

        return view('penugasanuser', compact('penugasan'));
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
            'anggota.*.id_user' => 'required|exists:users,nip',
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

    /**
     * Download Template Penugasan (Format XLSX) dengan referensi Tugas dan User
     */
    public function template()
    {
        $spreadsheet = new Spreadsheet();

        // ==========================================
        // SHEET 1: TEMPLATE UTAMA (FORM INPUT)
        // ==========================================
        $sheetTemplate = $spreadsheet->getActiveSheet();
        $sheetTemplate->setTitle('Template Penugasan');

        // Set Header Sheet 1
        $sheetTemplate->setCellValue('A1', 'Kode Tugas');
        $sheetTemplate->setCellValue('B1', 'Batas Waktu Lapor (YYYY-MM-DD)');
        $sheetTemplate->setCellValue('C1', 'NIP Anggota (Pisahkan dengan koma)');

        // Set Contoh Data Sheet 1
        $sheetTemplate->setCellValue('A2', 'CONTOH-KODE-TGS');
        $sheetTemplate->setCellValue('B2', \Carbon\Carbon::now()->addDays(7)->format('Y-m-d'));
        $sheetTemplate->setCellValue('C2', '199001012024011001, 199001012024011002');

        // Style Header Sheet 1
        $sheetTemplate->getStyle('A1:C1')->getFont()->setBold(true);
        $sheetTemplate->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF0F0F0');

        foreach (range('A', 'C') as $columnID) {
            $sheetTemplate->getColumnDimension($columnID)->setAutoSize(true);
        }


        // ==========================================
        // SHEET 2: DAFTAR TUGAS (REFERENSI)
        // ==========================================
        $sheetTugas = $spreadsheet->createSheet();
        $sheetTugas->setTitle('Daftar Tugas');

        // Header Sheet 2
        $sheetTugas->setCellValue('A1', 'Kode Tugas');
        $sheetTugas->setCellValue('B1', 'Nama Tugas');
        $sheetTugas->setCellValue('C1', 'Deskripsi');

        // Style Header Sheet 2 (Warna Abu-Abu Berbeda)
        $sheetTugas->getStyle('A1:C1')->getFont()->setBold(true);
        $sheetTugas->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Mengambil data tugas aktif dari database
        $daftarTugas = Tugas::all();
        $rowTugas = 2;
        foreach ($daftarTugas as $tugas) {
            $sheetTugas->setCellValue('A' . $rowTugas, $tugas->kodetugas);
            $sheetTugas->setCellValue('B' . $rowTugas, $tugas->nama_tugas);
            $sheetTugas->setCellValue('C' . $rowTugas, $tugas->deskripsi);
            $rowTugas++;
        }

        foreach (range('A', 'C') as $columnID) {
            $sheetTugas->getColumnDimension($columnID)->setAutoSize(true);
        }


        // ==========================================
        // SHEET 3: DAFTAR USER (REFERENSI)
        // ==========================================
        $sheetUser = $spreadsheet->createSheet();
        $sheetUser->setTitle('Daftar User');

        // Header Sheet 3 (Hanya NIP, Nama, dan Email sesuai permintaan)
        $sheetUser->setCellValue('A1', 'NIP');
        $sheetUser->setCellValue('B1', 'Nama');
        $sheetUser->setCellValue('C1', 'Email');

        // Style Header Sheet 3
        $sheetUser->getStyle('A1:C1')->getFont()->setBold(true);
        $sheetUser->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Mengambil seluruh data user dari database
        $daftarUser = User::all();
        $rowUser = 2;
        foreach ($daftarUser as $user) {
            $sheetUser->setCellValue('A' . $rowUser, $user->nip);
            $sheetUser->setCellValue('B' . $rowUser, $user->name);
            $sheetUser->setCellValue('C' . $rowUser, $user->email);
            $rowUser++;
        }

        foreach (range('A', 'C') as $columnID) {
            $sheetUser->getColumnDimension($columnID)->setAutoSize(true);
        }


        // ==========================================
        // PROSES DOWNLOAD FILE
        // ==========================================
        // Kembalikan fokus ke Sheet 1 agar otomatis terbuka pertama kali
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Penugasan_Lengkap.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Fungsi pembantu untuk menstandarkan format tanggal Excel ke YYYY-MM-DD
     */
    private function formatTanggalMySQL($tanggal)
    {
        if (empty($tanggal)) return null;

        if (is_numeric($tanggal)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $tanggal = trim($tanggal);
        $formats = ['d/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d', 'Y/m/d', 'm/d/Y'];

        foreach ($formats as $format) {
            try {
                return \Carbon\Carbon::createFromFormat($format, $tanggal)->format('Y-m-d');
            } catch (\Exception $e) {
                continue; 
            }
        }

        try {
            return \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function importProcess(Request $request)
    {
        // Pastikan Anda menerima data array dari input form (sesuaikan dengan nama input di form HTML Anda)
        $penugasanData = $request->input('penugasan');

        if (!$penugasanData) {
            return redirect()->route('admin.penugasan.index')->with('error', 'Tidak ada data yang diproses.');
        }

        foreach ($penugasanData as $row) {
            // Abaikan jika kodetugas kosong
            if (empty($row['kodetugas'])) continue;

            // 1. Format tanggal menggunakan fungsi pembantu
            $batasWaktuLapor = $this->formatTanggalMySQL($row['batas_waktu_lapor']);

            // 2. Simpan Data Penugasan
            $penugasan = Penugasan::create([
                'kodetugas'         => $row['kodetugas'],
                'batas_waktu_lapor' => $batasWaktuLapor,
                'id_admin'          => \Illuminate\Support\Facades\Auth::user()->nip, // Admin yang mengimport
            ]);

            // 3. Proses NIP Anggota (jika ada)
            if (!empty($row['nip_anggota'])) {
                // Pecah string berdasarkan koma menjadi array
                $nipArray = explode(',', $row['nip_anggota']);

                foreach ($nipArray as $nip) {
                    // Hilangkan spasi berlebih sebelum/sesudah NIP
                    $nipBersih = trim($nip);

                    // Pastikan NIP tidak kosong sebelum menyimpan
                    if (!empty($nipBersih)) {
                        \App\Models\AnggotaPenugasan::create([
                            'id_penugasan' => $penugasan->id,
                            'id_user'      => $nipBersih,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.penugasan.index')->with('success', 'Data Penugasan dan Anggota berhasil diimport!');
    }
}