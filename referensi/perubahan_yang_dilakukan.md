# Perubahan yang Dilakukan terhadap PRD dan SKPL

**Proyek:** SewaIn  
**Sumber acuan:** `referensi/PRD_Revisi_New.pdf` dan `referensi/Kel 1_SKPL_new.pdf`  
**Tanggal implementasi:** 14 November 2026  
**Stack:** Laravel 13.42, PHP 8.5, MySQL, Tailwind CSS 4, Vite

Dokumen ini mencatat semua penyesuaian, penyederhanaan, dan hal yang belum diimplementasikan dibandingkan PRD/SKPL. Tujuannya agar ruang lingkup aplikasi yang dibangun tetap jelas dan dapat dipertanggungjawabkan sebagai proyek akhir mata kuliah.

---

## 1. Ringkasan

Aplikasi yang dibangun adalah **MVP (Minimum Viable Product)** SewaIn dengan dua antarmuka terpisah:

1. **Antarmuka penyewa** — registrasi, login, katalog, detail barang, pengajuan pesanan, pembatalan pesanan, riwayat pesanan, dan ubah kata sandi.
2. **Antarmuka admin** — login, dashboard, CRUD barang, persetujuan/penolakan pesanan, daftar pengguna, laporan penyewaan, dan ubah kata sandi.

Perubahan paling besar dibandingkan PRD/SKPL adalah:

- **Hanya menggunakan 5 tabel** (`admin`, `pengguna`, `barang`, `tarif_sewa`, `pesanan_sewa`), bukan seluruh entitas yang diusulkan SKPL.
- **Autentikasi memakai username + password**, bukan Google OAuth.
- **Tidak ada peran pemilik barang (owner)**; admin mengelola seluruh barang dan pesanan.
- **Tidak ada pembayaran daring (Midtrans/QRIS)**, chat, ulasan/rating, denda, perpanjangan sewa, dan notifikasi email.
- **Tarif utama adalah harga harian**, sedangkan `tarif_sewa` menyimpan tarif per jam sebagai acuan tampilan.
- **Stok dikunci saat pesanan dibuat**, bukan saat pesanan disetujui.

---

## 2. Ruang Lingkup yang Disederhanakan

| Kebutuhan PRD/SKPL | Implementasi | Keterangan |
|---|---|---|
| Registrasi/login dengan akun Google | Registrasi/login dengan username + password | Tidak memerlukan Google OAuth; akun disimpan di tabel `pengguna` dan `admin`. |
| Verifikasi identitas KTP/kartu pelajar | Tidak diimplementasikan | Tidak ada unggah dokumen identitas, status verifikasi, atau persetujuan akun. |
| Tiga peran: penyewa, pemilik barang, admin | Dua peran: penyewa dan admin | Admin berperan sebagai pengelola seluruh barang, menggantikan fungsi owner. |
| Pemilik barang mengelola barang dan pesanannya | Admin mengelola barang dan seluruh pesanan | Tidak ada dashboard owner, komisi platform, atau pendapatan per pemilik. |
| Katalog dengan filter kategori | Pencarian kata kunci + filter ketersediaan | Kategori tidak diimplementasikan karena semua barang merupakan kamera digital. |
| Ketersediaan berbasis jadwal/tanggal | Ketersediaan berbasis jumlah stok | Tidak ada kalender ketersediaan per tanggal dan tidak ada pengecekan bentrok jadwal. |
| Durasi sewa minimal 4 jam | Durasi sewa minimal 1 hari, maksimal 30 hari | Perhitungan memakai hari, bukan jam. |
| Persetujuan pesanan maksimal 12 jam | Persetujuan manual oleh admin tanpa batas waktu otomatis | Tidak ada status `kedaluwarsa` otomatis. |
| Pembayaran QRIS via Midtrans (penuh/DP/bayar di tempat) | Simulasi modal tanpa transaksi | Tidak ada tabel pembayaran, status pembayaran, atau integrasi payment gateway; modal hanya menampilkan ringkasan dan QR ilustrasi. |
| Chat penyewa–pemilik | Tidak diimplementasikan | Komunikasi di luar sistem. |
| Perpanjangan sewa dan pencatatan pengambilan/pengembalian | Tidak diimplementasikan | Tidak ada pesanan induk, jadwal ambil/kembali, atau pemeriksaan kondisi barang. |
| Denda keterlambatan Rp100.000 per 12 jam dan denda kerusakan | Tidak diimplementasikan | Tidak ada perhitungan denda otomatis maupun manual. |
| Ulasan dan rating 1–5 | Tidak diimplementasikan | Tidak ada tabel ulasan. |
| Notifikasi email dan notifikasi real-time | Tidak diimplementasikan | Hanya flash message biasa setelah aksi berhasil/gagal. |
| Laporan dan ekspor Excel/CSV | Laporan penyewaan di halaman admin | Ringkasan status, barang terpopuler, total pendapatan, dan daftar pesanan; belum ada ekspor. |
| Pengaturan komisi platform | Hanya ubah kata sandi admin | Tidak ada konfigurasi komisi atau pengaturan sistem lain. |
| Admin menyetujui pendaftaran pemilik barang | Tidak relevan | Tidak ada pendaftaran owner. |
| Admin mengaktifkan/menonaktifkan pengguna | Admin hanya melihat daftar pengguna | Tidak ada aksi ubah status akun. |

