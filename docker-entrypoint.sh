#!/bin/bash
set -e

# Support Render dynamic $PORT
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80>/:$PORT>/g" /etc/apache2/sites-available/000-default.conf
fi

# Run database migration automatically on deploy if DB credentials are provided
if [ -n "$database_default_hostname" ] || [ -n "$DB_HOST" ]; then
    echo "Running CodeIgniter database migrations..."
    php spark migrate --all || true
fi

exec "$@"
