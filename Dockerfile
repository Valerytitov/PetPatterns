# ЭТАП 1: Composer для PHP-зависимостей (dev)
FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --optimize-autoloader --no-scripts --no-dev

# ЭТАП 2: Финальный образ (dev)
FROM php:8.2-fpm

# Устанавливаем системные зависимости
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        # Зависимости для Valentina
        libqt5core5a \
        libqt5gui5 \
        libqt5widgets5 \
        libqt5xml5 \
        libqt5svg5 \
        libqt5printsupport5 \
        libqt5xmlpatterns5 \
        libqt5concurrent5 \
        # Утилиты и зависимости для PHP
        poppler-utils \
        pdfposter \
        git \
        unzip \
        curl \
        # Зависимости для расширений PHP
        libonig-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        # Зависимости для запуска GUI-приложений в "безголовом" режиме
        xvfb \
        xauth \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

ENV XDG_RUNTIME_DIR=/tmp/runtime-www-data
RUN mkdir -p /tmp/runtime-www-data && chmod 700 /tmp/runtime-www-data && chown www-data:www-data /tmp/runtime-www-data

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip exif pcntl

# Устанавливаем composer внутрь контейнера для удобства
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

ARG VAL_ARCH=arm64
ENV VAL_ARCH=${VAL_ARCH}

# Копируем valentina и .so из bin/
COPY bin/valentina-${VAL_ARCH} /usr/local/bin/valentina
COPY bin/libvpropertyexplorer-${VAL_ARCH}.so /usr/local/lib/libvpropertyexplorer.so
COPY bin/libqmuparser-${VAL_ARCH}.so /usr/local/lib/libqmuparser.so
RUN ldconfig && chmod +x /usr/local/bin/valentina

# Копируем зависимости Composer
COPY --from=composer /app/vendor ./vendor

# Копируем код приложения
COPY . .

# Автоматически применяем миграции и сидеры при сборке (dev)
RUN php artisan migrate --seed

USER www-data