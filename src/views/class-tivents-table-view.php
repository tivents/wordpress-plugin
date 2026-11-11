<?php
/**
 * Table.php Class Doc Comment
 *
 * @category Class
 * @package  wordpress-plugin
 * @author   whelwig
 * @license  Restricted
 * @link     https://tivents.info/
 */

class Tivents_Table_View {

	/**
	 * Renders the products as a table.
	 *
	 * @param array $results    API result containing the products in 'data'.
	 * @param bool  $showImages Whether a thumbnail column is rendered.
	 * @param bool  $showDate   Whether the date column is rendered.
	 * @param bool  $showPlace  Whether the place column is rendered.
	 * @return string
	 */
	public static function tivents_set_table_view( $results, $showImages = false, $showDate = true, $showPlace = true ) {
		$rows = '';

		foreach ( $results['data'] ?? [] as $result ) {

			$url = esc_url( $result['short_url'] . '?utm=tivents-wordpress-plugin' );

			$rows .= '<tr class="tiv-table-row">';

			if ( $showImages ) {
				$imageUrl  = Tivents_Product_Controller::set_image_url( $result );
				$imageUrl .= ( str_contains( (string) $imageUrl, '?' ) ? '&' : '?' ) . 'width=160&height=120';
				$rows     .= '<td class="tiv-table-image"><img src="' . esc_url( $imageUrl ) . '" alt="' . esc_attr( $result['name'] ) . '" /></td>';
			}

			$rows .= '<td class="tiv-table-name tiv-product-name"><a href="' . $url . '">' . esc_html( $result['name'] ) . '</a></td>';

			if ( $showDate ) {
				$rows .= '<td class="tiv-table-date tiv-product-date">' . esc_html( Tivents_Product_Controller::tivents_set_product_time( $result ) ) . '</td>';
			}

			if ( $showPlace ) {
				$rows .= '<td class="tiv-table-place tiv-product-veneu">' . esc_html( $result['place'] ?? '' ) . '</td>';
			}

			// warning_level: 1 = available, 2 = few left, 3 = sold out.
			$warningLevel = (int) ( $result['warning_level'] ?? 1 );

			$rows .= '<td class="tiv-table-action">';
			if ( $warningLevel === 3 ) {
				$rows .= '<span class="tiv-table-button tiv-table-button-disabled" aria-disabled="true">' . esc_html__( 'Ausverkauft', 'tivents_products_feed' ) . '</span>';
			} else {
				$rows .= '<a class="tiv-table-button" href="' . $url . '">' . esc_html__( 'Jetzt buchen', 'tivents_products_feed' ) . '</a>';
				if ( $warningLevel === 2 ) {
					$rows .= '<div class="tiv-table-hint">' . esc_html__( 'Nur noch wenige verfügbar', 'tivents_products_feed' ) . '</div>';
				}
			}
			$rows .= '</td>';
			$rows .= '</tr>';
		}

		$columns = 2 + (int) $showImages + (int) $showDate + (int) $showPlace;

		if ( $rows === '' ) {
			$rows = '<tr class="tiv-table-row tiv-table-empty"><td colspan="' . $columns . '">' . esc_html__( 'Zur Zeit gibt es keine Produkte', 'tivents_products_feed' ) . '</td></tr>';
		}

		$div  = '<div class="tiv-table-wrapper">';
		$div .= '<table class="tiv-table">';
		$div .= '<thead><tr>';

		if ( $showImages ) {
			$div .= '<th class="tiv-table-image"></th>';
		}

		$div .= '<th>' . esc_html__( 'Name', 'tivents_products_feed' ) . '</th>';

		if ( $showDate ) {
			$div .= '<th>' . esc_html__( 'Datum', 'tivents_products_feed' ) . '</th>';
		}

		if ( $showPlace ) {
			$div .= '<th>' . esc_html__( 'Ort', 'tivents_products_feed' ) . '</th>';
		}

		$div .= '<th class="tiv-table-action"></th>';
		$div .= '</tr></thead>';
		$div .= '<tbody>' . $rows . '</tbody>';
		$div .= '</table>';
		$div .= '</div>';

		return $div;
	}
}
