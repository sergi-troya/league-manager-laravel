#!/bin/sh
set -e

# Carpetas típicas de Laravel (no molesta si aún no es Laravel)
mkdir -p storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rw storage bootstrap/cache 2>/dev/null || true

exec "$@"
