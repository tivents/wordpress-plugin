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

        extract(shortcode_atts(array(
            "type" => 'all',
            "style" => 'list',
            'divid' => 'no-id'
        ), $atts));

        if (get_option( 'tivents_partner_id' ) == null) {
            $div = '<div id="tivents-main" class="tiv-main">';
            $div .= '<div id="tivents-container" class="tiv-container">';
            $div .= '<h4 id="tivents-container-heading">Hier ist was nicht richtig eingestellt.</h4>';
            $div .= '<small>Die Partner ID fehlt.</small>';
            $div .= '</div>';
            $div .= '</div>';
            return $div;
        }

        $results =  callApi(getApiUrl($atts));

        $div = '<div class="row">';

        $div .= '<style>:root {';

        if(get_option('tivents_primary_color') != null) {
            $div .= '--tiv-prime-color: '.get_option('tivents_primary_color').';';
        }

        if(get_option('tivents_secondary_color') != null) {
            $div .= '--tiv-second-color: '.get_option('tivents_secondary_color').';';
        }

        if(get_option('tivents_text_color') != null) {
            $div .= '--tiv-text-color: '.get_option('tivents_text_color').';';
        }

        $div .= '};</style>';

        $div .= '<div id="tivents-main" class="tiv-main">';
        $div .= '<div id="tivents-container"class="tiv-container">';
        if(count($results) == 0) {
            $div .= '<h4 id="tivents-container-heading">'.__('Zur Zeit gibt es keine Produkte. Vielleicht später?', 'tivents_products_feed').'</h4>';
            $div .= '</div>';
            $div .= '</div>';
            return $div;
        }

        switch ($style) {
            case 'grid':
                $div .= TivProFeed_View_Grid::setGridView($results);
                break;
            case 'calendar':
                /**
                 * Enqueue the full calendar scripts and styles
                 */
                wp_enqueue_style( 'fullcalendar_daygrid_style');
                wp_enqueue_script('fullcalendar_core_script');
                wp_enqueue_script('fullcalendar_locale_script');
                wp_enqueue_style('sweetalert_style');
                wp_enqueue_script('sweetalert_script');
                wp_enqueue_style( 'tiv-calender-style');
                wp_enqueue_script('tiv-calender-js');

                if ($divid != 'no-id') {
                    $div .= '<div id="'.$divid.'">';
                }
                else {
                    $div .= '<div id="tiv-calendar">';
                }
                $div .= '<style>body: {background: #000000 !important;}</style>';
                $div .= TivProFeed_View_Calendar::setCalendarView($results, $divid);
                $div .= '</div>';
                $div .= '<div id="tivents-calendar-legend" class="mt-3"><h3>Legende:<br>';
                $div .= '<div id="tivents-calendar-legend-success" >Frei</div>';
                $div .= '<div id="tivents-calendar-legend-warning" >Geringe Verfügbarkeit</div>';
                $div .= '<div id="tivents-calendar-legend-danger" >Ausverkauft</div>';
                $div .= '</h3></div>';

                break;
            case 'list-no-image':
                $div .= TivProFeed_View_Lists::setListWithoutImages($results);
                break;
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