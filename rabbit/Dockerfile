# FROM php:8.1-fpm

# # Allow composer to run as root
# ENV COMPOSER_ALLOW_SUPERUSER 1

# # Composerのインストール
# COPY --from=composer /usr/bin/composer /usr/bin/composer

# # 依存関係のインストール
# RUN apt-get update && apt-get install -y \
#     curl \
#     zip \
#     unzip \
#     git \
#     libonig-dev \
#     libpq-dev \
#     && docker-php-ext-install pdo_pgsql mbstring

# RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
# RUN apt-get -y install nodejs

# # Laravelの依存関係をインストール
# WORKDIR /var/www
# COPY . /var/www
# RUN composer update
# RUN composer install --no-dev --working-dir=/var/www/

# # Node.jsとNPMのインストール


FROM richarvey/nginx-php-fpm

COPY . .

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apk add --update nodejs npm

CMD ["/start.sh"]


# EXPOSE 8080
# EXPOSE 5173
