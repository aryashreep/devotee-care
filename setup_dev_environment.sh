#!/bin/bash
set -e

# Exit with an error message if a command fails
function error_exit {
    echo "Error: $1" >&2
    exit 1
}

echo "--- Starting Development Environment Setup ---"

# 1. Install System Dependencies (PHP and extensions)
echo "--- Installing PHP and required extensions... ---"
sudo apt-get update -y || error_exit "Failed to update package lists."

# Added php8.3-curl and build-essential (needed for PECL/PCOV)
sudo apt-get install -y \
    build-essential \
    php-cli \
    php8.3-curl \
    php8.3-dom \
    php8.3-xml \
    php8.3-sqlite3 \
    php8.3-mbstring \
    php8.3-gd \
    php-pear \
    php-dev || error_exit "Failed to install PHP or extensions."

# Install and enable PCOV for code coverage
# Using -f with pecl to avoid prompts if already installed
sudo pecl install -f pcov || true
echo "extension=pcov.so" | sudo tee /etc/php/8.3/mods-available/pcov.ini > /dev/null
sudo ln -sfn /etc/php/8.3/mods-available/pcov.ini /etc/php/8.3/cli/conf.d/20-pcov.ini

echo "--- PHP and extensions installed successfully. ---"

# 2. Install Composer
echo "--- Installing Composer... ---"
if [ ! -f composer.phar ]; then
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" || error_exit "Failed to download Composer installer."
    php composer-setup.php --filename=composer.phar || error_exit "Composer installation failed."
    php -r "unlink('composer-setup.php');"
    echo "--- Composer installed locally as composer.phar. ---"
else
    echo "--- composer.phar already exists. ---"
fi

# 3. Install Composer Dependencies
echo "--- Installing project dependencies with Composer... ---"
# Added --no-interaction for automated setups
php composer.phar install --no-interaction || error_exit "Composer install failed."
echo "--- Project dependencies installed successfully. ---"

# 4. Install Node.js Dependencies
echo "--- Installing Node.js dependencies... ---"
npm install || error_exit "NPM install failed."
echo "--- Node.js dependencies installed successfully. ---"

# 5. Install Playwright
echo "--- Installing Playwright and its browser dependencies... ---"
# Note: Ensure python3-pip is installed if pip fails
pip install playwright || error_exit "Failed to install Playwright via pip."
playwright install --with-deps || error_exit "Failed to install Playwright browsers."
echo "--- Playwright setup complete. ---"

# 6. Set up .env file
echo "--- Configuring environment file (.env)... ---"
if [ ! -f .env ]; then
    cp .env.example .env || error_exit "Failed to copy .env.example."
    echo "--- .env file created from .env.example. ---"
else
    echo "--- .env file already exists. ---"
fi

# Ensure DB_DATABASE path is correctly set for SQLite
# We use sed to replace or append to ensure it points to the absolute-style path if needed
if grep -q "DB_CONNECTION=" .env; then
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
fi

if ! grep -q "DB_DATABASE=" .env; then
    echo "DB_DATABASE=$(pwd)/database/database.sqlite" >> .env
    echo "--- Added DB_DATABASE to .env file. ---"
fi

# 7. Generate Application Key
echo "--- Generating application key... ---"
php artisan key:generate || error_exit "Failed to generate application key."

# 8. Create SQLite Database File
echo "--- Creating database file... ---"
mkdir -p database
touch database/database.sqlite || error_exit "Failed to create database file."
echo "--- Database file created. ---"

# 9. Run Migrations and Seeders
echo "--- Running database migrations and seeders... ---"
php artisan migrate:fresh --seed --force || error_exit "Failed to run migrations."
echo "--- Database is set up and seeded. ---"

# 10. Clear Caches
echo "--- Clearing application caches... ---"
php artisan optimize:clear || error_exit "Failed to clear caches."
echo "--- Caches cleared. ---"

echo "--- Development Environment Setup Complete! ---"