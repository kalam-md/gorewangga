<?php

namespace App\Policies;

use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PemesananPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true; // Semua user yang login bisa melihat daftar
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pemesanan $pemesanan)
    {
        // Admin bisa melihat semua, user hanya bisa melihat miliknya
        return $user->role === 'petugas' || $user->id === $pemesanan->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Semua user yang login bisa membuat pemesanan
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pemesanan $pemesanan)
    {
        // Admin bisa update semua, user hanya bisa update miliknya yang masih pending
        if ($user->role === 'petugas') {
            return true;
        }
        
        return $user->id === $pemesanan->user_id && $pemesanan->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pemesanan $pemesanan)
    {
        // Admin bisa delete semua, user hanya bisa delete miliknya yang masih pending
        if ($user->role === 'petugas') {
            return true;
        }
        
        return $user->id === $pemesanan->user_id && $pemesanan->status === 'pending';
    }

    /**
     * Determine whether the user can upload payment proof.
     */
    public function uploadPaymentProof(User $user, Pemesanan $pemesanan)
    {
        // Hanya pemilik pemesanan yang bisa upload bukti pembayaran
        return $user->id === $pemesanan->user_id && 
               $pemesanan->status === 'pending' &&
               $pemesanan->pembayaran &&
               $pemesanan->pembayaran->status === 'pending';
    }

    /**
     * Determine whether the user can validate payment.
     */
    public function validatePayment(User $user, Pemesanan $pemesanan)
    {
        // Hanya admin yang bisa validasi pembayaran
        return $user->role === 'petugas' &&
               $pemesanan->pembayaran &&
               $pemesanan->pembayaran->bukti_transfer &&
               $pemesanan->pembayaran->status === 'pending';
    }
}