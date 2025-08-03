<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterInventory;

class MasterInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            // Bahan Baku
            [
                'kode_barang' => 'BB001',
                'kategori_barang' => 'Bahan Baku',
                'nama_barang' => 'Singkong Segar',
                'satuan' => 'Kg',
                'harga_beli' => 3000,
                'harga_jual' => 4500,
                'stok_minimum' => 100,
                'deskripsi' => 'Singkong segar untuk produksi keripik',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'BB002',
                'kategori_barang' => 'Bahan Baku',
                'nama_barang' => 'Kelapa Tua',
                'satuan' => 'Buah',
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'stok_minimum' => 50,
                'deskripsi' => 'Kelapa tua untuk produksi gula aren',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'BB003',
                'kategori_barang' => 'Bahan Baku',
                'nama_barang' => 'Minyak Goreng',
                'satuan' => 'Liter',
                'harga_beli' => 15000,
                'harga_jual' => 18000,
                'stok_minimum' => 20,
                'deskripsi' => 'Minyak goreng untuk produksi keripik',
                'is_active' => true,
            ],
            
            // Produk Jadi
            [
                'kode_barang' => 'PJ001',
                'kategori_barang' => 'Produk Jadi',
                'nama_barang' => 'Keripik Singkong Original',
                'satuan' => 'Pack',
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'stok_minimum' => 30,
                'deskripsi' => 'Keripik singkong rasa original kemasan 250gr',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'PJ002',
                'kategori_barang' => 'Produk Jadi',
                'nama_barang' => 'Keripik Singkong Pedas',
                'satuan' => 'Pack',
                'harga_beli' => 8500,
                'harga_jual' => 13000,
                'stok_minimum' => 25,
                'deskripsi' => 'Keripik singkong rasa pedas kemasan 250gr',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'PJ003',
                'kategori_barang' => 'Produk Jadi',
                'nama_barang' => 'Gula Aren Cetak',
                'satuan' => 'Kg',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'stok_minimum' => 15,
                'deskripsi' => 'Gula aren cetak kemasan 1kg',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'PJ004',
                'kategori_barang' => 'Produk Jadi',
                'nama_barang' => 'Gula Aren Semut',
                'satuan' => 'Pack',
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'stok_minimum' => 20,
                'deskripsi' => 'Gula aren semut kemasan 500gr',
                'is_active' => true,
            ],
            
            // Kemasan
            [
                'kode_barang' => 'KM001',
                'kategori_barang' => 'Kemasan',
                'nama_barang' => 'Plastik Kemasan 250gr',
                'satuan' => 'Lembar',
                'harga_beli' => 500,
                'harga_jual' => 750,
                'stok_minimum' => 200,
                'deskripsi' => 'Plastik kemasan untuk keripik 250gr',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'KM002',
                'kategori_barang' => 'Kemasan',
                'nama_barang' => 'Kotak Karton 1kg',
                'satuan' => 'Buah',
                'harga_beli' => 2000,
                'harga_jual' => 3000,
                'stok_minimum' => 50,
                'deskripsi' => 'Kotak karton untuk gula aren 1kg',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'KM003',
                'kategori_barang' => 'Kemasan',
                'nama_barang' => 'Label Produk',
                'satuan' => 'Lembar',
                'harga_beli' => 300,
                'harga_jual' => 500,
                'stok_minimum' => 500,
                'deskripsi' => 'Label produk BUMDES',
                'is_active' => true,
            ],
            
            // Peralatan
            [
                'kode_barang' => 'PR001',
                'kategori_barang' => 'Peralatan',
                'nama_barang' => 'Pisau Pemotong',
                'satuan' => 'Buah',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'stok_minimum' => 5,
                'deskripsi' => 'Pisau untuk memotong singkong',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'PR002',
                'kategori_barang' => 'Peralatan',
                'nama_barang' => 'Timbangan Digital',
                'satuan' => 'Buah',
                'harga_beli' => 150000,
                'harga_jual' => 200000,
                'stok_minimum' => 2,
                'deskripsi' => 'Timbangan digital kapasitas 5kg',
                'is_active' => true,
            ],
            
            // Lain-lain
            [
                'kode_barang' => 'LL001',
                'kategori_barang' => 'Lain-lain',
                'nama_barang' => 'Sarung Tangan Karet',
                'satuan' => 'Pasang',
                'harga_beli' => 5000,
                'harga_jual' => 8000,
                'stok_minimum' => 20,
                'deskripsi' => 'Sarung tangan untuk produksi',
                'is_active' => true,
            ],
            [
                'kode_barang' => 'LL002',
                'kategori_barang' => 'Lain-lain',
                'nama_barang' => 'Masker Kain',
                'satuan' => 'Buah',
                'harga_beli' => 3000,
                'harga_jual' => 5000,
                'stok_minimum' => 30,
                'deskripsi' => 'Masker kain untuk pekerja produksi',
                'is_active' => true,
            ],
        ];

        foreach ($inventories as $inventory) {
            MasterInventory::create($inventory);
        }
    }
}
