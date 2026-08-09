# =========================
# 1. Build frontend Vite
# =========================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build


# =========================
# 2. Laravel + PHP + Nginx
# =========================
FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader

# Ambil hasil build Vite dari stage frontend
COPY --from=frontend /app/public/build /app/public/build

ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080