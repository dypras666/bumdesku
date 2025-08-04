# Panduan Penggunaan Aplikasi BUMDES

## Selamat Datang di Sistem Keuangan BUMDES

Aplikasi ini dirancang untuk membantu pengelolaan keuangan Badan Usaha Milik Desa (BUMDES) dengan mudah dan praktis. Panduan ini akan membantu Anda memahami cara menggunakan aplikasi step by step.

## 🔐 Cara Masuk ke Aplikasi

### Langkah 1: Buka Aplikasi
1. Buka browser (Chrome, Firefox, Safari, dll)
2. Ketik alamat: `http://bumdesku.test`
3. Tekan Enter

### Langkah 2: Login
1. Masukkan email dan password Anda
2. Klik tombol "Masuk"

**Akun Percobaan:**
- **Pengurus/Admin**: email `admin@bumdesku.com`, password `password`
- **Staff**: email `user@bumdesku.com`, password `password`

## 📊 Memahami Dashboard (Halaman Utama)

Setelah login, Anda akan melihat dashboard yang menampilkan:

### Ringkasan Keuangan
- **Total Kas**: Jumlah uang yang tersedia saat ini
- **Pendapatan Bulan Ini**: Pemasukan dalam bulan berjalan
- **Pengeluaran Bulan Ini**: Pengeluaran dalam bulan berjalan
- **Laba/Rugi**: Selisih antara pendapatan dan pengeluaran

### Grafik dan Statistik
- Grafik batang menunjukkan perbandingan pendapatan vs pengeluaran
- Tren keuangan beberapa bulan terakhir

### Transaksi Terbaru
- Daftar 5 transaksi terakhir yang diinput
- Status setiap transaksi (Pending, Disetujui, Ditolak)

## 💰 Mengelola Transaksi Keuangan

### Cara Input Transaksi Baru

#### Langkah 1: Masuk ke Menu Transaksi
1. Klik menu **"Transaksi"** di sidebar kiri
2. Pilih **"Tambah Transaksi"**

#### Langkah 2: Isi Form Transaksi
1. **Jenis Transaksi**: 
   - Pilih "Pemasukan" untuk uang masuk
   - Pilih "Pengeluaran" untuk uang keluar

2. **Tanggal**: Pilih tanggal transaksi terjadi

3. **Jumlah**: Masukkan nominal uang (tanpa titik atau koma)
   - Contoh: untuk 2 juta tulis `2000000`

4. **Keterangan**: Jelaskan detail transaksi
   - Contoh: "Penjualan kerajinan bambu ke toko souvenir"
   - Contoh: "Pembelian bahan baku rotan"

5. **Akun**: Pilih kategori yang sesuai
   - Untuk pemasukan: "Pendapatan Usaha", "Pendapatan Lain-lain"
   - Untuk pengeluaran: "Persediaan", "Beban Operasional", dll

6. Klik **"Simpan"**

#### Contoh Input Transaksi

**Transaksi Pemasukan:**
```
Jenis: Pemasukan
Tanggal: 15 Januari 2024
Jumlah: 3500000
Keterangan: Penjualan kerajinan bambu batch Januari
Akun: Pendapatan Usaha
```

**Transaksi Pengeluaran:**
```
Jenis: Pengeluaran
Tanggal: 16 Januari 2024
Jumlah: 1200000
Keterangan: Pembelian bambu untuk produksi
Akun: Persediaan
```

### Melihat Daftar Transaksi

1. Klik menu **"Transaksi"**
2. Anda akan melihat tabel berisi semua transaksi
3. Gunakan filter untuk mencari transaksi tertentu:
   - **Filter Tanggal**: Pilih rentang tanggal
   - **Filter Status**: Pending, Disetujui, Ditolak
   - **Filter Jenis**: Pemasukan atau Pengeluaran

### Status Transaksi

