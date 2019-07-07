if [ ! -d "vendor" ]; then
    if [ ! -f "composer.phar" ]; then
        curl https://getcomposer.org/composer.phar --output /var/www/csp/composer.phar
        php composer.phar global require hirak/prestissimo
    fi
    export COMPOSER_ALLOW_SUPERUSER=1
    php composer.phar install
fi
php banner.php
php-fpm
