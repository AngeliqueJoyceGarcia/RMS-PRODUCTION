@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')

<link rel="stylesheet" href="{{ asset('css/calendarList.css') }}">

<script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.8/index.global.min.js'></script>
<script src='fullcalendar/dist/index.global.js'></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<div class="upper">
    <div class="containerUsers">
         Check-out
    </div>

    <div class="containerArchives">
        <a class="archive" href="{{ auth()->user()->role_id === 1 ? route('prebook.checkin') : route('reguser.checkin') }}">Check-in</a>
    </div>
</div>

<div id='calendar'></div>

<!-- Modal for Booking Details -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Check-out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bookingDetailsContainer">
                    <form id="bookingDetailsForm">
                        <label class="userdetails" for="name">Refund:</label>
                        <a href="#" id="refundLink">click here</a>

                        <div class="nonedit">

                            <div class="first">
                                <div>
                                <label class="labell"  for="rateName">Rate Name:</label>
                                <input class="input" type="text" id="rateName" name="rateName" readonly>
                                </div>

                                <div>
                                <label class="labell"  for="reservationType">Booking Type:</label>
                                <input class="input" type="text" id="reservationType" name="reservationType" readonly>
                                </div>
                            </div>

                            <div class="second">
                                <div>
                                <label class="labell"  for="name">Name:</label>
                                <input class="input" type="text" id="name" name="name" readonly>
                                </div>
                            
                                <div>
                                <label class="labell"  for="arrived_companion">Arrived Companions:</label>
                                <input class="input" type="number" id="arrived_companion" name="arrived_companion" readonly>
                                </div>
                            </div>

                            <div class="thirds">
                                <div>
                                <label class="labell"  for="status">Status:</label>
                                <input class="input" type="text" id="status" name="status" readonly>
                               
                                </div>
                            </div>

                            <hr>

                            <div class="forth">
                                <div>
                                <label class="labell"  for="total_amount_paid">Amount Paid:</label>
                                <input class="input" type="number" id="total_amount_paid" name="total_amount_paid" readonly>
                                </div>
                            
                                <div>
                                <label class="labell"  for="check_in">Check-in:</label>
                                <input class="input" type="datetime-local" id="check_in" name="check_in" readonly>
                                </div>
                            </div>


                        </div>

                        <div class="editted">

                            <div class="nines">
                                <div>
                                <label class="labell"  for="check_out">Check-out:<span class="req">*</span></label>
                                <input class="input" type="datetime-local" id="check_out" name="check_out" required>
                                </div>
                            </div>


                            <div class="nines">
                                <div>
                                <label class="labell"  for="remarks">Remarks:</label>
                                <input class="input" type="text" id="remarks" name="remarks">
                                </div>
                            </div>

                        </div>


                            <!-- hide this, it for action -->
                        <input type="hidden" id="bookingIdInput" name="bookingId" value="" readonly>
                        

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var checkinInhousepreBookings = @json($checkinInhousepreBookings); 
    var checkinDayTourpreBookings = @json($checkinDayTourpreBookings);
    var walkInBookings = @json($walkInBookings);

    // Check if the refresh flag is set
    @if(session('refresh'))
        location.reload(); // Refresh the page
    @endif

    // Add a click event listener to the "click here" link
    document.querySelector('#refundLink').addEventListener('click', function (event) {
        event.preventDefault();

        // Get the value from the hidden input field
        var bookingId = document.querySelector('#bookingIdInput').value;

        // Construct the URL with the bookingId value
        var url = '/admin/' + bookingId + '/edit';

        // Redirect the user to the constructed URL
        window.location.href = url;
    });

    document.querySelector('#bookingDetailsModal .btn-primary').addEventListener('click', function (event) {
        event.preventDefault();

        // Serialize the form data
        var formData = new FormData(bookingDetailsForm);

        // Send an AJAX request to update the booking details
        fetch('{{ route('booking.updatecheckout') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(function (response) {
            if (response.ok) {
                // Booking details updated successfully
                $('#bookingDetailsModal').modal('hide');
                // Refresh the page
                location.reload();
            } else {
                // Handle errors and display appropriate messages
                console.error('Error updating booking details');
                // Add error handling and display error messages here
            }
        })
        .catch(function (error) {
            console.error('Error updating booking details:', error);
            // Add error handling and display error messages here
        });
    });
  


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
        },
        events: [
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

            // checkinDayTourpreBooking
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
        ],
        eventClick: function (info) {
            var bookingDetails = info.event.extendedProps.bookingDetails;

            // Populate the input fields with booking details
            bookingDetailsForm.name.value = bookingDetails.name;
            bookingDetailsForm.reservationType.value = bookingDetails.reservation_type;
            bookingDetailsForm.rateName.value = bookingDetails.rate_name;
            bookingDetailsForm.arrived_companion.value = bookingDetails.arrived_companion;
            bookingDetailsForm.status.value = bookingDetails.status;
            bookingDetailsForm.total_amount_paid.value = bookingDetails.total_amount_paid;
            bookingDetailsForm.check_in.value = bookingDetails.check_in;
            bookingDetailsForm.remarks.value = bookingDetails.remarks;

            // Access the booking ID
            var bookingId = bookingDetails.id;

            // Set the value of the hidden input field
            bookingDetailsForm.bookingId.value = bookingDetails.id;

            bookingDetailsContainer.style.display = 'block';
            $('#bookingDetailsModal').modal('show');

           
        },
    });

    calendar.render();

});
</script>

@endsection
