document.addEventListener(
    'DOMContentLoaded',
    function () {
        let calendarEl = document.getElementById( elementId );
        let calendar   = new FullCalendar.Calendar(
            calendarEl,
            {
                headerToolbar: {
                    end : 'dayGridWeek dayGridMonth today prev,next',
                    start: 'title'
                },
                titleFormat: {
                    month: 'short',
                    year: 'numeric'
                },
                themeSystem: 'standard',
                allDay: true,

                dateClick: function(info) {
                    showEventsForDate(info.dateStr);
                },

                // height: 'auto',
                locale: 'de',
                firstDay: 1,
                selectable: true,
                initialView: 'dayGridMonth',
                initialDate: defaultDate,
                lazyFetching: true,
                events: {
                    url: '/wp-json/tivents/calendar/v1/events/',
                    method: 'get',
                    extraParams: {
                        'groupId': groupId ?? null
                    },
                    failure: function () {
                        return {};
                    },
                },
                height: 'auto',
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit'
                },
                eventDidMount: function (info) {
                    info.el.className = info.el.className + ' tiv-status-' + info.event.extendedProps.warning_level;
                },
                eventClick( info ) {
                    const clickedDate = info.event.startStr.split('T')[0];
                    showEventsForDate(clickedDate);
                },
            }
        );

        calendar.render();

        function showEventsForDate(dateStr) {
            const allEvents = calendar.getEvents();
            const dayEvents = allEvents.filter(e => e.startStr.startsWith(dateStr));

            const formattedDate = new Date(dateStr).toLocaleDateString('de-DE', {
                weekday: 'long',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            let html = '';
            if (dayEvents.length > 0) {
                html = '<ul style="text-align:center; list-style:none; padding:0;">';
                dayEvents.forEach(event => {
                    const time = event.start ? event.start.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '';

                    if(event.extendedProps.warning_level === 3) {
                        html += `<li class="btn btn-danger mr-2 swal2-styled">${time ? time : ''}</li>`;
                    } else {
                        html += `<a href="${event.extendedProps.short_url}" ><li class="btn btn-success tivents-button-success mr-2 swal2-styled">${time ? time : ''}</li></a>`;
                    }

                });
                html += '</ul>';
            } else {
                html = '<em>Keine Events an diesem Tag.</em>';
            }

            const swalWithBootstrapButtons = Swal.mixin(
                {
                    width: '48em',
                    customClass: {
                        confirmButton: 'btn btn-success mr-2',
                        cancelButton: 'btn btn-danger'
                    },

                    confirmButtonColor: '#28D29B',
                    cancelButtonColor: '#D6325B',
                    //buttonsStyling: false
                }
            )

            swalWithBootstrapButtons.fire(
                {
                    title: `Events am ${formattedDate}`,
                    html: html,
                }
            )
        }
    }
);
