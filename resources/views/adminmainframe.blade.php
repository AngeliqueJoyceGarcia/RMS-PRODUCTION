<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel = "stylesheet" href="{{ asset('css/mainframe.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
 
    <!-- The script tag below is for booking create to enable the retrieving of data from db -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> 
    <title>Resort</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bxs-home-alt-2'></i>
            <span class="logo-name"> Resort </span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="{{route('admin.index')}}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link-name"> Dashboard </span>
                </a>
                <ul class="sub-menu">
                    <li> <a  class="link-name" href="{{route('admin.index')}}">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bx-receipt' ></i>
                        <span class="link-name"> Reception </span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li> <a  class="link-name" href="#">Reception</a></li>
                    <li> <a href="{{route('booking.create')}}">Booking</a></li>
                    <li> <a href="{{route('refund.read')}}">Refund</a></li>
                    <li> <a href="{{route('prebook.checkin')}}">Check-In/Check-Out</a></li>
                    <li> <a href="{{route('openbook.read')}}">Open-book Tracker</a></li>
                    <li> <a href="{{route('resched.read')}}">Reshedule</a></li>
                    
                </ul> 
            </li>
            <li>
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bx-book-reader'></i>
                        <span class="link-name"> View </span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li> <a  class="link-name" href="#">View</a></li>
                  
                    <li> <a href="{{route('booking.read')}}">Booking Details</a></li>
                    <li> <a href="{{route('billing.read')}}">Billing</a></li>
                    <li> <a href="{{route('calendar.read')}}">Calendar</a></li>
                    <li> <a href="{{route('extras.view')}}">Extra Items</a></li>
                    <li> <a href="{{route('rates.view')}}">Entrance Fee</a></li>

                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bx-collection' ></i>
                        <span class="link-name"> Manage </span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li> <a  class="link-name" href="#">Manage</a></li>
                    <li> <a href="{{route('users.read')}}">User List</a></li>
                    <li> <a href="{{route('extras.read')}}">Extra Charge Item</a></li>
                    <li> <a href="{{route('eod.read')}}">Resort Schedule</a></li>
                    <li> <a href="{{route('reservationfeesetting')}}">Reservation Fee</a></li>
                    <li> <a href="{{route('calendar.manage')}}">Calendar Legends</a></li>
                    <li> <a href="{{route('rates.read')}}">Entrance Fee</a></li>
                    <li> <a href="{{route('maxpax.create')}}">Expected Guests</a></li>
                    <li> <a href="{{route('setDefault.view')}}">Set Default Rate</a></li>
                    <li> <a href="{{route('setDefault.viewCustom')}}">Custom Rate & Guests</a></li>
                    <li> <a href="{{route('bankcom.read')}}">Bank Commission</a></li>
                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <a href="{{route('reports.read')}}">
                        <i class='bx bxs-report' ></i>
                        <span class="link-name"> Reports </span>
                    </a>
                    <ul class="sub-menu">
                        <li> <a  class="link-name" href="#">Reports</a></li>
                    </ul>
                </div>
            </li>
            {{-- <li>
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bxs-user-account' ></i>
                        <span class="link-name"> Account </span>
                    </a>
                    <ul class="sub-menu">
                        <li> <a  class="link-name" href="#">Account</a></li>
                    </ul>
                </div>
            </li> --}}
            <li>
                <a href="{{route('admin.backup.read')}}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link-name"> Backup </span>
                </a>
                <ul class="sub-menu">
                    <li> <a  class="link-name" href="{{route('admin.backup.read')}}">Backup</a></li>
                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="styled-button">
                            <i class='bx bx-log-out'></i>
                            <span class="link-name"> Logout </span>
                        </button>
                    </form>
                </div>
            </li>
         </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu' ></i>
        </div>
        <div>
            @yield('main_content')
        </div>
    </section>

    <script>
        let arrow = document.querySelectorAll(".arrow");
        for(var i = 0; i < arrow.length; i++){
            arrow[i].addEventListener("click", (e)=>{  
                let arrrowParent = e.target.parentElement.parentElement;
                arrrowParent.classList.toggle("showMenu");

            });
        }

        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebar);

        sidebarBtn.addEventListener("click", ()=>{
            sidebar.classList.toggle("close");
        });

    </script>
    
</body>
</html>