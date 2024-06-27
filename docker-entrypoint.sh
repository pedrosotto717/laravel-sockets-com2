#!/bin/bash

# Set ServerName to suppress Apache warning
echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 1
done

composer install

# Install NPM dependencies and build the project
npm install
npm run build

# Run Laravel migrations
php artisan migrate

php artisan storage:link

# Start Supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
