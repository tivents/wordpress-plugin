<?php
/**
 * Calendar.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://cocase.eu/
 *
 */


class TivProFeed_View_Sponsorships {

	static function setList($results) {
	    $div = '';
        $div .= '<div class="row col-sm-12">';
		foreach ($results as $key => $values) {
            $div .= '<div class="col-sm-6 pb-3">';
			$div .= '<b>'.$key.'</b>';
			$div .= '<ul>';
			foreach ($values as $value) {
                $div .= '<li>'.$value['supporter'].'</li>';
            }
			$div .= '</ul></div>';

		}
        $div .= '</div>';
		return $div;
	}

	static function createSponsorshipView($atts)
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

}