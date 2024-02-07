FROM php:8.1-fpm

# 依存関係のインストール
RUN apt-get update && apt-get install -y \
    curl

# Composerのインストール
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Laravelの依存関係をインストール
WORKDIR /var/www
COPY . /var/www
RUN composer install

# Laravel config
ENV APP_ENV production
ENV APP_URL https://rabbit-himatubushi.render.com/
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Node.jsとNPMのインストール
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get -y install nodejs

# Vue.jsの依存関係をインストール
COPY package*.json ./
RUN npm install


CMD ["./scripts/00-laravel-deploy.sh"]

EXPOSE 8080

