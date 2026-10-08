#!/usr/bin/env bash
#
# Builds the cPanel-ready release archive for the Tricetech public website.
#
# Produces:  releases/tricetech-cpanel-<YYYYMMDD>.zip
# Layout:    tricetech_app/   Laravel application, production dependencies only
#            public_html/     web root: front controller, .htaccess, built assets
#            DEPLOY-CPANEL.md deployment guide
#
# Run from anywhere:  bash releases/cpanel/build-release.sh

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
SOURCE_DIR="$PROJECT_ROOT/releases/cpanel"
STAMP="$(date +%Y%m%d)"
RELEASE_NAME="tricetech-cpanel-$STAMP"
OUT_DIR="$PROJECT_ROOT/releases/$RELEASE_NAME"
APP_DIR="$OUT_DIR/tricetech_app"
PUBLIC_DIR="$OUT_DIR/public_html"
KEY_FILE="$SOURCE_DIR/app.key"
ZIP_PATH="$PROJECT_ROOT/releases/$RELEASE_NAME.zip"

echo "==> Project root: $PROJECT_ROOT"

# ---------------------------------------------------------------------------
# 1. Compiled frontend assets
# ---------------------------------------------------------------------------
if [ ! -d "$PROJECT_ROOT/node_modules" ]; then
    echo "==> Installing npm dependencies"
    (cd "$PROJECT_ROOT" && npm install)
fi

echo "==> Building frontend assets"
(cd "$PROJECT_ROOT" && npm run build)

# ---------------------------------------------------------------------------
# 2. A stable APP_KEY, so redeploying does not invalidate existing sessions
# ---------------------------------------------------------------------------
if [ ! -f "$KEY_FILE" ]; then
    mkdir -p "$SOURCE_DIR"
    php -r "echo 'base64:'.base64_encode(random_bytes(32));" > "$KEY_FILE"
    echo "==> Generated a new APP_KEY in $KEY_FILE"
fi
APP_KEY="$(cat "$KEY_FILE")"

# ---------------------------------------------------------------------------
# 3. Assemble the tree
# ---------------------------------------------------------------------------
echo "==> Assembling $RELEASE_NAME"
rm -rf "$OUT_DIR" "$ZIP_PATH"
mkdir -p "$APP_DIR" "$PUBLIC_DIR"

# Application files. public/ is deliberately excluded: its contents become
# public_html/, and the patched front controller points Laravel at it.
# .env.example is intentionally not shipped: it carries development values
# (local environment, debug on) that must never be copied into production.
# env.production.example replaces it in the release.
(cd "$PROJECT_ROOT" && cp -a \
    app bootstrap config database resources routes storage \
    artisan composer.json composer.lock \
    "$APP_DIR/")

# Web root files, including the patched front controller.
(cd "$PROJECT_ROOT" && cp -a public/. "$PUBLIC_DIR/")
cp "$SOURCE_DIR/index.php" "$PUBLIC_DIR/index.php"

# Production dependencies only, resolved from the committed lock file.
echo "==> Installing production dependencies into the release"
(cd "$PROJECT_ROOT" && cp -a vendor "$APP_DIR/vendor")
(cd "$APP_DIR" && composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --quiet)

# ---------------------------------------------------------------------------
# 4. Ship a ready-to-use .env
# ---------------------------------------------------------------------------
cat > "$APP_DIR/.env" <<'ENVEOF'
APP_NAME="Tricetech Solutions"
APP_ENV=production
APP_KEY=__APP_KEY__
APP_DEBUG=false

# The exact address opened in the browser. Change it if the site moves to
# another domain or a staging subdomain.
APP_URL=https://tricetech.ca
# Inquiry notifications print their received time in this timezone.
APP_TIMEZONE=America/Toronto

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

# debug keeps mail failures and logged submissions readable in
# tricetech_app/storage/logs/laravel.log. Switch to error to quieten it down.
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

DB_CONNECTION=sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=file

