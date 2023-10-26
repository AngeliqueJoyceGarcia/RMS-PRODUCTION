@extends('adminmainframe')

@section('main_content')
<h1>Enter Villa Details</h1>

<div>
    <a href="{{route('villas.read')}}">Cancel Create</a>
</div>

<form method="POST" action="{{ route('villas.store') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')

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
        <input type="text" name="villaname" placeholder="Villa Name"/>
    </div>

    <div>
        <label>Pricing</label>
        <input type="text" name="pricing" placeholder="Pricing"/>
    </div>

    <div>
        <label>Capacity</label>
        <input type="text" name="capacity" placeholder="Capacity"/>
    </div>

    <div>
        <label>Description</label>
        <input type="text" name="description" placeholder="Description"/>
    </div>

    <div>
        <label>Images</label>
        <input type="file" name="images[]" multiple accept="image/*" placeholder="Images"/>
    </div>

    <div>
        <label>Status Id</label>
        <input type="text" name="status_id" placeholder="Status Id"/>
    </div>

    

    <div>
        <input type="submit" value="Save User"/>
    </div>
</form>
@endsection