# 🚀 HIMATIKA Connect (HIMATIKA Hub)

> **Platform Terpadu Satu Pintu Informasi Publik & Workspace Manajemen Internal Himpunan Mahasiswa Informatika/Matematika.**

Dibangun menggunakan **CodeIgniter 4 (PHP 8.2+)**, **MySQL/MariaDB**, dan antarmuka modern responsif dengan **Tailwind CSS**.

---

## ✨ Fitur Utama

### 🌐 1. Area Publik (Mahasiswa Umum)
* **Landing Page Interaktif:** Hero section dinamis, statistik otomatis (*counter* anggota, proker, dan aspirasi terjawab), showcase divisi, agenda acara mendatang, dan warta terbaru.
* **Forum Aspirasi & Q&A Terkurasi:**
  * Mahasiswa dapat bertanya tanpa login akun (opsi kirim sebagai anonim atau menyertakan identitas).
  * Sistem *upvote* anti-duplikasi berbasis alamat IP.
  * Penyaringan status (*Menunggu Jawaban* vs *Terjawab Resmi*) dan filter kategori topik.
* **Warta & Jurnal Kegiatan:** Katalog artikel liputan acara, estimasi waktu baca (*read time*), dan pembaca counter (*views*).
* **Kalender Agenda Publik:** Jadwal seminar, workshop, dan kompetisi terbuka.

### 🔒 2. Workspace Internal (Pengurus Himpunan)
* **Sistem Autentikasi & RBAC 3 Tingkat:**
  * Login fleksibel dengan **Email** atau **NIM**.
  * Role: `admin` (BPH), `kadiv` (Kepala Divisi), dan `staff` (Anggota).
* **Workspace Dashboard:** Ringkasan statistik tugas saya, monitoring proker divisi, dan antrean aspirasi.
* **Task Tracker Papan Kanban (To Do, In Progress, Review, Done):**
  * Pemindahan status kartu tugas.
  * Pelaporan log perkembangan harian (*daily update*) & tautan berkas dokumen (*Google Drive/Docs*).
  * **Kalkulasi Progress Bar Otomatis:** Persentase kemajuan Program Kerja dihitung otomatis dari rasio tugas *Done*.
* **Agenda & Rapat Internal:**
  * Kalender koordinasi terpadu dengan warna identitas masing-masing divisi.
  * Sistem konfirmasi presensi rapat (*RSVP: Hadir / Izin*).
  * Jadwal piket harian sekretariat himpunan.
* **Panel Moderasi Q&A Mahasiswa:** Verifikasi aspirasi masuk, pemberian jawaban resmi pengurus, fitur pin ke beranda, dan penolakan spam.
* **Manajemen Berita (CMS):** Penulisan, penyuntingan, dan penerbitan warta rilis himpunan.

---

## 🛠️ Tech Stack

* **Backend Framework:** CodeIgniter 4 (v4.7+)
* **Database:** MySQL / MariaDB (18 tabel terintegrasi + relasi FK & Soft Deletes)
* **Frontend UI:** Tailwind CSS, Font Awesome 6 Icons, Google Fonts (*Plus Jakarta Sans*)
* **Runtime:** PHP 8.2+

---

## 🚀 Panduan Instalasi Lokal

### 1. Clone Repository
```bash
git clone https://github.com/USERNAME/himatika-connect.git
cd himatika-connect
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan kredensial koneksi database MySQL Anda di `.env`:
```ini
database.default.hostname = 127.0.0.1
database.default.database = himatika_db
database.default.username = root
database.default.password = 
```

### 4. Jalankan Migrasi & Database Seeder
Pastikan MySQL sudah aktif, lalu jalankan:
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

### 5. Jalankan Web Server Lokal
```bash
php spark serve
```
Buka browser di: **`http://localhost:8080`**

---

## 🔑 Akun Demo Pengujian (Kredensial Bawaan Seeder)

> Kata sandi untuk semua akun bawaan adalah: **`password123`**

| Role / Tingkat | Nama | Email Pengurus | Divisi |
|---|---|---|---|
| **Admin / BPH** | Admin HIMATIKA (Ketum) | `admin@himatika.org` | Badan Pengurus Harian |
| **Kepala Divisi** | Siti Rahma | `advokasi@himatika.org` | Advokasi & Kesra |
| **Kepala Divisi** | Devina Putri | `teknologi@himatika.org` | Teknologi & Inovasi |
| **Kepala Divisi** | Budi Santoso | `humas@himatika.org` | Humas & Komunikasi |
| **Staff Pengurus** | Rian Pratama | `rian.staff@himatika.org` | Advokasi |

---

## 📄 Lisensi
Dikembangkan untuk Himpunan Mahasiswa Informatika & Matematika (HIMATIKA).
Didistribusikan di bawah lisensi MIT.
