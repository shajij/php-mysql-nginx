FROM php:7.1-fpm-alpine
MAINTAINER Manuel Manhart
VOLUME /code
#WORKDIR /code
RUN   apk update \                                                                                                                                                                                                                        
  &&   apk add ca-certificates wget \                                                                                                                                                                                                      
  &&   update-ca-certificates
RUN docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql
