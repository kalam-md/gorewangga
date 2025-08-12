<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lapangan',
        'jenis',
        'harga_per_jam',
        'deskripsi',
        'gambar',
        'is_active'
    ];

    protected $casts = [
        'gambar' => 'array',
        'harga_per_jam' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationship
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function jadwalTersedia()
    {
        return $this->hasMany(Jadwal::class)->where('status', 'tersedia');
    }

    // Accessors
    public function getGambarUrlsAttribute()
    {
        if (!$this->gambar) {
            return [];
        }

        return collect($this->gambar)->map(function ($gambar) {
            return Storage::url($gambar);
        })->toArray();
    }

    public function getGambarUtamaAttribute()
    {
        if (!$this->gambar || empty($this->gambar)) {
            return asset('images/default-field.jpg');
        }

        return Storage::url($this->gambar[0]);
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_jam, 0, ',', '.');
    }

    public function getJenisLabelAttribute()
    {
        $labels = [
            'futsal' => 'Futsal',
            'basket' => 'Basket',
            'badminton' => 'Badminton',
            'tenis' => 'Tenis'
        ];

        return $labels[$this->jenis] ?? ucfirst($this->jenis);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_lapangan', 'like', "%{$search}%")
              ->orWhere('jenis', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Methods
    public function deleteGambar()
    {
        if ($this->gambar) {
            foreach ($this->gambar as $gambar) {
                Storage::delete($gambar);
            }
        }
    }

    public function isAvailableAt($tanggal, $jam_mulai, $jam_selesai)
    {
        return !$this->jadwals()
            ->where('tanggal', $tanggal)
            ->where('status', 'dipesan')
            ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                $query->where(function ($q) use ($jam_mulai, $jam_selesai) {
                    $q->where('jam_mulai', '<', $jam_selesai)
                      ->where('jam_selesai', '>', $jam_mulai);
                });
            })
            ->exists();
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }
}