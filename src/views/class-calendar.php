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

class TivProFeed_View_Calendar {

	static function setCalendarView($results, $divId = null){
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

		<?php
        $elementId = 'tiv-calendar';
        if ($divId != 'no-id') {
            $elementId = $divId;
        }


        if(get_option( 'tivents_default_date' ) != null && get_option( 'tivents_default_date' ) > date('Y-m-d')) {
            $variableString = 'let defaultDate = "'.date('Y-m-d', strtotime(get_option( 'tivents_default_date' ) )).'";';
        }
        else {
            $variableString = 'let defaultDate = "'.date('Y-m-d').'";';
        }

        $variableString .= 'var products = '.json_encode($results).';';
        $variableString .= 'var elementId = "'.$elementId.'";';

        wp_add_inline_script('tiv-calender-js', $variableString, 'before' );

		return ob_get_clean();
	}
}