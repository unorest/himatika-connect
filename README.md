# 🚀 HIMATIKA Connect (HIMATIKA Hub)

> **Platform Terpadu Satu Pintu Informasi Publik & Workspace Manajemen Internal Himpunan Mahasiswa Informatika / Matematika.**

Dibangun menggunakan **CodeIgniter 4 (PHP 8.2+)**, **MySQL / MariaDB**, dan antarmuka modern responsif dengan **Tailwind CSS**.

---

## ⚡ Cara Cepat Menjalankan di GitHub Codespaces (Untuk Orang Lain / Dosen / Tim)

Proyek ini sudah dilengkapi dengan **script instalasi otomatis 1-klik**. Siapa pun (penguji, dosen, teman sekelompok) dapat langsung mencoba dan menjalankan website ini **100% dari browser** tanpa perlu menginstall PHP, Composer, ataupun MySQL di laptop mereka.

### 📌 Langkah 1: Buat Instance Codespaces
1. Buka repositori ini di GitHub: **[https://github.com/unorest/himatika-connect](https://github.com/unorest/himatika-connect)**
2. Klik tombol hijau **`<> Code`** di pojok kanan atas.
3. Pilih tab **`Codespaces`**, lalu klik tombol **`Create codespace on main`**.
4. Tunggu beberapa saat hingga environment Visual Studio Code di browser siap digunakan.

---

### 📌 Langkah 2: Jalankan Script Otomatis (1 Perintah)
Setelah panel **Terminal** di bagian bawah Codespaces siap, ketik perintah berikut lalu tekan **Enter**:

```bash
bash setup.sh
```

> **Apa yang dilakukan oleh `bash setup.sh` secara otomatis?**
> * Menyiapkan file konfigurasi lingkungan `.env`.
> * Memasang paket PHP sistem (`php-mysql`, `php-intl`, `php-mbstring`, dll) dan server database **MariaDB**.
> * Membuat database `himatika_db` dan mengatur hak akses user database.
> * Menjalankan **18 file Database Migration** secara otomatis.
> * Mengisi data awal (**Seeder**) untuk 4 divisi resmi, kategori berita, proker kanban, jadwal rapat, dan 8 akun pengurus demo.
> * Menjalankan server website pada port **`8085`** dengan Clean URL.

---

### 📌 Langkah 3: Buka Website di Browser
Begitu proses setup selesai:
1. Notifikasi pop-up akan muncul di pojok kanan bawah:  
   👉 **"Your application running on port 8085 is available."** ➔ Klik tombol **"Open in Browser"**.
2. *Atau secara manual:* Di panel bawah (sebelah tab `Terminal`), klik tab **`PORTS`** ➔ Arahkan kursor ke baris port **`8085`** ➔ Klik **ikon Bola Dunia 🌐 (Open in Browser)**.

Website **HIMATIKA Hub** akan langsung tampil aktif di browser Anda!

---

### 🔄 Cara Menjalankan Kembali di Lain Hari
Jika Codespaces sempat ditutup atau dihentikan (*stop*), Anda tidak perlu mengulang proses `setup.sh`. Cukup buka kembali Codespaces Anda dan jalankan shortcut ini di terminal:

```bash
bash start.sh
```

---

## 🔑 Akun Demo Pengujian (Kredensial Default Seeder)

Semua akun pengurus di bawah ini telah terdaftar otomatis dan siap digunakan untuk pengujian fitur internal:

> 🔒 **Kata sandi untuk semua akun pengurus adalah:** **`password123`**  
> 🌐 **URL Halaman Login Pengurus:** **`/login`**

| Tingkat Peran (Role) | Nama Pengurus | Email Login / NIM | Divisi Resmi | Akses Menu |
|---|---|---|---|---|
| **Admin / Ketum** | Admin HIMATIKA | `admin@himatika.org` / `22010001` | Badan Pengurus Harian (BPH) | Akses Penuh: Kelola Pengurus (RBAC), CMS, Kanban, Rapat, Moderasi |
| **Kepala Divisi (Kadiv)** | Siti Rahma | `stupport@himatika.org` / `22010002` | **Divisi Stupport** | Workspace Kadiv, Kanban Proker, Agenda Rapat, Jawab Q&A |
| **Kepala Divisi (Kadiv)** | Budi Santoso | `pdd@himatika.org` / `22010003` | **Divisi PDD** | Workspace Kadiv, Manajemen Berita & Publikasi, Liputan Acara |
| **Kepala Divisi (Kadiv)** | Ahmad Fauzan | `pdsm@himatika.org` / `22010004` | **Divisi PDSM** | Workspace Kadiv, Kaderisasi, Log Harian, RSVP Rapat |
| **Kepala Divisi (Kadiv)** | Devina Putri | `intellogy@himatika.org` / `22010005` | **Divisi Intellogy** | Workspace Kadiv, Riset IT, Proker Hackfest, Daily Tracker |
| **Staff Anggota** | Rian Pratama | `staff.stupport@himatika.org` / `23010010` | **Divisi Stupport** | Workspace Staff, Papan Kanban, Update Status Tugas, Presensi |
| **Staff Anggota** | Nadia Putri | `staff.pdd@himatika.org` / `23010011` | **Divisi PDD** | Workspace Staff, Manajemen Konten Berita, Laporan Progres |
| **Staff Anggota** | Farhan Malik | `staff.intellogy@himatika.org` / `23010012` | **Divisi Intellogy** | Workspace Staff, Pelaporan Tugas Coding, RSVP Rapat |

---

## 🏛️ Struktur 4 Divisi Resmi HIMATIKA

Himpunan Mahasiswa Informatika / Matematika digerakkan oleh 4 divisi kerja utama:
1. **🛡️ Divisi Stupport:** Bertanggung jawab atas operasional, sarana prasarana, logistik perlengkapan, serta dukungan teknis kesekretariatan.
2. **📸 Divisi PDD (Publikasi, Dekorasi & Dokumentasi):** Mengelola media sosial, branding visual, liputan dokumentasi foto/video kegiatan, dan dekorasi acara.
3. **👥 Divisi PDSM (Pengembangan Daya Saing Mahasiswa):** Menyelenggarakan kaderisasi, pembinaan karakter kepemimpinan, dan peningkatan prestasi akademik mahasiswa.
4. **💻 Divisi Intellogy (Intelligence & Technology):** Mengawal riset teknologi informasi, pengembangan proyek software/website, workshop pemrograman, dan kompetisi saintifik.

---

## ✨ Ringkasan Fitur Unggulan

### 🌐 1. Area Publik (Mahasiswa Umum)
* **Landing Page Interaktif:** Hero section dinamis, statistik otomatis (*counter* anggota, proker, dan aspirasi terjawab), showcase 4 divisi, kalender kegiatan, dan warta terbaru.
* **Forum Aspirasi & Q&A Publik:**
  * Mahasiswa dapat bertanya tanpa registrasi akun (opsi kirim anonim atau identitas).
  * Sistem *upvote* anti-duplikasi berbasis IP address.
  * Filter status (*Menunggu Jawaban* vs *Terjawab Resmi*) dan filter kategori.
* **Warta & Berita Kegiatan:** Liputan acara, estimasi waktu baca (*read time*), dan penghitung pembaca (*views*).
* **Agenda Publik:** Kalender terpadu untuk seminar, workshop, dan kompetisi.

### 🔒 2. Workspace Internal (Pengurus Himpunan)
* **Sistem Autentikasi & RBAC 3 Tingkat:** Login fleksibel dengan Email atau NIM untuk peran `admin`, `kadiv`, dan `staff`.
* **Workspace Dashboard:** Monitoring tugas mandiri, progres proker divisi, dan ringkasan agenda terdekat.
* **Kanban Task Tracker (To Do, In Progress, Review, Done):**
  * Pemindahan kartu tugas dengan pelaporan catatan perkembangan harian (*daily update*) & link Google Drive dokumen.
  * **Kalkulasi Progress Bar Otomatis:** Persentase kemajuan Program Kerja dihitung dinamis dari rasio tugas *Done*.
* **Agenda & Rapat Internal:**
  * Jadwal koordinasi dengan kode warna tiap divisi.
  * Sistem konfirmasi kehadiran (*RSVP: Hadir / Izin*).
  * Jadwal piket harian sekretariat.
* **Panel Moderasi Aspirasi Mahasiswa:** Menjawab pertanyaan aspirasi publik, pin ke beranda, atau menolak spam.
* **Kelola Pengurus & Hak Akses (Khusus Admin BPH):** Menambah pengurus baru, mutasi divisi, ubah role, dan aktivasi/nonaktifkan akun.
* **CMS Manajemen Berita:** Penulisan dan penerbitan warta rilis pers himpunan.

---

## 🛠️ Tech Stack

* **Backend Framework:** CodeIgniter 4 (v4.7+)
* **Database:** MySQL / MariaDB (18 tabel terintegrasi + Foreign Keys & Soft Deletes)
* **Frontend UI:** Tailwind CSS, Font Awesome 6 Icons, Google Fonts (*Plus Jakarta Sans*)
* **Runtime:** PHP 8.2+

---

## 💻 Panduan Instalasi di Komputer Lokal (XAMPP / Laragon)

Jika ingin menjalankan secara offline di komputer lokal:

### 1. Clone Repository
```bash
git clone https://github.com/unorest/himatika-connect.git
cd himatika-connect
```

### 2. Konfigurasi Database di `.env`
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan pastikan kredensial database sudah sesuai:
```ini
database.default.hostname = 127.0.0.1
database.default.database = himatika_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### 3. Jalankan Database Migration & Seeder
Pastikan MySQL sudah berjalan (misal via XAMPP Control Panel), lalu ketik di terminal:
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

### 4. Jalankan Server Lokal
```bash
php spark serve
```
Buka browser di: **`http://localhost:8080`**

---

## 🌐 Panduan Upload ke Web Hosting (cPanel) Tanpa GitHub

Jika Anda ingin mem-publish website ke hosting kampus atau shared hosting publik tanpa menggunakan GitHub:
1. **Export Database:** Jalankan `mysqldump -u root himatika_db > himatika_db.sql` lalu import ke phpMyAdmin hosting.
2. **Upload Berkas:** Kompres folder proyek menjadi `.zip` (pastikan folder `app`, `public`, `vendor`, `writable`, dan file `.env` ikut terkompres), lalu ekstrak di cPanel File Manager.
3. **Pindahkan Public:** Pindahkan seluruh isi folder `public/` ke dalam folder `public_html/`.
4. **Edit `public_html/index.php`:** Arahkan baris `$pathsPath` ke path folder aplikasi Anda di hosting (misal: `require FCPATH . '../himatika/app/Config/Paths.php';`).
5. **Sesuaikan `.env`:** Isi nama database, username, password MySQL hosting, dan atur `CI_ENVIRONMENT = production` serta `app.baseURL = 'https://domain-anda.com/'`.
6. **Izin Folder:** Berikan izin akses tulis (*permission 775*) untuk folder `writable/`.

---

## 📄 Lisensi
Dikembangkan untuk Himpunan Mahasiswa Informatika & Matematika (HIMATIKA).  
Didistribusikan di bawah lisensi MIT.
