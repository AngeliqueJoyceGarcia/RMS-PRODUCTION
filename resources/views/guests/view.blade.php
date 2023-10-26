@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/guest.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Guest List</h1>
    </div>

    
    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-container" id="popupContainer">
      <div class="userExtra"> Guest Details</div>
      <hr>
    <form action="" method="POST">
        @csrf
        @method('POST')
        <div class="extra-details">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Name" value="{{ $guest->name }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="fbname">Facebook Name:</label>
                    <input type="text" id="fbname" name="fbname" placeholder="Facebook Name" value="{{ $guest->fbname }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="bday">Birthday:</label>
                    <input type="text" id="bday" name="bday" placeholder="Birthday" value="{{ $guest->bday }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="contact_num">Contact Number:</label>
                    <input type="text" id="contact_num" name="contact_num" placeholder="Contact Number" value="{{ $guest->contact_num }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Address" value="{{ $guest->address }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" placeholder="Email" value="{{ $guest->email }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="reservation_type">Reservation Type:</label>
                    <input type="text" id="reservation_type" name="reservation_type" placeholder="Reservation Type" value="{{ $guest->booking->reservation_type }}" readonly>
                </div>

                <div class="form-group">
                    <label for="totalCompanion">Total Companion:</label>
                    <input type="text" id="totalCompanion" name="totalCompanion" placeholder="Total Companion" value="{{ $guest->booking->total_companion }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" id="status" name="status" placeholder="Status" value="{{ $guest->booking->status }}" readonly>
                </div>
                
              
                
                <div class="form-group">
                    <label for="arrivedCompanion">Arrived Companion:</label>
                    <input type="text" id="arrivedCompanion" name="arrivedCompanion" placeholder="Arrived Companion" value="{{ $guest->booking->arrived_companion }}" readonly>
                </div>
            
        </div>

        <br>
    </form>
    <a href="{{ auth()->user()->role_id === 1 ?  route('guest.read') : route('reguserguests.read') }}" class="ExtraButtonClose"><span>Close</span></a>
    </div>
    
    <div class="upper">
        <div class="containerGuests">
            <span class="circlebackground">{{ isset($guests) ? count($guests) : 0 }}</span> Guests
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
            <th>Facebook Name</th>
            <th>Birthday</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Reservation Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($guests as $guest)
            <tr>
                <td>{{$guest->name}}</td>
                <td>{{$guest->fbname}}</td>
                <td>{{$guest->bday}}</td>
                <td>{{$guest->contact_num}}</td>
                <td>{{$guest->address}}</td>
                <td>{{$guest->reservation_type}}</td>
                <td>{{$guest->status}}</td>
                <td>
                    <div class="popup">
                        <button class="css-button">...</button>
                        <div class="popup-content">
                                <button class="popup-button">View</button>
                                <button class="popup-button">Edit</button>
                                <button class="popup-button delete-button" type="submit">Delete</button>
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

                <div class="paginated-guests" data-total="{{ $paginatedGuests->total() }}"></div>
                <div class="guestshowpage">Showing {{ $paginatedGuests->firstItem() }} to {{ $paginatedGuests->lastItem() }} of {{ $paginatedGuests->total() }} guests</div>

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
