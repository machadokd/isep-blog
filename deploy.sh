#!/usr/bin/env bash
# Deploy script para o Raspberry Pi de produção.
# Corre a partir da raiz do projeto: ./deploy.sh
set -euo pipefail

APP_DIR="/var/www/blog-isep"
WORKER_SERVICE="blog-isep-worker"

cd "$APP_DIR"

echo "==> A atualizar o código..."
sudo -u www-data git pull

echo "==> A instalar dependências..."
composer install --no-dev --optimize-autoloader
npm install
npm run build

echo "==> A correr migrações..."
php artisan migrate --force

echo "==> A limpar e recriar caches..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> A repor permissões..."
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R ug+rwx "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo "==> A reiniciar serviços..."
sudo systemctl restart "$WORKER_SERVICE"
sudo systemctl reload apache2

echo "==> Deploy concluído."
