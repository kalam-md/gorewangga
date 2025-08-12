<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = Pemesanan::with(['lapangan', 'user', 'pembayaran'])
            ->latest();
            
        if ($user->role === 'penyewa' || $user->role === 'petugas') {
            $query->where('user_id', $user->id);
        }
        
        $pemesanans = $query->paginate(10);
        
        return view('pemesanan.index', compact('pemesanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lapangans = Lapangan::where('is_active', true)->get();
        $jadwals = Jadwal::where('is_active', true)->orderBy('jam')->get();
        
        return view('pemesanan.create', compact('lapangans', 'jadwals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_pemesanan' => 'required|date|after_or_equal:today',
            'jadwal' => 'required|array|min:1',
            'jadwal.*' => 'required|exists:jadwals,id',
            'metode_pembayaran' => 'required|string|in:Transfer Bank,Tunai,E-Wallet',
        ]);

        DB::beginTransaction();

        try {
            $lapangan = Lapangan::findOrFail($request->lapangan_id);
            $user = Auth::user();
            $tanggal = $request->tanggal_pemesanan;

            // Ambil jam dari ID jadwal yang dipilih
            $jadwalDipilih = Jadwal::whereIn('id', $request->jadwal)
                ->orderBy('jam')
                ->get(['id', 'jam'])
                ->pluck('jam')
                ->toArray();

            // Validasi: Cek bentrok jadwal dengan pemesanan yang sudah ada
            $existingPemesanan = Pemesanan::where('lapangan_id', $lapangan->id)
                ->where('tanggal_pemesanan', $tanggal)
                ->whereIn('status', ['pending', 'sukses'])
                ->get();

            foreach ($existingPemesanan as $pemesanan) {
                $existingJadwal = json_decode($pemesanan->jadwal, true) ?? [];
                $intersection = array_intersect($jadwalDipilih, $existingJadwal);
                
                if (!empty($intersection)) {
                    $conflictJam = implode(', ', $intersection);
                    return back()
                        ->with('error', "Jadwal pada jam {$conflictJam} sudah dipesan untuk tanggal {$tanggal}.")
                        ->withInput();
                }
            }

            // Hitung total harga
            $totalHarga = $lapangan->harga_per_jam * count($jadwalDipilih);

            // Buat pemesanan baru
            $pemesanan = Pemesanan::create([
                'user_id' => $user->id,
                'lapangan_id' => $lapangan->id,
                'jadwal' => json_encode($jadwalDipilih), // Simpan sebagai JSON
                'tanggal_pemesanan' => $tanggal,
                'total_harga' => $totalHarga,
                'status' => 'pending'
            ]);

            // Buat pembayaran terkait
            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'metode' => $request->metode_pembayaran,
                'status' => 'pending'
            ]);

            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'Pemesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pemesanan $pemesanan)
    {
        $this->authorize('view', $pemesanan);
        
        $pemesanan->load(['lapangan', 'user', 'pembayaran']);
        
        return view('pemesanan.show', compact('pemesanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemesanan $pemesanan)
    {
        $this->authorize('update', $pemesanan);
        
        $request->validate([
            'status' => 'required|in:pending,sukses,gagal'
        ]);
        
        DB::beginTransaction();
        
        try {
            $pemesanan->update(['status' => $request->status]);
            
            // Update status pembayaran sesuai status pemesanan
            if ($request->status === 'sukses') {
                $pemesanan->pembayaran()->update(['status' => 'valid']);
            } elseif ($request->status === 'gagal') {
                $pemesanan->pembayaran()->update(['status' => 'invalid']);
            }
            
            DB::commit();
            
            return back()->with('success', 'Status pemesanan berhasil diperbarui');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemesanan $pemesanan)
    {
        $this->authorize('delete', $pemesanan);
        
        // Hapus bukti transfer jika ada
        if ($pemesanan->pembayaran && $pemesanan->pembayaran->bukti_transfer) {
            Storage::disk('public')->delete($pemesanan->pembayaran->bukti_transfer);
        }
        
        $pemesanan->delete();
        
        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil dibatalkan');
    }
    
    /**
     * Check available schedules
     */
    public function checkJadwal(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date|after_or_equal:today'
        ]);
        
        $lapanganId = $request->lapangan_id;
        $tanggal = $request->tanggal;
        
        // Dapatkan semua jadwal yang sudah dipesan pada tanggal dan lapangan tersebut
        $bookedJadwals = [];
        $pemesanans = Pemesanan::where('lapangan_id', $lapanganId)
            ->where('tanggal_pemesanan', $tanggal)
            ->whereIn('status', ['pending', 'sukses'])
            ->get();
            
        foreach ($pemesanans as $pemesanan) {
            $jadwal = json_decode($pemesanan->jadwal, true) ?? [];
            $bookedJadwals = array_merge($bookedJadwals, $jadwal);
        }
        
        // Hilangkan duplikat
        $bookedJadwals = array_unique($bookedJadwals);
        
        // Dapatkan ID jadwal yang sudah dipesan
        $bookedJadwalIds = Jadwal::whereIn('jam', $bookedJadwals)->pluck('id')->toArray();
        
        return response()->json([
            'booked' => $bookedJadwalIds
        ]);
    }

    /**
     * Upload bukti transfer
     */
    public function uploadBukti(Request $request, Pemesanan $pemesanan)
    {
        $this->authorize('update', $pemesanan);
        
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        DB::beginTransaction();
        
        try {
            if ($request->hasFile('bukti_transfer')) {
                // Hapus bukti transfer lama jika ada
                if ($pemesanan->pembayaran->bukti_transfer) {
                    Storage::disk('public')->delete($pemesanan->pembayaran->bukti_transfer);
                }
                
                // Upload bukti transfer baru
                $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
                
                // Update pembayaran
                $pemesanan->pembayaran()->update([
                    'bukti_transfer' => $path,
                    'tanggal_bayar' => now(),
                    'status' => 'pending' // Status menunggu validasi admin
                ]);
                
                DB::commit();
                
                return back()->with('success', 'Bukti transfer berhasil diupload. Menunggu validasi petugas.');
            }
            
            return back()->with('error', 'Gagal mengupload bukti transfer');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Validasi pembayaran (untuk admin)
     */
    public function validasiPembayaran(Request $request, Pemesanan $pemesanan)
    {
        $this->authorize('update', $pemesanan);
        
        $request->validate([
            'status_pembayaran' => 'required|in:valid,invalid'
        ]);
        
        DB::beginTransaction();
        
        try {
            $statusPemesanan = $request->status_pembayaran === 'valid' ? 'sukses' : 'gagal';
            
            // Update status pemesanan
            $pemesanan->update(['status' => $statusPemesanan]);
            
            // Update status pembayaran
            $pemesanan->pembayaran()->update(['status' => $request->status_pembayaran]);
            
            DB::commit();
            
            $message = $request->status_pembayaran === 'valid' 
                ? 'Pembayaran berhasil divalidasi' 
                : 'Pembayaran ditolak';
                
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}