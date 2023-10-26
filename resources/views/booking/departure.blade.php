@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/departure.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Pre-bookings List</h1>
    </div>
    
    <div class="upper">
        <div class="containerUsers">
           Check-out
        </div>

        <div class="containerArchives">
            <a class="archive" href="{{ auth()->user()->role_id === 1 ? route('prebook.checkin') : route('reguser.checkin') }}">Check-in</a>
        </div>
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
                <th>Arrived Companion</th>
                <th>Incidental Amt</th>
                <th>Total Amount</th>
                <th>Returned Towel Qty</th>
                <th>Returned Wristband Qty</th>
                <th>Total Amount Paid</th>
                <th>Total Claimed Refund</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
            $reversedBookings = $bookings->reverse();
            @endphp
            @foreach($reversedBookings as $booking)
                @foreach($booking->billings as $billing)
                @if($booking->reservation_type == 'Pre-book Inhouse' || $booking->reservation_type == 'Pre-book DayTour')
                <tr>
                    <td>{{$billing->name}}</td>
                    <td>{{$billing->bday}}</td>
                    <td>{{$billing->arrived_companion}}</td>
                    <td>{{$billing->refundablePrice }}</td>
                    <td>{{$billing->total_amount}}</td>
                    <td>{{$billing->returnedTowelQty ?? 0}}</td>
                    <td>{{$billing->returnedWristBandQty ?? 0}}</td>
                    <td>{{$billing->total_amount_paid}}</td>
                    <!-- default zero, will updated when refund is clicked -->
                    <td>{{ $billing->claimableRefund ?? 0.00}}</td>
                    <td>{{ $billing->status}}</td>
                    <td>{{ $billing->remarks }}</td>
                    <td>
                        
                        <div class="popup">
                            <button class="css-button">...</button>
                            <div class="popup-content">
                                    <form action="{{ auth()->user()->role_id === 1 ? route('prebook.departure_edit', ['billing' => $billing->id]) : route('reguser.departure_edit', ['billing' => $billing->id]) }}">
                                    <button class="popup-button">Update</button>
                                </form>
                                <form action="{{ auth()->user()->role_id === 1 ? route('refund.edit', ['billing' => $billing->id]) : route('reguserrefund.edit', ['billing' => $billing->id]) }}">
                                    <button class="popup-button">Refund</button>
                                </form>
                            </div>
                        </div>
                    </td>

                </tr>
                @endif
                @endforeach
            @endforeach
    </tbody>
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

                <div class="paginated-users" data-total="{{ $paginatedBookings->total() }}"></div>

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

        
</script>

@endsection