@extends(auth()->user()->role_id === 1 ? 'adminmainframe' : 'regmainframe')

@section('main_content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/rfsetting.css') }}">

<div class="body">
<div class="upp">
  <span class="uplabel">Reservation Fee Setting</span>
</div>

<div class="container">
    <div class="labelcontainer">
        <span class="label" for="rfsetting">Reservation Fee </span>
    </div>

    <div class="maincontainer">
        <div class="form-control">
          <input class="input input-alt" type="number" placeholder="Reservation Fee ( % )" required="" type="text">
          <span class="input-border input-border-alt"></span>
        </div>
        <button class="button"><span> Submit</span></button>
    </div>
</div>
</div>


@endsection
