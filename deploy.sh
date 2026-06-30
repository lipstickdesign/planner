#!/usr/bin/env bash
# Vivu Planner – deploy-script. Kjøres på Cloudways-serveren (SSH) etter git pull.
# Bruk: bash deploy.sh
set -e
cd "$(dirname "$0")"

echo "==> Composer"
composer install --no-dev --optimize-autoloader

echo "==> Migrasjoner"
php artisan migrate --force

echo "==> Cache (config/route/view)"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

echo "==> Ferdig. (Seeding kjøres KUN første gang – se DEPLOY.md trinn 3)"
