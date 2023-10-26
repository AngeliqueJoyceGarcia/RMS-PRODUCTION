@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Users List</h1>
        <div class="btnContainer">
            <a href="{{ route('users.create') }}" class="btnAddNewUser" >Add New User</a>
        </div>
    </div>
    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-container" id="popupContainer">
      <div class="usertitle"> Add New User</div>
      <hr>
        <form method="POST" action="{{route('users.update', ['user' => $user->id])}}">
            @csrf
            @method('PUT')

            <div class="user-details">

            <div class="form-group">
                    <span class="userdetails" for="firstname">First Name:<span class="required">(Required)</span></span>
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required value="{{ $user->firstname }}">
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="lastname">Last Name:<span class="required">(Required)</span></span>
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required value="{{ $user->lastname }}">
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="email">Email:<span class="required">(Required)</span></span>
                    <input type="text" id="email" name="email" placeholder="Email" required value="{{ $user->email }}">
            </div> 

            <div class="form-group">
                    <span class="userdetails" for="password">Password:<span class="required">(Required)</span></span>
                    <input type="text" id="password" name="password" placeholder="Password" required value="{{ $user->password }}">
                    <button class="genpass" type="button" id="generatePassword">Generate New Password</button>
            </div> 

            <div class="form-group">
                <span class="userdetails" for="is_active">Status<span class="required">(Required)</span></span>
                <select id="is_active" name="is_active" required>
                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>Hold</option>
                </select>

            </div>

            <div class="form-group">
                <label for="role_id">Role:<span class="required">(Required)</span></label>
                <select id="role_id" name="role_id" required>
                    
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                            {{ $role->role_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            </div>
            
            <br>
            <button class="UserButtonClose" href="{{ route('users.read') }}"> <span>Cancel</span></button>
            <button class="UserButton" type="submit"> <span>Add</span></button>
            </form>
    </div>

    <div class="upper">
        <div class="containerUsers">
            <span class="circlebackground">{{ isset($users) ? count($users) : 0 }}</span> Users
        </div>

        <div class="containerArchives">
            <a class="archive" href="{{ route('users.archives')}}">Deleted</a>
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
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Status</th>
            <th>Role Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->firstname}}</td>
                <td>{{$user->lastname}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->password}}</td>
                <td>{{$user->is_active ? 'Active' : 'Hold'}}</td>
                <td>{{ $user->role->role_name }}</td>
                <td>
                <div class="popup">
                    <button class="css-button">...</button>
                    <div class="popup-content">
                        <form action="{{ route('users.edit', ['user' => $user->id]) }}">
                            <button class="popup-button">Edit</button>
                        </form>
                        <form action="{{ route('users.destroy', ['user' => $user]) }}" method="POST">
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

                <div class="paginated-users" data-total="{{ $paginatedUsers->total() }}"></div>
                <div class="usershowpage">Showing {{ $paginatedUsers->firstItem() }} to {{ $paginatedUsers->lastItem() }} of {{ $paginatedUsers->total() }} users</div>

            </div>    
    </div>
</div>


    <!-- generate new password -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const generatePasswordButton = document.getElementById("generatePassword");
            const passwordInput = document.getElementById("password");

            generatePasswordButton.addEventListener("click", function() {
                // Generate a random password with a minimum length of 8 characters
                const randomPassword = generateRandomPassword(8);

                // Set the generated password in the password input field
                passwordInput.value = randomPassword;
            });

            function generateRandomPassword(length) {
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                let password = "";

                for (let i = 0; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * charset.length);
                    password += charset.charAt(randomIndex);
                }

                return password;
            }
        });
    </script>

   <!-- For closing -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.UserButtonClose').on('click', function(event) {
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

</div>

@endsection