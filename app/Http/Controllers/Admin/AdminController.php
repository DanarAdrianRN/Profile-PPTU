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
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins', 'username')->ignore($admin->id),
            ],
            'role' => ['required', Rule::in(['administrasi', 'media'])],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        if (session('admin.id') === $admin->id) {
            session(['admin' => [
                'id' => $admin->id,
                'nama_lengkap' => $admin->nama_lengkap,
                'email' => $admin->email,
                'username' => $admin->username,
                'role' => $admin->role,
            ]]);
        }

        return back()->with('success', 'Data admin berhasil diperbarui');
    }

    public function destroy(Admin $admin)
    {
        if (session('admin.id') === $admin->id) {
            return back()->with('error', 'Admin yang sedang login tidak dapat dihapus');
        }

        $admin->delete();

        return back()->with('success', 'Data admin berhasil dihapus');
    }
}
