FROM php:8.3-fpm as api

WORKDIR /usr/src

ARG user=api
ARG uid=1000

RUN apt update && apt install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    git \
    curl \
    zip \
    unzip \
    supervisor \
    default-mysql-client

# Media library packages for optimizing
RUN apt install -y \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    libavif-bin

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

RUN pecl install redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user

COPY . .

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /usr/src

USER $user

RUN composer install --optimize-autoloader --no-dev

RUN php artisan storage:link && \
    chmod -R 775 ./storage ./bootstrap/cache

COPY ./deployment/config/php-fpm/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./deployment/config/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

CMD ["/bin/sh", "-c", "php /usr/src/artisan event:cache && php /usr/src/artisan route:cache && php /usr/src/artisan config:cache && php /usr/src/artisan view:cache && php /usr/src/artisan icons:cache"]


FROM api AS worker
COPY ./deployment/config/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisor.conf
CMD ["/bin/sh", "-c", "supervisord -c /etc/supervisor/conf.d/supervisor.conf"]

FROM api AS scheduler
CMD ["/bin/sh", "-c", "nice -n 10 sleep 60 && php /usr/src/artisan schedule:run --verbose --no-interaction"]
