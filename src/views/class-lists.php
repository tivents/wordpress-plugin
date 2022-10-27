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


class TivProFeed_View_Lists {

    static function setFooter() {
        $div = '<div id="tiv-footer" class="row text-right">';
        $div .= '<p class="text-right"><small><figcaption class="blockquote-footer">
        '.__('build with', 'tivents_products_feed').' <a href="https://tiv.li/GmOz" target="_blank" style="color: var(--tiv-prime-color)">TIVENTS WP Plugin</a> v<cite title="Source Title">'.constant('TIVENTPRO_CURRENT_VERSION').'</cite>
  </figcaption>
</small></p>';

        $div .= '</div>';
        return $div;
    }


	static function setListWithImages($results) {
        $div = '';
		foreach ($results as $result) {
			if ($result['type'] == 2) {
				continue;
			}
			$product_url = TivProFeed_Controller_Products::getProductUrl($result['magento_instance'], $result['magento_url_key'], $result['short_url']);
			$date = TivProFeed_Controller_Products::setProductTime($result);

            if($result['cdn_image_key'] != null) {
                $imageUrl = 'https://d1jakwcoew848r.cloudfront.net/filters:autojpg()/'.$result['cdn_image_key'];
            }
            else {
                $imageUrl = $result['image_url'];
            }


			$div .= '<div class="tiv-product-l tiv-product-m tiv-product-s">';
			$div .= '<div class="tiv-sheet tiv-border">';
			$div .= $product_url;
			$div .= '<div class="tiv-sheet-inner">';
			$div .= '<div class="tiv-sheet-left">';
			$div .= '<div class="tiv-product-name tiv-font">'.$result['name'].'</div>';
			$div .= '<div class="tiv-product-date tiv-font">'.$date.'</div>';
			$div .= '<div class="tiv-product-veneu tiv-font">'.$result['place'].'</div>';
			$div .= '</div>';
			$div .=  '<div class="tiv-sheet-right">';
			$div .=  '<img class="tiv-product-img" src="'.$imageUrl.'" />';
			$div .=  '</div>';
			$div .=  '</div>';
			$div .= '</a>';
			$div .=  '</div>';
			$div .=  '</div>';
		}

		return $div;
	}


    static function setListWithoutImages($results) {
        $div = '';
        foreach ($results as $result) {
            if ($result['type'] == 2) {
                continue;
            }
            $product_url = TivProFeed_Controller_Products::getProductUrl($result['magento_instance'], $result['magento_url_key'], $result['short_url']);
            $date = TivProFeed_Controller_Products::setProductTime($result);
            $div .= '<div class="tiv-product-l tiv-product-m tiv-product-s">';
            $div .= '<div class="tiv-sheet tiv-border">';
            $div .= $product_url;
            $div .= '<div class="tiv-sheet-inner">';
            $div .= '<div class="tiv-sheet-left">';
            $div .= '<div class="tiv-product-name tiv-font">'.$result['name'].'</div>';
            $div .= '<div class="tiv-product-date tiv-font">'.$date.'</div>';
            $div .= '<div class="tiv-product-veneu tiv-font">'.$result['place'].'</div>';
            $div .= '</div>';
            $div .=  '</div>';
            $div .= '</a>';
            $div .=  '</div>';
            $div .=  '</div>';
        }

        return $div;
    }
}