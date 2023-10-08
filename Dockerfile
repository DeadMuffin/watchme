# Verwenden eines offiziellen Laravel-Dockerimages als Basis
FROM php:8.1-fpm

# Arbeitsverzeichnis im Container
WORKDIR /var/www/html

# Abhängigkeiten installieren
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Composer installieren
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Git Repo klonen mit dem Laravel-Projekt
RUN git clone https://github.com/DeadMuffin/watchme.git .

WORKDIR /var/www/html/watchme

# Composer Abhängigkeiten installieren
RUN php /usr/local/bin/composer install


# Install Node.js and npm (if needed)
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*
# Node.js Abhängigkeiten installieren (sofern vorhanden)
RUN npm install
RUN npm run build


RUN mv .env.example .env
RUN php artisan key:generate

CMD ["php","artisan","serve", "--host", "0.0.0.0"]