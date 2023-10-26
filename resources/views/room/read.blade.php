@extends('adminmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/room.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


<div class="container1">
        <h1>Room List</h1>
        <div class="btnContainer">
            <a href="{{ route('room.create') }}" class="btnAddNewRoom" id="openPopup">Add New Room</a>
        </div>
    </div>

    <div class="upper">
        <div class="containerRooms">
            <span class="circlebackground">{{ isset($rooms) ? count($rooms) : 0 }}</span> Rooms
        </div>

        <div class="containerArchives">
            <a class="archive" href="{{ route('room.archives')}}">Archives</a>
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
                              <th>No.</th>
                              <th>Room Name</th>
                              <th>Price</th>
                              <th>Description</th>
                              <th>Capacity</th>
                              <th>Images</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($rooms as $room)
                              <tr>
                                  <td>{{ $room->id }}</td>
                                  <td>{{ $room->roomname }}</td>
                                  <td>{{ $room->roomprice }}</td>
                                  <td>{{ $room->roomdescription }}</td>
                                  <td>{{ $room->roomcapacity }}</td>
                                  <td>
                                  @if($room->images)
                                      @php $imageUrls = json_decode($room->images, true); @endphp
                                      @foreach($imageUrls as $index => $imageUrl)
                                              <img src="{{ $imageUrl }}" alt="Room Image" width="100">
                                          </a>
                                      @endforeach
                                  @endif
                                  </td>
                                  <td>{{$room->status->name}}</td>
                                  <td>
                                        <form action="{{ route('room.edit', ['room' => $room->id]) }}">
                                            <button class="css-button">Edit</button>
                                        </form>
                                        <form action="{{ route('room.destroy', ['room' => $room]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="css-button delete-button" type="submit">Delete</button>
                                        </form>
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

                <div class="paginated-rooms" data-total="{{ $paginatedRooms->total() }}"></div>
                <div class="roomshowpage">Showing {{ $paginatedRooms->firstItem() }} to {{ $paginatedRooms->lastItem() }} of {{ $paginatedRooms->total() }} rooms</div>

            </div>    
            
    </div>


<!-- pagination -->
<script>
                const itemsPerPage = 5; // Number of items to display per page
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

                function updateRoomShowPage() {
                const paginatedRooms = document.querySelector('.paginated-rooms');
                const firstItem = (currentPage - 1) * itemsPerPage + 1;
                const lastItem = Math.min(currentPage * itemsPerPage, paginatedRooms.dataset.total);

                const roomShowPage = document.querySelector('.roomshowpage');
                roomShowPage.textContent = `Showing ${firstItem} to ${lastItem} of ${paginatedRooms.dataset.total} rooms`;
            }

            function handlePageChange(page) {
                currentPage = page;
                showPage(currentPage);

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === pageNumbersContainer.children.length;

                updatePagination();
                updateRoomShowPage(); // Call the new function to update the roomshowpage
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
                                  XLSX.writeFile(wb, "table_data.xlsx");
                              });
                          });
</script>
@endsection