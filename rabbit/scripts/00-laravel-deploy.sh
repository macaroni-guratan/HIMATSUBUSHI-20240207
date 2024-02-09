# echo "Running composer"
# composer global require hirak/prestissimo
# composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "ls..."
ls -al

# echo "yarn install..."
# # yarn のインストールを行う
# npm -g install yarn
# yarn install

# echo "build..."
# yarn build


# npm install
# echo "build..."
# npm run build
# cp -r  ./public/build /var/www/html/public/

#ENV
echo "copy .env..."
cp /etc/secrets/.env ./

echo "copy .conf..."
mkdir /etc/supervisor
mkdir /etc/supervisor/conf.d/
cp ./conf/supervisor/supervisor.conf /etc/supervisor/conf.d/
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
