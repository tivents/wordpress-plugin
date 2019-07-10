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
description: Crawl products form tivents - dev version
Version: 0.8
Author: Willi Helwig
License: GPLv2 or later
Text Domain: github-api
 *
 */

wp_register_style('tivents_products_style', plugins_url('css/tiv.css', __FILE__));
wp_enqueue_style( 'tivents_products_style');

register_activation_hook( __FILE__, 'tivents_products_feed_activation' );
register_deactivation_hook( __FILE__, 'tivents_products_feed_deactivation' );

add_action('admin_menu', 'tivents_products_feed_setup_menu');
add_action( 'admin_init', 'tivents_products_feed_register_settings' );

add_shortcode('tivents_products', 'tivents_products_feed_show');


function tivents_products_feed_activation()
{

}

function tivents_products_feed_deactivation()
{

}

function tivents_products_feed_setup_menu(){
	add_menu_page( 'tivents Products Feed', 'tivents Products Feed', 'manage_options', 'tivents_products_feed-settings', 'tivents_products_feed_init' );
}

function tivents_products_feed_register_settings() {
	add_option( 'tivents_partner_id', 'This is my option value.');
	add_option( 'tivents_primary_color', '#6eafdc');
	add_option( 'tivents_secondary_color', '#000000');
	register_setting( 'tivents_products_feed_options_group', 'tivents_partner_id', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_primary_color', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_secondary_color', 'tivents_products_feed_callback' );
}


function tivents_products_feed_init(){
	?>
	<div>
		<?php screen_icon(); ?>
		<h2>tivents Produkt Liste</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'tivents_products_feed_options_group' ); ?>
			<h3>Generelle Einstellungen</h3>
			<table>
				<tr valign="top">
					<th scope="row"><label for="tivents_partner_id">Ihre Partner ID</label></th>
					<td><input type="text" id="tivents_partner_id" name="tivents_partner_id" value="<?php echo get_option('tivents_partner_id'); ?>" /></td>
				</tr>
                <tr valign="top">
                    <th scope="row"><label for="tivents_primary_color">Primäre Farbe</label></th>
                    <td><input type="text" id="tivents_primary_color" name="tivents_primary_color" value="<?php echo get_option('tivents_primary_color'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="tivents_secondary_color">Sekundäre Farbe</label></th>
                    <td><input type="text" id="tivents_secondary_color" name="tivents_secondary_color" value="<?php echo get_option('tivents_secondary_color'); ?>" /></td>
                </tr>
			</table>
			<?php  submit_button(); ?>
		</form>
        <h2>Nutzung</h2>
        <p>Kopieren Sie einfach einen der folgenden Shortcodes in die Seite auf der die Produkte angezeigt werden sollen.</p>
        <h3>für alle Produkte</h3>
        <p><b>[tivents_products][/tivents_products]</b></p>
        <h3>nur für Gutscheine</h3>
        <p><b>[tivents_products type=coupons][/tivents_products]</b></p>
        <h3>nur für Events</h3>
        <p><b>[tivents_products type=events][/tivents_products]</b></p>
	</div>
	<?php
}


function tivents_products_feed_show($atts)
{
	$type = null;
	extract(shortcode_atts(array(
		"type" => 'all',
		"style" => 'list'
	), $atts));

	$apiURL = 'https://event-api.intern.tivents.de/v1/products';

	if ($type == 'events') {
		$apiURL .= '?_filters={"status":"400", "hosts_globalid":"'.get_option('tivents_partner_id').'","product_type":"1"}&_sortField=start&_sortDir=ASC';
	}
	else if ($type == 'coupons'){
		$apiURL .= '?_filters={"status":"400", "hosts_globalid":"'.get_option('tivents_partner_id').'","product_type":"2"}&_sortField=start&_sortDir=ASC';
	}

	else {
		$apiURL .= '?_filters={"status":"400", "hosts_globalid":"'.get_option('tivents_partner_id').'"}&_sortField=start&_sortDir=ASC';
    }

	$results = wp_remote_retrieve_body(wp_remote_get( $apiURL ,
		array(
			'headers' => array( 'X-Token' => '123',
	                         ))));

	$results = json_decode($results, TRUE);

	$div = '<div class="tiv-main">';
	$div .= '<div class="tiv-container">';
	$div .= '<style>:root {--tiv-prime-color: '.get_option('tivents_primary_color').';--tiv-scnd-color: '.get_option('tivents_secondary_color').';</style>';

	if ($style == 'grid')
	{


		foreach ($results as $challenge) {
			if ($challenge['type'] == 2) {
				continue;
			}
			$div .=  '<div class="tiv-grid-l tiv-grid-m tiv-grid-s">';
			$div .=  '<div class="tiv-sheet-grid">';
			$div .= '<a href="https://tivents.de/'.$challenge['magento_url_key'].'">';
			$div .=  '<img class="tiv-grid-img" src="'.$challenge['image_url'].'" />';
			$div .=  '<div class="tiv-grid-hover">';
			$div .=  '<div class="tiv-grid-info tiv-product-name">'.$challenge['name'].'</div>';
			$div .=  '<div class="tiv-grid-info tiv-product-date">'.$challenge['date'].'</div>';
			$div .=  '<div class="tiv-grid-info tiv-product-veneu">'.$challenge['place'].'</div>';
			$div .=  '</div>';
			$div .= '</a>';
			$div .=  '</div>';
			$div .=  '</div>';
		}


	}
	else {

		foreach ($results as $challenge) {
		    if ($challenge['type'] == 2) {
		        continue;
            }
			$div .=  '<div class="tiv-product-l tiv-product-m tiv-product-s">';
            $div .= '<div class="tiv-sheet tiv-border">';
            $div .= '<a href="https://tivents.de/'.$challenge['magento_url_key'].'">';
			$div .= '<div class="tiv-sheet-left">';
			$div .= '<div class="tiv-product-name">'.$challenge['name'].'</div>';
			$div .= '<div class="tiv-product-date">'.$challenge['date'].'</div>';
			$div .= '<div class="tiv-product-veneu">'.$challenge['place'].'</div>';
			$div .= '</div>';
			$div .=  '<div class="tiv-sheet-right">';
			$div .=  '<img class="tiv-product-img" src="'.$challenge['image_url'].'" />';
			$div .=  '</div>';
			$div .= '</a>';
			$div .=  '</div>';
			$div .=  '</div>';

		}
	}

	$div .=  '</div>';
	$div .=  '</div>';

	return $div;

}


