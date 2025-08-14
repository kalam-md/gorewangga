<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\UserController;
use App\Models\Lapangan;
use App\Models\Pemesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        // Hitung statistik dasar
        $totalUsers = User::where('role', 'penyewa')->count();
        $totalPemesanan = Pemesanan::count();
        $totalPendapatan = Pemesanan::where('status', 'sukses')
            ->whereHas('pembayaran', function($q) {
                $q->where('status', 'valid');
            })->sum('total_harga');
        $pemesananPending = Pemesanan::where('status', 'pending')->count();
        
        // Data untuk chart pemesanan per bulan (6 bulan terakhir)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Pemesanan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $chartData[] = [
                'label' => $month->format('M Y'),
                'value' => $count
            ];
        }
        
        // Data untuk chart lapangan (pemesanan per jenis lapangan)
        $fieldData = Pemesanan::join('lapangans', 'pemesanans.lapangan_id', '=', 'lapangans.id')
            ->selectRaw('lapangans.jenis, COUNT(*) as total')
            ->groupBy('lapangans.jenis')
            ->get();
        
        // Recent orders (5 terakhir)
        $recentOrders = Pemesanan::with(['user', 'lapangan', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik tambahan
        $stats = [
            'total_users' => $totalUsers,
            'total_pemesanan' => $totalPemesanan,
            'total_pendapatan' => $totalPendapatan,
            'pemesanan_pending' => $pemesananPending,
            'pemesanan_hari_ini' => Pemesanan::whereDate('tanggal_pemesanan', today())->count(),
            'pemesanan_bulan_ini' => Pemesanan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'lapangan_aktif' => Lapangan::where('is_active', true)->count(),
            'rata_rata_pemesanan' => $totalPemesanan > 0 ? round($totalPendapatan / $totalPemesanan, 0) : 0
        ];

        return view('dashboard.index', compact(
            'chartData', 
            'fieldData', 
            'recentOrders', 
            'stats'
        ));
    })->name('dashboard');

    Route::middleware('role:petugas')->group(function () {
        Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/{user}', [UserController::class, 'show'])->name('users.show');
    });

    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
    Route::get('/lapangan/create', [LapanganController::class, 'create'])->name('lapangan.create');
    Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');
    Route::get('/lapangan/{lapangan}', [LapanganController::class, 'show'])->name('lapangan.show');
    Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('lapangan.edit');
    Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('lapangan.update');
    Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal/{id}/toggle', [JadwalController::class, 'toggle'])->name('jadwal.toggle');
    Route::get('/jadwal/events', [JadwalController::class, 'getEvents'])->name('jadwal.events');

    Route::get('/pemesanan/check-jadwal', [PemesananController::class, 'checkJadwal'])
        ->name('pemesanan.check-jadwal');
    Route::resource('pemesanan', PemesananController::class);
    Route::post('/pemesanan/{pemesanan}/upload-bukti', [PemesananController::class, 'uploadBukti'])
        ->name('pemesanan.upload-bukti');
    Route::post('/pemesanan/{pemesanan}/validasi-pembayaran', [PemesananController::class, 'validasiPembayaran'])
        ->name('pemesanan.validasi-pembayaran')
        ->middleware('role:petugas');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/cetak-pdf/{id?}', [LaporanController::class, 'cetakPdf'])->name('cetak-pdf');
    });
});

Route::get('/public/jadwal/events', [JadwalController::class, 'getEvents'])->name('public.jadwal.events');