---

## 3. Perubahan Peran dan Autentikasi

### 3.1 Dua guard terpisah

Implementasi menggunakan dua guard Laravel:

| Guard | Provider | Model | Tabel | Halaman login |
|---|---|---|---|---|
| `web` | `pengguna` | `App\Models\Pengguna` | `pengguna` | `/login` |
| `admin` | `admin` | `App\Models\Admin` | `admin` | `/admin/login` |

Konsekuensinya:

- Penyewa tidak dapat mengakses halaman admin, dan sebaliknya.
- Sesi login penyewa dan admin terpisah.
- Redirect setelah login diatur di `bootstrap/app.php`.

### 3.2 Registrasi hanya untuk penyewa

- Penyewa dapat mendaftar sendiri melalui `/register` dengan username dan password.
- Admin **tidak** dapat mendaftar sendiri.
- Akun admin dibuat melalui seeder atau halaman `/admin/setup` (hanya muncul jika tabel `admin` masih kosong).

### 3.3 Perubahan atribut pengguna

| SKPL | Implementasi |
|---|---|
| `google_id`, `nama`, `email`, `foto_profil`, `id_peran`, `status_verifikasi`, `status_akun`, `tanggal_daftar`, `terakhir_masuk` | `id`, `username` (unik), `password` (hash), `remember_token`, `created_at`, `updated_at` |
| Email unik dari Google | Username unik, tanpa email |
| Tidak ada kata sandi | Kata sandi wajib, di-hash dengan bcrypt |

Alasan: PRD versi revisi menyebut autentikasi sederhana, dan username lebih mudah dipakai untuk demo proyek akhir. Kolom `created_at`/`updated_at` dapat menggantikan `tanggal_daftar`/`terakhir_masuk`.

---

## 4. Perubahan Basis Data

### 4.1 Apakah 5 tabel cukup?

**Ya, untuk ruang lingkup MVP ini 5 tabel sudah cukup.** Seluruh kebutuhan inti (akun, barang, tarif, pesanan, dan admin) dapat dipenuhi tanpa tabel tambahan. Namun ada beberapa konsekuensi yang harus diterima:

- Satu barang hanya memiliki **satu foto** (disimpan sebagai kolom `foto`), bukan tabel `foto_barang` terpisah.
- Tidak ada tabel kategori, sehingga semua barang dianggap satu kategori (kamera digital).
- Tidak ada tabel pembayaran, ulasan, chat, notifikasi, log aktivitas, pengembalian, atau komisi.
- Rincian barang dalam pesanan disimpan langsung di `pesanan_sewa` karena aturan SKPL menyatakan **satu pesanan hanya memuat satu barang**.

Jika nantinya fitur pembayaran, ulasan, multi-foto, atau multi-item per pesanan ingin ditambahkan, tabel baru tetap diperlukan.

### 4.2 Pemetaan entitas SKPL ke 5 tabel

| Entitas pada SKPL | Implementasi |
|---|---|
| Pengguna, Peran, Verifikasi Identitas, Alamat | `pengguna` |
| Admin | `admin` |
| Barang | `barang` |
| Foto Barang | Kolom `barang.foto` |
| Kategori | Tidak ada tabel; kategori implisit |
| Tarif Sewa | `tarif_sewa` |
| Pesanan Sewa | `pesanan_sewa` |
| Detail Pesanan | Kolom `id_barang`, `durasi_hari`, `total_harga` di `pesanan_sewa` |
| Pembayaran | Tidak diimplementasikan |
| Pengembalian | Tidak diimplementasikan |
| Ulasan | Tidak diimplementasikan |
| Log Aktivitas | Tidak diimplementasikan |
| Percakapan dan Pesan Chat | Tidak diimplementasikan |
| Notifikasi | Tidak diimplementasikan |
| Komisi Platform | Tidak diimplementasikan |

