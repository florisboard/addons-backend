FROM caddy:2.7.5-alpine

COPY ./ /usr/src

COPY ./deployment/config/Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
EXPOSE 443
