<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pemesanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'jadwal' => 'array',
        'tanggal_pemesanan' => 'date',
        'total_harga' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id');
    }

    // Accessors
    public function getJadwalFormattedAttribute()
    {
        if (is_string($this->jadwal)) {
            $jadwal = json_decode($this->jadwal, true);
        } else {
            $jadwal = $this->jadwal;
        }
        
        if (!$jadwal || !is_array($jadwal)) {
            return [];
        }
        
        return array_map(function($jam) {
            return Carbon::parse($jam)->format('H:i');
        }, $jadwal);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => [
                'class' => 'bg-yellow-100 text-yellow-800',
                'icon' => 'fa-clock',
                'text' => 'Pending'
            ],
            'sukses' => [
                'class' => 'bg-green-100 text-green-800',
                'icon' => 'fa-check-circle',
                'text' => 'Sukses'
            ],
            'gagal' => [
                'class' => 'bg-red-100 text-red-800',
                'icon' => 'fa-times-circle',
                'text' => 'Gagal'
            ]
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSukses($query)
    {
        return $query->where('status', 'sukses');
    }

    public function scopeGagal($query)
    {
        return $query->where('status', 'gagal');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForLapangan($query, $lapanganId)
    {
        return $query->where('lapangan_id', $lapanganId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('tanggal_pemesanan', $date);
    }

    // Methods
    public function canBeUpdated()
    {
        return $this->status === 'pending';
    }

    public function canBeCanceled()
    {
        return $this->status === 'pending';
    }

    public function needsPaymentProof()
    {
        return $this->status === 'pending' && 
               $this->pembayaran && 
               $this->pembayaran->status === 'pending' && 
               !$this->pembayaran->bukti_transfer;
    }

    public function canBeValidated()
    {
        return $this->pembayaran && 
               $this->pembayaran->bukti_transfer && 
               $this->pembayaran->status === 'pending';
    }
}