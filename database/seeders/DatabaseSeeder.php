<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun HRD / Superadmin
        User::updateOrCreate(
            ['email' => 'hrd@ultimate.edu'],
            [
                'name' => 'HRD Ultimate Education',
                'password' => Hash::make('password123'),
                'role' => 'hr',
                'department' => 'HR',
            ]
        );

        // Membuat contoh akun Reviewer (Lead Divisi)
        User::updateOrCreate(
            ['email' => 'lead.seo@ultimate.edu'],
            [
                'name' => 'Lead SEO Divison',
                'password' => Hash::make('password123'),
                'role' => 'reviewer',
                'department' => 'SEO',
            ]
        );

        User::updateOrCreate(
            ['email' => 'iqbal@gmail.com'],
            [
                'name' => 'Lead IT Division',
                'password' => Hash::make('password123'),
                'role' => 'reviewer',
                'department' => 'IT',
            ]
        );
    }
}
