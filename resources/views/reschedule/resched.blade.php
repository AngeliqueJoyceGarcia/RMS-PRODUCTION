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
         Reschedule
    </div>

   
</div>

<div id='calendar'></div>

<!-- Modal for Booking Details -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reschedule Guests</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bookingDetailsContainer">
                    <form id="bookingDetailsForm">

                        <div class="nonedit">

                            <div class="first">
                                <div>
                                <label class="labell" for="reservationType">Booking Type:</label>
                                <input class="input" type="text" id="reservationType" name="reservationType" readonly>
                                </div>

                                <div>
                                <label class="labell" for="rateName">Rate Name:</label>
                                <input class="input"  type="text" id="rateName" name="rateName" readonly>
                                </div>
                            </div>

                            <div class="second">
                                <div>
                                <label class="labell" for="name">Name:</label>
                                <input class="input"  type="text" id="name" name="name" readonly>
                                </div>
                            
                                <div>
                                <label class="labell" for="contactNum">Contact Number:</label>
                                <input class="input"  type="text" id="contactNum" name="contactNum" readonly>
                                </div>
                            </div>

                           
                            <div class="sixth">

                                <div>
                                <label class="labell" for="status">Status:</label>
                                <input class="input"  type="text" id="status" name="status" readonly>
                                </div>

                            </div>

                        </div>

                            {{-- can be edited --}}

                        <div class="editted">

                            <div class="eight">
                                <div>
                                    <label class="labell" for="check_in">Check-in:<span class="req">*</span></label>
                                    <input class="input"  type="datetime-local" id="check_in" name="check_in" >
                                </div>     
                            </div>
                        </div>
                        
                        
                        <!-- hide this, it for action -->
                        <input type="hidden" id="bookingIdInput" name="bookingId" value="">
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
    
    var pendingInhousepreBookings = @json($pendingInhousepreBookings); 
    var pendingDayTourpreBookings = @json($pendingDayTourpreBookings);
  

    // Check if the refresh flag is set
    @if(session('refresh'))
        location.reload(); // Refresh the page
    @endif

    document.querySelector('#bookingDetailsModal .btn-primary').addEventListener('click', function (event) {
        event.preventDefault();

        // Serialize the form data
        var formData = new FormData(bookingDetailsForm);

        // Send an AJAX request to update the booking details
        fetch('{{ route('booking.updateCheckIn') }}', {
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
                // Refresh the page
                location.reload();
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
        ],
        eventClick: function (info) {
            var bookingDetails = info.event.extendedProps.bookingDetails;

            // Populate the input fields with booking details
            bookingDetailsForm.reservationType.value = bookingDetails.reservation_type;
            bookingDetailsForm.rateName.value = bookingDetails.rate_name;
            bookingDetailsForm.name.value = bookingDetails.name;
            bookingDetailsForm.contactNum.value = bookingDetails.contact_num;        
            bookingDetailsForm.status.value = bookingDetails.status;
            bookingDetailsForm.check_in.value = bookingDetails.check_in;

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
