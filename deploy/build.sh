#!/usr/bin/env bash
#
# rePaw City — production build & prepare script.
#
# Builds both React apps (client SPA + admin portal) and prepares the Laravel
# backend for deployment to two subdomains:
#   repawcity.com        -> frontend/dist       (client SPA)
#   admin.repawcity.com  -> frontend/dist-admin (admin portal)
#
# Usage:
#   ./deploy/build.sh [deploy-dir] [deploy-admin-dir]
#
#   deploy-dir        (optional) destination for the client SPA build.
#                     Defaults to ./frontend/dist (stays in place).
#   deploy-admin-dir  (optional) destination for the admin portal build.
#                     Defaults to ./frontend/dist-admin (stays in place).
#
# Afterwards:
#   - cd backend && php artisan migrate --force
#   - cd backend && php artisan storage:link
#   - Configure deploy/nginx.conf to point at the deploy dirs.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DEPLOY_DIR="${1:-$ROOT/frontend/dist}"
DEPLOY_ADMIN_DIR="${2:-$ROOT/frontend/dist-admin}"

echo "==> Building frontend (client SPA + admin portal)"
(cd "$ROOT/frontend" && npm run build)

if [[ "$DEPLOY_DIR" != "$ROOT/frontend/dist" ]]; then
  echo "==> Copying client SPA build to $DEPLOY_DIR"
  rm -rf "$DEPLOY_DIR"
  mkdir -p "$DEPLOY_DIR"
  cp -R "$ROOT/frontend/dist/." "$DEPLOY_DIR/"
fi

if [[ "$DEPLOY_ADMIN_DIR" != "$ROOT/frontend/dist-admin" ]]; then
  echo "==> Copying admin portal build to $DEPLOY_ADMIN_DIR"
  rm -rf "$DEPLOY_ADMIN_DIR"
  mkdir -p "$DEPLOY_ADMIN_DIR"
  cp -R "$ROOT/frontend/dist-admin/." "$DEPLOY_ADMIN_DIR/"
fi

echo "==> Backend: composer install (production)"
(cd "$ROOT/backend" && composer install --no-dev --optimize-autoloader --no-interaction)

echo "==> Backend: cache config/routes for production"
(cd "$ROOT/backend" && php artisan config:cache && php artisan route:cache)

echo
echo "Done. Next steps on the server:"
echo "  1. cd $ROOT/backend && php artisan migrate --force"
echo "  2. cd $ROOT/backend && php artisan storage:link"
echo "  3. Configure nginx with deploy/nginx.conf"
echo "       repawcity.com       root -> $DEPLOY_DIR"
echo "       admin.repawcity.com root -> $DEPLOY_ADMIN_DIR"