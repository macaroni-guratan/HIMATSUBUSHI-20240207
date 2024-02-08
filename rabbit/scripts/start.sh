#ENV
echo "copy .env..."
cp /etc/secrets/.env ./
#DB
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
php artisan optimize;

npm run dev & artisan serve --host=0.0.0.0 --port=8080;
