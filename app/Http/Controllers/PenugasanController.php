<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\AnggotaPenugasan;
use App\Models\Tugas;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        // Load relasi ke tabel pivot (anggota) beserta user dan jabatannya
        $query = Penugasan::with(['tugas', 'admin', 'anggota.user', 'anggota.jabatan']);

        // Pencarian berdasarkan kode tugas, nama tugas, atau nama anggota
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
        $users = User::where('role', '!=', 'admin')->get(); // Hanya user biasa
        $jabatans = Jabatan::all(); // Mengambil master data jabatan
        return view('admin.tambahpenugasan', compact('tugas', 'users', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodetugas' => 'required|exists:tugas,kodetugas',
            'batas_waktu_lapor' => 'required|date',
            // Validasi untuk input array anggota yang berisi id_user dan id_jabatan
            'anggota' => 'required|array|min:1',
            'anggota.*.id_user' => 'required|exists:users,id',
            'anggota.*.id_jabatan' => 'required|exists:jabatans,id',
        ]);

        // Menggunakan DB::transaction agar jika insert anggota gagal, penugasan tidak terbuat sebagian
        DB::transaction(function () use ($request) {
            $penugasan = Penugasan::create([
                'kodetugas' => $request->kodetugas,
                'batas_waktu_lapor' => $request->batas_waktu_lapor,
                'id_admin' => Auth::id(),
            ]);

            foreach ($request->anggota as $item) {
                AnggotaPenugasan::create([
                    'id_penugasan' => $penugasan->id,
                    'id_user' => $item['id_user'],
                    'id_jabatan' => $item['id_jabatan'],
                ]);
            }
        });

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil dibuat beserta anggotanya!');
    }

    public function edit($id)
    {
        $p = Penugasan::with('anggota')->findOrFail($id);
        $tugas = Tugas::all();
        $users = User::where('role', '!=', 'admin')->get();
        $jabatans = Jabatan::all();
        
        return view('admin.editpenugasan', compact('p', 'tugas', 'users', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $p = Penugasan::findOrFail($id);
        
        $request->validate([
            'kodetugas' => 'required|exists:tugas,kodetugas',
            'batas_waktu_lapor' => 'required|date',
            'anggota' => 'required|array|min:1',
            'anggota.*.id_user' => 'required|exists:users,id',
            'anggota.*.id_jabatan' => 'required|exists:jabatans,id',
        ]);

        DB::transaction(function () use ($request, $p) {
            // Update data utama penugasan
            $p->update([
                'kodetugas' => $request->kodetugas,
                'batas_waktu_lapor' => $request->batas_waktu_lapor,
            ]);

            // Hapus semua anggota lama untuk diganti dengan yang baru (Sync manual)
            $p->anggota()->delete();

            // Insert anggota yang baru dikirim dari form
            foreach ($request->anggota as $item) {
                AnggotaPenugasan::create([
                    'id_penugasan' => $p->id,
                    'id_user' => $item['id_user'],
                    'id_jabatan' => $item['id_jabatan'],
                ]);
            }
        });

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $p = Penugasan::findOrFail($id);
        $p->delete(); // Otomatis menghapus anggota_penugasans jika menggunakan onDelete('cascade') di migration
        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil dihapus!');
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();

        // --- SHEET 1: Import Penugasan ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Penugasan');
        $sheet->setCellValue('A1', 'Kode Tugas');
        $sheet->setCellValue('B1', 'Batas Waktu Lapor (YYYY-MM-DD)');
        $sheet->setCellValue('C1', 'ID User (Penerima)');
        $sheet->setCellValue('D1', 'ID Jabatan');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Data Dummy
        $sheet->setCellValue('A2', 'TGS001');
        $sheet->setCellValue('B2', date('Y-m-d', strtotime('+7 days')));
        $sheet->setCellValue('C2', '2'); 
        $sheet->setCellValue('D2', '1'); 
        
        $sheet->setCellValue('A3', 'TGS001'); // Masukkan kode tugas & waktu yang sama untuk anggota yang berbeda
        $sheet->setCellValue('B3', date('Y-m-d', strtotime('+7 days')));
        $sheet->setCellValue('C3', '3'); 
        $sheet->setCellValue('D3', '2'); 

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- SHEET 2: Data Tugas ---
        $sheetTugas = $spreadsheet->createSheet();
        $sheetTugas->setTitle('Data Tugas');
        $sheetTugas->fromArray(['Kode Tugas', 'Nama Tugas', 'Deskripsi'], NULL, 'A1');
        $sheetTugas->getStyle('A1:C1')->getFont()->setBold(true);

        $tugas = Tugas::all();
        $row = 2;
        foreach ($tugas as $t) {
            $sheetTugas->setCellValue('A' . $row, $t->kodetugas);
            $sheetTugas->setCellValue('B' . $row, $t->nama_tugas);
            $sheetTugas->setCellValue('C' . $row, $t->deskripsi);
            $row++;
        }
        foreach (range('A', 'C') as $col) $sheetTugas->getColumnDimension($col)->setAutoSize(true);

        // --- SHEET 3: Data User ---
        $sheetUser = $spreadsheet->createSheet();
        $sheetUser->setTitle('Data User');
        $sheetUser->fromArray(['ID User', 'Nama User', 'Role'], NULL, 'A1');
        $sheetUser->getStyle('A1:C1')->getFont()->setBold(true);

        $users = User::where('role', '!=', 'admin')->get();
        $row = 2;
        foreach ($users as $u) {
            $sheetUser->setCellValue('A' . $row, $u->id);
            $sheetUser->setCellValue('B' . $row, $u->name);
            $sheetUser->setCellValue('C' . $row, $u->role);
            $row++;
        }
        foreach (range('A', 'C') as $col) $sheetUser->getColumnDimension($col)->setAutoSize(true);

        // --- SHEET 4: Data Jabatan ---
        $sheetJabatan = $spreadsheet->createSheet();
        $sheetJabatan->setTitle('Data Jabatan');
        $sheetJabatan->fromArray(['ID Jabatan', 'Nama Jabatan'], NULL, 'A1');
        $sheetJabatan->getStyle('A1:B1')->getFont()->setBold(true);

        $jabatans = Jabatan::all();
        $row = 2;
        foreach ($jabatans as $j) {
            $sheetJabatan->setCellValue('A' . $row, $j->id);
            $sheetJabatan->setCellValue('B' . $row, $j->nama_jabatan);
            $row++;
        }
        foreach (range('A', 'B') as $col) $sheetJabatan->getColumnDimension($col)->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Penugasan_Lengkap_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function export()
    {
        $penugasan = Penugasan::with(['tugas', 'admin', 'anggota.user', 'anggota.jabatan'])->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Kode Tugas', 'Nama Tugas', 'Daftar Anggota & Jabatan', 'Pemberi Tugas', 'Batas Lapor'];
        $sheet->fromArray($headers, NULL, 'A1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2;
        foreach ($penugasan as $p) {
            // Menggabungkan nama semua anggota dan jabatannya ke dalam satu cell text
            $listAnggota = [];
            foreach ($p->anggota as $anggota) {
                $nama = $anggota->user->name ?? 'Unknown';
                $jabatan = $anggota->jabatan->nama_jabatan ?? 'Unknown';
                $listAnggota[] = "$nama ($jabatan)";
            }
            $stringAnggota = implode(", ", $listAnggota);

            $sheet->setCellValue('A' . $row, $p->kodetugas);
            $sheet->setCellValue('B' . $row, $p->tugas->nama_tugas ?? '-');
            $sheet->setCellValue('C' . $row, $stringAnggota);
            $sheet->setCellValue('D' . $row, $p->admin->name ?? '-');
            $sheet->setCellValue('E' . $row, $p->batas_waktu_lapor);
            $row++;
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Penugasan_' . date('Ymd') . '.xlsx';
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function importProcess(Request $request)
    {
        // Sesuaikan parameter form request ini jika di frontend menggunakan input name yang berbeda
        $data = $request->input('penugasan'); 
        if (!$data) return back()->with('error', 'Tidak ada data.');

        DB::transaction(function () use ($data) {
            $currentPenugasan = null;
            $lastRef = '';

            foreach ($data as $row) {
                if (empty($row['kodetugas']) || empty($row['id_user'])) continue;

                // Logika Referensi: Menyatukan baris excel yang memiliki Kodetugas dan Batas Lapor yang sama
                $ref = $row['kodetugas'] . '_' . $row['batas_waktu_lapor'];

                // Jika referensi beda dari baris sebelumnya, buat data Induk Penugasan baru
                if ($ref !== $lastRef) {
                    $currentPenugasan = Penugasan::create([
                        'kodetugas' => $row['kodetugas'],
                        'batas_waktu_lapor' => $row['batas_waktu_lapor'],
                        'id_admin' => Auth::id(),
                    ]);
                    $lastRef = $ref;
                }

                // Masukkan id_user dari baris excel tersebut ke dalam tabel pivot
                AnggotaPenugasan::create([
                    'id_penugasan' => $currentPenugasan->id,
                    'id_user'      => $row['id_user'],
                    'id_jabatan'   => $row['id_jabatan'],
                ]);
            }
        });

        return redirect()->route('admin.penugasan.index')->with('success', 'Data penugasan multi-anggota berhasil diimport!');
    }
}