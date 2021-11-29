document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById(elementId);
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
        defaultDate: defaultDate,
        events: products,
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        eventRender: function(info) {
            info.el.className = info.el.className + ' tiv-status-' +info.event.extendedProps.warning_level;
        },
        eventClick(arg) {
            document.getElementById(elementId).innerHTML = '';
            if ( arg.event.extendedProps.warning_level == 2) {
                document.getElementById(elementId).append(arg.event.title + ' - AUSVERKAUFT');
            }
            else {
                document.getElementById(elementId).append(arg.event.title);
            }
            document.getElementById(elementId).innerHTML = '';
            document.getElementById(elementId).append(arg.event.extendedProps.place);
            document.getElementById(elementId).innerHTML = '';

            if ( arg.event.extendedProps.warning_level != 2) {
                document.getElementById(elementId).innerHTML = '<a class="btn btn-success float-right" href="'+shopUrl+'/'+arg.event.extendedProps.magento_url_key+'">Tickets buchen</a>';
            }
            else {
                document.getElementById(elementId).innerHTML = '<a class="btn btn-success float-right disabled" href="'+shopUrl+'/'+arg.event.extendedProps.magento_url_key+'">Tickets buchen</a>';
            }

            var button = document.getElementById('button'+elementId);
            button.click()
        },
    });
    calendar.render();
});