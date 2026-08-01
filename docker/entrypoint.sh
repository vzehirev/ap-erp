#!/usr/bin/env bash
#
# Build the demo database, make it unwritable, drop privileges, then run nginx
# and php-fpm together.
#
# This runs as root for exactly as long as it takes to seed: the database file
# ends up owned by root and mode 0444, and everything that serves a request runs
# as the unprivileged `demo` account. That is the fourth layer of the read-only
# guarantee, underneath the request gate, the missing write routes and
# "PRAGMA query_only".

set -euo pipefail

DB_PATH="${DB_DATABASE:-/srv/demo/demo.sqlite}"
DB_DIR="$(dirname "$DB_PATH")"

# No key is baked into the image, and none needs to survive a restart: the only
# thing this application encrypts is a session cookie holding an opaque token
# that is never read back. A fresh key per container is the right answer, and it
# means the image carries no secret at all.
if [ -z "${APP_KEY:-}" ]; then
    APP_KEY="base64:$(head -c 32 /dev/urandom | base64 | tr -d '\n')"
    export APP_KEY
fi

if [ "$(id -u)" = '0' ]; then
    mkdir -p "$DB_DIR"

    rm -f "$DB_PATH" "$DB_PATH-wal" "$DB_PATH-shm" "$DB_PATH-journal"
    install -o demo -g demo -m 0644 /dev/null "$DB_PATH"

    # Seeding is the one thing that writes, and it runs from the console, so
    # AppServiceProvider leaves the connection writable for it. Dates are laid
    # out backwards from today, which is why this happens at start-up rather
    # than at build time.
    su-exec demo php /app/artisan migrate --force --no-interaction
    su-exec demo php /app/artisan db:seed --force --no-interaction

    # No WAL: a database in WAL mode cannot be opened without write access to
    # its directory, which would defeat the point of the next two lines.
    su-exec demo php -r '
        $pdo = new PDO("sqlite:" . $argv[1]);
        $pdo->exec("PRAGMA wal_checkpoint(TRUNCATE)");
        $pdo->exec("PRAGMA journal_mode = DELETE");
        $pdo->exec("VACUUM");
    ' "$DB_PATH"

    rm -f "$DB_PATH-wal" "$DB_PATH-shm" "$DB_PATH-journal"

    chown root:root "$DB_PATH"
    chmod 0444 "$DB_PATH"
    chmod 0555 "$DB_DIR"

    mkdir -p /tmp/nginx-client /tmp/nginx-proxy /tmp/nginx-fastcgi /tmp/nginx-uwsgi /tmp/nginx-scgi
    chown -R demo:demo /tmp/nginx-* 2>/dev/null || true

    echo "demo database built: $(su-exec demo stat -c '%s bytes, mode %a, owner %U' "$DB_PATH")"

    exec su-exec demo "$0" "$@"
fi

# --- unprivileged from here ---

php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.conf &
FPM_PID=$!

nginx -c /etc/nginx/nginx.conf -g 'daemon off;' &
NGINX_PID=$!

terminate() {
    kill -TERM "$FPM_PID" "$NGINX_PID" 2>/dev/null || true
    wait
}
trap terminate TERM INT

# If either half dies the container dies with it, so Docker restarts a whole
# healthy pair rather than leaving nginx serving 502s.
wait -n
STATUS=$?
terminate
exit "$STATUS"
