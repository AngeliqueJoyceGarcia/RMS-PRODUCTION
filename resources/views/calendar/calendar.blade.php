@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')

<link rel="stylesheet" href="{{ asset('css/calendar.css') }}">

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<div id='calendar'></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,multiMonthYear,weekViewButton'
            },
            // Define a custom button named "weekViewButton"
            customButtons: {
                weekViewButton: {
                    text: 'week', // Displayed text on the button
                    click: function() {
                        // Redirect to the 'calendar.list' route
                        window.location.href = '{{ route("calendar.list") }}';
                    }
                }
            },
            events: [
                @foreach($bookings as $booking)
                    @if($booking->total_walkins > 0)
                    {
                        title: 'Walk-ins: {{ $booking->total_walkins }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'walkin-event',
                        backgroundColor: '{{ $WalkinColor }}', // Use the color for Walk-ins
                        borderColor: '{{ $WalkinColor }}', // Use the color for Walk-ins
                        children_qty: {{ $booking->children_qty_walkin }},
                        adult_qty: {{ $booking->adult_qty_walkin }},
                        senior_qty: {{ $booking->senior_qty_walkin }},
                    },
                    @endif
                    @if($booking->total_prebookInhouse > 0)
                    {
                        title: 'Pending Pre-book Inhouse: {{ $booking->total_prebookInhouse }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'prebook-inhouse-event',
                        backgroundColor: '{{ $pendingPrebookInhouseColor }}', // Use the color for Pre-book Inhouse
                        borderColor: '{{ $pendingPrebookInhouseColor }}', // Use the color for Pre-book Inhouse
                        children_qty: {{ $booking->children_qty_prebookInhouse }},
                        adult_qty: {{ $booking->adult_qty_prebookInhouse }},
                        senior_qty: {{ $booking->senior_qty_prebookInhouse }},
                    },
                    @endif
                    @if($booking->total_checkinInhouse > 0)
                    {
                        title: 'Check-in Pre-book Inhouse: {{ $booking->total_checkinInhouse }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'checkin-inhouse-event',
                        backgroundColor: '{{ $checkinPrebookInhouseColor }}', // Use the color for Check-in Inhouse
                        borderColor: '{{ $checkinPrebookInhouseColor }}', // Use the color for Check-in Inhouse
                        children_qty: {{ $booking->children_qty_checkinInhouse }},
                        adult_qty: {{ $booking->adult_qty_checkinInhouse }},
                        senior_qty: {{ $booking->senior_qty_checkinInhouse }},
                    },
                    @endif
                    @if($booking->total_prebookDayTour > 0)
                    {
                        title: 'Pending Pre-book DayTour: {{ $booking->total_prebookDayTour }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'prebook-daytour-event',
                        backgroundColor: '{{ $pendingPrebookDayTourColor }}', // Use the color for Pre-book DayTour
                        borderColor: '{{ $pendingPrebookDayTourColor }}', // Use the color for Pre-book DayTour
                        children_qty: {{ $booking->children_qty_prebookDayTour }},
                        adult_qty: {{ $booking->adult_qty_prebookDayTour }},
                        senior_qty: {{ $booking->senior_qty_prebookDayTour }},
                    },
                    @endif
                    @if($booking->total_checkinDayTour > 0)
                    {
                        title: 'Check-in Pre-book DayTour: {{ $booking->total_checkinDayTour }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'checkin-daytour-event',
                        backgroundColor: '{{ $checkinPrebookDayTourColor }}', // Use the color for Check-in DayTour
                        borderColor: '{{ $checkinPrebookDayTourColor }}', // Use the color for Check-in DayTour
                        children_qty: {{ $booking->children_qty_checkinDayTour }},
                        adult_qty: {{ $booking->adult_qty_checkinDayTour }},
                        senior_qty: {{ $booking->senior_qty_checkinDayTour }},
                    },
                    @endif
                    @if($booking->total_pendingopenbook > 0)
                    {
                        title: 'Pending Open book: {{ $booking->total_pendingopenbook }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'pending-openbook-event',
                        backgroundColor: '{{ $pendingOpenBookColor }}', // Use the color for Pending Open book
                        borderColor: '{{ $pendingOpenBookColor }}', // Use the color for Pending Open book
                        children_qty: {{ $booking->children_qty_pendingopenbook }},
                        adult_qty: {{ $booking->adult_qty_pendingopenbook }},
                        senior_qty: {{ $booking->senior_qty_pendingopenbook }},
                    },
                    @endif
                    @if($booking->total_checkinopenbook > 0)
                    {
                        title: 'Check-in Open book: {{ $booking->total_checkinopenbook }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'checkin-openbook-event',
                        backgroundColor: '{{ $checkinOpenBookColor }}', // Use the color for Check-in Open book
                        borderColor: '{{ $checkinOpenBookColor }}', // Use the color for Check-in Open book
                        children_qty: {{ $booking->children_qty_checkinopenbook }},
                        adult_qty: {{ $booking->adult_qty_checkinopenbook }},
                        senior_qty: {{ $booking->senior_qty_checkinopenbook }},
                    },
                    @endif
                    @if($booking->total_canceled > 0)
                    {
                        title: 'Canceled book: {{ $booking->total_canceled }}',
                        start: '{{ $booking->date }}',
                        rendering: 'background',
                        classNames: 'checkin-openbook-event',
                        backgroundColor: '{{ $checkinOpenBookColor }}', // Use the color for Check-in Open book
                        borderColor: '{{ $checkinOpenBookColor }}', // Use the color for Check-in Open book
                        children_qty: {{ $booking->children_qty_canceled }},
                        adult_qty: {{ $booking->adult_qty_canceled }},
                        senior_qty: {{ $booking->senior_qty_canceled }},
                    },
                    @endif
                @endforeach
            ],
            eventContent: function(arg) {
                var contentEl = document.createElement('div');
                if (calendar.view.type === 'multiMonthYear') {
                    // For multiMonthYear view, display only the number
                    contentEl.innerText = arg.event.title.split(': ')[1];
                } else {
                    // For dayGridMonth view, display the full details
                    contentEl.innerText = arg.event.title;
                }
                contentEl.style.backgroundColor = arg.event.backgroundColor;
                contentEl.className = 'custom-event-content'; // Add a custom class for styling
                return { domNodes: [contentEl] };
            },
            // eventMouseEnter: function(info) {
            //     // Create a div element for the popup
            //     var popupEl = document.createElement('div');
            //     popupEl.className = 'custom-popup';

            //    // Access quantities directly from the event being hovered over
            //     var childrenQty = info.event.extendedProps.children_qty_walkin || 0;
            //     var adultQty = info.event.extendedProps.adult_qty_walkin || 0;
            //     var seniorQty = info.event.extendedProps.senior_qty_walkin || 0;

            //     // Create content for the popup based on the event type
            //     var popupContent = '';
            //     if (info.event.classNames.includes('walkin-event')) {
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;

            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //         console.log(info.event) 

            //     } else if (info.event.classNames.includes('prebook-inhouse-event')) {
            //         // Access quantities for Pre-book Inhouse events
            //         childrenQty = info.event.extendedProps.children_qty_prebookInhouse || 0;
            //         adultQty = info.event.extendedProps.adult_qty_prebookInhouse || 0;
            //         seniorQty = info.event.extendedProps.senior_qty_prebookInhouse || 0;
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;
            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //     } else if (info.event.classNames.includes('checkin-inhouse-event')) {
            //         // Access quantities for Check-in Inhouse events
            //         childrenQty = info.event.extendedProps.children_qty_checkinInhouse || 0;
            //         adultQty = info.event.extendedProps.adult_qty_checkinInhouse || 0;
            //         seniorQty = info.event.extendedProps.senior_qty_checkinInhouse || 0;
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;
            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //     } else if (info.event.classNames.includes('prebook-daytour-event')) {
            //         // Access quantities for Pre-book DayTour events
            //         childrenQty = info.event.extendedProps.children_qty_prebookDayTour || 0;
            //         adultQty = info.event.extendedProps.adult_qty_prebookDayTour || 0;
            //         seniorQty = info.event.extendedProps.senior_qty_prebookDayTour || 0;
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;
            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //     } else if (info.event.classNames.includes('checkin-daytour-event')) {
            //         // Access quantities for Check-in DayTour events
            //         childrenQty = info.event.extendedProps.children_qty_checkinDayTour || 0;
            //         adultQty = info.event.extendedProps.adult_qty_checkinDayTour || 0;
            //         seniorQty = info.event.extendedProps.senior_qty_checkinDayTour || 0;
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;
            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //     } else if (info.event.classNames.includes('pending-openbook-event')) {
            //         // Access quantities for Pending Openbook events
            //         childrenQty = info.event.extendedProps.children_qty_pendingopenbook || 0;
            //         adultQty = info.event.extendedProps.adult_qty_pendingopenbook || 0;
            //         seniorQty = info.event.extendedProps.senior_qty_pendingopenbook || 0;
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;
            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //     } else if (info.event.classNames.includes('checkin-openbook-event')) {
            //         // Access quantities for Check-in Openbook events
            //         childrenQty = info.event.extendedProps.children_qty_checkinopenbook || 0;
            //         adultQty = info.event.extendedProps.adult_qty_checkinopenbook || 0;
            //         seniorQty = info.event.extendedProps.senior_qty_checkinopenbook || 0;
            //         popupContent = 'Children Qty: ' + childrenQty + '<br>' +
            //             'Adult Qty: ' + adultQty + '<br>' +
            //             'Senior Qty: ' + seniorQty;
            //         console.log('childrenQty:', childrenQty);
            //         console.log('adultQty:', adultQty);
            //         console.log('seniorQty:', seniorQty);
            //     }


            //     popupEl.innerHTML = popupContent;

            //     // Position the popup near the event
            //     popupEl.style.position = 'absolute';
            //     popupEl.style.left = info.jsEvent.clientX + 'px';
            //     popupEl.style.top = info.jsEvent.clientY + 'px';

            //     // Append the popup to the document body
            //     document.body.appendChild(popupEl);

            //     // Store a reference to the popup for later removal
            //     info.el._popup = popupEl;
            // },

            // eventMouseLeave: function(info) {
            //     // Remove the popup when the mouse leaves the event
            //     if (info.el._popup) {
            //         info.el._popup.remove();
            //     }
            // }

        });

        calendar.render();
    });


</script>


@endsection
