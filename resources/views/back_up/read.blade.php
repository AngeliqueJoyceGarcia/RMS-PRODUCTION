@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<div>
    <h1>Backup/Upload Backup</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div>
        <form action="{{ route('admin.backup.storeBilling') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div>
                <label for="jsonFile">Select a JSON file:</label>
                <input type="file" name="jsonFile" id="jsonFile" accept=".json">
            </div>

            <div>
                <button type="submit">Save Billings JSON</button>
            </div>
        </form>
    </div>
    <div>
        <form action="{{ route('admin.backup.storeBooking') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div>
                <label for="jsonFile">Select a JSON file:</label>
                <input type="file" name="jsonFile" id="jsonFile" accept=".json">
            </div>

            <div>
                <button type="submit">Save Bookings JSON</button>
            </div>
        </form>
    </div>

    <div>
        <form action="{{ route('admin.backup.billings') }}" method="GET">
            @csrf
            @method('GET')
            <button type="submit">Download Billing Backup</button>
        </form>
    </div>
    <div>
        <form action="{{ route('admin.backup.bookings') }}" method="GET">
            @csrf
            @method('GET')
            <button type="submit">Download Booking Backup</button>
        </form>
    </div>
</div>
@endsection