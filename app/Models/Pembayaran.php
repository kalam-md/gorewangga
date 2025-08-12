<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_bayar' => 'datetime'
    ];

    // Relationships
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // Accessors
    public function getBuktiTransferUrlAttribute()
    {
        if ($this->bukti_transfer) {
            return Storage::url($this->bukti_transfer);
        }
        return null;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => [
                'class' => 'bg-yellow-100 text-yellow-800',
                'icon' => 'fa-clock',
                'text' => 'Pending'
            ],
            'valid' => [
                'class' => 'bg-green-100 text-green-800',
                'icon' => 'fa-check-circle',
                'text' => 'Valid'
            ],
            'invalid' => [
                'class' => 'bg-red-100 text-red-800',
                'icon' => 'fa-times-circle',
                'text' => 'Invalid'
            ]
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getMetodeFormattedAttribute()
    {
        $methods = [
            'Transfer Bank' => 'Transfer Bank',
            'Tunai' => 'Tunai',
            'E-Wallet' => 'E-Wallet'
        ];

        return $methods[$this->metode] ?? $this->metode;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    public function scopeInvalid($query)
    {
        return $query->where('status', 'invalid');
    }

    public function scopeWithProof($query)
    {
        return $query->whereNotNull('bukti_transfer');
    }

    // Methods
    public function hasBuktiTransfer()
    {
        return !empty($this->bukti_transfer);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isValid()
    {
        return $this->status === 'valid';
    }

    public function isInvalid()
    {
        return $this->status === 'invalid';
    }
}