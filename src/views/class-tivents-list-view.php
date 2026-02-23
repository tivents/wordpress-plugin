<?php
/**
 * Calendar.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://tivents.info/
 */


class Tivents_List_View {


	static function setListWithImages( $results ) {
		$div = '';
		foreach ( $results['data'] as $result ) {

			if ( $result['type'] == 2 ) {
				continue;
			}

            $product_url = Tivents_Product_Controller::tivents_get_product_url( $result['short_url'] );
            $date        = Tivents_Product_Controller::tivents_set_product_time( $result );

			if ( $result['cdn_image_key'] != null ) {
				$imageUrl = 'https://cdn.tivents.io/' . $result['cdn_image_key'];
			} else {
				$imageUrl = $result['image_url'];
			}

			$div .= '<div class="tiv-product-l tiv-product-m tiv-product-s">';
			$div .= '<div class="tiv-sheet tiv-border">';
			$div .= $product_url;
			$div .= '<div class="tiv-sheet-inner">';
			$div .= '<div class="tiv-sheet-left">';
			$div .= '<div class="tiv-product-name tiv-font">' . $result['name'] . '</div>';
			$div .= '<div class="tiv-product-date tiv-font">' . $date . '</div>';
			$div .= '<div class="tiv-product-veneu tiv-font">' . $result['place'] . '</div>';
			$div .= '</div>';
			$div .= '<div class="tiv-sheet-right">';
			$div .= '<img class="tiv-product-img" src="' . $imageUrl . '" />';
			$div .= '</div>';
			$div .= '</div>';
			$div .= '</a>';
			$div .= '</div>';
			$div .= '</div>';
		}

		return $div;
	}


	static function setListWithoutImages( $results ) {
		$div = '';
		foreach ( $results as $result ) {
			if ( $result['type'] == 2 ) {
				continue;
			}
			$product_url = Tivents_Product_Controller::tivents_get_product_url( $result['short_url'] );
			$date        = Tivents_Product_Controller::tivents_set_product_time( $result );
			$div        .= '<div class="tiv-product-l tiv-product-m tiv-product-s">';
			$div        .= '<div class="tiv-sheet tiv-border">';
			$div        .= $product_url;
			$div        .= '<div class="tiv-sheet-inner">';
			$div        .= '<div class="tiv-sheet-left">';
			$div        .= '<div class="tiv-product-name tiv-font">' . $result['name'] . '</div>';
			$div        .= '<div class="tiv-product-date tiv-font">' . $date . '</div>';
			$div        .= '<div class="tiv-product-veneu tiv-font">' . $result['place'] . '</div>';
			$div        .= '</div>';
			$div        .= '</div>';
			$div        .= '</a>';
			$div        .= '</div>';
			$div        .= '</div>';
		}

		return $div;
	}

    static function setColumnList( $results, $columnQuantity = 2) {
        $div = '<div class="tiv-row row">';

        foreach ( $results['data'] as $result ) {

            if ( $result['type'] == 2 ) {
                continue;
            }

            $div .= match($columnQuantity) {
                1, '1' => '<div class="col-sm-12 p-2">',
                3, '3' => '<div class="col-sm-4 p-2">',
                4, '4' => '<div class="col-sm-3 p-2">',
                default => '<div class="col-sm-6 p-2">',
            };

            $div .= Tivents_Product_Controller::create_product_card( $result );

            $div .= '</div>';
        }

        $div .= '</div>';

        return $div;
    }

    static function setUnstyledList( $results) {
        $div = '<div id="tivents-product-list">';

        foreach ( $results['data'] as $result ) {

            if ( $result['type'] == 2 ) {
                continue;
            }

            $div .= '<div id="tivents-product-list-item">';
            $div .= Tivents_Product_Controller::create_product_unstyled_card( $result );
            $div .= '</div>';
        }

        $div .= '</div>';

        return $div;
    }
}
