#!/usr/bin/env bash

composer install -n
bin/console doctrine:migrations:migrate --no-interaction
bin/console doctrine:fixtures:load --no-interaction
bin/console lexik:jwt:generate-keypair --skip-if-exists

exec "$@"