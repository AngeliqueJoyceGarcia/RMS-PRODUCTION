@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/setdefault.css') }}">

<div class="body">
    <div class="upp">
        <span class="uplabel">Set Default Entrance Rate Setting</span>
    </div>

    <div class="container">
        <div class="labelcontainer">
            <span class="label" for="rfsetting">Default Rate</span>
        </div>

        <div class="maincontainer">
            <form id="setdefault" method="POST" action="{{ route('setDefault.store') }}">
                @csrf
                <div class="form-group">
                    <label for="weekday_default_rate">Set Default Rate for Weekdays:</label>
                    <select class="input" id="weekday_default_rate" name="weekday_default_rate">
                        <option value="">Select a rate</option>
                        @foreach($rates as $rate)
                            @php
                                $selected = '';
                                if ($defaultEntranceRate && ($rate->id == $defaultEntranceRate->weekday_rate_id)) {
                                    $selected = 'selected';
                                }
                            @endphp
                            <option value="{{ $rate->id }}" {{ $selected }}>
                                {{ $rate->rate_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="weekend_default_rate">Set Default Rate for Weekends:</label>
                    <select class="input" id="weekend_default_rate" name="weekend_default_rate">
                        <option value="">Select a rate</option>
                        @foreach($rates as $rate)
                            @php
                                $selected = '';
                                if ($defaultEntranceRate && ($rate->id == $defaultEntranceRate->weekend_rate_id)) {
                                    $selected = 'selected';
                                }
                            @endphp
                            <option value="{{ $rate->id }}" {{ $selected }}>
                                {{ $rate->rate_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="button" onclick="confirmSave()" ><span> Submit</span></button>
            </form>
        </div>
    </div>
</div>

<script>
    //confirmation
    var formSubmitted = false;

    function confirmSave() {
        var form = document.getElementById("setdefault");

        if (!form) {
            alert("Form not found.");
            return;
        }

        var requiredFields = form.querySelectorAll('[required]');
        var missingFields = [];

        for (var i = 0; i < requiredFields.length; i++) {
            var field = requiredFields[i];
            if (!field.value.trim()) {
                missingFields.push(field.getAttribute('name'));
            }
        }

        if (missingFields.length > 0) {
            alert("Please fill out the following required fields: " + missingFields.join(", "));
            return;
        }

        var result = window.confirm("Do you want to submit it?");

        if (result && !formSubmitted) {
            // User clicked "OK" (Yes) and the form hasn't been submitted yet.
            // Set the flag to prevent accidental double submission.
            formSubmitted = true;

            // Submit the form via AJAX or any other method.
            fetch(form.action, {
                method: form.method,
                body: new FormData(form),
            })
            .then(response => {
                if (response.ok) {
                    alert("Submission successful!");
                    // Reload the page after successful submission.
                    location.reload();
                } else {
                    alert("Form submission failed. Please try again.");
                }
            })
            .catch(error => {
                alert("An error occurred while submitting the form.");
            });

            // Add a listener for the "OK" click in the success alert to prevent further submissions.
            form.addEventListener("submit", function(event) {
                event.preventDefault();
            });
        } else {
            // User clicked "Cancel" (No), prevent the form from submitting and refresh the page.
            form.addEventListener("submit", function(event) {
                event.preventDefault();
            });
            alert("Cancelled");
        }

    }

</script>

@endsection
