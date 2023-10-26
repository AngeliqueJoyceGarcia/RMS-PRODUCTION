@extends('adminmainframe')

@section('main_content')
<h1>Archives</h1>

<div>
    @if(session()->has('success'))
        <div>
            {{session('success')}}
        </div>
    @endif       
</div>


<div>
    <ul>
        <li><a href="{{route('villas.read')}}">Go back to Villa</a></li>
    </ul> 
</div>

<div>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Villa Name</th>
            <th>Pricing</th>
            <th>Capacity</th>
            <th>Description</th>
            <th>Images</th>
            <th>Status ID</th>
            <th>Status Name</th>
            <th>Restore Villa</th>
        </tr>

        @foreach($villas as $villa)
            <tr>
                <td>{{$villa->id}}</td>
                <td>{{$villa->villaname}}</td>
                <td>{{$villa->pricing}}</td>
                <td>{{$villa->capacity}}</td>
                <td>{{$villa->description}}</td>
                <td>
                    @if($villa->images)
                        @php $imageUrls = json_decode($villa->images, true); @endphp
                        @foreach ($imageUrls as $index => $imageUrl)
                            <img src="{{ $imageUrl }}" alt="Villa Image" width="100">
                        @endforeach
                    @endif
                </td>
                <td>{{$villa->status_id}}</td>
                <td>{{$villa->status->name}}</td>
                <td>
                    <form method="POST" action="{{ route('villas.restore', ['villa' => $villa]) }}">
                        @csrf
                        @method('POST')
                        <button type="submit">Restore</button>
                    </form>
                </td>
        @endforeach
    </table>
</div>
@endsection