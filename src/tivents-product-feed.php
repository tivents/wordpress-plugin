<?php
/**
 * wp-faq-api.php Class Doc Comment
 *
 * @category Class
 * @package  wp-faq-api
 * @author   whelwig
 * @license  Restricted
 * @link     https://cocase.eu/
 *
 */

/**
 * Plugin Name: tivents Products Feed
description: Crawl products form tivents
Version: 1.2.2
Author: tivents
License: GPLv2 or later
Text Domain: tivents_products_feed
 *
 */


/**
 * Add Views
 */
require_once 'views/Footer.php';
require_once 'views/Calendar.php';
require_once 'views/Lists.php';
require_once 'views/Grid.php';

/**
 * Add controllers
 */
require_once 'controllers/Products.php';
require_once 'controllers/Settings.php';

wp_register_style('tivents_products_style', plugins_url('css/tiv.css', __FILE__));
wp_enqueue_style( 'tivents_products_style');



/**
 * Register all full calendar styles
 *
 */

wp_register_style('fullcalendar_core_style', plugins_url('plugins/fullcalendar/packages/core/main.css', __FILE__));


if (get_option('tivents_bootstrap_version') != null) {
	wp_register_script( 'fullcalendar_bootstrap_script', 'https://stackpath.bootstrapcdn.com/bootstrap/'.get_option('tivents_bootstrap_version').'/js/bootstrap.min.js', array( 'jquery' ), '1.1', true );
	wp_register_style('fullcalendar_bootstrap_style', 'https://stackpath.bootstrapcdn.com/bootstrap/'.get_option('tivents_bootstrap_version').'/css/bootstrap.min.css');
}
else {
	wp_register_script( 'fullcalendar_bootstrap_script', 'https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '1.1', true );
	wp_register_style('fullcalendar_bootstrap_style', 'https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
}

wp_register_style('fullcalendar_daygrid_style', plugins_url('plugins/fullcalendar/packages/daygrid/main.css', __FILE__));
wp_register_script('fullcalendar_popper_script', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array('jquery'),'1.1', true);
wp_register_script('fullcalendar_core_script', plugins_url('plugins/fullcalendar/packages/core/main.js', __FILE__), array('jquery'),'1.1', true);
wp_register_script('fullcalendar_daygrid_script', plugins_url('plugins/fullcalendar/packages/daygrid/main.js', __FILE__), array('jquery'),'1.1', true);
wp_register_script('fullcalendar_interaction_script', plugins_url('plugins/fullcalendar/packages/interaction/main.js', __FILE__), array('jquery'),'1.1', true);
wp_register_script('fullcalendar_languages_script', plugins_url('plugins/fullcalendar/packages/core/locales-all.min.js', __FILE__), array('jquery'),'1.1', true);

/**
 * set
 */
register_activation_hook( __FILE__, 'tivents_products_feed_activation' );
register_deactivation_hook( __FILE__, 'tivents_products_feed_deactivation' );

add_action('admin_menu', 'tivents_products_feed_setup_menu');
add_action( 'admin_init', 'tivents_products_feed_register_settings' );

add_shortcode('tivents_products', 'tivents_products_feed_show');


function tivents_products_feed_activation()
{
	$apiURL = 'https://public.tivents.systems/plugins/v1?status=1&plugin=1&type=wp&referer='.site_url();
	wp_remote_retrieve_body(wp_remote_get( $apiURL ,
		array(
			'headers' => array(
				'X-Token' => '123',
			))));
}

function tivents_products_feed_deactivation()
{
	$apiURL = 'https://public.tivents.systems/plugins/v1?status=0&plugin=1&type=wp&referer='.site_url();
	wp_remote_retrieve_body(wp_remote_get( $apiURL ,
		array(
			'headers' => array(
				'X-Token' => '123',
			))));
}

function tivents_products_feed_setup_menu(){

	add_menu_page( 'tivents Products Feed', 'tivents Products Feed', 'manage_options', 'tivents_products_feed-settings', 'tivents_products_feed_init', 'dashicons-tickets-alt');
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
	register_setting( 'tivents_products_feed_options_group', 'tivents_partner_id', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_primary_color', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_secondary_color', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_base_url', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_per_page', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_bootstrap_version', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_default_date', 'tivents_products_feed_callback' );
}


function tivents_products_feed_init(){
	?>
    <div>
		<?php screen_icon(); ?>
        <h2><?php echo __( 'tivents Product List', 'text-tivents_products_feed' );?></h2>
        <form method="post" action="options.php">
			<?php settings_fields( 'tivents_products_feed_options_group' ); ?>
            <h3>General Settings</h3>
            <table>
                <tr valign="top">
                    <th scope="row"><label for="tivents_partner_id">Ihre Partner ID</label></th>
                    <td><input type="text" id="tivents_partner_id" name="tivents_partner_id" value="<?php echo get_option('tivents_partner_id'); ?>" required/></td>
                    <td>Ihre Partner ID finden Sie, wenn Sie dort eingeloggt sind, in Ihrem tivents-Partnerbereich unter folgendem Link: <a href="https://tivents.de/veranstalter/konto/uebersicht" target="_blank">https://tivents.de/veranstalter/konto/uebersicht</a></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="tivents_base_url">Ihre Basis URL</label></th>
                    <td><input type="text" id="tivents_base_url" name="tivents_base_url" value="<?php echo get_option('tivents_base_url'); ?>"  placeholder="https://custom-shop.tivents.de"/></td>
                    <td>Sollten Sie einen angepassten Shop gebucht haben, muss hier die Basis URL des Shopes eingegeben werden. Z.B.: https://mayamare.tivents.de</td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="tivents_per_page">Anzahl der anzuzeigenden Produkte</label></th>
                    <td><input type="text" id="tivents_per_page" name="tivents_per_page" value="<?php echo get_option('tivents_per_page'); ?>"  placeholder="z.B. 5"/></td>
                    <td>Wie viele Produkte sollen angezeigt werden?</td>
                </tr>
				<hr>
	            <h3>Optional bei Veranstaltung in Kalendaransicht, wir nicht automatisch geändert.</h3>
                <tr valign="top">
                    <th scope="row"><label for="tivents_default_date">Anfangsdatum</label></th>
                    <td><input type="text" id="tivents_default_date" name="tivents_default_date" value="<?php echo get_option('tivents_default_date'); ?>" /></td>
                    <td>Bitte im Format YYYY-MM-DD eingeben. Z.B. 2020-01-28</td>
                </tr>
            </table>
			<?php  submit_button(); ?>
        </form>
    </div>
	<?php
}

function show_plugin_infos() {
	Settings::show_plugin_infos();
}

function set_design_settings() {
	Settings::set_design_settings();
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
			$div .= Grid::setGridView($results);
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
			    $div .= Calendar::setCalendarView($results, $divid, get_option('tivents_base_url'));
            }
		    else {
			    $div .= Calendar::setCalendarView($results, $divid, 'https://tivents.de');
            }
			$div .= '</div>';
			break;
		case 'list-no-image':
		case'list':
		default:
			$div .= Lists::setListWithImages($results);
			break;

	}
	$div .=  '</div>';
	//$div .= Footer::setFooter();
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





