<?php
/**
 * Plugin Name: tivents Products Feed
 * description: Crawl products form tivents
 * Version: 1.3.2
 * Author: tivents
 * Author URI:        https://tivents.info/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPLv2 or later
 * Text Domain: tivents_products_feed
 */


/**
 * Add Views
 */
require_once 'views/class-calendar.php';
require_once 'views/class-lists.php';
require_once 'views/class-grid.php';
require_once 'views/class-sponsorships.php';

/**
 * Add controllers
 */
require_once 'controllers/class-products.php';
require_once 'controllers/class-settings.php';

wp_register_style('tivents_products_style', plugins_url('assets/css/tiv.css', __FILE__));
wp_enqueue_style( 'tivents_products_style');

/**
 * Register all full calendar styles
 *
 */
wp_register_style('fullcalendar_core_style', plugins_url('plugins/fullcalendar/packages/core/main.css', __FILE__));



switch (get_option('tivents_bootstrap_version')) {

    case '4':
        wp_register_script( 'bootstrap_script', plugins_url('js/bootstrap/4.5.3.min.js', __FILE__));
        wp_register_style('bootstrap_style', plugins_url('css/bootstrap/4.5.3.min.css', __FILE__));
        break;
    default:
        wp_register_script( 'bootstrap_script', plugins_url('assets/js/bootstrap/3.4.1.min.js', __FILE__));
        wp_register_style('bootstrap_style', plugins_url('assets/css/bootstrap/3.4.1.min.css', __FILE__));
        break;
}

wp_register_style('fullcalendar_daygrid_style', plugins_url('plugins/fullcalendar/packages/daygrid/main.css', __FILE__));
wp_register_script('fullcalendar_core_script', plugins_url('plugins/fullcalendar/packages/core/main.js', __FILE__));
wp_register_script('fullcalendar_daygrid_script', plugins_url('plugins/fullcalendar/packages/daygrid/main.js', __FILE__));
wp_register_script('fullcalendar_interaction_script', plugins_url('plugins/fullcalendar/packages/interaction/main.js', __FILE__));
wp_register_script('fullcalendar_languages_script', plugins_url('plugins/fullcalendar/packages/core/locales-all.min.js', __FILE__));

add_action('admin_menu', 'tivents_products_feed_setup_menu');
add_action( 'admin_init', 'tivents_products_feed_register_settings' );

add_shortcode('tivents_products', 'tivents_products_feed_show');
add_shortcode('tivents_sponsorships', 'tivents_sponsorships_feed_show');

