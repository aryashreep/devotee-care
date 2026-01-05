#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Install System Dependencies ---
echo "--- Installing system dependencies... ---"
apt-get update
apt-get install -yq --no-install-recommends \
  php-cli \
  php-dom \
  php-xml \
  php-sqlite3 \
  php-mbstring \
  php-gd \
  unzip \
  nodejs \
  npm \
  sqlite3

# --- Install Composer ---
echo "--- Installing Composer... ---"
if [ ! -f "composer.phar" ]; then
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
else
    echo "Composer already installed."
fi

# --- Install PHP Dependencies ---
echo "--- Installing PHP dependencies... ---"
php composer.phar install

# --- Install NPM Dependencies ---
echo "--- Installing NPM dependencies... ---"
npm install

# --- Install Playwright and its dependencies ---
echo "--- Installing Playwright... ---"
npm i -D @playwright/test
npx playwright install --with-deps
echo "--- Playwright setup complete. ---"

# --- Configure environment file (.env) ---
echo "--- Configuring environment file (.env)... ---"
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "--- .env file created. ---"
else
    echo "--- .env file already exists. ---"
fi

# --- Generate Application Key ---
echo "--- Generating application key... ---"
php artisan key:generate

# --- Create Database File ---
echo "--- Creating database file... ---"
if [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
    echo "--- Database file created. ---"
else
    echo "--- Database file already exists. ---"
fi

# --- Install Spatie Permissions and Run Migrations ---
echo "--- Setting up Spatie Permissions and database... ---"
php composer.phar require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="permission-migrations"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="permission-config"
php artisan migrate:fresh --seed

# --- Clear Application Caches ---
echo "--- Clearing application caches... ---"
php artisan optimize:clear

echo "--- Development Environment Setup Complete! ---"