### 4.3 Struktur tabel yang dipakai

#### `admin`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint unsigned, PK | Auto increment |
| `username` | varchar(50), unik | Login admin |
| `password` | varchar(255) | Hash bcrypt |
| `remember_token` | varchar(100), nullable | Fitur "ingat saya" |
| `created_at`, `updated_at` | timestamp | Dibuat otomatis Laravel |

#### `pengguna`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint unsigned, PK | Auto increment |
| `username` | varchar(50), unik | Login penyewa |
| `password` | varchar(255) | Hash bcrypt |
| `remember_token` | varchar(100), nullable | Fitur "ingat saya" |
| `created_at`, `updated_at` | timestamp | Dibuat otomatis Laravel |

#### `barang`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint unsigned, PK | Auto increment |
| `nama` | varchar(100) | Nama barang |
| `foto` | varchar(255), nullable | Nama berkas di `public/foto_barang` |
| `harga` | int unsigned | **Harga sewa per hari** (rupiah) |
| `deskripsi` | text, nullable | Deskripsi barang |
| `stok` | int unsigned, default 0 | Jumlah unit tersedia |
| `created_at`, `updated_at` | timestamp | Dibuat otomatis Laravel |

Perubahan dari SKPL:

- SKPL menyatakan harga **tidak disimpan di `barang`**, tetapi diambil dari `tarif_sewa`. Implementasi menyimpan `harga` harian di `barang` sebagai sumber perhitungan total, dan tetap menyimpan `tarif_sewa` per jam sebagai acuan tampilan.
- Kolom `spesifikasi`, `kondisi`, `status`, `jumlah_disewa`, `id_pemilik`, dan `id_kategori` tidak digunakan.
- `stok` berkurang saat pesanan dibuat dan bertambah kembali saat pesanan ditolak, dibatalkan, atau selesai.

#### `tarif_sewa`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint unsigned, PK | Auto increment |
| `id_barang` | bigint unsigned, FK → `barang.id` | Cascade on delete |
| `harga_per_jam` | int unsigned | Tarif acuan per jam |
| `tanggal_mulai` | date | Tanggal tarif mulai berlaku |
| `tanggal_selesai` | date, nullable | `NULL` berarti tarif masih aktif |
| `created_at`, `updated_at` | timestamp | Dibuat otomatis Laravel |

Perubahan dari SKPL:

- SKPL memakai `status_aktif` (boolean). Implementasi memakai `tanggal_selesai = NULL` sebagai penanda tarif aktif.
- Tarif per jam **disinkronkan otomatis** dari harga harian dengan rumus `harga / 24`, dibulatkan ke ratusan rupiah dan minimal Rp1.000.
- Tarif tidak dihitung per durasi 4/8/12/24 jam seperti usulan SKPL.

#### `pesanan_sewa`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint unsigned, PK | Auto increment |
| `nomor_pesanan` | varchar(30), unik | Format `SWI-YYYYMMDD-0001` |
| `id_penyewa` | bigint unsigned, FK → `pengguna.id` | Restrict on delete |
| `id_barang` | bigint unsigned, FK → `barang.id` | Restrict on delete |
| `tanggal_mulai` | date | Tanggal mulai sewa |
| `tanggal_selesai` | date | Tanggal selesai sewa |
| `durasi_hari` | int unsigned | Jumlah hari (inklusif) |
| `total_harga` | int unsigned | `barang.harga × durasi_hari` |
| `status` | varchar(20), default `menunggu` | Status pesanan |
| `catatan` | text, nullable | Catatan dari penyewa |
| `alasan_penolakan` | text, nullable | Diisi admin saat menolak |
| `created_at`, `updated_at` | timestamp | Dibuat otomatis Laravel |

Kolom yang ditambahkan/disesuaikan dibandingkan rancangan tabel awal:

- `id_barang`, `durasi_hari`, `total_harga` — karena rincian barang dan perhitungan harga perlu disimpan.
- `status` — untuk alur persetujuan.
- `alasan_penolakan` — agar penyewa tahu alasan penolakan.
- `catatan` — untuk kebutuhan tambahan dari penyewa.
- `nomor_pesanan`, `id_penyewa`, `tanggal_mulai`, `tanggal_selesai` — dipertahankan sesuai kebutuhan dasar pesanan.
- `created_at`/`updated_at` — ditambahkan untuk pencatatan waktu dan laporan.

