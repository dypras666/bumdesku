<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Memiliki akses penuh ke seluruh sistem BUMDES',
                'permissions' => [
                    'manage_users',
                    'manage_roles',
                    'manage_financial_reports',
                    'manage_transactions',
                    'manage_accounts',
                    'manage_master_data',
                    'view_dashboard',
                    'export_reports',
                    'backup_restore',
                    'system_settings'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator BUMDES dengan akses manajemen utama',
                'permissions' => [
                    'manage_users',
                    'manage_financial_reports',
                    'manage_transactions',
                    'manage_accounts',
                    'view_dashboard',
                    'export_reports'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'finance_manager',
                'display_name' => 'Manajer Keuangan',
                'description' => 'Mengelola laporan keuangan dan transaksi',
                'permissions' => [
                    'manage_financial_reports',
                    'manage_transactions',
                    'manage_accounts',
                    'view_dashboard',
                    'export_reports'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Akuntan',
                'description' => 'Mengelola transaksi dan buku besar',
                'permissions' => [
                    'manage_transactions',
                    'manage_accounts',
                    'view_dashboard',
                    'view_financial_reports'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'cashier',
                'display_name' => 'Kasir',
                'description' => 'Mengelola transaksi harian',
                'permissions' => [
                    'manage_transactions',
                    'view_dashboard'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'viewer',
                'display_name' => 'Pengguna Umum',
                'description' => 'Hanya dapat melihat dashboard dan laporan',
                'permissions' => [
                    'view_dashboard',
                    'view_financial_reports'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }
    }
}
