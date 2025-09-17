<?php
/**
 * Plugin Name:         TIVENTS Products Feed
 * description:         Crawl products form tivents
 * Version:             1.6.6
 *
 * Author:              tivents
 * Author URI:          https://tivents.info/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * License:             GPLv2 or later
 * Text Domain:         tivents_products_feed
 * Domain Path:         /languages
 */


/*** Add Views */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once 'views/class-tivents-base-view.php';
require_once 'views/class-tivents-calendar-view.php';
require_once 'views/class-tivents-list-view.php';
require_once 'views/class-tivents-grid-view.php';
require_once 'views/class-tivents-sponsorship-view.php';

/*** Add controllers */
require_once 'controllers/class-tivents-product-controller.php';
require_once 'controllers/class-tivents-settings-controller.php';
require_once 'controllers/class-tivents-registration-controller.php';

define( 'TIVENTPRO_CURRENT_VERSION', '1.6.6' );


function register_styles() {
    if(!wp_style_is('tiv-plugin-style', 'enqueued' )) {
        wp_register_style( 'tiv-plugin-style', plugins_url( 'plugins/tivents/tiv-plugin.css', __FILE__ ) );
        wp_enqueue_style('tiv-plugin-style');
    }
}

function register_fullcalendar()
{
    if(!wp_style_is('fullcalendar_style', 'enqueued' )) {
        wp_register_style( 'fullcalendar_style', plugins_url( 'plugins/fullcalendar/main.min.css', __FILE__ ) );
        wp_enqueue_style( 'fullcalendar_style' );
    }
    if(!wp_script_is( 'fullcalendar_core_script', 'enqueued' )) {
        wp_register_script( 'fullcalendar_core_script', plugins_url( 'plugins/fullcalendar/main.min.js', __FILE__ ) );
        wp_enqueue_script('fullcalendar_core_script');
    }

    if(!wp_script_is( 'fullcalendar_locale_script', 'enqueued' )) {
        wp_register_script( 'fullcalendar_locale_script', plugins_url( 'plugins/fullcalendar/locales-all.min.js', __FILE__ ) );
        wp_enqueue_script('fullcalendar_locale_script');
    }

    if(!wp_style_is('tiv-calender-style', 'enqueued' )) {
        wp_register_style( 'tiv-calender-style', plugins_url( 'plugins/tivents/tiv-calendar.css', __FILE__ ) );
        wp_enqueue_style('tiv-calender-style');
    }

    if(!wp_script_is('tiv-calender-style', 'enqueued' )) {
        wp_register_script( 'tiv-calendar-js', plugins_url( 'plugins/tivents/tiv-calendar.js', __FILE__ ) );
    }
}

function register_sweetalert()
{
    if(!wp_style_is('sweetalert_style', 'enqueued' )) {
        wp_register_style( 'sweetalert_style', plugins_url( 'plugins/sweetalert/sweetalert2.min.css', __FILE__ ) );
        wp_enqueue_style('sweetalert_style');
    }

    if(!wp_script_is( 'sweetalert_script', 'enqueued' )) {
        wp_register_script( 'sweetalert_script', plugins_url( 'plugins/sweetalert/sweetalert2.all.min.js', __FILE__ ) );
        wp_enqueue_script( 'sweetalert_script' );
    }
}

add_action( 'wp_enqueue_scripts', 'register_styles' );
add_action( 'wp_enqueue_scripts', 'register_sweetalert' );
add_action( 'wp_enqueue_scripts', 'register_fullcalendar' );

add_action('rest_api_init', 'register_custom_calendar_api');

add_action( 'admin_menu', 'tivents_products_feed_setup_menu' );
add_action( 'admin_init', 'tivents_products_feed_register_settings' );

add_shortcode( 'tivents_products', 'tivents_products_feed_show' );
add_shortcode( 'tivents_sponsorships', 'tivents_sponsorships_feed_show' );

function tivents_products_feed_setup_menu() {
    add_menu_page(
        'TIVENTS',
        'Einstellungen',
        'manage_options',
        'tivents_products_feed-settings',
        'tivents_products_feed_init',
        'dashicons-tickets-alt'
    );
    add_submenu_page(
        'tivents_products_feed-settings',
        'Info/Nutzung',
        'Info/Nutzung',
        'manage_options',
        'infos',
        'tivents_show_plugin_infos'
    );
    add_submenu_page(
        'tivents_products_feed_calendar',
        'Kalendareinstellungen',
        'Kalendareinstellungen',
        'manage_calendar',
        'calendar',
        'show_calendar_settings'
    );
}

