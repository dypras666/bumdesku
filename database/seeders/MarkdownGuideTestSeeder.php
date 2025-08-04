<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guide;
use App\Models\User;

class MarkdownGuideTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@bumdes.id')->first();
        
        Guide::create([
            'title' => 'Test Markdown dengan Daftar Isi',
            'slug' => 'test-markdown-dengan-daftar-isi',
            'description' => 'Guide untuk menguji apakah daftar isi berfungsi dengan konten Markdown.',
            'content' => '
# Pendahuluan

Ini adalah panduan untuk menguji apakah daftar isi berfungsi dengan baik untuk konten Markdown.

## Persiapan Awal

Sebelum memulai, pastikan semua persiapan sudah dilakukan dengan baik.

### Dokumen yang Diperlukan

Berikut adalah dokumen-dokumen yang perlu disiapkan:

- Akta pendirian BUMDES
- SK pengurus
- NPWP
- Rekening bank

### Struktur Organisasi

Pastikan struktur organisasi sudah jelas:

1. Penasehat
2. Direktur
3. Bendahara
4. Sekretaris

## Implementasi Sistem

Tahap implementasi sistem meliputi beberapa langkah penting.

### Setup Database

Langkah-langkah setup database:

1. Buat database baru
2. Import struktur tabel
3. Isi data master
4. Test koneksi

### Konfigurasi Aplikasi

Konfigurasi yang perlu dilakukan:

- Setting database
- Setting email
- Setting backup
- Setting keamanan

## Pelatihan Pengguna

Pelatihan pengguna sangat penting untuk kesuksesan implementasi.

### Materi Pelatihan

Materi yang akan diberikan:

1. **Pengenalan Sistem**
   - Overview fitur
   - Navigasi dasar
   - Login dan logout

2. **Transaksi Harian**
   - Input transaksi
   - Validasi data
   - Approval workflow

3. **Pelaporan**
   - Generate laporan
   - Export data
   - Analisis hasil

### Jadwal Pelatihan

Pelatihan akan dilaksanakan dalam 3 hari:

- **Hari 1**: Pengenalan sistem
- **Hari 2**: Praktik transaksi
- **Hari 3**: Pelaporan dan troubleshooting

## Monitoring dan Evaluasi

Setelah implementasi, perlu dilakukan monitoring berkala.

### Indikator Keberhasilan

Beberapa indikator yang perlu dipantau:

1. Tingkat adopsi pengguna
2. Akurasi data
3. Kecepatan proses
4. Kepuasan pengguna

### Evaluasi Berkala

Evaluasi dilakukan setiap:

- Mingguan: Review transaksi
- Bulanan: Analisis laporan
- Triwulanan: Evaluasi sistem
- Tahunan: Review menyeluruh

## Kesimpulan

Implementasi sistem BUMDES memerlukan persiapan yang matang dan komitmen dari semua pihak.

### Rekomendasi

Beberapa rekomendasi untuk kesuksesan:

1. Libatkan semua stakeholder
2. Lakukan pelatihan berkala
3. Monitor secara konsisten
4. Evaluasi dan perbaiki terus-menerus

### Langkah Selanjutnya

Setelah sistem berjalan stabil:

- Eksplorasi fitur lanjutan
- Integrasi dengan sistem lain
- Pengembangan custom feature
- Scaling up operasional
',
            'category' => 'testing',
            'icon' => 'fas fa-vial',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => true,
            'order' => 99,
            'created_by' => $admin->id ?? 1,
        ]);
    }
}