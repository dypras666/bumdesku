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
                'title' => 'Langkah 3: Mengelola Chart of Accounts (COA)',
                'subtitle' => 'Atur struktur akun keuangan',
                'content' => 'Chart of Accounts (COA) adalah daftar sistematis semua akun yang digunakan dalam pembukuan. Ini adalah fondasi sistem akuntansi Anda.',
                'image' => 'fas fa-sitemap',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Buku Besar" → "Chart of Accounts"',
                    'Lihat struktur akun: 1-Aset, 2-Kewajiban, 3-Modal, 4-Pendapatan, 5-Beban',
                    'Tambah akun baru sesuai kebutuhan dengan kode yang tepat',
                    'Pastikan setiap akun memiliki kategori yang benar',
                    'Gunakan COA sebagai panduan saat membuat jurnal'
                ]
            ],
            [
                'id' => 5,
                'title' => 'Langkah 4: Pengaturan Sistem',
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
                'id' => 6,
                'title' => 'Langkah 5: Mencatat Transaksi',
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
                'id' => 7,
                'title' => 'Langkah 6: Mengelola Pinjaman',
                'subtitle' => 'Kelola pinjaman masuk dan keluar',
                'content' => 'Sistem pinjaman membantu Anda mengelola pinjaman yang diberikan kepada anggota atau pinjaman yang diterima BUMDES.',
                'image' => 'fas fa-handshake',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Pinjaman"',
                    'Pilih "Tambah Pinjaman Baru"',
                    'Isi data peminjam (nama, kontak, jumlah pinjaman)',
                    'Tentukan jenis pinjaman (Pinjaman Keluar/Pinjaman Masuk)',
                    'Atur suku bunga dan jangka waktu',
                    'Catat pembayaran cicilan secara berkala',
                    'Monitor status pinjaman (Aktif/Lunas/Bermasalah)'
                ]
            ],
            [
                'id' => 8,
                'title' => 'Langkah 7: Membuat Jurnal Umum',
                'subtitle' => 'Catat transaksi dengan sistem double entry',
                'content' => 'Jurnal umum menggunakan sistem double entry dimana setiap transaksi memiliki sisi debit dan kredit yang seimbang.',
                'image' => 'fas fa-book',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Buku Besar" → "Buat Jurnal"',
                    'Pilih tanggal transaksi',
                    'Masukkan keterangan transaksi',
                    'Pilih akun debit dan masukkan jumlahnya',
                    'Pilih akun kredit dan masukkan jumlahnya',
                    'Pastikan total debit = total kredit',
                    'Simpan dan posting jurnal'
                ]
            ],
            [
                'id' => 9,
                'title' => 'Langkah 8: Melihat Laporan Keuangan',
                'subtitle' => 'Pantau keuangan dengan laporan lengkap',
                'content' => 'Sistem akan otomatis membuat berbagai laporan keuangan yang bisa Anda lihat kapan saja.',
                'image' => 'fas fa-chart-bar',
                'type' => 'step',
                'steps' => [
                    'Buka menu "Laporan Keuangan"',
                    'Pilih jenis laporan: Neraca Saldo, Laba Rugi, Neraca',
                    'Tentukan periode laporan',
                    'Lihat laporan pinjaman untuk monitoring',
                    'Export laporan ke PDF/Excel/Word',
                    'Cetak laporan untuk dokumentasi'
                ]
            ],
            [
                'id' => 10,
                'title' => 'Fitur Khusus: Monitoring Pinjaman',
                'subtitle' => 'Pantau kesehatan portofolio pinjaman',
                'content' => 'Sistem menyediakan dashboard khusus untuk memantau semua pinjaman dan kinerjanya.',
                'image' => 'fas fa-chart-line',
                'type' => 'feature',
                'features' => [
                    'Dashboard pinjaman dengan statistik lengkap',
                    'Laporan aging pinjaman (berdasarkan jatuh tempo)',
                    'Notifikasi otomatis untuk pinjaman yang akan jatuh tempo',
                    'Tracking pembayaran dan sisa pinjaman',
                    'Analisis risiko kredit berdasarkan riwayat pembayaran',
                    'Export laporan pinjaman untuk audit'
                ]
            ],
            [
                'id' => 11,
                'title' => 'Fitur Khusus: Balance Checker',
                'subtitle' => 'Pastikan keseimbangan pembukuan',
                'content' => 'Sistem dilengkapi dengan balance checker otomatis untuk memastikan akurasi pembukuan.',
                'image' => 'fas fa-balance-scale',
                'type' => 'feature',
                'features' => [
                    'Pengecekan otomatis keseimbangan debit-kredit',
                    'Alert jika ada ketidakseimbangan dalam jurnal',
                    'Trial balance dengan indikator status seimbang',
                    'Laporan discrepancy untuk audit trail',
                    'Validasi otomatis sebelum posting transaksi',
                    'Dashboard kesehatan pembukuan'
                ]
            ],
            [
                'id' => 12,
                'title' => 'Tips Penting',
                'subtitle' => 'Hal-hal yang perlu diingat',
                'content' => 'Beberapa tips untuk menggunakan sistem dengan optimal.',
                'image' => 'fas fa-lightbulb',
                'type' => 'tips',
                'tips' => [
                    'Selalu backup data secara berkala',
                    'Catat transaksi setiap hari agar tidak lupa',
                    'Periksa trial balance secara rutin untuk memastikan keseimbangan',
                    'Monitor pinjaman bermasalah dan lakukan tindakan preventif',
                    'Gunakan COA yang konsisten untuk semua transaksi',
                    'Review laporan keuangan bulanan untuk analisis kinerja',
                    'Dokumentasikan semua kebijakan akuntansi dan pinjaman',
                    'Hubungi administrator jika ada masalah'
                ]
            ],
            [
                'id' => 13,
                'title' => 'Selamat!',
                'subtitle' => 'Anda siap menggunakan sistem lengkap',
                'content' => 'Sekarang Anda sudah siap untuk mengelola keuangan BUMDES dengan sistem yang profesional, lengkap dengan fitur pinjaman dan pembukuan yang akurat.',
                'image' => 'fas fa-trophy',
                'type' => 'success'
            ]
        ];
    }
}