Kolom SKPL yang tidak digunakan: `id_pemilik`, `id_pesanan_induk`, `tanggal_pengambilan`, `tanggal_pengembalian`, `status_pembayaran`, `total_denda`, dan kolom pembayaran lainnya.

---

## 5. Perubahan Alur Bisnis

### 5.1 Perhitungan harga

- Harga yang ditampilkan dan dihitung adalah **harga per hari**.
- Durasi dihitung **inklusif**: mulai dan selesai pada tanggal yang sama berarti 1 hari.
- `total_harga = harga per hari × durasi_hari`.
- Tidak ada DP, pajak, biaya layanan, komisi, atau denda.

### 5.2 Ketersediaan dan stok

| SKPL | Implementasi |
|---|---|
| Stok berkurang saat pesanan **dikonfirmasi** | Stok berkurang saat pesanan **dibuat** (reservasi) |
| Stok bertambah saat pesanan **selesai** | Stok bertambah saat pesanan **ditolak, dibatalkan, atau selesai** |
| Pengecekan bentrok jadwal per tanggal | Hanya mengecek jumlah `stok > 0` |
| Mencegah double booking berdasarkan jadwal | Mencegah pesanan melebihi stok total, bukan berdasarkan tanggal |

Alasan: mencegah dua penyewa memesan unit terakhir pada waktu yang sama. Konsekuensinya, barang yang sedang dipesan tidak dapat dipesan orang lain meskipun tanggalnya berbeda. Untuk MVP ini dinilai dapat diterima, tetapi perlu dicatat sebagai batasan.

### 5.3 Status pesanan

SKPL memiliki 9 status. Implementasi menyederhanakan menjadi **6 status**:

| Status implementasi | Label tampilan | Arti |
|---|---|---|
| `menunggu` | Menunggu Konfirmasi | Pesanan baru diajukan |
| `disetujui` | Disetujui | Admin menyetujui pesanan |
| `disewa` | Sedang Disewa | Barang sedang digunakan |
| `selesai` | Selesai | Barang dikembalikan, stok bertambah |
| `ditolak` | Ditolak | Admin menolak, stok bertambah |
| `dibatalkan` | Dibatalkan | Penyewa membatalkan saat masih menunggu, stok bertambah |

Status SKPL yang tidak digunakan: `kedaluwarsa`, `menunggu_pembayaran`, `terlambat`, `sudah_dikembalikan`.

### 5.4 Alur persetujuan

1. Penyewa membuat pesanan → status `menunggu`, stok langsung berkurang.
2. Admin dapat:
   - menyetujui → `disetujui`;
   - menolak dengan alasan → `ditolak`, stok bertambah.
3. Dari `disetujui`, admin menandai barang sedang disewa → `disewa`.
4. Dari `disewa`, admin menandai selesai → `selesai`, stok bertambah.
5. Penyewa hanya dapat membatalkan saat status masih `menunggu`.

Tidak ada batas waktu 12 jam, tidak ada pembatalan otomatis, dan tidak ada pembayaran sebelum persetujuan.

### 5.5 Riwayat dan laporan

- Penyewa melihat seluruh pesanannya di `/dashboard` dan detail di `/pesanan-saya/{id}`.
- Admin melihat seluruh pesanan, memfilter berdasarkan status, dan mencari nomor pesanan/penyewa.
- Laporan admin dapat difilter berdasarkan rentang tanggal dan menampilkan:
  - jumlah serta nilai pesanan per status;
  - 5 barang paling banyak dipesan;
  - total pendapatan dari pesanan `selesai`;
  - total nilai pesanan yang disetujui/sedang disewa/selesai.
- Belum ada grafik dan ekspor Excel/CSV.

### 5.6 Modal pembayaran QRIS

Pembayaran ditambahkan sebagai **simulasi alur** di halaman detail barang, bukan integrasi payment gateway nyata:

- Setelah penyewa mengisi tanggal sewa dan menekan **"Sewa sekarang"**, muncul modal pembayaran tanpa berpindah halaman.
- Modal menampilkan ringkasan penyewaan: foto dan nama barang, tanggal mulai/selesai, durasi, harga harian, dan total pembayaran.
- Modal menampilkan QR palsu (`public/images/qris-placeholder.svg`) sebagai simulasi metode QRIS.
- Pesanan baru dikirim dan dibuat ketika penyewa menekan **"Sudah bayar"**. Tombol **"Batal"**, ikon tutup, klik latar, atau tombol `Esc` menutup modal tanpa membuat pesanan.
- Setelah pesanan dibuat, penyewa diarahkan ke detail pesanan dan pesanan berstatus `menunggu` langsung muncul di dashboard admin seperti alur sebelumnya.
- Tidak ada tabel, kolom, status pembayaran, atau request ke payment gateway. QR hanya ilustrasi dan tidak ada transaksi nyata yang diproses.

