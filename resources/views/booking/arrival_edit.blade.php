@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/arrivaldep.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Pre-bookings List</h1>

    </div>

    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-container" id="popupContainer">
      <div class="usertitle"> Check-in details</div>    
      <form method="POST" action="{{ auth()->user()->role_id === 1 ? route('prebook.arival_update', ['billing' => $billing->id]) : route('reguser.arival_update', ['billing' => $billing->id]) }}">
        @csrf
        @method('PUT')
        <div class="user-details">

            <div class="form-group">
                    <span class="userdetails">Name:<span class="required"></span></span>
                    <input type="text" name="name" value="{{$billing->name}}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Birthday:<span class="required"></span></span>
                    <input type="text" name="birthday" value="{{$billing->bday }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Expected Children:<span class="required"></span></span>
                    <input type="number" name="expected_children" value="{{$billing->children_qty }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Expected Adult:<span class="required"></span></span>
                    <input type="number" name="expected_adult" value="{{$billing->adult_qty }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Expected Senior:<span class="required"></span></span>
                    <input type="number" name="expected_senior" value="{{$billing->senior_qty }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Arrived Companion:<span class="required">*</span></span>
                    <input type="number" name="arrived_companion" value="{{$billing->arrived_companion}}" min="0" max="{{$billing->total_companion}}">
            </div> 

            <div class="form-group">
                    <span class="userdetails">Towel Subtotal:<span class="required"></span></span>
                    <input type="number" name="towel_subtotal" value="0" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Wristband Subtotal:<span class="required"></span></span>
                    <input type="number" name="wristband_subtotal" value="0" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Incidental Total Amount:<span class="required"></span></span>
                    <input type="number" name="towel_subtotal" value="{{ number_format($billing->refundablePrice, 3, '.', '') }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Balance:<span class="required"></span></span>
                    <input type="number" name="wristband_subtotal" value="{{ number_format($billing->balance, 3) }}" readonly>
            </div> 

            <div class="form-group">
                    <span class="userdetails">Check-in Payment:<span class="required">*</span></span>
                    <input type="number" name="checkin_payment" value="{{ $billing->checkin_payment }}" min="{{ $billing->refundablePrice + $billing->balance }}" max="{{ $billing->refundablePrice + $billing->balance }}">
            </div> 

            <div class="form-group">
                    <span class="userdetails"for="remarks">Remarks:</span>
                    <input type="text" id="remarks" name="remarks" value="{{$billing->remarks}}">
            </div> 

            <input type="hidden" name="total_amount_paid" value="{{$billing->total_amount_paid}}">
            <input type="hidden" name="status" value="check-in">


            </div>
            <br>
            <a href="{{ auth()->user()->role_id === 1 ?  route('prebook.checkin') : route('reguser.checkin') }}" class="UserButtonClose"><span>Cancel</span></a>
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

    // for subtotal of towel and wristband 
    function calculateAndSetSubtotals() {
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

        // Calculate initial subtotals when the page loads
        var totalCompanion = {{ $billing->total_companion }};
        var TowelSubtotal = totalCompanion * towelPrice;
        var WristbandSubtotal = totalCompanion * wristbandPrice;

        // Update the input values with the initial subtotals
        document.querySelector('input[name="towel_subtotal"]').value = TowelSubtotal.toFixed(3);
        document.querySelector('input[name="wristband_subtotal"]').value = WristbandSubtotal.toFixed(3);
    }

    // Call the function when the page loads
    calculateAndSetSubtotals();

        
</script>
@endsection