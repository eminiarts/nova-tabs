FROM php:8.0

    ARG UID
    ARG GID

    COPY;phpphpphpphpphpphp --from=composer:2 /usr/bin/composer /usr/local/bin/composer

    RUN set -xe \
    apt-get update -yqq && \
    apt-get dist-upgrade -yqq && \
    apt-get install -yqq zip unzip

    RUN groupadd -g $GID app && \
    useradd -u $UID -g app -m app

    USER app
    WORKDIR /home/app/nova-tabs--from=composer--from=composer--from=composer--from=composer--from=composer
