# Capstone Project - Simple LMS

Proyek ini dibuat untuk memenuhi tugas akhir UAS mata kuliah Pemrograman Sisi Server. Proyek ini dibuat oleh Aurel Putri Widyanti (A11.2022.14494).

Proyek ini menyediakan lingkungan Dockerized untuk Laravel 11, termasuk PHP, Nginx, dan MySQL untuk keperluan pengembangan. Ikuti langkah-langkah di bawah untuk menjalankan proyek ini.

## Deskripsi Proyek

Proyek backend untuk aplikasi Simple LMS ini dirancang untuk menyediakan layanan yang mendukung pengelolaan pembelajaran daring. 

## Fitur Utama
### 1. Manajemen Kursus
    - Menampilkan daftar semua kursus yang telah dibuat oleh pengguna yang sedang login.
    - Menampilkan daftar seluruh kursus yang tersedia dalam sistem.
    - Membuat kursus baru dan menyimpannya di dalam database.
    - Menampilkan detail dari kursus tertentu berdasarkan ID kursus.
    - Mengedit informasi kursus yang mereka miliki.
    - Menghapus kursus yang mereka buat dari sistem.
    - Menampilkan semua konten yang tersedia di dalam kursus tertentu.
    - Menampilkan konten tertentu dari kursus berdasarkan ID konten.
    - Memungkinkan pengguna (student) untuk mendaftar dan mengikuti kursus tertentu.

### 2. Manajemen Kontent Kursus
    - Memungkinkan pengguna (teacher) untuk menambahkan konten baru pada kursus tertentu.
    - Menampilkan daftar semua komentar yang diberikan oleh pengguna (teacher atau student) pada konten tertentu.
    - Memungkinkan pengguna (teacher atau student) untuk memberikan komentar pada konten tertentu di dalam kursus.

### 3. Sistem Komen
    Hanya pengguna yang membuat komentar (teacher atau student) yang memiliki izin untuk menghapus komentar mereka sendiri.

### 4. Login
    Memungkinkan pengguna untuk masuk ke sistem dengan menggunakan kredensial mereka (email dan password).

## Fitur Tambahan
### 1. Register (+1 point)
    Mendaftarkan pengguna baru ke dalam sistem.

### 2. Manajemen Pemberitahuan Kursus (+4 point)
    - Membuat pengumuman baru pada kursus tertentu (hanya teacher).
    - Menampilkan semua pengumuman pada kursus tertentu (dapat diakses oleh teacher dan student).
    - Mengedit pengumuman yang sudah dibuat (hanya teacher).
    - Menghapus pengumuman (hanya teacher).

### 3. Manajemen Kategori Kursus (+4 point)
    - Membuat kategori baru.
    - Menampilkan semua kategori yang telah dibuat oleh semua pengguna.
    - Menghapus kategori yang dibuat oleh pengguna tersebut.
    - Menambahkan kolom kategori pada kursus saat membuat atau mengedit kursus. Kolom ini bersifat opsional (boleh kosong).

### 4. Manajemen Feedback Kursus (+4 point)
    - Menambahkan umpan balik pada kursus tertentu.
    - Menampilkan semua umpan balik pada kursus tertentu.
    - Memungkinkan student mengedit umpan balik yang telah ditulis.
    - Memungkinkan student menghapus umpan balik yang telah ditulis.
    
### 5. Manajemen Profil Pengguna (+2 point)
    - Menampilkan profil lengkap seorang pengguna, termasuk Nama Depan, Nama Belakang, Email, Handphone, Deskripsi, Foto Profil, daftar kursus yang diikuti, dan daftar kursus yang dibuat.
    - Memungkinkan pengguna yang sedang login untuk mengedit informasi profilnya, meliputi Nama Depan, Nama Belakang, Email, Handphone, Deskripsi, dan Foto Profil.

Aplikasi ini diharapkan dapat membantu dalam mengelola pembelajaran daring dengan lebih efisien dan efektif.

## Dokumentasi API
Dokumentasi API dapat diakses di [http://localhost:8080/docs/api](http://localhost:8080/docs/api)

## Prasyarat

Pastikan Anda telah menginstal:

-   [Docker](https://docs.docker.com/get-docker/)
-   [Docker Compose](https://docs.docker.com/compose/install/)

## Memulai

### Langkah 1 : Clone Repository

```bash
git clone https://github.com/aurelwidyanti/capstone-simple-lms.git
cd capstone-simple-lms
```

`Pastikan Docker Engine sudah berjalan sebelum melanjutkan.`

### Langkah 2 : Build dan Jalankan Kontainer Docker

Pastikan berada di direktori utama proyek (`capstone-simple-lms`) dan jalankan perintah berikut untuk membangun dan memulai kontainer.

```bash
docker-compose up -d --build
```

### Langkah 3 : Mengatur Izin

Untuk menghindari masalah izin, atur izin untuk direktori penyimpanan (`storage`) dan cache Laravel:

```bash
docker-compose exec app chmod -R 777 /var/www/storage /var/www/bootstrap/cache
```

### Langkah 4: Konfigurasi Laravel

1. Install dependensi Laravel menggunakan Composer:

    ```bash
    docker exec simple-lms-app composer install
    ```

2. Salin file `.env.example` menjadi `.env`:

    ```bash
    docker exec simple-lms-app cp .env.example .env
    ```

3. Generate application key:

    ```bash
    docker exec simple-lms-app php artisan key:generate
    ```

### Langkah 5: Atur Konfigurasi Database

Perbarui konfigurasi database di file `.env` yang berada di direktori `laravel-pss-app`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=simple-lms
DB_USERNAME=simple-lms
DB_PASSWORD=laravel
```

### Langkah 6: Jalankan Migrasi

Jalankan migrasi untuk membuat tabel dan `--seed` untuk mengisi data awal:

```bash
docker exec simple-lms-app php artisan migrate --seed
```

### Mengakses Aplikasi

Setelah kontainer berjalan, Anda bisa mengakses aplikasi Laravel di: [http://localhost:8080](http://localhost:8080)

## Dokumentasi API

Dokumentasi API dapat diakses di: [http://localhost:8080/docs/api](http://localhost:8080/docs/api)

## Menghentikan Kontainer

Untuk menghentikan kontainer Docker, jalankan:

```bash
docker-compose down
```

## Perintah Tambahan

-   **Membangun Ulang Kontainer**: Jika Anda melakukan perubahan pada `Dockerfile` atau `docker-compose.yml`, Anda dapat membangun ulang kontainer dengan:

    ```bash
    docker-compose up -d --build
    ```

-   **Mengakses Kontainer PHP**: Untuk masuk ke dalam kontainer PHP dan menjalankan perintah Artisan, gunakan:

    ```bash
    docker exec -it simple-lms-app bash
    ```

-   **Mengakses Kontainer MySQL**: Untuk masuk ke dalam kontainer MySQL, gunakan:

    ```bash
    docker exec -it mysql mysql -u simple-lms -p
    ```

    Masukan password `laravel` untuk mengakses database.

## Struktur Proyek

-   `Dockerfile`: Mendefinisikan lingkungan PHP-FPM.
-   `docker-compose.yml`: Mengatur kontainer Laravel, Nginx, dan MySQL.
-   `simple-lms-app`: Direktori aplikasi Laravel
-   `mysql`: Direktori penyimpanan data MySQL.
-   `nginx/laravel.conf`: File konfigurasi Nginx untuk menjalankan aplikasi Laravel.
