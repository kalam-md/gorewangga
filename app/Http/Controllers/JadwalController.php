<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        return view('jadwal.index');
    }

    public function getEvents(Request $request)
    {
        // Ambil pemesanan dengan status sukses dan pembayaran valid
        $pemesanans = Pemesanan::with(['lapangan', 'user', 'pembayaran'])
            ->where('status', 'sukses')
            ->whereHas('pembayaran', function($query) {
                $query->where('status', 'valid');
            })
            ->get();

        $events = [];

        foreach ($pemesanans as $pemesanan) {
            // Parse jadwal JSON
            $jadwalArray = is_string($pemesanan->jadwal) 
                ? json_decode($pemesanan->jadwal, true) 
                : $pemesanan->jadwal;

            if ($jadwalArray && is_array($jadwalArray) && count($jadwalArray) >= 2) {
                // Ambil jam mulai dan jam selesai
                $jamMulai = $jadwalArray[0]; // "07:00:00"
                $jamSelesai = end($jadwalArray); // jam terakhir dalam array
                
                // Tambahkan 1 jam ke jam selesai untuk durasi
                $jamSelesaiPlusOne = Carbon::parse($jamSelesai)->addHour()->format('H:i:s');

                // Buat event untuk FullCalendar
                $events[] = [
                    'id' => $pemesanan->id,
                    'title' => $pemesanan->lapangan->nama_lapangan . ' - ' . $pemesanan->user->nama_lengkap,
                    'start' => $pemesanan->tanggal_pemesanan->format('Y-m-d') . 'T' . $jamMulai,
                    'end' => $pemesanan->tanggal_pemesanan->format('Y-m-d') . 'T' . $jamSelesaiPlusOne,
                    'backgroundColor' => $this->getEventColor($pemesanan->lapangan->jenis),
                    'borderColor' => $this->getEventColor($pemesanan->lapangan->jenis),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'lapangan' => $pemesanan->lapangan->nama_lapangan,
                        'jenis' => $pemesanan->lapangan->jenis_label,
                        'penyewa' => $pemesanan->user->nama_lengkap,
                        'phone' => $pemesanan->user->no_telp,
                        'total_harga' => $pemesanan->total_harga_formatted,
                        'metode_bayar' => $pemesanan->pembayaran->metode_formatted,
                        'jadwal_detail' => $pemesanan->jadwal_formatted,
                        'durasi' => count($jadwalArray) . ' jam'
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    private function getEventColor($jenis)
    {
        $colors = [
            'futsal' => '#10B981',    // green
            'basket' => '#F59E0B',    // yellow
            'badminton' => '#8B5CF6', // purple
            'tenis' => '#EF4444'      // red
        ];

        return $colors[$jenis] ?? '#6B7280'; // default gray
    }
}