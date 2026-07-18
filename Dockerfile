FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader

ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 8080