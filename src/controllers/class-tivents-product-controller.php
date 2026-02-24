<?php
/**
 * Products.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://tivents.info/
 */

class Tivents_Product_Controller {

    public static function tivents_get_product_url( $shortUrl ): string
    {
        return '<a id="tivents-product-link" href="' . $shortUrl . '?utm=tivents-wordpress-plugin">';
    }

    public static function tivents_set_product_time( $result ) {
        if ( $result['date'] == null ) {
            $date = date( 'd.m.Y H:i', strtotime( $result['start'] ) ) . ' - ' . date( 'd.m.Y H:i', strtotime( $result['end'] ) );
        } else {
            $date = $result['date'];
        }
        return $date;
    }

    public static function tivents_create_div( $atts ) {

        extract(
            shortcode_atts(
                array(
                    'type'  => 'all',
                    'style' => 'list',
                    'divid' => 'no-id',
                    'columns'
                ),
                $atts
            )
        );

        if ( get_option( 'tivents_partner_id' ) == null ) {
            $div  = '<div id="tivents-main" class="tiv-main">';
            $div .= '<div id="tivents-container" class="tiv-container">';
            $div .= '<h4 id="tivents-container-heading">Hier ist was nicht richtig eingestellt.</h4>';
            $div .= '<small>Die Partner ID fehlt.</small>';
            $div .= '</div>';
            $div .= '</div>';
            return $div;
        }

        $results = tivents_call_api( tivents_get_api_url( $atts ) );

        $div = '<div class="row tiv-row">';

        $div .= '<style>:root {';

        if ( get_option( 'tivents_primary_color' ) != null ) {
            $div .= '--tiv-prime-color: ' . get_option( 'tivents_primary_color' ) . ';';
        }

        if ( get_option( 'tivents_secondary_color' ) != null ) {
            $div .= '--tiv-second-color: ' . get_option( 'tivents_secondary_color' ) . ';';
        }

        if ( get_option( 'tivents_text_color' ) != null ) {
            $div .= '--tiv-text-color: ' . get_option( 'tivents_text_color' ) . ';';
        }

        $div .= '};</style>';

        $div .= '<div id="tivents-main" class="tiv-main">';
        $div .= '<div id="tivents-container" class="tiv-container">';

        if ($style !== 'calendar' && count( $results ) == 0 ) {
            $div .= '<h4 id="tivents-container-heading">' . esc_html( __( 'Zur Zeit gibt es keine Produkte. Vielleicht später?', 'tivents_products_feed' ) ) . '</h4>';
            $div .= '</div>';
            $div .= '</div>';
            return $div;
        }

        switch ( $style ) {
            case 'grid':
                $div .= Tivents_Grid_View::tivents_set_grid_view( $results );
                break;
            case 'calendar':
                /*** Enqueue the full calendar scripts and styles */
                if ( $divid != 'no-id' ) {
                    $div .= '<div id="' . $divid . '">';
                } else {
                    $div .= '<div id="tiv-calendar">';
                }
                $div .= '<style>body: {background: #000000 !important;}</style>';
                $div .= Tivents_Calendar_View::tivents_set_calendar_view( $results, $divid, $atts['group'] ?? null );
                $div .= '</div>';
                $div .= '<div id="tivents-calendar-legend" class="mt-3"><h3>Legende:<br>';
                $div .= '<div id="tivents-calendar-legend-success" >Frei</div>';
                $div .= '<div id="tivents-calendar-legend-warning" >Geringe Verfügbarkeit</div>';
                $div .= '<div id="tivents-calendar-legend-danger" >Ausverkauft</div>';
                $div .= '</h3></div>';

                break;
            case 'list-no-image':
                $div .= Tivents_List_View::setListWithoutImages( $results );
                break;
            case 'unstyled-list':
                $div .= Tivents_List_View::setUnstyledList( $results );
                break;
            case 'list':
            default:
                if(array_key_exists('columns', $atts) && !is_null($atts['columns'])) {
                    $div .= Tivents_List_View::setColumnList( $results, $atts['columns'] );
                } else {
                    $div .= Tivents_List_View::setListWithImages( $results );
                }
                break;
        }

        $div .= '</div>';
        $div .= '</div>';
        // $div .= TivProFeed_Views_Base::setFooter();
        $div .= '</div>';

        return $div;
    }


    public static function create_product_card($product) {

        $product_url = Tivents_Product_Controller::tivents_get_product_url( $product['short_url'] );
        $date        = Tivents_Product_Controller::tivents_set_product_time( $product );

        if ( $product['cdn_image_key'] != null ) {
            $imageUrl = 'https://cdn.tivents.io/' . $product['cdn_image_key'];
        } else {
            $imageUrl = $product['image_url'];
        }

        $div = $product_url;

        $div .= '<div class="card mb-3 tivents-product-card">';
        $div .= '<div class="row g-0">';
        $div .= '<div class="col-sm-4">';
        $div .= '<img src="'.$imageUrl.'" class="img-fluid rounded-start" alt="...">';
        $div .= '</div>';
        $div .= '<div class="col-sm-8">';
        $div .= '<div class="card-body">';
        $div .= '<h5 class="card-title tivents-product-card-title">'.$product['name'].'</h5>';
        $div .= '<p class="card-text">'.$date.'</p>';
        $div .= '<p class="card-text"><small class="text-muted">'.$product['place'].'</small></p>';
        $div .= ' </div>';
        $div .= ' </div>';
        $div .= ' </div>';
        $div .= ' </div>';
        $div .= '</a>';

        return $div;
    }


    public static function create_product_unstyled_card($product) {

        $product_url = Tivents_Product_Controller::tivents_get_product_url( $product['short_url'] );
        $date        = Tivents_Product_Controller::tivents_set_product_time( $product );

        if ( $product['cdn_image_key'] != null ) {
            $imageUrl = 'https://cdn.tivents.io/' . $product['cdn_image_key'];
        } else {
            $imageUrl = $product['image_url'];
        }

        $div = $product_url;
        $div .= '<div id="tivents-product-card">';
        $div .= '<div id="tivents-product-image"><img src="'.$imageUrl.'" alt="'.$product['name'].' - Image"></div>';
        $div .= '<div id="tivents-product-name">'.$product['name'].'</div>';
        $div .= '<div id="tivents-product-date">'.$date.'</div>';
        $div .= '<div id="tivents-product-location">'.$product['place'].'</div>';
        $div .= ' </div>';
        $div .= '</a>';

        return $div;
    }
}
