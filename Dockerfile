FROM dunglas/frankenphp:php8.3-bookworm

# ── Sistema ────────────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
        git unzip zip libpq-dev curl \
    && docker-php-ext-install pdo pdo_pgsql opcache pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ── Node.js 22 ─────────────────────────────────────────────────────────────
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ── Composer ───────────────────────────────────────────────────────────────
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ── PHP deps (capa de caché) ───────────────────────────────────────────────
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# ── Node deps raíz (Laravel Vite assets) ──────────────────────────────────
COPY package.json ./
RUN npm install --include=dev

# ── Node deps React frontend ───────────────────────────────────────────────
COPY frontend/package.json frontend/package-lock.json ./frontend/
RUN npm --prefix frontend ci

# ── Copiar todo el source ──────────────────────────────────────────────────
COPY . .

# ── Build Laravel assets (CSS/JS para Blade, si aplica) ───────────────────
RUN npm run build

# ── Build React SPA ──────────────────────────────────────────────────────────
# index.html → public/spa/index.html  (Laravel catch-all lo sirve)
# assets/    → public/assets/         (Caddy los sirve como static files)
# favicon    → public/favicon.svg     (referencia absoluta /favicon.svg)
RUN npm --prefix frontend run build \
    && mkdir -p public/spa public/assets \
    && cp frontend/dist/index.html public/spa/index.html \
    && cp -r frontend/dist/assets/. public/assets/ \
    && (cp frontend/dist/favicon.svg public/favicon.svg 2>/dev/null || true)

# ── Permisos de storage ────────────────────────────────────────────────────
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# ── Caddyfile y start script ───────────────────────────────────────────────
COPY Caddyfile /etc/caddy/Caddyfile
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
