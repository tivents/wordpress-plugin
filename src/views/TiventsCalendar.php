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

class TiventsCalendar {

	static function setCalendarView($results, $divId = null, $shopUrl = null){
        wp_enqueue_style( 'bootstrap_style');
        wp_enqueue_script( 'bootstrap_script');
		ob_start(); ?>
        <button type="button" id="button<?php if ($divId != 'no-id') {echo $divId;};?>" class="btn btn-primary" data-toggle="modal" data-target="#eventModal<?php if ($divId != 'no-id') {echo $divId;};?>" style="display: none">
        </button>
        <!-- Modal -->
        <div class="modal fade" id="eventModal<?php if ($divId != 'no-id') {echo $divId;};?>" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #000000" id="product-title<?php if ($divId != 'no-id') {echo $divId;};?>"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tiv-product-info" id="product-info<?php if ($divId != 'no-id') {echo $divId;};?>"></div><br>
                        <div class="tiv-product-link" id="product-link<?php if ($divId != 'no-id') {echo $divId;};?>"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="close-button" type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    </div>
                </div>
            </div>
        </div>
        <script>

        </script>
		<?php
        $elementId = 'tiv-calendar';
        if ($divId != 'no-id') {
            $elementId = $divId;
        }
        $variabalString = 'var defaultDate = "'.date('Y-m-d H:i:s').'";';
        $variabalString .= 'var products = '.json_encode($results).';';
        $variabalString .= 'var elementId = "'.$elementId.'";';

        wp_enqueue_script( 'tivent-fullcalender', plugins_url('../assets/js/tiv-calendar.js', __FILE__) );
        wp_add_inline_script('tivent-fullcalender', $variabalString, 'before' );

		return ob_get_clean();
	}
}