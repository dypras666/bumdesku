# DOKUMENTASI MASTER DATA DAN JURNAL
## Sistem Informasi BUMDES

---

## DAFTAR ISI

1. [Pengantar](#pengantar)
2. [Master Data](#master-data)
   - [Master Akun](#master-akun)
   - [Master Unit](#master-unit)
   - [Master Persediaan](#master-persediaan)
3. [Sistem Jurnal](#sistem-jurnal)
   - [Konsep Dasar](#konsep-dasar)
   - [Jenis Transaksi](#jenis-transaksi)
   - [Cara Input Jurnal](#cara-input-jurnal)
4. [Penjelasan Detail Akun](#penjelasan-detail-akun)
   - [Akun Aset](#akun-aset)
   - [Akun Kewajiban](#akun-kewajiban)
   - [Akun Modal](#akun-modal)
   - [Akun Pendapatan](#akun-pendapatan)
   - [Akun Beban](#akun-beban)
5. [Contoh Kasus Praktis](#contoh-kasus-praktis)
6. [Tips dan Best Practice](#tips-dan-best-practice)

---

## PENGANTAR

Sistem BUMDES menggunakan metode **double-entry bookkeeping** (pembukuan berpasangan) di mana setiap transaksi akan mempengaruhi minimal 2 akun dengan jumlah debit = kredit.

### Prinsip Dasar:
- **DEBIT** = Sisi kiri, menambah aset dan beban, mengurangi kewajiban, modal, dan pendapatan
- **KREDIT** = Sisi kanan, menambah kewajiban, modal, dan pendapatan, mengurangi aset dan beban
- **Total Debit = Total Kredit** (harus selalu seimbang)

---

## MASTER DATA

### MASTER AKUN

Master Akun adalah daftar semua akun yang digunakan dalam sistem akuntansi BUMDES.

#### Struktur Kode Akun:
```
Format: X-XXXX
Contoh: 1-1001, 2-2001, 3-3001

Digit Pertama = Kategori:
1 = ASET
2 = KEWAJIBAN  
3 = MODAL
4 = PENDAPATAN
5 = BEBAN
```

#### Cara Mengelola Master Akun:

**1. Menambah Akun Baru:**
- Masuk ke menu "Master Data" → "Master Akun"
- Klik "Tambah Akun"
- Isi form:
  - **Kode Akun**: Sesuai format (contoh: 1-1007)
  - **Nama Akun**: Nama yang jelas (contoh: "Kas Kecil")
  - **Kategori**: Pilih kategori yang sesuai
  - **Saldo Awal**: Isi jika ada saldo awal
  - **Deskripsi**: Penjelasan fungsi akun
  - **Status**: Aktif/Tidak Aktif

**2. Mengubah Akun:**
- Klik ikon edit pada akun yang ingin diubah
- Ubah data yang diperlukan
- Simpan perubahan

**3. Menonaktifkan Akun:**
- Ubah status menjadi "Tidak Aktif"
- Akun tidak akan muncul dalam pilihan transaksi baru
- Data historis tetap tersimpan

---

### MASTER UNIT

Master Unit mengelola data unit-unit usaha dalam BUMDES.

#### Informasi yang Dikelola:
- **Nama Unit**: Nama unit usaha (contoh: "Unit Simpan Pinjam")
- **Kategori Unit**: Jenis unit (Produktif/Jasa/Perdagangan)
- **Penanggung Jawab**: User yang bertanggung jawab
- **Nilai Aset**: Total nilai aset unit
- **Alamat**: Lokasi unit
- **Deskripsi**: Penjelasan kegiatan unit

#### Cara Mengelola Master Unit:

**1. Menambah Unit Baru:**
- Masuk ke menu "Master Data" → "Master Unit"
- Klik "Tambah Unit"
- Isi semua field yang diperlukan
- Pilih penanggung jawab dari daftar user
- Simpan data

**2. Mengubah Data Unit:**
- Klik ikon edit pada unit yang ingin diubah
- Perubahan akan tercatat dalam history
- Sistem akan menyimpan log perubahan

---

### MASTER PERSEDIAAN

Master Persediaan mengelola data barang/produk yang digunakan dalam operasional BUMDES.

#### Informasi yang Dikelola:
- **Kode Barang**: Kode unik untuk identifikasi
- **Nama Barang**: Nama lengkap barang
- **Kategori**: Jenis barang (Bahan Baku/Barang Jadi/Kemasan/Peralatan/Lainnya)
- **Satuan**: Unit pengukuran (kg, pcs, liter, dll)
- **Harga Beli**: Harga pembelian standar
- **Harga Jual**: Harga jual standar
- **Stok Minimum**: Batas minimum stok (untuk referensi)
- **Deskripsi**: Penjelasan detail barang
- **Status**: Aktif/Tidak Aktif

#### Cara Mengelola Master Persediaan:

**1. Menambah Barang Baru:**
- Masuk ke menu "Master Data" → "Master Persediaan"
- Klik "Tambah Barang"
- Isi form dengan lengkap:
  ```
  Kode Barang: BB001
  Nama Barang: Singkong Segar
  Kategori: Bahan Baku
  Satuan: kg
  Harga Beli: 2000
  Harga Jual: 0 (jika tidak dijual langsung)
  Stok Minimum: 50
  Deskripsi: Singkong segar untuk bahan baku keripik
  Status: Aktif
  ```

**2. Kategori Barang:**
- **Bahan Baku**: Bahan mentah untuk produksi
- **Barang Jadi**: Produk siap jual
- **Kemasan**: Bahan pengemas produk
- **Peralatan**: Alat-alat operasional
- **Lainnya**: Item lain yang tidak masuk kategori di atas

**PENTING**: Master Persediaan hanya sebagai **referensi data**. Stok aktual tidak otomatis berubah dari jurnal transaksi.

---

## SISTEM JURNAL

### KONSEP DASAR

Jurnal adalah pencatatan transaksi keuangan yang mempengaruhi posisi keuangan BUMDES.

#### Komponen Jurnal:
- **Tanggal Transaksi**: Kapan transaksi terjadi
- **Kode Transaksi**: Nomor unik transaksi (otomatis)
- **Jenis Transaksi**: Kategori transaksi
- **Akun**: Akun yang terpengaruh
- **Debit/Kredit**: Posisi pencatatan
- **Jumlah**: Nilai transaksi
- **Keterangan**: Penjelasan transaksi

### JENIS TRANSAKSI

#### 1. **Transaksi Pendapatan**
Pencatatan semua pemasukan BUMDES:
- Penjualan produk
- Jasa yang diberikan
- Pendapatan bunga
- Pendapatan lain-lain

#### 2. **Transaksi Beban**
Pencatatan semua pengeluaran operasional:
- Pembelian bahan baku
- Gaji karyawan
- Biaya listrik, air, telepon
- Biaya transportasi
- Beban lain-lain

#### 3. **Transaksi Aset**
Pencatatan perubahan aset:
- Pembelian peralatan
- Penjualan aset
- Penyusutan aset

#### 4. **Transaksi Kewajiban**
Pencatatan utang dan pembayaran:
- Utang kepada supplier
- Pinjaman bank
- Pembayaran utang

#### 5. **Transaksi Modal**
Pencatatan perubahan modal:
- Setoran modal awal
- Tambahan modal
- Pengambilan modal

### CARA INPUT JURNAL

#### Langkah-langkah Input Jurnal:

**1. Masuk ke Menu Transaksi:**
- Pilih "Transaksi" → "Input Jurnal"
- Klik "Tambah Transaksi"

**2. Isi Header Transaksi:**
- **Tanggal**: Pilih tanggal transaksi
- **Jenis Transaksi**: Pilih kategori yang sesuai
- **Keterangan**: Tulis penjelasan singkat

**3. Input Detail Jurnal:**
- **Akun Debit**: Pilih akun yang di-debit
- **Jumlah Debit**: Masukkan nominal
- **Akun Kredit**: Pilih akun yang di-kredit  
- **Jumlah Kredit**: Masukkan nominal (harus sama dengan debit)
- **Keterangan Detail**: Penjelasan spesifik

**4. Validasi dan Simpan:**
- Pastikan Total Debit = Total Kredit
- Klik "Simpan Transaksi"
- Sistem akan generate kode transaksi otomatis

---

## PENJELASAN DETAIL AKUN

### AKUN ASET (Kode: 1-XXXX)

Aset adalah sumber daya yang dimiliki BUMDES dan memiliki nilai ekonomi.

#### **1-1001: Kas**
- **Fungsi**: Uang tunai yang ada di tangan
- **Debit**: Ketika menerima uang tunai
- **Kredit**: Ketika mengeluarkan uang tunai
- **Contoh**: 
  - Debit: Penjualan tunai, penerimaan piutang
  - Kredit: Pembelian tunai, pembayaran beban

#### **1-1002: Bank**
- **Fungsi**: Saldo rekening bank BUMDES
- **Debit**: Setoran ke bank, penerimaan transfer
- **Kredit**: Penarikan dari bank, transfer keluar
- **Contoh**:
  - Debit: Setoran kas ke bank
  - Kredit: Pembayaran via transfer

#### **1-1003: Piutang Usaha**
- **Fungsi**: Tagihan kepada pelanggan (penjualan kredit)
- **Debit**: Penjualan kredit
- **Kredit**: Pembayaran piutang oleh pelanggan
- **Contoh**:
  - Debit: Jual keripik Rp 500.000 belum dibayar
  - Kredit: Pelanggan bayar piutang Rp 300.000

#### **1-1004: Piutang Lain-lain**
- **Fungsi**: Tagihan selain dari penjualan
- **Debit**: Pinjaman kepada karyawan, uang muka
- **Kredit**: Pembayaran kembali pinjaman
- **Contoh**:
  - Debit: Kasbon karyawan Rp 200.000
  - Kredit: Potong gaji untuk kasbon

#### **1-1005: Persediaan Bahan Baku**
- **Fungsi**: Nilai bahan mentah untuk produksi
- **Debit**: Pembelian bahan baku
- **Kredit**: Pemakaian bahan baku untuk produksi
- **Contoh**:
  - Debit: Beli singkong 100kg @ Rp 2.000 = Rp 200.000
  - Kredit: Pakai singkong 50kg untuk produksi = Rp 100.000

#### **1-1006: Persediaan Barang Jadi**
- **Fungsi**: Nilai produk siap jual
- **Debit**: Hasil produksi (dari bahan baku)
- **Kredit**: Penjualan barang jadi
- **Contoh**:
  - Debit: Selesai produksi keripik senilai Rp 300.000
  - Kredit: Jual keripik senilai Rp 150.000

#### **1-1007: Peralatan**
- **Fungsi**: Alat-alat operasional BUMDES
- **Debit**: Pembelian peralatan baru
- **Kredit**: Penjualan/penghapusan peralatan
- **Contoh**:
  - Debit: Beli mesin penggoreng Rp 5.000.000
  - Kredit: Jual mesin lama Rp 1.000.000

#### **1-1008: Akumulasi Penyusutan Peralatan**
- **Fungsi**: Penyusutan nilai peralatan (akun kontra aset)
- **Debit**: Penghapusan penyusutan (saat jual aset)
- **Kredit**: Pencatatan penyusutan bulanan
- **Contoh**:
  - Kredit: Penyusutan mesin bulan ini Rp 100.000

### AKUN KEWAJIBAN (Kode: 2-XXXX)

Kewajiban adalah utang atau kewajiban BUMDES kepada pihak lain.

#### **2-2001: Utang Usaha**
- **Fungsi**: Utang kepada supplier (pembelian kredit)
- **Debit**: Pembayaran utang
- **Kredit**: Pembelian kredit
- **Contoh**:
  - Kredit: Beli bahan baku kredit Rp 1.000.000
  - Debit: Bayar utang supplier Rp 500.000

#### **2-2002: Utang Bank**
- **Fungsi**: Pinjaman dari bank
- **Debit**: Pembayaran cicilan pokok
- **Kredit**: Penerimaan pinjaman baru
- **Contoh**:
  - Kredit: Terima pinjaman bank Rp 10.000.000
  - Debit: Bayar cicilan pokok Rp 500.000

#### **2-2003: Utang Gaji**
- **Fungsi**: Gaji karyawan yang belum dibayar
- **Debit**: Pembayaran gaji
- **Kredit**: Akrual gaji bulanan
- **Contoh**:
  - Kredit: Gaji bulan ini Rp 2.000.000 (belum dibayar)
  - Debit: Bayar gaji karyawan Rp 2.000.000

### AKUN MODAL (Kode: 3-XXXX)

Modal adalah kekayaan bersih BUMDES (aset dikurangi kewajiban).

#### **3-3001: Modal Awal**
- **Fungsi**: Modal yang disetor saat pendirian
- **Debit**: Pengambilan modal (jarang)
- **Kredit**: Setoran modal awal
- **Contoh**:
  - Kredit: Setoran modal awal Rp 50.000.000

#### **3-3002: Laba Ditahan**
- **Fungsi**: Akumulasi laba yang tidak dibagi
- **Debit**: Pembagian laba, rugi
- **Kredit**: Laba bersih, koreksi laba tahun lalu
- **Contoh**:
  - Kredit: Laba bersih tahun ini Rp 5.000.000

### AKUN PENDAPATAN (Kode: 4-XXXX)

Pendapatan adalah pemasukan dari kegiatan operasional BUMDES.

#### **4-4001: Penjualan**
- **Fungsi**: Pendapatan dari penjualan produk utama
- **Debit**: Retur penjualan, koreksi
- **Kredit**: Penjualan produk
- **Contoh**:
  - Kredit: Jual keripik Rp 1.500.000
  - Debit: Retur keripik rusak Rp 50.000

#### **4-4002: Pendapatan Jasa**
- **Fungsi**: Pendapatan dari layanan jasa
- **Debit**: Pembatalan jasa
- **Kredit**: Penyediaan jasa
- **Contoh**:
  - Kredit: Jasa penggilingan padi Rp 300.000

#### **4-4003: Pendapatan Bunga**
- **Fungsi**: Bunga dari simpanan atau pinjaman yang diberikan
- **Debit**: Koreksi bunga
- **Kredit**: Penerimaan bunga
- **Contoh**:
  - Kredit: Bunga deposito Rp 100.000

#### **4-4004: Pendapatan Lain-lain**
- **Fungsi**: Pendapatan di luar operasional utama
- **Debit**: Koreksi pendapatan
- **Kredit**: Pendapatan insidental
- **Contoh**:
  - Kredit: Sewa gedung Rp 500.000

### AKUN BEBAN (Kode: 5-XXXX)

Beban adalah pengeluaran untuk operasional BUMDES.

#### **5-5001: Harga Pokok Penjualan**
- **Fungsi**: Biaya langsung untuk memproduksi barang yang dijual
- **Debit**: Biaya bahan baku, tenaga kerja langsung
- **Kredit**: Koreksi HPP
- **Contoh**:
  - Debit: Bahan baku terpakai Rp 800.000
  - Debit: Upah produksi Rp 200.000

#### **5-5002: Beban Gaji**
- **Fungsi**: Gaji karyawan administrasi dan umum
- **Debit**: Pembayaran gaji
- **Kredit**: Koreksi gaji
- **Contoh**:
  - Debit: Gaji admin Rp 1.500.000

#### **5-5003: Beban Listrik**
- **Fungsi**: Biaya listrik operasional
- **Debit**: Pembayaran tagihan listrik
- **Kredit**: Koreksi beban
- **Contoh**:
  - Debit: Bayar listrik Rp 300.000

#### **5-5004: Beban Transportasi**
- **Fungsi**: Biaya transportasi operasional
- **Debit**: Biaya bensin, ongkos kirim
- **Kredit**: Koreksi beban
- **Contoh**:
  - Debit: Bensin motor Rp 150.000
  - Debit: Ongkir produk Rp 75.000

#### **5-5005: Beban Penyusutan**
- **Fungsi**: Alokasi biaya penyusutan aset tetap
- **Debit**: Penyusutan bulanan
- **Kredit**: Koreksi penyusutan
- **Contoh**:
  - Debit: Penyusutan mesin Rp 100.000

#### **5-5006: Beban Lain-lain**
- **Fungsi**: Beban operasional lainnya
- **Debit**: Berbagai beban kecil
- **Kredit**: Koreksi beban
- **Contoh**:
  - Debit: Biaya ATK Rp 50.000
  - Debit: Biaya reparasi Rp 200.000

---

## CONTOH KASUS PRAKTIS

### KASUS 1: Setoran Modal Awal

**Transaksi**: BUMDES menerima setoran modal awal Rp 50.000.000 tunai

**Jurnal**:
```
Tanggal: 01/01/2024
Keterangan: Setoran modal awal BUMDES

Debit:  1-1001 Kas                 Rp 50.000.000
Kredit: 3-3001 Modal Awal          Rp 50.000.000
```

**Penjelasan**: Kas bertambah (debit), Modal bertambah (kredit)

### KASUS 2: Pembelian Bahan Baku Tunai

**Transaksi**: Beli singkong 200 kg @ Rp 2.000 = Rp 400.000 tunai

**Jurnal**:
```
Tanggal: 05/01/2024
Keterangan: Pembelian singkong untuk bahan baku

Debit:  1-1005 Persediaan Bahan Baku    Rp 400.000
Kredit: 1-1001 Kas                      Rp 400.000
```

**Penjelasan**: Persediaan bertambah (debit), Kas berkurang (kredit)

### KASUS 3: Pembelian Bahan Baku Kredit

**Transaksi**: Beli kelapa 100 butir @ Rp 3.000 = Rp 300.000 kredit

**Jurnal**:
```
Tanggal: 07/01/2024
Keterangan: Pembelian kelapa kredit dari Pak Budi

Debit:  1-1005 Persediaan Bahan Baku    Rp 300.000
Kredit: 2-2001 Utang Usaha              Rp 300.000
```

**Penjelasan**: Persediaan bertambah (debit), Utang bertambah (kredit)

### KASUS 4: Produksi Barang Jadi

**Transaksi**: Produksi keripik menggunakan bahan baku senilai Rp 500.000

**Jurnal**:
```
Tanggal: 10/01/2024
Keterangan: Produksi keripik singkong dan kelapa

Debit:  1-1006 Persediaan Barang Jadi   Rp 500.000
Kredit: 1-1005 Persediaan Bahan Baku    Rp 500.000
```

**Penjelasan**: Barang jadi bertambah (debit), Bahan baku berkurang (kredit)

### KASUS 5: Penjualan Tunai

**Transaksi**: Jual keripik senilai Rp 750.000 tunai

**Jurnal**:
```
Tanggal: 15/01/2024
Keterangan: Penjualan keripik tunai

Debit:  1-1001 Kas                      Rp 750.000
Kredit: 4-4001 Penjualan                Rp 750.000

Debit:  5-5001 Harga Pokok Penjualan    Rp 500.000
Kredit: 1-1006 Persediaan Barang Jadi   Rp 500.000
```

**Penjelasan**: 
- Kas bertambah, Pendapatan bertambah
- HPP dicatat, Persediaan berkurang

### KASUS 6: Penjualan Kredit

**Transaksi**: Jual keripik senilai Rp 1.000.000 kredit ke Toko ABC

**Jurnal**:
```
Tanggal: 20/01/2024
Keterangan: Penjualan kredit ke Toko ABC

Debit:  1-1003 Piutang Usaha            Rp 1.000.000
Kredit: 4-4001 Penjualan                Rp 1.000.000

Debit:  5-5001 Harga Pokok Penjualan    Rp 650.000
Kredit: 1-1006 Persediaan Barang Jadi   Rp 650.000
```

### KASUS 7: Pembayaran Piutang

**Transaksi**: Toko ABC bayar piutang Rp 600.000

**Jurnal**:
```
Tanggal: 25/01/2024
Keterangan: Pembayaran piutang dari Toko ABC

Debit:  1-1001 Kas                      Rp 600.000
Kredit: 1-1003 Piutang Usaha            Rp 600.000
```

### KASUS 8: Pembayaran Beban Operasional

**Transaksi**: Bayar listrik Rp 250.000, gaji karyawan Rp 1.500.000

**Jurnal**:
```
Tanggal: 30/01/2024
Keterangan: Pembayaran beban operasional

Debit:  5-5003 Beban Listrik            Rp 250.000
Debit:  5-5002 Beban Gaji               Rp 1.500.000
Kredit: 1-1001 Kas                      Rp 1.750.000
```

### KASUS 9: Pembelian Peralatan

**Transaksi**: Beli mesin penggoreng Rp 5.000.000 tunai

**Jurnal**:
```
Tanggal: 02/02/2024
Keterangan: Pembelian mesin penggoreng

Debit:  1-1007 Peralatan                Rp 5.000.000
Kredit: 1-1001 Kas                      Rp 5.000.000
```

### KASUS 10: Penyusutan Bulanan

**Transaksi**: Penyusutan mesin bulan Februari Rp 100.000

**Jurnal**:
```
Tanggal: 28/02/2024
Keterangan: Penyusutan mesin bulan Februari

Debit:  5-5005 Beban Penyusutan         Rp 100.000
Kredit: 1-1008 Akumulasi Penyusutan     Rp 100.000
```

---

## TIPS DAN BEST PRACTICE

### 1. **Konsistensi Pencatatan**
- Catat transaksi secara rutin dan tepat waktu
- Gunakan keterangan yang jelas dan konsisten
- Pastikan semua dokumen pendukung tersimpan

### 2. **Validasi Jurnal**
- Selalu pastikan Total Debit = Total Kredit
- Periksa kembali akun yang dipilih
- Validasi nominal dengan dokumen asli

### 3. **Backup Data**
- Lakukan backup data secara berkala
- Simpan dokumen fisik sebagai arsip
- Buat laporan bulanan untuk kontrol

### 4. **Pemisahan Tugas**
- Pisahkan fungsi input dan approval
- Buat sistem otorisasi bertingkat
- Lakukan review berkala

### 5. **Pelatihan Tim**
- Pastikan semua user memahami sistem
- Berikan pelatihan berkala
- Buat SOP yang jelas

### 6. **Monitoring dan Evaluasi**
- Review laporan keuangan bulanan
- Analisis varians budget vs aktual
- Lakukan audit internal berkala

---

## PENUTUP

Dokumentasi ini memberikan panduan lengkap untuk penggunaan master data dan sistem jurnal dalam BUMDES. Untuk pertanyaan lebih lanjut atau bantuan teknis, silakan hubungi administrator sistem.

**Catatan Penting**: 
- Selalu backup data sebelum melakukan perubahan besar
- Konsultasikan dengan akuntan untuk kasus-kasus kompleks
- Patuhi peraturan akuntansi yang berlaku

---

*Dokumen ini dibuat untuk membantu pengelolaan keuangan BUMDES secara profesional dan akuntabel.*