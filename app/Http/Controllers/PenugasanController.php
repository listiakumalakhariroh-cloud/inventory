<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penugasan::with(['tugas', 'admin', 'penerima']);

        // Pencarian berdasarkan kode tugas atau nama tugas di tabel relasi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tugas', function ($q) use ($search) {
                $q->where('kodetugas', 'like', "%{$search}%")
                    ->orWhere('nama_tugas', 'like', "%{$search}%");
            })->orWhereHas('penerima', function ($q) use ($search) {
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
        return view('admin.tambahpenugasan', compact('tugas', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodetugas' => 'required|exists:tugas,kodetugas',
            'id_penerima' => 'required|exists:users,id',
            'batas_waktu_lapor' => 'required|date',
        ]);

        Penugasan::create([
            'kodetugas' => $request->kodetugas,
            'id_penerima' => $request->id_penerima,
            'batas_waktu_lapor' => $request->batas_waktu_lapor,
            'id_admin' => Auth::id(),
        ]);

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil dibuat!');
    }

    public function edit($id)
    {
        $p = Penugasan::findOrFail($id);
        $tugas = Tugas::all();
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.editpenugasan', compact('p', 'tugas', 'users'));
    }

    public function update(Request $request, $id)
    {
        $p = Penugasan::findOrFail($id);
        $request->validate([
            'kodetugas' => 'required|exists:tugas,kodetugas',
            'id_penerima' => 'required|exists:users,id',
            'batas_waktu_lapor' => 'required|date',
        ]);

        $p->update($request->all());
        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $p = Penugasan::findOrFail($id);
        $p->delete();
        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil dihapus!');
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();

        // --- SHEET 1: Import Penugasan (Sheet Utama) ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Penugasan');

        // Header Utama
        $sheet->setCellValue('A1', 'Kode Tugas');
        $sheet->setCellValue('B1', 'ID Penerima (User ID)');
        $sheet->setCellValue('C1', 'Batas Waktu Lapor (YYYY-MM-DD)');

        // Styling Header & Contoh Data Dummy
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'TGS001'); // Contoh Kode
        $sheet->setCellValue('B2', '2');      // Contoh ID User
        $sheet->setCellValue('C2', date('Y-m-d', strtotime('+7 days')));

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- SHEET 2: Data Tugas (Referensi Detail) ---
        $sheetTugas = $spreadsheet->createSheet();
        $sheetTugas->setTitle('Data Tugas');

        // Header Detail Tugas
        $headersTugas = ['Kode Tugas', 'Nama Tugas', 'Deskripsi', 'Tgl Mulai', 'Tgl Selesai'];
        $sheetTugas->fromArray($headersTugas, NULL, 'A1');
        $sheetTugas->getStyle('A1:E1')->getFont()->setBold(true);

        // Ambil data tugas dari database
        $tugas = \App\Models\Tugas::all();
        $rowTugas = 2;
        foreach ($tugas as $t) {
            $sheetTugas->setCellValue('A' . $rowTugas, $t->kodetugas);
            $sheetTugas->setCellValue('B' . $rowTugas, $t->nama_tugas);
            $sheetTugas->setCellValue('C' . $rowTugas, $t->deskripsi);
            $sheetTugas->setCellValue('D' . $rowTugas, $t->tanggal_mulai);
            $sheetTugas->setCellValue('E' . $rowTugas, $t->tanggal_selesai);
            $rowTugas++;
        }

        foreach (range('A', 'E') as $col) {
            $sheetTugas->getColumnDimension($col)->setAutoSize(true);
        }

        // --- SHEET 3: Data User (Referensi Role) ---
        $sheetUser = $spreadsheet->createSheet();
        $sheetUser->setTitle('Data User');

        // Header User dengan Role
        $headersUser = ['ID User', 'Nama User', 'Role'];
        $sheetUser->fromArray($headersUser, NULL, 'A1');
        $sheetUser->getStyle('A1:C1')->getFont()->setBold(true);

        // Ambil data user yang bukan admin
        $users = \App\Models\User::where('role', '!=', 'admin')->get();
        $rowUser = 2;
        foreach ($users as $u) {
            $sheetUser->setCellValue('A' . $rowUser, $u->id);
            $sheetUser->setCellValue('B' . $rowUser, $u->name);
            $sheetUser->setCellValue('C' . $rowUser, $u->role);
            $rowUser++;
        }

        foreach (range('A', 'C') as $col) {
            $sheetUser->getColumnDimension($col)->setAutoSize(true);
        }

        // Set aktif kembali ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Proses Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Penugasan_Lengkap_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function export()
    {
        $penugasan = Penugasan::with(['tugas', 'penerima', 'admin'])->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Kode Tugas', 'Nama Tugas', 'Penerima', 'Pemberi', 'Batas Lapor'];
        $sheet->fromArray($headers, NULL, 'A1');

        $row = 2;
        foreach ($penugasan as $p) {
            $sheet->setCellValue('A' . $row, $p->kodetugas);
            $sheet->setCellValue('B' . $row, $p->tugas->nama_tugas ?? '-');
            $sheet->setCellValue('C' . $row, $p->penerima->name ?? '-');
            $sheet->setCellValue('D' . $row, $p->admin->name ?? '-');
            $sheet->setCellValue('E' . $row, $p->batas_waktu_lapor);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Penugasan_' . date('Ymd') . '.xlsx';
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function importProcess(Request $request)
    {
        $data = $request->input('penugasan'); // Sesuai dengan struktur modal import
        if (!$data) return back()->with('error', 'Tidak ada data.');

        foreach ($data as $row) {
            if (empty($row['kodetugas'])) continue;

            Penugasan::create([
                'kodetugas' => $row['kodetugas'],
                'id_penerima' => $row['id_penerima'],
                'batas_waktu_lapor' => $row['batas_waktu_lapor'],
                'id_admin' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.penugasan.index')->with('success', 'Data penugasan berhasil diimport!');
    }
}
