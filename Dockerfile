# Read-only demo of the materials ERP.
#
# Two stages: composer resolves the dependency tree with the full toolchain,
# then the runtime image gets the vendor directory and nothing else. No Node —
# the application's entire front end is Bootstrap, one small stylesheet and one
# small script, all committed, so there is no bundler in this build.

# --- dependencies ------------------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

# --- runtime -----------------------------------------------------------------
FROM php:8.4-fpm-alpine

# pdo_sqlite and mbstring are already compiled into the official image; nothing
# else in this application needs an extension.
RUN apk add --no-cache nginx bash su-exec \
    && docker-php-ext-enable opcache \
    && addgroup -g 1000 -S demo \
    && adduser -u 1000 -S -G demo -H -s /sbin/nologin demo \
    && rm -rf /etc/nginx/http.d /var/cache/apk/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-demo.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Set before the build steps below so that what is compiled into the image and
# what runs in the container agree.
ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_NAME="Materials ERP" \
    APP_LOCALE=bg \
    APP_FALLBACK_LOCALE=bg \
    APP_TIMEZONE=Europe/Sofia \
    LOG_CHANNEL=stderr \
    LOG_LEVEL=warning \
    SESSION_DRIVER=array \
    CACHE_STORE=array \
    QUEUE_CONNECTION=sync \
    MAIL_MAILER=log \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/srv/demo/demo.sqlite

WORKDIR /app

COPY --chown=root:root . /app
COPY --from=vendor --chown=root:root /app/vendor /app/vendor

# Routes and views are compiled in; config deliberately is not.
#
# `config:cache` freezes every env() call at build time, which would make every
# environment variable Coolify passes silently inert - including DB_DATABASE and
# DEMO_SEED_DATE. With opcache holding the config files anyway, caching them
# buys a millisecond and costs the ability to configure the container. Views are
# the ones that matter, and precompiling them is also what lets the whole of
# /app be read-only at runtime.
RUN rm -f /app/.env /app/database/*.sqlite \
    && rm -rf /app/docker /app/.git \
    && php artisan route:cache \
    && php artisan view:cache \
    && chmod -R a-w /app

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=4s --start-period=20s --retries=3 \
    CMD wget -qO- http://127.0.0.1:8080/up > /dev/null || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint"]
