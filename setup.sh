#!/bin/bash

# ==============================================================================
# Script Otomatis Setup HIMATIKA Connect (Codespaces & Debian/Ubuntu)
# Cukup jalankan: bash setup.sh
# ==============================================================================

echo "=================================================="
echo "  MEMULAI SETUP OTOMATIS HIMATIKA CONNECT"
echo "=================================================="

# 1. Pastikan file .env ada
if [ ! -f .env ]; then
    echo "[1/6] Menyalin .env.example menjadi .env..."
    cp .env.example .env
    sed -i "s/^app.baseURL/# app.baseURL/" .env
else
    echo "[1/6] File .env sudah ada."
    sed -i "s/^app.baseURL/# app.baseURL/" .env
fi

# 2. Pastikan paket PHP sistem & MariaDB terpasang
echo "[2/6] Memeriksa & menginstall PHP modul + MariaDB..."
sudo apt-get update -qq
sudo apt-get install -y -qq php-cli php-mysql php-intl php-mbstring php-xml php-curl mariadb-server > /dev/null 2>&1

# 3. Nyalakan service database
echo "[3/6] Menjalankan service MariaDB..."
sudo service mariadb start

# 4. Buat database himatika_db & set hak akses root
echo "[4/6] Mengonfigurasi database himatika_db..."
sudo mysql -e "
CREATE DATABASE IF NOT EXISTS himatika_db;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
" 2>/dev/null || sudo mariadb -e "
CREATE DATABASE IF NOT EXISTS himatika_db;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
" 2>/dev/null

# 5. Prioritaskan PHP sistem di PATH
export PATH="/usr/bin:$PATH"

# 6. Jalankan Migrasi dan Seeder
echo "[5/6] Menjalankan Database Migration & Seeder..."
php spark migrate --all
php spark db:seed DatabaseSeeder

echo "=================================================="
echo "  SETUP SELESAI! MEMULAI SERVER..."
echo "  Akun Pengurus Default:"
echo "  - Admin:  Username: admin | Password: password123"
echo "=================================================="

# Jalankan server
php spark serve --host 0.0.0.0 --port 8085
