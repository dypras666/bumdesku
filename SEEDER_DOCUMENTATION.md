# Dokumentasi Seeder BUMDES

## Overview
Sistem BUMDES menggunakan seeder Laravel untuk membuat data sample yang lengkap dan realistis. Semua data dibuat melalui seeder, tidak ada data yang dibuat manual.

## Urutan Eksekusi Seeder

### 1. Master Data (Foundation)
Seeder-seeder ini membuat data dasar yang diperlukan oleh seeder lainnya:

#### RoleSeeder
- Membuat role: superadmin, admin, manager, staff, user
- Setiap role memiliki permission yang berbeda

#### UserSeeder  
- Membuat 5 user dengan role berbeda
- Password default: "password"
- Email: superadmin@bumdes.com, admin@bumdes.com, dll.

#### SystemSettingSeeder
- Membuat pengaturan sistem lengkap
- Informasi perusahaan, pengaturan keuangan, dll.
- Menggunakan helper functions untuk akses mudah

#### MasterAccountSeeder
- Membuat chart of accounts lengkap
- Kategori: Aset, Kewajiban, Modal, Pendapatan, Beban
- Kode akun mengikuti standar akuntansi

#### MasterUnitSeeder
- Membuat unit-unit bisnis BUMDES
- Unit Kerajinan, Pertanian, Wisata, dll.

#### MasterInventorySeeder
- Membuat master data persediaan
- Produk kerajinan, hasil pertanian, dll.

### 2. Transactional Data
Seeder-seeder ini membuat data transaksi dan posting:

#### TransactionSeeder
- Membuat 20+ transaksi untuk 3-4 bulan terakhir
- Mix antara income dan expense
- Status: approved, pending
- Transaksi realistis sesuai bisnis BUMDES

#### GeneralLedgerSeeder
- Membuat posting otomatis dari transaksi approved
- Double entry bookkeeping
- Saldo awal untuk akun-akun utama
- Entry code otomatis

### 3. Reports (Final Output)
Seeder-seeder ini membuat laporan keuangan:

#### FinancialReportSeeder
- Membuat laporan bulanan, triwulan
- Status: draft, generated, finalized
- Berbagai jenis laporan: income statement, balance sheet, cash flow

#### DaftarLaporanKeuanganSeeder
- Membuat laporan keuangan komprehensif
- Laporan tahunan dan khusus
- Data laporan lengkap dengan analisis

## Cara Menjalankan Seeder

### Reset Database dan Jalankan Semua Seeder
```bash
php artisan migrate:fresh --seed
```

### Jalankan Seeder Tertentu
```bash
php artisan db:seed --class=TransactionSeeder
```

### Jalankan Semua Seeder (tanpa reset)
```bash
php artisan db:seed
```

## Data yang Dihasilkan

### Users (5 users)
- superadmin@bumdes.com (Superadmin)
- admin@bumdes.com (Admin)
- manager@bumdes.com (Manager)
- staff@bumdes.com (Staff)
- user@bumdes.com (User)

### Master Accounts (30+ accounts)
- Kas, Bank, Piutang (Aset)
- Utang, Modal (Kewajiban & Modal)
- Pendapatan Penjualan, Jasa (Pendapatan)
- Beban Operasional, Gaji (Beban)

### Transactions (20+ transactions)
- Transaksi 3-4 bulan terakhir
- Modal awal, penjualan, pembelian
- Biaya operasional, investasi
- Status approved dan pending

### General Ledger Entries (40+ entries)
- Double entry untuk setiap transaksi
- Saldo awal akun kas
- Posting otomatis dari transaksi

### Financial Reports (15+ reports)
- Laporan bulanan dan triwulan
- Income Statement, Balance Sheet, Cash Flow
- Status draft, generated, finalized

## Fitur Khusus

### Auto-Generated Codes
- Transaction codes: TRX000001, TRX000002, dll.
- Report codes: RPT000001, RPT000002, dll.
- General Ledger entry codes: GL000001A, GL000001B, dll.

### Realistic Data
- Transaksi sesuai dengan bisnis BUMDES
- Nominal yang realistis
- Deskripsi yang sesuai konteks

### Proper Relationships
- Foreign key relationships terjaga
- Data konsisten antar tabel
- Referential integrity

### Date Ranges
- Data tersebar dalam periode waktu yang masuk akal
- Transaksi historis dan terkini
- Laporan sesuai periode

## Troubleshooting

### Jika Data Tidak Muncul
1. Pastikan semua seeder dijalankan: `php artisan migrate:fresh --seed`
2. Cek log error: `tail -f storage/logs/laravel.log`
3. Pastikan foreign key constraints terpenuhi

### Jika Seeder Gagal
1. Cek urutan seeder di DatabaseSeeder
2. Pastikan model relationships benar
3. Cek data dependencies

### Reset Specific Data
```bash
# Reset hanya transaksi
php artisan db:seed --class=TransactionSeeder

# Reset hanya laporan
php artisan db:seed --class=FinancialReportSeeder
```

## Customization

### Menambah Data Transaksi
Edit `TransactionSeeder.php` dan tambahkan array transaksi baru.

### Menambah Master Account
Edit `MasterAccountSeeder.php` dan tambahkan akun baru.

### Menambah User
Edit `UserSeeder.php` dan tambahkan user baru.

## Best Practices

1. **Selalu gunakan seeder** untuk data sample
2. **Jangan buat data manual** di database
3. **Jalankan migrate:fresh --seed** untuk reset lengkap
4. **Backup data production** sebelum menjalankan seeder
5. **Test seeder** di environment development dulu

## Maintenance

### Update Seeder
Ketika ada perubahan struktur database:
1. Update migration terlebih dahulu
2. Update seeder yang terkait
3. Test dengan migrate:fresh --seed
4. Commit perubahan seeder bersamaan dengan migration

### Performance
- Seeder menggunakan batch insert untuk performance
- Chunk data besar untuk menghindari memory limit
- Disable foreign key checks saat diperlukan