<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            [
                'key' => 'company_name',
                'value' => 'BUMDES Maju Bersama',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Nama BUMDES'
            ],
            [
                'key' => 'company_logo',
                'value' => null,
                'type' => 'file',
                'group' => 'company',
                'description' => 'Logo BUMDES'
            ],
            [
                'key' => 'village_name',
                'value' => 'Desa Contoh',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Nama Desa'
            ],
            [
                'key' => 'village_address',
                'value' => 'Jl. Contoh No. 123, Kecamatan Contoh, Kabupaten Contoh',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Alamat Desa'
            ],
            [
                'key' => 'village_phone',
                'value' => '021-12345678',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Nomor Telepon Desa'
            ],
            [
                'key' => 'village_email',
                'value' => 'bumdes@desacontoh.id',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Email BUMDES'
            ],
            [
                'key' => 'director_name',
                'value' => 'Budi Santoso',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Nama Direktur BUMDES'
            ],
            [
                'key' => 'director_nip',
                'value' => '123456789',
                'type' => 'text',
                'group' => 'company',
                'description' => 'NIP Direktur BUMDES'
            ],

            // Financial Settings
            [
                'key' => 'default_currency',
                'value' => 'IDR',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'Mata Uang Default'
            ],
            [
                'key' => 'currency_symbol',
                'value' => 'Rp',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'Simbol Mata Uang'
            ],
            [
                'key' => 'decimal_places',
                'value' => '2',
                'type' => 'number',
                'group' => 'financial',
                'description' => 'Jumlah Desimal'
            ],
            [
                'key' => 'thousand_separator',
                'value' => '.',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'Pemisah Ribuan'
            ],
            [
                'key' => 'decimal_separator',
                'value' => ',',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'Pemisah Desimal'
            ],
            [
                'key' => 'fiscal_year_start',
                'value' => '01-01',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'Awal Tahun Fiskal (MM-DD)'
            ],

            // Journal Settings
            [
                'key' => 'auto_journal_numbering',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'journal',
                'description' => 'Penomoran Jurnal Otomatis'
            ],
            [
                'key' => 'journal_prefix',
                'value' => 'JRN',
                'type' => 'text',
                'group' => 'journal',
                'description' => 'Prefix Nomor Jurnal'
            ],
            [
                'key' => 'require_journal_approval',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'journal',
                'description' => 'Memerlukan Persetujuan Jurnal'
            ],
            [
                'key' => 'allow_backdated_entries',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'journal',
                'description' => 'Izinkan Entri Tanggal Mundur'
            ],

            // Report Settings
            [
                'key' => 'report_header_logo',
                'value' => null,
                'type' => 'file',
                'group' => 'report',
                'description' => 'Logo Header Laporan'
            ],
            [
                'key' => 'report_footer_text',
                'value' => 'Laporan ini dibuat secara otomatis oleh Sistem BUMDES',
                'type' => 'text',
                'group' => 'report',
                'description' => 'Teks Footer Laporan'
            ],
            [
                'key' => 'show_zero_balances',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'report',
                'description' => 'Tampilkan Saldo Nol di Laporan'
            ],

            // System Settings
            [
                'key' => 'system_timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Zona Waktu Sistem'
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Format Tanggal'
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i:s',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Format Waktu'
            ],
            [
                'key' => 'records_per_page',
                'value' => '25',
                'type' => 'number',
                'group' => 'system',
                'description' => 'Jumlah Record per Halaman'
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
