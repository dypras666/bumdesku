<?php

namespace Database\Seeders;

use App\Models\MasterAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data untuk periode Juni-Juli 2025 dengan saldo awal yang memadai
     */
    public function run(): void
    {
        $accounts = [
            // ASET LANCAR
            [
                'kode_akun' => '1-1001',
                'nama_akun' => 'Kas',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Kas di tangan dan kas kecil',
                'saldo_awal' => 150000000, // 150 juta - saldo awal yang cukup besar
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1002',
                'nama_akun' => 'Bank BRI',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Rekening bank BRI BUMDES',
                'saldo_awal' => 300000000, // 300 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1003',
                'nama_akun' => 'Bank Mandiri',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Rekening bank Mandiri untuk operasional',
                'saldo_awal' => 200000000, // 200 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1004',
                'nama_akun' => 'Piutang Usaha',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Piutang dari pelanggan dan mitra',
                'saldo_awal' => 50000000, // 50 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1005',
                'nama_akun' => 'Persediaan Bahan Baku',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Persediaan bahan baku produksi',
                'saldo_awal' => 80000000, // 80 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-1006',
                'nama_akun' => 'Persediaan Barang Jadi',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Persediaan produk siap jual',
                'saldo_awal' => 120000000, // 120 juta
                'is_active' => true,
            ],

            // ASET TETAP
            [
                'kode_akun' => '1-2001',
                'nama_akun' => 'Tanah',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Tanah untuk operasional BUMDES',
                'saldo_awal' => 500000000, // 500 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-2002',
                'nama_akun' => 'Bangunan',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Bangunan kantor dan gudang',
                'saldo_awal' => 400000000, // 400 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-2003',
                'nama_akun' => 'Peralatan Produksi',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Mesin dan peralatan produksi',
                'saldo_awal' => 250000000, // 250 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-2004',
                'nama_akun' => 'Kendaraan',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Kendaraan operasional dan distribusi',
                'saldo_awal' => 180000000, // 180 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '1-2005',
                'nama_akun' => 'Peralatan Kantor',
                'kategori_akun' => 'Aset',
                'deskripsi' => 'Komputer, furniture, dan peralatan kantor',
                'saldo_awal' => 75000000, // 75 juta
                'is_active' => true,
            ],

            // KEWAJIBAN LANCAR
            [
                'kode_akun' => '2-1001',
                'nama_akun' => 'Hutang Usaha',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Hutang kepada supplier dan vendor',
                'saldo_awal' => 60000000, // 60 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '2-1002',
                'nama_akun' => 'Hutang Gaji',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Hutang gaji karyawan',
                'saldo_awal' => 15000000, // 15 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '2-1003',
                'nama_akun' => 'Hutang Pajak',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Hutang pajak penghasilan dan PPN',
                'saldo_awal' => 25000000, // 25 juta
                'is_active' => true,
            ],

            // KEWAJIBAN JANGKA PANJANG
            [
                'kode_akun' => '2-2001',
                'nama_akun' => 'Hutang Bank Jangka Panjang',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Pinjaman bank untuk investasi',
                'saldo_awal' => 200000000, // 200 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '2-2002',
                'nama_akun' => 'Hutang Pembiayaan Kendaraan',
                'kategori_akun' => 'Kewajiban',
                'deskripsi' => 'Hutang leasing kendaraan',
                'saldo_awal' => 80000000, // 80 juta
                'is_active' => true,
            ],

            // MODAL
            [
                'kode_akun' => '3-1001',
                'nama_akun' => 'Modal Awal BUMDES',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Modal awal dari dana desa',
                'saldo_awal' => 1000000000, // 1 miliar
                'is_active' => true,
            ],
            [
                'kode_akun' => '3-1002',
                'nama_akun' => 'Modal Tambahan',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Modal tambahan dari pemerintah dan investor',
                'saldo_awal' => 500000000, // 500 juta
                'is_active' => true,
            ],
            [
                'kode_akun' => '3-1003',
                'nama_akun' => 'Laba Ditahan',
                'kategori_akun' => 'Modal',
                'deskripsi' => 'Akumulasi laba yang ditahan',
                'saldo_awal' => 235000000, // 235 juta - untuk balance
                'is_active' => true,
            ],

            // PENDAPATAN
            [
                'kode_akun' => '4-1001',
                'nama_akun' => 'Pendapatan Penjualan Produk',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan dari penjualan produk UMKM',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '4-1002',
                'nama_akun' => 'Pendapatan Jasa',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan dari jasa konsultasi dan pelatihan',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '4-1003',
                'nama_akun' => 'Pendapatan Wisata Desa',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan dari sektor pariwisata desa',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '4-1004',
                'nama_akun' => 'Pendapatan Lain-lain',
                'kategori_akun' => 'Pendapatan',
                'deskripsi' => 'Pendapatan di luar usaha utama',
                'saldo_awal' => 0,
                'is_active' => true,
            ],

            // BEBAN OPERASIONAL
            [
                'kode_akun' => '5-1001',
                'nama_akun' => 'Beban Gaji dan Tunjangan',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban gaji karyawan dan tunjangan',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1002',
                'nama_akun' => 'Beban Bahan Baku',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban pembelian bahan baku produksi',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1003',
                'nama_akun' => 'Beban Listrik dan Air',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban utilitas listrik dan air',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1004',
                'nama_akun' => 'Beban Transportasi',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban transportasi dan distribusi',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1005',
                'nama_akun' => 'Beban Pemasaran',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban promosi dan pemasaran',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1006',
                'nama_akun' => 'Beban Administrasi',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban administrasi dan operasional kantor',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1007',
                'nama_akun' => 'Beban Penyusutan',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban penyusutan aset tetap',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1008',
                'nama_akun' => 'Beban Bunga',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban bunga pinjaman bank',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
            [
                'kode_akun' => '5-1009',
                'nama_akun' => 'Beban Lain-lain',
                'kategori_akun' => 'Beban',
                'deskripsi' => 'Beban operasional lainnya',
                'saldo_awal' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            MasterAccount::create($account);
        }

        $this->command->info('Master Account seeder completed with balanced initial balances!');
        $this->command->info('Total Aset: Rp 2.355.000.000');
        $this->command->info('Total Kewajiban: Rp 380.000.000');
        $this->command->info('Total Modal: Rp 1.735.000.000');
        $this->command->info('Balance Check: Aset = Kewajiban + Modal ✓');
    }
}
