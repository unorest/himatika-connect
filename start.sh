#!/bin/bash

# Script Cepat Menjalankan HIMATIKA Connect di Codespaces
echo "Menyiapkan environment..."
export PATH="/usr/bin:$PATH"

echo "Menjalankan service database MariaDB..."
sudo service mariadb start

echo "Memulai CodeIgniter server di port 8085..."
php spark serve --host 0.0.0.0 --port 8085
