@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<h1>Update Companion Arrived</h1>


<form method="POST" action="{{ route('booking.update', ['booking' => $booking->id]) }}">
    @csrf
    @method('PUT')

    <div> 
        @if($errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        @endif
    </div>


    <div>
        <label>Arrived companion</label>
        <input type="number" name="arrived_companion" value="{{ $booking->arrived_companion }}"/>
    </div>
    <br>


    <div>
        <input type="submit" value="Update Companion Arrived"/>
    </div>
</form>
@endsection