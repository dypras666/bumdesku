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
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1002',
                'nama_akun' => 'Bank',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Rekening bank perusahaan',
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1003',
                'nama_akun' => 'Piutang Usaha',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Piutang dari pelanggan',
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1004',
                'nama_akun' => 'Persediaan',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Persediaan barang dagangan',
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-2001',
                'nama_akun' => 'Peralatan',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Peralatan operasional',
                'is_active' => true,
            ],

            // Kewajiban
            [
                'kode_akun' => '2-1001',
                'nama_akun' => 'Hutang Usaha',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Hutang kepada supplier',
                'is_active' => true,
            ],
            [
                'kode_akun' => '2-1002',
                'nama_akun' => 'Hutang Bank',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Pinjaman dari bank',
                'is_active' => true,
            ],

            // Modal
            [
                'kode_akun' => '3-1001',
                'nama_akun' => 'Modal Awal',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Modal awal BUMDES',
                'is_active' => true,
            ],
            [
                'kode_akun' => '3-1002',
                'nama_akun' => 'Laba Ditahan',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Akumulasi laba yang ditahan',
                'is_active' => true,
            ],

            // Pendapatan
            [
                'kode_akun' => '4-1001',
                'nama_akun' => 'Pendapatan Penjualan',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan dari penjualan barang/jasa',
                'is_active' => true,
            ],
            [
                'kode_akun' => '4-1002',
                'nama_akun' => 'Pendapatan Lain-lain',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan di luar usaha utama',
                'is_active' => true,
            ],

            // Beban
            [
                'kode_akun' => '5-1001',
                'nama_akun' => 'Beban Operasional',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban untuk operasional harian',
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1002',
                'nama_akun' => 'Beban Gaji',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban gaji karyawan',
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1003',
                'nama_akun' => 'Beban Listrik',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban listrik dan utilitas',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            MasterAccount::create($account);
        }
    }
}
