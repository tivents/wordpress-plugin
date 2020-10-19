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

class Products {

	static function getProductUrl($instance, $url) {
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

}