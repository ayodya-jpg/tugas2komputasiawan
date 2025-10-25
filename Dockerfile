# Tahap 1: Base Image (PHP 8.2-FPM)
FROM php:8.2-fpm-alpine

# Setel direktori kerja di dalam container
WORKDIR /var/www/html

# Install dependensi PHP yang umum untuk Laravel
RUN apk add --no-cache \
    curl \
    git \
    nodejs-current \
    npm \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    jpeg-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    gd \
    mbstring \
    exif \
    pcntl \
    bcmath \
    xml \
    pdo_mysql

# Install Composer (Manajer dependensi PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file proyek ke dalam image
COPY . .

# ✅ Tambahkan konfigurasi agar Git tidak error di folder ini
RUN git config --global --add safe.directory /var/www/html

# Install dependensi via Composer (mode produksi)
RUN composer install --optimize-autoloader --no-dev

# Atur kepemilikan dan izin file langsung di build
RUN chown -R www-data:www-data storage bootstrap/cache || true \ && chmod -R 777 storage bootstrap/cache || true

# Jalankan container sebagai root agar Jenkins bisa akses semua file
USER root

# Expose port default untuk PHP-FPM
EXPOSE 9000

# Perintah default untuk menjalankan container
CMD ["php-fpm"]
