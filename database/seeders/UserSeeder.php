<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $financeManagerRole = Role::where('name', 'finance_manager')->first();
        $accountantRole = Role::where('name', 'accountant')->first();
        $cashierRole = Role::where('name', 'cashier')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@bumdes.com',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Administrator BUMDES',
                'email' => 'admin@bumdes.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manajer Keuangan',
                'email' => 'finance@bumdes.com',
                'password' => Hash::make('password123'),
                'role_id' => $financeManagerRole?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Akuntan BUMDES',
                'email' => 'accountant@bumdes.com',
                'password' => Hash::make('password123'),
                'role_id' => $accountantRole?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kasir BUMDES',
                'email' => 'cashier@bumdes.com',
                'password' => Hash::make('password123'),
                'role_id' => $cashierRole?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pengguna Umum',
                'email' => 'user@bumdes.com',
                'password' => Hash::make('password123'),
                'role_id' => $viewerRole?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Demo User',
                'email' => 'demo@bumdes.com',
                'password' => Hash::make('demo123'),
                'role_id' => $viewerRole?->id,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
