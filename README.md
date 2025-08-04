# BUMDES - Sistem Manajemen Keuangan Badan Usaha Milik Desa

## Deskripsi Aplikasi

BUMDES adalah sistem manajemen keuangan yang dirancang khusus untuk Badan Usaha Milik Desa. Aplikasi ini membantu dalam pengelolaan transaksi keuangan, pencatatan akuntansi, dan pembuatan laporan keuangan yang sesuai dengan standar akuntansi dan kebutuhan pelaporan desa.

## Fitur Utama

- **Manajemen Transaksi**: Input dan pengelolaan transaksi pemasukan dan pengeluaran
- **Buku Besar**: Sistem pencatatan akuntansi double-entry otomatis
- **Laporan Keuangan**: Generasi laporan Laba Rugi, Neraca, dan Arus Kas
- **Master Data**: Pengelolaan akun, unit kerja, dan persediaan
- **Sistem Persetujuan**: Workflow approval untuk transaksi dan laporan
- **Dashboard**: Ringkasan keuangan dan analisis performa

## Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- Laravel 10.x
- MySQL 8.0 atau MariaDB 10.3+
- Composer
- Node.js & NPM (untuk asset compilation)
- Laravel Herd (untuk development)

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd bumdesku
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bumdesku
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Asset Compilation
```bash
npm run build
```

### 8. Setup Laravel Herd
```bash
herd park
herd link bumdesku
```

Aplikasi akan tersedia di: `http://bumdesku.test`

## Panduan Penggunaan

### 1. Login ke Sistem

**Akun Default:**
- **Admin**: admin@bumdesku.com / password
- **User**: user@bumdesku.com / password

### 2. Dashboard

Setelah login, Anda akan melihat dashboard yang menampilkan:
- Ringkasan keuangan bulan ini
- Grafik pendapatan vs pengeluaran
- Transaksi terbaru
- Status laporan keuangan

### 3. Input Transaksi

#### A. Akses Menu Transaksi
1. Klik menu **"Transaksi"** di sidebar
2. Pilih **"Tambah Transaksi"**

#### B. Form Input Transaksi
1. **Jenis Transaksi**: Pilih "Pemasukan" atau "Pengeluaran"
2. **Tanggal**: Pilih tanggal transaksi
3. **Jumlah**: Masukkan nominal dalam Rupiah
4. **Deskripsi**: Jelaskan detail transaksi
5. **Akun**: Pilih akun yang sesuai dari daftar
6. **Status**: Otomatis "Pending" untuk review

#### C. Contoh Input Transaksi Pemasukan
```
Jenis: Pemasukan
Tanggal: 15/01/2024
Jumlah: 2.500.000
Deskripsi: Penjualan kerajinan bambu ke toko souvenir
Akun: Pendapatan Usaha
```

#### D. Contoh Input Transaksi Pengeluaran
```
Jenis: Pengeluaran
Tanggal: 16/01/2024
Jumlah: 800.000
Deskripsi: Pembelian bahan baku bambu
Akun: Persediaan
```

### 4. Persetujuan Transaksi

#### A. Review Transaksi Pending
1. Klik menu **"Transaksi"**
2. Filter status **"Pending"**
3. Klik **"Detail"** pada transaksi yang akan direview

#### B. Approve/Reject Transaksi
1. Review detail transaksi
2. Klik **"Approve"** untuk menyetujui
3. Klik **"Reject"** untuk menolak (berikan alasan)
4. Transaksi yang diapprove otomatis masuk ke Buku Besar

### 5. Monitoring Buku Besar

#### A. Akses Buku Besar
1. Klik menu **"Buku Besar"**
2. Pilih periode yang ingin dilihat
3. Filter berdasarkan akun jika diperlukan

#### B. Verifikasi Pencatatan
- Setiap transaksi yang diapprove otomatis tercatat
- Sistem menggunakan metode double-entry
- Debit = Kredit (harus seimbang)

### 6. Pembuatan Laporan Keuangan

#### A. Akses Menu Laporan
1. Klik menu **"Laporan Keuangan"**
2. Pilih **"Buat Laporan Baru"**

#### B. Konfigurasi Laporan
1. **Jenis Laporan**: Pilih salah satu:
   - Laporan Laba Rugi
   - Neraca
   - Laporan Arus Kas
2. **Periode Awal**: Tanggal mulai periode
3. **Periode Akhir**: Tanggal akhir periode
4. **Status**: Otomatis "Draft"

#### C. Generate Laporan
1. Klik **"Generate"** untuk membuat laporan
2. Sistem akan mengambil data dari Buku Besar
3. Laporan akan ditampilkan dalam format yang dapat dicetak

#### D. Finalisasi Laporan
1. Review laporan yang telah digenerate
2. Klik **"Finalize"** untuk mengunci laporan
3. Laporan yang sudah finalized tidak dapat diubah

### 7. Jenis-Jenis Laporan

#### A. Laporan Laba Rugi
- Menampilkan pendapatan dan beban dalam periode tertentu
- Menghitung laba/rugi bersih
- Format: Pendapatan - Beban = Laba/Rugi

#### B. Neraca
- Menampilkan posisi keuangan pada tanggal tertentu
- Format: Aset = Kewajiban + Ekuitas
- Snapshot kondisi keuangan

#### C. Laporan Arus Kas
- Menampilkan aliran kas masuk dan keluar
- Dikategorikan: Operasional, Investasi, Pendanaan
- Menunjukkan perubahan posisi kas

### 8. Master Data Management

