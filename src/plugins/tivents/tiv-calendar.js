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
				eventClick( arg ) {
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

				if ( arg.event.extendedProps.warning_level === 3) {
					swalWithBootstrapButtons.fire(
						{
							icon: 'error',
							showConfirmButton: false,
							showCancelButton: true,
							cancelButtonText: 'Abbrechen',
							title: arg.event.extendedProps.name + ' - AUSVERKAUFT',
							text:'Das Event ist bereits ausverkauft.',
						}
					);
				} else {
					console.log( arg.event );
					swalWithBootstrapButtons.fire(
						{
							title: arg.event.extendedProps.name,
							html: arg.event.extendedProps.printDate,
							showCancelButton: true,
							cancelButtonText: 'Abbrechen',
							confirmButtonText: 'Tickets buchen',
							footer: arg.event.extendedProps.place
						}
					).then(
						(result) => {
							if (result.isConfirmed) {
								window.location.href = arg.event.extendedProps.short_url;
							}
						}
					);
				}
				},
			}
		);

		calendar.render();
	}
);