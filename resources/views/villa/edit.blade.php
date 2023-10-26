@extends('adminmainframe')

@section('main_content')
<h1>Update Villa Details</h1>

<div>
    <a href="{{route('villas.read')}}">Cancel edit</a>
</div>

<form method="POST" action="{{ route('villas.update', ['villa' => $villa->id]) }}" enctype="multipart/form-data">
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
        <label>Villa Name</label>
        <input type="text" name="villaname" value="{{$villa->villaname}}">
    </div>

    <div>
        <label>Pricing</label>
        <input type="text" name="pricing" value="{{$villa->pricing}}"/>
    </div>

    <div>
        <label>Capacity</label>
        <input type="text" name="capacity" value="{{$villa->capacity}}"/>
    </div>

    <div>
        <label>Description</label>
        <input type="text" name="description" value="{{$villa->description}}"/>
    </div>

    <div>
        <label>Images</label>
        <input type="file" name="images[]" multiple accept="image/*" />
    </div>

    <div>
        @if ($villa->images)
            <label>Current Images</label>
            <div>
                @php
                    $imageUrls = json_decode($villa->images, true);
                @endphp
                @foreach ($imageUrls as $imageUrl)
                    <img src="{{ $imageUrl }}" alt="Villa Image" width="100">
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <label>Status Id</label>
        <input type="text" name="status_id" value="{{$villa->status_id}}"/>
    </div>

    

    <div>
        <input type="submit" value="Save User"/>
    </div>
</form>
@endsection