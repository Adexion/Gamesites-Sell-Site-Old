git pull
composer dump-autoload --no-dev --classmap-authoritative
php bin/console doctrine:schema:update --force
composer install
composer dump-autoload --no-dev --classmap-authoritative
php bin/console doctrine:schema:update --force
composer install
yarn build
