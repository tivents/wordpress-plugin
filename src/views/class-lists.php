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


	static function setListWithImages($results) {

// include file

		$div = '';
		foreach ($results as $result) {
			if ($result['type'] == 2) {
				continue;
			}
			$product_url = TivProFeed_Controller_Products::getProductUrl($result['magento_instance'], $result['magento_url_key']);
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
			$div .=  '<div class="tiv-sheet-right">';
			$div .=  '<img class="tiv-product-img" src="'.$result['image_url'].'" />';
			$div .=  '</div>';
			$div .=  '</div>';
			$div .= '</a>';
			$div .=  '</div>';
			$div .=  '</div>';
		}

		return $div;
	}

}