- **🟡 Pending**: Transaksi menunggu persetujuan
- **🟢 Disetujui**: Transaksi sudah disetujui dan masuk ke pembukuan
- **🔴 Ditolak**: Transaksi ditolak dengan alasan tertentu

## ✅ Menyetujui Transaksi (Untuk Pengurus)

### Langkah 1: Lihat Transaksi Pending
1. Klik menu **"Transaksi"**
2. Filter status **"Pending"**
3. Klik **"Detail"** pada transaksi yang akan direview

### Langkah 2: Review dan Putuskan
1. Periksa detail transaksi:
   - Apakah jumlah sudah benar?
   - Apakah keterangan jelas?
   - Apakah ada bukti/dokumen pendukung?

2. Pilih tindakan:
   - **Setujui**: Klik tombol "Approve" jika transaksi benar
   - **Tolak**: Klik tombol "Reject" dan berikan alasan penolakan

### Tips Menyetujui Transaksi
- ✅ Pastikan ada bukti fisik (nota, kwitansi, foto)
- ✅ Cek kesesuaian jumlah dengan dokumen
- ✅ Pastikan keterangan jelas dan detail
- ❌ Jangan setujui transaksi yang meragukan

## 📚 Melihat Buku Besar

Buku besar adalah catatan lengkap semua transaksi yang sudah disetujui.

### Cara Akses Buku Besar
1. Klik menu **"Buku Besar"**
2. Pilih periode yang ingin dilihat
3. Pilih akun tertentu jika diperlukan

### Memahami Buku Besar
- **Tanggal**: Kapan transaksi diposting
- **Keterangan**: Detail transaksi
- **Debit**: Uang masuk ke akun tersebut
- **Kredit**: Uang keluar dari akun tersebut
- **Saldo**: Sisa saldo setelah transaksi

## 📋 Membuat Laporan Keuangan

### Jenis-Jenis Laporan

#### 1. Laporan Laba Rugi
Menunjukkan apakah BUMDES untung atau rugi dalam periode tertentu.

**Cara Membuat:**
1. Klik menu **"Laporan Keuangan"**
2. Klik **"Buat Laporan Baru"**
3. Pilih **"Laporan Laba Rugi"**
4. Tentukan periode (dari tanggal - sampai tanggal)
5. Klik **"Generate"**

**Isi Laporan:**
- **Pendapatan**: Semua pemasukan
- **Beban**: Semua pengeluaran operasional
- **Laba/Rugi**: Pendapatan dikurangi beban

#### 2. Neraca
Menunjukkan posisi keuangan BUMDES pada tanggal tertentu.

**Cara Membuat:**
1. Pilih **"Neraca"** saat membuat laporan
2. Tentukan tanggal neraca
3. Klik **"Generate"**

**Isi Laporan:**
- **Aset**: Harta yang dimiliki (kas, persediaan, peralatan)
- **Kewajiban**: Hutang yang harus dibayar
- **Modal**: Kekayaan bersih BUMDES

#### 3. Laporan Arus Kas
Menunjukkan aliran uang masuk dan keluar.

**Isi Laporan:**
- **Kas dari Operasional**: Uang dari kegiatan usaha
- **Kas dari Investasi**: Uang dari pembelian/penjualan aset
- **Kas dari Pendanaan**: Uang dari pinjaman/modal

### Mengelola Status Laporan

#### Status Draft
- Laporan baru dibuat dengan status "Draft"
- Masih bisa diubah dan diperbaiki
- Belum resmi

#### Finalisasi Laporan
1. Review laporan yang sudah dibuat
2. Pastikan semua data sudah benar
3. Klik **"Finalize"** untuk mengunci laporan
4. Laporan yang sudah finalized tidak bisa diubah lagi

### Tips Membuat Laporan
- 📅 Buat laporan secara rutin (bulanan)
- 🔍 Periksa data sebelum finalisasi
- 💾 Simpan/cetak laporan penting
- 📊 Gunakan laporan untuk evaluasi kinerja

