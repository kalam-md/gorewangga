<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['lapangan', 'user', 'pembayaran'])
            ->orderBy('tanggal_pemesanan', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status pemesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_bayar')) {
            $query->whereHas('pembayaran', function($q) use ($request) {
                $q->where('status', $request->status_bayar);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pemesanan', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pemesanan', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan jenis lapangan
        if ($request->filled('jenis')) {
            $query->whereHas('lapangan', function($q) use ($request) {
                $q->where('jenis', $request->jenis);
            });
        }

        // Search berdasarkan nama penyewa atau lapangan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($subQ) use ($search) {
                    $subQ->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                })->orWhereHas('lapangan', function($subQ) use ($search) {
                    $subQ->where('nama_lapangan', 'like', "%{$search}%");
                });
            });
        }

        $pemesanans = $query->paginate(15);

        // Statistik untuk dashboard
        $stats = [
            'total_pemesanan' => Pemesanan::count(),
            'pemesanan_sukses' => Pemesanan::where('status', 'sukses')->count(),
            'pemesanan_pending' => Pemesanan::where('status', 'pending')->count(),
            'total_pendapatan' => Pemesanan::where('status', 'sukses')
                ->whereHas('pembayaran', function($q) {
                    $q->where('status', 'valid');
                })->sum('total_harga'),
            'pemesanan_hari_ini' => Pemesanan::whereDate('tanggal_pemesanan', today())->count()
        ];

        return view('laporan.index', compact('pemesanans', 'stats'));
    }

    public function cetakPdf(Request $request, $id = null)
    {
        if ($id) {
            // Cetak satu pemesanan (struk/tiket)
            $pemesanan = Pemesanan::with(['lapangan', 'user', 'pembayaran'])
                ->findOrFail($id);
            
            $pdf = Pdf::loadView('laporan.pdf.tiket', compact('pemesanan'))
                ->setPaper('a5', 'portrait');
            
            $filename = 'Tiket-Pemesanan-' . $pemesanan->id . '-' . date('YmdHis') . '.pdf';
            
            return $pdf->download($filename);
        } else {
            // Cetak laporan keseluruhan
            $query = Pemesanan::with(['lapangan', 'user', 'pembayaran'])
                ->orderBy('tanggal_pemesanan', 'desc');

            // Apply same filters as index
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('status_bayar')) {
                $query->whereHas('pembayaran', function($q) use ($request) {
                    $q->where('status', $request->status_bayar);
                });
            }
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_pemesanan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_pemesanan', '<=', $request->tanggal_sampai);
            }
            if ($request->filled('jenis')) {
                $query->whereHas('lapangan', function($q) use ($request) {
                    $q->where('jenis', $request->jenis);
                });
            }

            $pemesanans = $query->get();
            
            $stats = [
                'total_pemesanan' => $pemesanans->count(),
                'total_pendapatan' => $pemesanans->where('status', 'sukses')
                    ->filter(function($p) {
                        return $p->pembayaran && $p->pembayaran->status === 'valid';
                    })->sum('total_harga'),
                'periode' => [
                    'dari' => $request->tanggal_dari ?: 'Semua',
                    'sampai' => $request->tanggal_sampai ?: 'Semua'
                ]
            ];

            $pdf = Pdf::loadView('laporan.pdf.laporan', compact('pemesanans', 'stats'))
                ->setPaper('a4', 'landscape');
            
            $filename = 'Laporan-Pemesanan-' . date('YmdHis') . '.pdf';
            
            return $pdf->download($filename);
        }
    }
}