<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lapangan::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by jenis
        if ($request->has('jenis') && !empty($request->jenis)) {
            $query->byJenis($request->jenis);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->active();
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $lapangans = $query->latest()->paginate(10);

        // Statistics
        $stats = [
            'total' => Lapangan::count(),
            'active' => Lapangan::active()->count(),
            'inactive' => Lapangan::where('is_active', false)->count(),
            'futsal' => Lapangan::byJenis('futsal')->count(),
            'basket' => Lapangan::byJenis('basket')->count(),
            'badminton' => Lapangan::byJenis('badminton')->count(),
            'tenis' => Lapangan::byJenis('tenis')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('lapangan.partials.table', compact('lapangans'))->render(),
                'pagination' => $lapangans->links()->render()
            ]);
        }

        return view('lapangan.index', compact('lapangans', 'stats'));
    }

    public function create()
    {
        return view('lapangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|in:futsal,basket,badminton,tenis',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['nama_lapangan', 'jenis', 'harga_per_jam', 'deskripsi']);
            $data['is_active'] = $request->has('is_active');

            // Handle multiple image uploads
            if ($request->hasFile('gambar')) {
                $gambarPaths = [];
                foreach ($request->file('gambar') as $file) {
                    $path = $file->store('lapangan', 'public');
                    $gambarPaths[] = $path;
                }
                $data['gambar'] = $gambarPaths;
            }

            $lapangan = Lapangan::create($data);

            DB::commit();

            return redirect()->route('lapangan.index')
                           ->with('success', 'Lapangan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files if error occurs
            if (isset($gambarPaths)) {
                foreach ($gambarPaths as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            return back()->withInput()
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Lapangan $lapangan)
    {
        $lapangan = Lapangan::findOrFail($lapangan->id);

        return view('lapangan.show', compact('lapangan'));
    }

    public function edit(Lapangan $lapangan)
    {
        return view('lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|in:futsal,basket,badminton,tenis',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'existing_images' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['nama_lapangan', 'jenis', 'harga_per_jam', 'deskripsi']);
            $data['is_active'] = $request->has('is_active');

            // Handle image updates
            $existingImages = $request->input('existing_images', []);
            $gambarPaths = [];

            // Keep selected existing images
            if ($lapangan->gambar) {
                foreach ($lapangan->gambar as $oldImage) {
                    if (in_array($oldImage, $existingImages)) {
                        $gambarPaths[] = $oldImage;
                    } else {
                        // Delete unselected images
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            // Add new images
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $path = $file->store('lapangan', 'public');
                    $gambarPaths[] = $path;
                }
            }

            $data['gambar'] = $gambarPaths;
            $lapangan->update($data);

            DB::commit();

            return redirect()->route('lapangan.show', $lapangan)
                           ->with('success', 'Lapangan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Lapangan $lapangan)
    {
        try {
            DB::beginTransaction();

            // Check if lapangan has any bookings
            $hasBookings = $lapangan->jadwals()->dipesan()->exists();
            if ($hasBookings) {
                return back()->with('error', 'Lapangan tidak dapat dihapus karena masih ada pemesanan aktif!');
            }

            // Delete images
            $lapangan->deleteGambar();

            // Delete lapangan (jadwals will be deleted automatically due to cascade)
            $lapangan->delete();

            DB::commit();

            return redirect()->route('lapangan.index')
                           ->with('success', 'Lapangan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Lapangan $lapangan)
    {
        try {
            $lapangan->update([
                'is_active' => !$lapangan->is_active
            ]);

            $status = $lapangan->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return response()->json([
                'success' => true,
                'message' => "Lapangan berhasil {$status}!",
                'is_active' => $lapangan->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'image_path' => 'required|string'
        ]);

        try {
            $imagePath = $request->image_path;
            $gambarArray = $lapangan->gambar ?? [];

            if (($key = array_search($imagePath, $gambarArray)) !== false) {
                // Remove from array
                unset($gambarArray[$key]);
                
                // Delete physical file
                Storage::disk('public')->delete($imagePath);
                
                // Update database
                $lapangan->update(['gambar' => array_values($gambarArray)]);

                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan!'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}