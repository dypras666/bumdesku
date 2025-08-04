<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorialSlideshowController extends Controller
{
    public function index()
    {
        $slides = $this->getTutorialSlides();
        $totalSlides = count($slides);
        return view('tutorial.slideshow', compact('slides', 'totalSlides'));
    }

    private function getTutorialSlides()
    {
        return [
            [
                'id' => 1,
                'title' => 'Selamat Datang di BUMDES',
                'subtitle' => 'Sistem Manajemen Keuangan Desa',
                'content' => 'Mari kita pelajari cara menggunakan sistem BUMDES untuk mengelola keuangan desa dengan mudah dan profesional.',
                'image' => 'fas fa-home',
                'type' => 'welcome'
            ],
            [
                'id' => 2,
                'title' => 'Langkah 1: Login ke Sistem',
                'subtitle' => 'Masuk dengan akun Anda',
                'content' => 'Pertama, masuk ke sistem menggunakan email dan password yang telah diberikan oleh administrator.',
                'image' => 'fas fa-sign-in-alt',
                'type' => 'step',
                'steps' => [
                    'Klik tombol "Login" di halaman utama',
                    'Masukkan email Anda',
                    'Masukkan password Anda',
                    'Klik "Masuk" untuk melanjutkan'
                ]
            ],
            [
                'id' => 3,
                'title' => 'Langkah 2: Pengaturan Data Master',
                'subtitle' => 'Siapkan data dasar sistem',
                'content' => 'Sebelum mencatat transaksi, kita perlu menyiapkan data master seperti akun, unit usaha, dan persediaan.',
                'image' => 'fas fa-database',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Data Master"',
                    'Isi data "Master Akun" untuk jenis-jenis akun keuangan',
                    'Tambahkan "Master Unit" untuk unit usaha desa',
                    'Input "Master Persediaan" untuk barang-barang yang dijual'
                ]
            ],
            [
                'id' => 4,
                'title' => 'Langkah 3: Pengaturan Sistem',
                'subtitle' => 'Sesuaikan dengan kebutuhan desa',
                'content' => 'Atur informasi desa, mata uang, dan pengaturan lainnya sesuai dengan kebutuhan BUMDES Anda.',
                'image' => 'fas fa-cogs',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Pengaturan Sistem"',
                    'Isi informasi perusahaan (nama desa, alamat, kontak)',
                    'Atur mata uang dan format angka',
                    'Sesuaikan pengaturan laporan'
                ]
            ],
            [
                'id' => 5,
                'title' => 'Langkah 4: Mencatat Transaksi',
                'subtitle' => 'Catat pemasukan dan pengeluaran',
                'content' => 'Sekarang Anda bisa mulai mencatat semua transaksi keuangan BUMDES dengan mudah.',
                'image' => 'fas fa-money-bill-wave',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Transaksi"',
                    'Pilih jenis transaksi (Pemasukan/Pengeluaran)',
                    'Isi detail transaksi dengan lengkap',
                    'Simpan transaksi'
                ]
            ],
            [
                'id' => 6,
                'title' => 'Langkah 5: Melihat Laporan',
                'subtitle' => 'Pantau keuangan dengan laporan',
                'content' => 'Sistem akan otomatis membuat laporan keuangan yang bisa Anda lihat kapan saja.',
                'image' => 'fas fa-chart-bar',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Laporan Keuangan"',
                    'Pilih jenis laporan yang diinginkan',
                    'Tentukan periode laporan',
                    'Lihat atau cetak laporan'
                ]
            ],
            [
                'id' => 7,
                'title' => 'Tips Penting',
                'subtitle' => 'Hal-hal yang perlu diingat',
                'content' => 'Beberapa tips untuk menggunakan sistem dengan optimal.',
                'image' => 'fas fa-lightbulb',
                'type' => 'tips',
                'tips' => [
                    'Selalu backup data secara berkala',
                    'Catat transaksi setiap hari agar tidak lupa',
                    'Periksa laporan secara rutin',
                    'Hubungi administrator jika ada masalah'
                ]
            ],
            [
                'id' => 8,
                'title' => 'Selamat!',
                'subtitle' => 'Anda siap menggunakan sistem',
                'content' => 'Sekarang Anda sudah siap untuk mengelola keuangan BUMDES dengan sistem yang profesional dan mudah digunakan.',
                'image' => 'fas fa-trophy',
                'type' => 'success'
            ]
        ];
    }
}
