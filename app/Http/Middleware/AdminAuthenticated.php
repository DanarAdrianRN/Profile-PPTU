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
            return redirect()->route('admin-login');
        }

        if (empty($admin['role'])) {
            $request->session()->forget('admin');

            return redirect()
                ->route('admin-login')
                ->with('login_error', 'Role admin belum tersedia. Silakan login ulang.');
        }

        $currentAdmin = Admin::find($admin['id']);

        if (! $currentAdmin) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin-login')
                ->with('login_error', 'Sesi admin tidak valid. Silakan login ulang.');
        }

        if ((int) ($admin['session_version'] ?? 1) !== (int) ($currentAdmin->session_version ?: 1)) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin-login')
                ->with('login_error', 'Sesi Anda sudah berakhir. Silakan login ulang.');
        }

        if ((bool) $request->session()->get('admin_force_password_change', false)) {
            $allowedRoutes = ['admin-password-force-edit', 'admin-password-force-update', 'admin-logout'];

            if (! $request->routeIs(...$allowedRoutes)) {
                return redirect()
                    ->route('admin-password-force-edit')
                    ->with('info', 'Anda harus mengganti password terlebih dahulu.');
            }
        }

        return $next($request);
    }
}

