<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = session('admin');

        if (!is_array($admin) || empty($admin['id'])) {
            $rememberToken = $request->cookie('admin_remember_token');

            if (! $rememberToken) {
                return redirect()->route('admin-login');
            }

            $rememberedAdmin = Admin::where('remember_token', hash('sha256', $rememberToken))->first();

            if (! $rememberedAdmin) {
                return redirect()->route('admin-login');
            }

            $admin = [
                'id' => $rememberedAdmin->id,
                'nama_lengkap' => $rememberedAdmin->nama_lengkap,
                'email' => $rememberedAdmin->email,
                'username' => $rememberedAdmin->username,
                'role' => $rememberedAdmin->role,
            ];

            session(['admin' => $admin]);
        }

        if (empty($admin['role'])) {
            $request->session()->forget('admin');

            return redirect()
                ->route('admin-login')
                ->with('login_error', 'Role admin belum tersedia. Silakan login ulang.');
        }

        return $next($request);
    }
}