## 🗂️ Mengelola Data Master

### Master Akun (Chart of Accounts)
Daftar semua kategori keuangan yang digunakan.

**Kategori Utama:**
- **Aset**: Kas, Bank, Persediaan, Peralatan
- **Kewajiban**: Hutang Usaha, Hutang Bank
- **Modal**: Modal Awal, Laba Ditahan
- **Pendapatan**: Pendapatan Usaha, Pendapatan Lain
- **Beban**: Beban Operasional, Beban Administrasi

### Master Unit Kerja
Pembagian unit/divisi dalam BUMDES.

**Contoh Unit:**
- Unit Perdagangan
- Unit Simpan Pinjam
- Unit Jasa
- Unit Produksi

### Master Persediaan
Daftar barang/produk yang dijual atau digunakan.

**Informasi Persediaan:**
- Nama barang
- Harga beli
- Harga jual
- Stok tersedia

## ⚙️ Pengaturan Sistem

### Pengaturan Perusahaan
1. Klik menu **"Pengaturan"**
2. Pilih **"Informasi Perusahaan"**
3. Update informasi:
   - Nama BUMDES
   - Alamat lengkap
   - Nomor telepon
   - Email
   - Logo (jika ada)

### Pengaturan Laporan
Atur tampilan laporan keuangan:
- Header laporan
- Footer laporan
- Logo untuk laporan
- Format tanggal

## 🔍 Tips dan Trik Penggunaan

### Tips Input Transaksi
1. **Konsisten**: Input transaksi setiap hari, jangan ditumpuk
2. **Detail**: Berikan keterangan yang jelas dan lengkap
3. **Bukti**: Selalu simpan nota/kwitansi sebagai bukti
4. **Kategori**: Pilih akun yang tepat sesuai jenis transaksi

### Tips Approval
1. **Teliti**: Periksa setiap detail sebelum menyetujui
2. **Cepat**: Jangan biarkan transaksi pending terlalu lama
3. **Komunikasi**: Jika menolak, berikan alasan yang jelas

### Tips Laporan
1. **Rutin**: Buat laporan bulanan secara konsisten
2. **Analisis**: Gunakan laporan untuk evaluasi kinerja
3. **Backup**: Simpan laporan penting di tempat aman
4. **Presentasi**: Gunakan laporan untuk rapat pengurus

## ❓ Pertanyaan Umum (FAQ)

### Q: Bagaimana jika salah input transaksi?
**A:** Hubungi pengurus untuk membatalkan transaksi yang salah, lalu input ulang dengan benar.

### Q: Mengapa transaksi saya ditolak?
**A:** Periksa alasan penolakan dari pengurus. Biasanya karena keterangan kurang jelas atau jumlah tidak sesuai bukti.

### Q: Bagaimana cara melihat laba rugi BUMDES?
**A:** Buat Laporan Laba Rugi untuk periode yang diinginkan di menu Laporan Keuangan.

### Q: Apakah bisa mengubah transaksi yang sudah disetujui?
**A:** Tidak bisa. Transaksi yang sudah disetujui tidak dapat diubah untuk menjaga integritas data.

### Q: Bagaimana cara backup data?
**A:** Hubungi administrator sistem untuk melakukan backup database secara berkala.

## 📞 Bantuan dan Dukungan

Jika mengalami kesulitan:
1. **Baca ulang panduan** ini dengan teliti
2. **Tanya pengurus** yang lebih berpengalaman
3. **Hubungi administrator** sistem jika ada masalah teknis
4. **Ikuti pelatihan** jika tersedia

---

**Selamat menggunakan aplikasi BUMDES! Semoga membantu mengelola keuangan desa dengan lebih baik.**

*Panduan ini dibuat untuk memudahkan penggunaan aplikasi. Jika ada saran perbaikan, silakan sampaikan kepada pengurus.*