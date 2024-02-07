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

CMD ["./scripts/start.sh"]

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
