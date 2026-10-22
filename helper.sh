#!/usr/bin/env bash

docker build -t addons-backend-helper -f helper.dockerfile .

docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        addons-backend-helper \
        composer install \
    --no-dev \
    --no-interaction \
    --no-autoloader \
    --no-ansi \
    --no-scripts \
    --audit

