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
			<h2><?php echo esc_html( __( 'TIVENTS Plugin Settings', 'tivents_products_feed' ) ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'tivents_products_feed_options_group' ); ?>
				<table class="form-table">
                    <tr>
                        <th scope="row"><label for="tivents_demo_environment"><?php esc_html_e( 'Demo-Umgebung', 'tivents_products_feed' ); ?></label></th>
                        <td>
                            <select id="tivents_demo_environment" name="tivents_demo_environment">
                                <option value="1" <?php selected( get_option( 'tivents_demo_environment' ), 1); ?>><?php esc_html_e( 'Ja', 'tivents_products_feed' ); ?></option>
                                <option value="0" <?php selected( get_option( 'tivents_demo_environment' ), 0 ); ?>><?php esc_html_e( 'Nein', 'tivents_products_feed' ); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e( 'Soll das Kalender dauerhaft geladen werden?', 'tivents_products_feed' ); ?> Value: <?php echo esc_html( get_option( 'tivents_demo_environment' ) ); ?></p>
                        </td>
                    </tr>

					<tr>
						<th scope="row"><label for="tivents_partner_id"><?php esc_html_e( 'Ihre Partner ID', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="text" id="tivents_partner_id" name="tivents_partner_id" value="<?php echo esc_html( get_option( 'tivents_partner_id' ) ); ?>" required/>
							<p class="description"><?php esc_html_e( 'Ihre Partner ID finden Sie, wenn Sie dort eingeloggt sind, in Ihrem tivents-Partnerbereich unter folgendem Link:', 'tivents_products_feed' ); ?> <a href="https://manage.tivents.app/partner/profile" target="_blank">https://manage.tivents.app/partner/profile</a></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_per_page"><?php esc_html_e( 'Anzahl der anzuzeigenden Produkte', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="text" id="tivents_per_page" name="tivents_per_page" value="<?php echo esc_html( get_option( 'tivents_per_page' ) ); ?>"  placeholder="<?php esc_attr_e( 'z.B. 5', 'tivents_products_feed' ); ?>"/>
							<p class="description"><?php esc_html_e( 'Wie viele Produkte sollen angezeigt werden?', 'tivents_products_feed' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_partner_api_key"><?php esc_html_e( 'API Key', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="text" id="tivents_partner_api_key" name="tivents_partner_api_key" value="<?php echo esc_html( get_option( 'tivents_partner_api_key' ) ); ?>" placeholder="<?php esc_attr_e( 'z.B. asdasd-asdas-asdasdasd-asdasdasd-asd', 'tivents_products_feed' ); ?>"/>
							<p class="description"><?php esc_html_e( 'Bitte bei TIVENTS erfragen', 'tivents_products_feed' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_default_date"><?php esc_html_e( 'Anfangsdatum', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="date" id="tivents_default_date" name="tivents_default_date" value="<?php echo esc_html( get_option( 'tivents_default_date' ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Bitte im Format YYYY-MM-DD eingeben. Z.B. 2020-01-28', 'tivents_products_feed' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_primary_color"><?php esc_html_e( 'Primäre Farbe', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="text" id="tivents_primary_color" name="tivents_primary_color" value="<?php echo esc_html( get_option( 'tivents_primary_color' ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Farbe für den Streifen an der Seite und den Hoover Effekt. Standard: #F5C800', 'tivents_products_feed' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_secondary_color"><?php esc_html_e( 'Sekundäre Farbe', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="text" id="tivents_secondary_color" name="tivents_secondary_color" value="<?php echo esc_html( get_option( 'tivents_secondary_color' ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Farbe für den Ort, Laufzeit oder Datum. Standard: #000000', 'tivents_products_feed' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="tivents_text_color"><?php esc_html_e( 'Text Farbe', 'tivents_products_feed' ); ?></label></th>
						<td>
							<input type="text" id="tivents_text_color" name="tivents_text_color" value="<?php echo esc_html( get_option( 'tivents_text_color' ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Farbe für den Produktnamen. Standard: #000000', 'tivents_products_feed' ); ?></p>
						</td>
					</tr>

                    <tr>
                        <th scope="row"><label for="tivents_load_calendar"><?php esc_html_e( 'Kalender laden', 'tivents_products_feed' ); ?></label></th>
                        <td>
                            <select id="tivents_load_calendar" name="tivents_load_calendar">
                                <option value="1" <?php selected( get_option( 'tivents_load_calendar' ), '1' ); ?>><?php esc_html_e( 'Ja', 'tivents_products_feed' ); ?></option>
                                <option value="0" <?php selected( get_option( 'tivents_load_calendar' ), '0' ); ?>><?php esc_html_e( 'Nein', 'tivents_products_feed' ); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e( 'Soll das Kalender dauerhaft geladen werden?', 'tivents_products_feed' ); ?> Value: <?php echo esc_html( get_option( 'tivents_load_calendar' ) ); ?></p>
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
			<h1><?php echo esc_html( __( 'TIVENTS Plugin Usage', 'tivents_products_feed' ) ); ?></h1>
			<h2>Shortcode</h2>
			<h2><?php esc_html_e( 'Nutzung', 'tivents_products_feed' ); ?></h2>
			<p><?php esc_html_e( 'Kopieren Sie einfach einen der folgenden Shortcodes in die Seite auf der die Produkte angezeigt werden sollen.)', 'tivents_products_feed' ); ?></p>
			<p><?php esc_html_e( 'Als optionaler Parameter kann dabei: "limit" genutzt werden. Z.B: [tivents_products limit=6][/tivents_products] Hier werden dann 6 Produkte angezeigt.', 'tivents_products_feed' ); ?></p>
			<table>
				<thead>
				<td><h2><?php esc_html_e( 'Listenansicht', 'tivents_products_feed' ); ?></h2></td>
				<td><h2><?php esc_html_e( 'Kachelansicht', 'tivents_products_feed' ); ?></h2></td>
				<td><h2><?php esc_html_e( 'Kalendaransicht', 'tivents_products_feed' ); ?></h2></td>
				</thead>
				<tr>
					<td>
						<h3><?php esc_html_e( 'für alle Produkte', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="list"][/tivents_products]</b></p>
					</td>
					<td>
						<h3><?php esc_html_e( 'für alle Produkte', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="grid"][/tivents_products]</b></p>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php esc_html_e( 'nur für Gutscheine', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="list" type=coupons][/tivents_products]</b></p>
					</td>
					<td>
						<h3><?php esc_html_e( 'nur für Gutscheine', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="grid" type=coupons][/tivents_products]</b></p>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<h3><?php esc_html_e( 'nur für Events', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="list" type=events][/tivents_products]</b></p>
					</td>
					<td>
						<h3><?php esc_html_e( 'nur für Events', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="grid" type=events][/tivents_products]</b></p>
					</td>
					<td>
						<h3><?php esc_html_e( 'nur für Events', 'tivents_products_feed' ); ?></h3>
						<p><b>[tivents_products style="calendar" type=events][/tivents_products]</b></p>
					</td>
				</tr>
				<tfoot>
				<tr>
					<td><?php esc_html_e( 'Beispiel:', 'tivents_products_feed' ); ?> <a href="https://wordpress-demo.tivdev.de/listenansichten" target="_blank">Listenansicht</a>  </td>
					<td><?php esc_html_e( 'Beispiel:', 'tivents_products_feed' ); ?> <a href="https://wordpress-demo.tivdev.de/kachelansicht" target="_blank">Kachelansicht</a> </td>
					<td><?php esc_html_e( 'Beispiel:', 'tivents_products_feed' ); ?> <a href="https://wordpress-demo.tivdev.de/kalenderansicht" target="_blank">Kalenderansicht</a> </td>
				</tr>
				</tfoot>
			</table>
		</div>
		<?php
	}
}
