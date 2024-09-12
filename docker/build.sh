#!/usr/bin/env bash

composer install -n
bin/console doctrine:migrations:migrate --no-interaction

exec "$@"