@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/extracharge.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


<div class="body">
    <div class="container1">
        <h1>Extra Items</h1>
    </div>
    
    <div class="upper">
        <div class="containerExtras">
            <span class="circlebackground">{{ isset($extras) ? count($extras) : 0 }}</span> Extra Items
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
            <th>Item Name</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($extras as $extra)
            <tr>
                <td>{{ $extra->item_name }}</td>
                <td>{{ $extra->price }}</td>
             
            </tr>
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

                <div class="paginated-extras" data-total="{{ $paginatedExtras->total() }}"></div>
                <div class="extrashowpage">Showing {{ $paginatedExtras->firstItem() }} to {{ $paginatedExtras->lastItem() }} of {{ $paginatedExtras->total() }} extra items</div>

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

                function updateExtrasShowPage() {
                const paginatedExtras = document.querySelector('.paginated-extras');
                const firstItem = (currentPage - 1) * itemsPerPage + 1;
                const lastItem = Math.min(currentPage * itemsPerPage, paginatedExtras.dataset.total);

                const extraShowPage = document.querySelector('.extrashowpage');
                extraShowPage.textContent = `Showing ${firstItem} to ${lastItem} of ${paginatedExtras.dataset.total} extra items`;
            }

            function handlePageChange(page) {
                currentPage = page;
                showPage(currentPage);

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === pageNumbersContainer.children.length;

                updatePagination();
                updateExtraShowPage(); // Call the new function to update the usershowpage
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
                                  XLSX.writeFile(wb, "ExtraItemsList.xlsx");
                              });
                          });

        
</script>


@endsection