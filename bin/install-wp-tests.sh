#!/usr/bin/env bash
# Sets up the WordPress test library used by phpunit.integration.xml.
#
# Usage: bash bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version]
#
# Example:
#   bash bin/install-wp-tests.sh wordpress_test root root 127.0.0.1 latest

set -e

DB_NAME="${1:-wordpress_test}"
DB_USER="${2:-root}"
DB_PASS="${3:-root}"
DB_HOST="${4:-127.0.0.1}"
WP_VERSION="${5:-latest}"

WP_TESTS_DIR="${WP_TESTS_DIR:-/tmp/wordpress-tests-lib}"
WP_CORE_DIR="${WP_CORE_DIR:-/tmp/wordpress}"

# ── Helper: resolve "latest" to an actual version tag ────────────────────────
resolve_wp_version() {
    if [ "$WP_VERSION" = "latest" ]; then
        WP_VERSION=$(curl -s "https://api.wordpress.org/core/version-check/1.7/" \
            | grep -oP '"version":"\K[^"]+' | head -1)
        echo "Resolved WordPress version: $WP_VERSION"
    fi
}

# ── Download WordPress ────────────────────────────────────────────────────────
install_wp() {
    if [ -d "$WP_CORE_DIR/wp-includes" ]; then
        echo "WordPress already installed at $WP_CORE_DIR"
        return
    fi

    mkdir -p "$WP_CORE_DIR"
    local archive="https://wordpress.org/wordpress-${WP_VERSION}.tar.gz"
    echo "Downloading WordPress $WP_VERSION …"
    curl -s "$archive" | tar -xz -C "$WP_CORE_DIR" --strip-components=1
}

# ── Download the WordPress test suite ────────────────────────────────────────
install_test_suite() {
    if [ -d "$WP_TESTS_DIR/includes" ]; then
        echo "WordPress test suite already installed at $WP_TESTS_DIR"
        return
    fi

    mkdir -p "$WP_TESTS_DIR"
    echo "Checking out WordPress test suite …"
    svn co --quiet \
        "https://develop.svn.wordpress.org/tags/${WP_VERSION}/tests/phpunit/includes/" \
        "$WP_TESTS_DIR/includes"
    svn co --quiet \
        "https://develop.svn.wordpress.org/tags/${WP_VERSION}/tests/phpunit/data/" \
        "$WP_TESTS_DIR/data"

    # Create wp-tests-config.php
    cat > "$WP_TESTS_DIR/wp-tests-config.php" <<PHP
<?php
define( 'ABSPATH', '${WP_CORE_DIR}/' );
define( 'WP_DEFAULT_THEME', 'default' );

define( 'DB_HOST',     '${DB_HOST}' );
define( 'DB_NAME',     '${DB_NAME}' );
define( 'DB_USER',     '${DB_USER}' );
define( 'DB_PASSWORD', '${DB_PASS}' );
define( 'DB_CHARSET',  'utf8' );
define( 'DB_COLLATE',  '' );

\$table_prefix = 'wptests_';

define( 'WP_TESTS_DOMAIN',        'example.org' );
define( 'WP_TESTS_EMAIL',         'admin@example.org' );
define( 'WP_TESTS_TITLE',         'Test Blog' );
define( 'WP_PHP_BINARY',          'php' );
define( 'WPLANG',                 '' );
PHP
}

# ── Create the test database ──────────────────────────────────────────────────
create_db() {
    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" \
        -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;" 2>/dev/null \
        || echo "Could not create database (may already exist)"
}

# ── Main ─────────────────────────────────────────────────────────────────────
resolve_wp_version
install_wp
install_test_suite
create_db

echo "Done. Run integration tests with:"
echo "  ./vendor/bin/phpunit --configuration phpunit.integration.xml"
