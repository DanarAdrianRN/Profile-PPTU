<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_success_redirects_to_dashboard(): void
    {
        $admin = Admin::create([
            'nama_lengkap' => 'Admin Yayasan',
            'email' => 'admin.yayasan@example.com',
            'username' => 'adminYayasan',
            'password' => Hash::make('qwerty123'),
        ]);

        $response = $this->post(route('admin-login-post'), [
'username_or_email' => 'adminYayasan',
            'password' => 'qwerty123',
        ]);

        $response->assertRedirect(route('admin-dashboard'));
        $this->assertNotNull(session('admin'));
        $this->assertSame($admin->id, session('admin.id'));
    }

    public function test_admin_login_failed_shows_error(): void
    {
        Admin::create([
'nama_lengkap' => 'Admin Media',
            'email' => 'admin.media@example.com',
            'username' => 'adminMedia',
            'password' => Hash::make('qwerty321'),
        ]);

        $response = $this->post(route('admin-login-post'), [
'username_or_email' => 'adminMedia',
'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('admin-login'));
        $response->assertSessionHas('login_error');
    }

    public function test_admin_logout_forgets_session(): void
    {
        session(['admin' => ['id' => 1, 'nama_lengkap' => 'X', 'username' => 'x']]);

        $response = $this->post(route('admin-logout'));

        $response->assertRedirect(route('admin-login'));
        $this->assertNull(session('admin'));
    }
}

