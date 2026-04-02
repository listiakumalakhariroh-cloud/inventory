<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Tambahkan dua baris ini untuk fungsi Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with('admin')->orderBy('created_at', 'desc')->get();
        return view('admin.tugas', compact('tugas'));
    }

    /**
     * Download Template Tugas (Format XLSX)
     */
    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header
        $sheet->setCellValue('A1', 'Nama Tugas');
        $sheet->setCellValue('B1', 'Deskripsi');
        $sheet->setCellValue('C1', 'Tanggal Mulai (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'Tanggal Selesai (YYYY-MM-DD)');

        // Set Contoh Data
        $sheet->setCellValue('A2', 'Contoh: Pengecekan Server');
        $sheet->setCellValue('B2', 'Mengecek ketersediaan server mingguan yang ada di rak utama.');
        $sheet->setCellValue('C2', date('Y-m-d'));
        $sheet->setCellValue('D2', date('Y-m-d', strtotime('+7 days')));

        // Format Header agar tebal (Bold)
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Mengatur lebar kolom otomatis (Auto-size)
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Mengatur tinggi baris otomatis berdasarkan isi (Wrap Text)
        $sheet->getStyle('A1:D2')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:D2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Proses download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Tugas.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export Data Tugas ke Excel (Format XLSX)
     */
    public function export()
    {
        $tugas = Tugas::with('admin')->orderBy('created_at', 'desc')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header
        $headers = ['Kode Tugas', 'Nama Tugas', 'Deskripsi', 'Tanggal Mulai', 'Tanggal Selesai', 'Admin Pembuat', 'Status'];
        $columnLetter = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnLetter . '1', $header);
            $columnLetter++;
        }

        // Format Header (Bold & Background Abu-abu)
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFF0F0F0');

        // Isi Data
        $row = 2; // Mulai dari baris ke-2
        foreach ($tugas as $t) {
            $today = \Carbon\Carbon::today();
            $start = \Carbon\Carbon::parse($t->tanggal_mulai);
            $end = \Carbon\Carbon::parse($t->tanggal_selesai);
            
            if($today->lt($start)) { $status = 'Mendatang'; }
            elseif($today->gt($end)) { $status = 'Selesai'; }
            else { $status = 'Aktif'; }

            $sheet->setCellValue('A' . $row, $t->kodetugas);
            $sheet->setCellValue('B' . $row, $t->nama_tugas);
            $sheet->setCellValue('C' . $row, $t->deskripsi);
            $sheet->setCellValue('D' . $row, $t->tanggal_mulai);
            $sheet->setCellValue('E' . $row, $t->tanggal_selesai);
            $sheet->setCellValue('F' . $row, $t->admin->name ?? 'Admin');
            $sheet->setCellValue('G' . $row, $status);

            $row++;
        }

        // Mengatur lebar kolom otomatis (Auto-size) dari kolom A sampai G
        foreach (range('A', 'G') as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }

        // Mengatur tinggi baris otomatis (Wrap Text) untuk seluruh area data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:G' . $highestRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:G' . $highestRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Proses download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Tugas_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    
    // ... biarkan fungsi create, store, edit, update, destroy tetap ada di bawahnya ...
}