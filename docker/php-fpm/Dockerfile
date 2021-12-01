FROM php:7.4-fpm-alpine

RUN apk --update --no-cache add bash \
    && apk add wget \
    && apk add git \
    && apk add php7-phar \
    && apk add php7-openssl \
    && apk add php7-xml \
    && apk add php7-pdo \
    && apk add php7-pdo_mysql \
    && apk add php7-mbstring \
    && apk add php7-iconv \
    && apk add php7-ctype \
    && apk add php7-posix \
    && apk add php7-intl \
    && apk add php7-opcache  \
    && apk add $PHPIZE_DEPS \
    && pecl install apcu \
    && apk add icu-dev \
    && pecl install xdebug \
    && apk add alpine-conf

RUN setup-timezone -z Europe/Rome

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install intl \
    && docker-php-ext-configure intl \
    && docker-php-ext-enable intl \
    && docker-php-ext-install opcache \
    && docker-php-ext-enable apcu

RUN mkdir -p /var/app
RUN adduser --disabled-password app && chown -R app:app /var/app

# Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Symfony installer
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY php-overrides.ini /usr/local/etc/php/conf.d/
COPY timezone.ini /usr/local/etc/php/conf.d/
COPY opcache.ini /usr/local/etc/php/conf.d/
COPY xdebug.ini /usr/local/etc/php/conf.d/

ARG XDEBUG_ENABLED
RUN if [ "${XDEBUG_ENABLED}" = "false" ]; then mv /usr/local/etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.off ; fi

USER app
WORKDIR /var/app

EXPOSE 9000

RUN echo "alias ll='ls -l'" >> ~/.bashrc
RUN echo "alias la='ls -la'" >> ~/.bashrc
