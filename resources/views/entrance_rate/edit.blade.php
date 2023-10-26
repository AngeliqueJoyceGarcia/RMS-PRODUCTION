@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/entrancerate.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Entrance Rates List</h1>
        <div class="btnContainer">
            <a href="{{ route('rates.create') }}" class="btnAddNewEntrance" >Add New Rate</a>
        </div>
    </div>

    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-container" id="popupContainer">
      <div class="userEntrance"> Add New Rate</div>
      <hr>
        <form method="POST" action="{{ (route('rates.update', ['rate' => $rate->id])) }}" >
            @csrf
            @method('PUT')

            <div class="Entrance-details">

                <div class="form-group">
                    <span class="Entrancedetails" for="rate_name">Entrance Rate Name:<span class="required">(Required)</span></span>
                    <input type="text" id="rate_name" name="rate_name" placeholder="Entrance Rate Name" value="{{ old('rate_name', $rate->rate_name) }}" required>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="baseChildPrice">Base Child Price:<span class="required">(Required)</span></span>
                    <input type="text" id="baseChildPrice" name="baseChildPrice" placeholder="Base Child Price" value="{{ old('baseChildPrice', $rate->baseChildPrice) }}" required>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="baseAdultPrice">Base Adult Price:<span class="required">(Required)</span></span>
                    <input type="text" id="baseAdultPrice" name="baseAdultPrice" placeholder="Base Adult Price" value="{{ old('baseAdultPrice', $rate->baseAdultPrice) }}" required>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="baseSeniorPrice">Base Senior Price:<span class="required">(Required)</span></span>
                    <input type="text" id="baseSeniorPrice" name="baseSeniorPrice" placeholder="Base Senior Price" value="{{ old('baseSeniorPrice', $rate->baseSeniorPrice) }}" required>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="vat">VAT %:<span class="required">(Required)</span></span>
                    <input type="text" id="vat" name="vat" placeholder="VAT (ex. 10)" value="{{ old('vat', $rate->vat) }}" required>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="servicecharge">Service Charge %:<span class="required">(Required)</span></span>
                    <input type="text" id="servicecharge" name="servicecharge" placeholder="Service Charge (ex. 10)" value="{{ old('servicecharge', $rate->servicecharge) }}" required>
                </div>
                
                
                <div class="form-group">
                    <span class="Entrancedetails" for="vatsc_childprice">w/ VAT & SC Child:</span>
                    <input type="text" id="vatsc_childprice" name="vatsc_childprice" placeholder="VAT SC Child Price" required readonly>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="vatsc_adultprice">w/ VAT & SC Adult:</span>
                    <input type="text" id="vatsc_adultprice" name="vatsc_adultprice" placeholder="VAT SC Adult Price" required readonly>
                </div>
                
                <div class="form-group">
                    <span class="Entrancedetails" for="vatsc_seniorprice">w/ SC Senior/PWD:</span>
                    <input type="text" id="vatsc_seniorprice" name="vatsc_seniorprice" placeholder=" SC Senior Price" required readonly>
                </div>
            </div>
            
            <button class="EntranceButtonClose" href="{{ route('rates.read') }}"> <span>Cancel</span></button>
            <button class="EntranceButton" type="submit"> <span>Edit</span></button>
            </form>
    </div>


    
    <div class="upper">
        <div class="containerEntrance">
            <span class="circlebackground">{{ isset($rates) ? count($rates) : 0 }}</span> Rates
        </div>

        <div class="containerArchives">
            <a class="archive" href="{{ route('rates.archives')}}">Deleted</a>
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
            <th>Rate Name</th>
            <th>Base Price: Child</th>
            <th>Base Price: Adult</th>
            <th>Base Price: Senior/PWD</th>
            <th>Service Charge</th>
            <th>VATs</th>
            <th>Total Rate: Child</th>
            <th>Total Rate: Adult</th>
            <th>Total Rate: Senior/PWD</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rates as $rate)
            <tr>
                <td>{{ $rate->rate_name }}</td>
                <td>{{ $rate->baseChildPrice }}</td>
                <td>{{ $rate->baseAdultPrice}}</td>
                <td>{{ $rate->baseSeniorPrice}}</td>
                <td>{{ $rate->servicecharge }}</td>
                <td>{{ $rate->vat}}</td>
                <td>{{ $rate->vatsc_childprice}}</td>
                <td>{{ $rate->vatsc_adultprice}}</td>
                <td>{{ $rate->vatsc_seniorprice}}</td>
                <td>
                    <div class="popup">
                        <button class="css-button">...</button>
                        <div class="popup-content">
                            <form action="{{ route('rates.edit', ['rate' => $rate->id]) }}">
                                <button class="popup-button">Edit</button>
                            </form>
                            <form action="{{ route('rates.destroy', ['rate' => $rate]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="popup-button delete-button" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </td>
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

                <div class="paginated-Entrance" data-total="{{ $paginatedEntrance->total() }}"></div>
                <div class="Entranceshowpage">Showing {{ $paginatedEntrance->firstItem() }} to {{ $paginatedEntrance->lastItem() }} of {{ $paginatedEntrance->total() }} entrance rates</div>

            </div>    
    </div>
