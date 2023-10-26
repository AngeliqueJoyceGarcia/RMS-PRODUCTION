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
         Check-in
    </div>

    <div class="containerArchives">
        <a class="archive" href="{{ auth()->user()->role_id === 1 ? route('prebook.checkout') : route('reguser.checkout') }}">Check-out</a>
    </div>
</div>

<div id='calendar'></div>

<!-- Modal for Booking Details -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Check-in</h5>
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

                            <div class="third">
                                <div>
                                <label class="labell" for="childrenQty">Expected Children Qty:</label>
                                <input class="input"  type="text" id="childrenQty" name="childrenQty" readonly>
                                </div>

                                <div>
                                <label class="labell" for="adultQty">Expected Adult Qty:</label>
                                <input class="input"  type="text" id="adultQty" name="adultQty" readonly>
                                </div>
                            </div>
                            <div class="thirds">
                                <div>
                                <label class="labell" for="seniorQty">Expected Senior Qty:</label>
                                <input class="input"  type="text" id="seniorQty" name="seniorQty" readonly>
                                </div>
                            </div>

                            <hr>

                            <div class="forth">
                                <div>
                                <label class="labell" for="towel_subtotal">Towel Subtotal:</label>
                                <input class="input"  type="text" id="towel_subtotal" name="towel_subtotal" value="0" readonly>
                                </div>
                            
                                <div>
                                <label class="labell" for="wristband_subtotal">Wristband Subtotal:</label>
                                <input class="input"  type="text" id="wristband_subtotal" name="wristband_subtotal" readonly>
                                </div>
                            </div>

                            <div class="fifth">

                                <div>
                                <label class="labell" for="incidental_total">Incidental Total Amount:</label>
                                <input class="input"  type="text" id="incidental_total" name="incidental_total" readonly>
                                </div>
                        
                                <div>
                                <label class="labell" for="balance">Balance:</label>
                                <input class="input"  type="text" id="balance" name="balance" readonly>
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

                            <div class="seven">
                                <div>
                                <label class="labell" for="arrived_companion">Arrived Companions:<span class="req">*</span></label>
                                <input class="input"  type="number" id="arrived_companion" name="arrived_companion">
                                </div>
                            </div>

                            <hr>

                            <div class="eight">
                                <div>
                                <label class="labell" for="check_in">Check-in:<span class="req">*</span></label>
                                <input class="input"  type="datetime-local" id="check_in" name="check_in" >
                                </div>

                                <div>
                                <label class="labell" for="checkin_payment">Check-in Payment:<span class="req">*</span></label>
                                <input class="input"  type="number" id="checkin_payment" name="checkin_payment">
                                </div>
                            </div>

                            <div class="nine">
                                <div>
                                <label class="labell" for="remarks">Remarks:</label>
                                <input class="inputtt"  type="text" id="remarks" name="remarks">
                                </div>
                            </div>

                        </div>
                        
                        
                        <!-- hide this, it for action -->
                        <input type="hidden" id="bookingIdInput" name="bookingId" value="">
                        <input type="hidden" id="totalAmountPaid" name="totalAmountPaid" value=0>
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
    var pendingOpenBookings = @json($pendingOpenBookings);

    // Check if the refresh flag is set
    @if(session('refresh'))
        location.reload(); // Refresh the page
    @endif

    document.querySelector('#bookingDetailsModal .btn-primary').addEventListener('click', function (event) {
        event.preventDefault();

        // Serialize the form data
        var formData = new FormData(bookingDetailsForm);

        // Send an AJAX request to update the booking details
        fetch('{{ route('booking.updatecheckin') }}', {
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
        ],
        eventClick: function (info) {
            var bookingDetails = info.event.extendedProps.bookingDetails;

            // Populate the input fields with booking details
            bookingDetailsForm.name.value = bookingDetails.name;
            bookingDetailsForm.contactNum.value = bookingDetails.contact_num;
            bookingDetailsForm.childrenQty.value = bookingDetails.children_qty;
            bookingDetailsForm.adultQty.value = bookingDetails.adult_qty;
            bookingDetailsForm.seniorQty.value = bookingDetails.senior_qty;
            bookingDetailsForm.arrived_companion.value = bookingDetails.arrived_companion;
            bookingDetailsForm.reservationType.value = bookingDetails.reservation_type;
            bookingDetailsForm.rateName.value = bookingDetails.rate_name;
            bookingDetailsForm.status.value = bookingDetails.status;
            bookingDetailsForm.check_in.value = bookingDetails.check_in;
            bookingDetailsForm.checkin_payment.value = bookingDetails.checkin_payment;
            bookingDetailsForm.remarks.value = bookingDetails.remarks;

            //towel subtotal, wristband subtotal,incidental total amount
            var towelPrice = 0;
            var wristbandPrice = 0;
            @foreach ($items as $item)
                @if ($item->item_name === 'Towel')
                    towelPrice = {{ $item->price }};
                @endif
                @if ($item->item_name === 'Wristband')
                    wristbandPrice = {{ $item->price }};
                @endif
            @endforeach

            // Check if towelPrice is still 0 (meaning it wasn't found in the table)
            if (towelPrice === 0) {
                towelPrice = 1000; // Set a default value
            }

            // Check if wristbandPrice is still 0 (meaning it wasn't found in the table)
            if (wristbandPrice === 0) {
                wristbandPrice = 200; // Set a default value
            }

            // calcutale totals
            var totalCompanion = bookingDetails.total_companion;
            var TowelSubtotal =  totalCompanion * towelPrice;
            var WristbandSubtotal = totalCompanion * wristbandPrice;
            var IncidentalTotal = parseFloat(TowelSubtotal + WristbandSubtotal);
            var Balance = parseFloat(bookingDetails.balance);

            // setting up min and max of checkin payment
            var checkinPaymentInput = document.getElementById("checkin_payment");
            checkinPaymentInput.min = IncidentalTotal + Balance;
            checkinPaymentInput.max = IncidentalTotal + Balance;


            var arrivedCompanion = document.getElementById("arrived_companion");
            arrivedCompanion.min = 0;
            arrivedCompanion.max = bookingDetails.total_companion;

            // Access the booking ID
            var bookingId = bookingDetails.id;

            // Set the value of the hidden input field
            bookingDetailsForm.bookingId.value = bookingDetails.id;
            bookingDetailsForm.totalAmountPaid.value = bookingDetails.total_amount_paid;


            // continuation of assignment
            bookingDetailsForm.towel_subtotal.value = TowelSubtotal.toFixed(3);
            bookingDetailsForm.wristband_subtotal.value = WristbandSubtotal.toFixed(3);
            bookingDetailsForm.incidental_total.value = IncidentalTotal.toFixed(3);
            bookingDetailsForm.balance.value = bookingDetails.balance;

            bookingDetailsContainer.style.display = 'block';
            $('#bookingDetailsModal').modal('show');

           
        },
    });

    calendar.render();

   

});
</script>

@endsection
