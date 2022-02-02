FROM php:7.4-fpm-alpine3.11 as base

LABEL MAINTAINER="Puncoz Nepal <info@puncoz.com>"

# Start as root
USER root

###########################################################################
# setup
###########################################################################
RUN apk add --no-cache \
    postgresql-dev libzip-dev zip unzip tzdata nginx freetype-dev libpng-dev libjpeg-turbo-dev

RUN apk add --update --no-cache --virtual .build-deps \
    pcre-dev ${PHPIZE_DEPS}

COPY --from=ochinchina/supervisord:latest /usr/local/bin/supervisord /usr/local/bin

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure exif --enable-exif

RUN pecl install redis \
      && docker-php-ext-enable redis \
      && docker-php-ext-configure zip \
      && docker-php-ext-install zip pdo pdo_pgsql pcntl bcmath gd exif \
#      opcache \
      && apk del pcre-dev ${PHPIZE_DEPS} \
      && apk del .build-deps \
      && rm -rf /tmp/pear /etc/nginx/conf.d/default.conf /usr/local/etc/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

## NGINX conf
COPY ./.gitlab/deploy/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./.gitlab/deploy/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./.gitlab/deploy/php/laravel.ini /usr/local/etc/php/conf.d
#COPY ./.gitlab/deploy/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./.gitlab/deploy/php/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./.gitlab/deploy/php/www.conf /usr/local/etc/php-fpm.d/www.conf

###########################################################################
# Crontab
###########################################################################

# preserve new line crontrab needs line end
ARG DOCKER_USER=www-data

RUN echo "" >> /etc/crontabs/root && \
    echo '* * * * * /usr/local/bin/php /var/www/artisan schedule:run >> /dev/null 2>&1' >> /etc/crontabs/root

RUN chmod -R 644 /etc/crontabs


###########################################################################
# SUPERVISOR:
###########################################################################

COPY  ./.gitlab/deploy/supervisord.d/supervisord.conf /etc/supervisord.conf
COPY  ./.gitlab/deploy/supervisord.d/*.conf /etc/supervisor.d/
RUN mkdir /var/log/supervisor  && touch /var/log/supervisor/supervisord.log && chmod 777 -R /var/log/supervisor/

###########################################################################
# Non-root user:
###########################################################################

# Switch to non-root 'app' user & install app dependencies
WORKDIR /var/www/

RUN chown -R $DOCKER_USER:$DOCKER_USER /var/www/
COPY ./.gitlab/deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

USER $DOCKER_USER

COPY ./composer.json /var/www/
COPY ./composer.lock /var/www/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --quiet --prefer-dist --no-scripts --optimize-autoloader

COPY ./ /var/www/

USER root

ARG APP_NAME=${APP_NAME}
ARG APP_ENV=${APP_ENV}
ARG APP_KEY=${APP_KEY}
ARG APP_DEBUG=${APP_DEBUG}
ARG DEBUGBAR_ENABLED=${DEBUGBAR_ENABLED}

ARG EDITABLE_DAYS=${EDITABLE_DAYS}
ARG DEADLINE=${DEADLINE}
ARG NUMBER_OF_ASSIGNMENTS=${NUMBER_OF_ASSIGNMENTS}

ARG APP_FRONT_DOMAIN=${APP_FRONT_DOMAIN}
ARG APP_ADMIN_DOMAIN=${APP_ADMIN_DOMAIN}
ARG APP_URL=${APP_URL}

ARG APP_TIMEZONE=${APP_TIMEZONE}
ARG APP_LOCALE=${APP_LOCALE}
ARG APP_LOCALE_FULL_CODE=${APP_LOCALE_FULL_CODE}

ARG LOG_HORIZON=${LOG_HORIZON}
ARG LOG_CHANNEL=${LOG_CHANNEL}
ARG LOG_SLACK_WEBHOOK_URL=${LOG_SLACK_WEBHOOK_URL}
ARG LOG_SLACK_CHANNEL=${LOG_SLACK_CHANNEL}
ARG LOG_LEVEL=${LOG_LEVEL}
ARG SENTRY_LARAVEL_DSN=${SENTRY_LARAVEL_DSN}
ARG SENTRY_TRACES_SAMPLE_RATE=${SENTRY_TRACES_SAMPLE_RATE}
ARG ARCANEDEV_LOGVIEWER_MIDDLEWARE=${SENTRY_TRACES_SAMPLE_RATE}

ARG DB_CONNECTION=${DB_CONNECTION}
ARG DB_HOST=${DB_HOST}
ARG DB_PORT=${DB_PORT}
ARG DB_DATABASE=${DB_DATABASE}
ARG DB_USERNAME=${DB_USERNAME}
ARG DB_PASSWORD=${DB_PASSWORD}

ARG BROADCAST_DRIVER=${BROADCAST_DRIVER}
ARG CACHE_DRIVER=${CACHE_DRIVER}
ARG QUEUE_CONNECTION=${QUEUE_CONNECTION}
ARG SESSION_DRIVER=${SESSION_DRIVER}
ARG SESSION_LIFETIME=${SESSION_LIFETIME}
ARG SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE}

ARG MEMCACHED_HOST=${MEMCACHED_HOST}

ARG REDIS_HOST=${REDIS_HOST}
ARG REDIS_PASSWORD=${REDIS_PASSWORD}
ARG REDIS_PORT=${REDIS_PORT}

ARG MAIL_MAILER=${MAIL_MAILER}
ARG MAIL_HOST=${MAIL_HOST}
ARG MAIL_PORT=${MAIL_PORT}
ARG MAIL_USERNAME=${MAIL_USERNAME}
ARG MAIL_PASSWORD=${MAIL_PASSWORD}
ARG MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
ARG MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
ARG MAIL_FROM_NAME=${MAIL_FROM_NAME}

ARG AWS_ENDPOINT=${AWS_ENDPOINT}
ARG AWS_URL=${AWS_URL}
ARG AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
ARG AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
ARG AWS_DEFAULT_REGION=${AWS_DEFAULT_REGION}
ARG AWS_BUCKET=${AWS_BUCKET}
ARG AWS_GLIDE_BUCKET=${AWS_GLIDE_BUCKET}
ARG AWS_EXPORT_CACHE_BUCKET=${AWS_EXPORT_CACHE_BUCKET}

#RUN php artisan ziggy:generate resources/assets/shared/js/routes.generated.js --url=https://$APP_ADMIN_DOMAIN

#FROM node:12-alpine AS build-stage
#WORKDIR /app
#COPY --from=base /var/www /app
#RUN yarn && yarn prod && rm -rf node_modules

#FROM base as base2
#COPY --from=build-stage /app /var/www
ARG DOCKER_USER=www-data
RUN chown -R $DOCKER_USER:$DOCKER_USER /var/www/

USER root

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
