<?php
/**
 * Settings.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://cocase.eu/
 *
 */

class Settings {

	static function set_design_settings(){ ?>
        <div>
            <form method="post" action="options.php">
				<?php settings_fields( 'tivents_products_feed_options_group' ); ?>
                <table>
                    <tr valign="top">
                        <th scope="row">
                            <label for="tivents_bootstrap_version">Bootstrag Version</label>
                        </th>
                        <td>
                            <select type="select" id="tivents_bootstrap_version" name="tivents_bootstrap_version">
                                <option value="4.3.1" <?php if  ( get_option('tivents_bootstrap_version') == '4.3.1') { echo 'selected';}; ?> >4.3.1</option>
                                <option value="3.3.7" <?php if  ( get_option('tivents_bootstrap_version') == '3.3.7') { echo 'selected';}; ?> >3.3.7</option>
                            </select>
                        </td>
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
            </form>
        </div>
		<?php
	}

	static function show_plugin_infos() {
		?>
        <div>
			<?php screen_icon(); ?>
            <h1><?php echo __( 'tivents Product List', 'text-tivents_products_feed' );?></h1>
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
                    <td>Beispiel: <a href="https://tivents.info/list-view/" target="_blank">https://tivents.info/list-view/</a>  </td>
                    <td>Beispiel: <a href="https://tivents.info/grid-view/" target="_blank">https://tivents.info/grid-view/</a> </td>
                </tr>
                </tfoot>
            </table>
        </div>
        </div>
		<?php
	}
}