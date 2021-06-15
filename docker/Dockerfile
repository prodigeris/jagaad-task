FROM php:7.4-cli

RUN apt-get clean
RUN apt-get update

RUN apt-get update \
     && apt-get install -y libzip-dev \
     && docker-php-ext-install zip

WORKDIR /usr/src/app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY ./docker/docker-entrypoint.sh /

CMD ["/docker-entrypoint.sh"]
