@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/guest.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Refund</h1>
    </div>
    
    <div class="upper">
        <div class="containerGuests">
            <span class="circlebackground">{{ isset($paginatedBillings) ? count($paginatedBillings) : 0 }}</span> Guests
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
            <th>Total Companion</th>
            <th>Towel Quantity</th>
            <th>Wristband Quantity</th>
            <th>Returned Towel Quantity</th>
            <th>Returned Wristband Quantity</th>
            <th>Total Refund Price</th> 
            <th>Total Amount</th> 
            <th>Total Amount Paid</th> 
            <th>Refund</th> 
        </tr>
    </thead>
    <tbody>
        @php
            // Reverse the order of $billings to show the latest one first
            $reversedBillings = $billings->reverse();
            @endphp
            @foreach($reversedBillings as $billing)
      
            <tr>
                <td>{{$billing->name}}</td>
                <td>{{$billing->total_companion}}</td>
                <td>{{$billing->total_companion}}</td>
                <td>{{$billing->total_companion}}</td>
                <td>{{$billing->returnedTowelQty}}</td>
                <td>{{$billing->returnedWristBandQty}}</td>
                <td>{{$billing->refundablePrice}}</td> 
                <td>{{$billing->total_amount}}</td> 
                <td>{{$billing->total_amount_paid}}</td> 
                <td>
                    <a class="css-button" href="{{ auth()->user()->role_id === 1 ? route('refund.edit', ['billing' => $billing->id]) : route('reguserrefund.edit', ['billing' => $billing->id]) }}">Refund</a>
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
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

                function updateGuestShowPage() {
                const paginatedGuests = document.querySelector('.paginated-guests');
                const firstItem = (currentPage - 1) * itemsPerPage + 1;
                const lastItem = Math.min(currentPage * itemsPerPage, paginatedGuests.dataset.total);

                const guestShowPage = document.querySelector('.guestshowpage');
                guestShowPage.textContent = `Showing ${firstItem} to ${lastItem} of ${paginatedGuests.dataset.total} guests`;
            }

            function handlePageChange(page) {
                currentPage = page;
                showPage(currentPage);

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === pageNumbersContainer.children.length;

                updatePagination();
                updateGuestShowPage(); // Call the new function to update the usershowpage
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
                                  XLSX.writeFile(wb, "GuestList.xlsx");
                              });
                          });

        
</script>


@endsection
