FROM dunglas/frankenphp

ARG WWWGROUP


ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl zip unzip git supervisor libcap2-bin libpng-dev dnsutils librsvg2-bin fswatch \
    && apt-get update

RUN install-php-extensions gd imagick \
       curl \
       imap pdo_mysql mbstring \
       xml zip bcmath soap \
       intl readline \
       ldap \
       msgpack igbinary redis \
       memcached pcov xdebug \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get update
