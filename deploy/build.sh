#!/usr/bin/env bash
#
# rePaw City — production build & prepare script.
#
# Builds both React apps (client SPA + admin portal) and prepares the Laravel
# backend for deployment to two subdomains:
#   repawcity.com        -> apps/client/dist (client SPA)
#   admin.repawcity.com  -> apps/admin/dist  (admin portal)
#
# Usage:
#   ./deploy/build.sh [deploy-dir] [deploy-admin-dir]
#
#   deploy-dir        (optional) destination for the client SPA build.
#                     Defaults to ./apps/client/dist (stays in place).
#   deploy-admin-dir  (optional) destination for the admin portal build.
#                     Defaults to ./apps/admin/dist (stays in place).
#
# Afterwards:
#   - cd backend && php artisan migrate --force
#   - cd backend && php artisan storage:link
#   - Configure deploy/nginx.conf to point at the deploy dirs.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DEPLOY_DIR="${1:-$ROOT/apps/client/dist}"
DEPLOY_ADMIN_DIR="${2:-$ROOT/apps/admin/dist}"

echo "==> Building client SPA + admin portal"
(cd "$ROOT" && npm run build)

if [[ "$DEPLOY_DIR" != "$ROOT/apps/client/dist" ]]; then
  echo "==> Copying client SPA build to $DEPLOY_DIR"
  rm -rf "$DEPLOY_DIR"
  mkdir -p "$DEPLOY_DIR"
  cp -R "$ROOT/apps/client/dist/." "$DEPLOY_DIR/"
fi

if [[ "$DEPLOY_ADMIN_DIR" != "$ROOT/apps/admin/dist" ]]; then
  echo "==> Copying admin portal build to $DEPLOY_ADMIN_DIR"
  rm -rf "$DEPLOY_ADMIN_DIR"
  mkdir -p "$DEPLOY_ADMIN_DIR"
  cp -R "$ROOT/apps/admin/dist/." "$DEPLOY_ADMIN_DIR/"
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
