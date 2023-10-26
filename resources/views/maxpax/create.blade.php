@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/maxpax.css') }}">



<div class="body">
    <div class="upp">
        <span class="uplabel">Expected Guest Arrival Setting</span>
    </div>

    <div class="container">
        <div class="labelcontainer">
            <span class="label" for="rfsetting">Expected Guest Arrival </span>
        </div>

        <div class="maincontainer">
            <form id="max" method="POST" action="{{ route('maxpax.store') }}" onsubmit="return submitForm()">
                @csrf
                <div class="form-controls">
                <div class="form-control">
                    <label for="maximum_pax" class="input-label">Waterpark Capacity: </label>
                    <input class="input input-alt" type="number" name="maximum_pax" placeholder="Maximum Customers" required="" value="">
                </div>
                </div>
                <div class="buttons">
                    <button class="button" type="submit" onclick="confirmSave()"><span>Submit</span></button>
                </div>
            </form>


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
</div>


<script>
    //confirmation
    var formSubmitted = false;

    function confirmSave() {
        var form = document.getElementById("max");

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

        return true;
    }

    //to remain the inputted data after submission
    function submitForm() {
        // Capture form values before submission (you can use localStorage or cookies)
        localStorage.setItem('maximum_pax', document.getElementById('max').elements['maximum_pax'].value);
        return true; // Allow the form to be submitted
    }

    // Repopulate form fields on page load
    window.onload = function () {
        var storedValue = localStorage.getItem('maximum_pax');
        if (storedValue !== null) {
            document.getElementById('max').elements['maximum_pax'].value = storedValue;
        }
    };

    </script>
@endsection
