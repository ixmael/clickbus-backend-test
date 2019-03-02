#!/usr/bin/env sh

apk update && apk upgrade

apk add --no-cache \
  php7 \
  php7-cli \
  php7-fpm \
  php7-common \
  php7-openssl \
  php7-pdo_mysql \
  php7-json \
  php7-phar \
  php7-iconv \
  php7-mbstring \
  php7-xml \
  php7-xmlrpc \
  php7-tokenizer \
  php7-ctype \
  php7-curl \
  php7-zip \
  php7-simplexml \
  php7-dom \
  php7-session
