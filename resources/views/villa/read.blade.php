@extends('adminmainframe')

@section('main_content')
<h1>Villas</h1>

<div>
    @if(session()->has('success'))
        <div>
            {{session('success')}}
        </div>
    @endif       
</div>


<div>
    <ul>
        <li><a href="{{route('villas.create')}}">Add Villa here</a></li>
        <li><a href="{{route('villas.archives')}}">View Archives here</a></li>
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
            <th>Update Villa</th>
            <th>Delete Villa</th>
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
                    <a href="{{ route('villas.edit', ['villa' => $villa->id]) }}">Edit</a>
                </td>
                <td>
                    <form action="{{ route('villas.destroy', ['villa' => $villa]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
        @endforeach
    </table>
</div>
@endsection