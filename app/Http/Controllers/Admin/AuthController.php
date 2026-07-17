<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.admin.login', [
            'showModal' => false,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username_or_email' => ['required', 'string'],
            'role' => ['required', Rule::in(['administrasi', 'media'])],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $identifier = $credentials['username_or_email'];

        $adminQuery = Admin::query();

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $adminQuery->where('email', $identifier);
        } else {
            $adminQuery->where('username', $identifier);
        }

        $admin = $adminQuery->first();

        if (! $admin) {
            return back()->withInput()->with('login_error', 'Akun admin tidak ditemukan');
        }


        if (! Hash::check($credentials['password'], $admin->password)) {
            return back()->withInput()->with('login_error', 'Username/Email atau password salah');
        }

        if ($admin->role !== $credentials['role']) {
            return back()->withInput()->with('login_error', 'Role yang dipilih tidak sesuai dengan akun admin');
        }

        $sessionAdmin = [
            'id' => $admin->id,
            'nama_lengkap' => $admin->nama_lengkap,
            'email' => $admin->email,
            'username' => $admin->username,
            'role' => $credentials['role'],
        ];


        session(['admin' => $sessionAdmin]);

        if ($request->boolean('remember')) {
            $rememberToken = Str::random(80);

            $admin->update([
                'remember_token' => hash('sha256', $rememberToken),
            ]);

            Cookie::queue('admin_remember_token', $rememberToken, 60 * 24 * 30);
        } else {
            $admin->update(['remember_token' => null]);
            Cookie::queue(Cookie::forget('admin_remember_token'));
        }

        return redirect()->route('admin-dashboard')->with(
            'success',
            'Selamat datang kembali, ' . $admin->nama_lengkap . '.'
        );
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'username_or_email' => ['required', 'string'],
            'role' => ['required', Rule::in(['administrasi', 'media'])],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $adminQuery = Admin::query()->where('role', $validated['role']);

        if (filter_var($validated['username_or_email'], FILTER_VALIDATE_EMAIL)) {
            $adminQuery->where('email', $validated['username_or_email']);
        } else {
            $adminQuery->where('username', $validated['username_or_email']);
        }

        $admin = $adminQuery->first();

        if (! $admin) {
            return back()
                ->withInput()
                ->with('forgot_error', 'Akun admin dengan role tersebut tidak ditemukan');
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
            'remember_token' => null,
        ]);

        Cookie::queue(Cookie::forget('admin_remember_token'));

        return back()->with('forgot_success', 'Password berhasil diperbarui. Silakan login kembali.');
    }

    public function logout(Request $request)
    {
        if ($adminId = session('admin.id')) {
            Admin::where('id', $adminId)->update(['remember_token' => null]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('admin_remember_token'));

        return redirect()->route('admin-login')->with(
            'info',
            'Anda telah keluar dari aplikasi dengan aman.'
        );
    }
}