function tivents_products_feed_setup_menu(){

    add_menu_page( 'tivents Products Feed',
        'tivents Products Feed',
        'manage_options',
        'tivents_products_feed-settings', 'tivents_products_feed_init', 'dashicons-tickets-alt');
    add_submenu_page( 'tivents_products_feed-settings',       // parent slug
        'Kalendereinstellungen',    // page title
        'Kalender',             // menu title
        'manage_options',           // capability
        'calendar',      // slug
        'tivents_products_feed_init' // callback
    );
    add_submenu_page( 'tivents_products_feed-settings',       // parent slug
        'Design',    // page title
        'Design',             // menu title
        'manage_options',           // capability
        'design',      // slug
        'set_design_settings' // callback
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
    add_option( 'tivents_secondary_color', '#000000');
    add_option( 'tivents_base_url', null);
    add_option( 'tivents_per_page', null);
    add_option( 'tivents_bootstrap_version', '4.3.1');
    add_option( 'tivents_default_date', null);
    add_option( 'tivents_partner_api_key', null);
    register_setting( 'tivents_products_feed_options_group', 'tivents_partner_id', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_primary_color', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_secondary_color', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_base_url', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_per_page', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_bootstrap_version', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_default_date', 'tivents_products_feed_callback' );
    register_setting( 'tivents_products_feed_options_group', 'tivents_partner_api_key', 'tivents_products_feed_callback' );
}


function tivents_products_feed_init(){
    TivProFeed_Controller_Settings::set_general_settings();
}

function show_plugin_infos() {
    TivProFeed_Controller_Settings::show_plugin_infos();
}

function set_design_settings() {
    TivProFeed_Controller_Settings::set_design_settings();
}


function tivents_products_feed_show($atts)
{
    $type = null;
    extract(shortcode_atts(array(
        "type" => 'all',
        "style" => 'list',
        'divid' => 'no-id'
    ), $atts));

    if (get_option( 'tivents_partner_id' ) == null) {
        $div = '<div class="tiv-main">';
        $div .= '<div class="tiv-container">';
        $div .= '<h4>Hier ist was nicht richtig eingestellt.</h4>';
        $div .= '<small>Die Partner ID fehlt.</small>';
        $div .= '</div>';
        $div .= '</div>';
        return $div;
    }

    $apiURL = getApiUrl($atts);
    $results = wp_remote_retrieve_body(wp_remote_get( $apiURL ,
        array(
            'headers' => array(
                'X-Token' => '123',
            ))));
    $results = json_decode($results, TRUE);

    if (count($results) == 0) {
        $div = '<div class="tiv-main">';
        $div .= '<div class="tiv-container">';
        $div .= '<h4>Zur Zeit gibt es keine Produkte. Vielleicht später?</h4>';
        $div .= '</div>';
        $div .= '</div>';

        return $div;

    }

    $div = '<div class="tiv-main">';
    $div .= '<div class="tiv-container">';
    $div .= '<style>:root {--tiv-prime-color: '.get_option('tivents_primary_color').';--tiv-scnd-color: '.get_option('tivents_secondary_color').';</style>';


    switch ($style) {

        case 'grid':
            $div .= TivProFeed_View_Grid::setGridView($results);
            break;
        case 'calendar':
            /**
             * Enqueue the full calendar scripts and styles
             */

            wp_enqueue_style( 'fullcalendar_bootstrap_style');
            wp_enqueue_script('fullcalendar_bootstrap_script');
            wp_enqueue_style( 'fullcalendar_core_style');
            wp_enqueue_style( 'fullcalendar_daygrid_style');
            wp_enqueue_script('fullcalendar_popper_script');
            wp_enqueue_script('fullcalendar_core_script');
            wp_enqueue_script('fullcalendar_daygrid_script');
            wp_enqueue_script('fullcalendar_interaction_script');
            wp_enqueue_script('fullcalendar_languages_script');
            if ($divid != 'no-id') {
                $div .= '<div id="'.$divid.'">';
            }
            else {
                $div .= '<div id="tiv-calendar">';
            }
            $div .= '<style>body: {background: #000000 !important;}</style>';
            if (get_option('tivents_base_url') != null) {
                $div .= TivProFeed_View_Calendar::setCalendarView($results, $divid, get_option('tivents_base_url'));
            }
            else {
                $div .= TivProFeed_View_Calendar::setCalendarView($results, $divid, 'https://tivents.de');
            }
            $div .= '</div>';
            break;
        case 'list-no-image':
        case'list':
        default:
            $div .= TivProFeed_View_Lists::setListWithImages($results);
            break;

    }
    $div .=  '</div>';
    $div .=  '</div>';
    return $div;
}


function tivents_sponsorships_feed_show($atts)
{
    if (get_option( 'tivents_partner_id' ) == null) {
        $div = '<div class="tiv-main">';
        $div .= '<div class="tiv-container">';
        $div .= '<h4>Hier ist was nicht richtig eingestellt.</h4>';
        $div .= '<small>Die Partner ID fehlt.</small>';
        $div .= '</div>';
        $div .= '</div>';
        return $div;
    }
    if (get_option( 'tivents_partner_api_key' ) == null) {
        $div = '<div class="tiv-main">';
        $div .= '<div class="tiv-container">';
        $div .= '<h4>Hier ist was nicht richtig eingestellt.</h4>';
        $div .= '<small>Api Key fehlt. <a href="https://docs.tivents.info/wordpress-plugin/api-key">Weitere Informationen</a> </small>';
        $div .= '</div>';
        $div .= '</div>';
        return $div;
    }

    $apiURL = getSponsorshipApiUrl($atts);
    $results = wp_remote_retrieve_body(wp_remote_get( $apiURL ,
        array(
            'headers' => array(
                'X-Token' => '6b70cb75-1726-41a4-a569-081759992780',
                'Vendor-Api-Key' => get_option( 'tivents_partner_api_key' ),
            ))));
    $results = json_decode($results, TRUE);

    if (count($results) == 0) {
        $div = '<div class="tiv-main">';
        $div .= '<div class="tiv-container">';
        $div .= '<h4>Zur Zeit gibt es keine Sponsoren. Vielleicht sind Sie der erste? Zur Buchung: <a href="https://zoo-goerlitz.tivents.app/" target="_blank">Shop</a></h4>';
        $div .= '</div>';
        $div .= '</div>';

        return $div;

    }
    $div = '<div class="tiv-main-sponorships">';
    $div .= '<div class="tiv-container">';
    $div .= '<style>:root {--tiv-prime-color: '.get_option('tivents_primary_color').';--tiv-scnd-color: '.get_option('tivents_secondary_color').';</style>';
    $div .= TivProFeed_View_Sponsorships::setList($results);
    $div .=  '</div>';
    $div .=  '</div>';
    return $div;
}


function getApiUrl($atts) {

    extract(shortcode_atts(array(
        "type" => 'all',
        "style" => 'list',
        "qty" => "qty",
        'group' => 'group'
    ), $atts));


    $apiURL = 'https://public.tivents.systems/products/v1';

    if ($group != 'group') {
        $apiURL .= '?id='.$group;
    }
    else {

        if (get_option( 'tivents_partner_id' ) == null || get_option( 'tivents_partner_id' ) == 'all-area') {
            if ( $type == 'events' ) {
                $apiURL .= '?_filters={"status":"400","product_type":"1"}&_sortField=start&_sortDir=ASC';
            } else if ( $type == 'coupons' ) {
                $apiURL .= '?_filters={"status":"400","product_type":"2"}&_sortField=start&_sortDir=ASC';
            } else {
                $apiURL .= '?_filters={"status":"400"}&_sortField=start&_sortDir=ASC';
            }
        }
        else {
            if ( $type == 'events' ) {
                $apiURL .= '?_filters={"status":"400", "hosts_globalid":"' . get_option( 'tivents_partner_id' ) . '","product_type":"1"}&_sortField=start&_sortDir=ASC';
            } else if ( $type == 'coupons' ) {
                $apiURL .= '?_filters={"status":"400", "hosts_globalid":"' . get_option( 'tivents_partner_id' ) . '","product_type":"2"}&_sortField=start&_sortDir=ASC';
            } else {
                $apiURL .= '?_filters={"status":"400", "hosts_globalid":"' . get_option( 'tivents_partner_id' ) . '"}&_sortField=start&_sortDir=ASC';
            }
        }
        if (is_int($qty)) {
            $apiURL .= '&_perPage='.$qty;
        }

        if ($qty == 'qty' && get_option( 'tivents_per_page' ) != null) {
            $apiURL .= '&_perPage='.get_option( 'tivents_per_page' );
        }
    }
    return $apiURL;
}

function getSponsorshipApiUrl() {
    return 'https://participants-api.intern.tivents.de/sponsorships/v1?source=wordpress';
}





