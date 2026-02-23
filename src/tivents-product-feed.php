<?php
/**
 * Plugin Name: TIVENTS Products Feed
 * description: Crawl products form tivents
 * Version: 2.0.2
 *
 * Author: tivents
 * Author URI: https://tivents.info/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPLv2 or later
 * Text Domain: tivents_products_feed
 * Domain Path: /languages
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

define( 'TIVENTPRO_CURRENT_VERSION', '2.0.2' );

function register_styles() {
    if(!wp_style_is('tiv-plugin-style', 'enqueued' )) {
        wp_register_style( 'tiv-plugin-style', plugins_url( 'assets/tivents/tiv-plugin.css', __FILE__ ) );
        wp_enqueue_style('tiv-plugin-style');
    }
}

function register_fullcalendar()
{
    if(!wp_style_is('fullcalendar_style', 'enqueued' )) {
        wp_register_style( 'fullcalendar_style', plugins_url( 'assets/fullcalendar/main.min.css', __FILE__ ) );
        wp_enqueue_style( 'fullcalendar_style' );
    }
    if(!wp_script_is( 'fullcalendar_core_script', 'enqueued' )) {
        wp_register_script( 'fullcalendar_core_script', plugins_url( 'assets/fullcalendar/main.min.js', __FILE__ ) );
        wp_enqueue_script('fullcalendar_core_script');
    }

    if(!wp_script_is( 'fullcalendar_locale_script', 'enqueued' )) {
        wp_register_script( 'fullcalendar_locale_script', plugins_url( 'assets/fullcalendar/locales-all.min.js', __FILE__ ) );
        wp_enqueue_script('fullcalendar_locale_script');
    }

    if(!wp_style_is('tiv-calender-style', 'enqueued' )) {
        wp_register_style( 'tiv-calender-style', plugins_url( 'assets/tivents/tiv-calendar.css', __FILE__ ) );
        wp_enqueue_style('tiv-calender-style');
    }

    if(!wp_script_is('tiv-calendar-js', 'enqueued' )) {
        wp_register_script( 'tiv-calendar-js', plugins_url( 'assets/tivents/tiv-calendar.js', __FILE__ ) );
        wp_enqueue_script('tiv-calendar-js');
    }
}

function register_sweetalert()
{
    if(!wp_style_is('sweetalert_style', 'enqueued' )) {
        wp_register_style( 'sweetalert_style', plugins_url( 'assets/sweetalert/sweetalert2.min.css', __FILE__ ) );
        wp_enqueue_style('sweetalert_style');
    }

    if(!wp_script_is( 'sweetalert_script', 'enqueued' )) {
        wp_register_script( 'sweetalert_script', plugins_url( 'assets/sweetalert/sweetalert2.all.min.js', __FILE__ ) );
        wp_enqueue_script( 'sweetalert_script' );
    }
}


add_action( 'wp_enqueue_scripts', 'register_styles' );
add_action( 'wp_enqueue_scripts', 'register_sweetalert' );


add_action('rest_api_init', 'register_custom_calendar_api');

add_action( 'admin_menu', 'tivents_products_feed_setup_menu' );
add_action( 'admin_init', 'tivents_products_feed_register_settings' );

add_shortcode( 'tivents_products', 'tivents_products_feed_show' );
add_shortcode( 'tivents_sponsorships', 'tivents_sponsorships_feed_show' );


/** ToDo Change the name of the plugin to TIVENTS Products Feed */
function tivents_products_feed_setup_menu() {
    add_menu_page(
        'TIVENTS',
        'TIVENTS Einstellungen',
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
        'tivents_products_feed-settings',
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

/*** Build the TIVENTS API to get the products of the vendor
 *
 * @param $attributs
 * @return string
 */
function tivents_get_api_url( $attributs  ) {

    extract(shortcode_atts(
            [
                'type'  => 'all',
                'style' => 'list',
                'limit'   => 'limit',
                'group' => 'group',
                'category' => 'category',
                'sort' => 'start',
                'start',
                'end',
            ],
            $attributs
        )
    );

    $apiURL = 'https://public.tivents.io/products/v1?filter[status]=400';

    if ( get_option( 'tivents_partner_id' ) == null || get_option( 'tivents_partner_id' ) == 'all-area' ) {
        $apiURL .= match($type) {
            'events' => '&filter[product_type]=1',
            'coupons' => '&filter[product_type]=2',
            'certificates' => '&filter[product_type]=6',
        };
    } elseif ( $style == 'calendar' ) {
        $apiURL .= '&filter[product_type]=1';
        $apiURL .= '&filter[is_group_product]=0';
        $apiURL .= '&filter[hosts_globalid]='.get_option('tivents_partner_id');
    } else {
        $apiURL .= '&filter[hosts_globalid]='.get_option('tivents_partner_id');
        if ( $type == 'events' ) {
            $apiURL .= '&filter[product_type]=1';
            $apiURL .= '&sort=start';

        } elseif ( $type == 'coupons' ) {
            $apiURL .= '&filter[product_type]=2';
        }
    }

    if ( $group != 'group' ) {
        $apiURL .= '&filter[product_group_id]='.$group;
    }

    if (isset($attributs['start']) && $attributs['start'] != null ) {

        if (isset($attributs['end']) && $attributs['end'] != null ) {
            $apiURL .= '&filter[event_date]='.date('Y-m-d', strtotime($attributs['start'])).','.date('Y-m-d', strtotime($attributs['end']));
        } else {
            $apiURL .= '&filter[start]='.date('Y-m-d', strtotime($attributs['start']));
        }
    }

    if ( is_int( $limit ) ) {
        $apiURL .= '&limit='.$limit;
    }

    if ( $category != 'category' ) {
        $apiURL .= '&filter[category]='.$category;
    } else if ( $limit == 'limit' && get_option( 'tivents_per_page' ) != null ) {
        $apiURL .= '&limit='.get_option( 'tivents_per_page');
    } else {
        $apiURL .= '&limit=400';
    }

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

    $apiResult = wp_remote_retrieve_body(wp_remote_get(
        $apiUrl, [
            'headers' => ['Content-Type' => 'application/json'],
            'timeout' => 60,
        ]
    ));

    return json_decode($apiResult, true);
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

    $result = tivents_call_api( tivents_get_api_url($attributes) );

    if(array_key_exists('data', $result)) {
        return rest_ensure_response($result['data']);
    } else {
        return rest_ensure_response($result);
    }

}