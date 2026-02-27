<?php
/**
 * Grid.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://tivents.info/
 */


class Tivents_Grid_View {

	static function tivents_set_grid_view( $results ) {
		$div = '';

        foreach ( $results['data'] as $result ) {

			if ( $result['type'] == 2 ) {
				continue;
			}

			$div .= '<div class="tiv-grid-l tiv-grid-m tiv-grid-s">';
			$div .= Tivents_Product_Controller::tivents_get_product_url( $result['short_url'] );
			$div .= '<div class="tiv-sheet-grid">';
			$div .= '<img class="tiv-grid-img tiv-sizer" src="' . Tivents_Product_Controller::set_image_url( $result ) . '" />';
            $div .= '<div class="tiv-grid-text tiv-product-name tiv-font">' . $result['name'] . '</div>';
			$div .= '<div class="tiv-grid-hover">';
			$div .= '<div class="tiv-grid-info tiv-product-date tiv-font">' . Tivents_Product_Controller::tivents_set_product_time( $result ) . '</div>';
			$div .= '<div class="tiv-grid-info tiv-product-veneu tiv-font">' . $result['place'] . '</div>';
			$div .= '</div>';
			$div .= '</div>';
			$div .= '</a>';
			$div .= '</div>';
		}

		return $div;
	}
}
