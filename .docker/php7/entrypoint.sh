curl https://getcomposer.org/composer.phar --output /app/composer.phar
php composer.phar global require hirak/prestissimo
export COMPOSER_ALLOW_SUPERUSER=1
php composer.phar install
php banner.php
php-fpm
