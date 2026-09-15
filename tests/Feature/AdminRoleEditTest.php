<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRoleEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_form_submits_username_and_role_can_be_saved(): void
    {
        $admin = Admin::create([
            'nama_lengkap' => 'Admin Uji', 'email' => 'uji@example.com',
            'username' => 'adminuji', 'password' => Hash::make('rahasia123'),
            'role' => 'administrasi',
        ]);
        $password = $admin->password;
        $html = view('pages.admin.administrasi.partials.form-admin', [
            'formAdmin' => $admin, 'mode' => 'edit',
        ])->render();
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $input = $xpath->query('//input[@name="username"]')->item(0);
        $this->assertTrue($input->hasAttribute('readonly'));
        $this->assertFalse($input->hasAttribute('disabled'));

        session(['admin' => ['id' => $admin->id, 'role' => $admin->role]]);
        (new AdminController)->update(Request::create('/', 'POST', [
            'nama_lengkap' => $admin->nama_lengkap,
            'email' => $admin->email,
            'username' => $input->getAttribute('value'),
            'role' => 'media',
        ]), $admin);
        $admin->refresh();
        $this->assertSame('media', $admin->role);
        $this->assertSame('adminuji', $admin->username);
        $this->assertSame($password, $admin->password);
        $this->assertSame('media', session('admin.role'));
    }
}
