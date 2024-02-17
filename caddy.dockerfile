FROM caddy:2.7.5-alpine

COPY ./ /usr/src

CMD ln -s ./storage/app/public ./public/storage
COPY ./deployment/config/Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
EXPOSE 443
