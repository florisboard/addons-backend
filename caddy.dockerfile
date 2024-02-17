FROM caddy:2.7.5-alpine

COPY ./ /usr/src

CMD ["/bin/sh", "-c", "ln -s /usr/src/storage/app/public /usr/src/public/storage"]

COPY ./deployment/config/Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
EXPOSE 443
