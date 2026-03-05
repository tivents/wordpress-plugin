<?php
/**
 * Bootstrap for integration tests – requires a full WordPress test environment.
 * Set up via bin/install-wp-tests.sh before running.
 */

define('WP_TESTS_DIR', getenv('WP_TESTS_DIR') ?: '/tmp/wordpress-tests-lib');

require_once WP_TESTS_DIR . '/includes/functions.php';

tests_add_filter('muplugins_loaded', function () {
    require dirname(__FILE__) . '/../src/tivents-product-feed.php';
});

require WP_TESTS_DIR . '/includes/bootstrap.php';
