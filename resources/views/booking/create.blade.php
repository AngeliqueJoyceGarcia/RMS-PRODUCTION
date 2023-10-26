@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/booking.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<div class="body">
            
        <div class="mainleft">
            <div class="upleft"><span class="AddReserv">ADD BOOKING</span></div>
            
            <div class="RateInformation">
                        <div class="ratetitle">
                            <label class="Rate">Rate:</label>
                            <select class = "input" id="rateNameSelect" name="rateNameSelect">
                                @foreach($entranceRates as $rate)
                                    <option value="{{ $rate->rate_name }}" {{ $currentRate['rate_name'] == $rate->rate_name ? 'selected' : '' }}>
                                        {{ $rate->rate_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="hiderates">
                            <div>
                                <label>Child Rate: </label>
                                <span id="childRate">{{ $currentRate['vatsc_childprice'] }}</span>
                            </div>
                            <div>
                                <label>Adult Rate: </label>
                                <span id="adultRate">{{ $currentRate['vatsc_adultprice'] }}</span>
                            </div>
                            <div>
                                <label>Senior Rate: </label>
                                <span id="seniorRate">{{ $currentRate['vatsc_seniorprice'] }}</span>
                            </div>
                        </div>
            </div>
                    <form method="POST" id="book" action="{{ auth()->user()->role_id === 1 ? route('booking.store') : route('reguserbooking.store') }}"  enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                   

            <div class="subsubleft">
                    <div class="reservationtype">
                        <!-- walkin, online will be applied next patch -->
                        <div class="reservationtypelabel">
                            <label for="reservation_type">Booking Type<span class="required">*</label>
                            <select class="input" id="reservation_type" name="reservation_type" required>
                                <option value="" disabled selected style="display:none;">Select</option>
                                <option value="Walk-in">Walk-in</option>
                                <option value="Pre-book Inhouse">Pre-book Inhouse</option>
                                <option value="Pre-book DayTour">Pre-book DayTour</option>
                                <option value="Open-book">Open-book</option>
                            </select>
                        </div>

                        <div class="childadult">
                        <div>
                            <label>No. of Children<span class="required">*</label>
                            <input class="input" type="search" id="childrenQty" type="number" name="children_qty" placeholder="No. of Children" required  onchange="calculateTotal(); calculateTotalCompanion(); calculateTotalRefundablePrice(); calculateBilling(); updateChildQuantity(); calculateTotalRefundablePrices(); calculateTotalCompletion(); calculateBillingCheckbox();" />
                        </div>

                        <div>
                            <label>No. of Adult<span class="required">*</label>
                            <input class="input" type="search"  id="adultQty" type="number" name="adult_qty" placeholder="No. of Adult" required  onchange="calculateTotal(); calculateTotalCompanion(); calculateTotalRefundablePrice(); calculateBilling(); updateAdultQuantity(); calculateTotalRefundablePrices(); calculateTotalCompletion(); calculateBillingCheckbox();"/>
                        </div>
                    
                        <div>
                            <label>No. of Senior Citizen<span class="required"></label>
                            <input class="input" type="search" id="seniorQty" type="number" name="senior_qty" required placeholder="No. of Senior Citizen"  value="0" min="0" onchange="calculateTotal(); calculateTotalCompanion(); calculateTotalRefundablePrice(); calculateBilling(); updateSeniorQuantity(); calculateTotalRefundablePrices(); calculateTotalCompletion(); calculateBillingCheckbox();"/>
                        </div>
                        </div>
                        <!-- total companion, for guest monitoring -->
                        <div class="totalcompanion">
                            <label class="totalcompanionlabel">Total Companion: </label>
                            <span id="totalCompanion">0</span>
                            <input type="hidden" name="total_companion" id="hiddenTotalCompanion" value="0">
                        </div>
                    </div>
                    <div class="checkinout">
                        <div>
                            <label>Check In<span class="required">*</label>
                            <input class="input" type="datetime-local" id="checkInDate" name="check_in" value="{{ date('Y-m-d\TH:i') }}" placeholder="Check In" />
                        </div>

                        <div class="checkout">
                            <label>Check Out<span class="required">*</label>
                            <input class="input" type="datetime-local" id="checkOutDate" name="check_out" value="{{ date('Y-m-d\TH:i') }}" placeholder="Check Out"/>
                        </div>
                    </div>
            </div>
            <div class="guestinfo">
                        <div class="labelguest">
                        <label class= "guestinfolabel">Guest Information</label>
                        </div>
                        <div class="guestinfomain">
                        <div>
                            <label>Name<span class="required">*</label>
                            <input class="input" type="search" type="text" name="name" required  placeholder="Name" />
                        </div>
            
                        <div>
                            <label>Contact Number<span class="required">*</label>
                            <input class="input" type="search" type="text" name="contact_num" required  placeholder="Contact Number" />
                        </div>

                        <div>
                            <label>Address<span class="required"></label>
                            <input class="input" type="search" type="text" name="address"  placeholder="Address" />
                        </div>

                        <div>
                            <label>Email<span class="required"></label>
                            <input class="input" type="search" type="text" name="email"  placeholder="Email"/>
                        </div>

                        <div>
                            <label>Facebook Name</label>
                            <input class="input" type="search" type="text" name="fbname"  placeholder="Facebook Name"/>
                        </div>

                        <div>
                            <label>Birthday<span class="required"></label>
                            <input class="input" type="date" name="bday"  placeholder="Birthday" />
                        </div>
                        </div>
            </div>
                <div class="arrived">
                        <div>
                            <label>Arrived Companion<span class="required">*</label>
                            <input class="input" type="search" type="number" name="arrived_companion"  required placeholder="Arrived Companion"/>
                        </div>
                </div>
                <!-- for refundable items edited by randel -->
                <div class="refundables">
                    <div class="extralabel">
                        <div>
                            <label class="extralabels">Incidental Deposit</label>
                        </div>

                        
                            <div class="subtowel">
                                <label class="sub">Subtotal Towel:</label>
                                <span id="subTotalTowel">0</span>
                                <input type="hidden" name="subTotalTowel" id="hiddenSubTotalTowel" value="0">
                            </div>
                            <div class="subwristband">
                                <label class="sub">Subtotal Wristband:</label>
                                <span id="subTotalWristband">0</span>
                                <input type="hidden" name="subTotalWristband" id="hiddenSubTotalWristband" value="0">
                            </div>
                        
                        <div>
                            <!-- Total item price-->
                            <div class="totalitem">
                                <label>Total Incidental Deposit:</label>
                                <span id="totalRefundablePrice">0</span>
                                <input type="hidden" name="refundablePrice" id="hiddenTotalRefundablePrice" value="0">
                            </div>                           
                        </div>
                    </div>
                  
                    <!-- Container for refundable items -->
                    <div class="extraextra" id="refundable-container">
                        <!-- Towel and wristband -->
                        <div class="extraitem">
                        @foreach($items as $item)
                            @if ($item['item_name'] === 'Towel')
                                <div>
                                    <label>Item Name</label>
                                    <input class="input" name="item_name" id="item_name1" value="{{ $item['item_name'] }}" readonly>
                                </div>
                                <div>
                                    <label>Quantity</label>
                                    <input class="input" type="number" type="text" name="qty" id="qty_towel" placeholder="Quantity" readonly/>
                                </div>
                                <div>
                                    <label>Amount</label>
                                    <input class="input" type="text" name="price" id="priceInput1" placeholder="Amount" value="{{ $item['price'] }}" readonly />
                                </div>
                            @endif
                        @endforeach
                        </div>
                        <div class="extraitem">
                        @foreach($items as $item)
                            @if ($item['item_name'] === 'Wristband')
                            <div>
                                <label>Item Name</label>
                                <input class="input" name="item_name" id="item_name" value="{{ $item['item_name'] }}" readonly>
                            </div>
                            <div>
                                <label>Quantity</label>
                                <input class="input" type="number" type="text" name="qty" id="qty_wristband" placeholder="Quantity" readonly/>
                            </div>
                    
                            <div>
                                <label>Amount</label>
                                <input class="input" type="text" name="price" id="priceInput" placeholder="Amount" value="{{ $item['price'] }}" readonly/>
                            </div>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </div>    
                <div class="lastleft">
                        <div class="extra">
                            <div class="extralabel">
                                <label class="extralabels">Extra Item</label>
                                <div>
                                    <!-- Total item price-->
                                    <div class="totalitem">
                                        <label>Total Item Amount:</label>
                                        <span id="totalItemPrice">0</span>
                                        <input type="hidden" name="total_itemprice" id="hiddenTotalItemPrice" value="0">
                                    </div>
                                </div>
                                <div class="btnaddextraitem">
                                    <button class="Btn" type="button" id="btnaddextraitem">
                                        <div class="sign">+</div>
                                        <div class="text">Add More Item</div>
                                    </button>
                                </div>
                            </div>

                            <!-- Container for extra items -->
                            <div class="extraextra" id="extra-items-container">
                      
                                <!-- normal extra item -->
                                <div class="extraitem" id="extraitem-template">
                                    <div>
                                        <label>Item Name</label>
                                        <select class="input" name="item_name">
                                            <option value="" disabled selected>Select Item</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item['item_name'] }}" data-price="{{ $item['price'] }}">{{ $item['item_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label>Quantity</label>
                                        <input class="input" type="number" name="qty" placeholder="Quantity"/>
                                    </div>
                                    <div>
                                        <label>Amount</label>
                                        <input class="input" type="text" name="price" placeholder="Amount" readonly/>
                                    </div>
                                    <button class="deleteBtn"><i class="fas fa-trash"></i></button>
                                </div>

                            </div>
                            
                        </div>
                </div>
        </div>
        <div class="mainright">
                    <div class="totalamount">
                        <!-- total fee = (((entrance-child * no-child) + (entrance-adult * no-adult) + (entrance-senior * no-senior)) + total-item-price) - fee-rate% -->
                            <div class="billing">
                                <label>BILLING</label>
                            </div>
                            <div class="base">
                                <div class="basetitle">
                                    <label>Name</label>
                                    <label>Base Amount</label>
                                    <label> Quantity</label>
                                    <label>Subtotal</label>
                                </div>
                                <div class="childbase1">
                                    <label>Child</label>
                                    <span id="childRates">{{ $currentRate['baseChildPrice'] }}</span>
                                    <span id="childQuantity">0</span>
                                    <span id="amountchildrates">0</span>
                                    <input type="hidden" id="hiddenamountchildrates" value="0">
                                </div>
                                <div class="adultbase1">
                                    <label>Adult</label>
                                    <span id="adultRates">{{ $currentRate['baseAdultPrice'] }}</span>
                                    <span id="adultQuantity">0</span>
                                    <span id="amountadultrates">0</span>
                                    <input type="hidden" id="hiddenamountadultrates" value="0">
                                </div>
                                <div class="seniorbase1">
                                    <label>Senior</label>
                                    <span id="seniorRates">{{ $currentRate['baseSeniorPrice'] }}</span>
                                    <span id="seniorQuantity">0</span>
                                    <span id="amountseniorrates">0</span>
                                    <input type="hidden" id="hiddenamountseniorrates" value="0">
                                </div>
                            </div>
                            <div class="vatsc">
                                <div class="vats">
                                    <div class="checkboxvat">
                                        <input type="checkbox" class="ui-checkbox" id="vatCheckbox" onclick="calculateBillingCheckbox(); calculatevalueofvatsctozero(); handleVatCheckboxChange(); " onchange="handleVatCheckboxChange();" checked>
                                    </div>
                                    <div class="vatsss">
                                        <label>VAT</label>
                                        <span id="vat">{{ $currentRate['vat'] }}</span>
                                    </div>
                                </div>
                                <div class="scs">
                                    <div class="checkboxsc">
                                        <input type="checkbox" class="ui-checkbox" id="scCheckbox"  onclick="calculateBillingCheckbox(); calculatevalueofvatsctozero(); handleScCheckboxChange(); " onchange="handleScCheckboxChange();" checked>
                                    </div>
                                    <div class="sccc">
                                        <label>Service Charge</label>
                                        <span id="sc">{{ $currentRate['servicecharge'] }}</span>
                                    </div>
                                </div>


                            </div>
                            <div class="childadultseniors">
                                <div class="vatstitle">
                                    <label>Name</label>
                                    <label>w/ VAT & SC</label>
                                </div>
                                <div class="childwvat">
                                    <label>Child</label>
                                    <span id="childvats">0</span>
                                    <input type="hidden" id="hiddenchildvats" value="0">
                                </div>
                                <div class="adultwvat">
                                    <label>Adult</label>
                                    <span id="adultvats">0</span>
                                    <input type="hidden" id="hiddenadultvats" value="0">
                                </div>
                                <div class="seniorwvat">
                                    <label>Senior</label>
                                    <span id="seniorvats">0</span>
                                    <input type="hidden" id="hiddenseniorvats" value="0">
                                </div>
                            </div>
                            <div class="walkinrate">
                                <div class="totalwithvats">
                                    <label>Total w/ VaT & SC</label>
                                    <span id="totalwithvats">0</span>
                                    <input type="hidden" id="hiddentotalwithvats" value="0">
                                </div>
                                <div class="incidental">
                                    <label>Incidental Deposit</label>
                                    <span id="totalRefundablePrices">0</span>
                                    <input type="hidden" id="hiddenTotalRefundablePrices" value="0">
                                </div>
                                <div class="subss">
                                        <div class="subtowels">
                                            <label class="sub">Subtotal Towel:</label>
                                            <span id="subTotalTowels">0</span>
                                            <input type="hidden" id="hiddenSubTotalTowels" value="0">
                                        </div>
                                        <div class="subwristbands">
                                            <label class="sub">Subtotal Wristband:</label>
                                            <span id="subTotalWristbands">0</span>
                                            <input type="hidden" id="hiddenSubTotalWristbands" value="0">
                                        </div>
                                </div>
                                <div class="prebookamount">
                                    <label>Total Pre-Booking Amount</label>
                                    <span id="totalPreBookAmount">0</span>
                                    <input type="hidden" name="total_prebookAmount" id="hiddenTotalPreBookAmount" value="0">
                                </div>
                                <div class="totalamounts">
                                    <label>Total Amount: </label>
                                    <span id="totalValue">0</span>
                                    <input type="hidden" name="total_amount" id="hiddenTotalAmount" value="0">
                                </div>
                            </div>
                            

                    </div>
                        <!-- dropdown of mode of payment -->
                        <div class="paymentmethod">
                            <label>Select Payment Method <span class="required">*</label>
                            <select class="input" type="search" name="payment_mode" id="paymentMethod" onchange="togglePaymentFields(); updateCommission(this);">
                                <option value="" disabled selected style="display:none;">Select</option>
                                <option value="Cash">Cash</option>
                                <option value="Gcash">GCash</option>
                                <option value="BDO Online">BDO Online</option>
                                <option value="BDO Credit Card">BDO Credit Card</option>
                                <option value="BDO Debit Card">BDO Debit Card</option>
                                <option value="BDO Amex">BDO Amex</option>
                                <option value="RCBC Credit Card">RCBC Credit Card</option>
                                <option value="BPI Credit Card">BPI Credit Card</option>
                                <option value="Website">Website</option>
                                <option value="Complimentary">Complimentary</option>
                                <option value="Gift">Gift Check</option>

                            </select>

                        </div>

                    
                    <div class="payment">

                        <div>
                            <label>Commission</label>
                            <input class="input" type="search" id="commission" name="commission" placeholder="Commission (%)"/>
                        </div>

                        <div>
                            <label>Approval Code</label>
                            <input class="input" type="search"  name="approval_code" placeholder="Approval Code"/>
                        </div>

                        <div>
                            <label>Reference Number</label>
                            <input class="input" type="search"  name="reference_num" placeholder="Reference Number"/>
                        </div>

                        <div>
                            <label>Card Number</label>
                            <input class="input" type="search" name="card_num" placeholder="Card Number"/>
                        </div>

                        <div>
                            <label>Account Name</label>
                            <input class="input" type="search" name="acc_name" placeholder="Account Name"/>
                        </div>

                        <div>
                            <label>File Attachment</label>
                            <input class="input" type="file" name="file_attach" accept=".pdf, .doc, .docx, .jpeg, .png, .jpg"/>
                        </div>

                        <div>
                            <label>Enter Admin Password</label>
                            <input class="input" type="password" name="password_admin" placeholder="Admin Password"/>
                        </div>

                        <div class="BookingConfirmMain">
                            <label class="BookingConfirm" >Booking Confirmation Number</label>
                            <input class="input" type="search" name="confirm_number" placeholder="Booking Confirmation Number"/>
                        </div>

                        <div>
                            <label>Gift Card Number</label>
                            <input class="input" type="search" name="gc_number" placeholder="Gift Card Number"/>
                        </div>

                        <div>
                            <label>Validity</label>
                            <input class="input" type="search" name="validity" placeholder="Validity"/>
                        </div>

                        <div>
                            <label>Worth</label>
                            <input class="input" type="search" name="worth" placeholder="Worth"/>
                        </div>

                        <div>
                            <label>Total Amount Paid <span class="required">*</label>
                            <input class="input" type="search" name="total_amount_paid" required  placeholder="Total Amount Paid"/>
                        </div>

                        <div>
                            <label>Balance</label>
                            <input class="input" type="number" name="balance" id="balance" onchange="updateBalance()" value=0 readonly>
                        </div>
                        
                    </div>

                        <br>

                        
                        <br>

                        <div class="status">
                        <input type="hidden" name="status" id="status" value="">
                        </div>
                        <div class="remarks">
                            <label>Remarks</label>
                            <textarea class="input" type="search" name="remarks"  rows="4" placeholder="Remarks"></textarea>
                        </div>
                        <br>

                        <div>
                            <button class="button1" type="submit" onclick="collectExtraItemData(); confirmSave();">Save Booking</button>
                        </div>

                        <input type="hidden" name="item_names" id="hiddenItemNames" value="">
                        <input type="hidden" name="quantities" id="hiddenQuantities" value="">
                        <input type="hidden" name="prices" id="hiddenPrices" value="">

                        <!-- Add these hidden fields to include rate_name, senior, adult, and child in the form data -->
                        <input type="hidden" name="rate_name" value="{{ $currentRate['rate_name'] }}">
                        <input type="hidden" name="baseChildPrice" value="{{ $currentRate['baseChildPrice'] }}">
                        <input type="hidden" name="baseAdultPrice" value="{{ $currentRate['baseAdultPrice'] }}">
                        <input type="hidden" name="baseSeniorPrice" value="{{ $currentRate['baseSeniorPrice'] }}">
                        <input type="hidden" name="vatsc_childprice" value="{{ $currentRate['vatsc_childprice'] }}">
                        <input type="hidden" name="vatsc_adultprice" value="{{ $currentRate['vatsc_adultprice'] }}">
                        <input type="hidden" name="vatsc_seniorprice" value="{{ $currentRate['vatsc_seniorprice'] }}">
                        <input type="hidden" name="servicecharge" value="{{ $currentRate['servicecharge'] }}">
                        <input type="hidden" name="vat" value="{{ $currentRate['vat'] }}">
                        <input type="hidden" name="returnedTowelQty" value=0>
                        <input type="hidden" name="returnedWristBandQty" value=0>
                        <input type="hidden" name="claimableRefund" id="claimableRefund" value=0>
                        <input type="hidden" name="refundablePrice" id="refundablePrice" value=0>
                        <input type="hidden" name="total_completion" id="totalcompletion1" value=0>
                        <input type="hidden" name="checkin_payment" id="checkin_payment" value=0>
                        <input type="hidden" name="vatCheckbox" id="vatValidator" value=true>
                        <input type="hidden" name="scCheckbox" id="scValidator" value=true>

                    </form>

                     <!-- Container for Pop-up Overlay -->
                     <div id="popupOverlay" class="overlay">
                            <div class="popup">
                                <div>
                                    <label>Enter Admin Password</label>
                                    <input class="input" type="password" name="password_admin" id="password_admin" placeholder="Admin Password" autocomplete="off"/>
                                </div>
                                <div class="butpop">
                                    <div class="confirmpop">
                                        <button class="button3" id="confirmpop" onclick="verifyPassword()">Confirm</button>
                                    </div>
                                    <div class="closepop">
                                        <button class="button2" id="closePopup">Close</button>
                                    </div>
                                    <input type="hidden" id="checkboxtype" value="">
                                </div>
                            </div>
                    </div>

                    <div id="popupOverlayforGift" class="overlay">
                            <div class="popup">
                                <div>
                                    <label>Enter Admin Password</label>
                                    <input class="input" type="password" name="password_admin" id="password_adminforGift" placeholder="Admin Password" autocomplete="off"/>
                                </div>
                                <div class="butpop">
                                    <div class="confirmpop">
                                        <button class="button3" id="confirmpopforGift" onclick="verifyPasswordforGift()">Confirm</button>
                                    </div>
                                    <div class="closepop">
                                        <button class="button2" id="closePopupforGift">Close</button>
                                    </div>
                                    <input type="hidden" id="checkboxtype" value="">
                                </div>
                            </div>
                    </div>
        </div>
