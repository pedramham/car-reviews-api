FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    acl \
    fcgi \
    file \
    gettext \
    git \
    ;

RUN set -eux; \
    install-php-extensions \
        apcu \
        intl \
        opcache \
        zip \
        ;

RUN composer install --optimize-autoloader

COPY . /var/www/html

WORKDIR /var/www/html

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80"]
