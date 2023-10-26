@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<div class="body">
    <div class="container1">
        <h1>Bank Commission</h1>
        <div class="btnContainer">
            <a href="{{ route('bankcom.create') }}" class="btnAddNewUser" >Add New Bank</a>
        </div>
    </div>
    
    <div class="upper">
        <div class="containerUsers">
            <span class="circlebackground">{{ isset($banks) ? count($banks) : 0 }}</span> Banks
        </div>

        <div class="containerArchives">
            <a class="archive" href="{{ route('bankcom.archives')}}">Deleted</a>
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
            <th>Bank Name</th>
            <th>Commission %</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banks as $bank)
            <tr>
                <td>{{$bank->bank_name}}</td>
                <td>{{$bank->bank_commission_percentage}}</td>
                <td>
                <div class="popup">
                    <button class="css-button">...</button>
                    <div class="popup-content">
                        <form action="{{ route('bankcom.edit', ['bank' => $bank->id]) }}" method="GET">
                            <button class="popup-button">Edit</button>
                        </form>
                        <form action="{{ route('bankcom.destroy', ['bank' => $bank]) }}" method="POST">
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
@endsection
