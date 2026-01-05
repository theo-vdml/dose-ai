#!/bin/bash
set -e

if [ "$IS_LARAVEL" = "true" ]; then
  if [ "$RAILPACK_SKIP_MIGRATIONS" != "true" ]; then
    echo "Running migrations and seeding database ..."
    php artisan migrate --force
  fi

  php artisan storage:link
  php artisan optimize:clear
  php artisan optimize

  echo "Starting Reverb server ..."
  php artisan reverb:start &

  echo "Starting Laravel queue worker ..."
  php artisan queue:work \
    --sleep=3 \
    --tries=3 \
    --timeout=60 &
fi

echo "Starting FrankenPHP server ..."
exec docker-php-entrypoint --config /Caddyfile --adapter caddyfile 2>&1
