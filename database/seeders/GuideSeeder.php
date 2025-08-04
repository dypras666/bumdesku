<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guide;
use App\Models\User;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the first superadmin user
        $superadminRole = \App\Models\Role::where('name', 'super_admin')->first();
        $superadmin = $superadminRole ? User::where('role_id', $superadminRole->id)->first() : null;
        
        if (!$superadmin) {
            $this->command->warn('No superadmin user found. Creating guides without creator.');
        }

        $guides = [
            // Getting Started
            [
                'title' => 'Selamat Datang di Sistem BUMDES',
                'slug' => 'selamat-datang-di-sistem-bumdes',
                'content' => '
# Selamat Datang di Sistem BUMDES

Sistem BUMDES (Badan Usaha Milik Desa) adalah platform manajemen keuangan yang dirancang khusus untuk membantu pengelolaan keuangan desa secara profesional dan transparan.

## Apa itu BUMDES?

BUMDES adalah badan usaha yang seluruh atau sebagian besar modalnya dimiliki oleh desa melalui penyertaan secara langsung yang berasal dari kekayaan desa yang dipisahkan guna mengelola aset, jasa pelayanan, dan usaha lainnya untuk sebesar-besarnya kesejahteraan masyarakat desa.

## Fitur Utama Sistem

### 1. Manajemen Transaksi
- Pencatatan transaksi harian
- Kategorisasi otomatis
- Validasi dan persetujuan

### 2. Laporan Keuangan
- Neraca
- Laporan Laba Rugi
- Laporan Arus Kas
- Buku Besar

### 3. Master Data
- Daftar Akun
- Data Unit Usaha
- Inventaris

### 4. Pengaturan Sistem
- Konfigurasi perusahaan
- Pengaturan keuangan
- Template laporan

## Memulai Penggunaan

1. **Login ke Sistem**: Gunakan kredensial yang telah diberikan
2. **Atur Profil Perusahaan**: Lengkapi informasi desa/BUMDES
3. **Setup Master Data**: Siapkan daftar akun dan unit usaha
4. **Mulai Transaksi**: Catat transaksi harian
5. **Generate Laporan**: Buat laporan keuangan berkala

## Dukungan dan Bantuan

Jika mengalami kesulitan, silakan hubungi administrator sistem atau merujuk ke panduan-panduan lainnya yang tersedia.
                ',
                'category' => 'getting-started',
                'order' => 1,
                'is_published' => true,
                'icon' => 'fas fa-home',
                'description' => 'Panduan pengenalan sistem BUMDES dan fitur-fitur utamanya',
                'created_by' => $superadmin?->id,
            ],
            [
                'title' => 'Cara Login dan Navigasi Dasar',
                'slug' => 'cara-login-dan-navigasi-dasar',
                'content' => '
# Cara Login dan Navigasi Dasar

## Login ke Sistem

### Langkah-langkah Login:
1. Buka halaman login sistem BUMDES
2. Masukkan email yang terdaftar
3. Masukkan password
4. Klik tombol "Login"

### Tips Keamanan:
- Gunakan password yang kuat
- Jangan bagikan kredensial login
- Logout setelah selesai menggunakan sistem
- Ganti password secara berkala

## Navigasi Dashboard

### Menu Utama:
- **Dashboard**: Ringkasan informasi keuangan
- **Transaksi**: Pencatatan dan pengelolaan transaksi
- **Laporan Keuangan**: Berbagai jenis laporan
- **Master Data**: Pengelolaan data master
- **Pengaturan**: Konfigurasi sistem

### Sidebar Navigation:
- Menu dapat diklik untuk mengakses fitur
- Submenu akan muncul saat hover atau klik
- Breadcrumb menunjukkan lokasi halaman saat ini

## Fitur Umum Interface

### Search dan Filter:
- Gunakan kotak pencarian untuk mencari data
- Filter berdasarkan tanggal, kategori, atau status
- Sorting data dengan klik header kolom

### Pagination:
- Navigasi halaman di bagian bawah tabel
- Pilih jumlah data per halaman
- Lompat ke halaman tertentu

### Export Data:
- Tombol export tersedia di berbagai halaman
- Format: PDF, Excel, CSV
- Data yang diexport sesuai filter yang aktif

## Shortcut Keyboard

- **Ctrl + S**: Simpan form
- **Ctrl + N**: Tambah data baru
- **Esc**: Tutup modal/dialog
- **Enter**: Submit form
                ',
                'category' => 'getting-started',
                'order' => 2,
                'is_published' => true,
                'icon' => 'fas fa-sign-in-alt',
                'description' => 'Panduan login dan navigasi dasar sistem BUMDES',
                'created_by' => $superadmin?->id,
            ],

            // Complete Year-End Guide
            [
                'title' => 'Panduan Lengkap: Dari Awal Hingga Laporan Akhir Tahun',
                'slug' => 'panduan-lengkap-dari-awal-hingga-laporan-akhir-tahun',
                'content' => '
# Panduan Lengkap: Dari Awal Hingga Laporan Akhir Tahun

Panduan komprehensif ini akan memandu Anda melalui seluruh proses pengelolaan keuangan BUMDES, mulai dari setup awal hingga pembuatan laporan akhir tahun.

## FASE 1: PERSIAPAN AWAL SISTEM

### 1.1 Setup Informasi Perusahaan
**Lokasi**: Menu Pengaturan → Pengaturan Perusahaan

**Langkah-langkah:**
1. **Akses Pengaturan Perusahaan**
   - Login sebagai administrator
   - Klik menu "Pengaturan" di sidebar
   - Pilih "Pengaturan Perusahaan"

2. **Lengkapi Informasi Dasar**
   - Nama BUMDES: [Nama lengkap BUMDES]
   - Alamat lengkap desa
   - Nomor telepon dan email
   - NPWP (jika ada)
   - Nomor SK pendirian BUMDES

3. **Upload Logo dan Dokumen**
   - Logo BUMDES (format PNG/JPG, max 2MB)
   - Kop surat resmi
   - Stempel digital (opsional)

4. **Pengaturan Keuangan**
   - Mata uang: Rupiah (IDR)
   - Format angka: 1.000.000,00
   - Tahun buku: Januari - Desember
   - Metode akuntansi: Akrual

### 1.2 Setup Master Data Akun
**Lokasi**: Menu Master Data → Daftar Akun

**Struktur Kode Akun BUMDES:**
```
1xxx - ASET
  11xx - Aset Lancar
    1101 - Kas
    1102 - Bank
    1103 - Piutang Usaha
    1104 - Persediaan Barang Dagang
    1105 - Biaya Dibayar Dimuka
  
  12xx - Aset Tetap
    1201 - Tanah
    1202 - Bangunan
    1203 - Kendaraan
    1204 - Peralatan
    1205 - Akumulasi Penyusutan

2xxx - KEWAJIBAN
  21xx - Kewajiban Lancar
    2101 - Utang Usaha
    2102 - Utang Gaji
    2103 - Utang Pajak
    2104 - Biaya Yang Masih Harus Dibayar
  
  22xx - Kewajiban Jangka Panjang
    2201 - Utang Bank
    2202 - Utang Investasi

3xxx - MODAL
  3101 - Modal Desa
  3102 - Modal Masyarakat
  3103 - Laba Ditahan
  3104 - Laba Tahun Berjalan

4xxx - PENDAPATAN
  4101 - Pendapatan Usaha Utama
  4102 - Pendapatan Usaha Sampingan
  4103 - Pendapatan Bunga
  4104 - Pendapatan Lain-lain

5xxx - BEBAN
  5101 - Harga Pokok Penjualan
  5102 - Beban Gaji
  5103 - Beban Listrik
  5104 - Beban Telepon
  5105 - Beban Transportasi
  5106 - Beban Penyusutan
  5107 - Beban Lain-lain
```

**Cara Input Master Akun:**
1. Klik "Tambah Akun Baru"
2. Masukkan kode akun (4 digit)
3. Nama akun yang jelas dan deskriptif
4. Pilih kategori (Aset/Kewajiban/Modal/Pendapatan/Beban)
5. Masukkan saldo awal (jika ada)
6. Simpan

### 1.3 Setup Master Data Unit Usaha
**Lokasi**: Menu Master Data → Unit Usaha

**Contoh Unit Usaha BUMDES:**
- Unit Simpan Pinjam
- Unit Perdagangan
- Unit Jasa
- Unit Produksi
- Unit Pariwisata

**Data yang diperlukan:**
- Nama unit usaha
- Kategori usaha
- Penanggung jawab
- Modal awal unit
- Target pendapatan

### 1.4 Setup Master Data Inventaris
**Lokasi**: Menu Master Data → Inventaris

**Kategori Inventaris:**
- Barang Dagang
- Bahan Baku
- Aset Tetap
- Perlengkapan Kantor

## FASE 2: OPERASIONAL HARIAN

### 2.1 Pencatatan Transaksi Harian
**Lokasi**: Menu Transaksi → Tambah Transaksi

**Jenis Transaksi Umum:**

**A. Transaksi Penerimaan:**
1. **Penjualan Tunai**
   - Debit: 1101 (Kas) - Rp xxx
   - Kredit: 4101 (Pendapatan Usaha) - Rp xxx

2. **Penjualan Kredit**
   - Debit: 1103 (Piutang Usaha) - Rp xxx
   - Kredit: 4101 (Pendapatan Usaha) - Rp xxx

3. **Penerimaan Piutang**
   - Debit: 1101 (Kas) - Rp xxx
   - Kredit: 1103 (Piutang Usaha) - Rp xxx

**B. Transaksi Pengeluaran:**
1. **Pembelian Tunai**
   - Debit: 1104 (Persediaan) - Rp xxx
   - Kredit: 1101 (Kas) - Rp xxx

2. **Pembelian Kredit**
   - Debit: 1104 (Persediaan) - Rp xxx
   - Kredit: 2101 (Utang Usaha) - Rp xxx

3. **Pembayaran Beban**
   - Debit: 5xxx (Beban terkait) - Rp xxx
   - Kredit: 1101 (Kas) - Rp xxx

### 2.2 Prosedur Pencatatan Transaksi
1. **Persiapan Dokumen**
   - Kumpulkan semua bukti transaksi
   - Pastikan dokumen lengkap dan sah
   - Urutkan berdasarkan tanggal

2. **Input Transaksi**
   - Buka menu Transaksi
   - Klik "Tambah Transaksi Baru"
   - Pilih jenis transaksi
   - Masukkan tanggal transaksi
   - Pilih akun debit dan kredit
   - Input nominal (harus balance)
   - Tambahkan keterangan yang jelas
   - Upload bukti transaksi (scan/foto)

3. **Validasi dan Approval**
   - Periksa kembali data yang diinput
   - Pastikan debit = kredit
   - Submit untuk approval (jika diperlukan)
   - Transaksi otomatis masuk ke buku besar

### 2.3 Monitoring Harian
**Dashboard yang perlu dipantau:**
- Saldo kas harian
- Total penjualan hari ini
- Outstanding piutang
- Utang yang jatuh tempo
- Stok barang kritis

## FASE 3: PELAPORAN BERKALA

### 3.1 Laporan Mingguan
**Yang perlu dibuat:**
- Ringkasan transaksi mingguan
- Posisi kas dan bank
- Aging piutang
- Status utang

### 3.2 Laporan Bulanan
**Lokasi**: Menu Laporan Keuangan

**Laporan yang wajib dibuat:**

**A. Neraca Bulanan**
1. Akses menu "Laporan Keuangan"
2. Pilih "Neraca"
3. Set periode: 1 bulan
4. Klik "Generate Laporan"
5. Review dan export PDF

**B. Laporan Laba Rugi**
1. Pilih "Laporan Laba Rugi"
2. Set periode: 1 bulan
3. Generate dan review
4. Analisis margin keuntungan

**C. Laporan Arus Kas**
1. Pilih "Laporan Arus Kas"
2. Set periode: 1 bulan
3. Kategorikan: Operasional, Investasi, Pendanaan
4. Generate laporan

**D. Buku Besar**
1. Pilih "Buku Besar"
2. Pilih akun yang ingin dilihat
3. Set periode
4. Review mutasi per akun

### 3.3 Analisis Kinerja Bulanan
**Indikator yang perlu dianalisis:**
- Pertumbuhan pendapatan
- Efisiensi biaya operasional
- Tingkat kolektibilitas piutang
- Perputaran persediaan
- ROI per unit usaha

## FASE 4: PERSIAPAN AKHIR TAHUN

### 4.1 Penyesuaian Akhir Tahun (Adjusting Entries)
**Lokasi**: Menu Transaksi → Jurnal Penyesuaian

**Jenis Penyesuaian:**

**A. Penyusutan Aset Tetap**
```
Debit: 5106 (Beban Penyusutan) - Rp xxx
Kredit: 1205 (Akumulasi Penyusutan) - Rp xxx
```

**B. Penyesuaian Persediaan**
```
Debit: 5101 (HPP) - Rp xxx
Kredit: 1104 (Persediaan) - Rp xxx
```

**C. Biaya Dibayar Dimuka**
```
Debit: 5xxx (Beban terkait) - Rp xxx
Kredit: 1105 (Biaya Dibayar Dimuka) - Rp xxx
```

**D. Biaya Yang Masih Harus Dibayar**
```
Debit: 5xxx (Beban terkait) - Rp xxx
Kredit: 2104 (Biaya YMH Dibayar) - Rp xxx
```

### 4.2 Rekonsiliasi Bank
**Langkah-langkah:**
1. Dapatkan rekening koran dari bank
2. Bandingkan dengan catatan kas/bank di sistem
3. Identifikasi perbedaan
4. Buat jurnal penyesuaian jika diperlukan
5. Pastikan saldo sesuai

### 4.3 Inventarisasi Fisik
**Proses:**
1. Hitung fisik semua aset tetap
2. Hitung persediaan barang dagang
3. Bandingkan dengan catatan sistem
4. Buat adjustment jika ada selisih
5. Update nilai aset jika diperlukan

## FASE 5: LAPORAN AKHIR TAHUN

### 5.1 Neraca Akhir Tahun
**Lokasi**: Menu Laporan Keuangan → Neraca

**Langkah-langkah:**
1. Pastikan semua transaksi sudah diinput
2. Lakukan semua jurnal penyesuaian
3. Generate Neraca periode 1 Jan - 31 Des
4. Review keseimbangan Aset = Kewajiban + Modal
5. Export dalam format PDF dan Excel

**Komponen Neraca:**
```
ASET
├── Aset Lancar
│   ├── Kas dan Setara Kas
│   ├── Piutang Usaha
│   ├── Persediaan
│   └── Biaya Dibayar Dimuka
└── Aset Tetap
    ├── Tanah
    ├── Bangunan
    ├── Kendaraan
    ├── Peralatan
    └── (Akumulasi Penyusutan)

KEWAJIBAN DAN MODAL
├── Kewajiban Lancar
│   ├── Utang Usaha
│   ├── Utang Gaji
│   └── Biaya YMH Dibayar
├── Kewajiban Jangka Panjang
│   └── Utang Bank
└── Modal
    ├── Modal Desa
    ├── Modal Masyarakat
    ├── Laba Ditahan
    └── Laba Tahun Berjalan
```

### 5.2 Laporan Laba Rugi Tahunan
**Struktur Laporan:**
```
PENDAPATAN
├── Pendapatan Usaha Utama
├── Pendapatan Usaha Sampingan
├── Pendapatan Bunga
└── Pendapatan Lain-lain
TOTAL PENDAPATAN

BEBAN
├── Harga Pokok Penjualan
├── Beban Operasional
│   ├── Beban Gaji
│   ├── Beban Listrik
│   ├── Beban Transportasi
│   └── Beban Lainnya
├── Beban Penyusutan
└── Beban Non-Operasional
TOTAL BEBAN

LABA BERSIH TAHUN BERJALAN
```

### 5.3 Laporan Arus Kas Tahunan
**Kategori Arus Kas:**

**A. Arus Kas Operasional**
- Penerimaan dari pelanggan
- Pembayaran kepada pemasok
- Pembayaran gaji karyawan
- Pembayaran beban operasional

**B. Arus Kas Investasi**
- Pembelian aset tetap
- Penjualan aset tetap
- Investasi jangka panjang

**C. Arus Kas Pendanaan**
- Penerimaan modal
- Pembayaran dividen
- Penerimaan/pembayaran utang

### 5.4 Catatan Atas Laporan Keuangan
**Informasi yang harus disertakan:**
1. **Gambaran Umum BUMDES**
   - Sejarah dan kegiatan usaha
   - Struktur organisasi
   - Kebijakan akuntansi

2. **Penjelasan Pos-pos Neraca**
   - Rincian aset tetap
   - Kebijakan penyusutan
   - Komposisi utang
   - Perubahan modal

3. **Penjelasan Pos-pos Laba Rugi**
   - Sumber pendapatan utama
   - Komponen beban terbesar
   - Pos-pos luar biasa

4. **Komitmen dan Kontinjensi**
   - Kontrak jangka panjang
   - Jaminan yang diberikan
   - Perkara hukum (jika ada)

## FASE 6: ANALISIS DAN EVALUASI

### 6.1 Analisis Rasio Keuangan
**Rasio Likuiditas:**
- Current Ratio = Aset Lancar / Kewajiban Lancar
- Quick Ratio = (Aset Lancar - Persediaan) / Kewajiban Lancar

**Rasio Profitabilitas:**
- Gross Profit Margin = (Pendapatan - HPP) / Pendapatan
- Net Profit Margin = Laba Bersih / Pendapatan
- ROA = Laba Bersih / Total Aset
- ROE = Laba Bersih / Total Modal

**Rasio Aktivitas:**
- Perputaran Piutang = Pendapatan / Rata-rata Piutang
- Perputaran Persediaan = HPP / Rata-rata Persediaan

### 6.2 Evaluasi Kinerja per Unit Usaha
**Metrik yang dievaluasi:**
- Kontribusi pendapatan
- Margin keuntungan
- Efisiensi operasional
- Pertumbuhan year-over-year

### 6.3 Rekomendasi untuk Tahun Berikutnya
**Berdasarkan analisis, buat rekomendasi:**
- Strategi peningkatan pendapatan
- Efisiensi biaya operasional
- Investasi aset produktif
- Pengembangan unit usaha baru

## FASE 7: PELAPORAN DAN PERTANGGUNGJAWABAN

### 7.1 Penyusunan Laporan Tahunan
**Komponen Laporan Tahunan:**
1. **Kata Pengantar Direktur**
2. **Profil BUMDES**
3. **Laporan Keuangan Audited**
4. **Analisis Kinerja Keuangan**
5. **Program Kerja Tahun Depan**
6. **Lampiran-lampiran**

### 7.2 Presentasi ke Stakeholder
**Persiapan Presentasi:**
- Ringkasan eksekutif
- Highlight pencapaian
- Tantangan yang dihadapi
- Rencana pengembangan
- Q&A session

### 7.3 Dokumentasi dan Arsip
**Dokumen yang harus diarsipkan:**
- Laporan keuangan lengkap
- Bukti-bukti transaksi
- Notulen rapat
- Kontrak dan perjanjian
- Dokumen legal

## CHECKLIST AKHIR TAHUN

### ✅ Persiapan
- [ ] Semua transaksi sudah diinput
- [ ] Rekonsiliasi bank selesai
- [ ] Inventarisasi fisik selesai
- [ ] Jurnal penyesuaian dibuat

### ✅ Laporan Keuangan
- [ ] Neraca akhir tahun
- [ ] Laporan Laba Rugi
- [ ] Laporan Arus Kas
- [ ] Catatan atas Laporan Keuangan

### ✅ Analisis
- [ ] Analisis rasio keuangan
- [ ] Evaluasi kinerja unit usaha
- [ ] Benchmark dengan tahun sebelumnya

### ✅ Pelaporan
- [ ] Laporan tahunan disusun
- [ ] Presentasi ke stakeholder
- [ ] Dokumentasi diarsipkan

## TIPS SUKSES

1. **Konsistensi**: Catat transaksi secara rutin dan tepat waktu
2. **Akurasi**: Pastikan semua data akurat dan dapat dipertanggungjawabkan
3. **Backup**: Selalu backup data secara berkala
4. **Training**: Latih semua user untuk menggunakan sistem dengan benar
5. **Review**: Lakukan review berkala untuk memastikan kualitas data
6. **Compliance**: Pastikan semua laporan sesuai standar akuntansi

## KONTAK DUKUNGAN

Jika mengalami kesulitan dalam proses ini, silakan hubungi:
- Administrator Sistem
- Tim IT Support
- Konsultan Keuangan BUMDES

---

*Panduan ini merupakan roadmap lengkap untuk mengelola keuangan BUMDES dari awal hingga akhir tahun. Ikuti setiap fase secara berurutan untuk hasil yang optimal.*
                ',
                'category' => 'comprehensive',
                'order' => 1,
                'is_published' => true,
                'icon' => 'fas fa-route',
                'description' => 'Panduan komprehensif dari setup awal hingga laporan akhir tahun',
                'created_by' => $superadmin?->id,
            ],

            // Financial Management
            [
                'title' => 'Pengelolaan Transaksi Keuangan',
                'slug' => 'pengelolaan-transaksi-keuangan',
                'content' => '
# Pengelolaan Transaksi Keuangan

## Jenis-jenis Transaksi

### 1. Transaksi Penerimaan (Debit)
- Penjualan produk/jasa
- Pendapatan bunga
- Hibah dan bantuan
- Modal awal

### 2. Transaksi Pengeluaran (Kredit)
- Pembelian bahan baku
- Biaya operasional
- Gaji karyawan
- Investasi aset

## Cara Mencatat Transaksi

### Langkah-langkah:
1. **Akses Menu Transaksi**
   - Klik menu "Transaksi" di sidebar
   - Pilih "Tambah Transaksi Baru"

2. **Isi Form Transaksi**
   - Pilih jenis transaksi
   - Masukkan tanggal transaksi
   - Pilih akun yang terkait
   - Masukkan jumlah nominal
   - Tambahkan keterangan

3. **Validasi Data**
   - Periksa kembali data yang diinput
   - Pastikan akun sudah benar
   - Konfirmasi nominal

4. **Simpan Transaksi**
   - Klik tombol "Simpan"
   - Transaksi akan masuk ke jurnal

## Prinsip Double Entry

Sistem menggunakan prinsip double entry bookkeeping:
- Setiap transaksi mempengaruhi minimal 2 akun
- Total debit harus sama dengan total kredit
- Sistem otomatis memvalidasi keseimbangan

## Kode Akun dan Kategorisasi

### Struktur Kode Akun:
- **1xxx**: Aset (Harta)
- **2xxx**: Kewajiban (Utang)
- **3xxx**: Modal/Ekuitas
- **4xxx**: Pendapatan
- **5xxx**: Beban/Biaya

### Contoh Penggunaan:
- 1101: Kas di Tangan
- 1102: Bank BRI
- 4101: Pendapatan Penjualan
- 5101: Beban Gaji

## Tips Pencatatan yang Baik

1. **Konsistensi**: Gunakan format yang sama
2. **Kelengkapan**: Isi semua field yang diperlukan
3. **Akurasi**: Periksa nominal dan akun
4. **Keterangan**: Berikan deskripsi yang jelas
5. **Tepat Waktu**: Catat transaksi sesegera mungkin
                ',
                'category' => 'financial-management',
                'order' => 1,
                'is_published' => true,
                'icon' => 'fas fa-exchange-alt',
                'description' => 'Panduan lengkap pengelolaan transaksi keuangan dalam sistem BUMDES',
                'created_by' => $superadmin?->id,
            ],
            [
                'title' => 'Membuat dan Membaca Laporan Keuangan',
                'slug' => 'membuat-dan-membaca-laporan-keuangan',
                'content' => '
# Membuat dan Membaca Laporan Keuangan

## Jenis Laporan Keuangan

### 1. Neraca (Balance Sheet)
**Tujuan**: Menunjukkan posisi keuangan pada tanggal tertentu

**Komponen**:
- **Aset**: Harta yang dimiliki
- **Kewajiban**: Utang yang harus dibayar
- **Ekuitas**: Modal bersih

**Rumus**: Aset = Kewajiban + Ekuitas

### 2. Laporan Laba Rugi (Income Statement)
**Tujuan**: Menunjukkan kinerja keuangan dalam periode tertentu

**Komponen**:
- **Pendapatan**: Pemasukan dari operasional
- **Beban**: Pengeluaran untuk operasional
- **Laba/Rugi**: Selisih pendapatan dan beban

### 3. Laporan Arus Kas (Cash Flow)
**Tujuan**: Menunjukkan pergerakan kas masuk dan keluar

**Kategori**:
- **Operasional**: Aktivitas usaha utama
- **Investasi**: Pembelian/penjualan aset
- **Pendanaan**: Modal dan pinjaman

## Cara Generate Laporan

### Langkah-langkah:
1. **Akses Menu Laporan**
   - Klik "Laporan Keuangan" di sidebar
   - Pilih jenis laporan yang diinginkan

2. **Set Parameter**
   - Pilih periode laporan (dari-sampai)
   - Tentukan format output
   - Atur filter jika diperlukan

3. **Generate Laporan**
   - Klik tombol "Generate"
   - Tunggu proses selesai
   - Review hasil laporan

4. **Export/Print**
   - Pilih format export (PDF/Excel)
   - Download atau print langsung

## Membaca dan Menganalisis Laporan

### Analisis Neraca:
- **Likuiditas**: Kemampuan bayar utang jangka pendek
- **Solvabilitas**: Kemampuan bayar utang total
- **Struktur Modal**: Perbandingan utang dan modal

### Analisis Laba Rugi:
- **Profitabilitas**: Tingkat keuntungan
- **Efisiensi**: Pengendalian biaya
- **Trend**: Perkembangan dari waktu ke waktu

### Analisis Arus Kas:
- **Arus Kas Operasional**: Kesehatan operasional
- **Arus Kas Investasi**: Pengembangan usaha
- **Arus Kas Pendanaan**: Struktur pembiayaan

## Tips Pelaporan yang Efektif

1. **Rutin**: Buat laporan secara berkala
2. **Akurat**: Pastikan data transaksi lengkap
3. **Konsisten**: Gunakan metode yang sama
4. **Analisis**: Jangan hanya buat, tapi analisis juga
5. **Dokumentasi**: Simpan laporan dengan baik
                ',
                'category' => 'financial-management',
                'order' => 2,
                'is_published' => true,
                'icon' => 'fas fa-chart-line',
                'description' => 'Panduan membuat dan menganalisis laporan keuangan BUMDES',
                'created_by' => $superadmin?->id,
            ],

            // System Administration
            [
                'title' => 'Pengaturan Master Data',
                'slug' => 'pengaturan-master-data',
                'content' => '
# Pengaturan Master Data

## Daftar Akun (Chart of Accounts)

### Struktur Akun:
Sistem menggunakan struktur akun standar akuntansi:

**1. ASET (1xxx)**
- 11xx: Aset Lancar
  - 1101: Kas
  - 1102: Bank
  - 1103: Piutang Usaha
  - 1104: Persediaan
- 12xx: Aset Tetap
  - 1201: Tanah
  - 1202: Bangunan
  - 1203: Kendaraan
  - 1204: Peralatan

**2. KEWAJIBAN (2xxx)**
- 21xx: Utang Lancar
  - 2101: Utang Usaha
  - 2102: Utang Gaji
  - 2103: Utang Pajak
- 22xx: Utang Jangka Panjang
  - 2201: Utang Bank
  - 2202: Utang Obligasi

**3. EKUITAS (3xxx)**
- 3101: Modal Disetor
- 3102: Laba Ditahan
- 3103: Laba Tahun Berjalan

**4. PENDAPATAN (4xxx)**
- 4101: Pendapatan Penjualan
- 4102: Pendapatan Jasa
- 4103: Pendapatan Lain-lain

**5. BEBAN (5xxx)**
- 5101: Beban Gaji
- 5102: Beban Listrik
- 5103: Beban Telepon
- 5104: Beban Penyusutan

### Cara Mengelola Akun:
1. **Tambah Akun Baru**
   - Akses menu Master Data > Daftar Akun
   - Klik "Tambah Akun"
   - Isi kode, nama, dan kategori
   - Set saldo awal jika diperlukan

2. **Edit Akun**
   - Pilih akun yang akan diedit
   - Update informasi yang diperlukan
   - Simpan perubahan

3. **Hapus Akun**
   - Pastikan akun tidak digunakan dalam transaksi
   - Pilih akun dan hapus

## Master Unit Usaha

### Jenis Unit Usaha BUMDES:
- **Unit Simpan Pinjam**: Layanan keuangan mikro
- **Unit Perdagangan**: Toko/warung desa
- **Unit Jasa**: Layanan masyarakat
- **Unit Produksi**: Pengolahan produk lokal
- **Unit Pariwisata**: Wisata desa

### Pengelolaan Unit:
1. **Registrasi Unit Baru**
   - Tentukan nama dan jenis unit
   - Set penanggung jawab
   - Alokasikan modal awal

2. **Monitoring Kinerja**
   - Track pendapatan per unit
   - Monitor biaya operasional
   - Evaluasi profitabilitas

## Master Inventaris

### Kategori Inventaris:
- **Barang Dagangan**: Produk untuk dijual
- **Bahan Baku**: Material produksi
- **Perlengkapan**: Alat operasional
- **Aset Tetap**: Peralatan investasi

### Pengelolaan Inventaris:
1. **Input Data Barang**
   - Kode barang unik
   - Nama dan deskripsi
   - Harga beli dan jual
   - Stok minimum

2. **Update Stok**
   - Catat pembelian
   - Catat penjualan
   - Adjustment stok
   - Stock opname berkala

## Best Practices

1. **Konsistensi Koding**: Gunakan sistem kode yang konsisten
2. **Dokumentasi**: Catat perubahan master data
3. **Backup**: Backup data master secara rutin
4. **Review**: Review dan update data secara berkala
5. **Training**: Pastikan user memahami struktur data
                ',
                'category' => 'system-administration',
                'order' => 1,
                'is_published' => true,
                'icon' => 'fas fa-database',
                'description' => 'Panduan pengaturan dan pengelolaan master data sistem BUMDES',
                'created_by' => $superadmin?->id,
            ],
            [
                'title' => 'Manajemen User dan Hak Akses',
                'slug' => 'manajemen-user-dan-hak-akses',
                'content' => '
# Manajemen User dan Hak Akses

## Tingkatan User dalam Sistem

### 1. Superadmin
**Hak Akses Penuh**:
- Kelola semua fitur sistem
- Manajemen user dan role
- Pengaturan sistem global
- Backup dan restore data
- Akses ke semua laporan

### 2. Admin
**Hak Akses Terbatas**:
- Kelola transaksi keuangan
- Generate laporan keuangan
- Kelola master data
- Tidak bisa kelola user lain

### 3. User/Operator
**Hak Akses Dasar**:
- Input transaksi harian
- Lihat laporan tertentu
- Update data terbatas
- Tidak bisa hapus data penting

### 4. Viewer
**Hak Akses Baca**:
- Lihat dashboard
- Lihat laporan (tanpa export)
- Tidak bisa input/edit data

## Cara Mengelola User

### Menambah User Baru:
1. **Akses Menu User Management**
   - Login sebagai superadmin/admin
   - Klik menu "Manajemen User"

2. **Isi Form User Baru**
   - Nama lengkap
   - Email (sebagai username)
   - Password sementara
   - Pilih role/tingkatan

3. **Set Hak Akses Spesifik**
   - Tentukan modul yang bisa diakses
   - Set batasan data (jika perlu)
   - Atur periode akses

4. **Aktivasi User**
   - Kirim kredensial ke user
   - Minta user ganti password
   - Verifikasi akses pertama

### Mengedit User Existing:
1. **Cari User**
   - Gunakan fitur search
   - Filter berdasarkan role
   - Pilih user yang akan diedit

2. **Update Informasi**
   - Edit nama/email
   - Ganti role jika perlu
   - Reset password jika diminta

3. **Kelola Status**
   - Aktif/nonaktif user
   - Suspend sementara
   - Hapus user (hati-hati)

## Pengaturan Keamanan

### Password Policy:
- Minimal 8 karakter
- Kombinasi huruf, angka, simbol
- Tidak boleh sama dengan data pribadi
- Wajib ganti setiap 3 bulan

### Session Management:
- Auto logout setelah idle
- Single session per user
- Log aktivitas user
- Deteksi login mencurigakan

### Data Access Control:
- Principle of least privilege
- Segregation of duties
- Audit trail semua aktivitas
- Backup log secara rutin

## Monitoring dan Audit

### Log Aktivitas User:
- Login/logout time
- Transaksi yang dilakukan
- Data yang diakses/diubah
- Export/print laporan

### Review Berkala:
- Evaluasi hak akses user
- Remove user yang tidak aktif
- Update role sesuai kebutuhan
- Training keamanan sistem

## Troubleshooting User Issues

### User Lupa Password:
1. Verifikasi identitas user
2. Reset password melalui admin
3. Kirim password sementara
4. Minta user ganti password

### User Tidak Bisa Login:
1. Cek status user (aktif/nonaktif)
2. Verifikasi email/username
3. Cek koneksi internet
4. Clear browser cache

### Hak Akses Bermasalah:
1. Review role assignment
2. Cek permission spesifik
3. Logout dan login ulang
4. Hubungi superadmin

## Best Practices

1. **Regular Review**: Review user access secara berkala
2. **Documentation**: Dokumentasi perubahan user
3. **Training**: Training keamanan untuk semua user
4. **Backup**: Backup data user dan permission
5. **Monitoring**: Monitor aktivitas user mencurigakan
                ',
                'category' => 'system-administration',
                'order' => 2,
                'is_published' => true,
                'icon' => 'fas fa-users-cog',
                'description' => 'Panduan manajemen user dan pengaturan hak akses dalam sistem BUMDES',
                'created_by' => $superadmin?->id,
            ],

            // Troubleshooting
            [
                'title' => 'Mengatasi Masalah Umum Sistem',
                'slug' => 'mengatasi-masalah-umum-sistem',
                'content' => '
# Mengatasi Masalah Umum Sistem

## Masalah Login dan Akses

### 1. Tidak Bisa Login
**Gejala**: Error saat memasukkan email/password

**Solusi**:
- Pastikan email dan password benar
- Cek caps lock pada keyboard
- Clear browser cache dan cookies
- Coba browser lain
- Hubungi admin untuk reset password

### 2. Session Expired
**Gejala**: Otomatis logout saat bekerja

**Solusi**:
- Login ulang ke sistem
- Simpan pekerjaan secara berkala
- Jangan idle terlalu lama
- Tutup tab browser yang tidak perlu

### 3. Hak Akses Ditolak
**Gejala**: Pesan "Access Denied" saat akses fitur

**Solusi**:
- Cek role/permission user
- Hubungi admin untuk update akses
- Pastikan user masih aktif
- Logout dan login ulang

## Masalah Input Data

### 1. Form Tidak Bisa Disimpan
**Gejala**: Error saat klik tombol simpan

**Solusi**:
- Cek semua field wajib sudah diisi
- Pastikan format data sesuai (tanggal, angka)
- Cek koneksi internet
- Refresh halaman dan coba lagi
- Cek apakah ada karakter khusus

### 2. Data Tidak Muncul
**Gejala**: Data yang diinput tidak tampil di list

**Solusi**:
- Refresh halaman
- Cek filter yang aktif
- Pastikan data tersimpan dengan benar
- Cek periode/tanggal filter
- Clear browser cache

### 3. Error Validasi
**Gejala**: Pesan error validasi muncul

**Solusi**:
- Baca pesan error dengan teliti
- Perbaiki field yang bermasalah
- Pastikan format sesuai ketentuan
- Cek duplikasi data
- Hubungi admin jika masih error

## Masalah Laporan

### 1. Laporan Kosong
**Gejala**: Laporan tidak menampilkan data

**Solusi**:
- Cek periode laporan
- Pastikan ada transaksi di periode tersebut
- Cek filter yang diterapkan
- Verifikasi data transaksi
- Generate ulang laporan

### 2. Error saat Export
**Gejala**: Gagal download laporan PDF/Excel

**Solusi**:
- Cek koneksi internet
- Coba format export lain
- Kurangi periode laporan
- Clear browser download
- Coba browser lain

### 3. Data Laporan Tidak Sesuai
**Gejala**: Angka di laporan tidak match dengan ekspektasi

**Solusi**:
- Cek transaksi di periode tersebut
- Verifikasi posting ke buku besar
- Cek saldo awal akun
- Review kategorisasi transaksi
- Hubungi admin untuk investigasi

## Masalah Performa

### 1. Sistem Lambat
**Gejala**: Loading lama saat akses fitur

**Solusi**:
- Cek koneksi internet
- Close aplikasi lain yang berat
- Clear browser cache
- Restart browser
- Coba waktu akses yang berbeda

### 2. Timeout Error
**Gejala**: Pesan timeout saat proses data

**Solusi**:
- Kurangi range data yang diproses
- Coba saat traffic rendah
- Refresh dan coba lagi
- Hubungi admin jika persisten

## Masalah Browser

### 1. Tampilan Berantakan
**Gejala**: Layout tidak normal

**Solusi**:
- Clear browser cache
- Update browser ke versi terbaru
- Coba browser lain (Chrome, Firefox)
- Disable browser extension
- Cek resolusi layar

### 2. Fitur Tidak Berfungsi
**Gejala**: Button/link tidak respond

**Solusi**:
- Enable JavaScript di browser
- Disable ad blocker
- Clear cookies
- Coba incognito/private mode
- Update browser

## Kapan Hubungi Admin

Hubungi administrator sistem jika:
- Masalah persisten setelah troubleshooting
- Error yang berkaitan dengan data keuangan
- Butuh reset password atau update akses
- Masalah yang mempengaruhi banyak user
- Kehilangan data penting

## Informasi yang Perlu Disiapkan

Saat melaporkan masalah, siapkan:
- Screenshot error message
- Langkah-langkah yang dilakukan
- Browser dan versi yang digunakan
- Waktu kejadian masalah
- Data yang terkait (jika ada)

## Pencegahan Masalah

1. **Backup Data**: Backup data penting secara rutin
2. **Update Browser**: Gunakan browser versi terbaru
3. **Koneksi Stabil**: Pastikan koneksi internet stabil
4. **Training**: Ikuti training penggunaan sistem
5. **Dokumentasi**: Catat prosedur kerja yang benar
                ',
                'category' => 'troubleshooting',
                'order' => 1,
                'is_published' => true,
                'icon' => 'fas fa-tools',
                'description' => 'Panduan mengatasi masalah umum yang sering terjadi dalam sistem BUMDES',
                'created_by' => $superadmin?->id,
            ],

            // Best Practices
            [
                'title' => 'Best Practices Pengelolaan Keuangan BUMDES',
                'slug' => 'best-practices-pengelolaan-keuangan-bumdes',
                'content' => '
# Best Practices Pengelolaan Keuangan BUMDES

## Prinsip Dasar Pengelolaan Keuangan

### 1. Transparansi
- **Keterbukaan Informasi**: Semua transaksi harus tercatat dan dapat dipertanggungjawabkan
- **Akses Publik**: Laporan keuangan dapat diakses oleh masyarakat desa
- **Dokumentasi Lengkap**: Setiap transaksi harus memiliki bukti yang sah

### 2. Akuntabilitas
- **Pertanggungjawaban**: Setiap penggunaan dana harus dapat dipertanggungjawabkan
- **Audit Trail**: Jejak audit yang jelas dari setiap transaksi
- **Pelaporan Berkala**: Laporan rutin kepada stakeholder

### 3. Efisiensi
- **Optimalisasi Sumber Daya**: Gunakan dana secara efisien dan efektif
- **Minimalisasi Biaya**: Hindari pemborosan dalam operasional
- **ROI Positif**: Pastikan investasi memberikan return yang positif

## Standar Operasional Prosedur (SOP)

### SOP Pencatatan Transaksi:
1. **Persiapan Dokumen**
   - Kumpulkan semua bukti transaksi
   - Verifikasi kelengkapan dokumen
   - Pastikan dokumen asli dan sah

2. **Input Data**
   - Input transaksi pada hari yang sama
   - Gunakan kode akun yang tepat
   - Berikan keterangan yang jelas dan lengkap

3. **Verifikasi**
   - Double check nominal dan akun
   - Pastikan keseimbangan debit-kredit
   - Review oleh supervisor

4. **Approval**
   - Persetujuan dari pihak berwenang
   - Dokumentasi approval
   - Posting ke buku besar

### SOP Pelaporan Keuangan:
1. **Persiapan Data**
   - Pastikan semua transaksi tercatat
   - Lakukan rekonsiliasi bank
   - Verifikasi saldo akun

2. **Generate Laporan**
   - Buat laporan sesuai periode
   - Review akurasi data
   - Cross-check antar laporan

3. **Review dan Approval**
   - Review oleh kepala unit
   - Approval oleh direktur
   - Dokumentasi persetujuan

4. **Distribusi**
   - Kirim ke stakeholder terkait
   - Publikasi sesuai ketentuan
   - Arsip laporan dengan baik

## Pengendalian Internal

### 1. Segregation of Duties
- **Pemisahan Fungsi**: Pisahkan fungsi otorisasi, pencatatan, dan penyimpanan
- **Dual Control**: Transaksi besar memerlukan persetujuan dua pihak
- **Rotasi Tugas**: Rotasi tugas secara berkala untuk mencegah fraud

### 2. Authorization Levels
- **Limit Otorisasi**: Tentukan batas otorisasi per level jabatan
- **Approval Matrix**: Matrix persetujuan yang jelas
- **Dokumentasi**: Semua otorisasi harus terdokumentasi

### 3. Physical Controls
- **Keamanan Kas**: Brankas dan sistem keamanan yang memadai
- **Akses Terbatas**: Batasi akses ke area keuangan
- **Backup Data**: Backup data secara rutin dan aman

## Manajemen Risiko Keuangan

### 1. Identifikasi Risiko
- **Risiko Kredit**: Risiko gagal bayar dari debitur
- **Risiko Likuiditas**: Risiko kekurangan kas operasional
- **Risiko Operasional**: Risiko dari proses internal
- **Risiko Pasar**: Risiko dari perubahan kondisi pasar

### 2. Mitigasi Risiko
- **Diversifikasi**: Diversifikasi sumber pendapatan
- **Asuransi**: Asuransi untuk aset penting
- **Cadangan**: Cadangan dana untuk emergency
- **Monitoring**: Monitor risiko secara berkala

### 3. Contingency Planning
- **Rencana Darurat**: Rencana untuk situasi krisis
- **Alternative Funding**: Sumber pendanaan alternatif
- **Recovery Plan**: Rencana pemulihan bisnis

## Compliance dan Regulasi

### 1. Peraturan Pemerintah
- **UU Desa**: Patuhi regulasi tentang desa
- **Peraturan Menteri**: Ikuti peraturan terkait BUMDES
- **Standar Akuntansi**: Gunakan standar akuntansi yang berlaku

### 2. Pelaporan Wajib
- **Laporan Bulanan**: Laporan rutin bulanan
- **Laporan Tahunan**: Laporan komprehensif tahunan
- **Laporan Khusus**: Laporan untuk program tertentu

### 3. Audit dan Review
- **Internal Audit**: Audit internal secara berkala
- **External Audit**: Audit eksternal tahunan
- **Management Review**: Review manajemen rutin

## Key Performance Indicators (KPI)

### 1. Financial KPIs
- **ROA (Return on Assets)**: Efektivitas penggunaan aset
- **ROE (Return on Equity)**: Return untuk pemegang saham
- **Current Ratio**: Kemampuan bayar utang jangka pendek
- **Debt to Equity**: Struktur permodalan

### 2. Operational KPIs
- **Revenue Growth**: Pertumbuhan pendapatan
- **Cost Efficiency**: Efisiensi biaya operasional
- **Customer Satisfaction**: Kepuasan pelanggan
- **Employee Productivity**: Produktivitas karyawan

### 3. Social Impact KPIs
- **Job Creation**: Penciptaan lapangan kerja
- **Community Development**: Pengembangan masyarakat
- **Environmental Impact**: Dampak lingkungan
- **Local Economic Growth**: Pertumbuhan ekonomi lokal

## Continuous Improvement

### 1. Regular Review
- **Monthly Review**: Review bulanan kinerja
- **Quarterly Assessment**: Evaluasi triwulanan
- **Annual Planning**: Perencanaan tahunan

### 2. Training dan Development
- **Staff Training**: Training rutin untuk staff
- **Management Development**: Pengembangan manajemen
- **Technology Update**: Update teknologi dan sistem

### 3. Innovation
- **Process Improvement**: Perbaikan proses berkelanjutan
- **Technology Adoption**: Adopsi teknologi baru
- **Best Practice Sharing**: Berbagi best practice

## Kesimpulan

Pengelolaan keuangan BUMDES yang baik memerlukan:
- Komitmen dari semua pihak
- Sistem dan prosedur yang jelas
- Monitoring dan evaluasi berkelanjutan
- Adaptasi terhadap perubahan
- Focus pada tujuan sosial dan ekonomi
                ',
                'category' => 'best-practices',
                'order' => 1,
                'is_published' => true,
                'icon' => 'fas fa-star',
                'description' => 'Panduan best practices untuk pengelolaan keuangan BUMDES yang efektif dan transparan',
                'created_by' => $superadmin?->id,
            ],
        ];

        foreach ($guides as $guide) {
            Guide::updateOrCreate(
                ['slug' => $guide['slug']], // Find by slug
                $guide // Update or create with this data
            );
        }

        $this->command->info('Guide seeder completed successfully!');
    }
}
