@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/report.css') }}">

<div class="body">
    <div class="container">
        <div class="labelcontainer">
            <span class="label" for="rfsetting">Report </span>
        </div>

        <div class="maincontainer">
            <form action="{{ auth()->user()->role_id === 1 ? route('reports.generate') : route('reguserreports.generate') }}" method="post">
                @csrf
                @method('POST')

                <div>
                    <label for="report_type">Select Report Type:</label>
                    <select class="inputtt" name="report_type" id="report_type" onchange="toggleContent()">
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div id='daily' style="display: block;">         
                    <!-- Input field to choose a specific day for daily reports -->
                    <label for="specific_day">Specific Day (for Daily Reports):</label>
                    <input class="inputtt"type="date" name="datepicker" id="datepicker">
                </div>

                <div id='weekly' style="display: none;">         
                    <!-- Input field to choose a specific day for weekly reports -->
                    <label for="specific_day">Specific Month (for Weekly Reports):</label>
                    <select class="inputtt" name="month">
                        @for ($month = 1; $month <= 12; $month++)
                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                        @endfor
                    </select>

                </div>


                <div id='weeklyToYearly' style="display: none;">         
                    <!-- Input field to choose a specific year for reports -->
                    <label for="specific_day">Specific Year (for Weekly, Monthly & Yearly Reports):</label>
                    <select class="inputtt" name="year">
                        @for ($year = 2000; $year <= 3000; $year++)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>


                <button class="button" type="submit">Generate Report</button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleContent() {
        var reportType = document.getElementById('report_type').value;
        var dailyDiv = document.getElementById('daily');
        var weeklyDiv = document.getElementById('weekly');
        var weeklyToYearlyDiv = document.getElementById('weeklyToYearly');

        if (reportType === 'Daily') {
            dailyDiv.style.display = 'block';
            weeklyDiv.style.display = 'none';
            weeklyToYearlyDiv.style.display = 'none';
        } else if (reportType === 'Weekly') {
            dailyDiv.style.display = 'none';
            weeklyDiv.style.display = 'block';
            weeklyToYearlyDiv.style.display = 'block';
        } else {
            dailyDiv.style.display = 'none';
            weeklyDiv.style.display = 'none';
            weeklyToYearlyDiv.style.display = 'block';
        }

    }
    // Call the function initially to set the initial visibility state
    toggleContent();
</script>


@endsection
