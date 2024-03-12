sudo apt install php -y
sudo apt install php-curl php-zip php-dom php-gd php-mysql php-xdebug -y
rm composer.lock
composer install --no-dev --optimize-autoloader
php artisan key:generate