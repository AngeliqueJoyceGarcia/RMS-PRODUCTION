<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel = "stylesheet" href="{{ asset('css/mainframe.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                <a href="{{route('reguser.index')}}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link-name"> Dashboard </span>
                </a>
                <ul class="sub-menu">
                    <li> <a  class="link-name" href="reguser.index">Dashboard</a></li>
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
                    <li> <a href="{{ route('reguserbooking.create') }}">Booking</a></li>
                    <li> <a href="{{route('reguserrefund.read')}}">Refund</a></li>
                    <li> <a href="{{route('reguser.checkin')}}">Check-In/Check-Out</a></li>
                    <li> <a href="{{route('reguser.openbook')}}">Open-book Tracker</a></li>
                    <li> <a href="{{route('reguser.resched')}}">Reshedule</a></li>
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
                   
                    <li> <a href="{{ route('reguserbooking.read') }}">Booking Details</a></li>
                    <li> <a href="{{ route('reguserbilling.read') }}">Billing</a></li>
                    <li> <a href="{{ route('regusercalendar.read') }}">Calendar</a></li>
                    <li> <a href="{{ route('reguserextras.view') }}">Extra Items</a></li>
                    <li> <a href="{{ route('reguserrates.view') }}">Entrance Fee</a></li>
                    
                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <a href="{{ route('reguserreports.read') }}">
                        <i class='bx bxs-report'></i>
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
                <div class="icon-link">
                    <form method="POST" action="{{ route('reguser.logout') }}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="styled-button">
                            <i class='bx bx-log-out'></i>
                            <span class="link-name"> Logout </span>
                        </button>
                    </form>
                    <ul class="sub-menu">
                        <li> <a  class="link-name" href="#">Logout</a></li>
                    </ul>
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