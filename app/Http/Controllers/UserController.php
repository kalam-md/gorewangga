<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'nama_lengkap' => $user->nama_lengkap,
            'username' => $user->username,
            'email' => $user->email,
            'no_telp' => $user->no_telp,
            'role' => $user->role,
            'created_at' => $user->created_at->format('d M Y H:i'),
            'updated_at' => $user->updated_at->format('d M Y H:i')
        ]);
    }
}