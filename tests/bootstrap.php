<?php
define('WP_TESTS_DIR', '/tmp/wordpress-tests-lib');
define('WP_PLUGIN_DIR', '/tmp/wordpress/wp-content/plugins');
require_once WP_TESTS_DIR . '/includes/functions.php';
tests_add_filter('muplugins_loaded', function() {
    require dirname(__FILE__) . '/../tivents-wordpress-plugin.php';
});
require WP_TESTS_DIR . '/includes/bootstrap.php';
