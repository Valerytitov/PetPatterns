# ЭТАП 1: "Сборщик"
# Используем стабильную Ubuntu 22.04 LTS
FROM ubuntu:22.04 AS builder

# Устанавливаем все зависимости для сборки qmake + make
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    cmake \
    pkg-config \
    qtbase5-dev \
    libqt5svg5-dev \
    qttools5-dev \
    qttools5-dev-tools \
    libqt5xmlpatterns5-dev

# Копируем локальные исходники v0.7.53
COPY valentina-src/ /src/
WORKDIR /src

# Создаем папку для сборки и запускаем QMAKE.
RUN mkdir build && cd build && qmake ../Valentina.pro -r "CONFIG+=noTests noRunPath no_ccache noDebugSymbols"

# Запускаем компиляцию.
RUN cd build && make -j$(nproc)

# ---

# ЭТАП 2: "Чистовой образ"
FROM php:8.2-fpm

# Устанавливаем системные зависимости, необходимые для ЗАПУСКА
RUN apt-get update && apt-get install -y --no-install-recommends \
    libqt5core5a \
    libqt5gui5 \
    libqt5widgets5 \
    libqt5xml5 \
    libqt5svg5 \
    libqt5printsupport5 \
    libqt5xmlpatterns5 \
    libqt5concurrent5 \
    poppler-utils \
    pdfposter \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    xvfb \
    xauth \
    && rm -rf /var/lib/apt/lists/*

# --- КОПИРУЕМ ФАЙЛЫ ПО ТОЧНЫМ ПУТЯМ, КОТОРЫЕ МЫ НАШЛИ ---
# Копируем главный исполняемый файл
COPY --from=builder /src/build/src/app/valentina/bin/valentina /usr/local/bin/valentina

# Копируем необходимые библиотеки
COPY --from=builder /src/build/src/libs/vpropertyexplorer/bin/libvpropertyexplorer.so* /usr/local/lib/
COPY --from=builder /src/build/src/libs/qmuparser/bin/libqmuparser.so* /usr/local/lib/
# -------------------------------------------------------------

# Обновляем кэш библиотек и делаем движок исполняемым
RUN ldconfig && chmod +x /usr/local/bin/valentina

# Устанавливаем XDG_RUNTIME_DIR для Qt
ENV XDG_RUNTIME_DIR=/tmp/runtime-www-data
RUN mkdir -p /tmp/runtime-www-data && chmod 700 /tmp/runtime-www-data && chown www-data:www-data /tmp/runtime-www-data

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

WORKDIR /var/www