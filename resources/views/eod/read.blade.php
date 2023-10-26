@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/daytour.css') }}">

<div class="body">
<div class="upp">
  <span class="uplabel">Resort Schedule Setting</span>
</div>

    <div class="labelcontainer">
        <span class="label" for="rfsetting">Schedule</span>
    </div>

<div class="mainframe"> 
    <form id="dayForm" action="{{ route('eod.store') }}" method="POST">
        @csrf
        @method('POST')
            <div>
                <label for="start-time">Start Time:</label>
                <input type="time" id="start-time" name="start_time" placeholder="Start Time" value="{{ old('start_time', isset($eod) ? \Carbon\Carbon::parse($eod->start_time)->format('H:i') : '') }}" required>
                
                <label for="end-time">End Time:</label>
                <input type="time" id="end-time" name="end_time" placeholder="End Time" value="{{ old('end_time', isset($eod) ? \Carbon\Carbon::parse($eod->end_time)->format('H:i') : '') }}" required>
                
                
                
          </div>       
        <button class="button3" type="submit" id="submitBtn" >Submit</button>
    </form>
</div>


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


