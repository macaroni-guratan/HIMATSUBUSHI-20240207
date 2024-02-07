#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
# composer install --no-dev --working-dir=/var/www/html
composer install

echo "copy .env..."
cp /etc/secrets/.env ./

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "clean database.sqlite file"
rm ./database/database.sqlite
echo "create database.sqlite file"
touch ./database/database.sqlite;
echo "change op permission database.sqlite file"
chmod 777 ./database/database.sqlite
echo "Running migrations..."
php artisan migrate --seed --force;

php artisan passport:client --password --name=password --provider=users;
php artisan passport:client --personal --name=sorcial;
php artisan passport:install;

# php artisan key:generate
php artisan optimize;
php artisan route:list;

yarn run dev;
# php artisan serve --host=0.0.0.0