FROM php:8.1-fpm

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
RUN composer install

# Node.jsとNPMのインストール
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get -y install nodejs

# Vue.jsの依存関係をインストール
COPY package*.json ./
RUN npm install

#ENV
RUN echo "copy .env..."
RUN cp /etc/secrets/.env ./
RUN echo "Caching config..."
RUN php artisan config:cache
RUN echo "Caching routes..."
RUN php artisan route:cache

#DB
RUN echo "clean database.sqlite file"
RUN rm ./database/database.sqlite
RUN echo "create database.sqlite file"
RUN touch ./database/database.sqlite;
RUN echo "change op permission database.sqlite file"
RUN chmod 777 ./database/database.sqlite
RUN echo "Running migrations..."
RUN php artisan migrate --seed --force;

RUN php artisan passport:client --password --name=password --provider=users;
RUN php artisan passport:client --personal --name=sorcial;
RUN php artisan passport:install;
RUN php artisan optimize;
RUN php artisan route:list;

RUN npm run dev
CMD php artisan serve --host=0.0.0.0 --port=8080

EXPOSE 8080




# # richarvey/nginx-php-fpmをベースとする
# FROM richarvey/nginx-php-fpm
# # FROM php:8.1-fpm

# COPY . .


# # Node.jsとNPMのインストール
# RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
# RUN apk -y install nodejs

# # Image config
# ENV SKIP_COMPOSER 1
# ENV WEBROOT /var/www/html
# ENV PHP_ERRORS_STDERR 1
# ENV RUN_SCRIPTS 1
# ENV REAL_IP_HEADER 1

# # Laravel config
# ENV APP_ENV production
# ENV APP_URL https://rabbit-himatubushi.render.com/
# ENV APP_DEBUG false
# ENV LOG_CHANNEL stderr

# # Allow composer to run as root
# ENV COMPOSER_ALLOW_SUPERUSER 1

# CMD ["/start.sh"]
