<?php

namespace Database\Seeders;

use App\Models\MasterAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Aset
            [
                'kode_akun' => '1-1001',
                'nama_akun' => 'Kas',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Kas di tangan dan kas di bank',
                'saldo_awal' => 50000000, // 50 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1002',
                'nama_akun' => 'Bank',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Rekening bank perusahaan',
                'saldo_awal' => 200000000, // 200 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1003',
                'nama_akun' => 'Piutang Usaha',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Piutang dari pelanggan',
                'saldo_awal' => 25000000, // 25 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1004',
                'nama_akun' => 'Persediaan',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Persediaan barang dagangan',
                'saldo_awal' => 75000000, // 75 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-2001',
                'nama_akun' => 'Peralatan',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Peralatan operasional',
                'saldo_awal' => 150000000, // 150 juta
                'is_active' => true,
            ],

            // Kewajiban
            [
                'kode_akun' => '2-1001',
                'nama_akun' => 'Hutang Usaha',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Hutang kepada supplier',
                'saldo_awal' => 30000000, // 30 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '2-1002',
                'nama_akun' => 'Hutang Bank',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Pinjaman dari bank',
                'saldo_awal' => 100000000, // 100 juta
                'is_active' => true,
            ],

            // Modal
            [
                'kode_akun' => '3-1001',
                'nama_akun' => 'Modal Awal',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Modal awal BUMDES',
                'saldo_awal' => 300000000, // 300 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '3-1002',
                'nama_akun' => 'Laba Ditahan',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Akumulasi laba yang ditahan',
                'saldo_awal' => 70000000, // 70 juta
                'is_active' => true,
            ],

            // Pendapatan
            [
                'kode_akun' => '4-1001',
                'nama_akun' => 'Pendapatan Penjualan',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan dari penjualan barang/jasa',
                'saldo_awal' => 0, // Pendapatan dimulai dari 0
                'is_active' => true,
            ],
            [
                'kode_akun' => '4-1002',
                'nama_akun' => 'Pendapatan Lain-lain',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan di luar usaha utama',
                'saldo_awal' => 0, // Pendapatan dimulai dari 0
                'is_active' => true,
            ],

            // Beban
            [
                'kode_akun' => '5-1001',
                'nama_akun' => 'Beban Operasional',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban untuk operasional harian',
                'saldo_awal' => 0, // Beban dimulai dari 0
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1002',
                'nama_akun' => 'Beban Gaji',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban gaji karyawan',
                'saldo_awal' => 0, // Beban dimulai dari 0
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1003',
                'nama_akun' => 'Beban Listrik',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban listrik dan utilitas',
                'saldo_awal' => 0, // Beban dimulai dari 0
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            MasterAccount::create($account);
        }
    }
}
