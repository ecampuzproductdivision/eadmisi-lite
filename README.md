# Academic Workspace (E-Akademik)

Aplikasi manajemen akademik berbasis Laravel 12, Vite, dan Tailwind CSS v4.

---

## 📋 Persyaratan Sistem (Prerequisites)

Sebelum memulai instalasi, pastikan sistem Anda telah terpasang software berikut:

*   **PHP** `>= 8.2`
*   **Composer** (Manajer dependensi PHP)
*   **Node.js** (Rekomendasi versi LTS terbaru) & **NPM**
*   **Database Engine** (Secara default dikonfigurasi menggunakan **SQLite**, namun bisa menggunakan **MySQL**, **PostgreSQL**, dll.)

---

## ⚡ Cara Instalasi Cepat (Automated Setup)

Projek ini dilengkapi dengan skrip setup otomatis yang didefinisikan dalam `composer.json`. Untuk melakukan instalasi secara otomatis, jalankan perintah berikut secara berurutan di terminal:

1. **Instal dependensi Composer awal:**
   ```bash
   composer install
   ```

2. **Jalankan skrip setup otomatis:**
   ```bash
   composer run setup
   ```
   *Skrip ini secara otomatis akan:*
   * Menyalin file `.env.example` ke `.env` (jika `.env` belum ada).
   * Menghasilkan Application Key (`php artisan key:generate`).
   * Menjalankan migrasi database (`php artisan migrate --force`).
   * Menginstal dependensi Node.js (`npm install`).
   * Membangun aset frontend (`npm run build`).

---

## 🛠️ Cara Instalasi Manual (Manual Setup)

Jika Anda ingin melakukan instalasi langkah demi langkah secara manual:

### 1. Duplikasi File Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

### 2. Instal Dependensi Backend (Composer)
```bash
composer install
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Konfigurasi Database
Secara default, aplikasi menggunakan database **SQLite**. 
* Jika menggunakan SQLite, buat file database kosong di folder database:
  ```bash
  # Di Windows PowerShell:
  New-Item -Path .\database\database.sqlite -ItemType File
  
  # Di Linux / macOS / Git Bash:
  touch database/database.sqlite
  ```
* Jika ingin menggunakan **MySQL** atau database lain, silakan perbarui baris berikut pada file `.env` Anda:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=nama_database_anda
  DB_USERNAME=root
  DB_PASSWORD=password_anda
  ```

### 5. Jalankan Migrasi & Database Seeder
Jalankan migrasi database beserta data awal (seeders):
```bash
php artisan migrate --seed
```

### 6. Instal Dependensi Frontend & Compile Aset
```bash
npm install
npm run build
```

---

## 🚀 Menjalankan Aplikasi

Aplikasi ini menggunakan `npx concurrently` untuk menjalankan server backend PHP, queue worker, Laravel Pail (logs), dan Vite dev server secara bersamaan dengan satu perintah mudah:

```bash
composer run dev
```

Atau, jika Anda ingin menjalankannya secara manual di terminal terpisah:

*   **Menjalankan Server Backend PHP:**
    ```bash
    php artisan serve
    ```
*   **Menjalankan Vite Asset Compiler (Hot Reload):**
    ```bash
    npm run dev
    ```

Aplikasi default dapat diakses melalui browser pada alamat: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Kredensial Akun Bawaan (Default Credentials)

Setelah database berhasil di-seed, Anda dapat menggunakan akun-akun bawaan berikut untuk masuk ke aplikasi:

### 👤 Super Admin (Akses Penuh)
*   **Email:** `admin@akademik.id`
*   **Password:** `password`

### 👥 Staff (Akses Terbatas / Read-only)
*   Terdapat 25 akun staff simulasi dengan format email `staff{1-25}@akademik.id` untuk pengujian. Contoh:
    *   **Email:** `staff1@akademik.id` (hingga `staff25@akademik.id`)
    *   **Password:** `password`

---

## 📂 Informasi Tambahan

*   **Skema Program Studi:** Terdapat file SQL referensi mentah `database/eakademik_prodi.sql` yang mendefinisikan tabel dan struktur untuk manajemen program studi.
*   **Struktur Menu & Fitur:** Pengaturan hak akses (Role), Menu, Halaman (Pages), dan Matriks Izin (Permission Matrix) dapat dikelola langsung oleh akun Super Admin melalui menu **Settings**.
