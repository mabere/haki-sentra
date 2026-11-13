# Sentra HAKI

## Instalasi di hosting cPanel

Pastikan hosting menyediakan PHP 8.3 atau lebih baru, MySQL/MariaDB, dan ekstensi PHP yang dibutuhkan Laravel (terutama `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `xml`, `ctype`, dan `tokenizer`).

1. **Buat database.** Di cPanel buka **MySQL Databases**, buat database dan pengguna, lalu hubungkan pengguna ke database dengan semua hak akses. Catat nama database, pengguna, dan kata sandi.
2. **Impor database fresh.** Buka **phpMyAdmin** dari cPanel, pilih database yang baru dibuat, buka tab **Import**, pilih file `haki_sentra_db.sql` dari paket aplikasi, lalu klik **Import/Go**. Tunggu sampai muncul pesan bahwa impor berhasil. Jangan jalankan migration setelah mengimpor file ini.
3. **Unduh aplikasi.** Buka repository GitHub, pilih **Code → Download ZIP**, lalu unggah dan ekstrak paket ke folder aplikasi, misalnya `sentrahaki` di luar `public_html` jika hosting mengizinkan. Pastikan file `haki_sentra_db.sql` juga tersedia di paket; bila terpisah, unduh dari repository.
4. **Siapkan file publik.** Arahkan document root domain ke folder `public` milik aplikasi. Jika cPanel tidak bisa mengubah document root, minta bantuan penyedia hosting untuk mengarahkannya ke folder `public`. Jangan menaruh seluruh folder aplikasi di lokasi yang bisa diakses publik.
5. **Pasang dependensi.** Dari Terminal cPanel, masuk ke folder aplikasi dan jalankan:

   ```bash
   composer install --no-dev --optimize-autoloader
   ```

   Jika tidak tersedia Terminal/Composer, minta penyedia hosting memasang dependensi Laravel untuk Anda.

6. **Buat konfigurasi.** Salin `.env.example` menjadi `.env`, lalu isi nilai berikut:

   ```dotenv
   APP_NAME="Sentra HAKI"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://alamat-domain-anda
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=pengguna_database
   DB_PASSWORD=kata_sandi_database
   ```

   Ganti contoh nilai dengan data hosting. Buat `APP_KEY` dengan perintah `php artisan key:generate` dari folder aplikasi. Simpan `.env` di luar folder publik dan jangan bagikan isinya.

7. **Bersihkan cache dan atur izin.** Jalankan `php artisan optimize:clear`. Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis oleh aplikasi.
8. **Buka situs.** Kunjungi alamat domain menggunakan HTTPS dan pastikan halaman aplikasi tampil.

## Catatan

- File `.env` berisi rahasia hosting; jangan unggah atau kirimkan kepada orang lain.
- Folder `storage` menyimpan file aplikasi. Pastikan file privat tidak dapat diakses langsung melalui URL publik.
- Bila hosting tidak menyediakan Terminal, minta penyedia hosting menjalankan Composer dan perintah Artisan di atas.