# Inquiry notifications are delivered through the cPanel server's own mail
# service, because the recipient mailboxes live on this server. Nothing leaves
# the host, so no SPF/DKIM setup is involved.
# To stop sending and only record submissions, set this to log - the message is
# then written to tricetech_app/storage/logs/laravel.log instead.
# The quotes matter: an unquoted space, as in the stock Laravel example, makes
# the whole .env unparsable and every page returns a blank 500.
MAIL_MAILER=sendmail
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -bs -i"
# This must be a real mailbox on the domain (cPanel > Email Accounts), otherwise
# the server bounces its own notifications.
MAIL_FROM_ADDRESS="website@tricetech.ca"
MAIL_FROM_NAME="${APP_NAME}"
# Everyone who should be notified about a website inquiry. Comma separated;
# every listed mailbox receives a copy and can reply straight to the visitor.
INQUIRY_RECIPIENT="info@tricetech.ca,junaid@tricetech.ca,coo@tricetech.ca"
ENVEOF

# The heredoc is quoted so ${APP_NAME} stays literal for Laravel to interpolate.
php -r '
    $path = $argv[1];
    $key = $argv[2];
    file_put_contents($path, str_replace("__APP_KEY__", $key, file_get_contents($path)));
' "$APP_DIR/.env" "$APP_KEY"

cp "$SOURCE_DIR/env.production.example" "$APP_DIR/env.production.example"
cp "$SOURCE_DIR/DEPLOY-CPANEL.md" "$OUT_DIR/DEPLOY-CPANEL.md"

# ---------------------------------------------------------------------------
# 5. Strip anything left over from local development
# ---------------------------------------------------------------------------
echo "==> Cleaning development artifacts"
rm -f "$APP_DIR/bootstrap/cache/packages.php" \
      "$APP_DIR/bootstrap/cache/services.php" \
      "$APP_DIR/bootstrap/cache/config.php" \
      "$APP_DIR/bootstrap/cache/events.php" \
      "$APP_DIR/bootstrap/cache/routes-v7.php"
rm -rf "$APP_DIR/storage/framework/views"/* \
       "$APP_DIR/storage/framework/sessions"/* \
       "$APP_DIR/storage/framework/cache/data"/* \
       "$APP_DIR/storage/framework/testing"/*
rm -f "$APP_DIR/storage/logs"/*.log
# Local visual-review exports and other storage/app scratch must never ship.
rm -rf "$APP_DIR/storage/app/design-preview" "$APP_DIR/storage/app/design-preview.php"

# Keep the placeholder files that guarantee these directories ship and stay present.
for dir in storage/framework/views storage/framework/sessions storage/framework/cache/data; do
    [ -f "$APP_DIR/$dir/.gitignore" ] || touch "$APP_DIR/$dir/.gitignore"
done

# ---------------------------------------------------------------------------
# 6. Verify the artifact, not the source tree
# ---------------------------------------------------------------------------
# The packaged application is booted with its own .env, vendor directory and
# views, exactly as the web server will boot it. This runs after the cleanup so
# the scratch check sees what would actually be zipped, and the boot's own
# leftovers are stripped immediately afterwards.
echo "==> Verifying the packaged application"
cp "$SOURCE_DIR/verify-package.php" "$PUBLIC_DIR/_verify.php"
php "$PUBLIC_DIR/_verify.php"
rm -f "$PUBLIC_DIR/_verify.php" "$APP_DIR/bootstrap/cache"/*.php \
      "$APP_DIR/storage/framework/views"/*.php "$APP_DIR/storage/logs"/*.log

# ---------------------------------------------------------------------------
# 7. Archive
# ---------------------------------------------------------------------------
echo "==> Creating $RELEASE_NAME.zip"
(cd "$OUT_DIR" && zip -r -q "$ZIP_PATH" tricetech_app public_html DEPLOY-CPANEL.md)

# Diagnostic helpers are packaged separately on purpose: they must never sit in
# the web root unnoticed. Upload one, use it, delete it. doctor.php explains a
# 500; mailcheck.php explains why an inquiry produced no email.
for helper in doctor mailcheck; do
    if [ -f "$SOURCE_DIR/$helper.php" ]; then
        rm -f "$SOURCE_DIR/$helper-for-cpanel.zip"
        (cd "$SOURCE_DIR" && zip -q "$helper-for-cpanel.zip" "$helper.php")
        echo "==> Helper: $SOURCE_DIR/$helper-for-cpanel.zip"
    fi
done

echo
echo "Done."
echo "  archive: $ZIP_PATH"
echo "  size:    $(du -h "$ZIP_PATH" | cut -f1)"
echo
echo "Upload it to your cPanel home directory, extract it there, then follow"
echo "the DEPLOY-CPANEL.md guide inside the archive."
