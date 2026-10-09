document.addEventListener(
    'DOMContentLoaded',
    function () {
        const calendarEl = document.getElementById(elementId);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                end : 'dayGridWeek dayGridMonth multiMonthYear today prev,next',
                start: 'title'
            },

            locale: 'de',

            titleFormat: {
                month: 'short',
                year: 'numeric'
            },

            initialView: "dayGridMonth",
            initialDate: defaultDate,

            dayMaxEventRows: 5,

            eventDisplay: 'block',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit'
            },

            moreLinkClick: function( info ) {
                console.log(info.date);
                const clickedDate = info.date.getFullYear()+'-'+info.date.getMonth()+'-'+info.date.getDate();
                showEventsForDate(clickedDate);
            },

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
            dateClick: function(info) {
                showEventsForDate(info.dateStr);
            },

            eventDidMount: function (info) {
                info.el.className = info.el.className + ' tiv-status-' + info.event.extendedProps.warning_level;
            },

            eventClick( info ) {
                const clickedDate = info.event.startStr.split('T')[0];
                showEventsForDate(clickedDate);
            },
        });
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
                        html += `<li class="tivents-calender-modal-list btn btn-danger m-2 swal2-styled">${time ? time : ''}</li>`;
                    } else {
                        html += `<a href="${event.extendedProps.short_url}" ><li class="tivents-calender-modal-list btn btn-success tivents-button-success m-2 swal2-styled">${time ? time : ''}</li></a>`;
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
                        confirmButton: 'btn btn-success m-2',
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
