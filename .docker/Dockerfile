FROM php:8.2-cli-alpine AS final

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions ctype curl intl json mbstring xml xdebug

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# keep container up
CMD ["php", "-S", "0.0.0.0:80", "-t", "./"]
