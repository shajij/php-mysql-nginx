FROM php:7.1-fpm-alpine 
COPY cert/. /usr/local/share/ca-certificates/
RUN update-ca-certificates 
RUN apk update; \
    apk upgrade;
RUN docker-php-ext-install mysqli