</div>

<script>
        //confirmation
        var formSubmitted = false;

        function confirmSave() {
            var form = document.getElementById("book");

            if (!form) {
                alert("Form not found.");
                return;
            }

            var requiredFields = form.querySelectorAll('[required]');
            var missingFields = [];

            for (var i = 0; i < requiredFields.length; i++) {
                var field = requiredFields[i];
                if (!field.value.trim()) {
                    missingFields.push(field.getAttribute('name'));
                }
            }

            if (missingFields.length > 0) {
                alert("Please fill out the following required fields: " + missingFields.join(", "));
                return;
            }

            var result = window.confirm("Do you want to add it?");

            if (result && !formSubmitted) {
                // User clicked "OK" (Yes) and the form hasn't been submitted yet.
                // Set the flag to prevent accidental double submission.
                formSubmitted = true;

                // Submit the form via AJAX or any other method.
                fetch(form.action, {
                    method: form.method,
                    body: new FormData(form),
                })
                .then(response => {
                    if (response.ok) {
                        alert("Submission successful!");
                        // Reload the page after successful submission.
                        location.reload();
                    } else {
                        alert("Form submission failed. Please try again.");
                    }
                })
                .catch(error => {
                    alert("An error occurred while submitting the form.");
                });

                // Add a listener for the "OK" click in the success alert to prevent further submissions.
                form.addEventListener("submit", function(event) {
                    event.preventDefault();
                });
            } else {
                // User clicked "Cancel" (No), prevent the form from submitting and refresh the page.
                form.addEventListener("submit", function(event) {
                    event.preventDefault();
                });
                alert("Cancelled");
            }
        }






    var rateNameSelect = document.getElementById('rateNameSelect');
    var childRateElement = document.getElementById('childRate');
    var adultRateElement = document.getElementById('adultRate');
    var seniorRateElement = document.getElementById('seniorRate');
    var childBaseElement = document.getElementById('childRates');
    var adultBaseElement = document.getElementById('adultRates');
    var seniorBaseElement = document.getElementById('seniorRates');
    var vatElement = document.getElementById('vat');
    var scElement = document.getElementById('sc');

    rateNameSelect.addEventListener('change', function() {
        var selectedRateName = rateNameSelect.value; // Get the selected rate name from the dropdown

        // Use a loop to find the matching rate in the PHP array $entranceRates
        var newChildRate = 0; // Default value if rate is not found
        var newAdultRate = 0; // Default value if rate is not found
        var newSeniorRate = 0; // Default value if rate is not found
        var newBaseChildPrice = 0; // Default value if rate is not found
        var newBaseAdultPrice = 0; // Default value if rate is not found
        var newBaseSeniorPrice = 0; // Default value if rate is not found
        var newVAT = 0; // Default value if rate is not found
        var newServiceCharge = 0; // Default value if rate is not found

        @foreach($entranceRates as $rate)
            if ("{{ $rate->rate_name }}" === selectedRateName) {
                newChildRate = {{ $rate->vatsc_childprice }};
                newAdultRate = {{ $rate->vatsc_adultprice }};
                newSeniorRate = {{ $rate->vatsc_seniorprice }};
                newBaseChildPrice = {{ $rate->baseChildPrice }};
                newBaseAdultPrice = {{ $rate->baseAdultPrice }};
                newBaseSeniorPrice = {{ $rate->baseSeniorPrice }};
                newVAT = {{ $rate->vat }};
                newServiceCharge = {{ $rate->servicecharge }};
            }
        @endforeach

        // Update the child, adult, and senior rate elements with the new values
        childRateElement.textContent = newChildRate.toFixed(3); // Format with two decimal places
        adultRateElement.textContent = newAdultRate.toFixed(3); // Format with two decimal places
        seniorRateElement.textContent = newSeniorRate.toFixed(3); // Format with two decimal places

        // Update the child, adult, and senior base rate elements with the new values
        childBaseElement.textContent = newBaseChildPrice.toFixed(3); // Format with two decimal places
        adultBaseElement.textContent = newBaseAdultPrice.toFixed(3); // Format with two decimal places
        seniorBaseElement.textContent = newBaseSeniorPrice.toFixed(3); // Format with two decimal places

       // Update the VAT and Service Charge elements with the new values as percentages
        vatElement.textContent = (newVAT).toFixed(0) + "%"; // Format as a percentage with two decimal places
        scElement.textContent = (newServiceCharge).toFixed(0) + "%"; // Format as a percentage with two decimal places



          // Update the hidden input fields with the new values
        document.querySelector('input[name="rate_name"]').value = selectedRateName;
        document.querySelector('input[name="baseChildPrice"]').value = newBaseChildPrice.toFixed(3);
        document.querySelector('input[name="baseAdultPrice"]').value = newBaseAdultPrice.toFixed(3);
        document.querySelector('input[name="baseSeniorPrice"]').value = newBaseSeniorPrice.toFixed(3);
        document.querySelector('input[name="vatsc_childprice"]').value = newChildRate.toFixed(3);
        document.querySelector('input[name="vatsc_adultprice"]').value = newAdultRate.toFixed(3);
        document.querySelector('input[name="vatsc_seniorprice"]').value = newSeniorRate.toFixed(3);
        document.querySelector('input[name="servicecharge"]').value = newServiceCharge;
        document.querySelector('input[name="vat"]').value = newVAT;

        // Log the selected rate name and new rates to the console for monitoring
        console.log('Selected Rate Name:', selectedRateName);
        console.log('New Child Rate:', newChildRate.toFixed(3));
        console.log('New Adult Rate:', newAdultRate.toFixed(3));
        console.log('New Senior Rate:', newSeniorRate.toFixed(3));
        console.log('New Child Base Price:', newBaseChildPrice.toFixed(3));
        console.log('New Adult Base Price:', newBaseAdultPrice.toFixed(3));
        console.log('New Senior Base Price:', newBaseSeniorPrice.toFixed(3));
        console.log('New VAT:', newVAT);
        console.log('New Service Charge:', newServiceCharge);

        // Call the calculateBilling function to update other calculations
        calculateBilling();
    });
        // Get references to the elements
        var reservationTypeDropdown = document.getElementById('reservation_type');
        var arrivedSection = document.querySelector('.arrived');
        var refundablesSection = document.querySelector('.refundables');
        var prebookAmount = document.querySelector('.prebookamount');
        var totalCompletion = document.querySelector('.totalCompletion');
        var incidental = document.querySelector('.incidental');
        var totalamount = document.querySelector('.totalamounts');
        var sub = document.querySelector('.subss');
        var date = document.querySelector('.checkinout');
        var datecheckout = document.querySelector('.checkout');
        var arrivedCompanionInput = document.querySelector('input[name="arrived_companion"]');

        // Function to hide or show the prebook and completion amounts based on the reservation type
        function togglePrebookAmount(show) {
            prebookAmount.style.display = show ? 'flex' : 'none';
        }

        // Function to toggle the "required" attribute for arrived companion input
        function toggleArrivedRequired(isRequired) {
            arrivedCompanionInput.required = isRequired;
        }

        // Function to handle changes in reservation type dropdown
        function handleReservationTypeChange() {
            var selectedValue = reservationTypeDropdown.value;
            
            // Show or hide elements based on the selected reservation type
            if (selectedValue === 'Pre-book Inhouse' || selectedValue === 'Pre-book DayTour') {
                arrivedSection.style.display = 'none';
                refundablesSection.style.display = 'none';
                incidental.style.display = 'none';
                totalamount.style.display = 'none';
                sub.style.display = 'none';
                date.style.display = 'flex';
                datecheckout.style.display = 'none';
                togglePrebookAmount(true);
                toggleArrivedRequired(false);
            } else if (selectedValue === 'Walk-in') {
                arrivedSection.style.display = 'flex';
                refundablesSection.style.display = 'block';
                incidental.style.display = 'flex';
                totalamount.style.display = 'flex';
                sub.style.display = 'flex';
                date.style.display = 'flex';
                datecheckout.style.display = 'block';
                togglePrebookAmount(false);
                toggleArrivedRequired(true);
            } else if (selectedValue === 'Open-book') {
                arrivedSection.style.display = 'none';
                refundablesSection.style.display = 'none';
                incidental.style.display = 'none';
                totalamount.style.display = 'none';
                sub.style.display = 'none';
                date.style.display = 'none';
                togglePrebookAmount(true);
                toggleArrivedRequired(false);
            }
        }

        // Add an event listener to the reservation type dropdown for real-time updates
        reservationTypeDropdown.addEventListener('change', handleReservationTypeChange);

        // Initialize the form based on the initial value of the dropdown
        handleReservationTypeChange();



    
        // Get the reservation type select element
        var reservationTypeSelect = document.getElementById('reservation_type');
        // Get the status input field
        var statusInput = document.getElementById('status');

        // Function to update the status based on the selected reservation type
        function updateStatus() {
            if (reservationTypeSelect.value === 'Walk-in') {
                statusInput.value = 'check-in';
            } else if (reservationTypeSelect.value === 'Pre-book Inhouse' || reservationTypeSelect.value === 'Pre-book DayTour') {
                statusInput.value = 'pending';
            } else if (reservationTypeSelect.value === 'Open-book') {
                statusInput.value = 'pending';
            }
        }

        // Add an event listener to the reservation type select element
        reservationTypeSelect.addEventListener('change', updateStatus);

        // Initialize the status input based on the initial value of the reservation type
        updateStatus();

        var checkInDateInput = document.getElementById('checkInDate');
        var checkOutDateInput = document.getElementById('checkOutDate');

        // Add an event listener to the check-in date input field
        checkInDateInput.addEventListener('input', function () {
            // Get the selected check-in date
            var checkInDate = this.value;

            // Set the check-out date to the same value as check-in date
            checkOutDateInput.value = checkInDate;
        });

        function collectExtraItemData() {
            const extraItems = document.querySelectorAll('.extraitem');
            const itemNames = [];
            const quantities = [];
            const prices = [];

            extraItems.forEach(extraItem => {
                const itemNameInput = extraItem.querySelector('[name="item_name"]');
                const quantityInput = extraItem.querySelector('[name="qty"]');
                const priceInput = extraItem.querySelector('[name="price"]');

                const itemName = itemNameInput.value;
                const quantity = quantityInput.value.toString(); // Convert to string
                const price = priceInput.value.toString(); // Convert to string

                // Ensure that both item name and quantity are provided before collecting data
                if (itemName && quantity) {
                    itemNames.push(itemName);
                    quantities.push(quantity);
                    prices.push(price);
                }
            });

            // If no items are selected, provide a default value (e.g., an empty string) for item_names
            const itemNamesValue = itemNames.length > 0 ? itemNames.join(',') : '';

            // Set the values of the hidden input fields with the arrays' values
            document.getElementById('hiddenItemNames').value = itemNamesValue;
            document.getElementById('hiddenQuantities').value = quantities.join(',');
            document.getElementById('hiddenPrices').value = prices.join(',');
        }

        // for dropdown mode of payment
        document.getElementById('paymentMethod').addEventListener('change', function () {
            var selectedMethod = document.getElementById('paymentMethod').value;
            document.getElementById('selectedMethod').textContent = selectedMethod;
        });

        // Calculate the total companion function
        function calculateTotalCompanion() {
            // Get the values from the input fields
            var childrenQty = parseInt(document.querySelector('[name="children_qty"]').value) || 0;
            var adultQty = parseInt(document.querySelector('[name="adult_qty"]').value) || 0;
            var seniorQty = parseInt(document.querySelector('[name="senior_qty"]').value) || 0;

            // Calculate the total companion
            var totalCompanion = childrenQty + adultQty + seniorQty;

            // Update the displayed total companion
            document.getElementById('totalCompanion').textContent = totalCompanion;

            // Update the hidden input field value
            document.getElementById('hiddenTotalCompanion').value = totalCompanion;

            // Update the quantity for towel and wristband based on total companion
            document.getElementById('qty_towel').value = totalCompanion;
            document.getElementById('qty_wristband').value = totalCompanion;

                // Recalculate the total item price
                calculateTotalItemPrice();
        }  
        

                // Add an event listener to the entire document to listen for changes in input fields
            document.addEventListener('input', function (event) {
                if (event.target.matches('.extraitem [name="qty"], .extraitem [name="price"]')) {
                    // If the change occurred in a quantity or price input field within .extraitem
                    calculateTotalItemPrice();
                }
            });

            // Calculate the total refundable price function
            function calculateTotalRefundablePrice() {
                // Get the values from the input fields
                var qty_towel = parseInt(document.getElementById('qty_towel').value);
                var qty_wristband = parseInt(document.getElementById('qty_wristband').value);
                var priceInput1 = parseFloat(document.getElementById('priceInput1').value);
                var priceInput = parseFloat(document.getElementById('priceInput').value);

                // Calculate the total refundable price
                var totalRefundablePrice = (qty_towel * priceInput1) + (qty_wristband * priceInput);

                var subTowel = (qty_towel * priceInput1);

                var subWrist = (qty_wristband * priceInput);

                //Update the display for subtotal
                document.getElementById('subTotalTowel').textContent = subTowel;
                document.getElementById('hiddenSubTotalTowel').textContent = subTowel;

                document.getElementById('subTotalWristband').textContent = subWrist;
                document.getElementById('hiddenSubTotalWristband').textContent = subWrist;

                // Update the displayed total refundable price
                document.getElementById('totalRefundablePrice').textContent = totalRefundablePrice;

                // Update the hidden input field value
                document.getElementById('hiddenTotalRefundablePrice').value = totalRefundablePrice;

                // Update the refundablePrice hidden input field value
                document.getElementById('refundablePrice').value = totalRefundablePrice;
                
                // Recalculate the total item price
                calculateTotalItemPrice();
            }

             // Calculate the total refundable price function
             function calculateTotalRefundablePrices() {
                // Get the values from the input fields
                var qty_towels = parseInt(document.getElementById('qty_towel').value);
                var qty_wristbands = parseInt(document.getElementById('qty_wristband').value);
                var priceInput1s = parseFloat(document.getElementById('priceInput1').value);
                var priceInputs = parseFloat(document.getElementById('priceInput').value);

                // Calculate the total refundable price
                var totalRefundablePrices = (qty_towels * priceInput1s) + (qty_wristbands * priceInputs);

                var subTowels = (qty_towels * priceInput1s);

                var subWrists = (qty_wristbands * priceInputs);

                //Update the display for subtotal
                document.getElementById('subTotalTowels').textContent = subTowels;
                document.getElementById('hiddenSubTotalTowels').textContent = subTowels;

                document.getElementById('subTotalWristbands').textContent = subWrists;
                document.getElementById('hiddenSubTotalWristbands').textContent = subWrists;

                // Update the displayed total refundable price
                document.getElementById('totalRefundablePrices').textContent = totalRefundablePrices;

                // Update the hidden input field value
                document.getElementById('hiddenTotalRefundablePrices').value = totalRefundablePrice;


            }

                // Calculate the total refundable price function
            function calculateTotalCompletion() {
                // Get the values from the input fields
                var completionqty_towels = parseInt(document.getElementById('qty_towel').value);
                var completionqty_wristbands = parseInt(document.getElementById('qty_wristband').value);
                var priceInput1s = parseFloat(document.getElementById('priceInput1').value);
                var priceInputs = parseFloat(document.getElementById('priceInput').value);

                // Calculate the total refundable price
                var totalCompletion = (completionqty_towels * priceInput1s) + (completionqty_wristbands * priceInputs);

                // Update the displayed total refundable price
                document.getElementById('totalCompletion').textContent = totalCompletion;

                // Update the hidden input field value
                document.getElementById('hiddenTotalCompletion').value = totalCompletion;

                // Update the refundablePrice hidden input field value
                document.getElementById('totalcompletion1').value = totalCompletion;
            }

            function calculateTotalItemPrice() {
                var totalItemPrice = 0;
                var extraItemSections = document.querySelectorAll('.extraitem');

                extraItemSections.forEach(function (extraItem) {
                    var itemName = extraItem.querySelector('[name="item_name"]').value;
                    var quantity = parseInt(extraItem.querySelector('[name="qty"]').value) || 0;
                    var price = parseFloat(extraItem.querySelector('[name="price"]').value) || 0;

                    // Skip the computation for Towel and Wristband
                    if (itemName !== "Towel" && itemName !== "Wristband") {
                        totalItemPrice += quantity * price;
                    }
                });

                // Update the displayed total item price and hidden input field
                document.getElementById('totalItemPrice').textContent = totalItemPrice.toFixed(3);
                document.getElementById('hiddenTotalItemPrice').value = totalItemPrice.toFixed(3);

                // Calculate the total amount by adding the rates and total item price
                calculateTotal();

                // Calculate the billing based on total item price
                calculateBilling();
            }


        // Function to toggle the visibility of the Delete button based on the number of extraitem elements
        function toggleDeleteButtonVisibility() {
            var extraItems = document.querySelectorAll('.extraitem');
            var deleteButtons = document.querySelectorAll('.deleteBtn');

            // Hide all Delete buttons by default
            deleteButtons.forEach(function (button) {
                button.style.display = 'none';
            });

            // Show the Delete button for each extraitem except the first one
            for (var i = 1; i < extraItems.length; i++) {
                // Check if deleteButtons[i] is defined before accessing its style property
                if (deleteButtons[i]) {
                    deleteButtons[i].style.display = 'block';
                }
            }
        }

        // Inside the click event listener for the "Add More Item" button
        document.getElementById('btnaddextraitem').addEventListener('click', function () {
            // Clone the extra item template and append it to the container
            var extraItemTemplate = document.getElementById('extraitem-template');
            var clonedExtraItem = extraItemTemplate.cloneNode(true);

            // Clear the input values in the cloned section
            clonedExtraItem.querySelectorAll('input').forEach(function (input) {
                input.value = '';
            });

            // Add a click event listener to the delete button
            clonedExtraItem.querySelector('.deleteBtn').addEventListener('click', function () {
                // Remove the parent extra item when the Delete button is clicked
                this.parentElement.remove();
                calculateTotalItemPrice(); // Recalculate total item price after deletion
                toggleDeleteButtonVisibility(); // Toggle the visibility of the Delete button
            });

            // Append the cloned extra item section to the container
            document.getElementById('extra-items-container').appendChild(clonedExtraItem);

            // Toggle the visibility of the Delete button
            toggleDeleteButtonVisibility();

            // Initialize the event listener for item selection change in the cloned item
            initializeItemSelection(clonedExtraItem);
            
            // Add an event listener for input change (quantity) in the cloned item
            clonedExtraItem.querySelectorAll('[name="qty"]').forEach(function (inputField) {
                inputField.addEventListener('input', calculateTotalItemPrice);
            });
        });


        // Event delegation to handle item selection changes and price updates
        document.getElementById('extra-items-container').addEventListener('change', function (event) {
            // Check if the changed element is a dropdown with the name "item_name"
            if (event.target && event.target.matches('[name="item_name"]')) {
                // Get the selected item's name from the data-price attribute
                const selectedOption = event.target.options[event.target.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                // Find the price input field in the same extra item section
                const priceInput = event.target.parentElement.nextElementSibling.querySelector('[name="price"]');

                // Set the price in the input field
                priceInput.value = price;
            }
        });

        // Function to initialize the event listener for item selection change
        function initializeItemSelection(extraItem) {
            extraItem.querySelector('[name="item_name"]').addEventListener('change', function () {
                // Get the selected item's name from the data-price attribute
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                // Find the price input field in the same extra item section
                const priceInput = extraItem.querySelector('[name="price"]');

                // Set the price in the input field
                priceInput.value = price;
            });
        }

        // Initialize event listeners for item selection change in existing extra items
        var existingExtraItems = document.querySelectorAll('.extraitem');
        existingExtraItems.forEach(function (extraItem) {
            initializeItemSelection(extraItem);
            
            // Add an event listener for input change (quantity) in existing extra items
            extraItem.querySelectorAll('[name="qty"]').forEach(function (inputField) {
                inputField.addEventListener('input', calculateTotalItemPrice);
            });
        });

        // Add the "Delete" button listeners initially
        toggleDeleteButtonVisibility();

        //function to calculate the billing
        function calculateBilling() {

                var childRatess = parseFloat(document.getElementById('childRate').textContent);
                var childRates = parseFloat(document.getElementById('childRates').textContent);
                var adultRatess = parseFloat(document.getElementById('adultRate').textContent);
                var adultRates = parseFloat(document.getElementById('adultRates').textContent);
                var seniorRatess = parseFloat(document.getElementById('seniorRate').textContent);
                var seniorRates = parseFloat(document.getElementById('seniorRates').textContent);
                var childrenQtys = parseInt(document.querySelector('[name="children_qty"]').value) || 0;
                var adultQtys = parseInt(document.querySelector('[name="adult_qty"]').value) || 0;
                var seniorQtys = parseInt(document.querySelector('[name="senior_qty"]').value) || 0; 
                var vat = parseInt(document.getElementById('vat').textContent);
                var sc = parseInt(document.getElementById('sc').textContent);
                var totalchildbase = parseFloat(document.getElementById('hiddenamountchildrates').value) || 0;
                var totaladultbase = parseFloat(document.getElementById('hiddenamountadultrates').value) || 0;
                var totalseniorbase = parseFloat(document.getElementById('hiddenamountseniorrates').value) || 0;
                var totalchildvats = parseFloat(document.getElementById('hiddenchildvats').value) || 0;
                var totaladultvats = parseFloat(document.getElementById('hiddenadultvats').value) || 0;
                var totalseniorvats = parseFloat(document.getElementById('hiddenseniorvats').value) || 0;
                var totalwithvats = parseFloat(document.getElementById('hiddentotalwithvats').value) || 0;
                var totalprebookamount = parseFloat(document.getElementById('hiddenTotalPreBookAmount').value) || 0;
                var totalRefundablePricePrebook = parseFloat(document.getElementById('hiddenTotalRefundablePrice').value) || 0;
                var totalRegularAmount = parseFloat(document.getElementById('hiddenTotalAmount').value) || 0;


                // for pre booking amount
                var totalItemPrice = parseFloat(document.getElementById('hiddenTotalItemPrice').value) || 0;


                var totalchilds = childRates * childrenQtys;
                var totaladults = adultRates * adultQtys;
                var totalseniors = seniorRates * seniorQtys;

                var totalchildvsc = childRatess * childrenQtys;
                var totaladultvsc = adultRatess * adultQtys;
                var totalseniorvsc = seniorRatess * seniorQtys;

                var totalwithvats = totalchildvsc + totaladultvsc + totalseniorvsc;

                var totalprebookamount = totalItemPrice + totalwithvats;



            // Update the displayed baseamounts per companion and hidden input field
            document.getElementById('amountchildrates').textContent = totalchilds.toFixed(3); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenamountchildrates').value = totalchilds.toFixed(3); // Update the hidden input field value

            // Update the displayed baseamounts per companion and hidden input field
            document.getElementById('amountadultrates').textContent = totaladults.toFixed(3); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenamountadultrates').value = totaladults.toFixed(3); // Update the hidden input field value

            // Update the displayed baseamounts per companion and hidden input field
            document.getElementById('amountseniorrates').textContent = totalseniors.toFixed(3); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenamountseniorrates').value = totalseniors.toFixed(3); // Update the hidden input field value
        
            //update with vat and sc
            document.getElementById('childvats').textContent = totalchildvsc.toFixed(3); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenchildvats').value = totalchildvsc.toFixed(3); // Update the hidden input field value
            document.getElementById('adultvats').textContent = totaladultvsc.toFixed(3); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenadultvats').value = totaladultvsc.toFixed(3); // Update the hidden input field value
            document.getElementById('seniorvats').textContent = totalseniorvsc.toFixed(3); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenseniorvats').value = totalseniorvsc.toFixed(3); // Update the hidden input field value
            
            //total with vat and sc
            document.getElementById('totalwithvats').textContent = totalwithvats.toFixed(3);
            document.getElementById('hiddentotalwithvats').value = totalwithvats.toFixed(3);

            // total prebook amount
            document.getElementById('totalPreBookAmount').textContent = totalprebookamount.toFixed(3);
            document.getElementById('hiddenTotalPreBookAmount').value = totalprebookamount.toFixed(3);

            calculateTotal();
            calculateBillingCheckbox();
        }

        // Function to calculate billing with optional VAT and Service Charge
        function calculateBillingCheckbox() {
                var basechild = parseFloat(document.getElementById('childRates').textContent);
                var baseadult = parseFloat(document.getElementById('adultRates').textContent);
                var basesenior = parseFloat(document.getElementById('seniorRates').textContent);
                var childrenQty = parseInt(document.querySelector('[name="children_qty"]').value) || 0;
                var adultQty = parseInt(document.querySelector('[name="adult_qty"]').value) || 0;
                var seniorQty = parseInt(document.querySelector('[name="senior_qty"]').value) || 0;
                var vat = document.getElementById('vatCheckbox').checked ? parseInt(document.getElementById('vat').textContent) : 0;
                var sc = document.getElementById('scCheckbox').checked ? parseInt(document.getElementById('sc').textContent) : 0;
                var totalItemPrice = parseFloat(document.getElementById('hiddenTotalItemPrice').value) || 0;
                var totalRefundablePrice = parseFloat(document.getElementById('hiddenTotalRefundablePrice').value) || 0;
                var totalprebookamount = parseFloat(document.getElementById('hiddenTotalPreBookAmount').value) || 0;

                
                
            // Computation for value of vat and sc per category
            var vatchild = (vat / 100) * basechild;
            var vatadult = (vat / 100)* baseadult;
            // var vatsenior = (vat / 100)* basesenior;

            var scchild = (sc / 100) * basechild;
            var scadult = (sc / 100)* baseadult;
            var scsenior = (sc / 100)* basesenior;

            // Computation for total vat and sc per category
            var vschild = (vatchild * childrenQty) + (scchild * childrenQty) + (basechild * childrenQty);
            var vsadult = (vatadult * adultQty) + (scadult * adultQty) + (baseadult * adultQty);
            var vssenior = (scsenior * seniorQty) + ((basesenior * seniorQty) - ((basesenior * seniorQty) * .20));


            var totalamountvs = vschild + vsadult + vssenior;

            var totalamount = totalamountvs + totalItemPrice + totalRefundablePrice;

            var totalprebookamount = totalamountvs + totalItemPrice;

            
            

            // Update with vat and sc
            document.getElementById('childvats').textContent = vschild.toFixed(3);
            document.getElementById('hiddenchildvats').value = vschild.toFixed(3);
            document.getElementById('adultvats').textContent = vsadult.toFixed(3);
            document.getElementById('hiddenadultvats').value = vsadult.toFixed(3);
            document.getElementById('seniorvats').textContent = vssenior.toFixed(3);
            document.getElementById('hiddenseniorvats').value = vssenior.toFixed(3);

            // Total with vat and sc
            document.getElementById('totalwithvats').textContent = totalamountvs.toFixed(3);
            document.getElementById('hiddentotalwithvats').value = totalamountvs.toFixed(3);

            // Total prebook amount
            document.getElementById('totalPreBookAmount').textContent = totalprebookamount.toFixed(3);
            document.getElementById('hiddenTotalPreBookAmount').value = totalprebookamount.toFixed(3);

            // Update the displayed total amount and hidden input field
            document.getElementById('totalValue').textContent = totalamount.toFixed(3);
            document.getElementById('hiddenTotalAmount').value = totalamount.toFixed(3);

            updateBalance();
        }


        // Attach event listeners to checkboxes to trigger the calculation function
        document.getElementById('vatCheckbox').addEventListener('change', calculateBillingCheckbox);
        document.getElementById('scCheckbox').addEventListener('change', calculateBillingCheckbox);

        // Initial calculation
        calculateBillingCheckbox();

        //add percentage sign
        var vat = document.getElementById('vat');
        var sc = document.getElementById('sc');

        vat.textContent += "%";
        sc.textContent += "%";



        function updateChildQuantity() {
        // Get the quantity input element
        var quantityInput = document.getElementById("childrenQty");
        
        // Get the quantity value
        var quantity = quantityInput.value;
        
        // Update the quantity displayed in the childbase1 section
        var quantitySpan = document.getElementById("childQuantity");
        if (quantitySpan) {
            quantitySpan.textContent = quantity;
        }
        }

        function updateAdultQuantity() {
        // Get the quantity input element
        var quantityInput = document.getElementById("adultQty");
        
        // Get the quantity value
        var quantity = quantityInput.value;
        
        // Update the quantity displayed in the childbase1 section
        var quantitySpan = document.getElementById("adultQuantity");
        if (quantitySpan) {
            quantitySpan.textContent = quantity;
        }
        }

        function updateSeniorQuantity() {
        // Get the quantity input element
        var quantityInput = document.getElementById("seniorQty");
        
        // Get the quantity value
        var quantity = quantityInput.value;
        
        // Update the quantity displayed in the childbase1 section
        var quantitySpan = document.getElementById("seniorQuantity");
        if (quantitySpan) {
            quantitySpan.textContent = quantity;
        }
        }



        // Function to calculate and update the total amount
        function calculateTotal() {
            // Get the values from the input fields
            var childRate = parseInt(document.getElementById('childRate').textContent);
            var adultRate = parseInt(document.getElementById('adultRate').textContent);
            var seniorRate = parseInt(document.getElementById('seniorRate').textContent);
            var childrenQty = parseInt(document.querySelector('[name="children_qty"]').value) || 0;
            var adultQty = parseInt(document.querySelector('[name="adult_qty"]').value) || 0;
            var seniorQty = parseInt(document.querySelector('[name="senior_qty"]').value) || 0;
            var totalItemPrice = parseFloat(document.getElementById('hiddenTotalItemPrice').value) || 0;
            var totalRefundablePrice = parseFloat(document.getElementById('hiddenTotalRefundablePrice').value) || 0;

            // Calculate the total amount as an integer
            var totalAmount = totalRefundablePrice + totalItemPrice + (childRate * childrenQty) + (adultRate * adultQty) + (seniorRate * seniorQty);

            // Update the displayed total amount and hidden input field
            document.getElementById('totalValue').textContent = totalAmount.toFixed(2); // Use toFixed(2) to display 2 decimal places
            document.getElementById('hiddenTotalAmount').value = totalAmount.toFixed(2); // Update the hidden input field value

            updateBalance();
        }

        // Listen for input changes on any of the input fields related to total companion
        var companionInputFields = document.querySelectorAll('[name="children_qty"], [name="adult_qty"], [name="senior_qty"], [name="rate_type"]');
        companionInputFields.forEach(function (inputField) {
            inputField.addEventListener('input', calculateTotalCompanion);
        });

        // Listen for input changes on the quantity and price fields for total item price
        var itemPriceInputFields = document.querySelectorAll('[name="qty"], [name="price"]');
        itemPriceInputFields.forEach(function (inputField) {
            inputField.addEventListener('input', calculateTotalItemPrice);
        });

        // Listen for input changes on the relevant input fields
        var inputFields = document.querySelectorAll('[name="children_qty"], [name="adult_qty"], [name="senior_qty"], [name="qty"], [name="price"]');
        inputFields.forEach(function (inputField) {
            inputField.addEventListener('input', calculateTotal);
        });

        // Call the calculateTotalCompanion, calculateTotalItemPrice, functions initially to set the initial values
        calculateTotalCompanion();
        calculateBilling();
        calculateTotalItemPrice();
        calculateTotal();
        
        function togglePaymentFields() {
            var paymentMethod = document.getElementById("paymentMethod").value;
            var paymentFields = document.getElementsByClassName("payment")[0].getElementsByTagName("div");
            var totalAmountPaidInput = document.getElementsByName("total_amount_paid")[0];
            

            // Hide all payment fields initially
            for (var i = 0; i < paymentFields.length; i++) {
                paymentFields[i].style.display = "none";
            }

            // Show specific fields based on the selected payment method
            if (paymentMethod === "Cash") {
                document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                document.getElementsByName("balance")[0].parentNode.style.display = "block";
            } else if (paymentMethod === "Gcash") {
                document.getElementsByName("reference_num")[0].parentNode.style.display = "block";
                document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                document.getElementsByName("balance")[0].parentNode.style.display = "block";
            } else if (paymentMethod === "BDO Online") {
                document.getElementsByName("commission")[0].parentNode.style.display = "block";
                document.getElementsByName("card_num")[0].parentNode.style.display = "block";
                document.getElementsByName("acc_name")[0].parentNode.style.display = "block";
                document.getElementsByName("reference_num")[0].parentNode.style.display = "block";
                document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                document.getElementsByName("balance")[0].parentNode.style.display = "block";
            } else if (paymentMethod === "BDO Credit Card" || paymentMethod === "RCBC Credit Card" || paymentMethod === "BPI Credit Card" || paymentMethod === "BDO Debit Card" || paymentMethod === "BDO Amex" ) {
                document.getElementsByName("commission")[0].parentNode.style.display = "block";
                document.getElementsByName("card_num")[0].parentNode.style.display = "block";
                document.getElementsByName("approval_code")[0].parentNode.style.display = "block";
                document.getElementsByName("acc_name")[0].parentNode.style.display = "block";
                document.getElementsByName("reference_num")[0].parentNode.style.display = "block";
                document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                document.getElementsByName("balance")[0].parentNode.style.display = "block";
            } else if (paymentMethod === "Website") {
                document.getElementsByName("file_attach")[0].parentNode.style.display = "block";
                document.getElementsByName("confirm_number")[0].parentNode.style.display = "block";
                document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                document.getElementsByName("balance")[0].parentNode.style.display = "block";
            }
            else if (paymentMethod === "Complimentary") {
                document.getElementsByName("password_admin")[0].parentNode.style.display = "block";
                totalAmountPaidInput.parentNode.style.display = "block";
                    // Set total_amount_paid to 0 for Complimentary payment
                    totalAmountPaidInput.value = "0";
            }
            else if (paymentMethod === "Gift") {
                document.getElementsByName("gc_number")[0].parentNode.style.display = "block";
                document.getElementsByName("validity")[0].parentNode.style.display = "block";
                document.getElementsByName("worth")[0].parentNode.style.display = "block";
                document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                document.getElementsByName("balance")[0].parentNode.style.display = "block";    
            }

        // Call the function initially to set the initial state
        togglePayment();
        }

        function togglePayment() {
            var paymentMethods = document.getElementById("paymentMethod");
            var paymentFieldss = document.querySelector(".payment");

            if (paymentMethods.value === "") {
                paymentFieldss.style.display = "none";
            } else {
                paymentFieldss.style.display = "flex";
            }
        }

        // Call the function initially to set the initial state
        togglePayment();

        


        // Function to calculate and update the balance
        function updateBalance() {
            // Get the selected value from the "Reservation Type" dropdown
            var reservationType = document.getElementById('reservation_type').value;
            
            // Get references to the input fields and parse their values as integers
            var totalAmountElement = document.getElementById('totalValue');
            var totalAmount = parseFloat(totalAmountElement.textContent);
            
            var totalAmountPaidInput = document.querySelector('input[name="total_amount_paid"]');
            var totalAmountPaid = parseFloat(totalAmountPaidInput.value) || 0; // Use parseInt and handle NaN gracefully
            
            // Calculate the balance based on the selected "Reservation Type"
            var totalBalance = 0;

            if (reservationType === "Walk-in") {
                totalBalance = totalAmount - totalAmountPaid;
            } else if (reservationType === "Pre-book Inhouse" || reservationType === "Pre-book DayTour") {
                var totalPreBookAmount = parseFloat(document.getElementById('totalPreBookAmount').textContent);
                totalBalance = totalPreBookAmount - totalAmountPaid;
            } else if (reservationType === "Open-book") {
                var totalPreBookAmount = parseFloat(document.getElementById('totalPreBookAmount').textContent);
                totalBalance = totalPreBookAmount - totalAmountPaid;
            } 

            
            // Set the balance value as an input field or element
            var balanceElement = document.getElementById('balance');
            
            if (balanceElement.tagName === 'INPUT') {
                balanceElement.value = totalBalance.toFixed(3); // If it's an input field, set the value
            } else {
                balanceElement.textContent = totalBalance.toFixed(3);// If it's a non-input element, set the text content
            }
        }

        // Select the Reservation Type dropdown element
        var reservationTypeDropdown = document.getElementById('reservation_type');

        // Add an event listener to detect changes in the Reservation Type dropdown
        reservationTypeDropdown.addEventListener('change', updateBalance);

        // Select the "total amount paid" input element
        var totalAmountPaidInput = document.querySelector('input[name="total_amount_paid"]');

        // Add an event listener to detect changes in the "total amount paid" input
        totalAmountPaidInput.addEventListener('input', updateBalance);

        // Select the "total amount" element
        var totalAmountElement = document.getElementById('totalValue');

        // Add an event listener to detect changes in the "total amount"
        totalAmountElement.addEventListener('input', updateBalance);

        // Initial calculation (if needed)
        updateBalance();


        // Verify admin password
        var plaintextArray = [];

        // Loop through the users and add their plaintext values to the array
        @foreach($users as $user)
            @if($user->role_id == 1)
                plaintextArray.push("{{ $user->plaintext }}");
            @endif
        @endforeach

        // Function to handle the "Confirm" button click
        function verifyPassword() {
            // Get the entered passwordAdmin value from the input field
            const passwordAdmin = document.getElementById("password_admin").value.trim(); // Trim leading and trailing spaces

            // Check if passwordAdmin exists in the plaintextArray (case-insensitive comparison)
            const foundMatch = plaintextArray.some(function (plaintext) {
                return plaintext.trim().toLowerCase() === passwordAdmin.toLowerCase();
            });

            var checkboxTypeInput = document.getElementById("checkboxtype");
            var vatCheckbox = document.getElementById("vatCheckbox");
            var scCheckbox = document.getElementById("scCheckbox");

            var vat = document.getElementById("vatValidator");
            var sc = document.getElementById("scValidator");

            if (foundMatch) {
                if (checkboxTypeInput.value === "VAT") {
                    vatCheckbox.checked = !foundMatch;
                    vat.value = !foundMatch;
                } else if (checkboxTypeInput.value === "SC") {
                    scCheckbox.checked = !foundMatch;
                    sc.value = !foundMatch;
                }
            } else {
                // Handle the case where no match was found
                if (checkboxTypeInput.value === "VAT") {
                    vatCheckbox.checked = true;
                    vat.value = true
                } else if (checkboxTypeInput.value === "SC") {
                    scCheckbox.checked = true;
                    sc.value = true
                }
            }

            // Check if either VAT or SC checkbox is unchecked
            if (!vatCheckbox.checked || !scCheckbox.checked) {
                // Run calculateBillingCheckbox if either of them is unchecked
                calculateBillingCheckbox();
            }

            // Hide the popup by setting its display to "none"
            document.getElementById("popupOverlay").style.display = "none";

            // Clears the password
            var passwordAdminInput = document.getElementById("password_admin");
            passwordAdminInput.value = "";

            calculateBillingCheckbox();
        }

        

        // Function to handle vat checkbox changes
        function handleVatCheckboxChange() {
            var checkbox = document.getElementById("vatCheckbox");

            if (!checkbox.checked) {
                document.getElementById("popupOverlay").style.display = "block";
                var checkboxTypeInput = document.getElementById("checkboxtype");
                checkboxTypeInput.value = "VAT";
            } else {
                document.getElementById("popupOverlay").style.display = "none";
            }
        }

        // Function to handle sc checkbox changes
        function handleScCheckboxChange() {
            var checkbox = document.getElementById("scCheckbox");

            if (!checkbox.checked) {
                document.getElementById("popupOverlay").style.display = "block";
                var checkboxTypeInput = document.getElementById("checkboxtype");
                checkboxTypeInput.value = "SC";
            } else {
                document.getElementById("popupOverlay").style.display = "none";
            }
        }


        // Close Popup Button
        document.getElementById("closePopup").addEventListener("click", function () {
            document.getElementById("popupOverlay").style.display = "none";
            var checkbox = document.getElementById("vatCheckbox");
            checkbox.checked = true;

            // Clears the password
            var passwordAdminInput = document.getElementById("password_admin");
            passwordAdminInput.value = "";

            calculateBillingCheckbox();
        });

          // Close Popup Button
          document.getElementById("closePopup").addEventListener("click", function () {
            document.getElementById("popupOverlay").style.display = "none";
            var checkbox = document.getElementById("scCheckbox");
            checkbox.checked = true;

            // Clears the password
            var passwordAdminInput = document.getElementById("password_admin");
            passwordAdminInput.value = "";

            calculateBillingCheckbox();
        });
        
        
        // Get the "Gift" option element 
        const giftOption = document.getElementById("paymentMethod").options[11];

        // Add an onchange event listener to the dropdown
        document.getElementById("paymentMethod").addEventListener("change", function() {
            if (this.value === "Gift") {
                // Show the password verification popup
                document.getElementById("popupOverlayforGift").style.display = "block";
            } else {
                // Hide the popup for other options
                document.getElementById("popupOverlayforGift").style.display = "none";
            }
        });


        function verifyPasswordforGift() {
            // Get the entered passwordAdmin value from the input field
            const passwordAdmin = document.getElementById("password_adminforGift").value.trim(); // Trim leading and trailing spaces

            // Check if passwordAdmin exists in the plaintextArray (case-insensitive comparison)
            const foundMatch = plaintextArray.some(function (plaintext) {
                return plaintext.trim().toLowerCase() === passwordAdmin.toLowerCase();
            });

            
            if (foundMatch) {
                if (paymentMethod === "Gift") {
                    document.getElementsByName("gc_number")[0].parentNode.style.display = "block";
                    document.getElementsByName("validity")[0].parentNode.style.display = "block";
                    document.getElementsByName("worth")[0].parentNode.style.display = "block";
                    document.getElementsByName("total_amount_paid")[0].parentNode.style.display = "block";
                    document.getElementsByName("balance")[0].parentNode.style.display = "block";    
                }
            } else{
                document.querySelector(".payment").style.display = "none";
                // Reset the select element to its default value
                document.getElementById("paymentMethod").value = "";
                
            }
            
            // Hide the popup by setting its display to "none"
            document.getElementById("popupOverlayforGift").style.display = "none";

            // Clears the password
            var passwordAdminInput = document.getElementById("password_adminforGift");
            passwordAdminInput.value = "";

        }
        
        // Add a click event listener to the "Close" button to hide the popup
        document.getElementById("closePopupforGift").addEventListener("click", function() {
            document.getElementById("popupOverlayforGift").style.display = "none";
            document.getElementById("paymentMethod").value = "";
            document.querySelector(".payment").style.display = "none";

            // Clears the password
            var passwordAdminInput = document.getElementById("password_adminforGift");
            passwordAdminInput.value = "";
        });


        function updateCommission(selectElement) {
        var selectedOptionValue = selectElement.value;

        // Get the "Commission" input element
        var commissionInput = document.getElementById("commission");

        // Loop through the $commissions array
        @foreach ($commissions as $commission)
            if ("{{ $commission->bank_name }}" === selectedOptionValue) {
                // Set the commission input value based on the selected option
                commissionInput.value = "{{ $commission->bank_commission_percentage }}";
                return; // Exit the loop once a match is found
            }
        @endforeach

        // If no match is found, clear the commission input
        commissionInput.value = "";
    }



</script>
@endsection