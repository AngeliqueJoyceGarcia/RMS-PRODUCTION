@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/cancelbook.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-container" id="popupContainer">
      <div class="usertitle">Cancel Booking</div>
      <hr>
        <form method="POST" action="{{ auth()->user()->role_id === 1 ? route('booking.update_cancel', ['booking' => $booking]) : route('reguser.update_cancel', ['booking' => $booking]) }}">
            @csrf
            @method('PUT')

            <div class="user-details">

                <div class="name">
                    <span class="userdetails">{{ $booking->name }}</span>
                </div>
                <div class="form-group">
                    <span class="userdetails">Remarks:<span class="required">(Required)</span></span>
                    <input type="text" id="remarks" name="remarks" placeholder="Reason to Cancel" required>
                </div>
            </div>

            <br>
            <a class="UserButtonClose" href="{{ route('booking.read') }}"><span>Cancel</span></a>
            <button class="UserButton" type="submit"><span>Update</span></button>
        </form>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.UserButtonClose').on('click', function(event) {
            event.preventDefault(); 
            window.location.href = $(this).attr('href'); 
        });
    });
</script>

@endsection