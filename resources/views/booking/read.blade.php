@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link rel="stylesheet" href="{{ asset('css/bookingtable.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <div class="upper">
        <div class="conbooking">
            <div class="containerBooking">
                <span>Booking Details</span>
            </div>
        </div>
        <div class="conbookingright">
            <div class="containerBookingcancel">
                <a class="archive" href="{{ auth()->user()->role_id === 1 ? route('booking.read.cancel') : route('reguser.read.cancel') }}">Canceled Bookings</a>
            </div>
        </div>
    </div>

        <div class="searching">
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search...">
                </div>
        </div>
        <div class="body">
        <div class="container">
            @php
            // Reverse the order of $bookings to show the latest one first
            $reversedBookings = $bookings->reverse();
            @endphp
            @foreach($reversedBookings as $booking)
            <div class="bookdetails">
                <div class="card-header">
                    <span class="reservationtype">{{$booking->reservation_type}}</span>
                </div>
                @if($booking->reservation_type == 'Walk-in')
                    <div class="checkinout">
                        <span>{{$booking->check_in}}</span>
                        <span>{{$booking->check_out}}</span>
                    </div>
                @elseif($booking->reservation_type == 'Pre-book Inhouse' || $booking->reservation_type == 'Pre-book DayTour')
                    <div class="checkinout1">
                        <span>{{$booking->check_in}}</span>
                    </div>
                @endif

                <div class="name">
                    <span class="guestname">{{$booking->name}}</span>
                </div>
                <div class="childadultsenior">
                    <div>
                        <label>Children</label>
                        <span>{{$booking->children_qty}}</span>
                    </div>
                    <div>
                        <label>Adult</label>
                        <span>{{$booking->adult_qty}}</span>
                    </div>
                    <div>
                        <label>Senior Citizen</label>
                        <span>{{$booking->senior_qty}}</span>
                    </div>
                </div>
                <div class="totalcom">
                    <div>
                        <label>Total Companion</label>
                        <span>{{$booking->total_companion}}</span>
                    </div>
                    <div>
                        <label>Arrived Companion</label>
                        <span>{{$booking->arrived_companion}}</span>
                    </div>
                </div>
                <div class="container1">
                @if($booking->reservation_type == 'Walk-in')
                    <div class="totalincidental">
                        <label>Incidental Deposit</label>
                        <span>{{$booking->refundablePrice}}</span>
                    </div>
                    @if (!empty($booking->total_itemprice))
                    <div class="totalamountprice">
                        <label>Total Item Price<span class="extra">(Extra Items)</label>
                        <span>{{$booking->total_itemprice}}</span>
                    </div>
                    @endif
                    <div class="totalamount">
                        <label>Total Amount</label>
                        <span>{{$booking->total_amount}}</span>
                    </div>
                    <div class="totalprebookamount" style="display: none;">
                        <label>Total Prebook Amount</label>
                        <span>{{$booking->total_prebookAmount}}</span>
                    </div>
                @elseif($booking->reservation_type == 'Pre-book Inhouse'  || $booking->reservation_type == 'Pre-book DayTour')
                    <div class="totalincidental" style="display:none;">
                        <label>Incidental Deposit</label>
                        <span>{{$booking->refundablePrice}}</span>
                    </div>
                    @if (!empty($booking->total_itemprice))
                    <div class="totalamountprice">
                        <label>Total Item Price<span class="extra">(Extra Items)</label>
                        <span>{{$booking->total_itemprice}}</span>
                    </div>
                    @endif
                    <div class="totalamount" style="display: none;">
                        <label>Total Amount</label>
                        <span>{{$booking->total_amount}}</span>
                    </div>
                    <div class="totalprebookamount">
                        <label>Total Pre-Book Amount</label>
                        <span>{{$booking->total_prebookAmount}}</span>
                    </div>
                    @elseif($booking->reservation_type == 'Open-book')
                    <div class="totalincidental" style="display:none;">
                        <label>Incidental Deposit</label>
                        <span>{{$booking->refundablePrice}}</span>
                    </div>
                    @if (!empty($booking->total_itemprice))
                    <div class="totalamountprice">
                        <label>Total Item Price<span class="extra">(Extra Items)</label>
                        <span>{{$booking->total_itemprice}}</span>
                    </div>
                    @endif
                    <div class="totalamount" style="display: none;">
                        <label>Total Amount</label>
                        <span>{{$booking->total_amount}}</span>
                    </div>
                    <div class="totalprebookamount">
                        <label>Total Open-Book Amount</label>
                        <span>{{$booking->total_prebookAmount}}</span>
                    </div>
                @endif
                    <div class="line"></div>
                    <div class="paymentmode">
                        <label>Mode of Payment</label>
                        <span>{{$booking->payment_mode}}</span>
                    </div>
                    <div class="conmode">
                    <div class="modedetails">
                        @if (!empty($booking->acc_name))
                        <div>
                            <label>Account Name</label>
                            <span>{{$booking->acc_name}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->commission))
                        <div>
                            <label>Commission</label>
                            <span>{{$booking->commission}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->approval_code))
                        <div>
                            <label>Approval Code</label>
                            <span>{{$booking->approval_code}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->reference_num))
                        <div>
                            <label>Reference Number</label>
                            <span>{{$booking->reference_num}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->card_num))
                        <div>
                            <label>Card Number</label>
                            <span>{{$booking->card_num}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->confirm_number))
                        <div>
                            <label>Booking Confirmation Number</label>
                            <span>{{$booking->confirm_number}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->gc_number))
                        <div>
                            <label>Gift Card Number</label>
                            <span>{{$booking->gc_number}}</span>
                        </div>
                        @endif

                        @if (!empty($booking->validity))
                        <div>
                            <label>Validity</label>
                            <span>{{$booking->validity}}</span>
                        </div>
                        @endif
                        @if (!empty($booking->worth))
                        <div>
                            <label>Worth</label>
                            <span>{{$booking->worth}}</span>
                        </div>
                        @endif
                    </div>


                    </div>
                    <div class="totalpaid">
                        <div>
                            <label>Total Amount Paid</label>
                            <span>{{$booking->total_amount_paid}}</span>
                        </div>
                    </div>
                    
                </div>
                <div class="details">
                <div class="details1">
                    <span>{{$booking->contact_num}}</span>
                    <span>{{$booking->address}}</span>
                    <span>{{$booking->email}}</span>
                </div>
                <div class="details2">
                    <span>{{$booking->fbname}}</span>
                    <span>{{$booking->bday}}</span>
                </div>
                </div>
                <div class="footer">
                    <span>{{$booking->rate_name}}</span>
                    <label>status: {{$booking->status}}</label>
                </div>
            </div>
        @endforeach
        </div>
        </div>

        <script>

           


            // For Searching data in the list
            document.addEventListener("DOMContentLoaded", function () {
                const searchInput = document.getElementById("searchInput");
                const bookdetailsList = document.querySelectorAll(".container .bookdetails");

                searchInput.addEventListener("input", function () {
                    const searchTerm = searchInput.value.toLowerCase();

                    bookdetailsList.forEach(bookdetail => {
                        const bookdetailData = bookdetail.textContent.toLowerCase();
                        if (bookdetailData.includes(searchTerm)) {
                            bookdetail.style.display = "block";
                        } else {
                            bookdetail.style.display = "none";
                        }
                    });
                });
            });
        </script>


@endsection