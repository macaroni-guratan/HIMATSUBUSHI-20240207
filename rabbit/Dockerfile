FROM php:8.1-fpm

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# 依存関係のインストール
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring

# Composerのインストール
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Laravelの依存関係をインストール
WORKDIR /var/www
COPY . /var/www
RUN composer update
RUN composer install --no-dev --working-dir=/var/www/

# Node.jsとNPMのインストール
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get -y install nodejs

# Vue.jsの依存関係をインストール
COPY package*.json ./
RUN npm install
RUN npm run build

#ENV
# RUN echo "copy .env..."
# RUN cp /etc/secrets/.env ./

RUN echo "Caching config..."
RUN php artisan config:cache
RUN echo "Caching routes..."
RUN php artisan route:cache

RUN php artisan passport:client --password --name=password --provider=users;
RUN php artisan passport:client --personal --name=sorcial;
RUN php artisan passport:install;
RUN php artisan optimize;

CMD ["./scripts/start.sh"]

EXPOSE 8080
