# 📘 PETUNJUK TEKNIS (JUKNIS) PENGGUNAAN APLIKASI
# SISTEM MANAJEMEN ASRAMA PPSDMAP

---

## 📌 DAFTAR ISI
1. [BAB I: PENDAHULUAN](#bab-i-pendahuluan)
   - 1.1 Gambaran Umum Sistem
   - 1.2 Hak Akses & Peran Pengguna (*Roles*)
   - 1.3 Kebutuhan Sistem, Zona Waktu & Akses Browser
2. [BAB II: AKSES MASUK SISTEM (LOGIN & PENGATURAN AKUN)](#bab-ii-akses-masuk-sistem-login--pengaturan-akun)
   - 2.1 Halaman Login
   - 2.2 Informasi Akun Default
   - 2.3 Pengalihan Otomatis Berdasarkan Peran (*Role Redirection*)
   - 2.4 Pengaturan Profil & Ganti Kata Sandi
   - 2.5 Keluar dari Sistem (*Logout*)
3. [BAB III: PETUNJUK OPERASIONAL ADMINISTRATOR (ADMIN)](#bab-iii-petunjuk-operasional-administrator-admin)
   - 3.1 Dashboard Administrator
   - 3.2 Manajemen Master Data Gedung
   - 3.3 Manajemen Master Data Kamar & Konsep Kapasitas (*Multi-Occupancy*)
   - 3.4 Manajemen Master Data Program Diklat
   - 3.5 Manajemen Master Data Peserta
   - 3.6 Fitur Import Data Peserta via Excel
   - 3.7 Manajemen Akun Pengguna Aplikasi
   - 3.8 Menu Laporan & Rekapitulasi Admin
4. [BAB IV: PETUNJUK OPERASIONAL RESEPSIONIS](#bab-iv-petunjuk-operasional-resepsionis)
   - 4.1 Dashboard Meja Resepsionis
   - 4.2 Prosedur Layanan Check-in Peserta & Alokasi Slot Kamar
   - 4.3 Pendaftaran Peserta Baru di Lokasi (*On-the-spot Registration*)
   - 4.4 Prosedur Layanan Check-out & Pelepasan Status Kamar Bertingkat
   - 4.5 Monitoring Ketersediaan Kamar & Penghuni Aktif
5. [BAB V: PETUNJUK MONITORING & ANALITIK PIMPINAN](#bab-v-petunjuk-monitoring--analitik-pimpinan)
   - 5.1 Dashboard Eksekutif & Tingkat Okupansi (*Occupancy Rate*)
   - 5.2 Laporan Tingkat Hunian Asrama
   - 5.3 Laporan Utilisasi Per Gedung & Rincian Denah Kamar
   - 5.4 Laporan Distribusi Per Program Diklat
   - 5.5 Fitur Ekspor Data (CSV) & Cetak Dokumen (Print PDF)
6. [BAB VI: TANYA JAWAB UMUM & PANDUAN PEMECAHAN MASALAH (FAQ & TROUBLESHOOTING)](#bab-vi-tanya-jawab-umum--panduan-pemecahan-masalah-faq--troubleshooting)

---

## BAB I: PENDAHULUAN

### 1.1 Gambaran Umum Sistem
**Aplikasi Sistem Manajemen Asrama PPSDMAP** adalah platform berbasis web yang dirancang khusus untuk mempermudah, mendigitalkan, dan mengotomatisasi seluruh alur operasional pengelolaan fasilitas asrama di lingkungan Pusat Pengembangan Sumber Daya Manusia Aparatur Perhubungan (PPSDMAP).

Sistem ini mengintegrasikan seluruh tahapan mulai dari:
1. Pengelolaan master data fasilitas (gedung, kamar, kapasitas tempat tidur/slot).
2. Pendataan agenda program diklat dan kepesertaan (baik input satuan maupun import Excel).
3. Layanan kedatangan (*check-in*) dengan pencarian otomatis peserta dan penempatan kamar siap huni (mendukung kamar kosong maupun kamar terisi sebagian yang masih memiliki sisa kapasitas).
4. Layanan kepulangan (*check-out*) serta pelepasan status kamar secara dinamis (*real-time*).
5. Pelaporan analitik dan rekapitulasi okupansi hunian untuk pemantauan pimpinan secara transparan dan akurat.

---

### 1.2 Hak Akses & Peran Pengguna (*Roles*)
Sistem menerapkan kontrol akses berbasis peran (*Role-Based Access Control*) dengan 3 tingkatan pengguna:

| No | Peran Pengguna (*Role*) | Deskripsi Tanggung Jawab Utama | Menu Utama yang Dapat Diakses |
|:---|:---|:---|:---|
| 1 | **Administrator (Admin)** | Mengelola data master sistem, fasilitas gedung & kamar, agenda diklat, import massal peserta, manajemen akun pengguna, serta rekapitulasi laporan. | Dashboard, Gedung, Kamar, Diklat, Peserta, Import Excel, Pengguna, Laporan. |
| 2 | **Resepsionis** | Melayani operasional harian meja depan (*front office*), proses check-in tamu, check-out tamu, monitoring ketersediaan kamar, dan monitoring penghuni aktif. | Dashboard, Check-in, Check-out, Ketersediaan Kamar, Penghuni Aktif, Tambah Peserta. |
| 3 | **Pimpinan** | Memantau kinerja fasilitas asrama, persentase tingkat keterisian hunian (*occupancy rate*), laporan per gedung, per diklat, serta export data. | Dashboard Eksekutif, Laporan Hunian, Laporan Per Gedung, Laporan Per Diklat. |

---

### 1.3 Kebutuhan Sistem, Zona Waktu & Akses Browser
- **Perangkat**: Komputer PC, Laptop, Tablet, maupun Smartphone.
- **Peramban Web (*Browser*)**: Google Chrome (versi terbaru direkomendasikan), Mozilla Firefox, Microsoft Edge, atau Safari.
- **Koneksi Jaringan**: Terhubung dengan jaringan intranet kantor / internet lokal server aplikasi.
- **Standar Waktu & Bahasa**: Sistem dikonfigurasi menggunakan standar **Waktu Indonesia Barat (WIB / `Asia/Jakarta`)** dan lokalisasi antarmuka dalam **Bahasa Indonesia** (penamaan hari, tanggal, dan bulan tampil secara otomatis dalam Bahasa Indonesia).

---

## BAB II: AKSES MASUK SISTEM (LOGIN & PENGATURAN AKUN)

### 2.1 Halaman Login
1. Buka browser dan masukkan alamat URL aplikasi asrama (contoh: `http://localhost:8080` atau alamat IP/Domain server yang ditentukan).
2. Halaman formulir masuk (*Login*) akan ditampilkan secara otomatis.
3. Masukkan **Alamat Email** yang telah terdaftar di sistem.
4. Masukkan **Kata Sandi (*Password*)**.
5. *(Opsional)* Centang kotak **Ingat Saya (*Remember Me*)** jika menggunakan perangkat pribadi agar sesi login tersimpan.
6. Klik tombol **Masuk ke Sistem**.

```
+----------------------------------------------------------------+
|                      ASRAMA PPSDMAP                            |
|             Sistem Manajemen Hunian Asrama                     |
|                                                                |
|   Email       : [ admin@example.com                   ]        |
|   Password    : [ ******************                  ]        |
|   [x] Ingat Saya                                               |
|                                                                |
|   [               MASUK KE SISTEM                 ]            |
+----------------------------------------------------------------+
```

---

### 2.2 Informasi Akun Default (Pengujian Awal / Administrator)
Pada instalasi standar sistem, akun bawaan yang tersedia adalah:

| Role / Peran | Alamat Email | Kata Sandi (*Default*) |
|:---|:---|:---|
| **Admin** | `admin@example.com` | `password` |
| **Resepsionis** | `resepsionis@example.com` | `password` |
| **Pimpinan** | `pimpinan@example.com` | `password` |

> ⚠️ **PENTING**: Segera ubah kata sandi default setelah Anda berhasil masuk pertama kali demi keamanan data asrama.

---

### 2.3 Pengalihan Otomatis Berdasarkan Peran (*Role Redirection*)
Setelah proses autentikasi berhasil, sistem akan mendeteksi peran akun secara otomatis dan mengarahkan Anda ke beranda dashboard yang sesuai:
- **Akun Admin** ➔ Dialihkan ke `/admin/dashboard`
- **Akun Resepsionis** ➔ Dialihkan ke `/resepsionis/dashboard`
- **Akun Pimpinan** ➔ Dialihkan ke `/pimpinan/dashboard`

---

### 2.4 Pengaturan Profil & Ganti Kata Sandi
Setiap pengguna dapat memperbarui informasi pribadi dan mengubah kata sandi:
1. Klik nama pengguna pada bagian bawah navigasi sidebar atau klik menu **Profil**.
2. Pada formulir **Informasi Profil**, Anda dapat memperbarui Nama Lengkap dan Alamat Email.
3. Pada bagian **Perbarui Kata Sandi**:
   - Masukkan **Kata Sandi Saat Ini**.
   - Masukkan **Kata Sandi Baru** (minimal 8 karakter).
   - Masukkan **Konfirmasi Kata Sandi Baru**.
4. Klik tombol **Simpan Perubahan**.

---

### 2.5 Keluar dari Sistem (*Logout*)
Untuk mengakhiri sesi kerja dengan aman:
1. Arahkan kursor ke bagian bawah menu navigasi sidebar di sebelah kiri.
2. Klik tombol **Keluar (*Logout*)** bergambar ikon pintu keluar.
3. Sesi Anda akan ditutup dan layar otomatis kembali ke halaman login.

---

## BAB III: PETUNJUK OPERASIONAL ADMINISTRATOR (ADMIN)

Sebagai **Administrator**, Anda bertugas membangun dan mengelola fondasi master data asrama sebelum proses operasional dapat berjalan.

```
ALUR KERJA ADMINISTRATOR:
[1. Buat Gedung] ➔ [2. Buat Kamar & Kapasitas] ➔ [3. Buat Agenda Diklat] ➔ [4. Import/Input Peserta] ➔ [5. Buat Akun Petugas]
```

---

### 3.1 Dashboard Administrator
Menampilkan ringkasan metrik menyeluruh:
- **Total Gedung**: Jumlah gedung yang terdaftar di sistem.
- **Total Kamar**: Jumlah seluruh unit kamar asrama.
- **Kamar Kosong & Terisi**: Status ketersediaan kamar secara *real-time* yang dihitung langsung dari data transaksi aktif menginap.
- **Total Peserta & Diklat**: Data akumulasi kegiatan pelatihan dan peserta.
- **Penghuni Aktif**: Jumlah peserta yang sedang aktif menempati asrama saat ini.
- **Status Okupansi Per Gedung**: Kartu visualisasi persentase keterisian masing-masing gedung.

---

### 3.2 Manajemen Master Data Gedung (`/admin/gedung`)
Menu ini digunakan untuk mendata seluruh blok/gedung asrama (contoh: Gedung Asrama A, Gedung Asrama B, Asrama Garuda, dsb).

#### A. Menambah Gedung Baru:
1. Klik menu **Gedung** pada sidebar admin.
2. Klik tombol **+ Tambah Gedung Baru**.
3. Masukkan **Nama Gedung** (contoh: *Gedung Asrama Cempaka*).
4. Klik **Simpan Gedung**.

#### B. Mengubah / Menghapus Gedung:
- Klik tombol **Edit** (ikon pensil) pada baris gedung untuk mengubah nama gedung.
- Klik tombol **Hapus** (ikon tempat sampah) untuk menghapus data gedung *(Catatan: Gedung yang masih memiliki kamar terisi tidak dapat dihapus)*.

---

### 3.3 Manajemen Master Data Kamar & Konsep Kapasitas (`/admin/kamar`)
Menu ini digunakan untuk mendata unit-unit kamar yang berada di dalam masing-masing gedung dengan dukungan multi-hunian (*multi-occupancy*).

#### A. Konsep Kapasitas & Status Ketersediaan Kamar:
Sistem mendukung penempatan lebih dari 1 peserta di dalam kamar yang sama (kamar bersama / *sharing room*). Status ketersediaan kamar dihitung secara otomatis berdasarkan jumlah penghuni aktif:
- **Kosong (0 Terisi)**: Belum ada penghuni, seluruh tempat tidur/slot tersedia (indikator hijau).
- **Terisi Sebagian (1 atau 2 Terisi)**: Sudah ada penghuni, namun masih ada slot kosong yang bisa ditempati peserta lain (indikator orange).
- **Penuh (3 Terisi / Mencapai Kapasitas)**: Seluruh tempat tidur telah terisi penuh (indikator merah).

#### B. Menambah Kamar Baru:
1. Klik menu **Kamar** pada sidebar admin.
2. Klik tombol **+ Tambah Kamar Baru**.
3. **Pilih Gedung**: Pilih gedung lokasi kamar berada.
4. **Nomor Kamar**: Masukkan nomor/kode kamar (contoh: *101*, *A-01*, *205*).
5. **Kapasitas**: Masukkan daya tampung tempat tidur/orang (contoh: *1*, *2*, atau *3 orang*).
6. **Status Awal**: Tentukan status awal (*Kosong* atau *Dalam Perawatan*).
7. Klik **Simpan Kamar**.

#### C. Filter & Pencarian Kamar:
- Gunakan dropdown filter gedung dan filter status ketersediaan (*Semua Status*, *Kosong*, *1 Terisi*, *2 Terisi*, *3 Terisi*) untuk menyaring daftar kamar secara cepat.

---

### 3.4 Manajemen Master Data Program Diklat (`/admin/diklat`)
Digunakan untuk mendaftarkan kegiatan pelatihan/pendidikan yang diselenggarakan oleh PPSDMAP.

#### A. Menambah Program Diklat:
1. Klik menu **Diklat** pada sidebar.
2. Klik tombol **+ Tambah Diklat Baru**.
3. **Nama Program Diklat**: Masukkan nama lengkap diklat (contoh: *Diklat Kepemimpinan Pengawas Angkatan IV*).
4. **Tanggal Mulai**: Tentukan tanggal pembukaan diklat.
5. **Tanggal Selesai**: Tentukan tanggal penutupan diklat.
6. Klik **Simpan Data Diklat**.

---

### 3.5 Manajemen Master Data Peserta (`/admin/peserta`)
Digunakan untuk mengelola daftar peserta diklat secara individual.

#### A. Menambah Peserta Secara Manual:
1. Klik menu **Peserta** ➔ Klik tombol **+ Tambah Peserta Baru**.
2. **Pilih Program Diklat**: Pilih program diklat yang diikuti peserta.
3. **Nama Lengkap Peserta**: Masukkan nama lengkap beserta gelar jika ada.
4. **NIP / NIK**: Masukkan nomor identitas pegawai/kependudukan.
5. **Instansi / Asal Kantor**: Masukkan asal instansi (contoh: *BPTD Kelas II Jawa Barat*, *Dinas Perhubungan*, dsb).
6. Klik **Simpan Peserta**.

---

### 3.6 Fitur Import Data Peserta via Excel (`/admin/peserta/import`)
Untuk mempercepat input data peserta dalam jumlah banyak (puluhan hingga ratusan peserta sekaligus) dari panitia diklat:

1. Klik menu **Import Excel** pada sidebar admin.
2. Unduh template resmi dengan mengklik tautan **Unduh Contoh Format Excel**.
3. Buka file template di Microsoft Excel dan isi data peserta sesuai kolom:
   - Kolom A: `nama_peserta` *(Wajib diisi)*
   - Kolom B: `nip_nik` *(Opsional)*
   - Kolom C: `instansi` *(Opsional)*
   - Kolom D: `nama_diklat` *(Wajib sesuai atau sistem otomatis mencocokkan nama diklat)*
4. Simpan file dalam format `.xlsx` atau `.csv`.
5. Kembali ke aplikasi, pilih program diklat tujuan (jika diperlukan) dan klik **Pilih File Excel**.
6. Klik tombol **Unggah & Proses Import Data**.
7. Sistem akan menampilkan notifikasi sukses beserta jumlah total data peserta yang berhasil dimasukkan ke sistem.

---

### 3.7 Manajemen Akun Pengguna Aplikasi (`/admin/user`)
Menu ini digunakan oleh Admin untuk mengelola akun petugas yang berhak mengakses aplikasi:

1. Klik menu **Pengguna** pada sidebar admin.
2. Klik **+ Tambah Pengguna Baru**.
3. Masukkan:
   - **Nama Lengkap Petugas**
   - **Alamat Email** (digunakan untuk login)
   - **Kata Sandi (*Password*)**
   - **Peran (*Role*)**: Pilih salah satu antara:
     - `Administrator`
     - `Resepsionis`
     - `Pimpinan`
4. Klik **Simpan Pengguna**.

---

### 3.8 Menu Laporan Administrator (`/admin/laporan`)
Admin memiliki hak akses penuh untuk melihat rekapitulasi data:
- **Tab Laporan Hunian**: Riwayat transaksi, tanggal masuk, tanggal keluar, dan status menginap.
- **Tab Laporan Per Gedung**: Rekapitulasi kapasitas tempat tidur, kamar terisi/sebagian/penuh, dan tingkat hunian per gedung.
- **Tab Laporan Per Diklat**: Distribusi alokasi kamar per program diklat.
- Dilengkapi fitur pencarian multi-kriteria, filter tanggal, export CSV, dan cetak laporan.

---

## BAB IV: PETUNJUK OPERASIONAL RESEPSIONIS

Resepsionis bertindak sebagai operator garis depan (*front office*) yang berinteraksi langsung dengan tamu dan peserta diklat yang datang ke asrama.

---

### 4.1 Dashboard Meja Resepsionis
Saat resepsionis login, layar menampilkan:
1. **Ringkasan Operasional Hari Ini**:
   - Jumlah tamu yang Check-in hari ini.
   - Jumlah tamu yang Check-out hari ini.
   - Total sisa kamar kosong siap huni.
   - Total penghuni yang sedang aktif menginap.
2. **Pintasan Cepat (*Quick Actions*)**:
   - Tombol cepat ke formulir **Check-in Peserta**.
   - Tombol cepat ke daftar **Proses Check-out**.
   - Tombol cepat ke **Tambah Peserta Baru di Lokasi**.
3. **Tabel Riwayat Transaksi Terbaru**: Menampilkan 5 transaksi terakhir yang tercatat di sistem secara *real-time*.

---

### 4.2 Prosedur Layanan Check-in Peserta & Alokasi Slot Kamar (`/resepsionis/checkin`)
Prosedur ini dilakukan saat peserta diklat tiba di meja resepsionis untuk mengambil kunci kamar.

```
ALUR CHECK-IN:
[Peserta Tiba] ➔ [Cari Nama Peserta (Live Combobox)] ➔ [Pilih Kamar Tersedia (Kosong/Sebagian)] ➔ [Simpan & Check-in] ➔ [Status Slot Terisi]
```

#### Langkah-langkah Check-in:
1. Klik menu **Check-in** pada sidebar resepsionis (atau klik tombol pintasan *Check-in Peserta* di dashboard).
2. **Pilih Peserta Diklat (Fitur Searchable Combobox)**:
   - Klik kolom input *"Ketik untuk mencari nama peserta, NIP, instansi, atau program diklat..."*.
   - Ketik sebagian nama peserta (contoh: ketik `Ahmad` atau `Budi`).
   - Sistem secara **real-time** langsung memfilter dan mengurutkan daftar peserta yang belum check-in.
   - Anda juga dapat menggunakan tombol panah atas/bawah (**`↑`** / **`↓`**) pada keyboard lalu tekan **`Enter`** untuk memilih peserta.
   - Setelah dipilih, muncul kartu ringkasan peserta terpilih berisi Nama, NIP, Instansi, dan Program Diklat.
3. **Pilih Kamar Siap Huni**:
   - Pilih unit kamar dari daftar dropdown kamar yang tersedia. 
   - Dropdown dikelompokkan rapi per gedung dan secara otomatis menampilkan:
     - Kamar yang **Kosong** (0 Terisi).
     - Kamar yang **Terisi Sebagian** yang masih memiliki sisa kapasitas tempat tidur (disertai keterangan status, misal: *Kamar 101 - (1/2 Terisi)*).
   - Kamar yang sudah penuh (mencapai kapasitas maksimal) secara otomatis tidak akan dimunculkan di dropdown.
4. **Tanggal & Waktu Masuk (Check-in)**:
   - Waktu otomatis terisi saat ini (WIB). Anda dapat mengubahnya jika diperlukan.
5. **Konfirmasi & Simpan**:
   - Klik tombol **Simpan & Check-in**.
6. **Hasil Otomatis Sistem**:
   - Sistem mencatat transaksi menginap baru.
   - Slot kamar bertambah; status kamar otomatis diperbarui (menjadi *Terisi Sebagian* atau *Penuh* jika seluruh kapasitas terpenuhi).
   - Nama peserta otomatis masuk ke daftar **Penghuni Aktif**.
   - Notifikasi sukses muncul di layar.

---

### 4.3 Pendaftaran Peserta Baru di Lokasi (*On-the-spot Registration*)
Jika terdapat peserta tambahan atau peserta yang belum terdaftar di data panitia diklat:

1. Pada halaman Check-in, klik tautan **+ Tambah Peserta Baru** (atau klik menu *Tambah Peserta* di sidebar).
2. Isi formulir:
   - **Pilih Program Diklat**
   - **Nama Lengkap Peserta** *(Wajib)*
   - **NIP / NIK** *(Opsional)*
   - **Instansi / Asal Kantor** *(Opsional)*
3. Pastikan kotak opsi **"Langsung lanjutkan ke formulir Check-in setelah peserta tersimpan"** tercentang.
4. Klik tombol **Simpan Peserta**.
5. Sistem akan menyimpan data peserta baru dan langsung mengarahkan Anda kembali ke formulir Check-in dengan nama peserta tersebut yang sudah otomatis terpilih!

---

### 4.4 Prosedur Layanan Check-out & Pelepasan Status Kamar Bertingkat (`/resepsionis/checkout`)
Prosedur ini dilakukan saat agenda diklat selesai dan peserta mengembalikan kunci kamar sebelum meninggalkan asrama.

```
ALUR CHECK-OUT:
[Peserta Kembalikan Kunci] ➔ [Buka Menu Check-out] ➔ [Cari Nama Peserta / Kamar] ➔ [Klik 'Proses Check-out'] ➔ [Pembaruan Status Kamar]
```

#### Langkah-langkah Check-out:
1. Klik menu **Check-out** pada sidebar resepsionis.
2. Di tabel **Daftar Tamu Menginap**, cari peserta berdasarkan nama, NIP, atau nomor kamar melalui kolom pencarian di bagian atas.
3. Pada baris data peserta yang bersangkutan, klik tombol merah **Proses Check-out**.
4. Kotak dialog konfirmasi akan muncul menampilkan informasi nama peserta dan nomor kamar.
5. Klik **Konfirmasi & Selesaikan Check-out**.
6. **Hasil Otomatis Sistem & Logika Multi-Hunian**:
   - Status transaksi peserta yang bersangkutan berubah menjadi **Selesai**.
   - Tanggal & jam keluar tercatat permanen di riwayat laporan.
   - **Pemeriksaan Sisa Penghuni**:
     - Jika kamar masih dihuni oleh peserta lain, status kamar tetap *Terisi* (*Terisi Sebagian*) dan sistem memberikan notifikasi: *"Kamar 101 masih terisi X orang"*.
     - Jika peserta tersebut adalah penghuni terakhir di kamar tersebut, status kamar otomatis kembali menjadi **Kosong** dan notifikasi menyatakan: *"Kamar 101 kini kosong kembali"*.

---

### 4.5 Monitoring Ketersediaan Kamar & Penghuni Aktif

#### A. Menu Ketersediaan Kamar (`/resepsionis/kamar-kosong`):
Halaman ini adalah pusat monitoring visual seluruh kondisi kamar asrama secara komprehensif:

1. **4 Kartu Ringkasan Statistik**:
   - **Total Kamar**: Jumlah seluruh kamar asrama yang terdaftar.
   - **Kamar Kosong (0 Terisi)**: Kamar yang siap diisi sepenuhnya (badge hijau).
   - **Terisi Sebagian (1-2 Terisi)**: Kamar yang masih memiliki tempat tidur kosong (badge orange).
   - **Kamar Penuh (3 Terisi)**: Kamar yang seluruh kapasitasnya telah terpakai (badge merah).
2. **Filter & Pencarian Lanjutan**:
   - Filter Gedung: Memilih blok gedung tertentu.
   - Pencarian Nomor Kamar: Mengetik nomor kamar.
   - **Filter Status Ketersediaan**:
     - *Semua Status*
     - *Siap Huni / Tersedia (Kosong & Terisi Sebagian)*
     - *Kosong Penuh (0 Terisi)*
     - *Sebagian Terisi (1-2 Terisi)*
     - *Penuh (3 Terisi)*
3. **Kartu Kamar Interaktif**:
   - **Badge Status**: Berwarna hijau (*Kosong*), orange (*1 Terisi* / *2 Terisi*), atau merah (*3 Terisi* / *Penuh*).
   - **Indikator Tempat Tidur (*Bed Pills*)**: Bar visual kapasitas yang menunjukkan slot terisi vs slot kosong.
   - **Pratinjau Penghuni Aktif (*Occupant Preview*)**: Menampilkan daftar nama peserta yang sedang menempati kamar tersebut.
   - **Tombol Aksi**:
     - Tombol **"Check-in ke Kamar Ini"** (hijau/orange) untuk kamar yang masih tersedia.
     - Tombol dinonaktifkan **"Kamar Penuh"** (merah) jika kamar sudah mencapai batas kapasitas.

#### B. Menu Penghuni Aktif (`/resepsionis/penghuni-aktif`):
- Menampilkan daftar lengkap seluruh peserta yang sedang aktif menginap di asrama hari ini.
- Memuat rincian: Nama Lengkap, Instansi, Program Diklat, Alokasi Gedung & Kamar, serta Waktu Check-in (tercatat dalam Bahasa Indonesia dan zona waktu WIB).
- Dilengkapi fitur pencarian dan filter per gedung serta per program diklat.
- Pengelolaan kepulangan difokuskan melalui menu **Check-out** untuk memastikan validasi yang rapi.

---

## BAB V: PETUNJUK MONITORING & ANALITIK PIMPINAN

Akun **Pimpinan** difokuskan pada pengawasan strategis (*monitoring & reporting*) tanpa mengubah data operasional secara langsung.

---

### 5.1 Dashboard Eksekutif Pimpinan (`/pimpinan/dashboard`)
Halaman ini menyajikan ringkasan visual performa asrama secara otomatis:
- **Indikator Tingkat Okupansi (*Occupancy Rate*)**: Persentase kapasitas tempat tidur asrama yang sedang digunakan.
- **Kartu Metrik Utama**: Total Kamar, Kamar Kosong, Kamar Terisi, Penghuni Aktif, Total Diklat, dan Total Peserta.
- **Monitoring Status Per Gedung**: Tingkat keterisian masing-masing gedung dalam bentuk persentase progress bar.
- **Aktivitas Hunian Terbaru**: Daftar tamu yang baru saja melakukan check-in dan alokasi kamarnya.

---

### 5.2 Laporan Tingkat Hunian Asrama (`/pimpinan/laporan-hunian`)
Merupakan laporan komprehensif riwayat seluruh transaksi menginap:

#### Fitur Filter Laporan:
1. **Pencarian Kata Kunci**: Cari berdasarkan nama peserta, NIP, instansi, program diklat, nomor kamar, atau nama gedung.
2. **Dari Tanggal & Sampai Tanggal**: Menyaring data riwayat transaksi berdasarkan rentang tanggal masuk tertentu (contoh: laporan bulanan / mingguan).
3. **Status Menginap**:
   - *Semua Status*
   - *Sedang Menginap* (penghuni aktif)
   - *Sudah Selesai* (tamu yang sudah check-out)
4. Klik tombol **Terapkan Filter** untuk menampilkan data yang disaring, atau klik tombol **Reset** untuk mengembalikan ke seluruh data.

---

### 5.3 Laporan Utilisasi Per Gedung & Rincian Denah Kamar (`/pimpinan/laporan-gedung`)
Menampilkan analisis beban dan keterisian per unit gedung:
1. Tabel menyajikan: Nama Gedung, Total Kamar, Kamar Terisi, Kamar Kosong, Total Kapasitas Orang, dan Persentase Okupansi Berbasis Tempat Tidur (*Bed Occupancy*).
2. Klik tombol **Lihat Kamar** pada gedung yang diinginkan.
3. Di bagian bawah akan muncul rincian denah status seluruh kamar di gedung tersebut:
   - **Kamar Kosong** (badge hijau, seluruh slot kosong).
   - **Kamar Terisi Sebagian** (badge orange, indikator bed pills, dan daftar nama penghuni aktif).
   - **Kamar Penuh** (badge merah, seluruh slot terisi beserta daftar penghuni).

---

### 5.4 Laporan Distribusi Per Program Diklat (`/pimpinan/laporan-diklat`)
Menampilkan rekapitulasi fasilitas asrama yang digunakan oleh masing-masing kegiatan diklat:
1. Tabel menyajikan: Nama Diklat, Periode Tanggal, Total Peserta, Jumlah Sedang Menginap, Selesai Menginap, dan Belum Check-in.
2. Klik tombol **Lihat Peserta** pada baris diklat yang ingin diperiksa.
3. Sistem akan membuka daftar seluruh peserta diklat tersebut beserta kamar yang ditempati dan status check-in masing-masing.

---

### 5.5 Fitur Ekspor Data (CSV) & Cetak Dokumen (Print PDF)
Pada seluruh halaman laporan, pimpinan dan admin dapat memanfaatkan tombol di pojok kanan atas:
- **Cetak / Print**: Membuka dialog pencetakan printer atau simpan sebagai dokumen PDF (*Save as PDF*) yang telah dioptimalkan secara rapi tanpa elemen navigasi website.
- **Export CSV**: Mengunduh seluruh data laporan ke dalam format file spreadsheet `.csv` untuk diolah lebih lanjut di Microsoft Excel.

---

## BAB VI: TANYA JAWAB UMUM & PANDUAN PEMECAHAN MASALAH (FAQ & TROUBLESHOOTING)

### Q1: Mengapa nama peserta tidak muncul pada kolom pencarian saat check-in?
> **Jawaban:** 
> Ada 2 kemungkinan:
> 1. Peserta tersebut sudah melakukan check-in dan saat ini tercatat sedang aktif menginap di kamar asrama.
> 2. Nama peserta belum didaftarkan di sistem. Silakan klik tombol **+ Tambah Peserta Baru** di samping label untuk mendaftarkannya terlebih dahulu.

---

### Q2: Mengapa nomor kamar tertentu tidak muncul di dropdown formulir check-in?
> **Jawaban:**
> Dropdown check-in hanya menampilkan kamar yang **masih memiliki slot kosong** (baik kamar berstatus kosong 0 terisi maupun kamar terisi sebagian yang belum mencapai kapasitas maksimal). Jika kamar tidak muncul, artinya seluruh tempat tidur di kamar tersebut sudah **Penuh (3 Terisi / mencapai batas kapasitas)** atau kamar sedang dalam perawatan (*maintenance*).

---

### Q3: Apakah satu kamar asrama dapat diisi oleh lebih dari 1 peserta (*sharing room*)?
> **Jawaban:**
> **Ya, sepenuhnya didukung.** Sistem mengelola ketersediaan kamar berdasarkan kapasitas masing-masing unit (misal kapasitas 2 atau 3 orang). Resepsionis dapat melakukan check-in beberapa peserta ke kamar yang sama selama kapasitas kamar belum penuh.

---

### Q4: Apa yang terjadi jika salah satu peserta di kamar bersama melakukan check-out lebih awal?
> **Jawaban:**
> Sistem hanya menyelesaikan transaksi untuk peserta yang check-out tersebut. Status kamar akan tetap mencatat peserta lain yang masih menginap (*Terisi Sebagian*), dan slot yang ditinggalkan akan otomatis terbuka kembali bagi peserta baru. Kamar baru berstatus *Kosong* apabila seluruh penghuni telah check-out.

---

### Q5: Bagaimana jika ada peserta yang berpindah kamar?
> **Jawaban:**
> 1. Buka menu **Check-out**, lalu proses check-out peserta dari kamar lamanya.
> 2. Buka menu **Check-in**, cari nama peserta tersebut, lalu pilih kamar barunya dan klik simpan.

---

### Q6: Format file apa yang didukung untuk Import Data Peserta?
> **Jawaban:**
> Format file yang didukung adalah Microsoft Excel (`.xlsx`, `.xls`) atau Comma Separated Values (`.csv`). Pastikan susunan kolom mengikuti template resmi yang dapat diunduh pada halaman Import.

---

### Q7: Bagaimana jika saya lupa kata sandi akun?
> **Jawaban:**
> Hubungi Administrator sistem. Administrator dapat mengatur ulang (*reset*) kata sandi akun Anda melalui menu **Pengguna** (`/admin/user`).

---

### Q8: Bagaimana jika hasil filter tanggal pada laporan tidak memuat data?
> **Jawaban:**
> Pastikan rentang **Dari Tanggal** lebih awal atau sama dengan **Sampai Tanggal**. Klik tombol **Reset** untuk menghapus seluruh filter dan memuat kembali semua data transaksi.

---

*Dokumen Petunjuk Teknis ini disusun sebagai standar operasional penggunaan Sistem Manajemen Asrama PPSDMAP.*  
*Pusat Pengembangan Sumber Daya Manusia Aparatur Perhubungan (PPSDMAP).*
