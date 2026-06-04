<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tugas;
use App\Models\Penugasan;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK UTAMA
        $totalUser = User::where('role', '!=', 'superadmin')->count();
        $tugasAktif = Penugasan::count(); 
        $menungguReview = Laporan::where('status', 'menunggu')->count();
        $selesaiBulanIni = Laporan::whereMonth('created_at', Carbon::now()->month)->count();

        // 2. RADAR DEADLINE & TIMELINE
        $urgentTugas = Penugasan::with(['tugas'])
                            ->where('batas_waktu_lapor', '<=', Carbon::now()->addDays(3))
                            ->orderBy('batas_waktu_lapor', 'asc')
                            ->take(5)->get();

        $timelineTugas = Penugasan::with(['tugas'])
                              ->orderBy('batas_waktu_lapor', 'asc')
                              ->take(5)->get();

        $laporanBaru = Laporan::with(['user', 'tugas', 'penugasan'])
                              ->orderBy('created_at', 'desc')
                              ->take(5)->get();

        // 3. GRAFIK DONAT (Status Penugasan)
        $totalSelesai = Laporan::count(); // Atau tambahkan ->where('status', 'disetujui') jika ada
        $totalTerlambat = Penugasan::where('batas_waktu_lapor', '<', Carbon::now()->format('Y-m-d'))->count();
        
        // Menghitung Tugas yang Belum Ditugaskan (Asumsi menggunakan 'kodetugas')
        $tugasDitugaskan = Penugasan::pluck('kodetugas')->toArray();
        $belumDitugaskan = Tugas::whereNotIn('kodetugas', $tugasDitugaskan)->count();

        // 4. GRAFIK TREN KINERJA (6 Bulan Terakhir)
        $trendLabels = [];
        $trendMasuk = [];
        $trendSelesai = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trendLabels[] = $date->translatedFormat('M Y'); // Contoh: "Jun 2026"

            // Hitung Tugas Masuk berdasarkan tanggal_mulai di tabel Tugas
            $trendMasuk[] = Tugas::whereMonth('tanggal_mulai', $date->month)
                                 ->whereYear('tanggal_mulai', $date->year)
                                 ->count();

            // Hitung Tugas Selesai berdasarkan Laporan yang dibuat
            $trendSelesai[] = Laporan::whereMonth('created_at', $date->month)
                                     ->whereYear('created_at', $date->year)
                                     ->count();
        }

        return view('admin.dashboardadmin', compact(
            'totalUser', 'tugasAktif', 'menungguReview', 'selesaiBulanIni', 
            'urgentTugas', 'laporanBaru', 'timelineTugas',
            'totalSelesai', 'totalTerlambat', 'belumDitugaskan',
            'trendLabels', 'trendMasuk', 'trendSelesai'
        ));
    }
}