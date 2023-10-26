@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src='fullcalendar/dist/index.global.js'></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div id='calendar'></div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customModalLabel">Select Rate & Expected Guests</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customRateForm" action="{{ route('setDefault.createEvent') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="rateSelect">Select Rate:</label>
                        <select class="form-control" id="rateSelect" name="rate_id">
                            @foreach($rates as $rate)
                                <option value="{{ $rate->id }}">{{ $rate->rate_name }}</option>
                            @endforeach
                        </select>
                        <div class="expected">
                            <label for="qty">Waterpark Capacity</label>
                            <input class="input" type="number" id="qty" name="maxPaxqty" placeholder="0" required="">
                            <input class="input" type="hidden" id="selectedDate" name="date">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveCustomRate">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,multiMonthYear'
            },
            events: [
                // Fetch custom events from the custom_event table
                @foreach($customEvents as $event)
                {
                    title: '{{ $event->entranceRate->rate_name }}', // Use the rate name or customize as needed
                    start: '{{ $event->event_date }}',
                    color: 'blue', // Customize the color as needed
                },
                @endforeach

                // Fetch custom max pax events from the custom_max_pax table
                @foreach($customMaxPaxEvents as $maxPaxEvent)
                {
                    title: '{{ $maxPaxEvent->name }}: {{ $maxPaxEvent->maximum_customers }}', // Customize the title as needed
                    start: '{{ $maxPaxEvent->event_date }}',
                    color: 'green', // Customize the color as needed
                },
                @endforeach
            ],
            dateClick: function(info) {
                openCustomModal(info.dateStr); // Open the custom modal when a date is clicked
            }
        });

        calendar.render();
    });

    // Function to open the custom modal
    function openCustomModal(date) {
        document.getElementById('selectedDate').value = date;
        $('#customModal').modal('show'); // Show the Bootstrap Modal
    }

    // Function to close the custom modal
    function closeCustomModal() {
        $('#customModal').modal('hide'); // Hide the Bootstrap Modal
    }

    // Function to handle the "Save Rate" button click in the custom modal
    document.getElementById('saveCustomRate').addEventListener('click', function () {
        // Submit the form using JavaScript
        document.getElementById('customRateForm').submit();
        closeCustomModal(); // Close the modal after submission
    });

</script>

@endsection
