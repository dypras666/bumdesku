<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guide;
use App\Models\User;

class TestGuideWithYoutubeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@bumdes.id')->first();
        
        Guide::create([
            'title' => 'Tutorial Video: Cara Menggunakan Sistem BUMDES',
            'slug' => 'tutorial-video-cara-menggunakan-sistem-bumdes',
            'description' => 'Video tutorial lengkap tentang cara menggunakan sistem BUMDES untuk mengelola keuangan desa dengan efektif.',
            'content' => '<h2>Selamat Datang di Tutorial Video</h2>
                         <p>Video ini akan memandu Anda langkah demi langkah dalam menggunakan sistem BUMDES.</p>
                         <h3>Yang Akan Anda Pelajari:</h3>
                         <ul>
                             <li>Cara login dan navigasi dasar</li>
                             <li>Mengelola transaksi keuangan</li>
                             <li>Membuat laporan keuangan</li>
                             <li>Tips dan trik penggunaan sistem</li>
                         </ul>
                         <p><strong>Durasi:</strong> Sekitar 15 menit</p>
                         <p><em>Pastikan untuk menonton video di atas sebelum melanjutkan ke panduan tertulis.</em></p>',
            'category' => 'tutorial',
            'icon' => 'fas fa-play-circle',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => true,
            'order' => 1,
            'created_by' => $admin->id ?? 1,
        ]);
    }
}
