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
Version: 1.1.0
Author: tivents
License: GPLv2 or later
Text Domain: tivents_products_feed
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
}

function tivents_products_feed_register_settings() {
	add_option( 'tivents_partner_id', null);
	add_option( 'tivents_primary_color', '#6eafdc');
	add_option( 'tivents_secondary_color', '#000000');
	add_option( 'tivents_secondary_color', '#000000');
	add_option( 'tivents_base_url', null);
	add_option( 'tivents_per_page', null);
	register_setting( 'tivents_products_feed_options_group', 'tivents_partner_id', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_primary_color', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_secondary_color', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_base_url', 'tivents_products_feed_callback' );
	register_setting( 'tivents_products_feed_options_group', 'tivents_per_page', 'tivents_products_feed_callback' );
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
        <p>Kopieren Sie einfach einen der folgenden Shortcodes in die Seite auf der die Produkte angezeigt werden sollen.)</p>
        <p>Als optionaler Parameter kann dabei: "qty" genutzt werden. Z.B: [tivents_products qty=6][/tivents_products] Hier werden dann 6 Produkte angezeigt.</p>
        <table>
            <thead>
                <td><h2>Listenansicht</h2></td>
                <td><h2>Kachelansicht</h2></td>
            </thead>
            <tr>
                <td>
                    <h3>für alle Produkte</h3>
                    <p><b>[tivents_products style="list"][/tivents_products]</b></p>
                </td>
                <td>
                    <h3>für alle Produkte</h3>
                    <p><b>[tivents_products style="grid"][/tivents_products]</b></p>
                </td>
            </tr>
            <tr>
                <td>
                <h3>nur für Gutscheine</h3>
                <p><b>[tivents_products style="list" type=coupons][/tivents_products]</b></p>
                </td>
                <td>
                <h3>nur für Gutscheine</h3>
                <p><b>[tivents_products style="grid" type=coupons][/tivents_products]</b></p>
                </td>

            </tr>
            <tr>
                <td>
                <h3>nur für Events</h3>
                <p><b>[tivents_products style="list" type=events][/tivents_products]</b></p>
                </td>
                <td>
                <h3>nur für Events</h3>
                <p><b>[tivents_products style="grid" type=events][/tivents_products]</b></p>
                </td>
            </tr>
            <tfoot>
            <tr>
                <td>Beispiel: <a href="https://tivents.info/list-view/" target="_blank">https://tivents.info/list-view/</a>  </td>
                <td>Beispiel: <a href="https://tivents.info/grid-view/" target="_blank">https://tivents.info/grid-view/</a> </td>
            </tr>
            </tfoot>
        </table>
    </div>
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

	if ($style == 'grid')
	{
		foreach ($results as $result) {
			if ($result['type'] == 2) {
				continue;
			}

			$product_url = getProductUrl($result['magento_instance'], $result['magento_url_key']);
			$date = setProductTime($result);

			$div .=  '<div class="tiv-grid-l tiv-grid-m tiv-grid-s">';
			$div .=  $product_url;
			$div .=  '<div class="tiv-sheet-grid">';
			$div .=  '<img class="tiv-grid-img tiv-sizer" src="'.$result['image_url'].'" />';
			$div .=  '<div class="tiv-grid-text tiv-product-name tiv-font">'.$result['name'].'</div>';
			$div .=  '<div class="tiv-grid-hover">';
			$div .=     '<div class="tiv-grid-info tiv-product-date tiv-font">'.$date.'</div>';
			$div .=     '<div class="tiv-grid-info tiv-product-veneu tiv-font">'.$result['place'].'</div>';
			$div .=  '</div>';
			$div .=  '</div>';
			$div .= '</a>';
			$div .=  '</div>';
		}
	}
	else {
		foreach ($results as $result) {
			if ($result['type'] == 2) {
				continue;
			}

			$product_url = getProductUrl($result['magento_instance'], $result['magento_url_key']);

			if ($result['date'] == null) {
				$date = date('d.m.Y H:i', strtotime($result['start']).' - '.date('d.m.Y H:i', strtotime($result['end'])));
			}
			else {
				$date = $result['date'];
			}

			$div .= '<div class="tiv-product-l tiv-product-m tiv-product-s">';
			$div .= '<div class="tiv-sheet tiv-border">';
			$div .= $product_url;
			$div .= '<div class="tiv-sheet-inner">';
			$div .= '<div class="tiv-sheet-left">';
			$div .= '<div class="tiv-product-name tiv-font">'.$result['name'].'</div>';
			$div .= '<div class="tiv-product-date tiv-font">'.$date.'</div>';
			$div .= '<div class="tiv-product-veneu tiv-font">'.$result['place'].'</div>';
			$div .= '</div>';
			$div .=  '<div class="tiv-sheet-right">';
			$div .=  '<img class="tiv-product-img" src="'.$result['image_url'].'" />';
			$div .=  '</div>';
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

function getApiUrl($atts) {

	extract(shortcode_atts(array(
		"type" => 'all',
		"style" => 'list',
		"qty" => "qty"
	), $atts));


	$apiURL = 'https://public.tivents.systems/products/v1';

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

	if ($qty == null && get_option( 'tivents_per_page' ) != null) {
		$apiURL .= '&_perPage='.get_option( 'tivents_per_page' );
	}

	return $apiURL;
}

function getProductUrl($instance, $url) {


	$url .= '?utm_source=wp&utm_medium=link&utm_campaign=base';
	if (get_option('tivents_base_url') != null) {
		$product_url = '<a href="'.get_option('tivents_base_url').'/'.$url.'">';
	}
	else {
		if ($instance == 10) {
			$product_url = '<a href="https://tivents.pro/'.$url.'">';
		}
		else {
			$product_url = '<a href="https://tivents.de/'.$url.'">';
		}
	}

	return $product_url;
}

function setProductTime($result) {
	if ($result['date'] == null) {
		$date = date('d.m.Y H:i', strtotime($result['start']).' - '.date('d.m.Y H:i', strtotime($result['end'])));
	}
	else {
		$date = $result['date'];
	}

	return $date;
}




