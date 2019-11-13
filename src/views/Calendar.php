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

	static function setCalendarView($results){
		ob_start(); ?>
        <button type="button" id="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong" style="display: none">
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #000000" id="product-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="product-info"></div><br>
                        <div id="product-link"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="close-button" type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('tiv-calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
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
                    eventClick(arg) {
                        document.getElementById('product-title').innerHTML = '';
                        document.getElementById('product-title').append(arg.event.title);
                        document.getElementById('product-info').innerHTML = '';
                        document.getElementById('product-info').append(arg.event.extendedProps.place);
                        document.getElementById('product-link').innerHTML = '';
                        document.getElementById('product-link').innerHTML = '<a class="btn btn-success pull-right" href="https://tivents.de/'+arg.event.extendedProps.magento_url_key+'">Tickets buchen</a>';
                        var button = document.getElementById('button');
                        button.click()
                    },
                    events: <?php echo json_encode($results) ?>,
                });
                calendar.render();
            });
        </script>
		<?php
		return ob_get_clean();
	}
}