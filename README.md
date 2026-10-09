# Sistem Manajemen Laboratorium

Aplikasi berbasis Laravel untuk mengelola data laboratorium, inventaris, mahasiswa, dan peminjaman ruang atau perangkat.

## Fitur

- Admin mengelola data laboratorium, mahasiswa, barang, dan akun admin.
- Mahasiswa melihat informasi laboratorium serta membuat dan memantau peminjaman.
- Admin mencatat peminjaman internal, eksternal individu, dan eksternal instansi.
- Peminjaman eksternal dikenai tarif Rp75.000 per hari kalender. Tanggal mulai dan tanggal selesai dihitung.
- Peminjaman eksternal yang dicatat admin langsung disetujui. Admin harus mengonfirmasi pelunasan sebelum checkout.
- Peminjaman instansi menggunakan seluruh laboratorium selama periode peminjaman.
- Admin mencatat checkout dan pengembalian aktual.
- Admin memperbarui kondisi meja dan perangkat, melihat riwayat inventaris, serta mengekspor data inventaris.
- Tabel daftar dapat digeser horizontal di layar ponsel.

## Teknologi dan prasyarat

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL atau SQLite
- Laravel 13

## Menjalankan secara lokal

1. Pasang dependensi PHP:

   ```bash
   composer install
   ```

2. Buat file environment dan application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Di Windows PowerShell, gunakan:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

3. Atur koneksi database di `.env`. Contoh untuk MySQL:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=manajemen-lab
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Buat database tersebut terlebih dahulu di MySQL. Untuk memakai SQLite, atur `DB_CONNECTION=sqlite` dan siapkan file database sesuai konfigurasi project.

4. Jalankan migration:

   ```bash
   php artisan migrate
   ```

   **Jangan gunakan `php artisan migrate:fresh` pada database yang berisi data.** Perintah tersebut menghapus seluruh tabel sebelum membuatnya kembali.

5. Pasang dan build aset frontend:

   ```bash
   npm install
   npm run build
   ```

6. Jalankan server Laravel:

   ```bash
   php artisan serve
   ```

   Buka `http://127.0.0.1:8000`.

   Untuk hot reload saat pengembangan, jalankan `npm run dev` di terminal terpisah.

## Login

### Admin

- Halaman login: `http://127.0.0.1:8000/admin/login`
- Username: `nama_cantik`
- Password: `nama_cantik123`

**Penting:** kredensial di atas dicantumkan sesuai permintaan, tetapi tidak dibuat oleh `DatabaseSeeder` pada repository ini. Akun tersebut hanya dapat digunakan jika sudah tersedia di tabel `admin`. Pada database baru, buat akun admin melalui `http://127.0.0.1:8000/admin/register`, lalu masuk lewat halaman login admin.

Ganti password demo sebelum aplikasi digunakan di lingkungan publik atau produksi. Jangan unggah password aktif ke repository publik.

### Mahasiswa

- Halaman login: `http://127.0.0.1:8000/mahasiswa/login`
- Mahasiswa masuk menggunakan NIM dengan password NIM juga, jangan lupa untuk mengubahnya setelah login.

## Menjalankan tes

```bash
php artisan test
```

## Catatan keamanan dan data

- Jangan membagikan atau memasukkan file `.env` ke repository.
- Gunakan `APP_DEBUG=false` dan password yang kuat pada lingkungan produksi.
- Batasi atau nonaktifkan pendaftaran admin publik sebelum aplikasi dipublikasikan.
- Buat backup database secara berkala.
- Gunakan `php artisan migrate` untuk menerapkan migration baru tanpa menghapus data yang sudah ada.
