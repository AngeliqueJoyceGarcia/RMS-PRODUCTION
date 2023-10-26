@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="body">
  <div class="upper">
    <a class="upper1">
      <p class="small">DASHBOARD</p>
      <div class="go-corner">
        <div class="go-arrow">
        </div>
      </div>
    </a>
  </div>

<hr style="border-color: #8a8a8a; border-width: .5px; border-style: solid; margin-left: 30px; margin-right: 30px; margin-top: 30px;">
<div class="main">
  <div class="maincontainer">
      
      <div class="maincard">
        <div class="card-content">
          <label class="botp">Total Daily Waterpark Capacity</label>
          <h1 class="h1">{{ $maxPax->maximum_customers }}</h1>
        </div>
      </div>

      <div class="second">

        <div class="card">
          <div class="card-content">
            <label class="botp2">Pre-book Inhouse</label>
            <h1 class="h2">{{$totalCompanionPrebookInhouseToday}}</h1>
          </div>
        </div>

        
        <div class="card">
          <div class="card-content">
            <label class="botp2">Pre-book DayTour</label>
            <h1 class="h2">{{$totalCompanionPrebookDayTourToday}}</h1>
          </div>
        </div>

        <div class="card">
          <div class="card-content">
            <label class="botp2">Walk-in</label>
            <h1 class="h2">{{$totalCompanionWalkinToday}}</h1>
          </div>
        </div>


      </div>

      <div class="third">

        <div class="card1">
          <div class="card-content">
            <label class="botp2">Open Booking</label>
            <h1 class="h2">{{$totalGuestsOpenBookingsToday}}</h1>
          </div>
        </div>

        <div class="card1">
          <div class="card-content">
            <label class="botp2">Remaining Customer</label>
            <h1 class="h2">  {{ $maxPax->maximum_customers - ($totalCompanionPrebookInhouseToday + $totalCompanionPrebookDayTourToday + $totalCompanionWalkinToday) }}</h1>
          </div>
        </div>

      </div>

      
  </div>

  <div class="right">
    <div class="rightcon">
          <div class="card2">
            <div class="card-content">
              <label class="botp2">Daily Guest</label>
              <h1 class="h2">{{ $totalGuestsWalkinToday + $totalGuestsPrebookInhouseToday + $totalGuestsPrebookDayTourToday + $totalGuestsOpenBookingsToday }}</h1>
            </div>
          </div>

          <div class="card2">
            <div class="card-content">
                <label class="botp2">Weekly Guest</label>
                <h1 class="h2">{{ $totalGuestsWeekly + $totalGuestsOpenBookingsToday}}</h1>
            </div>
        </div>
        <div class="card2">
            <div class="card-content">
                <label class="botp2">Monthly Guest</label>
                <h1 class="h2">{{$totalGuestsMonthly + $totalGuestsOpenBookingsToday}}</h1>
            </div>
        </div>
        <div class="card2">
            <div class="card-content">
                <label class="botp2">Yearly Guest</label>
                <h1 class="h2">{{ $totalGuestsYearly + $totalGuestsOpenBookingsToday}}</h1>
            </div>
        </div>
        
      </div>
  </div>

</div>
    


</div>

<div class="toast" id="custom-toast">
  {{ session('toast_message') }}
</div>



@if(session('toast_message'))
  <script>
      // Show the toast when a message is available
      document.addEventListener('DOMContentLoaded', function () {
          const toast = document.getElementById('custom-toast');
          toast.innerHTML = '{{ session('toast_message') }}';
          toast.classList.add('show');
          setTimeout(() => {
              toast.classList.remove('show');
          }, 5000); // Hide after 5 seconds (adjust as needed)
      });
  </script>
@endif





@endsection