#### A. Master Akun
1. Klik menu **"Master Data"** > **"Akun"**
2. Kelola chart of accounts
3. Kategorisasi: Aset, Kewajiban, Ekuitas, Pendapatan, Beban

#### B. Master Unit
1. Kelola unit-unit kerja dalam BUMDES
2. Setiap unit dapat memiliki akun terpisah

#### C. Master Persediaan
1. Kelola data barang/produk
2. Harga beli dan harga jual
3. Tracking inventory

### 9. Sistem Pengaturan

#### A. Pengaturan Perusahaan
1. Klik menu **"Pengaturan"** > **"Perusahaan"**
2. Update informasi BUMDES:
   - Nama perusahaan
   - Alamat
   - Logo
   - Kontak

#### B. Pengaturan Keuangan
1. Mata uang default
2. Format tanggal
3. Metode pembulatan

#### C. Pengaturan Laporan
1. Header dan footer laporan
2. Template laporan
3. Logo untuk laporan

## Workflow Lengkap: Dari Input ke Laporan

### Skenario: Transaksi Penjualan Kerajinan

#### Step 1: Input Transaksi Penjualan
```
1. Login sebagai user
2. Menu Transaksi > Tambah Transaksi
3. Input:
   - Jenis: Pemasukan
   - Tanggal: Hari ini
   - Jumlah: 3.500.000
   - Deskripsi: Penjualan kerajinan bambu batch Januari
   - Akun: Pendapatan Usaha
4. Simpan (Status: Pending)
```

#### Step 2: Approval Transaksi
```
1. Login sebagai admin
2. Menu Transaksi > Filter Pending
3. Review transaksi penjualan
4. Klik Approve
5. Status berubah menjadi Approved
6. Otomatis tercatat di Buku Besar:
   - Debit Kas: 3.500.000
   - Kredit Pendapatan Usaha: 3.500.000
```

#### Step 3: Input Transaksi Pembelian Bahan
```
1. Menu Transaksi > Tambah Transaksi
2. Input:
   - Jenis: Pengeluaran
   - Tanggal: Hari ini
   - Jumlah: 1.200.000
   - Deskripsi: Pembelian bambu untuk produksi
   - Akun: Persediaan
3. Simpan dan Approve
4. Tercatat di Buku Besar:
   - Debit Persediaan: 1.200.000
   - Kredit Kas: 1.200.000
```

#### Step 4: Generate Laporan Bulanan
```
1. Menu Laporan Keuangan > Buat Laporan
2. Pilih Laporan Laba Rugi
3. Periode: 1 Januari - 31 Januari 2024
4. Generate
5. Review hasil:
   - Pendapatan Usaha: 3.500.000
   - Beban Operasional: 1.200.000
   - Laba Bersih: 2.300.000
6. Finalize laporan
```

#### Step 5: Generate Neraca
```
1. Buat laporan Neraca untuk periode yang sama
2. Review posisi keuangan:
   - Aset (Kas + Persediaan): Total aset
   - Kewajiban: (jika ada)
   - Ekuitas: Saldo ekuitas + laba bersih
3. Pastikan Aset = Kewajiban + Ekuitas
```

## Tips dan Best Practices

### 1. Input Transaksi
- Selalu gunakan deskripsi yang jelas dan detail
- Pilih akun yang tepat sesuai dengan jenis transaksi
- Input transaksi secara berkala, jangan menumpuk

### 2. Approval Process
- Review setiap transaksi sebelum approve
- Pastikan dokumen pendukung tersedia
- Reject transaksi yang tidak sesuai dengan catatan yang jelas

### 3. Laporan Keuangan
- Buat laporan secara rutin (bulanan)
- Finalize laporan setelah yakin data sudah benar
- Simpan backup laporan yang sudah finalized

### 4. Backup Data
- Lakukan backup database secara berkala
- Export laporan penting ke PDF
- Simpan dokumen pendukung transaksi

## Troubleshooting

### 1. Transaksi Tidak Muncul di Buku Besar
- Pastikan transaksi sudah diapprove
- Check status transaksi di menu Transaksi
- Refresh halaman Buku Besar

### 2. Laporan Tidak Balance
- Periksa semua transaksi dalam periode
- Pastikan tidak ada transaksi yang terlewat
- Verify pencatatan double-entry

### 3. Error saat Generate Laporan
- Pastikan ada data transaksi dalam periode
- Check koneksi database
- Clear cache aplikasi: `php artisan optimize:clear`

## Data Seeder

Aplikasi ini dilengkapi dengan seeder lengkap yang mencakup:

### 1. Master Data
- **UserSeeder**: Akun admin dan user default
- **SystemSettingSeeder**: Pengaturan sistem dasar
- **MasterAccountSeeder**: Chart of accounts lengkap
- **MasterUnitSeeder**: Unit-unit kerja BUMDES
- **MasterInventorySeeder**: Data persediaan

### 2. Data Transaksi
- **TransactionSeeder**: Transaksi 3 bulan terakhir (20+ transaksi)
- **GeneralLedgerSeeder**: Buku besar otomatis dari transaksi
- **FinancialReportSeeder**: Laporan keuangan berbagai periode

### 3. Menjalankan Seeder
```bash
# Jalankan semua seeder
php artisan db:seed

# Atau jalankan seeder tertentu
php artisan db:seed --class=TransactionSeeder
php artisan db:seed --class=FinancialReportSeeder
```

### 4. Reset dan Seed Ulang
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed
```

## Support dan Kontak

Untuk bantuan teknis atau pertanyaan penggunaan:
- Email: support@bumdesku.com
- Dokumentasi: [Link dokumentasi]
- Issue tracker: [Link GitHub issues]

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
