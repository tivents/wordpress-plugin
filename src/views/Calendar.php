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

class Calendar {

	static function setCalendarView($results, $divId = null, $shopUrl = null){
		if (get_option('tivents_bootstrap_version') != null) {
			echo '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/'.get_option('tivents_bootstrap_version').'/js/bootstrap.min.js"></script>';
			echo '<link rel="stylesheet" type="text/javascript" href="https://stackpath.bootstrapcdn.com/bootstrap/'.get_option('tivents_bootstrap_version').'/css/bootstrap.min.css'.'">';
		}
		else {
			wp_register_script( 'fullcalendar_bootstrap_script', 'https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '1.1', true );
			wp_register_style('fullcalendar_bootstrap_style', 'https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		}
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
            document.addEventListener('DOMContentLoaded', function() {
				<?php if ( $divId   != 'no-id') { ?>
                var calendarEl = document.getElementById('<?php echo $divId ?>');
				<?php  }
				else { ?>
                var calendarEl = document.getElementById('tiv-calendar');
				<?php  } ?>
                var calendar = new FullCalendar.Calendar(calendarEl,  {
                    plugins: ['bootstrap', 'interaction', 'dayGrid' ],
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    themeSystem: 'standard',
                    locale: 'de',
                    firstDay: 1,
                    selectable: true,

					<?php if (get_option('tivents_default_date') != '') {?>
                    defaultDate: <?php echo '"'.get_option('tivents_default_date').'",';} ?>
                    events: <?php echo json_encode($results) ?>,
                eventTimeFormat: { // like '14:30:00'
                    hour: '2-digit',
                        minute: '2-digit',
                        meridiem: false
                },
                eventRender: function(info) {
                    info.el.className = info.el.className + ' tiv-status-' +info.event.extendedProps.warning_level;
                },
                eventClick(arg) {
                    document.getElementById('product-title<?php if ($divId != 'no-id') {echo $divId;};?>').innerHTML = '';
                    console.log(arg.event.extendedProps.warning_level);
                    if ( arg.event.extendedProps.warning_level == 2) {
                        document.getElementById('product-title<?php if ($divId != 'no-id') {echo $divId;};?>').append(arg.event.title + ' - AUSVERKAUFT');
                    }
                    else {
                        document.getElementById('product-title<?php if ($divId != 'no-id') {echo $divId;};?>').append(arg.event.title);
                    }
                    document.getElementById('product-info<?php if ($divId != 'no-id') {echo $divId;};?>').innerHTML = '';
                    document.getElementById('product-info<?php if ($divId != 'no-id') {echo $divId;};?>').append(arg.event.extendedProps.place);
                    document.getElementById('product-link<?php if ($divId != 'no-id') {echo $divId;};?>').innerHTML = '';

                    if ( arg.event.extendedProps.warning_level != 2) {
                        document.getElementById('product-link<?php if ($divId != 'no-id') {echo $divId;};?>').innerHTML = '<a class="btn btn-success float-right" href="<?php echo $shopUrl ?>/'+arg.event.extendedProps.magento_url_key+'">Tickets buchen</a>';
                    }
                    else {
                        document.getElementById('product-link<?php if ($divId != 'no-id') {echo $divId;};?>').innerHTML = '<a class="btn btn-success float-right disabled" href="<?php echo $shopUrl ?>/'+arg.event.extendedProps.magento_url_key+'">Tickets buchen</a>';
                    }

                    var button = document.getElementById('button<?php if ($divId != 'no-id') {echo $divId;};?>');
                    button.click()
                },

            });
                calendar.render();
            });
        </script>
		<?php
		return ob_get_clean();
	}
}