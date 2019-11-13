<?php
/**
 * Footer.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://cocase.eu/
 *
 */

/**
 * Class Footer
 */

/**
 * Add controllers
 */



class Footer {


	static function setFooter()
	{
		$plugin_data = get_file_data(dirname(plugin_dir_path(__FILE__)) . '/tivents-product-feed.php');
		$div = '<div class="tiv-plugin-info tiv-font center">Mit <i class="fa fa-heart"></i> bereit gestellt von tivents <br /></r><small>Version: '.$plugin_data['Version'].'</small></div>';
		return $div;
	}
}