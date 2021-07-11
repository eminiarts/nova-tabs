FROM php:8.0-cli-alpine3.13

ARG UID=1000
ARG GID=1000

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY --from=node:14-alpine /usr/local/bin/node /usr/local/bin/node
COPY --from=node:14-alpine /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

RUN apk add --no-cache zip unzip g++ autoconf libstdc++ libpng-dev

RUN mkdir -p /home/app/nova-tabs && \
    addgroup -g $GID app && \
    adduser -D -h /home/app/nova-tabs -u $UID -G app app

USER app
WORKDIR /home/app/nova-tabs
