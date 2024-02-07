# richarvey/nginx-php-fpmをベースとする
FROM richarvey/nginx-php-fpm
# FROM php:8.1-fpm

COPY . .

# Node.jsとNPMのインストール
RUN apk -y install nodejs

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_URL https://rabbit-himatubushi.render.com/
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
