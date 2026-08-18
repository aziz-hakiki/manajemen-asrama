# Product Requirements Document (PRD)
**Sistem Manajemen Asrama PPSDMAP**

## 1. Pendahuluan
### 1.1 Deskripsi Singkat
Aplikasi Sistem Manajemen Asrama PPSDMAP adalah platform berbasis web untuk mempermudah dan mendigitalkan operasional pengelolaan asrama. Sistem ini mengelola pendataan gedung, kamar, peserta diklat, serta memfasilitasi proses *check-in* dan *check-out* peserta. Sistem juga menyediakan laporan *real-time* untuk kebutuhan pemantauan oleh pimpinan.

### 1.2 Pengguna Sistem (Roles)
Terdapat 3 peran (*role*) pengguna utama dengan hak akses yang berbeda-beda:
1. **Admin**: Administrator sistem yang bertugas menyiapkan data master (gedung, kamar, diklat, pengguna) dan mengelola keseluruhan sistem.
2. **Resepsionis**: Operator lapangan yang bertugas melayani proses kedatangan (*check-in*), kepulangan (*check-out*), dan memantau status penghuni harian.
3. **Pimpinan**: Manajemen tingkat atas yang membutuhkan informasi strategis, statistik hunian, dan laporan operasional asrama.

---

## 2. Fitur dan Menu Sistem (Berdasarkan Role)

### 2.1 Menu Admin
Admin memiliki akses penuh ke pengaturan sistem dan data master.
* **Dashboard**: Ringkasan statistik sistem (total gedung, kamar, diklat aktif, peserta, dll).
* **Gedung**: Pengelolaan data gedung (Tambah, Edit, Hapus, Lihat data gedung seperti Gedung A, Gedung B, Gedung C).
* **Kamar**: Pengelolaan data kamar (Nomor kamar, kapasitas, relasi dengan gedung, status awal kamar).
* **Diklat**: Pengelolaan data program diklat (Nama diklat, tanggal mulai, tanggal selesai).
* **Peserta**: Pengelolaan data peserta (Nama, NIP/NIK, instansi, relasi dengan diklat).
* **Import Excel**: Fitur unggah dokumen Excel untuk memasukkan data peserta diklat secara massal dari panitia diklat.
* **Pengguna**: Manajemen akun aplikasi (Membuat akun untuk Admin, Resepsionis, dan Pimpinan beserta *password*).
* **Laporan**: Akses ke seluruh rekapitulasi data operasional asrama.

### 2.2 Menu Resepsionis
Resepsionis fokus pada operasional harian dan status kamar.
* **Dashboard**: Menampilkan informasi *real-time* (jumlah *check-in* hari ini, *check-out* hari ini, dan total kamar kosong).
* **Check-in**: Fitur untuk melayani peserta datang. Resepsionis mencari nama peserta, melihat daftar kamar kosong yang tersedia, memilih kamar, lalu menyimpan tanggal & waktu masuk.
* **Check-out**: Fitur untuk memproses kepulangan peserta. Resepsionis mencari peserta yang sedang menginap, memproses kepulangan, mencatat tanggal keluar, dan mengosongkan status kamar.
* **Kamar Kosong**: Daftar ketersediaan kamar yang belum terisi (siap huni).
* **Penghuni Aktif**: Daftar peserta yang berstatus sedang menginap di asrama (kamar berstatus Terisi).
* **Tambah Peserta**: Form input manual untuk mendaftarkan peserta baru jika tidak tercakup dalam data *import* Excel atau ada peserta tambahan mendadak.

### 2.3 Menu Pimpinan
Pimpinan fokus pada pemantauan hasil (*read-only/report*).
* **Dashboard**: Ringkasan tingkat hunian (*occupancy rate*) berupa grafik atau angka (total kamar terisi vs kosong).
* **Laporan Hunian**: Detail ringkasan penghuni aktif, kapasitas terisi, dan kapasitas kosong.
* **Laporan Per Gedung**: Statistik dan detail hunian yang difilter dan dikelompokkan berdasarkan gedung (Gedung A, B, C, dst).
* **Laporan Per Diklat**: Riwayat peserta dan alokasi kamar berdasarkan masing-masing kegiatan diklat (peserta dari diklat X menempati kamar mana saja).

---

## 3. Alur Kerja Utama (*User Flow*)

### Tahap 1: Admin Menyiapkan Data Master
1. Admin login ke dalam sistem.
2. Admin membuat data **Gedung** (contoh: Gedung A, Gedung B, Gedung C).
3. Admin membuat daftar **Kamar** dan menautkannya ke masing-masing Gedung.
4. Admin menginput data program **Diklat** yang akan/sedang berlangsung.
5. Admin mengatur **Akun Pengguna** untuk resepsionis dan pimpinan.

### Tahap 2: Input Data Peserta
* **Opsi Massal**: Admin masuk ke menu **Import Excel** dan mengunggah *file* berisikan daftar peserta dari panitia diklat.
* **Opsi Manual**: Resepsionis masuk ke menu **Tambah Peserta** untuk menginput data peserta secara satuan (jika diperlukan).

### Tahap 3: Check-in (Peserta Datang)
1. Peserta mendatangi resepsionis.
2. Resepsionis membuka menu **Check-in** dan mencari nama peserta tersebut.
3. Resepsionis mengecek daftar **Kamar Kosong** di sistem.
4. Resepsionis menetapkan kamar untuk peserta dan menekan tombol simpan/check-in.
5. Sistem mencatat tanggal masuk peserta.

### Tahap 4: Menginap (Status Berjalan)
* Sistem otomatis mengubah status kamar menjadi **Terisi**.
* Peserta tersebut otomatis masuk ke dalam daftar **Penghuni Aktif**.

### Tahap 5: Check-out (Peserta Pulang)
1. Peserta mengembalikan kunci kamar ke resepsionis.
2. Resepsionis membuka menu **Check-out** dan memilih peserta bersangkutan.
3. Resepsionis mengonfirmasi kepulangan.
4. Sistem mencatat tanggal keluar peserta.
5. Status kamar otomatis kembali menjadi **Kosong** dan siap digunakan.

### Tahap 6: Laporan Pimpinan
1. Pimpinan masuk ke sistem untuk melihat performa asrama.
2. Melalui menu laporan, pimpinan bisa melihat jumlah penghuni aktif (*real-time*).
3. Pimpinan juga dapat melihat rekapitulasi data hunian per gedung maupun rekapitulasi jumlah peserta per kegiatan diklat.

---

## 4. Struktur Database Tingkat Tinggi (Opsional/Rancangan)
Untuk mendukung fitur di atas, berikut adalah entitas database utama yang akan dibuat:
1. `Users` (id, nama, email, password, role)
2. `Gedung` (id, nama_gedung)
3. `Kamar` (id, gedung_id, nomor_kamar, kapasitas, status)
4. `Diklat` (id, nama_diklat, tanggal_mulai, tanggal_selesai)
5. `Peserta` (id, diklat_id, nama_peserta, instansi)
6. `Transaksi_Asrama` (id, peserta_id, kamar_id, tanggal_masuk, tanggal_keluar, status_menginap)

---
*Dokumen ini merupakan kerangka awal PRD yang bisa digunakan sebagai acuan pengembangan aplikasi selanjutnya.*
