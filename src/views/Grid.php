<?php
/**
 * Grid.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://cocase.eu/
 *
 */


require_once(dirname(plugin_dir_path(__FILE__)) . '/controllers/Products.php');

class Grid {

	static function setGridView ($results)
	{
		$div = '';
		foreach ($results as $result) {
			if ($result['type'] == 2) {
				continue;
			}
			$product_url = Products::getProductUrl($result['magento_instance'], $result['magento_url_key']);
			$date = Products::setProductTime($result);
			$div .=  '<div class="tiv-grid-l tiv-grid-m tiv-grid-s">';
			$div .=  $product_url;
			$div .=  '<div class="tiv-sheet-grid">';
			$div .=  '<img class="tiv-grid-img tiv-sizer" src="'.$result['image_url'].'" />';
			$div .=  '<div class="tiv-grid-text tiv-product-name tiv-font">'.$result['name'].'</div>';
			$div .=  '<div class="tiv-grid-hover">';
			$div .=     '<div class="tiv-grid-info tiv-product-date tiv-font">'.$date.'</div>';
			$div .=     '<div class="tiv-grid-info tiv-product-veneu tiv-font">'.$result['place'].'</div>';
			$div .=  '</div>';
			$div .=  '</div>';
			$div .= '</a>';
			$div .=  '</div>';
		}
		return $div;
	}
}