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


class TivProFeed_Controller_Settings {

    static function set_general_settings(){
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
                        <td>Ihre Partner ID finden Sie, wenn Sie dort eingeloggt sind, in Ihrem tivents-Partnerbereich unter folgendem Link: <a href="https://manage.tivents.app/partner/profile" target="_blank">https://manage.tivents.app/partner/profile</a></td>
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
                </table>

                <h3>Optional: API Key für Sponsoren Daten</h3>
                <table>
                    <tr valign="top">
                        <th scope="row"><label for="tivents_partner_api_key">API Key</label></th>
                        <td><input type="text" id="tivents_partner_api_key" name="tivents_partner_api_key" value="<?php echo get_option('tivents_partner_api_key'); ?>" placeholder="z.B. asdasd-asdas-asdasdasd-asdasdasd-asd"/></td>
                        <td>Bitte bei TIVENTS erfragen</td>
                    </tr>
                </table>

                <h3>Optional bei Veranstaltung in Kalendaransicht, wird nicht automatisch geändert.</h3>
                <table>
                <tr valign="top">
                    <th scope="row"><label for="tivents_default_date">Anfangsdatum</label></th>
                    <td><input type="date" id="tivents_default_date" name="tivents_default_date" value="<?php echo get_option('tivents_default_date'); ?>" /></td>
                    <td>Bitte im Format YYYY-MM-DD eingeben. Z.B. 2020-01-28</td>
                </tr>
                </table>
                <?php  submit_button(); ?>
            </form>
        </div>
        <?php
    }


    static function set_design_settings(){ ?>
        <div>
            <?php screen_icon(); ?>
            <h2><?php echo __( 'Design Settings', 'text-tivents_products_feed' );?></h2>
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
            <?php  submit_button(); ?>
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
                    <td>Beispiel: <a href="https://tivents.info/list-view/" target="_blank">https://demo.tivents.info/wordpress/listenansicht/</a>  </td>
                    <td>Beispiel: <a href="https://tivents.info/grid-view/" target="_blank">https://demo.tivents.info/wordpress/kachelansicht/</a> </td>
                </tr>
                </tfoot>
            </table>
        </div>
        </div>
		<?php
	}
}