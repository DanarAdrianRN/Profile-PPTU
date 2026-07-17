<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'nama_lengkap' => 'Admin Yayasan',
                'email' => 'admin.yayasan@example.com',
                'username' => 'adminYayasan',
                'role' => 'administrasi',
                'password' => 'qwerty123',
            ],
            [
                'nama_lengkap' => 'Admin Media',
                'email' => 'admin.media@example.com',
                'username' => 'adminMedia',
                'role' => 'media',
                'password' => 'qwerty321',
            ],
        ];

        foreach ($admins as $admin) {
            $existing = Admin::where('username', $admin['username'])->first();

            if ($existing) {
                // Update bila admin sudah ada (untuk memudahkan dev)
                $existing->update([
                    'nama_lengkap' => $admin['nama_lengkap'],
                    'email' => $admin['email'],
                    'role' => $admin['role'],
                    'password' => Hash::make($admin['password']),
                ]);

                continue;
            }

            Admin::create([
                'nama_lengkap' => $admin['nama_lengkap'],
                'email' => $admin['email'],
                'username' => $admin['username'],
                'role' => $admin['role'],
                'password' => Hash::make($admin['password']),
            ]);
        }
    }
}

