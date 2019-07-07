if [ ! -d "vendor" ]; then
    composer global require hirak/prestissimo
    export COMPOSER_ALLOW_SUPERUSER=1
    composer install
fi
php banner.php
php-fpm
