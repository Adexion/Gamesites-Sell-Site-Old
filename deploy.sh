git pull
bin/console cache:clear
php bin/console doctrine:schema:update --force
composer install
composer dump-autoload --no-dev --classmap-authoritative
yarn build