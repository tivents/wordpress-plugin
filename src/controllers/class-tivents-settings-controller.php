<?php
/**
 * Settings.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://tivents.info/
 */

class Tivents_Settings_Controller {

	static function tivents_set_general_settings() {
		?>
		<div>
			<h2><?php echo esc_html( __( 'TIVENTS Plugin Settings', 'text-tivents_products_feed' ) ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'tivents_products_feed_options_group' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="tivents_partner_id">Ihre Partner ID</label></th>
						<td>
							<input type="text" id="tivents_partner_id" name="tivents_partner_id" value="<?php echo esc_html( get_option( 'tivents_partner_id' ) ); ?>" required/>
							<p class="description">Ihre Partner ID finden Sie, wenn Sie dort eingeloggt sind, in Ihrem tivents-Partnerbereich unter folgendem Link: <a href="https://manage.tivents.app/partner/profile" target="_blank">https://manage.tivents.app/partner/profile</a></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_per_page">Anzahl der anzuzeigenden Produkte</label></th>
						<td>
							<input type="text" id="tivents_per_page" name="tivents_per_page" value="<?php echo esc_html( get_option( 'tivents_per_page' ) ); ?>"  placeholder="z.B. 5"/>
							<p class="description">Wie viele Produkte sollen angezeigt werden?</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_partner_api_key">API Key</label></th>
						<td>
							<input type="text" id="tivents_partner_api_key" name="tivents_partner_api_key" value="<?php echo esc_html( get_option( 'tivents_partner_api_key' ) ); ?>" placeholder="z.B. asdasd-asdas-asdasdasd-asdasdasd-asd"/>
							<p class="description">Bitte bei TIVENTS erfragen</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_default_date">Anfangsdatum</label></th>
						<td>
							<input type="date" id="tivents_default_date" name="tivents_default_date" value="<?php echo esc_html( get_option( 'tivents_default_date' ) ); ?>" />
							<p class="description">Bitte im Format YYYY-MM-DD eingeben. Z.B. 2020-01-28</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_primary_color">Primäre Farbe</label></th>
						<td>
							<input type="text" id="tivents_primary_color" name="tivents_primary_color" value="<?php echo esc_html( get_option( 'tivents_primary_color' ) ); ?>" />
							<p class="description">Farbe für den Streifen an der Seite und den Hoover Effekt. Standard: #F5C800</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_secondary_color">Sekundäre Farbe</label></th>
						<td>
							<input type="text" id="tivents_secondary_color" name="tivents_secondary_color" value="<?php echo esc_html( get_option( 'tivents_secondary_color' ) ); ?>" />
							<p class="description">Farbe für den Ort, Laufzeit oder Datum. Standard: #000000</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_text_color">Text Farbe</label></th>
						<td>
							<input type="text" id="tivents_text_color" name="tivents_text_color" value="<?php echo esc_html( get_option( 'tivents_text_color' ) ); ?>" />
							<p class="description">Farbe für den Produktnamen. Standard: #000000</p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	static function tivents_show_plugin_infos() {
		?>
		<div>
			<h1><?php echo esc_html( __( 'TIVENTS Plugin Usage', 'text-tivents_products_feed' ) ); ?></h1>
			<h2>Shortcode</h2>
			<h2>Nutzung</h2>
			<p>Kopieren Sie einfach einen der folgenden Shortcodes in die Seite auf der die Produkte angezeigt werden sollen.)</p>
			<p>Als optionaler Parameter kann dabei: "qty" genutzt werden. Z.B: [tivents_products qty=6][/tivents_products] Hier werden dann 6 Produkte angezeigt.</p>
			<table>
				<thead>
				<td><h2>Listenansicht</h2></td>
				<td><h2>Kachelansicht</h2></td>
				<td><h2>Kalendaransicht</h2></td>
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
					<td>
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
					<td></td>
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
					<td>
						<h3>nur für Events</h3>
						<p><b>[tivents_products style="calendar" type=events][/tivents_products]</b></p>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td>Beispiel: <a href="https://wordpress-demo.tivdev.de/listenansichten" target="_blank">Listenansicht</a>  </td>
					<td>Beispiel: <a href="https://wordpress-demo.tivdev.de/kachelansicht" target="_blank">Kachelansicht</a> </td>
					<td>Beispiel: <a href="https://wordpress-demo.tivdev.de/kalenderansicht" target="_blank">Kalenderansicht</a> </td>
				</tr>
				</tfoot>
			</table>
		</div>
		<?php
	}
}
