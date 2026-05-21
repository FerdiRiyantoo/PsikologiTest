<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        // Ambil user langsung dari DB, bukan dari Auth cache
        $user = User::find(Auth::id());

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->with('error', 'Password lama tidak sesuai.');
        }

        // Pastikan password baru berbeda dari yang lama
        if (Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.'])
                ->with('error', 'Password baru tidak boleh sama dengan password lama.');
        }

        // Update langsung via query builder (hindari cache Eloquent)
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);

        // Logout paksa agar session lama tidak dipakai
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}