if git pull | grep -q 'Already up to date.'; then
  exit 1;
fi

composer dump-autoload --no-dev --classmap-authoritative
rm var/cache -R
php bin/console doctrine:schema:update --force
composer install
composer dump-autoload --no-dev --classmap-authoritative
php bin/console doctrine:schema:update --force
composer install
chmod 777 var -R
chmod 777 public/assets -R
yarn install
yarn build
