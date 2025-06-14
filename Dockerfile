# ЭТАП 1: "Сборщик"
# Используем Ubuntu 20.04 LTS, как рекомендовано в официальной документации
FROM ubuntu:20.04 AS builder

# Устанавливаем все зависимости для сборки
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
    libqt5xmlpatterns5-dev \
    poppler-utils

# Клонируем репозиторий
RUN git clone https://gitlab.com/smart-pattern/valentina.git /src
WORKDIR /src

# Переключаемся на свежую версию 0.7.52
RUN git checkout v0.7.52

# Создаем папку для сборки и запускаем QMAKE
RUN mkdir build && cd build && qmake ../Valentina.pro -r "CONFIG+=noTests noRunPath no_ccache noDebugSymbols"

# Запускаем компиляцию
RUN cd build && make

# ---

# ЭТАП 2: "Чистовой образ"
FROM php:8.2-fpm

# Устанавливаем библиотеки для ЗАПУСКА
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
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Копируем наш свежескомпилированный движок под его ОРИГИНАЛЬНЫМ именем
COPY --from=builder /src/build/src/app/valentina/bin/valentina /usr/local/bin/valentina

# Копируем его внутренние библиотеки
COPY --from=builder /src/build/src/libs/qmuparser/bin/libqmuparser.so.* /usr/local/lib/
COPY --from=builder /src/build/src/libs/vpropertyexplorer/bin/libvpropertyexplorer.so.* /usr/local/lib/
RUN ldconfig

# Делаем движок исполняемым, используя его ОРИГИНАЛЬНОЕ имя
RUN chmod +x /usr/local/bin/valentina

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

WORKDIR /var/www
