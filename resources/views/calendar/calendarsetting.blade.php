@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')

<link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="body">
    <div class="upp">
      <span class="uplabel">Calendar Legends Settings</span>
    </div>
    
    <div class="container">
        <div class="labelcontainer">
            <span class="label" for="rfsetting">Calendar Legend</span>
        </div>
    
        <div class="maincontainer">

            <!-- Update the form action to include the selected data -->
            <form action="{{ route('calendar.store', ['selectedData' => $selectedData]) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="dataDropdown" style="font-size: 1.2rem;">Select Data:</label>
                    <select name="selectedData" id="dataDropdown">
                        <option value="Walk-ins" {{ $selectedData === 'Walk-ins' ? 'selected' : '' }}>Total Walk-ins</option>
                        <option value="Pending Pre-book Inhouse" {{ $selectedData === 'Pending Pre-book Inhouse' ? 'selected' : '' }}>Pending Pre-book Inhouse</option>
                        <option value="Check-in Pre-book Inhouse" {{ $selectedData === 'Check-in Pre-book Inhouse' ? 'selected' : '' }}>Check-in Pre-book Inhouse</option>
                        <option value="Pending Pre-book DayTour" {{ $selectedData === 'Pending Pre-book DayTour' ? 'selected' : '' }}>Pending Pre-book DayTour</option>
                        <option value="Check-in Pre-book DayTour" {{ $selectedData === 'Check-in Pre-book DayTour' ? 'selected' : '' }}>Check-in Pre-book DayTour</option>
                        <option value="Pending Open book" {{ $selectedData === 'Pending Open book' ? 'selected' : '' }}>Pending Open book</option>
                        <option value="Check-in Open book" {{ $selectedData === 'Check-in Open book' ? 'selected' : '' }}>Check-in Open book</option>
                        <option value="Canceled Books" {{ $selectedData === 'Canceled Books' ? 'selected' : '' }}>Canceled Books</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="colorPicker" style="font-size: 1.2rem;">Select Color:</label>
                    <input type="color" name="selectedColor" id="colorPicker" value="{{ $selectedColor }}">
                </div>

                <button class="button" type="submit">Submit</button>
            </form>

            
        </div>
    </div>
    </div>

    
    <!-- JavaScript to handle the selected values -->
    <script>
        // Get references to the dropdown and color picker
        const dataDropdown = document.getElementById('dataDropdown');
        const colorPicker = document.getElementById('colorPicker');
        
        // Function to update the color picker value based on the selected data
        function updateColorPicker(selectedData) {
            @if ($walkinsLegend)
                if (selectedData === 'Walk-ins') {
                    colorPicker.value = '{{ $walkinsLegend->color }}';
                }
            @endif
            @if ($pendingPrebookInhouseLegend)
                if (selectedData === 'Pending Pre-book Inhouse') {
                    colorPicker.value = '{{ $pendingPrebookInhouseLegend->color }}';
                }
            @endif
            @if ($checkinPrebookInhouseLegend)
                if (selectedData === 'Check-in Pre-book Inhouse') {
                    colorPicker.value = '{{ $checkinPrebookInhouseLegend->color }}';
                }
            @endif
            @if ($pendingPrebookDayTourLegend)
                if (selectedData === 'Pending Pre-book DayTour') {
                    colorPicker.value = '{{ $pendingPrebookDayTourLegend->color }}';
                }
            @endif
            @if ($checkinPrebookDayTourLegend)
                if (selectedData === 'Check-in Pre-book DayTour') {
                    colorPicker.value = '{{ $checkinPrebookDayTourLegend->color }}';
                }
            @endif
            @if ($pendingOpenBookLegend)
                if (selectedData === 'Pending Open book') {
                    colorPicker.value = '{{ $pendingOpenBookLegend->color }}';
                }
            @endif
            @if ($checkinOpenBookLegend)
                if (selectedData === 'Check-in Open book') {
                    colorPicker.value = '{{ $checkinOpenBookLegend->color }}';
                }
            @endif
            @if ($canceledBookLegend)
                if (selectedData === 'Canceled Books') {
                    colorPicker.value = '{{ $canceledBookLegend->color }}';
                }
            @endif
        }
    
        // Event listener to handle changes in the selected data option
        dataDropdown.addEventListener('change', function() {
            const selectedData = dataDropdown.value;
            // Update the color picker value based on the selected data
            updateColorPicker(selectedData);
        });
    
        // Event listener to handle changes in the selected color
        colorPicker.addEventListener('input', function() {
            const selectedColor = colorPicker.value;
            // You can use the selectedColor value here as needed
            console.log('Selected Color:', selectedColor);
        });
    
        // Check if there's a success flash message and display a toast
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", '', { timeOut: 3000 });
        @endif
    
        // Initialize the color picker based on the initial selected data
        updateColorPicker(dataDropdown.value);
    </script>

@endsection