</div>

<!-- For closing -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.EntranceButtonClose').on('click', function(event) {
                event.preventDefault(); 
                window.location.href = $(this).attr('href'); 
            });
        });
    </script>

<!-- pagination -->
<script>
                const itemsPerPage = 11; // Number of items to display per page
                const maxDisplayedPageNumbers = 3; // Maximum number of displayed page numbers
                let currentPage = 1; // Current active page

                const tableRows = document.querySelectorAll('.tablecontainer tbody tr');
                const pageNumbersContainer = document.querySelector('.page-numbers');
                const prevButton = document.querySelector('.prev-button');
                const nextButton = document.querySelector('.next-button');

                 // Function to calculate VAT SC prices
                 function calculatePrices() {
                    // Get input values
                    
                    var vat = parseFloat($('#vat').val()) || 0;
                    var servicecharge = parseFloat($('#servicecharge').val()) || 0;
                    var baseChildPrice = parseFloat($('#baseChildPrice').val()) || 0;
                    var baseAdultPrice = parseFloat($('#baseAdultPrice').val()) || 0;
                    var baseSeniorPrice = parseFloat($('#baseSeniorPrice').val()) || 0;
                     // Calculate VAT and service charge amounts
                     var vatAmountchild = (vat / 100) * baseChildPrice;
                    var serviceChargeAmountchild = (servicecharge / 100) * baseChildPrice;

                    var vatAmountadult = (vat / 100) * baseAdultPrice;
                    var serviceChargeAmountadult = (servicecharge / 100) * baseAdultPrice;

                    // var vatAmountsenior = (vat / 100) * baseSeniorPrice;
                    var serviceChargeAmountsenior = (servicecharge / 100) * baseSeniorPrice;

                    // var baseSeniorPrice = (baseAdultPrice - (.20 * baseAdultPrice)).toFixed(2);

                    // Calculate VAT SC prices
                    var vatsc_childprice = (baseChildPrice + vatAmountchild + serviceChargeAmountchild).toFixed(3);
                    var vatsc_adultprice = (baseAdultPrice + vatAmountadult + serviceChargeAmountadult).toFixed(3);
                    var vatsc_seniorprice = ((baseSeniorPrice - (0.20 * baseSeniorPrice)) + serviceChargeAmountsenior).toFixed(3);

                    // Update the input fields
                    $('#vatsc_childprice').val(vatsc_childprice);
                    $('#vatsc_adultprice').val(vatsc_adultprice);
                    $('#vatsc_seniorprice').val(vatsc_seniorprice);
                    // $('#baseSeniorPrice').val(baseSeniorPrice);
                    
                    calculatePrices();
                }

                // Bind the calculatePrices function to input change events
                $('#vat, #servicecharge, #baseChildPrice, #baseAdultPrice, #baseSeniorPrice').on('input', calculatePrices);

                // Initial calculation
                calculatePrices();

                

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

                function updateEntranceShowPage() {
                const paginatedEntrance = document.querySelector('.paginated-Entrance');
                const firstItem = (currentPage - 1) * itemsPerPage + 1;
                const lastItem = Math.min(currentPage * itemsPerPage, paginatedEntrance.dataset.total);

                const EntranceShowPage = document.querySelector('.Entranceshowpage');
                EntranceShowPage.textContent = `Showing ${firstItem} to ${lastItem} of ${paginatedEntrance.dataset.total} entrance rates`;
            }

            function handlePageChange(page) {
                currentPage = page;
                showPage(currentPage);

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === pageNumbersContainer.children.length;

                updatePagination();
                updateEntranceShowPage(); // Call the new function to update the usershowpage
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
                                  XLSX.writeFile(wb, "EntranceRates.xlsx");
                              });
                          });

        
</script>


@endsection