FROM ubuntu:22.04 AS base

WORKDIR /var/www

# Install dependencies
env DEBIAN_FRONTEND=noninteractive
RUN apt-get update; apt-get install -y --no-install-recommends libpq-dev vim nginx php8.1-fpm php8.1-mbstring php8.1-xml php8.1-pgsql php8.1-curl php8.1-gd ca-certificates

# Copy project code and install project dependencies
COPY --chown=www-data . /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env_production /var/www/.env
COPY docker_run.sh /docker_run.sh

FROM node:18 AS assets

WORKDIR /var/www

COPY package.json package-lock.json ./
RUN npm install

COPY resources/ ./resources
COPY vite.config.js ./
COPY --from=base /var/www/vendor ./vendor

RUN npm run build

FROM base

# Start command
CMD sh /docker_run.sh
