<?php
/**
 * Plugin Name:         TIVENTS Products Feed
 * description:         Crawl products form tivents
 * Version:             1.5.7
 *
 * Author:              tivents
 * Author URI:          https://tivents.info/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * License:             GPLv2 or later
 * Text Domain:         tivents_products_feed
 * Domain Path:         /languages
 */


/**
 * Add Views
 */
require_once 'views/class-base.php';
require_once 'views/class-calendar.php';
require_once 'views/class-lists.php';
require_once 'views/class-grid.php';
require_once 'views/class-sponsorships.php';

/**
 * Add controllers
 */
require_once 'controllers/class-products.php';
require_once 'controllers/class-settings.php';

wp_register_style('tiv-plugin-style', plugins_url('plugins/tivents/tiv-plugin.css', __FILE__));
wp_register_style('tiv-calender-style', plugins_url('plugins/tivents/tiv-calendar.css', __FILE__));
wp_register_script('tiv-calender-js', plugins_url('plugins/tivents/tiv-calendar.js', __FILE__) );

wp_enqueue_style( 'tiv-plugin-style');

define ( 'TIVENTPRO_CURRENT_VERSION', '1.5.7');

wp_register_style('fullcalendar_daygrid_style', plugins_url('plugins/fullcalendar/main.min.css', __FILE__));
wp_register_script('fullcalendar_core_script', plugins_url('plugins/fullcalendar/main.min.js', __FILE__));
wp_register_script('fullcalendar_locale_script', plugins_url('plugins/fullcalendar/locales-all.min.js', __FILE__));

wp_register_style('sweetalert_style', plugins_url('plugins/sweetalert/sweetalert2.min.css', __FILE__));
wp_register_script('sweetalert_script', plugins_url('plugins/sweetalert/sweetalert2.all.min.js', __FILE__));

add_action('admin_menu', 'tivents_products_feed_setup_menu');
add_action( 'admin_init', 'tivents_products_feed_register_settings' );

add_shortcode('tivents_products', 'tivents_products_feed_show');
add_shortcode('tivents_sponsorships', 'tivents_sponsorships_feed_show');

function tivents_products_feed_setup_menu(){

    add_menu_page( 'TIVENTS',
        'TIVENTS',
        'manage_options',
        'tivents_products_feed-settings',
        'tivents_products_feed_init',
        'dashicons-tickets-alt'
    );
    add_submenu_page( 'tivents_products_feed-settings',       // parent slug
        'Info/Nutzung',    // page title
        'Info/Nutzung',             // menu title
        'manage_options',           // capability
        'infos',      // slug
        'show_plugin_infos' // callback
    );

}

function tivents_products_feed_register_settings() {
    add_option( 'tivents_partner_id', null);
    add_option( 'tivents_primary_color', '#6eafdc');
    add_option( 'tivents_secondary_color', '#000000');
    add_option( 'tivents_text_color', null);
    add_option( 'tivents_base_url', null);
    add_option( 'tivents_per_page', null);
    add_option( 'tivents_bootstrap_version', '5.1.3');
    add_option( 'tivents_default_date', null);
    add_option( 'tivents_partner_api_key', null);

    register_setting( 'tivents_products_feed_options_group', 'tivents_partner_id', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_per_page', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_partner_api_key', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_default_date', 'tivents_products_feed_callback' );

    register_setting( 'tivents_products_feed_options_group', 'tivents_bootstrap_version', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_primary_color', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_secondary_color', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_text_color', 'tivents_products_feed_callback' );
}


function tivents_products_feed_init(){
    TivProFeed_Controller_Settings::set_general_settings();
}

function show_plugin_infos() {
    TivProFeed_Controller_Settings::show_plugin_infos();
}


function tivents_products_feed_show($atts)
{
    return TivProFeed_Controller_Products::createDiv($atts);
}


function tivents_sponsorships_feed_show($atts)
{
    return TivProFeed_View_Sponsorships::createSponsorshipView($atts);
}


function getApiUrl($atts) {

    extract(shortcode_atts(array(
        "type" => 'all',
        "style" => 'list',
        "qty" => "qty",
        'group' => 'group'
    ), $atts));


    $apiURL = 'https://products.tivents.net/public/v1';


    $urlSlug = '?_sortField=start&_sortDir=ASC';


    $filter = [
        'status' => 400
    ];

    if (get_option( 'tivents_partner_id' ) == null || get_option( 'tivents_partner_id' ) == 'all-area') {
        if ( $type == 'events' ) {
            $filter['product_type'] = 1;
        }
        if ( $type == 'coupons' ) {
            $filter['product_type'] = 2;
        }
    }

    elseif ( $style == 'calendar') {
        $filter['product_type'] = 1;
        $filter['hosts_globalid'] =  get_option( 'tivents_partner_id' );
    }
    else {
        $filter['hosts_globalid'] =  get_option( 'tivents_partner_id' );
        if ( $type == 'events' ) {
            $filter['product_type'] = 1;

        } else if ( $type == 'coupons' ) {
            $filter['product_type'] = 2;
        }
    }

    if ($group != 'group') {
        $filter['product_group_id'] = $group;
    }

    if (is_int($qty)) {
        $urlSlug .= '&_perPage='.$qty;
    }

    if ($qty == 'qty' && get_option( 'tivents_per_page' ) != null) {
        $urlSlug .= '&_perPage='.get_option( 'tivents_per_page' );
    }


    $urlSlug .= '&_filters='.json_encode($filter);
    $apiURL .= $urlSlug;

    return $apiURL;
}

function callApi($apiUrl) {

    return json_decode(wp_remote_retrieve_body(wp_remote_get( $apiUrl ,
            [
                'headers' => ['Content-Type' => 'application/json'],
                'timeout'     => 60,
            ]
        )
    ), true);

}

function getSponsorshipApiUrl() {
    return 'https://participants-api.intern.tivents.de/sponsorships/v1?source=wordpress';
}





