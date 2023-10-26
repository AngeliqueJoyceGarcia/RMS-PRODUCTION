@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')


@section('main_content')
<link rel="stylesheet" href="{{ asset('css/refund.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <div class="conbooking">
        <div class="containerBooking">
             <span>Refund Details</span>
        </div>
    </div>
        <div class="body">
            <div>
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
            </div>
            <div class="container">
                <form id="refund" method="POST" action="{{ auth()->user()->role_id === 1 ? route('refund.update', ['billing' => $bill->id]) : route('reguserrefund.update', ['billing' => $bill->id]) }}">
                    @csrf 
                    @method('PUT')
                    <div class="billingdetails">
                        <div class="card-header">
                            <span class="guestname">{{$bill->name}}</span>
                        </div>
                        <div class="checkinout">
                            <span>{{$bill->check_in}}</span>
                            <span>{{$bill->check_out}}</span>
                        </div>
                        <div class="name">
                            <span class="reservationtype">{{$bill->reservation_type}}</span>
                        </div>
                        <div class="childadultsenior">
                            <div>
                                <label>Children</label>
                                <span>{{$bill->children_qty}}</span>
                            </div>
                            <div>
                                <label>Adult</label>
                                <span>{{$bill->adult_qty}}</span>
                            </div>
                            <div>
                                <label>Senior Citizen</label>
                                <span>{{$bill->senior_qty}}</span>
                            </div>
                        </div>
                        <div class="totalcom">
                            <div>
                                <label>Total Companion</label>
                                <span>{{$bill->total_companion}}</span>
                            </div>
                            <div>
                                <label>Arrived Companion</label>
                                <span>{{$bill->arrived_companion}}</span>
                            </div>
                        </div>
                        <div class="container1">
                            <div>
                                <div class="title">
                                    <label>Base Price</label>
                                </div>
                                <div class="content">
                                    <label>Child: </label>
                                    <span>{{$bill->baseChildPrice}}</span>
                                </div>

                                <div class="content">
                                    <label>Adult: </label>
                                    <span>{{$bill->baseAdultPrice}}</span>
                                </div>

                                <div class="content">
                                    <label>Senior: </label>
                                    <span>{{$bill->baseSeniorPrice}}</span>
                                </div>
                            </div>
                            <div class="title">
                                <div class="vatscTitle">
                                    <label>With Vat & SC</label>
                                </div>

                                <div class="content">
                                    <label>VAT: </label>
                                    <span>{{$bill->vat}}</span>
                                </div>

                                <div class="content">
                                    <label>Service Charge: </label>
                                    <span>{{$bill->service_charge}}</span>
                                </div>
                                <div class="content">
                                    <label>Child: </label>
                                    <span>{{$bill->vatsc_childprice}}</span>
                                </div>

                                <div class="content">
                                    <label>Adult: </label>
                                    <span>{{$bill->vatsc_adultprice}}</span>
                                </div>

                                <div class="content">
                                    <label>Senior: </label>
                                    <span>{{$bill->vatsc_seniorprice}}</span>
                                </div>
                            </div>
                            <div>
                                <div class="title">
                                    <label>Refundable Items</label>
                                </div>
                                <div class="content">
                                    <label>Towel Quantity: </label>
                                    <span id="towelQuantity">{{$bill->total_companion}}</span>
                                </div>

                                <div class="content">
                                    <label>Wristband Quantity: </label>
                                    <span id="wristbandQuantity">{{$bill->total_companion}}</span>
                                </div>

                                <div class="refund">
                                    <label>Returned Towel Quantity</label>
                                    <input type="number" id="returnedTowelQuantity" name="returnedTowelQuantity" min="0" max="{{$bill->total_companion}}" value="{{$bill->returnedTowelQty}}">
                                </div>

                                <div class="refund">
                                    <label>Returned Wristband Quantity</label>
                                    <input type="number" id="returnedWristbandQuantity" name="returnedWristbandQuantity" max="{{$bill->total_companion}}" min="0" value="{{$bill->returnedWristBandQty}}">
                                </div>

                                <div class="refund">
                                    <label>Total Incidental Amount: </label>
                                    <span id="totalRefunded">{{$bill->refundablePrice}}</span>
                                </div>

                                <div class="refund">
                                    <label>Claimable Refund: </label>
                                    <span id="claimableRefund">0</span>
                                </div>

                                
                            </div>
                            <div class="totalItemPrice">
                                <label>Total Item Amount<span class="extra">(Extra Items)</label>
                                <span class="totalAmountValue">{{$bill->total_itemprice}}</span>
                            </div>
                            <div class="totalAmount">
                                <label>Total Amount</label>
                                <span id="initialTotalAmount">{{$bill->total_amount}}</span>
                            </div>
                            <div class="line"></div>

                            <div class="conmode">
                            <div class="modedetails">
                                <div>
                                    <label>Payment Mode</label>
                                    <span>{{$bill->payment_mode}}</span>
                                </div>
                            </div>
                            </div>
                            <div class="totalpaid">
                                <div>
                                    <label>Total Amount Paid</label>
                                    <span id="totalAmountPaid">{{$bill->total_amount_paid}}</span>
                                </div>
                            </div>
                            
                        </div>
                        <div class="footer">
                            <span>{{$bill->rate_name}}</span>
                        </div>

                        <div>
                            <button type="button" class="button1" onclick="confirmSave()">Save</button>
                        </div>
                    </div>

                    <input type="hidden" id="claimableRefundInput" name="claimableRefund" value="">
                    <input type="hidden" id="totalAmountInput" name="totalAmount" value="">
                    <input type="hidden" id="totalAmountPaidInput" name="totalAmountPaid" value="">
                    <input type="hidden" name="status" value="check-out">

                </form>
            </div>
        </div>

        <script>

        //confirmation
        var formSubmitted = false;

        function confirmSave() {
            var form = document.getElementById("refund");

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

            var result = window.confirm("Do you want to submit it?");

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
                
            // Function to calculate the claimable refund amount
            function calculateClaimableRefund() {
                const totalRefunded = parseFloat(document.getElementById('totalRefunded').textContent);
                const totalQuantity = parseInt(document.getElementById('towelQuantity').textContent) + parseInt(document.getElementById('wristbandQuantity').textContent);
                const returnedTowelQuantity = parseInt(document.getElementById('returnedTowelQuantity').value);
                const returnedWristbandQuantity = parseInt(document.getElementById('returnedWristbandQuantity').value);

                let claimableRefund = 0; // Initialize claimable refund amount

                // Check if returnedTowelQuantity has a value and calculate refund accordingly
                if (!isNaN(returnedTowelQuantity)) {
                    claimableRefund += returnedTowelQuantity * 1000;
                }

                // Check if returnedWristbandQuantity has a value and calculate refund accordingly
                if (!isNaN(returnedWristbandQuantity)) {
                    claimableRefund += returnedWristbandQuantity * 200;
                }

                // Update the claimableRefund based on total refunded
                claimableRefund = Math.min(claimableRefund, totalRefunded);

                document.getElementById('claimableRefund').textContent = claimableRefund.toFixed(2); // Show the result with 2 decimal places
            
                // Set the value of the hidden input field
                document.getElementById('claimableRefundInput').value = claimableRefund.toFixed(2);

            }

            // Attach event listeners to the input fields to update on change
            document.getElementById('returnedTowelQuantity').addEventListener('input', calculateClaimableRefund);
            document.getElementById('returnedWristbandQuantity').addEventListener('input', calculateClaimableRefund);

            // Initial calculation
            calculateClaimableRefund();


            // Initialize the initial total amount
            let initialTotalAmount = parseFloat(document.getElementById('initialTotalAmount').textContent);

            // Function to calculate the total amount
            function calculateTotalAmount() {
                const totalRefunded = parseFloat(document.getElementById('totalRefunded').textContent);
                const totalTowelQuantity = parseInt(document.getElementById('towelQuantity').textContent);
                const totalWristbandQuantity = parseInt(document.getElementById('wristbandQuantity').textContent);
                const returnedTowelQuantity = parseInt(document.getElementById('returnedTowelQuantity').value) || 0;
                const returnedWristbandQuantity = parseInt(document.getElementById('returnedWristbandQuantity').value) || 0;
                
                // Calculate the claimable refund
                const totalQuantity = totalTowelQuantity + totalWristbandQuantity;
                const perItemRefund = totalRefunded / totalQuantity;
                const claimableRefund = parseFloat(document.getElementById('claimableRefund').textContent);

                // Calculate the total amount by subtracting claimable refund from initial total amount
                const totalAmount = initialTotalAmount - claimableRefund;

                // Update the total amount with 2 decimal places
                document.getElementById('initialTotalAmount').textContent = totalAmount.toFixed(2);
                
                // Set the value of the hidden input field
                document.getElementById('totalAmountInput').value = totalAmount.toFixed(2);
            }

            // Function to update the total amount when input fields change
            function updateTotalAmountOnChange() {
                calculateTotalAmount();
            }

            // Attach event listeners to the input fields
            document.getElementById('returnedTowelQuantity').addEventListener('input', updateTotalAmountOnChange);
            document.getElementById('returnedWristbandQuantity').addEventListener('input', updateTotalAmountOnChange);


            // Initialize the initial total amount paid
            let initialTotalAmountPaid = parseFloat(document.getElementById('totalAmountPaid').textContent);

            // Function to calculate the total amount paid
            function calculateTotalAmountPaid() {
                const totalRefunded = parseFloat(document.getElementById('totalRefunded').textContent);
                const totalTowelQuantity = parseInt(document.getElementById('towelQuantity').textContent);
                const totalWristbandQuantity = parseInt(document.getElementById('wristbandQuantity').textContent);
                const returnedTowelQuantity = parseInt(document.getElementById('returnedTowelQuantity').value) || 0;
                const returnedWristbandQuantity = parseInt(document.getElementById('returnedWristbandQuantity').value) || 0;

                // Calculate the claimable refund
                const totalQuantity = totalTowelQuantity + totalWristbandQuantity;
                const perItemRefund = totalRefunded / totalQuantity;
                const claimableRefund = parseFloat(document.getElementById('claimableRefund').textContent);

                // Calculate the total amount by subtracting claimable refund from initial total amount
                const totalAmount = initialTotalAmountPaid - claimableRefund;

                // Update the total amount with 2 decimal places
                document.getElementById('totalAmountPaid').textContent = totalAmount.toFixed(2);

                // Set the value of the hidden input field
                document.getElementById('totalAmountPaidInput').value = totalAmount.toFixed(2);
            }

            // Function to update the total amount when input fields change
            function updateTotalAmountPaidOnChange() {
                calculateTotalAmountPaid();
            }

            // Attach event listeners to the input fields
            document.getElementById('returnedTowelQuantity').addEventListener('input', updateTotalAmountPaidOnChange);
            document.getElementById('returnedWristbandQuantity').addEventListener('input', updateTotalAmountPaidOnChange);
        

        
        </script>

@endsection