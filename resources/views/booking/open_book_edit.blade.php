@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/arrivaldep.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Open-bookings List</h1>

    </div>

    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-container" id="popupContainer">
      <div class="usertitle"> Open-book details</div>    
      <form method="POST" action="{{ auth()->user()->role_id === 1 ? route('openbook.update', ['billing' => $billing->id]) : route('reguser.openbook.update', ['billing' => $billing->id]) }}">
        @csrf
        @method('PUT')

        <div class="forrate">
            <div class="first-group">
                <div class="ratetitle">
                    <label class="Rate">Rate:</label>
                    <select class = "inputrate" id="rateNameSelect" name="rateNameSelect">
                        @foreach($rates as $rate)
                            <option value="{{ $rate->rate_name }}" {{ $billing->rate_name == $rate->rate_name ? 'selected' : '' }}>
                                {{ $rate->rate_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="rateinfos">
                <div class="rateinfo">
                        <div>
                            <label>Base Child Rate: </label>
                            <span id="basechildRate">{{ $billing->baseChildPrice }}</span>
                        </div>
                        <div>
                            <label>Base Adult Rate: </label>
                            <span id="baseadultRate">{{ $billing->baseAdultPrice }}</span>
                        </div>
                        <div>
                            <label>Base Senior Rate: </label>
                            <span id="baseseniorRate">{{ $billing->baseSeniorPrice }}</span>
                        </div>
                        
                </div>
                <div class="rateinfo1">
                        <div>
                            <label>Child Rate: </label>
                            <span id="childRate">{{ $billing->vatsc_childprice }}</span>
                        </div>
                        <div>
                            <label>Adult Rate: </label>
                            <span id="adultRate">{{ $billing->vatsc_adultprice }}</span>
                        </div>
                        <div>
                            <label>Senior Rate: </label>
                            <span id="seniorRate">{{ $billing->vatsc_seniorprice }}</span>
                        </div>

                </div>
            </div>
                    <div class="ratevatsc">
                    <div>
                        <label>Vat: </label>
                        <span id="vat">{{ $billing->vat }}</span>
                    </div>
                    <div>
                        <label>Service Charge: </label>
                        <span id="sc">{{ $billing->service_charge }}</span>
                    </div>
                    </div>
                    <input type="hidden" id="childRateInput" name="childRate" value="{{ $billing->baseChildPrice }}">
                    <input type="hidden" id="adultRateInput" name="adultRate" value="{{ $billing->baseAdultPrice }}">
                    <input type="hidden" id="seniorRateInput" name="seniorRate" value="{{ $billing->baseSeniorPrice }}">
                    <input type="hidden" id="vatInput" name="vat" value="{{ $billing->vat }}">
                    <input type="hidden" id="scInput" name="serviceCharge" value="{{ $billing->service_charge }}">
                    <input type="hidden" id="childRateVATInput" name="childRateVAT" value="{{ $billing->vatsc_childprice }}">
                    <input type="hidden" id="adultRateVATInput" name="adultRateVAT" value="{{ $billing->vatsc_adultprice }}">
                    <input type="hidden" id="seniorRateVATInput" name="seniorRateVAT" value="{{ $billing->vatsc_seniorprice }}">

            </div>
            
            <div class="user-details">

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Name:<span class="required"></span></span>
                    <input type="text" name="name" value="{{$billing->name}}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Birthday:<span class="required"></span></span>
                    <input type="text" name="birthday" value="{{$billing->bday }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Expected Children:<span class="required"></span></span>
                    <input type="number" name="expected_children" value="{{$billing->children_qty }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Expected Adult:<span class="required"></span></span>
                    <input type="number" name="expected_adult" value="{{$billing->adult_qty }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Expected Senior:<span class="required"></span></span>
                    <input type="number" name="expected_senior" value="{{$billing->senior_qty }}" readonly>
            </div> 


            <div class="form-group">
                    
                    <label>Check In<span class="required">*</label>
                    <input class="input" type="datetime-local" id="checkInDate" name="check_in" value="{{ $billing->check_in }}" placeholder="Check In" required/>
                
            </div>

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Total Amount:</span></span>
                    <input type="number" name="total_amount" value="{{$billing->total_amount }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Total Amount Paid:</span></span>
                    <input type="number" name="total_amount_paid" value="{{$billing->total_amount_paid }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="pre_booking_amount">Balance:<span class="required"></span></span>
                    <input type="number" name="balance" value="{{$billing->balance }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails"for="remarks">Remarks:<span class="required">*</span>
                    <input type="text" id="remarks" name="remarks" value="{{$billing->remarks}}">
            </div> 

            <input type="hidden" name="total_amount_paid" value="{{$billing->total_amount_paid}}">

            </div>

            <br>
            <a href="{{ auth()->user()->role_id === 1 ?  route('openbook.read') : route('reguser.openbook') }}" class="UserButtonClose"><span>Cancel</span></a>
            <button class="UserButton" type="submit"> <span>Update</span></button>
    </form>
    </div>
    
    <div class="upper">
        

        
    </div>

    <div class="container">
        <div class="container2">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search...">
            </div>
            <div class="downloadexcel">
                <button id="downloadButton">
                    <i class="fas fa-download download-icon"></i> Excel
                </button>
            </div>
        </div>
        <br>

    <div class="tablecontainer">
    <table>
    <thead>
        <tr>
                <th>Name</th>
                <th>Birthday</th>
                <th>Total Companion</th>
                <th>Arrived Companion</th>
                <th>Pre-Booking Amt</th>
                <th>Incidental Amt</th>
                <th>Total Amount</th>
                <th>Total Amount Paid</th>
                <th>Total Claimed Refund</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
       
    </table>
    </div>
            <div class="pagecontainer">
                
                <div class="pagination" id="pagination-container">
                    <button class="prev-button" disabled>Previous</button>
                    <div class="page-numbers">
                        <span class="page-number">1</span>
                        <span class="page-number">2</span>
                        <span class="page-number">3</span>
                    </div>
                    <button class="next-button">Next</button>
                </div>

                

            </div>    
    </div>
</div>

<!-- pagination -->
<script>
                const itemsPerPage = 11; // Number of items to display per page
                const maxDisplayedPageNumbers = 3; // Maximum number of displayed page numbers
                let currentPage = 1; // Current active page

                const tableRows = document.querySelectorAll('.tablecontainer tbody tr');
                const pageNumbersContainer = document.querySelector('.page-numbers');
                const prevButton = document.querySelector('.prev-button');
                const nextButton = document.querySelector('.next-button');

                function showPage(page) {
                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = startIndex + itemsPerPage;

                    tableRows.forEach((row, index) => {
                        if (index >= startIndex && index < endIndex) {
                            row.style.display = 'table-row';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                function updatePagination() {
                    pageNumbersContainer.innerHTML = ''; // Clear existing page numbers

                    const pageCount = Math.ceil(tableRows.length / itemsPerPage);
                    let startPage = currentPage - Math.floor(maxDisplayedPageNumbers / 2);
                    if (startPage < 1) {
                        startPage = 1;
                    }
                    const endPage = Math.min(startPage + maxDisplayedPageNumbers - 1, pageCount);

                    for (let i = startPage; i <= endPage; i++) {
                        const pageNumber = document.createElement('span');
                        pageNumber.classList.add('page-number');
                        pageNumber.textContent = i;
                        pageNumber.addEventListener('click', () => {
                            handlePageChange(i);
                        });
                        pageNumbersContainer.appendChild(pageNumber);
                    }
                }

                function handlePageChange(page) {
                    currentPage = page;
                    showPage(currentPage);

                    prevButton.disabled = currentPage === 1;
                    nextButton.disabled = currentPage === pageNumbersContainer.children.length;

                    updatePagination();
                }

                prevButton.addEventListener('click', () => {
                    if (currentPage > 1) {
                        handlePageChange(currentPage - 1);
                    }
                });

                nextButton.addEventListener('click', () => {
                    if (currentPage < pageNumbersContainer.children.length) {
                        handlePageChange(currentPage + 1);
                    }
                });

                function updateUserShowPage() {
                const paginatedUsers = document.querySelector('.paginated-users');
                const firstItem = (currentPage - 1) * itemsPerPage + 1;
                const lastItem = Math.min(currentPage * itemsPerPage, paginatedUsers.dataset.total);

                const userShowPage = document.querySelector('.usershowpage');
                userShowPage.textContent = `Showing ${firstItem} to ${lastItem} of ${paginatedUsers.dataset.total} users`;
            }

            function handlePageChange(page) {
                currentPage = page;
                showPage(currentPage);

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === pageNumbersContainer.children.length;

                updatePagination();
                updateUserShowPage(); // Call the new function to update the usershowpage
            }

                // Initial setup
                showPage(currentPage);
                prevButton.disabled = true;
                updatePagination();


    // For Searching data in the table
                    document.addEventListener("DOMContentLoaded", function() {
                          const searchInput = document.getElementById("searchInput");
                          const tableRows = document.querySelectorAll(".tablecontainer table tbody tr");

                          searchInput.addEventListener("input", function() {
                              const searchTerm = searchInput.value.toLowerCase();

                              tableRows.forEach(row => {
                                  const rowData = row.textContent.toLowerCase();
                                  if (rowData.includes(searchTerm)) {
                                      row.style.display = "table-row";
                                  } else {
                                      row.style.display = "none";
                                  }
                              });
                          });
                      });


    // Download excel
                        document.addEventListener("DOMContentLoaded", function() {
                              const downloadButton = document.getElementById("downloadButton");
                              
                              downloadButton.addEventListener("click", function() {
                                  const table = document.querySelector(".tablecontainer table");
                                  const ws = XLSX.utils.table_to_sheet(table);
                                  const wb = XLSX.utils.book_new();
                                  XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                                  XLSX.writeFile(wb, "UsersList.xlsx");
                              });
                          });

    // for rates
    var rateNameSelect = document.getElementById('rateNameSelect');
    var childRateElement = document.getElementById('childRate');
    var adultRateElement = document.getElementById('adultRate');
    var seniorRateElement = document.getElementById('seniorRate');
    var childBaseElement = document.getElementById('childRates');
    var adultBaseElement = document.getElementById('adultRates');
    var seniorBaseElement = document.getElementById('seniorRates');
    var vatElement = document.getElementById('vat');
    var scElement = document.getElementById('sc');

    // Define the updateRate function
    function updateRate() {
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

        @foreach($rates as $rate)
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

        // Update the hidden input fields with the new values
        document.getElementById('basechildRate').textContent = newBaseChildPrice.toFixed(3);
        document.getElementById('baseadultRate').textContent = newBaseAdultPrice.toFixed(3);
        document.getElementById('baseseniorRate').textContent = newBaseSeniorPrice.toFixed(3);
        document.getElementById('childRate').textContent = newChildRate.toFixed(3);
        document.getElementById('adultRate').textContent = newAdultRate.toFixed(3);
        document.getElementById('seniorRate').textContent = newSeniorRate.toFixed(3);
        document.getElementById('vat').textContent = newVAT + '%';
        document.getElementById('sc').textContent = newServiceCharge + '%';

        // Update the hidden input fields with the new values
        document.getElementById('childRateInput').value = newBaseChildPrice.toFixed(3);
        document.getElementById('adultRateInput').value = newBaseAdultPrice.toFixed(3);
        document.getElementById('seniorRateInput').value = newBaseSeniorPrice.toFixed(3);
        document.getElementById('vatInput').value = newVAT;
        document.getElementById('scInput').value = newServiceCharge;
        document.getElementById('childRateVATInput').value = newChildRate.toFixed(3);
        document.getElementById('adultRateVATInput').value = newAdultRate.toFixed(3);
        document.getElementById('seniorRateVATInput').value = newSeniorRate.toFixed(3);

        calculateTotalAmountAndBalance();
    }

    // Attach the updateRate function to the 'change' event of rateNameSelect
    rateNameSelect.addEventListener('change', updateRate);
    // to call the function initially
    updateRate();

    function calculateTotalAmountAndBalance() {
        // Get the values from the HTML elements
        var childRate = parseFloat(document.getElementById('childRate').textContent);
        var adultRate = parseFloat(document.getElementById('adultRate').textContent);
        var seniorRate = parseFloat(document.getElementById('baseseniorRate').textContent);
        var sc = parseFloat(document.getElementById('sc').textContent);
        var expectedChildren = parseInt(document.getElementsByName('expected_children')[0].value);
        var expectedAdult = parseInt(document.getElementsByName('expected_adult')[0].value);
        var expectedSenior = parseInt(document.getElementsByName('expected_senior')[0].value);
        var totalAmountPaid = parseFloat(document.getElementsByName('total_amount_paid')[0].value);

        var serviceChargeAmountsenior = (sc / 100) * seniorRate;
        var finalSeniorRate = ((seniorRate - (0.20 * seniorRate)) + serviceChargeAmountsenior).toFixed(3)

        // Calculate the total amount
        var totalAmount = (childRate * expectedChildren) + (adultRate * expectedAdult) + (finalSeniorRate * expectedSenior);

        // Calculate the balance
        var balance = totalAmount - totalAmountPaid;

        // Update the input fields with the calculated values
        document.getElementsByName('total_amount')[0].value = totalAmount.toFixed(3);
        document.getElementsByName('balance')[0].value = balance.toFixed(3);
    }

    // Call the calculateTotalAmountAndBalance function when needed, e.g., on page load or when inputs change
    calculateTotalAmountAndBalance();

        
</script>
@endsection