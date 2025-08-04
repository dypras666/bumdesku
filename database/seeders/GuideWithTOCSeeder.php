<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guide;
use App\Models\User;

class GuideWithTOCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@bumdes.id')->first();
        
        Guide::create([
            'title' => 'Panduan Lengkap Pengelolaan Keuangan BUMDES',
            'slug' => 'panduan-lengkap-pengelolaan-keuangan-bumdes',
            'description' => 'Panduan komprehensif untuk mengelola keuangan BUMDES dengan sistem yang terintegrasi dan transparan.',
            'content' => '<h1>Pendahuluan</h1>
                         <p>Badan Usaha Milik Desa (BUMDES) merupakan lembaga ekonomi desa yang dikelola oleh masyarakat dan pemerintahan desa dalam upaya memperkuat perekonomian desa dan dibentuk berdasarkan kebutuhan dan potensi desa.</p>
                         
                         <h2>Persiapan Awal</h2>
                         <p>Sebelum memulai pengelolaan keuangan BUMDES, ada beberapa hal yang perlu dipersiapkan:</p>
                         
                         <h3>Dokumen yang Diperlukan</h3>
                         <ul>
                             <li>Akta pendirian BUMDES</li>
                             <li>SK pengurus BUMDES</li>
                             <li>NPWP BUMDES</li>
                             <li>Rekening bank atas nama BUMDES</li>
                         </ul>
                         
                         <h3>Struktur Organisasi</h3>
                         <p>Pastikan struktur organisasi BUMDES sudah terbentuk dengan jelas, meliputi:</p>
                         <ul>
                             <li>Penasehat (Kepala Desa)</li>
                             <li>Pelaksana Operasional (Direktur, Sekretaris, Bendahara)</li>
                             <li>Pengawas</li>
                         </ul>
                         
                         <h2>Pengelolaan Transaksi</h2>
                         <p>Sistem pengelolaan transaksi BUMDES harus dilakukan secara sistematis dan terstruktur.</p>
                         
                         <h3>Jenis-jenis Transaksi</h3>
                         <p>Transaksi dalam BUMDES dapat dikategorikan menjadi:</p>
                         <ul>
                             <li><strong>Transaksi Operasional:</strong> Pembelian, penjualan, biaya operasional</li>
                             <li><strong>Transaksi Investasi:</strong> Pembelian aset, pengembangan usaha</li>
                             <li><strong>Transaksi Pendanaan:</strong> Modal awal, pinjaman, pembagian hasil usaha</li>
                         </ul>
                         
                         <h3>Prosedur Pencatatan</h3>
                         <p>Setiap transaksi harus dicatat dengan prosedur yang benar:</p>
                         <ol>
                             <li>Verifikasi dokumen pendukung</li>
                             <li>Input data transaksi ke sistem</li>
                             <li>Validasi oleh bendahara</li>
                             <li>Persetujuan oleh direktur</li>
                         </ol>
                         
                         <h2>Pelaporan Keuangan</h2>
                         <p>Pelaporan keuangan BUMDES harus dilakukan secara berkala dan transparan.</p>
                         
                         <h3>Jenis Laporan</h3>
                         <p>BUMDES wajib membuat beberapa jenis laporan keuangan:</p>
                         
                         <h4>Laporan Bulanan</h4>
                         <ul>
                             <li>Laporan Laba Rugi</li>
                             <li>Laporan Arus Kas</li>
                             <li>Laporan Posisi Keuangan</li>
                         </ul>
                         
                         <h4>Laporan Tahunan</h4>
                         <ul>
                             <li>Laporan Keuangan Audited</li>
                             <li>Laporan Pertanggungjawaban</li>
                             <li>Laporan Pembagian Hasil Usaha</li>
                         </ul>
                         
                         <h3>Transparansi dan Akuntabilitas</h3>
                         <p>Prinsip transparansi dan akuntabilitas harus diterapkan dalam setiap aspek pengelolaan keuangan:</p>
                         <ul>
                             <li>Publikasi laporan keuangan secara berkala</li>
                             <li>Rapat evaluasi dengan pengawas</li>
                             <li>Sosialisasi kepada masyarakat desa</li>
                         </ul>
                         
                         <h2>Penggunaan Sistem Digital</h2>
                         <p>Penggunaan sistem digital dalam pengelolaan keuangan BUMDES memberikan banyak keuntungan.</p>
                         
                         <h3>Keuntungan Sistem Digital</h3>
                         <ul>
                             <li>Efisiensi waktu dan tenaga</li>
                             <li>Akurasi data yang lebih tinggi</li>
                             <li>Kemudahan dalam pelaporan</li>
                             <li>Transparansi yang lebih baik</li>
                         </ul>
                         
                         <h3>Fitur Utama Sistem</h3>
                         <p>Sistem BUMDES dilengkapi dengan berbagai fitur:</p>
                         <ul>
                             <li>Manajemen transaksi</li>
                             <li>Buku besar otomatis</li>
                             <li>Laporan keuangan real-time</li>
                             <li>Dashboard monitoring</li>
                         </ul>
                         
                         <h2>Kesimpulan</h2>
                         <p>Pengelolaan keuangan BUMDES yang baik memerlukan komitmen dari seluruh pengurus dan dukungan sistem yang memadai. Dengan mengikuti panduan ini dan memanfaatkan sistem digital, BUMDES dapat berkembang menjadi lembaga ekonomi desa yang kuat dan berkelanjutan.</p>',
            'category' => 'keuangan',
            'icon' => 'fas fa-chart-line',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => true,
            'order' => 2,
            'created_by' => $admin->id ?? 1,
        ]);
    }
}