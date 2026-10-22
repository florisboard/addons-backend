FROM serversideup/php:8.4.27-frankenphp-v4.5.3

USER root

RUN install-php-extensions exif intl zip pcntl bcmath gd sockets

USER www-data


