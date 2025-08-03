<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterUnit;

class MasterUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Kantor
            [
                'kode_unit' => 'KT001',
                'kategori_unit' => 'Kantor',
                'nama_unit' => 'Kantor Pusat BUMDES',
                'nilai_aset' => 150000000,
                'penanggung_jawab' => 'Budi Santoso',
                'alamat' => 'Jl. Desa Makmur No. 1, Desa Sejahtera',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'KT002',
                'kategori_unit' => 'Kantor',
                'nama_unit' => 'Kantor Cabang Pasar',
                'nilai_aset' => 75000000,
                'penanggung_jawab' => 'Siti Aminah',
                'alamat' => 'Jl. Pasar Desa No. 15, Desa Sejahtera',
                'is_active' => true,
            ],
            
            // Produksi
            [
                'kode_unit' => 'PR001',
                'kategori_unit' => 'Produksi',
                'nama_unit' => 'Unit Produksi Keripik',
                'nilai_aset' => 200000000,
                'penanggung_jawab' => 'Ahmad Fauzi',
                'alamat' => 'Jl. Industri No. 5, Desa Sejahtera',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'PR002',
                'kategori_unit' => 'Produksi',
                'nama_unit' => 'Unit Produksi Gula Aren',
                'nilai_aset' => 120000000,
                'penanggung_jawab' => 'Dewi Sartika',
                'alamat' => 'Jl. Kebun Aren No. 10, Desa Sejahtera',
                'is_active' => true,
            ],
            
            // Gudang
            [
                'kode_unit' => 'GD001',
                'kategori_unit' => 'Gudang',
                'nama_unit' => 'Gudang Bahan Baku',
                'nilai_aset' => 80000000,
                'penanggung_jawab' => 'Joko Widodo',
                'alamat' => 'Jl. Gudang Utama No. 3, Desa Sejahtera',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'GD002',
                'kategori_unit' => 'Gudang',
                'nama_unit' => 'Gudang Produk Jadi',
                'nilai_aset' => 90000000,
                'penanggung_jawab' => 'Sri Mulyani',
                'alamat' => 'Jl. Gudang Selatan No. 7, Desa Sejahtera',
                'is_active' => true,
            ],
            
            // Kendaraan
            [
                'kode_unit' => 'KD001',
                'kategori_unit' => 'Kendaraan',
                'nama_unit' => 'Truk Distribusi L300',
                'nilai_aset' => 85000000,
                'penanggung_jawab' => 'Bambang Sutrisno',
                'alamat' => 'Pool Kendaraan BUMDES',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'KD002',
                'kategori_unit' => 'Kendaraan',
                'nama_unit' => 'Motor Pickup Carry',
                'nilai_aset' => 45000000,
                'penanggung_jawab' => 'Andi Setiawan',
                'alamat' => 'Pool Kendaraan BUMDES',
                'is_active' => true,
            ],
            
            // Peralatan
            [
                'kode_unit' => 'PL001',
                'kategori_unit' => 'Peralatan',
                'nama_unit' => 'Mesin Penggoreng Keripik',
                'nilai_aset' => 25000000,
                'penanggung_jawab' => 'Rudi Hartono',
                'alamat' => 'Unit Produksi Keripik',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'PL002',
                'kategori_unit' => 'Peralatan',
                'nama_unit' => 'Mesin Pemarut Kelapa',
                'nilai_aset' => 15000000,
                'penanggung_jawab' => 'Sari Indah',
                'alamat' => 'Unit Produksi Gula Aren',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            MasterUnit::create($unit);
        }
    }
}
