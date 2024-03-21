FROM dunglas/frankenphp

ARG user
ARG uid
ARG TZ
ARG serverName

ENV user $user
ENV uid $uid
ENV TZ $TZ
ENV SERVER_NAME $serverName

ENV DEBIAN_FRONTEND noninteractive

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

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
