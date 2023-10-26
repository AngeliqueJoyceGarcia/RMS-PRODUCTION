@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')

<link rel="stylesheet" href="{{ asset('css/calendarList.css') }}">

<script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.8/index.global.min.js'></script>
<script src='fullcalendar/dist/index.global.js'></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div id='calendar'></div>

<!-- Modal for Booking Details -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bookingDetailsContainer">
                    <form id="bookingDetailsForm">
                        <div class="user-details">
                            <div class="form-group">
                                <label class="userdetails" for="reservationType">Reservation Type:</label>
                                <input type="text" id="reservationType" name="reservationType" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="rateName">Rate Name:</label>
                                <input type="text" id="rateName" name="rateName" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="name">Name:</label>
                                <input type="text" id="name" name="name" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="contactNum">Contact Number:</label>
                                <input type="text" id="contactNum" name="contactNum" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="email">Email:</label>
                                <input type="text" id="email" name="email" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="bday">Birthday:</label>
                                <input type="text" id="bday" name="bday" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="total_companion">Total Companions:</label>
                                <input type="text" id="total_companion" name="total_companion" readonly>
                            </div>
                       
                            <div class="form-group">
                                <label class="userdetails" for="childrenQty">Children Qty:</label>
                                <input type="text" id="childrenQty" name="childrenQty" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="adultQty">Adult Qty:</label>
                                <input type="text" id="adultQty" name="adultQty" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="seniorQty">Senior Qty:</label>
                                <input type="text" id="seniorQty" name="seniorQty" readonly><br>
                            </div>
                         
                            
                            <div class="form-group">
                                <label class="userdetails" for="status">Status:</label>
                                <input type="text" id="status" name="status" readonly><br>
                            </div>
                            <div class="form-group">
                                <label class="userdetails" for="check_in">Check-in:</label>
                                <input type="text" id="check_in" name="check_in" readonly><br>
                            </div>
                     
                        <div class="form-group">
                            <label class="userdetails" for="total_amount">Total Amount:</label>
                            <input type="text" id="total_amount" name="total_amount" readonly>
                        </div>
                        <div class="form-group">
                            <label class="userdetails" for="total_amount_paid">Total Amount Paid:</label>
                            <input type="text" id="total_amount_paid" name="total_amount_paid" readonly>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var pendingInhousepreBookings = @json($pendingInhousepreBookings);
    var checkinInhousepreBookings = @json($checkinInhousepreBookings);
    var pendingDayTourpreBookings = @json($pendingDayTourpreBookings);
    var checkinDayTourpreBookings = @json($checkinDayTourpreBookings);
    var walkInBookings = @json($walkInBookings);
    var pendingOpenBookings = @json($pendingOpenBookings);
    var checkinOpenBookings = @json($checkinOpenBookings);


    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        allDaySlot: true,
        slotDuration: '24:00:00',
        slotLabelContent: '',
        allDayText: 'Guests',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dgm,weekViewButton'
        },
        customButtons: {
            weekViewButton: {
                text: 'week',
                click: function() {
                    window.location.href = '{{ route("calendar.list") }}';
                }
            },
            dgm: {
                text: 'month',
                click: function() {
                    window.location.href = '{{ route("calendar.read") }}';
                }
            },
        },
        events: [
            // pendingInhousepreBookings
            @foreach ($pendingInhousepreBookings as $pendingInhousepreBooking)
            {
                title: '{{ $pendingInhousepreBooking->name }}',
                allDay: true,
                start: '{{ $pendingInhousepreBooking->check_in }}',
                end: '{{ $pendingInhousepreBooking->check_out }}',
                color: '{{ $pendingPrebookInhouseColor }}',
                bookingDetails: @json($pendingInhousepreBooking),
            },
            @endforeach

            // checkinInhousepreBookings
            @foreach ($checkinInhousepreBookings as $checkinInhousepreBooking)
            {
                title: '{{ $checkinInhousepreBooking->name }}',
                allDay: true,
                start: '{{ $checkinInhousepreBooking->check_in }}',
                end: '{{ $checkinInhousepreBooking->check_out }}',
                color: '{{ $checkinPrebookInhouseColor }}',
                bookingDetails: @json($checkinInhousepreBooking),
            },
            @endforeach

            // pendingDayTourpreBookings
            @foreach ($pendingDayTourpreBookings as $pendingDayTourpreBooking)
            {
                title: '{{ $pendingDayTourpreBooking->name }}',
                allDay: true,
                start: '{{ $pendingDayTourpreBooking->check_in }}',
                end: '{{ $pendingDayTourpreBooking->check_out }}',
                color: '{{ $pendingPrebookDayTourColor }}',
                bookingDetails: @json($pendingDayTourpreBooking),
            },
            @endforeach

            // checkinDayTourpreBookings
            @foreach ($checkinDayTourpreBookings as $checkinDayTourpreBooking)
            {
                title: '{{ $checkinDayTourpreBooking->name }}',
                allDay: true,
                start: '{{ $checkinDayTourpreBooking->check_in }}',
                end: '{{ $checkinDayTourpreBooking->check_out }}',
                color: '{{ $checkinPrebookDayTourColor }}',
                bookingDetails: @json($checkinDayTourpreBooking),
            },
            @endforeach

            // walkInBookings
            @foreach ($walkInBookings as $walkInBooking)
            {
                title: '{{ $walkInBooking->name }}',
                allDay: true,
                start: '{{ $walkInBooking->check_in }}',
                end: '{{ $walkInBooking->check_out }}',
                color: '{{ $WalkinColor }}',
                bookingDetails: @json($walkInBooking),
            },
            @endforeach

            // pendingOpenBookings
            @foreach ($pendingOpenBookings as $pendingOpenBooking)
            {
                title: '{{ $pendingOpenBooking->name }}',
                allDay: true,
                start: '{{ $pendingOpenBooking->check_in }}',
                end: '{{ $pendingOpenBooking->check_out }}',
                color: '{{ $pendingOpenBookColor }}',
                bookingDetails: @json($pendingOpenBooking),
            },
            @endforeach

            // checkinOpenBookings
            @foreach ($checkinOpenBookings as $checkinOpenBooking)
            {
                title: '{{ $checkinOpenBooking->name }}',
                allDay: true,
                start: '{{ $checkinOpenBooking->check_in }}',
                end: '{{ $checkinOpenBooking->check_out }}',
                color: '{{ $checkinOpenBookColor }}',
                bookingDetails: @json($pendingDayTourpreBookings),
            },
            @endforeach

            // canceledBookings
            @foreach ($canceledBookings as $canceledBooking)
            {
                title: '{{ $canceledBooking->name }}',
                allDay: true,
                start: '{{ $canceledBooking->check_in }}',
                end: '{{ $canceledBooking->check_out }}',
                color: '{{ $canceledBookColor }}',
                bookingDetails: @json($canceledBookings),
            },
            @endforeach
        ],
        eventClick: function (info) {
            var bookingDetails = info.event.extendedProps.bookingDetails;

            // Populate the input fields with booking details
            bookingDetailsForm.reservationType.value = bookingDetails.reservation_type;
            bookingDetailsForm.rateName.value = bookingDetails.rate_name;
            bookingDetailsForm.childrenQty.value = bookingDetails.children_qty;
            bookingDetailsForm.adultQty.value = bookingDetails.adult_qty;
            bookingDetailsForm.seniorQty.value = bookingDetails.senior_qty;
            bookingDetailsForm.name.value = bookingDetails.name;
            bookingDetailsForm.contactNum.value = bookingDetails.contact_num;
            bookingDetailsForm.email.value = bookingDetails.email;
            bookingDetailsForm.bday.value = bookingDetails.bday;
            bookingDetailsForm.status.value = bookingDetails.status;
            bookingDetailsForm.check_in.value = bookingDetails.check_in;
            bookingDetailsForm.total_companion.value = bookingDetails.total_companion;
            bookingDetailsForm.total_amount.value = bookingDetails.total_amount;
            bookingDetailsForm.total_amount_paid.value = bookingDetails.total_amount_paid;

            bookingDetailsContainer.style.display = 'block';
            $('#bookingDetailsModal').modal('show');
        },
    });

    calendar.render();
});
</script>

@endsection