---

## 6. Fitur Tambahan yang Tidak Ada di PRD/SKPL

| Fitur | Keterangan |
|---|---|
| Halaman `/admin/setup` | Membuat admin pertama hanya jika tabel `admin` masih kosong. Berguna saat instalasi baru tanpa seeder. |
| Ubah kata sandi penyewa | Di `/profil`. |
| Ubah kata sandi admin | Di `/admin/pengaturan`. |
| Filter ketersediaan di katalog | Checkbox "hanya tampilkan yang tersedia". |
| Pencarian barang | Berdasarkan nama dan deskripsi. |
| Unggah foto barang | Format JPG, JPEG, PNG, WEBP; maksimal 2 MB; disimpan di `public/foto_barang`. |
| Data awal (seeder) | 1 admin, 1 penyewa demo, 9 barang kamera, dan tarif per jam yang disinkronkan. |
| Nomor pesanan otomatis | Format `SWI-YYYYMMDD-0001`. |
| Modal pembayaran QRIS | Muncul saat penyewa menekan **"Sewa sekarang"** di detail barang. Menampilkan ringkasan sewa dan QR palsu; pesanan baru dibuat setelah tombol **"Sudah bayar"** ditekan. Tidak ada transaksi nyata. |
| Palet warna hijau minimalis | Warna utama `#121F0D`, `#758850`, dan `#DCE7BE`, diterapkan lewat variabel tema Tailwind beserta penyesuaian aset SVG kamera. |
| Flash message dan konfirmasi aksi | Menggunakan JavaScript sederhana. |

---

## 7. Asumsi dan Batasan Teknis

1. **Database:** MySQL `sewain` di `127.0.0.1:3306`, user `root` tanpa password. Migrasi menghapus dan membuat ulang 5 tabel karena tabel awal kosong.
2. **Zona waktu:** `Asia/Makassar` (WITA), sesuai SKPL.
3. **Bahasa:** antarmuka dan pesan validasi menggunakan Bahasa Indonesia.
4. **Penyimpanan foto:** langsung di `public/foto_barang`, bukan di `storage/app/public` dan tanpa enkripsi, karena tidak ada dokumen identitas.
5. **Keamanan:** kata sandi di-hash, login dibatasi 6 percobaan per menit, dan CSRF aktif. HTTPS belum diatur karena aplikasi berjalan di lingkungan lokal.
6. **Soft delete:** tidak digunakan, sesuai SKPL. Menghapus barang akan menghapus fotonya, tetapi pesanan yang terkait akan ditolak oleh foreign key `restrictOnDelete`.
7. **Pengujian:** test suite memakai database MySQL terpisah `sewain_test` karena PHP lokal tidak memiliki ekstensi `pdo_sqlite`.
8. **Frontend:** Tailwind CSS 4 + Vite. Jalankan `npm run build` atau `npm run dev` setelah mengubah aset.
9. **Seeder:** `php artisan migrate:fresh --seed` akan membuat akun dan data demo. Jangan dijalankan jika data sudah ada dan ingin dipertahankan.

---

## 8. Kredensial Default

| Peran | URL login | Username | Password |
|---|---|---|---|
| Admin | `/admin/login` | `admin` | `admin123` |
| Penyewa | `/login` | `penyewa` | `penyewa123` |

Penyewa baru juga dapat mendaftar melalui `/register`.

---

## 9. Cara Menjalankan

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Atau gunakan:

```bash
composer run dev
```

Pengujian:

```bash
php artisan test --compact
```

---

## 10. Kesimpulan

Lima tabel yang tersedia **cukup** untuk membangun MVP SewaIn dengan alur katalog → pesanan → persetujuan admin → penyelesaian. Perubahan utama terletak pada penyederhanaan autentikasi, penghapusan peran owner dan fitur pembayaran/chat/ulasan/denda, penggunaan harga harian, serta reservasi stok saat pesanan dibuat. Semua perubahan ini dicatat agar perbedaan antara dokumen kebutuhan dan aplikasi yang dibangun dapat dijelaskan dengan jelas.
