<?php
/**
 * Products.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://cocase.eu/
 *
 */

class TivProFeed_Controller_Products {

    static function getProductUrl($instance, $url, $shortUrl = null) {

        if($shortUrl != null) {
            $product_url = '<a href="'.$shortUrl.'">';
        }
        else {
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
        }

        return $product_url;
    }

    static function setProductTime($result) {
        if ($result['date'] == null) {
            $date = date('d.m.Y H:i', strtotime($result['start'])).' - '.date('d.m.Y H:i', strtotime($result['end']));
        }
        else {
            $date = $result['date'];
        }
        return $date;
    }

    static function createDiv($atts)
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
                [
                    'headers' => ['X-Token' => '123'],
                ]
            )
        );
        $results = json_decode($results, TRUE);

        if (count($results) == 0) {
            $div = '<div class="row">';
            $div .= '<div class="tiv-main">';
            $div .= '<div class="tiv-container">';
            $div .= '<h4>Zur Zeit gibt es keine Produkte. Vielleicht später?</h4>';
            $div .= '</div>';
            $div .= '</div>';

            return $div;

        }

        $div = '<div class="row">';
        $div .= '<div class="tiv-main">';
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
            case 'list':
            default:
                $div .= TivProFeed_View_Lists::setListWithImages($results);
                break;

        }

        $div .=  '</div>';
        $div .=  '</div>';
        $div .=  '</div>';

        $div .= TivProFeed_View_Lists::setFooter();

        return $div;
    }

}