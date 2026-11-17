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
> 🌐 **URL Halaman Login Pengurus:** **`/login`** (Bisa login menggunakan **Email**, **NIM**, atau alias seperti `admin` / `superadmin`)

| No | Nama Pengurus | Jabatan | Role (Akses) | Email Login | NIM Login | Divisi |
|:---:|---|---|---|---|---|---|
| 1 | **Rabiah Aladawiyah** | Ketua Himpunan | `admin` (Super Admin) | `rabiah@himatika.org` | `240401000000` | Badan Pengurus Harian (BPH) |
| 2 | **Samuel Binsar Aditya Simamora** | Wakil Ketua | `admin` (Super Admin) | `samuel@himatika.org` | `240401000000` | Badan Pengurus Harian (BPH) |
| 3 | **Vina Lestari** | Sekretaris 1 | `staff` (Anggota) | `vina@himatika.org` | `240401000000` | Badan Pengurus Harian (BPH) |
| 4 | **Nadin Nadilah** | Sekretaris 2 | `staff` (Anggota) | `nadin@himatika.org` | `260401071001` | Badan Pengurus Harian (BPH) |
| 5 | **Dian Avila** | Bendahara | `staff` (Anggota) | `dian@himatika.org` | `250401010377` | Badan Pengurus Harian (BPH) |
| 6 | **M. Rendy Amril** | Ketua Stupport | `kadiv` (Kepala Divisi) | `rendy@himatika.org` | `240401010288` | Divisi Stupport |
| 7 | **Hikmah Indriyani** | Anggota Stupport | `staff` (Anggota) | `hikmah@himatika.org` | `250401010395` | Divisi Stupport |
| 8 | **M. Naufal Adzim Ramadhan** | Anggota Stupport | `staff` (Anggota) | `naufal@himatika.org` | `260401630042` | Divisi Stupport |
| 9 | **Linawati** | Anggota Stupport | `staff` (Anggota) | `linawati@himatika.org` | `260401010228` | Divisi Stupport |
| 10 | **Intan Puji Hirdian** | Ketua PDD | `kadiv` (Kepala Divisi) | `intan@himatika.org` | `250401010210` | Divisi PDD |
| 11 | **Ratu Bilqis Mantika** | Anggota PDD | `staff` (Anggota) | `balqis@himatika.org` | `240401020203` | Divisi PDD |
| 12 | **Triyas Apritantina** | Anggota PDD | `staff` (Anggota) | `triyas@himatika.org` | `260401010208` | Divisi PDD |
| 13 | **Cut Evelyn Anwar** | Ketua PSDM | `kadiv` (Kepala Divisi) | `cut.evelyn@himatika.org` | `250401010442` | Divisi PSDM |
| 14 | **Arsita Rahmawati Dewi** | Anggota PSDM | `staff` (Anggota) | `arsita@himatika.org` | `240401010318` | Divisi PSDM |
| 15 | **M. Dzaky Ghaly Ramdhani** | Anggota PSDM | `staff` (Anggota) | `dzaky@himatika.org` | `260401010316` | Divisi PSDM |
| 16 | **Johan Sudanta Hla** | Anggota PSDM | `staff` (Anggota) | `johan@himatika.org` | `260401630027` | Divisi PSDM |
| 17 | **Risma Diah Wulandari** | Anggota PSDM | `staff` (Anggota) | `risma@himatika.org` | `260401010315` | Divisi PSDM |
| 18 | **Raden Dimas Taufik Rahmat** | Ketua Intellogy | `kadiv` (Kepala Divisi) | `dimas@himatika.org` | `250401010460` | Divisi Intellogy |
| 19 | **Fajar Mauludin** | Anggota Intellogy | `staff` (Anggota) | `fajar@himatika.org` | `260401010227` | Divisi Intellogy |
| 20 | **Mochammad Wahyu R** | Anggota Intellogy | `staff` (Anggota) | `wahyu@himatika.org` | `260401630016` | Divisi Intellogy |
| 21 | **Chandra Handika** | Anggota Intellogy | `staff` (Anggota) | `chandra@himatika.org` | `260401010340` | Divisi Intellogy |

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
