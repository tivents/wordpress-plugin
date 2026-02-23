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

function initStyles($products, $divId = null, $groupId = null) {
    $elementId = 'tiv-calendar';
    if ( $divId != 'no-id' ) {
        $elementId = $divId;
    }

    if ( get_option( 'tivents_default_date' ) != null && get_option( 'tivents_default_date' ) > date( 'Y-m-d' ) ) {
        $variableString = 'let defaultDate = "' . date( 'Y-m-d', strtotime( get_option( 'tivents_default_date' ) ) ) . '";';
    } else {
        $variableString = 'let defaultDate = "' . date( 'Y-m-d' ) . '";';
    }

    if($groupId) {
        $variableString .= 'let groupId = "' . $groupId . '";';
    } else {
        $variableString .= 'let groupId = "group";';
    }

    $variableString .= 'let products = ' . json_encode( $products ) . ';';
    $variableString .= 'let elementId = "' . $elementId . '";';

    if(!wp_script_is( 'tiv-calendar-js', 'enqueued' )) {
        wp_register_script( 'tiv-calendar-js', plugins_url( '/../assets/tivents/tiv-calendar.js', __FILE__ ) );
        wp_enqueue_script('tiv-calendar-js');
        wp_add_inline_script( 'tiv-calendar-js', $variableString, 'before' );
    }
}

class Tivents_Calendar_View {

	static function tivents_set_calendar_view( $results, $divId = null, $groupId = null ) {
        initStyles( $results, $divId, $groupId );
		ob_start(); ?>
		<button type="button" id="button <?php if ( $divId != 'no-id' ) { echo esc_html( $divId );} ?>" class="btn btn-primary" data-toggle="modal" data-target="#eventModal<?php if ( $divId != 'no-id' ) { echo esc_html( $divId );} ?>" style="display: none">
		</button>
		<!-- Modal -->
		<div class="modal fade" id="eventModal<?php if ( $divId != 'no-id' ) {echo esc_html( $divId );} ?>" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" style="color: #000000" id="product-title<?php if ( $divId != 'no-id' ) { echo esc_html( $divId );} ?>"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="tiv-product-info" id="product-info <?php if ( $divId != 'no-id' ) { echo esc_html( $divId ); } ?>"></div><br>
						<div class="tiv-product-link" id="product-link <?php if ( $divId != 'no-id' ) { echo esc_html( $divId ); } ?>"></div>
					</div>
					<div class="modal-footer">
						<button id="close-button" type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
					</div>
				</div>
			</div>
		</div>

		<?php return ob_get_clean();
	}
}