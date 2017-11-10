## Tests
php vendor/bin/phpunit

## Start
php artisan key:generate
php artisan migrate
php artisan create:user

## Work
docker run --name php-crm.isuzu.market \
    --link  work-mysql:db \
    -p 8087:8000  \
    -v '/Users/user/PhpstormProjects/crm.isuzu.market:/app' \
    -w '/app' \
    --rm -i -t my/php sh

## Run server
php -S 0.0.0.0:8000 -t public/

## Create database
CREATE DATABASE IF NOT EXISTS crm_isuzu_market;

# напоминание о звонке

# проверка звонков у цифровой телефонии

* * * * * php /php/crm.isuzu.market/artisan schedule:run >> /dev/null 2>&1

// api для определения номера телефона
tests/Unit/GetRegionApiTest.php