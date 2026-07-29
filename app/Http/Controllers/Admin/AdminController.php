<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->get();

        return view('pages.admin.administrasi.data-admin', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username'],
            'role' => ['required', Rule::in(['administrasi', 'media'])],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);

        return back()->with('success', 'Data admin berhasil ditambahkan');
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
            'role' => ['required', Rule::in(['administrasi', 'media'])],
        ]);

        $admin->update($validated);

        if (session('admin.id') === $admin->id) {
            session(['admin' => [
                'id' => $admin->id,
                'nama_lengkap' => $admin->nama_lengkap,
                'email' => $admin->email,
                'username' => $admin->username,
                'role' => $admin->role,
                'session_version' => $admin->session_version,
            ]]);
        }

        return back()->with('success', 'Data admin berhasil diperbarui');
    }

    public function destroy(Admin $admin)
    {
        if (session('admin.id') === $admin->id) {
            return back()->with('error', 'Admin yang sedang login tidak dapat dihapus');
        }

        if (Admin::count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak dapat dihapus');
        }

        $admin->delete();

        return back()->with('success', 'Data admin berhasil dihapus');
    }

    public function sendResetPasswordLink(Admin $admin)
    {
        app(AuthController::class)->sendTemporaryLoginLink($admin->email, Admin::find(session('admin.id')));

        return back()->with('success', 'Cek email yang anda gunakan, link telah dikirim.');
    }
}
