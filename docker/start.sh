#!/bin/bash
set -e

echo "=== PythonJr — Iniciando ==="

# ── Garantizar directorios de storage en runtime ───────────────────────────
mkdir -p storage/framework/{sessions,views,cache,testing}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── Cachear configuración con los env vars de Railway (runtime) ────────────
echo "Cacheando configuración..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache   || echo "⚠️  view:cache falló (no crítico)"
php artisan event:cache  || echo "⚠️  event:cache falló (no crítico)"

# ── Migraciones (seguro de correr múltiples veces) ─────────────────────────
echo "Corriendo migraciones..."
php artisan migrate --force

# ── Seed solo si la BD está vacía (primera vez) ────────────────────────────
MODULO_COUNT=$(php artisan tinker --execute="echo App\Models\Modulo::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')

if [ -z "$MODULO_COUNT" ] || [ "$MODULO_COUNT" = "0" ]; then
    echo "Base de datos vacía. Corriendo seeders..."
    php artisan db:seed --force
    echo "Seeders completados!"
else
    echo "Base de datos lista ($MODULO_COUNT módulos encontrados). Saltando seed."
fi

# ── Backfill: vincular estudiantes huérfanos al primer admin de familia ────
php artisan tinker --execute="
\$admin = App\Models\User::where('role', 'admin')->first();
if (\$admin) {
    \$n = App\Models\User::where('role', 'estudiante')->whereNull('parent_id')->update(['parent_id' => \$admin->id]);
    if (\$n > 0) { echo \"Backfill: \$n estudiante(s) vinculados al admin.\"; }
}
" 2>/dev/null || true

# ── Iniciar FrankenPHP ─────────────────────────────────────────────────────
echo "Iniciando servidor en puerto ${PORT:-8080}..."
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
