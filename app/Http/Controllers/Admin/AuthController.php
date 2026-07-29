<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLoginToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    private const FORGOT_PASSWORD_MESSAGE = 'Jika email terdaftar dan mailer aktif, link telah dikirim.';
    private const TOKEN_EXPIRATION_MINUTES = 30;
    private const RATE_LIMIT_MAX_ATTEMPTS = 5;

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
            'session_version' => $admin->session_version ?: 1,
        ];


        session(['admin' => $sessionAdmin]);
        $admin->update(['remember_token' => null]);
        Cookie::queue(Cookie::forget('admin_remember_token'));

        return redirect()->route('admin-dashboard')->with(
            'success',
            'Selamat datang kembali, ' . $admin->nama_lengkap . '.'
        );
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (! $this->sendTemporaryLoginLink($validated['email'])) {
            return back()->with('forgot_error', 'Link login sementara gagal dikirim. Periksa konfigurasi email admin terlebih dahulu.');
        }

        return back()->with('forgot_success', self::FORGOT_PASSWORD_MESSAGE);
    }

    public function loginWithToken(Request $request, string $token)
    {
        $tokenRecord = AdminLoginToken::query()
            ->with('admin')
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $tokenRecord || ! $tokenRecord->admin) {
            return redirect()
                ->route('admin-login')
                ->with('login_error', 'Link login sementara tidak valid atau sudah kadaluarsa.');
        }

        $admin = $tokenRecord->admin;

        $tokenRecord->update(['used_at' => now()]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('admin_remember_token'));

        session([
            'admin' => $this->sessionAdmin($admin),
            'admin_force_password_change' => true,
            'admin_login_token_id' => $tokenRecord->id,
        ]);

        return redirect()
            ->route('admin-password-force-edit')
            ->with('info', 'Anda masuk memakai link sementara. Silakan ganti password untuk membuka akses admin.');
    }

    public function showForcePasswordForm()
    {
        return view('pages.admin.force-password', [
            'showModal' => false,
        ]);
    }

    public function completeTemporaryPassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $admin = Admin::findOrFail(session('admin.id'));

        $admin->update([
            'password' => Hash::make($validated['password']),
            'remember_token' => null,
            'session_version' => $admin->session_version + 1,
        ]);

        Cookie::queue(Cookie::forget('admin_remember_token'));
        $request->session()->regenerate();
        $request->session()->forget(['admin_force_password_change', 'admin_login_token_id']);
        session(['admin' => $this->sessionAdmin($admin->fresh())]);

        return redirect()
            ->route('admin-dashboard')
            ->with('success', 'Password berhasil diperbarui. Akses admin sudah dibuka kembali.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $admin = Admin::findOrFail(session('admin.id'));

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
        ]);

        $admin->update($validated);
        session(['admin' => $this->sessionAdmin($admin->fresh())]);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function updateOwnPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $admin = Admin::findOrFail(session('admin.id'));

        if (! Hash::check($validated['current_password'], $admin->password)) {
            return back()->with('error', 'Password lama tidak cocok.');
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
            'remember_token' => null,
            'session_version' => $admin->session_version + 1,
        ]);

        Cookie::queue(Cookie::forget('admin_remember_token'));
        session(['admin' => $this->sessionAdmin($admin->fresh())]);

        return back()->with('success', 'Password berhasil diperbarui.');
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

    public function sendTemporaryLoginLink(string $email, ?Admin $requestedBy = null): bool
    {
        $email = Str::lower(trim($email));
        $rateLimitKey = 'admin-forgot-password:' . sha1($email);

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::RATE_LIMIT_MAX_ATTEMPTS)) {
            return false;
        }

        RateLimiter::hit($rateLimitKey, 3600);

        $admin = Admin::where('email', $email)->first();

        if (! $admin) {
            return false;
        }

        if (config('mail.default') === 'log') {
            Log::warning('Mail belum dikonfigurasi untuk pengiriman inbox. Mailer masih menggunakan log driver.', [
                'admin_id' => $admin->id,
                'email' => $admin->email,
            ]);

            return false;
        }

        $plainToken = Str::random(64);

        $token = AdminLoginToken::create([
            'admin_id' => $admin->id,
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => now()->addMinutes(self::TOKEN_EXPIRATION_MINUTES),
            'requested_by_admin_id' => $requestedBy?->id,
        ]);

        $url = route('admin-password-token-login', $plainToken);

        try {
            Mail::html($this->temporaryLoginEmailHtml($admin, $url, $token->expires_at), function ($message) use ($admin) {
                $message->to($admin->email, $admin->nama_lengkap)
                    ->subject('Link Login Sementara Admin PPTU');
            });
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengirim link login sementara admin.', [
                'admin_id' => $admin->id,
                'message' => $exception->getMessage(),
            ]);

            $token->delete();

            return false;
        }

        return true;
    }

    private function sessionAdmin(Admin $admin): array
    {
        return [
            'id' => $admin->id,
            'nama_lengkap' => $admin->nama_lengkap,
            'email' => $admin->email,
            'username' => $admin->username,
            'role' => $admin->role,
            'session_version' => $admin->session_version ?: 1,
        ];
    }

    private function temporaryLoginEmailHtml(Admin $admin, string $url, $expiresAt): string
    {
        return view('emails.admin-temporary-login', [
            'admin' => $admin,
            'url' => $url,
            'expiresAt' => $expiresAt,
            'expirationMinutes' => self::TOKEN_EXPIRATION_MINUTES,
        ])->render();
    }
}

