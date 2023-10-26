@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')


@section('main_content')
<link rel="stylesheet" href="{{ asset('css/receiptBilling.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <div class="conbooking">
        <div class="containerBooking">
             <span>Billing Details</span>
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
            // Reverse the order of $billings to show the latest one first
            $reversedBillings = $billings->reverse();
            @endphp
            @foreach($reversedBillings as $billings)
            <div class="billingdetails">
                <div class="card-header">
                    <span class="guestname">{{$billings->name}}</span>
                </div>
                @if($billings->reservation_type == 'Walk-in')
                <div class="checkinout">
                    <span>{{$billings->check_in}}</span>
                    <span>{{$billings->check_out}}</span>
                </div>
                @elseif($billings->reservation_type == 'Pre-book Inhouse'  || $billings->reservation_type == 'Pre-book DayTour')
                <div class="checkinout1">
                    <span>{{$billings->check_in}}</span>
                </div>
                @endif
                <div class="name">
                    <span class="reservationtype">{{$billings->reservation_type}}</span>
                </div>
                <div class="childadultsenior">
                    <div>
                        <label>Children</label>
                        <span>{{$billings->children_qty}}</span>
                    </div>
                    <div>
                        <label>Adult</label>
                        <span>{{$billings->adult_qty}}</span>
                    </div>
                    <div>
                        <label>Senior Citizen</label>
                        <span>{{$billings->senior_qty}}</span>
                    </div>
                </div>
                <div class="totalcom">
                    <div class="totalcom1">
                    <div>
                        <label>Total Companion</label>
                        <span>{{$billings->total_companion}}</span>
                    </div>
                    <div>
                        <label>Arrived Companion</label>
                        <span>{{$billings->arrived_companion}}</span>
                    </div>
                    </div>
                    @if($billings->reservation_type == 'Walk-in')
                    <div class="totalcom2">
                    <div>
                        <label>Towel Quantity</label>
                        <span>{{$billings->total_companion}}</span>
                    </div>
                    <div>
                        <label>Wristband Quantity</label>
                        <span>{{$billings->total_companion}}</span>
                    </div>
                    </div>
                    @endif
                </div>
                <div class="container1">
                    <div>
                        <div class="title">
                            <label>Base Price</label>
                        </div>
                        <div class="content">
                            <label>Child: </label>
                            <span>{{$billings->baseChildPrice}}</span>
                        </div>

                        <div class="content">
                            <label>Adult: </label>
                            <span>{{$billings->baseAdultPrice}}</span>
                        </div>

                        <div class="content">
                            <label>Senior: </label>
                            <span>{{$billings->baseSeniorPrice}}</span>
                        </div>
                    </div>
                    <div class="title">
                        <div class="vatscTitle">
                            <label>With Vat & SC</label>
                        </div>
                        <div class="content">
                            <label>VAT: </label>
                            <span>{{$billings->vat}}%</span>
                        </div>

                        <div class="content">
                            <label>Service Charge: </label>
                            <span>{{$billings->service_charge}}%</span>
                        </div>

                        <div class="content">
                            <label>Child: </label>
                            <span>{{$billings->vatsc_childprice}}</span>
                        </div>

                        <div class="content">
                            <label>Adult: </label>
                            <span>{{$billings->vatsc_adultprice}}</span>
                        </div>

                        <div class="content">
                            <label>Senior: </label>
                            <span>{{$billings->vatsc_seniorprice}}</span>
                        </div>
                    </div>
                    @if($billings->reservation_type == 'Walk-in')
                    <div>
                        <div class="title">
                            <label>Incidental Deposit</label>
                        </div>
                        <div class="content">
                            <label>Towel Amount: </label>
                            <span>
                                @php
                                    $towelItem = $items->where('item_name', 'Towel')->first();
                                    if ($towelItem) {
                                        echo $towelItem->price;
                                    } else {
                                        echo 1000; // Default value
                                    }
                                @endphp
                            </span>
                        </div>

                        <div class="content">
                            <label>Wristband Amount: </label>
                            <span>
                                @php
                                    $wristbandItem = $items->where('item_name', 'Wristband')->first();
                                    if ($wristbandItem) {
                                        echo $wristbandItem->price;
                                    } else {
                                        echo 1000; // Default value
                                    }
                                @endphp
                            </span>
                        </div>
                        <div class="content">
                            <label>Towel Quantity: </label>
                            <span>{{$billings->total_companion}}</span>
                        </div>

                        <div class="content">
                            <label>Wristband Quantity: </label>
                            <span>{{$billings->total_companion}}</span>
                        </div>
                        @foreach($items as $item)
                        @if ($item['item_name'] === 'Towel')
                        <div class="content">
                            <label>Subtotal Towel: </label>
                            <span>{{$billings->total_companion * $item['price'] }}</span>
                        </div>
                        @endif
                        @if ($item['item_name'] === 'Wristband')
                        <div class="content">
                            <label>Subtotal Wristband: </label>
                            <span>{{$billings->total_companion * $item['price']}}</span>
                        </div>
                         @endif
                        @endforeach
                        <div class="content1">
                            <label>Total Incidental Deposit: </label>
                            <span>{{$billings->refundablePrice}}</span>
                        </div>
                    </div>
                    <div class="totalItemPrice" style="display: none;">
                        <label>Total Pre-book Amount<span class="extra"></label>
                        <span class="totalAmountValue">{{$billings->total_prebookAmount ?? 0}}</span>
                    </div>
                    <div class="totalItemPrice">
                        <label>Total Item Amount<span class="extra">(Extra Items)</label>
                        <span class="totalAmountValue">{{$billings->total_itemprice}}</span>
                    </div>
                    <div class="totalAmount">
                        <label>Total Amount</label>
                        <span>{{$billings->total_amount}}</span>
                    </div>
                    @elseif($billings->reservation_type == 'Pre-book Inhouse' || $billings->reservation_type == 'Pre-book DayTour')
                    <div class="totalItemPrice">
                        <label>Total Item Amount<span class="extra">(Extra Items)</label>
                        <span class="totalAmountValue">{{$billings->total_itemprice}}</span>
                    </div>
                    <div class="totalItemPrice">
                        <label>Total Pre-book Amount<span class="extra"></label>
                        <span class="totalAmountValue">{{$billings->total_prebookAmount ?? 0}}</span>
                    </div>
                    <div class="content">
                        <label>Balance<span class="extra"></label>
                        <span >{{$billings->balance ?? 0}}</span>
                    </div>
                    @elseif($billings->reservation_type == 'Open-book')
                    <div class="totalItemPrice">
                        <label>Total Item Amount<span class="extra">(Extra Items)</label>
                        <span class="totalAmountValue">{{$billings->total_itemprice}}</span>
                    </div>
                    <div class="totalItemPrice">
                        <label>Total Open-book Amount<span class="extra"></label>
                        <span class="totalAmountValue">{{$billings->total_prebookAmount ?? 0}}</span>
                    </div>
                    <div class="content">
                        <label>Balance<span class="extra"></label>
                        <span >{{$billings->balance ?? 0}}</span>
                    </div>
                    @endif
                    
                    <div class="line"></div>

                    <div class="conmode">
                    <div class="modedetails">
                        <div>
                            <label>Payment Mode</label>
                            <span>{{$billings->payment_mode}}</span>
                        </div>
                        @if (!empty($billings->acc_name))
                        <div>
                            <label>Account Name</label>
                            <span>{{$billings->acc_name}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->approval_code))
                        <div>
                            <label>Approval Code</label>
                            <span>{{$billings->approval_code}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->commission))
                        <div>
                            <label>Commission</label>
                            <span>{{$billings->commission}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->reference_num))
                        <div>
                            <label>Reference Number</label>
                            <span>{{$billings->reference_num}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->card_num))
                        <div>
                            <label>Card Number</label>
                            <span>{{$billings->card_num}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->confirm_number))
                        <div>
                            <label>Confirmation Number</label>
                            <span>{{$billings->confirm_number}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->gc_number))
                        <div>
                            <label>Gift Card Number</label>
                            <span>{{$billings->gc_number}}</span>
                        </div>
                        @endif

                        @if (!empty($billings->validity))
                        <div>
                            <label>Validity</label>
                            <span>{{$billings->validity}}</span>
                        </div>
                        @endif
                        @if (!empty($billings->worth))
                        <div>
                            <label>Worth</label>
                            <span>{{$billings->worth}}</span>
                        </div>
                        @endif
                    </div>
                    </div>
                    <div class="totalpaid">
                        <div>
                            <label>Total Amount Paid</label>
                            <span>{{$billings->total_amount_paid}}</span>
                        </div>
                    </div>
                    
                </div>
                <div class="footer">
                    <span>{{$billings->rate_name}}</span>
                </div>
                <div class="remarks">
                    <div class="remarkslabel">
                        <label>Remarks:</label>
                    </div>
                    <div class="remarkspan">
                        <span>{{$billings->remarks}}</span>
                    </div>
                </div>

                <div></div>
            </div>
        @endforeach
        </div>
        </div>

        <script>
            // For Searching data in the list
            document.addEventListener("DOMContentLoaded", function () {
                const searchInput = document.getElementById("searchInput");
                const billingdetailsList = document.querySelectorAll(".container .billingdetails");

                searchInput.addEventListener("input", function () {
                    const searchTerm = searchInput.value.toLowerCase();

                    billingdetailsList.forEach(billingdetail => {
                        const billingdetailData = billingdetail.textContent.toLowerCase();
                        if (billingdetailData.includes(searchTerm)) {
                            billingdetail.style.display = "block";
                        } else {
                            billingdetail.style.display = "none";
                        }
                    });
                });
            });
        </script>

@endsection