## Tests
php vendor/bin/phpunit

## Start
php artisan key:generate
php artisan migrate
php artisan create:user
php artisan db:seed --class=VariantsTableSeeder
php artisan db:seed --class=AddPriceToVariantsTableSeeder