function tivents_products_feed_register_settings() {
    add_option( 'tivents_partner_id', null );
    add_option( 'tivents_primary_color', '#289BEC' );
    add_option( 'tivents_secondary_color', '#000000' );
    add_option( 'tivents_text_color', null );
    add_option( 'tivents_base_url', null );
    add_option( 'tivents_per_page', null );
    add_option( 'tivents_bootstrap_version', '5.1.3' );
    add_option( 'tivents_default_date', null );
    add_option( 'tivents_partner_api_key', null );

    register_setting( 'tivents_products_feed_options_group', 'tivents_partner_id', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_per_page', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_partner_api_key', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_default_date', 'tivents_products_feed_callback' );

    register_setting( 'tivents_products_feed_options_group', 'tivents_bootstrap_version', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_primary_color', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_secondary_color', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_text_color', 'tivents_products_feed_callback' );
}

function create_block_copyright_date_block_init() {
    register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_copyright_date_block_init' );


function tivents_products_feed_init() {
    Tivents_Settings_Controller::tivents_set_general_settings();
}

function tivents_show_plugin_infos() {
    Tivents_Settings_Controller::tivents_show_plugin_infos();
}

function tivents_products_feed_show( $atts ) {
    return Tivents_Product_Controller::tivents_create_div( $atts );
}

function tivents_sponsorships_feed_show( $atts ) {
    return Tivents_Sponsorship_View::tivents_create_sponsorship_view( $atts );
}

/*** Build the TIVENTS API  in order to get the products of the vendor
 *
 * @param $atts
 * @return string */
function tivents_get_api_url( $attributs  ) {

    extract(shortcode_atts(
            ['type'  => 'all',
                'style' => 'list',
                'qty'   => 'qty',
                'group' => 'group',
                'start',
                'end',
            ],
            $attributs
        )
    );

    $apiURL = 'https://products.tivents.net/public/v1';
    $urlSlug = '?_sortField=start&_sortDir=ASC';

    $filter = ['status' => 400];

    if ( get_option( 'tivents_partner_id' ) == null || get_option( 'tivents_partner_id' ) == 'all-area' ) {
        $filter['product_type'] = match($type) {
            'events' => 1,
            'coupons' => 2,
            'certificates' => 6,
        };
    } elseif ( $style == 'calendar' ) {
        $filter['product_type']   = 1;
        $filter['is_group_product'] = 0;
        $filter['hosts_globalid'] = get_option( 'tivents_partner_id' );
    } else {
        $filter['hosts_globalid'] = get_option( 'tivents_partner_id' );
        if ( $type == 'events' ) {
            $filter['product_type'] = 1;

        } elseif ( $type == 'coupons' ) {
            $filter['product_type'] = 2;
        }
    }

    if ( $group != 'group' ) {
        $filter['product_group_id'] = $group;
    }

    if (isset($attributs['start']) && $attributs['start'] != null ) {
        $filter['start'] = date('Y-m-d', strtotime($attributs['start']));
    }

    if (isset($attributs['end']) && $attributs['end'] != null ) {
        $filter['end'] = date('Y-m-d', strtotime($attributs['end']));
    }

    if ( is_int( $qty ) ) {
        $urlSlug .= '&_perPage=' . $qty;
    }

    if ( $qty == 'qty' && get_option( 'tivents_per_page' ) != null ) {
        $urlSlug .= '&_perPage=' . get_option( 'tivents_per_page' );
    }
    else {
        $urlSlug .= '&_perPage=400';
    }

    $urlSlug .= '&_filters=' . json_encode( $filter );
    $apiURL  .= $urlSlug;

    return $apiURL;
}

/**
 *
 * Calls TIVENTS API and retrieve the products from the TIVENTS platform
 *
 * @param $apiUrl
 * @return string
 */
function tivents_call_api( $apiUrl ) {
    return json_decode(wp_remote_retrieve_body(wp_remote_get(
        $apiUrl, ['headers' => ['Content-Type' => 'application/json'], 'timeout' => 60,]
    )),
        true
    );
}

function register_custom_calendar_api() {
    register_rest_route('tivents/calendar/v1', '/events/', array(
        'methods'  => 'GET',
        'callback' => 'get_calendar_events',
        'permission_callback' => '__return_true',
    ));
}

function get_calendar_events( WP_REST_Request $request )
{
    $attributes = $request->get_attributes();
    $attributes['style'] = 'calendar';
    $attributes['type'] = 'events';
    $attributes['start'] =  $request->get_param('start');
    $attributes['end'] =  $request->get_param('end');
    if( $request->has_param('groupId')) {
        $attributes['group'] = $request->get_param( 'groupId' );
    }

    return rest_ensure_response(tivents_call_api( tivents_get_api_url($attributes) ));
}