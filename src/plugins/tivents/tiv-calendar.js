document.addEventListener('DOMContentLoaded', function() {

    let calendarEl = document.getElementById(elementId);
    let calendar = new FullCalendar.Calendar(calendarEl,  {
        headerToolbar: {
            start: 'prev,next today',
            center: '',
            end: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'standard',
        height: 'auto',
        locale: 'de',
        firstDay: 1,
        selectable: true,
        initialDate: defaultDate,
        events: products,
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit'
        },
        eventDidMount: function(info) {
            info.el.className = info.el.className + ' tiv-status-' +info.event.extendedProps.warning_level;
        },
        eventClick(arg) {

            const swalWithBootstrapButtons = Swal.mixin({
                width: '48em',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })


            if ( arg.event.extendedProps.warning_level === 2) {
                swalWithBootstrapButtons.fire({
                    icon: 'error',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Abbrechen',
                    title: arg.event.title + ' - AUSVERKAUFT',
                });
            }
            else {
                swalWithBootstrapButtons.fire({
                    title: arg.event.extendedProps.name,
                    text: arg.event.extendedProps.date,
                    showCancelButton: true,
                    cancelButtonText: 'Abbrechen',
                    confirmButtonText: 'Tickets buchen',
                    footer: arg.event.extendedProps.place
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =  arg.event.extendedProps.short_url;
                    }
                });
            }
        },
    });

    calendar.render();
});