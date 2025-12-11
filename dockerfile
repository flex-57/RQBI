# Dockerfile (simple)
FROM php:8.2-cli
RUN apt-get update && apt-get install -y git unzip libpq-dev && docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
CMD ["symfony", "server:start", "--no-tls", "--port=8000", "--host=0.0.0.0"]
